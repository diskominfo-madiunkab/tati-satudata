@extends('guest.layout')

@section('content')
<div class="page-banner pt-40 pb-40" style="background: linear-gradient(135deg, #023e8a 0%, #0077b6 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Tentang Satu Data Kabupaten Madiun</h1>
            <p class="text-white-50 mb-0">Portal resmi penyelenggaraan tata kelola data terbuka terpadu Pemerintah Kabupaten Madiun</p>
        </div>
    </div>
</div>

<section class="tentang-area pt-50 pb-70 bg-light">
    <div class="container">
        <div class="card shadow-sm border-0 rounded-3 p-4 p-md-5 bg-white">
            <h3 class="fw-bold text-dark mb-4 border-bottom pb-3">Tentang Satu Data Kabupaten Madiun</h3>

            <p class="text-secondary leading-relaxed fs-6" style="text-align: justify;">
                Satu Data Kabupaten Madiun merupakan bentuk pemenuhan amanah <strong>Peraturan Presiden Nomor 39 Tahun 2019 tentang Satu Data Indonesia</strong>. 
                Pemerintah Kabupaten Madiun memiliki <strong>Peraturan Bupati Madiun Nomor 9 Tahun 2024 tentang Satu Data Kabupaten Madiun</strong> yang mengatur mengenai Kebijakan Tata Kelola Data di Pemerintah Kabupaten Madiun. Hal ini sebagai bentuk upaya untuk menghasilkan tata kelola data yang akurat, mutakhir, terpadu, dan dapat dipertanggungjawabkan, serta mudah dibagi pakai antar Instansi Pusat dan Daerah.
            </p>

            <div class="my-4 p-4 rounded-3 bg-light border-start border-4 border-primary">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-check-circle text-primary me-2"></i>Prinsip Satu Data Indonesia:</h5>
                <ol class="mb-0 text-secondary ps-3" style="line-height: 1.8;">
                    <li>Memenuhi <strong>Standar Data</strong>,</li>
                    <li>Memiliki <strong>Metadata</strong> yang baku,</li>
                    <li>Memenuhi kaidah <strong>Interoperabilitas Data</strong>, dan</li>
                    <li>Menggunakan <strong>Kode Referensi dan/atau Data Induk</strong>.</li>
                </ol>
            </div>

            <p class="text-secondary leading-relaxed fs-6" style="text-align: justify;">
                Portal Satu Data Kabupaten Madiun merupakan portal resmi data terbuka Pemerintah Kabupaten Madiun. Portal Satu Data Kabupaten Madiun digunakan sebagai media penyelenggaraan Satu Data Kabupaten Madiun, mulai dari <strong>Perencanaan Data, Pengumpulan Data, Pemeriksaan Data, dan Penyebarluasan Data</strong> sesuai amanah Peraturan Presiden Nomor 39 Tahun 2019 tentang Satu Data Indonesia. Portal Satu Data Kabupaten Madiun sudah terintegrasi dengan Portal Satu Data Indonesia tingkat Provinsi dan Nasional.
            </p>

            <p class="text-secondary leading-relaxed fs-6" style="text-align: justify;">
                Seluruh Data yang tersedia pada Portal Satu Data Kabupaten Madiun bersifat <strong>Netral / Tidak Memihak dan diperuntukkan untuk Semua Pengguna Data</strong>. Data yang tersedia juga dipastikan bebas dari campur tangan dan kepentingan pihak luar.
            </p>

            <div class="alert alert-info border-0 rounded-3 d-flex align-items-center mt-3 mb-0" role="alert">
                <i class="fas fa-shield-alt fa-2x text-info me-3"></i>
                <div class="text-secondary small">
                    Portal Satu Data Kabupaten Madiun menjamin kerahasiaan dan melindungi data-data individu dari penyalahgunaan pihak-pihak yang tidak bertanggung jawab sesuai ketentuan perundang-undangan.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
