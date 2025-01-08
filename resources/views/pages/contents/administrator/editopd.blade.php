@extends('pages.main.layout')
@section('content')

    <div class="pagetitle">
        <h1>Edit OPD</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item">Daftar OPD</li>
                <li class="breadcrumb-item active">Edit OPD</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Data</h5>

                        <!-- General Form Elements -->
                        <form
                            action="{{ url('/opd/update/'.$data->id) }}"
                            method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nama OPD</label>
                                <div class="col-sm-10">
                                    <input id="nama_opd" name="nama_opd" type="text" class="form-control"
                                           value="{{ $data->nama_opd }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama_penjabat" class="col-sm-2 col-form-label">Nama Penjabat</label>
                                <div class="col-sm-10">
                                    <input type="text" id="nama_penjabat" class="form-control" name="nama_penjabat" value="{{ old('nama_penjabat', $data->nama_penjabat) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nip_penjabat" class="col-sm-2 col-form-label">NIP Penjabat</label>
                                <div class="col-sm-10">
                                    <input type="number" id="nip_penjabat" class="form-control" name="nip_penjabat" value="{{ old('nip_penjabat', $data->nip_penjabat) }}" minlength="18">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="pangkat_penjabat" class="col-sm-2 col-form-label">Pangkat Penjabat</label>
                                <div class="col-sm-10">
                                    <input type="text" id="pangkat_penjabat" class="form-control" name="pangkat_penjabat" value="{{ old('pangkat_penjabat', $data->pangkat_penjabat) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="jabatan_penjabat" class="col-sm-2 col-form-label">Jabatan Penjabat</label>
                                <div class="col-sm-10">
                                    <input type="text" id="jabatan_penjabat" class="form-control" name="jabatan_penjabat" value="{{ old('jabatan_penjabat', $data->jabatan_penjabat) }}">
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
