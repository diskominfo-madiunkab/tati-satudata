@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')

<div class="pagetitle">
    <h1>Edit User</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item">Daftar User</li>
        <li class="breadcrumb-item active">Edit User</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Edit User</h5>


              <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
                  <li class="nav-item" role="presentation">
                      <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile" role="tab" tabindex="-1">Profil</button>
                  </li>
                  <li class="nav-item" role="presentation">
                      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#change-password" role="tab">Password</button>
                  </li>
              </ul>

              <div class="tab-content p-2">
                  <div class="tab-pane active" id="profile">
                      <form action="{{ url('/user/update',$byid->id) }}" method="POST">
                          @csrf
                          <div class="row mb-3">
                              <label for="inputText" class="col-sm-2 col-form-label">Nama</label>
                              <div class="col-sm-10">
                                  <input id="nama" name="nama" type="text" class="form-control" value="{{$byid->name}}">
                              </div>
                          </div>
                          <div class="row mb-3">
                              <label for="inputText" class="col-sm-2 col-form-label"> Username</label>
                              <div class="col-sm-10">
                                  <input id="username" name="username" type="text" class="form-control" value="{{$byid->username}}">
                              </div>
                          </div>
                          <div class="row mb-3">
                              <label for="inputText" class="col-sm-2 col-form-label">Email</label>
                              <div class="col-sm-10">
                                  <input id="email" name="email" type="text" class="form-control"  value="{{ $byid->email }}">
                              </div>
                          </div>
                          <div class="row mb-3">
                              <label class="col-sm-2 col-form-label">Role</label>
                              <div class="col-sm-10">
                                  <select id="role_id" name="role_id" class="form-select" aria-label="Role"  value="{{ $byid->role->role_id }}">
                                      @foreach($roles as $dt)
                                          <option value="{{ $dt->id }}" {{$byid->role_id == $dt->id ? 'selected' : ''}}>{{ ucfirst($dt->name) }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>
                          <div class="row mb-3">
                              <label class="col-sm-2 col-form-label">OPD</label>
                              <div class="col-sm-10">
                                  <select id="opd_id" name="opd_id" class="form-select" aria-label="OPD" value="{{$byid->opd_id}}">
                                      <option  selected value="{{$byid->opd_id}}">{{$byid->opd->nama_opd}}</option>
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

                  <div class="tab-pane" id="change-password" role="tabpanel">
                      <h5 class="card-title">Ubah Kata Sandi</h5>
                      <form method="POST" action="{{route('admin.change-user-password', $byid->id)}}">
                          @csrf
                          <div class="row mb-3">
                              <label for="password" class="col-md-4 col-lg-3 col-form-label">Password Baru</label>
                              <div class="col-md-8 col-lg-9">
                                  <input name="password" type="password" class="form-control" minlength="5" id="password" required>
                                  <small class="help-text text-muted">Minimal 5 karakter.</small>
                              </div>
                          </div>
                          <div class="row mb-3">
                              <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">Konfirmasi Password Baru</label>
                              <div class="col-md-8 col-lg-9">
                                  <input name="password_confirmation" type="password" class="form-control" id="password_confirmation" required>
                              </div>
                          </div>
                          <div class="text-center">
                              <button type="submit" class="btn btn-primary">Ubah Password</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
        </div>

      </div>


    </div>
  </section>
@endsection
