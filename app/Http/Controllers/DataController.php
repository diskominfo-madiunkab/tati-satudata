<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Opd;
use App\Models\Data;
use App\Models\Berkas;
use App\Models\Document;
use App\Models\VisualIsi;
use App\Models\GrafikData;
use App\Models\SumberData;
use App\Imports\DataImport;
use App\Models\MasterTahun;
use App\Models\VisualTable;
use Illuminate\Support\Str;
use App\Models\VisualHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (Auth::user()->role_id == '1') {
            $data = Data::data_nonprodusen();
            // $opd = Opd::all();
            $opdsQuery = Opd::select('id', 'nama_opd')
                ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
            $opd = $opdsQuery->get();
            $status = '3';
            $tahun = MasterTahun::where('is_active', 1)->get();

            return view('pages.contents.administrator.indexdata', compact('data', 'opd', 'tahun', 'status'));
        } elseif (Auth::user()->role_id == '2' || Auth::user()->role_id == 4 || Auth::user()->role_id == 5) {
            // dd(Auth::user()->role_id);
            $year = date('Y');
            $file = Document::all();
            $tahun = MasterTahun::where('is_active', 1)->get();
            // $opd = Opd::all();
            $opdsQuery = Opd::select('id', 'nama_opd')
                ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
            $opd = $opdsQuery->get();
            $status = '3';
            // $data2 = Data::data_draft_walidata();
            $data = Cache::remember('data:pengumpulan:proses:' . auth()->user()->opd_id, 30, fn() => Data::whereIn('status_id', [Data::STATUS_DRAFT])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
                ->where('tahun', $year)
                ->latest()
                ->get());
            $data = Cache::remember('data:pengumpulan:proses:' . auth()->user()->opd_id, 30, function () {
                return Data::whereIn('status_id', [Data::STATUS_DRAFT])
                    ->when(auth()->user()->hasAnyRole('produsen'), function ($query) {
                        $query->where(function ($query) {
                            $query->where('opd_id', auth()->user()->opd_id)
                                ->orWhere('data_prioritas', true);
                        });
                    })
                    ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])

                    ->get();
            });
            // dd($data);

            if ($request->ajax()) {
                $query =
                    DB::table('data')
                    ->join('opds', function ($join) {
                        $join->on('data.opd_id', '=', 'opds.id');
                    })
                    ->join('status', function ($join) {
                        $join->on('data.status_id', '=', 'status.id');
                    })
                    ->join('users', function ($join) {
                        $join->on('data.user_id', '=', 'users.id');
                    })
                    ->select('data_prioritas', 'tahun', 'nama_opd', 'nama_data', 'jenis_data', 'sumber_data', 'status_id', 'status', 'name', 'user_id', 'data.opd_id', 'data.id', 'data_prioritas', 'data.kodeindikator')
                    ->where('status_id', '=', Data::STATUS_DRAFT);
                if (Auth::user()->role_id == 4) {
                    $query->where(function ($q) {
                        $q->where('data.opd_id', auth()->user()->opd_id)
                            ->orWhere('data_prioritas', 1);
                    });
                }

                if ($request->tahun != null) {
                    $query->where('tahun', $request->tahun);
                }

                if ($request->opd) {
                    $query->where('opds.id', $request->opd);
                }
                // if ($request->opd != null && $request->tahun != null) {
                //     $query->where('opds.id', $request->opd)->where('tahun', $request->tahun);
                // } elseif ($request->opd == null && $request->tahun != null) {
                //     $query->where('tahun', $request->tahun);
                // }

                $data = $query->orderBy('tahun', 'DESC')->get();

                // Tambahkan nomor urut secara manual pada setiap baris data
                $no = 1;
                foreach ($data as $row) {
                    $row->no = $no++;
                }
                // dd($data->count());

                return DataTables::of($data)
                    ->addColumn('status', function ($row) {
                        // Tambahkan aksi di sini jika diperlukan
                    })
                    ->addColumn('action', function ($row) {
                        // Tambahkan aksi di sini jika diperlukan
                    })
                    ->make(true);
            }
            $document = Document::where('type', 'DATA')->get();

            return view('pages.contents.walidata.indexdata', compact('data', 'file', 'tahun', 'opd', 'status', 'document'));
        } elseif (Auth::user()->role_id == '3') {
            $data = Data::data_produsen();
            $draft = Data::get_draft()->count();
            $tahun = MasterTahun::where('is_active', 1)->get();
            // $opd = Opd::all();
            $opdsQuery = Opd::select('id', 'nama_opd')
                ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
            $opd = $opdsQuery->get();
            $status = '3';
            if ($request->ajax()) {
                $query =
                    DB::table('data')
                    ->join('opds', function ($join) {
                        $join->on('data.opd_id', '=', 'opds.id');
                    })
                    ->join('status', function ($join) {
                        $join->on('data.status_id', '=', 'status.id');
                    })
                    ->join('users', function ($join) {
                        $join->on('data.user_id', '=', 'users.id');
                    })
                    ->select('nama_opd', 'tahun', 'nama_data', 'jenis_data', 'sumber_data', 'status_id', 'status', 'name', 'user_id', 'opds.id', 'data.id', 'data_prioritas')
                    ->where('status_id', Data::STATUS_DRAFT)
                    ->where('opds.id', '=', Auth::user()->opd_id);

                if ($request->tahun != null) {
                    $query->where('tahun', $request->tahun);
                }

                $data = $query->orderBy('tahun', 'DESC')->get();
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

            return view('pages.contents.produsen.indexdata', compact('data', 'draft', 'tahun', 'opd', 'status'));
        }
    }

    public function calculateProgress($id)
    {
        $data = Data::findOrFail($id);
        $progress = $data->calculateProgress();

        return response()->json(['progress' => $progress]);
    }

    public function filterData(Request $request)
    {
        $opd_id = $request->input('opd_id');
        $tahun = $request->input('tahun');

        // Lakukan query sesuai dengan filter yang diberikan
        $data = Data::query();

        if ($opd_id) {
            $data->where('opd_id', $opd_id);
        }

        if ($tahun) {
            $data->where('tahun', $tahun);
        }

        $filteredData = $data->get();

        $filteredData = [
            // Data yang difilter
        ];

        // Kembalikan data yang difilter dalam format yang sesuai
        return response()->json($filteredData);
    }

    public function data_filter_tahun(Request $request)
    {
        // dd($request->all());
        $status = $request->status;
        if (Auth::user()->role_id == '1') {
            $data = Data::data_nonprodusen_tahun($request->tahun, $request->opd);
            // $opd = Opd::all();
            $opdsQuery = Opd::select('id', 'nama_opd')
                ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
            $opd = $opdsQuery->get();
            $tahun = MasterTahun::where('is_active', 1)->get();

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
            // return view('pages.contents.administrator.indexdata', compact('data', 'opd'));
        } elseif (Auth::user()->role_id == '2' || auth()->user()->hasRole('pembina') || auth()->user()->hasRole('walidatapendukung')) {
            if ($status == 1) {
                $data = Data::data_setuju_walidata_tahun($request->tahun, $request->opd);
                // dd($data);
            } elseif ($status == 2) {
                $data = Data::data_tolak_walidata_tahun($request->tahun, $request->opd);
                // dd($data->count());
            } elseif ($status == 3) {
                $data = Data::data_draft_walidata_tahun($request->tahun, $request->opd);
            }
            $file = Document::all();
            $tahun = MasterTahun::where('is_active', 1)->get();
            // $opd = Opd::all();
            $opdsQuery = Opd::select('id', 'nama_opd')
                ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
            $opd = $opdsQuery->get();

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
            // return view('pages.contents.walidata.indexdata', compact('data', 'file', 'tahun'));
        } elseif (Auth::user()->role_id == '3') {
            if ($status == 1) {
                $data = Data::data_setuju_produsen_tahun($request->tahun);
            } elseif ($status == 2) {
                $data = Data::data_tolak_produsen_tahun($request->tahun);
            } elseif ($status == 3) {
                $data = Data::data_produsen_tahun($request->tahun);
            }

            $draft = Data::get_draft()->count();
            // $opd = Opd::all();
            $opdsQuery = Opd::select('id', 'nama_opd')
                ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
            $opd = $opdsQuery->get();
            $tahun = MasterTahun::where('is_active', 1)->get();

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
            // return view('pages.contents.produsen.indexdata', compact('data', 'draft'));
        }
    }

    public function searchData(Request $request)
    {
        // dd($request->status);
        // $searchQuery = $request->input('searchQuery');
        // $tahun = $request->input('tahun');
        // $opd = $request->input('opd');
        if ($request->status == Data::STATUS_DRAFT) {
            $data = Data::data_draft_walidata_search($request->tahun, $request->opd, $request->searchQuery);
        } elseif ($request->status == Data::STATUS_SETUJU) {
            $data = Data::data_setuju_walidata_search($request->tahun, $request->opd, $request->searchQuery);
        } elseif ($request->status == Data::STATUS_TOLAK) {
            $data = Data::data_tolak_walidata_search($request->tahun, $request->opd, $request->searchQuery);
        } elseif ($request->status == 'pengumpulan') {
            $data = Data::data_pengumpulan_walidata_search($request->tahun, $request->opd, $request->searchQuery);
        } elseif ($request->status == 'lengkap') {
            $data = Data::data_pengumpulan_lengkap_walidata_search($request->tahun, $request->opd, $request->searchQuery);
        } elseif ($request->status == 'pemeriksaan') {
            $data = Data::data_verif_walidata_search($request->tahun, $request->opd, $request->searchQuery);
        } elseif ($request->status == 'revisi') {
            $data = Data::data_revisi_walidata_search($request->tahun, $request->opd, $request->searchQuery);
        } elseif ($request->status == 'siap-publikasi') {
            $data = Data::data_siap_publikasi_walidata_search($request->tahun, $request->opd, $request->searchQuery);
        } elseif ($request->status == 'publikasi') {
            $data = Data::data_publikasi_walidata_search($request->tahun, $request->opd, $request->searchQuery);
        } elseif ($request->status == 'terpublikasi') {
            $data = Data::data_terpublikasi_walidata_search($request->tahun, $request->opd, $request->searchQuery);
        }

        return response()->json(['data' => $data]);
    }

    public function searchDataPredusen(Request $request)
    {
        if ($request->status == Data::STATUS_DRAFT) {
            $data = Data::data_draft_produsen_search($request->tahun, $request->searchQuery);
        } elseif ($request->status == Data::STATUS_SETUJU) {
            $data = Data::data_setuju_produsen_search($request->tahun, $request->searchQuery);
        } elseif ($request->status == Data::STATUS_TOLAK) {
            $data = Data::data_tolak_produsen_search($request->tahun, $request->searchQuery);
        } elseif ($request->status == 'pengumpulan') {
            $data = Data::data_pengumpulan_produsen_search($request->tahun, $request->searchQuery);
        } elseif ($request->status == 'lengkap') {
            $data = Data::data_pengumpulan_lengkap_produsen_search($request->tahun, $request->searchQuery);
        } elseif ($request->status == 'pemeriksaan') {
            $data = Data::data_verif_produsen_search($request->tahun, $request->searchQuery);
        } elseif ($request->status == 'revisi') {
            $data = Data::data_revisi_produsen_search($request->tahun, $request->searchQuery);
        } elseif ($request->status == 'siap-publikasi') {
            $data = Data::data_siap_publikasi_produsen_search($request->tahun, $request->searchQuery);
        } elseif ($request->status == 'publikasi') {
            $data = Data::data_publikasi_produsen_search($request->tahun, $request->searchQuery);
        } elseif ($request->status == 'terpublikasi') {
            $data = Data::data_terpublikasi_produsen_search($request->tahun, $request->searchQuery);
        }

        return response()->json(['data' => $data]);
    }

    public function data_filter_tahun_lalu(Request $request)
    {
        // $data =
        //     Data::whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
        //     ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);
        // ->where('opd_id', $request->opd);
        // ->get();
        // dd($request->all());
        $year = date('Y') - 1;
        $get_data = Data::data_draft_walidata();
        $data =
            DB::table('data')
            ->join('opds', function ($join) {
                $join->on('data.opd_id', '=', 'opds.id');
            })
            ->join('status', function ($join) {
                $join->on('data.status_id', '=', 'status.id');
            })
            ->join('users', function ($join) {
                $join->on('data.user_id', '=', 'users.id');
            })
            ->select('data_prioritas', 'tahun', 'nama_opd', 'nama_data', 'jenis_data', 'sumber_data', 'status_id', 'status', 'name', 'user_id', 'opds.id', 'data.id')
            ->where('tahun', '=', $year)
            ->whereNotIn('nama_data', $get_data->pluck('nama_data'))
            ->whereNotIn('status_id', [Data::STATUS_DRAFT, Data::STATUS_TOLAK]);
        // ->get();
        if (! empty($request->opd)) {
            $data = $data->where('opds.id', $request->opd)->get();
        } else {
            $data = $data->get();
        }

        // dd($data);
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function show()
    {
        if (Auth::user()->role_id == '1') {
            $data = Data::data_nonprodusen();
            // $opd = Opd::all();
            $opdsQuery = Opd::select('id', 'nama_opd')
                ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
            $opd = $opdsQuery->get();

            return view('pages.contents.administrator.indexdata', compact('data', 'opd'));
        } elseif (Auth::user()->role_id == '2' || auth()->user()->hasRole('pembina') || auth()->user()->hasRole('walidatapendukung')) {
            $data = Data::data_draft_walidata();

            return view('pages.contents.walidata.indexdata', compact('data'));
        } elseif (Auth::user()->role_id == '3') {
            $data = Data::data_produsen();
            $draft = Data::get_draft()->count();

            return view('pages.contents.produsen.indexdata', compact('data', 'draft'));
        }
    }

    public function create()
    {
        $year = date('Y') - 1;
        // $getyear = date('Y');
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $data = Opd::get_opd();
        $get_data = Data::data_draft_walidata();
        // dd($get_data->pluck('nama_data'));
        $data_tahun =
            DB::table('data')
            ->join('opds', function ($join) {
                $join->on('data.opd_id', '=', 'opds.id');
            })
            ->join('status', function ($join) {
                $join->on('data.status_id', '=', 'status.id');
            })
            ->join('users', function ($join) {
                $join->on('data.user_id', '=', 'users.id');
            })
            ->select('data_prioritas', 'tahun', 'nama_opd', 'nama_data', 'jenis_data', 'sumber_data', 'status_id', 'status', 'name', 'user_id', 'opds.id', 'data.id')
            ->where('tahun', '=', $year)
            ->whereNotIn('nama_data', $get_data->pluck('nama_data'))
            ->whereNotIn('status_id', [Data::STATUS_DRAFT, Data::STATUS_TOLAK])
            ->get();
        // $data_tahun = Data::whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI, Data::STATUS_SETUJU])
        //     ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
        //     ->get();
        // ->whereNotIn('tahun', '!=', $getyear)
        // dd($data_tahun);
        $sumber = SumberData::where('is_active', 1)->get();
        $tahun = MasterTahun::where('is_active', 1)->get();
        // $nama_data = Data::selesai_konfirmasi_walidata()->where('tahun', 2022);
        // dd($data_tahun);

        // get e-walidata
        $response = Http::withToken('e55838acb12247f3150efa488f8fcd54')
            ->get('https://sipd.go.id/ewalidata/serv/get_dssd', [
                'kodepemda' => '3519',
            ]);
        $sipd = $response->json();
        // dd($sipd, $response);
        if (! $response->ok()) {
            $sipd = [];
        }

        return view('pages.contents.walidata.createdata', compact('data_tahun', 'opd', 'data', 'sumber', 'tahun', 'sipd'));
    }

    public function fetch_data_sipd(Request $request)
    {

        $kodeindikator = $request->query('kodeindikator');
        $tahun_sipd = $request->query('tahun');
        $uraian_indikator = $request->query('uraian_indikator');

        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $sumber = SumberData::where('is_active', 1)->get();
        $tahun = MasterTahun::where('is_active', 1)->get();

        return view('pages.contents.walidata.create_sipd', compact('opd', 'sumber', 'tahun', 'kodeindikator', 'tahun_sipd', 'uraian_indikator'));
    }

    public function index_data(Request $request)
    {
        $get_data = Data::data_draft_walidata();
        $query =
            DB::table('data')
            ->join('opds', function ($join) {
                $join->on('data.opd_id', '=', 'opds.id');
            })
            ->join('status', function ($join) {
                $join->on('data.status_id', '=', 'status.id');
            })
            ->join('users', function ($join) {
                $join->on('data.user_id', '=', 'users.id');
            })
            ->select('data_prioritas', 'tahun', 'nama_opd', 'nama_data', 'jenis_data', 'sumber_data', 'status_id', 'status', 'name', 'user_id', 'opds.id', 'data.id')
            ->whereNotIn('nama_data', $get_data->pluck('nama_data'))
            // ->whereNotIn('status_id', [Data::STATUS_DRAFT, Data::STATUS_TOLAK])
            ->whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
            ->orderBy('tahun', 'DESC');
        if ($request->tahun != null) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->opd) {
            $query->where('opds.id', $request->opd);
        }

        $data = $query->orderBy('tahun', 'DESC')->get();

        return DataTables::of($data)
            ->make(true);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $user_id = Auth::user()->id;
        // dd(Auth::user());
        // $existingData = Data::where('opd_id', auth()->user()->opd_id)->where('nama_data', trim($request->nama_data))->count();
        $existingData = Data::where('nama_data', trim($request->nama_data))->where('opd_id', $request->opd_id)->where('tahun', $request->tahun)->whereNotIn('status_id', [Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])->count();
        // dd($existingData);
        if ($existingData > 0) {
            Alert::error('Gagal', 'Data sudah terdaftar pada sistem');

            return back();
        }
        // dd($request);
        $create = Data::create([
            'nama_data' => $request->nama_data,
            'opd_id' => $request->opd_id,
            'jenis_data' => $request->jenis_data,
            'sumber_data' => $request->sumber_data,
            'status_id' => Data::STATUS_DRAFT,
            'user_id' => $user_id,
            'tahun' => $request->tahun,
            'jadwal_rilis' => $request->jadwal_rilis,
            'jadwal_pemutakhiran' => $request->jadwal_pemutakhiran,
            'data_prioritas' => $request->data_prioritas,
            'kodeindikator' => $request->kodeindikator,

        ]);
        // dd($create);

        if ($create) {
            activity()->causedBy(auth()->id())->performedOn($create)->log('Menambahkan data: ' . $create->nama_data);

            if (Auth::user()->role_id == '1') {
                Alert::success('Berhasil', 'Berhasil menambahkan Data!');

                return redirect('/data_administrator');
            } elseif (Auth::user()->role_id == '2' || auth()->user()->hasRole('pembina') || auth()->user()->hasRole('walidatapendukung')) {
                Alert::success('Berhasil', 'Berhasil menambahkan Data!');

                return redirect('/data_walidata/draft');
            } elseif (Auth::user()->role_id == '3') {
                Alert::success('Berhasil', 'Berhasil menambahkan Data!');

                return redirect('/data_produsen/draft');
            }

            return redirect('/home');
        }
        Alert::error('Gagal', 'Gagal menambahkan Data!');

        return
            back()
            ->withInput();
    }

    public function add_data_tahun_lalu(Request $request)
    {
        // $data = $request->ids;
        $data = Data::with(['standar', 'indikator', 'variabel', 'berkas', 'visualtable.header.isi'])->whereIn('id', $request->ids)->get();
        $user_id = Auth::user()->id;
        $year = date('Y');
        foreach ($data as $item) {
            $create = Data::create([
                'nama_data' => $item['nama_data'],
                'opd_id' => $item['opd_id'],
                'jenis_data' => $item['jenis_data'],
                'sumber_data' => $item['sumber_data'],
                'status_id' => Data::STATUS_DRAFT,
                'user_id' => $user_id,
                'tahun' => $year,
                'jadwal_rilis' => $item['jadwal_rilis'],
                'jadwal_pemutakhiran' => $item['jadwal_pemutakhiran'],
                'data_prioritas' => $item['data_prioritas'],

            ]);


            $create->standar()->create([
                'konsep' => $item->standar->konsep,
                'definisi' => $item->standar->definisi,
                'klasifikasi' => $item->standar->klasifikasi,
                'ukuran' => $item->standar->ukuran,
                'satuan' => $item->standar->satuan,
                'kode' => $item->standar->kode,
            ]);
            if ($item->indikator) {
                $create->indikator()->create([
                    'nama' => $item->indikator->nama,
                    'konsep' => $item->indikator->konsep,
                    'definisi' => $item->indikator->definisi,
                    'interpretasi' => $item->indikator->interpretasi,
                    'metode' => $item->indikator->metode,
                    'ukuran' => $item->indikator->ukuran,
                    'satuan' => $item->indikator->satuan,
                    'klasifikasi_penyajian' => $item->indikator->klasifikasi_penyajian,
                    'komposit' => $item->indikator->komposit,
                    'publikasi_indikator_pembangun' => $item->indikator->publikasi_indikator_pembangun,
                    'nama_indikator_pembangun' => $item->indikator->nama_indikator_pembangun,
                    'kegiatan_variabel_pembangun' => $item->indikator->kegiatan_variabel_pembangun,
                    'kode_kegiatan_variabel_pembangun' => $item->indikator->kode_kegiatan_variabel_pembangun,
                    'nama_variabel_pembangun' => $item->indikator->nama_variabel_pembangun,
                    'level_estimasi' => $item->indikator->level_estimasi,
                    'umum' => $item->indikator->umum,
                ]);
            }
            if ($item->variabel) {
                $create->variabel()->create([
                    'nama' => $item->variabel->nama,
                    'konsep' => $item->variabel->konsep,
                    'alias' => $item->variabel->alias,
                    'definisi' => $item->variabel->definisi,
                    'referensi_pemilihan' => $item->variabel->referensi_pemilihan,
                    'referensi_waktu' => $item->variabel->referensi_waktu,
                    'tipe_data' => $item->variabel->tipe_data,
                    'klasifikasi_isian' => $item->variabel->klasifikasi_isian,
                    'ukuran' => $item->variabel->ukuran,
                    'satuan' => $item->variabel->satuan,
                    'aturan_validasi' => $item->variabel->aturan_validasi,
                    'kalimat_pertanyaan' => $item->variabel->kalimat_pertanyaan,
                    'umum' => $item->variabel->umum,
                ]);
            }

            foreach ($item->berkas as $berkas) {
                $newPath = 'public/exports/' . Str::slug($item->nama_data) . '/' . $year . '/' . $berkas->name;
                Storage::copy($berkas->path, $newPath);
                $create->berkas()->create([
                    'name' => $berkas->name,
                    'path' => $newPath,
                    'size' => $berkas->size,
                    'tahun' => $year,
                ]);
            }

            foreach ($item->visualtable as $visual) {
                $visualCreate = $create->visualtable()->create([
                    'namatabel' => $visual->namatabel,
                ]);
                foreach ($visual->header as $header) {
                    $headerCreate = $visualCreate->header()->create([
                        'id_namatabel' => $visualCreate->id,
                        'header' => $header->header,
                        'urutan_menyamping' => $header->urutan_menyamping,
                    ]);
                    foreach ($header->isi as $isi) {
                        $headerCreate->isi()->create([
                            'id_header' => $headerCreate->id,
                            'isi' => $isi->isi,
                            'urutan_kebawah' => $isi->urutan_kebawah,
                            'id_namatabel' => $visualCreate->id,
                        ]);
                    }
                }
            }
        }
        Alert::success('Berhasil', 'Berhasil menambahkan Data!');

        return redirect('/data_walidata/draft');
    }

    public function restore(Request $request, $id)
    {
        $data = Data::findOrFail($id);

        if (! in_array($data->status_id, [Data::STATUS_SETUJU, Data::STATUS_TOLAK])) {
            Alert::error('Gagal', 'Data tidak dapat direstore, status harus setuju/tolak');

            return back();
        }

        $data->update([
            'status_id' => Data::STATUS_DRAFT,
            'progress' => 0,
        ]);

        if ($data) {
            if (Auth::user()->role_id == '1') {
                activity()
                    ->performedOn($data)->log('Merestore Daftar Data');
                Alert::success('Berhasil', 'Data Berhasil Direstore!');

                return redirect('/data_administrator');
            } elseif (Auth::user()->role_id == '2' || auth()->user()->hasRole('pembina') || auth()->user()->hasRole('walidatapendukung')) {
                activity()->performedOn($data)->log('Merestore Daftar Data');
                Alert::success('Berhasil', 'Data Berhasil Direstore!');

                return redirect('/data_walidata/draft');
            } elseif (Auth::user()->role_id == '3') {
                activity()->performedOn($data)->log('Merestore Daftar Data');
                Alert::success('Berhasil', 'Data Berhasil Direstore!');

                return redirect('/data_produsen/draft');
            }

            return redirect('/home');
        }

        Alert::error('Gagal', 'Data Gagal Direstore!');

        return back()
            ->withInput();
    }

    public function detail($id)
    {
        $data = Data::find($id);
        $detail = Data::causer_id($id);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();

        return view('pages.contents.walidata.detaildata', compact('data', 'opd', 'detail'));
    }

    public function edit($id)
    {
        $data = Data::find($id);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $sumber = SumberData::where('is_active', 1)->get();
        $tahun = MasterTahun::where('is_active', 1)->get();

        return view('pages.contents.walidata.editdata', compact('tahun', 'data', 'sumber', 'opd'));
    }

    public function update(Request $request, $id)
    {

        $data = Data::findOrFail($id);
        $get_statusdata = $data->status_id;
        $data->update([
            'nama_data' => $request->nama_data,
            'opd_id' => $request->opd_id,
            'jenis_data' => $request->jenis_data,
            'sumber_data' => $request->sumber_data,
            'jadwal_rilis' => $request->jadwal_rilis,
            'jadwal_pemutakhiran' => $request->jadwal_pemutakhiran,
            'status_id' => $get_statusdata,
            'tahun' => $request->tahun,
        ]);

        if ($data) {
            if (Auth::user()->role_id == '1') {
                activity()->performedOn($data)->log('Mengedit Daftar Data');
                Alert::info('Berhasil', 'Berhasil memperbarui Data!');

                return redirect('/data_administrator');
            } elseif (Auth::user()->role_id == '2' || auth()->user()->hasRole('pembina') || auth()->user()->hasRole('walidatapendukung')) {
                activity()
                    ->performedOn($data)
                    ->log('Mengedit Daftar Data');
                Alert::info('Berhasil', 'Berhasil memperbarui Data!');

                return redirect('/data_walidata/draft');
            } elseif (Auth::user()->role_id == '3') {
                activity()->performedOn($data)->log('Mengedit Daftar Data');
                Alert::info('Berhasil', 'Berhasil memperbarui Data!');

                return redirect('/data_produsen/draft');
            }

            return redirect('/home');
        }
        Alert::error('Gagal', 'Gagal memperbarui Data!');

        return back()
            ->withInput();
    }

    public function destroy($id)
    {
        $data = Data::findOrFail($id);
        $nama_data = $data->nama_data;

        if ($data) {
            if (Auth::user()->role_id == '1') {
                activity()->log('Menghapus Daftar Data ' . $nama_data);
                $data->delete();
                Alert::success('Berhasil', 'Berhasil Menghapus Data!');

                return redirect('/data_administrator');
            } elseif (Auth::user()->role_id == '2' || auth()->user()->hasRole('pembina') || auth()->user()->hasRole('walidatapendukung')) {

                activity()->performedOn($data)->log('Menghapus Daftar Data ' . $nama_data);
                $data->delete();
                Alert::success('Berhasil', 'Berhasil Menghapus Data!');

                return redirect('/data_walidata/draft');
            } elseif (Auth::user()->role_id == '3') {
                activity()->log('Menghapus Daftar Data' . $nama_data);
                $data->delete();
                Alert::success('Berhasil', 'Berhasil Menghapus Data!');

                return redirect('/data_produsen/draft');
            }

            return redirect('/home');
        }

        Alert::error('Gagal', 'Gagal memperbarui Data!');

        return back()
            ->withInput();
    }

    public function selesai_konfirmasi_walidata(Request $request)
    {
        $data = Data::selesai_konfirmasi_walidata();
        // dd($data);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $status = '1';
        $tahun = MasterTahun::where('is_active', 1)->get();

        if ($request->ajax()) {
            $year = date('Y');
            // $year = '2022';
            $query = DB::table('data')
                ->join('opds', function ($join) {
                    $join->on('data.opd_id', '=', 'opds.id');
                })
                ->join('status', function ($join) {
                    $join->on('data.status_id', '=', 'status.id');
                })
                ->join('users', function ($join) {
                    $join->on('data.user_id', '=', 'users.id');
                })
                ->select('data_prioritas', 'tahun', 'nama_opd', 'nama_data', 'progress', 'jenis_data', 'sumber_data', 'status_id', 'status', 'name', 'user_id', 'opds.id', 'data.id', 'data_prioritas')
                ->whereNotIn('status_id', [Data::STATUS_DRAFT, Data::STATUS_TOLAK]);
            if (Auth::user()->role_id == 4) {
                $query->where(function ($q) {
                    $q->where('data.opd_id', auth()->user()->opd_id)
                        ->orWhere('data_prioritas', 1);
                });
            }
            // if ($request->tahun == null && $request->opd == null) {
            //     $query->where('tahun', $year);
            // } else {
            // if ($request->opd != null && $request->tahun != null) {
            //     $query->where('opds.id', $request->opd)->where('tahun', $request->tahun);
            // } elseif ($request->opd == null && $request->tahun != null) {
            //     $query->where('tahun', $request->tahun);
            // }
            // }
            if ($request->tahun != null) {
                $query->where('tahun', $request->tahun);
            }

            if ($request->opd) {
                $query->where('opds.id', $request->opd);
            }

            $data = $query->orderBy('tahun', 'DESC')->get();
            // Tambahkan nomor urut secara manual pada setiap baris data
            $no = 1;
            foreach ($data as $row) {
                $row->no = $no++;
            }

            return DataTables::of($data)
                ->addColumn('status', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->addColumn('action', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->addColumn('status_tahapan', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->make(true);
        }

        return view('pages.contents.walidata.indexdata_setuju', compact('data', 'status', 'tahun', 'opd'));
    }

    public function tolak_konfirmasi_walidata(Request $request)
    {
        $data = Data::data_tolak_walidata();
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $status = '2';
        $tahun = MasterTahun::where('is_active', 1)->get();
        if ($request->ajax()) {
            $year = date('Y');
            // $year = '2022';
            $query = DB::table('data')
                ->join('opds', function ($join) {
                    $join->on('data.opd_id', '=', 'opds.id');
                })
                ->join('status', function ($join) {
                    $join->on('data.status_id', '=', 'status.id');
                })
                ->join('users', function ($join) {
                    $join->on('data.user_id', '=', 'users.id');
                })
                ->select('data_prioritas', 'tahun', 'nama_opd', 'nama_data', 'jenis_data', 'progress', 'sumber_data', 'status_id', 'status', 'alasan', 'name', 'user_id', 'opds.id', 'data.id')
                ->where('status_id', '=', Data::STATUS_TOLAK);
            if (Auth::user()->role_id == 4) {
                $query->where(function ($q) {
                    $q->where('data.opd_id', auth()->user()->opd_id)
                        ->orWhere('data_prioritas', true);
                });
            }
            // if ($request->tahun == null && $request->opd == null) {
            //     $query->where('tahun', $year);
            // } else {
            // if ($request->opd != null && $request->tahun != null) {
            //     $query->where('opds.id', $request->opd)->where('tahun', $request->tahun);
            // } elseif (
            //     $request->opd == null && $request->tahun != null
            // ) {
            //     $query->where('tahun', $request->tahun);
            // }
            // }

            if ($request->tahun != null) {
                $query->where('tahun', $request->tahun);
            }

            if ($request->opd) {
                $query->where('opds.id', $request->opd);
            }

            $data = $query->orderBy('tahun', 'DESC')->get();
            // Tambahkan nomor urut secara manual pada setiap baris data
            $no = 1;
            foreach ($data as $row) {
                $row->no = $no++;
            }

            return DataTables::of($data)
                ->addColumn('status', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->addColumn('action', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->make(true);
        }

        return view('pages.contents.walidata.indexdata_tolak', compact('data', 'status', 'tahun', 'opd'));
    }

    public function get_all_opd()
    {
        $get_all = Data::data_produsen_setuju_all();

        return view('pages.contents.walidata.indexall_opd', compact('get_all'));
    }

    public function get_all_opdall(Request $request)
    {
        $year = date('Y');
        $data = Data::with('opd')->prioritas()->opd($request->id)->where('tahun', $request->tahun);
        $opd = Opd::whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI'])->pluck('nama_opd', 'id');
        $draft = Data::where('opd_id', '=', $request->id)->where('status_id', '=', 3)->count();
        $tahun = MasterTahun::where('is_active', 1)->get();
        // dd($data);
        // if ($request->ajax()) {
        //     $query =
        //         DB::table("data")
        //         ->join("opds", function ($join) {
        //             $join->on("data.opd_id", "=", "opds.id");
        //         })
        //         ->join("status", function ($join) {
        //             $join->on("data.status_id", "=", "status.id");
        //         })
        //         ->join("users", function ($join) {
        //             $join->on("data.user_id", "=", "users.id");
        //         })
        //         ->select("data_prioritas", "tahun", "nama_opd", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
        //         ->where('status_id', '=', Data::STATUS_DRAFT);

        //     if ($request->tahun == null && $request->opd == null) {
        //         $query->where('tahun', $year);
        //     } else {
        //         if ($request->opd != null && $request->tahun == null) {
        //             $query->where('opds.id', $request->opd);
        //         } elseif ($request->opd == null && $request->tahun != null) {
        //             $query->where('tahun', $request->tahun);
        //         }
        //     }

        //     $data = $query->orderBy('tahun', 'DESC')->get();
        //     // Tambahkan nomor urut secara manual pada setiap baris data
        //     $no = 1;
        //     foreach ($data as $row) {
        //         $row->no = $no++;
        //     }

        //     return DataTables::of($data)
        //         ->addColumn('status', function ($row) {
        //             // Tambahkan aksi di sini jika diperlukan
        //         })
        //         ->addColumn('action', function ($row) {
        //             // Tambahkan aksi di sini jika diperlukan
        //         })
        //         ->make(true);
        // }
        return view('pages.contents.walidata.index_get_opd', compact('data', 'opd', 'draft', 'tahun'));
    }

    public function getData(Request $request)
    {
        // dd($request->all());
        $id = decrypt($request->id);
        if ($id == 'all') {
            $data = Data::with('opd')->prioritas()->where('tahun', $request->tahun);
            $datatables = DataTables::eloquent($data)
                ->editColumn('opd_id', function ($data) {
                    return $data->opd->nama_opd;
                })
                ->editColumn('status_id', function ($data) {
                    return $data->status->status;
                })
                ->addIndexColumn()
                ->toArray();
            $datatables['draft_counter'] = Data::where('status_id', '=', 3)->where('tahun', $request->tahun)->count();
        } else {
            // $data = Data::where('tahun', $request->tahun)->with('opd')->prioritas()->OPD($id);
            $data = Data::where('tahun', $request->tahun)->with('opd')->prioritas()->OPD($id);
            $datatables = DataTables::eloquent($data)
                ->editColumn('opd_id', function ($data) {
                    return $data->opd->nama_opd;
                })
                ->editColumn('status_id', function ($data) {
                    return $data->status->status;
                })
                ->addIndexColumn()
                ->toArray();
            $datatables['draft_counter'] = Data::where('opd_id', '=', $id)->where('status_id', '=', 3)->where('tahun', $request->tahun)->count();
            // dd($datatables['data'], $datatables['draft_counter']);
        }

        return $datatables;
    }

    public function setuju(Request $request, $id)
    {
        $id = decrypt($request->id);
        $data = Data::findOrFail($id);

        $data->update([
            'status_id' => Data::STATUS_SETUJU,
        ]);
        if ($data) {
            if (Auth::user()->role_id == '1') {
                activity()->performedOn($data)->log('Menyetujui Daftar Data');
                Alert::success('Berhasil', 'Data Berhasil Disetujui!');

                return redirect('/data_administrator');
            } elseif (Auth::user()->role_id == '2') {
                activity()->performedOn($data)->log('Menyetujui Daftar Data');
                Alert::success('Berhasil', 'Data Berhasil Disetujui!');

                return redirect('/data_walidata/draft');
            } elseif (Auth::user()->role_id == '3') {
                activity()->performedOn($data)->log('Menyetujui Daftar Data');
                Alert::success('Berhasil', 'Data Berhasil Disetujui!');

                return redirect('/data_produsen/draft');
            } else {
                return redirect('/home');
            }
        }

        Alert::error('Gagal', 'Data Gagal Disetujui!');

        return back()
            ->withInput();
    }

    public function setuju_data(Request $request)
    {
        $id = $request->id_sukses;
        $data = Data::findOrFail($id);

        $data->update([
            'status_id' => Data::STATUS_SETUJU,
        ]);
        if ($data) {
            if (Auth::user()->role_id == '1') {
                activity()->performedOn($data)->log('Menyetujui Daftar Data');
                Alert::success('Berhasil', 'Data Berhasil Disetujui!');

                return redirect('/data_administrator');
            } elseif (Auth::user()->role_id == '2') {
                activity()->performedOn($data)->log('Menyetujui Daftar Data');
                Alert::success('Berhasil', 'Data Berhasil Disetujui!');

                return redirect('/data_walidata/draft');
            } elseif (Auth::user()->role_id == '3') {
                activity()->performedOn($data)->log('Menyetujui Daftar Data');
                Alert::success('Berhasil', 'Data Berhasil Disetujui!');

                return redirect('/data_produsen/draft');
            } else {
                return redirect('/home');
            }
        }

        Alert::error('Gagal', 'Data Gagal Disetujui!');

        return back()
            ->withInput();
    }

    public function alasan(Request $request)
    {
        // $id = $request->id_alasan;
        $data = Data::findOrFail($id);
        $alasan = $request->alasan;
        $data->update([
            'alasan' => $alasan,
            'status_id' => Data::STATUS_TOLAK,
        ]);

        if ($data) {
            if (Auth::user()->role_id == '1') {
                activity()->performedOn($data)->log('Menolak Daftar Data');
                Alert::success('Berhasil', 'Berhasil Menolak Data dan Memberi Alasan!');

                return redirect('/data_administrator');
            } elseif (Auth::user()->role_id == '2' || auth()->user()->hasRole('pembina') || auth()->user()->hasRole('walidatapendukung')) {
                activity()->performedOn($data)->log('Menolak Daftar Data');
                Alert::success('Berhasil', 'Berhasil Menolak Data dan Memberi Alasan!');

                return redirect('/data_walidata/draft');
            } elseif (Auth::user()->role_id == '3') {
                activity()->performedOn($data)->log('Menolak Daftar Data');
                Alert::success('Berhasil', 'Berhasil Menolak Data dan Memberi Alasan!');

                return redirect('/data_produsen/draft');
            }

            return redirect('/home');
        }

        Alert::error('Gagal', 'Gagal Menolak Data dan Memberi Alasan!');

        return back()
            ->withInput();
    }

    public function alasan_data(Request $request)
    {
        $id = $request->id_alasan;
        $data = Data::findOrFail($id);
        $alasan = $request->alasan;
        $data->update([
            'alasan' => $alasan,
            'status_id' => Data::STATUS_TOLAK,
        ]);

        if ($data) {
            if (Auth::user()->role_id == '1') {
                activity()->performedOn($data)->log('Menolak Daftar Data');
                Alert::success('Berhasil', 'Berhasil Menolak Data dan Memberi Alasan!');

                return redirect('/data_administrator');
            } elseif (Auth::user()->role_id == '2' || auth()->user()->hasRole('pembina') || auth()->user()->hasRole('walidatapendukung')) {
                activity()->performedOn($data)->log('Menolak Daftar Data');
                Alert::success('Berhasil', 'Berhasil Menolak Data dan Memberi Alasan!');

                return redirect('/data_walidata/draft');
            } elseif (Auth::user()->role_id == '3') {
                activity()->performedOn($data)->log('Menolak Daftar Data');
                Alert::success('Berhasil', 'Berhasil Menolak Data dan Memberi Alasan!');

                return redirect('/data_produsen/draft');
            }

            return redirect('/home');
        }

        Alert::error('Gagal', 'Gagal Menolak Data dan Memberi Alasan!');

        return back()
            ->withInput();
    }

    public function verifikasi_data()
    {
        $verifikasi = Data::verifikasi_data();

        return view('pages.contents.produsen.indexverifikasi', compact('verifikasi'));
    }

    public function selesai_konfirmasi(Request $request)
    {
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $data = Cache::remember('data:setujui:' . auth()->user()->opd_id, 30, fn() => Data::selesai_konfirmasi());
        $draft = Data::get_draft()->count();
        $status = '1';
        $tahun = MasterTahun::where('is_active', 1)->get();
        if ($request->ajax()) {
            $query =
                DB::table('data')
                ->join('opds', function ($join) {
                    $join->on('data.opd_id', '=', 'opds.id');
                })
                ->join('status', function ($join) {
                    $join->on('data.status_id', '=', 'status.id');
                })
                ->join('users', function ($join) {
                    $join->on('data.user_id', '=', 'users.id');
                })
                ->select('nama_opd', 'tahun', 'nama_data', 'jenis_data', 'sumber_data', 'status_id', 'status', 'name', 'user_id', 'opds.id', 'data.id', 'data_prioritas')
                ->where('opds.id', '=', auth()->user()->opd_id)
                ->whereNotIn('status_id', [Data::STATUS_DRAFT, Data::STATUS_TOLAK]);

            if ($request->tahun != null) {
                $query->where('tahun', $request->tahun);
            }

            $data = $query->orderBy('tahun', 'DESC')->get();
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

        return view('pages.contents.produsen.indexdata', compact('data', 'draft', 'status', 'tahun', 'opd'));
    }

    public function tolak_konfirmasi(Request $request)
    {
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $data = Data::tolak_konfirmasi();
        $draft = Data::get_draft()->count();
        $status = '2';
        $tahun = MasterTahun::where('is_active', 1)->get();
        if ($request->ajax()) {
            $query =
                DB::table('data')
                ->join('opds', function ($join) {
                    $join->on('data.opd_id', '=', 'opds.id');
                })
                ->join('status', function ($join) {
                    $join->on('data.status_id', '=', 'status.id');
                })
                ->join('users', function ($join) {
                    $join->on('data.user_id', '=', 'users.id');
                })
                ->select('nama_opd', 'tahun', 'nama_data', 'jenis_data', 'sumber_data', 'status_id', 'status', 'name', 'alasan', 'user_id', 'opds.id', 'data.id', 'data_prioritas')
                ->where('status_id', '=', Data::STATUS_TOLAK)
                ->where('opds.id', '=', Auth::user()->opd_id);

            if ($request->tahun != null) {
                $query->where('tahun', $request->tahun);
            }

            $data = $query->orderBy('tahun', 'DESC')->get();
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

        return view('pages.contents.produsen.indexdata', compact('data', 'draft', 'status', 'tahun', 'opd'));
    }

    public function pdf(Request $request)
    {
        Carbon::setLocale('id');
        $id = Auth::user()->opd_id;
        if ($request->ajax()) {
            $dataDraft = Data::get_draft($request->get('tahun'))->count();
            if ($dataDraft > 0) {
                return response()->json(['message' => 'Anda belum bisa mengunduh berita acara dikarenakan masih ada DATA yang berstatus DRAFT', 'data' => $dataDraft], 400);
            } else {
                return response()->json(['message' => 'ok']);
            }
        }
        $data = Data::data_produsen_setuju($request->get('tahun'));
        $today = Carbon::now();
        $tahun = Carbon::now()->translatedFormat('Y');
        // dd($tahun);
        $path = base_path('public/assets/img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data1 = file_get_contents($path);
        $pict = 'data:image/' . $type . ';base64,' . base64_encode($data1);
        $opd = Opd::where('id', '=', $id)->firstOrFail();
        $adminOPD = Opd::where('nama_opd', 'Adminstrator')->firstOrFail();

        $pdf = PDF::loadView('pages.contents.pdf', compact('data', 'tahun', 'today', 'pict', 'opd', 'adminOPD'));

        return $pdf->setPaper('a4', 'portrait')->setOptions(['defaultFont' => 'serif'])->stream();
    }

    public function pdf2(Request $request)
    {
        ini_set('memory_limit', '512M');
        $id = decrypt($request->opd_id);
        // dd($request->all());
        $request_tahun = $request->tahun;
        if ($id == 'all') {
            $data = Data::with('opd')->prioritas()->where('tahun', $request_tahun)->get();
        } else {
            $data = Data::with('opd')->prioritas()->OPD($id)->where('tahun', $request_tahun)->get();
        }

        $dt = Carbon::now()->translatedFormat('l, d F Y');
        $tahun = Carbon::now()->translatedFormat('Y');
        $bln = Carbon::now()->translatedFormat('F');
        $tgl = Carbon::now()->translatedFormat('j');
        $hari = Carbon::now()->translatedFormat('l');

        $path = base_path('public/assets/img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data1 = file_get_contents($path);
        $pict = 'data:image/' . $type . ';base64,' . base64_encode($data1);

        if ($id == 'all') {
            $pdf = PDF::loadView('pages.contents.pdf_all', compact('data', 'hari', 'dt', 'tgl', 'bln', 'tahun', 'pict', 'request_tahun'));

            return $pdf->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'serif'])->stream();
        } else {
            $today = Carbon::now();
            $adminOPD = Opd::where('nama_opd', 'Adminstrator')->firstOrFail();
            $opd = Opd::where('id', '=', $id)->firstOrFail();
            $pdf = PDF::loadView('pages.contents.pdf', compact('data', 'today', 'pict', 'opd', 'adminOPD', 'request_tahun'));

            return $pdf->setPaper('a4')->setOptions(['defaultFont' => 'serif'])->stream();
        }
    }

    public function notif()
    {
        $notif = Data::causer_id();

        return view('pages.contents.walidata.notif', compact('notif'));
    }

    public function draft(Request $request)
    {
        $id = decrypt($request->id);
        if ($id == 'all') {
            $draft = Data::where('status_id', '=', 3)->count();
        } else {
            $draft = Data::where('opd_id', '=', $id)->where('status_id', '=', 3)->count();
        }

        return view('pages.contents.walidata.index_get_opd', compact('draft'));
    }

    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new DataImport, $request->file('file'));
            Alert::success('Berhasil', 'Data berhasil diimport');

            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), ['importData', $exception->getCode()]);
            Alert::error('Gagal', $exception->getMessage() . PHP_EOL . '. Pastikan Anda menggunakan template yang tepat.');

            return back();
        }
    }

    public function detailData($id)
    {
        $data = Data::find($id);
        // $get_visual_data = VisualData::all();
        $get_visual_data = VisualTable::where('id_data', $id)->get();
        $get_berkas = Berkas::where('data_id', $id)->get();
        // dd($get_visual_data);
        // dd($get_visual_data);

        // Mengambil data tabel berdasarkan nama
        $namaTabels = VisualTable::all();
        // dd($namaTabels);
        $tables = []; // Initialize an empty array
        $headers = []; // Initialize an empty array
        $rows = []; // Initialize an empty array

        if ($namaTabels->isNotEmpty()) {
            foreach ($namaTabels as $namaTabel) {
                $table = VisualTable::where('id', $namaTabel->id)->where('id_data', $id)->first();

                if ($table) {
                    // Mengambil data header terurut berdasarkan urutan menyamping
                    $headers = VisualHeader::where('id_namatabel', $table->id)
                        ->orderBy('urutan_menyamping')
                        ->get();

                    // Mengambil data isi terurut berdasarkan urutan kebawah
                    $rows = VisualIsi::where('id_namatabel', $table->id)
                        ->orderBy('urutan_kebawah')
                        ->get()
                        ->groupBy('urutan_kebawah');

                    // Mengambil data isi terurut berdasarkan urutan kebawah
                    $rows_grafik = VisualIsi::where('id_namatabel', $table->id)
                        ->orderBy('id_header')
                        ->get()
                        ->groupBy('id_header');

                    $tables[] = [
                        'table' => $table,
                        'headers' => $headers,
                        'rows_grafik' => $rows_grafik,
                        'rows' => $rows,
                    ];
                    break;
                }
            }
        }
        $data = Data::with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            // ->when(auth()->user()->hasAnyRole('produsen'), fn ($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);
        $existingBerkas = $data->berkas->map(function ($b) use ($data) {

            // dd($b->path, url($b->path));
            return [
                'name' => $b->name,
                'size' => $b->size,
                'previewUrl' => route('filepreview', ['payload' => Crypt::encryptString($b->path)]),
                'deleteUrl' => route('delete-berkas', [$data->id, $b->id]),
            ];
        })->toArray();
        // $berkasData = $data->berkas->transform(function ($b) use ($data) {

        //     return [
        //         'id' => $b->id,
        //         'name' => $b->name,
        //         'created_at' => $b->created_at,
        //         // 'previewUrl' => Storage::url($b->path),
        //         'previewUrl' => route('filepreview', ['payload' => Crypt::encryptString($b->path)]),
        //     ];
        // })->toArray();
        // dd($berkasData[0]['previewUrl']);

        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $histories = Data::causer_id($id);
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

        foreach ($existingData as $item) {
            if ($item->header == 'Tahun') {
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

            if ($item->axis_x_header == 'Tahun') {
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

            if ($item->axis_y_header == 'Tahun') {
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
                if ($item->header == 'Tahun') {
                    $noUrut = VisualIsi::where('id_namatabel', $item->id_table)->pluck('urutan_kebawah')->unique()->toArray();
                    $noUrutTahun = VisualIsi::where('id_namatabel', $item->id_table)->whereIn('urutan_kebawah', $noUrut)->where('isi', $name)->pluck('urutan_kebawah');
                    $data1 = VisualIsi::where('id_namatabel', $item->id_table)->where('id_header', $item->axis_y)->whereIn('urutan_kebawah', $noUrutTahun)->pluck('isi')->toArray();

                    $seriesData[$item->id][] = [
                        'name' => $name,
                        'data' => $data1,
                    ];
                } else {
                    $seriesData[$item->id][] = [
                        'name' => $name,
                        'data' => array_map('intval', explode(',', $axis_y[$index])),
                    ];
                }
            }

            foreach ($axis_x as $index => $name) {
                if ($item->header == 'Tahun') {
                    $noUrut = VisualIsi::where('id_namatabel', $item->id_table)->pluck('urutan_kebawah')->unique()->toArray();
                    $noUrutTahun = VisualIsi::where('id_namatabel', $item->id_table)->whereIn('urutan_kebawah', $noUrut)->where('isi', $name)->pluck('urutan_kebawah');
                    $data1 = VisualIsi::where('id_namatabel', $item->id_table)->where('id_header', $item->axis_y)->whereIn('urutan_kebawah', $noUrutTahun)->pluck('isi')->toArray();

                    $seriesDataLine[$item->id][] = [
                        'name' => $name,
                        'data' => $data1,
                    ];
                } else {
                    $seriesDataLine[$item->id][] = [
                        'name' => $name,
                        'data' => array_map('intval', explode(',', $axis_y[$index])),
                    ];
                }
            }
        }
        // dd($seriesDataLine);
        $kategori = json_encode($kategori);
        $axis_y = json_encode($axis_y);
        $axis_x = json_encode($axis_x);

        // dd($tables);
        return view(
            'pages.contents.data-detail',
            compact(
                'data',
                'opd',
                'existingBerkas',
                'histories',
                'get_visual_data',
                'get_berkas',
                'namaTabels',
                'tables',
                'headers',
                'rows',
                'existingData',
                'axis_x',
                'axis_y',
                'kategori',
                'seriesData',
                'seriesDataLine'
            )
        );
    }

    public function detailDataStandar($id)
    {
        $data = Data::find($id);
        // $get_visual_data = VisualData::all();
        $get_visual_data = VisualTable::where('id_data', $id)->get();
        $get_berkas = Berkas::where('data_id', $id)->get();
        // dd($get_visual_data);
        // dd($get_visual_data);

        // Mengambil data tabel berdasarkan nama
        $namaTabels = VisualTable::all();
        // dd($namaTabels);
        $tables = []; // Initialize an empty array
        $headers = []; // Initialize an empty array
        $rows = []; // Initialize an empty array

        if ($namaTabels->isNotEmpty()) {
            foreach ($namaTabels as $namaTabel) {
                $table = VisualTable::where('id', $namaTabel->id)->where('id_data', $id)->first();

                if ($table) {
                    // Mengambil data header terurut berdasarkan urutan menyamping
                    $headers = VisualHeader::where('id_namatabel', $table->id)
                        ->orderBy('urutan_menyamping')
                        ->get();

                    // Mengambil data isi terurut berdasarkan urutan kebawah
                    $rows = VisualIsi::where('id_namatabel', $table->id)
                        ->orderBy('urutan_kebawah')
                        ->get()
                        ->groupBy('urutan_kebawah');

                    // Mengambil data isi terurut berdasarkan urutan kebawah
                    $rows_grafik = VisualIsi::where('id_namatabel', $table->id)
                        ->orderBy('id_header')
                        ->get()
                        ->groupBy('id_header');

                    $tables[] = [
                        'table' => $table,
                        'headers' => $headers,
                        'rows_grafik' => $rows_grafik,
                        'rows' => $rows,
                    ];
                    break;
                }
            }
        }
        $data = Data::with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            // ->when(auth()->user()->hasAnyRole('produsen'), fn ($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);
        $existingBerkas = $data->berkas->map(function ($b) use ($data) {

            // dd($b->path, url($b->path));
            return [
                'name' => $b->name,
                'size' => $b->size,
                'previewUrl' => route('filepreview', ['payload' => Crypt::encryptString($b->path)]),
                'deleteUrl' => route('delete-berkas', [$data->id, $b->id]),
            ];
        })->toArray();
        // $berkasData = $data->berkas->transform(function ($b) use ($data) {

        //     return [
        //         'id' => $b->id,
        //         'name' => $b->name,
        //         'created_at' => $b->created_at,
        //         // 'previewUrl' => Storage::url($b->path),
        //         'previewUrl' => route('filepreview', ['payload' => Crypt::encryptString($b->path)]),
        //     ];
        // })->toArray();
        // dd($berkasData[0]['previewUrl']);

        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $histories = Data::causer_id($id);
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
            if ($item->header == 'Tahun') {
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

            if ($item->axis_x_header == 'Tahun') {
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

            if ($item->axis_y_header == 'Tahun') {
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
                if ($item->header == 'Tahun') {
                    $noUrut = VisualIsi::where('id_namatabel', $item->id_table)->pluck('urutan_kebawah')->unique()->toArray();
                    $noUrutTahun = VisualIsi::where('id_namatabel', $item->id_table)->whereIn('urutan_kebawah', $noUrut)->where('isi', $name)->pluck('urutan_kebawah');
                    $data1 = VisualIsi::where('id_namatabel', $item->id_table)->where('id_header', $item->axis_y)->whereIn('urutan_kebawah', $noUrutTahun)->pluck('isi')->toArray();

                    $seriesData[$item->id][] = [
                        'name' => $name,
                        'data' => $data1,
                    ];
                } else {
                    $seriesData[$item->id][] = [
                        'name' => $name,
                        'data' => array_map('intval', explode(',', $axis_y[$index])),
                    ];
                }
            }

            foreach ($axis_x as $index => $name) {
                if ($item->header == 'Tahun') {
                    $noUrut = VisualIsi::where('id_namatabel', $item->id_table)->pluck('urutan_kebawah')->unique()->toArray();
                    $noUrutTahun = VisualIsi::where('id_namatabel', $item->id_table)->whereIn('urutan_kebawah', $noUrut)->where('isi', $name)->pluck('urutan_kebawah');
                    $data1 = VisualIsi::where('id_namatabel', $item->id_table)->where('id_header', $item->axis_y)->whereIn('urutan_kebawah', $noUrutTahun)->pluck('isi')->toArray();

                    $seriesDataLine[$item->id][] = [
                        'name' => $name,
                        'data' => $data1,
                    ];
                } else {
                    $seriesDataLine[$item->id][] = [
                        'name' => $name,
                        'data' => array_map('intval', explode(',', $axis_y[$index])),
                    ];
                }
            }
        }
        // dd($seriesDataLine);

        $kategori = json_encode($kategori);
        $axis_y = json_encode($axis_y);
        $axis_x = json_encode($axis_x);

        // dd($tables);
        return view(
            'pages.contents.data-detail-standar',
            compact(
                'data',
                'opd',
                'existingBerkas',
                'histories',
                'get_visual_data',
                'get_berkas',
                'namaTabels',
                'tables',
                'headers',
                'rows',
                'existingData',
                'axis_x',
                'axis_y',
                'kategori',
                'seriesData',
                'seriesDataLine'
            )
        );
    }

    public function aktifkan_data_prioritas($id)
    {
        $data = Data::findOrFail($id);
        if ($data->data_prioritas == null | $data->data_prioritas == 0) {
            $data->update([
                'data_prioritas' => 1,

            ]);
            Alert::success('Berhasil', 'Data dijadikan Data Prioritas');

            return back();
        } elseif (($data->data_prioritas == 1)) {
            $data->update([
                'data_prioritas' => 0,

            ]);
            Alert::info('Peringatan', 'Data dijadikan Bukan Data Prioritas');

            return back();
        }

        return redirect('/tahun');
    }

    public function aktifkan_data_prioritas_produsen($id)
    {
        $data = Data::findOrFail($id);
        if ($data->data_prioritas == null | $data->data_prioritas == 0) {
            $data->update([
                'data_prioritas' => 1,

            ]);
            Alert::success('Berhasil', 'Data dijadikan Data Prioritas');

            return back();
        } elseif (($data->data_prioritas == 1)) {
            $data->update([
                'data_prioritas' => 0,

            ]);
            Alert::info('Peringatan', 'Data dijadikan Bukan Data Prioritas');

            return back();
        }

        return redirect('/tahun');
    }
}
