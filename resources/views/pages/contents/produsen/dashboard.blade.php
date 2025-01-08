'@extends('pages.main.layout')

@section('content')

<div class="pagetitle">
    @if (!empty($selectedTahun))
    <h1>Dashboard {{ $selectedTahun }}</h1>
    @else
    <h1>Dashboard</h1>
    @endif
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="text-center" style="margin-left: 80%">
        <a class="btn btn-md btn-outline-secondary mb-3" style="width: 200px;" data-bs-toggle="collapse"
            data-bs-target="#flush-collapseOne">
            <i class="bi bi-funnel"></i>
            <span>Filter</span>
        </a>
    </div>

    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">

            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="row mb-3">
                        <form method="GET" action="{{route('d_produsen')}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <select id="tahun" name="tahun" class="form-select select2 filter"
                                        aria-label="Default select example">
                                        </option>
                                        <option value="">Semua Tahun</option>
                                        @foreach( $tahun as $th)
                                        <option value="{{ $th->tahun }}">{{ $th->tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" style="width: 50%" class="btn btn-block btn-primary"
                                        id='btnFilter'><i class="fas fa-filter"></i>
                                        Tampilkan
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Daftar Data</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-card-list"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$daftardata}} <span class="text-success small pt-1 fw-bold">Data</span>
                                    </h6>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Data Prioritas</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-star"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$data}} <span class="text-success small pt-1 fw-bold">Data</span>
                                    </h6>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Left side columns -->
        <div class="col-lg-12">
            <div class="row">
                <div class="col">
                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 16px">Proses Standar Data</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-folder2-open"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$dataStandarData}}</h6>
                                    <span class="text-success small pt-1 fw-bold">Data</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col">
                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 16px">Proses Pengumpulan</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-folder2-open"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$dataPengumpulan}}</h6>
                                    <span class="text-success small pt-1 fw-bold">Data</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col">
                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 16px">Proses Verifikasi</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$dataTelahLengkap}}</h6>
                                    <span class="text-success small pt-1 fw-bold">Data</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col">
                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 16px">Proses Revisi</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-arrow-repeat"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$dataRevisi}}</h6>
                                    <span class="text-success small pt-1 fw-bold">Data</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col">
                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 16px">Siap Publikasi</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-send"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$dataSiapPublish}}</h6>
                                    <span class="text-success small pt-1 fw-bold">Data</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col">
                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 16px">Terpublikasi</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-send-check"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$dataTerpublikasi}}</h6>
                                    <span class="text-success small pt-1 fw-bold">Data</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->

    </div>
</section>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar 10 Data Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Nama</td>
                                    <td>Status</td>
                                    <td>Produsen</td>
                                    <td>Tanggal</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataTerbaru as $d)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$d->nama_data}}</td>
                                    <td>{{$d->status->status}}</td>
                                    <td>{{$d->opd->nama_opd}}</td>
                                    <td>{{optional($d->created_at)->format('d/m/Y H:i')}}</td>
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