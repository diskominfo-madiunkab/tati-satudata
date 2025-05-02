<?php

namespace App\Http\Controllers\Produsen;

use App\Exports\DataExport;
use App\Exports\IndikatorExport;
use App\Exports\VariabelExport;
use App\Http\Controllers\Controller;
use App\Jobs\PurgeTmpFiles;
use App\Models\Data;
use App\Models\MasterTahun;
use App\Models\Opd;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use ZipArchive;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $year = date('Y');
        $data = Data::where('status_id', Data::STATUS_SIAP_PUBLIKASI)
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'publikasi'])
            ->where('opd_id', auth()->user()->opd_id)
            ->where('tahun', $year)
            ->latest()
            ->get();
        $status = 'publikasi';
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            // ->when(auth()->user()->hasAnyRole('produsen') && auth()->user()->opd_id, fn($q) => $q->where('id', auth()->user()->opd_id))
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();
        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI])
                // ->when(auth()->user()->hasAnyRole('produsen') && auth()->user()?->opd_id, fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'publikasi']);

            // ->latest();
            // dd($query->get());
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
        return view('pages.contents.produsen.publikasi.index', compact('data', 'status', 'opd', 'tahun'));
    }

    public function filter_publikasi_produsen(Request $request)
    {
        $status = $request->status;
        $year = $request->tahun;
        $opd = $request->opd;
        if ($status == 'publikasi') {
            $data = Data::where('status_id', Data::STATUS_SIAP_PUBLIKASI)->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
                ->latest();

            if (!empty($year)) {
                $data = $data->where('tahun', $year)->where('opd_id', auth()->user()->opd_id);
            } elseif (empty($year)) {
                $data = $data->where('opd_id', auth()->user()->opd_id);
            }

            return response()->json(array(
                "success" => true,
                "data" => $data->get()
            ));
        } elseif ($status == 'terpublikasi') {
            $data = Data::where('status_id', Data::STATUS_TERPUBLIKASI)
                ->with(['opd', 'berkas', 'status', 'indikator', 'publikasi', 'variabel', 'standar', 'kegiatan'])->latest();

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

    public function terpublikasi(Request $request)
    {
        $year = date('Y');
        $data = Data::where('status_id', Data::STATUS_TERPUBLIKASI)
            // ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'publikasi'])
            ->where('opd_id', auth()->user()->opd_id)
            ->where('tahun', $year)
            ->latest()
            ->get();
        $status = 'terpublikasi';
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();
        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_TERPUBLIKASI])
                // ->when(auth()->user()->hasAnyRole('produsen'), fn ($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'publikasi']);

            // ->latest();
            // dd($query->get());

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
        return view('pages.contents.produsen.publikasi.index', compact('data', 'status', 'opd', 'tahun'));
    }

    public function exportData($id)
    {
        $data = Data::with(['opd', 'berkas', 'standar', 'status'])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);

        if ($data->status_id != Data::STATUS_TERPUBLIKASI) {
            return redirect()->back()->with([
                Alert::error('Gagal', 'Data belum terpublikasi')
            ]);
        }

        if (in_array(strtolower($data->jenis_data), ['variabel', 'indikator'])) {
            $data->with(strtolower($data->jenis_data));
        }

        $export = new DataExport($data);

        if ($data->berkas->isEmpty()) {
            return Excel::download($export, $data->name . '.xlsx', \MaatWebsite\Excel\Excel::XLSX);
        }

        Storage::makeDirectory('public/exports/' . Str::slug($data->nama_data));
        $exportPath = 'public/exports/' . Str::slug($data->nama_data) . '/data-export.xlsx';
        $filePath = Storage::path($exportPath);
        Excel::store($export, $exportPath, 'local', \Maatwebsite\Excel\Excel::XLSX);

        $jenisData = strtolower($data->jenis_data);
        $metadataPath = Storage::path('public/exports/' . Str::slug($data->nama_data) . '/Metadata.xlsx');
        if ($jenisData === 'indikator') {
            $metadata = new IndikatorExport($data->indikator, $data->opd);
        } else if ($jenisData === 'variabel') {
            $metadata = new VariabelExport($data->variabel, $data->opd);
            $metadata->standarData($data->standar);
        }

        $archive = new ZipArchive();
        $tmpArchivePath = Storage::path('tmp/data-' . $data->id . '.tmp');
        Storage::makeDirectory('tmp');
        file_put_contents($tmpArchivePath, NULL);

        if ($archive->open($tmpArchivePath, ZipArchive::CREATE) !== TRUE) {
            return redirect()->back()->with([
                Alert::error('Gagal', 'Gagal membuat berkas zip')
            ]);
        }

        $archive->addFile($filePath, 'Informasi Data.xlsx');

        if ($metadata->export($metadataPath)) {
            $archive->addFile($metadata->getOutputFilePath(), 'Metadata.xlsx');
        }

        foreach ($data->berkas as $berkas) {
            $archive->addFile(Storage::path($berkas->path), 'berkas/' . $berkas->name);
        }
        $archive->close();

        PurgeTmpFiles::dispatch([$filePath, $tmpArchivePath])->delay(now()->addHours(2));

        return response()->file($tmpArchivePath, [
            'Content-Type' => 'application/x-zip',
            'Content-Disposition' => 'attachment; filename="' . $data->nama_data . '.zip"',
        ]);
    }
}
