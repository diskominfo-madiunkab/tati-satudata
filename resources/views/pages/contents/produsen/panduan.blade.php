@extends('pages.main.layout')

@section('content')
<div class="pagetitle">
    <h1>Buku Panduan Produsen Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Panduan</li>
            <li class="breadcrumb-item active">Buku Panduan Produsen</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 pb-3 mb-4 border-bottom">
                    <div>
                        <h4 class="fw-bold text-dark mb-1"><i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Panduan Penyelenggaraan Satu Data untuk Produsen Data</h4>
                        <p class="text-muted small mb-0">Petunjuk teknis pengisian Perencanaan Data, Pengumpulan Data Tabular/Geospasial, dan Pengajuan Verifikasi Walidata</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ asset('storage/panduan/Panduan_Produsen_Data_SDI_2026.pdf') }}" target="_blank" class="btn btn-danger fw-semibold">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Unduh Buku Panduan (PDF)
                        </a>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 bg-light rounded-4 h-100 border-start border-4 border-primary">
                            <span class="badge bg-primary mb-3 fs-6">Tahap 1</span>
                            <h5 class="fw-bold mb-2">Perencanaan Data</h5>
                            <p class="small text-muted mb-0">Pengisian daftar usulan data baru, pemilihan kode referensi, pengisian variabel & indikator sesuai Standar Data Statistik Nasional (SDSN BPS).</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 bg-light rounded-4 h-100 border-start border-4 border-warning">
                            <span class="badge bg-warning text-dark mb-3 fs-6">Tahap 2</span>
                            <h5 class="fw-bold mb-2">Pengumpulan Data</h5>
                            <p class="small text-muted mb-0">Input data tabular langsung di portal atau unggah berkas excel/csv. Dukungan pengisian multi-level (Kabupaten, Kecamatan, Desa) dan periode.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 bg-light rounded-4 h-100 border-start border-4 border-info">
                            <span class="badge bg-info text-dark mb-3 fs-6">Tahap 3</span>
                            <h5 class="fw-bold mb-2">Pemeriksaan / Verifikasi</h5>
                            <p class="small text-muted mb-0">Pemeriksaan kelengkapan metadata dan keabsahan data oleh Walidata (Diskominfo) serta Pembina Data (BPS & Bapperida). Respon catatan revisi jika ada.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 bg-light rounded-4 h-100 border-start border-4 border-success">
                            <span class="badge bg-success mb-3 fs-6">Tahap 4</span>
                            <h5 class="fw-bold mb-2">Penyebarluasan</h5>
                            <p class="small text-muted mb-0">Publikasi data terbuka ke publik, portal Satu Data Kabupaten Madiun, dan integrasi otomatis ke Portal Satu Data Indonesia Nasional.</p>
                        </div>
                    </div>
                </div>

                <div class="card bg-primary bg-opacity-10 border-0 rounded-4 p-4 mt-4">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-info-circle-fill text-primary fa-2x"></i>
                        <div>
                            <h6 class="fw-bold text-primary mb-1">Butuh Bantuan Lebih Lanjut?</h6>
                            <p class="small text-secondary mb-0">
                                Jika mengalami kendala teknis dalam pengoperasian Portal Satu Data, silakan menghubungi Walidata (Dinas Kominfo Kab. Madiun) melalui email: <strong>sdi@madiunkab.go.id</strong> atau layanan helpdesk Satu Data.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
