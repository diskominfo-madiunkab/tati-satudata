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
                    <h5 class="card-title">List Box Value</h5>
                    <!-- Table with stripped rows -->

                    <a href="{{ route('box-value.create') }}" class="btn btn-md btn-primary mb-3 float-right"
                        data-bs-placement="bottom" title="Tambah Data" style="width: 200px"><i
                            class="bi bi-plus-circle"></i> Tambah Data</a>

                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Ringkasan Nilai</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">Logo</th>
                                <th scope="col">Data</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach($data as $dt)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $dt->judul }}</td>
                                <td>{{ $dt->ringkasan_nilai }}</td>
                                <td>{{ $dt->satuan }}</td>
                                <td>{{ $dt->logo }}</td>
                                <td>{{ $dt->data->nama_data }}</td>
                                <td style="width: 150px">
                                    <div class="row">
                                        <div class="col">
                                            <a href="{{ route('box-value.edit', ['box_value' =>Crypt::encrypt($dt->id)]) }}"
                                                class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                        </div>
                                        <div class="col">
                                            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                                action="{{ route('box-value.destroy', ['box_value' => Crypt::encrypt($dt->id)]) }}"
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