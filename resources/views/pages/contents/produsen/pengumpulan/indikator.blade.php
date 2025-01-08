@extends('pages.main.layout')
@section('content')

    <div class="pagetitle">
        <h1>Metadata Indikator</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Daftar Pengumpulan</li>
                <li class="breadcrumb-item">{{$data->nama_data}}</li>
                <li class="breadcrumb-item">Metadata Indikator</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <a href="/data_produsen/pengumpulan/{{$data->id}}/tambah-indikator" class="btn btn-md btn-primary mb-3 float-right"><i class="bi bi-plus"></i> Indikator</a>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Metadata Indikator</h5>
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Indikator</th>
                                    <th scope="col">Konsep</th>
                                    <th scope="col">Definisi</th>
                                    <th scope="col">Interpretasi</th>
                                    <th scope="col">Metode/Rumus Perhitungan</th>
                                    <th scope="col">Ukuran</th>
                                    <th scope="col">Satuan</th>
                                    <th scope="col">Komposit?</th>
                                    <th scope="col">Level Estimasi</th>
                                    <th scope="col">Diakses oleh umum</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data->indikator as $meta)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$meta->nama}}</td>
                                        <td>{{$meta->konsep}}</td>
                                        <td>{{$meta->definisi}}</td>
                                        <td>{{$meta->interpretasi}}</td>
                                        <td>{{$meta->metode}}</td>
                                        <td>{{$meta->ukuran}}</td>
                                        <td>{{$meta->satuan}}</td>
                                        <td>{{$meta->komposit ? 'Ya' : 'Tidak'}}</td>
                                        <td>{{$meta->level_estimasi}}</td>
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
