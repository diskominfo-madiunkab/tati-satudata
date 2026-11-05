@extends('pages.main.layout')

@section('content')
    @include('sweetalert::alert')
    <div class="pagetitle">
        <h1>Detail Data: {{ $data->nama_data }}</h1>
        {{-- <h2>{{$data->status_id}}</h2> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item">Daftar Data</li>
                <li class="breadcrumb-item active">{{ $data->nama_data }}</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    {{-- @php
dd($data);
@endphp --}}
    {{-- @if ($data->status_id == 1) --}}
    <section class="section">

        <!-- Default Tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                    role="tab" aria-controls="home" aria-selected="true">Tabel</button>
            </li>
            @if ($tables)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                        role="tab" aria-controls="profile" aria-selected="false">Grafik</button>
                </li>
            @endif
        </ul>
        <div class="tab-content pt-2" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="col-lg-12">
                    <!-- Direct Portal Interactive Data Grid (Halaman 30 & 31) -->
                    <div class="card mb-4 border shadow-sm">
                        <div class="card-body pt-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 border-bottom pb-3">
                                <div>
                                    <h5 class="card-title mb-1 text-primary"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Pengisian Data Langsung Melalui Portal</h5>
                                    <p class="text-muted small mb-0">Input data statistik secara interaktif langsung dari portal tanpa file Excel, terstandarisasi dengan kode referensi wilayah & periode.</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary" id="btnSimpanGridDraft">
                                        <i class="bi bi-save me-1"></i> Simpan Sementara (Draft)
                                    </button>
                                    <button type="button" class="btn btn-success" id="btnAjukanVerifikasi">
                                        <i class="bi bi-send-check me-1"></i> Ajukan Verifikasi Walidata
                                    </button>
                                </div>
                            </div>

                            <!-- Controls -->
                            <div class="row g-2 p-3 bg-light rounded border mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small">Level Data Wilayah</label>
                                    <select id="gridLevelData" class="form-select form-select-sm">
                                        <option value="Kabupaten" {{ ($data->level_data ?? '') == 'Kabupaten' ? 'selected' : '' }}>Kabupaten (Kode 35.19 - Kab. Madiun)</option>
                                        <option value="Kecamatan" {{ ($data->level_data ?? '') == 'Kecamatan' || empty($data->level_data) ? 'selected' : '' }}>Kecamatan (15 Kecamatan + Kode Referensi)</option>
                                        <option value="Desa/Kelurahan" {{ ($data->level_data ?? '') == 'Desa/Kelurahan' ? 'selected' : '' }}>Desa / Kelurahan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small">Periode / Frekuensi Data</label>
                                    <select id="gridPeriodeData" class="form-select form-select-sm">
                                        <option value="Tahunan" {{ ($data->periode_data ?? '') == 'Tahunan' || empty($data->periode_data) ? 'selected' : '' }}>Tahunan (Per Tahun)</option>
                                        <option value="Bulanan" {{ ($data->periode_data ?? '') == 'Bulanan' ? 'selected' : '' }}>Bulanan (Januari - Desember)</option>
                                        <option value="Triwulan" {{ ($data->periode_data ?? '') == 'Triwulan' ? 'selected' : '' }}>Triwulanan (TW 1 - TW 4)</option>
                                        <option value="Semester" {{ ($data->periode_data ?? '') == 'Semester' ? 'selected' : '' }}>Semesteran (Semester 1 & 2)</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnApplyStructure">
                                        <i class="bi bi-arrow-repeat me-1"></i> Terapkan Struktur
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnAddColumn">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah Kolom
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnAddRow">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah Baris
                                    </button>
                                </div>
                            </div>

                            <!-- Interactive Grid Table -->
                            <div class="table-responsive border rounded mb-3" style="max-height: 480px; overflow-y: auto;">
                                <table class="table table-bordered table-hover align-middle mb-0" id="portalDataGrid">
                                    <thead class="table-primary sticky-top" id="gridThead">
                                    </thead>
                                    <tbody id="gridTbody">
                                    </tbody>
                                </table>
                            </div>

                            <div id="gridSaveAlert" class="alert alert-success d-none py-2 mb-0" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i> <span id="gridSaveAlertMsg">Data berhasil disimpan sementara!</span>
                            </div>
                        </div>
                    </div>

                    @if (auth()->user()->hasAnyRole('produsen') && ($data->status_id == 10 || $data->status_id == 7 || $data->status_id == 1 || $data->status_id == 4))
                        <div class="card collapsed-card">
                            <div class="row">
                                <div class="card-body">
                                    <div class="card-title d-flex justify-content-between align-items-center">
                                        <span>Unggah Berkas Pendukung (Opsional)</span>
                                        <span class="badge bg-secondary">Lampiran</span>
                                    </div>
                                        <div class="modal fade" id="basicModal" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">List Template</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="input-group mb-3">
                                                            {{-- list --}}
                                                            @foreach ($document as $bkrs)
                                                                <div class="list-group" style="margin-bottom: 5px">
                                                                    <a href="{{ url('/download-template', $bkrs->id) }}"
                                                                        class="list-group-item list-group-item-action">
                                                                        {{ $bkrs->keterangan }}
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <form action=" {{ route('visual.data.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="number" value="{{ $data->id }}" name="id_data" id="id_data"
                                            hidden>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-6 col-form-label">Berkas Data</label>
                                            <div class="col-sm-12">
                                                <input type="file" name="berkas" id="berkas" class="form-control">
                                            </div>
                                        </div>
                                        @if ($data->is_from_walidata)
                                            <div class="row mb-3">
                                                <label for="inputText" class="col-sm-6 col-form-label">Value Tahunan
                                                    E-Walidata</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="value_sipd" id="value_sipd"
                                                        placeholder="Isikan Value Tahunan E-Walidata" class="form-control">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="footer">
                                            <button type="submit" class="btn btn-primary" id="store">Tambah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($data->relationLoaded('verifikasi'))
                        <section class="section">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title">Revisi Berkas Data</div>
                                            <div class="table-responsive">
                                                <table class="table table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Nama Berkas</th>
                                                            <th>Status</th>
                                                            <th>Komentar</th>
                                                            @if (auth()->user()->hasAnyRole('produsen'))
                                                                <th>Aksi</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data->berkas as $berkas)
                                                            @php
                                                                $v = $data->verifikasi->firstWhere(
                                                                    'field',
                                                                    $berkas['id'],
                                                                );
                                                                // dd();
                                                            @endphp
                                                            <tr>

                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>
                                                                    <a href="{{ route('filepreview', ['payload' => Crypt::encryptString($berkas->path)]) }}"
                                                                        target="_new">{{ $berkas['name'] ?? '-' }} <i
                                                                            class="bi bi-link"></i></a>
                                                                </td>
                                                                <td>
                                                                    <h5>
                                                                        @if ($v === null)
                                                                            <span class="badge border-info text-info">Belum
                                                                                Diverifikasi</span>
                                                                        @elseif ($v->accepted == 0)
                                                                            <span
                                                                                class="badge border-danger text-danger">Revisi</span>
                                                                        @elseif ($v->accepted == 1)
                                                                            <span
                                                                                class="badge border-success text-success">Disetujui</span>
                                                                        @endif
                                                                    </h5>
                                                                </td>
                                                                <td>
                                                                    <em>{{ $v && $v->comment ? $v->comment : '-' }}</em>
                                                                </td>
                                                                @if (auth()->user()->hasAnyRole('produsen'))
                                                                    <td><a href="javascript:void(0)"
                                                                            class="btn btn-outline-danger btn-sm"
                                                                            onclick='$("#modal-hapus").modal("show");
                                                                    $("#id_berkas").val("{{ $berkas->id }}");
                                                                    $("#id_visualdata").val("{{ $berkas->visual_id }}");
                                                                    $("#tahunhapus").val("{{ $data->tahun }}");'>
                                                                            <i class="bi bi-trash"></i> Hapus</a></td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            @if ($data->is_from_walidata)
                                <h5 class="card-title">Value Tahunan
                                    E-Walidata</h5>
                                <div class="col-sm-12">
                                    <input type="text" name="value_sipd" id="value_sipd"
                                        value="{{ $data->value_sipd }}" class="form-control" disabled>
                                </div>
                            @endif

                            <h5 class="card-title"> List Berkas </h5>
                            @if (auth()->user()->hasAnyRole('produsen'))
                                {{-- <a href="javascript:void(0)" class="btn btn-success mb-2" id="btn-create-post">Tambah</a>
                        --}}
                            @endif
                            {{-- disini list berkas --}}
                            <div class="table-responsive">
                                <table class="table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            {{-- <th>Tahun</th>
                                        <th>Nilai</th> --}}
                                            <th>Berkas</th>
                                            @if (auth()->user()->hasAnyRole('produsen'))
                                                <th>Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->berkas as $keys => $berkas1)
                                            <tr>
                                                <td>{{ $keys + 1 }}</td>
                                                <td>
                                                    {{-- @php
                                            dd($berkas1->visual_id);
                                            @endphp --}}
                                                </td>
                                                <td><a href="{{ Storage::url($berkas1->path) }}"
                                                        target="_new">{{ $berkas1->name ?? '-' }}
                                                        <i class="bi bi-link"></i></a></td>
                                                @if (auth()->user()->hasAnyRole('produsen'))
                                                    @if ($data->relationLoaded('verifikasi'))
                                                    @else
                                                        <td><a href="javascript:void(0)"
                                                                class="btn btn-outline-danger btn-sm"
                                                                onclick='$("#modal-hapus").modal("show");
                                                                                                            $("#id_berkas").val("{{ $berkas1->id }}");
                                                                                                            $("#id_visualdata").val("{{ $berkas1->visual_id }}");
                                                                                                            $("#tahunhapus").val("{{ $data->tahun }}");'>
                                                                <i class="bi bi-trash"></i> Hapus</a></td>
                                                    @endif
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive">
                                @if ($tables)
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
                                                            @if (auth()->user()->hasAnyRole('produsen'))
                                                                {{-- <th>Actions</th> --}}
                                                            @endif
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
                                                                        $cellValue = $cell ? $cell->isi : '';
                                                                        $originalCellValue = $cell ? $cell->isi : '0';
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
                                                                @if (auth()->user()->hasAnyRole('produsen'))
                                                                    {{-- <td>
                                                <button type="button" class="btn btn-primary btn-update"
                                                    data-table="{{ $table->id }}"
                                                    data-row="{{ $rowIndex }}">Update</button>
                                                <button type="button" class="btn btn-danger btn-delete"
                                                    data-table="{{ $table->id }}"
                                                    data-row="{{ $rowIndex }}">Delete</button>
                                            </td> --}}
                                                                @endif
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
                                @endif
                            </div>

                            {{-- <a href="{{url()->previous('d_' . auth()->user()->role->name)}}" --}}
                            @if ($data->status_id == 7)
                                <a href="{{ auth()->user()->hasAnyRole('produsen') ? '/data_produsen/verifikasi/revisi' : '/data_walidata/verifikasi/revisi' }}"
                                    {{-- href="{{ url()->previous() }}" --}} class="btn btn-outline-secondary"><i
                                        class="bi bi-arrow-left"></i>
                                    Kembali</a>
                            @else
                                <a href="{{ auth()->user()->hasAnyRole('produsen') ? '/data_produsen/pengumpulan' : '/data_walidata/pengumpulan' }}"
                                    {{-- href="{{ url()->previous() }}" --}} class="btn btn-outline-secondary"><i
                                        class="bi bi-arrow-left"></i>
                                    Kembali</a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            @if ($tables)
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                        <div class="col-lg-9">
                            @foreach ($tables as $table)
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Grafik Data {{ $data->nama_data }}</h5>
                                        <div>
                                            <label for="chartTypeSelect">Pilih Jenis Grafik:</label>
                                            <select id="chartTypeSelect">
                                                <option value="bar">Bar Chart</option>
                                                <option value="line">Line Chart</option>
                                            </select>
                                        </div>

                                        <div id="chartContainer">
                                            <div id="chart"></div>
                                        </div>
                                        {{-- <div id="columnChart{{ $loop->index }}"></div>
                            <div id="lineChart{{ $loop->index }}"></div> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-lg-3">
                            @foreach ($tables as $k => $table)
                                <div class="card" style="margin-bottom: 55%">
                                    <div class="card-body">
                                        <form id="grafikForm{{ $k }}"
                                            action="{{ route('chart.storeDataByFilter') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="text" name="id_data" id="id_data"
                                                value="{{ $data->id }}" hidden>
                                            <input type="text" name="id_table" id="id_table"
                                                value="{{ $table['table']['id'] }}" hidden>
                                            <h5 class="card-title">Filter</h5>

                                            <label for="dropdown_axis_x_{{ $table['table']['id'] }}">Axis X</label>
                                            <select name="axis_x" id="dropdown_axis_x_{{ $k }}"
                                                class="form-select select2 select-axis-x"
                                                aria-label="Default select example">
                                                <option value="0">-- Data Tunggal --</option>
                                                @foreach ($table['headers'] as $header)
                                                    {{-- @if (empty(sizeOf($existingData)))
                                    <option value="">--Tidak Ada Data--</option>
                                    @else --}}
                                                    <option value="{{ $header->id }}"
                                                        @if ($existingData->isNotEmpty() && isset($existingData[$k]) && $existingData[$k]->axis_x == $header->id) selected @endif>
                                                        {{ $header->header }}
                                                    </option>
                                                    {{-- @endif --}}
                                                @endforeach
                                            </select>

                                            <label for="dropdown_axis_y_{{ $table['table']['id'] }}">Axis Y</label>
                                            <span class="badge border-warning border-1 text-warning">*Inputan berupa
                                                nilai</span>
                                            <select name="axis_y" id="dropdown_axis_y_{{ $k }}"
                                                class="form-select select2 select-axis-y"
                                                aria-label="Default select example">
                                                @foreach ($table['headers'] as $header)
                                                    {{-- @if (empty(sizeOf($existingData)))
                                    <option value="">--Tidak Ada Data--</option>
                                    @else --}}
                                                    <option value="{{ $header->id }}"
                                                        @if ($existingData->isNotEmpty() && isset($existingData[$k]) && $existingData[$k]->axis_y == $header->id) selected @endif>
                                                        {{ $header->header }}
                                                    </option>
                                                    {{-- @endif --}}
                                                @endforeach
                                            </select>

                                            <label for="dropdown_category_{{ $table['table']['id'] }}">Kategori</label>
                                            <select name="kategori" id="dropdown_category_{{ $k }}"
                                                class="form-select select2 select-category"
                                                aria-label="Default select example">
                                                @foreach ($table['headers'] as $header)
                                                    {{-- @if (empty(sizeOf($existingData)))
                                    <option value="">--Tidak Ada Data--</option>
                                    @else --}}
                                                    @if ($header->header == 'Tahun')
                                                        <option value="{{ $header->id }}"
                                                            @if ($existingData->isNotEmpty() && isset($existingData[$k]) && $existingData[$k]->kategori == $header->id) selected @endif>
                                                            {{ $header->header }}</option>
                                                    @endif
                                                    {{-- @endif --}}
                                                @endforeach
                                            </select>

                                            <br>
                                            @if (($data->status_id == 10 || $data->status_id == 7) && auth()->user()->hasRole('produsen'))
                                                <button type="submit" id="btn-submit{{ $k }}"
                                                    class="btn btn-success"> Tampilkan
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            @endif

            {{-- @foreach ($tables as $table)
        <h2>{{ $table['table']->namatabel }}</h2>
        @foreach ($table['headers'] as $key => $header)
        <label for="dropdown_{{ $header->id }}">{{ $header->header }}</label>
        <select id="dropdown_{{ $header->id }}" class="form-select select2" aria-label="Default select example">
            @foreach ($table['rows_grafik'][$header->id] as $row)
            <option value="{{ $row->isi }}">{{ $row->isi }}</option>
            @endforeach
        </select>
        @endforeach
        @endforeach --}}
        </div><!-- End Default Tabs -->
        @include('pages.contents.produsen.pengumpulan.edit-data_upload')
        @include('pages.contents.produsen.pengumpulan.edit-data_edit')
        @include('pages.contents.produsen.pengumpulan.edit-data_hapus')
        @include('pages.contents.produsen.pengumpulan.edit-data_tambah')
        @include('pages.contents.produsen.pengumpulan.edit-data_hapus_berkas')

    </section>
    {{-- @endif --}}
    {{-- <section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Detail Data</h5>

                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-2 col-form-label">Nama Data</label>
                        <div class="col-sm-10">
                            <input id="nama_data" name="nama_data" type="text" class="form-control"
                                value="{{$data->nama_data}}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Jenis Data</label>
                        <div class="col-sm-10">
                            <select id="jenis_data" name="jenis_data" class="form-select" disabled>
                                <option selected value="{{$data->jenis_data}}">{{$data->jenis_data}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" id="opd_id">Produsen Data(PIC)</label>
                        <div class="col-sm-10">
                            <select id="opd_id" name="opd_id" class="form-select" disabled>
                                <option selected value="{{$data->opd_id}}">{{$data->opd->nama_opd}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Sumber Data</label>
                        <div class="col-sm-10">
                            <select id="sumber_data" name="sumber_data" class="form-select" aria-label="Sumber Data"
                                disabled>
                                <option selected value="{{$data->sumber_data}}">{{$data->sumber_data}}</option>
                            </select>
                        </div>
                    </div>

                    <a href="{{url()->previous('d_' . auth()->user()->role->name)}}"
                        class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>

                </div>
            </div>

        </div>


    </div>
</section> --}}



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check if 'tab' parameter is present in the URL
            const activeTab = '{{ session('active_tab') }}';
            console.log(activeTab);
            if (activeTab === 'grafik') {
                // Activate the 'grafik' tab
                document.querySelector('#profile-tab').click();
            }
        });
    </script>
    @foreach ($existingData as $item)
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const chartTypeSelect = document.getElementById('chartTypeSelect');
                const chartElement = document.querySelector("#chart");

                const names = {!! $kategori !!};
                const data = {!! $axis_y !!};
                const name_y = "{!! $item->axis_y_header !!}";
                const categories = {!! $axis_x !!};
                const seriesData{{ $item->id }} = @json($seriesData[$item->id] ?? []);
                const seriesDataLine{{ $item->id }} = @json($seriesDataLine[$item->id] ?? []);

                let myChart = null;

                function createChart(type) {
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

                // Initial chart creation
                createChart(chartTypeSelect.value);

                // Event listener for chart type change
                chartTypeSelect.addEventListener('change', function() {
                    createChart(this.value); // Create and render a new chart based on the selected type
                });
            });
        </script>
    @endforeach
@endsection

{{-- @push('js')
<script>
    $(document).ready(function() {
    // Mendeteksi submit form
    $('form[id^="grafikForm"]').submit(function(e) {
    e.preventDefault();

    var form = $(this); // Mengambil form yang sedang di-submit
    var btnSubmit = form.find('.btn-submit'); // Mengambil tombol Submit terkait

    // Mengirim form menggunakan AJAX
    $.ajax({
    type: 'POST',
    url: form.attr('action'),
    data: form.serialize(),
    success: function(response) {
    // Mengaktifkan Tab Grafik
    $('#profile-tab').tab('show');
    }
    });
    });
    });
</script>
@endpush --}}

@push('js')
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Update cell data
            $('.btn-update').click(function() {
                let tableId = $(this).data('table');
                let rowIndex = $(this).data('row');
                let row = $('tr:eq(' + (rowIndex + 1) + ')');
                let cells = row.find('.editable-cell');

                cells.each(function() {
                    let headerId = $(this).data('header');
                    let cellId = $(this).data('cell');
                    let cellData = $(this).text().trim();

                    // Perform an AJAX request to update the cell data
                    $.ajax({
                        url: '/data/update-cell',
                        type: 'POST',
                        data: {
                            table_id: tableId,
                            row_index: rowIndex,
                            header_id: headerId,
                            cell_id: cellId,
                            cell_data: cellData
                        },
                        success: function(response) {
                            console.log('Cell data updated successfully');
                        },
                        error: function(error) {
                            console.log('An error occurred while updating cell data');
                        }
                    });
                });
            });

            // Delete row
            $('.btn-delete').click(function() {
                let tableId = $(this).data('table');
                let rowIndex = $(this).data('row');
                let row = $('tr:eq(' + (rowIndex + 1) + ')');

                // Perform an AJAX request to delete the row
                $.ajax({
                    url: '/data/delete-row',
                    type: 'POST',
                    data: {
                        table_id: tableId,
                        row_index: rowIndex
                    },
                    success: function(response) {
                        console.log('Row deleted successfully');
                        row.remove();
                    },
                    error: function(error) {
                        console.log('An error occurred while deleting the row');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var activeTab = '{{ session('active_tab') }}';
            if (activeTab === 'profile') {
                // Tambahkan kode untuk mengatur tab grafik sebagai aktif di sini
            }
        });
    </script>

    <script>
    $(function() {
        const districtsList = @json($districts ?? []);
        const defaultMadiunDistricts = (districtsList && districtsList.length > 0) ? districtsList : [
            {code: '351901', name: 'Kebonsari'},
            {code: '351902', name: 'Geger'},
            {code: '351903', name: 'Dagangan'},
            {code: '351904', name: 'Kare'},
            {code: '351905', name: 'Gemarang'},
            {code: '351906', name: 'Wungu'},
            {code: '351907', name: 'Madiun'},
            {code: '351908', name: 'Jiwan'},
            {code: '351909', name: 'Balerejo'},
            {code: '351910', name: 'Sawahan'},
            {code: '351911', name: 'Mejayan'},
            {code: '351912', name: 'Saradan'},
            {code: '351913', name: 'Pilangkenceng'},
            {code: '351914', name: 'Wonoasri'}
        ];

        let existingGridData = null;
        @if(!empty($data->data_grid_json))
            try {
                existingGridData = JSON.parse(@json($data->data_grid_json));
            } catch(e){}
        @elseif(!empty($tables) && count($tables) > 0)
            @php
                $firstTable = $tables[0];
                $tHeaders = $firstTable['headers']->sortBy('urutan_menyamping')->pluck('header')->toArray();
                $tRows = [];
                foreach ($firstTable['rows']->values() as $r) {
                    $rowVals = [];
                    foreach ($firstTable['headers']->sortBy('urutan_menyamping')->values() as $h) {
                        $cell = $r->firstWhere('id_header', $h->id);
                        $rowVals[] = $cell ? $cell->isi : '';
                    }
                    $tRows[] = $rowVals;
                }
            @endphp
            existingGridData = {
                headers: @json($tHeaders),
                rows: @json($tRows)
            };
        @endif

        function renderGridFromData(headers, rows) {
            let theadHtml = '<tr>';
            headers.forEach((h, idx) => {
                theadHtml += '<th><input type="text" class="form-control form-control-sm fw-bold border-0 bg-transparent text-white grid-header-input" value="' + h + '"></th>';
            });
            theadHtml += '<th style="width: 50px;">Aksi</th></tr>';
            $('#gridThead').html(theadHtml);

            let tbodyHtml = '';
            rows.forEach((r, rIdx) => {
                tbodyHtml += '<tr>';
                r.forEach((c, cIdx) => {
                    let isCodeCol = (cIdx === 0);
                    tbodyHtml += '<td><input type="text" class="form-control form-control-sm grid-cell-input ' + (isCodeCol ? 'bg-light font-monospace' : '') + '" value="' + (c || '') + '"></td>';
                });
                tbodyHtml += '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 btn-del-row"><i class="bi bi-x"></i></button></td></tr>';
            });
            $('#gridTbody').html(tbodyHtml);
        }

        function buildDefaultStructure() {
            let level = $('#gridLevelData').val();
            let periode = $('#gridPeriodeData').val();
            let headers = ['Kode Referensi', 'Wilayah / Kategori'];
            let currentYear = {{ $data->tahun ?? date('Y') }};

            if (periode === 'Bulanan') {
                headers.push('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
            } else if (periode === 'Triwulan') {
                headers.push('Triwulan I', 'Triwulan II', 'Triwulan III', 'Triwulan IV');
            } else if (periode === 'Semester') {
                headers.push('Semester I', 'Semester II');
            } else {
                headers.push(String(currentYear - 2), String(currentYear - 1), String(currentYear));
            }

            let rows = [];
            if (level === 'Kabupaten') {
                let row = ['35.19', 'Kabupaten Madiun'];
                for (let i = 2; i < headers.length; i++) row.push('');
                rows.push(row);
            } else if (level === 'Kecamatan') {
                defaultMadiunDistricts.forEach(d => {
                    let row = [d.code, d.name];
                    for (let i = 2; i < headers.length; i++) row.push('');
                    rows.push(row);
                });
            } else {
                let row = ['-', 'Desa / Wilayah'];
                for (let i = 2; i < headers.length; i++) row.push('');
                rows.push(row);
            }

            renderGridFromData(headers, rows);
        }

        if (existingGridData && existingGridData.headers && existingGridData.headers.length > 0) {
            renderGridFromData(existingGridData.headers, existingGridData.rows);
        } else {
            buildDefaultStructure();
        }

        $('#btnApplyStructure').on('click', function() {
            if (confirm('Terapkan struktur baru? Data sel yang belum tersimpan mungkin akan diatur ulang.')) {
                buildDefaultStructure();
            }
        });

        $('#btnAddColumn').on('click', function() {
            let colName = prompt('Masukkan nama kolom baru:', 'Kolom Baru');
            if (colName) {
                $('#gridThead tr').find('th:last').before('<th><input type="text" class="form-control form-control-sm fw-bold border-0 bg-transparent text-white grid-header-input" value="' + colName + '"></th>');
                $('#gridTbody tr').each(function() {
                    $(this).find('td:last').before('<td><input type="text" class="form-control form-control-sm grid-cell-input" value=""></td>');
                });
            }
        });

        $('#btnAddRow').on('click', function() {
            let colCount = $('#gridThead tr th').length - 1;
            let rowHtml = '<tr>';
            for (let i = 0; i < colCount; i++) {
                rowHtml += '<td><input type="text" class="form-control form-control-sm grid-cell-input" value=""></td>';
            }
            rowHtml += '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 btn-del-row"><i class="bi bi-x"></i></button></td></tr>';
            $('#gridTbody').append(rowHtml);
        });

        $(document).on('click', '.btn-del-row', function() {
            $(this).closest('tr').remove();
        });

        function collectGridPayload() {
            let headers = [];
            $('#gridThead tr th').each(function(idx) {
                let input = $(this).find('input.grid-header-input');
                if (input.length > 0) {
                    headers.push(input.val() || 'Kolom ' + (idx + 1));
                }
            });

            let rows = [];
            $('#gridTbody tr').each(function() {
                let rowVals = [];
                $(this).find('input.grid-cell-input').each(function() {
                    rowVals.push($(this).val());
                });
                if (rowVals.length > 0) {
                    rows.push(rowVals);
                }
            });

            return {
                headers: headers,
                rows: rows,
                level_data: $('#gridLevelData').val(),
                periode_data: $('#gridPeriodeData').val(),
                nama_tabel: '{{ addslashes($data->nama_data) }}'
            };
        }

        function saveGridData(callback) {
            let payload = collectGridPayload();
            payload._token = '{{ csrf_token() }}';

            $.ajax({
                url: '{{ route("pengumpulan.simpan-grid", $data->id) }}',
                type: 'POST',
                data: payload,
                success: function(resp) {
                    $('#gridSaveAlert').removeClass('d-none alert-danger').addClass('alert-success');
                    $('#gridSaveAlertMsg').text('Data berhasil disimpan sementara sebagai draft!');
                    setTimeout(function() { $('#gridSaveAlert').addClass('d-none'); }, 4000);
                    if (typeof callback === 'function') callback();
                },
                error: function(err) {
                    $('#gridSaveAlert').removeClass('d-none alert-success').addClass('alert-danger');
                    $('#gridSaveAlertMsg').text('Gagal menyimpan data grid.');
                }
            });
        }

        $('#btnSimpanGridDraft').on('click', function() {
            saveGridData();
        });

        $('#btnAjukanVerifikasi').on('click', function() {
            Swal.fire({
                title: 'Ajukan ke Verifikasi Walidata?',
                text: 'Pastikan data telah diisi dengan benar dan lengkap sesuai standar data & metadata.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ajukan Verifikasi',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#198754'
            }).then((result) => {
                if (result.isConfirmed) {
                    saveGridData(function() {
                        $.ajax({
                            url: '{{ route("siap-verifikasi", $data->id) }}',
                            type: 'PATCH',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(res) {
                                if (res.ok) {
                                    Swal.fire('Berhasil!', res.message, 'success').then(() => {
                                        window.location.href = '{{ route("pengumpulan.produsen") }}';
                                    });
                                } else {
                                    Swal.fire('Perhatian', res.message, 'warning');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Gagal mengajukan verifikasi', 'error');
                            }
                        });
                    });
                }
            });
        });
    });
    </script>
@endpush





{{-- <div class="table-responsive">
    <table class="table table-stripped">
        <thead>
            <tr>
                <th>#</th>
                {{-- <th>Tahun</th>
                <th>Nilai</th>
<th>Berkas</th>
@if (auth()->user()->hasAnyRole('produsen'))
    <th>Aksi</th>
@endif
</tr>
</thead>
<tbody>
    @foreach ($get_berkas as $keys => $berkas1)
        <tr>
            <td>{{ $keys + 1 }}</td>
            <td><a href="{{ Storage::url($berkas1->path) }}"
                    target="_new">{{ $berkas1->name ?? '-' }} <i class="bi bi-link"></i></a>
            </td>
            @if (auth()->user()->hasAnyRole('produsen'))
                <td><a href="javascript:void(0)" class="btn btn-outline-danger btn-sm"
                        onclick='$("#modal-hapus").modal("show");
                                                    $("#id_berkas").val("{{ $berkas1->id }}");
                                                    $("#id_visualdata").val("{{ $berkas1->visual_id }}");
                                                    $("#tahunhapus").val("{{ $data->tahun }}");'>
                        <i class="bi bi-trash"></i> Hapus</a></td>
            @endif
        </tr>
    @endforeach
</tbody>
</table>
</div> --}}
