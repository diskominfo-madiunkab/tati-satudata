@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Edit Sumber Referensi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Sumber Referensi</li>
            <li class="breadcrumb-item active">Edit Sumber Referensi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Referensi</h5>

                    <!-- General Form Elements -->
                    <form action="{{ url('/sumberdata/update/'.$data->id) }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Nama OPD</label>
                            <div class="col-sm-10">
                                <input id="sumber_data" name="sumber_data" type="text" class="form-control"
                                    value="{{ $data->sumber_data }}">
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