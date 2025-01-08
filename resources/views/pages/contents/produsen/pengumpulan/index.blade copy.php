@extends('pages.main.layout')

@section('content')
@php
$role = auth()->user()->hasAnyRole('produsen') ? 'produsen' : 'walidata';
@endphp

<div class="pagetitle">
    <h1>Daftar Pengumpulan Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Data</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Pengumpulan Data</h5>

                    <ul class="nav nav-tabs nav-tabs-bordered d-flex text-center" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <a href="/data_{{$role}}/pengumpulan" class="nav-link {{!isset($status) ? 'active' : ''}} w-100" id="pengumpulan-tab"><i class="bi bi-folder"></i> Proses Pengumpulan</a>
                        </li>

                        <li class="nav-item flex-fill" role="presentation">
                            <a href="/data_{{$role}}/pengumpulan/lengkap" class="nav-link w-100 {{isset($status) && $status == 'lengkap' ? 'active' : ''}}" id="lengkap-tab"><i class="bi bi-check-all"></i> Telah Lengkap</a>
                        </li>
                    </ul>

                    <div class="tab-content p-2">
                        <div class="tab-pane active" id="pengumpulan-tab">
                            @if(!isset($status))
                            <p>Halaman ini berisi daftar data yang telah disetujui dan tahap proses pengumpulan.</p>
                            @else
                            <p>Halaman ini berisi daftar data yang siap untuk diverifikasi.</p>
                            @endif
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Data</th>
                                        <th scope="col">Jenis</th>
                                        <th scope="col">Produsen Data</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Progress</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $dt)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $dt->nama_data }}</td>
                                        <td>{{ $dt->jenis_data }}</td>
                                        <td>{{ $dt->opd->nama_opd }}</td>
                                        <td>{{ $dt->status_id == \App\Models\Data::STATUS_SETUJU ? 'Proses Pengumpulan' : 'Telah Lengkap' }}</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped bg-primary progress-bar-animated" role="progressbar" style="width: {{$dt->calculateProgress()}}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" title="Total: {{$dt->calculateProgress()}}%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($dt->status_id == \App\Models\Data::STATUS_SETUJU || $dt->status_id == \App\Models\Data::STATUS_PROSES_PENGUMPULAN)
                                            <div class="d-flex flex-column gap-2">
                                                <a class="btn btn-outline-primary btn-sm" href="/data_{{$role}}/pengumpulan/{{$dt->id}}/data"><i class="bi bi-files"></i> {{$role == 'produsen' || $dt->status_id != \App\Models\Data::STATUS_SETUJU || $dt->status_id != \App\Models\Data::STATUS_PROSES_PENGUMPULAN || $dt->status_id != \App\Models\Data::STATUS_REVISI ? ' Unggah Berkas' : ' Berkas'}}</a>
                                                <a class="btn btn-outline-primary btn-sm" href="/data_{{$role}}/pengumpulan/{{$dt->id}}/standar"><i class="bi bi-sim-fill"></i> Standar Data</a>
                                                <a class="btn btn-outline-success btn-sm" href="/data_{{$role}}/pengumpulan/{{$dt->id}}/{{strtolower($dt->jenis_data)}}"><i class="bi bi-bar-chart"></i> Meta Data {{$dt->jenis_data}}</a>
                                                <a class="btn btn-outline-success btn-sm" href="/data_{{$role}}/pengumpulan/{{$dt->id}}/kegiatan"><i class="bi bi-activity"></i> Meta Data Kegiatan</a>
                                                @if(!isset($status) && $role == 'produsen' && $dt->calculateProgress() >= 60 && ($dt->status_id == \App\Models\Data::STATUS_PROSES_PENGUMPULAN || $dt->status_id = \App\Models\Data::STATUS_SETUJU || $dt->status_id == \App\Models\Data::STATUS_REVISI))
                                                <a class="btn btn-verify btn-outline-success" href="{{route('siap-verifikasi', $dt->id)}}">Siap Verifikasi<i class="bi bi-check"></i></a>
                                                @endif
                                            </div>
                                            @else
                                            <a class="btn btn-outline-primary btn-sm" href="{{route($role . '.data.detail', $dt->id)}}"><i class="bi bi-eye"></i> Detail</a>
                                            @endif
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
    </div>
</section>

@endsection

@push('js')
<script>
    $(function() {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });

        $('table.table').on('click', 'a.btn-verify', function(e) {
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
                            }
                        })
                        .then((r) => Swal.fire(r.ok ? 'Sukses' : 'Gagal', r.message, r.ok ? 'success' : 'error'))
                        .catch(() => Swal.fire('Error', 'Terjadi galat saat memproses permintaan', 'error'));
                }
            })
        });
    });
</script>
@endpush