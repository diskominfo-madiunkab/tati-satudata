@extends('pages.main.layout')

@section('content')
    <div class="pagetitle">
        <h1>Rekapitulasi Keterisian Data OPD</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Rekapitulasi OPD</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <!-- Summary Stats Cards -->
                @php
                    $grandDraft = 0;
                    $grandTolak = 0;
                    $grandStandar = 0;
                    $grandPengumpulan = 0;
                    $grandVerifikasi = 0;
                    $grandRevisi = 0;
                    $grandSiapPublikasi = 0;
                    $grandTerpublikasi = 0;
                    $grandTotal = 0;

                    foreach ($opds as $opd) {
                        $o = $opdData->where('opd_id', $opd->id);
                        $dDraft = $o->where('status_id', \App\Models\Data::STATUS_DRAFT)->sum('total');
                        $dTolak = $o->where('status_id', \App\Models\Data::STATUS_TOLAK)->sum('total');
                        $dStandar = $o->whereIn('status_id', [
                            \App\Models\Data::STATUS_PENGAJUAN_STANDART_DATA,
                            \App\Models\Data::STATUS_SETUJU,
                            \App\Models\Data::STATUS_REVISI_STANDART_DATA,
                        ])->sum('total');
                        $dPengumpulan = $o->where('status_id', \App\Models\Data::STATUS_SETUJU_STANDART_DATA)->sum('total');
                        $dVerifikasi = $o->where('status_id', \App\Models\Data::STATUS_PROSES_VERIFIKASI)->sum('total');
                        $dRevisi = $o->where('status_id', \App\Models\Data::STATUS_REVISI)->sum('total');
                        $dSiapPublikasi = $o->where('status_id', \App\Models\Data::STATUS_SIAP_PUBLIKASI)->sum('total');
                        $dTerpublikasi = $o->where('status_id', \App\Models\Data::STATUS_TERPUBLIKASI)->sum('total');
                        $dTotal = $dDraft + $dTolak + $dStandar + $dPengumpulan + $dVerifikasi + $dRevisi + $dSiapPublikasi + $dTerpublikasi;

                        $grandDraft += $dDraft;
                        $grandTolak += $dTolak;
                        $grandStandar += $dStandar;
                        $grandPengumpulan += $dPengumpulan;
                        $grandVerifikasi += $dVerifikasi;
                        $grandRevisi += $dRevisi;
                        $grandSiapPublikasi += $dSiapPublikasi;
                        $grandTerpublikasi += $dTerpublikasi;
                        $grandTotal += $dTotal;
                    }

                    $persentaseAkumulatif = $grandTotal > 0 ? round(($grandTerpublikasi / $grandTotal) * 100, 1) : 0;
                @endphp

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white mb-0 shadow-sm rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-white mb-1 p-0">Total Dataset</h6>
                                        <h3 class="mb-0 fw-bold">{{ $grandTotal }}</h3>
                                    </div>
                                    <i class="bi bi-folder fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white mb-0 shadow-sm rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-white mb-1 p-0">Terpublikasi</h6>
                                        <h3 class="mb-0 fw-bold">{{ $grandTerpublikasi }}</h3>
                                    </div>
                                    <i class="bi bi-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark mb-0 shadow-sm rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-dark mb-1 p-0">Proses / Verifikasi</h6>
                                        <h3 class="mb-0 fw-bold">{{ $grandStandar + $grandPengumpulan + $grandVerifikasi + $grandSiapPublikasi }}</h3>
                                    </div>
                                    <i class="bi bi-hourglass-split fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white mb-0 shadow-sm rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-white mb-1 p-0">Ketercapaian Akumulatif</h6>
                                        <h3 class="mb-0 fw-bold">{{ $persentaseAkumulatif }}%</h3>
                                    </div>
                                    <i class="bi bi-pie-chart fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Matriks Rekapitulasi Status Per OPD</h5>

                        <div class="row mb-3 flex">
                            <div class="col-sm-6">
                                <select class="form-select" aria-label="Select OPD" id="selectOpd">
                                    <option {{ empty(request()->get('opd_id')) ? 'selected' : '' }} value="-1">Semua OPD</option>
                                    @foreach ($opds as $opd)
                                        <option value="{{ $opd->id }}" {{ request()->get('opd_id') == $opd->id ? 'selected' : '' }}>
                                            {{ $opd->nama_opd }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <select class="form-select" aria-label="Tahun" id="selectYear">
                                    <option value="" {{ empty(request()->get('y')) ? 'selected' : '' }}>Semua Tahun</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->tahun }}" {{ request()->get('y') == $year->tahun ? 'selected' : '' }}>
                                            Tahun {{ $year->tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <a href="{{ route('rekap_walidata_excel', request()->input()) }}" class="btn btn-success w-100">
                                    <i class="bi bi-file-excel me-1"></i> Export Excel
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle datatable">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th style="width: 40px;">No</th>
                                        <th>Nama OPD / Produsen Data</th>
                                        <th>Draft</th>
                                        <th>Ditolak</th>
                                        <th>Standar Data</th>
                                        <th>Pengumpulan</th>
                                        <th>Pemeriksaan</th>
                                        <th>Revisi</th>
                                        <th>Siap Publikasi</th>
                                        <th>Terpublikasi</th>
                                        <th>Total</th>
                                        <th style="width: 110px;">% Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($opds as $opd)
                                        @php
                                            $o = $opdData->where('opd_id', $opd->id);
                                            $dDraft = $o->where('status_id', \App\Models\Data::STATUS_DRAFT)->sum('total');
                                            $dTolak = $o->where('status_id', \App\Models\Data::STATUS_TOLAK)->sum('total');
                                            $dStandar = $o->whereIn('status_id', [
                                                \App\Models\Data::STATUS_PENGAJUAN_STANDART_DATA,
                                                \App\Models\Data::STATUS_SETUJU,
                                                \App\Models\Data::STATUS_REVISI_STANDART_DATA,
                                            ])->sum('total');
                                            $dPengumpulan = $o->where('status_id', \App\Models\Data::STATUS_SETUJU_STANDART_DATA)->sum('total');
                                            $dVerifikasi = $o->where('status_id', \App\Models\Data::STATUS_PROSES_VERIFIKASI)->sum('total');
                                            $dRevisi = $o->where('status_id', \App\Models\Data::STATUS_REVISI)->sum('total');
                                            $dSiapPublikasi = $o->where('status_id', \App\Models\Data::STATUS_SIAP_PUBLIKASI)->sum('total');
                                            $dTerpublikasi = $o->where('status_id', \App\Models\Data::STATUS_TERPUBLIKASI)->sum('total');
                                            $dTotal = $dDraft + $dTolak + $dStandar + $dPengumpulan + $dVerifikasi + $dRevisi + $dSiapPublikasi + $dTerpublikasi;
                                            $persenOpd = $dTotal > 0 ? round(($dTerpublikasi / $dTotal) * 100, 1) : 0;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">{{ $opd->nama_opd }}</td>
                                            <td class="text-center">{{ $dDraft }}</td>
                                            <td class="text-center text-danger">{{ $dTolak }}</td>
                                            <td class="text-center">{{ $dStandar }}</td>
                                            <td class="text-center">{{ $dPengumpulan }}</td>
                                            <td class="text-center text-warning fw-semibold">{{ $dVerifikasi }}</td>
                                            <td class="text-center text-danger">{{ $dRevisi }}</td>
                                            <td class="text-center text-primary">{{ $dSiapPublikasi }}</td>
                                            <td class="text-center text-success fw-bold">{{ $dTerpublikasi }}</td>
                                            <td class="text-center fw-bold">{{ $dTotal }}</td>
                                            <td class="text-center">
                                                <div class="progress" style="height: 18px;" title="{{ $persenOpd }}%">
                                                    <div class="progress-bar bg-{{ $persenOpd >= 80 ? 'success' : ($persenOpd >= 50 ? 'info' : 'warning') }}" 
                                                         role="progressbar" style="width: {{ $persenOpd }}%;" aria-valuenow="{{ $persenOpd }}" aria-valuemin="0" aria-valuemax="100">
                                                        {{ $persenOpd }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary fw-bold text-center">
                                    <tr>
                                        <td colspan="2" class="text-start ps-3">TOTAL AKUMULATIF KABUPATEN</td>
                                        <td>{{ $grandDraft }}</td>
                                        <td class="text-danger">{{ $grandTolak }}</td>
                                        <td>{{ $grandStandar }}</td>
                                        <td>{{ $grandPengumpulan }}</td>
                                        <td class="text-warning">{{ $grandVerifikasi }}</td>
                                        <td class="text-danger">{{ $grandRevisi }}</td>
                                        <td class="text-primary">{{ $grandSiapPublikasi }}</td>
                                        <td class="text-success">{{ $grandTerpublikasi }}</td>
                                        <td>{{ $grandTotal }}</td>
                                        <td>
                                            <span class="badge bg-{{ $persentaseAkumulatif >= 80 ? 'success' : ($persentaseAkumulatif >= 50 ? 'info' : 'warning') }} fs-6">
                                                {{ $persentaseAkumulatif }}%
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
