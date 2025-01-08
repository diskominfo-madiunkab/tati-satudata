@extends('pages.main.layout')

@php
    if ($data->relationLoaded('verifikasi')) {
        $variables = ['nama', 'alias', 'definisi', 'konsep', 'referensi_pemilihan', 'referensi_waktu', 'tipe_data', 'klasifikasi_isian', 'ukuran', 'satuan', 'aturan_validasi', 'kalimat_pertanyaan', 'umum'];
        foreach ($variables as $var) {
            $$var = $data->verifikasi->firstWhere('field', $var);
        }
    }
@endphp

@section('content')

    <div class="pagetitle">
        <h1>Verifikasi Metadata Variabel</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">{{$data->nama_data}}</li>
                <li class="breadcrumb-item active">Metadata Variabel</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Metadata Variabel</h5>

                        <div class="row mb-3 align-items-center">
                            <label for="nama" class="col-sm-2 col-form-label">Nama Variabel</label>
                            <div class="col-sm-10">
                                <div class="input-group has-validation">
                                    <input id="nama" name="nama" type="text" class="form-control {{ $nama ? ($nama->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" placeholder="Nama Variabel" value="{{old('nama', optional($data->variabel)->nama ?? $data->nama_data)}}" disabled>
                                    @if (isset($nama) && !empty($nama->comment))
                                        <p class="text-muted text-comment">Komentar: {{$nama->comment}}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="alias" class="col-sm-2 col-form-label">Alias</label>
                            <div class="col-sm-10">
                                <input id="alias" name="alias" type="text" class="form-control {{ $alias ? ($alias->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" placeholder="Alias" value="{{old('alias', optional($data->variabel)->alias)}}" disabled>
                                @if (isset($alias) && !empty($alias->comment))
                                    <p class="text-muted text-comment">Komentar: {{$alias->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                            <div class="col-sm-10">
                                <textarea name="konsep" class="form-control {{ $konsep ? ($konsep->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" style="height: 100px" spellcheck="false" placeholder="Konsep" disabled>{{old('konsep', optional($data->variabel)->konsep ?? optional($data->standar)->konsep)}}</textarea>
                                @if (isset($konsep) && !empty($konsep->comment))
                                    <p class="text-muted text-comment">Komentar: {{$konsep->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="definisi" class="col-sm-2 col-form-label">Definisi</label>
                            <div class="col-sm-10">
                                <textarea id="definisi" name="definisi" type="text" class="form-control {{ $definisi ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" style="height: 100px" spellcheck="false" placeholder="Definisi" disabled>{{old('definisi', optional($data->variabel)->definisi ?? optional($data->standar)->definisi)}}</textarea>
                                @if (isset($definisi) && !empty($definisi->comment))
                                    <p class="text-muted text-comment">Komentar: {{$definisi->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="referensi_pemilihan" class="col-sm-2 col-form-label">Referensi Pemilihan</label>
                            <div class="col-sm-10">
                                <input id="referensi_pemilihan" name="referensi_pemilihan" type="text" class="form-control {{ $referensi_pemilihan ? ($referensi_pemilihan->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" placeholder="Referensi Pemilihan" value="{{old('referensi_pemilihan', optional($data->variabel)->referensi_pemilihan)}}" disabled>
                                @if (isset($referensi_pemilihan) && !empty($referensi_pemilihan->comment))
                                    <p class="text-muted text-comment">Komentar: {{$referensi_pemilihan->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="referensi_waktu" class="col-sm-2 col-form-label">Referensi Waktu</label>
                            <div class="col-sm-10">
                                <input id="referensi_waktu" name="referensi_waktu" type="text" class="form-control {{ $referensi_waktu ? ($referensi_waktu->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" placeholder="Referensi Waktu" value="{{old('referensi_waktu', optional($data->variabel)->referensi_waktu)}}" disabled>
                                @if (isset($referensi_waktu) && !empty($referensi_waktu->comment))
                                    <p class="text-muted text-comment">Komentar: {{$referensi_waktu->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tipe_data" class="col-sm-2 col-form-label">Tipe Data</label>
                            <div class="col-sm-8">
                                <select class="form-control {{ $tipe_data ? ($tipe_data->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" name="tipe_data" id="tipe_data" disabled>
                                    <option value="integer" {{old('tipe_data', optional($data->variabel)->tipe_data) == 'integer' || empty(optional($data->variabel)->tipe_data) ? 'selected' : ''}}>Integer</option>
                                    <option value="float" {{old('tipe_data', optional($data->variabel)->tipe_data) == 'float' ? 'selected' : ''}}>Float</option>
                                    <option value="char" {{old('tipe_data', optional($data->variabel)->tipe_data) == 'char' ? 'selected' : ''}}>Char</option>
                                    <option value="string" {{old('tipe_data', optional($data->variabel)->tipe_data) == 'string' ? 'selected' : ''}}>String</option>
                                    <option value="array" {{old('tipe_data', optional($data->variabel)->tipe_data) == 'array' ? 'selected' : ''}}>Array</option>
                                </select>

                                @if (isset($tipe_data) && !empty($tipe_data->comment))
                                    <p class="text-muted text-comment">Komentar: {{$tipe_data->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                            <div class="col-sm-10">
                                <input id="ukuran" name="ukuran" type="text" class="form-control {{ $ukuran ? ($ukuran->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" placeholder="Ukuran" value="{{old('ukuran', optional($data->variabel)->ukuran ?? optional($data->standar)->ukuran)}}" disabled>
                            </div>
                            @if (isset($ukuran) && !empty($ukuran->comment))
                                <p class="text-muted text-comment">Komentar: {{$ukuran->comment}}</p>
                            @endif
                        </div>

                        <div class="row mb-3">
                            <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                            <div class="col-sm-10">
                                <input id="satuan" name="satuan" type="text" class="form-control {{ $satuan ? ($satuan->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" placeholder="Satuan" value="{{old('satuan', optional($data->variabel)->satuan ?? optional($data->standar)->satuan)}}" disabled>
                            </div>
                            @if (isset($satuan) && !empty($satuan->comment))
                                <p class="text-muted text-comment">Komentar: {{$satuan->comment}}</p>
                            @endif
                        </div>

                        <div class="row mb-3">
                            <label for="klasifikasi_isian" class="col-sm-2 col-form-label">Klasifikasi Isian</label>
                            <div class="col-sm-10">
                                <textarea name="klasifikasi_isian" class="form-control {{ $klasifikasi_isian ? ($klasifikasi_isian->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" style="height: 100px" spellcheck="false" placeholder="Klasifikasi Isian" disabled>{{old('klasifikasi_isian', optional($data->variabel)->klasifikasi_isian ?? optional($data->standar)->klasifikasi)}}</textarea>
                                @if (isset($klasifikasi_isian) && !empty($klasifikasi_isian->comment))
                                    <p class="text-muted text-comment">Komentar: {{$klasifikasi_isian->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="aturan_validasi" class="col-sm-2 col-form-label">Aturan Validasi</label>
                            <div class="col-sm-10">
                                <textarea name="aturan_validasi" class="form-control {{ $aturan_validasi ? ($aturan_validasi->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" style="height: 100px" spellcheck="false" placeholder="Aturan Validasi" disabled>{{old('aturan_validasi', optional($data->variabel)->aturan_validasi)}}</textarea>
                                @if (isset($aturan_validasi) && !empty($aturan_validasi->comment))
                                    <p class="text-muted text-comment">Komentar: {{$aturan_validasi->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="kalimat_pertanyaan" class="col-sm-2 col-form-label">Kalimat Pertanyaan</label>
                            <div class="col-sm-10">
                                <textarea name="kalimat_pertanyaan" class="form-control {{ $kalimat_pertanyaan ? ($kalimat_pertanyaan->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light" style="height: 100px" spellcheck="false" placeholder="Kalimat Pertanyaan" disabled>{{old('kalimat_pertanyaan', optional($data->variabel)->kalimat_pertanyaan)}}</textarea>

                                @if (isset($kalimat_pertanyaan) && !empty($kalimat_pertanyaan->comment))
                                    <p class="text-muted text-comment">Komentar: {{$kalimat_pertanyaan->comment}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="umum1" class="col-sm-2 col-form-label">Apakah kolom ini dapat diakses umum</label>
                            <div class="col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="umum" id="umum1"
                                           value="1" {{old('umum', optional($data->variabel)->umum) == 1 || empty(old('umum', optional($data->variabel)->umum)) ? 'checked' : ''}} disabled>
                                    <label class="form-check-label" for="umum1">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="umum" id="umum2" value="0" {{old('umum', optional($data->variabel)->umum) == 0 ? 'checked' : ''}} disabled>
                                    <label class="form-check-label" for="umum2">
                                        Tidak
                                    </label>
                                </div>

                                @if (isset($umum) && !empty($umum->comment))
                                    <p class="text-muted text-comment">Komentar: {{$umum->comment}}</p>
                                @endif
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
                        category: 'variabel',
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
                $.get('{{route('verifikasi.get-komentar', $data->id)}}', {field: fieldName, category: 'variabel'})
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
                                return $.post('{{route('verifikasi.komentar', $data->id)}}', {field: fieldName, comment: comment, category: 'variabel'})
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
