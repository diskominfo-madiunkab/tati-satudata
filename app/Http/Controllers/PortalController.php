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
use App\Models\VisualHeader;
use App\Models\VisualIsi;
use App\Models\Visualisasi;
use App\Models\VisualTable;
use App\Models\Wilayah;
use App\Services\CkanApi\Facades\CkanApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

    public function visualisasi(Request $request)
    {
        $query = Visualisasi::orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }
        $visualisasis = $query->paginate(9);
        return view('guest.visualisasi', compact('visualisasis'));
    }

    public function visualisasi_detail($id)
    {
        try {
            $decryptedId = is_numeric($id) ? $id : decrypt($id);
            $visualisasi = Visualisasi::findOrFail($decryptedId);
            $pop = Visualisasi::where('id', '!=', $decryptedId)->orderBy('created_at', 'desc')->limit(4)->get();
            return view('guest.detail-visualisasi', compact('visualisasi', 'pop'));
        } catch (\Throwable $th) {
            return redirect()->route('guest.visualisasi')->with('error', 'Visualisasi tidak ditemukan');
        }
    }

    public function publikasi(Request $request)
    {
        $query = PublikasiGuest::with('opd')->where(function($q) {
            $q->where('status', 'Terbit')
              ->orWhereNull('status');
        })->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $publikasi = $query->paginate(12);
        $tahuns = MasterTahun::orderBy('tahun', 'desc')->get();

        // Data publikasi yang berstatus Belum Terbit untuk tab Jadwal Rencana Terbit
        $rencanaPublikasi = PublikasiGuest::with('opd')
            ->where('status', 'Belum Terbit')
            ->orderBy('created_at', 'desc')
            ->get();
        $jadwalTerbit = JadwalTerbit::orderBy('rencana_terbit', 'desc')->get();

        return view('guest.publikasi', compact('publikasi', 'tahuns', 'jadwalTerbit', 'rencanaPublikasi'));
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
            ->orderByDesc('tahun')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

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

        // 2. Puskesmas List (Standar KMK HK.01.07/MENKES/2099/2023 - 26 Puskesmas Kab. Madiun)
        $puskesmas = [
            ['kode' => '35190200001', 'nama' => 'Puskesmas Gantrung', 'kecamatan' => 'Kebonsari', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. P Diponegoro No 311, Ds. Mojorejo, Kec Kebonsari'],
            ['kode' => '35190200002', 'nama' => 'Puskesmas Kebonsari', 'kecamatan' => 'Kebonsari', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Husni Thamrin RT 008/RW 001, Ds. Balerejo Kec. Kebonsari'],
            ['kode' => '35190200003', 'nama' => 'Puskesmas Geger', 'kecamatan' => 'Geger', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Raya Ponorogo No. 48, Ds. Purworejo, Kec Geger'],
            ['kode' => '35190200004', 'nama' => 'Puskesmas Kaibon', 'kecamatan' => 'Geger', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Pancotaruno No. 407, Ds. Kaibon, Kec. Geger'],
            ['kode' => '35190200005', 'nama' => 'Puskesmas Mlilir', 'kecamatan' => 'Dolopo', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Raya Madiun Ponorogo Km 19 Kel. Mlilir Kec. Dolopo'],
            ['kode' => '35190200006', 'nama' => 'Puskesmas Bangunsari', 'kecamatan' => 'Dolopo', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Panjang Punjung Kel. Bangunsari Kec. Dolopo'],
            ['kode' => '35190200007', 'nama' => 'Puskesmas Dagangan', 'kecamatan' => 'Dagangan', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Dagangan Pagotan No. 57 Ds. Dagangan, Kec. Dagangan'],
            ['kode' => '35190200008', 'nama' => 'Puskesmas Jetis', 'kecamatan' => 'Dagangan', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Jetis, Ds. Jetis, Kec. Dagangan'],
            ['kode' => '35190200009', 'nama' => 'Puskesmas Wungu', 'kecamatan' => 'Wungu', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Raya Kare No. 113, Ds. Wungu, Kec. Wungu'],
            ['kode' => '35190200010', 'nama' => 'Puskesmas Mojopurno', 'kecamatan' => 'Wungu', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Raya Dungus, Ds. Mojopurno, Kec. Wungu'],
            ['kode' => '35190200011', 'nama' => 'Puskesmas Kare', 'kecamatan' => 'Kare', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl Raya Randualas Kare, RT 02 RW 01 Ds.Kare, Kec.Kare'],
            ['kode' => '35190200012', 'nama' => 'Puskesmas Gemarang', 'kecamatan' => 'Gemarang', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Tentara Genie Pelajar No 17, Ds. Gemarang, Kec. Gemarang'],
            ['kode' => '35190200013', 'nama' => 'Puskesmas Saradan', 'kecamatan' => 'Saradan', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Saradan – Madiun Ds. Sugihwaras Kec. Saradan'],
            ['kode' => '35190200014', 'nama' => 'Puskesmas Sumbersari', 'kecamatan' => 'Saradan', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Tulung No. 05, Ds. Sumbersari, Kec. Saradan'],
            ['kode' => '35190200015', 'nama' => 'Puskesmas Pilangkenceng', 'kecamatan' => 'Pilangkenceng', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Kenongorejo No. 774 Ds. Kenongorejo Kec. Pilangkenceng'],
            ['kode' => '35190200016', 'nama' => 'Puskesmas Krebet', 'kecamatan' => 'Pilangkenceng', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Gawang Utara No. 55 Ds. Krebet Kec. Pilangkenceng'],
            ['kode' => '35190200017', 'nama' => 'Puskesmas Klecorejo', 'kecamatan' => 'Mejayan', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Wates, Ds. Klecorejo, Kec. Mejayan'],
            ['kode' => '35190200018', 'nama' => 'Puskesmas Mejayan', 'kecamatan' => 'Mejayan', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Panglima Sudirman No. 52, Ds. Mejayan, Kec. Mejayan'],
            ['kode' => '35190200019', 'nama' => 'Puskesmas Wonoasri', 'kecamatan' => 'Wonoasri', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Raya Wonoasri, Ds. Wonoasri, Kec. Wonoasri'],
            ['kode' => '35190200020', 'nama' => 'Puskesmas Balerejo', 'kecamatan' => 'Balerejo', 'tipe' => 'Rawat Inap', 'alamat' => 'Jl. Raya Madiun Surabaya No. 82, Ds. Balerejo, Kec. Balerejo'],
            ['kode' => '35190200021', 'nama' => 'Puskesmas Simo', 'kecamatan' => 'Balerejo', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Raya Balerejo-Muneng No.96, Ds. Simo, Kec. Balerejo'],
            ['kode' => '35190200022', 'nama' => 'Puskesmas Madiun', 'kecamatan' => 'Madiun', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Raya Puskesmas No.9, Ds. Tiron, Kec. Madiun'],
            ['kode' => '35190200023', 'nama' => 'Puskesmas Dimong', 'kecamatan' => 'Madiun', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Raya Dimong, Ds. Dimong, Kec. Madiun'],
            ['kode' => '35190200024', 'nama' => 'Puskesmas Sawahan', 'kecamatan' => 'Sawahan', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Raya Kajang No. 31 Ds. Kajang Kec. Sawahan'],
            ['kode' => '35190200025', 'nama' => 'Puskesmas Klagenserut', 'kecamatan' => 'Jiwan', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Raya Klagenserut Rt 08 Rw 03 Kec. Jiwan'],
            ['kode' => '35190200026', 'nama' => 'Puskesmas Jiwan', 'kecamatan' => 'Jiwan', 'tipe' => 'Non Rawat Inap', 'alamat' => 'Jl. Raya Solo No.85, Ds. Jiwan, Kec. Jiwan'],
        ];

        if ($search && $tab === 'puskesmas') {
            $puskesmas = array_values(array_filter($puskesmas, function($item) use ($search) {
                return stripos($item['nama'], $search) !== false ||
                       stripos($item['kode'], $search) !== false ||
                       stripos($item['kecamatan'], $search) !== false ||
                       stripos($item['alamat'], $search) !== false;
            }));
        }

        // 3. Live SDSN BPS API Fetching with token
        $sdsnData = [];
        $sdsnTotal = 0;
        $sdsnPage = max(1, intval($request->get('sdsn_page', 1)));
        $sdsnLastPage = 1;
        $sdsnToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMzMyNzc0MzAwZjczMjdkZjU0NjE0YzU1YzVmNTVhOTlhMjdmNjFiZTIwM2M2MDBiNjk3ZWZjY2U2NTVhZmE0YjgwNmUzMWVmZGIxZmQ3MjMiLCJpYXQiOjE3NjI0ODYxMDYuMDk4MTM4LCJuYmYiOjE3NjI0ODYxMDYuMDk4MTQyLCJleHAiOjE3OTQwMjIxMDYuMDcwNDM2LCJzdWIiOiIzOTgiLCJzY29wZXMiOltdfQ.elTIU65UH0JBp-C3uwm0yCYcZ2W_EJGhhcsMimYu9QWEXEjiqUqFbhe1c9Km21OK9zYMbfAarIRhvrDGruPMBiyKUidaMiwY6zgUky9tTwdv3NE7iXvzXbMiTeTFnZm1zJYj_8JGeaD-ScrJWQtX-5_g9gtYZO9TVViK5PDv7XEWhitDVhM0sRpIKcFZYe4AGSN9qnBkjKgXO4yiqXLdZb2kqHvGsTSMPWTtQ44DK8atRYw7KZWjwsYxhtH9tFSNv_4GjpHgpenqEggZCZd1XreY_S-5U8MfctoOV1CD-llQ5BkOBxJ-znKZwSTAWbiHF13RvkLhib5s_bunqSIe2A';

        if ($tab === 'sdsn') {
            $cacheKey = 'sdsn_bps_page_' . $sdsnPage . '_' . md5($search);
            $sdsnResponse = Cache::remember($cacheKey, 1800, function() use ($sdsnToken, $sdsnPage, $search) {
                try {
                    $response = Http::withToken($sdsnToken)
                        ->timeout(3)
                        ->get('https://dna.web.bps.go.id/api/sdsn/search', [
                            'length' => 15,
                            'page' => $sdsnPage,
                            'search' => $search ?: ''
                        ]);
                    if ($response->successful()) {
                        return $response->json();
                    }
                } catch (\Throwable $e) {
                    // Fail silently to local fallback
                }
                return null;
            });

            if ($sdsnResponse && isset($sdsnResponse['result']['data'])) {
                $sdsnData = $sdsnResponse['result']['data'];
                $sdsnTotal = $sdsnResponse['result']['total_items'] ?? count($sdsnData);
                $sdsnLastPage = $sdsnResponse['result']['last_page'] ?? ceil($sdsnTotal / 15);
            } else {
                // Fallback local standard data if API unavailable
                $localStandar = DB::table('standar_data')
                    ->leftJoin('data', 'standar_data.data_id', '=', 'data.id')
                    ->select('standar_data.*', 'data.nama_data');
                if ($search) {
                    $localStandar->where(function($q) use ($search) {
                        $q->where('data.nama_data', 'like', "%{$search}%")
                          ->orWhere('standar_data.konsep', 'like', "%{$search}%")
                          ->orWhere('standar_data.definisi', 'like', "%{$search}%");
                    });
                }
                $localData = $localStandar->get();
                $sdsnTotal = count($localData);
                $sdsnData = $localData->slice(($sdsnPage - 1) * 15, 15)->map(function($st) {
                    return [
                        'kode_sds' => $st->kode_referensi_bps ?? $st->kode_referensi_bappenas ?? ('SDS-3519-' . str_pad($st->id, 3, '0', STR_PAD_LEFT)),
                        'nama_data' => $st->nama_data ?? 'Standar Data Statistik Daerah',
                        'konsep' => $st->konsep ?? 'Statistik Sektoral',
                        'definisi' => $st->definisi ?? 'Definisi Standar Data SDI',
                        'ukuran' => $st->ukuran ?? 'Jumlah / Nilai',
                        'satuan' => $st->satuan ?? 'Satuan Unit',
                    ];
                })->values()->all();
                $sdsnLastPage = max(1, ceil($sdsnTotal / 15));
            }
        }

        return view('guest.kode-referensi', compact('districts', 'villages', 'puskesmas', 'tab', 'search', 'sdsnData', 'sdsnTotal', 'sdsnPage', 'sdsnLastPage'));
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
            'sort' => $request->get('sort', 'metadata_modified desc, metadata_created desc'),
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

        $data = [];
        $totalCount = 0;
        $orgs = [];
        $groups = [];

        try {
            $ckanData = CkanApi::dataset()->all($searchQuery);
            $orgs = CkanApi::organization()->all(['limit' => 1000]);
            $groups = CkanApi::group()->all(['limit' => 1000]);

            $result = $ckanData['result'] ?? [];
            $data = ($ckanData && isset($ckanData['success']) && $ckanData['success']) ? ($ckanData['result']['results'] ?? []) : [];
            $totalCount = $ckanData['result']['count'] ?? count($data);
            $orgs = ($orgs && isset($orgs['success']) && $orgs['success']) ? ($orgs['result'] ?? []) : [];
            $groups = ($groups && isset($groups['success']) && $groups['success']) ? ($groups['result'] ?? []) : [];
        } catch (\Throwable $e) {
            $data = [];
        }

        // Fallback ke database lokal jika CKAN kosong atau tidak terhubung
        if (empty($data)) {
            $localQuery = Data::with(['opd', 'standar', 'berkas'])->where('status_id', Data::STATUS_TERPUBLIKASI);
            if ($request->filled('q')) {
                $localQuery->where('nama_data', 'like', '%' . $request->get('q') . '%');
            }
            if ($request->filled('org')) {
                $localQuery->whereHas('opd', function($q) use ($request) {
                    $q->where('nama_opd', $request->get('org'));
                });
            }
            if ($request->filled('group')) {
                $localQuery->where('group_id', $request->get('group'));
            }
            if ($request->filled('sumber')) {
                $localQuery->where(function($q) use ($request) {
                    $q->where('sumber_referensi', 'like', '%' . $request->get('sumber') . '%')
                      ->orWhere('sumber_data', 'like', '%' . $request->get('sumber') . '%');
                });
            }
            $sort = $request->get('sort', 'newest');
            if ($sort === 'title_string asc') {
                $localQuery->orderBy('nama_data', 'asc');
            } elseif ($sort === 'title_string desc') {
                $localQuery->orderBy('nama_data', 'desc');
            } elseif ($sort === 'oldest') {
                $localQuery->orderBy('tahun', 'asc')->orderBy('created_at', 'asc')->orderBy('id', 'asc');
            } else {
                $localQuery->orderByDesc('tahun')->orderByDesc('created_at')->orderByDesc('id');
            }

            $totalCount = $localQuery->count();
            $localItems = $localQuery->skip($start)->take($limit)->get();

            $data = $localItems->map(function($item) {
                $resources = [];
                if ($item->berkas && $item->berkas->isNotEmpty()) {
                    foreach ($item->berkas as $b) {
                        $ext = strtoupper(pathinfo($b->name, PATHINFO_EXTENSION) ?: 'XLSX');
                        $resources[] = ['format' => $ext];
                    }
                }
                return [
                    'id' => $item->id,
                    'title' => $item->nama_data,
                    'notes' => $item->standar ? $item->standar->definisi : ($item->definisi ?: 'Data Sektoral Pemkab Madiun'),
                    'metadata_created' => $item->created_at ? $item->created_at->toIso8601String() : now()->toIso8601String(),
                    'tahun' => $item->tahun ?: ($item->created_at ? $item->created_at->format('Y') : date('Y')),
                    'organization' => $item->opd ? ['name' => $item->opd->nama_opd, 'title' => $item->opd->nama_opd] : ['name' => 'madiun', 'title' => 'Pemkab Madiun'],
                    'resources' => $resources,
                    'num_resources' => $item->berkas ? $item->berkas->count() : 0,
                    'groups' => $item->group_id ? [['name' => $item->group_id, 'display_name' => $item->group_id]] : [],
                    'sumber_data' => $item->sumber_referensi ?: $item->sumber_data,
                ];
            })->toArray();
        }

        if (empty($orgs)) {
            $orgs = Opd::whereNotIn('nama_opd', ['Administrator', 'Adminstrator', 'TATI'])->orderBy('nama_opd')->get()->map(fn($o) => ['name' => $o->nama_opd, 'title' => $o->nama_opd])->toArray();
        }

        if (empty($groups)) {
            $defaultUrusan = [
                'Pendidikan', 'Kesehatan', 'Pekerjaan Umum dan Penataan Ruang', 'Perumahan Rakyat dan Kawasan Permukiman',
                'Ketenteraman, Ketertiban Umum, dan Pelindungan Masyarakat', 'Sosial', 'Tenaga Kerja', 'Pemberdayaan Perempuan dan Perlindungan Anak',
                'Pangan', 'Pertanahan', 'Lingkungan Hidup', 'Administrasi Kependudukan dan Pencatatan Sipil', 'Pemberdayaan Masyarakat dan Desa',
                'Pengendalian Penduduk dan Keluarga Berencana', 'Perhubungan', 'Komunikasi dan Informatika', 'Koperasi, Usaha Kecil, dan Menengah',
                'Penanaman Modal', 'Kepemudaan dan Olahraga', 'Statistik', 'Persandian', 'Kebudayaan', 'Perpustakaan', 'Kearsipan',
                'Kelautan dan Perikanan', 'Pariwisata', 'Pertanian', 'Kehutanan', 'Energi dan Sumber Daya Mineral', 'Perdagangan', 'Perindustrian', 'Transmigrasi'
            ];
            $groups = array_map(fn($u) => ['name' => $u, 'title' => $u, 'display_name' => $u], $defaultUrusan);
        }

        $sumberList = SumberData::where('is_active', 1)->pluck('sumber_data')->filter()->values()->toArray();
        if (empty($sumberList)) {
            $sumberList = Data::whereNotNull('sumber_data')->where('sumber_data', '!=', '')->distinct()->pluck('sumber_data')->take(20)->toArray();
        }

        $pages = ceil($totalCount / $limit);
        $hasPrevPage = $page > 1;
        $hasNextPage = $page < $pages;

        return view('guest.data', compact('data', 'groups', 'orgs', 'sumberList', 'totalCount', 'pages', 'page', 'hasPrevPage', 'hasNextPage'));
    }

    public function showDataset($name)
    {
        $dataset = null;
        try {
            $data = Cache::remember('data:detail:' . $name, 300, function() use ($name) {
                return CkanApi::dataset()->show($name);
            });
            $dataset = ($data && isset($data['success']) && $data['success']) ? ($data['result'] ?? null) : null;
        } catch (\Throwable $e) {
            $dataset = null;
        }

        $decodedName = urldecode($name);
        $localData = null;

        // 1. Jika numeric, cari berdasarkan ID lokal
        if (is_numeric($name)) {
            $localData = Data::with(['opd', 'status', 'standar', 'berkas', 'indikator', 'variabel', 'kegiatan', 'visualtable.header', 'visualtable.isi'])
                ->where('id', (int)$name)
                ->first();
        }

        // 2. Cari melalui tabel publikasi jika $name adalah CKAN dataset_id (UUID) atau slug
        if (!$localData) {
            $pubDataId = DB::table('publikasi')->where('dataset_id', $name)->orWhere('slug', $name)->value('data_id');
            if ($pubDataId) {
                $localData = Data::with(['opd', 'status', 'standar', 'berkas', 'indikator', 'variabel', 'kegiatan', 'visualtable.header', 'visualtable.isi'])
                    ->where('id', $pubDataId)
                    ->first();
            }
        }

        // 3. Jika dataset dari CKAN tersedia, cari data lokal berdasarkan judul / slug CKAN
        if (!$localData && !empty($dataset)) {
            $ckanTitle = $dataset['title'] ?? '';
            $ckanName = $dataset['name'] ?? '';
            if ($ckanTitle) {
                $localData = Data::with(['opd', 'status', 'standar', 'berkas', 'indikator', 'variabel', 'kegiatan', 'visualtable.header', 'visualtable.isi'])
                    ->where('nama_data', $ckanTitle)
                    ->orWhere('nama_data', 'like', '%' . $ckanTitle . '%')
                    ->first();
            }
            if (!$localData && $ckanName) {
                $localData = Data::with(['opd', 'status', 'standar', 'berkas', 'indikator', 'variabel', 'kegiatan', 'visualtable.header', 'visualtable.isi'])
                    ->where('nama_data', str_replace('-', ' ', $ckanName))
                    ->orWhere('nama_data', 'like', '%' . str_replace('-', ' ', $ckanName) . '%')
                    ->first();
            }
        }

        // 4. Jika masih belum ditemukan dan bukan UUID, cari berdasarkan string decodedName
        if (!$localData && !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $name)) {
            $cleanName = str_replace('-', ' ', $decodedName);
            $localData = Data::with(['opd', 'status', 'standar', 'berkas', 'indikator', 'variabel', 'kegiatan', 'visualtable.header', 'visualtable.isi'])
                ->where('nama_data', $decodedName)
                ->orWhere('nama_data', $name)
                ->orWhere('nama_data', 'like', '%' . $cleanName . '%')
                ->first();
        }

        if ($localData) {
            $localData->increment('views_count');
        }

        if (!$dataset) {
            if ($localData) {
                $resources = [];
                if ($localData->berkas && $localData->berkas->isNotEmpty()) {
                    foreach ($localData->berkas as $b) {
                        $ext = pathinfo($b->name, PATHINFO_EXTENSION);
                        $filePath = asset(str_replace('public/', 'storage/', $b->path));
                        $dlCount = FileDownloadCount::where('file_name', $filePath)
                            ->orWhere('file_name', 'like', '%' . basename($b->path) . '%')
                            ->orWhere('file_name', 'like', '%' . $b->name . '%')
                            ->value('download_count') ?? 0;
                        $resources[] = [
                            'name' => $b->name,
                            'description' => 'Berkas ' . $localData->nama_data . ($b->tahun ? ' (' . $b->tahun . ')' : ''),
                            'created' => $b->created_at ? $b->created_at->format('d/m/Y') : '-',
                            'format' => strtoupper($ext ?: 'XLSX'),
                            'url_download' => $filePath,
                            'url_preview' => $filePath,
                            'url' => $filePath,
                            'download_count' => $dlCount,
                            'tahun' => $b->tahun,
                        ];
                    }
                }

                $totalDownloads = max(intval($localData->downloads_count ?? 0), array_sum(array_column($resources, 'download_count')));

                $dataset = [
                    'id' => $localData->id,
                    'title' => $localData->nama_data,
                    'notes' => $localData->standar ? $localData->standar->definisi : ($localData->definisi ?? ''),
                    'description' => $localData->standar ? $localData->standar->definisi : ($localData->definisi ?? ''),
                    'metadata_created' => $localData->created_at ? $localData->created_at->format('d/m/Y') : '-',
                    'metadata_modified' => $localData->updated_at ? $localData->updated_at->format('d/m/Y') : '-',
                    'created' => $localData->created_at ? $localData->created_at->format('d/m/Y') : '-',
                    'modified' => $localData->updated_at ? $localData->updated_at->format('d/m/Y') : '-',
                    'views_count' => $localData->views_count ?? 1,
                    'downloads_count' => $totalDownloads,
                    'tahun' => $localData->tahun ?: ($localData->created_at ? $localData->created_at->format('Y') : date('Y')),
                    'group_id' => $localData->group_id,
                    'sumber_data' => $localData->sumber_referensi ?: $localData->sumber_data,
                    'resources' => $resources,
                    'organization' => [
                        'title' => $localData->opd ? $localData->opd->nama_opd : 'Kabupaten Madiun',
                        'link' => route('dataset', ['org' => $localData->opd ? $localData->opd->nama_opd : ''])
                    ]
                ];
            } else {
                return redirect()->route('dataset')->with('error', 'Dataset tidak ditemukan');
            }
        } elseif (!empty($dataset) && empty($dataset['resources']) && $localData && $localData->berkas && $localData->berkas->isNotEmpty()) {
            $resources = [];
            foreach ($localData->berkas as $b) {
                $ext = pathinfo($b->name, PATHINFO_EXTENSION);
                $filePath = asset(str_replace('public/', 'storage/', $b->path));
                $dlCount = FileDownloadCount::where('file_name', $filePath)
                    ->orWhere('file_name', 'like', '%' . basename($b->path) . '%')
                    ->orWhere('file_name', 'like', '%' . $b->name . '%')
                    ->value('download_count') ?? 0;
                $resources[] = [
                    'name' => $b->name,
                    'description' => 'Berkas ' . $localData->nama_data . ($b->tahun ? ' (' . $b->tahun . ')' : ''),
                    'created' => $b->created_at ? $b->created_at->format('d/m/Y') : '-',
                    'format' => strtoupper($ext ?: 'XLSX'),
                    'url_download' => $filePath,
                    'url_preview' => $filePath,
                    'url' => $filePath,
                    'download_count' => $dlCount,
                    'tahun' => $b->tahun,
                ];
            }
            $dataset['resources'] = $resources;
            $dataset['views_count'] = $localData->views_count ?? 1;
            $dataset['downloads_count'] = max(intval($localData->downloads_count ?? 0), array_sum(array_column($resources, 'download_count')));
            $dataset['tahun'] = $localData->tahun;
            $dataset['group_id'] = $localData->group_id;
            $dataset['sumber_data'] = $localData->sumber_referensi ?: $localData->sumber_data;
        }

        $targetDataId = $localData ? $localData->id : ($dataset['id'] ?? 0);
        $rawTables = VisualTable::with(['header', 'isi'])->where('id_data', $targetDataId)->get();
        if ($rawTables->isEmpty() && !empty($dataset['id']) && $dataset['id'] != $targetDataId) {
            $rawTables = VisualTable::with(['header', 'isi'])->where('id_data', $dataset['id'])->get();
        }
        $tables = [];
        foreach ($rawTables as $rTable) {
            $tables[] = [
                'table' => $rTable,
                'headers' => $rTable->header ?: collect(),
                'rows' => $rTable->isi ? $rTable->isi->groupBy('urutan_kebawah') : collect(),
            ];
        }

        $existingData = collect();
        $axis_x = '[]';
        $axis_y = '[]';
        $kategori = '[]';
        $seriesData = [];
        $seriesDataLine = [];
        $axis_y_name = '';

        if (!empty($targetDataId) && $rawTables->isNotEmpty()) {
            $cekGrafik = GrafikData::where('id_data', $targetDataId)->first();
            if (!$cekGrafik) {
                foreach ($rawTables as $rTable) {
                    if ($rTable->header && $rTable->header->isNotEmpty()) {
                        $hdrList = $rTable->header;
                        $katHdr = $hdrList->firstWhere('header', 'Tahun') ?: $hdrList->first();
                        $numHdr = $hdrList->where('id', '!=', $katHdr->id)->last() ?: $hdrList->last();
                        GrafikData::create([
                            'id_data' => $targetDataId,
                            'id_table' => $rTable->id,
                            'axis_x' => 0,
                            'axis_y' => $numHdr->id,
                            'kategori' => $katHdr->id,
                        ]);
                    }
                }
            }

            $existingData = GrafikData::where('id_data', $targetDataId)
                ->leftJoin('visual_headers as vh_kat', 'grafik_data.kategori', '=', 'vh_kat.id')
                ->leftJoin('visual_headers as vh_axis_x', 'grafik_data.axis_x', '=', 'vh_axis_x.id')
                ->leftJoin('visual_headers as vh_axis_y', 'grafik_data.axis_y', '=', 'vh_axis_y.id')
                ->select(
                    'grafik_data.*',
                    'vh_kat.header as header',
                    'vh_axis_x.header as axis_x_header',
                    'vh_axis_y.header as axis_y_header'
                )
                ->get();

            $axis_x_arr = [];
            $axis_y_arr = [];
            $kategori_arr = [];

            foreach ($existingData as $item) {
                $axis_y_name = $item->axis_y_header ?: 'Nilai';

                if ($item->header == 'Tahun') {
                    $kategori_arr = VisualIsi::where('id_header', $item->kategori)
                        ->orderBy('id_header')
                        ->pluck('isi')
                        ->unique()
                        ->values()
                        ->toArray();
                } else {
                    $kategori_arr = VisualIsi::where('id_header', $item->kategori)
                        ->orderBy('id_header')
                        ->pluck('isi')
                        ->toArray();
                }

                if ($item->axis_x && $item->axis_x != 0) {
                    $axis_x_arr = VisualIsi::where('id_header', $item->axis_x)
                        ->orderBy('id_header')
                        ->pluck('isi')
                        ->unique()
                        ->toArray();
                } else {
                    $axis_x_arr = [];
                }

                $rawAxisY = VisualIsi::where('id_header', $item->axis_y)
                    ->orderBy('id_header')
                    ->pluck('isi')
                    ->toArray();

                $axis_y_arr = array_map(function($v) {
                    $clean = trim(str_replace(',', '.', (string)$v));
                    return is_numeric($clean) ? (float)$clean : 0;
                }, $rawAxisY);

                foreach ($kategori_arr as $index => $name) {
                    if ($item->header == 'Tahun') {
                        $noUrut = VisualIsi::where('id_namatabel', $item->id_table)->pluck('urutan_kebawah')->unique()->toArray();
                        $noUrutTahun = VisualIsi::where('id_namatabel', $item->id_table)->whereIn('urutan_kebawah', $noUrut)->where('isi', $name)->pluck('urutan_kebawah');
                        $data1 = VisualIsi::where('id_namatabel', $item->id_table)->where('id_header', $item->axis_y)->whereIn('urutan_kebawah', $noUrutTahun)->pluck('isi')->toArray();
                        $cleanData1 = array_map(function($v) {
                            $clean = trim(str_replace(',', '.', (string)$v));
                            return is_numeric($clean) ? (float)$clean : 0;
                        }, $data1);

                        $seriesData[$item->id][] = [
                            'name' => $name,
                            'data' => $cleanData1,
                        ];
                    } else {
                        $val = $axis_y_arr[$index] ?? 0;
                        $seriesData[$item->id][] = [
                            'name' => $name,
                            'data' => [$val],
                        ];
                    }
                }

                if (!empty($axis_x_arr)) {
                    foreach ($axis_x_arr as $index => $name) {
                        if ($item->header == 'Tahun') {
                            $noUrut = VisualIsi::where('id_namatabel', $item->id_table)->pluck('urutan_kebawah')->unique()->toArray();
                            $noUrutTahun = VisualIsi::where('id_namatabel', $item->id_table)->whereIn('urutan_kebawah', $noUrut)->where('isi', $name)->pluck('urutan_kebawah');
                            $data1 = VisualIsi::where('id_namatabel', $item->id_table)->where('id_header', $item->axis_y)->whereIn('urutan_kebawah', $noUrutTahun)->pluck('isi')->toArray();
                            $cleanData1 = array_map(function($v) {
                                $clean = trim(str_replace(',', '.', (string)$v));
                                return is_numeric($clean) ? (float)$clean : 0;
                            }, $data1);

                            $seriesDataLine[$item->id][] = [
                                'name' => $name,
                                'data' => $cleanData1,
                            ];
                        } else {
                            $val = $axis_y_arr[$index] ?? 0;
                            $seriesDataLine[$item->id][] = [
                                'name' => $name,
                                'data' => [$val],
                            ];
                        }
                    }
                }
            }

            $kategori = json_encode($kategori_arr);
            $axis_y = json_encode($axis_y_arr);
            $axis_x = json_encode($axis_x_arr);
        }

        $getmeta = $localData ?: Data::with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'visualtable.header', 'visualtable.isi'])
            ->where('nama_data', $dataset['title'])
            ->latest()
            ->first();

        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();

        return view('guest.detail-dataset', compact('dataset', 'existingData', 'axis_x', 'axis_y', 'kategori', 'seriesData', 'tables', 'axis_y_name', 'getmeta', 'opd', 'seriesDataLine'));
    }

    public function downloadFileCount(Request $request)
    {
        $urlDownload = $request->input('url_download');
        $idData = $request->input('id_data');
        if ($idData) {
            Data::where('id', $idData)->increment('downloads_count');
        }

        $fileDownload = FileDownloadCount::where('file_name', $urlDownload)
            ->orWhere('file_name', 'like', '%' . basename($urlDownload) . '%')
            ->first();

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
