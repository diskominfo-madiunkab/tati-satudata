@extends('pages.main.layout')

@php
    // dd($data->relationLoaded('verifikasi'));
    if ($data->relationLoaded('verifikasi')) {
        $v = optional($data->verifikasi);
        $variables = [
            'nama',
            'alias',
            'definisi',
            'interpretasi',
            'konsep',
            'metode',
            'ukuran',
            'satuan',
            'klasifikasi_penyajian',
            'komposit',
            'publikasi_indikator_pembangun',
            'nama_indikator_pembangun',
            'kegiatan_variabel_pembangun',
            'kode_kegiatan_variabel_pembangun',
            'nama_variabel_pembangun',
            'level_estimasi',
            'umum',
        ];
        foreach ($variables as $var) {
            $$var = $v->firstWhere('field', $var);
        }
    }
@endphp

@section('content')
    @include('sweetalert::alert')

    <div class="pagetitle">
        <h1>Metadata Indikator</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Daftar Pengumpulan Data</a></li>
                <li class="breadcrumb-item">{{ $data->nama_data }}</li>
                <li class="breadcrumb-item active">Metadata Indikator</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-content-center justify-content-between flex-wrap mb-3">
                            <h5 class="card-title">Metadata Indikator</h5>
                            <div class="align-self-center">
                                <button class="btn btn-xs btn-outline-success" data-bs-toggle="modal"
                                    data-bs-target="#modalImport">Import Metadata <i class="bi bi-file-excel"></i></button>
                            </div>
                        </div>

                        @php
                            // dd($getdata);
                        @endphp

                        <form action="{{ route('simpan-indikator', $data->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @if ($existingData > 0)
                                <div class="row mb-3">
                                    <label for="nama" class="col-sm-2 col-form-label">Nama Indikator</label>
                                    <div class="col-sm-10">
                                        <input id="nama" name="nama" type="text"
                                            class="form-control {{ isset($nama) ? ($nama->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            placeholder="Nama Indikator" value="{{ old('nama', $getdata->nama_data) }}"
                                            readonly>
                                        @if (isset($nama) && !empty($nama->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $nama->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                                    <div class="col-sm-10">
                                        <input id="konsep" name="konsep" type="text" readonly
                                            class="form-control {{ isset($konsep) ? ($konsep->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            placeholder="Konsep"
                                            value="{{ old('konsep', optional($data->standar)->konsep) }}">
                                        @if (isset($konsep) && !empty($konsep->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $konsep->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Definisi" class="col-sm-2 col-form-label">Definisi</label>
                                    <div class="col-sm-10">
                                        <textarea name="definisi" readonly
                                            class="form-control {{ isset($definisi) ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            style="height: 100px" spellcheck="false" placeholder="Definisi">{{ old('definisi', optional($data->standar)->definisi) }}</textarea>
                                        @if (isset($definisi) && !empty($definisi->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $definisi->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="interpretasi" class="col-sm-2 col-form-label">Interpretasi</label>
                                    <div class="col-sm-10">
                                        <textarea id="interpretasi" name="interpretasi" type="text"
                                            class="form-control {{ isset($interpretasi) ? ($interpretasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            style="height: 100px" spellcheck="false" placeholder="Interpretasi">{{ old('interpretasi', optional($getdata->indikator)->interpretasi) }}</textarea>
                                        @if (isset($interpretasi) && !empty($interpretasi->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $interpretasi->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                @php
                                    $isImage =
                                        optional($getdata->indikator)->metode &&
                                        Str::startsWith(optional($getdata->indikator)->metode, 'public/');
                                @endphp
                                <div class="row mb-3">
                                    <label for="metode" class="col-sm-2 col-form-label">Metode / Rumus Perhitungan</label>
                                    <div class="col-sm-6">
                                        <textarea name="metode" id="metode"
                                            class="form-control {{ isset($metode) ? ($metode->accepted ? 'is-valid' : 'is-invalid') : '' }}">{{ optional($getdata->indikator)->metode }}</textarea>
                                        @if (isset($metode) && !empty($metode->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $metode->comment }}</p>
                                            <hr>
                                        @endif
                                    </div>
                                    <div class="col-sm-auto">
                                        <p>atau</p>
                                    </div>
                                    <div class="col-auto flex-fill">
                                        @if ($isImage)
                                            <img class="img-fluid rounded"
                                                style="{{ isset($metode) ? ($metode->accepted ? 'border:5px solid green' : 'border:5px solid red') : '' }}"
                                                height="250px" width="250px"
                                                src="{{ Storage::url(optional($getdata->indikator)->metode) }}">
                                        @endif
                                        <input
                                            class="form-control {{ isset($metode) ? ($metode->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            name="metode_image" accept="image/*" type="file" id="metode_image">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                                    <div class="col-sm-10">
                                        <input id="ukuran" name="ukuran" type="text" readonly
                                            class="form-control {{ isset($ukuran) ? ($ukuran->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            placeholder="Ukuran"
                                            value="{{ old('ukuran', optional($data->standar)->ukuran) }}">
                                        @if (isset($ukuran) && !empty($ukuran->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $ukuran->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                                    <div class="col-sm-10">
                                        <input id="satuan" name="satuan" type="text" readonly
                                            class="form-control {{ isset($satuan) ? ($satuan->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            placeholder="Satuan"
                                            value="{{ old('satuan', optional($data->standar)->satuan) }}">
                                        @if (isset($satuan) && !empty($satuan->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $satuan->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="klasifikasi_penyajian" class="col-sm-2 col-form-label">Klasifikasi
                                        Penyajian</label>
                                    <div class="col-sm-10">
                                        <textarea name="klasifikasi_penyajian"
                                            class="form-control {{ isset($klasifikasi_penyajian) ? ($klasifikasi_penyajian->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            style="height: 100px" spellcheck="false" placeholder="Klasifikasi Penyajian">{{ old('klasifikasi_penyajian', $getdata->indikator?->klasifikasi_penyajian ?? optional($data->standar)->klasifikasi) }}</textarea>

                                        @if (isset($klasifikasi_penyajian) && !empty($klasifikasi_penyajian->comment))
                                            <p class="text-muted text-comment">Komentar:
                                                {{ $klasifikasi_penyajian->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="komposit" class="col-sm-2 col-form-label">Apakah indikator
                                        komposit?</label>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input {{ isset($komposit) ? ($komposit->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                type="radio" name="komposit" id="komposit1" value="1"
                                                {{ old('komposit', optional($getdata->indikator)->komposit) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gridRadios1"> Ya </label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input {{ isset($komposit) ? ($komposit->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                type="radio" name="komposit" id="komposit2" value="0"
                                                {{ old('komposit', optional($getdata->indikator)->komposit) == 0 ||
                                                empty(old('komposit', optional($getdata->indikator)->komposit))
                                                    ? 'checked'
                                                    : '' }}>
                                            <label class="form-check-label" for="gridRadios1"> Tidak </label>
                                        </div>
                                        @if (isset($komposit) && !empty($komposit->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $komposit->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <section class="komposit-section">
                                    <div class="row mb-3">
                                        <label for="publikasi_indikator_pembangun"
                                            class="col-sm-2 col-form-label">Publikasi
                                            Ketersediaan
                                            Indikator Pembangun</label>
                                        <div class="col-sm-10">
                                            <input id="publikasi_indikator_pembangun" name="publikasi_indikator_pembangun"
                                                type="text"
                                                class="form-control {{ isset($publikasi_indikator_pembangun) ? ($publikasi_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                value="{{ old('publikasi_indikator_pembangun', optional($getdata->indikator)->publikasi_indikator_pembangun) }}">

                                            @if (isset($publikasi_indikator_pembangun) && !empty($publikasi_indikator_pembangun->comment))
                                                <p class="text-muted text-comment">Komentar:
                                                    {{ $publikasi_indikator_pembangun->comment }}</p>
                                            @endif
                                            <small class="text-muted">Catatan: Diisikan ketika indikator komposit.</small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="nama_indikator_pembangun" class="col-sm-2 col-form-label">Nama
                                            Indikator
                                            Pembangun</label>
                                        <div class="col-sm-10">
                                            <textarea name="nama_indikator_pembangun"
                                                class="form-control {{ isset($nama_indikator_pembangun) ? ($nama_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                style="height: 100px" spellcheck="false" placeholder="Nama Indikator Pembangun">{{ old('nama_indikator_pembangun', optional($getdata->indikator)->nama_indikator_pembangun) }}</textarea>

                                            @if (isset($nama_indikator_pembangun) && !empty($nama_indikator_pembangun->comment))
                                                <p class="text-muted text-comment">Komentar:
                                                    {{ $nama_indikator_pembangun->comment }}
                                                </p>
                                                <hr>
                                            @endif
                                            <small class="text-muted">Catatan: Diisikan ketika indikator komposit. Daftar
                                                nama
                                                dipisah menggunakan enter.</small>
                                        </div>
                                    </div>
                                </section>
                                <section class="no-komposit-section">
                                    {{-- <div class="row mb-3"> --}}
                                    {{-- <label for="kode_kegiatan_variabel_pembangun" class="col-sm-2 col-form-label">Kode
                                    Kegiatan --}}
                                    {{-- Penghasil Variabel Pembangun</label> --}}
                                    {{-- <div class="col-sm-10"> --}}
                                    {{-- <input id="kode_kegiatan_variabel_pembangun"
                                        name="kode_kegiatan_variabel_pembangun" type="text"
                                        class="form-control {{ isset($kode_kegiatan_variabel_pembangun) ? ($kode_kegiatan_variabel_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                        disabled placeholder="Diisi oleh petugas"
                                        value="{{old('kode_kegiatan_variabel_pembangun', optional($getdata->indikator)->kode_kegiatan_variabel_pembangun)}}"> --}}
                                    {{-- @if (isset($kode_kegiatan_vaariabel_pembangun) && !empty($kode_kegiatan_vaariabel_pembangun->comment)) --}}
                                    {{-- <p class="text-muted text-comment">Komentar:
                                        {{$kode_kegiatan_vaariabel_pembangun->comment}}</p> --}}
                                    {{-- @endif --}}
                                    {{-- </div> --}}
                                    {{-- </div> --}}

                                    {{-- <div class="row mb-3"> --}}
                                    {{-- <label for="kegiatan_variabel_pembangun"
                                    class="col-sm-2 col-form-label">Kegiatan --}}
                                    {{-- Penghasil Variabel Pembangun</label> --}}
                                    {{-- <div class="col-sm-10"> --}}
                                    {{-- <input id="kegiatan_variabel_pembangun" name="kegiatan_variabel_pembangun"
                                        type="text"
                                        class="form-control {{ isset($kegiatan_variabel_pembangun) ? ($kegiatan_variabel_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                        value="{{old('kegiatan_variabel_pembangun', optional($getdata->indikator)->kegiatan_variabel_pembangun)}}"> --}}
                                    {{-- @if (isset($kegiatan_variabel_pembangun) && !empty($kegiatan_variabel_pembangun->comment)) --}}
                                    {{-- <p class="text-muted text-comment">Komentar:
                                        {{$kegiatan_variabel_pembangun->comment}}</p> --}}
                                    {{-- @endif --}}
                                    {{-- </div> --}}
                                    {{-- </div> --}}

                                    <div class="row mb-3">
                                        <label for="nama_variabel_pembangun" class="col-sm-2 col-form-label">Nama Variabel
                                            Pembangun</label>
                                        <div class="col-sm-10">
                                            <textarea name="nama_variabel_pembangun"
                                                class="form-control {{ isset($nama_variabel_pembangun) ? ($nama_variabel_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                style="height: 100px" spellcheck="false" placeholder="Nama Variabel Pembangun">{{ old('nama_variabel_pembangun', optional($getdata->indikator)->nama_variabel_pembangun) }}</textarea>

                                            @if (isset($nama_variabel_pembangun) && !empty($nama_variabel_pembangun->comment))
                                                <p class="text-muted text-comment">Komentar:
                                                    {{ $nama_variabel_pembangun->comment }}
                                                </p>
                                                <hr>
                                            @endif
                                            <small class="text-muted">Catatan: Diisikan ketika <b>bukan</b> indikator
                                                komposit.
                                                Daftar nama
                                                dipisah menggunakan enter.</small>
                                        </div>
                                    </div>
                                </section>

                                <div class="row mb-3">
                                    <label for="level_estimasi" class="col-sm-2 col-form-label">Level Estimasi</label>
                                    <div class="col-sm-10">
                                        <select
                                            class="form-control form-select {{ isset($level_estimasi) ? ($level_estimasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            name="level_estimasi" id="level_estimasi">
                                            <option value="nasional"
                                                {{ old('level_estimasi', optional($getdata->indikator)->level_estimasi) == 'nasional' ||
                                                empty(old('level_estimasi', optional($getdata->indikator)->level_estimasi))
                                                    ? 'selected'
                                                    : '' }}>
                                                Nasional
                                            </option>
                                            <option value="provinsi"
                                                {{ old('level_estimasi', optional($getdata->indikator)->level_estimasi) == 'provinsi' ? 'selected' : '' }}>
                                                Provinsi</option>
                                            <option value="kabupaten"
                                                {{ old('level_estimasi', optional($getdata->indikator)->level_estimasi) == 'kabupaten' ? 'selected' : '' }}>
                                                Kabupaten/kota</option>
                                            <option value="perangkat_daerah"
                                                {{ old('level_estimasi', optional($getdata->indikator)->level_estimasi) == 'perangkat_daerah' ? 'selected' : '' }}>
                                                Perangkat Daerah</option>
                                            <option value="kecamatan"
                                                {{ old('level_estimasi', optional($getdata->indikator)->level_estimasi) == 'kecamatan' ? 'selected' : '' }}>
                                                Kecamatan</option>
                                            <option value="kelurahan"
                                                {{ old('level_estimasi', optional($getdata->indikator)->level_estimasi) == 'kelurahan' ? 'selected' : '' }}>
                                                Desa/Kelurahan</option>
                                            <option value="rt"
                                                {{ old('level_estimasi', optional($getdata->indikator)->level_estimasi) == 'rt' ? 'selected' : '' }}>
                                                Rumah Tangga</option>
                                            <option value="individu"
                                                {{ old('level_estimasi', optional($getdata->indikator)->level_estimasi) == 'individu' ? 'selected' : '' }}>
                                                Individu</option>
                                        </select>
                                        @if (isset($level_estimasi) && !empty($level_estimasi->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $level_estimasi->comment }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="umum1" class="col-sm-2 col-form-label">Apakah kolom ini dapat diakses
                                        umum</label>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input {{ isset($umum) ? ($umum->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                type="radio" name="umum" id="umum1" value="1"
                                                {{ old('umum', optional($getdata->indikator)->umum) == 1 ||
                                                empty(old('umum', optional($getdata->indikator)->umum))
                                                    ? 'checked'
                                                    : '' }}>
                                            <label class="form-check-label" for="umum1">
                                                Ya
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input {{ isset($umum) ? ($umum->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                type="radio" name="umum" id="umum2" value="0"
                                                {{ old('umum', optional($getdata->indikator)->umum) == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="umum2">
                                                Tidak
                                            </label>
                                        </div>
                                        @if (isset($umum) && !empty($umum->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $umum->comment }}</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="row mb-3">
                                    <label for="nama" class="col-sm-2 col-form-label">Nama Indikator</label>
                                    <div class="col-sm-10">
                                        <input id="nama" name="nama" type="text"
                                            class="form-control {{ isset($nama) ? ($nama->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            placeholder="Nama Indikator" value="{{ old('nama', $data->nama_data) }}"
                                            readonly>
                                        @if (isset($nama) && !empty($nama->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $nama->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                                    <div class="col-sm-10">
                                        <input id="konsep" name="konsep" type="text" readonly
                                            class="form-control {{ isset($konsep) ? ($konsep->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            placeholder="Konsep"
                                            value="{{ old('konsep', optional($data->standar)->konsep) }}">
                                        @if (isset($konsep) && !empty($konsep->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $konsep->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Definisi" class="col-sm-2 col-form-label">Definisi</label>
                                    <div class="col-sm-10">
                                        <textarea name="definisi" readonly
                                            class="form-control {{ isset($definisi) ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            style="height: 100px" spellcheck="false" placeholder="Definisi">{{ old('definisi', optional($data->standar)->definisi) }}</textarea>
                                        @if (isset($definisi) && !empty($definisi->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $definisi->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="interpretasi" class="col-sm-2 col-form-label">Interpretasi</label>
                                    <div class="col-sm-10">
                                        <textarea id="interpretasi" name="interpretasi" type="text"
                                            class="form-control {{ isset($interpretasi) ? ($interpretasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            style="height: 100px" spellcheck="false" placeholder="Interpretasi">{{ old('interpretasi', optional($data->indikator)->interpretasi) }}</textarea>
                                        @if (isset($interpretasi) && !empty($interpretasi->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $interpretasi->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                @php
                                    $isImage =
                                        optional($data->indikator)->metode &&
                                        Str::startsWith(optional($data->indikator)->metode, 'public/');
                                @endphp
                                <div class="row mb-3">
                                    <label for="metode" class="col-sm-2 col-form-label">Metode / Rumus
                                        Perhitungan</label>
                                    <div class="col-sm-6">
                                        <textarea name="metode" id="metode"
                                            class="form-control {{ isset($metode) ? ($metode->accepted ? 'is-valid' : 'is-invalid') : '' }}">{{ optional($data->indikator)->metode }}</textarea>
                                        @if (isset($metode) && !empty($metode->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $metode->comment }}</p>
                                            <hr>
                                        @endif
                                    </div>
                                    <div class="col-sm-auto">
                                        <p>atau</p>
                                    </div>
                                    <div class="col-auto flex-fill">
                                        @if ($isImage)
                                            <img class="img-fluid rounded"
                                                style="{{ isset($metode) ? ($metode->accepted ? 'border:5px solid green' : 'border:5px solid red') : '' }}"
                                                height="250px" width="250px"
                                                src="{{ Storage::url(optional($data->indikator)->metode) }}">
                                        @endif
                                        <input
                                            class="form-control {{ isset($metode) ? ($metode->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            name="metode_image" accept="image/*" type="file" id="metode_image">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                                    <div class="col-sm-10">
                                        <input id="ukuran" name="ukuran" type="text" readonly
                                            class="form-control {{ isset($ukuran) ? ($ukuran->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            placeholder="Ukuran"
                                            value="{{ old('ukuran', optional($data->standar)->ukuran) }}">
                                        @if (isset($ukuran) && !empty($ukuran->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $ukuran->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                                    <div class="col-sm-10">
                                        <input id="satuan" name="satuan" type="text" readonly
                                            class="form-control {{ isset($satuan) ? ($satuan->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            placeholder="Satuan"
                                            value="{{ old('satuan', optional($data->standar)->satuan) }}">
                                        @if (isset($satuan) && !empty($satuan->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $satuan->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="klasifikasi_penyajian" class="col-sm-2 col-form-label">Klasifikasi
                                        Penyajian</label>
                                    <div class="col-sm-10">
                                        <textarea name="klasifikasi_penyajian" readonly
                                            class="form-control {{ isset($klasifikasi_penyajian) ? ($klasifikasi_penyajian->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            style="height: 100px" spellcheck="false" placeholder="Klasifikasi Penyajian">{{ old('klasifikasi_penyajian', optional($data->standar)->klasifikasi) }}</textarea>
                                        @if (isset($klasifikasi_penyajian) && !empty($klasifikasi_penyajian->comment))
                                            <p class="text-muted text-comment">Komentar:
                                                {{ $klasifikasi_penyajian->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="komposit" class="col-sm-2 col-form-label">Apakah indikator
                                        komposit?</label>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input {{ isset($komposit) ? ($komposit->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                type="radio" name="komposit" id="komposit1" value="1"
                                                {{ old('komposit', optional($data->indikator)->komposit) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gridRadios1"> Ya </label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input {{ isset($komposit) ? ($komposit->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                type="radio" name="komposit" id="komposit2" value="0"
                                                {{ old('komposit', optional($data->indikator)->komposit) == 0 ||
                                                empty(old('komposit', optional($data->indikator)->komposit))
                                                    ? 'checked'
                                                    : '' }}>
                                            <label class="form-check-label" for="gridRadios1"> Tidak </label>
                                        </div>
                                        @if (isset($komposit) && !empty($komposit->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $komposit->comment }}</p>
                                        @endif
                                    </div>
                                </div>

                                <section class="komposit-section">
                                    <div class="row mb-3">
                                        <label for="publikasi_indikator_pembangun"
                                            class="col-sm-2 col-form-label">Publikasi
                                            Ketersediaan
                                            Indikator Pembangun</label>
                                        <div class="col-sm-10">
                                            <input id="publikasi_indikator_pembangun" name="publikasi_indikator_pembangun"
                                                type="text"
                                                class="form-control {{ isset($publikasi_indikator_pembangun) ? ($publikasi_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                value="{{ old('publikasi_indikator_pembangun', optional($data->indikator)->publikasi_indikator_pembangun) }}">
                                            @if (isset($publikasi_indikator_pembangun) && !empty($publikasi_indikator_pembangun->comment))
                                                <p class="text-muted text-comment">Komentar:
                                                    {{ $publikasi_indikator_pembangun->comment }}</p>
                                            @endif
                                            <small class="text-muted">Catatan: Diisikan ketika indikator komposit.</small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="nama_indikator_pembangun" class="col-sm-2 col-form-label">Nama
                                            Indikator
                                            Pembangun</label>
                                        <div class="col-sm-10">
                                            <textarea name="nama_indikator_pembangun"
                                                class="form-control {{ isset($nama_indikator_pembangun) ? ($nama_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                style="height: 100px" spellcheck="false" placeholder="Nama Indikator Pembangun">{{ old('nama_indikator_pembangun', optional($data->indikator)->nama_indikator_pembangun) }}</textarea>
                                            @if (isset($nama_indikator_pembangun) && !empty($nama_indikator_pembangun->comment))
                                                <p class="text-muted text-comment">Komentar:
                                                    {{ $nama_indikator_pembangun->comment }}
                                                </p>
                                                <hr>
                                            @endif
                                            <small class="text-muted">Catatan: Diisikan ketika indikator komposit. Daftar
                                                nama
                                                dipisah menggunakan enter.</small>
                                        </div>
                                    </div>
                                </section>
                                <section class="no-komposit-section">
                                    {{-- <div class="row mb-3"> --}}
                                    {{-- <label for="kode_kegiatan_variabel_pembangun" class="col-sm-2 col-form-label">Kode
                                    Kegiatan --}}
                                    {{-- Penghasil Variabel Pembangun</label> --}}
                                    {{-- <div class="col-sm-10"> --}}
                                    {{-- <input id="kode_kegiatan_variabel_pembangun"
                                        name="kode_kegiatan_variabel_pembangun" type="text"
                                        class="form-control {{ isset($kode_kegiatan_variabel_pembangun) ? ($kode_kegiatan_variabel_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                        disabled placeholder="Diisi oleh petugas"
                                        value="{{old('kode_kegiatan_variabel_pembangun', optional($data->indikator)->kode_kegiatan_variabel_pembangun)}}"> --}}
                                    {{-- @if (isset($kode_kegiatan_vaariabel_pembangun) && !empty($kode_kegiatan_vaariabel_pembangun->comment)) --}}
                                    {{-- <p class="text-muted text-comment">Komentar:
                                        {{$kode_kegiatan_vaariabel_pembangun->comment}}</p> --}}
                                    {{-- @endif --}}
                                    {{-- </div> --}}
                                    {{-- </div> --}}

                                    {{-- <div class="row mb-3"> --}}
                                    {{-- <label for="kegiatan_variabel_pembangun"
                                    class="col-sm-2 col-form-label">Kegiatan --}}
                                    {{-- Penghasil Variabel Pembangun</label> --}}
                                    {{-- <div class="col-sm-10"> --}}
                                    {{-- <input id="kegiatan_variabel_pembangun" name="kegiatan_variabel_pembangun"
                                        type="text"
                                        class="form-control {{ isset($kegiatan_variabel_pembangun) ? ($kegiatan_variabel_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                        value="{{old('kegiatan_variabel_pembangun', optional($data->indikator)->kegiatan_variabel_pembangun)}}"> --}}
                                    {{-- @if (isset($kegiatan_variabel_pembangun) && !empty($kegiatan_variabel_pembangun->comment)) --}}
                                    {{-- <p class="text-muted text-comment">Komentar:
                                        {{$kegiatan_variabel_pembangun->comment}}</p> --}}
                                    {{-- @endif --}}
                                    {{-- </div> --}}
                                    {{-- </div> --}}

                                    <div class="row mb-3">
                                        <label for="nama_variabel_pembangun" class="col-sm-2 col-form-label">Nama Variabel
                                            Pembangun</label>
                                        <div class="col-sm-10">
                                            <textarea name="nama_variabel_pembangun"
                                                class="form-control {{ isset($nama_variabel_pembangun) ? ($nama_variabel_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                style="height: 100px" spellcheck="false" placeholder="Nama Variabel Pembangun">{{ old('nama_variabel_pembangun', optional($data->indikator)->nama_variabel_pembangun) }}</textarea>
                                            @if (isset($nama_variabel_pembangun) && !empty($nama_variabel_pembangun->comment))
                                                <p class="text-muted text-comment">Komentar:
                                                    {{ $nama_variabel_pembangun->comment }}
                                                </p>
                                                <hr>
                                            @endif
                                            <small class="text-muted">Catatan: Diisikan ketika <b>bukan</b> indikator
                                                komposit.
                                                Daftar nama
                                                dipisah menggunakan enter.</small>
                                        </div>
                                    </div>
                                </section>

                                <div class="row mb-3">
                                    <label for="level_estimasi" class="col-sm-2 col-form-label">Level Estimasi</label>
                                    <div class="col-sm-10">
                                        <select
                                            class="form-control form-select {{ isset($level_estimasi) ? ($level_estimasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            name="level_estimasi" id="level_estimasi">
                                            <option value="nasional"
                                                {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'nasional' ||
                                                empty(old('level_estimasi', optional($data->indikator)->level_estimasi))
                                                    ? 'selected'
                                                    : '' }}>
                                                Nasional
                                            </option>
                                            <option value="provinsi"
                                                {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'provinsi' ? 'selected' : '' }}>
                                                Provinsi</option>
                                            <option value="kabupaten"
                                                {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'kabupaten' ? 'selected' : '' }}>
                                                Kabupaten/kota
                                            </option>
                                            <option value="perangkat_daerah"
                                                {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'perangkat_daerah' ? 'selected' : '' }}>
                                                Perangkat
                                                Daerah</option>
                                            <option value="kecamatan"
                                                {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'kecamatan' ? 'selected' : '' }}>
                                                Kecamatan
                                            </option>
                                            <option value="kelurahan"
                                                {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'kelurahan' ? 'selected' : '' }}>
                                                Desa/Kelurahan
                                            </option>
                                            <option value="rt"
                                                {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'rt' ? 'selected' : '' }}>
                                                Rumah Tangga</option>
                                            <option value="individu"
                                                {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'individu' ? 'selected' : '' }}>
                                                Individu</option>
                                        </select>
                                        @if (isset($level_estimasi) && !empty($level_estimasi->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $level_estimasi->comment }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="umum1" class="col-sm-2 col-form-label">Apakah kolom ini dapat diakses
                                        umum</label>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input {{ isset($umum) ? ($umum->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                type="radio" name="umum" id="umum1" value="1"
                                                {{ old('umum', optional($data->indikator)->umum) == 1 || empty(old('umum', optional($data->indikator)->umum))
                                                    ? 'checked'
                                                    : '' }}>
                                            <label class="form-check-label" for="umum1">
                                                Ya
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input {{ isset($umum) ? ($umum->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                type="radio" name="umum" id="umum2" value="0"
                                                {{ old('umum', optional($data->indikator)->umum) == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="umum2">
                                                Tidak
                                            </label>
                                        </div>
                                        @if (isset($umum) && !empty($umum->comment))
                                            <p class="text-muted text-comment">Komentar: {{ $umum->comment }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if (auth()->user()->hasAnyRole('produsen'))
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            @endif

                            {{-- <a href="{{ url()->previous('d_' . (auth()->user()->hasAnyRole('produsen') ? 'produsen' : 'walidata')) }}"
                                class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a> --}}
                            @if ($data->status_id == 7)
                                <a href="{{ auth()->user()->hasAnyRole('produsen') ? '/data_produsen/verifikasi/revisi' : '/data_walidata/verifikasi/revisi' }}"
                                    class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
                                    Kembali</a>
                            @else
                                <a href="{{ auth()->user()->hasAnyRole('produsen') ? '/data_produsen/pengumpulan' : '/data_walidata/pengumpulan' }}"
                                    class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
                                    Kembali</a>
                            @endif

                        </form><!-- End General Form Elements -->

                    </div>
                </div>

            </div>

            <div class="modal fade" id="modalImport" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Import Metadata Indikator dari Excel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda dapat meng-<em>import</em> metadata dari file Excel menggunakan template data di bawah
                                ini.
                                @foreach ($document as $bkrs)
                                    <div class="list-group" style="margin-bottom: 5px">
                                        <a href="{{ url('/download-template', $bkrs->id) }}"
                                            class="list-group-item list-group-item-action">
                                            template data <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </p>
                            <form enctype="multipart/form-data" action="{{ route('import-indikator', $data->id) }}"
                                id="formImport" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <label for="inputNumber" class="col-sm-2 col-form-label">Berkas Excel</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" id="fileImport" name="metadata"
                                            required>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" form="formImport" role="button"
                                onclick="document.getElementById('formImport').submit();">Upload</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('js')
    <script>
        // $(function () {
        //         $('section.no-komposit-section').hide();
        //         $('section.komposit-section').hide();
        //         $('input[name="komposit"]').change(function () {
        //             if ($('input[name="komposit"]:checked').val() == 1) {
        //                 $('section.komposit-section').show();
        //                 $('section.no-komposit-section').hide();
        //             } else {
        //                 $('section.komposit-section').hide();
        //                 $('section.no-komposit-section').show();
        //             }
        //         }).trigger('change');
        //     })
    </script>
@endpush
