@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')
<div class="pagetitle">
    <h1>Tambah Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Data</li>
            <li class="breadcrumb-item active">Tambah Data Dari E-walidata</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Data</h5>

                        <!-- General Form Elements -->
                        <form @if(Auth::user()->role_id == '1')
                            action="/data_administrator/store"
                            @elseif(Auth::user()->role_id == '2')
                            action="/data_walidata/store"
                            @elseif(Auth::user()->role_id == '3')
                            action="/data_produsen/store"
                            @endif
                            method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nama Data</label>
                                <div class="col-sm-10">
                                    <input id="nama_data" name="nama_data" type="text" value="{{$uraian_indikator}}"
                                        class="form-control" required>
                                </div>
                            </div>
                            <input id="kodeindikator" name="kodeindikator" type="text" value="{{$kodeindikator}}"
                                class="form-control" hidden>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Jenis Data</label>
                                <div class="col-sm-10">
                                    <select id="jenis_data" name="jenis_data" class="form-select"
                                        aria-label="Default select example" required>
                                        <option value="" disabled hidden>Pilih</option>
                                        <option value="Indikator" selected>Indikator</option>
                                        <option value="Variabel">Variabel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Produsen Data (PIC)</label>
                                <div class="col-sm-10">
                                    <select id="opd_id" style="width: 100%" name="opd_id" class="form-select select2"
                                        aria-label="Default select example" required>
                                        <option value="" disabled selected hidden>Pilih</option>
                                        @foreach($opd as $dt)
                                        <option value="{{ $dt->id }}">{{ $dt->nama_opd }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Sumber Referensi</label>
                                <div class="col-sm-10">

                                    <select id="sumber_data" style="width: 100%" name="sumber_data"
                                        class="form-select select2" aria-label="Default select example" required>
                                        <option value="" disabled selected hidden>Pilih</option>
                                        @foreach( $sumber as $sd)
                                        <option value="{{ $sd->sumber_data }}" {{$sd->sumber_data == "Data Provinsi" ?
                                            "selected" : ""}}>{{ $sd->sumber_data }}</option>
                                        @endforeach

                                        {{-- <option value="RPJMD">RPJMD</option>
                                        <option value="SPM">SPM</option>
                                        <option value="SDGs">SDGs</option>
                                        <option value="Data Provinsi">Data Provinsi</option> --}}
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Tahun</label>
                                <div class="col-sm-10">

                                    <select id="tahun" style="width: 100%" name="tahun" class="form-select select2"
                                        aria-label="Default select example" required>
                                        <option value="" disabled selected hidden>Pilih</option>
                                        @foreach( $tahun as $th)
                                        <option value="{{ $th->tahun }}" {{$th->tahun == $tahun_sipd ?
                                            "selected" : ""}}>{{ $th->tahun }}</option>
                                        @endforeach

                                        {{-- <option value="RPJMD">RPJMD</option>
                                        <option value="SPM">SPM</option>
                                        <option value="SDGs">SDGs</option>
                                        <option value="Data Provinsi">Data Provinsi</option> --}}
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="jadwal_rilis" class="col-sm-2 col-form-label">Jadwal Rilis</label>
                                <div class="col-sm-10">
                                    <input id="jadwal_rilis" name="jadwal_rilis" type="date" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Jadwal Pemutakhiran</label>
                                <div class="col-sm-10">

                                    <select id="jadwal_pemutakhiran" style="width: 100%" name="jadwal_pemutakhiran"
                                        class="form-select select2" aria-label="Default select example" required>
                                        <option value="" disabled hidden>Pilih</option>
                                        <option value="Harian">Harian</option>
                                        <option value="Mingguan">Mingguan</option>
                                        <option value="Bulanan">Bulanan</option>
                                        <option value="Tahunan" selected>Tahunan</option>
                                        <option value="Triwulanan">Triwulanan</option>
                                        <option value="Semesteran">Semesteran</option>
                                        <option value="Empat Tahunan">Empat Tahunan</option>
                                        <option value=">Dua Tahunan">>Dua Tahunan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="data_prioritas" class="col-sm-2 col-form-label">Data Prioritas</label>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="data_prioritas"
                                            id="data_prioritas1" value="1">
                                        <label class="form-check-label" for="data_prioritas">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="data_prioritas"
                                            id="data_prioritas0" value="0">
                                        <label class="form-check-label" for="data_prioritas0">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i>
                                        SIMPAN</button>
                                </div>
                            </div>

                            <a href="{{url()->previous('d_' . auth()->user()->role->name)}}"
                                class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>


                        </form><!-- End General Form Elements -->

                    </div>
                </div>
            </div><!-- End Bordered Tabs Justified -->



        </div>


    </div>
</section>
{{-- <td>
    @if ($dt->status_id == 1)
    [perencanaan]Setuju
    @elseif($dt->status_id == 8 | $dt->status_id == 9)
    [pemeriksaan data]Telah sesuai
    @endif
</td> --}}

{{-- @push('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
            $('#opd_id').select2()
            $('#sumber_data').select2()
            $('#tahun').select2()
            $('#jadwal_pemutakhiran').select2()
            $('#get_data').select2()
        });
</script>
@endpush --}}
@push('js')
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>

</script>
@endpush

@endsection