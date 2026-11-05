@extends('pages.main.layout')

@section('content')
<div class="pagetitle">
    <h1>Master Standar Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/d_produsen">Dashboard</a></li>
            <li class="breadcrumb-item active">Master Standar Data</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <!-- Info Banner -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card info-card sales-card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Database Standar Data</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                            <i class="bi bi-database"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ number_format($totalStandar) }}</h6>
                            <span class="text-muted small pt-2">Standar Data Terdaftar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card info-card revenue-card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Standar Berkodifikasi</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white">
                            <i class="bi bi-upc-scan"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ number_format($totalDenganKode) }}</h6>
                            <span class="text-success small pt-1 fw-bold">Memiliki Kode Standar BPS/Daerah</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body pt-3">
                    <div class="alert alert-light border d-flex align-items-center mb-3">
                        <i class="bi bi-info-circle text-primary fs-4 me-3"></i>
                        <div class="small">
                            <strong>Referensi Standar Data Statistik:</strong> Gunakan tabel standar data ini sebagai acuan pengisian konsep, definisi, ukuran, satuan, dan klasifikasi penyajian saat menyusun Usulan/Perencanaan Data bagi Perangkat Daerah Anda.
                        </div>
                    </div>

                    <!-- Search Form -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <form action="{{ route('produsen.standar-data.index') }}" method="GET" class="d-flex gap-2 flex-grow-1" style="max-width: 500px;">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari konsep, definisi, ukuran, satuan..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                                @if(request('search'))
                                    <a href="{{ route('produsen.standar-data.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i> Reset</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th style="width: 140px;">Kode Standar</th>
                                    <th style="width: 240px;">Konsep</th>
                                    <th>Definisi</th>
                                    <th style="width: 120px;">Ukuran</th>
                                    <th style="width: 100px;">Satuan</th>
                                    <th style="width: 140px;">Klasifikasi</th>
                                    <th style="width: 80px;" class="text-center">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($standarData as $idx => $item)
                                    <tr>
                                        <td>{{ $standarData->firstItem() + $idx }}</td>
                                        <td>
                                            @if($item->kode)
                                                <span class="badge bg-secondary font-monospace">{{ $item->kode }}</span>
                                            @else
                                                <span class="text-muted fst-italic">-</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold">{{ $item->konsep }}</td>
                                        <td><small class="text-secondary">{{ Str::limit($item->definisi ?? '-', 150) }}</small></td>
                                        <td><span class="badge bg-light text-dark border">{{ $item->ukuran ?? '-' }}</span></td>
                                        <td>{{ $item->satuan ?? '-' }}</td>
                                        <td><small>{{ $item->klasifikasi ?? '-' }}</small></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                onclick="showDetailStandar({{ json_encode($item) }})" 
                                                title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                            Tidak ada standar data ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Menampilkan {{ $standarData->firstItem() ?? 0 }} s.d. {{ $standarData->lastItem() ?? 0 }} dari {{ $standarData->total() }} Standar Data
                        </div>
                        <div>
                            {{ $standarData->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detail Standar Data (Read-Only) -->
<div class="modal fade" id="modalDetailStandar" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle me-1"></i> Rincian Standar Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <label class="col-sm-3 fw-bold">Kode Standar</label>
                    <div class="col-sm-9" id="detail_kode">-</div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-3 fw-bold">Konsep</label>
                    <div class="col-sm-9 fw-semibold text-primary" id="detail_konsep">-</div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-3 fw-bold">Definisi</label>
                    <div class="col-sm-9" id="detail_definisi" style="white-space: pre-line;">-</div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-3 fw-bold">Ukuran</label>
                    <div class="col-sm-9" id="detail_ukuran">-</div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-3 fw-bold">Satuan</label>
                    <div class="col-sm-9" id="detail_satuan">-</div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-3 fw-bold">Klasifikasi Penyajian</label>
                    <div class="col-sm-9" id="detail_klasifikasi">-</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showDetailStandar(item) {
    document.getElementById('detail_kode').innerHTML = item.kode ? '<span class="badge bg-secondary font-monospace">' + item.kode + '</span>' : '-';
    document.getElementById('detail_konsep').textContent = item.konsep || '-';
    document.getElementById('detail_definisi').textContent = item.definisi || '-';
    document.getElementById('detail_ukuran').textContent = item.ukuran || '-';
    document.getElementById('detail_satuan').textContent = item.satuan || '-';
    document.getElementById('detail_klasifikasi').textContent = item.klasifikasi || '-';
    
    var modal = new bootstrap.Modal(document.getElementById('modalDetailStandar'));
    modal.show();
}
</script>
@endsection
