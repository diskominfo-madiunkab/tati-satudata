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
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Data</h5>
                        {{-- <div class="text-center" style="margin-left: 70%">
                        <a class="btn btn-md btn-outline-secondary mb-3 float-right" style="width: 200px;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseOne">
                            <i class="bi bi-funnel"></i>
                            <span>Filter</span>
                        </a>
                    </div> --}}


                        {{-- <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">

                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
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
                                            <input type="text" id="searchInput" class="form-control"
                                                placeholder="Search data...">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" style="width: 50%" class="btn btn-block btn-primary"
                                                id='btnFilter'><i class="fas fa-filter"></i>
                                                Tampilkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                        <ul class="nav nav-tabs nav-tabs-bordered d-flex text-center" role="tablist">
                            <li class="nav-item flex-fill" role="presentation">
                                <a href="/data_produsen/draft"
                                    class="nav-link w-100 {{ isset($status) && $status == '3' ? 'active' : '' }}"
                                    id="draft-tab"><i class="bi bi-folder-check"></i> Draft</a>
                            </li>

                            <li class="nav-item flex-fill" role="presentation">
                                <a href="/data_produsen/selesai_konfirmasi"
                                    class="nav-link w-100 {{ isset($status) && $status == '1' ? 'active' : '' }}"
                                    id="disetujui-tab"><i class="bi bi-check-square"></i> Disetujui</a>
                            </li>

                            <li class="nav-item flex-fill" role="presentation">
                                <a href="/data_produsen/tolak_konfirmasi"
                                    class="nav-link w-100 {{ isset($status) && $status == '2' ? 'active' : '' }}"
                                    id="ditolak-tab"><i class="bi bi-x-circle"></i> Ditolak</a>
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
                                                            <option value="{{ $th->tahun }}">{{ $th->tahun }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @if (isset($status) && $status === '1')
                                                    <div class="col-md-6 d-flex justify-content-end">
                                                        <form style="margin-bottom: 30px" id="berita-acara"
                                                            action="{{ url('/data_produsen/export-pdf') }}" target="_blank">
                                                            <input type="hidden" name="tahun" id="tahunUnduhBeritaAcara"
                                                                value="">
                                                            <button type="button" class="btn btn-success"
                                                                onclick="confirmBeritacara('berita-acara')"><i
                                                                    class="bi bi-download"></i>
                                                                Unduh
                                                                Berita Acara</button>
                                                        </form>
                                                        <div class="modal fade" id="beritaacara" tabindex="-1">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Unduh Berita Acara</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Anda belum bisa mengunduh berita acara
                                                                        dikarenakan masih ada DATA
                                                                        yang
                                                                        berstatus DRAFT
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-bs-dismiss="modal">
                                                                            Close
                                                                        </button>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id='data-kosong' style="display:none;">
                                    <p class="alert alert-danger text-center"></p>
                                </div>
                                <div id="div-table" class="table-responsive">
                                    <table id="id-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Nama Data</th>
                                                <th scope="col">Produsen (PIC)</th>
                                                {{-- <th scope="col">Jenis</th> --}}
                                                <th scope="col">Sumber Referensi</th>
                                                <th scope="col">Tahun</th>
                                                <th scope="col">Status</th>
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
        @foreach ($data as $dt)
            {{-- <div class="modal fade" id="menyetujui-{{ $dt->id }}" tabindex="-1">
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

                    <form action="{{ url('/data_produsen/setuju/'.encrypt($dt->id)) }}" method="get"
                        enctype="multipart/form-data">
                        @csrf
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
    </div> --}}

            <div class="modal fade" id="verticalycentered-{{ $dt->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="font-weight: bold; color:red">TOLAK DATA !</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
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
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#alasan">Lanjutkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="alasan" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Alasan Penolakan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-3">
                            <form action="{{ url('data_produsen/alasan', $dt->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
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
        @endforeach
    </section>


    <div class="modal fade" id="basicModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/data_produsen/import" method="post" enctype="multipart/form-data">
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

    {{-- modal new --}}

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

    @push('js')
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
    integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script> --}}
        <meta name="csrf-token" content="{{ csrf_token() }}" />
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
            function getStatusUrl() {
                var status = '{{ $status }}';
                var url;
                // Cek status dan sesuaikan URL
                if (status === '3') {
                    url = "{{ route('draft.produsen') }}";
                } else if (status === '1') {
                    url = "{{ route('produsen.setuju') }}";
                } else if (status === '2') {
                    url = "{{ route('produsen.tolak') }}";
                }
                return url;
            }

            $(document).ready(function() {
                function setFiltersFromLocalStorage() {
                    if (localStorage.getItem('tahun')) {
                        $('#tahun').val(localStorage.getItem('tahun')).trigger('change');
                        $('#tahunUnduhBeritaAcara').val(localStorage.getItem('tahun'));
                    }
                }

                // Save filter values to local storage when changed
                $('#tahun').on('change', function() {
                    localStorage.setItem('tahun', $(this).val());
                });

                // Apply filters from local storage on page load
                setFiltersFromLocalStorage();
                var table = $('#id-table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": getStatusUrl(),
                        "type": "GET",
                        "data": function(d) {
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
                            "data": "no"
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
                                console.log(full);
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
                                actionButtons += '<td>';
                                actionButtons += '<table><tr>';
                                actionButtons += '<td>';
                                actionButtons += '<a href="/data_produsen/detail/' + full.id +
                                    '" class="btn btn-sm btn-warning" style="color: white" data-bs-placement="bottom" title="Detail Data"><i class="bi bi-info-circle"></i></a>';
                                actionButtons += '</td>';
                                if (full.status_id == '3') {
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
                                    actionButtons += '<form action="/data_produsen/edit/' + full.id +
                                        '">';
                                    actionButtons +=
                                        '<button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></button>';
                                    actionButtons += '</form>';
                                    actionButtons += '</td>';
                                }
                                actionButtons += '<td>';
                                if (full.data_prioritas == null || full.data_prioritas == 0) {
                                    actionButtons += '<i class="bi bi-star"></i>';
                                } else {
                                    actionButtons +=
                                        '<i style="color:orange" class="bi bi-star-fill"></i>';
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
                $(' #tahun').change(function() {
                    table.draw();
                    $('#tahunUnduhBeritaAcara').val($(this).val());
                });
            });
        </script>
        {{-- <script>
    document.addEventListener("DOMContentLoaded", function() {
    function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
    }
    });
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
</script> --}}
        <script>
            $(document).ready(function() {

                $('#btnFilter').click(function() {
                    var status = '{{ $status }}';
                    var formData = {
                        tahun: $('#tahun').val(),
                        status: status,
                    }
                    var divviewdata = document.getElementById('div-table');
                    var divdatakosong = document.getElementById('data-kosong');
                    divviewdata.style.display = 'block';
                    // divdatakosong.style.display = 'none';
                    $('#isiTable').empty();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route('filter_tahun_produsen') }}',
                        type: 'POST',
                        data: formData,
                        success: function success(result) {
                            console.log(result);

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


                                var tableRow = '<tr>' +
                                    '<td>' + (i + 1) + '</td>' +
                                    '<td>' + datas.nama_data + '</td>' +
                                    '<td>' + datas.nama_opd + '</td>' +
                                    '<td>' + datas.sumber_data + '</td>' +
                                    '<td>' + datas.tahun + '</td>' +
                                    '<td>' + statusBadge + '</td>' +
                                    '<td>';
                                if (datas.user_id != '{{ Auth::user()->id }}') {
                                    tableRow += '<table>' +
                                        '<tr>' +
                                        '<td>' +
                                        '<a href="{{ url('/data_produsen/detail') }}/' +
                                        datas.id +
                                        '" class="btn btn-sm btn-warning" style="color: white" data-bs-placement="bottom" title="Detail Data"><i class="bi bi-info-circle"></i></a>' +
                                        '</td>';
                                    if (status == 3) {
                                        tableRow += '<td>' +
                                            '<a href="javascript:void(0)" class="btn btn-sm btn-success float-right" onclick="$(\'#menyetujui-data\').modal(\'show\');$(\'#id_sukses\').val(\'' +
                                            datas.id +
                                            '\')"><i class="bi bi-check-circle"></i></a>' +
                                            '</td>' +
                                            '<td>' +
                                            '<a href="javascript:void(0)" class="btn btn-sm btn-danger float-right" onclick="$(\'#verticalycentered-tolak\').modal(\'show\');$(\'#id_verticalycentered\').val(\'' +
                                            datas.id +
                                            '\')"><i class="bi bi-x-circle"></i></a>' +
                                            '</td>' +
                                            '<td>' +
                                            '<form action="{{ url('/data_produsen/edit/') }}/' +
                                            datas.id + '">' +
                                            '<button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></button>' +
                                            '</form>' +
                                            '</td>';
                                        if (datas.data_prioritas == null | datas
                                            .data_prioritas == 0) {
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
                                    }
                                }
                                tableRow += '</tr>';

                                $('#isiTable').append(tableRow);
                            });
                        }
                    })
                });

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
                        tahun: $('#tahun').val(),
                        status: status,
                        searchQuery: searchValue
                    }
                    var divviewdata = document.getElementById('div-table');
                    var divdatakosong = document.getElementById('data-kosong');
                    divviewdata.style.display = 'block';
                    // divdatakosong.style.display = 'none';
                    $('#isiTable').empty();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route('search_data_produsen') }}',
                        type: 'POST',
                        data: formData,
                        success: function success(result) {
                            $('#isiTable').empty();
                            console.log(result);

                            var no = 1;
                            var first = true;

                            result.data.forEach(function(datas, i) {
                                if (datas.length == null) {
                                    divdatakosong.style.display = 'none';
                                }
                                var statusBadge = '';
                                console.log(datas);
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


                                var tableRow = '<tr>' +
                                    '<td>' + (i + 1) + '</td>' +
                                    '<td>' + datas.nama_data + '</td>' +
                                    '<td>' + datas.nama_opd + '</td>' +
                                    '<td>' + datas.sumber_data + '</td>' +
                                    '<td>' + datas.tahun + '</td>' +
                                    '<td>' + statusBadge + '</td>' +
                                    '<td>';
                                if (datas.user_id != '{{ Auth::user()->id }}') {
                                    tableRow += '<table>' +
                                        '<tr>' +
                                        '<td>' +
                                        '<a href="{{ url('/data_produsen/detail') }}/' +
                                        datas.id +
                                        '" class="btn btn-sm btn-warning" style="color: white" data-bs-placement="bottom" title="Detail Data"><i class="bi bi-info-circle"></i></a>' +
                                        '</td>';
                                    if (status == 3) {
                                        tableRow += '<td>' +
                                            '<a href="javascript:void(0)" class="btn btn-sm btn-success float-right" onclick="$(\'#menyetujui-data\').modal(\'show\');$(\'#id_sukses\').val(\'' +
                                            datas.id +
                                            '\')"><i class="bi bi-check-circle"></i></a>' +
                                            '</td>' +
                                            '<td>' +
                                            '<a href="javascript:void(0)" class="btn btn-sm btn-danger float-right" onclick="$(\'#verticalycentered-tolak\').modal(\'show\');$(\'#id_verticalycentered\').val(\'' +
                                            datas.id +
                                            '\')"><i class="bi bi-x-circle"></i></a>' +
                                            '</td>' +
                                            '<td>' +
                                            '<form action="{{ url('/data_produsen/edit/') }}/' +
                                            datas.id + '">' +
                                            '<button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></button>' +
                                            '</form>' +
                                            '</td>';
                                    }
                                }
                                tableRow += '</tr>';

                                $('#isiTable').append(tableRow);
                            });
                        }
                    })

                });
            });
        </script>


        <script type="text/javascript">
            function confirmSetuju(item_id) {
                swal({
                        title: 'Apakah Anda Yakin Menyetujui Data?',
                        text: "Anda Akan Menyetujui Data!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        //  dangerMode: true,
                        // cancel: true,
                        buttons: true,
                        // dangerMode: true,
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

        <script type="text/javascript">
            function confirmBeritacara(item_id) {
                swal({
                        title: 'Apakah Anda Yakin Mengunduh Berita Acara?',
                        text: "Anda Akan Mengunduh Berita Acara!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        buttons: true,
                        confirmButtonText: 'Yes, delete it!'
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            const url = $('#' + item_id).attr('action') + '?tahun=' + $('#tahunUnduhBeritaAcara').val();
                            $.ajax({
                                url: url,
                                type: 'GET',
                                dataType: 'json',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                success: function(result) {
                                    $('#' + item_id).submit()
                                },
                                error: function(xhr, status, error) {
                                    $('#beritaacara').modal('show');
                                }
                            })
                        } else {
                            //  swal("Cancelled Successfully");
                        }
                    });
            };

            function confirmDraft(item_id) {
                swal({
                        title: 'Anda belum bisa mengunduh berita acara dikarenakan masih ada DATA yang berstatus DRAFT!',
                        text: "Silakahan selesaikan Konfirmasi terlebih dahulu!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#d33',
                        //  dangerMode: true,
                        buttons: true,
                        confirmButtonText: 'Yes, delete it!'
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $('#' + item_id).submit();
                        } else {
                            //  swal("Cancelled Successfully");
                        }
                    });
            };
        </script>
    @endpush
@endsection
