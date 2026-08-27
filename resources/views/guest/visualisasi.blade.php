@extends('guest.layout')

@section('content')
<div class="page-banner pt-60 pb-60" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Visualisasi Interaktif</h1>
            <p class="text-white-50 mb-0" style="font-size: 16px;">Eksplorasi data tematik Kabupaten Madiun dalam bentuk dashboard visual interaktif Tableau</p>
        </div>
    </div>
</div>

<section class="visualisasi-area pt-50 pb-70 bg-light">
    <div class="container">
        <!-- Search Filter -->
        <div class="card shadow-sm border-0 mb-4 rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('guest.visualisasi') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul visualisasi atau data..." value="{{ request('search') }}" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 16px;">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold" style="border-radius: 8px;"><i class="fas fa-search me-1"></i> Cari</button>
                    @if(request('search'))
                        <a href="{{ route('guest.visualisasi') }}" class="btn btn-outline-secondary" style="border-radius: 8px;"><i class="fas fa-redo"></i></a>
                    @endif
                </form>
            </div>
        </div>

        <div class="row g-4">
            @forelse($visualisasis as $vis)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden d-flex flex-column" style="transition: transform 0.2s, box-shadow 0.2s;">
                    <div class="card-img-top position-relative d-flex align-items-center justify-content-center" style="height: 220px; background: linear-gradient(135deg, #0d3b66 0%, #002855 100%);">
                        <div class="text-center text-white p-3">
                            <i class="fas fa-chart-pie fa-3x mb-2 text-warning opacity-75"></i>
                            <div class="small fw-semibold text-white-50">DASHBOARD TABLEAU</div>
                            <span class="badge bg-primary mt-2 px-3 py-1"><i class="fas fa-bolt me-1"></i> Interaktif</span>
                        </div>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="text-muted small mb-2"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($vis->created_at)->translatedFormat('d F Y') }}</div>
                        <h5 class="card-title fw-bold text-dark mb-2" style="font-size: 1.1rem; line-height: 1.4;">{{ $vis->title }}</h5>
                        <p class="card-text text-muted small mb-4 flex-grow-1">
                            {{ Str::limit(strip_tags($vis->content ?: 'Dashboard visualisasi data interaktif Pemerintah Kabupaten Madiun.'), 100) }}
                        </p>
                        <a href="{{ route('guest.visualisasi.detail', is_numeric($vis->id) ? encrypt($vis->id) : $vis->id) }}" class="btn btn-primary w-100 fw-semibold rounded-pill py-2">
                            <i class="fas fa-eye me-1"></i> Buka Dashboard Visual
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="card shadow-sm border-0 rounded-4 p-5 bg-white">
                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                    <h5 class="fw-bold text-muted">Belum ada Visualisasi Interaktif yang dipublikasikan</h5>
                    <p class="text-muted small mb-0">Dashboard visualisasi Tableau akan segera ditampilkan di halaman ini.</p>
                </div>
            </div>
            @endforelse
        </div>

        @if(method_exists($visualisasis, 'hasPages') && $visualisasis->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $visualisasis->withQueryString()->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
