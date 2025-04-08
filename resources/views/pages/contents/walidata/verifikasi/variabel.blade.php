@extends('pages.main.layout')

@php
    $variables = [
        'nama',
        'alias',
        'definisi',
        'konsep',
        'referensi_pemilihan',
        'referensi_waktu',
        'tipe_data',
        'klasifikasi_isian',
        'ukuran',
        'satuan',
        'aturan_validasi',
        'kalimat_pertanyaan',
        'umum',
    ];
    foreach ($variables as $var) {
        $$var = $data->verifikasi->firstWhere('field', $var);
    }
@endphp

@section('content')
    <div class="pagetitle">
        <h1>Verifikasi Metadata Variabel</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Verifikasi Data</a></li>
                <li class="breadcrumb-item">{{ $data->nama_data }}</li>
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

                        <form action="{{ route('simpan-variabel', $data->id) }}" method="POST">
                            @csrf
                            <div class="row mb-3 align-items-center">
                                <label for="nama" class="col-sm-2 col-form-label">Nama Variabel</label>
                                <div class="col-sm-8">
                                    <div class="input-group has-validation">
                                        <input id="nama" name="nama" type="text"
                                            class="form-control {{ $nama ? ($nama->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                            placeholder="Nama Variabel"
                                            value="{{ old('nama', optional($data->variabel)->nama ?? $data->nama_data) }}"
                                            disabled>
                                    </div>
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

                            <div class="row mb-3 align-items-center">
                                <label for="alias" class="col-sm-2 col-form-label">Alias</label>
                                <div class="col-sm-8">
                                    <input id="alias" name="alias" type="text"
                                        class="form-control {{ $alias ? ($alias->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="Alias" value="{{ old('alias', optional($data->variabel)->alias) }}"
                                        disabled>
                                </div>
                                <div class="col-sm-2">
                                    <div class="btn-group-sm">
                                        <button
                                            class="btn btn-actions btn-accept btn-sm {{ $alias && $alias->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                            data-name="alias">Setuju <i class="bi bi-check"></i></button>
                                        <button
                                            class="btn btn-actions btn-reject btn-sm {{ $alias && !$alias->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                            data-name="alias">Revisi <i class="bi bi-x"></i></button>
                                        {{-- <button class="btn btn-comment btn-sm btn-outline-primary" data-name="alias"><i
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
                            </div>

                            <div class="row mb-3">
                                <label for="definisi" class="col-sm-2 col-form-label">Definisi</label>
                                <div class="col-sm-8">
                                    <textarea id="definisi" name="definisi" type="text"
                                        class="form-control {{ $definisi ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        style="height: 100px" spellcheck="false" placeholder="Definisi" disabled>{{ old('definisi', optional($data->variabel)->definisi ?? optional($data->standar)->definisi) }}</textarea>
                                </div>
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
                            </div>

                            <div class="row mb-3">
                                <label for="referensi_pemilihan" class="col-sm-2 col-form-label">Referensi Pemilihan</label>
                                <div class="col-sm-8">
                                    <input id="referensi_pemilihan" name="referensi_pemilihan" type="text"
                                        class="form-control {{ $referensi_pemilihan ? ($referensi_pemilihan->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="Referensi Pemilihan"
                                        value="{{ old('referensi_pemilihan', optional($data->variabel)->referensi_pemilihan) }}"
                                        disabled>
                                </div>
                                <div class="col-sm-2">
                                    <div class="btn-group-sm">
                                        <button
                                            class="btn btn-actions btn-accept btn-sm {{ $referensi_pemilihan && $referensi_pemilihan->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                            data-name="referensi_pemilihan">Setuju <i class="bi bi-check"></i></button>
                                        <button
                                            class="btn btn-actions btn-reject btn-sm {{ $referensi_pemilihan && !$referensi_pemilihan->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                            data-name="referensi_pemilihan">Revisi <i class="bi bi-x"></i></button>
                                        {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="referensi_pemilihan"><i class="bi bi-chat-dots"></i>
                                        Komentar</button> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="referensi_waktu" class="col-sm-2 col-form-label">Referensi Waktu</label>
                                <div class="col-sm-8">
                                    <input id="referensi_waktu" name="referensi_waktu" type="text"
                                        class="form-control {{ $referensi_waktu ? ($referensi_waktu->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="Referensi Waktu"
                                        value="{{ old('referensi_waktu', optional($data->variabel)->referensi_waktu) }}"
                                        disabled>
                                </div>
                                <div class="col-sm-2">
                                    <div class="btn-group-sm">
                                        <button
                                            class="btn btn-actions btn-accept btn-sm {{ $referensi_waktu && $referensi_waktu->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                            data-name="referensi_waktu">Setuju <i class="bi bi-check"></i></button>
                                        <button
                                            class="btn btn-actions btn-reject btn-sm {{ $referensi_waktu && !$referensi_waktu->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                            data-name="referensi_waktu">Revisi <i class="bi bi-x"></i></button>
                                        {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="referensi_waktu"><i class="bi bi-chat-dots"></i> Komentar</button>
                                    --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="tipe_data" class="col-sm-2 col-form-label">Tipe Data</label>
                                <div class="col-sm-8">
                                    <select
                                        class="form-control {{ $tipe_data ? ($tipe_data->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        name="tipe_data" id="tipe_data" disabled>
                                        <option value="integer"
                                            {{ old('tipe_data', optional($data->variabel)->tipe_data) == 'integer' ||
                                            empty(optional($data->variabel)->tipe_data)
                                                ? 'selected'
                                                : '' }}>
                                            Integer</option>
                                        <option value="float"
                                            {{ old('tipe_data', optional($data->variabel)->tipe_data) == 'float' ? 'selected' : '' }}>
                                            Float</option>
                                        <option value="char"
                                            {{ old('tipe_data', optional($data->variabel)->tipe_data) == 'char' ? 'selected' : '' }}>
                                            Char</option>
                                        <option value="string"
                                            {{ old('tipe_data', optional($data->variabel)->tipe_data) == 'string' ? 'selected' : '' }}>
                                            String</option>
                                        <option value="array"
                                            {{ old('tipe_data', optional($data->variabel)->tipe_data) == 'array' ? 'selected' : '' }}>
                                            Array</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <div class="btn-group-sm">
                                        <button
                                            class="btn btn-actions btn-accept btn-sm {{ $tipe_data && $tipe_data->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                            data-name="tipe_data">Setuju <i class="bi bi-check"></i></button>
                                        <button
                                            class="btn btn-actions btn-reject btn-sm {{ $tipe_data && !$tipe_data->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                            data-name="tipe_data">Revisi <i class="bi bi-x"></i></button>
                                        {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="tipe_data"><i class="bi bi-chat-dots"></i> Komentar</button> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                                <div class="col-sm-8">
                                    <input id="ukuran" name="ukuran" type="text"
                                        class="form-control {{ $ukuran ? ($ukuran->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="Ukuran"
                                        value="{{ old('ukuran', optional($data->variabel)->ukuran ?? optional($data->standar)->ukuran) }}"
                                        disabled>
                                </div>
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
                            </div>

                            <div class="row mb-3">
                                <label for="klasifikasi_isian" class="col-sm-2 col-form-label">Klasifikasi Isian</label>
                                <div class="col-sm-8">
                                    <textarea name="klasifikasi_isian"
                                        class="form-control {{ $klasifikasi_isian ? ($klasifikasi_isian->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        style="height: 100px" spellcheck="false" placeholder="Klasifikasi Isian" disabled>{{ old('klasifikasi_isian', optional($data->variabel)->klasifikasi_isian ?? optional($data->standar)->klasifikasi) }}</textarea>
                                </div>
                                <div class="col-sm-2">
                                    <div class="btn-group-sm">
                                        <button
                                            class="btn btn-actions btn-accept btn-sm {{ $klasifikasi_isian && $klasifikasi_isian->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                            data-name="klasifikasi_isian">Setuju <i class="bi bi-check"></i></button>
                                        <button
                                            class="btn btn-actions btn-reject btn-sm {{ $klasifikasi_isian && !$klasifikasi_isian->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                            data-name="klasifikasi_isian">Revisi <i class="bi bi-x"></i></button>
                                        {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="klasifikasi_isian"><i class="bi bi-chat-dots"></i> Komentar</button>
                                    --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="aturan_validasi" class="col-sm-2 col-form-label">Aturan Validasi</label>
                                <div class="col-sm-8">
                                    <textarea name="aturan_validasi"
                                        class="form-control {{ $aturan_validasi ? ($aturan_validasi->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        style="height: 100px" spellcheck="false" placeholder="Aturan Validasi" disabled>{{ old('aturan_validasi', optional($data->variabel)->aturan_validasi) }}</textarea>
                                </div>
                                <div class="col-sm-2">
                                    <div class="btn-group-sm">
                                        <button
                                            class="btn btn-actions btn-accept btn-sm {{ $aturan_validasi && $aturan_validasi->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                            data-name="aturan_validasi">Setuju <i class="bi bi-check"></i></button>
                                        <button
                                            class="btn btn-actions btn-reject btn-sm {{ $aturan_validasi && !$aturan_validasi->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                            data-name="aturan_validasi">Revisi <i class="bi bi-x"></i></button>
                                        {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="aturan_validasi"><i class="bi bi-chat-dots"></i> Komentar</button>
                                    --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="kalimat_pertanyaan" class="col-sm-2 col-form-label">Kalimat Pertanyaan</label>
                                <div class="col-sm-8">
                                    <textarea name="kalimat_pertanyaan"
                                        class="form-control {{ $kalimat_pertanyaan ? ($kalimat_pertanyaan->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        style="height: 100px" spellcheck="false" placeholder="Kalimat Pertanyaan" disabled>{{ old('kalimat_pertanyaan', optional($data->variabel)->kalimat_pertanyaan) }}</textarea>
                                </div>
                                <div class="col-sm-2">
                                    <div class="btn-group-sm">
                                        <button
                                            class="btn btn-actions btn-accept btn-sm {{ $kalimat_pertanyaan && $kalimat_pertanyaan->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                            data-name="kalimat_pertanyaan">Setuju <i class="bi bi-check"></i></button>
                                        <button
                                            class="btn btn-actions btn-reject btn-sm {{ $kalimat_pertanyaan && !$kalimat_pertanyaan->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                            data-name="kalimat_pertanyaan">Revisi <i class="bi bi-x"></i></button>
                                        {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="kalimat_pertanyaan"><i class="bi bi-chat-dots"></i> Komentar</button>
                                    --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="umum1" class="col-sm-2 col-form-label">Apakah kolom ini dapat diakses
                                    umum</label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="umum" id="umum1"
                                            value="1"
                                            {{ old('umum', optional($data->variabel)->umum) == 1 || empty(old('umum', optional($data->variabel)->umum))
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
                                            {{ old('umum', optional($data->variabel)->umum) == 0 ? 'checked' : '' }}
                                            disabled>
                                        <label class="form-check-label" for="umum2">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
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
                                        category: 'variabel',
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
                                                        category: 'variabel'
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
                                category: 'variabel',
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
