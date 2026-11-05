@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Edit Infografis</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/infografis">Master Data</a></li>
            <li class="breadcrumb-item"><a href="{{ route('infografis.index') }}">Kelola Infografis</a></li>
            <li class="breadcrumb-item active">Edit Infografis</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title">Form Edit Infografis</h5>

                    <form action="{{ route('infografis.update', ['id' => $infografis->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="title" class="col-sm-3 col-form-label fw-bold">Judul Infografis <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $infografis->title) }}" placeholder="Masukkan Judul Infografis" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="image" class="col-sm-3 col-form-label fw-bold">Gambar Utama (Sampul)</label>
                            <div class="col-sm-9">
                                @if($infografis->image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url('public/blogs/' . $infografis->image) }}" class="rounded shadow-sm" style="max-height: 120px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*">
                                <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah cover utama.</div>
                                @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Existing Multi-Images list -->
                        @if($infografis->images && $infografis->images->count() > 0)
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">Slide Infografis Saat Ini</label>
                            <div class="col-sm-9">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($infografis->images as $img)
                                        <div class="position-relative border rounded p-1 text-center bg-light" style="width: 110px;">
                                            <img src="{{ Storage::url($img->image) }}" class="rounded img-fluid" style="height: 90px; object-fit: cover;">
                                            <div class="mt-1">
                                                <a href="{{ route('infografis.image.delete', $img->id) }}" class="btn btn-xs btn-outline-danger w-100 py-0" onclick="return confirm('Hapus slide ini?')" style="font-size: 11px;">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <label for="images" class="col-sm-3 col-form-label fw-bold">Tambah Slide Baru (Multi-Image)</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                    id="images" name="images[]" multiple accept="image/*">
                                <div class="form-text text-muted">Pilih satu atau lebih file gambar untuk ditambahkan ke slide Instagram carousel.</div>
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
                                    placeholder="https://public.tableau.com/views/...">{{ old('tableau', $infografis->tableau) }}</textarea>
                                <div class="form-text text-muted">Boleh dikosongkan jika infografis hanya gambar statis / carousel.</div>
                                @error('tableau')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="content" class="col-sm-3 col-form-label fw-bold">Deskripsi & Pembahasan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="tinymce-editor form-control @error('content') is-invalid @enderror"
                                    id="content" name="content" rows="6" placeholder="Masukkan Narasi dan Pembahasan Infografis">{{ old('content', $infografis->content) }}</textarea>
                                @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3 mt-4">
                            <div class="col-sm-9 offset-sm-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> SIMPAN PERUBAHAN</button>
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
