@extends('guest.layout')

@section('content')
<div class="page-banner pt-40 pb-40" style="background: linear-gradient(135deg, #023e8a 0%, #0077b6 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Buku Publikasi & Rencana Terbit</h1>
            <p class="text-white-50 mb-0">Koleksi dokumen resmi, buku statistik daerah, serta jadwal rencana terbit publikasi Satu Data Kabupaten Madiun.</p>
        </div>
    </div>
</div>

<section class="publikasi-area pt-50 pb-70 bg-light">
    <div class="container">
        <!-- Tabs Navigasi: Publikasi & Jadwal Rencana Terbit -->
        <ul class="nav nav-pills nav-fill mb-4 bg-white p-2 rounded-3 shadow-sm" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="buku-tab" data-bs-toggle="pill" data-bs-target="#tab-buku" type="button" role="tab">
                    <i class="fas fa-book-open me-2"></i> Daftar Buku Publikasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="jadwal-tab" data-bs-toggle="pill" data-bs-target="#tab-jadwal" type="button" role="tab">
                    <i class="fas fa-calendar-check me-2"></i> Jadwal Rencana Terbit (Buku Publikasi)
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- TAB 1: Daftar Buku Publikasi -->
            <div class="tab-pane fade show active" id="tab-buku" role="tabpanel">
                <!-- Filter Search -->
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-body p-4">
                        <form action="{{ route('guest.publikasi') }}" method="GET">
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-10 col-md-9">
                                    <label class="form-label fw-semibold text-muted small"><i class="fas fa-search me-1"></i> Cari Publikasi</label>
                                    <input type="text" name="search" class="form-control" placeholder="Kata kunci judul buku publikasi..." value="{{ request('search') }}">
                                </div>
                                <div class="col-lg-2 col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Cari</button>
                                    @if(request('search'))
                                        <a href="{{ route('guest.publikasi') }}" class="btn btn-outline-secondary"><i class="fas fa-redo"></i></a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Grid Publikasi Buku -->
                <div class="row g-4">
                    @forelse($publikasi as $pub)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white hover-shadow transition">
                            <div class="position-relative overflow-hidden bg-light" style="height: 280px;">
                                @if($pub->image)
                                    <img src="{{ asset('storage/public/blogs/' . $pub->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $pub->title }}">
                                @elseif($pub->gambar)
                                    <img src="{{ asset('storage/public/blogs/' . $pub->gambar) }}" class="w-100 h-100 object-fit-cover" alt="{{ $pub->title ?? $pub->nama_publikasi }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary bg-opacity-10 text-muted">
                                        <i class="fas fa-book fa-3x"></i>
                                    </div>
                                @endif
                                <span class="badge bg-primary position-absolute top-0 end-0 m-2">
                                    {{ $pub->created_at ? $pub->created_at->format('Y') : date('Y') }}
                                </span>
                            </div>
                            <div class="card-body p-3 d-flex flex-column">
                                <h6 class="card-title fw-bold text-dark mb-2 line-clamp-2" title="{{ $pub->title ?? $pub->nama_publikasi }}">
                                    {{ $pub->title ?? $pub->nama_publikasi }}
                                </h6>
                                <div class="text-muted small mb-3 flex-grow-1">
                                    <i class="fas fa-calendar-day me-1"></i> {{ $pub->created_at ? $pub->created_at->translatedFormat('d M Y') : '-' }}
                                </div>
                                <div class="d-grid gap-2">
                                    @if($pub->pdf_path)
                                        <a href="{{ asset('storage/public/blogs/' . $pub->pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-file-pdf me-1"></i> Unduh Dokumen (PDF)
                                        </a>
                                    @elseif($pub->berkas)
                                        <a href="{{ asset('storage/public/blogs/' . $pub->berkas) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-file-pdf me-1"></i> Unduh Dokumen (PDF)
                                        </a>
                                    @else
                                        <a href="{{ route('guest.publikasi.detail', encrypt($pub->id)) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-info-circle me-1"></i> Detail Publikasi
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-book-open fa-3x mb-3 text-muted"></i>
                        <p>Belum ada publikasi yang sesuai dengan kriteria pencarian.</p>
                    </div>
                    @endforelse
                </div>

                @if(method_exists($publikasi, 'hasPages') && $publikasi->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $publikasi->withQueryString()->links() }}
                    </div>
                @endif
            </div>

            <!-- TAB 2: Jadwal Rencana Terbit (Buku Publikasi) -->
            <div class="tab-pane fade" id="tab-jadwal" role="tabpanel">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-calendar-alt text-primary me-2"></i>Jadwal Rencana Terbit Buku Publikasi Statistik Sektoral</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th>Judul Rencana Publikasi</th>
                                    <th>Instansi / Produsen Penyusun</th>
                                    <th class="text-center">Tahun Data</th>
                                    <th class="text-center">Estimasi Jadwal Terbit</th>
                                    <th class="text-center">Frekuensi</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwalTerbit as $index => $jadwal)
                                <tr>
                                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $jadwal->judul_buku }}</div>
                                        @if($jadwal->deskripsi)
                                            <div class="small text-muted">{{ Str::limit($jadwal->deskripsi, 90) }}</div>
                                        @endif
                                    </td>
                                    <td class="small fw-semibold text-muted">{{ $jadwal->penyusun ?: 'Pemerintah Kab. Madiun' }}</td>
                                    <td class="text-center"><span class="badge bg-secondary">{{ $jadwal->tahun }}</span></td>
                                    <td class="text-center small">
                                        <strong>{{ $jadwal->rencana_terbit ? \Carbon\Carbon::parse($jadwal->rencana_terbit)->translatedFormat('d F Y') : '-' }}</strong>
                                    </td>
                                    <td class="text-center"><span class="badge bg-light text-dark border">{{ $jadwal->frekuensi_terbit ?: 'Tahunan' }}</span></td>
                                    <td class="text-center">
                                        @if($jadwal->status_terbit == 'Terbit')
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Terbit</span>
                                        @elseif($jadwal->status_terbit == 'Proses Penyusunan')
                                            <span class="badge bg-warning text-dark"><i class="fas fa-sync-alt me-1"></i> Penyusunan</span>
                                        @else
                                            <span class="badge bg-info text-dark"><i class="fas fa-calendar-day me-1"></i> Direncanakan</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Belum ada jadwal terbit publikasi yang terdaftar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
