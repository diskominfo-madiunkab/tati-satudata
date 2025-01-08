@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')

<div class="pagetitle">
    <h1>Daftar Tag</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Tag</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Tag</h5>

                    <a href="/tag/create" class="btn btn-md btn-success mb-3 float-right">Tambah Tag</a>

                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tag</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach($result as $dt)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $dt['display_name']}}</td>
                                <td class="">
                                    <div class="row">
                                        <div class="col">
                                            <a href="{{ route('tag.edit', ['id' => $dt['id']]) }}"
                                                class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                        </div>
                                        <div class="col">
                                            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                                action="{{ route('tag.delete', ['id' => $dt['id']]) }}">
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i
                                                        class="bi bi-trash"></i></button>
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