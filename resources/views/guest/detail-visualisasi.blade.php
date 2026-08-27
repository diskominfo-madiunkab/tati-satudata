@extends('guest.layout')

@section('content')
<div class="page-banner pt-50 pb-50" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">{{ $visualisasi->title }}</h1>
            <ul class="d-flex justify-content-center list-unstyled gap-2 text-white-50 mb-0 small">
                <li><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li>/</li>
                <li><a href="{{ route('guest.visualisasi') }}" class="text-white-50 text-decoration-none">Visualisasi</a></li>
                <li>/</li>
                <li class="text-white">Detail</li>
            </ul>
        </div>
    </div>
</div>

<section class="visualisasi-detail-area pt-50 pb-70 bg-light">
    <div class="container">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4 bg-white">
            <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-primary px-3 py-2 me-2"><i class="fas fa-chart-pie me-1"></i> Dashboard Tableau</span>
                    <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($visualisasi->created_at)->translatedFormat('d F Y') }}</span>
                </div>
                @if(!empty($visualisasi->tableau_url))
                <a href="{{ $visualisasi->tableau_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    <i class="fas fa-external-link-alt me-1"></i> Buka Fullscreen
                </a>
                @endif
            </div>
            <div class="card-body p-4">
                @if(!empty($visualisasi->tableau_url))
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm border" style="min-height: 650px;">
                        <iframe src="{{ $visualisasi->tableau_url }}" title="{{ $visualisasi->title }}" allowfullscreen frameborder="0" style="width: 100%; height: 100%;"></iframe>
                    </div>
                @else
                    <div class="p-5 text-center bg-light rounded-3">
                        <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Tautan Tableau belum disematkan pada data visualisasi ini.</p>
                    </div>
                @endif

                @if(!empty($visualisasi->content))
                <div class="mt-4 pt-3 border-top">
                    <h5 class="fw-bold text-dark mb-3">Deskripsi & Penjelasan Data</h5>
                    <div class="text-secondary leading-relaxed">
                        {!! $visualisasi->content !!}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('guest.visualisasi') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Visualisasi
            </a>
        </div>
    </div>
</section>
@endsection
