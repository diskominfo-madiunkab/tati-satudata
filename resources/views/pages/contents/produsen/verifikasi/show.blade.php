@extends('pages.main.layout')

@section('content')

    <div class="pagetitle">
        <h1>Verifikasi Berkas - {{$data->nama_data}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Daftar Verifikasi Data</li>
                <li class="breadcrumb-item active">{{$data->nama_data}}</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab"
                                        data-bs-target="#home-justified" type="button" role="tab" aria-controls="home"
                                        aria-selected="true">Berkas Data
                                </button>
                            </li>
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#profile-justified" type="button" role="tab"
                                        aria-controls="profile" aria-selected="false">Metadata
                                </button>
                            </li>
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#contact-justified" type="button" role="tab"
                                        aria-controls="contact" aria-selected="false">Contact
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content pt-2">
                            <div class="tab-pane">
                                <div class="card-title">Berkas Data</div>
                                <div class="table-responsive">
                                    <table class="table table-stripped datatable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Tgl. Unggah</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($existingBerkas as $berkas)
                                            @php
                                                $v = $data->verifikasi->firstWhere('field', $berkas['id']);
                                            @endphp
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td><a href="{{$berkas['previewUrl']}}" target="_new">{{$berkas['name'] ?? '-'}} <i class="bi bi-link"></i></a></td>
                                                <td>{{$berkas['created_at'] ? $berkas['created_at']->format('d/m/Y H:i') : '-'}}</td>
                                                <td>
                                                    <div class="btn-group-sm">
                                                        <button class="btn btn-actions btn-accept btn-sm {{$v && $v->accepted ? 'btn-success' : 'btn-outline-success'}}" data-name="{{$berkas['id']}}">Setuju <i class="bi bi-check"></i></button>
                                                        <button class="btn btn-actions btn-reject btn-sm {{$v && !$v->accepted ? 'btn-danger' : 'btn-outline-danger'}}" data-name="{{$berkas['id']}}">Revisi <i class="bi bi-x"></i></button>
                                                        <button class="btn btn-comment btn-sm btn-outline-primary" data-name="{{$berkas['id']}}"><i class="bi bi-chat-dots"></i> Komentar</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
        $(function() {
            $('button.btn-actions').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{route('verifikasi.verify', $data->id)}}',
                    method: 'PATCH',
                    data: {
                        category: 'berkas',
                        accepted: $(this).hasClass('btn-accept'),
                        field: $(this).data('name')
                    }
                })
                    .then((r) => Toast.fire({icon: r.ok ? 'success' : 'error', title: r.message}))
                    .catch(() => Toast.fire({icon: 'error', title: 'Gagal menyimpan perubahan'}));
            });

            $('button.btn-comment').on('click', function (e) {
                e.preventDefault();
                Swal.showLoading();
                let fieldName = $(this).data('name');
                $.get('{{route('verifikasi.get-komentar', $data->id)}}', {field: fieldName, category: 'berkas'})
                    .then(function(r) {
                        if (Swal.isLoading()) Swal.hideLoading();
                        Swal.fire({
                            title: 'Komentar untuk field ini',
                            input: 'textarea',
                            inputValue: r.comment ?? '-',
                            inputAttributes: {
                                autocapitalize: 'off',
                                spellCheck: false,
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Simpan',
                            showLoaderOnConfirm: true,
                            preConfirm: (comment) => {
                                return $.post('{{route('verifikasi.komentar', $data->id)}}', {field: fieldName, comment: comment, category: 'berkas'})
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error(response.message)
                                        }
                                        return response;
                                    })
                                    .catch(error => {
                                        Swal.showValidationMessage(
                                            `Request gagal: ${error}`
                                        )
                                    })
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Toast.fire({icon: result.value.ok ? 'success' : 'error', title: result.value.message});
                            }
                        });
                    });
            })
        });
    </script>
@endpush
