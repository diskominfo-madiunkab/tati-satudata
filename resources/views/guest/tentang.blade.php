@extends('guest.layout')

@section('content')
<section class="uni-banner">
    <div class="container">
        <div class="uni-banner-text-area">
            <h1>Tentang</h1>
            <ul>
                <li><a href="{{('/')}}">Beranda</a></li>
                <li>Tentang</li>
            </ul>
        </div>
    </div>
</section>


<div class="terms ptb-100">
    <div class="container">
        {{-- <h1 style="text-align: center;">Tentang</h1> --}}
        <h1 style="margin-top: 40px;">Tentang Satu Data Kabupaten Madiun</h1>
        <p style="text-align: justify;">
            Satu Data Kabupaten Madiun merupakan bentuk pemenuhan amanah <strong>Peraturan Presiden Nomor 39 Tahun 2019
                tentang
                Satu Data Indonesia</strong>.
            Pemerintah Kabupaten Madiun memiliki <strong>Peraturan Bupati Madiun Nomor 9 Tahun 2024 tentang Satu Data
                Kabupaten
                Madiun</strong> yang mengatur
            mengenai Kebijakan Tata Kelola Data di Pemerintah Kabupaten Madiun. Hal ini sebagai bentuk upaya untuk
            menghasilkan
            tata kelola data yang akurat,
            mutakhir, terpadu, dan dapat dipertanggungjawabkan, serta mudah dibagi pakai antar Instansi Pusat dan
            Daerah.
        </p>
        <p style="text-align: justify;">Prinsip Satu Data Indonesia, yaitu:</p>
        <ul style="list-style-type: decimal; margin-left: 40px;">
            <li>memenuhi <strong>Standar Data</strong>,</li>
            <li>memiliki <strong>Metadata</strong>,</li>
            <li>memenuhi kaidah <strong>Interoperabilitas Data</strong>, dan</li>
            <li>menggunakan <strong>Kode Referensi dan/atau Data Induk</strong>.</li>
        </ul>
        <p style="text-align: justify;">
            Portal Satu Data Kabupaten Madiun merupakan portal resmi data terbuka Pemerintah Kabupaten Madiun. Portal
            Satu Data
            Kabupaten Madiun digunakan
            sebagai media penyelenggaraan Satu Data Kabupaten Madiun, mulai dari <strong>Perencanaan Data, Pengumpulan
                Data,
                Pemeriksaan Data, dan
                Penyebarluasan Data</strong> sesuai amanah Peraturan Presiden Nomor 39 Tahun 2019 tentang Satu Data
            Indonesia.
            Portal Satu Data Kabupaten Madiun
            sudah terintegrasi dengan Portal Satu Data Indonesia tingkat Provinsi dan Nasional.
        </p>
        <p style="text-align: justify;">
            Seluruh Data yang tersedia pada Portal Satu Data Kabupaten Madiun bersifat <strong>Netral/Tidak Memihak dan
                diperuntukkan untuk Semua Pengguna Data</strong>.
            Data yang tersedia juga dipastikan bebas dari campur tangan dan kepentingan pihak luar.
        </p>

    </div>
</div>
@endsection