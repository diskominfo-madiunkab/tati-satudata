@extends('pages.main.layout')

@section('title', 'Publikasi Data - Organisasi')

@section('content')
@include('sweetalert::alert')
<div class="pagetitle">
    <h1>Publikasi Data - Organisasi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Daftar Publikasi</li>
            <li class="breadcrumb-item">Data - {{$data->nama_data}}</li>
            <li class="breadcrumb-item">Organisasi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pilih Organisasi</h5>

                    <div class="mb-3">
                        @include('pages.contents.walidata.publikasi.tab-header')
                    </div>

                    <form method="POST" action="{{route('publikasi.organisasi.store', $data->id)}}">
                        @csrf
                        <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                            <div class="tab-pane fade active show" id="tab-org" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row mb-3">
                                    <label for="nama_data" class="col-sm-2 col-form-label">Nama Data</label>
                                    <div class="col-sm-10">
                                        <input id="nama_data" name="nama_data" type="text" class="form-control"
                                            placeholder="Nama Data" value="{{$data->nama_data}}" disabled>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="nama_opd" class="col-sm-2 col-form-label">Nama OPD</label>
                                    <div class="col-sm-10">
                                        <input id="nama_opd" name="nama_opd" type="text" class="form-control"
                                            placeholder="Nama Data" value="{{$data->opd->nama_opd}}" disabled>
                                    </div>
                                </div>

                                <hr>
                                <div class="row mt-2">
                                    <div class="col-sm-10">
                                        <h5>Data CKAN</h5>
                                        <p>Pilih/buat organisasi untuk CKAN.</p>
                                    </div>
                                    <div class="col-sm-2 justify-content-end">
                                        @if ($data->status_id != 9)

                                        <a class="btn btn-sm btn-outline-primary" data-bs-target="#createOrg"
                                            data-bs-toggle="modal"><i class="bi bi-plus"></i> Tambah Organisasi Baru</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="org_id" class="col-sm-2 col-form-label">Organisasi/OPD</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" id="org_id" name="org_id" required
                                            {{($data->status_id == 9) ? "disabled" : ""}}>

                                            <option value="-1" {{empty(optional($data->publikasi)->org_id) ? 'selected'
                                                : ''}}>- Pilih Data -</option>
                                            @foreach($orgs as $org)
                                            <option value="{{$org['id']}}" {{old('org_id', optional($data->
                                                publikasi)->org_id) == $org['id'] ? 'selected' :
                                                ''}}>{{$org['display_name']}}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Organisasi ini sebagai kepemilikian data yang akan
                                            dipublikasi</small>
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


<div class="modal fade" id="createOrg" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Organisasi di CKAN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('publikasi.organisasi.create')}}" id="formCreateOrg">
                    @csrf
                    <div class="row mb-3">
                        <label for="org_name" class="col-sm-2 col-form-label">Nama OPD <i
                                class="text-danger">*</i></label>
                        <div class="col-sm-10">
                            <input id="org_name" name="org_name" type="text" class="form-control"
                                placeholder="Nama Organisasi/OPD pada CKAN" value="{{$data->opd->nama_opd}}" required>
                            <small>URL pada CKAN (harus unik): /organizations/<span class="text-primary"
                                    id="org_name_slug"></span></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="org_desc" class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <textarea id="org_desc" name="org_desc" type="text" class="form-control"
                                placeholder="Deskripsi tentang OPD"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('formCreateOrg').submit()">Buat Organisasi <i
                        class="bi bi-send"></i></button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function slugify(string) {
            const a = 'àáâäæãåāăąçćčđďèéêëēėęěğǵḧîïíīįìıİłḿñńǹňôöòóœøōõőṕŕřßśšşșťțûüùúūǘůűųẃẍÿýžźż·/_,:;'
            const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiiiilmnnnnoooooooooprrsssssttuuuuuuuuuwxyyzzz------'
            const p = new RegExp(a.split('').join('|'), 'g')

            return string.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(p, c => b.charAt(a.indexOf(c)))
                .replace(/&/g, '-and-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '')
        }

        $(function() {
            let $org = $('#org_id');
            $org.select2();

            $org.change(function() {
                if ($(this).val() == -1) {

                } else {

                }
            });

            $('#org_name').on('keyup', function() {
                $('#org_name_slug').text(slugify($(this).val()));
            }).trigger('keyup');
        });
</script>
@endpush