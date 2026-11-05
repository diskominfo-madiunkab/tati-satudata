<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-heading">Dashboard</li>

        <li class="nav-item">
            <a class="nav-link {{request()->routeIs('d_produsen') ? '' : 'collapsed' }}" href="/d_produsen">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-heading">Standar Data</li>

        <li class="nav-item">
            <a class="nav-link {{Str::contains(request()->url(), 'data_produsen/standar-data') ? '' : 'collapsed'}}"
                href="/data_produsen/standar-data">
                <i class="bi bi-database"></i><span>Master Standar Data</span>
            </a>
        </li>

        <li class="nav-heading">Perencanaan Data</li>

        <li class="nav-item">
            <a class="nav-link {{Str::contains(request()->url(), ['data_produsen/draft', 'data_produsen/selesai_konfirmasi', 'data_produsen/tolak_konfirmasi']) ? '' : 'collapsed'}}"
                href="/data_produsen/draft">
                <i class="bi bi-check-square"></i><span>Perencanaan Data</span>
            </a>
        </li>

        <li class="nav-heading">Pengumpulan Data</li>

        <li class="nav-item">
            <a class="nav-link {{Str::contains(request()->url(), 'data_produsen/pengumpulan') ? '' : 'collapsed'}}"
                href="/data_produsen/pengumpulan">
                <i class="bi bi-list-check"></i>
                <span>Pengumpulan Data</span>
            </a>
        </li>

        <li class="nav-heading">Pemeriksaan Data</li>

        <li class="nav-item">
            <a class="nav-link {{Str::contains(request()->url(), 'data_produsen/verifikasi') ? '' : 'collapsed'}}"
                href="/data_produsen/verifikasi">
                <i class="bi bi-folder-check"></i>
                <span>Pemeriksaan Data</span>
            </a>
        </li>

        <li class="nav-heading">Penyebarluasan Data</li>
        <li class="nav-item">
            <a class="nav-link {{Str::contains(request()->url(), 'data_produsen/penyebarluasan') ? '' : 'collapsed'}}"
                href="/data_produsen/penyebarluasan">
                <i class="bi bi-send"></i>
                <span>Penyebarluasan Data</span>
            </a>
        </li>

        <li class="nav-heading">Bantuan & Dokumen</li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="/panduan/buku-panduan-portal-satu-data.pdf" target="_blank" download>
                <i class="bi bi-book"></i>
                <span>Buku Panduan Portal</span>
            </a>
        </li>
    </ul>

</aside><!-- End Sidebar-->