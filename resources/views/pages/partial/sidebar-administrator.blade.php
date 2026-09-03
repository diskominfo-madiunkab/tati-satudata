<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    {{-- <li class="nav-heading">Dashboard</li> --}}

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/d_administrator') ? 'collapse' : 'collapsed'}} "
        href="/d_administrator">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Dashboard Nav -->
    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/usulan-data') ? 'collapse' : 'collapsed'}}"
        href="/usulan-data">
        <i class="bi bi-list-columns"></i>
        <span>Usulan Data</span>
      </a>
    </li><!-- End Blank Page Nav -->

    {{-- <li class="nav-heading">Dashboard</li> --}}

    {{-- <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/data_administrator') ? 'collapse' : 'collapsed'}} "
        href="/data_administrator">
        <i class="bi bi-file-earmark-bar-graph"></i>
        <span>Perencanaan Data</span>
      </a>
    </li><!-- End Dashboard Nav --> --}}


    <li class="nav-heading">Master Data</li>

    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/opd') ? 'collapse' : 'collapsed'}}" href="/opd">
        <i class="bi bi-building"></i>
        <span>Kelola OPD</span>
      </a>
    </li><!-- End Blank Page Nav -->
    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/sumberdata') ? 'collapse' : 'collapsed'}}"
        href="/sumberdata">
        <i class="bi bi-file-earmark-medical"></i>
        <span>Sumber Referensi</span>
      </a>
    </li><!-- End Blank Page Nav -->
    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/group') ? 'collapse' : 'collapsed'}}" href="/group">
        <i class="bi bi-people-fill"></i>
        <span>Kelola Group</span>
      </a>
    </li><!-- End Blank Page Nav -->
    {{-- <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/tag') ? 'collapse' : 'collapsed'}}" href="/tag">
        <i class="bi bi-tag"></i>
        <span>Kelola Tag</span>
      </a>
    </li><!-- End Blank Page Nav --> --}}
    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/upload-download') ? 'collapse' : 'collapsed'}}"
        href="/upload-download">
        <i class="bi bi-files"></i>
        <span>Kelola File</span>
      </a>
    </li><!-- End Blank Page Nav -->
    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/user') ? 'collapse' : 'collapsed'}}" href="/user">
        <i class="bi bi-person-fill"></i>
        <span>Kelola User</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/publikasi-admin') ? 'collapse' : 'collapsed'}}"
        href="{{route('publikasi-guest.index')}}">
        <i class="bi bi-cloud-upload-fill"></i>
        <span>Kelola Publikasi</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/infografis') ? 'collapse' : 'collapsed'}}"
        href="{{route('infografis.index')}}">
        <i class="bi bi-info-circle"></i>
        <span>Kelola Infografis</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/tahun') ? 'collapse' : 'collapsed'}}" href="/tahun">
        <i class="bi bi-calendar2-date"></i>
        <span>Kelola Tahun</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{Str::contains(request()->url(), '/data-demografis') ? 'collapse' : 'collapsed'}}"
        href="/data-demografis">
        <i class="bi bi-globe"></i>
        <span>Kelola Demografis</span>
      </a>
    </li>


  </ul>

</aside><!-- End Sidebar-->