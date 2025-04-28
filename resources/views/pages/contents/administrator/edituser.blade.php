@extends('pages.main.layout')
@section('css')
    <style>
        .password-strength-group .password-strength-meter {
            width: 100%;
            transition: height 0.3s;
            display: flex;
            justify-content: stretch;
        }

        .password-strength-group .password-strength-meter .meter-block {
            height: 4px;
            background: #ccc;
            margin-right: 6px;
            flex-grow: 1;
        }

        .password-strength-group .password-strength-meter .meter-block:last-child {
            margin: 0;
        }

        .password-strength-group .password-strength-message {
            font-weight: 20px;
            height: 1em;
            text-align: right;
            transition: all 0.5s;
            margin-top: 3px;
            position: relative;
        }

        .password-strength-group .password-strength-message .message-item {
            font-size: 12px;
            position: absolute;
            right: 0;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .password-strength-group[data-strength="1"] .meter-block:nth-child(-n+1) {
            background: #cc3d04;
        }

        .password-strength-group[data-strength="1"] .message-item:nth-child(1) {
            opacity: 1;
        }

        .password-strength-group[data-strength="2"] .meter-block:nth-child(-n+2) {
            background: #ffc43b;
        }

        .password-strength-group[data-strength="2"] .message-item:nth-child(2) {
            opacity: 1;
        }

        .password-strength-group[data-strength="3"] .meter-block:nth-child(-n+3) {
            background: #9ea60a;
        }

        .password-strength-group[data-strength="3"] .message-item:nth-child(3) {
            opacity: 1;
        }

        .password-strength-group[data-strength="4"] .meter-block:nth-child(-n+4) {
            background: #289116;
        }

        .password-strength-group[data-strength="4"] .message-item:nth-child(4) {
            opacity: 1;
        }
    </style>
@endsection
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
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile"
                                    role="tab" tabindex="-1">Profil</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#change-password"
                                    role="tab">Password</button>
                            </li>
                        </ul>

                        <div class="tab-content p-2">
                            <div class="tab-pane active" id="profile">
                                <form action="{{ url('/user/update', $byid->id) }}" method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Nama</label>
                                        <div class="col-sm-10">
                                            <input id="nama" name="nama" type="text" class="form-control"
                                                value="{{ $byid->name }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label"> Username</label>
                                        <div class="col-sm-10">
                                            <input id="username" name="username" type="text" class="form-control"
                                                value="{{ $byid->username }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input id="email" name="email" type="text" class="form-control"
                                                value="{{ $byid->email }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Role</label>
                                        <div class="col-sm-10">
                                            <select id="role_id" name="role_id" class="form-select" aria-label="Role"
                                                value="{{ $byid->role->role_id }}">
                                                @foreach ($roles as $dt)
                                                    <option value="{{ $dt->id }}"
                                                        {{ $byid->role_id == $dt->id ? 'selected' : '' }}>
                                                        {{ ucfirst($dt->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">OPD</label>
                                        <div class="col-sm-10">
                                            <select id="opd_id" name="opd_id" class="form-select" aria-label="OPD"
                                                value="{{ $byid->opd_id }}">
                                                <option selected value="{{ $byid->opd_id }}">{{ $byid->opd?->nama_opd }}
                                                </option>
                                                @foreach ($opd as $dt)
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
                                <form method="POST" action="{{ route('admin.change-user-password', $byid->id) }}">
                                    @csrf
                                    <div class="row mb-3">
                                        <label id="label-password" class="col-sm-2 col-form-label">Kata Sandi</label>
                                        <div class="col-sm-10">
                                            <input class="form-control mb-2 @error('password') is-invalid @enderror"
                                                type="password" name="password" placeholder="Kata Sandi" id="password">
                                            <div class="invalid-feedback" id="pwError"></div>
                                            <div class="form-container">
                                                <div class="password-strength-group" data-strength="">
                                                    <div id="password-strength-meter" class="password-strength-meter">
                                                        <div class="meter-block"></div>
                                                        <div class="meter-block"></div>
                                                        <div class="meter-block"></div>
                                                        <div class="meter-block"></div>
                                                    </div>
                                                    <div class="password-strength-message">
                                                        <div class="message-item">
                                                            Kata Sandi Lemah <small style="font-size: 12px;"
                                                                id="alert-password-strength-1"></small>
                                                        </div>
                                                        <div class="message-item">
                                                            Kata Sandi Sedang <small style="font-size: 12px;"
                                                                id="alert-password-strength-2"></small>
                                                        </div>
                                                        <div class="message-item">
                                                            Kata Sandi Kuat <small style="font-size: 12px;"
                                                                id="alert-password-strength-3"></small>
                                                        </div>
                                                        <div class="message-item">
                                                            Kata Sandi Sangat Kuat <small style="font-size: 12px;"
                                                                id="alert-password-strength-4"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label id="label-konfirmasi-password" class="col-sm-2 col-form-label">Konfirmasi
                                            Kata
                                            Sandi</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="password" name="password_confirmation"
                                                placeholder="Konfirmasi Kata Sandi" id="password_confirmation">
                                            <div class="invalid-feedback" id="pwcError"></div>
                                            <small id="alert-password-confirm"></small>
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

@push('js')
    <script>
        function passwordCheck(password) {
            if (password.length >= 8)
                strength += 1;
            if (password.match(/(?=.[!,%,&,@,#,$,^,,?,_,~,<,>,])/))
                strength += 1;
            if (password.match(/(?=.*[a-z])/))
                strength += 1;
            if (password.match(/(?=.*[A-Z])/))
                strength += 1;

            displayBar(strength);

            if (password.length >= 8) {
                $('#alert-password-strength-' + strength).html('(' + password.length + '/8 Karakter)');
                $('#alert-password-strength-' + strength).css('color', 'green');
            } else {
                $('#alert-password-strength-' + strength).html('(' + password.length + '/8 Karakter)');
                $('#alert-password-strength-' + strength).css('color', 'red');
            }
        }

        function displayBar(strength) {
            $(".password-strength-group").attr('data-strength', strength);
        }

        $("#password").keyup(function() {
            strength = 0;
            var password = $(this).val();

            passwordCheck(password);
        });

        $("#password_confirmation").keyup(function() {
            var password = $('#password').val();
            var password_confirmation = $(this).val();

            if (password_confirmation == password) {
                $('#alert-password-confirm').html('Kata Sandi dan Konfirmasi Kata Sandi Sesuai');
                $('#alert-password-confirm').css('color', 'green');
            } else {
                $('#alert-password-confirm').html('Kata Sandi dan Konfirmasi Kata Sandi Belum Sesuai');
                $('#alert-password-confirm').css('color', 'red');
            }
        });

        function ubahPassword() {
            var x = document.getElementById("password");
            var y = document.getElementById("password_confirmation");
            if (x.type === "password") {
                x.type = "text";
                y.type = "text";
            } else {
                x.type = "password";
                y.type = "password";
            }
        }

        function passwordCheck(password) {
            if (password.length >= 8)
                strength += 1;
            if (password.match(/(?=.[!,%,&,@,#,$,^,,?,_,~,<,>,])/))
                strength += 1;
            if (password.match(/(?=.*[a-z])/))
                strength += 1;
            if (password.match(/(?=.*[A-Z])/))
                strength += 1;

            displayBar(strength);

            if (password.length >= 8) {
                $('#alert-password-strength-' + strength).html('(' + password.length + '/8 Karakter)');
                $('#alert-password-strength-' + strength).css('color', 'green');
            } else {
                $('#alert-password-strength-' + strength).html('(' + password.length + '/8 Karakter)');
                $('#alert-password-strength-' + strength).css('color', 'red');
            }
        }

        function displayBar(strength) {
            $(".password-strength-group").attr('data-strength', strength);
        }

        $("#password").keyup(function() {
            strength = 0;
            var password = $(this).val();

            passwordCheck(password);
        });

        $("#password_confirmation").keyup(function() {
            var password = $('#password').val();
            var password_confirmation = $(this).val();

            if (password_confirmation == password) {
                $('#alert-password-confirm').html('Kata Sandi dan Konfirmasi Kata Sandi Sesuai');
                $('#alert-password-confirm').css('color', 'green');
            } else {
                $('#alert-password-confirm').html('Kata Sandi dan Konfirmasi Kata Sandi Belum Sesuai');
                $('#alert-password-confirm').css('color', 'red');
            }
        });

        function ubahPassword() {
            var x = document.getElementById("password");
            var y = document.getElementById("password_confirmation");
            if (x.type === "password") {
                x.type = "text";
                y.type = "text";
            } else {
                x.type = "password";
                y.type = "password";
            }
        }
    </script>
@endpush
