<?php

namespace App\Http\Controllers\Walidata;

use App\Exports\DataExport;
use App\Exports\IndikatorExport;
use App\Exports\VariabelExport;
use App\Http\Controllers\Controller;
use App\Jobs\PurgeTmpFiles;
use App\Jobs\SendFilesToCKAN;
use App\Models\Data;
use App\Models\MasterTahun;
use App\Models\Opd;
use App\Services\CkanApi\Facades\CkanApi;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use ZipArchive;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $year = date('Y');
        $data = Data::where('status_id', Data::STATUS_SIAP_PUBLIKASI)
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'publikasi'])
            ->where('tahun', $year)
            ->latest()
            ->get();
        $status = 'publikasi';
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $tahun = MasterTahun::where('is_active', 1)->get();
        if ($request->ajax()) {
            // dd($request->all());
            $query =
                Data::whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI])
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'publikasi']);

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
        return view('pages.contents.walidata.publikasi.index', compact('data', 'status', 'opd', 'tahun'));
    }

    public function filter_publikasi(Request $request)
    {
        $status = $request->status;
        $year = $request->tahun;
        $opd = $request->opd;
        if ($status == 'publikasi') {
            $data = Data::where('status_id', Data::STATUS_SIAP_PUBLIKASI)->with(['opd', 'status', 'berkas', 'publikasi', 'indikator', 'variabel', 'standar', 'kegiatan'])
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
        } elseif ($status == 'terpublikasi') {
            $data = Data::where('status_id', Data::STATUS_TERPUBLIKASI)
                ->with(['opd', 'berkas', 'status', 'indikator', 'publikasi', 'variabel', 'standar', 'kegiatan'])->latest();

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

    public function terpublikasi(Request $request)
    {
        $year = date('Y');
        $data = Data::where('status_id', Data::STATUS_TERPUBLIKASI)
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'publikasi'])
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
                ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
                ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'publikasi']);

            // ->latest();
            // dd($request->opd, $query->limit(5)->get());
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

        return view('pages.contents.walidata.publikasi.index', compact('data', 'status', 'opd', 'tahun'));
    }

    public function ckanshow($id)
    {
        $data = CkanApi::dataset()->all();
        $data = Data::findOrFail($id);
        // dd($data->publikasi()->get());
    }

    public function organisasi($id)
    {
        $data = Data::with(['publikasi'])->findOrFail($id);
        $orgs = Cache::remember('publikasi:orgs', 180, fn() => CkanApi::organization()->all(['limit' => 1000]));
        $orgs = $orgs['result'] ?? [];

        // dd($data);
        return view('pages.contents.walidata.publikasi.organisasi', compact('data', 'orgs'));
    }

    public function createOrganisasi(Request $request)
    {
        $request->validate([
            'org_name' => 'required|string',
            'org_desc' => 'nullable|string'
        ]);

        $res = CkanApi::organization()->show($request->org_name);

        if (isset($res['success']) && $res['success']) {
            return redirect()->back()->with([
                Alert::error('Gagal', 'OPD/Organisasi sudah tersedia di CKAN')
            ]);
        }

        $res = CkanApi::organization()->create([
            'name' => Str::slug($request->get('org_name')),
            'title' => $request->get('org_name', ''),
            'description' => $request->get('org_desc', '')
        ]);

        if (isset($res['result'])) {
            return redirect()->back()->with([
                Alert::success('Berhasil', 'OPD/Organisasi berhasil ditambahkan ke CKAN')
            ]);
        }

        return redirect()->back()->with([
            Alert::error('Gagal', 'OPD/Organisasi gagal ditambahkan di CKAN')
        ]);
    }

    public function simpanOrganisasi($id, Request $request)
    {
        $request->validate([
            'org_id' => 'required|uuid'
        ]);

        $res = CkanApi::organization()->show($request->org_id);
        if (isset($res['success']) && !$res['success']) {
            return redirect()->back()->with([
                Alert::error('Gagal', 'OPD/Organisasi tidak tersedia pada CKAN')
            ]);
        }

        $data = Data::findOrFail($id);

        if ($data->status_id != Data::STATUS_SIAP_PUBLIKASI) {
            return redirect()->back()->with([
                Alert::error('Gagal', 'Status data belum siap untuk dipublikasi')
            ]);
        }

        $data->publikasi()->updateOrCreate(
            ['data_id' => $data->id],
            ['org_id' => $request->org_id]
        );

        Alert::success('Berhasil', 'Organisasi berhasil dipilih');

        return redirect()->route('publikasi.dataset', $id);
    }

    public function dataset($id)
    {
        $data = Data::with('publikasi')->findOrFail($id);
        $orgs = Cache::remember('publikasi:orgs', 180, fn() => CkanApi::organization()->all(['limit' => 1000]));
        $orgs = $orgs['result'] ?? [];
        $group = CkanApi::group()->all(['limit' => 100]);

        return view('pages.contents.walidata.publikasi.dataset', compact('data', 'orgs', 'group'));
    }

    public function simpanDataset($id, Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'visibility' => 'required|numeric|in:0,1',
            'group_id' => 'string',
            'tags' => 'string'
        ]);

        $data = Data::findOrFail($id);

        if ($data->status_id != Data::STATUS_SIAP_PUBLIKASI) {
            return redirect()->back()->with([
                Alert::error('Gagal', 'Status data belum siap untuk dipublikasi')
            ]);
        }

        $data->publikasi()->updateOrCreate(
            ['data_id' => $data->id],
            [
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'visibility' => $request->get('visibility'),
                'group_id' => $request->get('group_id'),
                'tags' => $request->get('tags'),
            ]
        );


        Alert::success('Berhasil', 'Informasi dataset berhasil disimpan');

        return redirect()->route('publikasi.review', $id);
    }

    public function review($id)
    {
        $data = Data::with(['publikasi', 'berkas'])->findOrFail($id);
        $orgs = CkanApi::organization()->all(['limit' => 1000]);
        $orgs = $orgs['result'] ?? [];
        $group = CkanApi::group()->all(['limit' => 100]);

        return view('pages.contents.walidata.publikasi.review', compact('data', 'orgs', 'group'));
    }

    //  "tags" => array:2 [▼
    //   0 => array:5 [▼
    //     "vocabulary_id" => null
    //     "state" => "active"
    //     "display_name" => "Indikator Sasaran"
    //     "id" => "2b7cec9d-9073-44f8-ae5a-2b793d3957d6"
    //     "name" => "Indikator Sasaran"
    //   ]
    //   1 => array:5 [▶]
    // ]
    // "groups" => array:1 [▼
    //   0 => array:6 [▼
    //     "display_name" => "Kebudayaan"
    //     "description" => ""
    //     "image_display_url" => "http://katalog-data.madiunkab.go.id/uploads/group/2022-10-05-101209.907846masks.png"
    //     "title" => "Kebudayaan"
    //     "id" => "8bf20bc6-832e-4fbe-a117-4b28b1a31f58"
    //     "name" => "kebudayaan"
    //   ]
    // ]

    // public function publish($id)
    // {
    //     $data = Data::with(['publikasi', 'opd'])->findOrFail($id);

    //     if ($data->status_id != Data::STATUS_SIAP_PUBLIKASI) {
    //         return redirect()->back()->with([
    //             Alert::error('Gagal', 'Status data belum siap untuk dipublikasi')
    //         ]);
    //     }

    //     if (empty($data->publikasi) || empty($data->publikasi->org_id)) {
    //         return redirect()->back()->with([
    //             Alert::error('Gagal', 'Data publikasi kosong')
    //         ]);
    //     }

    //     // dd(CkanApi::dataset());
    //     // CkanApi::dataset()->create
    //     // dd($data);
    //     $group = CkanApi::group()->show($data->publikasi->group_id);
    //     // dd($group["result"]["display_name"]);
    //     // dd($group["result"]);
    //     try {

    //         $getslug = substr($data->publikasi->title, 0, 100);
    //         $newslug = Str::slug($getslug);

    //         $existingDataset = CkanApi::dataset()->show($newslug);
    //         // dd($existingDataset);
    //         if (!empty($existingDataset['result'])) {
    //             CkanApi::dataset()->delete($newslug);
    //         }



    //         $dataset = CkanApi::dataset()->create([
    //             'owner_org' => $data->publikasi->org_id,
    //             'title' => $data->publikasi->title,
    //             'name' => $newslug,
    //             'url' => $newslug,
    //             'notes' => $data->publikasi->description,
    //             'private' => $data->publikasi->visibility == 0,
    //             'groups' => [
    //                 'name' => $group["result"]["display_name"] ?? '',
    //             ],
    //         ]);
    //         // dd($dataset);


    //         if (!$dataset || empty($dataset['result']) || (isset($dataset['success']) && !$dataset['success'])) {
    //             Log::error('Gagal publikasi data: ' . json_encode($dataset), ['Publikasi']);
    //             throw new \Exception('Error tidak diketahui');
    //         }

    //         DB::beginTransaction();

    //         $data->publikasi->update([
    //             'dataset_id' => $dataset['result']['id'],
    //             'published_at' => now(),
    //             'slug' => $newslug,
    //         ]);
    //         $data->update([
    //             'status_id' => Data::STATUS_TERPUBLIKASI
    //         ]);

    //         DB::commit();
    //     } catch (\Exception $exception) {
    //         DB::rollBack();

    //         $errorMsg = isset($dataset['error']) && isset($dataset['error']['name']) ? implode(PHP_EOL, $dataset['error']['name']) : '';
    //         return redirect()->back()->with([
    //             Alert::error('Gagal', 'Gagal mempublikasi data, Response ckan tidak valid: ' . $exception->getCode() . ' | ' . $errorMsg)
    //         ]);
    //     }

    //     SendFilesToCKAN::dispatch($data, $dataset['result']['id']);

    //     return redirect()->back()->with([
    //         Alert::success('Berhasil', 'Data berhasil dipublikasi ke CKAN.')
    //     ]);
    // }
    public function publish($id)
    {
        //ambil data berdasarkan id
        $data = Data::with(['publikasi', 'opd'])->findOrFail($id);

        if ($data->status_id != Data::STATUS_SIAP_PUBLIKASI) {
            Alert::error('Gagal', 'Status data belum siap untuk dipublikasi');
            return redirect()->back();
        }

        if (empty($data->publikasi) || empty($data->publikasi->org_id)) {
            Alert::error('Gagal', 'Data publikasi kosong');
            return redirect()->back();
        }

        try {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 0);
            $group = CkanApi::group()->show($data->publikasi->group_id);
            if (!$group || empty($group['result'])) {
                Alert::error('Gagal', 'Grup tidak ditemukan di CKAN');
                return redirect()->back();
            }

            // dd($group);
            // dd($group['result']['id']);

            $getslug = substr($data->publikasi->title, 0, 100);
            $newslug = Str::slug($getslug);

            $existingDataset = CkanApi::dataset()->show($newslug);
            if (!empty($existingDataset['result'])) {
                CkanApi::dataset()->delete($newslug);
            }
            $tagsArray = explode(',', $data->publikasi->tags);
            $tags = array_map(function ($tag) {
                return [
                    'name' => strtolower(trim($tag)),
                    'vocabulary_id' => null,
                ];
            }, $tagsArray);

            $datasetData = [
                'owner_org' => $data->publikasi->org_id,
                'title' => $data->publikasi->title,
                'name' => $newslug,
                'url' => $newslug,
                'notes' => $data->publikasi->description,
                'private' => $data->publikasi->visibility == 0,
                'groups' => [
                    [
                        'id' => $group['result']['id']
                    ],
                ],
                'tags' => $tags,

            ];
            // $dataset = CkanApi::dataset()->create($datasetData);
            // dd($dataset['result']['id']);

            // URL API CKAN
            $apiUrl = 'https://katalog-data.madiunkab.go.id/api/3/action/package_create';

            // API Key CKAN (ganti dengan API key yang sesuai)
            $apiKey = 'ca8c7114-12c2-4b3f-a7d5-12ca85a73f60';

            // Buat instance Guzzle client
            $client = new Client();

            // Kirim permintaan POST ke API CKAN
            $response = $client->post($apiUrl, [
                'headers' => [
                    'Authorization' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $datasetData,
                'verify' => false,
            ]);

            // Ambil respons dari API
            $responseBody = json_decode($response->getBody(), true);
            // dd($responseBody);
            Log::info('Creating dataset with data: ' . json_encode($datasetData));

            if (!$responseBody || empty($responseBody['result']) || (isset($responseBody['success']) && !$responseBody['success'])) {
                Log::error('Gagal publikasi data: ' . json_encode($responseBody), ['Publikasi']);
                throw new \Exception('Error tidak diketahui');
            }
            SendFilesToCKAN::dispatch($data, $responseBody['result']['id']);
            $payload = [
                'data' => [
                    [
                        'kodeindikator' => $data->kodeindikator,
                        'kodepemda' => "3519",
                        'tahun' => $data->tahun,
                        'data' => $data->value_sipd,
                    ]
                ]
            ];

            $response = Http::withToken('4ddba5c8f23a81e75d62731ce590a661')
                ->post('https://sipd.go.id/ewalidata/serv/push_dssd', $payload);


            DB::beginTransaction();

            $data->publikasi->update([
                'dataset_id' => $responseBody['result']['id'],
                'published_at' => now(),
                'slug' => $newslug,
            ]);
            $data->update([
                'status_id' => Data::STATUS_TERPUBLIKASI
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            $errorMsg = isset($responseBody['error']) && isset($responseBody['error']['name']) ? implode(PHP_EOL, $responseBody['error']['name']) : $exception->getMessage();
            Alert::error('Gagal', 'Gagal mempublikasi data, Response CKAN tidak valid: ' . $exception->getCode() . ' | ' . $errorMsg);
            return redirect()->back();
        }


        // dd($dd);
        Alert::success('Berhasil', 'Data berhasil dipublikasi ke CKAN.');
        return redirect()->back();
    }



    public function exportData($id)
    {
        $data = Data::with(['opd', 'berkas', 'standar', 'status'])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->findOrFail($id);
        // dd($data->indikator);

        if (!in_array($data->status_id, [Data::STATUS_TERPUBLIKASI, Data::STATUS_SIAP_PUBLIKASI])) {
            Alert::error('Gagal', 'Data belum siap/terpublikasi');
            return redirect()->back();
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
        Excel::store($export, $exportPath, 'public', \Maatwebsite\Excel\Excel::XLSX);

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
            Alert::error('Gagal', 'Gagal membuat berkas zip');
            return redirect()->back();
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
