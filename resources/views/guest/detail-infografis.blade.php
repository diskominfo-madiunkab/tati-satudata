@extends('guest.layout')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .infografis-carousel {
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
        border-radius: 16px;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    .infografis-carousel .swiper-slide {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
    }
    .infografis-carousel img {
        width: 100%;
        height: auto;
        max-height: 800px;
        object-fit: contain;
        display: block;
    }
    .swiper-button-next, .swiper-button-prev {
        color: #0d3b66;
        background: rgba(255, 255, 255, 0.9);
        width: 44px;
        height: 44px;
        border-radius: 50%;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .swiper-button-next:after, .swiper-button-prev:after {
        font-size: 18px;
        font-weight: bold;
    }
    .swiper-pagination-bullet-active {
        background: #0d3b66;
    }
</style>
@endpush

@section('content')
<div class="page-banner pt-50 pb-50" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2 fs-3">{{ $infografis->title }}</h1>
            <ul class="d-flex justify-content-center list-unstyled gap-2 text-white-50 mb-0 small">
                <li><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li>/</li>
                <li><a href="{{ route('guest.infografis') }}" class="text-white-50 text-decoration-none">Infografis</a></li>
                <li>/</li>
                <li class="text-white">Detail</li>
            </ul>
        </div>
    </div>
</div>

<section class="detail-infografis-area pt-50 pb-70 bg-light">
    <div class="container">
        <div class="card shadow-sm border-0 rounded-4 p-4 bg-white mb-4">
            <!-- Metadata Info -->
            <div class="d-flex flex-wrap align-items-center justify-content-between pb-3 mb-4 border-bottom text-muted small">
                <div>
                    <i class="far fa-calendar-alt text-primary me-1"></i> Dipublikasikan: <strong>{{ $infografis->created_at ? $infografis->created_at->translatedFormat('d F Y') : '-' }}</strong>
                </div>
                <div>
                    <a href="{{ route('guest.infografis') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Galeri
                    </a>
                </div>
            </div>

            <!-- Carousel Multi-Image Slider (Instagram Feed Concept) -->
            @php
                $allImages = collect();
                if ($infografis->image) {
                    $allImages->push(asset('storage/public/blogs/' . $infografis->image));
                }
                if ($infografis->images && $infografis->images->count() > 0) {
                    foreach($infografis->images as $img) {
                        $allImages->push(asset('storage/' . $img->image));
                    }
                }
            @endphp

            @if($allImages->count() > 1)
                <div class="swiper infografis-carousel shadow-sm mb-4">
                    <div class="swiper-wrapper">
                        @foreach($allImages as $index => $imgUrl)
                            <div class="swiper-slide text-center">
                                <img src="{{ $imgUrl }}" alt="{{ $infografis->title }} - Slide {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            @elseif($allImages->count() == 1)
                <div class="text-center mb-4">
                    <img src="{{ $allImages->first() }}" class="img-fluid rounded-4 shadow-sm" style="max-height: 800px;" alt="{{ $infografis->title }}">
                </div>
            @endif

            <!-- Deskripsi & Penjelasan -->
            @if(!empty($infografis->content))
            <div class="infografis-description mt-4 pt-3 border-top">
                <h5 class="fw-bold mb-3 text-dark">Deskripsi & Pembahasan</h5>
                <div class="text-secondary leading-relaxed fs-6">
                    {!! $infografis->content !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (document.querySelector('.infografis-carousel')) {
            new Swiper('.infografis-carousel', {
                loop: false,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                spaceBetween: 15,
                autoHeight: true,
            });
        }
    });
</script>
@endpush
