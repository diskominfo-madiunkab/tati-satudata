@extends('pages.main.layout')

@section('content')
<div class="pagetitle">
    <h1>Kelola Regulasi Satu Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Kelola Regulasi</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 p-0">Daftar Regulasi & Peraturan</h5>
                    <a href="{{ route('kelola-regulasi.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Regulasi
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table class="table table-hover table-bordered align-middle datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th>Judul Peraturan</th>
                                    <th>Nomor & Tahun</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Dokumen PDF</th>
                                    <th class="text-center">Status</th>
                                    <th style="width: 150px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($regulasis as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $item->judul }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->nomor ?: '-' }}</span>
                                        <div class="small text-muted">Tahun {{ $item->tahun }}</div>
                                    </td>
                                    <td><span class="badge bg-info text-dark">{{ $item->kategori }}</span></td>
                                    <td class="text-center">
                                        @if($item->file_dokumen)
                                            <a href="{{ asset('storage/' . $item->file_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-file-earmark-pdf"></i> Unduh
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $item->status ?: 'Berlaku' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('kelola-regulasi.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="{{ route('kelola-regulasi.delete', $item->id) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus regulasi ini?');" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Belum ada regulasi yang ditambahkan.</td>
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
