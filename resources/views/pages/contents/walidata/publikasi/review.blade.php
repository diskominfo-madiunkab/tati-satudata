@extends('pages.main.layout')
@push('styles')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
<style>
    .bootstrap-tagsinput {
        margin: 0;
        width: 100%;
        padding: 0.5rem 0.75rem 0;
        font-size: 1rem;
        line-height: 1.25;
        transition: border-color 0.15s ease-in-out;
    }

    .bootstrap-tagsinput .tag {
        display: inline-block;
        background-color: #636c72;
        padding: 0 .4em .15em;
        border-radius: .25rem;
        margin-bottom: 0.4em;
    }

    .bootstrap-tagsinput .tag [data-role="remove"]:after {
        content: '\00d7';
    }

    .bootstrap-tagsinput input {
        margin-bottom: 0.5em;
    }

    .bootstrap-tagsinput.has-focus {
        background-color: #fff;
        border-color: #5cb3fd;
    }

    /* .form-control[disabled] {
        background-color: #e9ecef;
        cursor: not-allowed;
    } */
</style>
@endpush
@section('title', 'Publikasi Data - Organisasi')

@section('content')
@include('sweetalert::alert')
<div class="pagetitle">
    <h1>Penyebarluasan Data - Review</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Daftar Publikasi</li>
            <li class="breadcrumb-item">Data - {{$data->nama_data}}</li>
            <li class="breadcrumb-item">Review</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Review</h5>

                    <div class="mb-3">
                        @include('pages.contents.walidata.publikasi.tab-header')
                    </div>

                    <form method="POST" action="{{route('publikasi.publish', $data->id)}}" id="formPublikasi">
                        @csrf
                        <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                            <div class="tab-pane fade active show" id="tab-org" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row mb-3">
                                    <label for="nama_data" class="col-sm-2 col-form-label">Nama Data</label>
                                    <div class="col-sm-10">
                                        <input id="nama_data" name="judul_kegiatan" type="text" class="form-control"
                                            placeholder="Nama Data" value="{{$data->nama_data}}" disabled>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="org_id" class="col-sm-2 col-form-label">Organisasi/OPD</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" id="org_id" name="org_id" disabled>
                                            <option value="-1" {{empty(optional($data->publikasi)->org_id) ? 'selected'
                                                : ''}}>- Pilih Data -</option>
                                            @foreach($orgs as $org)
                                            <option value="{{$org['id']}}" {{old('org_id', optional($data->
                                                publikasi)->org_id) == $org['id'] ? 'selected' :
                                                ''}}>{{$org['display_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="title" class="col-sm-2 col-form-label">Judul Dataset</label>
                                    <div class="col-sm-10">
                                        <input id="title" name="title" type="text" class="form-control"
                                            placeholder="Judul Dataset"
                                            value="{{old('title', optional($data->publikasi)->title ?? $data->nama_data)}}"
                                            readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Deskripsi <i
                                            class="bi bi-markdown"></i></label>
                                    <div class="col-sm-10">
                                        <div class="" id="description_editor" spellcheck="false"></div>
                                        <textarea class="d-none" name="description" id="description"
                                            disabled>{{old('description', optional($data->publikasi)->description)}}</textarea>
                                    </div>
                                </div>

                                <br>
                                <br>

                                <div class="row mb-3">
                                    <label for="visibility" class="col-sm-2 col-form-label">Visibilitas</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" name="visibility" id="visibility" disabled>
                                            <option value="0" {{old('visibility', optional($data->
                                                publikasi)->visibility) == 0 ? 'selected' : ''}}>Private</option>
                                            <option value="1" {{old('visibility', optional($data->
                                                publikasi)->visibility) == 1 ? 'selected' : ''}}>Publik</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="kodeindikator" class="col-sm-2 col-form-label">Kode Indikator <br>
                                        E-Walidata</label>
                                    <div class="col-sm-10">
                                        <input id="kodeindikator" name="kodeindikator" type="text" class="form-control"
                                            placeholder="Judul Dataset"
                                            value="{{old('kodeindikator', $data->kodeindikator ?? " ")}}" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="value_sipd" class="col-sm-2 col-form-label">Value Tahunan <br>
                                        E-Walidata</label>
                                    <div class="col-sm-10">
                                        <input id="value_sipd" name="value_sipd" type="text" class="form-control"
                                            placeholder="Value" value="{{old('value_sipd', $data->value_sipd ?? " ")}}"
                                            readonly>
                                    </div>
                                </div>
                                <h5>DATA GROUP CKAN</h5>
                                <div class="row mb-3">
                                    <label for="group_id" class="col-sm-2 col-form-label">Group</label>
                                    <div class="col-sm-10">
                                        <select disabled class="form-select" name="group_id" id="group_id"
                                            {{($data->status_id ==
                                            9) ? "disabled" : ""}}>
                                            @foreach($group['result'] as $gp)
                                            <option value="{{$gp['id']}}" {{old('group_id', optional($data->
                                                publikasi)->group_id) == $gp['id'] ? 'selected' :
                                                ''}}>{{$gp['display_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="tags" class="col-sm-2 col-form-label">Tags</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="tags" name="tags" class="form-control" disabled
                                            data-role="tagsinput" placeholder="Tambahkan Tag" value="{{old('tags', optional($data->
                                                publikasi)->tags)}}">
                                        {{-- <small class="form-text text-muted">Pisahkan tag dengan koma atau tekan
                                            enter</small> --}}
                                    </div>
                                </div>





                                <div class="row mb-3">
                                    <div class="col-sm-2">Berkas</div>
                                    <div class="col-sm-10">

                                        <table class="table table-stripped table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama Berkas</th>
                                                    <th>Ukuran</th>
                                                    <th>Diunggah pada</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data->berkas as $berkas)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>
                                                        <a href="{{route('filepreview', ['payload' => Crypt::encryptString($berkas->path)])}}"
                                                            download="" target="_new">{{$berkas['name'] ?? '-'}} <i
                                                                class="bi bi-link"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{Storage::exists($berkas->path) ?
                                                        \App\Models\Berkas::humanFileSize(Storage::size($berkas->path))
                                                        : 'Data Hilang'}}</td>
                                                    <td>{{$berkas['created_at'] ? $berkas['created_at']->format('d/m/Y
                                                        H:i') : '-'}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @if($data->status_id == \App\Models\Data::STATUS_SIAP_PUBLIKASI)
                                <div class="row mb-3">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <button class="btn btn-lg btn-outline-primary" id="btnConfirmation"><i
                                                class="bi bi-send-check"></i> Publikasi</button>
                                    </div>
                                </div>
                                @endif

                                <a href="{{ $data->status_id == 9 ? route('publikasi.terpublikasi') : route('publikasi.index') }}"
                                    class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('js')
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/quilljs-markdown@latest/dist/quilljs-markdown-common-style.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quilljs-markdown@latest/dist/quilljs-markdown.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script>
    $(function() {
            let description = new Quill('#description_editor', {
                readOnly: true,
                theme: 'snow'
            });
            new QuillMarkdown(description);

            description.setText('{{old('description', optional($data->publikasi)->description)}}')

            description.on('text-change', function () {
                $('#description').val(description.getText());
            });

            $('#btnConfirmation').on('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Konfirmasi publikasi ke CKAN?',
                    text: 'Data ini akan diunggah ke CKAN, untuk mengubah informasi yang sudah terunggah Anda harus mengubah melalui CKAN.',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    confirmButtonText: 'Publikasi',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#formPublikasi').submit();
                    }
                });
            });
        });
</script>
<script>
    $(document).ready(function() {
            $('#tags').tagsinput({
                trimValue: true,
                confirmKeys: [13, 44, 32],
                focusClass: 'my-focus-class'
            });

            $('.bootstrap-tagsinput input').on('focus', function() {
                $(this).closest('.bootstrap-tagsinput').addClass('has-focus');
            }).on('blur', function() {
                $(this).closest('.bootstrap-tagsinput').removeClass('has-focus');
            });
        });
</script>
@endpush