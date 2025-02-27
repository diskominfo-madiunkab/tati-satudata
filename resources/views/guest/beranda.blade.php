@extends('guest.layout')

@section('content')

    {{-- style="background-image: url('assets-guest/images/2.jpg')" --}}
    {{-- <section class="main-banner plr-100 bg-f9fbfe"
    style="background-image: url('assets-guest/images/fix2.jpg'); background-size: cover;">


    <div class="banner-social-icons">
        <ul>
            <li><a href="https://www.facebook.com/pemkab.madiun/" target="_blank"><i class="fab fa-facebook-f"></i></a>
            </li>
            <li><a href="https://www.youtube.com/channel/UCv2HWvm0mF1gHJ327SMhn1Q" target="_blank"><i
                        class="fab fa-youtube"></i></a></li>
            <li><a href="https://twitter.com/pemkab_madiun" target="_blank"><i class="fab fa-twitter"></i></a></li>
            <li><a href="https://www.instagram.com/pemkabmadiun/" target="_blank"><i class="fab fa-instagram"></i></a>
            </li>
        </ul>
    </div>
    <div class="container-fluid" style="margin-top: 5%; margin-bottom: 8%">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="banner-text-area banner-text-area-1">
                    <h6 style="font-size: 30px">PORTAL</h6>
                    <h1>SATU DATA INDONESIA</h1>
                    <h1 style="font-size: 40px">PEMERINTAH KABUPATEN MADIUN</h1>
                    <p>Media penyelenggaraan Satu Data Indonesia, mulai dari perencanaan, pengumpulan, pemeriksaan, dan
                        penyebarluasan
                        data sesuai amanah
                        Peraturan Presiden 39 tahun 2019 tentang Satu Data Indonesia
                    </p>
                    <a class="default-button" href="#cari" data-role="smoothscroll"><i class="fas fa-search"></i> Cari
                        Data</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="banner-img-area-1" alt="image">

                    <img src="{{asset('assets-guest/images/edit 2.jpg')}}" alt="image">

                </div>
            </div>
        </div>
    </div>
</section> --}}
    <style>
        /* Gaya umum untuk gambar responsif */
        .responsive-image {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Gaya untuk section dengan kelas "my-section" */
        .my-section {
            position: relative;
            margin-top: -35px;
        }

        .main-banner {
            position: relative;
            width: 100%;
            max-width: 100%;
            height: auto;
        }

        /* Gaya untuk tombol yang mengambang di atas gambar */
        .floating-button {
            position: absolute;
            bottom: 250px;
            /* Jarak dari bawah (atur sesuai kebutuhan) */
            left: 10%;
            /* Posisi horizontal (tengah) */
            transform: translateX(-50%);
            /* Menggeser tombol ke kiri agar posisi tengah sesuai */
            /* background-color: #f44336; */
            /* Warna latar tombol */
            color: #fff;
            /* Warna teks tombol */
            padding: 12px 24px;
            /* Padding tombol */
            border: none;
            /* Hapus garis tepi tombol */
            border-radius: 4px;
            /* Mengatur sudut tombol */
        }

        .floating-button i {
            margin-right: 8px;
            /* Margin ikon agar ada jarak dari teks tombol */
        }
    </style>
    <section class="my-section">
        <div class="main-banner">
            <img src="{{ asset('assets-guest\images\8.jpg') }}" alt="image" class="responsive-image">
            {{-- <div class="floating-button">
            <a class="default-button" href="#cari" data-role="smoothscroll">
                <i class="fas fa-search"></i> Cari Data
            </a>
        </div> --}}
        </div>
    </section>


    {{-- <section class="feedback ptb-100">
    <div class="container">
        <div class="default-section-title default-section-title-middle mt-10">
            <h3>INFOGRAFIS</h3>
            <p>Infografis Portal Satu Data Kabupaten Madiun.<a href="{{route('guest.infografis')}}"
                    style="color: #59c3fd">Liat Selengkapnya</a>
            </p>
        </div>
        <div class="section-content">
            <div class="feedback-slider-area-2 owl-carousel">
                @foreach ($infografis as $infos)
                <div class="feedback-2 mlr-15 mb-30">
                    <div class="blog-card bg-f9fbfe">
                        <div class="blog-card-img" style="height: 350px; width: auto">
                            <a href="{{ route('guest.infografis.detail', ['id' => $infos->id]) }}"><img
                                    src="{{ Storage::url('public/blogs/').$infos->image }}" alt="image"></a>
                        </div>
                        <div class="blog-card-text-area">
                            <div class="blog-date">
                                <ul>
                                    <li><i class="fas fa-user"></i>Dibuat Oleh Admin</li>
                                    <li><i class="far fa-calendar-alt"></i> {{ date('d-M-Y H:i',
                                        strtotime($infos->created_at)) }}</li>
                                </ul>
                            </div>
                            <h4><a href="{{ route('guest.infografis.detail', ['id' => $infos->id]) }}">{{ $infos->title
                                    }}</a></h4>
                            <a class="read-more-btn"
                                href="{{ route('guest.infografis.detail', ['id' => $infos->id]) }}">Detail</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section> --}}

    <section class="fun-facts fun-facts-1 pt-100 pb-100">
        <div class="container">
            <div class="row">
                @foreach ($demografis as $dt)
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                        <div class="fun-facts-card">
                            {!! $dt->icon !!}
                            <h2><span class="" data-count="{{ $dt->jml_data }}">{{ $dt->jml_data }}</span></h2>
                            <p>{{ $dt->narasi_data }} <br>
                                {{ $dt->narasi_1 }} {{ $dt->jml_narasi_1 }}
                                <br>
                                {{ $dt->narasi_2 }} {{ $dt->jml_narasi_2 }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- <section class="feedback ptb-100">
    <div class="container">
        <div class="default-section-title default-section-title-middle mt-10">
            <h3>Publikasi</h3>
            <p>Publikasi Portal Satu Data Kabupaten Madiun.<a href="{{route('guest.publikasi')}}"
                    style="color: #59c3fd">Liat Selengkapnya</a>
            </p>
        </div>
        <div class="section-content">
            <div class="feedback-slider-area-2 owl-carousel">
                @foreach ($publikasi as $publ)
                <div class="feedback-2 mlr-15 mb-30">
                    <div class="blog-card bg-f9fbfe">
                        <div class="blog-card-img" style="height: 350px; width: auto">
                            <a href="{{ route('guest.publikasi.detail', ['id' => $publ->id]) }}"><img
                                    src="{{ Storage::url('public/blogs/').$publ->image }}" alt="image"></a>
                        </div>
                        <div class="blog-card-text-area">
                            <div class="blog-date">
                                <ul>
                                    <li><i class="fas fa-user"></i>Dibuat Oleh Admin</li>
                                    <li><i class="far fa-calendar-alt"></i> {{ date('d-M-Y H:i',
                                        strtotime($publ->created_at)) }}</li>
                                </ul>
                            </div>

                            <div class="row">
                                <div>
                                    <h4><a href="{{ route('guest.publikasi.detail', ['id' => $publ->id]) }}">{{
                                            $publ->title
                                            }}</a></h4>
                                    <img style="height: 50px; width: auto"
                                        src="{{asset('assets-guest/images/pdf.png')}}" alt="image">

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section> --}}

    <section class="we-are pb-80 pt-70 bg-f9fbfe">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="why-we">
                        {{-- <img src="assets-guest/images/why-we/ww1.jpg" alt="image"> --}}
                        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                        <lottie-player src="https://assets8.lottiefiles.com/packages/lf20_8rs5Fb08t9.json"
                            background="transparent" speed="1" style="width: auto; height: 500px;" loop autoplay>
                        </lottie-player>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="why-we-text-area">
                        <div class="default-section-title">
                            {{-- <span>WHO WE ARE</span> --}}
                            <h3>DATASET</h3>
                            <h3>KABUPATEN MADIUN</h3>
                            <p>Temukan data yang Anda butuhkan mengenai Kabupaten Madiun dengan mudah dimana saja. Kini
                                telah tersedia {{ $data['result']['count'] }} dataset yang dapat Anda akses bersama Portal
                                Satu Data Kabupaten Madiun.</p>
                        </div>

                        {{-- <div class="why-we-text-list"> --}}
                        {{-- <section class="fun-facts bg-f9fbfe"> --}}
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-5 col-md-6 col-sm-6 col-6">
                                    <div class="fun-facts-card fun-facts-card-2">
                                        <a href="{{ route('dataset') }}">
                                            <i class="flaticon-download-file"></i>
                                            <h2><span class="odometer"
                                                    data-count="{{ $data['result']['count'] }}">{{ $data['result']['count'] }}</span>
                                            </h2>
                                            <p>Jumlah Dataset</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6 col-sm-6 col-6">
                                    <div class="fun-facts-card fun-facts-card-2">
                                        <a href="http://ckan-data.madiunkab.go.id/organization/" target="_blank">
                                            <i class="flaticon-government-building"></i>
                                            <h2><span class="odometer"
                                                    data-count="{{ $opd }}">{{ $opd }}</span>
                                            </h2>
                                            <p>Jumlah Organisasi</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6 col-sm-6 col-6">
                                    <div class="fun-facts-card fun-facts-card-2">
                                        <a href="{{ route('guest.publikasi') }}">
                                            <i class="flaticon-world"></i>
                                            <h2><span class="odometer"
                                                    data-count="{{ $publikasi }}">{{ $publikasi }}</span>
                                            </h2>
                                            <p>Jumlah Publikasi</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6 col-sm-6 col-6">
                                    <div class="fun-facts-card fun-facts-card-2">
                                        <a href="{{ route('guest.infografis') }}">
                                            <i class="flaticon-customer-service"></i>
                                            <h2><span class="odometer"
                                                    data-count="{{ $infografis }}">{{ $infografis }}</span></h2>
                                            <p>Jumlah Infografis</p>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--
                        </section> --}}
                        {{--
                    </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="fun-facts fun-facts-1 pt-70 pb-100">
        <div class="container" id="cari">
            <div class="default-section-title default-section-title-middle mt-10">
                <h3 style="color: white">CARI DATASET</h3>
            </div>
            <div class="row">
                <form action="{{ route('dataset') }}">
                    <div class="input-group search-box">

                        <input style="border-radius: 10px" type="text" name="q" class="form-control"
                            placeholder="Cari Dataset">
                        <button style="border-radius: 10px" class="btn" type="submit"><i
                                class="fas fa-search"></i></button>

                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="services pt-100 pb-70">
        <div class="container">
            <div class="default-section-title default-section-title-middle">
                <h3>KELOMPOK DATA</h3>
                <p>Dibawah ini merupakan daftar dari kelompok data</p>
            </div>
            <div class="section-content">
                <div class="service-slider-area-1 owl-carousel">
                    @foreach ($groups as $group)
                        <div class="service-card mlr-15 mb-30">
                            <div class="service-card-img">
                                <a href="{{ route('dataset', ['group' => $group['name'] ?? '-']) }}">
                                    @if (!empty($group['image_display_url']))
                                        <div style="display: flex; justify-content: center; align-items: center;">
                                            <img style="height: 200px; width: auto;"
                                                src="{{ $group['image_display_url'] }}" alt="image">
                                        </div>
                                    @endif
                                    <i class="flaticon-google-docs"></i>
                                </a>
                            </div>
                            <div class="service-card-text">
                                <h4><a
                                        href="{{ route('dataset', ['group' => $group['name'] ?? '-']) }}">{{ $group['title'] }}</a>
                                </h4>
                                {{-- <h5 class="text-primary">{{$group['display_name'] ?? '-'}}</h5> --}}
                                <a class="read-more-btn"
                                    href="{{ route('dataset', ['group' => $group['name'] ?? '-']) }}">Detail</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <section class="services service-2 fun-facts-1 ptb-100 bg-222222">
        <div class="container">
            <div class="default-section-title default-section-title-middle">
                <h3>Penyediaan Data Statistik</h3>
                <h4 style="color: white; font-weight: bold">Dibawah ini merupakan daftar dari Data Statistik</h4>
            </div>
            <div class="section-content">
                <div class="service-slider-area owl-carousel">
                    @foreach ($boxvalue as $dt)
                        <div class="service-card-2"
                            style="min-height: 40vh; background-color: #f8f9fa; border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px; text-align: center; display: flex; flex-direction: column; justify-content: space-between;">
                            <div style="flex-grow: 1;">
                                <div style="margin-top: -15px;">
                                    {!! $dt->logo !!}
                                </div>
                                {{-- <i class="fa fa-users" aria-hidden="true"></i> --}}
                                <h3 style="color: #59c3fd; margin-top: 10px;"><a
                                        style="text-decoration: none; color: inherit;"
                                        href="{{ route('dataset.show', $dt->data->publikasi->dataset_id) }}">{{ $dt->judul }}</a>
                                </h3>
                                <b
                                    style="font-size: 40px; display: block; margin-top: 10px;">{{ $dt->ringkasan_nilai }}</b>
                                <p style="font-size: 16px; color: #6c757d;">{{ $dt->satuan }}</p>
                            </div>
                            {{-- <a class="read-more-btn"
                                style="margin-top: 15px; color: #fff; background-color: #007bff; padding: 10px 20px; border-radius: 25px; text-decoration: none; display: inline-block;"
                                href="{{ route('dataset.show', $dt->data->publikasi->dataset_id) }}">Lihat data</a> --}}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="contact-form-area pb-100 bg-f9fbfe">
        <div class="container">
            <div class="default-section-title default-section-title-middle" style="padding-top: 30px ">
                <h3>Usulan Data</h3>
                <p>Para penggguna dapat mengajukan permohonan usulan data</p>
            </div>
            <div class="section-content">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="google-map pr-20">
                            <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

                            <dotlottie-player
                                src="https://lottie.host/a89bfac3-17e9-45e7-ba9b-f1b468826f69/6DWH1qkrPz.json"
                                background="transparent" speed="1" style="width: 400px; height: 500px;" loop
                                autoplay>
                            </dotlottie-player>
                            {{-- <iframe class="g-map"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d78339.6186660101!2d-106.73462151445834!3d52.15045315715413!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5304f6bf47ed992b%3A0x5049e3295772690!2sSaskatoon%2C%20SK%2C%20Canada!5e0!3m2!1sen!2sbd!4v1629617114800!5m2!1sen!2sbd"></iframe>
                        --}}
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="contact-form-text-area">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                                <br />
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <br />
                            @endif
                            <form id="" action="{{ route('send.usulan') }}" method="POST">
                                @csrf

                                <div class="row align-items-center">
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Nama" id="name"
                                                name="nama" required data-error="Please enter your name">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Email" id="email" required
                                                data-error="Please enter your Email">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" name="kelompok" class="form-control" placeholder="Kelompok"
                                            id="kelompok" required data-error="Silahkan isikan kelompok">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" name="subjek" class="form-control" placeholder="Subjek"
                                            id="subjek" required data-error="Silahkan isikan subjek">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> --}}

                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <select name="kelamin" id="kelamin" class="form-control"
                                                data-error="Silahkan isi jenis kelamin" required>
                                                <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                                <option value="laki-laki">Laki-Laki</option>
                                                <option value="perempuan">Perempuan</option>
                                            </select>
                                            {{-- <input type="text" name="subjek" class="form-control" placeholder="Subjek"
                                            id="subjek" required data-error="Silahkan isikan subjek"> --}}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" name="no_hp" class="form-control"
                                                placeholder="Nomor Whatsapp" id="no_hp" required
                                                data-error="Silahkan isikan nomor WA" pattern="\d{10,15}" maxlength="15"
                                                required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <input type="text" name="pekerjaan" class="form-control"
                                                placeholder="Pekerjaan" id="pekerjaan" required
                                                data-error="Silahkan isikan pekerjaan">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <textarea name="usulan" id="usulan" class="form-control" placeholder="Usulan data..." cols="30"
                                                rows="5" required data-error="Silahkan isi usulan anda"></textarea>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-12">
                                        <!-- Add CAPTCHA widget here -->
                                        <div class="form-group">
                                            <label for="captcha">Captcha</label>
                                            <div class="captcha">
                                                <span>{!! captcha_img() !!}</span>
                                                <button type="button" class="btn btn-danger btn-sm" id="reload">
                                                    &#x21bb; Reload Captcha
                                                </button>
                                            </div>
                                            <input type="text" id="captcha" class="form-control mt-2"
                                                name="captcha" required data-error="Please enter the CAPTCHA"
                                                placeholder="Enter CAPTCHA">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12">
                                    <button class="default-button" type="submit"><span>Kirim Usulan</span></button>
                                    <div id="msgSubmit" class="h6 text-center hidden"></div>
                                    <div class="clearfix"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    @push('js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            $('#reload').click(function() {
                $.ajax({
                    type: 'GET',
                    url: '{{ url('reload-captcha') }}',
                    success: function(data) {
                        var timestamp = new Date()
                            .getTime(); // Generate unique timestamp to prevent caching
                        $('.captcha span img').attr('src', data.captcha + '?' +
                            timestamp); // Update the captcha image URL with timestamp
                    }
                });
            });
        </script>
    @endpush

@endsection
