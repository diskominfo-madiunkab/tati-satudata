@extends('pages.main.layout')
@section('content')
    <div class="pagetitle">
        @include('sweetalert::alert')
        <h1>Daftar Data</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item">Perencanaan Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Data</h5>

                        @if (auth()->user()->hasRole('walidata'))
                            <table>
                                <th>
                                </th>
                            </table>
                            <a href="/data_walidata/create" class="btn btn-md btn-primary mb-3 float-right"
                                style="width: 200px"><i class="bi bi-plus-circle"></i> Tambah Data</a>

                            <a href="" class="btn btn-md btn-success mb-3 float-right" style="width: 200px"
                                data-bs-toggle="modal" data-bs-target="#verticalycentered">
                                <i class="bi bi-file-earmark-spreadsheet"></i> Import Data
                            </a>

                            <a class="btn btn-md btn-outline-primary mb-3 float-right" style="width: 200px"
                                href="/get_all_opdall">
                                <i class="bi bi-arrow-down-circle-fill"></i>
                                <span>Berita Acara</span>
                            </a>

                            {{-- <a class="btn btn-md btn-outline-secondary mb-3 float-right" style="width: 200px;float: right"
                        data-bs-toggle="collapse" data-bs-target="#flush-collapseOne">
                        <i class="bi bi-funnel"></i>
                        <span>Filter</span>
                    </a> --}}

                            {{-- <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">

                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <select id="tahun" name="tahun" class="form-select select2"
                                                aria-label="Default select example">
                                                </option>
                                                <option value="">Semua Tahun</option>
                                                @foreach ($tahun as $th)
                                                <option value="{{ $th->tahun }}">{{ $th->tahun }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select id="opd" name="opd" class="form-select select2"
                                                aria-label="Default select example">
                                                <option value="">Semua OPD</option>
                                                @foreach ($opd as $op)
                                                <option value="{{ $op->id }}">{{ $op->nama_opd }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" id="searchInput" class="form-control"
                                                placeholder="Search data...">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" style="width: 100%" class="btn btn-block btn-primary"
                                                id='btnFilter'><i class="fas fa-filter"></i>
                                                Tampilkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                        @endif

                        <ul class="nav nav-tabs nav-tabs-bordered d-flex text-center" role="tablist">
                            <li class="nav-item flex-fill" role="presentation">
                                <a href="/data_walidata/draft"
                                    class="nav-link w-100 {{ isset($status) && $status == '3' ? 'active' : '' }}"
                                    id="draft-tab"><i class="bi bi-folder-check"></i> Draft</a>
                            </li>

                            <li class="nav-item flex-fill" role="presentation">
                                <a href="/data_walidata/selesai_konfirmasi_walidata"
                                    class="nav-link w-100 {{ isset($status) && $status == '1' ? 'active' : '' }}"
                                    id="disetujui-tab"><i class="bi bi-check-square"></i> Disetujui</a>
                            </li>

                            <li class="nav-item flex-fill" role="presentation">
                                <a href="/data_walidata/tolak_konfirmasi_walidata"
                                    class="nav-link w-100 {{ isset($status) && $status == '2' ? 'active' : '' }}"
                                    id="ditolak-tab"><i class="bi bi-x-circle me-2"></i> Ditolak</a>
                            </li>
                        </ul>

                        <div class="tab-content p-2">
                            <div class="tab-pane active" id="tab-draft">
                                <div class="card">

                                    <div class="card-body">
                                        <h5 class="card-title">Filter Data</h5>

                                        <div class="row mb-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select id="tahun" name="tahun" class="form-select select2"
                                                        aria-label="Default select example">
                                                        @php
                                                            $year = date('Y');
                                                        @endphp
                                                        </option>
                                                        <option value="">Semua Tahun</option>
                                                        @foreach ($tahun as $th)
                                                            {{-- <option value="{{ $th->tahun }}" {{ $th->tahun == $year ?
                                                        'selected'
                                                        : ''}}>{{ $th->tahun }}</option> --}}
                                                            <option value="{{ $th->tahun }}">{{ $th->tahun }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <select id="opd" name="opd" class="form-select select2"
                                                        aria-label="Default select example">
                                                        {{-- <option value="" disabled selected hidden>Pilih OPD</option>
                                                    --}}
                                                        <option value="">Semua OPD</option>
                                                        @foreach ($opd as $op)
                                                            <option value="{{ $op->id }}">{{ $op->nama_opd }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div id='data-kosong'>
                                @if ($data->count() == 0)
                                @php
                                $year = date('Y');
                                @endphp
                                <p class="alert alert-danger text-center">Data Tahun {{$year}} Kosong
                                </p>
                                @endif
                            </div> --}}
                                <div id='data-kosong' style="display:none;">
                                    <p class="alert alert-danger text-center"></p>
                                </div>
                                <div id="div-table" class="table-responsive">

                                    <table id="id-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Nama Data</th>
                                                <th scope="col">Produsen (PIC)</th>
                                                <th scope="col">Sumber Referensi</th>
                                                <th scope="col">Tahun</th>
                                                <th scope="col">Status Data</th>
                                                <th scope="col">Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="isiTable"
                                            style="text-align: left;
                                                vertical-align: top;">

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="verticalycentered" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight: bold; color:green">IMPORT DATA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="modal-title"><i class="bi bi-caret-right-fill"></i>Sebelum import
                        data menggunakan file excel silahkan mengunduh template data melalui tombol berikut
                        @foreach ($document as $bkrs)
                            <form action="{{ url('/download-template', $bkrs->id) }}">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-download"></i> Template Data
                                </button>
                            </form>
                        @endforeach
                    </h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#alasan">Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div><!-- End Vertically centered Modal-->
    {{-- modal input --}}
    <div class="modal fade" id="alasan" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight: bold; color:green">IMPORT DATA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/data_walidata/import" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="file" name="file" class="form-control" placeholder="Recipient's username"
                                aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-primary" type="submit" id="button-addon2">
                                Import
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="menyetujui-data" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight: bold; color:green">
                        SETUJUI DATA!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <h7 class="modal-title"><i class="bi bi-caret-right-fill"></i>Apakah
                        anda sudah yakin untuk menyetujui data?
                    </h7>
                </div>
                <div class="modal-footer">

                    <form action="{{ route('data_produsen.perencanaan.setuju') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_sukses" id="id_sukses">
                        <div class="input-group mb-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal
                            </button>
                            <button class="btn btn-primary" type="submit" id="button-addon2">Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="verticalycentered-tolak" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight: bold; color:red">TOLAK DATA !</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_verticalycentered" id="id_verticalycentered">
                    <h7 class="modal-title"><i class="bi bi-caret-right-fill"></i>Pastikan
                        bahwa data yang anda TOLAK bukan atau tidak
                        sesuai dengan DATA anda!
                    </h7>
                    <br>
                    <h7 class="modal-title"><i class="bi bi-caret-right-fill"></i>Apakah
                        anda sudah yakin untuk menolak? Jika sudah
                        yakin, Silahkan isikan Alasan untuk MENOLAK
                        DATA!
                    </h7>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal
                    </button>
                    <button type="button" class="btn btn-primary" href="javascript:void(0)"
                        onclick='$("#alasan-tolak").modal("show");$("#verticalycentered-tolak").modal("hide");$("#id_alasan").val($("#id_verticalycentered").val());'>Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="alasan-tolak" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alasan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form action="{{ route('data_produsen.perencanaan.tolak') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_alasan" id="id_alasan">
                        <div class="row mb-3">
                            <textarea type="text" name="alasan" id="alasan" class="form-control" placeholder="Berikan Alasan Penolakan"
                                aria-label="Berikan Alasan Penolakan" required></textarea>
                        </div>

                        <button class="btn btn-primary" type="submit" id="button-addon2">
                            Kirim <i class="bi bi-send"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    {{-- @php
dd(Auth::user());
@endphp --}}

    @push('js')
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
            $(document).ready(function() {

                var opd_id = '{{ Auth::user()->opd_id }}';
                var walidata = '{{ auth()->user()->hasRole('walidata') }}';
                var walidatapendukung = '{{ Auth::user()->role_id == 5 }}';


                // Function to set filters from local storage
                function setFiltersFromLocalStorage() {
                    if (localStorage.getItem('tahun')) {
                        $('#tahun').val(localStorage.getItem('tahun')).trigger('change');
                    }
                    if (localStorage.getItem('opd')) {
                        $('#opd').val(localStorage.getItem('opd')).trigger('change');
                    }
                }

                // Save filter values to local storage when changed
                $('#tahun').on('change', function() {
                    localStorage.setItem('tahun', $(this).val());
                });

                $('#opd').on('change', function() {
                    localStorage.setItem('opd', $(this).val());
                });

                // Apply filters from local storage on page load
                setFiltersFromLocalStorage();
                var table = $('#id-table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{{ route('walidata.draft') }}",
                        "type": "GET",
                        "data": function(d) {
                            d.opd = $('#opd').val();
                            d.tahun = $('#tahun').val();
                        },
                        "dataSrc": function(response) {
                            var tahun = $('#tahun').val();
                            if (response.data.length === 0) {
                                $('#data-kosong .alert').text('Data Tahun ' + tahun + ' Kosong');
                                $('#data-kosong').show();
                            } else {
                                $('#data-kosong').hide();
                            }
                            return response.data;
                        }
                    },

                    "columns": [{
                            "data": "DT_RowIndex"
                        },
                        {
                            "data": "nama_data"
                        },
                        {
                            "data": "nama_opd"
                        },
                        {
                            "data": "sumber_data"
                        },
                        {
                            "data": "tahun"
                        },
                        {
                            "data": "status",
                            "orderable": false,
                            "searchable": false,
                            "render": function(data, type, full, meta) {
                                var status = '';
                                if (full.status_id == 3) {
                                    status +=
                                        '<span class="badge bg-secondary"><i class="bi bi-collection me-1"></i>Draft</span>';
                                } else if (full.status_id == 2) {
                                    status +=
                                        '<span class="badge bg-danger"><i class="bi bi-exclamation-octagon me-1"></i>Tolak</span>';
                                } else {
                                    status +=
                                        '<span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i>Setuju</span>';
                                }
                                return status;

                            }

                        },
                        {
                            "data": "action",
                            "orderable": false,
                            "searchable": false,
                            "render": function(data, type, full, meta) {
                                var actionButtons = '';
                                // Tambahkan action sesuai dengan logika pada contoh blade
                                actionButtons += '<td>';
                                actionButtons += '<div class="btnConfirm" style="margin-bottom: 0;">';
                                actionButtons += '<table><tr>';

                                actionButtons += '<td>';
                                actionButtons += '<a href="{{ url('/data_walidata/detail') }}/' + full
                                    .id +
                                    '" class="btn btn-sm btn-warning" style="color: white" data-bs-placement="bottom" title="Detail Data"><i class="bi bi-info-circle"></i></a>';
                                actionButtons += '</td>';
                                if (full.opd_id == opd_id && walidata != 1) {
                                    // console.log(walidata);
                                    actionButtons += '<td>';
                                    actionButtons +=
                                        '<a href="javascript:void(0)" class="btn btn-sm btn-success float-right" onclick="$(\'#menyetujui-data\').modal(\'show\');$(\'#id_sukses\').val(\'' +
                                        full.id + '\');"><i class="bi bi-check-circle"></i></a>';
                                    actionButtons += '</td>';
                                    actionButtons += '<td>';
                                    actionButtons +=
                                        '<a href="javascript:void(0)" class="btn btn-sm btn-danger float-right" onclick="$(\'#verticalycentered-tolak\').modal(\'show\');$(\'#id_verticalycentered\').val(\'' +
                                        full.id + '\');"><i class="bi bi-x-circle"></i></a>';
                                    actionButtons += '</td>';
                                    actionButtons += '<td>';
                                    actionButtons += '<a href="{{ url('/data_walidata/edit') }}/' +
                                        full.id +
                                        '" class="btn btn-sm btn-primary" data-bs-placement="bottom" title="Edit Data"><i class="bi bi-pencil-fill"></i></a>';
                                    actionButtons += '</td>';
                                }

                                if (walidata) {

                                    actionButtons += '<td>';
                                    actionButtons += '<form id="delete-pegawai-' + full.id +
                                        '" action="{{ url('/data_walidata/destroy') }}/' + full.id +
                                        '">';
                                    actionButtons +=
                                        '<button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(\'delete-pegawai-' +
                                        full.id +
                                        '\')" data-bs-placement="bottom" title="Hapus Data"><i class="bi bi-trash"></i></button>';
                                    actionButtons += '</form>';
                                    actionButtons += '</td>';
                                    actionButtons += '<td>';
                                    actionButtons += '<form action="/data_walidata/edit/' + full.id +
                                        '">';
                                    actionButtons +=
                                        '<button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></button>';
                                    actionButtons += '</form>';
                                    actionButtons += '</td>';
                                }

                                actionButtons += '<td>';
                                if (full.data_prioritas == null || full.data_prioritas == 0) {
                                    actionButtons +=
                                        '<a href="{{ url('/data_walidata/ubah_data_prioritas') }}/' +
                                        full.id +
                                        '" class="btn btn-sm" data-bs-placement="bottom" title="Data Prioritas"><i class="bi bi-star"></i></a>';
                                } else {
                                    actionButtons +=
                                        '<a href="{{ url('/data_walidata/ubah_data_prioritas') }}/' +
                                        full.id +
                                        '" class="btn btn-sm" data-bs-placement="bottom" title="Data Prioritas"><i style="color:orange" class="bi bi-star-fill"></i></a>';
                                }
                                actionButtons += '</td>';

                                actionButtons += '</tr></table></div>';
                                actionButtons += '</td>';

                                return actionButtons;
                            }

                        },
                    ]
                });

                // Event untuk filter ketika nilai opd atau tahun berubah
                $('#opd, #tahun').change(function() {
                    table.draw();
                });
            });
            $(document).ready(function() {
                $('#btnFilter').click(function() {
                        var status = '{{ $status }}';
                        var formData = {
                            tahun: $('#tahun_s').val(),
                            status: status,
                            opd: $('#opd_s').val(),
                        }
                        var divviewdata = document.getElementById('div-table');
                        var divdatakosong = document.getElementById('data-kosong');
                        divviewdata.style.display = 'block';


                        $('#isiTable').empty();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: '{{ route('filter_tahun') }}',
                            type: 'POST',
                            data: formData,
                            success: function success(result) {
                                // console.log(result);


                                var no = 1;
                                var first = true;

                                result.data.forEach(function(datas, i) {
                                    console.log(datas.data_prioritas);
                                    if (datas.length == null) {
                                        divdatakosong.style.display = 'none';
                                    }
                                    var statusBadge = '';
                                    if (datas.status_id == 3) {
                                        statusBadge =
                                            '<span class="badge bg-secondary"><i class="bi bi-collection me-1"></i>' +
                                            datas.status + '</span>';
                                    } else if (datas.status_id == 2) {
                                        statusBadge =
                                            '<span class="badge bg-danger"><i class="bi bi-exclamation-octagon me-1"></i>' +
                                            datas.status + '</span>';
                                    } else {
                                        statusBadge =
                                            '<span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i>Setuju</span>';
                                    }

                                    var statusBadgeTahapan = '';
                                    if (datas.status_id == 1 && datas.progress == 0) {
                                        statusBadgeTahapan =
                                            '<span class="badge bg-info">1. Perencanaan Data</span>';
                                    } else if (datas.status_id == 1 && datas.progress != 0 |
                                        datas.status_id == 5) {
                                        statusBadgeTahapan =
                                            '<span class="badge bg-info">1. Perencanaan Data</span><br><span class="badge bg-primary">2. Pengumpulan Data</span>';
                                    } else if (datas.status_id == 6 | datas.status_id == 7 |
                                        datas.status_id == 8) {
                                        statusBadgeTahapan =
                                            '<span class="badge bg-info">1. Perencanaan Data</span><br><span class="badge bg-primary">2. Pengumpulan Data</span><br><span class="badge bg-warning">3. Pemeriksaan Data</span>';
                                    } else if (datas.status_id == 9) {
                                        statusBadgeTahapan =
                                            '<span class="badge bg-info">1. Perencanaan Data</span><br><span class="badge bg-primary">2. Pengumpulan Data</span><br><span class="badge bg-warning">3. Pemeriksaan Data</span><br><span class="badge bg-success">4. Penyebarluasan Data</span>';
                                    }


                                    var tableRow = '<tr>' +
                                        '<td>' + (i + 1) + '</td>' +
                                        '<td>' + datas.nama_data + '</td>' +
                                        '<td>' + datas.nama_opd + '</td>' +
                                        '<td>' + datas.sumber_data + '</td>' +
                                        '<td>' + datas.tahun + '</td>' +
                                        '<td>' + statusBadge + '</td>';
                                    if (status == 1) {
                                        tableRow += '<td>' + statusBadgeTahapan + '</td>';
                                    }
                                    tableRow +=
                                        '<td>' +
                                        '<div class="btnConfirm" style="margin-bottom: 0;">' +
                                        '<table>' +
                                        '<tr>' +
                                        '<td>' +
                                        ' <a href="{{ url('/data_walidata/detail') }}/' + datas
                                        .id +
                                        '" class="btn btn-sm btn-warning" style="color: white" data-bs-placement="bottom" title="Detail Data"><i class="bi bi-info-circle"></i></a>' +
                                        '</td>';

                                    if ('{{ auth()->user()->hasRole('walidata') }}') {
                                        if (status == 1 | status == 2) {

                                            tableRow += '<td>' +
                                                '<form id="restore-data-' + datas.id +
                                                '" action="{{ url('/data_walidata/restore') }}/' +
                                                datas.id + '">';

                                            if (datas.status_id == 1 && datas.progress == 0) {
                                                tableRow +=
                                                    '<button type="button" class="btn btn-sm btn-success" onclick="confirmRestore(\'restore-data-' +
                                                    datas.id +
                                                    '\')" data-bs-placement="bottom" title="Restore Data"><i class="bi bi-arrow-repeat"></i></button>';
                                            } else if (datas.status_id == 1 && datas.progress !=
                                                0) {
                                                tableRow +=
                                                    '<button type="button" class="btn btn-sm btn-success" onclick="confirmcantrestore()" data-bs-placement="bottom" title="Restore Data"><i class="bi bi-arrow-repeat"></i></button>';
                                            } else if (datas.status_id == 2) {
                                                tableRow +=
                                                    '<button type="button" class="btn btn-sm btn-success" onclick="confirmRestore(\'restore-data-' +
                                                    datas.id +
                                                    '\')" data-bs-placement="bottom" title="Restore Data"><i class="bi bi-arrow-repeat"></i></button>';
                                            } else if (datas.status_id != 1 && datas
                                                .status_id != 2) {
                                                tableRow +=
                                                    '<button type="button" class="btn btn-sm btn-success" onclick="confirmcantrestore()" data-bs-placement="bottom" title="Restore Data"><i class="bi bi-arrow-repeat"></i></button>';
                                            }

                                            tableRow += '</form>' +
                                                '</td>';

                                        } else {
                                            tableRow += '<td>' +
                                                '<a href="{{ url('/data_walidata/edit') }}/' +
                                                datas.id +
                                                '" class="btn btn-sm btn-primary" data-bs-placement="bottom" title="Edit Data"><i class="bi bi-pencil-fill"></i></a>' +
                                                '</td>' +
                                                '<td>' +
                                                '<form id="delete-pegawai-' + datas.id +
                                                '" action="{{ url('/data_walidata/destroy') }}/' +
                                                datas.id + '">' +
                                                '<button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(\'delete-pegawai-' +
                                                datas.id +
                                                '\')" data-bs-placement="bottom" title="Hapus Data"><i class="bi bi-trash"></i></button>' +
                                                '</form>' +
                                                '</td>';
                                        }
                                    }

                                    if (datas.data_prioritas == null | datas.data_prioritas ==
                                        0) {
                                        tableRow += '<td>' +
                                            '<a href="/data_walidata/ubah_data_prioritas/' +
                                            datas.id +
                                            '" class="btn btn-sm" data-bs-placement="bottom" title="Data Prioritas"><i class="bi bi-star"></i></a>' +
                                            '</td>';
                                    } else {
                                        tableRow += '<td>' +
                                            '<a href="/data_walidata/ubah_data_prioritas/' +
                                            datas.id +
                                            '" class="btn btn-sm" data-bs-placement="bottom" title="Data Prioritas"><i style="color:orange" class="bi bi-star-fill"></i></a>' +
                                            '</td>';
                                    }

                                    tableRow += '</tr>' +
                                        '</table>' +
                                        '</div>' +
                                        '</td>' +
                                        '</tr>';

                                    $('#isiTable').append(tableRow);
                                });
                            }
                        })
                    }

                );

                $('#searchInput').keyup(function() {
                    var searchValue = $(this).val().toLowerCase();
                    filterTable(searchValue);
                });

                function filterTable(value) {
                    $('#isiTable tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                }


                $('#searchInput').keyup(function() {
                    var searchValue = $(this).val().toLowerCase();
                    filterTable(searchValue);
                    // Also, add the following lines to send the search query to the server
                    var status = '{{ $status }}';
                    var formData = {
                        tahun: $('#tahun_d').val(),
                        status: status,
                        opd: $('#opd_d').val(),
                        searchQuery: searchValue
                    };
                    console.log(status);


                    var divviewdata = document.getElementById('div-table');
                    var divdatakosong = document.getElementById('data-kosong');
                    divviewdata.style.display = 'block';

                    $('#isiTable').empty();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '{{ route('search_data') }}',
                        type: 'POST',
                        data: formData,
                        success: function(result) {
                            $('#isiTable').empty();
                            // console.log(result);
                            // console.log(data);

                            var no = 1;
                            var first = true;
                            result.data.forEach(function(datas, i) {
                                if (datas.length == null) {
                                    divdatakosong.style.display = 'none';
                                }
                                var statusBadge = '';
                                if (datas.status_id == 3) {
                                    statusBadge =
                                        '<span class="badge bg-secondary"><i class="bi bi-collection me-1"></i>' +
                                        datas.status + '</span>';
                                } else if (datas.status_id == 2) {
                                    statusBadge =
                                        '<span class="badge bg-danger"><i class="bi bi-exclamation-octagon me-1"></i>' +
                                        datas.status + '</span>';
                                } else {
                                    statusBadge =
                                        '<span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i>Setuju</span>';
                                }

                                var statusBadgeTahapan = '';
                                if (datas.status_id == 1 && datas.progress == 0) {
                                    statusBadgeTahapan =
                                        '<span class="badge bg-info">1. Perencanaan Data</span>';
                                } else if (datas.status_id == 1 && datas.progress != 0 |
                                    datas.status_id == 5) {
                                    statusBadgeTahapan =
                                        '<span class="badge bg-info">1. Perencanaan Data</span><br><span class="badge bg-primary">2. Pengumpulan Data</span>';
                                } else if (datas.status_id == 6 | datas.status_id == 7 |
                                    datas.status_id == 8) {
                                    statusBadgeTahapan =
                                        '<span class="badge bg-info">1. Perencanaan Data</span><br><span class="badge bg-primary">2. Pengumpulan Data</span><br><span class="badge bg-warning">3. Pemeriksaan Data</span>';
                                } else if (datas.status_id == 9) {
                                    statusBadgeTahapan =
                                        '<span class="badge bg-info">1. Perencanaan Data</span><br><span class="badge bg-primary">2. Pengumpulan Data</span><br><span class="badge bg-warning">3. Pemeriksaan Data</span><br><span class="badge bg-success">4. Penyebarluasan Data</span>';
                                }


                                var tableRow = '<tr>' +
                                    '<td>' + (i + 1) + '</td>' +
                                    '<td>' + datas.nama_data + '</td>' +
                                    '<td>' + datas.nama_opd + '</td>' +
                                    '<td>' + datas.sumber_data + '</td>' +
                                    '<td>' + datas.tahun + '</td>' +
                                    '<td>' + statusBadge + '</td>';
                                if (status == 1) {
                                    tableRow += '<td>' + statusBadgeTahapan + '</td>';
                                }
                                tableRow +=
                                    '<td>' +
                                    '<div class="btnConfirm" style="margin-bottom: 0;">' +
                                    '<table>' +
                                    '<tr>' +
                                    '<td>' +
                                    ' <a href="{{ url('/data_walidata/detail') }}/' + datas
                                    .id +
                                    '" class="btn btn-sm btn-warning" style="color: white" data-bs-placement="bottom" title="Detail Data"><i class="bi bi-info-circle"></i></a>' +
                                    '</td>';
                                if ('{{ auth()->user()->hasRole('walidata') }}') {
                                    if (status == 1 | status == 2) {

                                        tableRow += '<td>' +
                                            '<form id="restore-data-' + datas.id +
                                            '"action="{{ url('/data_walidata/restore') }}/' +
                                            datas.id + '">';

                                        if (datas.status_id == 1 && datas.progress == 0) {
                                            tableRow +=
                                                '<button type="button" class="btn btn-sm btn-success" onclick="confirmRestore(\'restore-data-' +
                                                datas.id +
                                                '\')" data-bs-placement="bottom" title="Restore Data"><i class="bi bi-arrow-repeat"></i></button>';
                                        } else if (datas.status_id == 1 && datas.progress !=
                                            0) {
                                            tableRow +=
                                                '<button type="button" class="btn btn-sm btn-success" onclick="confirmcantrestore()" data-bs-placement="bottom" title="Restore Data"><i class="bi bi-arrow-repeat"></i></button>';
                                        } else if (datas.status_id == 2) {
                                            tableRow +=
                                                '<button type="button" class="btn btn-sm btn-success" onclick="confirmRestore(\'restore-data-' +
                                                datas.id +
                                                '\')" data-bs-placement="bottom" title="Restore Data"><i class="bi bi-arrow-repeat"></i></button>';
                                        } else if (datas.status_id != 1 && datas
                                            .status_id != 2) {
                                            tableRow +=
                                                '<button type="button" class="btn btn-sm btn-success" onclick="confirmcantrestore()" data-bs-placement="bottom" title="Restore Data"><i class="bi bi-arrow-repeat"></i></button>';
                                        }

                                        tableRow += '</form>' +
                                            '</td>';

                                    } else {
                                        tableRow += '<td>' +
                                            '<a href="{{ url('/data_walidata/edit') }}/' +
                                            datas.id +
                                            '" class="btn btn-sm btn-primary" data-bs-placement="bottom" title="Edit Data"><i class="bi bi-pencil-fill"></i></a>' +
                                            '</td>' + '<td>' + '<form id="delete-pegawai-' +
                                            datas.id +
                                            '" action="{{ url('/data_walidata/destroy') }}/' +
                                            datas.id + '">' +
                                            '<button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(\'delete-pegawai-' +
                                            datas.id +
                                            '\' )" data-bs-placement="bottom" title="Hapus Data"><i class="bi bi-trash"></i></button>' +
                                            '</form>' +
                                            '</td>';
                                    }
                                }
                                if (datas.data_prioritas == null | datas.data_prioritas ==
                                    0) {
                                    tableRow += '<td>' +
                                        '<a href="/data_walidata/ubah_data_prioritas/' +
                                        datas.id +
                                        '" class="btn btn-sm" data-bs-placement="bottom" title="Data Prioritas"><i class="bi bi-star"></i></a>' +
                                        '</td>';
                                } else {
                                    tableRow += '<td>' +
                                        '<a href="/data_walidata/ubah_data_prioritas/' +
                                        datas.id +
                                        '" class="btn btn-sm" data-bs-placement="bottom" title="Data Prioritas"><i style="color:orange" class="bi bi-star-fill"></i></a>' +
                                        '</td>';
                                }

                                tableRow += '</tr>' +
                                    '</table>' +
                                    '</div>' +
                                    '</td>' +
                                    '</tr>';

                                $('#isiTable').append(tableRow);
                            });
                        }
                    });
                });

            });
        </script>
    @endpush

    <script>
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        function filterFunction() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            div = document.getElementById("myDropdown");
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                txtValue = a[i].textContent || a[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }
    </script>

    <script type="text/javascript">
        function confirmcantrestore() {
            Swal.fire(
                'Restore Data Tidak Dapat Dilakukan!',
                'Data Sudah Melewati Tahap Perencanaan Data!',
                'error'
            )
        };

        function confirmRestore(form_id) {
            swal({
                    title: 'Apakah Anda Yakin Mengembalikan Data Menjadi DRAFT?',
                    text: "Anda akan mengembalikan data menjadi status Draft!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    buttons: true,
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $('#' + form_id).submit();
                    } else {

                    }
                });
        };

        function confirmDelete(item_id) {
            swal({
                    title: 'Apakah Anda Yakin Menghapus Data?',
                    text: "Anda Tidak Akan Dapat Mengembalikannya!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    buttons: true,
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $('#' + item_id).submit();
                    } else {
                        // swal("Cancelled Successfully");
                    }
                });
        };
    </script>
@endsection

@push('js')
    <script>
        $(function() {
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
                search.set('y', val);

                if (val == '{{ date('Y') }}') {
                    search.delete('y');
                }

                window.location.search = search.toString();
            });
        });
    </script>
@endpush
