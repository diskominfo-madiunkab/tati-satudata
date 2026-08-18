@extends('guest.layout')

@section('content')
    <script type="module" src="https://public.tableau.com/javascripts/api/tableau.embedding.3.latest.min.js"></script>

<style>
    .dropbtn {
        background-color: #59c3fd;
        color: white;
        padding: 8px;
        font-size: 16px;
        border: none;
        cursor: pointer;
        border-radius: 6px;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: #000000;
    }

    /* responsive embed container for tableau - wrapper dan inner untuk scaling */
    .tableau-wrapper {
        width: 100%;
        max-width: 1366px; /* optional: jangan melebihi ukuran asli dashboard */
        margin: 1rem 0;
        overflow: hidden;
        position: relative;
        /* height akan di-set via JS sesuai scale */
        background: transparent;
    }

    .tableau-inner {
        transform-origin: 0 0;
        -webkit-transform-origin: 0 0;
        will-change: transform;
        /* pastikan element memiliki ukuran asli agar scaling bekerja */
        width: 1366px;  /* originalWidth */
        height: 795px;  /* originalHeight */
    }

    /* target element tableau-viz / iframe styling */
    tableau-viz,
    .tableau-inner object,
    .tableau-inner iframe {
      display: block;
      width: 1366px;  /* originalWidth */
      height: 795px;  /* originalHeight */
      border: none;
    }

    .badge-tableau {
        font-size: 0.85rem;
        padding: 6px 10px;
        border-radius: 6px;
    }

    /* small tweak for desktop: align title and badge */
    .title-with-badge {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }
</style>

<section class="uni-banner">
    <div class="container">
        <div class="uni-banner-text-area">
            <h1>Detail Infografis</h1>
            <ul>
                <li><a href="{{('/')}}">Beranda</a></li>
                <li><a href="{{('/infografis-guest')}}">Infografis</a></li>
                <li>Detail Infografis</li>
            </ul>
        </div>
    </div>
</section>

<section class="blog-details ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="blog-details-text-area details-text-area">
                    <img src="{{ Storage::url('public/blogs/').$infografis->image }}" alt="image" class="img-fluid">
                    <div class="blog-date">
                        <ul>
                            <li><i class="fas fa-user"></i> Dibuat Oleh Admin</li>
                            <li><i class="far fa-calendar-alt"></i> {{ date('d-M-Y H:i', strtotime($infografis->created_at)) }}</li>
                        </ul>
                    </div>

                    {{-- Title + badge (badge muncul jika ada tableau) --}}
                    <div class="title-with-badge">
                        <h3 class="mt-0">{{$infografis->title}}</h3>

                        @if(!empty($infografis->tableau))
                            @php
                                // ukuran konten tableau tanpa tag HTML
                                $tableauText = strip_tags($infografis->tableau);
                                $len = mb_strlen($tableauText);
                            @endphp

                            @if($len < 200)
                                <span class="badge badge-tableau bg-success text-white">Tableau</span>
                            @else
                                <span class="badge badge-tableau bg-info text-white">Tableau</span>
                            @endif
                        @endif
                    </div>

                    {{-- Tableau embed tampil di bawah judul jika ada.
                         Kita bungkus dalam container (.tableau-wrapper) agar bisa di-scale otomatis --}}
                    @if(!empty($infografis->tableau))
                        <div class="tableau-wrapper" id="tableauWrapper">
                            <div class="tableau-inner" id="tableauInner">
                                <!-- Jika kamu menyimpan full embed HTML di $infografis->tableau, bisa langsung echo: -->
                                {{-- {!! $infografis->tableau !!} --}}

                                <!-- Contoh menggunakan tableau-viz dengan src tertentu.
                                     Pastikan ukuran width/height di CSS sama dengan original canvas Tableau -->
                                <tableau-viz
                                    id="tableauViz"
                                    src="{{ $infografis->tableau }}"
                                    toolbar="hidden"
                                    hide-tabs
                                ></tableau-viz>
                            </div>
                        </div>
                    @endif

                    {!! $infografis->content !!}
                </div>

                <div class="blog-text-footer mt-30">
                    <div class="social-icons">
                        @php
                        $url = 'https://data.madiunkab.go.id/infografis-guest/detail/'.$infografis->id;
                        @endphp
                        <ul>
                            <li><span>Bagikan :</span></li>
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li><a href="https://twitter.com/intent/tweet?url={{ $url }}" target="_blank"><i
                                        class="fab fa-twitter"></i></a>
                            </li>
                            <li><a href="whatsapp://send?text={{ $url }}" target="_blank"><i
                                        class="fab fa-whatsapp"></i></a></li>
                            <li><a href="javascript:void(0)" onclick="copyToClipboard('{{ $url }}')"><i
                                        class="fas fa-link"></i></a></li>
                        </ul>

                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="sidebar-area pl-20 pt-30">
                    <div class="dropdown mb-30">
                        <button class="dropbtn">Unduh <i style="color:white" class="fas fa-caret-down"></i></button>
                        <div class="dropdown-content">
                            <a
                                href="{{ route('download.image.infografis', ['id' => encrypt($infografis->id)]) }}">Gambar</a>
                            <a
                                href="{{ route('download.image.infografis.pdf', ['id' => encrypt($infografis->id)]) }}">PDF</a>
                        </div>
                    </div>
                    <div class="sidebar-card search-box">
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search Here.." required>
                                <button class="btn" type="submit"><i class="fas fa-search"></i></button>

                            </div>
                        </form>

                    </div>
                    <div class="sidebar-card recent-news mt-30">

                        <h3>Popular Infografis</h3>
                        @foreach($pop as $infos)
                        <div class="recent-news-card">
                            <a href="{{ route('guest.infografis.detail', ['id' => encrypt($infos->id)]) }}"><img
                                    style="height: 50px; width: auto"
                                    src="{{ Storage::url('public/blogs/').$infos->image }}" alt="image"></a>
                            <h5><a href="{{ route('guest.infografis.detail', ['id' => encrypt($infos->id)]) }}">{{
                                    $infos->title
                                    }}</a></h5>
                            <p>{{ date('d-M-Y H:i', strtotime($infos->created_at)) }}</p>
                        </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function copyToClipboard(text) {
      var tempInput = document.createElement("input");
      tempInput.value = text;
      document.body.appendChild(tempInput);
      tempInput.select();
      document.execCommand("copy");
      document.body.removeChild(tempInput);
      alert("Tautan berhasil disalin!");
    }
</script>

<script>
    (function () {
      // ukuran canvas Tableau asli (sesuaikan jika dashboard mu punya dimensi lain)
      const originalWidth = 1366;
      const originalHeight = 795;

      const wrapper = document.getElementById('tableauWrapper');
      const inner = document.getElementById('tableauInner');

      if (!wrapper || !inner) return; // kalau nggak ada tableau di page, skip

      function applyScale() {
        const containerWidth = wrapper.clientWidth;
        // scale berdasarkan lebar container agar fit penuh; batasi maximal scale = 1
        const scale = Math.min(containerWidth / originalWidth, 1);
        inner.style.transform = 'scale(' + scale + ')';
        inner.style.webkitTransform = 'scale(' + scale + ')';
        // atur tinggi wrapper supaya area layout tetap sesuai
        const scaledHeight = Math.ceil(originalHeight * scale);
        wrapper.style.height = scaledHeight + 'px';
      }

      // debounce resize
      let resizeTimer = null;
      function onResize() {
        if (resizeTimer) clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          applyScale();
          resizeTimer = null;
        }, 80);
      }

      // monitor perubahan lebar wrapper (mis sidebar show/hide)
      if ('ResizeObserver' in window) {
        const ro = new ResizeObserver(() => applyScale());
        ro.observe(wrapper);
      }

      window.addEventListener('resize', onResize);

      // tunggu elemen tableau siap, lakukan scale awal
      function waitForViz() {
        const viz = document.getElementById('tableauViz') || inner.querySelector('iframe, object, embed');
        if (viz) {
          // beberapa embed memerlukan sedikit waktu lagi untuk render; jalankan applyScale beberapa kali
          applyScale();
          setTimeout(applyScale, 300);
          setTimeout(applyScale, 900);
        } else {
          // kalau belum ketemu, ulangi beberapa kali
          setTimeout(waitForViz, 150);
        }
      }
      waitForViz();
    })();
</script>

@endsection
