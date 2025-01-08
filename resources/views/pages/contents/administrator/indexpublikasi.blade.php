@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')

<div class="pagetitle">
    <h1>Daftar Publikasi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Publikasi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Publikasi</h5>

                    <a href="{{route('publikasi.create')}}" class="btn btn-md btn-success mb-3 float-right">Tambah
                        Publikasi</a>
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Gambar</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Konten</th>
                                <th scope="col">Berkas</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach($publikasi as $pub)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td class="text-center">
                                    <img src="{{ Storage::url('public/blogs/').$pub->image }}" class="rounded"
                                        style="width: 150px">
                                </td>
                                <td>{{ $pub->title }}</td>
                                <td>{!! $pub->content !!}</td>
                                <td><a href="{{ route('publication.download', $pub->id) }}">Unduh</a></td>
                                <td class="text-center">
                                    <a href="{{ route('publikasi.edit', ['id' => $pub->id]) }}"
                                        class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>

                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"
                                            data-bs-toggle="modal"
                                            data-bs-target="#basicModal{{$pub->id}}"></i></button>
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

@include('pages.contents.administrator.deletepublikasi')

@endsection