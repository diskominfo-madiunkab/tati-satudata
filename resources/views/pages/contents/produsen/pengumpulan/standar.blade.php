@extends('pages.main.layout')

@section('title', 'Standar Data - ' . $data->nama_data)
@section('content')
@include('sweetalert::alert')
<div class="pagetitle">
    <h1>Standar Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/data_{{auth()->user()->role->name}}/pengumpulan">Daftar Pengumpulan
                    Data</a></li>
            <li class="breadcrumb-item">Data - {{$data->nama_data}}</li>
            <li class="breadcrumb-item active">Standar Data</li>
        </ol>
    </nav>
</div><!-- End Page Title -->
@php
$v = optional($data->verifikasi);
$variables = ['konsep', 'klasifikasi', 'definisi', 'ukuran', 'satuan', 'kode'];
foreach ($variables as $var) {
$$var = $v->firstWhere('field', $var);
}
// dd($definisi);
@endphp

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Standar Data: <em>{{$data->nama_data}}</em></h5>

                    <form method="POST">
                        @csrf
                        {{-- @php
                        dd($kode);
                        @endphp --}}
                        @if($existingData > 0)

                        <div class="row mb-3">
                            <label for="kode" class="col-sm-2 col-form-label">Kode Standar Data</label>
                            <div class="col-sm-10">
                                <textarea id="kode" name="kode" required
                                    class="form-control {{ isset($kode) ? ($kode->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                    placeholder="Kode Standar Data">@if(isset($getdata) && isset($getdata->standar)){{ old('kode', optional($getdata->standar)->kode) }}@else{{ old('kode') }}@endif</textarea>
                                @if (isset($kode) && !empty($kode->comment))
                                <p class="text-muted text-comment">Komentar: {{$kode->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                            <div class="col-sm-10">
                                <textarea id="konsep" name="konsep" required
                                    class="form-control {{ isset($konsep) ? ($konsep->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                    placeholder="Konsep Standar Data">@if(isset($getdata) && isset($getdata->standar)){{ old('konsep', optional($getdata->standar)->konsep) }}@else{{ old('konsep') }}@endif</textarea>
                                @if (isset($konsep) && !empty($konsep->comment))
                                <p class="text-muted text-comment">Komentar: {{$konsep->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="definisi" class="col-sm-2 col-form-label">Definisi</label>
                            <div class="col-sm-10">
                                <textarea id="definisi" name="definisi" required
                                    class="form-control {{ isset($definisi) ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                    placeholder="Definisi Standar Data">@if(isset($getdata) && isset($getdata->standar)){{ old('definisi', optional($getdata->standar)->definisi) }}@else{{ old('definisi') }}@endif</textarea>
                                @if (isset($definisi) && !empty($definisi->comment))
                                <p class="text-muted text-comment">Komentar: {{$definisi->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="klasifikasi" class="col-sm-2 col-form-label">Klasifikasi</label>
                            <div class="col-sm-10">
                                <textarea id="klasifikasi" name="klasifikasi" required
                                    class="form-control {{ isset($klasifikasi) ? ($klasifikasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                    placeholder="Klasifikasi Standar Data">@if(isset($getdata) && isset($getdata->standar)){{ old('klasifikasi', optional($getdata->standar)->klasifikasi) }}@else{{ old('klasifikasi') }}@endif</textarea>
                                @if (isset($klasifikasi) && !empty($klasifikasi->comment))
                                <p class="text-muted text-comment">Komentar: {{$klasifikasi->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                            <div class="col-sm-10">
                                <textarea id="ukuran" name="ukuran" required
                                    class="form-control {{ isset($ukuran) ? ($ukuran->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                    placeholder="Ukuran Standar Data">@if(isset($getdata) && isset($getdata->standar)){{ old('ukuran', optional($getdata->standar)->ukuran) }}@else{{ old('ukuran') }}@endif</textarea>
                                @if (isset($ukuran) && !empty($ukuran->comment))
                                <p class="text-muted text-comment">Komentar: {{$ukuran->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                            <div class="col-sm-10">
                                <textarea id="satuan" name="satuan" required
                                    class="form-control {{ isset($satuan) ? ($satuan->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                    placeholder="Satuan Standar Data">@if(isset($getdata) && isset($getdata->standar)){{ old('satuan', optional($getdata->standar)->satuan) }}@else{{ old('satuan') }}@endif</textarea>
                                @if (isset($satuan) && !empty($satuan->comment))
                                <p class="text-muted text-comment">Komentar: {{$satuan->comment}}</p>
                                @endif
                            </div>
                        </div>

                        @else

                        <div class="row mb-3">
                            <label for="kode" class="col-sm-2 col-form-label">Kode Standar Data</label>
                            <div class="col-sm-10">
                                <textarea id="kode" name="kode" class="form-control"
                                    placeholder="Kode Referensi Standar Data">{{old('kode', optional($data->standar)->kode)}}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                            <div class="col-sm-10">
                                <textarea id="konsep" name="konsep" class="form-control"
                                    placeholder="Konsep Standar Data">{{old('konsep', optional($data->standar)->konsep)}}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="definisi" class="col-sm-2 col-form-label">Definisi</label>
                            <div class="col-sm-10">
                                <textarea id="definisi" name="definisi" class="form-control"
                                    placeholder="Definisi Standar Data">{{old('definisi', optional($data->standar)->definisi)}}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="klasifikasi" class="col-sm-2 col-form-label">Klasifikasi</label>
                            <div class="col-sm-10">
                                <textarea id="klasifikasi" name="klasifikasi" class="form-control"
                                    placeholder="Klasifikasi Standar Data">{{old('klasifikasi', optional($data->standar)->klasifikasi)}}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                            <div class="col-sm-10">
                                <textarea id="ukuran" name="ukuran" class="form-control"
                                    placeholder="Ukuran Standar Data">{{old('ukuran', optional($data->standar)->ukuran)}}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                            <div class="col-sm-10">
                                <textarea id="satuan" name="satuan" class="form-control"
                                    placeholder="Satuan Standar Data">{{old('satuan', optional($data->standar)->satuan)}}</textarea>
                            </div>
                        </div>

                        @endif

                        @if(auth()->user()->hasAnyRole('produsen'))
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">KIRIM</button>
                            </div>
                        </div>
                        @endif

                        <a href="{{auth()->user()->hasAnyRole('produsen') ? '/data_produsen/standar-data' : '/data_walidata/standar-data'}}"
                            class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>

                    </form>
                </div>
            </div>

        </div>


    </div>
</section>
@endsection