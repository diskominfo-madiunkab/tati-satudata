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
use App\Models\Visualisasi;
use App\Models\Wilayah;
use App\Services\CkanApi\Facades\CkanApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
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
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        $visualisasis = $query->paginate(9);

        // Fallback: If visualisasis is empty, check if any infografis has tableau
        if ($visualisasis->total() == 0) {
            $infografisTableau = Infografis::whereNotNull('tableau')->where('tableau', '!=', '')->orderBy('created_at', 'desc')->paginate(9);
            if ($infografisTableau->total() > 0) {
                $visualisasis = $infografisTableau;
            }
        }

        return view('guest.visualisasi', compact('visualisasis'));
    }

    public function visualisasi_detail($id)
    {
        try {
            $decryptedId = is_numeric($id) ? $id : decrypt($id);
            $visualisasi = Visualisasi::find($decryptedId);
            if (!$visualisasi) {
                $infografis = Infografis::findOrFail($decryptedId);
                $visualisasi = (object)[
                    'id' => $infografis->id,
                    'title' => $infografis->title,
                    'tableau_url' => $infografis->tableau,
                    'content' => $infografis->content,
                    'created_at' => $infografis->created_at,
                ];
            }
            return view('guest.detail-visualisasi', compact('visualisasi'));
        } catch (\Throwable $th) {
            return redirect()->route('guest.visualisasi')->with('error', 'Data visualisasi tidak ditemukan');
        }
    }

    public function publikasi(Request $request)
    {
        $query = PublikasiGuest::with('opd')->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%");
            });
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
            $publikasi = PublikasiGuest::with('opd')->findOrFail($decryptedId);
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

        // 2. Puskesmas List (26 Puskesmas Kabupaten Madiun - KMK Kemenkes No. HK.01.07/MENKES/2099/2023)
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
        $sdsnToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpaSI6MzMyNzc0MzAwZjczMjdkZjU0NjE0YzU1YmNTNTVhOTlhMjdmNjFiZTIwM2M2MDBiNjk3ZWZjY2U2NTYhZTIxMTYwMjcwNmUzMWVmZGIxZmQ3MjM1LCJpYXQiOjE3NjI0ODYxMDYuMDk4MTM4LCJuYmYiOjE3NjI0ODYxMDYuMDk4MTQyLCJleHAiOjE3OTQwMjE3MDYsInN1YiI6IjE0MDFkOTM4LTgzZTgtNDBmYi1iNzUxLTIwOTZhYmFhNDFhMSIsInNjb3BlcyI6W119.EJGhhcsMimYu9QWEXEjiqUqFbhe1c9Km21OK9zYMbfAarIRhvrDGRuPMBiyKUidaMiWY6zgUky9tTwdv3NE7iXvzXbMiTeTfNZm1zJYj_8JGeaD-ScrjWQtX-5_g9gtYZO9TVViK5PDv7XEWhitDVhM0sRpIKcFZYe4AGSN9qnBkjKgXO4yiQXLdZb2kqHvGsTSMPWTtQ44DK8atRYw7KZWjwsYxhth9tFSNv_4GjpHgpenqEggZCZd1XreY_S-5U8MfctoOV1CD-llQ5BkOBxJ-znKZwSTAWbiHF13RvkLhib5s_bunqSIe2A';

        if ($tab === 'sdsn') {
            $cacheKey = 'sdsn_bps_page_' . $sdsnPage . '_' . md5($search);
            $sdsnResponse = Cache::remember($cacheKey, 1800, function() use ($sdsnToken, $sdsnPage, $search) {
                try {
                    $response = Http::withToken($sdsnToken)
                        ->timeout(2)
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

            if ($sdsnResponse && isset($sdsnResponse['data'])) {
                $sdsnData = $sdsnResponse['data'];
                $sdsnTotal = $sdsnResponse['total'] ?? count($sdsnData);
            } else {
                // Fallback local standard data
                $localStandar = DB::table('standar_data')
                    ->leftJoin('data', 'standar_data.data_id', '=', 'data.id')
                    ->select('standar_data.*', 'data.nama_data')
                    ->get();
                $sdsnData = $localStandar->map(function($st) {
                    return [
                        'code' => $st->kode_referensi_bps ?? $st->kode_referensi_bappenas ?? ('SDS-3519-' . str_pad($st->id, 3, '0', STR_PAD_LEFT)),
                        'data_name' => $st->nama_data ?? 'Standar Data Statistik Daerah',
                        'concept' => $st->konsep ?? 'Statistik Sektoral',
                        'definition' => $st->definisi ?? 'Definisi Standar Data SDI',
                        'size' => $st->ukuran ?? 'Persentase / Jumlah',
                        'unit' => $st->satuan ?? 'Satuan Unit',
                        'classification' => $st->klasifikasi ?? 'Standar Nasional',
                    ];
                })->toArray();
                $sdsnTotal = count($sdsnData);
            }
        }

        return view('guest.kode-referensi', compact('districts', 'villages', 'puskesmas', 'tab', 'search', 'sdsnData', 'sdsnTotal', 'sdsnPage'));
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

        $totalDatasetCount = Data::where('status_id', Data::STATUS_TERPUBLIKASI)->count();

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
            if ($request->filled('sumber_referensi')) {
                $localQuery->where('sumber_referensi', $request->sumber_referensi);
            }
            if ($request->filled('tahun')) {
                $localQuery->where('tahun', $request->tahun);
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
        $tahuns = MasterTahun::orderBy('tahun', 'desc')->get();
        $sumberReferensiList = ['RPJMD', 'Renstra', 'SPM', 'SDGs', 'LPPK', 'IKP', 'Lainnya'];

        return view('guest.data', compact('data', 'groups', 'orgs', 'pages', 'page', 'hasPrevPage', 'hasNextPage', 'totalDatasetCount', 'tahuns', 'sumberReferensiList'));
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
            ->where('nama_data', $dataset['title'] ?? '')
            ->orWhere('id', $dataset['id'] ?? 0)
            ->latest()
            ->first();

        if ($getmeta) {
            $getmeta->increment('views_count');
        }

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

    public function downloadDatasetFormat($id, $format)
    {
        $data = Data::with(['berkas', 'visualtable.header', 'visualtable.isi', 'standar', 'opd'])->find($id);
        if (!$data) {
            return redirect()->route('dataset')->with('error', 'Dataset tidak ditemukan');
        }

        // Increment download counter
        $data->increment('downloads_count');

        $filename = Str::slug($data->nama_data ?: 'dataset-madiun') . '-' . ($data->tahun ?: date('Y'));

        if ($format === 'json') {
            $jsonData = [
                'id' => $data->id,
                'nama_data' => $data->nama_data,
                'tahun' => $data->tahun,
                'opd' => $data->opd ? $data->opd->nama_opd : null,
                'sumber_referensi' => $data->sumber_referensi ?: $data->sumber_data,
                'level_data' => $data->level_data,
                'standar' => $data->standar,
                'tables' => $data->visualtable->map(function($t) {
                    return [
                        'nama_tabel' => $t->nama_table,
                        'headers' => $t->header ? $t->header->pluck('header') : [],
                        'rows' => $t->isi ? $t->isi->groupBy('urutan_kebawah')->map(fn($r) => $r->pluck('isi')->toArray())->values() : []
                    ];
                })
            ];
            return response()->json($jsonData, 200, [
                'Content-Disposition' => 'attachment; filename="' . $filename . '.json"',
            ]);
        }

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
            ];

            return response()->stream(function() use ($data) {
                $file = fopen('php://output', 'w');
                $firstTable = $data->visualtable->first();
                if ($firstTable && $firstTable->header && $firstTable->header->count() > 0) {
                    fputcsv($file, $firstTable->header->pluck('header')->toArray());
                    if ($firstTable->isi) {
                        $rows = $firstTable->isi->groupBy('urutan_kebawah');
                        foreach ($rows as $row) {
                            fputcsv($file, $row->pluck('isi')->toArray());
                        }
                    }
                } else {
                    fputcsv($file, ['No', 'Nama Data', 'Tahun', 'Produsen Data', 'Sumber Referensi']);
                    fputcsv($file, [1, $data->nama_data, $data->tahun, $data->opd ? $data->opd->nama_opd : '-', $data->sumber_referensi ?: $data->sumber_data]);
                }
                fclose($file);
            }, 200, $headers);
        }

        // For xlsx / excel
        $berkas = $data->berkas->first();
        if ($berkas && !empty($berkas->path_berkas) && Storage::exists($berkas->path_berkas)) {
            return Storage::download($berkas->path_berkas, $filename . '.xlsx');
        }

        return $this->downloadDatasetFormat($id, 'csv');
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
