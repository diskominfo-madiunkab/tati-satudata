@extends('guest.layout')

@section('content')
<div class="page-banner pt-60 pb-60" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Katalog Data</h1>
            <p class="text-white-50 mb-0" style="font-size: 16px;">Menampilkan Daftar Data Pemerintah Kabupaten Madiun</p>
        </div>
    </div>
</div>

<section class="katalog-area pt-50 pb-70 bg-light">
    <div class="container">
        <!-- Filter Card -->
        <div class="card shadow-sm border-0 mb-4 rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('guest.katalog') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-search me-1"></i> Cari Data</label>
                            <input type="text" name="search" class="form-control border-secondary-subtle" placeholder="Kata kunci nama data..." value="{{ request('search') }}" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 9px 14px;">
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-calendar-alt me-1"></i> Tahun</label>
                            <select name="tahun" class="form-select border-secondary-subtle" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 9px 14px;">
                                <option value="">Semua Tahun</option>
                                @foreach($tahuns as $th)
                                    <option value="{{ $th->tahun }}" {{ request('tahun') == $th->tahun ? 'selected' : '' }}>{{ $th->tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-building me-1"></i> Produsen / OPD</label>
                            <select name="opd_id" class="form-select border-secondary-subtle" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 9px 14px; text-overflow: ellipsis;">
                                <option value="">Semua Produsen / OPD</option>
                                @foreach($opds as $opd)
                                    <option value="{{ $opd->id }}" {{ request('opd_id') == $opd->id ? 'selected' : '' }}>{{ $opd->nama_opd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 fw-semibold" style="border-radius: 8px; padding: 9px 14px;"><i class="fas fa-search me-1"></i> Cari</button>
                            <a href="{{ route('guest.katalog') }}" class="btn btn-outline-secondary" title="Reset Filter" style="border-radius: 8px; padding: 9px 14px;"><i class="fas fa-redo"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Count -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small">Menampilkan <strong>{{ $katalog->total() }}</strong> total data</span>
        </div>

        <!-- Table Card -->
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="width: 50px;" class="text-center">No</th>
                            <th scope="col">Nama Data</th>
                            <th scope="col">Produsen Data (OPD)</th>
                            <th scope="col" class="text-center">Tahun</th>
                            <th scope="col" class="text-center">Sumber Referensi</th>
                            <th scope="col" class="text-center">Status Terkini</th>
                            <th scope="col" class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($katalog as $index => $item)
                        <tr>
                            <td class="text-center text-muted small">{{ $katalog->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->nama_data }}</div>
                                <div class="text-muted small">
                                    <span class="badge bg-light text-dark border">{{ $item->jenis_data ?: 'Data Sektoral' }}</span>
                                    @if($item->data_prioritas)
                                        <span class="badge bg-danger"><i class="fas fa-star me-1"></i> Prioritas</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-break">{{ $item->opd ? $item->opd->nama_opd : '-' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $item->tahun }}</span>
                            </td>
                            <td class="text-center small">
                                <span class="badge bg-info text-dark">{{ $item->sumber_referensi ?: ($item->sumber_data ?: 'RPJMD') }}</span>
                            </td>
                            <td class="text-center">
                                @if($item->status_id == \App\Models\Data::STATUS_TERPUBLIKASI)
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Terpublikasi</span>
                                @elseif($item->status_id == \App\Models\Data::STATUS_SIAP_PUBLIKASI)
                                    <span class="badge bg-primary"><i class="fas fa-clock me-1"></i> Siap Publikasi</span>
                                @elseif($item->status_id == \App\Models\Data::STATUS_PROSES_VERIFIKASI)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-search me-1"></i> Pemeriksaan</span>
                                @elseif($item->status_id == \App\Models\Data::STATUS_DRAFT)
                                    <span class="badge bg-secondary"><i class="fas fa-file me-1"></i> Perencanaan / Draft</span>
                                @elseif($item->status_id == \App\Models\Data::STATUS_TOLAK)
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Ditolak</span>
                                @else
                                    <span class="badge bg-light text-dark border">{{ $item->status ? $item->status->status : 'Proses' }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status_id == \App\Models\Data::STATUS_TERPUBLIKASI)
                                    <a href="{{ route('dataset.show', $item->nama_data) }}" class="btn btn-sm btn-outline-primary" title="Lihat Dataset"><i class="fas fa-eye"></i></a>
                                @else
                                    <button class="btn btn-sm btn-light border text-muted" title="Data Internal (Belum Terpublikasi)"><i class="fas fa-lock"></i></button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i>
                                <p class="mb-0">Tidak ada data katalog yang sesuai dengan filter pencarian.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($katalog->hasPages())
                <div class="card-footer bg-white p-3 d-flex justify-content-center">
                    {{ $katalog->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
