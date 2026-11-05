@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Edit Dokumen Regulasi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/d_administrator">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('regulasi.index') }}">Kelola Regulasi</a></li>
            <li class="breadcrumb-item active">Edit Regulasi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title">Form Edit Regulasi</h5>

                    <form action="{{ route('regulasi.update', $regulasi->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="judul" class="col-sm-3 col-form-label fw-bold">Judul Peraturan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    id="judul" name="judul" value="{{ old('judul', $regulasi->judul) }}" placeholder="Contoh: Peraturan Bupati Madiun Nomor 9 Tahun 2024" required>
                                @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nomor" class="col-sm-3 col-form-label fw-bold">Nomor Peraturan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('nomor') is-invalid @enderror"
                                    id="nomor" name="nomor" value="{{ old('nomor', $regulasi->nomor) }}" placeholder="Contoh: Nomor 9 Tahun 2024">
                                @error('nomor')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tahun" class="col-sm-3 col-form-label fw-bold">Tahun Penetapan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                    id="tahun" name="tahun" value="{{ old('tahun', $regulasi->tahun ?: date('Y')) }}" placeholder="2024">
                                @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="kategori" class="col-sm-3 col-form-label fw-bold">Kategori Regulasi <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                                    @foreach(['Peraturan Presiden', 'Peraturan Menteri', 'Peraturan Gubernur', 'Peraturan Bupati', 'Keputusan Bupati', 'Surat Edaran', 'SOP', 'Petunjuk Teknis', 'Lainnya'] as $kat)
                                        <option value="{{ $kat }}" {{ old('kategori', $regulasi->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                    @endforeach
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
                                    <option value="Berlaku" {{ old('status', $regulasi->status ?: 'Berlaku') == 'Berlaku' ? 'selected' : '' }}>Berlaku</option>
                                    <option value="Tidak Berlaku" {{ old('status', $regulasi->status) == 'Tidak Berlaku' ? 'selected' : '' }}>Tidak Berlaku / Dicabut</option>
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
                                    id="tentang" name="tentang" rows="4" placeholder="Masukkan ringkasan pokok materi regulasi">{{ old('tentang', $regulasi->tentang) }}</textarea>
                                @error('tentang')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="file_dokumen" class="col-sm-3 col-form-label fw-bold">Berkas Dokumen PDF</label>
                            <div class="col-sm-9">
                                @if($regulasi->file_dokumen)
                                    <div class="mb-2">
                                        <a href="{{ Storage::url($regulasi->file_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-file-earmark-pdf me-1"></i> Unduh File PDF Saat Ini
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('file_dokumen') is-invalid @enderror"
                                    id="file_dokumen" name="file_dokumen" accept=".pdf">
                                <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah berkas PDF. Maksimal 15MB.</div>
                                @error('file_dokumen')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3 mt-4">
                            <div class="col-sm-9 offset-sm-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> SIMPAN PERUBAHAN</button>
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

