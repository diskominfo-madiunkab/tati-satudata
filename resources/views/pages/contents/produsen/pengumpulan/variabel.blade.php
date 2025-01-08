@extends('pages.main.layout')
@section('content')

    <div class="pagetitle">
        <h1>Metadata Variabel</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Daftar Pengumpulan</li>
                <li class="breadcrumb-item">{{$data->nama_data}}</li>
                <li class="breadcrumb-item">Daftar Metadata Variabel</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <a href="/data_produsen/pengumpulan/{{$data->id}}/tambah-variabel" class="btn btn-md btn-primary mb-3 float-right"><i class="bi bi-plus"></i> Variabel</a>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Metadata Variabel</h5>
                        <div class="table-responsive">
                            <table class="table table">
                                <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Variabel</th>
                                    <th scope="col">Alias</th>
                                    <th scope="col">Tipe Data</th>
                                    <th scope="col">Definisi</th>
                                    <th scope="col">Konsep</th>
                                    <th scope="col">Referensi Pemilihan</th>
                                    <th scope="col">Referensi Waktu</th>
                                    <th scope="col">Diakses umum?</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data->variabel as $meta)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$meta->nama}}</td>
                                        <td>{{$meta->alias}}</td>
                                        <td>{{ucfirst($meta->tipe_data)}}</td>
                                        <td>{{$meta->definisi}}</td>
                                        <td>{{$meta->konsep}}</td>
                                        <td>{{$meta->referensi_pemilihan}}</td>
                                        <td>{{$meta->referensi_waktu}}</td>
                                        <td>{{$meta->umum ? 'Ya' : 'Tidak'}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
