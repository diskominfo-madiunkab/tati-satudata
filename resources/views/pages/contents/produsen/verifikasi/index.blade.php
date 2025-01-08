@extends('pages.main.layout')

@section('content')
@include('sweetalert::alert')
@php
$role = auth()->user()->hasAnyRole('produsen') ? 'produsen' : 'walidata';
@endphp
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
                    @if($status == 'pemeriksaan')
                    <p>Halaman ini berisi daftar data yang berstatus proses verifikasi.</p>
                    @elseif ($status == 'revisi')
                    <p>Halaman ini berisi daftar data yang berstatus revisi.</p>
                    @else
                    <p>Halaman ini berisi daftar data yang berstatus siap publikasi.</p>
                    @endif

                    <ul class="nav nav-tabs nav-tabs-bordered d-flex text-center" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <a href="/data_produsen/verifikasi"
                                class="nav-link {{isset($status) && $status == 'pemeriksaan' ? 'active' : ''}} w-100"
                                id="verifikasi-tab"><i class="bi bi-pencil"></i> Proses Verifikasi</a>
                        </li>

                        <li class="nav-item flex-fill" role="presentation">
                            <a href="/data_produsen/verifikasi/revisi"
                                class="nav-link w-100 {{isset($status) && $status == 'revisi' ? 'active' : ''}}"
                                id="lengkap-tab"><i class="bi bi-x-circle"></i> Revisi</a>
                        </li>

                        <li class="nav-item flex-fill" role="presentation">
                            <a href="/data_produsen/verifikasi/siap-publikasi"
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
                                                    <option value="{{ $th->tahun }}">{{ $th->tahun }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if (Auth::user()->role_id == '2')
                                            <div class="col-md-6">
                                                <select id="opd" name="opd" class="form-select select2"
                                                    aria-label="Default select example">
                                                    <option value="" disabled selected hidden>Pilih OPD</option>
                                                    {{-- <option value="">Semua OPD</option> --}}
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
         url = "{{ route('produsen.verifikasi.index') }}";
       
    }else if (status === 'revisi') {
         url = "{{ route('produsen.verifikasi.revisi') }}";
      
    }else if (status === 'siap-publikasi') {
         url = "{{ route('produsen.verifikasi.sesuai') }}";
    } 
    return url;
    }
        $(document).ready(function() {
            function setFiltersFromLocalStorage() {
            if (localStorage.getItem('tahun')) {
            $('#tahun').val(localStorage.getItem('tahun')).trigger('change');
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
                if (full.status_id == 7 ) {
                    var verifikasiFields = [];
                    var hasBerkasCategory = false;
                    var hasIndikatorOrVariabelCategory = false;
                    var anyRejected = false;
                    var anyRejectedInBerkas = false;
                    
                    if (Array.isArray(full.verifikasi)) {
                    verifikasiFields = full.verifikasi.map(function(item) {
                    return {
                    field: item.field,
                    category: item.category,
                    accepted: item.accepted
                    };
                    });
                    
                    
                    hasBerkasCategory = verifikasiFields.some(function(verifikasi) {
                    return verifikasi.category === "berkas";
                    });

                    anyRejectedInBerkas = verifikasiFields.some(function(verifikasi) {
                    return verifikasi.category === "berkas" && verifikasi.accepted === false;
                    });
                    
                    hasIndikatorOrVariabelCategory = verifikasiFields.some(function(verifikasi) {
                    return verifikasi.category === "indikator" || verifikasi.category === "variabel";
                    });

                    if (hasIndikatorOrVariabelCategory) {
                    anyRejected = verifikasiFields.some(function(verifikasi) {
                    return (verifikasi.category === "indikator" || verifikasi.category === "variabel") && verifikasi.accepted === false;
                    });
                    }
                    }
                    
                    var indikatorVariabelButtonClass = 'btn-success'; // default
                    if (hasIndikatorOrVariabelCategory) {
                    indikatorVariabelButtonClass = anyRejected ? 'btn-danger' : 'btn-success';
                    }
                    
                   var berkasButtonClass = hasBerkasCategory ? (anyRejectedInBerkas ? 'btn-danger' : 'btn-success') : 'btn-danger';
                   // Button for berkas category
                    // if (hasBerkasCategory) {
                    buttonsHtml += '<a style="width: 100px" class="btn ' + berkasButtonClass + ' btn-sm" href="/data_' + role + '/pengumpulan/' + full.id + '/data">';
                        buttonsHtml += '<i class="bi bi-files"></i> ' + (role === 'produsen' ? 'Unggah Berkas' : 'Berkas') + '</a>';
                    // }
                    // Button for indikator or variabel category
                    // if (hasIndikatorOrVariabelCategory) {
                    buttonsHtml += '<a style="width: 100px" class="btn ' + indikatorVariabelButtonClass + ' btn-sm" href="/data_' + role + '/pengumpulan/' + full.id + '/' + full.jenis_data.toLowerCase() + '">';
                        buttonsHtml += '<i class="bi bi-bar-chart"></i> Metadata ' + full.jenis_data + '</a>';
                    // }
                    if (role == ' produsen' &&  full.status_id == 4 || full.status_id == 1 || full.status_id == 7) {
                        // if (berkasButtonClass == 'btn-success' && indikatorVariabelButtonClass === 'btn-success'){
                        buttonsHtml += '<a style="width: 100px" class="btn btn-verify btn-outline-primary" href="/data_produsen/pengumpulan/' + full.id + '/verifikasi">Ajukan Verifikasi';
                        buttonsHtml += '<i class="bi bi-check"></i></a>';
                        // }
                    }
                } else if(full.status_id == 9 || full.status_id == 8){
                    buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_produsen/detail-data/' + full.id + '">';
                        buttonsHtml += '<i class="bi bi-eye"></i> Detail Data</a>';
                }else {
                     if (full.status === 'siap-publikasi') {
                        buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_produsen/verifikasi/' + full.id + '/berkas">';
                        buttonsHtml += '<i class="bi bi-files"></i> Berkas</a>';
                        
                        if (['indikator', 'variabel'].includes(full.jenis_data.toLowerCase())) {
                            buttonsHtml += '<a class="btn btn-outline-success btn-sm" href="/data_produsen/verifikasi/' + full.id + '/' + full.jenis_data.toLowerCase() + '">';
                            buttonsHtml += '<i class="bi bi-bar-chart"></i> Metadata ' + full.jenis_data + '</a>';
                        }
                    }else{
                        
                    }
                    buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_produsen/detail-data/' + full.id + '">';
                    buttonsHtml += '<i class="bi bi-eye"></i> Detail Data</a>';
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

    // Menambahkan event listener pada tombol verifikasi di luar tabel
    $(document).on('click', 'a.btn-verify', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');

        swalWithBootstrapButtons.fire({
            title: 'Apakah Anda yakin?',
            text: 'Pastikan semua metadata sudah terisi lengkap sebelum memasuki proses verifikasi!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ajukan Verifikasi!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success: function(response) {
                        Swal.fire(response.ok ? 'Sukses' : 'Gagal', response.message, response.ok ? 'success' : 'error')
                        .then(() => {
                            // Refresh halaman setelah berhasil
                            if (response.ok) {
                                window.location.reload();
                            }
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi galat saat memproses permintaan', 'error');
                    }
                });
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
            var tdContent = '{{ $role == "produsen" ? "Unggah Berkas" : "Detail Berkas" }}';
            var STATUS_SIAP_PUBLIKASI = '{{\App\Models\Data::STATUS_SIAP_PUBLIKASI}}';
            var STATUS_REVISI = '{{\App\Models\Data::STATUS_REVISI}}';
            var STATUS_PROSES_PENGUMPULAN = '{{\App\Models\Data::STATUS_PROSES_PENGUMPULAN}}';
            var STATUS_SETUJU = '{{\App\Models\Data::STATUS_SETUJU}}';
            
            var formData = {
                tahun : $('#tahun').val(),
                status : status,
                role : role,
                opd : $('#opd').val(),
            }
            if (role == 'produsen') {
                url = '{{route("filter_verifikasi")}}'
            } else if (role == 'walidata'){
                url = '{{route("filter_verifikasi_walidata")}}'
            }
            console.log(status);
            console.log(role);
            console.log(STATUS_REVISI);
            console.log(STATUS_SIAP_PUBLIKASI);
            console.log(STATUS_PROSES_PENGUMPULAN);
            console.log(STATUS_SETUJU);
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

                        
                        tableRow += '<td>' + '<div class="d-flex flex-row gap-2">';
                        if (datas.status_id == STATUS_REVISI || datas.status_id == STATUS_SIAP_PUBLIKASI) {
                            tableRow += 
                                '<a class="btn btn-outline-primary btn-sm" href="/data_'+role+'/pengumpulan/'+datas.id+'/data"><i class="bi bi-cloud-upload"></i>'+tdContent+'</a>' +
                                '<a class="btn btn-outline-primary btn-sm" href="/data_'+role+'/pengumpulan/'+datas.id+'/standar"><i class="bi bi-sim-fill"></i> Standar Data</a>' +
                                '<a class="btn btn-outline-success btn-sm" href="/data_'+role+'/pengumpulan/'+datas.id+'/' + datas.jenis_data.toLowerCase() + '"><i class="bi bi-bar-chart"></i> Metadata '+datas.jenis_data+'</a>';
                                // '<a class="btn btn-outline-success btn-sm" href="/data_'+role+'/pengumpulan/'+datas.id+'/kegiatan"><i class="bi bi-activity"></i> Metadata Kegiatan</a>';
                            if ( role == 'produsen' && (datas.status_id == STATUS_PROSES_PENGUMPULAN || datas.status_id == STATUS_SETUJU || datas.status_id == STATUS_REVISI)) {
                                tableRow += '<a class="btn btn-verify btn-outline-success" href="/data_produsen/pengumpulan/'+datas.id+'/verifikasi">Ajukan Verifikasi<i class="bi bi-check"></i></a>';
                            }
                        
                        } else {
                            if (typeof status !== 'undefined' && status == 'siap-publikasi') {
                                tableRow += '<a class="btn btn-outline-primary btn-sm" href="/data_produsen/verifikasi/'+datas.id+'/berkas"><i class="bi bi-cloud-upload"></i> Berkas</a>' ;
                                if (datas.jenis_data.toLowerCase() == 'indikator' || datas.jenis_data.toLowerCase() == 'variabel'){
                                tableRow += '<a class="btn btn-outline-success btn-sm" href="/data_produsen/verifikasi/'+datas.id+'/'+datas.jenis_data.toLowerCase()+'"><i class="bi bi-bar-chart"></i> Metadata '+datas.jenis_data.toLowerCase()+'</a>' ;
                                }
                            }
                           
                               tableRow += '<a class="btn btn-outline-primary btn-sm" href="/data_produsen/detail-data/' + datas.id + '"><i class="bi bi-eye"></i> Detail' + '</a>'; 
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
        var tdContent = '{{ $role == "produsen" ? "Unggah Berkas" : "Detail Berkas" }}';
        var STATUS_SIAP_PUBLIKASI = '{{\App\Models\Data::STATUS_SIAP_PUBLIKASI}}';
        var STATUS_REVISI = '{{\App\Models\Data::STATUS_REVISI}}';
        var STATUS_PROSES_PENGUMPULAN = '{{\App\Models\Data::STATUS_PROSES_PENGUMPULAN}}';
        var STATUS_SETUJU = '{{\App\Models\Data::STATUS_SETUJU}}';
        
        var formData = {
        tahun : $('#tahun').val(),
        status : status,
        role : role,
        opd : $('#opd').val(),
        searchQuery: searchValue
        }
        if (role == 'produsen') {
        url = '{{route("search_data_produsen")}}'
        } else if (role == 'walidata'){
        url = '{{route("search_data")}}'
        }
        console.log(status);
        console.log(role);
        console.log(STATUS_REVISI);
        console.log(STATUS_SIAP_PUBLIKASI);
        console.log(STATUS_PROSES_PENGUMPULAN);
        console.log(STATUS_SETUJU);
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
$('#isiTable').empty();
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
    '<td>' + (new Date(datas.updated_at)).toLocaleString('en-ID', { timeZone: 'Asia/Jakarta', hour12: true, day:
        '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + '</td>';


    tableRow += '<td>' + '<div class="d-flex flex-row gap-2">';
            if (datas.status_id == STATUS_REVISI || datas.status_id == STATUS_SIAP_PUBLIKASI) {
            tableRow +=
            '<a class="btn btn-outline-primary btn-sm" href="/data_'+role+'/pengumpulan/'+datas.id+'/data"><i class="bi bi-cloud-upload"></i>'+tdContent+'</a>' +
            '<a class="btn btn-outline-primary btn-sm" href="/data_'+role+'/pengumpulan/'+datas.id+'/standar"><i class="bi bi-sim-fill"></i> Standar Data</a>' +
            '<a class="btn btn-outline-success btn-sm" href="/data_'+role+'/pengumpulan/'+datas.id+'/' + datas.jenis_data.toLowerCase() + '"><i class="bi bi-bar-chart"></i> Metadata '+datas.jenis_data+'</a>';
            // '<a class="btn btn-outline-success btn-sm" href="/data_'+role+'/pengumpulan/'+datas.id+'/kegiatan"><i class="bi bi-activity"></i> Metadata Kegiatan</a>';
            if ( role == 'produsen' && (datas.status_id == STATUS_PROSES_PENGUMPULAN || datas.status_id == STATUS_SETUJU
            || datas.status_id == STATUS_REVISI)) {
            tableRow += '<a class="btn btn-verify btn-outline-success" href="/data_produsen/pengumpulan/'+datas.id+'/verifikasi">Ajukan Verifikasi<i class="bi bi-check"></i></a>';
            }

            } else {
            if (typeof status !== 'undefined' && status == 'siap-publikasi') {
            tableRow += '<a class="btn btn-outline-primary btn-sm" href="/data_produsen/verifikasi/'+datas.id+'/berkas"><i class="bi bi-cloud-upload"></i> Berkas</a>' ;
            if (datas.jenis_data.toLowerCase() == 'indikator' || datas.jenis_data.toLowerCase() == 'variabel'){
            tableRow += '<a class="btn btn-outline-success btn-sm" href="/data_produsen/verifikasi/'+datas.id+'/'+datas.jenis_data.toLowerCase()+'"><i class="bi bi-bar-chart"></i> Metadata '+datas.jenis_data.toLowerCase()+'</a>' ;
            }
            }

            tableRow += '<a class="btn btn-outline-primary btn-sm" href="/data_produsen/detail-data/' + datas.id + '"><i class="bi bi-eye"></i> Detail' + '</a>';
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