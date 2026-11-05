@extends('guest.layout')

@push('style')
<style>
    .regulasi-area .nice-select {
        display: none !important;
    }
    .regulasi-area select.form-select {
        display: block !important;
        border: 1.5px solid #adb5bd !important;
        border-radius: 6px;
        background-color: #fff;
        height: 42px;
    }
    .regulasi-area .form-control {
        border: 1.5px solid #adb5bd !important;
        border-radius: 6px;
        height: 42px;
    }
    .regulasi-area .btn {
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="page-banner">
    <div class="container">
        <div class="page-banner-content text-center">
            <div class="page-banner-pill">
                <i class="fas fa-gavel text-info"></i> Satu Data Kabupaten Madiun
            </div>
            <h1 class="page-banner-title">Regulasi Satu Data</h1>
            <p class="page-banner-subtitle">Dasar hukum, peraturan, dan pedoman teknis penyelenggaraan Satu Data Indonesia di Kabupaten Madiun.</p>
        </div>
    </div>
</div>

<section class="regulasi-area pt-50 pb-70 bg-light">
    <div class="container">
        <!-- Filter Card -->
        <div class="card shadow-sm border mb-4 rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('guest.regulasi') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-5 col-md-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-search me-1"></i> Cari Regulasi</label>
                            <input type="text" name="search" class="form-control border" style="border-color: #ced4da !important;" placeholder="Kata kunci judul, nomor, atau tentang peraturan..." value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-tags me-1"></i> Kategori</label>
                            <select name="kategori" class="form-select border" style="border: 1.5px solid #b0bec5 !important; background-color: #fff; box-shadow: none;">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-calendar-alt me-1"></i> Tahun</label>
                            <select name="tahun" class="form-select border" style="border: 1.5px solid #b0bec5 !important; background-color: #fff; box-shadow: none;">
                                <option value="">Semua Tahun</option>
                                @foreach($tahuns as $th)
                                    <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
                            @if(request('search') || request('kategori') || request('tahun'))
                                <a href="{{ route('guest.regulasi') }}" class="btn btn-outline-secondary"><i class="fas fa-redo"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card shadow-sm border rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th>Judul Peraturan</th>
                            <th class="text-center" style="width: 170px;">Nomor & Tahun</th>
                            <th class="text-center" style="width: 160px;">Kategori</th>
                            <th class="text-center" style="width: 110px;">Status</th>
                            <th class="text-center" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($regulasi as $index => $item)
                        <tr>
                            <td class="text-center text-muted small">{{ $regulasi->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $item->judul }}</div>
                                @if($item->tentang)
                                    <div class="text-muted small mt-1"><i class="fas fa-info-circle me-1"></i>Tentang: {{ $item->tentang }}</div>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $item->nomor ?: '-' }}</span>
                                @if($item->tahun)
                                    <div class="text-muted small">Tahun {{ $item->tahun }}</div>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">{{ $item->kategori }}</span>
                            </td>
                            <td class="text-center">
                                @if($item->status == 'Berlaku' || empty($item->status))
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Berlaku</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i> Tidak Berlaku</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->file_dokumen)
                                    <a href="{{ Storage::url($item->file_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Unduh PDF">
                                        <i class="fas fa-file-pdf me-1"></i> PDF
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalRegulasi{{ $item->id }}">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </button>
                                @endif

                                <!-- Modal Detail -->
                                <div class="modal fade text-start" id="modalRegulasi{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fs-6 fw-bold">Detail Regulasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6 class="fw-bold text-dark mb-2">{{ $item->judul }}</h6>
                                                <table class="table table-sm table-borderless mb-0">
                                                    <tr>
                                                        <td class="text-muted" style="width: 100px;">Nomor</td>
                                                        <td>: {{ $item->nomor ?: '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Tahun</td>
                                                        <td>: {{ $item->tahun ?: '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Kategori</td>
                                                        <td>: {{ $item->kategori }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Status</td>
                                                        <td>: {{ $item->status ?: 'Berlaku' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Tentang</td>
                                                        <td>: {{ $item->tentang ?: '-' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-gavel fa-3x mb-3 text-muted"></i>
                                <p class="mb-0">Tidak ada dokumen regulasi yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($regulasi, 'hasPages') && $regulasi->hasPages())
                <div class="d-flex justify-content-center p-3 border-top">
                    {{ $regulasi->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
