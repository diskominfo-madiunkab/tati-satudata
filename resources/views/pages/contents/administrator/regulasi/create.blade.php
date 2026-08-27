@extends('pages.main.layout')

@section('content')
<div class="pagetitle">
    <h1>Tambah Regulasi Satu Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kelola-regulasi.index') }}">Kelola Regulasi</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('kelola-regulasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Peraturan / Regulasi <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Contoh: Peraturan Bupati Madiun tentang Satu Data" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor Peraturan</label>
                                <input type="text" name="nomor" class="form-control" value="{{ old('nomor') }}" placeholder="Contoh: Nomor 9 Tahun 2024">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kategori Regulasi <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-select" required>
                                    <option value="Peraturan Bupati" {{ old('kategori') == 'Peraturan Bupati' ? 'selected' : '' }}>Peraturan Bupati</option>
                                    <option value="Peraturan Daerah" {{ old('kategori') == 'Peraturan Daerah' ? 'selected' : '' }}>Peraturan Daerah</option>
                                    <option value="Keputusan Bupati" {{ old('kategori') == 'Keputusan Bupati' ? 'selected' : '' }}>Keputusan Bupati</option>
                                    <option value="Peraturan Presiden" {{ old('kategori') == 'Peraturan Presiden' ? 'selected' : '' }}>Peraturan Presiden</option>
                                    <option value="Undang-Undang" {{ old('kategori') == 'Undang-Undang' ? 'selected' : '' }}>Undang-Undang</option>
                                    <option value="Pedoman Teknis" {{ old('kategori') == 'Pedoman Teknis' ? 'selected' : '' }}>Pedoman Teknis</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tahun <span class="text-danger">*</span></label>
                                <select name="tahun" class="form-select" required>
                                    @foreach($tahuns as $th)
                                        <option value="{{ $th->tahun }}" {{ old('tahun') == $th->tahun ? 'selected' : '' }}>{{ $th->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status Regulasi</label>
                                <select name="status" class="form-select">
                                    <option value="Berlaku" {{ old('status') == 'Berlaku' ? 'selected' : '' }}>Berlaku</option>
                                    <option value="Tidak Berlaku" {{ old('status') == 'Tidak Berlaku' ? 'selected' : '' }}>Tidak Berlaku</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tentang / Keterangan Singkat</label>
                            <textarea name="tentang" class="form-control" rows="3" placeholder="Tentang materi yang diatur...">{{ old('tentang') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Upload Dokumen PDF</label>
                            <input type="file" name="file_dokumen" class="form-control @error('file_dokumen') is-invalid @enderror" accept=".pdf">
                            <small class="text-muted">Maksimal 15 MB format PDF.</small>
                            @error('file_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Regulasi</button>
                            <a href="{{ route('kelola-regulasi.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
