@extends('guest.layout')

@section('content')
<div class="page-banner pt-60 pb-60" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Pencarian Dataset</h1>
            <p class="text-white-50 mb-0" style="font-size: 16px;">Temukan kumpulan data terbuka dan sektoral yang dipublikasikan oleh Pemerintah Kabupaten Madiun</p>
        </div>
    </div>
</div>

<section class="dataset-area pt-50 pb-70 bg-light">
    <div class="container">
        <!-- Filter Card Horizontal -->
        <div class="card shadow-sm border-0 mb-4 rounded-3">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('dataset') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-12">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-search me-1"></i> Kata Kunci Pencarian</label>
                            <input type="text" name="q" class="form-control" placeholder="Cari nama dataset atau topik..." value="{{ request('q') }}" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px;">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-layer-group me-1"></i> Urusan Data</label>
                            <select class="form-select" name="group" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 9px 14px;">
                                <option value="">Semua Urusan</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group['name'] }}" {{ request('group') == $group['name'] ? 'selected' : '' }}>
                                        {{ $group['display_name'] ?? $group['title'] ?? $group['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-bookmark me-1"></i> Sumber Referensi</label>
                            <select class="form-select" name="sumber_referensi" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 9px 14px;">
                                <option value="">Semua Sumber</option>
                                @foreach($sumberReferensiList as $sumber)
                                    <option value="{{ $sumber }}" {{ request('sumber_referensi') == $sumber ? 'selected' : '' }}>{{ $sumber }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-building me-1"></i> Produsen Data / OPD</label>
                            <select class="form-select" name="org" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 9px 14px;">
                                <option value="">Semua Produsen Data</option>
                                @foreach($orgs as $org)
                                    <option value="{{ $org['name'] }}" {{ request('org') == $org['name'] ? 'selected' : '' }}>
                                        {{ $org['title'] ?? $org['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 fw-semibold" style="border-radius: 8px; padding: 9px 14px;">
                                <i class="fas fa-search me-1"></i> Cari Dataset
                            </button>
                            @if(request()->hasAny(['q', 'group', 'org', 'sumber_referensi', 'tahun']))
                                <a href="{{ route('dataset') }}" class="btn btn-outline-secondary" title="Reset Filter" style="border-radius: 8px; padding: 9px 14px;">
                                    <i class="fas fa-redo"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card: Counter Dataset Disebarluaskan & Hasil Pencarian -->
        <div class="row g-3 align-items-center mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-3 bg-white p-3 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white p-2 rounded-2 me-3">
                            <i class="fas fa-share-alt fa-lg"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total Dataset Disebarluaskan</span>
                            <h5 class="fw-bold text-dark mb-0">{{ $totalDatasetCount ?? count($data) }} <span class="fs-6 fw-normal text-muted">Dataset</span></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="text-muted small">Menampilkan <strong>{{ count($data) }}</strong> dataset pada halaman ini</span>
            </div>
        </div>

        <!-- Dataset List Cards -->
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden bg-white p-0">
            <div class="list-group list-group-flush">
                @forelse($data as $d)
                <div class="list-group-item p-4 hover-shadow transition">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                        <h5 class="fw-bold mb-1">
                            <a href="{{ route('dataset.show', $d['id'] ?? $d['title']) }}" class="text-primary text-decoration-none hover-underline">
                                {{ $d['title'] }}
                            </a>
                        </h5>
                        <a href="{{ route('dataset.show', $d['id'] ?? $d['title']) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <p class="text-muted small mb-3 text-break" style="line-height: 1.6;">
                        {{ Str::words(strip_tags($d['notes'] ?? 'Dataset statistik sektoral yang dikelola oleh Pemerintah Kabupaten Madiun.'), 35) }}
                    </p>
                    <div class="d-flex align-items-center flex-wrap gap-3 small text-muted">
                        @if(!empty($d['organization']))
                        <span><i class="fas fa-building text-secondary me-1"></i> {{ $d['organization']['title'] ?? $d['organization']['name'] ?? 'Pemkab Madiun' }}</span>
                        @endif
                        @if(!empty($d['metadata_created']))
                        <span><i class="far fa-calendar text-secondary me-1"></i> {{ \Carbon\Carbon::parse($d['metadata_created'])->format('Y') }}</span>
                        @endif
                        @if(!empty($d['groups']) && count($d['groups']) > 0)
                        <span><i class="fas fa-layer-group text-secondary me-1"></i> {{ $d['groups'][0]['display_name'] ?? $d['groups'][0]['title'] ?? 'Urusan' }}</span>
                        @endif
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                            <i class="fas fa-check-circle me-1"></i> Terpublikasi
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i>
                    <h5 class="fw-bold">Tidak ada dataset ditemukan</h5>
                    <p class="small mb-0">Coba ubah kata kunci atau bersihkan filter pencarian.</p>
                </div>
                @endforelse
            </div>

            @if($pages > 1)
            <div class="card-footer bg-white p-3 d-flex justify-content-center">
                <nav>
                    <ul class="pagination mb-0">
                        <li class="page-item {{ !$hasPrevPage ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ route('dataset', array_merge(request()->input(), ['page' => $page - 1])) }}">« Sebelumnya</a>
                        </li>
                        <li class="page-item disabled">
                            <span class="page-link">Halaman {{ $page }} dari {{ $pages }}</span>
                        </li>
                        <li class="page-item {{ !$hasNextPage ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ route('dataset', array_merge(request()->input(), ['page' => $page + 1])) }}">Selanjutnya »</a>
                        </li>
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection