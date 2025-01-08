@extends('pages.main.layout')

@section('content')
@php
$role = auth()->user()->hasAnyRole('produsen') ? 'produsen' : 'walidata';
@endphp

<div class="pagetitle">
    <h1>Daftar Standar Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Standar Data</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filter Data</h5>

                </div>
            </div> --}}

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Standar Data</h5>

                    <ul class="nav nav-tabs nav-tabs-bordered d-flex text-center" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <a href="/data_{{$role}}/standar-data"
                                class="nav-link w-100 {{$status == 'proses' ? 'active' : ''}}" id="proses-tab"><i
                                    class="bi bi-pencil-square"></i> Proses Verifikasi</a>
                        </li>

                        <li class="nav-item flex-fill" role="presentation">
                            <a href="/data_{{$role}}/standar-data/revisi"
                                class="nav-link w-100 {{$status == 'revisi' ? 'active' : ''}}" id="revisi-tab"><i
                                    class="bi bi-x-circle"></i> Revisi</a>
                        </li>

                        <li class="nav-item flex-fill" role="presentation">
                            <a href="/data_{{$role}}/standar-data/setuju"
                                class="nav-link w-100 {{isset($status) && $status == 'setuju' ? 'active' : ''}}"
                                id="setuju-tab"><i class="bi bi-check-all"></i> Telah Disetujui</a>
                        </li>
                    </ul>

                    <div class="tab-content p-2">
                        <div class="tab-pane active" id="proses-tab">
                            {{-- @if(!isset($status))
                            <p>Halaman ini berisi daftar data yang telah disetujui dan tahap proses pengumpulan.</p>
                            @else
                            <p>Halaman ini berisi daftar data yang siap untuk diverifikasi.</p>
                            @endif --}}
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
                                            <th scope="col">Tahun</th>
                                            <th scope="col">Produsen Data</th>
                                            <th scope="col">Sumber Referensi</th>
                                            <th scope="col">Status</th>
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

{{-- @push('js')
<script>
    $(document).ready(function(){
        $(".filter").on('change', function(){
            let tahun = $("#tahun").val()
            let opd = $("#opd").val()
        console.log([tahun,opd])
        })
    })
</script>
@endpush --}}

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
    if (status === 'proses') {
        if(role === 'produsen'){
            url = "{{ route('produsen.standar-data.index') }}";
        }else{
            url = "{{ route('walidata.standar-data.index') }}";
        }
    } else if (status === 'revisi'){
        if(role === 'produsen'){
        url = "{{ route('produsen.standar-data.revisi') }}";
        }else{
        url = "{{ route('walidata.standar-data.revisi') }}";
        }
     } else if (status === 'setuju'){
        if(role === 'produsen'){
        url = "{{ route('produsen.standar-data.setuju') }}";
        }else{
        url = "{{ route('walidata.standar-data.setuju') }}";
        }
    } else {
        if(role === 'produsen'){
        url = "{{ route('pengumpulan.produsen') }}";
        }else{
        url = "{{ route('pengumpulan.wali') }}";
        }
        
    }
    return url;
    }

// console.log(getStatusUrl());
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
    $(document).ready(function() {
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
            { "data": "tahun" },
            { "data": "opd.nama_opd" }, // Assuming 'opd' is a nested property of your data
            { "data": "sumber_data" },
            {
            "data": "status",
            "orderable": false,
            "searchable": false,
            "render": function(progress, type, full, meta) {
            var buttonsHtml = '<div class="d-flex flex-column gap-2">';
              if (full.status_id == 13) {
                buttonsHtml += '<span class="badge rounded-pill bg-secondary">Proses Verifikasi</span>';
                } else if (full.status_id == 10){
                buttonsHtml += '<span class="badge rounded-pill bg-success">Setuju</span>';
                }else if (full.status_id == 12){
                buttonsHtml += '<span class="badge rounded-pill bg-danger">Revisi</span>';
                }
                else if (full.status_id == 1 || full.status_id == 13){
                    buttonsHtml += '<span class="badge rounded-pill bg-info">Proses Pembuatan</span>';
                }else {
                    buttonsHtml += '<span class="badge rounded-pill bg-success">Setuju</span>';

                }
                buttonsHtml += '</div>';
            
            return buttonsHtml;
            }
            },
            {
            "data": "prioritas",
            "orderable": false,
            "searchable": false,
            "render": function(progress, type, full, meta) {
            var buttonsHtml = '<div class="d-flex flex-column gap-2">';
                // buttonsHtml += full.status_id;
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
                        // buttonsHtml += '<p>'+full.status_id+'</p>';
               
                // console.log(full.data_prioritas);
                if(role == 'walidata'){
                    if(full.status_id == 13){
                        buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/standar-data/' + full.id + '/standar">';
                        buttonsHtml +='<i class="bi bi-check2-circle"></i>Verifikasi</a>' ;
                        var refFields = [
                        "kode",
                        "konsep",
                        "definisi",
                        "klasifikasi",
                        "ukuran",
                        "satuan"
                        // "berkas"
                        ];
                       var verifikasiFields = full.verifikasi.map(function(item) {
                    return item.field;
                    });
                    
                    // Memeriksa apakah semua refFields ada di verifikasiFields dan memfilter field yang relevan
                    var allFieldsVerified = refFields.every(function(field) {
                    return verifikasiFields.includes(field);
                    });
                    
                    if (allFieldsVerified) {
                        buttonsHtml += '<a style="width: 100px" class="btn btn-outline-primary btn-sm btn-action" href="#" data-status-url="/data_walidata/standar-data/verifikasi/' + full.id + '/status" data-complete-url="/data_walidata/standar-data/verifikasi/' + full.id + '/complete">Selesaikan?</a>'
                        }
                    } else if(full.status_id == 12){
                        buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/standar-data/' + full.id + '/standar">';
                        buttonsHtml += '<i class="bi bi-sim-fill"></i>Standar Data</a>';
                    } else if(full.status_id == 10){
                        buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/detail-data/' + full.id + '">';
                        buttonsHtml +='<i class="bi bi-eye"></i> Detail Data</a>' ;
                    } else {
                        buttonsHtml += '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/detail-data-standar/' + full.id + '">';
                            buttonsHtml +='<i class="bi bi-eye"></i> Detail Data</a>' ;
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
    $(function() {
            $('#id-table').on('click', 'a.btn-action', function (e) {
                e.preventDefault();
                Swal.showLoading();
                let completeUrl = $(this).data('completeUrl');
                console.log(completeUrl);
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
</script>
@endpush

@push('js')
<script>
    $(document).ready( function () {
        $('#btnFilter').click(function(){
            var status = '{{ $status }}';
            var role = '{{ $role }}';
            var STATUS_SETUJU = '{{\App\Models\Data::STATUS_SETUJU}}';
            var STATUS_PROSES_PENGUMPULAN = '{{\App\Models\Data::STATUS_PROSES_PENGUMPULAN}}';
            var STATUS_REVISI = '{{\App\Models\Data::STATUS_REVISI}}';
            
            var formData = {
                tahun : $('#tahun').val(),
                status : status,
                role : role,
                opd : $('#opd').val(),
            }
            if (role == 'produsen') {
                url = '{{route("filter_pengumpulan")}}'
            } else if (role == 'walidata'){
                url = '{{route("filter_pengumpulan_walidata")}}'
            }
            console.log(status);
            console.log(role);
            console.log(STATUS_PROSES_PENGUMPULAN);
            console.log(STATUS_REVISI);
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
                        if(datas.standar == null){
                        var standar = 0;
                        }else{
                        var standar = 0;
                        for(var key in datas.standar){
                        standar++;
                        }
                        }

                        if(datas.indikator == null){
                            var indikator = 0
                        }else{
                           var indikator = 0;
                           for(var key in datas.indikator){
                                indikator++;
                           }
                        }
                        if(datas.variabel == null){
                        var variabel = 0;
                        }else{
                        var variabel = 0;
                        for(var key in datas.variabel){
                        variabel++;
                        }
                        }

                        if(datas.berkas == null){
                        var berkas = 0;
                        }else{
                        var berkas = 0;
                        for(var key in datas.berkas){
                        berkas++;
                        }
                        } 
                        var progress = datas.progress;
                        var progress_bar = 0;

                        progress = progress != 0 ? progress : 0;
                        
                        if (progress >= 100) {
                         progress_bar = Math.min(100, progress);
                        }
                        
                        if (standar != 0) {
                        progress += 15;
                        }
                        
                        if (indikator !=0  && variabel == 0) {
                        progress += 25;
                        }
                        
                        if (indikator == 0 && variabel != 0) {
                        progress += 25;
                        }
                        
                        if (berkas != 0) {
                        progress += 50;
                        }
                        
                         progress_bar = Math.min(100, progress);
                         console.log([progress_bar]);

                        // var statusId = '';
                        // if (datas.status_id == STATUS_SETUJU) {
                        //     statusId = 'Proses Pengumpulan';
                        // } else {
                        //     statusId = 'Telah Lengkap';
                        // }


                        var classProgress = '<div class="progress"> <div class="progress-bar progress-bar-striped bg-primary progress-bar-animated" role="progressbar" style="width:'+progress_bar+'%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" title="Total:'+progress_bar+'%"></div> </div>';

                        var tableRow = '<tr>' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + datas.nama_data + '</td>' +
                            '<td>' + datas.jenis_data + '</td>' +
                            '<td>' + datas.tahun + '</td>' +
                            '<td>' + datas.opd.nama_opd + '</td>' +
                            '<td>' + datas.sumber_data + '</td>' +
                            '<td>' + classProgress +'</td>';
                        tableRow += '<td>';
                        if (datas.status_id == STATUS_SETUJU || datas.status_id == STATUS_PROSES_PENGUMPULAN){
                            tableRow += '<div class="d-flex flex-column gap-2">' +
                            '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/pengumpulan/' + datas.id + '/data"><i class="bi bi-cloud-upload"></i>';
                                if(role == 'produsen' || datas.status_id != STATUS_SETUJU || datas.status_id != STATUS_PROSES_PENGUMPULAN || datas.status_id != STATUS_REVISI){
                                    tableRow += 'Input Data';
                                }else{
                                    tableRow += 'Detail Data';
                                }
                               tableRow += '</a>' +
                            '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/pengumpulan/' + datas.id + '/standar"><i class="bi bi-sim-fill"></i> Standar Data</a>' +
                            '<a class="btn btn-outline-success btn-sm" href="/data_' + role + '/pengumpulan/' + datas.id + '/' + datas.jenis_data.toLowerCase() + '"><i class="bi bi-bar-chart"></i> Metadata ' + datas.jenis_data + '</a>';
                              if (typeof status !== 'undefined' && role == 'produsen' && progress_bar >= 60 && (datas.status_id ==
                            STATUS_PROSES_PENGUMPULAN || datas.status_id == STATUS_SETUJU || datas.status_id == STATUS_REVISI)){
                            tableRow += '<a class="btn btn-verify btn-outline-success" href="/data_produsen/pengumpulan/' + datas.id + '/verifikasi">Siap Verifikasi<i class="bi bi-check"></i></a>';
                            }
                            tableRow += '</div>';
                        }else{
                            tableRow += '<div class="d-flex flex-column gap-2">' + 
                                '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/pengumpulan/' + datas.id + '/data"><i class="bi bi-cloud-upload"></i> Upload Berkas</a>' +
                            '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/detail-data/' + datas.id + '"><i class="bi bi-eye"></i> Detail</a>';
                        }
                        tableRow += 
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
        var role = '{{ $role }}';
        var STATUS_SETUJU = '{{\App\Models\Data::STATUS_SETUJU}}';
        var STATUS_PROSES_PENGUMPULAN = '{{\App\Models\Data::STATUS_PROSES_PENGUMPULAN}}';
        var STATUS_REVISI = '{{\App\Models\Data::STATUS_REVISI}}';
        
        var formData = {
        tahun : $('#tahun').val(),
        status : status,
        role : role,
        opd : $('#opd').val(),
        searchQuery: searchValue
        }
        if (role == 'produsen') {
        url = '{{ route("search_data_produsen") }}'
        } else if (role == 'walidata'){
        url = '{{ route("search_data") }}'
        }
        console.log(status);
        console.log(role);
        console.log(STATUS_PROSES_PENGUMPULAN);
        console.log(STATUS_REVISI);
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
        $('#isiTable').empty();
        console.log(result);
        
        
        var no = 1;
        var first = true;
        result.data.forEach(function(datas, i) {
        if(datas.standar == null){
        var standar = 0;
        }else{
        var standar = 0;
        for(var key in datas.standar){
        standar++;
        }
        }
        
        if(datas.indikator == null){
        var indikator = 0
        }else{
        var indikator = 0;
        for(var key in datas.indikator){
        indikator++;
        }
        }
        if(datas.variabel == null){
        var variabel = 0;
        }else{
        var variabel = 0;
        for(var key in datas.variabel){
        variabel++;
        }
        }
        
        if(datas.berkas == null){
        var berkas = 0;
        }else{
        var berkas = 0;
        for(var key in datas.berkas){
        berkas++;
        }
        }
        var progress = datas.progress;
        var progress_bar = 0;
        
        progress = progress != 0 ? progress : 0;
        
        if (progress >= 100) {
        progress_bar = Math.min(100, progress);
        }
        
        if (standar != 0) {
        progress += 15;
        }
        
        if (indikator !=0 && variabel == 0) {
        progress += 25;
        }
        
        if (indikator == 0 && variabel != 0) {
        progress += 25;
        }
        
        if (berkas != 0) {
        progress += 50;
        }
        
        progress_bar = Math.min(100, progress);
        console.log([progress_bar]);
        
        // var statusId = '';
        // if (datas.status_id == STATUS_SETUJU) {
        // statusId = 'Proses Pengumpulan';
        // } else {
        // statusId = 'Telah Lengkap';
        // }
        
        
        var classProgress = '<div class="progress"> <div class="progress-bar progress-bar-striped bg-primary progress-bar-animated" role="progressbar" style="width:'+progress_bar+'%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" title="Total:'+progress_bar+'%"></div></div>';
        
        var tableRow = '<tr>' +
            '<td>' + (i + 1) + '</td>' +
            '<td>' + datas.nama_data + '</td>' +
            '<td>' + datas.jenis_data + '</td>' +
            '<td>' + datas.tahun + '</td>' +
            '<td>' + datas.opd.nama_opd + '</td>' +
            '<td>' + datas.sumber_data + '</td>' +
            '<td>' + classProgress +'</td>';
            tableRow += '<td>';
                if (datas.status_id == STATUS_SETUJU || datas.status_id == STATUS_PROSES_PENGUMPULAN){
                tableRow += '<div class="d-flex flex-column gap-2">' +
                    '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/pengumpulan/' + datas.id + '/data"><i class="bi bi-cloud-upload"></i>';
                        if(role == 'produsen' || datas.status_id != STATUS_SETUJU || datas.status_id !=
                        STATUS_PROSES_PENGUMPULAN || datas.status_id != STATUS_REVISI){
                        tableRow += 'Input Data';
                        }else{
                        tableRow += 'Detail Data';
                        }
                        tableRow += '</a>' +
                    '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/pengumpulan/' + datas.id + '/standar"><i class="bi bi-sim-fill"></i> Standar Data</a>' +
                    '<a class="btn btn-outline-success btn-sm" href="/data_' + role + '/pengumpulan/' + datas.id + '/' + datas.jenis_data.toLowerCase() + '"><i class="bi bi-bar-chart"></i> Metadata ' + datas.jenis_data + '</a>';
                    if (typeof status !== 'undefined' && role == 'produsen' && progress_bar >= 60 && (datas.status_id ==
                    STATUS_PROSES_PENGUMPULAN || datas.status_id == STATUS_SETUJU || datas.status_id == STATUS_REVISI)){
                    tableRow += '<a class="btn btn-verify btn-outline-success" href="/data_produsen/pengumpulan/' + datas.id + '/verifikasi">Siap Verifikasi<i class="bi bi-check"></i></a>';
                    }
                    tableRow += '</div>';
                }else{
                tableRow += '<div class="d-flex flex-column gap-2">' +
                    '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/pengumpulan/' + datas.id + '/data"><i class="bi bi-cloud-upload"></i> Upload Berkas</a>' +
                    '<a class="btn btn-outline-primary btn-sm" href="/data_' + role + '/detail-data/' + datas.id + '"><i class="bi bi-eye"></i> Detail</a>';
                    }
                    tableRow +=
                    '</td>' +
            '</tr>';
        
        
        $('#isiTable').append(tableRow);
        });
        }
        })
        
        });
    });
</script>
@endpush
@endsection


@push('js')

@endpush