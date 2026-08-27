<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-heading">Master Data & Pengaturan</li>

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/opd') ? 'collapse' : 'collapsed'}}" href="/opd">
        <i class="bi bi-building"></i>
        <span>Kelola OPD</span>
      </a>
    </li><!-- End Kelola OPD Nav -->

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/sumberdata') ? 'collapse' : 'collapsed'}}"
        href="/sumberdata">
        <i class="bi bi-file-earmark-medical"></i>
        <span>Sumber Referensi</span>
      </a>
    </li><!-- End Sumber Data Nav -->

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/group') ? 'collapse' : 'collapsed'}}" href="/group">
        <i class="bi bi-people-fill"></i>
        <span>Kelola Group</span>
      </a>
    </li><!-- End Group Nav -->

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/upload-download') ? 'collapse' : 'collapsed'}}"
        href="/upload-download">
        <i class="bi bi-files"></i>
        <span>Kelola File</span>
      </a>
    </li><!-- End Kelola File Nav -->

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/user') ? 'collapse' : 'collapsed'}}" href="/user">
        <i class="bi bi-person-fill"></i>
        <span>Kelola User</span>
      </a>
    </li><!-- End Kelola User Nav -->

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/publikasi-admin') ? 'collapse' : 'collapsed'}}"
        href="{{route('publikasi-guest.index')}}">
        <i class="bi bi-book"></i>
        <span>Kelola Buku (Publikasi)</span>
      </a>
    </li><!-- End Kelola Buku Nav -->

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/infografis') ? 'collapse' : 'collapsed'}}"
        href="{{route('infografis.index')}}">
        <i class="bi bi-images"></i>
        <span>Kelola Infografis</span>
      </a>
    </li><!-- End Kelola Infografis Nav -->

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/kelola-visualisasi') ? 'collapse' : 'collapsed'}}"
        href="{{route('kelola-visualisasi.index')}}">
        <i class="bi bi-pie-chart-fill"></i>
        <span>Kelola Visualisasi</span>
      </a>
    </li><!-- End Kelola Visualisasi Nav -->

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/kelola-regulasi') ? 'collapse' : 'collapsed'}}"
        href="{{route('kelola-regulasi.index')}}">
        <i class="bi bi-shield-check"></i>
        <span>Kelola Regulasi</span>
      </a>
    </li><!-- End Kelola Regulasi Nav -->

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/tahun') ? 'collapse' : 'collapsed'}}" href="/tahun">
        <i class="bi bi-calendar2-date"></i>
        <span>Kelola Tahun</span>
      </a>
    </li><!-- End Kelola Tahun Nav -->

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/data-demografis') ? 'collapse' : 'collapsed'}}"
        href="/data-demografis">
        <i class="bi bi-globe"></i>
        <span>Kelola Demografis</span>
      </a>
    </li><!-- End Kelola Demografis Nav -->

  </ul>

</aside><!-- End Sidebar-->