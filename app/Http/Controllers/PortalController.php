<?php

namespace App\Http\Controllers;

use App\Models\BoxValue;
use App\Models\Data;
use App\Models\FileDownloadCount;
use App\Models\GrafikData;
use App\Models\Infografis;
use App\Models\JadwalTerbit;
use App\Models\MasterTahun;
use App\Models\Opd;
use App\Models\PublikasiGuest;
use App\Models\Regulasi;
use App\Models\SumberData;
use App\Models\UsulanData;
use App\Models\Visitor;
use App\Models\VisualData;
use App\Models\VisualIsi;
use App\Models\VisualTable;
use App\Models\Wilayah;
use App\Services\CkanApi\Facades\CkanApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PortalController extends Controller
{
    public function storeDataByFilter(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        $id_data = $request->input('id_data');
        $id_table = $request->input('id_table');
        $selectedAxisX = $request->input('axis_x');
        $selectedAxisY = $request->input('axis_y');
        $selectedCategory = $request->input('kategori');

        $grafik = GrafikData::where('id_table', $id_table)->first();
        if ($grafik) {
            $grafik->update([
                'id_data' => $id_data,
                'axis_x' => $selectedAxisX,
                'axis_y' => $selectedAxisY,
                'kategori' => $selectedCategory,
            ]);
        } else {
            $grafik = GrafikData::create([
                'id_data' => $id_data,
                'id_table' => $id_table,
                'axis_x' => $selectedAxisX,
                'axis_y' => $selectedAxisY,
                'kategori' => $selectedCategory,
            ]);
        }
        return redirect()->back()->with('active_tab', 'grafik');
    }

    public function index()
    {
        set_time_limit(600);

        if (!request()->hasCookie('newComer') && !isset($_COOKIE['newComer'])) {
            \Illuminate\Support\Facades\Cookie::queue('newComer', 'uwu', 120);

            $day = date("j");
            $month = date("n");
            $year = date("Y");
            $cek_visitor = Visitor::where('tgl', $day)->where('bln', $month)->where('thn', $year)->first();

            if (!empty($cek_visitor)) {
                $cek_visitor->increment('jumlah');
            } else {
                Visitor::create([
                    'nama' => 'pengunjung',
                    'tgl' => $day,
                    'bln' => $month,
                    'thn' => $year,
                    'jumlah' => 1
                ]);
            }
        }
        $infografis = Infografis::count();
        $publikasi = PublikasiGuest::count();
        
        try {
            $data = CkanApi::dataset()->all();
            if (!$data || !isset($data['result'])) {
                $data = ['result' => ['count' => Data::where('status_id', Data::STATUS_TERPUBLIKASI)->count(), 'results' => []]];
            }
        } catch (\Throwable $e) {
            $data = ['result' => ['count' => Data::where('status_id', Data::STATUS_TERPUBLIKASI)->count(), 'results' => []]];
        }

        $demografis = Wilayah::all();
        $org = Opd::count();
        $opd = max(0, $org - 2);

        try {
            $groups = Cache::remember('front', 3600, fn() => CkanApi::group()->all(['limit' => 100]));
            $groups = $groups['result'] ?? [];
        } catch (\Throwable $e) {
            $groups = [];
        }

        $boxvalue = BoxValue::with('data.publikasi')->get();
        return view('guest.beranda', compact('groups', 'infografis', 'publikasi', 'data', 'opd', 'demografis', 'boxvalue'));
    }

    public function tentang()
    {
        return view('guest.tentang');
    }

    public function infografis(Request $request)
    {
        $query = Infografis::with('images')->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }
        $infografis = $query->paginate(12);
        return view('guest.infografis', compact('infografis'));
    }

    public function infografis_detail($id)
    {
        try {
            $decryptedId = is_numeric($id) ? $id : decrypt($id);
            $infografis = Infografis::with('images')->findOrFail($decryptedId);
            $pop = Infografis::where('id', '!=', $decryptedId)->orderBy('created_at', 'desc')->limit(4)->get();
            return view('guest.detail-infografis', compact('infografis', 'pop'));
        } catch (\Throwable $th) {
            return redirect()->route('guest.infografis')->with('error', 'Data infografis tidak ditemukan');
        }
    }

    public function publikasi(Request $request)
    {
        $query = PublikasiGuest::orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $publikasi = $query->paginate(12);
        $tahuns = MasterTahun::orderBy('tahun', 'desc')->get();
        $jadwalTerbit = JadwalTerbit::orderBy('rencana_terbit', 'desc')->get();

        return view('guest.publikasi', compact('publikasi', 'tahuns', 'jadwalTerbit'));
    }

    public function publikasi_detail($id)
    {
        try {
            $decryptedId = is_numeric($id) ? $id : decrypt($id);
            $publikasi = PublikasiGuest::findOrFail($decryptedId);
            $pop = PublikasiGuest::where('id', '!=', $decryptedId)->orderBy('created_at', 'desc')->limit(4)->get();
            return view('guest.detail-publikasi', compact('publikasi', 'pop'));
        } catch (\Throwable $th) {
            return redirect()->route('guest.publikasi')->with('error', 'Publikasi tidak ditemukan');
        }
    }

    public function regulasi(Request $request)
    {
        $query = Regulasi::orderBy('tahun', 'desc')->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('nomor', 'like', "%{$search}%")
                  ->orWhere('tentang', 'like', "%{$search}%");
            });
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $regulasi = $query->paginate(15);
        $kategoris = Regulasi::select('kategori')->distinct()->pluck('kategori');
        $tahuns = Regulasi::select('tahun')->whereNotNull('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('guest.regulasi', compact('regulasi', 'kategoris', 'tahuns'));
    }

    public function katalogData(Request $request)
    {
        $query = Data::with(['opd', 'status', 'standar', 'sumberData', 'kegiatan', 'variabel', 'indikator'])
            ->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_data', 'like', "%{$search}%")
                  ->orWhereHas('opd', function($qOpd) use ($search) {
                      $qOpd->where('nama_opd', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('opd_id')) {
            $query->where('opd_id', $request->opd_id);
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->filled('sumber_referensi')) {
            $query->where('sumber_referensi', $request->sumber_referensi);
        }

        $katalog = $query->paginate(15);
        $tahuns = MasterTahun::orderBy('tahun', 'desc')->get();
        $opds = Opd::whereNotIn('nama_opd', ['Administrator', 'Adminstrator', 'TATI'])->orderBy('nama_opd', 'asc')->get();
        $sumberReferensiList = ['RPJMD', 'Renstra', 'SDSN BPS', 'IP Bappenas', 'Lainnya'];

        return view('guest.katalog-data', compact('katalog', 'tahuns', 'opds', 'sumberReferensiList'));
    }

    public function kodeReferensi(Request $request)
    {
        $tab = $request->get('tab', 'wilayah');
        $search = $request->get('search', '');

        // 1. Wilayah Kecamatan & Desa di Kab Madiun (City Code: 3519)
        $districtsQuery = DB::table('indonesia_districts')
            ->where('city_code', '3519')
            ->orderBy('code', 'asc');

        if ($search && $tab === 'wilayah') {
            $districtsQuery->where('name', 'like', "%{$search}%");
        }
        $districts = $districtsQuery->get();

        $villagesQuery = DB::table('indonesia_villages')
            ->join('indonesia_districts', 'indonesia_villages.district_code', '=', 'indonesia_districts.code')
            ->where('indonesia_districts.city_code', '3519')
            ->select('indonesia_villages.*', 'indonesia_districts.name as district_name')
            ->orderBy('indonesia_villages.code', 'asc');

        if ($search && $tab === 'desa') {
            $villagesQuery->where(function($q) use ($search) {
                $q->where('indonesia_villages.name', 'like', "%{$search}%")
                  ->orWhere('indonesia_districts.name', 'like', "%{$search}%")
                  ->orWhere('indonesia_villages.code', 'like', "%{$search}%");
            });
        }
        $villages = $villagesQuery->paginate(20, ['*'], 'village_page');

        // 2. Puskesmas List
        $puskesmas = [
            ['kode' => 'P3519010101', 'nama' => 'Puskesmas Kebonsari', 'kecamatan' => 'Kebonsari', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Kebonsari No. 12'],
            ['kode' => 'P3519020101', 'nama' => 'Puskesmas Dolopo', 'kecamatan' => 'Dolopo', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Dolopo No. 45'],
            ['kode' => 'P3519020102', 'nama' => 'Puskesmas Bangunsari', 'kecamatan' => 'Dolopo', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Desa Bangunsari'],
            ['kode' => 'P3519030101', 'nama' => 'Puskesmas Geger', 'kecamatan' => 'Geger', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Ponorogo-Madiun Km 9'],
            ['kode' => 'P3519030102', 'nama' => 'Puskesmas Kaibon', 'kecamatan' => 'Geger', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Desa Kaibon'],
            ['kode' => 'P3519040101', 'nama' => 'Puskesmas Dagangan', 'kecamatan' => 'Dagangan', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Dagangan'],
            ['kode' => 'P3519050101', 'nama' => 'Puskesmas Kare', 'kecamatan' => 'Kare', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Kare No. 5'],
            ['kode' => 'P3519060101', 'nama' => 'Puskesmas Gemarang', 'kecamatan' => 'Gemarang', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Gemarang No. 10'],
            ['kode' => 'P3519070101', 'nama' => 'Puskesmas Wungu', 'kecamatan' => 'Wungu', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Mojopurno No. 2'],
            ['kode' => 'P3519070102', 'nama' => 'Puskesmas Kresek', 'kecamatan' => 'Wungu', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Desa Kresek'],
            ['kode' => 'P3519080101', 'nama' => 'Puskesmas Madiun', 'kecamatan' => 'Madiun', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Nglames No. 34'],
            ['kode' => 'P3519080102', 'nama' => 'Puskesmas Dimong', 'kecamatan' => 'Madiun', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Desa Dimong'],
            ['kode' => 'P3519090101', 'nama' => 'Puskesmas Jiwan', 'kecamatan' => 'Jiwan', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Solo No. 78'],
            ['kode' => 'P3519100101', 'nama' => 'Puskesmas Balerejo', 'kecamatan' => 'Balerejo', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Madiun-Surabaya'],
            ['kode' => 'P3519100102', 'nama' => 'Puskesmas Simo', 'kecamatan' => 'Balerejo', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Desa Simo'],
            ['kode' => 'P3519110101', 'nama' => 'Puskesmas Mejayan', 'kecamatan' => 'Mejayan', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Panglima Sudirman Caruban'],
            ['kode' => 'P3519110102', 'nama' => 'Puskesmas Klecorejo', 'kecamatan' => 'Mejayan', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Desa Klecorejo'],
            ['kode' => 'P3519120101', 'nama' => 'Puskesmas Saradan', 'kecamatan' => 'Saradan', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Saradan No. 15'],
            ['kode' => 'P3519120102', 'nama' => 'Puskesmas Sumbersari', 'kecamatan' => 'Saradan', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Desa Sumbersari'],
            ['kode' => 'P3519130101', 'nama' => 'Puskesmas Pilangkenceng', 'kecamatan' => 'Pilangkenceng', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Kenep No. 8'],
            ['kode' => 'P3519140101', 'nama' => 'Puskesmas Sawahan', 'kecamatan' => 'Sawahan', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Sawahan No. 22'],
            ['kode' => 'P3519150101', 'nama' => 'Puskesmas Wonoasri', 'kecamatan' => 'Wonoasri', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Buduran No. 1'],
        ];

        if ($search && $tab === 'puskesmas') {
            $puskesmas = array_values(array_filter($puskesmas, function($item) use ($search) {
                return stripos($item['nama'], $search) !== false ||
                       stripos($item['kode'], $search) !== false ||
                       stripos($item['kecamatan'], $search) !== false;
            }));
        }

        return view('guest.kode-referensi', compact('districts', 'villages', 'puskesmas', 'tab', 'search'));
    }

    public function geoportal()
    {
        return view('guest.geoportal');
    }

    public function apiDatasetList(Request $request)
    {
        $query = Data::with(['opd:id,nama_opd', 'standar', 'sumberData:id,sumber_data'])
            ->where('status_id', Data::STATUS_TERPUBLIKASI)
            ->select('id', 'nama_data', 'tahun', 'opd_id', 'sumber_referensi', 'level_data', 'periode_data', 'created_at', 'updated_at');

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('sumber_referensi')) {
            $query->where('sumber_referensi', $request->sumber_referensi);
        }
        if ($request->filled('search')) {
            $query->where('nama_data', 'like', '%' . $request->search . '%');
        }

        $datasets = $query->paginate($request->get('limit', 15));

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Daftar Dataset Terpublikasi Kabupaten Madiun',
            'data' => $datasets->items(),
            'pagination' => [
                'total' => $datasets->total(),
                'current_page' => $datasets->currentPage(),
                'last_page' => $datasets->lastPage(),
                'per_page' => $datasets->perPage(),
            ],
            'endpoint_detail' => url('/api/v1/datasets/{id}'),
        ]);
    }

    public function apiDatasetDetail($id)
    {
        $data = Data::with([
            'opd',
            'standar',
            'sumberData',
            'kegiatan',
            'variabel',
            'indikator',
            'berkas',
            'visualtable.header',
            'visualtable.isi'
        ])->find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Dataset tidak ditemukan',
            ], 404);
        }

        $tables = [];
        if ($data->visualtable) {
            foreach ($data->visualtable as $vTable) {
                $headers = $vTable->header ? $vTable->header->pluck('header')->toArray() : [];
                $isis = $vTable->isi ? $vTable->isi->groupBy('urutan_kebawah')->map(function($row) {
                    return $row->pluck('isi')->toArray();
                })->values()->toArray() : [];

                $tables[] = [
                    'table_id' => $vTable->id,
                    'table_name' => $vTable->nama_table,
                    'headers' => $headers,
                    'rows' => $isis,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => [
                'id' => $data->id,
                'nama_data' => $data->nama_data,
                'tahun' => $data->tahun,
                'sumber_referensi' => $data->sumber_referensi,
                'level_data' => $data->level_data,
                'periode_data' => $data->periode_data,
                'opd' => $data->opd ? $data->opd->nama_opd : null,
                'standar_data' => $data->standar ? [
                    'konsep' => $data->standar->konsep,
                    'definisi' => $data->standar->definisi,
                    'klasifikasi' => $data->standar->klasifikasi,
                    'satuan' => $data->standar->satuan,
                    'ukuran' => $data->standar->ukuran,
                    'kode_referensi_bappenas' => $data->standar->kode_referensi_bappenas,
                    'kode_referensi_bps' => $data->standar->kode_referensi_bps,
                ] : null,
                'metadata' => [
                    'kegiatan' => $data->kegiatan,
                    'variabel' => $data->variabel,
                    'indikator' => $data->indikator,
                ],
                'tables' => $tables,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
            ]
        ]);
    }

    public function data(Request $request)
    {
        $limit = 15;
        $page = max(intval($request->get('page', 0)), 1);
        $start = $page > 1 ? ($page * $limit) - $limit : 0;
        $searchQuery = [
            'rows' => $limit,
            'start' => $start,
            'q' => $request->get('q'),
            'sort' => $request->get('sort', 'score desc, metadata_modified desc'),
            'include_private' => false
        ];

        $searchQuery['fq'] = [];
        if ($request->filled('group')) {
            $searchQuery['fq'][] = 'groups:' . $request->get('group');
        }
        if ($request->filled('org')) {
            $searchQuery['fq'][] = 'organization:' . $request->get('org');
        }
        if ($request->filled('tag')) {
            $searchQuery['fq'][] = 'tags:' . $request->get('tag');
        }
        $searchQuery['fq'] = implode(' AND ', $searchQuery['fq']);

        try {
            $ckanData = CkanApi::dataset()->all($searchQuery);
            $orgs = CkanApi::organization()->all(['limit' => 1000]);
            $groups = CkanApi::group()->all(['limit' => 1000]);

            $result = $ckanData['result'] ?? [];
            $data = ($ckanData && isset($ckanData['success']) && $ckanData['success']) ? ($ckanData['result']['results'] ?? []) : [];
            $orgs = ($orgs && isset($orgs['success']) && $orgs['success']) ? ($orgs['result'] ?? []) : [];
            $groups = ($groups && isset($groups['success']) && $groups['success']) ? ($groups['result'] ?? []) : [];
        } catch (\Throwable $e) {
            // Graceful fallback to local database
            $localQuery = Data::with('opd')->where('status_id', Data::STATUS_TERPUBLIKASI);
            if ($request->filled('q')) {
                $localQuery->where('nama_data', 'like', '%' . $request->get('q') . '%');
            }
            $totalCount = $localQuery->count();
            $localItems = $localQuery->skip($start)->take($limit)->get();
            
            $data = $localItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->nama_data,
                    'notes' => $item->standar ? $item->standar->definisi : 'Data Sektoral Pemkab Madiun',
                    'metadata_created' => $item->created_at ? $item->created_at->toIso8601String() : now()->toIso8601String(),
                    'organization' => $item->opd ? ['name' => $item->opd->nama_opd, 'title' => $item->opd->nama_opd] : ['name' => 'madiun', 'title' => 'Pemkab Madiun'],
                    'resources' => [],
                    'num_resources' => 0,
                    'groups' => [],
                ];
            })->toArray();

            $result = ['count' => $totalCount];
            $orgs = Opd::whereNotIn('nama_opd', ['Administrator', 'Adminstrator', 'TATI'])->get()->map(fn($o) => ['name' => $o->nama_opd, 'title' => $o->nama_opd])->toArray();
            $groups = [];
        }

        $pages = ceil(($result['count'] ?? 0) / $limit);
        $hasPrevPage = $page > 1;
        $hasNextPage = $page < $pages;

        return view('guest.data', compact('data', 'groups', 'orgs', 'pages', 'page', 'hasPrevPage', 'hasNextPage'));
    }

    public function showDataset($name)
    {
        try {
            $data = CkanApi::dataset()->show($name);
            $dataset = $data['result'] ?? null;
        } catch (\Throwable $e) {
            $dataset = null;
        }

        if (!$dataset) {
            $localData = Data::where('nama_data', $name)->orWhere('id', $name)->first();
            if ($localData) {
                $dataset = [
                    'id' => $localData->id,
                    'title' => $localData->nama_data,
                    'notes' => $localData->standar ? $localData->standar->definisi : '',
                    'metadata_created' => $localData->created_at,
                    'metadata_modified' => $localData->updated_at,
                    'resources' => [],
                    'organization' => ['title' => $localData->opd ? $localData->opd->nama_opd : 'Kabupaten Madiun']
                ];
            } else {
                return redirect()->route('dataset')->with('error', 'Dataset tidak ditemukan');
            }
        }

        $existingData = VisualData::where('id_data', $dataset['id'] ?? 0)->get();
        $rawTables = VisualTable::with(['header', 'isi'])->where('id_data', $dataset['id'] ?? 0)->get();
        $tables = [];
        foreach ($rawTables as $rTable) {
            $tables[] = [
                'table' => $rTable,
                'headers' => $rTable->header ?: collect(),
                'rows' => $rTable->isi ? $rTable->isi->groupBy('urutan_kebawah') : collect(),
            ];
        }

        $getmeta = Data::with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'visualtable.header', 'visualtable.isi'])
            ->where('nama_data', $dataset['title'])
            ->latest()
            ->first();

        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $axis_x = '[]';
        $axis_y = '[]';
        $kategori = '[]';
        $seriesData = [];
        $seriesDataLine = [];
        $axis_y_name = '';

        return view('guest.detail-dataset', compact('dataset', 'existingData', 'axis_x', 'axis_y', 'kategori', 'seriesData', 'tables', 'axis_y_name', 'getmeta', 'opd', 'seriesDataLine'));
    }

    public function downloadFileCount(Request $request)
    {
        $urlDownload = $request->input('url_download');
        $fileDownload = FileDownloadCount::where('file_name', $urlDownload)->first();

        if ($fileDownload) {
            $fileDownload->increment('download_count');
            return response()->json(['download_count' => $fileDownload->download_count]);
        } else {
            $fileDownload = FileDownloadCount::create([
                'file_name' => $urlDownload,
                'download_count' => 1,
            ]);
            return response()->json(['download_count' => $fileDownload->download_count]);
        }
    }

    public function detail($name, Request $request)
    {
        abort_unless($request->wantsJson(), 400, 'Bad Request');

        $data = Cache::remember('data:detail:' . $name, 60, fn() => CkanApi::dataset()->show($name));
        if (!$data || isset($data['error'])) {
            Cache::forget('data:detail:' . $name);
            return response()->json(['error' => 'Dataset tidak ditemukan'], 404);
        }

        $data = $data['result'];
        $resources = [];
        foreach ($data['resources'] as $resource) {
            if ($resource['state'] !== 'active') continue;

            $resources[] = [
                'id' => $resource['id'],
                'name' => $resource['name'],
                'description' => $resource['description'],
                'created' => !empty($resource['created']) ? Carbon::parse($resource['created'])->translatedFormat('d F Y') : '-',
                'format' => $resource['format'],
                'url_download' => $resource['url'],
                'url_preview' => rtrim(config('ckan_api.url'), '/') . '/dataset/' . $data['url'] . '/resource/' . $resource['id'],
                'size' => $resource['size']
            ];
        }

        return response()->json([
            'title' => $data['title'],
            'description' => $data['notes'],
            'created' => !empty($data['metadata_created']) ? Carbon::parse($data['metadata_created'])->translatedFormat('d F Y') : '-',
            'modified' => !empty($data['metadata_modified']) ? Carbon::parse($data['metadata_modified'])->translatedFormat('d F Y') : '-',
            'link' => rtrim(config('ckan_api.url'), '/') . '/dataset/' . $data['url'],
            'organization' => !empty($data['organization']) ? [
                'title' => $data['organization']['title'],
                'link' => rtrim(config('ckan_api.url'), '/') . '/organization/' . $data['organization']['name'],
                'image' => $data['organization']['image_url'] ?: null
            ] : [],
            'resources' => $resources
        ]);
    }

    public function send_usulan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pekerjaan' => 'required|string|max:255',
            'kelamin' => 'required|string|max:255',
            'no_hp' => 'required|string',
            'usulan' => 'required|string',
            'captcha' => 'required|captcha'
        ]);

        UsulanData::create([
            'nama' => $request->input('nama'),
            'email' => $request->input('email'),
            'pekerjaan' => $request->input('pekerjaan'),
            'kelamin' => $request->input('kelamin'),
            'no_wa' => $request->input('no_hp'),
            'usulan' => $request->input('usulan'),
            'tahun' => date('Y'),
        ]);

        return redirect()->back()->with('success', 'Usulan berhasil dikirim.');
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }

    public function berita()
    {
        return view('portal.landingpage.berita');
    }
}
