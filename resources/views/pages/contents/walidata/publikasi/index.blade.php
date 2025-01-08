@extends('pages.main.layout')

@section('content')
@php
$role = auth()->user()->hasAnyRole('produsen') ? 'produsen' : 'walidata';
$role_id = auth()->user()->role_id;
@endphp
@include('sweetalert::alert')
<div class="pagetitle">
    <h1>Daftar Data Penyebarluasan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Penyebarluasan Data</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Data Penyebarluasan</h5>

                    <ul class="nav nav-tabs nav-tabs-bordered d-flex text-center" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <a href="{{route('publikasi.index')}}"
                                class="nav-link {{isset($status) && $status == 'publikasi' ? 'active' : ''}} w-100"
                                id="verifikasi-tab"><i class="bi bi-send"></i> Siap Dipublikasi</a>
                        </li>

                        <li class="nav-item flex-fill" role="presentation">
                            <a href="{{route('publikasi.terpublikasi')}}"
                                class="nav-link w-100 {{isset($status) && $status == 'terpublikasi' ? 'active' : ''}}"
                                id="lengkap-tab"><i class="bi bi-send-check"></i> Terpublikasi</a>
                        </li>
                    </ul>


                    <div class="tab-content p-2">
                        <div class="tab-pane active">
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
                                                    @foreach( $tahun as $th)
                                                    {{-- <option value="{{ $th->tahun }}" {{ $th->tahun == $year ?
                                                        'selected'
                                                        : ''}}>{{ $th->tahun }}</option> --}}
                                                    <option value="{{ $th->tahun }}">{{ $th->tahun }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if (Auth::user()->role_id == '2' || Auth::user()->role_id == '4' ||
                                            Auth::user()->role_id == '5')
                                            <div class="col-md-6">
                                                <select id="opd" name="opd" class="form-select select2"
                                                    aria-label="Default select example">
                                                    {{-- <option value="" disabled selected hidden>Pilih OPD</option>
                                                    --}}
                                                    <option value="">Semua OPD</option>
                                                    @foreach( $opd as $op)
                                                    <option value="{{ $op->id }}">{{ $op->nama_opd }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif

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
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Data</th>
                                            <th scope="col">Jenis</th>
                                            <th scope="col">Sumber Referensi</th>
                                            <th scope="col">Tahun</th>
                                            <th scope="col">Produsen Data</th>
                                            <th scope="col">Terakhir diubah</th>
                                            <th scope="col"> </th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="isiTable" style="text-align: left;vertical-align: top;">
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

@endsection

@push('js')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
<script>
    var role = "{{ $role }}";
    var status = '{{ $status }}'; // Mengambil status dari suatu sumber
    function getStatusUrl() {
    var role = "{{ $role }}";
    // console.log(role);
    var status = '{{ $status }}'; // Mengambil status dari suatu sumber
    var url;
    // Cek status dan sesuaikan URL
    if (status === 'publikasi') {
    url = "{{ url('/data_walidata/publikasi') }}";
    
    }else if (status === 'terpublikasi') {
    url = "{{ url('/data_walidata/publikasi/terpublikasi') }}";
    
    }else if (status === 'siap-publikasi') {
    url = "{{ route('produsen.verifikasi.sesuai') }}";
    }
    return url;
    }
    $(document).ready(function() {
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
    var role_id = "{{ $role_id }}";
            var table = $('#id-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": getStatusUrl(),
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
        "columns": [
            { "data": "no" },
            { "data": "nama_data" },
            { "data": "jenis_data" },
            { "data": "sumber_data" },
            { "data": "tahun" },
            { "data": "opd.nama_opd" }, 
            { "data": "updated_at" },
            {
            "data": "prioritas",
            "orderable": false,
            "searchable": false,
            "render": function(progress, type, full, meta) {
            var buttonsHtml = '<div class="d-flex flex-column gap-2">';
                if (full.data_prioritas == null | full.data_prioritas == 0) {
                buttonsHtml += '<i class="bi bi-star"></i>';
                } else{
                buttonsHtml += '<i style="color:orange" class="bi bi-star-fill"></i>';
                }
                buttonsHtml += '</div>';
            
            return buttonsHtml;
            }
            },
            {
            "data": "progress",
            "orderable": false,
            "searchable": false,
            "render": function(progress, type, full, meta) {
            var buttonsHtml = '<div class="d-flex flex-row gap-2">';
                console.log(role);
                
                if (full.status_id == 9) { 
                    buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/publikasi/' + full.id + '/organisasi"><i class="bi bi-info-circle"></i>Detail Publikasi</a>';
                }else {
                    if (role_id == 2){
                        buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/publikasi/' + full.id + '/organisasi"><i class="bi bi-info-circle"></i> Publikasi</a>';
                    }else{

                    }

                }
                buttonsHtml += '<a class="btn btn-outline-success btn-sm" href="/export/' + full.id + '"><i class="bi bi-file-zip"></i>Export</a>';
                // buttonsHtml += '<a class="btn btn-outline-success btn-sm" href="/data_walidata/publikasi/' + full.id + '/ckanshow"><i class="bi bi-file-zip"></i>Cek CKAN</a>';
                
                if (full.status_id == 9) {
                    if (full.publikasi && full.publikasi.slug) {
                        buttonsHtml += '<a href="https://katalog-data.madiunkab.go.id/dataset/'+ full.publikasi.slug +'" class="btn btn-outline-primary btn-sm" target="_new">CKAN <i class="bi bi-app-indicator"></i></a>';
                        buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/detail-data/' + full.id + '"><i class="bi bi-eye"></i>Detail Data</a>';
                    }
                }
                // if (full.status_id == 8){
                //     buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/detail-data/' + full.id + '"><i class="bi bi-eye"></i>Detail Data</a>';
                // }
                 else { 
                    buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/detail-data/' + full.id + '"><i class="bi bi-eye"></i>Detail Data</a>';
                }
                buttonsHtml += '</div>';
            
            return buttonsHtml;
            }
            }
        ]
    });
            // Event untuk filter ketika nilai opd atau tahun berubah
            $('#opd, #tahun').change(function() {
            table.draw();
            });
            
            $(document).ready(function() {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
            });
           $(document).on('click', 'a.btn-action', function (e) {
                e.preventDefault();
                Swal.showLoading();
                let completeUrl = $(this).data('completeUrl');
                $.get($(this).data('statusUrl'))
                    .then(function(r) {
                        if (Swal.isLoading()) Swal.hideLoading();

                        if (r.ok === false) {
                            Toast.fire({icon: 'error', title: r.message});
                            return;
                        }

                        Swal.fire({
                            icon: r.code === 1 ? 'info' : 'warning',
                            title: 'Konfirmasi Selesai Proses Verifikasi',
                            text: r.message,
                            showCancelButton: true,
                            cancelButtonText: 'Batal',
                            confirmButtonText: 'Ya, Selesai',
                            showLoaderOnConfirm: true,
                            preConfirm: (comment) => {
                                return $.ajax({url: completeUrl, method: 'PATCH'})
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
                                setTimeout(() => window.location.reload(), 2000);
                            }
                        });
                    });
            })
            });
        });
</script>

<script>
    $(document).ready( function () {
        $('#btnFilter').click(function(){
            var status = '{{ $status }}';
            var role = '{{ $role }}';
            var STATUS_SIAP_PUBLIKASI = '{{\App\Models\Data::STATUS_SIAP_PUBLIKASI}}';
            var STATUS_TERPUBLIKASI = '{{\App\Models\Data::STATUS_TERPUBLIKASI}}';
            
            var formData = {
                tahun : $('#tahun').val(),
                status : status,
                role : role,
                opd : $('#opd').val(),
            }
            if (role == 'produsen') {
                url = '{{route("filter_publikasi")}}'
            } else if (role == 'walidata'){
                url = '{{route("filter_publikasi")}}'
            }
            console.log(status);
            console.log(role);
            console.log(STATUS_SIAP_PUBLIKASI);
            console.log(formData);
            var divviewdata = document.getElementById('div-table');
            var divdatakosong = document.getElementById('data-kosong');
            divviewdata.style.display = 'block';
            divdatakosong.style.display = 'none';
            $('#isiTable').empty();
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function success(result) {
                    console.log(result);

                    var no = 1;
                    var first = true;
                    result.data.forEach(function(datas, i) {
                        // console.log(datas.publikasi.slug);
                        if(datas.length == null){
                        divdatakosong.style.display = 'none';
                        }
                    
                        var tableRow = '<tr>' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + datas.nama_data + '</td>' +
                            '<td>' + datas.jenis_data + '</td>' +
                            '<td>' + datas.opd.nama_opd + '</td>' +
                            '<td>' + datas.sumber_data + '</td>' +
                            '<td>' + (new Date(datas.updated_at)).toLocaleString('en-ID', { timeZone: 'Asia/Jakarta', hour12: true, day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + '</td>'+
                            '<td>' + '<div class="d-flex flex-row gap-2">' +
                                '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/publikasi/' + datas.id + '/organisasi"><i class="bi bi-info-circle"></i> Publikasi</a>'+
                                '<a class="btn btn-outline-success btn-sm" href="/export/' + datas.id + '"><i class="bi bi-file-zip"></i>Export</a>';
                            if(datas.status_id == STATUS_TERPUBLIKASI){
                                if (datas.publikasi && datas.publikasi.slug) {
                                    tableRow += '<a href="https://katalog-data.madiunkab.go.id/dataset/'+ datas.publikasi.slug +'" class="btn btn-outline-primary btn-sm" target="_new">CKAN <i class="bi bi-app-indicator"></i></a>';
                                }
                            } else {
                                tableRow += '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/detail-data/'+ datas.id +'"><i class="bi bi-eye"></i> Informasi Data</a>';
                            }

                        tableRow += '</div>' +
                                '</td>' + 
                            '</tr>';

                            $('#isiTable').append(tableRow);
                    })
                    
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
        var role = '{{ $role }}';
        var STATUS_SIAP_PUBLIKASI = '{{\App\Models\Data::STATUS_SIAP_PUBLIKASI}}';
        var STATUS_TERPUBLIKASI = '{{\App\Models\Data::STATUS_TERPUBLIKASI}}';
        
        var formData = {
        tahun : $('#tahun').val(),
        status : status,
        role : role,
        opd : $('#opd').val(),
        searchQuery: searchValue
        }
        if (role == 'produsen') {
        url = '{{ route("search_data") }}'
        } else if (role == 'walidata'){
        url = '{{ route("search_data") }}'
        }
        console.log(status);
        console.log(role);
        console.log(STATUS_SIAP_PUBLIKASI);
        console.log(formData);
        var divviewdata = document.getElementById('div-table');
        var divdatakosong = document.getElementById('data-kosong');
        divviewdata.style.display = 'block';
        divdatakosong.style.display = 'none';
        $('#isiTable').empty();
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        success: function success(result) {
        console.log(result);
        
        var no = 1;
        var first = true;
        result.data.forEach(function(datas, i) {
        // console.log(datas.publikasi.slug);
        if(datas.length == null){
        divdatakosong.style.display = 'none';
        }
        
        var tableRow = '<tr>' +
            '<td>' + (i + 1) + '</td>' +
            '<td>' + datas.nama_data + '</td>' +
            '<td>' + datas.jenis_data + '</td>' +
            '<td>' + datas.opd.nama_opd + '</td>' +
            '<td>' + datas.sumber_data + '</td>' +
            '<td>' + (new Date(datas.updated_at)).toLocaleString('en-ID', { timeZone: 'Asia/Jakarta', hour12: true, day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + '</td>'+
            '<td>' + '<div class="d-flex flex-row gap-2">' +
                    '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/publikasi/' + datas.id + '/organisasi"><i class="bi bi-info-circle"></i> Publikasi</a>'+
                    '<a class="btn btn-outline-success btn-sm" href="/export/' + datas.id + '"><i class="bi bi-file-zip"></i>Export</a>';
                    if(datas.status_id == STATUS_TERPUBLIKASI){
                    if (datas.publikasi && datas.publikasi.slug) {
                    tableRow += '<a href="https://katalog-data.madiunkab.go.id/dataset/'+ datas.publikasi.slug +'" class="btn btn-outline-primary btn-sm" target="_new">CKAN <i class="bi bi-app-indicator"></i></a>';
                    }
                    } else {
                    tableRow += '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/detail-data/'+ datas.id +'"><i class="bi bi-eye"></i> Informasi Data</a>';
                    }
        
                    tableRow += '</div>' +
                '</td>' +
            '</tr>';
        
        $('#isiTable').append(tableRow);
        })
        
        }
        })


        });
    });
</script>
@endpush