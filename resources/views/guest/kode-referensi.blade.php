@extends('guest.layout')

@section('content')
<div class="page-banner pt-40 pb-40" style="background: linear-gradient(135deg, #0f4c81 0%, #1b263b 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Kode Referensi</h1>
            <p class="text-white-50 mb-0">Menampilkan Kode Referensi Wilayah, Puskesmas dan Kode Referensi Indikator Pembangunan</p>
        </div>
    </div>
</div>

<section class="kode-referensi-area pt-50 pb-70 bg-light">
    <div class="container">
        <!-- Navigation Tabs -->
        <ul class="nav nav-pills nav-fill mb-4 bg-white p-2 rounded-3 shadow-sm" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tab == 'wilayah' ? 'active' : '' }}" href="{{ route('guest.kode-referensi', ['tab' => 'wilayah']) }}">
                    <i class="fas fa-map-marked-alt me-2"></i> Kode Wilayah Kecamatan (15)
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tab == 'desa' ? 'active' : '' }}" href="{{ route('guest.kode-referensi', ['tab' => 'desa']) }}">
                    <i class="fas fa-city me-2"></i> Kode Wilayah Desa / Kelurahan
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tab == 'puskesmas' ? 'active' : '' }}" href="{{ route('guest.kode-referensi', ['tab' => 'puskesmas']) }}">
                    <i class="fas fa-hospital me-2"></i> Kode Referensi Puskesmas
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tab == 'sdsn' ? 'active' : '' }}" href="{{ route('guest.kode-referensi', ['tab' => 'sdsn']) }}">
                    <i class="fas fa-book me-2"></i> Standar SDSN & Bappenas
                </a>
            </li>
        </ul>

        <!-- Search Bar -->
        <div class="card shadow-sm border-0 mb-4 rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('guest.kode-referensi') }}" method="GET" class="d-flex gap-2">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <input type="text" name="search" class="form-control" placeholder="{{ $tab == 'sdsn' ? 'Cari standar data SDSN BPS (nama data, konsep, definisi)...' : 'Cari nama atau kode referensi...' }}" value="{{ $search }}">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-search me-1"></i> Cari</button>
                    @if($search)
                        <a href="{{ route('guest.kode-referensi', ['tab' => $tab]) }}" class="btn btn-outline-secondary"><i class="fas fa-redo"></i></a>
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

        <!-- Tab 3: Puskesmas -->
        @if($tab == 'puskesmas')
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-clinic-medical text-primary me-2"></i>Daftar Kode Referensi Puskesmas Kabupaten Madiun</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th class="text-center" style="width: 160px;">Kode Faskes (Kemenkes)</th>
                            <th>Nama Puskesmas</th>
                            <th>Kecamatan</th>
                            <th class="text-center">Jenis Layanan</th>
                            <th>Alamat / Wilayah Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($puskesmas as $index => $item)
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td class="text-center"><span class="badge bg-danger font-monospace">{{ $item['kode'] }}</span></td>
                            <td class="fw-bold text-dark">{{ $item['nama'] }}</td>
                            <td><span class="badge bg-light text-dark border">Kec. {{ $item['kecamatan'] }}</span></td>
                            <td class="text-center">
                                <span class="badge {{ $item['tipe'] == 'Rawat Inap' ? 'bg-success' : 'bg-primary' }}">
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

        <!-- Tab 4: Standar SDSN & Bappenas -->
        @if($tab == 'sdsn')
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-database text-primary me-2"></i>Standar Data Statistik Nasional (SDSN BPS)
                    </h5>
                    <small class="text-muted">Tarikan langsung dari API DNA BPS Republik Indonesia (ms-sds.web.bps.go.id)</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                        <i class="fas fa-check-circle me-1"></i> Terintegrasi API Live
                    </span>
                    <span class="badge bg-secondary">Total: {{ number_format($sdsnTotal, 0, ',', '.') }} Standar Data</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th class="text-center" style="width: 140px;">Kode SDS</th>
                            <th style="min-width: 220px;">Nama Data</th>
                            <th style="min-width: 180px;">Konsep</th>
                            <th style="min-width: 280px;">Definisi</th>
                            <th class="text-center" style="width: 130px;">Ukuran</th>
                            <th class="text-center" style="width: 120px;">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sdsnData as $index => $item)
                        @php
                            $rowNum = (($sdsnPage - 1) * 15) + $index + 1;
                            $kodesds = $item['kode_sds'] ?? ($item['code'] ?? '-');
                            $namadata = $item['nama_data'] ?? ($item['data_name'] ?? '-');
                            $konsep = $item['konsep'] ?? ($item['concept'] ?? '-');
                            $definisi = $item['definisi'] ?? ($item['definition'] ?? '-');
                            $ukuran = $item['ukuran'] ?? '-';
                            $satuan = $item['satuan'] ?? '-';
                        @endphp
                        <tr>
                            <td class="text-center text-muted small">{{ $rowNum }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace">{{ $kodesds }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark text-capitalize">{{ $namadata }}</div>
                            </td>
                            <td class="small text-muted">{{ $konsep }}</td>
                            <td class="small text-muted">{{ $definisi }}</td>
                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $ukuran }}</span></td>
                            <td class="text-center"><span class="badge bg-light text-primary border">{{ $satuan }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-search fa-2x mb-2 text-muted"></i>
                                <p class="mb-0">Standar data SDSN tidak ditemukan untuk kriteria pencarian ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sdsnLastPage > 1)
            <div class="card-footer bg-white p-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span class="small text-muted">
                    Menampilkan halaman <strong>{{ $sdsnPage }}</strong> dari <strong>{{ $sdsnLastPage }}</strong> (Total: {{ number_format($sdsnTotal, 0, ',', '.') }} data)
                </span>
                <nav aria-label="SDSN Pagination">
                    <ul class="pagination pagination-sm mb-0">
                        @if($sdsnPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ route('guest.kode-referensi', ['tab' => 'sdsn', 'search' => $search, 'sdsn_page' => $sdsnPage - 1]) }}">&laquo; Sebelumnya</a>
                        </li>
                        @else
                        <li class="page-item disabled"><span class="page-link">&laquo; Sebelumnya</span></li>
                        @endif

                        <li class="page-item active"><span class="page-link">{{ $sdsnPage }}</span></li>

                        @if($sdsnPage < $sdsnLastPage)
                        <li class="page-item">
                            <a class="page-link" href="{{ route('guest.kode-referensi', ['tab' => 'sdsn', 'search' => $search, 'sdsn_page' => $sdsnPage + 1]) }}">Berikutnya &raquo;</a>
                        </li>
                        @else
                        <li class="page-item disabled"><span class="page-link">Berikutnya &raquo;</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>

        <!-- Documentation Reference Cards (Bappenas & SDSN API) -->
        <div class="row g-4 mt-2">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white p-3 rounded-3 me-3"><i class="fas fa-database fa-2x"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">Kode Referensi Satu Data Indonesia (Bappenas)</h5>
                            <span class="text-muted small">data.go.id/reference</span>
                        </div>
                    </div>
                    <p class="text-muted small">Pedoman standardisasi kode referensi induk Satu Data Indonesia yang mencakup data wilayah administratif, kode instansi/OPD, klasifikasi baku lapangan usaha (KBLI), dan metadata standar nasional.</p>
                    <div class="mt-auto">
                        <a href="https://data.go.id/reference" target="_blank" class="btn btn-outline-primary w-100"><i class="fas fa-external-link-alt me-1"></i> Buka Portal Referensi data.go.id</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success text-white p-3 rounded-3 me-3"><i class="fas fa-chart-line fa-2x"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">Dokumentasi API SDSN BPS</h5>
                            <span class="text-muted small">dna.web.bps.go.id/api/documentation</span>
                        </div>
                    </div>
                    <p class="text-muted small">Katalog referensi konsep, definisi, klasifikasi, ukuran, dan satuan yang diterbitkan oleh Badan Pusat Statistik (BPS) Republik Indonesia sebagai Pembina Data Statistik Nasional.</p>
                    <div class="mt-auto">
                        <a href="https://dna.web.bps.go.id/api/documentation" target="_blank" class="btn btn-outline-success w-100"><i class="fas fa-external-link-alt me-1"></i> Buka Dokumentasi API SDSN BPS</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
