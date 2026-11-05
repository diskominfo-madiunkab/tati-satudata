@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')

<div class="pagetitle">
    <h1>Kelola Buku Publikasi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/d_administrator">Dashboard</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Kelola Buku</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title p-0 m-0">Daftar Buku Publikasi</h5>
                        <a href="{{ route('publikasi.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Buku Publikasi
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table datatable table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 40px;">#</th>
                                    <th scope="col" style="width: 90px;">Sampul</th>
                                    <th scope="col">Judul Publikasi</th>
                                    <th scope="col">Perangkat Daerah</th>
                                    <th scope="col" class="text-center" style="width: 80px;">Tahun</th>
                                    <th scope="col" class="text-center" style="width: 110px;">Status</th>
                                    <th scope="col" style="width: 150px;">Jadwal Terbit</th>
                                    <th scope="col" class="text-center" style="width: 80px;">Berkas</th>
                                    <th scope="col" class="text-center" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach($publikasi as $pub)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-center">
                                        @if($pub->image)
                                            <img src="{{ Storage::url('public/blogs/').$pub->image }}" class="rounded shadow-sm" style="width: 60px; height: 80px; object-fit: cover;">
                                        @else
                                            <span class="badge bg-light text-muted border p-2"><i class="bi bi-file-earmark-text fs-5"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $pub->title }}</div>
                                        @if($pub->content)
                                            <div class="small text-muted">{!! Str::limit(strip_tags($pub->content), 80) !!}</div>
                                        @endif
                                    </td>
                                    <td class="small">
                                        {{ $pub->opd->nama_opd ?? $pub->instansi ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $pub->tahun ?: '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($pub->status == 'Terbit' || empty($pub->status))
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Terbit</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Belum Terbit</span>
                                        @endif
                                    </td>
                                    <td class="small">
                                        @if($pub->status == 'Belum Terbit')
                                            <div><span class="text-muted">Rencana:</span> <strong>{{ $pub->jadwal_rencana_terbit ?: '-' }}</strong></div>
                                        @else
                                            <div><strong>{{ $pub->jadwal_terbit ?: ($pub->created_at ? $pub->created_at->format('d/m/Y') : '-') }}</strong></div>
                                        @endif
                                        <div class="text-muted" style="font-size: 11px;">Frekuensi: {{ $pub->frekuensi ?: 'Tahunan' }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if($pub->pdf_path)
                                            <a href="{{ route('publication.download', $pub->id) }}" class="btn btn-sm btn-outline-danger" title="Unduh PDF">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('publikasi.edit', ['id' => $pub->id]) }}" class="btn btn-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePublikasiModal{{ $pub->id }}" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Modal Delete -->
                                        <div class="modal fade" id="deletePublikasiModal{{ $pub->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        Apakah Anda yakin ingin menghapus publikasi <strong>"{{ $pub->title }}"</strong>? Tindakan ini tidak dapat dibatalkan.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <a href="{{ route('publikasi.delete', ['id' => $pub->id]) }}" class="btn btn-danger">Hapus</a>
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
