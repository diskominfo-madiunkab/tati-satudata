@extends('guest.layout')

@section('content')
<div class="page-banner pt-60 pb-60" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Tentang Satu Data</h1>
            <p class="text-white-50 mb-0" style="font-size: 16px;">Tata Kelola dan Prinsip Penyelenggaraan Satu Data Indonesia di Kabupaten Madiun</p>
        </div>
    </div>
</div>

<section class="tentang-area pt-50 pb-70 bg-light">
    <div class="container">
        <div class="card shadow-sm border-0 rounded-4 p-5 bg-white mb-4">
            <h2 class="fw-bold text-dark mb-4">Tentang Satu Data Kabupaten Madiun</h2>
            
            <p class="text-secondary fs-6 leading-relaxed mb-4 text-justify" style="line-height: 1.8;">
                Satu Data Kabupaten Madiun merupakan bentuk pemenuhan amanah <strong>Peraturan Presiden Nomor 39 Tahun 2019 tentang Satu Data Indonesia</strong>. Pemerintah Kabupaten Madiun memiliki <strong>Peraturan Bupati Madiun Nomor 9 Tahun 2024 tentang Satu Data Kabupaten Madiun</strong> yang mengatur mengenai Kebijakan Tata Kelola Data di Pemerintah Kabupaten Madiun. Hal ini sebagai bentuk upaya terpadu untuk menghasilkan data yang akurat, mutakhir, terpadu, dan dapat dipertanggungjawabkan, serta mudah dibagi pakai antar Instansi Pusat dan Daerah.
            </p>

            <div class="card border-0 bg-light rounded-3 p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-check-circle text-primary me-2"></i>4 (Empat) Prinsip Satu Data Indonesia:</h5>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 bg-white rounded-3 shadow-sm h-100 border-start border-4 border-primary">
                            <span class="badge bg-primary mb-2">Prinsip 1</span>
                            <h6 class="fw-bold mb-1">Standar Data</h6>
                            <p class="small text-muted mb-0">Memenuhi konsep, definisi, ukuran, klasifikasi, dan satuan baku.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 bg-white rounded-3 shadow-sm h-100 border-start border-4 border-success">
                            <span class="badge bg-success mb-2">Prinsip 2</span>
                            <h6 class="fw-bold mb-1">Metadata Baku</h6>
                            <p class="small text-muted mb-0">Memiliki dokumentasi kegiatan, variabel, dan indikator statistik.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 bg-white rounded-3 shadow-sm h-100 border-start border-4 border-info">
                            <span class="badge bg-info text-dark mb-2">Prinsip 3</span>
                            <h6 class="fw-bold mb-1">Interoperabilitas</h6>
                            <p class="small text-muted mb-0">Format data terbuka dan dapat dibagipakaikan antar sistem elektronik.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 bg-white rounded-3 shadow-sm h-100 border-start border-4 border-warning">
                            <span class="badge bg-warning text-dark mb-2">Prinsip 4</span>
                            <h6 class="fw-bold mb-1">Kode Referensi</h6>
                            <p class="small text-muted mb-0">Menggunakan kode referensi wilayah, faskes, dan data induk nasional.</p>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-secondary fs-6 leading-relaxed mb-3 text-justify" style="line-height: 1.8;">
                Portal Satu Data Kabupaten Madiun merupakan portal resmi data terbuka Pemerintah Kabupaten Madiun. Portal ini digunakan sebagai media penyelenggaraan Satu Data Kabupaten Madiun, mulai dari <strong>Perencanaan Data, Pengumpulan Data, Pemeriksaan Data (Verifikasi Walidata), dan Penyebarluasan Data</strong> sesuai amanah regulasi nasional.
            </p>

            <p class="text-secondary fs-6 leading-relaxed mb-3 text-justify" style="line-height: 1.8;">
                Seluruh data yang tersedia pada Portal Satu Data Kabupaten Madiun bersifat <strong>Netral/Tidak Memihak dan diperuntukkan untuk Semua Pengguna Data</strong>, dipastikan bebas dari kepentingan pihak luar, serta menjamin kerahasiaan dan perlindungan data individu dari penyalahgunaan.
            </p>
        </div>
    </div>
</section>
@endsection