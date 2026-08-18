@extends('guest.layout')

@section('content')
<section class="uni-banner">
    <div class="container">
        <div class="uni-banner-text-area">
            <h1>Infografis</h1>
            <ul>
                <li><a href="{{('/')}}">Beranda</a></li>
                <li>Infografis</li>
            </ul>
        </div>
    </div>
</section>


<section class="blog-details pt-70 pb-100">
    <div class="container">
        <div class="row ">


@foreach($infografis as $blog)
<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
    <div class="blog-card bg-f9fbfe">
        <div class="blog-card-img" style="height: 350px; width: auto">
            <a href="{{ route('guest.infografis.detail', ['id' => encrypt($blog->id)]) }}">
                <img src="{{ Storage::url('public/blogs/').$blog->image }}" alt="image">
            </a>
        </div>
        <div class="blog-card-text-area">

<div class="blog-date">
    <ul>
        <li>
            @if(!empty($blog->tableau))
                <span style="display:inline-block; background:#28a745; color:#fff; 
                             border-radius:12px; padding:2px 8px; font-size:10px; margin-right:6px;">
                    Tableau
                </span>
            @endif
            <i class="fas fa-user"></i> Dibuat Oleh Admin
        </li>
        <li>
            <i class="far fa-calendar-alt"></i> {{ date('d-M-Y H:i', strtotime($blog->created_at)) }}
        </li>
    </ul>
</div>

            <h4>
                <a href="{{ route('guest.infografis.detail', ['id' => encrypt($blog->id)]) }}">
                    {{ $blog->title }}
                </a>
            </h4>
            {{-- <p>{{ strip_tags($blog->content) }}</p> --}}
            <a class="read-more-btn" href="{{ route('guest.infografis.detail', ['id' => encrypt($blog->id)]) }}">
                Selengkapnya
            </a>
        </div>
    </div>
</div>
@endforeach



        </div>
        {{-- <div class="paginations mt-30">
            <ul>
                <li><a class="active" href="blog.html">1</a></li>
                <li><a href="blog.html">2</a></li>
                <li><a href="blog.html">3</a></li>
                <li><a href="blog.html"><i class="fas fa-chevron-right"></i></a></li>
            </ul>
        </div> --}}
    </div>
</section>
@endsection
