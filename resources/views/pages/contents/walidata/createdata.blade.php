@extends('pages.main.layout')
@section('content')
    @include('sweetalert::alert')
    <div class="pagetitle">
        <h1>Tambah Data</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item">Daftar Data</li>
                <li class="breadcrumb-item active">Tambah Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Bordered Tabs Justified -->
                <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                    <li class="nav-item flex-fill" role="presentation">
                        <button class="nav-link w-100 active" id="profile-tab" data-bs-toggle="tab"
                            data-bs-target="#bordered-justified-profile" type="button" role="tab"
                            aria-controls="profile" aria-selected="false">Tambah Data Dari Tahun Sebelumnya</button>
                    </li>
                    <li class="nav-item flex-fill" role="presentation">
                        <button class="nav-link w-100" id="home-tab" data-bs-toggle="tab"
                            data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home"
                            aria-selected="true">Tambah Data Baru</button>
                    </li>
                    <li class="nav-item flex-fill" role="presentation">
                        <button class="nav-link w-100" id="sipd-tab" data-bs-toggle="tab"
                            data-bs-target="#bordered-justified-sipd" type="button" role="tab" aria-controls="sipd"
                            aria-selected="true">E-Walidata</button>
                    </li>

                </ul>
                <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                    <div class="tab-pane fade" id="bordered-justified-home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Tambah Data</h5>

                                <!-- General Form Elements -->
                                <form
                                    @if (Auth::user()->role_id == '1') action="/data_administrator/store"
                                @elseif(Auth::user()->role_id == '2')
                                action="/data_walidata/store"
                                @elseif(Auth::user()->role_id == '3')
                                action="/data_produsen/store" @endif
                                    method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Nama Data</label>
                                        <div class="col-sm-10">
                                            <input id="nama_data" name="nama_data" type="text" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Jenis Data</label>
                                        <div class="col-sm-10">
                                            <select id="jenis_data" name="jenis_data" class="form-select"
                                                aria-label="Default select example" required>
                                                <option value="" disabled selected hidden>Pilih</option>
                                                <option value="Indikator">Indikator</option>
                                                <option value="Variabel">Variabel</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Produsen Data (PIC)</label>
                                        <div class="col-sm-10">
                                            <select id="opd_id" style="width: 100%" name="opd_id"
                                                class="form-select select2" aria-label="Default select example" required>
                                                <option value="" disabled selected hidden>Pilih</option>
                                                @foreach ($opd as $dt)
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
                                                @foreach ($sumber as $sd)
                                                    <option value="{{ $sd->sumber_data }}">{{ $sd->sumber_data }}</option>
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

                                            <select id="tahun" style="width: 100%" name="tahun"
                                                class="form-select select2" aria-label="Default select example" required>
                                                <option value="" disabled selected hidden>Pilih</option>
                                                @foreach ($tahun as $th)
                                                    <option value="{{ $th->tahun }}">{{ $th->tahun }}</option>
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
                                            <input id="jadwal_rilis" name="jadwal_rilis" type="date"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Jadwal Pemutakhiran</label>
                                        <div class="col-sm-10">

                                            <select id="jadwal_pemutakhiran" style="width: 100%"
                                                name="jadwal_pemutakhiran" class="form-select select2"
                                                aria-label="Default select example" required>
                                                <option value="" disabled selected hidden>Pilih</option>
                                                <option value="Harian">Harian</option>
                                                <option value="Mingguan">Mingguan</option>
                                                <option value="Bulanan">Bulanan</option>
                                                <option value="Tahunan">Tahunan</option>
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

                                    <a href="{{ url()->previous('d_' . auth()->user()->role->name) }}"
                                        class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>


                                </form><!-- End General Form Elements -->

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show active" id="bordered-justified-profile" role="tabpanel"
                        aria-labelledby="profile-tab">
                        <div class="card">

                            <div class="card-body">
                                <h5 class="card-title">Filter Data</h5>

                                <div class="row mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select id="tahun_aktif" name="tahun" class="form-select select2"
                                                aria-label="Default select example">
                                                </option>
                                                <option value="">Semua Tahun</option>
                                                @foreach ($tahun as $th)
                                                    <option value="{{ $th->tahun }}">{{ $th->tahun }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <select id="opd" name="opd" class="form-select select2"
                                                aria-label="Default select example">
                                                <option value="" disabled selected hidden>Pilih OPD</option>
                                                <option value="">Semua OPD</option>
                                                @foreach ($opd as $op)
                                                    <option value="{{ $op->id }}">{{ $op->nama_opd }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row mb-3">
                                <div class="col-md-12">
                                    <button type="button" style="width: 100%" class="btn btn-block btn-primary"
                                        id='btnFilter'><i class="fas fa-filter"></i>
                                        Tampilkan
                                    </button>
                                </div>
                            </div> --}}
                            </div>
                        </div>
                        <div class="card">

                            <div class="card-body">
                                <br>
                                <table id="table" class="table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="head-cb"></th>
                                            {{-- <th scope="col">#</th> --}}
                                            <th scope="col">Nama Data</th>
                                            <th scope="col">Produsen (PIC)</th>
                                            <th scope="col">Jenis</th>
                                            {{-- <th scope="col">Status Data</th> --}}
                                            <th scope="col">Sumber</th>
                                            <th scope="col">Tahun</th>
                                        </tr>
                                    </thead>
                                    <tbody id="isiTable">

                                    </tbody>
                                </table>

                                <button type="button" id="btn-simpan" disabled onclick="simpanDataTerpilih()"
                                    class="btn btn-md btn-primary mb-3 float-right">
                                    <i class="bi bi-save"></i>
                                    <span>Simpan</span>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="bordered-justified-sipd" role="tabpanel" aria-labelledby="sipd-tab">

                        <div class="card">

                            <div class="card-body">
                                <br>
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Kode Indikator</th>
                                            <th scope="col">Uraian Indikator</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Bidang Urusan</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sipd as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item['kodeindikator'] }}</td>
                                                <td>{{ $item['uraian_indikator'] }}</td>
                                                <td>{{ $item['satuan'] }}</td>
                                                <td>{{ $item['bidangurusan'] }}</td>
                                                <td><a href="{{ route('data_walidata.fetch.sipd', ['kodeindikator' => $item['kodeindikator'], 'uraian_indikator' => $item['uraian_indikator']]) }}"
                                                        class="btn btn-primary">
                                                        <i class="bi bi-bookmarks"></i> Add
                                                    </a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

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
            $(document).ready(function() {
                $('.select2').select2();
            });
        </script>
        <script>
            var selectedIds = [];
            $(document).ready(function() {
                var table = $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('index_data.walidata') }}',
                        type: 'GET',
                        data: function(d) {
                            d.tahun = $('#tahun_aktif').val();
                            d.opd = $('#opd').val();
                        }
                    },
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                var checked = selectedIds.includes(data.id) ? 'checked' : '';
                                return '<input type="checkbox" class="head-bd" value="' + data.id +
                                    '" ' + checked + '>';
                            }
                        },
                        {
                            data: 'nama_data',
                            name: 'nama_data'
                        },
                        {
                            data: 'nama_opd',
                            name: 'nama_opd'
                        },
                        {
                            data: 'jenis_data',
                            name: 'jenis_data'
                        },
                        {
                            data: 'sumber_data',
                            name: 'sumber_data'
                        },
                        {
                            data: 'tahun',
                            name: 'tahun'
                        }
                    ],
                    drawCallback: function() {
                        // Setelah DataTables memuat ulang data, set ulang status checkbox
                        $('.head-bd').each(function() {
                            if (selectedIds.includes($(this).val())) {
                                $(this).prop('checked', true);
                            }
                        });
                    }
                });

                $('#tahun_aktif, #opd').on('change', function() {
                    table.ajax.reload();
                });

                $("#head-cb").on('click', function() {
                    var isChecked = $("#head-cb").prop('checked');
                    $(".head-bd").prop('checked', isChecked);
                    if (isChecked) {
                        $(".head-bd").each(function() {
                            var value = $(this).val();
                            if (!selectedIds.includes(value)) {
                                selectedIds.push(value);
                            }
                        });
                    } else {
                        selectedIds = [];
                    }
                    $("#btn-simpan").prop('disabled', !isChecked);
                });

                $("#table tbody").on('click', '.head-bd', function() {
                    var value = $(this).val();
                    if ($(this).prop('checked')) {
                        if (!selectedIds.includes(value)) {
                            selectedIds.push(value);
                        }
                    } else {
                        var index = selectedIds.indexOf(value);
                        if (index !== -1) {
                            selectedIds.splice(index, 1);
                        }
                    }
                    $("#btn-simpan").prop('disabled', selectedIds.length === 0);
                });

            });

            function simpanDataTerpilih() {
                $.ajax({
                    url: '{{ route('add.data.tahun.lalu') }}',
                    type: 'POST',
                    data: {
                        ids: selectedIds
                    },
                    success: function(result) {
                        window.location.href = '/data_walidata/draft';
                        Swal.fire({
                            icon: 'success',
                            title: 'Data berhasil ditambahkan',
                            showConfirmButton: false,
                            timer: 3500
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }
        </script>
    @endpush
    {{-- @push('js')

<script>
    $(document).ready(function() {
    $('table').DataTable({
        "ordering" : false,
    });
    
    $('#opd').on('change', function() {
    var tahun = $(this).val();
    table.column(3).search(tahun).draw();
    });
    });
    $(document).ready(function() {
        $('#btnFilter').click(function() {
            var formData = {
                opd: $('#opd').val(),
            }
            $('#isiTable').empty();
            $.ajax({
                url: '{{route("filter_tahun_lalu")}}',
                type: 'POST',
                data: formData,
                success: function(result) {
                    console.log(result);
                    
                    var no = 1;
                    var first = true;

                    result.data.forEach(function(datas, i) {
                        $('#isiTable').append('<tr><td><input type="checkbox" class="head-bd" value="' + datas.id + '"></td><td>' + no++ + '</td><td>' + datas.nama_data + '</td><td>' + datas.nama_opd + '</td><td>' + datas.jenis_data + '</td><td>' + datas.sumber_data + '</td></tr>');
                    });
                }
            })
        });
    });
</script>
@endpush --}}
@endsection

{{-- @foreach ($data_tahun as $dt)
<tr>
    <td><input type="checkbox" class="head-bd" value="{{$dt->id}}"></td>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $dt->nama_data }}</td>
    <td>{{ $dt->nama_opd }}</td>
    <td>{{ $dt->jenis_data }}</td>

    <td>{{ $dt->sumber_data }}</td>
    <td>{{ $dt->tahun }}</td>
</tr>
@endforeach --}}
