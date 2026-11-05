@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')

<div class="pagetitle">
    <h1>Kelola Regulasi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/d_administrator">Dashboard</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Kelola Regulasi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title p-0 m-0">Daftar Dokumen Regulasi</h5>
                        <a href="{{ route('regulasi.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Regulasi
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table datatable table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 40px;">#</th>
                                    <th scope="col">Judul Regulasi</th>
                                    <th scope="col" style="width: 160px;">Nomor & Tahun</th>
                                    <th scope="col" style="width: 140px;">Kategori</th>
                                    <th scope="col" class="text-center" style="width: 100px;">Status</th>
                                    <th scope="col" class="text-center" style="width: 80px;">Berkas</th>
                                    <th scope="col" class="text-center" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach($regulasis as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->judul }}</div>
                                        @if($item->tentang)
                                            <div class="small text-muted">{{ Str::limit($item->tentang, 80) }}</div>
                                        @endif
                                    </td>
                                    <td class="small">
                                        <div class="fw-bold">{{ $item->nomor ?: '-' }}</div>
                                        <div class="text-muted">Tahun {{ $item->tahun ?: '-' }}</div>
                                    </td>
                                    <td class="small">
                                        <span class="badge bg-info text-dark">{{ $item->kategori }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->status == 'Berlaku' || empty($item->status))
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Berlaku</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i> Tidak Berlaku</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->file_dokumen)
                                            <a href="{{ Storage::url($item->file_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Unduh PDF">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('regulasi.edit', $item->id) }}" class="btn btn-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRegModal{{ $item->id }}" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Modal Delete -->
                                        <div class="modal fade" id="deleteRegModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        Apakah Anda yakin ingin menghapus regulasi <strong>"{{ $item->judul }}"</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <a href="{{ route('regulasi.delete', $item->id) }}" class="btn btn-danger">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection

