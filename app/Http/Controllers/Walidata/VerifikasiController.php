<?php

namespace App\Http\Controllers\Walidata;

use App\Http\Controllers\Controller;
use App\Models\Data;
use App\Models\GrafikData;
use App\Models\MasterTahun;
use App\Models\Opd;
use App\Models\Verifikasi;
use App\Models\VisualIsi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $year = date('Y');
        $data = Data::where('status_id', Data::STATUS_PROSES_VERIFIKASI)->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('tahun', $year)
            ->latest()
            ->get();
        $status = 'pemeriksaan';
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();
        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'verifikasi']);
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

            if (
                $request->tahun != null
            ) {
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
                ->addColumn('updated_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('m/d/Y, h:i A');
                })
                ->addColumn('status', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->addColumn('action', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->make(true);
        }
        // dd($data);

        return view('pages.contents.walidata.verifikasi.index', compact('data', 'opd', 'tahun', 'status'));
    }

    public function berkas($id)
    {
        $data = Data::with(['opd', 'berkas', 'verifikasi' => fn($q) => $q->category('berkas')])->findOrFail($id);
        // dd($data);
        $officeLiveUrl = 'https://view.officeapps.live.com/op/view.aspx?src=https://data.madiunkab.go.id';
        $existingBerkas = $data->berkas->transform(function ($b) use ($data, $officeLiveUrl) {
            $fileExtension = pathinfo($b->name, PATHINFO_EXTENSION);
            $fileType = $fileExtension === 'csv' ? 'CSV' : 'XLSX';
            return [
                'id' => $b->id,
                'name' => $b->name,
                'path' => $b->path,
                'created_at' => $b->created_at,
                'previewUrl' => route('filepreview', ['payload' => Crypt::encryptString($b->path)]),
                'officeLive' => $officeLiveUrl . Storage::url($b->path), // tambahkan $officeLiveUrl ke dalam URL pratinjau
                'fileType' => $fileType,
            ];
        })->toArray();
        // $dd = Storage::url($existingBerkas['path']);
        // dd($existingBerkas);

        $existingBerkasIds = array_column($existingBerkas, 'id');
        // dd($existingBerkasIds);
        // Hapus verifikasi yang tidak ada di $existingBerkas
        $hapusverif = Verifikasi::where('category', 'berkas')
            ->where('data_id', $id)
            ->whereNotIn('field', $existingBerkasIds)
            ->delete();
        // dd($hapusverif);

        // menampilkan grafik
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

        $axis_x = [];
        $axis_y = [];
        $kategori = [];
        $seriesData = [];
        $seriesDataLine = [];

        foreach ($existingData as $item) {
            if ($item->header == "Tahun") {
                $kategori = VisualIsi::where(
                    'id_header',
                    $item->kategori
                )
                    ->orderBy('id_header')
                    ->pluck('isi')
                    ->unique()
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

        return view('pages.contents.walidata.verifikasi.berkas', compact('data', 'existingBerkas', 'existingData', 'axis_x', 'axis_y', 'kategori', 'seriesData', 'seriesDataLine'));
    }

    public function variabel($id)
    {
        $data = Data::with(['variabel', 'standar', 'verifikasi' => fn($q) => $q->category('variabel')])->findOrFail($id);

        // if ($data->status_id != Data::STATUS_PROSES_VERIFIKASI) {
        //     return redirect()->back()->with([
        //         Alert::error('Gagal', 'Data tidak dapat verifikasi, karena status data bukan dalam proses verifikasi')
        //     ]);
        // }

        return view('pages.contents.walidata.verifikasi.variabel', compact('data'));
    }

    public function indikator($id)
    {
        $data = Data::with(['indikator', 'standar', 'verifikasi' => fn($q) => $q->category('indikator')])->findOrFail($id);

        // if ($data->status_id != Data::STATUS_PROSES_VERIFIKASI) {
        //     return redirect()->back()->with([
        //         Alert::error('Gagal', 'Data tidak dapat verifikasi, karena status data bukan dalam proses verifikasi')
        //     ]);
        // }
        // dd($data);

        return view('pages.contents.walidata.verifikasi.indikator', compact('data'));
    }

    public function getKomentar($id, Request $request)
    {
        $request->validate([
            'field' => 'required',
            'category' => 'required|in:variabel,indikator,berkas,kegiatan'
        ]);

        $comment = Verifikasi::where('category', $request->get('category'))->where('data_id', $id)->where('field', $request->get('field'))
            ->first();

        return response()->json(['ok' => true, 'message' => '', 'comment' => $comment->comment ?? '']);
    }

    public function komentar($id, Request $request)
    {
        $request->validate([
            'field' => 'required',
            'category' => 'required|in:variabel,indikator,berkas,kegiatan'
        ]);

        Verifikasi::updateOrCreate(
            $data = [
                'category' => $request->get('category'),
                'data_id' => $id,
                'field' => $request->get('field')
            ],
            array_merge($data, [
                'comment' => $request->get('comment')
            ])
        );

        return response()->json(['ok' => true, 'message' => 'Komentar berhasil disimpan']);
    }

    public function verify($id, Request $request)
    {
        // dd($request->get('field'));
        $request->validate([
            'field' => 'required',
            'accepted' => 'required',
            'category' => 'required|in:variabel,indikator,berkas,kegiatan'
        ]);

        $data = Data::find($id);
        // dd($data);
        if (!in_array($data->status_id, [Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_REVISI])) {
            return redirect()->back()->with([
                Alert::error('Gagal', 'Data tidak dalam status proses verifikasi / revisi.')
            ]);
        }

        $accepted = $request->get('accepted');

        // dd($request);

        Verifikasi::updateOrCreate(
            $data = [
                'category' => $request->get('category'),
                'data_id' => $id,
                'field' => $request->get('field')
            ],
            array_merge($data, [
                'accepted' => $accepted
            ])
        );

        return response()->json(['ok' => true, 'message' => 'Berhasil disimpan']);
    }


    public function status($id)
    {
        $data = Data::with(['verifikasi'])->find($id);

        if (!$data) {
            return response()->json(['ok' => false, 'code' => 404, 'message' => 'Data tidak ditemukan']);
        }

        if ($data->status_id != Data::STATUS_PROSES_VERIFIKASI) {
            return response()->json(['ok' => false, 'code' => -2, 'message' => 'Status data tidak valid', 'status' => $data->status_id]);
        }

        if ($data->verifikasi->count() < 1) {
            return response()->json(['ok' => false, 'code' => -1, 'message' => 'Anda belum menyelesaikan proses verifikasi']);
        }

        if ($data->verifikasi->where('accepted', 0)->count() > 0) {
            return response()->json(['ok' => true, 'code' => 0, 'message' => 'Terdapat isian yang harus direvisi, apakah Anda yakin ingin menyelesaikan proses verifikasi?']);
        }

        return response()->json(['ok' => true, 'code' => 1, 'message' => 'Tidak ditemukan isian yang harus direvisi, apakah Anda yakin ingin menyelesaikan proses verifikasi?']);
    }

    public function status_standar($id)
    {
        $data = Data::with(['verifikasi'])->find($id);

        if (!$data) {
            return response()->json(['ok' => false, 'code' => 404, 'message' => 'Data tidak ditemukan']);
        }

        if ($data->status_id != Data::STATUS_PENGAJUAN_STANDART_DATA) {
            return response()->json(['ok' => false, 'code' => -2, 'message' => 'Status data tidak valid', 'status' => $data->status_id]);
        }

        if ($data->verifikasi->count() < 1) {
            return response()->json(['ok' => false, 'code' => -1, 'message' => 'Anda belum menyelesaikan proses verifikasi']);
        }

        if ($data->verifikasi->where('accepted', 0)->count() > 0) {
            return response()->json(['ok' => true, 'code' => 0, 'message' => 'Terdapat isian yang harus direvisi, apakah Anda yakin ingin menyelesaikan proses verifikasi?']);
        }

        return response()->json(['ok' => true, 'code' => 1, 'message' => 'Tidak ditemukan isian yang harus direvisi, apakah Anda yakin ingin menyelesaikan proses verifikasi?']);
    }

    public function complete($id)
    {
        $data = Data::with(['verifikasi'])->find($id);

        if (!$data) {
            return response()->json(['ok' => false, 'message' => 'Data tidak ditemukan']);
        }

        if ($data->status_id != Data::STATUS_PROSES_VERIFIKASI) {
            return response()->json(['ok' => false, 'message' => 'Status data tidak valid']);
        }

        if ($data->verifikasi->count() < 1) {
            return response()->json(['ok' => false, 'message' => 'Anda belum menyelesaikan proses verifikasi']);
        }

        $isRevisi = $data->verifikasi->where('accepted', 0)->count() > 0;
        // dd($isRevisi);
        $data->update([
            'status_id' => $isRevisi ? Data::STATUS_REVISI : Data::STATUS_SIAP_PUBLIKASI,
            'progress' => $isRevisi ? 50 : 100,
        ]);
        // dd($data);

        activity()->causedBy(auth()->id())->performedOn($data)->log('Data telah diverifikasi dengan hasil: ' . ($isRevisi ? 'revisi' : 'lolos & siap dipublikasi'));

        return response()->json(['ok' => true, 'message' => 'Data telah diubah menjadi ' . ($isRevisi ? 'revisi' : 'siap untuk dipublikasi')]);
    }

    public function complete_standar($id)
    {
        $data = Data::with(['verifikasi'])->find($id);

        if (!$data) {
            return response()->json(['ok' => false, 'message' => 'Data tidak ditemukan']);
        }

        if ($data->status_id != Data::STATUS_PENGAJUAN_STANDART_DATA) {
            return response()->json(['ok' => false, 'message' => 'Status data tidak valid']);
        }

        if ($data->verifikasi->count() < 1) {
            return response()->json(['ok' => false, 'message' => 'Anda belum menyelesaikan proses verifikasi']);
        }

        $isRevisi = $data->verifikasi->where('accepted', 0)->count() > 0;
        // dd($isRevisi);
        $data->update([
            'status_id' => $isRevisi ? Data::STATUS_REVISI_STANDART_DATA : Data::STATUS_SETUJU_STANDART_DATA,
            // 'progress' => $isRevisi ? 50 : 100,
        ]);
        // dd($data);

        activity()->causedBy(auth()->id())->performedOn($data)->log('Data standar data telah diverifikasi dengan hasil: ' . ($isRevisi ? 'revisi' : 'disetujui'));

        return response()->json(['ok' => true, 'message' => 'Data telah diubah menjadi ' . ($isRevisi ? 'revisi' : 'siap untuk proses pengumpulan')]);
    }

    public function revisi(Request $request)
    {
        $year = date('Y');
        $data = Data::where('status_id', Data::STATUS_REVISI)
            ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('tahun', $year)
            ->get();
        $status = 'revisi';
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();
        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_REVISI])
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

            // data
            // Tambahkan nomor urut secara manual pada setiap baris data
            $no = 1;
            foreach ($data as $row) {
                $row->no = $no++;
            }
            // dd($data);

            return DataTables::of($data)
                ->addColumn('updated_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('m/d/Y, h:i A');
                })
                ->addColumn('status', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->addColumn('action', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->make(true);
        }
        return view('pages.contents.walidata.verifikasi.index', compact('data', 'status', 'opd', 'tahun'));
    }

    public function siapPublikasi(Request $request)
    {
        $year = date('Y');
        $data = Data::whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
            ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('tahun', $year)
            ->get();
        $status = 'siap-publikasi';
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();
        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
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

            // data
            // Tambahkan nomor urut secara manual pada setiap baris data
            $no = 1;
            foreach ($data as $row) {
                $row->no = $no++;
            }
            // dd($data);

            return DataTables::of($data)
                ->addColumn('updated_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('m/d/Y, h:i A');
                })
                ->addColumn('status', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->addColumn('action', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->make(true);
        }
        return view('pages.contents.walidata.verifikasi.index', compact('data', 'status', 'opd', 'tahun'));
    }

    public function filter_verifikasi(Request $request)
    {
        $status = $request->status;
        $year = $request->tahun;
        $opd = $request->opd;
        if ($status == 'pemeriksaan') {
            $data = Data::where('status_id', Data::STATUS_PROSES_VERIFIKASI)->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
                ->latest();

            if (!empty($year) && empty($opd)) {
                $data = $data->where('tahun', $year);
            } elseif (empty($year) && !empty($opd)) {
                $data = $data->where('opd_id',  $opd);
            } elseif (!empty($year) && !empty($opd)) {
                $data = $data->where('tahun', $year)->where('opd_id',  $opd);
            }

            // dd($data->get());

            return response()->json(array(
                "success" => true,
                "data" => $data->get()
            ));
        } elseif ($status == 'revisi') {
            $data = Data::where('status_id', Data::STATUS_REVISI)
                ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])->latest();

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
        } elseif ($status == 'siap-publikasi') {
            $data = Data::whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
                ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])->latest();

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
}
