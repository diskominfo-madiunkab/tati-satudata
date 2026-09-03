@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')

<div class="pagetitle">
  <h1>Daftar Sumber Referensi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
      <li class="breadcrumb-item">Daftar Sumber Referensi</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Daftar Sumber Referensi</h5>

          <a href="/sumberdata/create" class="btn btn-md btn-success mb-3 float-right"><i class="bi bi-plus"></i>Tambah
            Sumber Referensi</a>

          <table class="table datatable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Sumber Referensi</th>
                <th scope="col">Keterangan</th>
                <th scope="col">Opsi</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              @foreach($data as $dt)
              <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $dt->sumber_data }}</td>
                @if($dt->is_active == '1')
                <td>
                  <span class="text-success"><b>Aktif</b>
                </td>
                @else
                <td>
                  <span class="text-secondary">Nonaktif</span>
                </td>
                @endif
                <td>
                  <form action="{{ url('/sumberdata/edit/'.$dt->id) }}">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></button>
                  </form>
                </td>
                @if ($dt->is_active == '1')
                <td>
                  <a href="/master_sumberdata_ubah/{{$dt->id}}" class="btn btn-warning" style="color: white">
                    <i class="glyphicon glyphicon-submit"></i>
                    Non-Aktifkan
                  </a>
                </td>
                @else
                <td>
                  <a href="/master_sumberdata_ubah/{{$dt->id}}" class="btn btn-success">
                    <i class="glyphicon glyphicon-submit"></i>
                    Aktifkan
                  </a>
                </td>
                @endif
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