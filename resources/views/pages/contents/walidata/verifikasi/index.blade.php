@extends('pages.main.layout')

@section('content')
@php
$role = auth()->user()->hasAnyRole('produsen') ? 'produsen' : 'walidata';
@endphp
@include('sweetalert::alert')
<div class="pagetitle">
    <h1>Daftar Pemeriksaan Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Pemeriksaan Data</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Data</h5>

                    <ul class="nav nav-tabs nav-tabs-bordered d-flex text-center" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <a href="/data_walidata/verifikasi"
                                class="nav-link {{isset($status) && $status == 'pemeriksaan' ? 'active' : ''}} w-100"
                                id="verifikasi-tab"><i class="bi bi-pencil"></i> Proses Verifikasi</a>
                        </li>

                        <li class="nav-item flex-fill" role="presentation">
                            <a href="/data_walidata/verifikasi/revisi"
                                class="nav-link w-100 {{isset($status) && $status == 'revisi' ? 'active' : ''}}"
                                id="lengkap-tab"><i class="bi bi-x-circle"></i> Revisi</a>
                        </li>

                        <li class="nav-item flex-fill" role="presentation">
                            <a href="/data_walidata/verifikasi/siap-publikasi"
                                class="nav-link w-100 {{isset($status) && $status == 'siap-publikasi' ? 'active' : ''}}"
                                id="lengkap-tab"><i class="bi bi-check-lg"></i> Telah Sesuai</a>
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
                                            @if (Auth::user()->role_id == '2'|| Auth::user()->role_id == '4' ||
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
                                    <tbody id="isiTable" style="text-align: left;
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
    if (status === 'pemeriksaan') {
         url = "{{ url('/data_walidata/verifikasi') }}";
       
    }else if (status === 'revisi') {
         url = "{{ route('walidata.verifikasi.revisi') }}";
      
    }else if (status === 'siap-publikasi') {
         url = "{{ url('data_walidata/verifikasi/siap-publikasi') }}";
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
                if (full.status_id == 6) {
                    buttonsHtml += '<a style="width: 100px" class="btn btn-outline-primary btn-sm" href="/data_walidata/verifikasi/' + full.id + '/berkas">';
                    buttonsHtml += '<i class="bi bi-file-binary-fill"></i> Verifikasi Berkas</a>';
                    buttonsHtml += '<a style="width: 100px" class="btn btn-outline-info btn-sm" href="/data_walidata/verifikasi/' + full.id + '/' + full.jenis_data.toLowerCase() + '">';
                    buttonsHtml += '<i class="bi bi-bar-chart"></i> Verifikasi Metadata ' + full.jenis_data + '</a>';
                    // Check if verifikasi count is not zero and skip certain fields
                    // var excludeFields = ["kode", "konsep", "definisi", "klasifikasi", "ukuran", "satuan","komposit"];
                    var refFields = [
                    "konsep",
                    "definisi",
                    "interpretasi",
                    "metode",
                    "komposit",
                    "ukuran",
                    "satuan",
                    "klasifikasi_penyajian",
                    "nama_variabel_pembangun",
                    "level_estimasi",
                    "umum",
                    // "berkas"
                    ];
                    if (full.jenis_data == "Variabel") {
                    refFields = [
                    "alias",
                    "konsep",
                    "definisi",
                    "referensi_pemilihan",
                    "referensi_waktu",
                    "tipe_data",
                    "ukuran",
                    "satuan",
                    "klasifikasi_isian",
                    "aturan_validasi",
                    "kalimat_pertanyaan",
                    "umum",
                    // "berkas"
                    // Tambahkan field lainnya sesuai kebutuhan
                    ];
                    }
                    var verifikasiFields = full.verifikasi.map(function(item) {
                    return item.field;
                    });
                    
                    // Memeriksa apakah ada field yang berupa angka
                    var hasNumberField = verifikasiFields.some(function(field) {
                    return !isNaN(field);
                    });
                    
                    // Memeriksa apakah semua refFields ada di verifikasiFields atau ada field berupa angka
                    var allFieldsVerified = refFields.every(function(field) {
                    return verifikasiFields.includes(field);
                    });
                    
                    // Menambahkan pengecekan untuk field dengan category "berkas"
                    var berkasFieldExists = full.verifikasi.some(function(item) {
                    return item.category === "berkas";
                    });

                    // console.log(allFieldsVerified, hasNumberField, berkasFieldExists);
                    
                    // if ((allFieldsVerified || hasNumberField) && berkasFieldExists) {
                    // console.log(allFieldsVerified,berkasFieldExists);
                    
                    if (allFieldsVerified && berkasFieldExists) {
                    buttonsHtml += '<a style="width: 100px" class="btn btn-outline-primary btn-sm btn-action" href="#" data-status-url="/data_walidata/verifikasi/' + full.id + '/status" data-complete-url="/data_walidata/verifikasi/' + full.id + '/complete">Selesaikan?</a>';
                    }
                } else {
                    if(full.status_id == 8 || full.status_id == 9){
                    buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/detail-data/' + full.id + '">';
                    buttonsHtml += '<i class="bi bi-eye"></i> Detail Data</a>';
                    }else{
                    buttonsHtml += '<a style="width: 100px" class="btn btn-outline-primary btn-sm" href="/data_walidata/verifikasi/' + full.id + '/berkas"><i class="bi bi-file-binary-fill"></i> Berkas</a>';
                        if (['indikator', 'variabel'].includes(full.jenis_data.toLowerCase())) {
                            buttonsHtml += '<a class="btn btn-outline-success btn-sm" href="/data_walidata/verifikasi/' + full.id + '/' + full.jenis_data.toLowerCase() + '">';
                            buttonsHtml += '<i class="bi bi-bar-chart"></i> Metadata ' + full.jenis_data + '</a>';
                        }
                    buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/detail-data/' + full.id + '">';
                    buttonsHtml += '<i class="bi bi-eye"></i> Detail Data</a>';
                    }
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

            $(document).on('click', 'a.btn-action', function (e) {
                e.preventDefault();
                Swal.showLoading();
                let completeUrl = $(this).data('completeUrl');
                console.log();
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
            });
        });
</script>


<script>
    $(document).ready( function () {
        $('#btnFilter').click(function(){
            var status = '{{ $status }}';
            var role = '{{ $role }}';
            var STATUS_PROSES_VERIFIKASI = '{{\App\Models\Data::STATUS_PROSES_VERIFIKASI}}';
            
            var formData = {
                tahun : $('#tahun').val(),
                status : status,
                role : role,
                opd : $('#opd').val(),
            }
            if (role == 'produsen') {
                url = '{{route("filter_pengumpulan")}}'
            } else if (role == 'walidata'){
                url = '{{route("filter_verifikasi_walidata")}}'
            }
            console.log(status);
            console.log(role);
            console.log(STATUS_PROSES_VERIFIKASI);
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
                        if(datas.length == null){
                        divdatakosong.style.display = 'none';
                        }
                    
                        var tableRow = '<tr>' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + datas.nama_data + '</td>' +
                            '<td>' + datas.jenis_data + '</td>' +
                            '<td>' + datas.opd.nama_opd + '</td>' +
                            '<td>' + datas.sumber_data + '</td>' +
                            '<td>' + (new Date(datas.updated_at)).toLocaleString('en-ID', { timeZone: 'Asia/Jakarta', hour12: true, day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + '</td>';

                        tableRow += '<td>'+ '<div class="d-flex flex-row gap-2">';
                        if (datas.status_id == STATUS_PROSES_VERIFIKASI) {
                            tableRow += 
                                '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/verifikasi/' + datas.id + '/berkas"><i class="bi bi-file-binary-fill"></i> Verifikasi Berkas' + '</a>' +
                                '<a class="btn btn-outline-info btn-sm" href="/data_walidata/verifikasi/' + datas.id + '/' + datas.jenis_data.toLowerCase() + '"><i class="bi bi-bar-chart"></i> Verifikasi Metadata ' + datas.jenis_data + '' + '</a>' +
                                '<a class="btn btn-outline-primary btn-sm btn-action" href="#" data-status-url="/data_walidata/verifikasi/' + datas.id + '/status" data-complete-url="/data_walidata/verifikasi/' + datas.id + '/complete"> Selesaikan?' + '</a>';
                        
                        } else {
                            tableRow +=
                                
                                '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/verifikasi/' + datas.id + '/berkas"><i class="bi bi-file-binary-fill"></i> Verifikasi Berkas' +
                                '</a>' ;
                                if (datas.jenis_data.toLowerCase() == 'indikator' || datas.jenis_data.toLowerCase() == 'variabel'){
                                tableRow += '<a class="btn btn-outline-success btn-sm" href="/data_walidata/pengumpulan/' + datas.id + '/' + datas.jenis_data.toLowerCase() + '"><i class="bi bi-eye"></i> Metadata ' + datas.jenis_data + '</a>' ;
                                }
                        
                                tableRow += '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/detail-data/' + datas.id + '"><i class="bi bi-eye"></i> Detail' + '</a>';
                        }
                        tableRow += '</td>' + '</div>' ;

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
                var STATUS_PROSES_VERIFIKASI = '{{\App\Models\Data::STATUS_PROSES_VERIFIKASI}}';
                
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
                console.log(STATUS_PROSES_VERIFIKASI);
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
                if(datas.length == null){
                divdatakosong.style.display = 'none';
                }
                
                var tableRow = '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + datas.nama_data + '</td>' +
                    '<td>' + datas.jenis_data + '</td>' +
                    '<td>' + datas.opd.nama_opd + '</td>' +
                    '<td>' + datas.sumber_data + '</td>' +
                    '<td>' + (new Date(datas.updated_at)).toLocaleString('en-ID', { timeZone: 'Asia/Jakarta', hour12: true, day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + '</td>';
                
                    tableRow += '<td>'+ '<div class="d-flex flex-row gap-2">';
                            if (datas.status_id == STATUS_PROSES_VERIFIKASI) {
                            tableRow +=
                            '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/verifikasi/' + datas.id + '/berkas"><i class="bi bi-file-binary-fill"></i> Verifikasi Berkas' + '</a>' +
                            '<a class="btn btn-outline-info btn-sm" href="/data_walidata/verifikasi/' + datas.id + '/' + datas.jenis_data.toLowerCase() + '"><i class="bi bi-bar-chart"></i> Verifikasi Metadata ' + datas.jenis_data + '' + '</a>' +
                            '<a class="btn btn-outline-primary btn-sm btn-action" href="#" data-status-url="/data_walidata/verifikasi/' + datas.id + '/status" data-complete-url="/data_walidata/verifikasi/' + datas.id + '/complete"> Selesaikan?' + '</a>';
                
                            } else {
                            tableRow +=
                
                            '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/verifikasi/' + datas.id + '/berkas"><i class="bi bi-file-binary-fill"></i> Verifikasi Berkas' + '</a>' ;
                            if (datas.jenis_data.toLowerCase() == 'indikator' || datas.jenis_data.toLowerCase() == 'variabel'){
                            tableRow += '<a class="btn btn-outline-success btn-sm" href="/data_walidata/pengumpulan/' + datas.id + '/' + datas.jenis_data.toLowerCase() + '"><i class="bi bi-eye"></i> Metadata ' + datas.jenis_data + '</a>' ;
                            }
                
                            tableRow += '<a class="btn btn-outline-primary btn-sm" href="/data_walidata/detail-data/' + datas.id + '"><i class="bi bi-eye"></i> Detail' + '</a>';
                            }
                            tableRow += '</td>' + '</div>' ;
                
                    $('#isiTable').append(tableRow);
                    })
                
                    }
                    })

        });
    });
</script>
@endpush