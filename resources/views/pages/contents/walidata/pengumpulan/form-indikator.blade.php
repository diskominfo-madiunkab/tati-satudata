@extends('pages.main.layout')

@php
    if ($data->relationLoaded('verifikasi')) {
        $v = optional($data->verifikasi);
        $variables = ['nama', 'alias', 'definisi', 'interpretasi', 'konsep', 'metode', 'ukuran', 'satuan', 'klasifikasi_penyajian', 'komposit', 'publikasi_indikator_pembangun', 'nama_indikator_pembangun', 'kegiatan_variabel_pembangun', 'kode_kegiatan_variabel_pembangun', 'nama_variabel_pembangun', 'level_estimasi', 'umum'];
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
                <li class="breadcrumb-item">{{$data->nama_data}}</li>
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
                        </div>

                        <form action="{{route('simpan-indikator', $data->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <label for="nama" class="col-sm-2 col-form-label">Nama Indikator</label>
                                <div class="col-sm-10">
                                    <input id="nama" name="nama" type="text" class="form-control {{ isset($nama) ? ($nama->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                           placeholder="Nama Indikator" value="{{old('nama', $data->nama_data)}}" readonly>
                                    @if (isset($nama) && !empty($nama->comment))
                                        <p class="text-muted text-comment">Komentar: {{$nama->comment}}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                                <div class="col-sm-10">
                                    <input id="konsep" name="konsep" type="text" class="form-control {{ isset($konsep) ? ($konsep->accepted ? 'is-valid' : 'is-invalid') : '' }}" placeholder="Konsep" value="{{old('ukuran', optional($data->indikator)->konsep ?? optional($data->standar)->konsep)}}" readonly>
                                    @if (isset($konsep) && !empty($konsep->comment))
                                        <p class="text-muted text-comment">Komentar: {{$konsep->comment}}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="Definisi" class="col-sm-2 col-form-label">Definisi</label>
                                <div class="col-sm-10">
                                    <textarea name="definisi" class="form-control {{ isset($definisi) ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }}" style="height: 100px" spellcheck="false" placeholder="Definisi" disabled>{{old('definisi', optional($data->indikator)->definisi ?? optional($data->standar)->definisi)}}</textarea>
                                    @if (isset($definisi) && !empty($definisi->comment))
                                        <p class="text-muted text-comment">Komentar: {{$definisi->comment}}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="interpretasi" class="col-sm-2 col-form-label">Interpretasi</label>
                                <div class="col-sm-10">
                                    <textarea id="interpretasi" name="interpretasi" type="text" class="form-control {{ isset($interpretasi) ? ($interpretasi->accepted ? 'is-valid' : 'is-invalid') : '' }}" style="height: 100px" spellcheck="false" placeholder="Interpretasi" disabled>{{old('interpretasi', optional($data->indikator)->interpretasi)}}</textarea>
                                    @if (isset($interpretasi) && !empty($interpretasi->comment))
                                        <p class="text-muted text-comment">Komentar: {{$interpretasi->comment}}</p>
                                    @endif
                                </div>
                            </div>

                            @php
                                $isImage = optional($data->indikator)->metode && Str::startsWith(optional($data->indikator)->metode, 'public/');
                            @endphp
                            <div class="row mb-3">
                                <label for="metode" class="col-sm-2 col-form-label">Metode / Rumus Perhitungan</label>
                                <div class="col-sm-6">
                                    @if ($isImage)
                                        <img class="img-fluid rounded" height="250px" width="250px" style="{{ isset($metode) ? ($metode->accepted) ? 'border:5px solid green' : 'border:5px solid red' : '' }}" src="{{Storage::url(optional($data->indikator)->metode)}}">
                                    @else
                                        <textarea name="metode" id="metode" class="form-control {{ isset($metode) ? ($metode->accepted) ? 'is-valid' : 'is-invalid' : '' }}" disabled>{{optional($data->indikator)->metode}}</textarea>
                                    @endif
                                    @if (isset($metode) && !empty($metode->comment))
                                        <p class="text-muted text-comment">Komentar: {{$metode->comment}}</p>
                                        <hr>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                                <div class="col-sm-10">
                                    <input id="ukuran" name="ukuran" type="text" class="form-control {{ isset($ukuran) ? ($ukuran->accepted ? 'is-valid' : 'is-invalid') : '' }}" placeholder="Ukuran" value="{{old('ukuran', optional($data->indikator)->ukuran ?? optional($data->standar)->ukuran)}}" readonly>
                                    @if (isset($ukuran) && !empty($ukuran->comment))
                                        <p class="text-muted text-comment">Komentar: {{$ukuran->comment}}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                                <div class="col-sm-10">
                                    <input id="satuan" name="satuan" type="text" class="form-control {{ isset($satuan) ? ($satuan->accepted ? 'is-valid' : 'is-invalid') : '' }}" placeholder="Satuan" value="{{old('satuan', optional($data->indikator)->satuan ?? optional($data->standar)->satuan)}}" readonly>
                                    @if (isset($satuan) && !empty($satuan->comment))
                                        <p class="text-muted text-comment">Komentar: {{$satuan->comment}}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="klasifikasi_penyajian" class="col-sm-2 col-form-label">Klasifikasi Penyajian</label>
                                <div class="col-sm-10">
                                    <textarea name="klasifikasi_penyajian" class="form-control {{ isset($klasifikasi_penyajian) ? ($klasifikasi_penyajian->accepted ? 'is-valid' : 'is-invalid') : '' }}" style="height: 100px" spellcheck="false" placeholder="Klasifikasi Penyajian" disabled>{{old('klasifikasi_penyajian', optional($data->indikator)->klasifikasi_penyajian)}}</textarea>
                                    @if (isset($klasifikasi_penyajian) && !empty($klasifikasi_penyajian->comment))
                                        <p class="text-muted text-comment">Komentar: {{$klasifikasi_penyajian->comment}}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="komposit" class="col-sm-2 col-form-label">Apakah indikator komposit?</label>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input {{ isset($komposit) ? ($komposit->accepted ? 'is-valid' : 'is-invalid') : '' }}" type="radio" name="komposit"  id="komposit1" value="1" {{old('komposit', optional($data->indikator)->komposit) == 1 ? 'checked' : ''}} disabled>
                                        <label class="form-check-label" for="gridRadios1"> Ya </label></div>
                                    <div class="form-check">
                                        <input class="form-check-input {{ isset($komposit) ? ($komposit->accepted ? 'is-valid' : 'is-invalid') : '' }}" type="radio" name="komposit" id="komposit2" value="0" {{old('komposit', optional($data->indikator)->komposit) == 0 || empty(old('komposit', optional($data->indikator)->komposit)) ? 'checked' : ''}} disabled>
                                        <label class="form-check-label" for="gridRadios1"> Tidak </label></div>
                                    @if (isset($komposit) && !empty($komposit->comment))
                                        <p class="text-muted text-comment">Komentar: {{$komposit->comment}}</p>
                                    @endif
                                </div>
                            </div>

                            <section class="komposit-section">
                                <div class="row mb-3">
                                    <label for="publikasi_indikator_pembangun" class="col-sm-2 col-form-label">Publikasi Ketersediaan
                                        Indikator Pembangun</label>
                                    <div class="col-sm-10">
                                        <input id="publikasi_indikator_pembangun" name="publikasi_indikator_pembangun" type="text" class="form-control {{ isset($publikasi_indikator_pembangun) ? ($publikasi_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}" value="{{old('publikasi_indikator_pembangun', optional($data->indikator)->publikasi_indikator_pembangun)}}" readonly>
                                        @if (isset($publikasi_indikator_pembangun) && !empty($publikasi_indikator_pembangun->comment))
                                            <p class="text-muted text-comment">Komentar: {{$publikasi_indikator_pembangun->comment}}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="nama_indikator_pembangun" class="col-sm-2 col-form-label">Nama Indikator
                                        Pembangun</label>
                                    <div class="col-sm-10">
                                        <textarea name="nama_indikator_pembangun" class="form-control {{ isset($nama_indikator_pembangun) ? ($nama_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}" style="height: 100px" spellcheck="false" placeholder="Nama Indikator Pembangun" readonly>{{old('nama_indikator_pembangun', optional($data->indikator)->nama_indikator_pembangun)}}</textarea>
                                        @if (isset($nama_indikator_pembangun) && !empty($nama_indikator_pembangun->comment))
                                            <p class="text-muted text-comment">Komentar: {{$nama_indikator_pembangun->comment}}</p>
                                            <hr>
                                        @endif
                                        <small class="text-muted">Daftar nama dipisah menggunakan enter.</small>
                                    </div>
                                </div>
                            </section>
                            <section class="no-komposit-section">
                                <div class="row mb-3">
                                    <label for="nama_variabel_pembangun" class="col-sm-2 col-form-label">Nama Variabel Pembangun</label>
                                    <div class="col-sm-10">
                                        <textarea name="nama_variabel_pembangun" class="form-control {{ isset($nama_variabel_pembangun) ? ($nama_variabel_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}" style="height: 100px" spellcheck="false" placeholder="Nama Variabel Pembangun" readonly>{{old('nama_variabel_pembangun', optional($data->indikator)->nama_variabel_pembangun)}}</textarea>
                                        @if (isset($nama_variabel_pembangun) && !empty($nama_variabel_pembangun->comment))
                                            <p class="text-muted text-comment">Komentar: {{$nama_variabel_pembangun->comment}}</p>
                                            <hr>
                                        @endif
                                        <small class="text-muted">Daftar nama dipisah menggunakan enter.</small>
                                    </div>
                                </div>
                            </section>

                            <div class="row mb-3">
                                <label for="level_estimasi" class="col-sm-2 col-form-label">Level Estimasi</label>
                                <div class="col-sm-10">
                                    <select class="form-control form-select {{ isset($level_estimasi) ? ($level_estimasi->accepted ? 'is-valid' : 'is-invalid') : '' }}" name="level_estimasi" id="level_estimasi" disabled>
                                        <option value="nasional" {{old('level_estimasi', optional($data->indikator)->level_estimasi) == 'nasional' || empty(old('level_estimasi', optional($data->indikator)->level_estimasi)) ? 'selected' : ''}}>Nasional</option>
                                        <option value="provinsi" {{old('level_estimasi', optional($data->indikator)->level_estimasi) == 'provinsi' ? 'selected' : ''}}>Provinsi</option>
                                        <option value="kabupaten" {{old('level_estimasi', optional($data->indikator)->level_estimasi) == 'kabupaten' ? 'selected' : ''}}>Kabupaten/kota</option>
                                        <option value="perangkat_daerah" {{old('level_estimasi', optional($data->indikator)->level_estimasi) == 'perangkat_daerah' ? 'selected' : ''}}>Perangkat Daerah</option>
                                        <option value="kecamatan" {{old('level_estimasi', optional($data->indikator)->level_estimasi) == 'kecamatan' ? 'selected' : ''}}>Kecamatan</option>
                                        <option value="kelurahan" {{old('level_estimasi', optional($data->indikator)->level_estimasi) == 'kelurahan' ? 'selected' : ''}}>Desa/Kelurahan</option>
                                        <option value="rt" {{old('level_estimasi', optional($data->indikator)->level_estimasi) == 'rt' ? 'selected' : ''}}>Rumah Tangga</option>
                                        <option value="individu" {{old('level_estimasi', optional($data->indikator)->level_estimasi) == 'individu' ? 'selected' : ''}}>Individu</option>
                                    </select>
                                    @if (isset($level_estimasi) && !empty($level_estimasi->comment))
                                        <p class="text-muted text-comment">Komentar: {{$level_estimasi->comment}}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="umum1" class="col-sm-2 col-form-label">Apakah kolom ini dapat diakses umum</label>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input {{ isset($umum) ? ($umum->accepted ? 'is-valid' : 'is-invalid') : '' }}" type="radio" name="umum" id="umum1"
                                               value="1" {{old('umum', optional($data->indikator)->umum) == 1 || empty(old('umum', optional($data->indikator)->umum)) ? 'checked' : ''}} disabled>
                                        <label class="form-check-label" for="umum1">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input {{ isset($umum) ? ($umum->accepted ? 'is-valid' : 'is-invalid') : '' }}" type="radio" name="umum" id="umum2" value="0" {{old('umum', optional($data->indikator)->umum) == 0 ? 'checked' : ''}} disabled>
                                        <label class="form-check-label" for="umum2">
                                            Tidak
                                        </label>
                                    </div>
                                    @if (isset($umum) && !empty($umum->comment))
                                        <p class="text-muted text-comment">Komentar: {{$umum->comment}}</p>
                                    @endif
                                </div>
                            </div>

                            @if(auth()->user()->hasAnyRole('produsen'))
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            @endif
                            {{-- <a href="{{url()->previous('d_' . (auth()->user()->hasAnyRole('produsen') ? 'produsen' : 'walidata'))}}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a> --}}
                            <a href="{{auth()->user()->hasAnyRole('produsen') ? '/data_produsen/pengumpulan' : '/data_walidata/pengumpulan'}}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>

                            {{-- <a href="/data_produsen/pengumpulan" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a> --}}

                        </form><!-- End General Form Elements -->

                    </div>
                </div>

            </div>


        </div>
    </section>
@endsection

@push('js')
    <script>
        $(function () {
            $('section.no-komposit-section').hide();
            $('section.komposit-section').hide();
            $('input[name="komposit"]').change(function () {
                if ($('input[name="komposit"]:checked').val() == 1) {
                    $('section.komposit-section').show();
                    $('section.no-komposit-section').hide();
                } else {
                    $('section.komposit-section').hide();
                    $('section.no-komposit-section').show();
                }
            }).trigger('change');
        })
    </script>
@endpush
