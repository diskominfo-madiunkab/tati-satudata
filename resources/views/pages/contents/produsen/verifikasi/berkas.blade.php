@extends('pages.main.layout')

@section('content')

<div class="pagetitle">
    <h1>Data - {{$data->nama_data}}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">{{$data->nama_data}}</li>
            <li class="breadcrumb-item">Berkas</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <div class="card-title">Berkas Data</div>
                    <div class="table-responsive">
                        <table class="table table-stripped datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Tgl. Unggah</th>
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($existingBerkas as $berkas)
                                @php
                                $v = $data->verifikasi->firstWhere('field', $berkas['id']);
                                @endphp
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td><a href="{{$berkas['previewUrl']}}" target="_new">{{$berkas['name'] ?? '-'}} <i
                                                class="bi bi-link"></i></a></td>
                                    <td>{{$berkas['created_at'] ? $berkas['created_at']->format('d/m/Y H:i') : '-'}}
                                    </td>
                                    {{-- <td>
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-sm {{$v && $v->accepted ? 'btn-success' : 'btn-outline-success'}}"
                                                data-name="{{$berkas['id']}}">Setuju <i
                                                    class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-sm {{$v && !$v->accepted ? 'btn-danger' : 'btn-outline-danger'}}"
                                                data-name="{{$berkas['id']}}">Revisi <i class="bi bi-x"></i></button>
                                            <button class="btn btn-sm btn-comment btn-outline-primary"
                                                data-name="{{$berkas['id']}}"><i class="bi bi-chat-dots"></i>
                                                Komentar</button>
                                        </div>
                                    </td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
            $('button.btn-comment').on('click', function (e) {
                e.preventDefault();
                Swal.showLoading();
                let fieldName = $(this).data('name');
                $.get('{{route('produsen.verifikasi.get-komentar', $data->id)}}', {field: fieldName, category: 'berkas'})
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
                        })
                    });
            })
        });
</script>
@endpush