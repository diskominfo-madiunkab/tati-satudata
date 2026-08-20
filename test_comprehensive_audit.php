<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/', 'GET');
$app->instance('request', $request);
$kernel->bootstrap();

$users = [
    'guest' => null,
    'admin' => \App\Models\User::where('role_id', 1)->first() ?? \App\Models\User::first(),
    'walidata' => \App\Models\User::where('role_id', 2)->first() ?? \App\Models\User::first(),
    'produsen' => \App\Models\User::where('role_id', 3)->first() ?? \App\Models\User::first(),
    'pembina' => \App\Models\User::where('role_id', 4)->first() ?? \App\Models\User::first(),
];

$firstData = \App\Models\Data::first();
$dataId = $firstData ? $firstData->id : 1;
$dataTitle = $firstData ? $firstData->nama_data : 'test';

$firstPub = \App\Models\PublikasiGuest::first();
$pubId = $firstPub ? $firstPub->id : 1;

$firstInfo = \App\Models\Infografis::first();
$infoId = $firstInfo ? $firstInfo->id : 1;

$routesToTest = [
    // Public routes (Guest)
    ['user' => 'guest', 'url' => '/', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/katalog-data', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/kode-referensi', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/kode-referensi?tab=desa', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/kode-referensi?tab=puskesmas', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/kode-referensi?tab=sdsn', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/regulasi', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/geoportal', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/publikasi-guest', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/publikasi-guest/detail/' . $pubId, 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/infografis-guest', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/infografis-guest/detail/' . $infoId, 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/dataset', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/dataset/' . $dataId, 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/tentang', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/login', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/api/v1/datasets', 'method' => 'GET'],
    ['user' => 'guest', 'url' => '/api/v1/datasets/' . $dataId, 'method' => 'GET'],

    // Admin routes
    ['user' => 'admin', 'url' => '/home', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/data_administrator', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/data_administrator/create', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/user', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/user/create', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/opd', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/opd/create', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/tag', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/tag/create', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/group', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/group/create', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/tahun', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/tahun/create', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/sumberdata', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/sumberdata/create', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/infografis', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/infografis/create', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/publikasi-admin', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/publikasi-admin/create', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/usulan-data', 'method' => 'GET'],
    ['user' => 'admin', 'url' => '/rekapitulasi/opd', 'method' => 'GET'],

    // Walidata routes
    ['user' => 'walidata', 'url' => '/home', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/draft', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/create', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/standar-data', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/pengumpulan', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/verifikasi', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/verifikasi/revisi', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/verifikasi/siap-publikasi', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/verifikasi/' . $dataId . '/berkas', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/verifikasi/' . $dataId . '/indikator', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/verifikasi/' . $dataId . '/variabel', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/verifikasi/' . $dataId . '/riwayat-revisi', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/publikasi', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/publikasi/' . $dataId . '/preview', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/data_walidata/publikasi/' . $dataId . '/review', 'method' => 'GET'],
    ['user' => 'walidata', 'url' => '/d_walidata/rekap', 'method' => 'GET'],

    // Produsen routes
    ['user' => 'produsen', 'url' => '/home', 'method' => 'GET'],
    ['user' => 'produsen', 'url' => '/data_produsen/draft', 'method' => 'GET'],
    ['user' => 'produsen', 'url' => '/data_produsen/create', 'method' => 'GET'],
    ['user' => 'produsen', 'url' => '/data_produsen/standar-data', 'method' => 'GET'],
    ['user' => 'produsen', 'url' => '/data_produsen/pengumpulan', 'method' => 'GET'],
    ['user' => 'produsen', 'url' => '/data_produsen/pengumpulan/' . $dataId . '/isi-indikator', 'method' => 'GET'],
    ['user' => 'produsen', 'url' => '/data_produsen/pengumpulan/' . $dataId . '/isi-variabel', 'method' => 'GET'],
    ['user' => 'produsen', 'url' => '/data_produsen/pengumpulan/' . $dataId . '/isi-berkas', 'method' => 'GET'],
    ['user' => 'produsen', 'url' => '/data_produsen/pengumpulan/' . $dataId . '/isi-data', 'method' => 'GET'],
    ['user' => 'produsen', 'url' => '/data_produsen/publikasi', 'method' => 'GET'],

    // Pembina routes
    ['user' => 'pembina', 'url' => '/home', 'method' => 'GET'],
    ['user' => 'pembina', 'url' => '/data_walidata/draft', 'method' => 'GET'],
    ['user' => 'pembina', 'url' => '/data_walidata/standar-data', 'method' => 'GET'],
    ['user' => 'pembina', 'url' => '/d_walidata/rekap', 'method' => 'GET'],
];

echo "======================================================" . PHP_EOL;
echo "🚀 MEMULAI AUDIT KOMPREHENSIF SELURUH RUTE APLIKASI" . PHP_EOL;
echo "======================================================" . PHP_EOL;

$passed = 0;
$failed = 0;
$errors = [];

foreach ($routesToTest as $item) {
    $role = $item['user'];
    $url = $item['url'];
    $method = $item['method'];
    $u = $users[$role];

    if ($u) {
        \Illuminate\Support\Facades\Auth::login($u);
    } else {
        \Illuminate\Support\Facades\Auth::logout();
    }

    $req = \Illuminate\Http\Request::create($url, $method);
    if ($u) {
        $req->setUserResolver(fn() => $u);
    }

    try {
        $res = $kernel->handle($req);
        $status = $res->getStatusCode();

        if ($status >= 500) {
            echo "❌ [FAIL {$status}] ({$role}) {$url}" . PHP_EOL;
            $failed++;
            $errors[] = [
                'role' => $role,
                'url' => $url,
                'status' => $status,
                'content' => substr(strip_tags($res->getContent()), 0, 200)
            ];
        } else {
            echo "✅ [OK {$status}] ({$role}) {$url}" . PHP_EOL;
            $passed++;
        }
        $kernel->terminate($req, $res);
    } catch (\Throwable $e) {
        echo "💥 [EXCEPTION] ({$role}) {$url}: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
        $failed++;
        $errors[] = [
            'role' => $role,
            'url' => $url,
            'status' => 'EXCEPTION',
            'content' => $e->getMessage() . ' (' . $e->getFile() . ':' . $e->getLine() . ')'
        ];
    }
}

echo PHP_EOL . "======================================================" . PHP_EOL;
echo "📊 HASIL AUDIT:" . PHP_EOL;
echo "✅ Berhasil (Bebas Error 500): {$passed}" . PHP_EOL;
echo "❌ Gagal (Error 500 / Exception): {$failed}" . PHP_EOL;
echo "======================================================" . PHP_EOL;

if ($failed > 0) {
    echo PHP_EOL . "DETAIL ERROR:" . PHP_EOL;
    foreach ($errors as $err) {
        echo "- [{$err['status']}] ({$err['role']}) {$err['url']}" . PHP_EOL;
        echo "  Detail: {$err['content']}" . PHP_EOL;
    }
    exit(1);
} else {
    echo "🎉 SEMUA RUTE 100% BEBAS ERROR 500!" . PHP_EOL;
    exit(0);
}
