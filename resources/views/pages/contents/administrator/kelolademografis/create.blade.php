@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Tambah Demografis</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Demografis</li>
            <li class="breadcrumb-item active">Tambah Demografis</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Buat Demografis</h5>
                    <p style="font-size: 12px"><span style="color:red">*</span> Wajib Terisi</p>

                    <!-- General Form Elements -->
                    <form action="{{route('data-demografis.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="icon" class="col-sm-2 col-form-label">Icon Demografis <span
                                    style="color:red">*</span></label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" name="icon"
                                    value="{{ old('icon') }}"
                                    placeholder='<i class="fa fa-globe" aria-hidden="true"></i>' required>

                                <!-- error message untuk title -->
                                @error('icon')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <p style="font-size: 12px">Lihat icon di sini <a href="https://fontawesome.com/v4/icons/"
                                    target="_blank">https://fontawesome.com/v4/icons/</a></p>
                        </div>

                        <div class="row mb-3">
                            <label for="narasi_data" class="col-sm-2 col-form-label">Narasi Data <span
                                    style="color:red">*</span></label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('narasi_data') is-invalid @enderror"
                                    name="narasi_data" value="{{ old('narasi_data') }}" placeholder="Narasi Data"
                                    required>

                                <!-- error message untuk title -->
                                @error('narasi_data')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jml_data" class="col-sm-2 col-form-label">Jumlah Data <span
                                    style="color:red">*</span></label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('jml_data') is-invalid @enderror"
                                    name="jml_data" value="{{ old('jml_data') }}" placeholder="Jumlah Narasi Data"
                                    required>

                                <!-- error message untuk title -->
                                @error('jml_data')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="narasi_1" class="col-sm-2 col-form-label">Narasi 1</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('narasi_1') is-invalid @enderror"
                                    name="narasi_1" value="{{ old('narasi_1') }}" placeholder="Narasi Data 1">

                                <!-- error message untuk title -->
                                @error('narasi_1')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jml_narasi_1" class="col-sm-2 col-form-label">Jumlah Narasi 1</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('jml_narasi_1') is-invalid @enderror"
                                    name="jml_narasi_1" value="{{ old('jml_narasi_1') }}"
                                    placeholder="Jumlah Narasi Data 1">

                                <!-- error message untuk title -->
                                @error('jml_narasi_1')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="narasi_2" class="col-sm-2 col-form-label">Narasi 2</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('narasi_2') is-invalid @enderror"
                                    name="narasi_2" value="{{ old('narasi_2') }}" placeholder="Narasi Data 2">

                                <!-- error message untuk title -->
                                @error('narasi_2')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jml_narasi_2" class="col-sm-2 col-form-label">Jumlah Narasi 2</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('jml_narasi_2') is-invalid @enderror"
                                    name="jml_narasi_2" value="{{ old('jml_narasi_2') }}"
                                    placeholder="Jumlah Narasi Data 2">

                                <!-- error message untuk title -->
                                @error('jml_narasi_2')
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

@endsection