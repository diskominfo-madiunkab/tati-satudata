@extends('guest.layout')

@section('content')
<div class="page-banner">
    <div class="container">
        <div class="page-banner-content text-center">
            <div class="page-banner-pill">
                <i class="fas fa-chart-line text-info"></i> Satu Data Kabupaten Madiun
            </div>
            <h1 class="page-banner-title">Visualisasi Interaktif Tableau</h1>
            <p class="page-banner-subtitle">Menampilkan dashboard dan visualisasi data interaktif pembangunan daerah Kabupaten Madiun berbasis Tableau</p>
        </div>
    </div>
</div>

<section class="visualisasi-area pt-50 pb-70 bg-light">
    <div class="container">
        <!-- Search Filter -->
        <div class="card shadow-sm border mb-4 rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('guest.visualisasi') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-10 col-md-9">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-search me-1"></i> Cari Visualisasi Tableau</label>
                            <input type="text" name="search" class="form-control" placeholder="Kata kunci judul dashboard atau data..." value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-2 col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
                            @if(request('search'))
                                <a href="{{ route('guest.visualisasi') }}" class="btn btn-outline-secondary"><i class="fas fa-redo"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4">
            @forelse($visualisasis as $vis)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100 border shadow-sm rounded-3 overflow-hidden bg-white hover-shadow transition d-flex flex-column">
                    <div class="position-relative bg-dark d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #1b263b 0%, #0d1b2a 100%);">
                        <div class="text-center text-white p-3">
                            <i class="fas fa-chart-line fa-3x mb-2 text-info"></i>
                            <div class="fw-bold fs-6">Tableau Public Dashboard</div>
                        </div>
                        <span class="badge bg-primary position-absolute top-0 end-0 m-3">
                            <i class="fas fa-cube me-1"></i> Interaktif
                        </span>
                    </div>

                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        <div class="text-muted small mb-2">
                            <i class="fas fa-calendar-alt me-1"></i> {{ $vis->created_at ? $vis->created_at->translatedFormat('d F Y') : '-' }}
                        </div>
                        <h5 class="card-title fw-bold mb-3">
                            <a href="{{ route('guest.visualisasi.detail', encrypt($vis->id)) }}" class="text-dark text-decoration-none">
                                {{ $vis->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit(strip_tags($vis->content), 120) ?: 'Eksplorasi visualisasi data interaktif lengkap pada dashboard Tableau.' }}
                        </p>
                        <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                            <a href="{{ route('guest.visualisasi.detail', encrypt($vis->id)) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                Buka Dashboard <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                            <a href="{{ $vis->tableau_url }}" target="_blank" class="text-muted small" title="Buka di Tableau Public">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-chart-bar fa-3x mb-3 text-muted"></i>
                <p>Belum ada visualisasi Tableau yang dipublikasikan.</p>
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

