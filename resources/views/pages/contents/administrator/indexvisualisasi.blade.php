@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')

<div class="pagetitle">
    <h1>Kelola Visualisasi (Tableau)</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/d_administrator">Dashboard</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Kelola Visualisasi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title p-0 m-0">Daftar Visualisasi Interaktif Tableau</h5>
                        <a href="{{ route('visualisasi.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Visualisasi
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table datatable table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">#</th>
                                    <th scope="col">Judul Visualisasi</th>
                                    <th scope="col">URL Tableau</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col" class="text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach($visualisasis as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->title }}</div>
                                    </td>
                                    <td class="small">
                                        <a href="{{ $item->tableau_url }}" target="_blank" class="text-primary text-decoration-none" title="{{ $item->tableau_url }}">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> {{ Str::limit($item->tableau_url, 45) }}
                                        </a>
                                    </td>
                                    <td class="small text-muted">
                                        {!! Str::limit(strip_tags($item->content), 80) !!}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('visualisasi.edit', $item->id) }}" class="btn btn-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteVisModal{{ $item->id }}" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Modal Delete -->
                                        <div class="modal fade" id="deleteVisModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        Apakah Anda yakin ingin menghapus visualisasi <strong>"{{ $item->title }}"</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <a href="{{ route('visualisasi.delete', $item->id) }}" class="btn btn-danger">Hapus</a>
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

