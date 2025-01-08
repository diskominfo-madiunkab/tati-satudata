@extends('pages.main.layout')

@section('title', 'Metadata Kegiatan')

@section('content')

    <div class="pagetitle">
        <h1>Metadata Kegiatan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Daftar Data</li>
                <li class="breadcrumb-item">Data - {{$data->nama_data}}</li>
                <li class="breadcrumb-item">Metadata Kegiatan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Metadata Kegiatan</h5>
                        <form method="POST" action="{{route('simpan-kegiatan', $data->id)}}">
                            @csrf
                            <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-kegiatan" type="button" role="tab"
                                            aria-controls="home" aria-selected="true">Informasi Kegiatan
                                    </button>
                                </li>

                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-penyelenggara" type="button" role="tab"
                                            aria-controls="profile" aria-selected="false">Penyelenggara
                                    </button>
                                </li>

                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-penanggungjawab" type="button" role="tab"
                                            aria-controls="contact" aria-selected="false">Penanggung Jawab
                                    </button>
                                </li>

                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-perenacanaan" type="button" role="tab"
                                            aria-controls="contact" aria-selected="false">Perencanaan & Persiapan
                                    </button>
                                </li>

                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-desain-kegiatan" type="button" role="tab"
                                            aria-controls="contact" aria-selected="false">Desain Kegiatan
                                    </button>
                                </li>

                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-desain-sampel" type="button" role="tab"
                                            aria-controls="contact" aria-selected="false">Desain Sampel
                                    </button>
                                </li>

                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-pengumpulan-data" type="button" role="tab"
                                            aria-controls="contact" aria-selected="false">Pengumpulan Data
                                    </button>
                                </li>

                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-pengolahan-analisis" type="button" role="tab"
                                            aria-controls="contact" aria-selected="false">Pengolahan & Analisis
                                    </button>
                                </li>

                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-diseminasi-hasil" type="button" role="tab"
                                            aria-controls="contact" aria-selected="false">Diseminasi Hasil
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                                <div class="tab-pane fade active show" id="tab-kegiatan" role="tabpanel"
                                     aria-labelledby="home-tab">
                                    <div class="row mb-3">
                                        <label for="judul_kegiatan" class="col-sm-2 col-form-label">Judul Kegiatan</label>
                                        <div class="col-sm-10">
                                            <input id="judul_kegiatan" name="judul_kegiatan" type="text" class="form-control" placeholder="Judul Kegiatan" value="{{old('judul_kegiatan', optional($data->kegiatan)->judul_kegiatan)}}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="tahun_kegiatan" class="col-sm-2 col-form-label">Tahun Kegiatan</label>
                                        <div class="col-sm-10">
                                            <input id="tahun_kegiatan" name="tahun_kegiatan" type="number" min="1900" max="2999" class="form-control" placeholder="Tahun Kegiatan" value="{{old('tahun_kegiatan', optional($data->kegiatan)->tahun_kegiatan)}}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="kode_kegiatan" class="col-sm-2 col-form-label">Kode Kegiatan</label>
                                        <div class="col-sm-10">
                                            <input id="kode_kegiatan" name="kode_kegiatan" type="text" class="form-control" placeholder="Kode Kegiatan" value="{{old('kode_kegiatan', optional($data->kegiatan)->kode_kegiatan)}}">
                                            <small class="help-block text-muted text-sm">Diisi oleh petugas BPS</small>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="pengumpulan_data" class="col-sm-2 col-form-label">Cara Pengumpulan Data</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="pengumpulan_data" id="pengumpulan_data">
                                                <option value="-1" {{empty(old('pengumpulan_data', optional($data->kegiatan)->pengumpulan_data)) ? 'selected' : ''}}>-- Pilih Cara Pengumpulan Data --</option>
                                                <option value="1" {{old('pengumpulan_data', optional($data->kegiatan)->pengumpulan_data) == 1 ? 'selected' : ''}}>Pencacahan Lengkap</option>
                                                <option value="2" {{old('pengumpulan_data', optional($data->kegiatan)->pengumpulan_data) == 2 ? 'selected' : ''}}>Survei</option>
                                                <option value="3" {{old('pengumpulan_data', optional($data->kegiatan)->pengumpulan_data) == 3 ? 'selected' : ''}}>Kompilasi Produk Administrasi</option>
                                                <option value="4" {{old('pengumpulan_data', optional($data->kegiatan)->pengumpulan_data) == 4 ? 'selected' : ''}}>Cara lain sesuai dengan perkembangan IT</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="sektor_kegiatan" class="col-sm-2 col-form-label">Sektor Kegiatan</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="sektor_kegiatan" id="sektor_kegiatan">
                                                <option value="-1" {{empty(old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan)) ? 'selected' : ''}}>-- Pilih Sektor Kegiatan --</option>
                                                <option value="1" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 1 ? 'selected' : ''}}>Pertanian dan Perikanan</option>
                                                <option value="2" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 2 ? 'selected' : ''}}>Demografi dan Kependudukan</option>
                                                <option value="3" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 3 ? 'selected' : ''}}>Pembangunan</option>
                                                <option value="4" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 4 ? 'selected' : ''}}>Proyeksi Ekonomi</option>
                                                <option value="5" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 5 ? 'selected' : ''}}>Pendidikan dan Pelatihan</option>
                                                <option value="6" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 6 ? 'selected' : ''}}>Lingkungan</option>
                                                <option value="7" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 7 ? 'selected' : ''}}>Keuangan</option>
                                                <option value="8" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 8 ? 'selected' : ''}}>Globalisasi</option>
                                                <option value="9" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 9 ? 'selected' : ''}}>Kesehatan</option>
                                                <option value="10" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 10 ? 'selected' : ''}}>Industri dan Jasa</option>
                                                <option value="11" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 11 ? 'selected' : ''}}>Teknologi Informasi dan Komunikasi</option>
                                                <option value="12" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 12 ? 'selected' : ''}}>Perdagangan Internasional dan Neraca Perdagangan</option>
                                                <option value="13" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 13 ? 'selected' : ''}}>Ketenagakerjaan</option>
                                                <option value="14" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 14 ? 'selected' : ''}}>Neraca Nasional</option>
                                                <option value="15" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 15 ? 'selected' : ''}}>Indikator Ekonomi Bulanan</option>
                                                <option value="16" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 16 ? 'selected' : ''}}>Produktivitas</option>
                                                <option value="17" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 17 ? 'selected' : ''}}>Harga dan Paritas Daya Beli</option>
                                                <option value="18" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 18 ? 'selected' : ''}}>Sektor Publik, Perpajakan, dan Regulasi Pasar</option>
                                                <option value="19" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 19 ? 'selected' : ''}}>Perwilayahan dan Perkotaan</option>
                                                <option value="20" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 20 ? 'selected' : ''}}>Ilmu Pengetahuan dan Hak Paten</option>
                                                <option value="21" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 21 ? 'selected' : ''}}>Perlindungan Sosial dan Kesejahteraan</option>
                                                <option value="22" {{old('sektor_kegiatan', optional($data->kegiatan)->sektor_kegiatan) == 22 ? 'selected' : ''}}>Transportasi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="jenis_kegiatan" class="col-sm-2 col-form-label">Jenis Kegiatan Statistik</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="jenis_kegiatan" id="jenis_kegiatan">
                                                <option value="-1" {{empty(old('jenis_kegiatan', optional($data->kegiatan)->jenis_kegiatan)) ? 'selected' : ''}}>-- Pilih Jenis Kegiatan Statistik --</option>
                                                <option value="1" {{old('jenis_kegiatan', optional($data->kegiatan)->jenis_kegiatan) == 1 ? 'selected' : ''}}>Statistik Dasar</option>
                                                <option value="2" {{old('jenis_kegiatan', optional($data->kegiatan)->jenis_kegiatan) == 2 ? 'selected' : ''}}>Statistik Sektoral</option>
                                                <option value="3" {{old('jenis_kegiatan', optional($data->kegiatan)->jenis_kegiatan) == 3 ? 'selected' : ''}}>Statistik Khusus</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="level_estimasi" class="col-sm-2 col-form-label">Jika kegiatan statistik sektoral, apakah mendapatkan rekomendasi kegiatan statistik dari BPS?</label>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="level_estimasi" id="level_estimasi1"
                                                       value="1" {{old('level_estimasi', optional($data->kegiatan)->level_estimasi) == 1 ? 'checked' : ''}}>
                                                <label class="form-check-label" for="level_estimasi1">
                                                    Ya
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="level_estimasi" id="level_estimasi0" value="0" {{empty(old('level_estimasi', optional($data->kegiatan)->level_estimasi)) || old('level_estimasi', optional($data->kegiatan)->level_estimasi) == 2 ? 'checked' : ''}}>
                                                <label class="form-check-label" for="level_estimasi0">
                                                    Tidak
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-10">
                                            <button class="btn btn-outline-primary"><i class="bi bi-save"></i> Simpan</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Penyelenggara -->
                                <div class="tab-pane fade" id="tab-penyelenggara" role="tabpanel"
                                     aria-labelledby="profile-tab">
                                    <div class="row mb-3">
                                        <label for="instansi_penyelenggara" class="col-sm-2 col-form-label">Instansi Penyelenggara</label>
                                        <div class="col-sm-10">
                                            <input id="instansi_penyelenggara" name="instansi_penyelenggara" type="text" class="form-control" placeholder="Instansi Penyelenggara" value="{{old('instansi_penyelenggara', optional($data->kegiatan)->instansi_penyelenggara)}}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="alamant_penyelenggara" class="col-sm-2 col-form-label">Alamat Lengkap Instansi Penyelenggara</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="alamat_penyelenggara" id="alamat_penyelenggara" placeholder="Alamat Instansi Penyelenggara">{{old('alamat_penyelenggara', optional($data->kegiatan)->alamat_penyelenggara)}}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="telepon_penyelenggara" class="col-sm-2 col-form-label">Telepon Penyelenggara</label>
                                        <div class="col-sm-10">
                                            <input id="telepon_penyelenggara" name="telepon_penyelenggara" type="text" class="form-control" placeholder="Nomor Telepon Penyelenggara" value="{{old('telepon_penyelenggara', optional($data->kegiatan)->telepon_penyelenggara)}}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="email_penyelenggara" class="col-sm-2 col-form-label">Email Penyelenggara</label>
                                        <div class="col-sm-10">
                                            <input id="email_penyelenggara" name="email_penyelenggara" type="email" class="form-control" placeholder="Email Penyelenggara" value="{{old('email_penyelenggara', optional($data->kegiatan)->email_penyelenggara)}}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="faksimile_penyelenggara" class="col-sm-2 col-form-label">Faksimile Penyelenggara</label>
                                        <div class="col-sm-10">
                                            <input id="faksimile_penyelenggara" name="faksimile_penyelenggara" type="text" class="form-control" placeholder="Faksimile Penyelenggara" value="{{old('faksimile_penyelenggara', optional($data->kegiatan)->faksimile_penyelenggara)}}">
                                        </div>
                                    </div>

                                    @if(auth()->user()->hasAnyRole('produsen'))
                                        <div class="row mb-3">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                                <button class="btn btn-outline-primary"><i class="bi bi-save"></i> Simpan</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Penanggung Jawab -->
                                <div class="tab-pane fade" id="tab-penanggungjawab" role="tabpanel"
                                     aria-labelledby="contact-tab">
                                    <h5 class="card-title">Unit Eselon Penanggung Jawab</h5>
                                    <div class="row mb-3">
                                        <label for="eselon_1" class="col-sm-2 col-form-label">Eselon 1</label>
                                        <div class="col-sm-10">
                                            <input id="eselon_1" name="eselon_1" type="text" class="form-control" placeholder="Eselon 1" value="{{old('eselon_1', optional($data->kegiatan)->eselon_1)}}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="eselon_2" class="col-sm-2 col-form-label">Eselon 2</label>
                                        <div class="col-sm-10">
                                            <input id="eselon_2" name="eselon_2" type="text" class="form-control" placeholder="Eselon 2" value="{{old('eselon_2', optional($data->kegiatan)->eselon_2)}}">
                                        </div>
                                    </div>

                                    <h5 class="card-title mt-3">Penanggung Jawab Teknis (setingkat Eselon 3)</h5>
                                    <div class="row mb-3">
                                        <label for="alamat_penanggungjawab" class="col-sm-2 col-form-label">Alamat Instansi Penanggung Jawab</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="alamat_penanggungjawab" id="alamat_penanggungjawab" placeholder="Alamat Instansi Penanggung Jawab">{{old('alamat_penanggungjawab', optional($data->kegiatan)->alamat_penanggungjawab)}}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="telepon_penanggungjawab" class="col-sm-2 col-form-label">Telepon Penanggung Jawab</label>
                                        <div class="col-sm-10">
                                            <input id="telepon_penanggungjawab" name="telepon_penanggungjawab" type="text" class="form-control" placeholder="Nomor Telepon Penanggung Jawab" value="{{old('telepon_penanggungjawab', optional($data->kegiatan)->telepon_penanggungjawab)}}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="email_penanggungjawab" class="col-sm-2 col-form-label">Email Penanggung Jawab</label>
                                        <div class="col-sm-10">
                                            <input id="email_penanggungjawab" name="email_penanggungjawab" type="email" class="form-control" placeholder="Email Penanggung Jawab" value="{{old('email_penanggungjawab', optional($data->kegiatan)->email_penanggungjawab)}}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="faksimile_penanggungjawab" class="col-sm-2 col-form-label">Faksimile Penanggung Jawab</label>
                                        <div class="col-sm-10">
                                            <input id="faksimile_penanggungjawab" name="faksimile_penanggungjawab" type="text" class="form-control" placeholder="Faksimile Penanggung Jawab" value="{{old('faksimile_penanggungjawab', optional($data->kegiatan)->faksimile_penanggungjawab)}}">
                                        </div>
                                    </div>

                                    @if(auth()->user()->hasAnyRole('produsen'))
                                        <div class="row mb-3">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                                <button class="btn btn-outline-primary"><i class="bi bi-save"></i> Simpan</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Perencaana & Persiapan -->
                                <div class="tab-pane fade" id="tab-perenacanaan" role="tabpanel">
                                    <h5 class="card-title">Latar Belakang & Tujuan Kegiatan</h5>
                                    <div class="row mb-3">
                                        <label for="latar_belakang" class="col-sm-2 col-form-label">Latar Belakang Kegiatan</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="latar_belakang" id="latar_belakang" placeholder="Latar Belakang">{{old('latar_belakang', optional($data->kegiatan)->latar_belakang)}}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="tujuan_kegiatan" class="col-sm-2 col-form-label">Tujuan Kegiatan</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="tujuan_kegiatan" id="tujuan_kegiatan" placeholder="Tujuan Kegiatan">{{old('tujuan_kegiatan', optional($data->kegiatan)->tujuan_kegiatan)}}</textarea>
                                        </div>
                                    </div>

                                    <h5 class="card-title">Rencana Jadwal Kegiatan</h5>
                                    <ol type="A">
                                        <li>
                                            <ol type="1">
                                                <li>
                                                    <div class="row mb-3">
                                                        <label for="jadwal_perencanaan_kegiatan" class="col-sm-2 col-form-label">Perencanaan Kegiatan</label>
                                                        <div class="col-sm-10">
                                                            <input type="date" class="form-control" name="jadwal_perencanaan_kegiatan" id="jadwal_perencanaan_kegiatan" value="{{old('jadwal_perencanaan_kegiatan', optional($data->kegiatan)->jadwal_perencanaan_kegiatan)}}">
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row mb-3">
                                                        <label for="jadwal_perencanaan_desain" class="col-sm-2 col-form-label">Perencanaan Desain</label>
                                                        <div class="col-sm-10">
                                                            <input type="date" class="form-control" name="jadwal_perencanaan_desain" id="jadwal_perencanaan_desain" value="{{old('jadwal_perencanaan_desain', optional($data->kegiatan)->jadwal_perencanaan_desain)}}">
                                                        </div>
                                                    </div>
                                                </li>
                                            </ol>
                                        </li>
                                        <li>
                                            <ol type="1">
                                                <li>
                                                    <div class="row mb-3">
                                                        <label for="jadwal_pengumpulan_data" class="col-sm-2 col-form-label">Pengumpulan Data</label>
                                                        <div class="col-sm-10">
                                                            <input type="date" class="form-control" name="jadwal_pengumpulan_data" id="jadwal_pengumpulan_data" value="{{old('jadwal_pengumpulan_data', optional($data->kegiatan)->jadwal_pengumpulan_data)}}">
                                                        </div>
                                                    </div>
                                                </li>
                                            </ol>
                                        </li>
                                        <li>
                                            <ol>
                                                <li>
                                                    <div class="row mb-3">
                                                        <label for="jadwal_pengolahan_data" class="col-sm-2 col-form-label">Pengolahan Data</label>
                                                        <div class="col-sm-10">
                                                            <input type="date" class="form-control" name="jadwal_pengolahan_data" id="jadwal_pengolahan_data" value="{{old('jadwal_pengolahan_data', optional($data->kegiatan)->jadwal_pengolahan_data)}}">
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row mb-3">
                                                        <label for="jadwal_analasis_data" class="col-sm-2 col-form-label">Analisis Data</label>
                                                        <div class="col-sm-10">
                                                            <input type="date" class="form-control" name="jadwal_analasis_data" id="jadwal_analasis_data" value="{{old('jadwal_analasis_data', optional($data->kegiatan)->jadwal_analasis_data)}}">
                                                        </div>
                                                    </div>
                                                </li>
                                            </ol>
                                        </li>
                                        <li>
                                            <ol>
                                                <li>
                                                    <div class="row mb-3">
                                                        <label for="jadwal_diseminasi_hasil" class="col-sm-2 col-form-label">Diseminasi Hasil</label>
                                                        <div class="col-sm-10">
                                                            <input type="date" class="form-control" name="jadwal_diseminasi_hasil" id="jadwal_diseminasi_hasil" value="{{old('jadwal_diseminasi_hasil', optional($data->kegiatan)->jadwal_diseminasi_hasil)}}">
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row mb-3">
                                                        <label for="jadwal_evaluasi" class="col-sm-2 col-form-label">Evaluasi</label>
                                                        <div class="col-sm-10">
                                                            <input type="date" class="form-control" name="jadwal_evaluasi" id="jadwal_evaluasi" value="{{old('jadwal_evaluasi', optional($data->kegiatan)->jadwal_evaluasi)}}">
                                                        </div>
                                                    </div>
                                                </li>
                                            </ol>
                                        </li>
                                    </ol>

                                    @if(auth()->user()->hasAnyRole('produsen'))
                                        <div class="row mb-3">
                                            <div class="col-sm-2 mx-auto">
                                                <button class="btn btn-outline-primary"><i class="bi bi-save"></i> Simpan</button>
                                            </div>
                                            <div class="col-auto">
                                            </div>
                                        </div>
                                    @endif

                                    <h5 class="card-title">
                                        Variabel (karakteristik) yang Dikumpulkan
                                        <span class="float-right">
                                            <a href="#" id="btnAddVariabel" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambahVariabel"><i class="bi bi-plus-circle"></i> Tambah</a>
                                        </span>
                                    </h5>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr class="text-center">
                                            <th>Nama Variabel</th>
                                            <th>Konsep</th>
                                            <th>Definisi</th>
                                            <th>Referensi Waktu (Periode Enumerasi)</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbodyVariabel">
                                            @if ($data->kegiatan && is_array($data->kegiatan->variabel_dikumpulkan) && !empty($data->kegiatan->variabel_dikumpulkan))
                                                @foreach($data->kegiatan->variabel_dikumpulkan as $var)
                                                    <tr>
                                                        <td>{{$var['nama'] ?? '-'}}</td>
                                                        <td>{{$var['konsep'] ?? '-'}}</td>
                                                        <td>{{$var['definisi'] ?? '-'}}</td>
                                                        <td>{{$var['referensi_waktu'] ?? '-'}}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr id="variabelEmptyData">
                                                    <td colspan="4" class="text-center">No Data</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                </div>

                                <!-- Desain Kegiatan -->
                                <div class="tab-pane fade" id="tab-desain-kegiatan" role="tabpanel">
                                    <div class="row mb-3">
                                        <label for="kegiatan_dilakukan" class="col-sm-2 col-form-label">Kegiatan ini dilakukan</label>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="kegiatan_dilakukan" id="kegiatan_dilakukan_sekali"
                                                       value="1" {{old('kegiatan_dilakukan', optional($data->kegiatan)->kegiatan_dilakukan) == 1 || empty(old('kegiatan_dilakukan', optional($data->kegiatan)->kegiatan_dilakukan)) ? 'checked' : ''}}>
                                                <label class="form-check-label" for="kegiatan_dilakukan_sekali">
                                                    Hanya Sekali
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="kegiatan_dilakukan" id="kegiatan_dilakukan_berulang" value="2" {{old('kegiatan_dilakukan', optional($data->kegiatan)->kegiatan_dilakukan) == 2 ? 'checked' : ''}}>
                                                <label class="form-check-label" for="kegiatan_dilakukan_berulang">
                                                    Berulang
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="sectionBerulang">
                                        <label for="frekuensi_penyelenggaraan" class="col-sm-2 col-form-label">Frekuensi Penyelenggaraan</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="frekuensi_penyelenggaraan" name="frekuensi_penyelenggaraan">
                                                <option value="-1" {{empty(old('frekuensi_penyelenggaraan', optional($data->kegiatan)->frekuensi_penyelenggaraan)) ? 'selected' : ''}}>-- Pilih Frekuensi Penyelenggaraan --</option>
                                                <option value="1" {{old('frekuensi_penyelenggaraan', optional($data->kegiatan)->frekuensi_penyelenggaraan) == 1 ? 'selected' : ''}}>Harian</option>
                                                <option value="2" {{old('frekuensi_penyelenggaraan', optional($data->kegiatan)->frekuensi_penyelenggaraan) == 2 ? 'selected' : ''}}>Mingguan</option>
                                                <option value="3" {{old('frekuensi_penyelenggaraan', optional($data->kegiatan)->frekuensi_penyelenggaraan) == 3 ? 'selected' : ''}}>Bulanan</option>
                                                <option value="4" {{old('frekuensi_penyelenggaraan', optional($data->kegiatan)->frekuensi_penyelenggaraan) == 4 ? 'selected' : ''}}>Triwulanan</option>
                                                <option value="5" {{old('frekuensi_penyelenggaraan', optional($data->kegiatan)->frekuensi_penyelenggaraan) == 5 ? 'selected' : ''}}>Empat bulanan</option>
                                                <option value="6" {{old('frekuensi_penyelenggaraan', optional($data->kegiatan)->frekuensi_penyelenggaraan) == 6 ? 'selected' : ''}}>Semesteran</option>
                                                <option value="7" {{old('frekuensi_penyelenggaraan', optional($data->kegiatan)->frekuensi_penyelenggaraan) == 7 ? 'selected' : ''}}>Tahunan</option>
                                                <option value="8" {{old('frekuensi_penyelenggaraan', optional($data->kegiatan)->frekuensi_penyelenggaraan) == 8 ? 'selected' : ''}}>> Dua Tahunan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="sectionSekali">
                                        <label for="tipe_pengumpulan_data" class="col-sm-2 col-form-label">Tipe Pengumpulan Data</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="tipe_pengumpulan_data" name="tipe_pengumpulan_data">
                                                <option value="-1" {{empty(old('tipe_pengumpulan_data', optional($data->kegiatan)->tipe_pengumpulan_data)) ? 'selected' : ''}}>-- Pilih Tipe Pengumpulan Data --</option>
                                                <option value="1" {{old('tipe_pengumpulan_data', optional($data->kegiatan)->tipe_pengumpulan_data) == 1 ? 'selected' : ''}}>Longitudinal Panel</option>
                                                <option value="2" {{old('tipe_pengumpulan_data', optional($data->kegiatan)->tipe_pengumpulan_data) == 2 ? 'selected' : ''}}>Cross Sectional</option>
                                                <option value="3" {{old('tipe_pengumpulan_data', optional($data->kegiatan)->tipe_pengumpulan_data) == 3 ? 'selected' : ''}}>Longitudinal Cross Sectional</option>
                                            </select>
                                        </div>
                                    </div>

                                    <h5 class="card-title">Wilayah Kegiatan</h5>
                                    <div class="row mb-3">
                                        <label for="cakupan_wilayah_pengumpulan_data" class="col-sm-2 col-form-label">Cakupan Wilayah Pengumpulan Data</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="cakupan_wilayah_pengumpulan_data" name="cakupan_wilayah_pengumpulan_data">
                                                <option value="1" {{empty(old('cakupan_wilayah_pengumpulan_data', optional($data->kegiatan)->cakupan_wilayah_pengumpulan_data)) || old('cakupan_wilayah_pengumpulan_data', optional($data->kegiatan)->cakupan_wilayah_pengumpulan_data) == 1 ? 'selected' : ''}}>Seluruh Wilayah Indonesia</option>
                                                <option value="2" {{old('cakupan_wilayah_pengumpulan_data', optional($data->kegiatan)->cakupan_wilayah_pengumpulan_data) == 2 ? 'selected' : ''}}>Sebagian Wilayah Indonesia</option>
                                            </select>
                                        </div>
                                    </div>

                                    <section id="sectionSebagianWilayah">
                                        <div class="row mb-3">
                                            <label for="provinsi_kegiatan" class="col-sm-2 col-form-label">Provinsi</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="provinsi_kegiatan" name="provinsi_kegiatan">
                                                    <option value="-1" selected>-- Pilih Provinsi --</option>

                                                    @foreach($provinces as $code => $name)
                                                        <option value="{{$code}}" {{old('provinsi_kegiatan', optional($data->kegiatan)->provinsi_kegiatan) == $code ? 'selected' : ''}}>{{$name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="kota_kegiatan" class="col-sm-2 col-form-label">Kabupaten / Kota</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="kota_kegiatan" name="kota_kegiatan">
                                                    <option value="-1">-- Pilih Kota --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </section>

                                    <section id="sectionSeluruhWilayah">
                                        <div class="row mb-3">
                                            <label for="metode_pengumpulan_data" class="col-sm-2 col-form-label">Metode Pengumpulan Data</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="metode_pengumpulan_data" id="metode_pengumpulan_data">
                                                    <option value="-1" {{empty(old('metode_pengumpulan_data', optional($data->kegiatan)->metode_pengumpulan_data))}}>-- Pilih metode pengumpulan data --</option>
                                                    <option value="1" {{ old('metode_pengumpulan_data', optional($data->kegiatan)->metode_pengumpulan_data) == 1 ? 'selected' : ''}}>Wawancara</option>
                                                    <option value="2" {{ old('metode_pengumpulan_data', optional($data->kegiatan)->metode_pengumpulan_data) == 2 ? 'selected' : ''}}>Mengisi kuesioner sendiri (swacacah)</option>
                                                    <option value="3" {{ old('metode_pengumpulan_data', optional($data->kegiatan)->metode_pengumpulan_data) == 3 ? 'selected' : ''}}>Pengamatan (observasi)</option>
                                                    <option value="4" {{ old('metode_pengumpulan_data', optional($data->kegiatan)->metode_pengumpulan_data) == 4 ? 'selected' : ''}}>Lainnya (Sebutkan)</option>
                                                </select>

                                                <input class="form-control mt-2" type="text" name="metode_pengumpulan_data_lainnya" id="metode_pengumpulan_data_lainnya" placeholder="Sebutkan metode pengumpulan data ..." value="{{ old('metode_pengumpulan_data', optional($data->kegiatan)->metode_pengumpulan_data) }}">
                                            </div>
                                        </div>
                                    </section>

                                    <div class="row mb-3">
                                        <label for="sarana_pengumpulan_data" class="col-sm-2 col-form-label">Sarana Pengumpulan Data</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="sarana_pengumpulan_data" id="sarana_pengumpulan_data">
                                                <option value="-1" {{empty(old('sarana_pengumpulan_data', optional($data->kegiatan)->sarana_pengumpulan_data)) || old('sarana_pengumpulan_data', optional($data->kegiatan)->sarana_pengumpulan_data) == -1  ? 'selected' : ''}}>-- Pilih sarana pengumpulan data --</option>
                                                <option value="1" {{ old('sarana_pengumpulan_data', optional($data->kegiatan)->sarana_pengumpulan_data) == 1 ? 'selected' : ''}}>Pencil-and-Paper Interviewing (PAPI)</option>
                                                <option value="2" {{ old('sarana_pengumpulan_data', optional($data->kegiatan)->sarana_pengumpulan_data) == 2 ? 'selected' : ''}}>Computer-assisted Personal Interviewing (CAPI)</option>
                                                <option value="3" {{ old('sarana_pengumpulan_data', optional($data->kegiatan)->sarana_pengumpulan_data) == 3 ? 'selected' : ''}}>Computer-assisted Telephones Interviewing (CATI)</option>
                                                <option value="4" {{ old('sarana_pengumpulan_data', optional($data->kegiatan)->sarana_pengumpulan_data) == 4 ? 'selected' : ''}}>Computer Aided Web Interviewing (CAWI)</option>
                                                <option value="5" {{ old('sarana_pengumpulan_data', optional($data->kegiatan)->sarana_pengumpulan_data) == 5 ? 'selected' : ''}}>Mail</option>
                                                <option value="6" {{ old('sarana_pengumpulan_data', optional($data->kegiatan)->sarana_pengumpulan_data) == 6 || !is_numeric(old('sarana_pengumpulan_data', optional($data->kegiatan)->sarana_pengumpulan_data)) ? 'selected' : ''}}>Lainnya (Sebutkan)</option>
                                            </select>

                                            <input class="form-control mt-2" type="text" name="sarana_pengumpulan_data_lainnya" id="sarana_pengumpulan_data_lainnya" value="{{!is_numeric(old('sarana_pengumpulan_data', optional($data->kegiatan)->sarana_pengumpulan_data)) ? old('sarana_pengumpulan_data', optional($data->kegiatan)->sarana_pengumpulan_data) : ''}}" placeholder="Sebutkan sarana pengumpulan data ...">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="unit_pengumpulan_data" class="col-sm-2 col-form-label">Unit Pengumpulan Data</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="unit_pengumpulan_data" id="unit_pengumpulan_data">
                                                <option value="-1" {{empty(old('unit_pengumpulan_data', optional($data->kegiatan)->unit_pengumpulan_data)) || old('unit_pengumpulan_data', optional($data->kegiatan)->unit_pengumpulan_data) == -1  ? 'selected' : ''}}>-- Pilih sarana pengumpulan data --</option>
                                                <option value="1" {{ old('unit_pengumpulan_data', optional($data->kegiatan)->unit_pengumpulan_data) == 1 ? 'selected' : ''}}>Individu</option>
                                                <option value="2" {{ old('unit_pengumpulan_data', optional($data->kegiatan)->unit_pengumpulan_data) == 2 ? 'selected' : ''}}>Rumah tangga</option>
                                                <option value="3" {{ old('unit_pengumpulan_data', optional($data->kegiatan)->unit_pengumpulan_data) == 3 ? 'selected' : ''}}>Usaha/perusahaan</option>
                                                <option value="4" {{ old('unit_pengumpulan_data', optional($data->kegiatan)->unit_pengumpulan_data) == 4 || !is_numeric(old('unit_pengumpulan_data', optional($data->kegiatan)->unit_pengumpulan_data)) ? 'selected' : ''}}>Lainnya (Sebutkan)</option>
                                            </select>

                                            <input class="form-control mt-2" type="text" name="unit_pengumpulan_data_lainnya" id="unit_pengumpulan_data_lainnya" value="{{!is_numeric(old('unit_pengumpulan_data', optional($data->kegiatan)->unit_pengumpulan_data)) ? old('unit_pengumpulan_data', optional($data->kegiatan)->unit_pengumpulan_data) : ''}}" placeholder="Sebutkan unit pengumpulan data ...">
                                        </div>
                                    </div>

                                    @if(auth()->user()->hasAnyRole('produsen'))
                                        <div class="row mb-3">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                                <button class="btn btn-outline-primary"><i class="bi bi-save"></i> Simpan</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Desain sampel -->
                                <div class="tab-pane fade" id="tab-desain-sampel" role="tabpanel">
                                    <div class="row mb-3">
                                        <label for="jenis_rancangan_sampel" class="col-sm-2 col-form-label">Jenis Rancangan Sampel</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="jenis_rancangan_sampel" name="jenis_rancangan_sampel">
                                                <option value="-1" {{empty(old('jenis_rancangan_sampel', optional($data->kegiatan)->jenis_rancangan_sampel)) ? 'selected' : ''}}>-- Pilih metode sampel --</option>
                                                <option value="1" {{old('jenis_rancangan_sampel', optional($data->kegiatan)->jenis_rancangan_sampel) == 1 ? 'selected' : ''}}>Single Stage/Phase</option>
                                                <option value="2" {{old('jenis_rancangan_sampel', optional($data->kegiatan)->jenis_rancangan_sampel) == 2 ? 'selected' : ''}}>Multi Stage/Phase</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="metode_pemilihan_sampel_tahap_akhir" class="col-sm-2 col-form-label">Metode Pemilihan Sampel Tahap Terakhir</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="metode_pemilihan_sampel_tahap_akhir" name="metode_pemilihan_sampel_tahap_akhir">
                                                <option value="-1" {{empty(old('metode_pemilihan_sampel_tahap_akhir', optional($data->kegiatan)->metode_pemilihan_sampel_tahap_akhir)) ? 'selected' : ''}}>-- Pilih metode sampel --</option>
                                                <option value="1" {{old('metode_pemilihan_sampel_tahap_akhir', optional($data->kegiatan)->metode_pemilihan_sampel_tahap_akhir) == 1 ? 'selected' : ''}}>Sampel Probabilitas</option>
                                                <option value="2" {{old('metode_pemilihan_sampel_tahap_akhir', optional($data->kegiatan)->metode_pemilihan_sampel_tahap_akhir) == 2 ? 'selected' : ''}}>Sampel Nonprobabilitas</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="metode_sampel_yang_digunakan" class="col-sm-2 col-form-label">Metode Sampel Yang Digunakan</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="metode_sampel_yang_digunakan" name="metode_sampel_yang_digunakan">
                                                <option value="-1" {{empty(old('metode_sampel_yang_digunakan', optional($data->kegiatan)->metode_sampel_yang_digunakan)) ? 'selected' : ''}}>-- Pilih Metode Pemilihan Sampel --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <section id="sectionProbabilitas">
                                        <div class="row mb-3">
                                            <label for="kerangka_sampel_tahap_akhir" class="col-sm-2 col-form-label">Kerangka Sampel Tahap Terakhir</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="kerangka_sampel_tahap_akhir" name="kerangka_sampel_tahap_akhir">
                                                    <option value="-1" {{empty(old('kerangka_sampel_tahap_akhir', optional($data->kegiatan)->kerangka_sampel_tahap_akhir)) ? 'selected' : ''}}>Pilih Kerangka Sampel Tahap Terakhir</option>
                                                    <option value="1" {{old('kerangka_sampel_tahap_akhir', optional($data->kegiatan)->kerangka_sampel_tahap_akhir) == 1 ? 'selected' : ''}}>List Frame</option>
                                                    <option value="2" {{old('kerangka_sampel_tahap_akhir', optional($data->kegiatan)->kerangka_sampel_tahap_akhir) == 2 ? 'selected' : ''}}>Area Frame</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="fraksi_sampel_keseluruhan" class="col-sm-2 col-form-label">Fraksi Sampel Keseluruhan</label>
                                            <div class="col-sm-10">
                                                <span id="fraksi_sampel_keseluruhan_editor" class="form-control"></span>
                                                <input class="d-none" id="fraksi_sampel_keseluruhan" name="fraksi_sampel_keseluruhan">
                                                <small class="help-block text-muted">
                                                    Rumus menggunakan format LaTeX.
                                                </small>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="nilai_perkiraan_sampling_error_variabel_utama" class="col-sm-2 col-form-label">Nilai Perkiraan Sampling Error Variabel Utama</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" name="nilai_perkiraan_sampling_error_variabel_utama" id="nilai_perkiraan_sampling_error_variabel_utama" min="0" step="0.01" value="{{old('nilai_perkiraan_sampling_error_variabel_utama', optional($data->kegiatan)->nilai_perkiraan_sampling_error_variabel_utama)}}">
                                            </div>
                                        </div>
                                    </section>

                                    <section id="sectionNonProbabilitas">
                                        <div class="row mb-3">
                                            <label for="unit_sampel" class="col-sm-2 col-form-label">Unit Sampel</label>
                                            <div class="col-sm-10">
                                                <span id="unit_sampel_editor" class="form-control"></span>
                                                <input class="d-none" id="unit_sampel" name="unit_sampel">
                                                <small class="help-block text-muted">
                                                    Rumus menggunakan format LaTeX.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="unit_observasi" class="col-sm-2 col-form-label">Unit Observasi</label>
                                            <div class="col-sm-10">
                                                <input name="unit_observasi" id="unit_observasi" class="form-control" type="text" placeholder="Unit Observasi" value="{{old('unit_observasi', optional($data->kegiatan)->unit_observasi)}}">
                                            </div>
                                        </div>
                                    </section>

                                    @if(auth()->user()->hasAnyRole('produsen'))
                                        <div class="row mb-3">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                                <button class="btn btn-outline-primary"><i class="bi bi-save"></i> Simpan</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Pengumpulan Data -->
                                <div class="tab-pane fade" id="tab-pengumpulan-data" role="tabpanel">
                                    <div class="row mb-3">
                                        <label for="pilot_survey" class="col-sm-2 col-form-label">Apakah Melakukan Uji Coba (Pilot Survey)?</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="pilot_survey" name="pilot_survey">
                                                <option value="1" {{empty(old('pilot_survey', optional($data->kegiatan)->pilot_survey)) || old('pilot_survey', optional($data->kegiatan)->pilot_survey) == 1 ? 'selected' : ''}}>Ya</option>
                                                <option value="2" {{old('pilot_survey', optional($data->kegiatan)->pilot_survey) == 2 ? 'selected' : ''}}>Tidak</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="metode_pemeriksaan_kualitas_pengumpulan_data" class="col-sm-2 col-form-label">Metode Pemeriksaan Kualitas Pengumpulan Data</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="metode_pemeriksaan_kualitas_pengumpulan_data" name="metode_pemeriksaan_kualitas_pengumpulan_data">
                                                <option value="1" {{old('metode_pemeriksaan_kualitas_pengumpulan_data', optional($data->kegiatan)->metode_pemeriksaan_kualitas_pengumpulan_data) == 1 ? 'selected' : ''}}>Kunjungan kembali (revisit)</option>
                                                <option value="2" {{old('metode_pemeriksaan_kualitas_pengumpulan_data', optional($data->kegiatan)->metode_pemeriksaan_kualitas_pengumpulan_data) == 2 ? 'selected' : ''}}>Supervisi</option>
                                                <option value="3" {{old('metode_pemeriksaan_kualitas_pengumpulan_data', optional($data->kegiatan)->metode_pemeriksaan_kualitas_pengumpulan_data) == 3 ? 'selected' : ''}}>Task Force</option>
                                                <option value="4" {{old('metode_pemeriksaan_kualitas_pengumpulan_data', optional($data->kegiatan)->metode_pemeriksaan_kualitas_pengumpulan_data) == 4 || !is_numeric(old('metode_pemeriksaan_kualitas_pengumpulan_data', optional($data->kegiatan)->metode_pemeriksaan_kualitas_pengumpulan_data)) ? 'selected' : ''}}>Lainnya (sebutkan)</option>
                                            </select>

                                            <input class="form-control mt-2" type="text" name="metode_pemeriksaan_kualitas_pengumpulan_data_lainnya" id="metode_pemeriksaan_kualitas_pengumpulan_data_lainnya" value="{{!is_numeric(old('metode_pemeriksaan_kualitas_pengumpulan_data', optional($data->kegiatan)->metode_pemeriksaan_kualitas_pengumpulan_data)) ? old('metode_pemeriksaan_kualitas_pengumpulan_data', optional($data->kegiatan)->metode_pemeriksaan_kualitas_pengumpulan_data) : ''}}" placeholder="Sebutkan metode pemeriksaan kualitas pengumpulan data lainnya ...">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="penyesuaian_nonrespon" class="col-sm-2 col-form-label">Apakah Melakukan Penyesuaian Nonrespon?</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="penyesuaian_nonrespon" name="penyesuaian_nonrespon">
                                                <option value="1" {{empty(old('penyesuaian_nonrespon', optional($data->kegiatan)->penyesuaian_nonrespon)) || old('penyesuaian_nonrespon', optional($data->kegiatan)->penyesuaian_nonrespon) == 1 ? 'selected' : ''}}>Ya</option>
                                                <option value="2" {{old('penyesuaian_nonrespon', optional($data->kegiatan)->penyesuaian_nonrespon) == 2 ? 'selected' : ''}}>Tidak</option>
                                            </select>
                                        </div>
                                    </div>

                                    <section id="sectionPetugasPengumpulanData">
                                        <div class="row mb-3">
                                            <label for="petugas_pengumpulan_data" class="col-sm-2 col-form-label">Petugas Pengumpulan Data</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="petugas_pengumpulan_data" name="petugas_pengumpulan_data">
                                                    <option value="1" {{empty(old('petugas_pengumpulan_data', optional($data->kegiatan)->petugas_pengumpulan_data)) || old('petugas_pengumpulan_data', optional($data->kegiatan)->petugas_pengumpulan_data) == 1 ? 'selected' : ''}}>Staf instansi penyelenggara</option>
                                                    <option value="2" {{old('petugas_pengumpulan_data', optional($data->kegiatan)->petugas_pengumpulan_data) == 2 ? 'selected' : ''}}>Mitra/tenaga kontrak</option>
                                                    <option value="3" {{old('petugas_pengumpulan_data', optional($data->kegiatan)->petugas_pengumpulan_data) == 3 ? 'selected' : ''}}>Staf instansi penyelenggara dan mitra/tenaga kontrak</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="pendidikan_petugas_pengumpulan_data" class="col-sm-2 col-form-label">Persyaratan Pendidikan Terendah Petugas Pengumpulan Data</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="pendidikan_petugas_pengumpulan_data" name="pendidikan_petugas_pengumpulan_data">
                                                    <option value="1" {{empty(old('pendidikan_petugas_pengumpulan_data', optional($data->kegiatan)->pendidikan_petugas_pengumpulan_data)) || old('pendidikan_petugas_pengumpulan_data', optional($data->kegiatan)->pendidikan_petugas_pengumpulan_data) == 3 ? 'selected' : ''}}>≤ SMP</option>
                                                    <option value="2"  {{old('pendidikan_petugas_pengumpulan_data', optional($data->kegiatan)->pendidikan_petugas_pengumpulan_data) == 2 ? 'selected' : ''}}>>SMA/SMK</option>
                                                    <option value="3"  {{old('pendidikan_petugas_pengumpulan_data', optional($data->kegiatan)->pendidikan_petugas_pengumpulan_data) == 3 ? 'selected' : ''}}>>Diploma I/II/III</option>
                                                    <option value="4"  {{old('pendidikan_petugas_pengumpulan_data', optional($data->kegiatan)->pendidikan_petugas_pengumpulan_data) == 4 ? 'selected' : ''}}>>Diploma IV/S1/S2/S3</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Jumlah Petugas</label>
                                            <div class="col-sm-10 row form-inline">
                                                <div class="col-sm-5">
                                                    <label for="jumlah_petugas_supervisor" class="col-form-label">Supervisor/penyelia/pengawas</label>
                                                    <input class="form-control" type="number" min="1" name="jumlah_petugas_supervisor" id="jumlah_petugas_supervisor" value="{{old('jumlah_petugas_supervisor', optional($data->kegiatan)->jumlah_petugas_supervisor)}}">
                                                </div>

                                                <div class="col-sm-5">
                                                    <label for="jumlah_petugas_pengumpul_data" class="col-form-label">Pengumpul data/enumerator</label>
                                                    <input class="form-control" type="number" min="1" name="jumlah_petugas_pengumpul_data" id="jumlah_petugas_pengumpul_data" value="{{old('jumlah_petugas_pengumpul_data', optional($data->kegiatan)->jumlah_petugas_pengumpul_data)}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="pelatihan_petugas" class="col-sm-2 col-form-label">Apakah Melakukan Pelatihan Petugas?</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="pelatihan_petugas" name="pelatihan_petugas">
                                                    <option value="1" {{empty(old('pelatihan_petugas', optional($data->kegiatan)->pelatihan_petugas)) || old('pelatihan_petugas', optional($data->kegiatan)->pelatihan_petugas) == 1 ? 'selected' : ''}}>Ya</option>
                                                    <option value="2" {{old('pelatihan_petugas', optional($data->kegiatan)->pelatihan_petugas) == 2 ? 'selected' : ''}}>Tidak</option>
                                                </select>
                                            </div>
                                        </div>
                                    </section>

                                    @if(auth()->user()->hasAnyRole('produsen'))
                                        <div class="row mb-3">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                                <button class="btn btn-outline-primary"><i class="bi bi-save"></i> Simpan</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Pengolahan & Analisis -->
                                <div class="tab-pane fade" id="tab-pengolahan-analisis" role="tabpanel">
                                    <h5 class="card-title">Tahap Pengolahan Data</h5>

                                    <div class="row mb-3">
                                        <label for="editing" class="col-sm-2 col-form-label">Penyuntingan (Editing)</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="editing" name="editing">
                                                <option value="1" {{empty(old('editing', optional($data->kegiatan)->editing)) || old('editing', optional($data->kegiatan)->editing) == 1 ? 'selected' : ''}}>Ya</option>
                                                <option value="2" {{old('editing', optional($data->kegiatan)->editing) == 2 ? 'selected' : ''}}>Tidak</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="coding" class="col-sm-2 col-form-label">Penyandian (Coding)</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="coding" name="coding">
                                                <option value="1" {{empty(old('coding', optional($data->kegiatan)->coding)) || old('coding', optional($data->kegiatan)->coding) == 1 ? 'selected' : ''}}>Ya</option>
                                                <option value="2" {{old('coding', optional($data->kegiatan)->coding) == 2 ? 'selected' : ''}}>Tidak</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="data_entry" class="col-sm-2 col-form-label">Data Entry</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="data_entry" name="data_entry">
                                                <option value="1" {{empty(old('data_entry', optional($data->kegiatan)->data_entry)) || old('data_entry', optional($data->kegiatan)->data_entry) == 1 ? 'selected' : ''}}>Ya</option>
                                                <option value="2" {{old('data_entry', optional($data->kegiatan)->data_entry) == 2 ? 'selected' : ''}}>Tidak</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="validasi" class="col-sm-2 col-form-label">Penyahihan (Validasi)</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="validasi" name="validasi">
                                                <option value="1" {{empty(old('validasi', optional($data->kegiatan)->validasi)) || old('validasi', optional($data->kegiatan)->validasi) == 1 ? 'selected' : ''}}>Ya</option>
                                                <option value="2" {{old('validasi', optional($data->kegiatan)->validasi) == 2 ? 'selected' : ''}}>Tidak</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="metode_analisis" class="col-sm-2 col-form-label">Metode Analisis</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="metode_analisis" name="metode_analisis">
                                                <option value="1" {{empty(old('metode_analisis', optional($data->kegiatan)->metode_analisis)) || old('metode_analisis', optional($data->kegiatan)->metode_analisis) == 1 ? 'selected' : ''}}>Deskriptif</option>
                                                <option value="2" {{old('metode_analisis', optional($data->kegiatan)->metode_analisis) == 2 ? 'selected' : ''}}>Inferensia</option>
                                                <option value="3" {{old('metode_analisis', optional($data->kegiatan)->metode_analisis) == 3 ? 'selected' : ''}}>Deskriptif dan Inferensia</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="unit_analisis" class="col-sm-2 col-form-label">Unit Analisis</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="unit_analisis" name="unit_analisis">
                                                <option value="1" {{empty(old('unit_analisis', optional($data->kegiatan)->unit_analisis)) || old('validasi', optional($data->kegiatan)->unit_analisis) == 1 ? 'selected' : ''}}>Individu</option>
                                                <option value="2" {{old('unit_analisis', optional($data->kegiatan)->unit_analisis) == 2 ? 'selected' : ''}}>Rumah tangga</option>
                                                <option value="3" {{old('unit_analisis', optional($data->kegiatan)->unit_analisis) == 3 ? 'selected' : ''}}>Usaha/perusahaan</option>
                                                <option value="4" {{!is_numeric(old('unit_analisis', optional($data->kegiatan)->unit_analisis)) || old('unit_analisis', optional($data->kegiatan)->unit_analisis) == 4 ? 'selected' : ''}}>Lainnya (sebutkan)</option>
                                            </select>

                                            <input class="form-control mt-2" type="text" name="unit_analisis_lainnya" id="unit_analisis_lainnya" value="{{!is_numeric(old('unit_analisis', optional($data->kegiatan)->unit_analisis)) ? old('unit_analisis', optional($data->kegiatan)->unit_analisis) : ''}}" placeholder="Sebutkan unit analisis lainnya lainnya ...">
{{--                                            <small class="help-text text-muted">Klik salah satu opsi dan tekan tombol CTRL untuk memilih lebih dari 1</small>--}}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="tingkat_penyajian_hasil_analisis" class="col-sm-2 col-form-label">Tingkat Penyajian Hasil Analisis</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="tingkat_penyajian_hasil_analisis" name="tingkat_penyajian_hasil_analisis">
                                                <option value="1" {{empty(old('validasi', optional($data->kegiatan)->tingkat_penyajian_hasil_analisis)) || old('validasi', optional($data->kegiatan)->tingkat_penyajian_hasil_analisis) == 1 ? 'selected' : ''}}>Nasional</option>
                                                <option value="2" {{old('tingkat_penyajian_hasil_analisis', optional($data->kegiatan)->tingkat_penyajian_hasil_analisis) == 2 ? 'selected' : ''}}>Provinsi</option>
                                                <option value="3" {{old('tingkat_penyajian_hasil_analisis', optional($data->kegiatan)->tingkat_penyajian_hasil_analisis) == 3 ? 'selected' : ''}}>Kabupaten/Kota</option>
                                                <option value="4" {{old('tingkat_penyajian_hasil_analisis', optional($data->kegiatan)->tingkat_penyajian_hasil_analisis) == 4 ? 'selected' : ''}}>Kecamatan</option>
                                                <option value="5" {{!is_numeric(old('tingkat_penyajian_hasil_analisis', optional($data->kegiatan)->tingkat_penyajian_hasil_analisis)) || old('tingkat_penyajian_hasil_analisis', optional($data->kegiatan)->tingkat_penyajian_hasil_analisis) == 5 ? 'selected' : ''}}>Kecamatan</option>
                                            </select>

                                            <input class="form-control mt-2" type="text" name="tingkat_penyajian_hasil_analisis_lainnya" id="tingkat_penyajian_hasil_analisis_lainnya" value="{{!is_numeric(old('tingkat_penyajian_hasil_analisis', optional($data->kegiatan)->tingkat_penyajian_hasil_analisis)) ? old('tingkat_penyajian_hasil_analisis', optional($data->kegiatan)->tingkat_penyajian_hasil_analisis) : ''}}" placeholder="Sebutkan unit analisis lainnya lainnya ...">
{{--                                            <small class="help-text text-muted">Klik salah satu opsi dan tekan tombol CTRL untuk memilih lebih dari 1</small>--}}
                                        </div>
                                    </div>

                                    @if(auth()->user()->hasAnyRole('produsen'))
                                        <div class="row mb-3">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                                <button class="btn btn-outline-primary"><i class="bi bi-save"></i> Simpan</button>
                                            </div>
                                        </div>
                                    @endif

                                </div>

                                <!-- Diseminasi Hasil -->
                                <div class="tab-pane fade" id="tab-diseminasi-hasil" role="tabpanel">
                                    <h5 class="card-title">Produk Kegiatan yang Tersedia untuk Umum</h5>
                                    <div class="row mb-3">
                                        <label for="hardcopy" class="col-sm-2 form-label">Tercetak (hardcopy)</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="hardcopy" name="hardcopy">
                                                <option value="1" {{empty(old('hardcopy', optional($data->kegiatan)->hardcopy)) || old('hardcopy', optional($data->kegiatan)->hardcopy) == 1 ? 'selected' : ''}}>Ya</option>
                                                <option value="2" {{old('hardcopy', optional($data->kegiatan)->hardcopy) == 2 ? 'selected' : ''}}>Tidak</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="softcopy" class="col-sm-2 form-label">Digital (softcopy)</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="softcopy" name="softcopy">
                                                <option value="1" {{empty(old('softcopy', optional($data->kegiatan)->softcopy)) || old('softcopy', optional($data->kegiatan)->softcopy) == 1 ? 'selected' : ''}}>Ya</option>
                                                <option value="2" {{old('softcopy', optional($data->kegiatan)->softcopy) == 2 ? 'selected' : ''}}>Tidak</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="data_mikro" class="col-sm-2 form-label">Data Mikro</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="data_mikro" name="data_mikro">
                                                <option value="1" {{empty(old('data_mikro', optional($data->kegiatan)->data_mikro)) || old('data_mikro', optional($data->kegiatan)->data_mikro) == 1 ? 'selected' : ''}}>Ya</option>
                                                <option value="2" {{old('data_mikro', optional($data->kegiatan)->data_mikro) == 2 ? 'selected' : ''}}>Tidak</option>
                                            </select>
                                        </div>
                                    </div>

                                    @if(auth()->user()->hasAnyRole('produsen'))
                                        <div class="row mb-3">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                                <button class="btn btn-outline-primary"><i class="bi bi-save"></i> Simpan</button>
                                            </div>
                                        </div>
                                    @endif

                                    <h5 class="card-title">
                                        Judul dan Rencana Rilis Produk Kegiatan
                                        <span class="float-right">
                                            <a href="#" id="btnAddPublikasi" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPublikasi"><i class="bi bi-plus-circle"></i> Tambah</a>
                                        </span>
                                    </h5>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr class="text-center">
                                            <th>Judul Publikasi</th>
                                            <th>Rencana Rilis</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbodyPublikasi">
                                        @if ($data->kegiatan && is_array($data->kegiatan->rencana_publikasi) && !empty($data->kegiatan->rencana_publikasi))
                                            @foreach($data->kegiatan->rencana_publikasi as $p)
                                                <tr>
                                                    <td>{{$p['judul'] ?? '-'}}</td>
                                                    <td>{{$p['rencana_rilis'] ?? '-'}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr id="publikasiEmptyData">
                                                <td colspan="4" class="text-center">No Data</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalTambahVariabel" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Variabel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="formTambahVariabel">
                        <div class="row mb-3">
                            <label for="variabel_nama" class="col-sm-2 col-form-label">Nama Variabel</label>
                            <div class="col-sm-10">
                                <input id="variabel_nama" name="variabel_nama" type="text" class="form-control" placeholder="Nama Variabel (Karakteristik)">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="variabel_konsep" class="col-sm-2 col-form-label">Konsep</label>
                            <div class="col-sm-10">
                                <input id="variabel_konsep" name="variabel_konsep" type="text" class="form-control" placeholder="Konsep" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="variabel_definisi" class="col-sm-2 col-form-label">Definisi</label>
                            <div class="col-sm-10">
                                <textarea name="variabel_definisi" id="variabel_definisi" class="form-control" style="height: 100px" spellcheck="false" placeholder="Definisi" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="variabel_referensi_waktu" class="col-sm-2 col-form-label">Referensi Waktu</label>
                            <div class="col-sm-10">
                                <input id="variabel_referensi_waktu" name="variabel_referensi_waktu" type="date" class="form-control" placeholder="Referensi Waktu" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSubmitVariabel"><i class="bi bi-plus"></i> Tambah</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahPublikasi" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Publikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="formTambahPublikasi">
                        <div class="row mb-3">
                            <label for="publikasi_judul" class="col-sm-2 col-form-label">Judul Publikasi</label>
                            <div class="col-sm-10">
                                <input id="publikasi_judul" name="publikasi_judul" type="text" class="form-control" placeholder="Judul Publikasi">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="publikasi_rencana_rilis" class="col-sm-2 col-form-label">Rencana rilis</label>
                            <div class="col-sm-10">
                                <input id="publikasi_rencana_rilis" name="publikasi_rencana_rilis" type="date" class="form-control" placeholder="Rencana Rilis">
                                <small class="text-help text-muted">
                                    Isian tanggal boleh tidak diisi, namun bulan dan tahun wajib diisi
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSubmitPublikasi"><i class="bi bi-plus"></i> Tambah</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link href="{{asset('assets/vendor/mathquill/mathquill.css')}}" rel="stylesheet">
@endsection
@push('js')
    <script src="{{asset('assets/vendor/mathquill/mathquill.min.js')}}"></script>
    <script>
        $(function() {
            var MQ = MathQuill.getInterface(2);

            $('#sectionSekali').hide();
            $('#sectionBerulang').hide();
            $('input[name="kegiatan_dilakukan"]').change(function() {
                if ($('#kegiatan_dilakukan_sekali').is(':checked')) {
                    $('#sectionBerulang').hide();
                    $('#sectionSekali').show();
                } else {
                    $('#sectionBerulang').show();
                    $('#sectionSekali').hide();
                }
            }).trigger('change');

            $('#sectionSebagianWilayah').hide();
            $('#sectionSeluruhWilayah').show();
            $('#cakupan_wilayah_pengumpulan_data').change(function() {
                if ($(this).val() == 1) {
                    $('#sectionSebagianWilayah').hide();
                    $('#sectionSeluruhWilayah').show();
                } else {
                    $('#sectionSebagianWilayah').show();
                    $('#sectionSeluruhWilayah').hide();
                }
            }).trigger('change');

            $('#metode_pengumpulan_data_lainnya').hide();
            $('#metode_pengumpulan_data').change(function() {
                if ($(this).val() == 4) {
                    $('#metode_pengumpulan_data').prop('name', 'metode_pengumpulan_data_etc');
                    $('#metode_pengumpulan_data_lainnya').prop('name', 'metode_pengumpulan_data').show();
                } else {
                    $('#metode_pengumpulan_data_lainnya').prop('name', 'metode_pengumpulan_data_lainnya').hide();
                    $('#metode_pengumpulan_data').prop('name', 'metode_pengumpulan_data');
                }
            }).trigger('change');

            $('#sectionPetugasPengumpulanData').hide();
            $('#sarana_pengumpulan_data_lainnya').hide();
            $('#sarana_pengumpulan_data').change(function() {
                var val = parseInt($(this).val());
                if (val === 6 || isNaN(val)) {
                    $('#sarana_pengumpulan_data').prop('name', 'sarana_pengumpulan_data_etc');
                    $('#sarana_pengumpulan_data_lainnya').prop('name', 'sarana_pengumpulan_data').show();
                } else if (val === 1 || val === 2 || val === 3) {
                    $('#sectionPetugasPengumpulanData').show();
                } else {
                    $('#sarana_pengumpulan_data_lainnya').prop('name', 'sarana_pengumpulan_data_lainnya').hide();
                    $('#sarana_pengumpulan_data').prop('name', 'sarana_pengumpulan_data');
                }
            }).trigger('change');

            $('#unit_pengumpulan_data_lainnya').hide();
            $('#unit_pengumpulan_data').change(function() {
                var val = parseInt($(this).val());
                if (val === 4 || isNaN(val)) {
                    $('#unit_pengumpulan_data').prop('name', 'unit_pengumpulan_data_etc');
                    $('#unit_pengumpulan_data_lainnya').prop('name', 'unit_pengumpulan_data').show();
                } else {
                    $('#unit_pengumpulan_data_lainnya').prop('name', 'unit_pengumpulan_data_lainnya').hide();
                    $('#unit_pengumpulan_data').prop('name', 'unit_pengumpulan_data');
                }
            }).trigger('change');

            $('#sectionProbabilitas').hide();
            $('#sectionNonProbabilitas').hide();
            let metodeSampelEl = document.getElementById('metode_sampel_yang_digunakan');
            $('#metode_pemilihan_sampel_tahap_akhir').change(function() {
                let listMetodeSampel;
                if ($(this).val() == 1) {
                    $('#sectionProbabilitas').show();
                    $('#sectionNonProbabilitas').hide();
                    listMetodeSampel = [
                        {id: 1, name: 'Simple Random Sampling'},
                        {id: 2, name: 'Systematic Random Sampling'},
                        {id: 3, name: 'Stratified Random Sampling'},
                        {id: 4, name: 'Cluster Sampling'},
                        {id: 5, name: 'Probability Proportional to Size Sampling'},
                    ];
                } else {
                    $('#sectionProbabilitas').hide();
                    $('#sectionNonProbabilitas').show();
                    listMetodeSampel = [
                        {id: 6, name: 'Quota Sampling'},
                        {id: 7, name: 'Accidental Sampling'},
                        {id: 8, name: 'Purposive Sampling'},
                        {id: 9, name: 'Snowball Sampling'},
                        {id: 10, name: 'Saturation Sampling'}
                    ];
                }

                for (let i = 0; i < listMetodeSampel.length; i++) {
                    metodeSampelEl[i] = new Option(listMetodeSampel[i].name, listMetodeSampel[i].id, false, parseInt('{{old('metode_sampel_yang_digunakan', optional($data->kegiatan)->metode_sampel_yang_digunakan)}}') == listMetodeSampel[i].id);
                }
            }).trigger('change');

            $('#metode_pemeriksaan_kualitas_pengumpulan_data_lainnya').hide();
            $('#metode_pemeriksaan_kualitas_pengumpulan_data').change(function() {
                if ($(this).val() == 4) {
                    $('#metode_pemeriksaan_kualitas_pengumpulan_data').prop('name', 'metode_pemeriksaan_kualitas_pengumpulan_data_etc');
                    $('#metode_pemeriksaan_kualitas_pengumpulan_data_lainnya').prop('name', 'metode_pemeriksaan_kualitas_pengumpulan_data').show();
                } else {
                    $('#metode_pemeriksaan_kualitas_pengumpulan_data_lainnya').prop('name', 'metode_pemeriksaan_kualitas_pengumpulan_data_lainnya').hide();
                    $('#metode_pemeriksaan_kualitas_pengumpulan_data').prop('name', 'metode_pemeriksaan_kualitas_pengumpulan_data');
                }
            });

            $('#unit_analisis_lainnya').hide();
            $('#unit_analisis').change(function() {
                if ($(this).val() == 4) {
                    $('#unit_analisis').prop('name', 'unit_analisis_etc');
                    $('#unit_analisis_lainnya').prop('name', 'unit_analisis').show();
                } else {
                    $('#unit_analisis_lainnya').prop('name', 'unit_analisis_lainnya').hide();
                    $('#unit_analisis').prop('name', 'unit_analisis');
                }
            }).trigger('change');


            $('#tingkat_penyajian_hasil_analisis_lainnya').hide();
            $('#tingkat_penyajian_hasil_analisis').change(function() {
                if ($(this).val() == 4) {
                    $('#tingkat_penyajian_hasil_analisis').prop('name', 'tingkat_penyajian_hasil_analisis_etc');
                    $('#tingkat_penyajian_hasil_analisis_lainnya').prop('name', 'tingkat_penyajian_hasil_analisis').show();
                } else {
                    $('#tingkat_penyajian_hasil_analisis_lainnya').prop('name', 'tingkat_penyajian_hasil_analisis_lainnya').hide();
                    $('#tingkat_penyajian_hasil_analisis').prop('name', 'tingkat_penyajian_hasil_analisis');
                }
            });

            let provinsiKegiatan = '{{old('provinsi_kegiatan', optional($data->kegiatan)->provinsi_kegiatan)}}';
            $('#provinsi_kegiatan').change(function() {
                let cityEl = document.getElementById('kota_kegiatan');
                $.get('{{route('ajax.cities')}}/'  + $(this).val())
                    .then(function (data) {
                        $('#kota_kegiatan').empty();
                        if (data.cities) {
                            for (let i in data.cities) {
                                let city = data.cities[i];
                                cityEl[i] = new Option(city.name, city.code, false, '{{old('kota_kegiatan', optional($data->kegiatan)->kota_kegiatan)}}' == city.code);
                            }
                        }
                    })
                    .catch(() => alert('Gagal memuat data kota / kabupaten'));
            });
            if (provinsiKegiatan !== '' && $('#cakupan_wilayah_pengumpulan_data').val() == 2) {
                $('#provinsi_kegiatan').val(provinsiKegiatan).trigger('change');
            }

            $('#btnSubmitVariabel').on('click', function(e) {
                e.preventDefault();

                $.post({
                    url: '{{route('simpan-variabel-dikumpulkan', $data->id)}}',
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                }, {
                    nama: $('#variabel_nama').val(),
                    konsep: $('#variabel_konsep').val(),
                    definisi: $('#variabel_definisi').val(),
                    referensi_waktu: $('#variabel_referensi_waktu').val()
                }).then(function() {
                    $('#tbodyVariabel').append(
                        '<tr><td>' + $('#variabel_nama').val() + '</td>' +
                        '<td>' + $('#variabel_konsep').val() + '</td>' +
                        '<td>' + $('#variabel_definisi').val() + '</td>' +
                        '<td>' + $('#variabel_referensi_waktu').val() + '</td></tr>'
                    );
                    $('#variabelEmptyData').remove();
                    $('#variabel_nama').val('');
                    $('#variabel_konsep').val('');
                    $('#variabel_definisi').val('');
                    $('#variabel_referensi_waktu').val('01/01/1990');
                });
            });


            $('#btnSubmitPublikasi').on('click', function(e) {
                e.preventDefault();
                $.post({
                    url: '{{route('simpan-publikasi', $data->id)}}',
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                }, {
                    judul: $('#publikasi_judul').val(),
                    rencana_rilis: $('#publikasi_rencana_rilis').val()
                }).then(function() {
                    $('#publikasiEmptyData').remove();
                    $('#tbodyPublikasi').append(
                        '<tr><td>' + $('#publikasi_judul').val() + '</td>' +
                        '<td>' + $('#publikasi_rencana_rilis').val() + '</td></tr>'
                    );
                    $('#publikasi_judul').val('');
                    $('#publikasi_rencana_rilis').val('01/01/1990');
                });
            });

            let fraksiSampel = MQ.MathField(document.getElementById('fraksi_sampel_keseluruhan_editor'), {
                spaceBehavesLikeTab: true,
                handlers: {
                    edit: function() {
                        $('#fraksi_sampel_keseluruhan').val(encodeURIComponent(fraksiSampel.latex()));
                    }
                }
            });
            let oldFraksi = decodeURIComponent('{{old('fraksi_sampel_keseluruhan', optional($data->kegiatan)->fraksi_sampel_keseluruhan)}}');
            if (oldFraksi !== '') {
                fraksiSampel.latex(oldFraksi);
            }

            let unitSampel = MQ.MathField(document.getElementById('unit_sampel_editor'), {
                spaceBehavesLikeTab: true,
                handlers: {
                    edit: function() {
                        $('#unit_sampel').val(encodeURIComponent(unitSampel.latex()));
                    }
                }
            });
            let oldUnit = decodeURIComponent('{{old('unit_sampel', optional($data->kegiatan)->unit_sampel)}}');
            if (oldUnit !== '') {
                unitSampel.latex(oldUnit);
            }

            // function myConfirmation() {
            //     return 'Are you sure you want to quit?';
            // }
            //
            // window.onbeforeunload = myConfirmation;
        });
    </script>
@endpush
