@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Tambah User</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item">Daftar User</li>
        <li class="breadcrumb-item active">Tambah User</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Tambah User</h5>

            <!-- General Form Elements -->
            <form
                action="{{ url('/user/store') }}"

                method="POST">
                @csrf
              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">Nama</label>
                <div class="col-sm-10">
                  <input id="nama" name="nama" type="text" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                  <input id="username" name="username" type="text" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input id="email" name="email" type="text" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Role</label>
                <div class="col-sm-10">
                  <select id="role_id" name="role_id" class="form-select" aria-label="Default select example">
                    <option value="" disabled selected hidden>Role</option>
                    @foreach($role as $dt)
                    <option value="{{ $dt->id }}">{{ ucfirst($dt->name) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">OPD</label>
                <div class="col-sm-10">
                  <select id="opd_id" name="opd_id" class="form-select" aria-label="Default select example">
                    <option value="" disabled selected hidden>OPD</option>
                    @foreach($opd as $dt)
                    <option value="{{ $dt->id }}">{{ $dt->nama_opd }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
              </div>

            </form><!-- End General Form Elements -->

          </div>
        </div>

      </div>


    </div>
  </section>
  @endsection
