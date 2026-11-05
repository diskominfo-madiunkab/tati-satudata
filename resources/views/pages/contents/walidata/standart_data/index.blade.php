@extends('pages.main.layout')

@section('content')
<div class="pagetitle">
    <h1>Master Standar Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/d_walidata">Dashboard</a></li>
            <li class="breadcrumb-item active">Master Standar Data</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <!-- KPI Summary Row -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card info-card sales-card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Standar Data</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                            <i class="bi bi-database"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ number_format($totalStandar) }}</h6>
                            <span class="text-muted small pt-2">Database Master Daerah</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card info-card revenue-card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Standar Berkodifikasi</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white">
                            <i class="bi bi-upc-scan"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ number_format($totalDenganKode) }}</h6>
                            <span class="text-success small pt-1 fw-bold">Memiliki Kode Standar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card info-card customers-card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Referensi SDSN BPS</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white">
                            <i class="bi bi-globe"></i>
                        </div>
                        <div class="ps-3">
                            <h6>Aktif</h6>
                            <span class="text-muted small pt-2">Terintegrasi DNA BPS</span>
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
                    <!-- Default Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered" id="standarTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="daerah-tab" data-bs-toggle="tab" data-bs-target="#tab-daerah" type="button" role="tab">
                                <i class="bi bi-hdd-stack me-1"></i> Database Standar Data Daerah
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sdsn-tab" data-bs-toggle="tab" data-bs-target="#tab-sdsn" type="button" role="tab">
                                <i class="bi bi-cloud-arrow-down me-1"></i> Referensi SDSN BPS (DNA)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content pt-3">
                        <!-- Tab 1: Database Standar Data Daerah -->
                        <div class="tab-pane fade show active" id="tab-daerah" role="tabpanel">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <form action="{{ route('walidata.standar-data.index') }}" method="GET" class="d-flex gap-2 flex-grow-1" style="max-width: 500px;">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Cari konsep, definisi, kode, ukuran..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                                        @if(request('search'))
                                            <a href="{{ route('walidata.standar-data.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i> Reset</a>
                                        @endif
                                    </div>
                                </form>

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahStandar">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Standar Data
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th style="width: 140px;">Kode Standar</th>
                                            <th style="width: 220px;">Konsep</th>
                                            <th>Definisi</th>
                                            <th style="width: 120px;">Ukuran</th>
                                            <th style="width: 100px;">Satuan</th>
                                            <th style="width: 130px;">Klasifikasi</th>
                                            <th style="width: 110px;" class="text-center">Aksi</th>
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
                                                <td><small class="text-secondary">{{ Str::limit($item->definisi ?? '-', 160) }}</small></td>
                                                <td><span class="badge bg-light text-dark border">{{ $item->ukuran ?? '-' }}</span></td>
                                                <td>{{ $item->satuan ?? '-' }}</td>
                                                <td><small>{{ $item->klasifikasi ?? '-' }}</small></td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-primary" 
                                                            onclick="editStandar({{ json_encode($item) }})" 
                                                            title="Edit Standar Data">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger" 
                                                            onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->konsep) }}')" 
                                                            title="Hapus Standar Data">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                                    Tidak ada data standar ditemukan.
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

                        <!-- Tab 2: Referensi SDSN BPS -->
                        <div class="tab-pane fade" id="tab-sdsn" role="tabpanel">
                            <div class="alert alert-info d-flex align-items-center mb-4">
                                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Standar Data Statistik Nasional (SDSN) - BPS RI</strong>
                                    <p class="mb-0 small">Modul ini terhubung secara konseptual dengan repositori SDSN BPS melalui DNA BPS. Standar data yang terdaftar di BPS dapat dijadikan acuan dalam penyusunan Daftar Data pada tahap Perencanaan Data.</p>
                                </div>
                            </div>

                            <div class="card border p-3 mb-4 bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="fw-bold mb-1"><i class="bi bi-link-45deg"></i> Dokumentasi API SDSN BPS</h6>
                                        <p class="small text-muted mb-0">Akses langsung OpenAPI & endpoint SDSN untuk sinkronisasi metadata dan standar data statistik.</p>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                        <a href="https://dna.web.bps.go.id/api/documentation/" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Portal DNA BPS
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="fw-bold"><i class="bi bi-lightbulb text-warning me-1"></i> Panduan Penggunaan Standar Data dalam Perencanaan:</h6>
                                    <ol class="small text-secondary ps-3">
                                        <li>Standar Data merupakan pedoman atribut yang mencakup <strong>Konsep, Definisi, Ukuran, Satuan, dan Klasifikasi Penyajian</strong>.</li>
                                        <li>Walidata dapat memperbarui database Standar Data kapan saja tanpa terikat siklus tahapan verifikasi.</li>
                                        <li>Saat Walidata atau Produsen Data menyusun <strong>Perencanaan Data (17 Field)</strong>, memilih kode standar data akan mengisi atribut secara otomatis.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Tab Content -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Standar Data -->
<div class="modal fade" id="modalTambahStandar" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('walidata.standar-data.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i> Tambah Master Standar Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Kode Standar Data</label>
                        <div class="col-sm-9">
                            <input type="text" name="kode" class="form-control" placeholder="Contoh: STD-001 / Kode SDSN BPS">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Konsep <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="konsep" class="form-control" required placeholder="Nama / Konsep Data">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Definisi</label>
                        <div class="col-sm-9">
                            <textarea name="definisi" class="form-control" rows="4" placeholder="Uraian definisi operasional standar data"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Ukuran</label>
                        <div class="col-sm-9">
                            <input type="text" name="ukuran" class="form-control" placeholder="Contoh: Jumlah, Rasio, Persentase, Nilai">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Satuan</label>
                        <div class="col-sm-9">
                            <input type="text" name="satuan" class="form-control" placeholder="Contoh: Jiwa, Orang, Unit, Ton, Km, %">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Klasifikasi Penyajian</label>
                        <div class="col-sm-9">
                            <input type="text" name="klasifikasi" class="form-control" placeholder="Contoh: Kelompok Umur, Jenis Kelamin, Wilayah">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Standar Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Standar Data -->
<div class="modal fade" id="modalEditStandar" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditStandar" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-1"></i> Edit Master Standar Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Kode Standar Data</label>
                        <div class="col-sm-9">
                            <input type="text" name="kode" id="edit_kode" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Konsep <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="konsep" id="edit_konsep" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Definisi</label>
                        <div class="col-sm-9">
                            <textarea name="definisi" id="edit_definisi" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Ukuran</label>
                        <div class="col-sm-9">
                            <input type="text" name="ukuran" id="edit_ukuran" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Satuan</label>
                        <div class="col-sm-9">
                            <input type="text" name="satuan" id="edit_satuan" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Klasifikasi Penyajian</label>
                        <div class="col-sm-9">
                            <input type="text" name="klasifikasi" id="edit_klasifikasi" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Perbarui Standar Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Delete Standar Data (Hidden) -->
<form id="formDeleteStandar" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function editStandar(item) {
    document.getElementById('formEditStandar').action = '/data_walidata/standar-data/' + item.id + '/update';
    document.getElementById('edit_kode').value = item.kode || '';
    document.getElementById('edit_konsep').value = item.konsep || '';
    document.getElementById('edit_definisi').value = item.definisi || '';
    document.getElementById('edit_ukuran').value = item.ukuran || '';
    document.getElementById('edit_satuan').value = item.satuan || '';
    document.getElementById('edit_klasifikasi').value = item.klasifikasi || '';
    
    var modal = new bootstrap.Modal(document.getElementById('modalEditStandar'));
    modal.show();
}

function confirmDelete(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus Standar Data "' + nama + '"?')) {
        var form = document.getElementById('formDeleteStandar');
        form.action = '/data_walidata/standar-data/' + id + '/destroy';
        form.submit();
    }
}
</script>
@endsection
