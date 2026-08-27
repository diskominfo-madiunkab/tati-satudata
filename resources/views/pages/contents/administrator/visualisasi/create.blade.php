@extends('pages.main.layout')

@section('content')
<div class="pagetitle">
    <h1>Tambah Visualisasi Tableau</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kelola-visualisasi.index') }}">Kelola Visualisasi</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('kelola-visualisasi.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Visualisasi <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Dashboard Capaian Kinerja Kabupaten Madiun" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">URL Embed Tableau Public <span class="text-danger">*</span></label>
                            <input type="url" name="tableau_url" class="form-control @error('tableau_url') is-invalid @enderror" value="{{ old('tableau_url') }}" placeholder="https://public.tableau.com/views/..." required>
                            <small class="text-muted">Masukkan URL embed Tableau dari opsi 'Share' di Tableau Public.</small>
                            @error('tableau_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi / Penjelasan Visualisasi</label>
                            <textarea name="content" class="form-control" rows="5" placeholder="Penjelasan mengenai visualisasi interaktif ini...">{{ old('content') }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Visualisasi</button>
                            <a href="{{ route('kelola-visualisasi.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
