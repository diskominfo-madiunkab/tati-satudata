@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Tambah Publikasi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Publikasi</li>
            <li class="breadcrumb-item active">Tambah Publikasi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Buat Publikasi</h5>

                    <!-- General Form Elements -->
                    <form action="{{ route('publikasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="image" class="col-sm-2 col-form-label">Gambar</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    name="image" required>

                                <!-- error message untuk title -->
                                @error('image')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="title" class="col-sm-2 col-form-label">Judul</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    name="title" value="{{ old('title') }}" placeholder="Masukkan Judul Publikasi"
                                    required>

                                <!-- error message untuk title -->
                                @error('title')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="content" class="col-sm-2 col-form-label">Konten</label>
                            <div class="col-sm-10">
                                {{-- <textarea
                                    class="form-control form-control-sm @error('content') is-invalid @enderror"
                                    name="content" rows="10" placeholder="Masukkan Konten Publikasi"
                                    required>{{ old('content') }}</textarea> --}}
                                <textarea
                                    class="tinymce-editor form-control form-control-sm @error('content') is-invalid @enderror"
                                    name="content" rows="10" placeholder="Masukkan Konten Publikasi"
                                    required>{{ old('content') }}></textarea>
                                <!-- End TinyMCE Editor -->

                                <!-- error message untuk content -->
                                @error('content')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="pdf" class="col-sm-2 col-form-label">File PDF</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control @error('pdf') is-invalid @enderror" name="pdf"
                                    required>
                                <span class="badge border-danger border-1 text-danger">Maksimal ukuran file pdf adalah
                                    10mb</span>

                                <!-- error message untuk title -->
                                {{-- @error('pdf')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror --}}
                                <!-- error message untuk ukuran file PDF -->
                                @error('pdf')
                                @if($message == 'Ukuran file PDF tidak boleh melebihi 10MB.')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @endif
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">SIMPAN</button>
                            </div>
                        </div>

                    </form><!-- End General Form Elements -->

                </div>
            </div>

        </div>


    </div>
</section>

@push('js')
{{-- <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'content' );
</script> --}}
{{-- <script src="{{ asset('js/ckeditor.js') }}"></script> --}}
@endpush
@endsection