<!DOCTYPE html>
<html lang="zxx">

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
                            <li><a href="{{url('/')}}"><i class="fas fa-envelope"></i> <span class=""
                                        data-cfemail="">diskominfo@madiunkab.go.id</span></a>
                            </li>
                            <li><a href="https://goo.gl/maps/MHqcbdfZxLF7Rmxs6"><i
                                        class="fas fa-map-marker-alt"></i>Kabupaten Madiun, Jawa
                                    Timur</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="topbar-right-area">
                        <ul>
                            {{-- <li><a href="{{ url('/clear-cache') }}"><i class="fas fa-user"
                                        style="color:red"></i></a>
                            </li> --}}
                            <li><a href="{{route('login')}}"><i class="fas fa-user"></i> Login</a></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="navbar-area">

        <div class="main-responsive-nav">
            <div class="container-fluid plr-50">
                <div class="mobile-nav" style="background-color: red">
                    <a href="{{url('/')}}" class="logo"><img style="width: 10%; height:auto"
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
                                <a class="nav-link {{ request()->routeIs('/') ? 'active' : null }}" href="/">Beranda</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::segment(1) === 'dataset' ? 'active' : null }}"
                                    href="{{route('dataset')}}">Dataset</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::segment(1) === 'publikasi-guest' ? 'active' : null }}"
                                    href="{{route('guest.publikasi')}}">Publikasi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::segment(1) === 'infografis-guest' ? 'active' : null }}"
                                    href="{{route('guest.infografis')}}">Infografis</a>
                            </li>
                            <li class="nav-item" style="min-width: 114px">
                                <a class="nav-link" href="{{config('ckan_api.url')}}" target="_blank">Open-Data</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="http://madiunkab.ina-sdi.or.id/" target="_blank">Geoportal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::segment(1) === 'tentang' ? 'active' : null }}"
                                    href="/tentang">Tentang</a>
                            </li>
                        </ul>
                        <div class="menu-sidebar">
                            <ul>
                                <li><a class="nav-link" href="{{route('dataset')}}"><i class="fas fa-search"></i></a>
                                </li>
                                {{-- <li><a class="default-button" href="contact.html">Get in Touch</a></li> --}}
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    @yield('header')

    {{-- content --}}

    @yield('content')

    {{-- end content --}}

    <section class="footer">
        <div class="container">
            <div class="footer-content ptb-100">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="footer-logo-area">
                            <a href="{{url('/')}}"><img style="width:100px; height:auto"
                                    src="{{asset('landing-assets/img/services/120x120.png')}}" alt="image"></a>
                            <h4 style="color: white">KABUPATEN MADIUN</h4>
                            <p>Portal Data Terpadu Pemkab Madiun</p>
                            <div class="footer-social-area">
                                <ul>
                                    <li><span>Sosial Media: </span></li>
                                    <li><a href="https://www.facebook.com/pemkab.madiun/" target="_blank"><i
                                                class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="https://www.youtube.com/channel/UCv2HWvm0mF1gHJ327SMhn1Q"
                                            target="_blank"><i class="fab fa-youtube"></i></a></li>
                                    <li><a href="https://twitter.com/pemkab_madiun" target="_blank"><i
                                                class="fab fa-twitter"></i></a></li>
                                    <li><a href="https://www.instagram.com/pemkabmadiun/" target="_blank"><i
                                                class="fab fa-instagram"></i></a></li>
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
                                <p><a href="https://goo.gl/maps/MHqcbdfZxLF7Rmxs6" target="_blank">Jl. Mastrip No.23
                                        Kel. Klegen Kec. Kartoharjo Kota Madiun – Jawa Timur 63117, Indonesia</a></p>
                            </div>
                            <div class="footer-contact-card">
                                <i class="fas fa-envelope"></i>
                                <h5>Email: </h5>
                                <p><a href=""><span class="" data-cfemail="">diskominfo@madiunkab.go.id</span></a>
                                </p>
                            </div>
                            <div class="footer-contact-card">
                                <i class="fas fa-phone-alt"></i>
                                <h5>Telp: </h5>
                                <p><a href="">0351-462927</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-links footer-quick-links">
                            <h3>Akses Cepat</h3>
                            <ul>
                                <li>
                                    <i class="fas fa-angle-right"></i><a href="/">Beranda</a>
                                </li>
                                <li>
                                    <i class="fas fa-angle-right"></i><a href="{{route('dataset')}}">Dataset</a>
                                </li>
                                <li>
                                    <i class="fas fa-angle-right"></i><a
                                        href="{{route('guest.publikasi')}}">Publikasi</a>
                                </li>
                                <li>
                                    <i class="fas fa-angle-right"></i><a
                                        href="{{route('guest.infografis')}}">Infografis</a>
                                </li>
                                <li>
                                    <i class="fas fa-angle-right"></i><a href="{{config('ckan_api.url')}}"
                                        target="_blank">Open-Data</a>
                                </li>
                                <li>
                                    <i class="fas fa-angle-right"></i><a href="/tentang">Tentang</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6 col-12">
                        <?php 
                                        $day=date("j");
                                        $month=date("n");
                                        $year=date("Y"); 
                                    ?>
                        <p style="color: white">Pengunjung Tahun {{date('Y')}}</p>
                        <p style="color: white">Hari ini:
                            <?= App\Models\Visitor::where('nama','pengunjung')->where('tgl', $day)->where('bln', $month)->where('thn', $year)->sum('jumlah'); ?>
                            Orang
                        </p>
                        <p style="color: white">Bulan ini:
                            <?= App\Models\Visitor::where('nama','pengunjung')->where('bln', $month)->where('thn', $year)->sum('jumlah'); ?>
                            Orang
                        </p>
                        <p style="color: white">Tahun ini:
                            <?= App\Models\Visitor::where('nama','pengunjung')->where('thn', $year)->sum('jumlah'); ?>
                            Orang
                        </p>
                        <p style="color: white">Total Pengunjung:
                            <?= App\Models\Visitor::where('nama','pengunjung')->sum('jumlah'); ?> Orang
                        </p>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-links footer-newsletter">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.3095426230657!2d111.65157501474695!3d-7.541181294561197!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e79c7dca41021df%3A0xc15f5ab78e034eeb!2sPusat%20Pemerintahan%20Kabupaten%20Madiun!5e0!3m2!1sid!2sid!4v1618635895432!5m2!1sid!2sid"
                                width="300" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>©Copyright {{date('Y')}}. PEMERINTAH
                    KABUPATEN MADIUN<a target="_blank" href=""></a></p>
            </div>
        </div>
    </section>

    <div class="popup">
        <div class="popup-content">
            <button class="close-btn" id="popup-close"><i class="fas fa-times"></i></button>
            <form action="{{route('dataset')}}">
                <div class="input-group search-box">

                    <input type="text" class="form-control" placeholder="Cari Dataset">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>

                </div>
            </form>
        </div>
    </div>



    <div class="go-top"><i class="fas fa-chevron-up"></i></div>

    {{-- <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js">
    </script> --}}
    @stack('js')
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
</body>

</html>
