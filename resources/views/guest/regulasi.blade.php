@extends('guest.layout')

@section('content')
<div class="page-banner pt-40 pb-40" style="background: linear-gradient(135deg, #1d3557 0%, #457b9d 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Regulasi Satu Data</h1>
            <p class="text-white-50 mb-0">Dasar hukum, peraturan, dan pedoman teknis penyelenggaraan Satu Data Indonesia di Kabupaten Madiun.</p>
        </div>
    </div>
</div>

<section class="regulasi-area pt-50 pb-70 bg-light">
    <div class="container">
        <!-- Filter Card -->
        <div class="card shadow-sm border-0 mb-4 rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('guest.regulasi') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-5 col-md-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-search me-1"></i> Cari Regulasi</label>
                            <input type="text" name="search" class="form-control" placeholder="Kata kunci judul, nomor, atau tentang peraturan..." value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-tags me-1"></i> Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-calendar-alt me-1"></i> Tahun</label>
                            <select name="tahun" class="form-select">
                                <option value="">Semua Tahun</option>
                                @foreach($tahuns as $th)
                                    <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                            <a href="{{ route('guest.regulasi') }}" class="btn btn-outline-secondary"><i class="fas fa-redo"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th>Judul Peraturan</th>
                            <th class="text-center" style="width: 160px;">Nomor & Tahun</th>
                            <th class="text-center" style="width: 150px;">Kategori</th>
                            <th class="text-center" style="width: 100px;">Status</th>
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
                                <span class="badge bg-success">{{ $item->status ?: 'Berlaku' }}</span>
                            </td>
                            <td class="text-center">
                                @if($item->file_dokumen)
                                    <a href="{{ asset('storage/' . $item->file_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Unduh PDF">
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
                                                <h5 class="modal-title fw-bold">{{ $item->judul }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Nomor:</strong> {{ $item->nomor }}</p>
                                                <p><strong>Kategori:</strong> {{ $item->kategori }}</p>
                                                <p><strong>Tahun:</strong> {{ $item->tahun }}</p>
                                                <p><strong>Tentang:</strong> {{ $item->tentang }}</p>
                                                <p><strong>Status:</strong> <span class="badge bg-success">{{ $item->status }}</span></p>
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
                                <p class="mb-0">Tidak ada regulasi yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($regulasi->hasPages())
                <div class="card-footer bg-white p-3 d-flex justify-content-center">
                    {{ $regulasi->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
