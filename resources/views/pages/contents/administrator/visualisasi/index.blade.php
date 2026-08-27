@extends('pages.main.layout')

@section('content')
<div class="pagetitle">
    <h1>Kelola Visualisasi Tableau</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Kelola Visualisasi</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 p-0">Daftar Visualisasi Interaktif</h5>
                    <a href="{{ route('kelola-visualisasi.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Visualisasi
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table class="table table-hover table-bordered align-middle datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th>Judul Visualisasi</th>
                                    <th>URL Embed Tableau</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 150px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($visualisasis as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $item->title }}</td>
                                    <td>
                                        <a href="{{ $item->tableau_url }}" target="_blank" class="small text-truncate d-inline-block" style="max-width: 280px;">
                                            {{ $item->tableau_url }} <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </td>
                                    <td class="small text-muted">{{ Str::limit(strip_tags($item->content), 80) ?: '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('kelola-visualisasi.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="{{ route('kelola-visualisasi.delete', $item->id) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus visualisasi ini?');" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada visualisasi yang ditambahkan.</td>
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
