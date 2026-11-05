@extends('pages.main.layout')

@section('content')

<div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1>Dashboard {{ !empty($selectedTahun) ? $selectedTahun : '' }}</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/d_produsen">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ auth()->user()->opd->nama_opd ?? 'Produsen Data' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="/panduan/buku-panduan-portal-satu-data.pdf" target="_blank" download class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-pdf me-1"></i> Unduh Buku Panduan Portal
            </a>
        </div>
    </div>
</div><!-- End Page Title -->

<section class="section dashboard mt-3">
    <!-- Filter Card -->
    <div class="card mb-3">
        <div class="card-body pt-3">
            <form method="GET" action="{{ route('d_produsen') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Tahun Data</label>
                    <select id="tahun" name="tahun" class="form-select select2 filter" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($tahun as $th)
                            <option value="{{ $th->tahun }}" {{ $selectedTahun == $th->tahun ? 'selected' : '' }}>{{ $th->tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end mt-md-4">
                    @if(!empty($selectedTahun))
                        <a href="{{ route('d_produsen') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- 3 SDI Percentage Metric Cards (Sesuai Halaman 19-20 & Halaman 28) -->
    <div class="row">
        <div class="col-md-4">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">% Keterisian Data</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                            <i class="bi bi-pie-chart"></i>
                        </div>
                        <div class="ps-3">
                            <h3 class="fw-bold mb-0 text-primary">{{ $pctKeterisian }}%</h3>
                            <span class="text-muted small">Pemeriksaan + Revisi + Publikasi</span>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, $pctKeterisian) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">% Data Valid</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="ps-3">
                            <h3 class="fw-bold mb-0 text-success">{{ $pctValid }}%</h3>
                            <span class="text-muted small">Siap Publikasi + Terpublikasi</span>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, $pctValid) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">% Data Terpublikasi</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white">
                            <i class="bi bi-send-check"></i>
                        </div>
                        <div class="ps-3">
                            <h3 class="fw-bold mb-0 text-info">{{ $pctTerpublikasi }}%</h3>
                            <span class="text-muted small">Tayang di Portal Publik</span>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ min(100, $pctTerpublikasi) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Breakdown Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body pt-3">
                    <h5 class="card-title mb-2">Ringkasan Status Tahapan Data Daerah</h5>
                    <div class="row text-center g-2 mb-2">
                        <div class="col-md col-6">
                            <div class="border rounded p-2 bg-light">
                                <div class="small text-muted">Total Data</div>
                                <div class="fs-5 fw-bold text-dark">{{ $daftardata }}</div>
                            </div>
                        </div>
                        <div class="col-md col-6">
                            <div class="border rounded p-2 bg-light">
                                <div class="small text-muted">Data Prioritas</div>
                                <div class="fs-5 fw-bold text-warning"><i class="bi bi-star-fill text-warning me-1"></i>{{ $dataPrioritas }}</div>
                            </div>
                        </div>
                        <div class="col-md col-6">
                            <div class="border rounded p-2 bg-light">
                                <div class="small text-muted">Draft Usulan</div>
                                <div class="fs-5 fw-bold text-secondary">{{ $dataDraft }}</div>
                            </div>
                        </div>
                        <div class="col-md col-6">
                            <div class="border rounded p-2 bg-light">
                                <div class="small text-muted">Pengumpulan</div>
                                <div class="fs-5 fw-bold text-primary">{{ $dataPengumpulan }}</div>
                            </div>
                        </div>
                        <div class="col-md col-6">
                            <div class="border rounded p-2 bg-light">
                                <div class="small text-muted">Pemeriksaan</div>
                                <div class="fs-5 fw-bold text-info">{{ $dataPemeriksaan }}</div>
                            </div>
                        </div>
                        <div class="col-md col-6">
                            <div class="border rounded p-2 bg-light">
                                <div class="small text-muted">Revisi</div>
                                <div class="fs-5 fw-bold text-warning">{{ $dataRevisi }}</div>
                            </div>
                        </div>
                        <div class="col-md col-6">
                            <div class="border rounded p-2 bg-light">
                                <div class="small text-muted">Terpublikasi</div>
                                <div class="fs-5 fw-bold text-success">{{ $dataTerpublikasi }}</div>
                            </div>
                        </div>
                        <div class="col-md col-6">
                            <div class="border rounded p-2 bg-light">
                                <div class="small text-muted">Ditolak</div>
                                <div class="fs-5 fw-bold text-danger">{{ $dataTolak }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">10 Data Terbaru Diperbarui</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Data</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th>Prioritas</th>
                                    <th>Tanggal Pembaruan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataTerbaru as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-semibold">{{ $item->nama_data }}</td>
                                        <td><span class="badge bg-secondary">{{ $item->tahun }}</span></td>
                                        <td>
                                            @if($item->status_id == \App\Models\Data::STATUS_TERPUBLIKASI)
                                                <span class="badge bg-success">Terpublikasi</span>
                                            @elseif($item->status_id == \App\Models\Data::STATUS_SIAP_PUBLIKASI)
                                                <span class="badge bg-info">Siap Publikasi</span>
                                            @elseif($item->status_id == \App\Models\Data::STATUS_REVISI)
                                                <span class="badge bg-warning text-dark">Perlu Revisi</span>
                                            @elseif($item->status_id == \App\Models\Data::STATUS_PROSES_VERIFIKASI)
                                                <span class="badge bg-primary">Pemeriksaan</span>
                                            @elseif($item->status_id == \App\Models\Data::STATUS_TOLAK)
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $item->status->status ?? 'Draft' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->data_prioritas == 1)
                                                <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Prioritas</span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('detail_produsen', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Rincian">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
