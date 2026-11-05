@extends('guest.layout')

@section('content')
<div class="page-banner">
    <div class="container">
        <div class="page-banner-content text-center">
            <div class="page-banner-pill">
                <i class="fas fa-images text-info"></i> Satu Data Kabupaten Madiun
            </div>
            <h1 class="page-banner-title">Infografis Satu Data</h1>
            <p class="page-banner-subtitle">Menampilkan Koleksi Infografis dan Visualisasi dari dataset yang telah dikumpulkan pada Portal Satu Data Kabupaten Madiun</p>
        </div>
    </div>
</div>

<section class="infografis-area pt-50 pb-70 bg-light">
    <div class="container">
        <!-- Search Filter -->
        <div class="card shadow-sm border mb-4 rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('guest.infografis') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-10 col-md-9">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-search me-1"></i> Cari Infografis</label>
                            <input type="text" name="search" class="form-control" placeholder="Kata kunci judul infografis..." value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-2 col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
                            @if(request('search'))
                                <a href="{{ route('guest.infografis') }}" class="btn btn-outline-secondary"><i class="fas fa-redo"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4">
            @forelse($infografis as $info)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100 border shadow-sm rounded-3 overflow-hidden bg-white hover-shadow transition d-flex flex-column">
                    <a href="{{ route('guest.infografis.detail', encrypt($info->id)) }}" class="text-decoration-none">
                        <div class="position-relative overflow-hidden" style="height: 280px; background-color: #f8f9fa;">
                            @if($info->image)
                                <img src="{{ Storage::url('public/blogs/' . $info->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $info->title }}">
                            @elseif($info->images && $info->images->count() > 0)
                                <img src="{{ Storage::url($info->images->first()->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $info->title }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted">
                                    <i class="fas fa-chart-pie fa-3x"></i>
                                </div>
                            @endif

                            @if(($info->images && $info->images->count() > 1))
                                <span class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-3">
                                    <i class="fas fa-images me-1"></i> {{ $info->images->count() }} Slide
                                </span>
                            @endif

                            @if($info->tableau)
                                <span class="badge bg-primary position-absolute bottom-0 start-0 m-3">
                                    <i class="fas fa-chart-line me-1"></i> Tableau
                                </span>
                            @endif
                        </div>
                    </a>
                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        <div class="text-muted small mb-2"><i class="fas fa-calendar-alt me-1"></i> {{ $info->created_at ? $info->created_at->translatedFormat('d F Y') : '-' }}</div>
                        <h5 class="card-title fw-bold mb-3">
                            <a href="{{ route('guest.infografis.detail', encrypt($info->id)) }}" class="text-dark text-decoration-none">
                                {{ $info->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit(strip_tags($info->content), 110) }}
                        </p>
                        <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                            <a href="{{ route('guest.infografis.detail', encrypt($info->id)) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-chart-bar fa-3x mb-3 text-muted"></i>
                <p>Belum ada infografis yang sesuai dengan kriteria pencarian.</p>
            </div>
            @endforelse
        </div>

        @if(method_exists($infografis, 'hasPages') && $infografis->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $infografis->withQueryString()->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
