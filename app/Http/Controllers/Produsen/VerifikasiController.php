<?php

namespace App\Http\Controllers\Produsen;

use App\Http\Controllers\Controller;
use App\Models\Data;
use App\Models\MasterTahun;
use App\Models\Opd;
use App\Models\Verifikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $year = date('Y');
        $data = Data::where('status_id', Data::STATUS_PROSES_VERIFIKASI)
            ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('opd_id', auth()->user()->opd_id)
            ->where('tahun', $year)
            ->get();
        $status = 'pemeriksaan';
        // dd($data);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();
        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI])
                ->when(auth()->user()->hasAnyRole('produsen'), fn ($q) => $q->where('opd_id', auth()->user()->opd_id))
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

        return view('pages.contents.produsen.verifikasi.index', compact('data', 'status', 'opd', 'tahun'));
    }

    public function revisi(Request $request)
    {
        $year = date('Y');
        $data = Data::where('status_id', Data::STATUS_REVISI)
            ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'verifikasi'])
            ->where('opd_id', auth()->user()->opd_id)
            ->where('tahun', $year)
            ->get();
        // dd($data->verifikasi);
        $status = 'revisi';
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();
        // $getrevisi_berkas = $data->verifikasi->firstWhere('field', $data->berkas['id']);
        // dd($getrevisi_berkas);
        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_REVISI])
                ->when(auth()->user()->hasAnyRole('produsen'), fn ($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'verifikasi']);

            // ->latest();
            // dd($query->get());
            if ($request->tahun != null) {
                $query->where('tahun', $request->tahun);
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

        return view('pages.contents.produsen.verifikasi.index', compact('data', 'status', 'opd', 'tahun'));
    }

    public function siapPublikasi(Request $request)
    {
        $year = date('Y');
        $data = Data::whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
            ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('opd_id', auth()->user()->opd_id)
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
                ->when(auth()->user()->hasAnyRole('produsen'), fn ($q) => $q->where('opd_id', auth()->user()->opd_id))
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

        return view('pages.contents.produsen.verifikasi.index', compact('data', 'status', 'opd', 'tahun'));
    }

    public function variabel($id)
    {
        $data = Data::with(['variabel', 'standar', 'verifikasi' => fn ($q) => $q->category('variabel')])->findOrFail($id);

        return view('pages.contents.produsen.verifikasi.variabel', compact('data'));
    }

    public function indikator($id)
    {
        $data = Data::with(['indikator', 'standar', 'verifikasi' => fn ($q) => $q->category('indikator')])->findOrFail($id);

        return view('pages.contents.produsen.verifikasi.indikator', compact('data'));
    }

    public function berkas($id)
    {
        $data = Data::with(['opd', 'berkas', 'verifikasi' => fn ($q) => $q->category('berkas')])->findOrFail($id);

        $existingBerkas = $data->berkas->transform(function ($b) use ($data) {
            return [
                'id' => $b->id,
                'name' => $b->name,
                'created_at' => $b->created_at,
                'previewUrl' => route('filepreview', ['payload' => Crypt::encryptString($b->path)]),
            ];
        })->toArray();

        return view('pages.contents.produsen.verifikasi.berkas', compact('data', 'existingBerkas'));
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

    public function filter_verifikasi(Request $request)
    {
        $status = $request->status;
        $year = $request->tahun;
        $opd = $request->opd;
        if ($status == 'pemeriksaan') {
            $data = Data::where('status_id', Data::STATUS_PROSES_VERIFIKASI)
                ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);

            if (!empty($year)) {
                $data = $data->where('tahun', $year)->where('opd_id', auth()->user()->opd_id);
            } elseif (empty($year)) {
                $data = $data->where('opd_id', auth()->user()->opd_id);
            }

            return response()->json(array(
                "success" => true,
                "data" => $data->get()
            ));
        } elseif ($status == 'revisi') {
            $data = Data::where('status_id', Data::STATUS_REVISI)
                ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);

            if (!empty($year)) {
                $data = $data->where('tahun', $year)->where('opd_id', auth()->user()->opd_id);
            } elseif (empty($year)) {
                $data = $data->where('opd_id', auth()->user()->opd_id);
            }

            return response()->json(array(
                "success" => true,
                "data" => $data->get()
            ));
        } elseif ($status == 'siap-publikasi') {
            $data = Data::whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
                ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);

            if (!empty($year)) {
                $data = $data->where('tahun', $year)->where('opd_id', auth()->user()->opd_id);
            } elseif (empty($year)) {
                $data = $data->where('opd_id', auth()->user()->opd_id);
            }

            return response()->json(array(
                "success" => true,
                "data" => $data->get()
            ));
        }
    }
}
