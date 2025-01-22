@extends('pages.main.layout')
@section('content')
    <div class="pagetitle">
        <h1>Upload Download</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item">Daftar File</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar File</h5>


                        <a href="" class="btn btn-md btn-warning mb-3 float-right" data-bs-toggle="modal"
                            data-bs-target="#basicModal">Upload Excel</a>
                        <!-- Table with stripped rows -->
                        <div class="modal fade" id="basicModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Upload Excel</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="/upload-proses" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group mb-3">
                                                <input type="file" name="document" class="form-control"
                                                    aria-label="Recipient's username" aria-describedby="button-addon2"
                                                    required>

                                            </div>
                                            <div class="form-group">
                                                <b>Type</b>
                                                <select id="type" name="type" class="form-select"
                                                    aria-label="Default select example" required>
                                                    <option value="" disabled selected>Pilih</option>
                                                    <option value="DATA">TEMPLATE DATA</option>
                                                    <option value="OPD">TEMPLATE OPD</option>
                                                    <option value="USER">TEMPLATE USER</option>
                                                    <option value="INDIKATOR">TEMPLATE INDIKATOR</option>
                                                    <option value="VARIABEL">TEMPLATE VARIABEL</option>
                                                    <option value="PANDUAN">PANDUAN</option>
                                                    <option value="TEMPLATE BERKAS DATA">TEMPLATE BERKAS DATA</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <b>Keterangan</b>
                                                <textarea class="form-control" name="keterangan" required></textarea>
                                            </div>
                                            <br>
                                            <button class="btn btn-primary float-left" type="submit"
                                                id="button-addon2">Import</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Document</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">Tipe</th>
                                    <th scope="col">Opsi</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($document as $dt)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dt->document }}</td>
                                        <td>{{ $dt->keterangan }}</td>
                                        <td>{{ $dt->type }}</td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td>
                                                        <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                                            action="{{ url('/upload-hapus', $dt->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"><i
                                                                    class="bi bi-trash"></i></button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                                            action="{{ url('/download', $dt->id) }}">

                                                            <button type="submit" class="btn btn-sm btn-info"><i
                                                                    class="bi bi-download"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>


                                            </table>
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
