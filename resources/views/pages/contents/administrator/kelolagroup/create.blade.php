@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Tambah Group</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Group</li>
            <li class="breadcrumb-item active">Tambah Group</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Buat Group</h5>

                    <!-- General Form Elements -->
                    <form action="/group/store" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="name_group" class="col-sm-2 col-form-label">Nama Group</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name_group') is-invalid @enderror"
                                    name="name_group" value="{{ old('name_group') }}" placeholder="Masukkan Nama Group"
                                    required>

                                <!-- error message untuk title -->
                                @error('name_group')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="image_url" class="col-sm-2 col-form-label">Gambar</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control @error('image_url') is-invalid @enderror"
                                    name="image_url" required>

                                <!-- error message untuk title -->
                                @error('image_url')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi"
                                    rows="5" placeholder="Masukkan Deskripsi" required>{{ old('deskripsi') }}</textarea>

                                <!-- error message untuk deskripsi -->
                                @error('deskripsi')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
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
<script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'content' );
</script>
@endpush
@endsection