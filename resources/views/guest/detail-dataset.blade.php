@extends('guest.layout')

@section('content')

    <div class="page-banner pt-50 pb-50" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%);">
        <div class="container">
            <div class="page-banner-content text-center text-white">
                <h1 class="text-white fw-bold mb-2">Detail Dataset</h1>
                <ul class="d-flex justify-content-center list-unstyled gap-2 text-white-50 mb-0 small">
                    <li><a href="{{ '/' }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                    <li>/</li>
                    <li><a href="{{ '/dataset' }}" class="text-white-50 text-decoration-none">Dataset</a></li>
                    <li>/</li>
                    <li class="text-white">Detail</li>
                </ul>
            </div>
        </div>
    </div>

    <section class="blog-details pt-50 pb-70 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-details-text-area details-text-area">
                        <!-- Top Metadata Card -->
                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4 bg-white">
                            <div class="card-header bg-white border-0 p-4 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <h4 class="fw-bold text-dark mb-1">{{ $dataset['title'] }}</h4>
                                    <span class="badge bg-primary px-3 py-1 me-2"><i class="fas fa-database me-1"></i> Data Sektoral</span>
                                    <span class="badge bg-light text-dark border px-3 py-1"><i class="fas fa-building me-1"></i> {{ $dataset['organization']['title'] ?? 'Pemkab Madiun' }}</span>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge bg-light text-dark border px-3 py-2">
                                        <i class="fas fa-eye text-primary me-1"></i> Dilihat: <strong>{{ $getmeta->views_count ?? 1 }}</strong> kali
                                    </span>
                                    <span class="badge bg-light text-dark border px-3 py-2">
                                        <i class="fas fa-download text-success me-1"></i> Diunduh: <strong>{{ $getmeta->downloads_count ?? 0 }}</strong> kali
                                    </span>
                                </div>
                            </div>

                            <div class="card-body p-4 pt-2">
                                <div class="p-3 bg-light rounded-3 mb-4">
                                    <h6 class="fw-bold text-muted small mb-1"><i class="fas fa-info-circle me-1"></i> Deskripsi Dataset:</h6>
                                    <p class="text-secondary mb-0" style="line-height: 1.6;">
                                        {{ $dataset['description'] ?? ($dataset['notes'] ?? ($getmeta->standar ? $getmeta->standar->definisi : 'Tidak ada deskripsi tambahan.')) }}
                                    </p>
                                </div>

                                <!-- Action Buttons: Direct Downloads in Detail -->
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 border rounded-3 bg-white">
                                    <div>
                                        <span class="fw-semibold text-dark small d-block mb-1"><i class="fas fa-file-download text-primary me-1"></i> Unduh Dataset Lengkap:</span>
                                        <span class="text-muted small">Pilih format data yang Anda butuhkan (CSV, Excel XLSX, atau JSON).</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('dataset.download.format', ['id' => $getmeta ? $getmeta->id : ($dataset['id'] ?? 1), 'format' => 'csv']) }}" class="btn btn-sm btn-outline-info fw-semibold rounded-pill px-3 py-2">
                                            <i class="fas fa-file-csv me-1"></i> Unduh CSV
                                        </a>
                                        <a href="{{ route('dataset.download.format', ['id' => $getmeta ? $getmeta->id : ($dataset['id'] ?? 1), 'format' => 'xlsx']) }}" class="btn btn-sm btn-outline-success fw-semibold rounded-pill px-3 py-2">
                                            <i class="fas fa-file-excel me-1"></i> Unduh XLSX
                                        </a>
                                        <a href="{{ route('dataset.download.format', ['id' => $getmeta ? $getmeta->id : ($dataset['id'] ?? 1), 'format' => 'json']) }}" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill px-3 py-2" target="_blank">
                                            <i class="fas fa-code me-1"></i> Akses JSON API
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs Nav -->
                        <ul class="nav nav-tabs nav-fill bg-white p-2 rounded-3 shadow-sm mb-4" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-semibold" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">
                                    <i class="fas fa-info-circle me-1"></i> Informasi Data
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" id="standar-tab" data-bs-toggle="tab" data-bs-target="#standar" type="button" role="tab" aria-controls="standar" aria-selected="false">
                                    <i class="fas fa-certificate me-1"></i> Standar Data
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" id="meta-tab" data-bs-toggle="tab" data-bs-target="#meta" type="button" role="tab" aria-controls="meta" aria-selected="false">
                                    <i class="fas fa-tags me-1"></i> Metadata
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" id="tabel-tab" data-bs-toggle="tab" data-bs-target="#tabel" type="button" role="tab" aria-controls="tabel" aria-selected="false">
                                    <i class="fas fa-table me-1"></i> Tabel Data
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" id="grafik-tab" data-bs-toggle="tab" data-bs-target="#grafik" type="button" role="tab" aria-controls="grafik" aria-selected="false">
                                    <i class="fas fa-chart-line me-1"></i> Grafik Data
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content pt-2" id="myTabContent">
                            <div class="tab-pane fade show active" id="info" role="tabpanel"
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
                            <div class="tab-pane fade" id="standar" role="tabpanel" aria-labelledby="standar-tab">

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
                            <div class="tab-pane fade" id="meta" role="tabpanel" aria-labelledby="meta-tab">

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
                                                <div class="col-sm-6">
                                                    @if ($isImage)
                                                        <img class="img-fluid rounded" height="250px" width="250px"
                                                            src="{{ Storage::url(optional($getmeta->indikator)->metode) }}">
                                                    @else
                                                        <textarea name="metode" id="metode" class="form-control" disabled>{{ optional($getmeta->indikator)->metode }}</textarea>
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
                                                    <select
                                                        class="form-control form-select {{ isset($level_estimasi) ? ($level_estimasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        name="level_estimasi" id="level_estimasi" disabled>
                                                        <option value="nasional"
                                                            {{ old('level_estimasi', optional($getmeta->indikator)->level_estimasi) == 'nasional' ||
                                                            empty(old('level_estimasi', optional($getmeta->indikator)->level_estimasi))
                                                                ? 'selected'
                                                                : '' }}>
                                                            Nasional</option>
                                                        <option value="provinsi"
                                                            {{ old('level_estimasi', optional($getmeta->indikator)->level_estimasi) == 'provinsi' ? 'selected' : '' }}>
                                                            Provinsi</option>
                                                        <option value="kabupaten"
                                                            {{ old('level_estimasi', optional($getmeta->indikator)->level_estimasi) == 'kabupaten' ? 'selected' : '' }}>
                                                            Kabupaten/kota</option>
                                                        <option value="perangkat_daerah"
                                                            {{ old('level_estimasi', optional($getmeta->indikator)->level_estimasi) == 'perangkat_daerah' ? 'selected' : '' }}>
                                                            Perangkat Daerah</option>
                                                        <option value="kecamatan"
                                                            {{ old('level_estimasi', optional($getmeta->indikator)->level_estimasi) == 'kecamatan' ? 'selected' : '' }}>
                                                            Kecamatan</option>
                                                        <option value="kelurahan"
                                                            {{ old('level_estimasi', optional($getmeta->indikator)->level_estimasi) == 'kelurahan' ? 'selected' : '' }}>
                                                            Desa/Kelurahan</option>
                                                        <option value="rt"
                                                            {{ old('level_estimasi', optional($getmeta->indikator)->level_estimasi) == 'rt' ? 'selected' : '' }}>
                                                            Rumah Tangga</option>
                                                        <option value="individu"
                                                            {{ old('level_estimasi', optional($getmeta->indikator)->level_estimasi) == 'individu' ? 'selected' : '' }}>
                                                            Individu</option>
                                                    </select>
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
                                                        style="min-height: 100px; max-height: 250px; overflow-y: auto;" spellcheck="false" placeholder="Definisi" readonly>{{ old('definisi', optional($getmeta->variabel)->definisi ?? optional($getmeta->standar)->definisi) }}</textarea>
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
                                                    <select
                                                        class="form-select form-control {{ isset($tipe_data) ? ($tipe_data->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        style="width: 100%; border-radius: 8px;"
                                                        name="tipe_data" id="tipe_data" disabled>
                                                        <option value="integer"
                                                            {{ old('tipe_data', optional($getmeta->variabel)->tipe_data) == 'integer' ||
                                                            empty(optional($getmeta->variabel)->tipe_data)
                                                                ? 'selected'
                                                                : '' }}>Integer</option>
                                                        <option value="float"
                                                            {{ old('tipe_data', optional($getmeta->variabel)->tipe_data) == 'float' ? 'selected' : '' }}>
                                                            Float</option>
                                                        <option value="char"
                                                            {{ old('tipe_data', optional($getmeta->variabel)->tipe_data) == 'char' ? 'selected' : '' }}>
                                                            Char</option>
                                                        <option value="string"
                                                            {{ old('tipe_data', optional($getmeta->variabel)->tipe_data) == 'string' ? 'selected' : '' }}>
                                                            String</option>
                                                        <option value="array"
                                                            {{ old('tipe_data', optional($getmeta->variabel)->tipe_data) == 'array' ? 'selected' : '' }}>
                                                            Array</option>
                                                    </select>
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
                            <div class="tab-pane fade" id="tabel" role="tabpanel" aria-labelledby="tabel-tab">
                                @if ($tables)
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-table text-primary me-2"></i>Visualisasi Tabel Data</h5>
                                                <div class="d-flex gap-2 align-items-center mt-2 mt-md-0">
                                                    <a href="{{ route('api.v1.datasets.detail.web', $getmeta ? $getmeta->id : ($dataset['id'] ?? 1)) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-code me-1"></i> Endpoint API JSON
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <input type="text" id="liveTableFilterInput" class="form-control" placeholder="Filter / cari baris pada tabel..." onkeyup="filterDatasetTable()">
                                                </div>
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
                                                            <table class="table table-striped">
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
                                @endif
                            </div>
                            <div class="tab-pane fade" id="grafik" role="tabpanel" aria-labelledby="grafik-tab">
                                @if ($tables)
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            @if ($existingData)
                                                <div class="col-md-9">
                                                    @foreach ($tables as $table)
                                                        <div class="card" style="margin-bottom: 5%; margin-top: 5%">
                                                            <div class="card-header">Grafik Visualisasi
                                                                {{ $table['table']['namatabel'] }}</div>
                                                            {{-- <div>
                                                <label for="chartTypeSelect">Pilih Jenis Grafik:</label>
                                                <select id="chartTypeSelect">
                                                    <option value="bar">Bar Chart</option>
                                                    <option value="line">Line Chart</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-12">
                                                <div class="form-group">
                                                    <label for="dropdown_chartTypeSelect">Axis X</label>
                                                    <select name="chartTypeSelect" id="dropdown_chartTypeSelect"
                                                        class="form-control select-axis-x"
                                                        aria-label="Default select example">
                                                        <option value="0">-- Data Tunggal --</option>
                                                    </select>
                                                </div>
                                            </div> --}}
                                                            <div class="card-body">
                                                                <!-- Column Chart -->


                                                                <div id="chartContainer">
                                                                    <div id="chart"></div>
                                                                </div>
                                                                {{-- <div id="columnChart{{ $loop->index }}">
                                                </div> --}}
                                                                <!-- End Column Chart -->
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div class="col-md-3 col-sm-12 col-12">
                                                    <div class="contact-form-text-area">
                                                        <div class="card" style="margin-bottom: 10px">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Tipe Grafik</h5>
                                                                <div class="col-md-12 col-sm-12 col-12">
                                                                    <form action="">
                                                                        <div class="form-group">
                                                                            <select name="chartTypeSelect"
                                                                                id="chartTypeSelect"
                                                                                class="form-control select-axis-x"
                                                                                aria-label="Default select example">
                                                                                <option value="bar">Bar Chart</option>
                                                                                <option value="line">Line Chart</option>
                                                                            </select>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @foreach ($tables as $k => $table)
                                                            <div class="card" style="margin-bottom: 27%">
                                                                <div class="card-body">
                                                                    <h5 class="card-title">Column Chart</h5>

                                                                    <form id="grafikForm{{ $k }}"
                                                                        action="{{ route('dataset.chart.storeDataByFilter') }}"
                                                                        method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <div class="row">
                                                                            <input type="text" name="id_data"
                                                                                id="id_data"
                                                                                value="{{ $table['table']['id_data'] }}"
                                                                                hidden>
                                                                            <input type="text" name="id_table"
                                                                                id="id_table"
                                                                                value="{{ $table['table']['id'] }}"
                                                                                hidden>
                                                                            <div class="col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label for="dropdown_axis_x">Axis
                                                                                        X</label>
                                                                                    <select name="axis_x"
                                                                                        id="dropdown_axis_x"
                                                                                        class="form-control select-axis-x"
                                                                                        aria-label="Default select example">
                                                                                        <option value="0">-- Data
                                                                                            Tunggal --</option>
                                                                                        @foreach ($table['headers'] as $header)
                                                                                            <option
                                                                                                value="{{ $header->id }}"
                                                                                                @if ($existingData->isNotEmpty() && isset($existingData[$k]) && $existingData[$k]->axis_x == $header->id) selected @endif>
                                                                                                {{ $header->header }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <div class="help-block with-errors">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label for="dropdown_axis_y">Axis
                                                                                        Y</label>
                                                                                    <select name="axis_y"
                                                                                        id="dropdown_axis_y"
                                                                                        class="form-control select-axis-y"
                                                                                        aria-label="Default select example">
                                                                                        @foreach ($table['headers'] as $header)
                                                                                            <option
                                                                                                value="{{ $header->id }}"
                                                                                                @if ($existingData->isNotEmpty() && isset($existingData[$k]) && $existingData[$k]->axis_y == $header->id) selected @endif>
                                                                                                {{ $header->header }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <div class="help-block with-errors">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="dropdown_category">Kategori</label>
                                                                                    <select name="kategori"
                                                                                        id="dropdown_category"
                                                                                        class="form-control select-category"
                                                                                        aria-label="Default select example">
                                                                                        @foreach ($table['headers'] as $header)
                                                                                            @if ($header->header == 'Tahun')
                                                                                                <option
                                                                                                    value="{{ $header->id }}"
                                                                                                    @if ($existingData->isNotEmpty() && isset($existingData[$k]) && $existingData[$k]->kategori == $header->id) selected @endif>
                                                                                                    {{ $header->header }}
                                                                                                </option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <div class="help-block with-errors">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-sm-12 col-12">
                                                                                <button class="default-button"
                                                                                    id="btn-submit{{ $k }}"
                                                                                    type="submit"><span>Tampilkan</span></button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- Default Tabs -->


                    </div>

                </div>

            </div>
        </div>
    </section>

    @push('js')
        @foreach ($existingData as $item)
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const chartTypeSelect = document.getElementById('chartTypeSelect');

                    setTimeout(function() {
                        createChart('bar');
                    }, 2000);

                    $('#chartTypeSelect').on('change', function() {
                        const selectedType = $(this).val();
                        createChart(selectedType);

                    });
                    const chartElement = document.querySelector("#chart");

                    const names = {!! $kategori !!};
                    const data = {!! $axis_y !!};
                    const name_y = "{!! $item->axis_y_header !!}";
                    const categories = {!! $axis_x !!};
                    const seriesData{{ $item->id }} = @json($seriesData[$item->id] ?? []);
                    const seriesDataLine{{ $item->id }} = @json($seriesDataLine[$item->id] ?? []);

                    let myChart = null;

                    function createChart(type) {
                        // console.log(type);

                        // Destroy the old chart if it exists
                        if (myChart) {
                            myChart.destroy();
                        }

                        let chartOptions = {};

                        if (type === 'bar') {
                            if (categories.length === 0) {
                                // console.log('dududu');
                                chartOptions = {
                                    series: [{
                                        data: data
                                    }],
                                    chart: {
                                        height: 350,
                                        type: 'bar',
                                        events: {
                                            click: function(chart, w, e) {
                                                // console.log(chart, w, e)
                                            }
                                        }
                                    },
                                    plotOptions: {
                                        bar: {
                                            columnWidth: '45%',
                                            distributed: true,
                                        }
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    legend: {
                                        show: false
                                    },
                                    xaxis: {
                                        categories: names,
                                        labels: {
                                            style: {
                                                fontSize: '12px'
                                            }
                                        }
                                    }
                                };
                            } else {
                                // console.log('lalala');

                                chartOptions = {
                                    series: seriesData{{ $item->id }},
                                    chart: {
                                        type: 'bar',
                                        height: 350
                                    },
                                    plotOptions: {
                                        bar: {
                                            horizontal: false,
                                            columnWidth: '55%',
                                            endingShape: 'rounded'
                                        }
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    stroke: {
                                        show: true,
                                        width: 2,
                                        colors: ['transparent']
                                    },
                                    xaxis: {
                                        categories: categories,
                                    },
                                    yaxis: {
                                        title: {
                                            text: name_y
                                        }
                                    },
                                    fill: {
                                        opacity: 1
                                    },
                                    tooltip: {
                                        y: {
                                            formatter: function(val) {
                                                return "Y (Nilai) " + val
                                            }
                                        }
                                    }
                                };
                            }
                        } else if (type === 'line') {
                            // console.log('pppp');

                            chartOptions = {
                                series: seriesDataLine{{ $item->id }},
                                chart: {
                                    height: 350,
                                    type: 'line',
                                    zoom: {
                                        enabled: false
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    curve: 'smooth'
                                },
                                title: {
                                    text: 'Line Chart',
                                    align: 'left'
                                },
                                grid: {
                                    row: {
                                        colors: ['#f3f3f3', 'transparent'], // alternating rows
                                        opacity: 0.5
                                    }
                                },
                                xaxis: {
                                    categories: names,
                                },
                                yaxis: {
                                    title: {
                                        text: name_y
                                    }
                                }
                            };
                        }

                        // Create a new chart instance
                        myChart = new ApexCharts(chartElement, chartOptions);
                        myChart.render();
                    }
                    // console.log('ssss');


                    // // Initial chart creation
                    // createChart(chartTypeSelect.value);

                    // // Event listener for chart type change
                    // chartTypeSelect.addEventListener('change', function() {

                    //     createChart(this.value); // Create and render a new chart based on the selected type
                    // });
                });
            </script>
        @endforeach
    @endpush

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check if 'tab' parameter is present in the URL
            const activeTab = '{{ session('active_tab') }}';
            // console.log(activeTab);
            if (activeTab === 'grafik') {
                // Activate the 'grafik' tab
                document.querySelector('#grafik-tab').click();
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Tangkap klik tombol unduh
            $('.download-btn').click(function() {
                // console.log('bisaa');
                // Ambil URL unduhan dari data-url-download yang disimpan pada atribut data
                var urlDownload = $(this).data('url-download');

                // Lakukan request ke route untuk menghitung jumlah unduhan
                $.ajax({
                    type: 'GET',
                    url: '/download-file-count', // Ganti dengan route yang sesuai
                    data: {
                        url_download: urlDownload
                    },
                    success: function(response) {
                        // Response dari server, response dapat berupa apa saja
                        console.log('Jumlah unduhan bertambah: ' + response);
                    },
                    error: function(xhr, status, error) {
                        // Tangani error jika diperlukan
                        console.error(error);
                    }
                });
            });
        });
    </script>



<script>
function filterDatasetTable() {
    var input, filter, tables, tr, td, i, j, txtValue, found;
    input = document.getElementById("liveTableFilterInput");
    filter = input.value.toUpperCase();
    tables = document.querySelectorAll(".table-responsive table");
    
    tables.forEach(function(table) {
        tr = table.getElementsByTagName("tr");
        for (i = 1; i < tr.length; i++) {
            found = false;
            td = tr[i].getElementsByTagName("td");
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            tr[i].style.display = found ? "" : "none";
        }
    });
}
</script>
@endsection

@push('js')
@endpush
