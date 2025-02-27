@extends('guest.layout')

@section('content')

    <section class="uni-banner">
        <div class="container">
            <div class="uni-banner-text-area">
                <h1>Detail Dataset</h1>
                <ul>
                    <li><a href="{{ '/' }}">Beranda</a></li>
                    <li><a href="{{ '/dataset' }}">Dataset</a></li>
                    <li>Detail Dataset</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="blog-details ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-details-text-area details-text-area">
                        {{-- content --}}
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="card">
                                        {{-- @php
                                    dd($dataset['resources']);
                                    @endphp --}}
                                        <div class="card-header">Detail Dataset</div>

                                        <div class="card-body">
                                            <div class="table-responsive table-berkas">
                                                <h5>{{ $dataset['title'] }}</h5>
                                                <table class="table table-stripped">
                                                    <tbody>
                                                        <tr>
                                                            <td><span class="font-weight-bold">Judul</span></td>
                                                            <td>: {{ $dataset['title'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><span class="font-weight-bold">Deskripsi</span></td>
                                                            <td>: {{ $dataset['description'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><span class="font-weight-bold">Organisasi / OPD</span></td>
                                                            <td>: @if (!empty($dataset['organization']))
                                                                    <a
                                                                        href="{{ $dataset['organization']['link'] }}">{{ $dataset['organization']['title'] }}</a>
                                                                @else
                                                                    Tidak ada informasi organisasi
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span class="font-weight-bold">Dipublikasi</span></td>
                                                            <td>: {{ $dataset['created'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><span class="font-weight-bold">Terakhir dimodifikasi:</span>
                                                            </td>
                                                            <td>: {{ $dataset['modified'] }}</td>
                                                        </tr>
                                                    </tbody>

                                                </table>
                                            </div>

                                            <div class="table-responsive table-berkas">
                                                <h5>Daftar Berkas</h5>
                                                <table class="table table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama</th>
                                                            <th>Deskripsi</th>
                                                            <th>Tgl. Diunggah</th>
                                                            <th>Format</th>
                                                            <th>Unduh | Pratinjau</th>
                                                            <th>Jumlah Unduh Dokumen</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- @php
                                                    dd($dataset['resources']);
                                                    @endphp --}}
                                                        @foreach ($dataset['resources'] as $resource)
                                                            <tr>
                                                                <td>{{ $resource['name'] }}</td>
                                                                <td>{{ $resource['description'] ?? '-' }}</td>
                                                                <td>{{ $resource['created'] }}</td>
                                                                <td>{{ $resource['format'] }}</td>
                                                                <td>
                                                                    {{-- @php
                                                            dd($resource);
                                                            @endphp --}}
                                                                    {{-- <a href="{{ $resource['url_download'] }}"
                                                                target="_blank">
                                                                <i style="color: green" class="fas fa-download"></i>
                                                                Unduh</a> --}}
                                                                    <a href="{{ $resource['url_download'] }}"
                                                                        data-url-download="{{ $resource['url_download'] }}"
                                                                        class="download-btn" target="_blank">
                                                                        <i style="color: green" class="fas fa-download"></i>
                                                                        Unduh
                                                                    </a>
                                                                    <br>
                                                                    <a href="{{ $resource['url_preview'] }}"
                                                                        target="_blank"> <i style="color: blue"
                                                                            class="fas fa-eye"></i>
                                                                        Pratinjau</a>
                                                                </td>
                                                                <td>
                                                                    <div style="align-item:center">
                                                                        <i style="color: black" class="fas fa-download"></i>
                                                                        {{ $resource['download_count'] }} Kali
                                                                    </div>
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
                        <br>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                                    type="button" role="tab" aria-controls="info" aria-selected="true">Informasi
                                    Data</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="standar-tab" data-bs-toggle="tab" data-bs-target="#standar"
                                    type="button" role="tab" aria-controls="standar" aria-selected="false">Standar
                                    Data</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="meta-tab" data-bs-toggle="tab" data-bs-target="#meta"
                                    type="button" role="tab" aria-controls="meta"
                                    aria-selected="false">Metadata</button>
                            </li>
                            {{-- @if ($tables) --}}
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tabel-tab" data-bs-toggle="tab" data-bs-target="#tabel"
                                    type="button" role="tab" aria-controls="tabel" aria-selected="false">Tabel
                                    Data</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="grafik-tab" data-bs-toggle="tab" data-bs-target="#grafik"
                                    type="button" role="tab" aria-controls="grafik" aria-selected="false">Grafik
                                    Data</button>
                            </li>
                            {{-- @endif --}}
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
                                                    <input id="definisi" name="definisi" type="text"
                                                        class="form-control {{ isset($definisi) ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        placeholder="Definisi"
                                                        value="{{ old('definisi', optional($getmeta->variabel)->definisi ?? optional($getmeta->standar)->definisi) }}"
                                                        readonly>

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
                                                        class="form-control {{ isset($tipe_data) ? ($tipe_data->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                        name="tipe_data" id="tipe_data" disabled>
                                                        <option value="integer"
                                                            {{ old('tipe_data', optional($getmeta->variabel)->tipe_data) == 'integer' ||
                                                            empty(optional($getmeta->variabel)->tipe_data)
                                                                ? 'selected'
                                                                : '' }}
                                                            disabled>Integer</option>
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
                                            <h5 class="card-title"> Visualisasi Data </h5>

                                            <div class="table-responsive">

                                                <div class="table-responsive">
                                                    @foreach ($tables as $tableData)
                                                        @php
                                                            $table = $tableData['table'];
                                                            $headers = $tableData['headers'];
                                                            $rows = $tableData['rows'];
                                                        @endphp

                                                        <h2>{{ $table->namatabel }}</h2>

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


@endsection

@push('js')
@endpush
