@extends('pages.main.layout')

@section('content')
<div class="pagetitle">
    <h1>Edit Regulasi Satu Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kelola-regulasi.index') }}">Kelola Regulasi</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('kelola-regulasi.update', $regulasi->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Peraturan / Regulasi <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $regulasi->judul) }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor Peraturan</label>
                                <input type="text" name="nomor" class="form-control" value="{{ old('nomor', $regulasi->nomor) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kategori Regulasi <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-select" required>
                                    @php
                                        $kats = ['Peraturan Bupati', 'Peraturan Daerah', 'Keputusan Bupati', 'Peraturan Presiden', 'Undang-Undang', 'Pedoman Teknis'];
                                    @endphp
                                    @foreach($kats as $k)
                                        <option value="{{ $k }}" {{ old('kategori', $regulasi->kategori) == $k ? 'selected' : '' }}>{{ $k }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tahun <span class="text-danger">*</span></label>
                                <select name="tahun" class="form-select" required>
                                    @foreach($tahuns as $th)
                                        <option value="{{ $th->tahun }}" {{ old('tahun', $regulasi->tahun) == $th->tahun ? 'selected' : '' }}>{{ $th->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status Regulasi</label>
                                <select name="status" class="form-select">
                                    <option value="Berlaku" {{ old('status', $regulasi->status) == 'Berlaku' ? 'selected' : '' }}>Berlaku</option>
                                    <option value="Tidak Berlaku" {{ old('status', $regulasi->status) == 'Tidak Berlaku' ? 'selected' : '' }}>Tidak Berlaku</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tentang / Keterangan Singkat</label>
                            <textarea name="tentang" class="form-control" rows="3">{{ old('tentang', $regulasi->tentang) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Upload Dokumen PDF Baru</label>
                            @if($regulasi->file_dokumen)
                                <div class="mb-2">
                                    <span class="badge bg-light text-dark border"><i class="bi bi-file-pdf text-danger me-1"></i> File saat ini: {{ basename($regulasi->file_dokumen) }}</span>
                                </div>
                            @endif
                            <input type="file" name="file_dokumen" class="form-control @error('file_dokumen') is-invalid @enderror" accept=".pdf">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah file PDF.</small>
                            @error('file_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Perbarui Regulasi</button>
                            <a href="{{ route('kelola-regulasi.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
