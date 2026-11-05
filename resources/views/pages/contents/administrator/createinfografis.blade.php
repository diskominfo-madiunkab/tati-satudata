@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Tambah Infografis</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/infografis">Master Data</a></li>
            <li class="breadcrumb-item"><a href="{{ route('infografis.index') }}">Kelola Infografis</a></li>
            <li class="breadcrumb-item active">Tambah Infografis</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title">Form Tambah Infografis (Format Carousel / Multi-Gambar)</h5>

                    <form action="{{ route('infografis.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="title" class="col-sm-3 col-form-label fw-bold">Judul Infografis <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" placeholder="Masukkan Judul Infografis" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="image" class="col-sm-3 col-form-label fw-bold">Gambar Utama (Sampul)</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*">
                                <div class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 5MB. Gambar ini tampil sebagai thumbnail di halaman depan.</div>
                                @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="images" class="col-sm-3 col-form-label fw-bold">
                                Upload Multi-Gambar (Slide Carousel)
                            </label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                    id="images" name="images[]" multiple accept="image/*">
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Sesuai konsep format Instagram: Anda dapat memilih beberapa gambar sekaligus (tekan Ctrl/Cmd saat memilih file) agar pengunjung dapat menggeser (swipe) slide infografis.
                                </div>
                                @error('images.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tableau" class="col-sm-3 col-form-label fw-bold">URL Tableau (Opsional)</label>
                            <div class="col-sm-9">
                                <textarea name="tableau" id="tableau" rows="2"
                                    class="form-control @error('tableau') is-invalid @enderror"
                                    placeholder="https://public.tableau.com/views/...">{{ old('tableau') }}</textarea>
                                <div class="form-text text-muted">Boleh dikosongkan jika infografis hanya berbasis gambar / poster.</div>
                                @error('tableau')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="content" class="col-sm-3 col-form-label fw-bold">Deskripsi & Pembahasan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="tinymce-editor form-control @error('content') is-invalid @enderror"
                                    id="content" name="content" rows="6" placeholder="Masukkan Narasi dan Pembahasan Infografis">{{ old('content') }}</textarea>
                                @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3 mt-4">
                            <div class="col-sm-9 offset-sm-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> SIMPAN</button>
                                <a href="{{ route('infografis.index') }}" class="btn btn-secondary px-3">BATAL</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection
