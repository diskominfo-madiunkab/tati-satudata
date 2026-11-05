@extends('pages.main.layout')
@section('content')
    <div class="pagetitle">
        <h1>Detail Data</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item">Daftar Data</li>
                <li class="breadcrumb-item active">Detail Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"></h5>

                        <!-- General Form Elements -->
                        <!-- End General Form Elements -->
                        <!-- Default Tabs -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true"><i
                                        class="bi bi-card-list"></i> Detail Data
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false"><i
                                        class="bi bi-clock-history"></i> Riwayat Data
                                </button>
                            </li>
                            {{-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
                        </li> --}}
                        </ul>
                        <div class="tab-content pt-2" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                @if(($data->status_id == 2 || $data->status_id == \App\Models\Data::STATUS_TOLAK) && !empty($data->alasan))
                                    <div class="alert alert-danger d-flex align-items-center mt-2 mb-3" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2 fs-5"></i>
                                        <div>
                                            <strong>Catatan Penolakan / Revisi:</strong> {{ $data->alasan }}
                                        </div>
                                    </div>
                                @endif
                                <form
                                    @if (Auth::user()->role_id == '1') action="{{ url('data_administrator/update', $data->id) }}"
                                @elseif(Auth::user()->role_id == '2')
                                action="{{ url('data_walidata/update', $data->id) }}"
                                @elseif(Auth::user()->role_id == '3')
                                action="{{ url('data_produsen/update', $data->id) }}" @endif
                                    method="POST">
                                    @csrf
                                    <div class="row mb-3" style="padding-top:20px">
                                        <label for="kode_ddd" class="col-sm-2 col-form-label">Kode DDD</label>
                                        <div class="col-sm-10">
                                            <input id="kode_ddd" name="kode_ddd" type="text" class="form-control" value="{{ $data->kode_ddd ?? '-' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="kode_ddp" class="col-sm-2 col-form-label">Kode DDP</label>
                                        <div class="col-sm-10">
                                            <input id="kode_ddp" name="kode_ddp" type="text" class="form-control" value="{{ $data->kode_ddp ?? '-' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Nama Data</label>
                                        <div class="col-sm-10">
                                            <input id="nama_data" name="nama_data" type="text" class="form-control"
                                                value="{{ $data->nama_data }}" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Jenis Data</label>
                                        <div class="col-sm-10">
                                            <select id="jenis_data" name="jenis_data" class="form-select"
                                                aria-label="Default select example" value="{{ $data->jenis_data }}"
                                                disabled>
                                                <option selected value="{{ $data->jenis_data }}">{{ $data->jenis_data }}
                                                </option>
                                                <option value="Indikator">Indikator</option>
                                                <option value="Variabel">Variabel</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Produsen Data(PIC)</label>
                                        <div class="col-sm-10">
                                            <select id="opd_id" name="opd_id" class="form-select"
                                                aria-label="Default select example" value="{{ $data->opd_id }}" disabled>
                                                <option selected value="{{ $data->opd_id }}">{{ $data->opd->nama_opd }}
                                                </option>
                                                @foreach ($opd as $dt)
                                                    <option value="{{ $dt->id }}">{{ $dt->nama_opd }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Sumber Referensi</label>
                                        <div class="col-sm-10">
                                            <select id="sumber_data" name="sumber_data" class="form-select"
                                                aria-label="Default select example" value="{{ $data->sumber_data }}"
                                                disabled>
                                                <option selected value="{{ $data->sumber_data }}">{{ $data->sumber_data }}
                                                </option>
                                                <option value="RPJMD">RPJMD</option>
                                                <option value="SPM">SPM</option>
                                                <option value="SDGs">SDGs</option>
                                                <option value="Data Provinsi">Data Provinsi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Tahun</label>
                                        <div class="col-sm-10">

                                            <select id="tahun" name="tahun" class="form-select select2"
                                                aria-label="Default select example" value="{{ $data->tahun }}" disabled>
                                                <option selected value="{{ $data->tahun }}">{{ $data->tahun }}</option>

                                                {{-- <option value="RPJMD">RPJMD</option>
                                            <option value="SPM">SPM</option>
                                            <option value="SDGs">SDGs</option>
                                            <option value="Data Provinsi">Data Provinsi</option> --}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3" style="padding-top:0px">
                                        <label for="inputText" class="col-sm-2 col-form-label">Jadwal Rilis</label>
                                        <div class="col-sm-10">
                                            <?php
                                            $value = $data->jadwal_rilis ?? '-';
                                            if ($value !== '-') {
                                                $formattedDate = date('d F Y', strtotime($value));
                                            } else {
                                                $formattedDate = '-';
                                            }
                                            ?>
                                            <input id="nama_data" name="nama_data" type="text" class="form-control"
                                                value="<?php echo $formattedDate; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3" style="padding-top:0px">
                                        <label for="inputText" class="col-sm-2 col-form-label">Jadwal
                                            Pemutakhiran</label>
                                        <div class="col-sm-10">
                                            <input id="nama_data" name="nama_data" type="text" class="form-control"
                                                value="{{ $data->jadwal_pemutakhiran ?? '-' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="data_prioritas" class="col-sm-2 col-form-label">Data
                                            Prioritas</label>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="data_prioritas"
                                                    id="data_prioritas1" value="1"
                                                    {{ $data->data_prioritas == 1 ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="data_prioritas1">
                                                    Ya
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="data_prioritas"
                                                    id="data_prioritas0" value="0"
                                                    {{ $data->data_prioritas == 0 ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="data_prioritas0">
                                                    Tidak
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Level Data</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{ $data->level_data ?? 'Kabupaten' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Periode Data</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{ $data->periode_data ?? $data->jadwal_pemutakhiran ?? '-' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Kategori RAD</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{ $data->kategori_rad ?? '-' }}" disabled>
                                        </div>
                                    </div>
                                    @if($data->konsep || $data->definisi)
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Konsep</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" rows="2" disabled>{{ $data->konsep ?? '-' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Definisi</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" rows="3" disabled>{{ $data->definisi ?? '-' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Ukuran / Satuan</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{ ($data->ukuran ?? '-') . ' / ' . ($data->satuan ?? '-') }}" disabled>
                                        </div>
                                    </div>
                                    @endif

                                </form>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                @foreach ($detail as $dt)
                                    <nav style="--bs-breadcrumb-divider: '>';">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a
                                                    href="#">{{ \Carbon\Carbon::parse($dt->created_at)->format('d/m/Y H:i') }}</a>
                                            </li>
                                            <li class="breadcrumb-item"><a href=""><i
                                                        class="bi bi-clock-history"></i> {{ $dt->name }}</a></li>
                                            <li class="breadcrumb-item active">{{ $dt->description }}
                                            </li>
                                        </ol>
                                    </nav>
                                @endforeach
                            </div>
                        </div><!-- End Default Tabs -->
                        @php
                            $backUrl = url()->previous();
                            if (!$backUrl || $backUrl == url()->current()) {
                                if (auth()->user()->hasAnyRole('produsen')) {
                                    $backUrl = '/data_produsen/draft';
                                } elseif (auth()->user()->hasAnyRole('walidata')) {
                                    $backUrl = '/data_walidata/draft';
                                } else {
                                    $backUrl = '/data_walidata/draft';
                                }
                            }
                        @endphp
                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top">
                            <a href="{{ $backUrl }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>

                            @if(auth()->user()->hasAnyRole('produsen') && $data->status_id == \App\Models\Data::STATUS_DRAFT)
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak">
                                        <i class="bi bi-x-circle me-1"></i> Tolak / Minta Revisi
                                    </button>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSetuju">
                                        <i class="bi bi-check-circle me-1"></i> Setujui Daftar Data
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    @if(auth()->user()->hasAnyRole('produsen') && $data->status_id == \App\Models\Data::STATUS_DRAFT)
        <!-- Modal Setuju -->
        <div class="modal fade" id="modalSetuju" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('data_produsen.perencanaan.setuju') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_sukses" value="{{ $data->id }}">
                        <div class="modal-header">
                            <h5 class="modal-title text-success"><i class="bi bi-check-circle me-1"></i> Konfirmasi Persetujuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menyetujui Daftar Data <strong>"{{ $data->nama_data }}"</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i> Ya, Setujui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Tolak -->
        <div class="modal fade" id="modalTolak" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('data_produsen.perencanaan.tolak') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_alasan" value="{{ $data->id }}">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-1"></i> Penolakan / Permintaan Revisi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="alasan" class="form-label">Tuliskan Alasan Penolakan / Catatan Revisi <span class="text-danger">*</span></label>
                                <textarea name="alasan" id="alasan" class="form-control" rows="4" required placeholder="Jelaskan alasan penolakan atau perbaikan yang dibutuhkan..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle me-1"></i> Tolak Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
