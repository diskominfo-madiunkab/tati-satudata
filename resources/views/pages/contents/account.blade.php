@extends('pages.main.layout')
@section('content')
    @include('sweetalert::alert')
    <div class="pagetitle">
        <h1>Profil</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Landing</a></li>
                <li class="breadcrumb-item active">Profil</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Profil</h5>

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
                                <h5 class="card-title">Informasi Akun</h5>
                                <div class="row">
                                    <p class="col-md-4 col-lg-3 label">Username</p>
                                    <div class="col-md-8 col-lg-9">
                                        <p>{{$user->username}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <p class="col-md-4 col-lg-3 label">Email</p>
                                    <div class="col-md-8 col-lg-9">
                                        <p>{{$user->email}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <p class="col-md-4 col-lg-3 label">OPD</p>
                                    <div class="col-md-8 col-lg-9">
                                        <p>{{$user->opd->nama_opd}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <p class="col-md-4 col-lg-3 label">Peran</p>
                                    <div class="col-md-8 col-lg-9">
                                        <p>{{ucwords(optional($user->role)->name)}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <p class="col-md-4 col-lg-3 col-form-label">Tgl. Daftar</p>
                                    <div class="col-md-8 col-lg-9">
                                        <p>{{$user->created_at->translatedFormat('d F Y H:i')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="change-password" role="tabpanel">
                                <h5 class="card-title">Ubah Kata Sandi</h5>
                                <form method="POST" action="{{route('account.change-password')}}">
                                    @csrf
                                    <div class="row mb-3">
                                        <label for="old_password" class="col-md-4 col-lg-3 col-form-label">Password Sekarang</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="old_password" type="password" class="form-control" id="old_password" required>
                                        </div>
                                    </div>
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
