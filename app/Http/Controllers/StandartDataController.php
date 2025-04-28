<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\MasterTahun;
use App\Models\Opd;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class StandartDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $year = date('Y');
        // $year = '2022';
        $data = Cache::remember('data:pengumpulan:proses:' . auth()->user()->opd_id, 30, fn() => Data::whereIn('status_id', [Data::STATUS_SETUJU])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('tahun', $year)
            ->latest()
            ->get());
        $status = 'proses';
        // dd($data);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();

        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_SETUJU, Data::STATUS_PENGAJUAN_STANDART_DATA])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);

            // ->latest();
            // dd($query->get());

            if ($request->tahun != null) {
                $query->where('tahun', $request->tahun);
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

        return view('pages.contents.produsen.standart_data.index', compact('data', 'opd', 'tahun', 'status'));
    }

    public function index_walidata(Request $request)
    {
        $year = date('Y');
        // $year = '2022';
        $data = Cache::remember('data:pengumpulan:proses:' . auth()->user()->opd_id, 30, fn() => Data::whereIn('status_id', [Data::STATUS_SETUJU])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('tahun', $year)
            ->latest()
            ->get());
        $status = 'proses';
        // dd($data);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();

        if ($request->ajax()) {
            // dd(Data::STATUS_PENGAJUAN_STANDART_DATA);
            $query =
                Data::whereIn('status_id', [Data::STATUS_PENGAJUAN_STANDART_DATA, Data::STATUS_SETUJU])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'verifikasi']);
            if (Auth::user()->role_id == 4) {
                $query->where(function ($q) {
                    $q->where('data.opd_id', auth()->user()->opd_id)
                        ->orWhere('data_prioritas', 1);
                });
            }
            // ->latest();
            if (auth()->user()->role_id == 4) {
                $query->where(function ($q) {
                    $q->where('opd_id', auth()->user()->opd_id)
                        ->orWhere('data_prioritas', true);
                });
            }
            // dd($query->limit('5')->get());

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

            if ($request->opd != null) {
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



        return view('pages.contents.walidata.standart_data.index', compact('data', 'opd', 'tahun', 'status'));
    }

    public function verifikasiStandarData($id, Request $request)
    {
        $data = Data::with(['opd', 'standar'])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);
        // dd($data);
        $year = date('Y') - 1;
        // $existingData = Data::where('nama_data', trim($data->nama_data))->where('tahun', trim($year))->count();
        $existingData = Data::where('nama_data', 'like', '%' . $data->nama_data . '%')
            // ->where('tahun', $year)
            ->count();
        $getdata = [];
        if ($existingData > 0) {
            // dd('a');
            // $year = date('Y');
            $getdata = Data::with(['opd', 'standar'])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                // ->where('tahun', $year)
                ->where('nama_data', $data->nama_data)
                ->first();

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

                $getdata->standar()->updateOrCreate(
                    ['data_id' => $getdata->id],
                    array_merge(['data_id' => $getdata->id], $validated)
                );
                $getdata->update(['status_id' => Data::STATUS_PENGAJUAN_STANDART_DATA]);
                activity()->causedBy(auth()->id())->performedOn($data)->log('Data standar data dibuat: ' . $data->nama_data . '');

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


            $data->refresh();
            // dd($data);

            activity()->causedBy(auth()->id())->performedOn($data)->log('Data standar data dibuat: ' . $data->nama_data . '');


            Alert::success('Berhasil', 'Standar data berhasil disimpan');
            // return redirect('/data_produsen/pengumpulan');
        }


        // dd($getdata);

        return view('pages.contents.walidata.standart_data.verif', compact('data', 'getdata', 'existingData'));
    }

    public function verify($id, Request $request)
    {
        // dd($request->get('field'));
        $request->validate([
            'field' => 'required',
            'accepted' => 'required',
            'category' => 'required|in:variabel,indikator,berkas,kegiatan,standar'
        ]);

        $data = Data::find($id);
        // dd($data);
        if (!in_array($data->status_id, [Data::STATUS_PENGAJUAN_STANDART_DATA, Data::STATUS_REVISI_STANDART_DATA])) {
            return redirect()->back()->with([
                Alert::error('Gagal', 'Data tidak dalam status proses verifikasi / revisi.')
            ]);
        }
        activity()->causedBy(auth()->id())->performedOn($data)->log('Verifikasi data: ' . $data->nama_data . '');

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

    public function getKomentar($id, Request $request)
    {
        $request->validate([
            'field' => 'required',
            'category' => 'required|in:variabel,indikator,berkas,kegiatan,standar'
        ]);

        $comment = Verifikasi::where('category', $request->get('category'))->where('data_id', $id)->where('field', $request->get('field'))
            ->first();

        return response()->json(['ok' => true, 'message' => '', 'comment' => $comment->comment ?? '']);
    }

    public function komentar($id, Request $request)
    {
        $request->validate([
            'field' => 'required',
            'category' => 'required|in:variabel,indikator,berkas,kegiatan,standar'
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

    public function setuju(Request $request)
    {
        $year = date('Y');
        // $year = '2022';
        $data = Data::whereIn('status_id', [Data::STATUS_SETUJU_STANDART_DATA, Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_REVISI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('tahun', $year)
            ->latest()
            ->get();
        $status = 'setuju';
        // dd($data);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();

        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_SETUJU_STANDART_DATA, Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_REVISI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);

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

            // if ($request->opd) {
            //     $query->where('opd_id', $request->opd);
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
                ->addColumn('status', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->addColumn('action', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->make(true);
        }

        return view('pages.contents.produsen.standart_data.index', compact('data', 'opd', 'tahun', 'status'));
    }

    public function setuju_walidata(Request $request)
    {
        $year = date('Y');
        // $year = '2022';
        $data = Data::whereIn('status_id', [Data::STATUS_SETUJU_STANDART_DATA])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('tahun', $year)
            ->latest()
            ->get();
        $status = 'setuju';
        // dd($data);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();

        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_SETUJU_STANDART_DATA, Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_REVISI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
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
                ->addColumn('status', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->addColumn('action', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->make(true);
        }

        return view('pages.contents.walidata.standart_data.index', compact('data', 'opd', 'tahun', 'status'));
    }

    public function revisi(Request $request)
    {
        $year = date('Y');
        // $year = '2022';
        $data =  Data::whereIn('status_id', [Data::STATUS_REVISI_STANDART_DATA])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('tahun', $year)
            ->latest()
            ->get();
        $status = 'revisi';
        // dd($data);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();

        // dd($request->all());
        if ($request->ajax()) {
            $query =
                Data::whereIn('status_id', [Data::STATUS_REVISI_STANDART_DATA])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);

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

            // if ($request->opd) {
            //     $query->where('opd_id', $request->opd);
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
                ->addColumn('status', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->addColumn('action', function ($row) {
                    // Tambahkan aksi di sini jika diperlukan
                })
                ->make(true);
        }
        // dd($data);

        return view('pages.contents.produsen.standart_data.index', compact('data', 'opd', 'tahun', 'status'));
    }
    public function revisi_walidata(Request $request)
    {
        $year = date('Y');
        // $year = '2022';
        // dd
        $data =  Data::whereIn('status_id', [Data::STATUS_REVISI_STANDART_DATA])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('tahun', $year)
            ->latest()
            ->get();
        $status = 'revisi';
        // dd($data);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();

        // dd($request->all());
        if ($request->ajax()) {
            $query =
                Data::whereIn('status_id', [Data::STATUS_REVISI_STANDART_DATA])
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
        // dd($data);

        return view('pages.contents.walidata.standart_data.index', compact('data', 'opd', 'tahun', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
