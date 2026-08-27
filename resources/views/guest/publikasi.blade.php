@extends('guest.layout')

@section('content')
<div class="page-banner pt-60 pb-60" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Buku Publikasi & Rencana Terbit</h1>
            <p class="text-white-50 mb-0" style="font-size: 16px;">Koleksi dokumen resmi, buku statistik daerah, serta jadwal rencana terbit publikasi Pemerintah Kabupaten Madiun</p>
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
                                <div class="col-lg-8 col-md-6">
                                    <label class="form-label fw-semibold text-muted small"><i class="fas fa-search me-1"></i> Cari Publikasi</label>
                                    <input type="text" name="search" class="form-control" placeholder="Kata kunci judul buku publikasi atau instansi..." value="{{ request('search') }}" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px;">
                                </div>
                                <div class="col-lg-2 col-md-3 col-6">
                                    <label class="form-label fw-semibold text-muted small"><i class="fas fa-calendar-alt me-1"></i> Tahun</label>
                                    <select name="tahun" class="form-select" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 9px 14px;">
                                        <option value="">Semua Tahun</option>
                                        @foreach($tahuns as $th)
                                            <option value="{{ $th->tahun }}" {{ request('tahun') == $th->tahun ? 'selected' : '' }}>{{ $th->tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-3 col-6 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100 fw-semibold" style="border-radius: 8px; padding: 9px 14px;"><i class="fas fa-search me-1"></i> Cari</button>
                                    @if(request()->hasAny(['search', 'tahun', 'status']))
                                        <a href="{{ route('guest.publikasi') }}" class="btn btn-outline-secondary" style="border-radius: 8px; padding: 9px 14px;"><i class="fas fa-redo"></i></a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Grid Publikasi Buku -->
                <div class="row g-4">
                    @forelse($publikasi as $pub)
                    @php
                        $isTerbit = ($pub->status == 'Terbit' || $pub->pdf_path || $pub->berkas);
                        $opdName = $pub->opd ? $pub->opd->nama_opd : ($pub->instansi ?: 'Pemerintah Kabupaten Madiun');
                        $pubYear = $pub->tahun ?: ($pub->created_at ? $pub->created_at->format('Y') : date('Y'));
                    @endphp
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition d-flex flex-column">
                            <div class="position-relative overflow-hidden bg-light" style="height: 280px;">
                                @if($isTerbit && ($pub->image || $pub->gambar))
                                    <img src="{{ asset('storage/public/blogs/' . ($pub->image ?: $pub->gambar)) }}" class="w-100 h-100 object-fit-cover" alt="{{ $pub->title ?? $pub->nama_publikasi }}">
                                @else
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-center" style="background: linear-gradient(135deg, #0d3b66 0%, #1e40af 100%);">
                                        <i class="fas fa-book fa-3x text-white-50 mb-2"></i>
                                        <span class="text-white fw-semibold small">{{ $isTerbit ? 'Buku Terpublikasi' : 'Rencana Terbit' }}</span>
                                    </div>
                                @endif
                                <span class="badge bg-dark position-absolute top-0 end-0 m-2 opacity-90">
                                    {{ $pubYear }}
                                </span>
                                <span class="badge {{ $isTerbit ? 'bg-success' : 'bg-warning text-dark' }} position-absolute top-0 start-0 m-2">
                                    <i class="fas {{ $isTerbit ? 'fa-check-circle' : 'fa-clock' }} me-1"></i> {{ $isTerbit ? 'Terbit' : 'Rencana' }}
                                </span>
                            </div>
                            <div class="card-body p-3 d-flex flex-column flex-grow-1">
                                <div class="text-muted small mb-1">
                                    <i class="fas fa-building text-secondary me-1"></i> {{ Str::limit($opdName, 30) }}
                                </div>
                                <h6 class="card-title fw-bold text-dark mb-2 line-clamp-2" title="{{ $pub->title ?? $pub->nama_publikasi }}">
                                    {{ $pub->title ?? $pub->nama_publikasi }}
                                </h6>
                                @if($pub->jadwal_rencana_terbit || $pub->jadwal_terbit)
                                    <div class="small text-muted mb-3">
                                        <i class="far fa-calendar-alt text-primary me-1"></i> Rencana: {{ \Carbon\Carbon::parse($pub->jadwal_rencana_terbit ?: $pub->jadwal_terbit)->translatedFormat('d M Y') }}
                                    </div>
                                @endif
                                <div class="mt-auto d-grid gap-2">
                                    @if($isTerbit && ($pub->pdf_path || $pub->berkas))
                                        <a href="{{ asset('storage/public/blogs/' . ($pub->pdf_path ?: $pub->berkas)) }}" target="_blank" class="btn btn-sm btn-outline-danger fw-semibold rounded-pill py-2">
                                            <i class="fas fa-file-pdf me-1"></i> Unduh Dokumen (PDF)
                                        </a>
                                    @else
                                        <a href="{{ route('guest.publikasi.detail', is_numeric($pub->id) ? encrypt($pub->id) : $pub->id) }}" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill py-2">
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
