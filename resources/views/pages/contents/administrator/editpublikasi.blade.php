@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Edit Buku Publikasi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/publikasi-admin">Master Data</a></li>
            <li class="breadcrumb-item"><a href="{{ route('publikasi-guest.index') }}">Kelola Buku</a></li>
            <li class="breadcrumb-item active">Edit Buku Publikasi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title">Form Edit Buku Publikasi</h5>

                    <form action="{{ route('publikasi.update', ['id' => $publikasi->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="title" class="col-sm-3 col-form-label fw-bold">Judul Publikasi <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $publikasi->title) }}" placeholder="Masukkan Judul Publikasi" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="opd_id" class="col-sm-3 col-form-label fw-bold">Perangkat Daerah (OPD)</label>
                            <div class="col-sm-9">
                                <select class="form-select @error('opd_id') is-invalid @enderror" id="opd_id" name="opd_id">
                                    <option value="">-- Pilih Perangkat Daerah (Opsional) --</option>
                                    @foreach($opds as $opd)
                                        <option value="{{ $opd->id }}" {{ old('opd_id', $publikasi->opd_id) == $opd->id ? 'selected' : '' }}>
                                            {{ $opd->nama_opd }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control mt-2 @error('instansi') is-invalid @enderror"
                                    id="instansi" name="instansi" value="{{ old('instansi', $publikasi->instansi) }}" placeholder="Atau tulis nama instansi jika tidak ada di daftar">
                                @error('opd_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tahun" class="col-sm-3 col-form-label fw-bold">Tahun Data</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                    id="tahun" name="tahun" value="{{ old('tahun', $publikasi->tahun ?: date('Y')) }}" placeholder="2026">
                                @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="status" class="col-sm-3 col-form-label fw-bold">Status Publikasi <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="Terbit" {{ old('status', $publikasi->status ?: 'Terbit') == 'Terbit' ? 'selected' : '' }}>Terbit (Sudah ada dokumen & terpublikasi)</option>
                                    <option value="Belum Terbit" {{ old('status', $publikasi->status) == 'Belum Terbit' ? 'selected' : '' }}>Belum Terbit (Jadwal Rencana Terbit)</option>
                                </select>
                                <div class="form-text text-muted">Buku dengan status <strong>Terbit</strong> akan tampil di tab <em>Daftar Buku Publikasi</em>. Buku dengan status <strong>Belum Terbit</strong> akan tampil di tab <em>Jadwal Rencana Terbit</em>.</div>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3" id="group_rencana_terbit">
                            <label for="jadwal_rencana_terbit" class="col-sm-3 col-form-label fw-bold">Jadwal Rencana Terbit</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('jadwal_rencana_terbit') is-invalid @enderror"
                                    id="jadwal_rencana_terbit" name="jadwal_rencana_terbit" value="{{ old('jadwal_rencana_terbit', $publikasi->jadwal_rencana_terbit) }}" placeholder="Contoh: Triwulan III 2026 atau 30 Agustus 2026">
                                @error('jadwal_rencana_terbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3" id="group_jadwal_terbit">
                            <label for="jadwal_terbit" class="col-sm-3 col-form-label fw-bold">Jadwal Terbit</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('jadwal_terbit') is-invalid @enderror"
                                    id="jadwal_terbit" name="jadwal_terbit" value="{{ old('jadwal_terbit', $publikasi->jadwal_terbit) }}" placeholder="Contoh: 15 Agustus 2026">
                                @error('jadwal_terbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="frekuensi" class="col-sm-3 col-form-label fw-bold">Frekuensi Terbit</label>
                            <div class="col-sm-9">
                                <select class="form-select @error('frekuensi') is-invalid @enderror" id="frekuensi" name="frekuensi">
                                    <option value="Tahunan" {{ old('frekuensi', $publikasi->frekuensi) == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                                    <option value="Semesteran" {{ old('frekuensi', $publikasi->frekuensi) == 'Semesteran' ? 'selected' : '' }}>Semesteran</option>
                                    <option value="Triwulanan" {{ old('frekuensi', $publikasi->frekuensi) == 'Triwulanan' ? 'selected' : '' }}>Triwulanan</option>
                                    <option value="Bulanan" {{ old('frekuensi', $publikasi->frekuensi) == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="Insidental" {{ old('frekuensi', $publikasi->frekuensi) == 'Insidental' ? 'selected' : '' }}>Insidental</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3" id="group_image">
                            <label for="image" class="col-sm-3 col-form-label fw-bold">Gambar Sampul</label>
                            <div class="col-sm-9">
                                @if($publikasi->image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url('public/blogs/' . $publikasi->image) }}" class="rounded shadow-sm" style="height: 120px; object-fit: cover;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*">
                                <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar sampul.</div>
                                @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3" id="group_pdf">
                            <label for="pdf" class="col-sm-3 col-form-label fw-bold">Berkas PDF</label>
                            <div class="col-sm-9">
                                @if($publikasi->pdf_path)
                                    <div class="mb-2">
                                        <a href="{{ route('publication.download', $publikasi->id) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                                            <i class="bi bi-file-earmark-pdf me-1"></i> Unduh File PDF Saat Ini
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('pdf') is-invalid @enderror"
                                    id="pdf" name="pdf" accept=".pdf">
                                <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah file PDF. Maksimal 10MB.</div>
                                @error('pdf')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="content" class="col-sm-3 col-form-label fw-bold">Konten / Ringkasan</label>
                            <div class="col-sm-9">
                                <textarea class="tinymce-editor form-control @error('content') is-invalid @enderror"
                                    id="content" name="content" rows="6" placeholder="Masukkan Ringkasan Isi Buku Publikasi">{{ old('content', $publikasi->content) }}</textarea>
                                @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3 mt-4">
                            <div class="col-sm-9 offset-sm-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> SIMPAN PERUBAHAN</button>
                                <a href="{{ route('publikasi-guest.index') }}" class="btn btn-secondary px-3">BATAL</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection
