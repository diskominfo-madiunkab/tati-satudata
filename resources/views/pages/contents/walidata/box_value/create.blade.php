@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')

<div class="pagetitle">
    <h1>Box Value</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Box Value</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Buat Box Value</h5>
                    <p style="font-size: 12px"><span style="color:red">*</span> Wajib Terisi</p>

                    <!-- General Form Elements -->
                    <form action="{{route('box-value.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="logo" class="col-sm-2 col-form-label">Logo <span
                                    style="color:red">*</span></label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('logo') is-invalid @enderror" name="logo"
                                    value="{{ old('logo') }}"
                                    placeholder='<i class="fa fa-globe" aria-hidden="true"></i>' required>

                                <!-- error message untuk title -->
                                @error('logo')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <p style="font-size: 12px">Lihat logo di sini <a href="https://fontawesome.com/v4/icons/"
                                    target="_blank">https://fontawesome.com/v4/icons/</a></p>
                        </div>

                        <div class="row mb-3">
                            <label for="judul" class="col-sm-2 col-form-label">Judul <span
                                    style="color:red">*</span></label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    name="judul" value="{{ old('judul') }}" placeholder="Judul" required>

                                <!-- error message untuk title -->
                                @error('judul')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ringkasan_nilai" class="col-sm-2 col-form-label">Ringkasan Nilai <span
                                    style="color:red">*</span></label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('ringkasan_nilai') is-invalid @enderror"
                                    name="ringkasan_nilai" value="{{ old('ringkasan_nilai') }}"
                                    placeholder="Ringkasan Nilai" required>

                                <!-- error message untuk title -->
                                @error('ringkasan_nilai')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('satuan') is-invalid @enderror"
                                    name="satuan" value="{{ old('satuan') }}" placeholder="Satuan">

                                <!-- error message untuk title -->
                                @error('satuan')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="data_id" class="col-sm-2 col-form-label">Data Terpublikasi</label>

                            <div class="col-sm-10">
                                <select name="data_id" id="data_id" class="form-select select2"
                                    aria-label="Default select example">
                                    <option value="">-- Pilih Data --</option>
                                    @foreach( $data as $op)
                                    <option value="{{ $op->id }}">{{ $op->nama_data }}</option>
                                    @endforeach
                                </select>
                                <!-- error message untuk title -->
                                @error('data_id')
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

@push('js')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script>
    $(document).ready(function () {
            $('.select2').select2();
        });
</script>
@endpush