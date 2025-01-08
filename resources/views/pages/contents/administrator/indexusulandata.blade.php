@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')

<div class="pagetitle">
  <h1>Usulan Data</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
      <li class="breadcrumb-item">Usulan Data</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">List Usulan Data</h5>
          <!-- Table with stripped rows -->

          <table class="table datatable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
                <th scope="col">Pekerjaan</th>
                <th scope="col">Jenis Kelamin</th>
                <th scope="col">Nomor WA</th>
                <th scope="col">Tahun</th>
                <th scope="col">Usulan Data</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              @foreach($data as $dt)
              <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $dt->nama }}</td>
                <td>{{ $dt->email }}</td>
                <td>{{ $dt->pekerjaan }}</td>
                <td>{{ $dt->kelamin }}</td>
                <td>{{ $dt->no_wa }}</td>
                <td>{{ $dt->tahun }}</td>
                <td>{{ $dt->usulan }}</td>
                <td style="width: 150px">
                  <div class="row">
                    {{-- <div class="col">
                      <a href="{{ route('box-value.edit', ['box_value' =>Crypt::encrypt($dt->id)]) }}"
                        class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                    </div> --}}
                    <div class="col">
                      <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                        action="{{ route('usulan-data.destroy', ['usulan_datum' => Crypt::encrypt($dt->id)]) }}"
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