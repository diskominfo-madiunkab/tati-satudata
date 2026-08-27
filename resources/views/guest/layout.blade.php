    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

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

    <style>
        :root {
            --sdi-font: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --sdi-primary: #0284c7;
            --sdi-primary-dark: #0369a1;
            --sdi-primary-light: #e0f2fe;
            --sdi-dark: #0f172a;
            --sdi-slate: #334155;
            --sdi-muted: #64748b;
        }

        body {
            font-family: var(--sdi-font) !important;
        }

        /* Topbar Modern Styling */
        .sdi-topbar {
            background: linear-gradient(90deg, #071527 0%, #0d233e 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
            padding: 8px 0;
            font-family: var(--sdi-font);
        }

        .sdi-topbar a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .sdi-topbar a:hover {
            color: #38bdf8;
        }

        .sdi-topbar .badge-official {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .sdi-topbar-btn {
            background: rgba(255, 255, 255, 0.1);
            color: #fff !important;
            padding: 4px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 12px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .sdi-topbar-btn:hover {
            background: #0284c7;
            border-color: #0284c7;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.4);
            color: #fff !important;
        }

        /* Modern Sticky Navbar */
        .sdi-navbar-wrapper {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 4px 20px -4px rgba(15, 23, 42, 0.08);
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .sdi-navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 74px;
        }

        .sdi-brand-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 12px;
            padding: 4px 0;
        }

        .sdi-brand-logo img {
            height: 46px;
            width: auto;
            object-fit: contain;
            transition: transform 0.2s ease;
        }

        .sdi-brand-logo:hover img {
            transform: scale(1.02);
        }

        /* Desktop Nav List */
        .sdi-nav-menu {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 4px;
        }

        .sdi-nav-item {
            position: relative;
        }

        .sdi-nav-link {
            font-family: var(--sdi-font) !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
            color: #475569 !important;
            padding: 8px 12px !important;
            border-radius: 8px;
            text-decoration: none !important;
            white-space: nowrap !important;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            line-height: 1.4;
        }

        .sdi-nav-link:hover {
            color: #0284c7 !important;
            background: rgba(2, 132, 199, 0.08) !important;
            transform: translateY(-1px);
        }

        .sdi-nav-link.active {
            color: #0284c7 !important;
            background: #e0f2fe !important;
            font-weight: 700 !important;
            box-shadow: inset 0 0 0 1px rgba(2, 132, 199, 0.25);
        }

        .sdi-nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 12px;
            right: 12px;
            height: 3px;
            background: #0284c7;
            border-radius: 3px;
        }

        /* Right Nav Actions */
        .sdi-nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sdi-search-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #64748b;
            padding: 7px 14px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .sdi-search-pill:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
            color: #0f172a;
            transform: translateY(-1px);
        }

        .sdi-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff !important;
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 14px -2px rgba(2, 132, 199, 0.35);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        .sdi-cta-btn:hover {
            background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
            box-shadow: 0 6px 18px -2px rgba(2, 132, 199, 0.5);
            transform: translateY(-1px);
            color: #ffffff !important;
        }

        /* Responsive overrides */
        @media (max-width: 1199px) {
            .sdi-nav-link {
                font-size: 13px !important;
                padding: 6px 9px !important;
            }
            .sdi-search-pill span {
                display: none;
            }
        }

        @media (max-width: 991px) {
            .sdi-desktop-nav {
                display: none !important;
            }
            .sdi-mobile-header {
                display: flex !important;
            }
        }

        @media (min-width: 992px) {
            .sdi-mobile-header {
                display: none !important;
            }
        }

        /* Modern Offcanvas Mobile Navigation */
        .sdi-mobile-header {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .sdi-hamburger-btn {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            color: #0f172a;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sdi-hamburger-btn:hover {
            background: #e2e8f0;
            color: #0284c7;
        }

        .sdi-mobile-drawer {
            position: fixed;
            top: 0;
            left: -100%;
            width: 82%;
            max-width: 320px;
            height: 100%;
            background: #ffffff;
            z-index: 1050;
            box-shadow: 10px 0 30px rgba(0,0,0,0.15);
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sdi-mobile-drawer.open {
            left: 0;
        }

        .sdi-drawer-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1040;
            display: none;
        }

        .sdi-drawer-backdrop.show {
            display: block;
        }

        .sdi-drawer-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sdi-drawer-body {
            padding: 16px;
            flex: 1;
        }

        .sdi-drawer-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #334155;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }

        .sdi-drawer-link:hover,
        .sdi-drawer-link.active {
            background: #f0f9ff;
            color: #0284c7;
        }

        .sdi-nav-dropdown {
            position: relative;
        }

        .sdi-nav-dropdown:hover .sdi-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .sdi-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 220px;
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            box-shadow: 0 12px 28px -4px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
            padding: 8px;
            list-style: none;
            margin: 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 1050;
        }

        .sdi-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.15s ease;
        }

        .sdi-dropdown-item:hover {
            background: #f0f9ff;
            color: #0284c7;
        }

        .sdi-dropdown-item.active {
            background: #e0f2fe;
            color: #0284c7;
            font-weight: 700;
        }

        .sdi-dropdown-item i {
            width: 18px;
            font-size: 14px;
            color: #0284c7;
        }

        .sdi-drawer-link i {
            width: 20px;
            color: #64748b;
            font-size: 15px;
        }

        .sdi-drawer-link.active i {
            color: #0284c7;
        }
    </style>
    @stack('style')
</head>

<body>

    <!-- TOPBAR RESMI SDI -->
    <header class="sdi-topbar d-none d-md-block">
        <div class="container-fluid px-lg-5">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge-official">
                        <i class="fas fa-shield-alt"></i> PORTAL RESMI
                    </span>
                    <span class="text-white-50">|</span>
                    <a href="mailto:sdi@madiunkab.go.id" class="d-inline-flex align-items-center gap-2">
                        <i class="fas fa-envelope text-info"></i>
                        <span>sdi@madiunkab.go.id</span>
                    </a>
                    <span class="text-white-50">|</span>
                    <a href="https://goo.gl/maps/MHqcbdfZxLF7Rmxs6" target="_blank" class="d-inline-flex align-items-center gap-2">
                        <i class="fas fa-map-marker-alt text-danger"></i>
                        <span>Kabupaten Madiun, Jawa Timur</span>
                    </a>
                </div>
                <div>
                    <span class="small text-white-50"><i class="fas fa-calendar-alt me-1"></i> {{ date('d F Y') }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN NAVBAR RESMI SDI -->
    <header class="sdi-navbar-wrapper">
        <!-- Desktop Nav -->
        <div class="sdi-navbar-container sdi-desktop-nav">
            <!-- Brand Logo -->
            <a class="sdi-brand-logo" href="{{ url('/') }}">
                <img src="/landing-assets/img/services/logo2.png" alt="Satu Data Kabupaten Madiun" />
            </a>

            <!-- Navigation Links -->
            <nav>
                <ul class="sdi-nav-menu">
                    <li class="sdi-nav-item">
                        <a class="sdi-nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            Beranda
                        </a>
                    </li>
                    <li class="sdi-nav-item">
                        <a class="sdi-nav-link {{ request()->is('katalog-data*') ? 'active' : '' }}" href="{{ route('guest.katalog') }}">
                            Katalog Data
                        </a>
                    </li>
                    <li class="sdi-nav-item">
                        <a class="sdi-nav-link {{ request()->is('dataset*') ? 'active' : '' }}" href="{{ route('dataset') }}">
                            Dataset
                        </a>
                    </li>
                    <li class="sdi-nav-item">
                        <a class="sdi-nav-link {{ request()->is('kode-referensi*') ? 'active' : '' }}" href="{{ route('guest.kode-referensi') }}">
                            Kode Referensi
                        </a>
                    </li>
                    <!-- Dropdown Menu Publikasi (Buku, Infografis, Visualisasi) -->
                    <li class="sdi-nav-item sdi-nav-dropdown">
                        <a class="sdi-nav-link {{ (request()->is('publikasi-guest*') || request()->is('infografis-guest*') || request()->is('visualisasi-guest*')) ? 'active' : '' }}" href="#" role="button">
                            Publikasi <i class="fas fa-chevron-down ms-1" style="font-size: 11px;"></i>
                        </a>
                        <ul class="sdi-dropdown-menu">
                            <li>
                                <a class="sdi-dropdown-item {{ request()->is('publikasi-guest*') ? 'active' : '' }}" href="{{ route('guest.publikasi') }}">
                                    <i class="fas fa-book"></i>
                                    <span>Buku</span>
                                </a>
                            </li>
                            <li>
                                <a class="sdi-dropdown-item {{ request()->is('infografis-guest*') ? 'active' : '' }}" href="{{ route('guest.infografis') }}">
                                    <i class="fas fa-image"></i>
                                    <span>Infografis</span>
                                </a>
                            </li>
                            <li>
                                <a class="sdi-dropdown-item {{ request()->is('visualisasi-guest*') ? 'active' : '' }}" href="{{ route('guest.visualisasi') }}">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Visualisasi</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sdi-nav-item">
                        <a class="sdi-nav-link {{ request()->is('regulasi*') ? 'active' : '' }}" href="{{ route('guest.regulasi') }}">
                            Regulasi
                        </a>
                    </li>
                    <li class="sdi-nav-item">
                        <a class="sdi-nav-link {{ request()->is('geoportal*') ? 'active' : '' }}" href="{{ route('guest.geoportal') }}">
                            Geoportal
                        </a>
                    </li>
                    <li class="sdi-nav-item">
                        <a class="sdi-nav-link {{ request()->is('tentang*') ? 'active' : '' }}" href="/tentang">
                            Tentang
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Right Actions -->
            <div class="sdi-nav-actions">
                <a href="{{ route('dataset') }}" class="sdi-search-pill" title="Cari Dataset">
                    <i class="fas fa-search"></i>
                    <span>Cari Data</span>
                </a>
                <a href="{{ route('login') }}" class="sdi-cta-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk Portal</span>
                </a>
            </div>
        </div>

        <!-- Mobile Header -->
        <div class="sdi-mobile-header">
            <a class="sdi-brand-logo" href="{{ url('/') }}">
                <img src="/landing-assets/img/services/logo2.png" alt="Satu Data Kabupaten Madiun" style="height: 38px;" />
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('dataset') }}" class="btn btn-sm btn-light rounded-circle p-2" title="Cari Data">
                    <i class="fas fa-search text-primary"></i>
                </a>
                <button type="button" class="sdi-hamburger-btn" id="sdiMobileMenuBtn" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Offcanvas Drawer -->
    <div class="sdi-drawer-backdrop" id="sdiDrawerBackdrop"></div>
    <div class="sdi-mobile-drawer" id="sdiMobileDrawer">
        <div class="sdi-drawer-header">
            <img src="/landing-assets/img/services/logo2.png" alt="Satu Data Kabupaten Madiun" style="height: 36px;" />
            <button type="button" class="btn-close" id="sdiDrawerCloseBtn" aria-label="Close"></button>
        </div>
        <div class="sdi-drawer-body">
            <a class="sdi-drawer-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                <i class="fas fa-home"></i> <span>Beranda</span>
            </a>
            <a class="sdi-drawer-link {{ request()->is('katalog-data*') ? 'active' : '' }}" href="{{ route('guest.katalog') }}">
                <i class="fas fa-book-open"></i> <span>Katalog Data</span>
            </a>
            <a class="sdi-drawer-link {{ request()->is('dataset*') ? 'active' : '' }}" href="{{ route('dataset') }}">
                <i class="fas fa-database"></i> <span>Dataset</span>
            </a>
            <a class="sdi-drawer-link {{ request()->is('kode-referensi*') ? 'active' : '' }}" href="{{ route('guest.kode-referensi') }}">
                <i class="fas fa-list-ol"></i> <span>Kode Referensi</span>
            </a>
            <div class="px-3 py-1 text-muted small fw-bold text-uppercase mt-2">Publikasi</div>
            <a class="sdi-drawer-link ps-4 {{ request()->is('publikasi-guest*') ? 'active' : '' }}" href="{{ route('guest.publikasi') }}">
                <i class="fas fa-book"></i> <span>Buku</span>
            </a>
            <a class="sdi-drawer-link ps-4 {{ request()->is('infografis-guest*') ? 'active' : '' }}" href="{{ route('guest.infografis') }}">
                <i class="fas fa-image"></i> <span>Infografis</span>
            </a>
            <a class="sdi-drawer-link ps-4 {{ request()->is('visualisasi-guest*') ? 'active' : '' }}" href="{{ route('guest.visualisasi') }}">
                <i class="fas fa-chart-line"></i> <span>Visualisasi</span>
            </a>
            <div class="border-top my-2"></div>
            <a class="sdi-drawer-link {{ request()->is('regulasi*') ? 'active' : '' }}" href="{{ route('guest.regulasi') }}">
                <i class="fas fa-gavel"></i> <span>Regulasi</span>
            </a>
            <a class="sdi-drawer-link {{ request()->is('geoportal*') ? 'active' : '' }}" href="{{ route('guest.geoportal') }}">
                <i class="fas fa-map-marked-alt"></i> <span>Geoportal</span>
            </a>
            <a class="sdi-drawer-link {{ request()->is('tentang*') ? 'active' : '' }}" href="/tentang">
                <i class="fas fa-info-circle"></i> <span>Tentang</span>
            </a>

            <div class="mt-4 pt-3 border-top">
                <a href="{{ route('login') }}" class="sdi-cta-btn w-100 justify-content-center py-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk Portal</span>
                </a>
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
                            <p><a href="mailto:sdi@madiunkab.go.id">sdi@madiunkab.go.id</a></p>
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
                            <li><i class="fas fa-angle-right"></i><a href="{{ route('guest.publikasi') }}">Buku Publikasi</a></li>
                            <li><i class="fas fa-angle-right"></i><a href="{{ route('guest.infografis') }}">Infografis</a></li>
                            <li><i class="fas fa-angle-right"></i><a href="{{ route('guest.visualisasi') }}">Visualisasi</a></li>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.getElementById('sdiMobileMenuBtn');
            const closeBtn = document.getElementById('sdiDrawerCloseBtn');
            const drawer = document.getElementById('sdiMobileDrawer');
            const backdrop = document.getElementById('sdiDrawerBackdrop');

            function toggleDrawer(open) {
                if (open) {
                    if (drawer) drawer.classList.add('open');
                    if (backdrop) backdrop.classList.add('show');
                    document.body.style.overflow = 'hidden';
                } else {
                    if (drawer) drawer.classList.remove('open');
                    if (backdrop) backdrop.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }

            if (menuBtn) menuBtn.addEventListener('click', () => toggleDrawer(true));
            if (closeBtn) closeBtn.addEventListener('click', () => toggleDrawer(false));
            if (backdrop) backdrop.addEventListener('click', () => toggleDrawer(false));
        });
    </script>
    @stack('js')
</body>
</html>
