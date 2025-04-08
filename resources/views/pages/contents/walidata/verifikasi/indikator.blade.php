@extends('pages.main.layout')

@php
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
    // $role = Auth::user()->roles[0]['name'];
    // $wali = 'walidata';
@endphp

@section('content')
    <div class="pagetitle">
        <h1>Metadata Indikator</h1>
        {{-- <p>{{$data->status_id}}</p> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Daftar Pengumpulan Data</a></li>
                <li class="breadcrumb-item">{{ $data->nama_data }}</li>
                <li class="breadcrumb-item active">Metadata Indikator</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Metadata Indikator</h5>

                        @php
                            // dd($data);
                        @endphp

                        <form action="{{ route('simpan-indikator', $data->id) }}" method="POST">
                            @csrf
                            <div class="row mb-3 align-items-center">
                                <label for="nama" class="col-sm-2 col-form-label">Nama Indikator</label>
                                <div class="col-sm-8">
                                    <div class="input-group has-validation">
                                        <input id="nama" name="nama" type="text"
                                            class="form-control {{ $nama ? ($nama->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                            placeholder="Nama Indikator"
                                            value="{{ old('nama', optional($data->variabel)->nama ?? $data->nama_data) }}"
                                            disabled>
                                    </div>
                                    {{-- {{$nama->accepted}} --}}
                                </div>
                                <div class="col-sm-2">
                                    <div class="btn-group-sm">
                                        {{-- <button
                                        class="btn btn-actions btn-accept btn-sm {{$nama && $nama->accepted ? 'btn-success' : 'btn-outline-success'}}"
                                        data-name="nama">Setuju <i class="bi bi-check"></i></button>
                                    <button
                                        class="btn btn-actions btn-reject btn-sm {{$nama && !$nama->accepted ? 'btn-danger' : 'btn-outline-danger'}}"
                                        data-name="nama">Revisi <i class="bi bi-x"></i></button> --}}
                                        {{-- <button class="btn btn-comment btn-sm btn-outline-primary" data-name="nama"><i
                                            class="bi bi-chat-dots"></i> Komentar</button> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                                <div class="col-sm-8">
                                    <textarea name="konsep"
                                        class="form-control {{ $konsep ? ($konsep->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        style="height: 100px" spellcheck="false" placeholder="Konsep" disabled>{{ old('konsep', optional($data->variabel)->konsep ?? optional($data->standar)->konsep) }}</textarea>
                                </div>
                                {{-- {{$konsep->accepted}} --}}
                                @if ($data->status_id != 7)
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $konsep && $konsep->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="konsep">Setuju <i class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $konsep && !$konsep->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="konsep">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="konsep"><i class="bi bi-chat-dots"></i> Komentar</button> --}}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <label for="definisi" class="col-sm-2 col-form-label">Definisi</label>
                                <div class="col-sm-8">
                                    <textarea id="definisi" name="definisi" type="text"
                                        class="form-control {{ $definisi ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        style="height: 100px" spellcheck="false" placeholder="Definisi" disabled>{{ old('definisi', optional($data->variabel)->definisi ?? optional($data->standar)->definisi) }}</textarea>
                                </div>
                                {{-- {{$definisi->accepted}} --}}
                                @if ($data->status_id != 7)
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $definisi && $definisi->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="definisi">Setuju <i class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $definisi && !$definisi->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="definisi">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="definisi"><i class="bi bi-chat-dots"></i> Komentar</button> --}}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <label for="interpretasi" class="col-sm-2 col-form-label">Interpretasi</label>
                                <div class="col-sm-8">
                                    <textarea id="interpretasi" name="interpretasi" type="text" readonly
                                        class="form-control {{ isset($interpretasi) ? ($interpretasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                        style="height: 100px" spellcheck="false" placeholder="Interpretasi">{{ old('interpretasi', optional($data->indikator)->interpretasi) }}</textarea>
                                </div>
                                {{-- {{$interpretasi->accepted}} --}}
                                @if ($data->status_id != 7)
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $interpretasi && $interpretasi->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="interpretasi">Setuju <i class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $interpretasi && !$interpretasi->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="interpretasi">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="interpretasi"><i class="bi bi-chat-dots"></i> Komentar</button> --}}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <label for="metode" class="col-sm-2 col-form-label">Metode / Rumus Perhitungan</label>
                                <div class="row col-sm-8">
                                    @php
                                        $isImage = Str::startsWith(optional($data->indikator)->metode, 'public/');
                                    @endphp
                                    <div class="col-sm-{{ $isImage ? 4 : 8 }}">
                                        <textarea name="metode" id="metode"
                                            class="form-control {{ isset($metode) ? ($metode->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            style="height: 100px" spellcheck="false" placeholder="Metode / Rumus Perhitungan" disabled>{{ optional($data->indikator)->metode }}</textarea>
                                    </div>
                                    {{-- {{$metode->accepted}} --}}
                                    @if ($isImage)
                                        <div class="col-sm-4">
                                            <img class="img-fluid rounded" height="250px" width="250px"
                                                src="{{ Storage::url(optional($data->indikator)->metode) }}">
                                        </div>
                                    @endif
                                </div>
                                @if ($data->status_id != 7)
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $metode && $metode->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="metode">Setuju <i class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $metode && !$metode->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="metode">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="metode"><i class="bi bi-chat-dots"></i> Komentar</button> --}}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                                <div class="col-sm-8">
                                    <input id="ukuran" name="ukuran" type="text" readonly
                                        class="form-control {{ $ukuran ? ($ukuran->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="Ukuran"
                                        value="{{ old('ukuran', optional($data->indikator)->ukuran ?? optional($data->standar)->ukuran) }}">
                                </div>
                                {{-- {{$ukuran->accepted}} --}}
                                @if ($data->status_id != 7)
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $ukuran && $ukuran->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="ukuran">Setuju <i class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $ukuran && !$ukuran->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="ukuran">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="ukuran"><i class="bi bi-chat-dots"></i> Komentar</button> --}}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                                <div class="col-sm-8">
                                    <input id="satuan" name="satuan" type="text"
                                        class="form-control {{ $satuan ? ($satuan->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="Satuan"
                                        value="{{ old('satuan', optional($data->variabel)->satuan ?? optional($data->standar)->satuan) }}"
                                        disabled>
                                </div>
                                {{-- {{$satuan->accepted}} --}}
                                @if ($data->status_id != 7)
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $satuan && $satuan->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="satuan">Setuju <i class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $satuan && !$satuan->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="satuan">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="satuan"><i class="bi bi-chat-dots"></i> Komentar</button> --}}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <label for="klasifikasi_penyajian" class="col-sm-2 col-form-label">Klasifikasi
                                    Penyajian</label>
                                <div class="col-sm-8">
                                    <textarea name="klasifikasi_penyajian" readonly
                                        class="form-control {{ $klasifikasi_penyajian ? ($klasifikasi_penyajian->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        style="height: 100px" spellcheck="false" placeholder="Klasifikasi Penyajian">{{ old('klasifikasi_penyajian', optional($data->indikator)->klasifikasi_penyajian) }}</textarea>
                                </div>
                                {{-- {{$klasifikasi_penyajian->accepted}} --}}
                                @if ($data->status_id != 7)
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $klasifikasi_penyajian && $klasifikasi_penyajian->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="klasifikasi_penyajian">Setuju <i
                                                    class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $klasifikasi_penyajian && !$klasifikasi_penyajian->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="klasifikasi_penyajian">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="klasifikasi_penyajian"><i class="bi bi-chat-dots"></i>
                                        Komentar</button> --}}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <label for="komposit" class="col-sm-2 col-form-label">Apakah indikator komposit?</label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input {{ isset($komposit) ? ($komposit->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            type="radio" name="komposit" id="komposit1" value="1"
                                            {{ old('komposit', optional($data->indikator)->komposit) == 1 ? 'checked' : '' }}
                                            disabled>
                                        <label class="form-check-label" for="gridRadios1"> Ya </label>
                                    </div>
                                    {{-- {{$komposit->accepted}} --}}
                                    <div class="form-check">
                                        <input
                                            class="form-check-input {{ isset($komposit) ? ($komposit->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                            type="radio" name="komposit" id="komposit2" value="0"
                                            {{ old('komposit', optional($data->indikator)->komposit) == 0 ||
                                            empty(old('komposit', optional($data->indikator)->komposit))
                                                ? 'checked'
                                                : '' }}
                                            disabled>
                                        <label class="form-check-label" for="gridRadios1"> Tidak </label>
                                    </div>
                                </div>
                                @if ($data->status_id != 7)
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $komposit && $komposit->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="komposit">Setuju <i class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $komposit && !$komposit->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="komposit">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="komposit"><i class="bi bi-chat-dots"></i>
                                        Komentar</button> --}}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <section class="komposit-section">
                                <div class="row mb-3">
                                    <label for="publikasi_indikator_pembangun" class="col-sm-2 col-form-label">Publikasi
                                        Ketersediaan
                                        Indikator Pembangun</label>
                                    <div class="col-sm-8">
                                        <input id="publikasi_indikator_pembangun" name="publikasi_indikator_pembangun"
                                            type="text" readonly
                                            class="form-control {{ $publikasi_indikator_pembangun ? ($publikasi_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                            value="{{ old('publikasi_indikator_pembangun', optional($data->indikator)->publikasi_indikator_pembangun) }}">
                                        <small class="text-muted">Catatan: Diisikan ketika indikator komposit.</small>
                                    </div>
                                    {{-- {{$publikasi_indikator_pembangun->accepted}} --}}
                                    @if ($data->status_id != 7)
                                        <div class="col-sm-2">
                                            <div class="btn-group-sm">
                                                <button
                                                    class="btn btn-actions btn-accept btn-sm {{ $publikasi_indikator_pembangun && $publikasi_indikator_pembangun->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                    data-name="publikasi_indikator_pembangun">Setuju <i
                                                        class="bi bi-check"></i></button>
                                                <button
                                                    class="btn btn-actions btn-reject btn-sm {{ $publikasi_indikator_pembangun && !$publikasi_indikator_pembangun->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                    data-name="publikasi_indikator_pembangun">Revisi <i
                                                        class="bi bi-x"></i></button>
                                                {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                            data-name="publikasi_indikator_pembangun"><i class="bi bi-chat-dots"></i>
                                            Komentar</button> --}}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="row mb-3">
                                    <label for="nama_indikator_pembangun" class="col-sm-2 col-form-label">Nama Indikator
                                        Pembangun</label>
                                    <div class="col-sm-8">
                                        <textarea name="nama_indikator_pembangun" readonly
                                            class="form-control {{ $nama_indikator_pembangun ? ($nama_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                            style="height: 100px" spellcheck="false" placeholder="Nama Indikator Pembangun">{{ old('nama_indikator_pembangun', optional($data->indikator)->nama_indikator_pembangun) }}</textarea>
                                        <small class="text-muted">Catatan: Diisikan ketika indikator komposit. Daftar nama
                                            dipisah menggunakan enter.</small>
                                    </div>
                                    {{-- {{$nama_indikator_pembangun->accepted}} --}}
                                    @if ($data->status_id != 7)
                                        <div class="col-sm-2">
                                            <div class="btn-group-sm">
                                                <button
                                                    class="btn btn-actions btn-accept btn-sm {{ $nama_indikator_pembangun && $nama_indikator_pembangun->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                    data-name="nama_indikator_pembangun">Setuju <i
                                                        class="bi bi-check"></i></button>
                                                <button
                                                    class="btn btn-actions btn-reject btn-sm {{ $nama_indikator_pembangun && !$nama_indikator_pembangun->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                    data-name="nama_indikator_pembangun">Revisi <i
                                                        class="bi bi-x"></i></button>
                                                {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                            data-name="nama_indikator_pembangun"><i class="bi bi-chat-dots"></i>
                                            Komentar</button> --}}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </section>
                            <section class="no-komposit-section">
                                {{-- <div class="row mb-3"> --}}
                                {{-- <label for="kode_kegiatan_variabel_pembangun" class="col-sm-2 col-form-label">Kode
                                    Kegiatan Penghasil Variabel Pembangun</label> --}}
                                {{-- <div class="col-sm-8"> --}}
                                {{-- <input id="kode_kegiatan_variabel_pembangun"
                                        name="kode_kegiatan_variabel_pembangun" type="text" class="form-control"
                                        disabled placeholder="Diisi oleh petugas"
                                        value="{{old('kode_kegiatan_variabel_pembangun', optional($data->indikator)->kode_kegiatan_variabel_pembangun)}}"> --}}
                                {{-- </div> --}}
                                {{-- </div> --}}

                                {{-- <div class="row mb-3"> --}}
                                {{-- <label for="kegiatan_variabel_pembangun" class="col-sm-2 col-form-label">Kegiatan
                                    Penghasil Variabel Pembangun</label> --}}
                                {{-- <div class="col-sm-8"> --}}
                                {{-- <input id="kegiatan_variabel_pembangun" name="kegiatan_variabel_pembangun"
                                        type="text"
                                        class="form-control {{ $kegiatan_variabel_pembangun ? ($kegiatan_variabel_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        value="{{old('kegiatan_variabel_pembangun', optional($data->indikator)->kegiatan_variabel_pembangun)}}"> --}}
                                {{-- </div> --}}
                                {{-- <div class="col-sm-2"> --}}
                                {{-- <div class="btn-group-sm"> --}}
                                {{-- <button
                                            class="btn btn-actions btn-accept btn-sm {{$kegiatan_variabel_pembangun && $kegiatan_variabel_pembangun->accepted ? 'btn-success' : 'btn-outline-success'}}"
                                            data-name="kegiatan_variabel_pembangun">Setuju <i
                                                class="bi bi-check"></i></button> --}}
                                {{-- <button
                                            class="btn btn-actions btn-reject btn-sm {{$kegiatan_variabel_pembangun && !$kegiatan_variabel_pembangun->accepted ? 'btn-danger' : 'btn-outline-danger'}}"
                                            data-name="kegiatan_variabel_pembangun">Revisi <i
                                                class="bi bi-x"></i></button> --}}
                                {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                            data-name="kegiatan_variabel_pembangun"><i class="bi bi-chat-dots"></i>
                                            Komentar</button> --}}
                                {{-- </div> --}}
                                {{-- </div> --}}
                                {{-- </div> --}}

                                <div class="row mb-3">
                                    <label for="nama_variabel_pembangun" class="col-sm-2 col-form-label">Nama Variabel
                                        Pembangun</label>
                                    <div class="col-sm-8">
                                        <textarea name="nama_variabel_pembangun" readonly
                                            class="form-control {{ $nama_variabel_pembangun ? ($nama_variabel_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                            style="height: 100px" spellcheck="false" placeholder="Nama Variabel Pembangun">{{ old('nama_variabel_pembangun', optional($data->indikator)->nama_variabel_pembangun) }}</textarea>
                                        <small class="text-muted">Catatan: Diisikan ketika <b>bukan</b> indikator komposit.
                                            Daftar nama
                                            dipisah menggunakan enter.</small>
                                    </div>
                                    {{-- {{$nama_variabel_pembangun->accepted}} --}}
                                    @if ($data->status_id != 7)
                                        <div class="col-sm-2">
                                            <div class="btn-group-sm">
                                                <button
                                                    class="btn btn-actions btn-accept btn-sm {{ $nama_variabel_pembangun && $nama_variabel_pembangun->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                    data-name="nama_variabel_pembangun">Setuju <i
                                                        class="bi bi-check"></i></button>
                                                <button
                                                    class="btn btn-actions btn-reject btn-sm {{ $nama_variabel_pembangun && !$nama_variabel_pembangun->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                    data-name="nama_variabel_pembangun">Revisi <i
                                                        class="bi bi-x"></i></button>
                                                {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                            data-name="nama_variabel_pembangun"><i class="bi bi-chat-dots"></i>
                                            Komentar</button> --}}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </section>

                            <div class="row mb-3">
                                <label for="level_estimasi" class="col-sm-2 col-form-label">Level Estimasi</label>
                                <div class="col-sm-8">
                                    <select
                                        class="form-control {{ $level_estimasi ? ($level_estimasi->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        name="level_estimasi" id="level_estimasi" disabled>
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
                                </div>
                                {{-- {{$level_estimasi->accepted}} --}}
                                @if ($data->status_id != 7)
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $level_estimasi && $level_estimasi->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="level_estimasi">Setuju <i class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $level_estimasi && !$level_estimasi->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="level_estimasi">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="level_estimasi"><i class="bi bi-chat-dots"></i> Komentar</button>
                                    --}}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <label for="umum1" class="col-sm-2 col-form-label">Apakah kolom ini dapat diakses
                                    umum</label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="umum" id="umum1"
                                            value="1"
                                            {{ old('umum', optional($data->indikator)->umum) == 1 || empty(old('umum', optional($data->indikator)->umum))
                                                ? 'checked'
                                                : '' }}
                                            disabled>
                                        <label class="form-check-label" for="umum1">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="umum" id="umum2"
                                            value="0"
                                            {{ old('umum', optional($data->indikator)->umum) == 0 ? 'checked' : '' }}
                                            disabled>
                                        <label class="form-check-label" for="umum2">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                                {{-- {{$umum->accepted}} --}}
                                @if ($data->status_id != 7)
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $umum && $umum->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="umum">Setuju <i class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $umum && !$umum->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="umum">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary" data-name="umum"><i
                                            class="bi bi-chat-dots"></i> Komentar</button> --}}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                        @if ($data->status_id == 7)
                            <a href="{{ auth()->user()->hasAnyRole('produsen') ? url('/data_produsen/verifikasi/revisi') : url('/data_walidata/verifikasi/revisi') }}"
                                {{-- href="{{ url()->previous() }}"  --}} class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
                                Kembali</a>
                        @else
                            <a href="{{ auth()->user()->hasAnyRole('produsen') ? url('/data_produsen/verifikasi') : url('/data_walidata/verifikasi') }}"
                                {{-- href="{{ url()->previous() }}"  --}} class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
                                Kembali</a>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </section>
@endsection

@push('js')
    <script>
        $(function() {
            $('button.btn-actions').on('click', function(e) {
                e.preventDefault();
                let isAccept = $(this).hasClass('btn-accept');
                let isReject = $(this).hasClass('btn-reject');
                if (isAccept || isReject) {
                    Swal.fire({
                        title: 'Apakah Anda yakin ingin mengkonfirmasi?',
                        showCancelButton: true,
                        confirmButtonText: isAccept ? 'Ya, Setuju' : 'Ya, Revisi',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: isAccept ? '#28a745' : '#dc3545',
                        cancelButtonColor: '#6c757d',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                    url: '{{ route('verifikasi.verify', $data->id) }}',
                                    method: 'PATCH',
                                    data: {
                                        category: 'indikator',
                                        accepted: isAccept ? 1 : 0,
                                        field: $(this).data('name')
                                    }
                                })
                                .then((r) => {
                                    let inputValue = '';
                                    if (r.comment) {
                                        inputValue = r.comment;
                                    }
                                    Swal.fire({
                                        title: 'Komentar untuk field ini',
                                        input: 'textarea',
                                        inputValue: inputValue,
                                        inputAttributes: {
                                            autocapitalize: 'off',
                                            spellCheck: false,
                                        },
                                        showCancelButton: true,
                                        confirmButtonText: 'Simpan',
                                        showLoaderOnConfirm: true,
                                        preConfirm: (comment) => {
                                            return $.post(
                                                    '{{ route('verifikasi.komentar', $data->id) }}', {
                                                        field: $(this).data('name'),
                                                        comment: comment,
                                                        category: 'indikator'
                                                    })
                                                .then(response => {
                                                    if (!response.ok) {
                                                        throw new Error(response
                                                            .message)
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
                                        console.log(result);
                                        Toast.fire({
                                            icon: result.value.ok ? 'success' :
                                                'error',
                                            title: result.value.message
                                        });
                                        location
                                            .reload(); // Merefresh halaman setelah memberikan komentar
                                    });
                                })
                                .catch(() => Toast.fire({
                                    icon: 'error',
                                    title: 'Gagal menyimpan perubahan'
                                }));
                        }
                    });
                } else {
                    $.ajax({
                            url: '{{ route('verifikasi.verify', $data->id) }}',
                            method: 'PATCH',
                            data: {
                                category: 'indikator',
                                accepted: null,
                                field: $(this).data('name')
                            }
                        })
                        .then((r) => {
                            Toast.fire({
                                icon: r.ok ? 'success' : 'error',
                                title: r.message
                            });
                            location.reload(); // Merefresh halaman setelah memberikan komentar
                        })
                        .catch(() => Toast.fire({
                            icon: 'error',
                            title: 'Gagal menyimpan perubahan'
                        }));
                }
            });
        });
    </script>
@endpush
