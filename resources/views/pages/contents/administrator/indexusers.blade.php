@extends('pages.main.layout')
@section('content')
    @include('sweetalert::alert')

    <div class="pagetitle">
        <h1>Daftar User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item">Daftar User</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar User</h5>

                        <a href="{{ url('/user/create') }}" class="btn btn-md btn-success mb-3 float-right">Tambah User</a>

                        <a href="" class="btn btn-md btn-warning mb-3 float-right" data-bs-toggle="modal"
                            data-bs-target="#basicModal">Import Excel</a>
                        <!-- Table with stripped rows -->
                        <div class="modal fade" id="basicModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Import Excel</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Anda dapat meng-<em>import</em> data user dari file Excel menggunakan <a
                                                href="{{ url('/up-download', 7) }}" class="text-primary">template data <i
                                                    class="bi bi-download"></i></a>.</p>
                                        <form action="/user/import" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group mb-3">
                                                <input type="file" name="file" class="form-control"
                                                    placeholder="Recipient's username" aria-label="Recipient's username"
                                                    aria-describedby="button-addon2">
                                                <button class="btn btn-primary" type="submit"
                                                    id="button-addon2">Import</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">OPD</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($data as $dt)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dt->name }}</td>
                                        <td>{{ $dt->username }}</td>
                                        <td>{{ $dt->opd ? $dt->opd->nama_opd : '-' }}</td>
                                        <td>{{ $dt->role->name }}</td>
                                        <td>{{ $dt->email }}</td>
                                        <td>
                                            <div class="form-group" style="margin-bottom: 0;">
                                                {{-- <a href="{{ url('/user/edit', $dt->id) }}" class="btn btn-primary"><i
                        class="bi bi-pencil-fill"></i></a> --}}
                                                <form action="{{ url('/user/edit', $dt->id) }}">

                                                    <button type="submit" class="btn btn-sm btn-primary"><i
                                                            class="bi bi-pencil-fill"></i></button>
                                                </form>
                                                <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                                    action="{{ url('user/destroy', $dt->id) }}">

                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                        {{-- <td>
                  <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="" method="POST">

                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-info">Aksi</button>
                  </form>
                </td> --}}
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
