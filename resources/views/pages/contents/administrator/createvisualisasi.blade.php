@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Tambah Visualisasi Tableau</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/d_administrator">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('visualisasi.index') }}">Kelola Visualisasi</a></li>
            <li class="breadcrumb-item active">Tambah Visualisasi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title">Form Tambah Visualisasi Tableau</h5>

                    <form action="{{ route('visualisasi.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <label for="title" class="col-sm-3 col-form-label fw-bold">Judul Visualisasi <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" placeholder="Masukkan Judul Visualisasi" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tableau_url" class="col-sm-3 col-form-label fw-bold">URL Tableau <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea name="tableau_url" id="tableau_url" rows="3"
                                    class="form-control @error('tableau_url') is-invalid @enderror"
                                    placeholder="https://public.tableau.com/views/..." required>{{ old('tableau_url') }}</textarea>
                                <div class="form-text text-muted">Tempelkan link share publik Tableau (mis: https://public.tableau.com/views/...).</div>
                                @error('tableau_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="content" class="col-sm-3 col-form-label fw-bold">Konten / Ringkasan</label>
                            <div class="col-sm-9">
                                <textarea class="tinymce-editor form-control @error('content') is-invalid @enderror"
                                    id="content" name="content" rows="6" placeholder="Masukkan narasi atau deskripsi visualisasi">{{ old('content') }}</textarea>
                                @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3 mt-4">
                            <div class="col-sm-9 offset-sm-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> SIMPAN</button>
                                <a href="{{ route('visualisasi.index') }}" class="btn btn-secondary px-3">BATAL</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection

