@extends('pages.main.layout')

@section('title', 'Standar Data - ' . $data->nama_data)
@section('content')
    @include('sweetalert::alert')
    <div class="pagetitle">
        <h1>Standar Data</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/data_{{ auth()->user()->role->name }}/pengumpulan">Daftar Pengumpulan
                        Data</a></li>
                <li class="breadcrumb-item">Data - {{ $data->nama_data }}</li>
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
    @endphp
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Standar Data: <em>{{ $data->nama_data }}</em></h5>
                        {{-- <h5 class="card-title">Standar id: <em>{{$getdata->status_id}}</em></h5> --}}

                        <form method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="kode" class="col-sm-2 col-form-label">Kode Standar Data</label>
                                <div class="col-sm-8">
                                    <textarea id="kode" name="kode" readonly
                                        class="form-control {{ $kode ? ($kode->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="kode Standar Data">
@if (isset($getdata) && isset($getdata->standar))
{{ old('kode', optional($getdata->standar)->kode) }}@else{{ old('kode') }}
@endif
</textarea>
                                </div>
                                {{-- {{$kode->accepted}} --}}
                                @if ($data->status_id == '13')
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $kode && $kode->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="kode">Setuju <i class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $kode && !$kode->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="kode">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary" data-name="kode"><i
                                            class="bi bi-chat-dots"></i>
                                        Komentar</button> --}}
                                        </div>
                                    </div>
                                @endif

                            </div>
                            <div class="row mb-3">
                                <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                                <div class="col-sm-8">
                                    <textarea id="konsep" name="konsep" readonly
                                        class="form-control {{ $konsep ? ($konsep->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="Konsep Standar Data">
@if (isset($getdata) && isset($getdata->standar))
{{ old('konsep', optional($getdata->standar)->konsep) }}@else{{ old('konsep') }}
@endif
</textarea>
                                </div>
                                {{-- {{$konsep->accepted}} --}}
                                @if ($data->status_id == '13')
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
                                    <textarea id="definisi" name="definisi" readonly
                                        class="form-control {{ $definisi ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="Definisi Standar Data">
@if (isset($getdata) && isset($getdata->standar))
{{ old('definisi', optional($getdata->standar)->definisi) }}@else{{ old('definisi') }}
@endif
</textarea>
                                </div>
                                @if ($data->status_id == '13')
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
                                <label for="klasifikasi" class="col-sm-2 col-form-label">Klasifikasi</label>
                                <div class="col-sm-8">
                                    <textarea id="klasifikasi" name="klasifikasi" readonly
                                        class="form-control {{ $klasifikasi ? ($klasifikasi->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="Klasifikasi Standar Data">
@if (isset($getdata) && isset($getdata->standar))
{{ old('klasifikasi', optional($getdata->standar)->klasifikasi) }}@else{{ old('klasifikasi') }}
@endif
</textarea>
                                </div>
                                @if ($data->status_id == '13')
                                    <div class="col-sm-2">
                                        <div class="btn-group-sm">
                                            <button
                                                class="btn btn-actions btn-accept btn-sm {{ $klasifikasi && $klasifikasi->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                data-name="klasifikasi">Setuju <i class="bi bi-check"></i></button>
                                            <button
                                                class="btn btn-actions btn-reject btn-sm {{ $klasifikasi && !$klasifikasi->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-name="klasifikasi">Revisi <i class="bi bi-x"></i></button>
                                            {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                        data-name="klasifikasi"><i class="bi bi-chat-dots"></i> Komentar</button> --}}
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="row mb-3">
                                <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                                <div class="col-sm-8">
                                    <textarea id="ukuran" name="ukuran" readonly
                                        class="form-control {{ $ukuran ? ($ukuran->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="Ukuran Standar Data">
@if (isset($getdata) && isset($getdata->standar))
{{ old('ukuran', optional($getdata->standar)->ukuran) }}@else{{ old('ukuran') }}
@endif
</textarea>
                                </div>
                                @if ($data->status_id == '13')
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
                                    <textarea id="satuan" name="satuan" readonly
                                        class="form-control {{ $satuan ? ($satuan->accepted ? 'is-valid' : 'is-invalid') : '' }} bg-light"
                                        placeholder="Satuan Standar Data">
@if (isset($getdata) && isset($getdata->standar))
{{ old('satuan', optional($getdata->standar)->satuan) }}@else{{ old('satuan') }}
@endif
</textarea>
                                </div>
                                @if ($data->status_id == '13')
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



                            @if (auth()->user()->hasAnyRole('produsen'))
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-8">
                                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                                    </div>
                                </div>
                            @endif

                            <a {{-- href="{{auth()->user()->hasAnyRole('produsen') ? '/data_produsen/standar-data' : '/data_walidata/standar-data'}}" --}} href="{{ url()->previous() }}" class="btn btn-outline-secondary"><i
                                    class="bi bi-arrow-left"></i> Kembali</a>

                        </form>
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
                                    url: '{{ route('walidata.standar-data.verifikasi.verify', $data->id) }}',
                                    method: 'PATCH',
                                    data: {
                                        category: 'standar',
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
                                                    '{{ route('walidata.standar-data.verifikasi.komentar', $data->id) }}', {
                                                        field: $(this).data('name'),
                                                        comment: comment,
                                                        category: 'standar'
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
                            url: '{{ route('walidata.standar-data.verifikasi.verify', $data->id) }}',
                            method: 'PATCH',
                            data: {
                                category: 'standar',
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
