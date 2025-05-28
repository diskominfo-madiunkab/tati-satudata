<?php

use App\Http\Controllers\BoxValueController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfografisController;
use App\Http\Controllers\ListExcelController;
use App\Http\Controllers\MasterTahunController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\PengumpulanController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\UpdownloadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Produsen;
use App\Http\Controllers\PublikasiGuestController;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\StandartDataController;
use App\Http\Controllers\SumberDataController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UsulanDataController;
use App\Http\Controllers\VisualDataController;
use App\Http\Controllers\Walidata;
use App\Http\Controllers\WilayahController;
use App\Imports\DataImport;
use App\Imports\OpdImport;
use App\Imports\UserImport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

Route::get('callback', [SsoController::class, 'callback']);
Route::get('/', [PortalController::class, 'index']);
Route::get('/tentang', [PortalController::class, 'tentang']);
Route::get('/infografis-guest', [PortalController::class, 'infografis'])->name('guest.infografis');
Route::get('/infografis-guest/detail/{id}', [PortalController::class, 'infografis_detail'])->name('guest.infografis.detail');
Route::get('/publikasi-guest', [PortalController::class, 'publikasi'])->name('guest.publikasi');
Route::get('/publikasi-guest/detail/{id}', [PortalController::class, 'publikasi_detail'])->name('guest.publikasi.detail');
Route::get('/download-publikasi-pdf{id}', [PortalController::class, 'download'])->name('guest.publikasi.download.pdf');
Route::get('/dataset', [PortalController::class, 'data'])->name('dataset');
// Route::get('/dataset/{name}', [PortalController::class, 'detail'])->name('dataset.detail');
Route::get('/dataset/{name}', [PortalController::class, 'showDataset'])->name('dataset.show');
Route::get('/download-file-count', [PortalController::class, 'downloadFileCount'])->name('download.file.count');
Route::post('/dataset/grafik', [PortalController::class, 'storeDataByFilter'])->name('dataset.chart.storeDataByFilter');
Route::get('/download-detail-infografis/{id}', [PortalController::class, 'downloadImage'])->name('download.image.infografis');
Route::get('/download-detail-infografis-pdf/{id}', [PortalController::class, 'downloadPdf'])->name('download.image.infografis.pdf');
//Route::get('/berita', [PortalController::class, 'berita']);
Route::post('/send-usulan', [PortalController::class, 'send_usulan'])->name('send.usulan');
// Route::get('/reload-captcha', [PortalController::class, 'reloadCaptcha']);
Route::get('reload-captcha', function () {
    return response()->json(['captcha' => captcha_src()]);
});
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return redirect()->back()->with('success', 'Cache cleared successfully');
});

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);
// Route::middleware(['role:administrator|walidata|walidatapendukung', 'auth:web'])->group(
// Route::middleware(['role:walidata|walidatapendukung|administrator|walidatapendukung', 'auth:web'])->group(
//     function () {
//         Route::resource('usulan-data', UsulanDataController::class);
//     }
// );



Route::get('/download-excel-list', [ListExcelController::class, 'index'])->name('download-excel-list');
Route::middleware(['role:administrator', 'auth:web'])->group(function () {
    Route::get('/d_administrator', [HomeController::class, 'dashboardAdmin'])->name('d_administrator');
    Route::post('/filter_tahun_admin', [DataController::class, 'data_filter_tahun'])->name('filter_tahun_admin');
    Route::get('/data_administrator', [DataController::class, 'index'])->name('data_administrator');
    Route::get('/data_administrator/create', [DataController::class, 'create'])->name('data_administrator.create');
    Route::post('/data_administrator/store', [DataController::class, 'store'])->name('data_administrator.store');
    Route::get('/data_administrator/edit/{id}', [DataController::class, 'edit'])->name('data_administrator.edit');
    Route::post('/data_administrator/update/{id}', [DataController::class, 'update'])->name('data_administrator.update');
    Route::get('/data_administrator/destroy/{id}', [DataController::class, 'destroy'])->name('data_administrator.destroy');
    Route::get('/data_administrator/get_all_opdall', [DataController::class, 'get_all_opdall'])->name('data_administrator.getopd');

    Route::resource('data-demografis', WilayahController::class);

    Route::post('/data/import', function () {
        Excel::import(new DataImport, request()->file('file'));
        return back();
    });
    Route::get('/data_administrator/verifikasi_data', [DataController::class, 'verifikasi_data'])->name('data_administrator.verif');


    Route::get('/opd', [OpdController::class, 'index'])->name('opd');
    Route::get('/opd/create', [OpdController::class, 'create'])->name('opd.create');
    Route::post('/opd/store', [OpdController::class, 'store'])->name('opd.store');
    Route::get('/opd/edit/{id}', [OpdController::class, 'edit'])->name('opd.edit');
    Route::post('/opd/update/{id}', [OpdController::class, 'update'])->name('opd.update');
    Route::get('/opd/destroy/{id}', [OpdController::class, 'destroy'])->name('opd.destroy');
    // Route::resource('opd', UserController::class);
    Route::post('/opd/import', function () {
        Excel::import(new OpdImport, request()->file('file'));
        Alert::success('Berhasil', 'Berhasil Menambahkan Data Dari Excel!');
        return back();
    });
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::post('/user/{id}/password/update', [UserController::class, 'changePassword'])->name('admin.change-user-password');
    // Route::resource('user', UserController::class);
    Route::post('/user/import', function () {
        Excel::import(new UserImport, request()->file('file'));
        Alert::success('Berhasil', 'Berhasil Menambahkan Data Dari Excel!');
        return back();
    });

    Route::get('/upload-download', [UpdownloadController::class, 'index']);
    Route::get('/upload', [UpdownloadController::class, 'upload']);
    Route::post('/upload-proses', [UpdownloadController::class, 'proses_upload']);
    Route::get('/upload-hapus/{id}', [UpdownloadController::class, 'destroy']);
    Route::get('/download/{id}', [UpdownloadController::class, 'download']);

    Route::get('/sumberdata', [SumberDataController::class, 'index']);
    Route::get('/sumberdata/create', [SumberDataController::class, 'create']);
    Route::post('/sumberdata/store', [SumberDataController::class, 'store']);
    Route::get('/sumberdata/edit/{id}', [SumberDataController::class, 'edit']);
    Route::post('/sumberdata/update/{id}', [SumberDataController::class, 'update']);
    Route::get('master_sumberdata_ubah/{id}', [SumberDataController::class, 'aktifkan'])->name('master-sumberdata-ubah');

    Route::get('/tag', [TagController::class, 'index'])->name('tag.index');
    Route::get('/tag/create', [TagController::class, 'create'])->name('tag.create');
    Route::post('/tag/store', [TagController::class, 'store'])->name('tag.store');
    Route::get('/tag/edit/{id}', [TagController::class, 'edit'])->name('tag.edit');
    Route::put('/tag/update/{id}', [TagController::class, 'update'])->name('tag.update');
    Route::get('/tag/delete/{id}', [TagController::class, 'destroy'])->name('tag.delete');

    Route::get('/group', [GroupController::class, 'index'])->name('group.index');
    Route::get('/group/create', [GroupController::class, 'create'])->name('group.create');
    Route::post('/group/store', [GroupController::class, 'store'])->name('group.store');
    Route::get('/group/edit/{id}', [GroupController::class, 'edit'])->name('group.edit');
    Route::post('/group/update/{id}', [GroupController::class, 'update'])->name('group.update');
    Route::get('/group/delete/{id}', [GroupController::class, 'destroy'])->name('group.delete');

    Route::get('/tahun', [MasterTahunController::class, 'index']);
    Route::get('/tahun/create', [MasterTahunController::class, 'create']);
    Route::post('/tahun/store', [MasterTahunController::class, 'store']);
    Route::get('/tahun/edit/{id}', [MasterTahunController::class, 'edit']);
    Route::post('/tahun/update/{id}', [MasterTahunController::class, 'update']);
    Route::get('master_tahun_ubah/{id}', [MasterTahunController::class, 'aktifkan'])->name('master-tahun-ubah');

    // Route::resource('/infografis', [InfografisController::class]);

    Route::get('/infografis', [InfografisController::class, 'index'])->name('infografis.index');
    Route::get('/infografis/create', [infografisController::class, 'create'])->name('infografis.create');
    Route::post('/infografis/store', [infografisController::class, 'store'])->name('infografis.store');
    Route::get('/infografis/edit/{id}', [infografisController::class, 'edit'])->name('infografis.edit');
    Route::post('/infografis/update/{id}', [infografisController::class, 'update'])->name('infografis.update');
    Route::get('/infografis/destroy/{id}', [infografisController::class, 'destroy'])->name('infografis.delete');

    Route::get('/publikasi-admin', [PublikasiGuestController::class, 'index'])->name('publikasi-guest.index');
    Route::get('/publikasi-admin/create', [PublikasiGuestController::class, 'create'])->name('publikasi.create');
    Route::post('/publikasi-admin/store', [PublikasiGuestController::class, 'store'])->name('publikasi.store');
    Route::get('/publikasi-admin/edit/{id}', [PublikasiGuestController::class, 'edit'])->name('publikasi.edit');
    Route::post('/publikasi-admin/update/{id}', [PublikasiGuestController::class, 'update'])->name('publikasi.update');
    Route::get('/publikasi-admin/destroy/{id}', [PublikasiGuestController::class, 'destroy'])->name('publikasi.delete');
    Route::get('/publikasi-admin/download/{id}', [PublikasiGuestController::class, 'download'])->name('publication.download');
});

Route::middleware(['role:walidata|pembina|walidatapendukung|administrator', 'auth:web'])->group(function () {
    Route::get('/d_walidata', [HomeController::class, 'dashboardWalidata'])->name('d_walidata');
    Route::get('/d_walidata/rekap', [HomeController::class, 'dashboardRekapOPD'])->name('rekap_walidata');
    Route::get('/d_walidata/rekap/excel', [HomeController::class, 'dashboardRekapOPDExcel'])->name('rekap_walidata_excel');

    Route::get('/data_walidata/draft', [DataController::class, 'index'])->name('walidata.draft');
    Route::post('/filter_tahun', [DataController::class, 'data_filter_tahun'])->name('filter_tahun');
    Route::post('/data_walidata/search', [DataController::class, 'searchData'])->name('search_data');
    Route::post('/filter_insert_data_tahun_lalu', [DataController::class, 'data_filter_tahun_lalu'])->name('filter_tahun_lalu');
    Route::get('/data_walidata/create', [DataController::class, 'create'])->middleware(['role:walidata|walidatapendukung'])->name('data_walidata.create');
    Route::get('/data_walidata/draft/api', [DataController::class, 'apiData'])->name('walidata.draft.api');
    Route::get('/data_walidata/fetch', [DataController::class, 'fetch_data_sipd'])->middleware(['role:walidata|walidatapendukung'])->name('data_walidata.fetch.sipd');
    Route::get('/data_walidata', [DataController::class, 'index_data'])->middleware(['role:walidata|walidatapendukung'])->name('index_data.walidata');

    // tambah data dari tahun sebelumnya
    Route::post('/data_walidata/add_last_years_data', [DataController::class, 'add_data_tahun_lalu'])->middleware(['role:walidata|walidatapendukung'])->name('add.data.tahun.lalu');

    Route::post('/data_walidata/store', [DataController::class, 'store'])->middleware(['role:walidata|walidatapendukung']);
    Route::get('/data_walidata/edit/{id}', [DataController::class, 'edit'])->name('edit_walidata');
    Route::get('/data_walidata/detail/{id}', [DataController::class, 'detail'])->name('detail_walidata');
    Route::post('/data_walidata/update/{id}', [DataController::class, 'update'])->middleware(['role:walidata|walidatapendukung']);
    Route::get('/data_walidata/destroy/{id}', [DataController::class, 'destroy'])->middleware(['role:walidata|walidatapendukung']);
    Route::get('/data_walidata/get_all_opdall', [DataController::class, 'get_all_opdall']);


    Route::resource('box-value', BoxValueController::class);
    // standardata
    Route::get('/data_walidata/standar-data', [StandartDataController::class, 'index_walidata'])->name('walidata.standar-data.index');
    Route::get('/data_walidata/standar-data/revisi', [StandartDataController::class, 'revisi_walidata'])->name('walidata.standar-data.revisi');
    Route::get('/data_walidata/standar-data/setuju', [StandartDataController::class, 'setuju_walidata'])->name('walidata.standar-data.setuju');
    // Route::match(['get', 'post'], '/data_walidata/pengumpulan/{id}/standar', [PengumpulanController::class, 'standarData'])->name('standar');
    Route::match(['get', 'post'], '/data_walidata/standar-data/{id}/standar', [StandartDataController::class, 'verifikasiStandarData'])->name('walidata.standar-data.verif');
    Route::patch('/data_walidata/standar-data/verifikasi/{id}/verify', [StandartDataController::class, 'verify'])->name('walidata.standar-data.verifikasi.verify');
    Route::get('/data_walidata/standar-data/verifikasi/{id}/status', [Walidata\VerifikasiController::class, 'status_standar'])->name('walidata.standar-data.verifikasi.status');
    Route::patch('/data_walidata/standar-data/verifikasi/{id}/complete', [Walidata\VerifikasiController::class, 'complete_standar'])->name('walidata.standar-data.verifikasi.complete');

    Route::get('/data_walidata/standar-data/verifikasi/{id}/komentar', [StandartDataController::class, 'getKomentar'])->name('walidata.standar-data.verifikasi.get-komentar');
    Route::post('/data_walidata/standar-data/verifikasi/{id}/komentar', [StandartDataController::class, 'komentar'])->name('walidata.standar-data.verifikasi.komentar');




    Route::get('/get_data_opd', [DataController::class, 'get_all_opd'])->middleware(['role:walidata|walidatapendukung']);
    Route::get('/get_all_opdall', [DataController::class, 'get_all_opdall'])->middleware(['role:walidata|walidatapendukung|walidatapendukung']);
    Route::get('/get_all_opdall/cari/{id}', [DataController::class, 'cari_opd'])->middleware(['role:walidata|walidatapendukung']);

    // Route::post('/data_walidata/export-pdf', [DataController::class, 'pdf2']);
    Route::get('/data_walidata/export-pdf2', [DataController::class, 'pdf2'])->middleware(['role:walidata|walidatapendukung']);
    Route::get('/data_walidata/verifikasi_data', [DataController::class, 'verifikasi_data'])->middleware(['role:walidata|walidatapendukung']);
    Route::get('/data_walidata/tolak_konfirmasi_walidata', [DataController::class, 'tolak_konfirmasi_walidata'])->name('tolak_konfirmasi_walidata');
    Route::get('/data_walidata/selesai_konfirmasi_walidata', [DataController::class, 'selesai_konfirmasi_walidata'])->name('selesai_konfirmasi_walidata');
    Route::get('/calculate-progress/{id}', [DataController::class, 'calculateProgress'])->name('calculate_progress');

    Route::get('/data_walidata/ubah_data_prioritas/{id}', [DataController::class, 'aktifkan_data_prioritas'])->name('aktifkan-data-prioritas');
    Route::get('/data_walidata/restore/{id}', [DataController::class, 'restore'])->middleware(['role:walidata|walidatapendukung|pembina']);
    Route::get('getData', [DataController::class, 'getData'])->middleware(['role:walidata|walidatapendukung'])->name('getData');

    Route::get('/data_walidata/pengumpulan', [PengumpulanController::class, 'pengumpulan'])->name('pengumpulan');
    Route::get('/data_walidata/pengumpulan/{id}/data', [PengumpulanController::class, 'detailData']);
    Route::get('/data_walidata/pengumpulan/{id}/indikator', [Walidata\PengumpulanController::class, 'indikator'])->name('walidata.pengumpulan.indikator');
    Route::get('/data_walidata/pengumpulan/{id}/variabel', [Walidata\PengumpulanController::class, 'variabel'])->name('walidata.pengumpulan.variabel');
    Route::get('/data_walidata/pengumpulan/{id}/standar', [PengumpulanController::class, 'standarData']);
    Route::get('/data_walidata/pengumpulan/{id}/kegiatan', [PengumpulanController::class, 'kegiatan']);

    Route::get('/data_walidata/pengumpulan', [PengumpulanController::class, 'pengumpulan'])->name('pengumpulan.wali');
    Route::get('/data_walidata/pengumpulan/lengkap', [PengumpulanController::class, 'pengumpulan_lengkap'])->name('pengumpulan-lengkap.wali');

    // filter
    Route::post('/filter_pengumpulan_walidata', [PengumpulanController::class, 'filter_pengumpulan'])->name('filter_pengumpulan_walidata');
    Route::get('/get_calculate_walidata', [PengumpulanController::class, 'ajaxcalculateProgress'])->name('ajaxcalculateProgress');

    Route::get('/data_walidata/verifikasi', [Walidata\VerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::get('/data_walidata/verifikasi/{id}/berkas', [Walidata\VerifikasiController::class, 'berkas'])->name('verifikasi.berkas');
    Route::get('/data_walidata/verifikasi/{id}/variabel', [Walidata\VerifikasiController::class, 'variabel'])->name('verifikasi.variabel');
    Route::get('/data_walidata/verifikasi/{id}/indikator', [Walidata\VerifikasiController::class, 'indikator'])->name('verifikasi.indikator');
    Route::get('/data_walidata/verifikasi/{id}/komentar', [Walidata\VerifikasiController::class, 'getKomentar'])->name('verifikasi.get-komentar');
    Route::post('/data_walidata/verifikasi/{id}/komentar', [Walidata\VerifikasiController::class, 'komentar'])->name('verifikasi.komentar');
    Route::patch('/data_walidata/verifikasi/{id}/verify', [Walidata\VerifikasiController::class, 'verify'])->name('verifikasi.verify');
    Route::put('/data_walidata/verifikasi/{id}/verify-multi', [Walidata\VerifikasiController::class, 'verifyMulti'])->name('verifikasi.verify-multi');
    Route::get('/data_walidata/verifikasi/{id}/status', [Walidata\VerifikasiController::class, 'status'])->name('verifikasi.status');
    Route::patch('/data_walidata/verifikasi/{id}/complete', [Walidata\VerifikasiController::class, 'complete'])->name('verifikasi.complete');
    Route::get('/data_walidata/verifikasi', [Walidata\VerifikasiController::class, 'index']);
    Route::get('/data_walidata/verifikasi/revisi', [Walidata\VerifikasiController::class, 'revisi'])->name('walidata.verifikasi.revisi');
    Route::get('/data_walidata/verifikasi/siap-publikasi', [Walidata\VerifikasiController::class, 'siapPublikasi']);

    Route::post('/filter_verifikasi_walidata', [Walidata\VerifikasiController::class, 'filter_verifikasi'])->name('filter_verifikasi_walidata');


    Route::group(['prefix' => '/data_walidata/publikasi', 'as' => 'publikasi.', 'middleware' => 'role:walidata|walidatapendukung|pembina'], function () {
        Route::get('/', [Walidata\PublikasiController::class, 'index'])->name('index');
        Route::get('/{id}/organisasi', [Walidata\PublikasiController::class, 'organisasi'])->name('organisasi');
        Route::post('/{id}/organisasi', [Walidata\PublikasiController::class, 'simpanOrganisasi'])->name('organisasi.store');
        Route::post('organisasi/create', [Walidata\PublikasiController::class, 'createOrganisasi'])->name('organisasi.create');
        Route::get('/{id}/dataset', [Walidata\PublikasiController::class, 'dataset'])->name('dataset');
        Route::post('/{id}/dataset', [Walidata\PublikasiController::class, 'simpanDataset'])->name('dataset.store');
        Route::get('/{id}/review', [Walidata\PublikasiController::class, 'review'])->name('review');
        Route::post('/{id}/publish', [Walidata\PublikasiController::class, 'publish'])->name('publish');
        Route::get('/terpublikasi', [Walidata\PublikasiController::class, 'terpublikasi'])->name('terpublikasi');
        Route::get('/{id}/ckanshow', [Walidata\PublikasiController::class, 'ckanshow'])->name('ckanshow');
    });
    Route::post('/filter_publikasi', [Walidata\PublikasiController::class, 'filter_publikasi'])->name('filter_publikasi');


    Route::post('/data_walidata/import', [DataController::class, 'importData'])->middleware(['role:walidata|walidatapendukung']);
    Route::get('/data_walidata/notif', [DataController::class, 'notif'])->name('notif');
    Route::get('/draft', [DataController::class, 'draft'])->name('draft.wali');

    Route::get('/data_walidata/detail-data/{id}', [DataController::class, 'detailData'])->name('walidata.data.detail');
    Route::get('/data_walidata/detail-data-standar/{id}', [DataController::class, 'detailDataStandar'])->name('walidata.data.detailDataStandar');
});

Route::post('/storeDataByFilter', [PengumpulanController::class, 'storeDataByFilter'])->middleware(['auth:web'])->name('chart.storeDataByFilter');

Route::middleware(['role:produsen|pembina|administrator', 'auth:web'])->group(function () {
    Route::get('/d_produsen', [HomeController::class, 'dashboardProdusen'])->name('d_produsen');
    Route::get('/data_produsen/draft', [DataController::class, 'index'])->name('draft.produsen');
    Route::post('/filter_tahun_produsen', [DataController::class, 'data_filter_tahun'])->name('filter_tahun_produsen');
    Route::get('/data_produsen/create', [DataController::class, 'create']);
    Route::post('/data_produsen/store', [DataController::class, 'store']);
    Route::get('/data_produsen/edit/{id}', [DataController::class, 'edit']);
    Route::get('/data_produsen/ubah_data_prioritas/{id}', [DataController::class, 'aktifkan_data_prioritas_produsen'])->name('aktifkan-data-prioritas-produsen');
    Route::post('/data_produsen/update/{id}', [DataController::class, 'update']);
    Route::get('/data_produsen/detail/{id}', [DataController::class, 'detail'])->name('detail_produsen');
    Route::post('/data_produsen/alasan/{id}', [DataController::class, 'alasan']);
    Route::get('/data_produsen/destroy/{id}', [DataController::class, 'destroy']);
    Route::get('/data_produsen/setuju/{id}', [DataController::class, 'setuju']);
    Route::get('/data_produsen/tolak/{id}', [DataController::class, 'tolak']);
    Route::get('/data_produsen/show/{id}', [DataController::class, 'show']);
    Route::get('/data_produsen/export-pdf', [DataController::class, 'pdf']);
    Route::get('/data_produsen/selesai_konfirmasi', [DataController::class, 'selesai_konfirmasi'])->name('produsen.setuju');
    Route::get('/data_produsen/tolak_konfirmasi', [DataController::class, 'tolak_konfirmasi'])->name('produsen.tolak');
    Route::post('//data_produsen/search', [DataController::class, 'searchDataPredusen'])->name('search_data_produsen');
    // new setuju dan tolak perencanaan data
    Route::post('/data_produsen/perencanaan/setuju', [DataController::class, 'setuju_data'])->name('data_produsen.perencanaan.setuju');
    Route::post('/data_produsen/perencanaan/tolak', [DataController::class, 'alasan_data'])->name('data_produsen.perencanaan.tolak');

    // standart_data
    Route::get('/data_produsen/standar-data', [StandartDataController::class, 'index'])->name('produsen.standar-data.index');
    Route::get('/data_produsen/standar-data/revisi', [StandartDataController::class, 'revisi'])->name('produsen.standar-data.revisi');
    Route::get('/data_produsen/standar-data/setuju', [StandartDataController::class, 'setuju'])->name('produsen.standar-data.setuju');
    // Route::match(['get', 'post'], '/data_produsen/pengumpulan/{id}/standar', [PengumpulanController::class, 'standarData'])->name('standar');
    Route::match(['get', 'post'], '/data_produsen/standar-data/{id}/standar', [PengumpulanController::class, 'standarData'])->name('produsen.standar-data.standar');


    Route::get('/data_produsen/pengumpulan/{id}/data', [PengumpulanController::class, 'detailData'])->name('pengumpulan.visual.grafik');
    Route::get('/data_produsen/pengumpulan/{id}/indikator', [PengumpulanController::class, 'indikator'])->name('indikator');
    Route::post('/data_produsen/pengumpulan/{id}/simpan-indikator', [PengumpulanController::class, 'simpanIndikator'])->name('simpan-indikator');
    Route::post('/data_produsen/pengumpulan/{id}/upload-indikator', [PengumpulanController::class, 'importIndikator'])->name('import-indikator');
    Route::get('/data_produsen/pengumpulan/{id}/variabel', [PengumpulanController::class, 'variabel'])->name('variabel');
    Route::post('/data_produsen/pengumpulan/{id}/simpan-variabel', [PengumpulanController::class, 'simpanVariabel'])->name('simpan-variabel');
    Route::post('/data_produsen/pengumpulan/{id}/upload-variabel', [PengumpulanController::class, 'importVariabel'])->name('import-variabel');
    Route::get('/data_produsen/pengumpulan/{id}/kegiatan', [PengumpulanController::class, 'kegiatan']);
    Route::post('/data_produsen/pengumpulan/{id}/kegiatan', [PengumpulanController::class, 'simpanKegiatan'])->name('simpan-kegiatan');
    Route::post('/data_produsen/pengumpulan/{id}/kegiatan/variabel-dikumpulkan', [PengumpulanController::class, 'simpanVariabelDikumpulkan'])->name('simpan-variabel-dikumpulkan');
    Route::post('/data_produsen/pengumpulan/{id}/kegiatan/publikasi', [PengumpulanController::class, 'simpanPublikasi'])->name('simpan-publikasi');
    Route::patch('/data_produsen/pengumpulan/{id}/verifikasi', [PengumpulanController::class, 'siapVerifikasi'])->name('siap-verifikasi');
    Route::post('/filter_pengumpulan', [PengumpulanController::class, 'filter_pengumpulan'])->name('filter_pengumpulan');

    // visualisasi data
    Route::post('/data_produsen/pengumpulan/visualisasi_data/store', [VisualDataController::class, 'store'])->name('visual.data.store');
    Route::post('/data_produsen/pengumpulan/visualisasi_data/update', [VisualDataController::class, 'update'])->name('visual.data.update');
    Route::post('/data_produsen/pengumpulan/visualisasi_data/delete', [VisualDataController::class, 'destroy'])->name('visual.data.delete');
    Route::post('/data_produsen/pengumpulan/visualisasi_data/upload', [VisualDataController::class, 'upload'])->name('visual.data.upload');
    Route::post('/data_produsen/pengumpulan/berkas_data/delete', [VisualDataController::class, 'destroy_berkas'])->name('visual.berkas.delete');
    // visual data excel
    Route::post('/data_produsen/pengumpulan/visualisasi_data/import', [VisualDataController::class, 'import'])->name('visual.data.import');
    //visual data update delete
    Route::post('/data/update-cell', [VisualDataController::class, 'updateCell'])->name('data.updateCell');
    Route::post('/data/delete-row', [VisualDataController::class, 'deleteRow'])->name('data.deleteRow');


    Route::get('/data_produsen/verifikasi', [Produsen\VerifikasiController::class, 'index'])->name('produsen.verifikasi.index');
    Route::get('/data_produsen/verifikasi/revisi', [Produsen\VerifikasiController::class, 'revisi'])->name('produsen.verifikasi.revisi');
    Route::get('/data_produsen/verifikasi/siap-publikasi', [Produsen\VerifikasiController::class, 'siapPublikasi'])->name('produsen.verifikasi.sesuai');
    Route::get('/data_produsen/verifikasi/{id}/berkas', [Produsen\VerifikasiController::class, 'berkas'])->name('produsen.verifikasi.berkas');
    Route::get('/data_produsen/verifikasi/{id}/variabel', [Produsen\VerifikasiController::class, 'variabel'])->name('produsen.verifikasi.variabel');
    Route::get('/data_produsen/verifikasi/{id}/indikator', [Produsen\VerifikasiController::class, 'indikator'])->name('produsen.verifikasi.indikator');
    Route::get('/data_produsen/verifikasi/{id}/komentar', [Produsen\VerifikasiController::class, 'getKomentar'])->name('produsen.verifikasi.get-komentar');

    Route::post('/filter_verifikasi', [Produsen\VerifikasiController::class, 'filter_verifikasi'])->name('filter_verifikasi');

    Route::get('/data_produsen/pengumpulan/{id}/export', [PengumpulanController::class, 'exportData'])->name('export-data.produsen');

    Route::get('/data_produsen/pengumpulan', [PengumpulanController::class, 'pengumpulan'])->name('pengumpulan.produsen');
    Route::get('/data_produsen/pengumpulan/lengkap', [PengumpulanController::class, 'pengumpulan_lengkap'])->name('pengumpulan-lengkap.produsen');
    Route::match(['get', 'post'], '/data_produsen/pengumpulan/{id}/standar', [PengumpulanController::class, 'standarData'])->name('standar');
    Route::post('/data_produsen/{id}/upload-berkas', [PengumpulanController::class, 'uploadBerkas'])->name('upload-berkas');
    Route::delete('/data_produsen/{id}/delete-berkas/{berkasId}', [PengumpulanController::class, 'deleteBerkas'])->name('delete-berkas');

    Route::get('/data_produsen/penyebarluasan', [Produsen\PublikasiController::class, 'index'])->name('produsen.penyebarluasan');
    Route::get('/data_produsen/penyebarluasan/terpublikasi', [Produsen\PublikasiController::class, 'terpublikasi'])->name('produsen.penyebarluasan-terpublikasi');

    Route::get('/data_produsen/detail-data/{id}', [DataController::class, 'detailData'])->name('produsen.data.detail');
    Route::get('/data_produsen/detail-data-standar/{id}', [DataController::class, 'detailDataStandar'])->name('produsen.data.detailDataStandar');

    Route::post('/filter_publikasi_produsen', [Produsen\PublikasiController::class, 'filter_publikasi_produsen'])->name('filter_publikasi_produsen');

    Route::post('/data_produsen/import', function () {
        Excel::import(new DataImport, request()->file('file'));
        return back();
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('usulan-data', UsulanDataController::class);
    Route::get('/filepreview', [FileController::class, 'preview'])->name('filepreview');
    Route::get('/up-download/{id}', [UpdownloadController::class, 'download']);
    Route::get('/download-template/{id}', [UpdownloadController::class, 'download']);
    Route::get('/export/{id}', [Walidata\PublikasiController::class, 'exportData'])->name('export-data');
    Route::get('/account', [HomeController::class, 'account'])->name('account');
    Route::post('/account/change-password', [HomeController::class, 'changePassword'])->name('account.change-password');
});

Route::get('/ajax/provinces', [WilayahController::class, 'province'])->name('ajax.provinces');
Route::get('/ajax/cities/{provinceId?}', [WilayahController::class, 'city'])->name('ajax.cities');
Route::get('/ajax/opds', [OpdController::class, 'opds'])->name('ajax.opds');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
