@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Tambah Dokumen Regulasi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/d_administrator">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('regulasi.index') }}">Kelola Regulasi</a></li>
            <li class="breadcrumb-item active">Tambah Regulasi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title">Form Tambah Regulasi</h5>

                    <form action="{{ route('regulasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="judul" class="col-sm-3 col-form-label fw-bold">Judul Peraturan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    id="judul" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Peraturan Bupati Madiun Nomor 9 Tahun 2024" required>
                                @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nomor" class="col-sm-3 col-form-label fw-bold">Nomor Peraturan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('nomor') is-invalid @enderror"
                                    id="nomor" name="nomor" value="{{ old('nomor') }}" placeholder="Contoh: Nomor 9 Tahun 2024">
                                @error('nomor')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tahun" class="col-sm-3 col-form-label fw-bold">Tahun Penetapan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                    id="tahun" name="tahun" value="{{ old('tahun', date('Y')) }}" placeholder="2024">
                                @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="kategori" class="col-sm-3 col-form-label fw-bold">Kategori Regulasi <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                                    <option value="Peraturan Presiden" {{ old('kategori') == 'Peraturan Presiden' ? 'selected' : '' }}>Peraturan Presiden</option>
                                    <option value="Peraturan Menteri" {{ old('kategori') == 'Peraturan Menteri' ? 'selected' : '' }}>Peraturan Menteri</option>
                                    <option value="Peraturan Gubernur" {{ old('kategori') == 'Peraturan Gubernur' ? 'selected' : '' }}>Peraturan Gubernur</option>
                                    <option value="Peraturan Bupati" {{ old('kategori', 'Peraturan Bupati') == 'Peraturan Bupati' ? 'selected' : '' }}>Peraturan Bupati</option>
                                    <option value="Keputusan Bupati" {{ old('kategori') == 'Keputusan Bupati' ? 'selected' : '' }}>Keputusan Bupati</option>
                                    <option value="Surat Edaran" {{ old('kategori') == 'Surat Edaran' ? 'selected' : '' }}>Surat Edaran</option>
                                    <option value="SOP" {{ old('kategori') == 'SOP' ? 'selected' : '' }}>SOP (Standar Operasional Prosedur)</option>
                                    <option value="Petunjuk Teknis" {{ old('kategori') == 'Petunjuk Teknis' ? 'selected' : '' }}>Petunjuk Teknis</option>
                                    <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="status" class="col-sm-3 col-form-label fw-bold">Status Keberlakuan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="Berlaku" {{ old('status', 'Berlaku') == 'Berlaku' ? 'selected' : '' }}>Berlaku</option>
                                    <option value="Tidak Berlaku" {{ old('status') == 'Tidak Berlaku' ? 'selected' : '' }}>Tidak Berlaku / Dicabut</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tentang" class="col-sm-3 col-form-label fw-bold">Tentang / Ringkasan</label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('tentang') is-invalid @enderror"
                                    id="tentang" name="tentang" rows="4" placeholder="Masukkan ringkasan pokok materi regulasi">{{ old('tentang') }}</textarea>
                                @error('tentang')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="file_dokumen" class="col-sm-3 col-form-label fw-bold">Berkas Dokumen PDF</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control @error('file_dokumen') is-invalid @enderror"
                                    id="file_dokumen" name="file_dokumen" accept=".pdf">
                                <div class="form-text text-muted">Format file: PDF. Maksimal 15MB.</div>
                                @error('file_dokumen')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3 mt-4">
                            <div class="col-sm-9 offset-sm-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> SIMPAN</button>
                                <a href="{{ route('regulasi.index') }}" class="btn btn-secondary px-3">BATAL</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection

