@extends('guest.layout')

@section('content')
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
                    <img src="{{ Storage::url('public/blogs/').$infografis->image }}" alt="image">
                    <div class="blog-date">
                        <ul>
                            <li><i class="fas fa-user"></i> Dibuat Oleh Admin</a></li>
                            <li><i class="far fa-calendar-alt"></i> {{ date('d-M-Y H:i',
                                strtotime($infografis->created_at)) }}</li>
                        </ul>
                    </div>
                    <h3 class="mt-0">{{$infografis->title}}</h3>
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
                            <p>{{ date('d-M-Y H:i',
                                strtotime($infos->created_at)) }}</p>
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

@endsection