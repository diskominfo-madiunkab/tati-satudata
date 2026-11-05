@extends('guest.layout')

@section('content')
<div class="page-banner pt-30 pb-30" style="background: linear-gradient(135deg, #0d3b66 0%, #0077b6 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-2">
                    <li class="breadcrumb-item"><a href="/" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guest.visualisasi') }}" class="text-white-50">Visualisasi</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Detail</li>
                </ol>
            </nav>
            <h2 class="text-white fw-bold mb-0 fs-3">{{ $visualisasi->title }}</h2>
        </div>
    </div>
</div>

<section class="detail-visualisasi-area pt-40 pb-70 bg-light">
    <div class="container">
        <div class="card shadow-sm border-0 rounded-3 p-4 bg-white mb-4">
            <!-- Metadata Info -->
            <div class="d-flex flex-wrap align-items-center justify-content-between pb-3 mb-4 border-bottom text-muted small">
                <div>
                    <i class="fas fa-calendar-alt text-primary me-1"></i> Dipublikasikan: <strong>{{ $visualisasi->created_at ? $visualisasi->created_at->translatedFormat('d F Y') : '-' }}</strong>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ $visualisasi->tableau_url }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3">
                        <i class="fas fa-external-link-alt me-1"></i> Buka Fullscreen
                    </a>
                    <a href="{{ route('guest.visualisasi') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Galeri Visualisasi
                    </a>
                </div>
            </div>

            <!-- Tableau Interactive Embed -->
            @php
                $embedUrl = $visualisasi->tableau_url;
                if (!str_contains($embedUrl, 'showVizHome=no')) {
                    $separator = str_contains($embedUrl, '?') ? '&' : '?';
                    $embedUrl .= $separator . ':showVizHome=no&:embed=true';
                }
            @endphp
            <div class="my-2 p-2 border rounded-3 bg-light shadow-sm">
                <div class="ratio ratio-16x9" style="min-height: 600px;">
                    <iframe src="{{ $embedUrl }}" frameborder="0" allowfullscreen style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
            </div>

            <!-- Deskripsi & Penjelasan -->
            @if($visualisasi->content)
            <div class="visualisasi-description mt-4 pt-3 border-top">
                <h4 class="fw-bold mb-3 text-dark">Deskripsi & Pembahasan</h4>
                <div class="text-secondary leading-relaxed fs-6">
                    {!! $visualisasi->content !!}
                </div>
            </div>
            @endif
        </div>

        @if(isset($pop) && $pop->count() > 0)
        <div class="mt-5">
            <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-chart-line text-primary me-2"></i>Visualisasi Lainnya</h5>
            <div class="row g-3">
                @foreach($pop as $item)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 border shadow-sm rounded-3 p-3 bg-white">
                        <h6 class="fw-bold mb-2">
                            <a href="{{ route('guest.visualisasi.detail', encrypt($item->id)) }}" class="text-dark text-decoration-none">
                                {{ Str::limit($item->title, 50) }}
                            </a>
                        </h6>
                        <div class="small text-muted mb-3">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</div>
                        <a href="{{ route('guest.visualisasi.detail', encrypt($item->id)) }}" class="btn btn-xs btn-outline-primary mt-auto">
                            Buka <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

