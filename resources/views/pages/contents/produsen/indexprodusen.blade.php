@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Daftar Data</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item">Daftar Data</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Daftar Data</h5>
            
            <a 
            @if(Auth::user()->role_id == '3')
            href="/data_administrator/create"
            @elseif(Auth::user()->role_id == '3')
            href="/data_walidata/create"
            @elseif(Auth::user()->role_id == '3')
            href="/data_produsen/create"
            @endif
            class="btn btn-md btn-success mb-3 float-right">Tambah
                Data</a>

                <a href="" class="btn btn-md btn-success mb-3 float-right" data-bs-toggle="modal" data-bs-target="#basicModal">Import Excel</a>
                <!-- Table with stripped rows -->
                  <div class="modal fade" id="basicModal" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Import Excel</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/data_produsen/import" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="file" name="file" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
                                    <button class="btn btn-primary" type="submit" id="button-addon2">Import</button>
                                </div>
                            </form>                    
                        </div>
                      </div>
                    </div>
                  </div>
            <!-- Table with stripped rows -->
            <table class="table datatable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nama Data</th>
                  <th scope="col">Produsen (PIC)</th>
                  <th scope="col">Jenis</th>
                  <th scope="col">Sumber</th>
                  <th scope="col">Opsi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; ?>
                  @foreach($data as $dt)
                <tr>
                  <td>{{ $no++ }}</td>
                  <td>{{ $dt->nama_data }}</td>
                  <td>{{ $dt->opd_id }}</td>
                  <td>{{ $dt->jenis_data }}</td>
                  <td>{{ $dt->sumber_data }}</td>
                  <td><form onsubmit="return confirm('Apakah Anda Yakin ?');" action="" method="POST">
                                
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-info">Aksi</button>
                </form></td>
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