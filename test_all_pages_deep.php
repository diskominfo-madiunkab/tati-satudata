<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$roles = [
    'administrator' => 1,
    'walidata' => 2,
    'produsen' => 3,
    'pembina' => 4,
    'walidatapendukung' => 2
];

$roleRoutes = [
    'administrator' => ['/data_administrator', '/rekapitulasi/opd'],
    'walidata' => [
        '/data_walidata/draft',
        '/data_walidata/create',
        '/data_walidata/standar-data',
        '/data_walidata/pengumpulan',
        '/data_walidata/verifikasi',
        '/data_walidata/publikasi',
        '/rekapitulasi/opd'
    ],
    'produsen' => [
        '/data_produsen/draft',
        '/data_produsen/create',
        '/data_produsen/standar-data',
        '/data_produsen/pengumpulan'
    ],
    'pembina' => [
        '/data_walidata/draft',
        '/data_walidata/standar-data',
        '/rekapitulasi/opd'
    ],
    'walidatapendukung' => [
        '/data_walidata/draft',
        '/data_walidata/pengumpulan',
        '/rekapitulasi/opd'
    ],
];

echo "=== MEMERIKSA SELURUH HALAMAN ROLE INTERNAL ===" . PHP_EOL;

$failed = 0;
$passed = 0;

foreach ($roleRoutes as $roleName => $routes) {
    $user = \App\Models\User::where('role_id', $roles[$roleName])->first();
    if (!$user) {
        $user = \App\Models\User::first();
    }
    
    echo PHP_EOL . "--- Role: {$roleName} (User: {$user->name}) ---" . PHP_EOL;
    
    foreach ($routes as $uri) {
        // Authenticate as this user
        \Illuminate\Support\Facades\Auth::login($user);
        
        $req = \Illuminate\Http\Request::create($uri, 'GET');
        $req->setUserResolver(fn() => $user);
        
        try {
            $res = $kernel->handle($req);
            $code = $res->getStatusCode();
            
            if ($code == 200 || $code == 302) {
                echo "  [PASS] {$code} - {$uri}" . PHP_EOL;
                $passed++;
            } else {
                echo "  [FAIL] {$code} - {$uri}" . PHP_EOL;
                $failed++;
            }
            $kernel->terminate($req, $res);
        } catch (\Throwable $e) {
            echo "  [EXCEPTION] {$uri}: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
            $failed++;
        }
    }
}

echo PHP_EOL . "=== RINGKASAN AUDIT ===" . PHP_EOL;
echo "Total Halaman Lulus (HTTP 200/302): {$passed}" . PHP_EOL;
echo "Total Halaman Gagal (HTTP >=500): {$failed}" . PHP_EOL;

if ($failed === 0) {
    echo "STATUS: 100% SUKSES DAN BEBAS ERROR 500!" . PHP_EOL;
}
