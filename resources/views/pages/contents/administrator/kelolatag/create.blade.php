@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Tambah Group</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Tag</li>
            <li class="breadcrumb-item active">Tambah Tag</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Buat Tag</h5>

                    <!-- General Form Elements -->
                    <form action="/tag/store" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="display_name" class="col-sm-2 col-form-label">Nama Tag</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('display_name') is-invalid @enderror"
                                    name="display_name" value="{{ old('display_name') }}"
                                    placeholder="Masukkan Nama Tag" required>

                                <!-- error message untuk title -->
                                @error('display_name')
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