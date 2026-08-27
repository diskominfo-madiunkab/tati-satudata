@extends('pages.main.layout')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard & Rekapitulasi Penyelenggaraan Satu Data</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <!-- Filter Card -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body p-3">
                <form method="GET" action="{{ auth()->user()->hasRole('administrator') ? route('d_administrator') : route('d_walidata') }}" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <label class="form-label small text-muted mb-1"><i class="bi bi-building me-1"></i> Pilih Produsen Data (OPD)</label>
                        <select name="opd_id" class="form-select select2">
                            <option value="">-- Semua Perangkat Daerah (OPD) --</option>
                            @foreach ($opds as $opd)
                                <option value="{{ $opd->id }}" {{ (isset($opdId) && $opdId == $opd->id) ? 'selected' : '' }}>{{ $opd->nama_opd }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1"><i class="bi bi-calendar-date me-1"></i> Pilih Tahun</label>
                        <select name="tahun" class="form-select select2">
                            <option value="">-- Semua Tahun --</option>
                            @foreach ($tahun as $th)
                                <option value="{{ $th->tahun }}" {{ $selectedTahun == $th->tahun ? 'selected' : '' }}>Tahun {{ $th->tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2 align-self-end">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold"><i class="bi bi-filter me-1"></i> Terapkan</button>
                        @if(!empty($selectedTahun) || !empty($opdId))
                            <a href="{{ auth()->user()->hasRole('administrator') ? route('d_administrator') : route('d_walidata') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- 3 RUMUS PERSENTASE STATISTIK SDI (Hal 19) -->
    <section class="section mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100 bg-white border-start border-4 border-primary">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-semibold small text-uppercase">1. Persentase Keterisian Data</span>
                            <span class="badge bg-primary fs-6">{{ $persenKeterisian ?? 0 }}%</span>
                        </div>
                        <h3 class="fw-bold text-dark mb-2">{{ $persenKeterisian ?? 0 }}%</h3>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, $persenKeterisian ?? 0) }}%"></div>
                        </div>
                        <small class="text-muted" style="font-size: 11px;">(Pemeriksaan + Revisi + Siap Publish + Terpublikasi) / (Total - Tolak)</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100 bg-white border-start border-4 border-info">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-semibold small text-uppercase">2. Persentase Validitas Data</span>
                            <span class="badge bg-info text-dark fs-6">{{ $persenValid ?? 0 }}%</span>
                        </div>
                        <h3 class="fw-bold text-dark mb-2">{{ $persenValid ?? 0 }}%</h3>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ min(100, $persenValid ?? 0) }}%"></div>
                        </div>
                        <small class="text-muted" style="font-size: 11px;">(Siap Publikasi + Terpublikasi) / (Total - Tolak)</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100 bg-white border-start border-4 border-success">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-semibold small text-uppercase">3. Persentase Terpublikasi</span>
                            <span class="badge bg-success fs-6">{{ $persenTerpublikasi ?? 0 }}%</span>
                        </div>
                        <h3 class="fw-bold text-dark mb-2">{{ $persenTerpublikasi ?? 0 }}%</h3>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, $persenTerpublikasi ?? 0) }}%"></div>
                        </div>
                        <small class="text-muted" style="font-size: 11px;">Terpublikasi / (Total - Tolak)</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATUS CARD STATISTIK -->
    <section class="section dashboard mb-4">
        <div class="row g-3">
            <div class="col">
                <div class="card info-card sales-card shadow-sm border-0">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted p-0 mb-2">Daftar Data</h6>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light text-primary">
                                <i class="bi bi-card-list"></i>
                            </div>
                            <div class="ps-3">
                                <h5 class="mb-0 fw-bold">{{ $daftardata }}</h5>
                                <span class="text-muted small">Dataset</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card info-card sales-card shadow-sm border-0">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted p-0 mb-2">Data Prioritas</h6>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light text-warning">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <div class="ps-3">
                                <h5 class="mb-0 fw-bold">{{ $dataPrioritas }}</h5>
                                <span class="text-muted small">Prioritas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card info-card revenue-card shadow-sm border-0">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted p-0 mb-2">Standar Data</h6>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light text-info">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div class="ps-3">
                                <h5 class="mb-0 fw-bold">{{ $dataStandarData }}</h5>
                                <span class="text-muted small">Proses</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card info-card revenue-card shadow-sm border-0">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted p-0 mb-2">Pengumpulan</h6>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light text-secondary">
                                <i class="bi bi-pencil-square"></i>
                            </div>
                            <div class="ps-3">
                                <h5 class="mb-0 fw-bold">{{ $dataPengumpulan }}</h5>
                                <span class="text-muted small">Pengumpulan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card info-card revenue-card shadow-sm border-0">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted p-0 mb-2">Verifikasi</h6>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light text-primary">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="ps-3">
                                <h5 class="mb-0 fw-bold">{{ $dataTelahLengkap }}</h5>
                                <span class="text-muted small">Pemeriksaan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card info-card revenue-card shadow-sm border-0">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted p-0 mb-2">Revisi</h6>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light text-danger">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <div class="ps-3">
                                <h5 class="mb-0 fw-bold">{{ $dataRevisi }}</h5>
                                <span class="text-muted small">Perlu Revisi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card info-card revenue-card shadow-sm border-0">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted p-0 mb-2">Siap Publikasi</h6>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light text-warning">
                                <i class="bi bi-send"></i>
                            </div>
                            <div class="ps-3">
                                <h5 class="mb-0 fw-bold">{{ $dataSiapPublish }}</h5>
                                <span class="text-muted small">Valid</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card info-card revenue-card shadow-sm border-0">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted p-0 mb-2">Terpublikasi</h6>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light text-success">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="ps-3">
                                <h5 class="mb-0 fw-bold">{{ $dataTerpublikasi }}</h5>
                                <span class="text-muted small">Publik</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MATRIKS REKAPITULASI STATUS PER OPD (Hal 18 & 20) -->
    @if(isset($opdData) && isset($opds))
    <section class="section mb-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-table text-primary me-2"></i>Matriks Rekapitulasi Status Perangkat Daerah (OPD)</h5>
                    <small class="text-muted">Monitoring keterisian dan tahapan data masing-masing OPD</small>
                </div>
                <a href="{{ route('rekap_walidata_excel', ['y' => $selectedTahun, 'opd_id' => $opdId ?? '']) }}" class="btn btn-sm btn-success fw-semibold">
                    <i class="bi bi-file-excel me-1"></i> Unduh Excel
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0 datatable">
                    <thead class="table-dark text-center">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Nama Perangkat Daerah (OPD)</th>
                            <th>Draft</th>
                            <th>Standar Data</th>
                            <th>Pengumpulan</th>
                            <th>Pemeriksaan</th>
                            <th>Revisi</th>
                            <th>Siap Publikasi</th>
                            <th>Terpublikasi</th>
                            <th>Ditolak</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($opds as $index => $o)
                        @php
                            $items = $opdData->where('opd_id', $o->id);
                            $cDraft = $items->where('status_id', \App\Models\Data::STATUS_DRAFT)->sum('total');
                            $cStandar = $items->whereIn('status_id', [\App\Models\Data::STATUS_PENGAJUAN_STANDART_DATA, \App\Models\Data::STATUS_SETUJU, \App\Models\Data::STATUS_REVISI_STANDART_DATA])->sum('total');
                            $cPengumpulan = $items->where('status_id', \App\Models\Data::STATUS_SETUJU_STANDART_DATA)->sum('total');
                            $cVerifikasi = $items->where('status_id', \App\Models\Data::STATUS_PROSES_VERIFIKASI)->sum('total');
                            $cRevisi = $items->where('status_id', \App\Models\Data::STATUS_REVISI)->sum('total');
                            $cSiapPublikasi = $items->where('status_id', \App\Models\Data::STATUS_SIAP_PUBLIKASI)->sum('total');
                            $cTerpublikasi = $items->where('status_id', \App\Models\Data::STATUS_TERPUBLIKASI)->sum('total');
                            $cTolak = $items->where('status_id', \App\Models\Data::STATUS_TOLAK)->sum('total');
                            $cTotal = $cDraft + $cStandar + $cPengumpulan + $cVerifikasi + $cRevisi + $cSiapPublikasi + $cTerpublikasi + $cTolak;
                        @endphp
                        <tr>
                            <td class="text-center text-muted small">{{ $index + 1 }}</td>
                            <td class="fw-semibold text-dark">{{ $o->nama_opd }}</td>
                            <td class="text-center">{{ $cDraft ?: '-' }}</td>
                            <td class="text-center">{{ $cStandar ?: '-' }}</td>
                            <td class="text-center">{{ $cPengumpulan ?: '-' }}</td>
                            <td class="text-center"><span class="badge {{ $cVerifikasi > 0 ? 'bg-primary' : 'bg-light text-dark' }}">{{ $cVerifikasi ?: '-' }}</span></td>
                            <td class="text-center"><span class="badge {{ $cRevisi > 0 ? 'bg-danger' : 'bg-light text-dark' }}">{{ $cRevisi ?: '-' }}</span></td>
                            <td class="text-center"><span class="badge {{ $cSiapPublikasi > 0 ? 'bg-warning text-dark' : 'bg-light text-dark' }}">{{ $cSiapPublikasi ?: '-' }}</span></td>
                            <td class="text-center"><span class="badge {{ $cTerpublikasi > 0 ? 'bg-success' : 'bg-light text-dark' }}">{{ $cTerpublikasi ?: '-' }}</span></td>
                            <td class="text-center">{{ $cTolak ?: '-' }}</td>
                            <td class="text-center fw-bold">{{ $cTotal }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">Data rekapitulasi belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    <section class="section">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold text-dark mb-3">Daftar 10 Data Terbaru</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th>Nama Data</th>
                                        <th>Produsen</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataTerbaru as $d)
                                        <tr>
                                            <td class="text-muted small">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold text-dark">{{ $d->nama_data }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ optional($d->opd)->nama_opd }}</span></td>
                                            <td class="small text-muted">{{ optional($d->created_at)->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold text-dark mb-3">Aktivitas Terbaru</h5>
                        <div class="activity">
                            @foreach ($lastActivities as $a)
                                <div class="activity-item d-flex pb-3">
                                    <div class="activite-label small text-muted me-2" style="min-width: 80px;">{{ optional($a->created_at)->diffForHumans(null, true) }}</div>
                                    <i class="bi bi-circle-fill activity-badge text-primary align-self-start me-2" style="font-size: 8px; margin-top: 5px;"></i>
                                    <div class="activity-content small text-secondary">
                                        <strong>{{ $a->causer?->name ?: 'Sistem' }}</strong> - {{ $a->description }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
