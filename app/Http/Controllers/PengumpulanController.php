<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
use App\Imports\MetadataIndikatorImport;
use App\Imports\MetadataVariabelImport;
use App\Models\Berkas;
use App\Models\Data;
use App\Models\Document;
use App\Models\GrafikData;
use App\Models\MasterTahun;
use App\Models\Opd;
use App\Models\StandarData;
use App\Models\Verifikasi;
use App\Models\VisualData;
use App\Models\VisualHeader;
use App\Models\VisualIsi;
use App\Models\VisualTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\Province;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class PengumpulanController extends Controller
{
    public function storeDataByFilter(Request $request)
    {
        // dd($request);
        // dd($request->all());
        $id_data = $request->input('id_data');
        $id_table = $request->input('id_table');
        $selectedAxisX = $request->input('axis_x');
        $selectedAxisY = $request->input('axis_y');
        $selectedCategory = $request->input('kategori');

        // foreach ($id_table as $k => $v_table) {
        $grafik = GrafikData::where('id_table', $id_table)->first();

        if ($grafik) {
            // dd('atas');
            $grafik->update([
                'id_data' => $id_data,
                // 'id_table' => $v_table,
                'axis_x' => $selectedAxisX,
                'axis_y' => $selectedAxisY,
                'kategori' => $selectedCategory,
            ]);
        } else {
            // dd('bawah');
            $grafik = GrafikData::create([
                'id_data' => $id_data,
                'id_table' => $id_table,
                'axis_x' => $selectedAxisX,
                'axis_y' => $selectedAxisY,
                'kategori' => $selectedCategory,
            ]);
        }
        // }
        // return redirect()->route('pengumpulan.visual.grafik', ['id' => $id_data])->with('success', 'Form submitted successfully!');
        return redirect()->back()->with('active_tab', 'grafik');
    }

    public function pengumpulan(Request $request)
    {
        $year = date('Y');
        // $year = '2022';
        $data = Data::whereIn('status_id', [Data::STATUS_SETUJU_STANDART_DATA])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('tahun', $year)
            ->latest()
            ->get();
        $status = 'pengumpulan';
        // dd($data);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();

        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_SETUJU_STANDART_DATA])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);
            if (Auth::user()->role_id == 4) {
                $query->where(function ($q) {
                    $q->where('data.opd_id', auth()->user()->opd_id)
                        ->orWhere('data_prioritas', 1);
                });
            }
            // ->latest();
            // dd($query->get());

            // if (
            //     $request->opd != null && $request->tahun != null
            // ) {
            //     $query->where('opd_id', $request->opd)->where('tahun', $request->tahun);
            // } elseif ($request->opd == null && $request->tahun != null) {
            //     $query->where('tahun', $request->tahun);
            // }
            if ($request->tahun != null) {
                $query->where('tahun', $request->tahun);
            }

            if ($request->opd) {
                $query->where('opd_id', $request->opd);
            }


            $data = $query->orderBy(
                'tahun',
                'DESC'
            )->get()->map(function ($item) {
                $item->progress = $item->calculateProgress();

                // Menambahkan nilai progress ke setiap item
                return $item;
            });

            // data
            // Tambahkan nomor urut secara manual pada setiap baris data
            $no = 1;
            foreach ($data as $row) {
                $row->no = $no++;
            }
            // dd($data);

            return DataTables::of($data)
                ->addColumn('status', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->addColumn('action', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->make(true);
        }

        return view('pages.contents.produsen.pengumpulan.index', compact('data', 'opd', 'tahun', 'status'));
    }

    public function pengumpulan_lengkap(Request $request)
    {
        $year = date('Y');
        // $year = '2022';
        $data = Cache::remember('data:pengumpulan:lengkap:' . auth()->user()->opd_id, 30, fn() => Data::whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_REVISI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('tahun', $year)
            ->latest()
            ->get());
        // dd($data);
        $status = 'lengkap';
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();
        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_REVISI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);
            if (Auth::user()->role_id == 4) {
                $query->where(function ($q) {
                    $q->where('data.opd_id', auth()->user()->opd_id)
                        ->orWhere('data_prioritas', 1);
                });
            }
            // ->latest();
            // dd($query->get());
            if ($request->tahun != null) {
                $query->where('tahun', $request->tahun);
            }

            if ($request->opd) {
                $query->where('opd_id', $request->opd);
            }
            // if (
            //     $request->opd != null && $request->tahun != null
            // ) {
            //     $query->where('opd_id', $request->opd)->where('tahun', $request->tahun);
            // } elseif ($request->opd == null && $request->tahun != null) {
            //     $query->where('tahun', $request->tahun);
            // }


            $data = $query->orderBy(
                'tahun',
                'DESC'
            )->get()->map(function ($item) {
                $item->progress = $item->calculateProgress();

                // Menambahkan nilai progress ke setiap item
                return $item;
            });

            // Tambahkan nomor urut secara manual pada setiap baris data
            $no = 1;
            foreach ($data as $row) {
                $row->no = $no++;
            }
            // dd($data);

            return DataTables::of($data)
                ->addColumn('status', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->addColumn('action', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->make(true);
        }
        return view('pages.contents.produsen.pengumpulan.index', compact('data', 'status', 'opd', 'tahun'));
    }

    public function filter_pengumpulan(Request $request)
    {
        // dd($request->all());
        $status = $request->status;
        if ($status == 'pengumpulan') {
            $year = $request->tahun;
            $opd = $request->opd;
            $data = Data::whereIn('status_id', [Data::STATUS_SETUJU])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
                ->latest();

            if (!empty($year) && empty($opd)) {
                $data = $data->where('tahun', $year);
            } elseif (empty($year) && !empty($opd)) {
                $data = $data->where('opd_id',  $opd);
            } elseif (!empty($year) && !empty($opd)) {
                $data = $data->where('tahun', $year)->where('opd_id',  $opd);
            }

            return response()->json(array(
                "success" => true,
                "data" => $data->get()
            ));
        } elseif ($status == 'lengkap') {
            $year = $request->tahun;
            $opd = $request->opd;
            $data = Data::whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_REVISI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
                ->latest();

            if (!empty($year) && empty($opd)) {
                $data = $data->where('tahun', $year);
            } elseif (empty($year) && !empty($opd)) {
                $data = $data->where('opd_id',  $opd);
            } elseif (!empty($year) && !empty($opd)) {
                $data = $data->where('tahun', $year)->where('opd_id',  $opd);
            }

            return response()->json(array(
                "success" => true,
                "data" => $data->get()
            ));
        }
    }

    public function detailData($id)
    {
        // dd('sadas');

        // $get_visual_data = VisualData::all();
        $get_visual_data = VisualTable::where('id_data', $id)->get();
        $get_berkas = Berkas::where('data_id', $id)->get();
        // dd($get_visual_data);

        // Mengambil data tabel berdasarkan nama
        $namaTabels = VisualTable::where('id_data', $id)->get();
        // dd($namaTabels);
        $tables = []; // Initialize an empty array
        $headers = []; // Initialize an empty array
        $rows = []; // Initialize an empty array
        $data = Data::with([
            'opd',
            'berkas'
        ])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);

        // dd($data->berkas);
        if ($data->berkas != null) {
            if ($namaTabels->isNotEmpty()) {
                foreach ($namaTabels as $namaTabel) {
                    // dd($namaTabel);
                    $namaTabel = VisualTable::where('id', $namaTabel->id)->where('id_data', $id)->first();

                    if ($namaTabel) {
                        // Mengambil data header terurut berdasarkan urutan menyamping
                        $headers = VisualHeader::where('id_namatabel', $namaTabel->id)
                            ->orderBy('urutan_menyamping')
                            ->get();

                        // Mengambil data isi terurut berdasarkan urutan kebawah
                        $rows = VisualIsi::where('id_namatabel', $namaTabel->id)
                            ->orderBy('urutan_kebawah')
                            ->get()
                            ->groupBy('urutan_kebawah');


                        // Mengambil data isi terurut berdasarkan urutan kebawah
                        $rows_grafik = VisualIsi::where('id_namatabel', $namaTabel->id)
                            ->orderBy('id_header')
                            ->get()
                            ->groupBy('id_header');

                        $tables[] = [
                            'table' => $namaTabel,
                            'headers' => $headers,
                            'rows_grafik' => $rows_grafik,
                            'rows' => $rows
                        ];
                        // break;
                    }
                }
            }
        }
        // dd($data->berkas, $tables);

        // dd($tables);

        // ->when(auth()->user()->hasAnyRole('produsen'), fn ($q) => $q->where('opd_id', auth()->user()->opd_id))

        // $berkasData = $get_visual_data->berkas->transform(function ($b) use ($get_visual_data) {

        //     return [
        //         'id' => $b->id,
        //         'name' => $b->name,
        //         'created_at' => $b->created_at,
        //         // 'previewUrl' => Storage::url($b->path),
        //         'previewUrl' => route('filepreview', ['payload' => Crypt::encryptString($b->path)]),
        //     ];
        // })->toArray();



        if (in_array($data->status_id, [Data::STATUS_REVISI, Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])) {
            $data->load('verifikasi');
        }

        $existingBerkas = $data->berkas->map(function ($b) use ($data) {

            // dd($b->path, url($b->path));
            return [
                'name' => $b->name,
                'size' => $b->size,
                'previewUrl' => route('filepreview', ['payload' => Crypt::encryptString($b->path)]),
                'deleteUrl' => route('delete-berkas', [$data->id, $b->id]),
            ];
        })->toArray();
        // dd($existingBerkas[0]['previewUrl']);

        // if (!in_array($data->status_id, [Data::STATUS_SETUJU, Data::STATUS_PROSES_PENGUMPULAN, Data::STATUS_REVISI, Data::STATUS_SIAP_PUBLIKASI])) {
        //     return redirect()->back()->with('errors', 'Status Data belum selesai');
        // }
        // dd($existingBerkas);
        $tahun = MasterTahun::where('is_active', 1)->get();
        $cek_axis_x = GrafikData::where('id_data', $data->id)->first();

        // dd('bawah');
        if ($cek_axis_x == null) {
            $existingData = GrafikData::where('id_data', $data->id)
                ->join('visual_headers', 'grafik_data.kategori', '=', 'visual_headers.id')
                ->join('visual_headers as vh_axis_x', 'grafik_data.axis_x', '=', 'vh_axis_x.id')
                ->join('visual_headers as vh_axis_y', 'grafik_data.axis_y', '=', 'vh_axis_y.id')
                ->select('grafik_data.*', 'visual_headers.header', 'vh_axis_x.header as axis_x_header', 'vh_axis_y.header as axis_y_header')
                ->get();
        } else {
            if ($cek_axis_x->axis_x == 0) {
                // dd('atas');
                $existingData = GrafikData::where('id_data', $data->id)
                    // ->join('visual_headers', 'grafik_data.kategori', '=', 'visual_headers.id')
                    // ->join('visual_headers as vh_axis_x', 'grafik_data.axis_x', '=', 'vh_axis_x.id')
                    // ->join('visual_headers as vh_axis_y', 'grafik_data.axis_y', '=', 'vh_axis_y.id')
                    // ->select('grafik_data.*', 'kategoriHeader.header', 'axisXHeader.header as axis_x_header', 'axisYHeader.header as axis_y_header')
                    ->get();
            } else {
                // dd('bawah');
                $existingData = GrafikData::where('id_data', $data->id)
                    ->join('visual_headers', 'grafik_data.kategori', '=', 'visual_headers.id')
                    ->join('visual_headers as vh_axis_x', 'grafik_data.axis_x', '=', 'vh_axis_x.id')
                    ->join('visual_headers as vh_axis_y', 'grafik_data.axis_y', '=', 'vh_axis_y.id')
                    ->select('grafik_data.*', 'visual_headers.header', 'vh_axis_x.header as axis_x_header', 'vh_axis_y.header as axis_y_header')
                    ->get();
            }
        }

        // dd($existingData);
        // dd(sizeOf($existingData));

        $axis_x = [];
        $axis_y = [];
        $kategori = [];
        $seriesData = [];
        $seriesDataLine = [];

        // dd($existingData);

        foreach ($existingData as $item) {
            if ($item->header == "Tahun") {
                $kategori = VisualIsi::where(
                    'id_header',
                    $item->kategori
                )
                    ->orderBy('id_header')
                    ->pluck('isi')
                    ->unique()
                    ->values()
                    ->toArray();
            } else {
                $kategori = VisualIsi::where('id_header', $item->kategori)
                    ->orderBy('id_header')
                    ->pluck('isi')
                    ->toArray();
            }

            if ($item->axis_x_header == "Tahun") {
                $axis_x = VisualIsi::where('id_header', $item->axis_x)
                    ->orderBy('id_header')
                    ->pluck('isi')
                    ->unique()
                    ->toArray();
            } else {
                $axis_x = VisualIsi::where('id_header', $item->axis_x)
                    ->orderBy('id_header')
                    ->pluck('isi')
                    ->unique()
                    ->toArray();
            }

            if ($item->axis_y_header == "Tahun") {
                $axis_y = VisualIsi::where('id_header', $item->axis_y)
                    ->orderBy('id_header')
                    ->pluck('isi')
                    ->unique()
                    ->toArray();
            } else {
                $axis_y = VisualIsi::where('id_header', $item->axis_y)
                    ->orderBy('id_header')
                    ->pluck('isi')
                    ->toArray();
            }

            foreach ($kategori as $index => $name) {
                if ($item->header == "Tahun") {
                    $noUrut = VisualIsi::where('id_namatabel', $item->id_table)->pluck('urutan_kebawah')->unique()->toArray();
                    $noUrutTahun = VisualIsi::where('id_namatabel', $item->id_table)->whereIn('urutan_kebawah', $noUrut)->where('isi', $name)->pluck('urutan_kebawah');
                    $data1 = VisualIsi::where('id_namatabel', $item->id_table)->where('id_header', $item->axis_y)->whereIn('urutan_kebawah', $noUrutTahun)->pluck('isi')->toArray();

                    $seriesData[$item->id][] = [
                        'name' => $name,
                        'data' => $data1
                    ];
                } else {
                    $seriesData[$item->id][] = [
                        'name' => $name,
                        'data' => array_map('intval', explode(',', $axis_y[$index]))
                    ];
                }
            }

            foreach ($axis_x as $index => $name) {
                if ($item->header == "Tahun") {
                    $noUrut = VisualIsi::where('id_namatabel', $item->id_table)->pluck('urutan_kebawah')->unique()->toArray();
                    $noUrutTahun = VisualIsi::where('id_namatabel', $item->id_table)->whereIn('urutan_kebawah', $noUrut)->where('isi', $name)->pluck('urutan_kebawah');
                    $data1 = VisualIsi::where('id_namatabel', $item->id_table)->where('id_header', $item->axis_y)->whereIn('urutan_kebawah', $noUrutTahun)->pluck('isi')->toArray();

                    $seriesDataLine[$item->id][] = [
                        'name' => $name,
                        'data' => $data1
                    ];
                } else {
                    $seriesDataLine[$item->id][] = [
                        'name' => $name,
                        'data' => array_map('intval', explode(',', $axis_y[$index]))
                    ];
                }
            }
        }

        $kategori = json_encode($kategori);
        $axis_y = json_encode($axis_y);
        $axis_x = json_encode($axis_x);
        $document = Document::where('type', 'TEMPLATE BERKAS DATA')->get();
        if ($data->status_id == 7) {
            Alert::info('Perhatian!', 'Jika ingin revisi berkas, harap hapus terlebih dahulu berkas yang akan di revisi!');
        }
        // dd($seriesDataLine);
        return view('pages.contents.produsen.pengumpulan.edit-data', compact(
            'document',
            'data',
            'existingBerkas',
            'get_visual_data',
            'get_berkas',
            'namaTabels',
            'tahun',
            'tables',
            'headers',
            'rows',
            'existingData',
            'axis_x',
            'axis_y',
            'kategori',
            'seriesData',
            'seriesDataLine'
        ));
    }

    public function standarData($id, Request $request)
    {

        $data = Data::with(['opd', 'standar'])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->find($id);

        $year = date('Y') - 1;
        // $existingData = Data::where('nama_data', trim($data->nama_data))->where('tahun', trim($year))->count();
        $existingData = Data::where('nama_data', 'like', '%' . $data->nama_data . '%')
            // ->where('tahun', $year)
            ->count();
        // dd($existingData);

        // dd($existingData);
        $getdata = [];
        if ($existingData > 0) {
            // $year = date('Y');
            $getdata = Data::with(['opd', 'standar'])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->find($id);
            // dd($getdata->standar());

            if ($request->filled('definisi')) {
                $validated = $request->validate([
                    'kode' => 'required|string',
                    'konsep' => 'required|string',
                    'definisi' => 'required|string',
                    'klasifikasi' => 'required|string',
                    'ukuran' => 'required|string',
                    'satuan' => 'required|string'
                ]);

                $getdata->standar()->updateOrCreate(
                    ['data_id' => $getdata->id],
                    array_merge(['data_id' => $getdata->id], $validated)
                );
                $getdata->update(['status_id' => Data::STATUS_PENGAJUAN_STANDART_DATA]);
                activity()->causedBy(auth()->id())->performedOn($data)->log('Data standar data di perbarui');

                $getdata->refresh();

                Alert::success('Berhasil', 'Standar data berhasil disimpan');
                // return redirect('/data_produsen/pengumpulan');
            }
        }
        // dd($getdata);

        if ($request->filled('definisi')) {
            $validated = $request->validate([
                'kode' => 'required|string',
                'konsep' => 'required|string',
                'definisi' => 'required|string',
                'klasifikasi' => 'required|string',
                'ukuran' => 'required|string',
                'satuan' => 'required|string'
            ]);

            $data->standar()->updateOrCreate(
                ['data_id' => $data->id],
                array_merge(['data_id' => $data->id], $validated)
            );
            $data->update(['status_id' => Data::STATUS_PENGAJUAN_STANDART_DATA]);
            activity()->causedBy(auth()->id())->performedOn($data)->log('Data standar data di perbarui');

            $data->refresh();
            // dd($data);

            Alert::success('Berhasil', 'Standar data berhasil disimpan');
            // return redirect('/data_produsen/pengumpulan');
        }

        return view('pages.contents.produsen.pengumpulan.standar', compact('data', 'getdata', 'existingData'));
    }

    public function uploadBerkas($id, Request $request)
    {
        $request->validate([
            'berkas' => 'required|file'
        ]);

        $data = Data::when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))->findOrFail($id);

        if ($data->status_id != Data::STATUS_SETUJU && $data->status_id != Data::STATUS_REVISI) {
            return response()->json(['message' => 'invalid'], 403);
        }

        $fileName = $request->file('berkas')->getClientOriginalName();
        $storedPath = $request->file('berkas')->storeAs('public/exports/' . Str::slug($data->nama_data), $fileName);

        if (!$storedPath) {
            return response([], 500);
        }

        $berkas = $data->berkas()->create([
            'name' => $fileName,
            'size' => Storage::size($storedPath),
            'path' => $storedPath
        ]);

        return response()->json([
            'name' => $berkas->name,
            'size' => $berkas->size
        ]);
    }

    public function deleteBerkas($dataId, $berkasId)
    {
        $berkas = Berkas::findOrFail($berkasId);

        if (Storage::exists($berkas->path)) {
            Storage::delete($berkas->path);
        }

        Verifikasi::where('data_id', $dataId)->where('category', 'berkas')->where('field', $berkasId)->delete();
        $berkas->delete();

        return response()->noContent();
    }

    public function metadata($id)
    {
        $data = Data::when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))->findOrFail($id);

        if (in_array(strtolower($data->jenis_data), ['variabel', 'indikator'])) {
            $data->with(strtolower($data->jenis_data));
        }

        return view('pages.contents.produsen.pengumpulan.' . strtolower($data->jenis_data), compact('data'));
    }

    public function indikator($id)
    {

        $data = Data::with(['indikator', 'standar'])->findOrFail($id);
        // dd($data);


        $year = date('Y') - 1;
        $now = date('Y');
        // $existingData = Data::where('nama_data', trim($data->nama_data))->where('tahun', trim($year))->count();
        $existingData = Data::where('opd_id', auth()->user()->opd_id)->where('nama_data', 'like', '%' . $data->nama_data . '%')->where('tahun', $year)->count();
        $cekexistingData = Data::where('opd_id', auth()->user()->opd_id)->where('nama_data', 'like', '%' . $data->nama_data . '%')->where('tahun', $now)->with('indikator')->first();
        // dd($cekexistingData);
        // dd($existingData2->indikator);
        $getdata = [];
        if ($existingData > 0) {
            // dd('a');
            // $year = date('Y');
            $getdata = Data::with(['opd', 'standar', 'indikator'])
                // ->where('id', $id)
                ->where('opd_id', auth()->user()->opd_id)
                ->where('tahun', $year)->where('nama_data', $data->nama_data)
                ->first();
            if (isset($cekexistingData->indikator)) {
                // dd('b');
                $getdata = Data::with(['opd', 'standar', 'indikator'])
                    ->where('id', $id)
                    ->where('opd_id', auth()->user()->opd_id)
                    ->where('tahun', $now)->where('nama_data', $data->nama_data)
                    ->first();
            }
        }
        // dd($getdata);

        if ($data->status_id == Data::STATUS_REVISI) {
            $data->load('verifikasi');
        }
        $document = Document::where('type', 'INDIKATOR')->get();

        return view('pages.contents.produsen.pengumpulan.form-indikator', compact('data', 'existingData', 'getdata', 'document'));
    }

    public function importIndikator($id, Request $request)
    {
        $data = Data::findOrFail($id);

        $indikatorData = Excel::toCollection(new MetadataIndikatorImport($data->id, $data->nama_data), $request->file('metadata'));

        $meta = data_get($indikatorData, '0.0', []);
        if ($meta->count() < 12) {
            return redirect()->back()->with([
                Alert::error('Gagal', 'Berkas excel tidak valid. Data kosong! Pastikan Anda menggunakan template yang sudah disediakan')
            ]);
        }

        $import = new MetadataIndikatorImport($data->id, $data->nama_data);
        $import->model($meta->all());

        return redirect()->back()->with([
            Alert::success('Berhasil', 'Import metadata berhasil. Silahkan periksa kembali hasil import metadata')
        ]);
    }

    public function simpanIndikator($id, Request $request)
    {
        // dd($request->all());

        $data = Data::with(['indikator'])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);
        // dd($data->id);

        $request->validate([
            'metode_image' => 'nullable|image'
        ]);


        $formData = $request->all();

        if ($request->hasFile('metode_image')) {
            $formData['metode'] = $request->file('metode_image')->storeAs('public/exports/' . Str::slug($data->nama_data), $request->file('metode_image')->getClientOriginalName());
        }
        // dd($formData);
        $aa = $data->indikator()->updateOrCreate(
            ['data_id' => $data->id],
            array_merge($formData, ['data_id' => $data->id])
        );
        // dd($aa);


        $updateStandarData = [];
        $standarDataFields = ['konsep', 'definisi', 'klasifikasi_penyajian', 'satuan', 'ukuran'];
        foreach ($standarDataFields as $field) {
            if ($request->filled($field)) {
                $updateStandarData[$field] = $request->get($field);
            }
        }
        if (isset($updateStandarData['klasifikasi_penyajian'])) {
            $updateStandarData['klasifikasi'] = $updateStandarData['klasifikasi_penyajian'];
        }

        // dd($updateStandarData);
        $data->standar()->updateOrCreate(
            ['data_id' => $data->id],
            $updateStandarData
        );

        Alert::success('Berhasil', 'Metadata berhasil disimpan');

        return redirect()->to(url()->previous(back()));
    }

    public function variabel($id)
    {
        $data = Data::with(['variabel', 'standar'])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);

        $year = date('Y') - 1;
        $now = date('Y');
        $existingData = Data::where('opd_id', auth()->user()->opd_id)->where('nama_data', 'like', '%' . $data->nama_data . '%')->where('tahun', $year)->count();
        $cekexistingData = Data::where('opd_id', auth()->user()->opd_id)->where('nama_data', 'like', '%' . $data->nama_data . '%')->where('tahun', $now)->with('variabel')->first();

        $getdata = [];
        if ($existingData > 0) {
            // $year = date('Y');
            $getdata = Data::with(['opd', 'standar', 'variabel'])
                ->where('opd_id', auth()->user()->opd_id)
                ->where('tahun', $year)->where('nama_data', $data->nama_data)
                ->first();
            if (isset($cekexistingData->variabel)) {
                $getdata = Data::with(['opd', 'standar', 'variabel'])
                    ->where('opd_id', auth()->user()->opd_id)
                    ->where('tahun', $now)->where('nama_data', $data->nama_data)
                    ->first();
            }
        }
        // dd($getdata);


        if ($data->status_id == Data::STATUS_REVISI) {
            $data->load('verifikasi');
        }
        $document = Document::where('type', 'VARIABEL')->get();

        return view('pages.contents.produsen.pengumpulan.form-variabel', compact('document', 'data', 'existingData', 'getdata'));
    }

    public function importVariabel($id, Request $request)
    {
        if (!$request->hasFile('metadata')) {
            return redirect()->back()->with([
                Alert::error('Gagal', 'Berkas excel tidak valid. Data kosong! Pastikan Anda menggunakan template yang sudah disediakan')
            ]);
        }

        $data = Data::findOrFail($id);

        $indikatorData = Excel::toCollection(new MetadataVariabelImport($data->id, $data->nama_data), $request->file('metadata'));

        $meta = data_get($indikatorData, '0.0', []);
        if ($meta->count() < 14) {
            return redirect()->back()->with([
                Alert::error('Gagal', 'Berkas excel tidak valid. Data kosong! Pastikan Anda menggunakan template yang sudah disediakan')
            ]);
        }

        $import = new MetadataVariabelImport($data->id, $data->nama_data);
        $import->model($meta->all());

        return redirect()->back()->with([
            Alert::success('Berhasil', 'Import metadata berhasil. Silahkan periksa kembali hasil import metadata')
        ]);
    }

    public function simpanVariabel($id, Request $request)
    {
        $data = Data::when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);

        $data->variabel()->updateOrCreate(
            ['data_id' => $data->id],
            array_merge($request->all(), ['data_id' => $data->id])
        );

        $updateStandarData = [];
        $standarDataFields = ['konsep', 'definisi', 'klasifikasi_isian', 'satuan', 'ukuran'];
        foreach ($standarDataFields as $field) {
            if ($request->filled($field)) {
                $updateStandarData[$field] = $request->get($field);
            }
        }
        if (isset($updateStandarData['klasifikasi_isian'])) {
            $updateStandarData['klasifikasi'] = $updateStandarData['klasifikasi_isian'];
        }

        $data->standar()->updateOrCreate(
            ['data_id' => $data->id],
            $updateStandarData
        );
        Alert::success('Berhasil', 'Metadata berhasil disimpan');
        return redirect()->back();
    }

    public function kegiatan($id)
    {
        $data = Data::with(['kegiatan'])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);
        $provinces = Province::pluck('name', 'code');
        return view('pages.contents.produsen.pengumpulan.kegiatan', compact('data', 'provinces'));
    }

    public function simpanKegiatan($id, Request $request)
    {
        $data = Data::with(['kegiatan'])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);
        $data->kegiatan()->updateOrCreate(
            ['data_id' => $data->id],
            array_merge($request->all(), ['data_id' => $data->id]),
        );

        Alert::success('Berhasil', 'Metadata berhasil disimpan');

        return redirect()->back()->withInput();
    }

    public function simpanVariabelDikumpulkan($id, Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'definisi' => 'required|string',
            'konsep' => 'required|string',
            'referensi_waktu' => 'required|date',
        ]);
        $data = Data::with(['kegiatan'])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);

        if (empty($data->kegiatan)) {
            $data->kegiatan()->create([
                'variabel_dikumpulkan' => [$validated]
            ]);
        } else {
            $variable = $data->kegiatan->variabel_dikumpulkan;
            $variable[] = $validated;
            $data->kegiatan->update(['variabel_dikumpulkan' => $variable]);
        }

        return response()->noContent();
    }

    public function simpanPublikasi($id, Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required',
            'rencana_rilis' => 'required|date',
        ]);
        $data = Data::with(['kegiatan'])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);

        if (empty($data->kegiatan)) {
            $data->kegiatan()->create([
                'rencana_publikasi' => [$validated]
            ]);
        } else {
            $daftarPublikasi = $data->kegiatan->rencana_rilis;
            $daftarPublikasi[] = $validated;
            $data->kegiatan->update(['rencana_rilis' => $daftarPublikasi]);
        }

        return response()->noContent();
    }

    public function siapVerifikasi($id)
    {
        $data = Data::with(['kegiatan', 'standar'])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);

        if (in_array(strtolower($data->jenis_data), ['variabel', 'indikator'])) {
            $data->with(strtolower($data->jenis_data));
        }

        if ($data->calculateProgress() < 60) {
            return response()->json(['ok' => false, 'message' => 'Mohon lengkapi isian metadata terlebih dahulu sebelum lanjut ke proses verifikasi']);
        }

        if ($data->status_id == Data::STATUS_PROSES_VERIFIKASI) {
            return response()->json(['ok' => false, 'message' => 'Data ini sedang dalam proses verifikasi']);
        }

        $data->update(['progress' => 100, 'status_id' => Data::STATUS_PROSES_VERIFIKASI]);

        activity()->causedBy(auth()->id())->performedOn($data)->log('Data diajukan ke tahap verifikasi');

        return response()->json(['ok' => true, 'message' => 'Sukses! Data dalam tahap verifikasi']);
    }

    public function exportData($id)
    {
        $data = Data::when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))->findOrFail($id);

        return Excel::download(new DataExport($data), 'testexcel-' . Str::random(10) . '.xlsx');
    }
}
