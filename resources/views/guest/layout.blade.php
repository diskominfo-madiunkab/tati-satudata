<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{asset('assets-guest/css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/css/fontawsome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/fonts/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/css/meanmenu.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/css/nice-select.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/css/magnific-popup.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/css/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/css/odometer.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets-guest/css/responsive.css')}}">
    <title>Satu Data Kabupaten Madiun</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    @stack('style')
</head>

<body>

    <section class="topbar plr-100 bg-black">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="topbar-left-area">
                        <ul>
                            <li><a href="{{url('/')}}"><i class="fas fa-envelope"></i> <span>diskominfo@madiunkab.go.id</span></a>
                            </li>
                            <li><a href="https://goo.gl/maps/MHqcbdfZxLF7Rmxs6" target="_blank"><i
                                        class="fas fa-map-marker-alt"></i>Kabupaten Madiun, Jawa Timur</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="topbar-right-area">
                        <ul>
                            <li><a href="{{route('login')}}"><i class="fas fa-user"></i> Login DAPUR SDI</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="navbar-area">
        <div class="main-responsive-nav">
            <div class="container-fluid plr-50">
                <div class="mobile-nav" style="background-color: #0d3b66">
                    <a href="{{url('/')}}" class="logo"><img style="width: 20%; height:auto"
                            src="/landing-assets/img/services/logo2.png" alt="logo" /></a>
                </div>
            </div>
        </div>

        <div class="main-nav plr-100">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-md navbar-light">
                    <a class="navbar-brand" href="{{url('/')}}">
                        <img style="width:30%; height:auto" src="/landing-assets/img/services/logo2.png" alt="logo" />
                    </a>
                    <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Beranda</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('katalog-data*') ? 'active' : '' }}" href="{{ route('guest.katalog') }}">Katalog Data</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('dataset*') ? 'active' : '' }}" href="{{ route('dataset') }}">Dataset</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('kode-referensi*') ? 'active' : '' }}" href="{{ route('guest.kode-referensi') }}">Kode Referensi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('publikasi-guest*') ? 'active' : '' }}" href="{{ route('guest.publikasi') }}">Publikasi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('infografis-guest*') ? 'active' : '' }}" href="{{ route('guest.infografis') }}">Infografis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('regulasi*') ? 'active' : '' }}" href="{{ route('guest.regulasi') }}">Regulasi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('geoportal*') ? 'active' : '' }}" href="{{ route('guest.geoportal') }}">Geoportal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('tentang*') ? 'active' : '' }}" href="/tentang">Tentang</a>
                            </li>
                        </ul>
                        <div class="menu-sidebar">
                            <ul>
                                <li><a class="nav-link" href="{{route('dataset')}}"><i class="fas fa-search"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    @yield('header')

    @yield('content')

    <section class="footer pt-100 pb-70 bg-black">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="footer-logo-area">
                        <a href="/"><img src="/landing-assets/img/services/logo2.png" alt="image"></a>
                        <p>Portal Resmi Satu Data Indonesia (SDI) Kabupaten Madiun untuk mewujudkan keterpaduan perencanaan, pelaksanaan, evaluasi, dan pengendalian pembangunan.</p>
                        <div class="footer-social-icons">
                            <span>Ikuti Kami:</span>
                            <ul>
                                <li><a href="https://www.facebook.com/pemkab.madiun/" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="https://www.youtube.com/channel/UCv2HWvm0mF1gHJ327SMhn1Q" target="_blank"><i class="fab fa-youtube"></i></a></li>
                                <li><a href="https://twitter.com/pemkab_madiun" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="https://www.instagram.com/pemkabmadiun/" target="_blank"><i class="fab fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="footer-links footer-contact">
                        <h3>Hubungi Kami</h3>
                        <div class="footer-contact-card">
                            <i class="fas fa-map-marker-alt"></i>
                            <h5>Alamat: </h5>
                            <p><a href="https://goo.gl/maps/MHqcbdfZxLF7Rmxs6" target="_blank">Jl. Mastrip No.23 Kel. Klegen Kec. Kartoharjo Kota Madiun – Jawa Timur 63117</a></p>
                        </div>
                        <div class="footer-contact-card">
                            <i class="fas fa-envelope"></i>
                            <h5>Email: </h5>
                            <p><a href="mailto:diskominfo@madiunkab.go.id">diskominfo@madiunkab.go.id</a></p>
                        </div>
                        <div class="footer-contact-card">
                            <i class="fas fa-phone-alt"></i>
                            <h5>Telp: </h5>
                            <p>0351-462927</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="footer-links footer-quick-links">
                        <h3>Akses Cepat</h3>
                        <ul>
                            <li><i class="fas fa-angle-right"></i><a href="/">Beranda</a></li>
                            <li><i class="fas fa-angle-right"></i><a href="{{ route('guest.katalog') }}">Katalog Data</a></li>
                            <li><i class="fas fa-angle-right"></i><a href="{{ route('dataset') }}">Dataset</a></li>
                            <li><i class="fas fa-angle-right"></i><a href="{{ route('guest.kode-referensi') }}">Kode Referensi</a></li>
                            <li><i class="fas fa-angle-right"></i><a href="{{ route('guest.publikasi') }}">Publikasi</a></li>
                            <li><i class="fas fa-angle-right"></i><a href="{{ route('guest.infografis') }}">Infografis</a></li>
                            <li><i class="fas fa-angle-right"></i><a href="{{ route('guest.regulasi') }}">Regulasi</a></li>
                            <li><i class="fas fa-angle-right"></i><a href="{{ route('guest.geoportal') }}">Geoportal</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="footer-links footer-contact">
                        <h3>Statistik Pengunjung</h3>
                        @php
                            $day = date("j");
                            $month = date("n");
                            $year = date("Y"); 
                        @endphp
                        <p class="text-white mb-1"><i class="fas fa-user-clock me-2"></i>Hari ini: <strong>{{ App\Models\Visitor::where('nama','pengunjung')->where('tgl', $day)->where('bln', $month)->where('thn', $year)->sum('jumlah') }}</strong></p>
                        <p class="text-white mb-1"><i class="fas fa-calendar-alt me-2"></i>Bulan ini: <strong>{{ App\Models\Visitor::where('nama','pengunjung')->where('bln', $month)->where('thn', $year)->sum('jumlah') }}</strong></p>
                        <p class="text-white mb-1"><i class="fas fa-calendar me-2"></i>Tahun {{ date('Y') }}: <strong>{{ App\Models\Visitor::where('nama','pengunjung')->where('thn', $year)->sum('jumlah') }}</strong></p>
                        <p class="text-white"><i class="fas fa-users me-2"></i>Total: <strong>{{ App\Models\Visitor::where('nama','pengunjung')->sum('jumlah') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>©Copyright {{date('Y')}}. PEMERINTAH KABUPATEN MADIUN</p>
        </div>
    </section>

    <div class="go-top"><i class="fas fa-chevron-up"></i></div>

    <script src="{{asset('assets-guest/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets-guest/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets-guest/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets-guest/js/meanmenu.js')}}"></script>
    <script src="{{asset('assets-guest/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('assets-guest/js/magnific-popup.min.js')}}"></script>
    <script src="{{asset('assets-guest/js/TweenMax.js')}}"></script>
    <script src="{{asset('assets-guest/js/nice-select.min.js')}}"></script>
    <script src="{{asset('assets-guest/js/form-validator.min.js')}}"></script>
    <script src="{{asset('assets-guest/js/contact-form-script.js')}}"></script>
    <script src="{{asset('assets-guest/js/ajaxchimp.min.js')}}"></script>
    <script src="{{asset('assets-guest/js/owl.carousel2.thumbs.min.js')}}"></script>
    <script src="{{asset('assets-guest/js/appear.min.js')}}"></script>
    <script src="{{asset('assets-guest/js/odometer.min.js')}}"></script>
    <script src="{{asset('assets-guest/js/custom.js')}}"></script>
    <script src="{{asset('landing-assets/vendor/wow/wow.min.js')}}"></script>
    @stack('js')
</body>
</html>
