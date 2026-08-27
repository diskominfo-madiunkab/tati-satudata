@extends('guest.layout')

@section('content')
<div class="page-banner pt-60 pb-60" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Kode Referensi</h1>
            <p class="text-white-50 mb-0" style="font-size: 16px;">Menampilkan Kode Referensi Wilayah, Puskesmas dan Kode Referensi Indikator Pembangunan</p>
        </div>
    </div>
</div>

<section class="kode-referensi-area pt-50 pb-70 bg-light">
    <div class="container">
        <!-- Navigation Tabs -->
        <ul class="nav nav-pills nav-fill mb-4 bg-white p-2 rounded-3 shadow-sm" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold {{ $tab == 'wilayah' ? 'active' : '' }}" href="{{ route('guest.kode-referensi', ['tab' => 'wilayah']) }}">
                    <i class="fas fa-map-marked-alt me-2"></i> Kode Wilayah Kecamatan (15)
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold {{ $tab == 'desa' ? 'active' : '' }}" href="{{ route('guest.kode-referensi', ['tab' => 'desa']) }}">
                    <i class="fas fa-city me-2"></i> Kode Wilayah Desa / Kelurahan
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold {{ $tab == 'puskesmas' ? 'active' : '' }}" href="{{ route('guest.kode-referensi', ['tab' => 'puskesmas']) }}">
                    <i class="fas fa-hospital me-2"></i> Kode Referensi Puskesmas (26)
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold {{ $tab == 'sdsn' ? 'active' : '' }}" href="{{ route('guest.kode-referensi', ['tab' => 'sdsn']) }}">
                    <i class="fas fa-book me-2"></i> Standar SDSN (BPS) & Indikator
                </a>
            </li>
        </ul>

        <!-- Search Bar -->
        <div class="card shadow-sm border-0 mb-4 rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('guest.kode-referensi') }}" method="GET" class="d-flex gap-2">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode referensi..." value="{{ $search }}" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 16px;">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold" style="border-radius: 8px;"><i class="fas fa-search me-1"></i> Cari</button>
                    @if($search)
                        <a href="{{ route('guest.kode-referensi', ['tab' => $tab]) }}" class="btn btn-outline-secondary" style="border-radius: 8px;"><i class="fas fa-redo"></i></a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Tab 1: Kecamatan -->
        @if($tab == 'wilayah')
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-map-marker-alt text-primary me-2"></i>Daftar Kode Wilayah Kecamatan (Kabupaten Madiun - 35.19)</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th class="text-center" style="width: 150px;">Kode Kemendagri / Bappenas</th>
                            <th>Nama Kecamatan</th>
                            <th class="text-center">Provinsi / Kabupaten</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($districts as $index => $district)
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td class="text-center"><span class="badge bg-primary fs-6">{{ $district->code }}</span></td>
                            <td class="fw-bold text-dark">{{ $district->name }}</td>
                            <td class="text-center small text-muted">Jawa Timur / Kabupaten Madiun</td>
                            <td class="text-center"><span class="badge bg-success">Standar Nasional</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Data kecamatan tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Tab 2: Desa / Kelurahan -->
        @if($tab == 'desa')
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-home text-primary me-2"></i>Daftar Kode Wilayah Desa / Kelurahan</h5>
                <span class="badge bg-secondary">Total: {{ $villages->total() }} Desa/Kelurahan</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th class="text-center" style="width: 180px;">Kode Wilayah</th>
                            <th>Nama Desa / Kelurahan</th>
                            <th>Kecamatan</th>
                            <th class="text-center">Tipe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($villages as $index => $village)
                        <tr>
                            <td class="text-center text-muted">{{ $villages->firstItem() + $index }}</td>
                            <td class="text-center"><span class="badge bg-secondary font-monospace">{{ $village->code }}</span></td>
                            <td class="fw-bold text-dark">{{ $village->name }}</td>
                            <td class="text-muted"><span class="badge bg-light text-dark border">{{ $village->district_name ?? '-' }}</span></td>
                            <td class="text-center"><span class="badge bg-info text-dark">Desa / Kelurahan</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Data desa/kelurahan tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($villages->hasPages())
                <div class="card-footer bg-white p-3 d-flex justify-content-center">
                    {{ $villages->withQueryString()->links() }}
                </div>
            @endif
        </div>
        @endif

        <!-- Tab 3: Puskesmas (26 Puskesmas Sesuai KMK Kemenkes 2023) -->
        @if($tab == 'puskesmas')
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-clinic-medical text-primary me-2"></i>Daftar Kode Referensi Puskesmas Kabupaten Madiun</h5>
                    <small class="text-muted">Berdasarkan Keputusan Menteri Kesehatan RI No. HK.01.07/MENKES/2099/2023</small>
                </div>
                <span class="badge bg-danger">Total: {{ count($puskesmas) }} Puskesmas</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th class="text-center" style="width: 150px;">Kode Puskesmas</th>
                            <th>Nama Puskesmas</th>
                            <th>Kecamatan</th>
                            <th class="text-center">Kemampuan Pelayanan</th>
                            <th>Alamat / Wilayah Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($puskesmas as $index => $item)
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td class="text-center"><span class="badge bg-danger font-monospace fs-6">{{ $item['kode'] }}</span></td>
                            <td class="fw-bold text-dark">{{ $item['nama'] }}</td>
                            <td><span class="badge bg-light text-dark border">Kec. {{ $item['kecamatan'] }}</span></td>
                            <td class="text-center">
                                <span class="badge {{ $item['tipe'] == 'Rawat Inap' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $item['tipe'] }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $item['alamat'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Data puskesmas tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Tab 4: SDSN & Bappenas Reference Table (Live API BPS & Standards) -->
        @if($tab == 'sdsn')
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-chart-line text-success me-2"></i>Standar Data Statistik Nasional (SDSN BPS & SDI)</h5>
                    <small class="text-muted">Katalog referensi konsep, definisi, ukuran, satuan, dan klasifikasi standar nasional</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="https://dna.web.bps.go.id" target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-external-link-alt me-1"></i> Buka Portal SDSN BPS
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th class="text-center" style="width: 140px;">Kode SDS</th>
                            <th>Nama Data / Indikator</th>
                            <th>Konsep</th>
                            <th>Definisi</th>
                            <th class="text-center">Ukuran / Satuan</th>
                            <th class="text-center">Klasifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sdsnData as $idx => $sds)
                        @php
                            $sdsArr = (array) $sds;
                            $code = $sdsArr['code'] ?? $sdsArr['kode'] ?? ('SDS-3519-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT));
                            $dataName = $sdsArr['data_name'] ?? $sdsArr['nama_data'] ?? '-';
                            $concept = $sdsArr['concept'] ?? $sdsArr['konsep'] ?? '-';
                            $definition = $sdsArr['definition'] ?? $sdsArr['definisi'] ?? '-';
                            $size = $sdsArr['size'] ?? $sdsArr['ukuran'] ?? '-';
                            $unit = $sdsArr['unit'] ?? $sdsArr['satuan'] ?? '-';
                            $classification = $sdsArr['classification'] ?? $sdsArr['klasifikasi'] ?? '-';
                        @endphp
                        <tr>
                            <td class="text-center text-muted small">{{ (($sdsnPage - 1) * 15) + $idx + 1 }}</td>
                            <td class="text-center"><span class="badge bg-success font-monospace">{{ $code }}</span></td>
                            <td class="fw-bold text-dark">{{ $dataName }}</td>
                            <td class="small text-muted">{{ $concept }}</td>
                            <td class="small text-secondary" style="max-width: 300px;">{{ Str::limit($definition, 120) }}</td>
                            <td class="text-center small">
                                <span class="badge bg-light text-dark border">{{ $size }}</span><br>
                                <span class="text-muted">{{ $unit }}</span>
                            </td>
                            <td class="text-center small">
                                <span class="badge bg-info text-dark">{{ $classification }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Data Standar Data Statistik Nasional tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sdsnTotal > 15)
            <div class="card-footer bg-white p-3 d-flex justify-content-center">
                <nav>
                    <ul class="pagination mb-0">
                        <li class="page-item {{ $sdsnPage <= 1 ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ route('guest.kode-referensi', ['tab' => 'sdsn', 'sdsn_page' => $sdsnPage - 1, 'search' => $search]) }}">« Sebelumnya</a>
                        </li>
                        <li class="page-item disabled">
                            <span class="page-link">Halaman {{ $sdsnPage }} (Total: {{ $sdsnTotal }} data)</span>
                        </li>
                        <li class="page-item {{ ($sdsnPage * 15) >= $sdsnTotal ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ route('guest.kode-referensi', ['tab' => 'sdsn', 'sdsn_page' => $sdsnPage + 1, 'search' => $search]) }}">Selanjutnya »</a>
                        </li>
                    </ul>
                </nav>
            </div>
            @endif
        </div>
        @endif
    </div>
</section>
@endsection
