@extends('pages.main.layout')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard & Rekapitulasi Keterisian Data OPD</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard & Rekapitulasi OPD</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <!-- Summary Stats & SDI Percentage Calculation -->
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

                    // Rumus SDI (Halaman 19): Basis D = Total Seluruh Data - Ditolak
                    $grandNonTolak = $grandTotal - $grandTolak;
                    $pctKeterisian = $grandNonTolak > 0 ? round((($grandVerifikasi + $grandRevisi + $grandSiapPublikasi + $grandTerpublikasi) / $grandNonTolak) * 100, 1) : 0;
                    $pctValid = $grandNonTolak > 0 ? round((($grandSiapPublikasi + $grandTerpublikasi) / $grandNonTolak) * 100, 1) : 0;
                    $pctTerpublikasi = $grandNonTolak > 0 ? round(($grandTerpublikasi / $grandNonTolak) * 100, 1) : 0;
                @endphp

                <!-- 4 KPI Cards: Total Dataset & 3 Persentase SDI -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card bg-primary text-white h-100 shadow-sm rounded-3 border-0">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-uppercase small fw-bold opacity-75">Total Dataset</div>
                                        <h3 class="mb-0 fw-bold">{{ $grandTotal }}</h3>
                                    </div>
                                    <i class="bi bi-folder2-open fs-1 opacity-75"></i>
                                </div>
                                <div class="mt-2 small opacity-75 border-top pt-2">
                                    <span>Aktif / Non-Ditolak: <strong>{{ $grandNonTolak }}</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card bg-warning text-dark h-100 shadow-sm rounded-3 border-0">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-uppercase small fw-bold text-muted">Persentase Keterisian Data</div>
                                        <h3 class="mb-0 fw-bold">{{ $pctKeterisian }}%</h3>
                                    </div>
                                    <i class="bi bi-hourglass-split fs-1 text-warning opacity-75"></i>
                                </div>
                                <div class="mt-2 small text-muted border-top pt-2">
                                    (Pemeriksaan + Revisi + Siap + Terbit) / Non-Tolak
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card bg-info text-white h-100 shadow-sm rounded-3 border-0">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-uppercase small fw-bold opacity-75">Persentase Data yang Valid</div>
                                        <h3 class="mb-0 fw-bold">{{ $pctValid }}%</h3>
                                    </div>
                                    <i class="bi bi-shield-check fs-1 opacity-75"></i>
                                </div>
                                <div class="mt-2 small opacity-75 border-top pt-2">
                                    (Siap Publikasi + Terpublikasi) / Non-Tolak
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card bg-success text-white h-100 shadow-sm rounded-3 border-0">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-uppercase small fw-bold opacity-75">Persentase Terpublikasi</div>
                                        <h3 class="mb-0 fw-bold">{{ $pctTerpublikasi }}%</h3>
                                    </div>
                                    <i class="bi bi-check2-circle fs-1 opacity-75"></i>
                                </div>
                                <div class="mt-2 small opacity-75 border-top pt-2">
                                    Terpublikasi / Non-Tolak
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Metrics Strip -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-3">
                        <div class="row text-center g-2">
                            <div class="col-6 col-md-3 col-lg border-end">
                                <small class="text-muted d-block">Draft</small>
                                <span class="fw-bold fs-5 text-secondary">{{ $grandDraft }}</span>
                            </div>
                            <div class="col-6 col-md-3 col-lg border-end">
                                <small class="text-muted d-block">Standar Data</small>
                                <span class="fw-bold fs-5 text-info">{{ $grandStandar }}</span>
                            </div>
                            <div class="col-6 col-md-3 col-lg border-end">
                                <small class="text-muted d-block">Pengumpulan</small>
                                <span class="fw-bold fs-5 text-primary">{{ $grandPengumpulan }}</span>
                            </div>
                            <div class="col-6 col-md-3 col-lg border-end">
                                <small class="text-muted d-block">Pemeriksaan</small>
                                <span class="fw-bold fs-5 text-warning">{{ $grandVerifikasi }}</span>
                            </div>
                            <div class="col-6 col-md-3 col-lg border-end">
                                <small class="text-muted d-block">Revisi</small>
                                <span class="fw-bold fs-5 text-danger">{{ $grandRevisi }}</span>
                            </div>
                            <div class="col-6 col-md-3 col-lg border-end">
                                <small class="text-muted d-block">Siap Publikasi</small>
                                <span class="fw-bold fs-5 text-primary">{{ $grandSiapPublikasi }}</span>
                            </div>
                            <div class="col-6 col-md-3 col-lg border-end">
                                <small class="text-muted d-block">Terpublikasi</small>
                                <span class="fw-bold fs-5 text-success">{{ $grandTerpublikasi }}</span>
                            </div>
                            <div class="col-6 col-md-3 col-lg">
                                <small class="text-muted d-block">Ditolak</small>
                                <span class="fw-bold fs-5 text-muted">{{ $grandTolak }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Matriks Rekapitulasi Table & Filters -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between align-items-center">
                            <span>Matriks Rekapitulasi Status Per OPD</span>
                            <span class="badge bg-secondary">Total OPD: {{ count($opds) }}</span>
                        </h5>

                        <!-- Filter Form with functional onchange submit -->
                        <form method="GET" action="{{ route('rekap_walidata') }}" id="filterRekapForm" class="mb-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-5">
                                    <label class="form-label small text-muted mb-1">Perangkat Daerah (OPD)</label>
                                    <select class="form-select" aria-label="Select OPD" name="opd_id" id="selectOpd" onchange="document.getElementById('filterRekapForm').submit();">
                                        <option value="-1" {{ empty(request()->get('opd_id')) || request()->get('opd_id') == '-1' ? 'selected' : '' }}>Semua OPD</option>
                                        @foreach ($allOpds ?? $opds as $opdItem)
                                            <option value="{{ $opdItem->id }}" {{ request()->get('opd_id') == $opdItem->id ? 'selected' : '' }}>
                                                {{ $opdItem->nama_opd }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-muted mb-1">Tahun Anggaran / Data</label>
                                    <select class="form-select" aria-label="Tahun" name="y" id="selectYear" onchange="document.getElementById('filterRekapForm').submit();">
                                        <option value="" {{ empty(request()->get('y')) ? 'selected' : '' }}>Semua Tahun</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year->tahun }}" {{ request()->get('y') == $year->tahun ? 'selected' : '' }}>
                                                Tahun {{ $year->tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex gap-2 align-items-end pt-md-4">
                                    @if(request()->filled('opd_id') && request()->get('opd_id') != '-1' || request()->filled('y'))
                                        <a href="{{ route('rekap_walidata') }}" class="btn btn-outline-secondary w-50" title="Reset Filter">
                                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                                        </a>
                                    @endif
                                    <a href="{{ route('rekap_walidata_excel', request()->all()) }}" class="btn btn-success {{ (request()->filled('opd_id') && request()->get('opd_id') != '-1' || request()->filled('y')) ? 'w-50' : 'w-100' }}">
                                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle datatable">
                                <thead class="table-dark text-center align-middle">
                                    <tr>
                                        <th style="width: 35px;">No</th>
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
                                        <th style="width: 90px;" title="(Pemeriksaan + Revisi + Siap + Terbit) / Non-Tolak">% Keterisian</th>
                                        <th style="width: 85px;" title="(Siap + Terbit) / Non-Tolak">% Valid</th>
                                        <th style="width: 90px;" title="Terpublikasi / Non-Tolak">% Terpublikasi</th>
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

                                            $dNonTolak = $dTotal - $dTolak;
                                            $pctKeterisianOpd = $dNonTolak > 0 ? round((($dVerifikasi + $dRevisi + $dSiapPublikasi + $dTerpublikasi) / $dNonTolak) * 100, 1) : 0;
                                            $pctValidOpd = $dNonTolak > 0 ? round((($dSiapPublikasi + $dTerpublikasi) / $dNonTolak) * 100, 1) : 0;
                                            $pctTerpublikasiOpd = $dNonTolak > 0 ? round(($dTerpublikasi / $dNonTolak) * 100, 1) : 0;
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
                                                <span class="badge bg-{{ $pctKeterisianOpd >= 80 ? 'success' : ($pctKeterisianOpd >= 50 ? 'warning' : 'secondary') }}">
                                                    {{ $pctKeterisianOpd }}%
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $pctValidOpd >= 80 ? 'info' : ($pctValidOpd >= 50 ? 'warning' : 'secondary') }}">
                                                    {{ $pctValidOpd }}%
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $pctTerpublikasiOpd >= 80 ? 'success' : ($pctTerpublikasiOpd >= 50 ? 'info' : 'secondary') }}">
                                                    {{ $pctTerpublikasiOpd }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary fw-bold text-center align-middle">
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
                                            <span class="badge bg-{{ $pctKeterisian >= 80 ? 'success' : ($pctKeterisian >= 50 ? 'warning' : 'secondary') }} fs-7">
                                                {{ $pctKeterisian }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $pctValid >= 80 ? 'info' : ($pctValid >= 50 ? 'warning' : 'secondary') }} fs-7">
                                                {{ $pctValid }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $pctTerpublikasi >= 80 ? 'success' : ($pctTerpublikasi >= 50 ? 'info' : 'secondary') }} fs-7">
                                                {{ $pctTerpublikasi }}%
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