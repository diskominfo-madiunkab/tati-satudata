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
</style>
@endpush

@section('title', 'Publikasi Data - Organisasi')

@section('content')
@include('sweetalert::alert')
<div class="pagetitle">
    <h1>Publikasi Data - Dataset</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Daftar Publikasi</li>
            <li class="breadcrumb-item">Data - {{$data->nama_data}}</li>
            <li class="breadcrumb-item">Dataset</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Dataset</h5>

                    <div class="mb-3">
                        @include('pages.contents.walidata.publikasi.tab-header')
                    </div>

                    <form method="POST" action="{{route('publikasi.dataset.store', $data->id)}}">
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
                                            {{($data->status_id == 9) ? "disabled" : ""}}
                                        placeholder="Judul Dataset"
                                        value="{{old('title', optional($data->publikasi)->title ?? $data->nama_data)}}">
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Deskripsi <i
                                            class="bi bi-markdown"></i></label>
                                    <div class="col-sm-10">
                                        <div class="" id="description_editor" spellcheck="false"></div>
                                        <textarea class="d-none" name="description"
                                            id="description">{{old('description', optional($data->publikasi)->description)}}</textarea>
                                    </div>
                                </div>

                                <br>
                                <br>

                                <div class="row mb-3">
                                    <label for="visibility" class="col-sm-2 col-form-label">Visibilitas</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" name="visibility" id="visibility"
                                            {{($data->status_id == 9) ? "disabled" : ""}}>
                                            <option value="0" {{old('visibility', optional($data->
                                                publikasi)->visibility) == 0 ? 'selected' : ''}}>Private</option>
                                            <option value="1" {{old('visibility', optional($data->
                                                publikasi)->visibility) == 1 ? 'selected' : ''}}>Publik</option>
                                        </select>
                                    </div>
                                </div>
                                <h5>DATA GROUP CKAN</h5>
                                <div class="row mb-3">
                                    <label for="group_id" class="col-sm-2 col-form-label">Group</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" name="group_id" id="group_id" {{($data->status_id ==
                                            9) ? "disabled" : ""}}>
                                            @foreach($group['result'] as $gp)
                                            <option value="{{$gp['id']}}">{{$gp['display_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="tags" class="col-sm-2 col-form-label">Tags</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="tags" name="tags" class="form-control"
                                            data-role="tagsinput" placeholder="Tambahkan Tag">
                                        <small class="form-text text-muted">Pisahkan tag dengan koma atau tekan
                                            enter</small>
                                    </div>
                                </div>

                                @if($data->status_id == \App\Models\Data::STATUS_SIAP_PUBLIKASI)
                                <div class="row mb-3">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <button class="btn btn-outline-primary"><i class="bi bi-save"></i> Simpan &
                                            Lanjutkan</button>
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
           let readOnly = {{ $data->status_id == 9 ? 'true' : 'false' }};
        let description = new Quill('#description_editor', {
        readOnly: readOnly,
        theme: 'snow'
            });
            new QuillMarkdown(description);

            description.setText('{{old('description', optional($data->publikasi)->description)}}')

            description.on('text-change', function () {
                $('#description').val(description.getText());
            });
        })
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