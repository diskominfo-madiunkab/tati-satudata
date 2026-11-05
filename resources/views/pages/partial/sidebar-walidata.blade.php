<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-heading">Dashboard</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('rekap_walidata') || request()->routeIs('d_walidata') ? '' : 'collapsed' }}"
                href="{{ route('rekap_walidata') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard & Rekapitulasi</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link {{ Str::contains(request()->url(), '/usulan-data') ? '' : 'collapsed' }}"
                href="/usulan-data">
                <i class="bi bi-list-columns"></i>
                <span>Usulan Data</span>
            </a>
        </li><!-- End Blank Page Nav -->

        <li class="nav-heading">Standar Data</li>

        <li class="nav-item">
            <a class="nav-link {{ Str::contains(request()->url(), 'data_walidata/standar-data') ? '' : 'collapsed' }}"
                href="/data_walidata/standar-data">
                <i class="bi bi-database"></i><span>Master Standar Data</span>
            </a>
        </li>

        <li class="nav-heading">Perencanaan Data</li>

        <li class="nav-item">
            <a class="nav-link {{ in_array(request()->path(), ['data_walidata/draft', 'data_walidata/selesai_konfirmasi_walidata', 'data_walidata/tolak_konfirmasi_walidata']) ? '' : 'collapsed' }}"
                href="/data_walidata/draft">
                <i class="bi bi-check-square"></i><span>Perencanaan Data</span>
            </a>
        </li>

        <li class="nav-heading">Pengumpulan Data</li>

        <li class="nav-item">
            <a class="nav-link {{ Str::contains(request()->url(), 'data_walidata/pengumpulan') ? 'collapse' : 'collapsed' }}"
                href="/data_walidata/pengumpulan">
                <i class="bi bi-list-check"></i>
                <span>Pengumpulan Data</span>
            </a>
        </li>

        <li class="nav-heading">Pemeriksaan Data</li>

        <li class="nav-item">
            <a class="nav-link {{ Str::contains(request()->url(), 'data_walidata/verifikasi') ? 'collapse' : 'collapsed' }}"
                href="/data_walidata/verifikasi">
                <i class="bi bi-folder-check"></i>
                <span>Pemeriksaan Data</span>
            </a>
        </li>

        @if (auth()->user()->hasRole('walidata') ||
                auth()->user()->hasRole('walidatapendukung') ||
                auth()->user()->hasRole('pembina'))
            <li class="nav-heading">Penyebarluasan Data</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('publikasi.*') ? 'collapse' : 'collapsed' }}"
                    href="{{ route('publikasi.index') }}">
                    <i class="bi bi-send"></i>
                    <span>Penyebarluasan Data</span>
                </a>
            </li>
        @endif
        @if (Auth::user()->role_id != 4)
            <li class="nav-heading">Box Value</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('box-value.*') ? 'collapse' : 'collapsed' }}"
                    href="{{ route('box-value.index') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Box Value</span>
                </a>
            </li>
        @endif

        <li class="nav-heading">Bantuan & Dokumen</li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="/panduan/buku-panduan-portal-satu-data.pdf" target="_blank" download>
                <i class="bi bi-book"></i>
                <span>Buku Panduan Portal</span>
            </a>
        </li>
    </ul>

</aside>
