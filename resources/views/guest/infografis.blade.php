@extends('guest.layout')

@section('content')
<div class="page-banner pt-60 pb-60" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Infografis</h1>
            <p class="text-white-50 mb-0" style="font-size: 16px;">Menampilkan Koleksi Infografis dan Visualisasi dari dataset yang telah dikumpulkan pada Portal Satu Data Kabupaten Madiun</p>
        </div>
    </div>
</div>

<section class="infografis-area pt-50 pb-70 bg-light">
    <div class="container">
        <!-- Search Filter -->
        <div class="card shadow-sm border-0 mb-4 rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('guest.infografis') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari infografis..." value="{{ request('search') }}" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 16px;">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold" style="border-radius: 8px;"><i class="fas fa-search me-1"></i> Cari</button>
                    @if(request('search'))
                        <a href="{{ route('guest.infografis') }}" class="btn btn-outline-secondary" style="border-radius: 8px;"><i class="fas fa-redo"></i></a>
                    @endif
                </form>
            </div>
        </div>

        <div class="row g-4">
            @forelse($infografis as $info)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition d-flex flex-column">
                    <a href="{{ route('guest.infografis.detail', is_numeric($info->id) ? encrypt($info->id) : $info->id) }}" class="text-decoration-none">
                        <div class="position-relative overflow-hidden" style="height: 280px; background-color: #f8f9fa;">
                            @if($info->image)
                                <img src="{{ asset('storage/public/blogs/' . $info->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $info->title }}">
                            @elseif($info->images && $info->images->count() > 0)
                                <img src="{{ asset('storage/' . $info->images->first()->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $info->title }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted">
                                    <i class="fas fa-image fa-3x"></i>
                                </div>
                            @endif

                            @if($info->images && $info->images->count() > 1)
                                <span class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-3">
                                    <i class="fas fa-images me-1"></i> {{ $info->images->count() }} Slide
                                </span>
                            @endif
                        </div>
                    </a>
                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        <div class="text-muted small mb-2"><i class="fas fa-calendar-alt me-1"></i> {{ $info->created_at ? $info->created_at->translatedFormat('d F Y') : '-' }}</div>
                        <h5 class="card-title fw-bold mb-2">
                            <a href="{{ route('guest.infografis.detail', is_numeric($info->id) ? encrypt($info->id) : $info->id) }}" class="text-dark text-decoration-none hover-primary">
                                {{ $info->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted small mb-4 flex-grow-1">
                            {{ Str::limit(strip_tags($info->content), 100) }}
                        </p>
                        <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                            <a href="{{ route('guest.infografis.detail', is_numeric($info->id) ? encrypt($info->id) : $info->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 fw-semibold w-100 text-center">
                                Lihat Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-chart-bar fa-3x mb-3 text-muted"></i>
                <p>Belum ada infografis yang dipublikasikan.</p>
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
