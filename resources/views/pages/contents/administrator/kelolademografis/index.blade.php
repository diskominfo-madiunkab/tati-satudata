@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')

<div class="pagetitle">
    <h1>Daftar Demografis</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Demografis</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Demografis</h5>

                    <a href="{{route('data-demografis.create')}}" class="btn btn-md btn-success mb-3 float-right">Tambah
                        Demografis</a>

                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Icon</th>
                                <th scope="col">Narasi Data</th>
                                <th scope="col">Jumlah Data</th>
                                <th scope="col">Narasi 1</th>
                                <th scope="col">Jumlah Narasi 1</th>
                                <th scope="col">Narasi 2</th>
                                <th scope="col">Jumlah Narasi 2</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach($data as $dt)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $dt->icon}}</td>
                                <td>{{ $dt->narasi_data}}</td>
                                <td>{{ $dt->jml_data}}</td>
                                <td>{{ $dt->narasi_1}}</td>
                                <td>{{ $dt->jml_narasi_1}}</td>
                                <td>{{ $dt->narasi_2}}</td>
                                <td>{{ $dt->jml_narasi_2}}</td>
                                <td class="text-center">
                                    <div class="row">
                                        <div class="col">
                                            <a href="{{ route('data-demografis.edit', ['data_demografi' => encrypt($dt->id)]) }}"
                                                class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                        </div>
                                        <div class="col">
                                            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                                action="{{ route('data-demografis.destroy', ['data_demografi' => Crypt::encrypt($dt->id)]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->

                </div>
            </div>

        </div>
    </div>
</section>

@endsection