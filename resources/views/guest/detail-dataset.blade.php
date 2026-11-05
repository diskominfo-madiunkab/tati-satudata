@extends('guest.layout')

@push('style')
<style>
    .dataset-detail-area {
        background-color: #f8fafc;
    }
    .card-detail-modern {
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        background: #ffffff;
        border: 1px solid #eef2f6;
    }
    .stat-badge-box {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .stat-badge-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .stat-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    @media (max-width: 576px) {
        .stat-badge-box {
            padding: 8px 10px;
            gap: 8px;
        }
        .stat-icon-wrapper {
            width: 34px;
            height: 34px;
            font-size: 14px;
        }
    }
    .detail-nav-tabs .nav-link {
        color: #475569;
        font-weight: 600;
        border-radius: 8px;
        padding: 10px 18px;
        margin-right: 6px;
        border: 1px solid transparent;
        background: transparent;
        transition: all 0.2s ease;
        font-size: 14px;
    }
    .detail-nav-tabs .nav-link:hover {
        color: #0d6efd;
        background-color: #f1f5f9;
    }
    .detail-nav-tabs .nav-link.active {
        color: #fff !important;
        background-color: #0d6efd !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
    }
    .berkas-table th {
        background-color: #0d3b66 !important;
        color: #ffffff !important;
        font-size: 13.5px;
        font-weight: 600;
        border: none;
    }
    .berkas-table td {
        vertical-align: middle;
        font-size: 13.5px;
    }
    .btn-berkas-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.2;
        border-radius: 6px;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .btn-berkas-download {
        background-color: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .btn-berkas-download:hover {
        background-color: #16a34a;
        color: #ffffff;
        border-color: #16a34a;
        box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
    }
    .btn-berkas-preview {
        background-color: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    .btn-berkas-preview:hover {
        background-color: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    }
    .detail-form-card .form-control:disabled,
    .detail-form-card .form-control[readonly] {
        background-color: #f8fafc !important;
        border-color: #e2e8f0 !important;
        color: #1e293b !important;
        opacity: 1;
        font-size: 14px;
    }
    .detail-form-card label {
        font-weight: 600;
        font-size: 13.5px;
        color: #475569;
    }
    .tab-content > .tab-pane.active {
        opacity: 1 !important;
    }
    .card-detail-modern .nice-select {
        width: 100% !important;
        float: none !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        height: 40px !important;
        line-height: 38px !important;
        padding-left: 14px !important;
        padding-right: 32px !important;
        font-size: 13.5px !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
        margin-bottom: 4px !important;
    }
    .card-detail-modern .nice-select .current {
        display: block !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        padding-right: 8px !important;
        color: #1e293b !important;
        font-weight: 500 !important;
    }
    .card-detail-modern .nice-select:hover {
        border-color: #0d6efd !important;
    }
    .card-detail-modern .nice-select:after {
        right: 14px !important;
    }
    .card-detail-modern .nice-select .list {
        width: 100% !important;
        max-height: 240px !important;
        overflow-y: auto !important;
        border-radius: 8px !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
        border: 1px solid #e2e8f0 !important;
        z-index: 99 !important;
    }
    .card-header .nice-select {
        width: auto !important;
        min-width: 140px !important;
        display: inline-block !important;
    }
    .btn-filter-submit {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
        color: #ffffff !important;
        border: none !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        padding: 11px 16px !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.28) !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
    }
    .btn-filter-submit:hover {
        background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 6px 18px rgba(13, 110, 253, 0.4) !important;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')

    <div class="page-banner">
        <div class="container">
            <div class="page-banner-content text-center">
                <div class="page-banner-pill">
                    <i class="fas fa-file-alt text-info"></i> Satu Data Kabupaten Madiun
                </div>
                <h1 class="page-banner-title">Detail Dataset</h1>
                <p class="page-banner-subtitle">Informasi Lengkap dan Unduh Berkas Dataset Pemerintah Kabupaten Madiun</p>
            </div>
        </div>
    </div>

    <section class="dataset-detail-area pt-40 pb-70">
        <div class="container">
            <!-- Card 1: Detail Dataset Overview & Quick Download -->
            <div class="card card-detail-modern p-3 p-md-5 mb-4 border-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 pb-3 border-bottom">
                    <div class="flex-grow-1 w-100">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                                <i class="fas fa-database me-1"></i> Dataset Terbuka
                            </span>
                            @if(!empty($getmeta->data_prioritas))
                            <span class="badge bg-danger text-white px-2 py-1">
                                <i class="fas fa-star me-1"></i> Data Prioritas
                            </span>
                            @endif
                        </div>
                        <h2 class="fw-bold text-dark mb-0 fs-3 text-break">{{ $dataset['title'] }}</h2>
                    </div>

                    <!-- Quick Download Button at top right (Page 6 requirement) -->
                    @if(!empty($dataset['resources']) && count($dataset['resources']) > 0)
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle px-4 py-2 fw-semibold shadow-sm rounded-pill d-inline-flex align-items-center" type="button" id="dropdownUnduhTop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-download me-2"></i> Unduh Dataset
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2" aria-labelledby="dropdownUnduhTop" style="min-width: 260px;">
                            @foreach($dataset['resources'] as $res)
                            @php
                                $rUrl = $res['url_download'] ?? $res['url'] ?? '#';
                                $rFmt = strtoupper($res['format'] ?? 'BERKAS');
                            @endphp
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center download-btn" href="{{ $rUrl }}" data-url-download="{{ $rUrl }}" target="_blank">
                                    <span class="badge {{ $rFmt == 'CSV' ? 'bg-info text-dark' : ($rFmt == 'PDF' ? 'bg-danger text-white' : 'bg-success text-white') }} me-2 px-2">{{ $rFmt }}</span>
                                    <div class="text-truncate">
                                        <span class="d-block text-dark fw-semibold small">{{ $res['name'] }}</span>
                                        <span class="text-muted" style="font-size: 11px;">Format {{ $rFmt }}</span>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="mt-3">
                    <p class="text-muted mb-3" style="line-height: 1.6; font-size: 14.5px;">
                        {{ $dataset['description'] ?? ($dataset['notes'] ?? 'Dataset sektoral resmi Pemerintah Kabupaten Madiun.') }}
                    </p>
                </div>

                <!-- Taxonomy Metadata Badges -->
                <div class="d-flex flex-wrap align-items-center gap-2 pt-1 pb-3">
                    @if(!empty($dataset['organization']))
                    <span class="badge bg-light text-dark border px-3 py-2">
                        <i class="fas fa-building text-primary me-1"></i> {{ $dataset['organization']['title'] ?? 'Pemkab Madiun' }}
                    </span>
                    @endif
                    <span class="badge bg-light text-dark border px-3 py-2">
                        <i class="far fa-calendar-alt text-primary me-1"></i> Tahun {{ $getmeta->tahun ?? ($dataset['tahun'] ?? date('Y')) }}
                    </span>
                    @if(!empty($dataset['group_id']) || !empty($getmeta->group_id))
                    <span class="badge bg-light text-dark border px-3 py-2">
                        <i class="fas fa-layer-group text-primary me-1"></i> {{ $dataset['group_id'] ?? $getmeta->group_id }}
                    </span>
                    @endif
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                        <i class="fas fa-bookmark me-1"></i> {{ $getmeta->sumber_referensi ?: ($dataset['sumber_data'] ?? 'RPJMD') }}
                    </span>
                </div>

                <!-- Stats & Engagement Badges (Page 6 requirement: Jumlah Dilihat & Jumlah Diunduh) -->
                <div class="row g-3 pt-3 border-top">
                    <div class="col-6 col-lg-3">
                        <div class="stat-badge-box">
                            <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div>
                                <span class="text-muted d-block small" style="font-size: 11.5px;">Jumlah Dilihat</span>
                                <span class="fw-bold text-dark fs-6">{{ number_format($dataset['views_count'] ?? 1, 0, ',', '.') }}</span>
                                <span class="text-muted small">kali</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-badge-box">
                            <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                                <i class="fas fa-download"></i>
                            </div>
                            <div>
                                <span class="text-muted d-block small" style="font-size: 11.5px;">Jumlah Diunduh</span>
                                <span class="fw-bold text-dark fs-6" id="datasetDownloadCounter">{{ number_format($dataset['downloads_count'] ?? 0, 0, ',', '.') }}</span>
                                <span class="text-muted small">kali</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-badge-box">
                            <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <span class="text-muted d-block small" style="font-size: 11.5px;">Jumlah Berkas</span>
                                <span class="fw-bold text-dark fs-6">{{ count($dataset['resources'] ?? []) }}</span>
                                <span class="text-muted small">berkas</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-badge-box">
                            <div class="stat-icon-wrapper bg-secondary bg-opacity-10 text-secondary">
                                <i class="far fa-clock"></i>
                            </div>
                            <div>
                                <span class="text-muted d-block small" style="font-size: 11.5px;">Terakhir Dimodifikasi</span>
                                <span class="fw-bold text-dark fs-6" style="font-size: 13.5px !important;">{{ $dataset['modified'] ?? ($dataset['created'] ?? '-') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Daftar Berkas & Filter Global (Opsi A) -->
            @php
                $resYears = collect($dataset['resources'] ?? [])->pluck('tahun')->filter();
                $tableYears = collect();
                if (!empty($tables)) {
                    foreach ($tables as $tItem) {
                        $hdrs = is_array($tItem) ? ($tItem['headers'] ?? collect()) : ($tItem->header ?? collect());
                        $thnHdr = $hdrs->firstWhere('header', 'Tahun');
                        if ($thnHdr) {
                            $rws = is_array($tItem) ? ($tItem['rows'] ?? collect()) : ($tItem->isi ? $tItem->isi->groupBy('urutan_kebawah') : collect());
                            foreach ($rws as $rw) {
                                $cell = $rw->firstWhere('id_header', $thnHdr->id);
                                if ($cell && trim($cell->isi) && is_numeric(trim($cell->isi))) {
                                    $tableYears->push(trim($cell->isi));
                                }
                            }
                        }
                    }
                }
                $availableYears = $resYears->concat($tableYears)->unique()->sortDesc()->values();
            @endphp
            <div class="card card-detail-modern mb-4 border-0 overflow-hidden shadow-sm">
                <div class="card-header bg-white p-3 p-md-4 border-bottom">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold text-dark mb-0 fs-6">
                                <i class="fas fa-folder-open text-primary me-2"></i>Daftar Berkas & Data Terbuka
                            </h5>
                            <span class="badge bg-light text-secondary border px-2 py-1" id="berkasCountBadge">
                                {{ count($dataset['resources'] ?? []) }} Berkas Tersedia
                            </span>
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-sync-alt text-primary me-1"></i> Filter terintegrasi menyaring berkas & tabel data
                        </div>
                    </div>

                    <!-- Filter Toolbar Terintegrasi (Opsi A) -->
                    <div class="row g-2 align-items-center bg-light p-2 rounded-3 border">
                        <div class="col-md-6 col-lg-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                                <input type="text" id="liveTableFilterInput" class="form-control form-control-sm border-start-0 dataset-filter-input" placeholder="Filter tahun atau kata kunci berkas & tabel data..." value="{{ request('filter') ?? request('tahun') ?? request('q') ?? '' }}" onkeyup="filterDatasetTable(this)">
                                <button class="btn btn-outline-secondary btn-sm clear-filter-btn" type="button" onclick="clearDatasetTableFilter()" title="Reset Filter" style="{{ (request('filter') || request('tahun') || request('q')) ? '' : 'display: none;' }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @if ($availableYears->isNotEmpty())
                            <div class="col-md-6 col-lg-7">
                                <div class="d-flex align-items-center gap-1 flex-wrap justify-content-md-end">
                                    <span class="small text-muted me-1 fw-semibold"><i class="fas fa-calendar-alt text-primary me-1"></i>Tahun:</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 active year-filter-pill" onclick="setQuickYearFilter('')">Semua</button>
                                    @foreach ($availableYears as $yr)
                                        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 year-filter-pill" onclick="setQuickYearFilter('{{ $yr }}')">{{ $yr }}</button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 berkas-table" id="daftarBerkasTable">
                        <thead>
                            <tr>
                                <th scope="col" style="padding-left: 20px;">Nama Berkas</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col" class="text-center">Tgl. Diunggah</th>
                                <th scope="col" class="text-center">Format</th>
                                <th scope="col" class="text-center">Unduhan</th>
                                <th scope="col" class="text-center" style="min-width: 200px; padding-right: 20px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataset['resources'] as $resource)
                            @php
                                $resUrl = $resource['url_download'] ?? $resource['url'] ?? '#';
                                $resPreview = $resource['url_preview'] ?? '#';
                                $fmt = strtoupper($resource['format'] ?? 'BERKAS');
                                $tahunBerkas = $resource['tahun'] ?? '';
                            @endphp
                            <tr class="berkas-row" data-tahun="{{ $tahunBerkas }}" data-search="{{ strtolower(($resource['name'] ?? '') . ' ' . ($resource['description'] ?? '') . ' ' . $tahunBerkas) }}">
                                <td style="padding-left: 20px;">
                                    <div class="fw-bold text-dark">{{ $resource['name'] }}</div>
                                </td>
                                <td class="text-muted small">{{ $resource['description'] ?? '-' }}</td>
                                <td class="text-center text-muted small">{{ $resource['created'] ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $fmt == 'CSV' ? 'bg-info text-dark' : ($fmt == 'PDF' ? 'bg-danger text-white' : 'bg-success text-white') }} px-2 py-1">
                                        {{ $fmt }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-muted border px-2 py-1">
                                        <i class="fas fa-download text-success me-1"></i> {{ $resource['download_count'] ?? 0 }} kali
                                    </span>
                                </td>
                                <td class="text-center" style="padding-right: 20px; white-space: nowrap;">
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <a href="{{ $resUrl }}" data-url-download="{{ $resUrl }}" class="btn-berkas-action btn-berkas-download download-btn" target="_blank" title="Unduh Berkas">
                                            <i class="fas fa-download"></i> Unduh
                                        </a>
                                        <a href="{{ $resPreview }}" class="btn-berkas-action btn-berkas-preview" target="_blank" title="Pratinjau Berkas">
                                            <i class="fas fa-eye"></i> Pratinjau
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle me-1"></i> Belum ada berkas terlampir untuk dataset ini.
                                </td>
                            </tr>
                            @endforelse
                            <tr id="berkasNoMatchRow" style="display: none;">
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-search me-1"></i> Tidak ada berkas yang cocok dengan filter pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card 3: Tabs Container -->
            <div class="card card-detail-modern border-0 mb-4 overflow-hidden">
                <div class="card-header bg-white p-3 p-md-4 border-bottom">
                    @php
                        $activeTabName = request('tab', session('active_tab', 'info'));
                    @endphp
                    <ul class="nav nav-pills detail-nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTabName == 'info' ? 'active' : '' }}" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                                type="button" role="tab" aria-controls="info" aria-selected="{{ $activeTabName == 'info' ? 'true' : 'false' }}">
                                <i class="fas fa-info-circle me-1"></i> Informasi Data
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTabName == 'standar' ? 'active' : '' }}" id="standar-tab" data-bs-toggle="tab" data-bs-target="#standar"
                                type="button" role="tab" aria-controls="standar" aria-selected="{{ $activeTabName == 'standar' ? 'true' : 'false' }}">
                                <i class="fas fa-clipboard-check me-1"></i> Standar Data
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTabName == 'meta' ? 'active' : '' }}" id="meta-tab" data-bs-toggle="tab" data-bs-target="#meta"
                                type="button" role="tab" aria-controls="meta" aria-selected="{{ $activeTabName == 'meta' ? 'true' : 'false' }}">
                                <i class="fas fa-tags me-1"></i> Metadata
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTabName == 'tabel' ? 'active' : '' }}" id="tabel-tab" data-bs-toggle="tab" data-bs-target="#tabel"
                                type="button" role="tab" aria-controls="tabel" aria-selected="{{ $activeTabName == 'tabel' ? 'true' : 'false' }}">
                                <i class="fas fa-table me-1"></i> Tabel Data
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTabName == 'grafik' ? 'active' : '' }}" id="grafik-tab" data-bs-toggle="tab" data-bs-target="#grafik"
                                type="button" role="tab" aria-controls="grafik" aria-selected="{{ $activeTabName == 'grafik' ? 'true' : 'false' }}">
                                <i class="fas fa-chart-bar me-1"></i> Grafik Data
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4 detail-form-card">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade {{ $activeTabName == 'info' ? 'show active' : '' }}" id="info" role="tabpanel"
                            aria-labelledby="info-tab">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-3" style="padding-top:20px">
                                            <label for="inputText" class="col-sm-2 col-form-label">Nama Data</label>
                                            <div class="col-sm-10">
                                                <input id="nama_data" name="nama_data" type="text"
                                                    class="form-control" value="{{ $getmeta->nama_data }}" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Jenis Data</label>
                                            <div class="col-sm-10">
                                                <input id="nama_data" name="nama_data" type="text"
                                                    class="form-control" value="{{ $getmeta->jenis_data ?? '-' }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Produsen Data(PIC)</label>
                                            <div class="col-sm-10">
                                                <input id="nama_data" name="nama_data" type="text"
                                                    class="form-control" value="{{ $getmeta->opd->nama_opd ?? '-' }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Sumber Referensi</label>
                                            <div class="col-sm-10">
                                                <input id="nama_data" name="nama_data" type="text"
                                                    class="form-control" value="{{ $getmeta->sumber_data ?? '-' }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Tahun</label>
                                            <div class="col-sm-10">
                                                <input id="nama_data" name="nama_data" type="text"
                                                    class="form-control" value="{{ $getmeta->tahun ?? '-' }}" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3" style="padding-top:0px">
                                            <label for="inputText" class="col-sm-2 col-form-label">Jadwal Rilis</label>
                                            <div class="col-sm-10">
                                                <?php
                                                $value = $getmeta->jadwal_rilis ?? '-';
                                                if ($value !== '-') {
                                                    $formattedDate = date('d F Y', strtotime($value));
                                                } else {
                                                    $formattedDate = '-';
                                                }
                                                ?>
                                                <input id="nama_data" name="nama_data" type="text"
                                                    class="form-control" value="<?php echo $formattedDate; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3" style="padding-top:0px">
                                            <label for="inputText" class="col-sm-2 col-form-label">Jadwal
                                                Pemutakhiran</label>
                                            <div class="col-sm-10">
                                                <input id="nama_data" name="nama_data" type="text"
                                                    class="form-control"
                                                    value="{{ $getmeta->jadwal_pemutakhiran ?? '-' }}" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="data_prioritas" class="col-sm-2 col-form-label">Data
                                                Prioritas</label>
                                            <div class="col-sm-10">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="data_prioritas"
                                                        id="data_prioritas1" value="1"
                                                        {{ $getmeta->data_prioritas == 1 ? 'checked' : '' }}
                                                        disabled>
                                                    <label class="form-check-label" for="data_prioritas1">
                                                        Ya
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="data_prioritas"
                                                        id="data_prioritas0" value="0"
                                                        {{ $getmeta->data_prioritas == 0 ? 'checked' : '' }}
                                                        disabled>
                                                    <label class="form-check-label" for="data_prioritas0">
                                                        Tidak
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $activeTabName == 'standar' ? 'show active' : '' }}" id="standar" role="tabpanel" aria-labelledby="standar-tab">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <label for="kode" class="col-sm-2 col-form-label">Kode Standar
                                                Data</label>
                                            <div class="col-sm-10">
                                                <textarea id="kode" name="kode" class="form-control" placeholder="Kode Standar Data" readonly>{{ old('kode', optional($getmeta->standar)->kode) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                                            <div class="col-sm-10">
                                                <textarea id="konsep" name="konsep" class="form-control" placeholder="Konsep Standar Data" readonly>{{ old('konsep', optional($getmeta->standar)->konsep) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="definisi" class="col-sm-2 col-form-label">Definisi</label>
                                            <div class="col-sm-10">
                                                <textarea id="definisi" name="definisi" class="form-control" placeholder="Definisi Standar Data" readonly>{{ old('definisi', optional($getmeta->standar)->definisi) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="klasifikasi" class="col-sm-2 col-form-label">Klasifikasi</label>
                                            <div class="col-sm-10">
                                                <textarea id="klasifikasi" name="klasifikasi" class="form-control" placeholder="Klasifikasi Standar Data" readonly>{{ old('klasifikasi', optional($getmeta->standar)->klasifikasi) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                                            <div class="col-sm-10">
                                                <textarea id="ukuran" name="ukuran" class="form-control" placeholder="Ukuran Standar Data" readonly>{{ old('ukuran', optional($getmeta->standar)->ukuran) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                                            <div class="col-sm-10">
                                                <textarea id="satuan" name="satuan" class="form-control" placeholder="Satuan Standar Data" readonly>{{ old('satuan', optional($getmeta->standar)->satuan) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $activeTabName == 'meta' ? 'show active' : '' }}" id="meta" role="tabpanel" aria-labelledby="meta-tab">

                                <div class="card">
                                    <div class="card-body">
                                        @if (strtolower($getmeta->jenis_data) == 'indikator')
                                            <div class="row mb-3">
                                                <label for="nama" class="col-sm-2 col-form-label">Nama
                                                    Indikator</label>
                                                <div class="col-sm-10">
                                                    <input id="nama" name="nama" type="text"
                                                        class="form-control {{ isset($nama) ? ($nama->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Nama Indikator"
                                                        value="{{ old('nama', $getmeta->nama_data) }}" readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                                                <div class="col-sm-10">
                                                    <input id="konsep" name="konsep" type="text"
                                                        class="form-control {{ isset($konsep) ? ($konsep->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Konsep"
                                                        value="{{ old('ukuran', optional($getmeta->indikator)->konsep ?? optional($getmeta->standar)->konsep) }}"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="Definisi" class="col-sm-2 col-form-label">Definisi</label>
                                                <div class="col-sm-10">
                                                    <textarea name="definisi"
                                                        class="form-control {{ isset($definisi) ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        style="height: 100px" spellcheck="false" placeholder="Definisi" readonly>{{ old('definisi', optional($getmeta->indikator)->definisi ?? optional($getmeta->standar)->definisi) }}</textarea>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="interpretasi"
                                                    class="col-sm-2 col-form-label">Interpretasi</label>
                                                <div class="col-sm-10">
                                                    <input id="interpretasi" name="interpretasi" type="text"
                                                        class="form-control {{ isset($interpretasi) ? ($interpretasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Interpretasi"
                                                        value="{{ old('interpretasi', optional($getmeta->indikator)->interpretasi) }}"
                                                        readonly>
                                                </div>
                                            </div>

                                            @php
                                                $isImage =
                                                    optional($getmeta->indikator)->metode &&
                                                    Str::startsWith(optional($getmeta->indikator)->metode, 'public/');
                                            @endphp
                                            <div class="row mb-3">
                                                <label for="metode" class="col-sm-2 col-form-label">Metode / Rumus
                                                    Perhitungan</label>
                                                <div class="col-sm-10">
                                                    @if ($isImage)
                                                        <img class="img-fluid rounded" style="max-height: 250px; object-fit: contain;"
                                                            src="{{ Storage::url(optional($getmeta->indikator)->metode) }}">
                                                    @else
                                                        <textarea name="metode" id="metode" class="form-control" style="min-height: 80px; resize: vertical;" readonly>{{ optional($getmeta->indikator)->metode }}</textarea>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                                                <div class="col-sm-10">
                                                    <input id="ukuran" name="ukuran" type="text"
                                                        class="form-control {{ isset($ukuran) ? ($ukuran->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Ukuran"
                                                        value="{{ old('ukuran', optional($getmeta->indikator)->ukuran ?? optional($getmeta->standar)->ukuran) }}"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                                                <div class="col-sm-10">
                                                    <input id="satuan" name="satuan" type="text"
                                                        class="form-control {{ isset($satuan) ? ($satuan->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Satuan"
                                                        value="{{ old('satuan', optional($getmeta->indikator)->satuan ?? optional($getmeta->standar)->satuan) }}"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="klasifikasi_penyajian"
                                                    class="col-sm-2 col-form-label">Klasifikasi
                                                    Penyajian</label>
                                                <div class="col-sm-10">
                                                    <textarea name="klasifikasi_penyajian"
                                                        class="form-control {{ isset($klasifikasi_penyajian) ? ($klasifikasi_penyajian->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        style="height: 100px" spellcheck="false" placeholder="Klasifikasi Penyajian" readonly>{{ old('klasifikasi_penyajian', optional($getmeta->indikator)->klasifikasi_penyajian) }}</textarea>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="komposit" class="col-sm-2 col-form-label">Apakah indikator
                                                    komposit?</label>
                                                <div class="col-sm-10">
                                                    <div class="form-check"><input class="form-check-input"
                                                            type="radio" name="komposit" id="komposit1" value="1"
                                                            {{ old('komposit', optional($getmeta->indikator)->komposit) == 1 ? 'checked' : '' }}
                                                            readonly>
                                                        <label class="form-check-label" for="gridRadios1"> Ya </label>
                                                    </div>
                                                    <div class="form-check"><input class="form-check-input"
                                                            type="radio" name="komposit" id="komposit2" value="0"
                                                            {{ old('komposit', optional($getmeta->indikator)->komposit) == 0 ||
                                                            empty(old('komposit', optional($getmeta->indikator)->komposit))
                                                                ? 'checked'
                                                                : '' }}
                                                            readonly>
                                                        <label class="form-check-label" for="gridRadios1"> Tidak </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <section class="komposit-section">
                                                <div class="row mb-3">
                                                    <label for="publikasi_indikator_pembangun"
                                                        class="col-sm-2 col-form-label">Publikasi Ketersediaan
                                                        Indikator Pembangun</label>
                                                    <div class="col-sm-10">
                                                        <input id="publikasi_indikator_pembangun"
                                                            name="publikasi_indikator_pembangun" type="text"
                                                            class="form-control {{ isset($publikasi_indikator_pembangun) ? ($publikasi_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                            value="{{ old('publikasi_indikator_pembangun', optional($getmeta->indikator)->publikasi_indikator_pembangun) }}"
                                                            readonly>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="nama_indikator_pembangun"
                                                        class="col-sm-2 col-form-label">Nama
                                                        Indikator
                                                        Pembangun</label>
                                                    <div class="col-sm-10">
                                                        <textarea name="nama_indikator_pembangun"
                                                            class="form-control {{ isset($nama_indikator_pembangun) ? ($nama_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                            style="height: 100px" spellcheck="false" placeholder="Nama Indikator Pembangun" readonly>{{ old('nama_indikator_pembangun', optional($getmeta->indikator)->nama_indikator_pembangun) }}</textarea>
                                                        <small class="text-muted">Daftar nama dipisah menggunakan
                                                            enter.</small>
                                                    </div>
                                                </div>
                                            </section>
                                            <section class="no-komposit-section">
                                                <div class="row mb-3">
                                                    <label for="nama_variabel_pembangun"
                                                        class="col-sm-2 col-form-label">Nama
                                                        Variabel Pembangun</label>
                                                    <div class="col-sm-10">
                                                        <textarea name="nama_variabel_pembangun"
                                                            class="form-control {{ isset($nama_variabel_pembangun) ? ($nama_variabel_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                            style="height: 100px" spellcheck="false" placeholder="Nama Variabel Pembangun" readonly>{{ old('nama_variabel_pembangun', optional($getmeta->indikator)->nama_variabel_pembangun) }}</textarea>
                                                        <small class="text-muted">Daftar nama dipisah menggunakan
                                                            enter.</small>
                                                    </div>
                                                </div>
                                            </section>

                                            <div class="row mb-3">
                                                <label for="level_estimasi" class="col-sm-2 col-form-label">Level
                                                    Estimasi</label>
                                                <div class="col-sm-10">
                                                    @php
                                                        $lvl = optional($getmeta->indikator)->level_estimasi;
                                                        $lvlLabels = [
                                                            'nasional' => 'Nasional',
                                                            'provinsi' => 'Provinsi',
                                                            'kabupaten' => 'Kabupaten/Kota',
                                                            'perangkat_daerah' => 'Perangkat Daerah',
                                                            'kecamatan' => 'Kecamatan',
                                                            'kelurahan' => 'Desa/Kelurahan',
                                                            'rt' => 'Rumah Tangga',
                                                            'individu' => 'Individu',
                                                        ];
                                                        $displayLevel = $lvlLabels[$lvl] ?? ucwords(str_replace('_', ' ', $lvl ?: 'Kabupaten/Kota'));
                                                    @endphp
                                                    <input id="level_estimasi" name="level_estimasi" type="text"
                                                        class="form-control {{ isset($level_estimasi) ? ($level_estimasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        value="{{ $displayLevel }}" readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="umum1" class="col-sm-2 col-form-label">Apakah kolom ini
                                                    dapat
                                                    diakses umum</label>
                                                <div class="col-sm-10">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="umum"
                                                            id="umum1" value="1"
                                                            {{ old('umum', optional($getmeta->indikator)->umum) == 1 ||
                                                            empty(old('umum', optional($getmeta->indikator)->umum))
                                                                ? 'checked'
                                                                : '' }}
                                                            disabled>
                                                        <label class="form-check-label" for="umum1">
                                                            Ya
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="umum"
                                                            id="umum2" value="0"
                                                            {{ old('umum', optional($getmeta->indikator)->umum) == 0 ? 'checked' : '' }}
                                                            disabled>
                                                        <label class="form-check-label" for="umum2">
                                                            Tidak
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row mb-3">
                                                <label for="nama" class="col-sm-2 col-form-label">Nama
                                                    Variabel</label>
                                                <div class="col-sm-10">
                                                    <input id="nama" name="nama" type="text"
                                                        class="form-control {{ isset($nama) ? ($nama->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Nama Variabel"
                                                        value="{{ old('nama', $getmeta->nama_data) }}" readonly>

                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="alias" class="col-sm-2 col-form-label">Alias</label>
                                                <div class="col-sm-10">
                                                    <input id="alias" name="alias" type="text"
                                                        class="form-control {{ isset($alias) ? ($alias->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Alias"
                                                        value="{{ old('alias', optional($getmeta->variabel)->alias) }}"
                                                        readonly>

                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                                                <div class="col-sm-10">
                                                    <textarea name="konsep"
                                                        class="form-control {{ isset($konsep) ? ($konsep->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        style="height: 100px" spellcheck="false" placeholder="Konsep" readonly>{{ old('konsep', optional($getmeta->variabel)->konsep ?? optional($getmeta->standar)->konsep) }}</textarea>

                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="definisi" class="col-sm-2 col-form-label">Definisi</label>
                                                <div class="col-sm-10">
                                                    <textarea id="definisi" name="definisi"
                                                        class="form-control {{ isset($definisi) ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        style="min-height: 100px; resize: vertical;" spellcheck="false" placeholder="Definisi" readonly>{{ old('definisi', optional($getmeta->variabel)->definisi ?? optional($getmeta->standar)->definisi) }}</textarea>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="referensi_pemilihan" class="col-sm-2 col-form-label">Referensi
                                                    Pemilihan</label>
                                                <div class="col-sm-10">
                                                    <input id="referensi_pemilihan" name="referensi_pemilihan"
                                                        type="text"
                                                        class="form-control {{ isset($referensi_pemilihan) ? ($referensi_pemilihan->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Referensi Pemilihan"
                                                        value="{{ old('referensi_pemilihan', optional($getmeta->variabel)->referensi_pemilihan) }}"
                                                        readonly>

                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="referensi_waktu" class="col-sm-2 col-form-label">Referensi
                                                    Waktu</label>
                                                <div class="col-sm-10">
                                                    <input id="referensi_waktu" name="referensi_waktu" type="text"
                                                        class="form-control {{ isset($referensi_waktu) ? ($referensi_waktu->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Referensi Waktu"
                                                        value="{{ old('referensi_waktu', optional($getmeta->variabel)->referensi_waktu) }}"
                                                        readonly>

                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="tipe_data" class="col-sm-2 col-form-label">Tipe Data</label>
                                                <div class="col-sm-10">
                                                    <input id="tipe_data" name="tipe_data" type="text"
                                                        class="form-control {{ isset($tipe_data) ? ($tipe_data->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        value="{{ ucfirst(optional($getmeta->variabel)->tipe_data ?: 'Integer') }}" readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                                                <div class="col-sm-10">
                                                    <input id="ukuran" name="ukuran" type="text"
                                                        class="form-control {{ isset($ukuran) ? ($ukuran->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Ukuran"
                                                        value="{{ old('ukuran', optional($getmeta->variabel)->ukuran ?? optional($getmeta->standar)->ukuran) }}"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                                                <div class="col-sm-10">
                                                    <input id="satuan" name="satuan" type="text"
                                                        class="form-control {{ isset($satuan) ? ($satuan->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Satuan"
                                                        value="{{ old('satuan', optional($getmeta->variabel)->satuan ?? optional($getmeta->standar)->satuan) }}"
                                                        readonly>

                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="klasifikasi_isian" class="col-sm-2 col-form-label">Klasifikasi
                                                    Isian</label>
                                                <div class="col-sm-10">
                                                    <textarea name="klasifikasi_isian"
                                                        class="form-control {{ isset($klasifikasi_isian) ? ($klasifikasi_isian->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        style="height: 100px" spellcheck="false" placeholder="Klasifikasi Isian" readonly>{{ old('klasifikasi_isian', optional($getmeta->variabel)->klasifikasi_isian ?? optional($getmeta->standar)->klasifikasi) }}</textarea>

                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="aturan_validasi" class="col-sm-2 col-form-label">Aturan
                                                    Validasi</label>
                                                <div class="col-sm-10">
                                                    <textarea name="aturan_validasi"
                                                        class="form-control {{ isset($aturan_validasi) ? ($aturan_validasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        style="height: 100px" spellcheck="false" placeholder="Aturan Validasi" readonly>{{ old('aturan_validasi', optional($getmeta->variabel)->aturan_validasi) }}</textarea>

                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="kalimat_pertanyaan" class="col-sm-2 col-form-label">Kalimat
                                                    Pertanyaan</label>
                                                <div class="col-sm-10">
                                                    <textarea name="kalimat_pertanyaan"
                                                        class="form-control {{ isset($kalimat_pertanyaan) ? ($kalimat_pertanyaan->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        style="height: 100px" spellcheck="false" placeholder="Kalimat Pertanyaan" readonly>{{ old('kalimat_pertanyaan', optional($getmeta->variabel)->kalimat_pertanyaan) }}</textarea>

                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="umum1" class="col-sm-2 col-form-label">Apakah kolom ini
                                                    dapat
                                                    diakses umum</label>
                                                <div class="col-sm-10">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="umum"
                                                            id="umum1" value="1"
                                                            {{ old('umum', optional($getmeta->variabel)->umum) == 1 ||
                                                            empty(old('umum', optional($getmeta->variabel)->umum))
                                                                ? 'checked'
                                                                : '' }}
                                                            disabled>
                                                        <label class="form-check-label" for="umum1">
                                                            Ya
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="umum"
                                                            id="umum2" value="0"
                                                            {{ old('umum', optional($getmeta->variabel)->umum) == 0 ? 'checked' : '' }}
                                                            disabled>
                                                        <label class="form-check-label" for="umum2">
                                                            Tidak
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $activeTabName == 'tabel' ? 'show active' : '' }}" id="tabel" role="tabpanel" aria-labelledby="tabel-tab">
                                @if ($tables)
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                                <div class="d-flex align-items-center gap-2">
                                                    <h5 class="card-title mb-0 fw-bold"><i class="fas fa-table text-primary me-2"></i>Visualisasi Tabel Data</h5>
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small">
                                                        <i class="fas fa-sync-alt me-1"></i> Tersinkron dengan Filter Atas
                                                    </span>
                                                </div>
                                                <div class="d-flex gap-2 align-items-center mt-2 mt-md-0">
                                                    <a href="{{ route('api.v1.datasets.detail.web', $getmeta ? $getmeta->id : ($dataset['id'] ?? 1)) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-code me-1"></i> Endpoint API JSON
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <div class="col-md-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                                                        <input type="text" id="tabTableFilterInput" class="form-control form-control-sm border-start-0 dataset-filter-input" placeholder="Saring baris tabel data..." value="{{ request('filter') ?? request('tahun') ?? request('q') ?? '' }}" onkeyup="filterDatasetTable(this)">
                                                        <button class="btn btn-outline-secondary btn-sm clear-filter-btn" type="button" onclick="clearDatasetTableFilter()" title="Reset Filter" style="{{ (request('filter') || request('tahun') || request('q')) ? '' : 'display: none;' }}">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @if ($availableYears->isNotEmpty())
                                                    <div class="col-md-6 mt-2 mt-md-0">
                                                        <div class="d-flex align-items-center gap-1 flex-wrap justify-content-md-end">
                                                            <span class="small text-muted me-1 fw-semibold"><i class="fas fa-calendar-alt text-primary me-1"></i>Tahun:</span>
                                                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 active year-filter-pill" onclick="setQuickYearFilter('')">Semua</button>
                                                            @foreach ($availableYears as $yr)
                                                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 year-filter-pill" onclick="setQuickYearFilter('{{ $yr }}')">{{ $yr }}</button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="table-responsive shadow-sm border rounded-3" style="max-height: 550px; overflow-y: auto; overflow-x: auto;">
                                                <div class="p-2">
                                                    @foreach ($tables as $tableData)
                                                        @php
                                                            $table = is_array($tableData) ? ($tableData['table'] ?? null) : $tableData;
                                                            $headers = is_array($tableData) ? ($tableData['headers'] ?? collect()) : ($tableData->header ?? collect());
                                                            $rawRows = is_array($tableData) ? ($tableData['rows'] ?? collect()) : ($tableData->isi ? $tableData->isi->groupBy('urutan_kebawah') : collect());
                                                            $rows = $rawRows;
                                                            $tableName = $table ? ($table->namatabel ?? ($table->nama_table ?? 'Tabel Data')) : 'Tabel Data';
                                                        @endphp

                                                        <h5 class="fw-bold text-dark mt-2 mb-3"><i class="fas fa-table text-primary me-2"></i>{{ $tableName }}</h5>

                                                        @if ($headers->isNotEmpty() && $rows->isNotEmpty())
                                                            <table class="table table-striped table-hover align-middle visual-dataset-table">
                                                                <thead>
                                                                    <tr>
                                                                        @foreach ($headers->sortBy('urutan_menyamping') as $header)
                                                                            <th>{{ $header->header }}</th>
                                                                        @endforeach

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($rows->values() as $rowIndex => $row)
                                                                        <tr>
                                                                            @foreach ($headers->sortBy('urutan_menyamping')->values() as $header)
                                                                                @php
                                                                                    // dd($rowIndex);
                                                                                    $cell = $row->firstWhere(
                                                                                        'id_header',
                                                                                        $header->id,
                                                                                    );
                                                                                    $cellValue = $cell
                                                                                        ? $cell->isi
                                                                                        : '';
                                                                                    $originalCellValue = $cell
                                                                                        ? $cell->isi
                                                                                        : '0';
                                                                                @endphp
                                                                                <td>
                                                                                    <div data-table="{{ $table->id }}"
                                                                                        data-row="{{ $rowIndex }}"
                                                                                        data-header="{{ $header->id }}"
                                                                                        data-cell="{{ $cell ? $cell->id : '' }}"
                                                                                        class="editable-cell">
                                                                                        {{ $originalCellValue }}
                                                                                    </div>
                                                                                </td>
                                                                            @endforeach

                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        @else
                                                            <p>No data available.</p>
                                                        @endif

                                                        <hr>
                                                    @endforeach
                                                </div>

                                            </div>



                                        </div>
                                    </div>
                                @else
                                    <div class="card card-detail-modern border-0 shadow-sm">
                                        <div class="card-body text-center py-5">
                                            <div class="mb-3 text-muted">
                                                <i class="fas fa-table fa-3x opacity-50"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark">Visualisasi Tabel Belum Tersedia</h6>
                                            <p class="text-muted small mb-0">Dataset ini belum memiliki data tabular di sistem. Anda dapat mengunduh berkas lengkapnya pada bagian <strong>Daftar Berkas & Data Terbuka</strong> di atas.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade {{ $activeTabName == 'grafik' ? 'show active' : '' }}" id="grafik" role="tabpanel" aria-labelledby="grafik-tab">
                                @if (!empty($tables) && count($tables) > 0 && $existingData->isNotEmpty())
                                    @foreach ($tables as $k => $table)
                                        @php
                                            $curExisting = $existingData[$k] ?? $existingData->first() ?? null;
                                        @endphp
                                        <div class="row g-4 align-items-start my-2">
                                            <!-- Sisi Kiri: Visualisasi Grafik -->
                                            <div class="col-lg-8 col-xl-9">
                                                <div class="card card-detail-modern border-0 shadow-sm overflow-hidden">
                                                    <div class="card-header bg-white p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary" style="width: 36px; height: 36px; font-size: 16px;">
                                                                <i class="fas fa-chart-bar"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-bold text-dark mb-0">Visualisasi Grafik</h6>
                                                                <small class="text-muted">{{ $table['table']['namatabel'] ?? 'Tabel Data' }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <label for="chartTypeSelect{{ $k }}" class="small fw-semibold text-muted text-nowrap mb-0 d-none d-sm-inline">Tipe Grafik:</label>
                                                            <select id="chartTypeSelect{{ $k }}" class="form-select form-select-sm fw-medium select-chart-type" style="width: auto; min-width: 130px; border-radius: 6px;">
                                                                <option value="bar" selected>Bar Chart</option>
                                                                <option value="line">Line Chart</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-3 p-md-4 bg-white">
                                                        <div id="chartContainer{{ $k }}" class="w-100" style="min-height: 400px;">
                                                            <div id="chart{{ $k }}"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Sisi Kanan: Pengaturan Parameter Grafik -->
                                            <div class="col-lg-4 col-xl-3">
                                                <div class="card card-detail-modern border-0 shadow-sm p-3 p-md-4">
                                                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                                        <div class="stat-icon-wrapper bg-info bg-opacity-10 text-primary" style="width: 32px; height: 32px; font-size: 14px;">
                                                            <i class="fas fa-sliders-h"></i>
                                                        </div>
                                                        <h6 class="fw-bold text-dark mb-0">Parameter Grafik</h6>
                                                    </div>

                                                    <form id="grafikForm{{ $k }}" action="{{ route('dataset.chart.storeDataByFilter') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id_data" value="{{ $table['table']['id_data'] }}">
                                                        <input type="hidden" name="id_table" value="{{ $table['table']['id'] }}">

                                                        <div class="mb-3">
                                                            <label for="dropdown_axis_x{{ $k }}" class="form-label small fw-semibold text-muted mb-1">
                                                                <i class="fas fa-arrows-alt-h me-1 text-primary"></i> Axis X (Grup / Kategori)
                                                            </label>
                                                            <select name="axis_x" id="dropdown_axis_x{{ $k }}" class="form-select form-select-sm select-axis-x" style="border-radius: 6px;">
                                                                <option value="0">-- Data Tunggal --</option>
                                                                @foreach ($table['headers'] as $header)
                                                                    <option value="{{ $header->id }}" @if ($curExisting && $curExisting->axis_x == $header->id) selected @endif>
                                                                        {{ $header->header }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="dropdown_axis_y{{ $k }}" class="form-label small fw-semibold text-muted mb-1">
                                                                <i class="fas fa-arrows-alt-v me-1 text-success"></i> Axis Y (Nilai Data)
                                                            </label>
                                                            <select name="axis_y" id="dropdown_axis_y{{ $k }}" class="form-select form-select-sm select-axis-y" style="border-radius: 6px;">
                                                                @foreach ($table['headers'] as $header)
                                                                    <option value="{{ $header->id }}" @if ($curExisting && $curExisting->axis_y == $header->id) selected @endif>
                                                                        {{ $header->header }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="dropdown_category{{ $k }}" class="form-label small fw-semibold text-muted mb-1">
                                                                <i class="fas fa-calendar-alt me-1 text-info"></i> Kategori (Waktu / Tahun)
                                                            </label>
                                                            <select name="kategori" id="dropdown_category{{ $k }}" class="form-select form-select-sm select-category" style="border-radius: 6px;">
                                                                @foreach ($table['headers'] as $header)
                                                                    @if ($header->header == 'Tahun')
                                                                        <option value="{{ $header->id }}" @if ($curExisting && $curExisting->kategori == $header->id) selected @endif>
                                                                            {{ $header->header }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                                @if ($table['headers']->where('header', 'Tahun')->isEmpty())
                                                                    @foreach ($table['headers'] as $header)
                                                                        <option value="{{ $header->id }}" @if ($curExisting && $curExisting->kategori == $header->id) selected @endif>
                                                                            {{ $header->header }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>

                                                        <button class="btn btn-filter-submit w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold text-nowrap mt-3" id="btn-submit{{ $k }}" type="submit">
                                                            <i class="fas fa-sync-alt"></i> Tampilkan Grafik
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-5">
                                        <div class="mb-3 text-muted" style="font-size: 42px;">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <h6 class="text-muted fw-bold">Belum Ada Data Visualisasi Grafik</h6>
                                        <p class="text-muted small">Data visualisasi belum tersedia untuk dataset ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
            document.addEventListener("DOMContentLoaded", function() {
                const chartInstances = {};

                @foreach ($existingData as $k => $item)
                (function() {
                    const chartIndex = {{ $k }};
                    const chartElem = document.querySelector("#chart" + chartIndex);
                    if (!chartElem) return;

                    const names = {!! $kategori !!};
                    const data = {!! $axis_y !!};
                    const name_y = "{!! addslashes($item->axis_y_header ?? 'Nilai') !!}";
                    const categories = {!! $axis_x !!};
                    const seriesData = @json($seriesData[$item->id] ?? []);
                    const seriesDataLine = @json($seriesDataLine[$item->id] ?? []);

                    function getOptions(type) {
                        let opts = {};
                        const isSingle = (!categories || categories.length === 0);

                        if (type === 'line') {
                            opts = {
                                series: isSingle 
                                    ? [{ name: name_y || 'Nilai', data: data }] 
                                    : (seriesDataLine && seriesDataLine.length ? seriesDataLine : [{ name: name_y || 'Nilai', data: data }]),
                                chart: {
                                    height: 380,
                                    type: 'line',
                                    toolbar: { show: true },
                                    animations: { enabled: true }
                                },
                                stroke: { curve: 'smooth', width: 3 },
                                colors: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1'],
                                xaxis: {
                                    categories: isSingle ? names : categories,
                                    labels: { style: { fontSize: '12px' } }
                                },
                                yaxis: {
                                    title: { text: name_y || 'Nilai' }
                                },
                                tooltip: {
                                    y: {
                                        formatter: function(val) {
                                            return (name_y ? name_y + ': ' : '') + val;
                                        }
                                    }
                                }
                            };
                        } else {
                            // Default Bar Chart
                            opts = {
                                series: isSingle 
                                    ? [{ name: name_y || 'Nilai', data: data }] 
                                    : (seriesData && seriesData.length ? seriesData : [{ name: name_y || 'Nilai', data: data }]),
                                chart: {
                                    height: 380,
                                    type: 'bar',
                                    toolbar: { show: true },
                                    animations: { enabled: true }
                                },
                                plotOptions: {
                                    bar: {
                                        columnWidth: isSingle ? '40%' : '55%',
                                        borderRadius: 6,
                                        horizontal: false,
                                    }
                                },
                                colors: ['#0d6efd', '#198754', '#0dcaf0', '#ffc107', '#fd7e14'],
                                dataLabels: { enabled: false },
                                xaxis: {
                                    categories: isSingle ? names : categories,
                                    labels: { style: { fontSize: '12px' } }
                                },
                                yaxis: {
                                    title: { text: name_y || 'Nilai' }
                                },
                                tooltip: {
                                    y: {
                                        formatter: function(val) {
                                            return (name_y ? name_y + ': ' : '') + val;
                                        }
                                    }
                                }
                            };
                        }
                        return opts;
                    }

                    function renderChart(type) {
                        if (chartInstances[chartIndex]) {
                            chartInstances[chartIndex].destroy();
                        }
                        const options = getOptions(type);
                        chartInstances[chartIndex] = new ApexCharts(chartElem, options);
                        chartInstances[chartIndex].render();
                    }

                    window['renderChart' + chartIndex] = renderChart;

                    $('#chartTypeSelect' + chartIndex).on('change', function() {
                        renderChart($(this).val());
                    });

                    // Render immediately if active
                    const $grafikPane = $('#grafik');
                    if ($grafikPane.hasClass('show') || $grafikPane.hasClass('active')) {
                        renderChart('bar');
                    }
                })();
                @endforeach

                // Tab switch event listener
                const grafikTabBtn = document.querySelector('#grafik-tab');
                if (grafikTabBtn) {
                    grafikTabBtn.addEventListener('shown.bs.tab', function() {
                        setTimeout(function() {
                            @foreach ($existingData as $k => $item)
                                if (typeof window['renderChart{{ $k }}'] === 'function') {
                                    const selType = $('#chartTypeSelect{{ $k }}').val() || 'bar';
                                    window['renderChart{{ $k }}'](selType);
                                }
                            @endforeach
                        }, 150);
                    });
                }

                // Auto-activate tab from session or URL query param
                const urlParams = new URLSearchParams(window.location.search);
                const tabParam = urlParams.get('tab');
                const activeTab = '{{ session('active_tab') }}';
                if (tabParam) {
                    const targetBtn = document.querySelector('#' + tabParam + '-tab');
                    if (targetBtn) {
                        targetBtn.click();
                    }
                } else if (activeTab === 'grafik' && grafikTabBtn) {
                    grafikTabBtn.click();
                }

                // Init table & berkas filter if prefilled
                var filterInput = document.getElementById('liveTableFilterInput');
                if (filterInput && filterInput.value.trim()) {
                    filterDatasetTable();
                }

                // Tangkap klik tombol unduh
                $(document).on('click', '.download-btn', function() {
                    var urlDownload = $(this).data('url-download');
                    var datasetId = '{{ $dataset["id"] ?? "" }}';

                    $.ajax({
                        type: 'GET',
                        url: '{{ route("download.file.count") }}',
                        data: {
                            url_download: urlDownload,
                            id_data: datasetId
                        },
                        success: function(response) {
                            var $counter = $('#datasetDownloadCounter');
                            if ($counter.length) {
                                var curr = parseInt($counter.text().replace(/\D/g, '')) || 0;
                                $counter.text((curr + 1).toLocaleString('id-ID'));
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error tracking download count:', error);
                        }
                    });
                });
            });

        function filterDatasetTable(sourceInput) {
            var rawFilter = '';
            if (sourceInput && typeof sourceInput.value !== 'undefined') {
                rawFilter = sourceInput.value.trim();
            } else {
                var firstInput = document.querySelector(".dataset-filter-input");
                if (firstInput) {
                    rawFilter = firstInput.value.trim();
                }
            }
            var filter = rawFilter.toUpperCase();

            // Sync all input elements across the page
            var allInputs = document.querySelectorAll(".dataset-filter-input");
            allInputs.forEach(function(inp) {
                if (inp !== sourceInput && inp.value !== rawFilter) {
                    inp.value = rawFilter;
                }
            });

            // Update all clear buttons
            var clearBtns = document.querySelectorAll(".clear-filter-btn");
            clearBtns.forEach(function(btn) {
                btn.style.display = rawFilter ? "" : "none";
            });

            // 1. Filter Visual Dataset Tables
            var tables = document.querySelectorAll(".visual-dataset-table");
            tables.forEach(function(table) {
                var tbodyRows = table.querySelectorAll("tbody tr");
                tbodyRows.forEach(function(tr) {
                    if (!filter) {
                        tr.style.display = "";
                    } else {
                        var text = (tr.textContent || tr.innerText || "").toUpperCase();
                        tr.style.display = text.indexOf(filter) > -1 ? "" : "none";
                    }
                });
            });

            // 2. Synchronize filter to Detail Dataset (Daftar Berkas)
            var berkasRows = document.querySelectorAll(".berkas-row");
            var matchedBerkas = 0;
            var totalBerkas = berkasRows.length;

            berkasRows.forEach(function(row) {
                if (!filter) {
                    row.style.display = "";
                    matchedBerkas++;
                } else {
                    var searchData = (row.getAttribute("data-search") || "").toUpperCase();
                    var tahunData = (row.getAttribute("data-tahun") || "").toUpperCase();
                    var rowText = (row.textContent || row.innerText).toUpperCase();
                    if (searchData.indexOf(filter) > -1 || tahunData.indexOf(filter) > -1 || rowText.indexOf(filter) > -1) {
                        row.style.display = "";
                        matchedBerkas++;
                    } else {
                        row.style.display = "none";
                    }
                }
            });

            var noMatchRow = document.getElementById("berkasNoMatchRow");
            if (noMatchRow) {
                noMatchRow.style.display = (totalBerkas > 0 && matchedBerkas === 0) ? "" : "none";
            }
            var countBadge = document.getElementById("berkasCountBadge");
            if (countBadge) {
                if (!filter) {
                    countBadge.innerHTML = totalBerkas + ' Berkas Tersedia';
                    countBadge.className = 'badge bg-light text-secondary border px-2 py-1';
                } else {
                    countBadge.innerHTML = '<i class="fas fa-filter text-primary me-1"></i> ' + matchedBerkas + ' dari ' + totalBerkas + ' Berkas Cocok ("' + rawFilter + '")';
                    countBadge.className = 'badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1';
                }
            }

            // Update Year Pills Active State
            document.querySelectorAll(".year-filter-pill").forEach(function(pill) {
                var pText = pill.textContent.trim();
                if ((!rawFilter && pText === 'Semua') || (rawFilter && pText === rawFilter)) {
                    pill.classList.add('active');
                } else {
                    pill.classList.remove('active');
                }
            });
        }

        function clearDatasetTableFilter() {
            var allInputs = document.querySelectorAll(".dataset-filter-input");
            allInputs.forEach(function(inp) {
                inp.value = "";
            });
            filterDatasetTable();
            if (allInputs.length > 0) {
                allInputs[0].focus();
            }
        }

        function setQuickYearFilter(year) {
            var allInputs = document.querySelectorAll(".dataset-filter-input");
            allInputs.forEach(function(inp) {
                inp.value = year;
            });
            filterDatasetTable();
        }

        function initDatasetTableFilterFromUrl() {
            var allInputs = document.querySelectorAll(".dataset-filter-input");
            if (allInputs.length > 0 && allInputs[0].value.trim()) {
                filterDatasetTable(allInputs[0]);
            }
        }
        if (document.readyState !== 'loading') {
            initDatasetTableFilterFromUrl();
        } else {
            document.addEventListener("DOMContentLoaded", initDatasetTableFilterFromUrl);
        }
    </script>
@endpush
