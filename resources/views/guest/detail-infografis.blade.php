@extends('guest.layout')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .infografis-carousel {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        border-radius: 12px;
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
        max-height: 750px;
        object-fit: contain;
        display: block;
    }
    .swiper-button-next, .swiper-button-prev {
        color: #d90429;
        background: rgba(255, 255, 255, 0.85);
        width: 44px;
        height: 44px;
        border-radius: 50%;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .swiper-button-next:after, .swiper-button-prev:after {
        font-size: 18px;
        font-weight: bold;
    }
    .swiper-pagination-bullet-active {
        background: #d90429;
    }
</style>
@endpush

@section('content')
<div class="page-banner pt-30 pb-30" style="background: linear-gradient(135deg, #1b263b 0%, #0d1b2a 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-2">
                    <li class="breadcrumb-item"><a href="/" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guest.infografis') }}" class="text-white-50">Infografis</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Detail</li>
                </ol>
            </nav>
            <h2 class="text-white fw-bold mb-0 fs-3">{{ $infografis->title }}</h2>
        </div>
    </div>
</div>

<section class="detail-infografis-area pt-40 pb-70 bg-light">
    <div class="container">
        <div class="card shadow-sm border-0 rounded-3 p-4 bg-white mb-4">
            <!-- Metadata Info -->
            <div class="d-flex flex-wrap align-items-center justify-content-between pb-3 mb-4 border-bottom text-muted small">
                <div>
                    <i class="fas fa-calendar-alt text-danger me-1"></i> Dipublikasikan: <strong>{{ $infografis->created_at ? $infografis->created_at->translatedFormat('d F Y') : '-' }}</strong>
                </div>
                <div>
                    <a href="{{ route('guest.infografis') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Galeri Infografis
                    </a>
                </div>
            </div>

            <!-- Carousel Multi-Image Slider (Instagram Concept) -->
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
                    <img src="{{ $allImages->first() }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 750px;" alt="{{ $infografis->title }}">
                </div>
            @endif

            <!-- Tableau Interactive Embed (if available) -->
            @if($infografis->tableau)
                <div class="my-4 p-3 border rounded-3 bg-light">
                    <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-chart-line text-primary me-2"></i>Visualisasi Interaktif (Tableau)</h5>
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ $infografis->tableau }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            @endif

            <!-- Deskripsi & Penjelasan -->
            <div class="infografis-description mt-3">
                <h4 class="fw-bold mb-3 text-dark">Deskripsi & Pembahasan</h4>
                <div class="text-secondary leading-relaxed fs-6">
                    {!! $infografis->content !!}
                </div>
            </div>
        </div>
    </div>
</section>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
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
            spaceBetween: 10,
            autoHeight: true,
        });
    });
</script>
@endpush
@endsection
