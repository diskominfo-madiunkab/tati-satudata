@extends('pages.main.layout')

@section('content')

<div class="pagetitle">
    <h1>Rekap OPD</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Rekapitulasi OPD</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Rekapitulasi OPD</h5>

                    <div class="row mb-3 flex">
                        <div class="col-sm-6">
                            <select class="form-select" aria-label="Select OPD" id="selectOpd">
                                <option {{empty(request()->get('opd_id')) ? 'selected' : ''}} value="-1">Semua OPD
                                </option>
                                @foreach($opds as $opd)
                                <option value="{{$opd->id}}" {{request()->get('opd_id') == $opd->id ? 'selected' :
                                    ''}}>{{$opd->nama_opd}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select class="form-select" aria-label="Tahun" id="selectYear">
                                {{-- <option {{empty(request()->get('y')) ? 'selected' : ''}}
                                    value="{{date('Y')}}">Tahun {{date('Y')}}</option> --}}
                                <option value="" {{ empty(request()->get('y')) ? 'selected' : '' }}>Semua Tahun</option>
                                {{-- <option value="">Semua Tahun</option> --}}
                                @foreach($years as $year)
                                <option value="{{$year->tahun}}" {{request()->get('y') == $year->tahun ? 'selected' :
                                    ''}}>{{$year->tahun}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <a href="{{route('rekap_walidata_excel', request()->input())}}" class="btn btn-success"><i
                                    class="bi bi-file-excel"></i> Export</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <td>No.</td>
                                    <td>Nama OPD</td>
                                    <td>Draft</td>
                                    <td>Ditolak</td>
                                    <td>Proses Standar Data</td>
                                    <td>Proses Pengumpulan</td>
                                    {{-- <td>Telah Lengkap</td> --}}
                                    <td>Proses Verifikasi</td>
                                    <td>Revisi</td>
                                    <td>Siap Publikasi</td>
                                    <td>Terpublikasi</td>
                                    <td>Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($opds as $opd)
                                @php
                                $o = $opdData->where('opd_id', $opd->id);
                                // dd($o);
                                @endphp
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$opd->nama_opd}}</td>
                                    <td>{{$o->where('status_id', \App\Models\Data::STATUS_DRAFT)->sum('total')}}</td>
                                    <td>{{$o->where('status_id', \App\Models\Data::STATUS_TOLAK)->sum('total')}}</td>
                                    <td>{{$o->where('status_id', [\App\Models\Data::STATUS_PENGAJUAN_STANDART_DATA,
                                        \App\Models\Data::STATUS_SETUJU,
                                        \App\Models\Data::STATUS_REVISI_STANDART_DATA])->sum('total')}}</td>
                                    {{-- <td>{{$o->where('status_id', \App\Models\Data::STATUS_SETUJU)->sum('total')}}
                                    </td> --}}
                                    <td>{{$o->where('status_id',
                                        \App\Models\Data::STATUS_SETUJU_STANDART_DATA)->sum('total')}}
                                    </td>
                                    {{-- <td>{{$o->where('status_id',
                                        \App\Models\Data::STATUS_LENGKAP)->sum('total')}}
                                    </td> --}}
                                    <td>{{$o->where('status_id',
                                        \App\Models\Data::STATUS_PROSES_VERIFIKASI)->sum('total')}}
                                    </td>
                                    <td>{{$o->where('status_id', \App\Models\Data::STATUS_REVISI)->sum('total')}}</td>
                                    <td>{{$o->where('status_id',
                                        \App\Models\Data::STATUS_SIAP_PUBLIKASI)->sum('total')}}
                                    </td>
                                    <td>{{$o->where('status_id', \App\Models\Data::STATUS_TERPUBLIKASI)->sum('total')}}
                                    </td>
                                    <td>{{$o->where('status_id', \App\Models\Data::STATUS_DRAFT)
                                        ->sum('total') + $o->where('status_id', \App\Models\Data::STATUS_TOLAK)
                                        ->sum('total') + $o->where('status_id',
                                        \App\Models\Data::STATUS_SETUJU)
                                        ->sum('total') + $o->where('status_id',
                                        \App\Models\Data::STATUS_PROSES_VERIFIKASI)
                                        ->sum('total') + $o->where('status_id', \App\Models\Data::STATUS_REVISI)
                                        ->sum('total') + $o->where('status_id', \App\Models\Data::STATUS_SIAP_PUBLIKASI)
                                        ->sum('total') + $o->where('status_id', \App\Models\Data::STATUS_TERPUBLIKASI)
                                        // ->sum('total') + $o->where('status_id', \App\Models\Data::STATUS_SETUJU)
                                        // ->sum('total')+ $o->where('status_id', \App\Models\Data::STATUS_LENGKAP)
                                        ->sum('total')}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('js')
<script>
    $(function () {
            let search = new URLSearchParams(window.location.search);
            $('#selectOpd').change(function() {
                let val = $(this).val();
                search.set('opd_id', val);

                if (val == '-1') {
                    search.delete('opd_id');
                }

                window.location.search = search.toString();
            });

            $('#selectYear').change(function() {
                let val = $(this).val();
                search.set('y', val); // always set 'y' parameter
                
                if (val === '') {
                search.delete('y');
                }
                window.location.search = search.toString();
            });
        });
</script>
@endpush