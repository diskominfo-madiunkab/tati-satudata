@extends('pages.main.layout')
@section('content')

    <div class="pagetitle">
        <h1>Detail Data: <em>{{ $data->nama_data }}</em></h1>
        {{-- <h1>Detail Data: <em>{{$data->status_id}}</em></h1> --}}
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"></h5>

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true"><i
                                        class="bi bi-card-list"></i> Informasi Data
                                </button>
                            </li>
                            @if ($data->status_id != \App\Models\Data::STATUS_SETUJU)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="metadata" data-bs-toggle="tab"
                                        data-bs-target="#standartab" type="button" role="tab"
                                        aria-controls="profile"><i class="bi bi-info-circle"></i>
                                        Standar Data
                                    </button>
                                </li>
                            @endif


                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#historytab"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false"><i
                                        class="bi bi-clock-history"></i> Riwayat Data
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content pt-2">
                            <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row mb-3" style="padding-top:20px">
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
                                            value="{{ $data->jenis_data }}" disabled>
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
                                            <option selected value="{{ $data->opd_id }}">{{ $data->opd->nama_opd }}</option>
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
                                            aria-label="Default select example" value="{{ $data->sumber_data }}" disabled>
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
                                        <input id="nama_data" name="nama_data" type="text" class="form-control"
                                            value="{{ $data->tahun ?? '-' }}" disabled>
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

                                <a href="{{ url()->previous('d_' . auth()->user()->role->name) }}"
                                    class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                            </div>
                            <div class="tab-pane" id="berkastab">
                                {{-- <div class="table-responsive">
                                <table class="table table-stripped datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Tgl. Unggah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($berkasData as $berkas)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td><a href="{{$berkas['previewUrl']}}" target="_new">{{$berkas['name'] ??
                                                    '-'}} <i class="bi bi-link"></i></a></td>
                                            <td>{{$berkas['created_at'] ? $berkas['created_at']->format('d/m/Y H:i') :
                                                '-'}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> --}}
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="table-tab" data-bs-toggle="tab"
                                            data-bs-target="#table" type="button" role="tab" aria-controls="table"
                                            aria-selected="true">Tabel</button>
                                    </li>
                                    @if ($tables)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="grafik-tab" data-bs-toggle="tab"
                                                data-bs-target="#grafik" type="button" role="tab"
                                                aria-controls="grafik" aria-selected="false">Grafik</button>
                                        </li>
                                    @endif
                                </ul>
                                <div class="tab-content pt-2" id="myTabContent">
                                    <div class="tab-pane fade show active" id="table" role="tabpanel"
                                        aria-labelledby="table-tab">
                                        <div class="col-lg-12">
                                            @if (auth()->user()->hasAnyRole('produsen') && ($data->status_id == 10 || $data->status_id == 7))
                                                <div class="card">
                                                    <div class="row">
                                                        <div class="card-body">
                                                            <div class="card-title">Berkas Data
                                                                <h6>Sebelum mengunggah berkas harap unduh template untuk
                                                                    mengunggah berkas</h6>
                                                            </div>

                                                            <form action="{{ route('visual.data.store') }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="number" value="{{ $data->id }}"
                                                                    name="id_data" id="id_data" hidden>
                                                                <input type="file" name="berkas" id="berkas"
                                                                    class="form-control"
                                                                    style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
                                                                    required>

                                                                <div class="footer">
                                                                    <button type="submit" class="btn btn-primary"
                                                                        id="store">Tambah</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title"> List Berkas </h5>
                                                    @if (auth()->user()->hasAnyRole('produsen'))
                                                        {{-- <a href="javascript:void(0)" class="btn btn-success mb-2"
                                                    id="btn-create-post">Tambah</a>
                                                --}}
                                                    @endif
                                                    <div class="table-responsive">
                                                        <table class="table table-stripped">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    {{-- <th>Tahun</th>
                                                                <th>Nilai</th> --}}
                                                                    <th>Berkas</th>
                                                                    @if (auth()->user()->hasAnyRole('produsen'))
                                                                        {{-- <th>Aksi</th> --}}
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($get_berkas as $keys => $berkas1)
                                                                    <tr>
                                                                        <td>{{ $keys + 1 }}</td>
                                                                        {{-- <td>
                                                                    @php
                                                                    dd(Storage::url($berkas1->path));
                                                                    @endphp
                                                                </td> --}}
                                                                        <td><a href="{{ Storage::url($berkas1->path) }}"
                                                                                target="_new">{{ $berkas1->name ?? '-' }}
                                                                                <i class="bi bi-link"></i>
                                                                            </a></td>
                                                                        @if (auth()->user()->hasAnyRole('produsen') && $data->status_id !== '8')
                                                                            {{-- <td><a href="javascript:void(0)"
                                                                        class="btn btn-outline-danger btn-sm"
                                                                        onclick='$("#modal-hapus").modal("show");
                                                                                    $("#id_berkas").val("{{ $berkas1->id }}");
                                                                                    $("#id_visualdata").val("{{ $berkas1->visual_id }}");
                                                                                    $("#tahunhapus").val("{{ $data->tahun }}");'>
                                                                        <i class="bi bi-trash"></i> Hapus</a></td> --}}
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
                                                                                        @if (auth()->user()->hasAnyRole('produsen'))
                                                                                            {{-- <td>
                                                                        <button type="button"
                                                                            class="btn btn-primary btn-update"
                                                                            data-table="{{ $table->id }}"
                                                                            data-row="{{ $rowIndex }}">Update</button>
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-delete"
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

                                                    {{-- <a href="{{url()->previous('d_' . auth()->user()->role->name)}}"
                                                    --}} <a
                                                        href="{{ auth()->user()->hasAnyRole('produsen') ? '/data_produsen/pengumpulan' : '/data_walidata/pengumpulan' }}"
                                                        class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
                                                        Kembali</a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($tables)
                                        <div class="tab-pane fade" id="grafik" role="tabpanel"
                                            aria-labelledby="grafik-tab">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    @foreach ($tables as $table)
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Grafik Data {{ $data->nama_data }}
                                                                </h5>
                                                                <div>
                                                                    <label for="chartTypeSelect">Pilih Jenis
                                                                        Grafik:</label>
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
                                                                    action="{{ route('chart.storeDataByFilter') }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="text" name="id_data" id="id_data"
                                                                        value="{{ $data->id }}" hidden>
                                                                    <input type="text" name="id_table" id="id_table"
                                                                        value="{{ $table['table']['id'] }}" hidden>
                                                                    <h5 class="card-title">Filter</h5>

                                                                    <label
                                                                        for="dropdown_axis_x_{{ $table['table']['id'] }}">Axis
                                                                        X</label>
                                                                    <select name="axis_x"
                                                                        id="dropdown_axis_x_{{ $k }}"
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

                                                                    <label
                                                                        for="dropdown_axis_y_{{ $table['table']['id'] }}">Axis
                                                                        Y</label>
                                                                    <span
                                                                        class="badge border-warning border-1 text-warning">*Inputan
                                                                        berupa nilai</span>
                                                                    <select name="axis_y"
                                                                        id="dropdown_axis_y_{{ $k }}"
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

                                                                    <label
                                                                        for="dropdown_category_{{ $table['table']['id'] }}">Kategori</label>
                                                                    <select name="kategori"
                                                                        id="dropdown_category_{{ $k }}"
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
                                                                    @if (auth()->user()->hasAnyRole('produsen') && ($data->status_id == 10 || $data->status_id == 7))
                                                                        <button type="submit"
                                                                            id="btn-submit{{ $k }}"
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
                                </div>
                                @if ($data->relationLoaded('verifikasi'))
                                    <section class="section">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="card-title">Revisi Berkas Data</div>
                                                        <div class="table-responsive">
                                                            <table class="table table-stripped datatable">
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
                                                                                    target="_new">{{ $berkas['name'] ?? '-' }}
                                                                                    <i class="bi bi-link"></i></a>
                                                                            </td>
                                                                            <td>
                                                                                <h5>
                                                                                    @if ($v === null)
                                                                                        <span
                                                                                            class="badge border-info text-info">Belum
                                                                                            Diverifikasi</span>
                                                                                    @elseif($v->accepted == 0)
                                                                                        <span
                                                                                            class="badge border-danger text-danger">Revisi</span>
                                                                                    @elseif($v->accepted == 1)
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
                                                                                        <i class="bi bi-trash"></i>
                                                                                        Hapus</a></td>
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
                            </div>
                            <div class="tab-pane" id="standartab">
                                <div class="row mb-3">
                                    <label for="kode" class="col-sm-2 col-form-label">Kode</label>
                                    <div class="col-sm-10">
                                        <textarea id="kode" name="kode" class="form-control" placeholder="kode Standar Data" readonly>{{ old('kode', optional($data->standar)->kode) }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                                    <div class="col-sm-10">
                                        <textarea id="konsep" name="konsep" class="form-control" placeholder="Konsep Standar Data" readonly>{{ old('konsep', optional($data->standar)->konsep) }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="definisi" class="col-sm-2 col-form-label">Definisi</label>
                                    <div class="col-sm-10">
                                        <textarea id="definisi" name="definisi" class="form-control" placeholder="Definisi Standar Data" readonly>{{ old('definisi', optional($data->standar)->definisi) }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="klasifikasi" class="col-sm-2 col-form-label">Klasifikasi</label>
                                    <div class="col-sm-10">
                                        <textarea id="klasifikasi" name="klasifikasi" class="form-control" placeholder="Klasifikasi Standar Data" readonly>{{ old('klasifikasi', optional($data->standar)->klasifikasi) }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                                    <div class="col-sm-10">
                                        <textarea id="ukuran" name="ukuran" class="form-control" placeholder="Ukuran Standar Data" readonly>{{ old('ukuran', optional($data->standar)->ukuran) }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                                    <div class="col-sm-10">
                                        <textarea id="satuan" name="satuan" class="form-control" placeholder="Satuan Standar Data" readonly>{{ old('satuan', optional($data->standar)->satuan) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="metadatatab">
                                @if (strtolower($data->jenis_data) == 'indikator')
                                    <div class="row mb-3">
                                        <label for="nama" class="col-sm-2 col-form-label">Nama Indikator</label>
                                        <div class="col-sm-10">
                                            <input id="nama" name="nama" type="text"
                                                class="form-control {{ isset($nama) ? ($nama->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                placeholder="Nama Indikator" value="{{ old('nama', $data->nama_data) }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                                        <div class="col-sm-10">
                                            <input id="konsep" name="konsep" type="text"
                                                class="form-control {{ isset($konsep) ? ($konsep->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                placeholder="Konsep"
                                                value="{{ old('ukuran', optional($data->indikator)->konsep ?? optional($data->standar)->konsep) }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Definisi" class="col-sm-2 col-form-label">Definisi</label>
                                        <div class="col-sm-10">
                                            <textarea name="definisi"
                                                class="form-control {{ isset($definisi) ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                style="height: 100px" spellcheck="false" placeholder="Definisi" readonly>{{ old('definisi', optional($data->indikator)->definisi ?? optional($data->standar)->definisi) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="interpretasi" class="col-sm-2 col-form-label">Interpretasi</label>
                                        <div class="col-sm-10">
                                            <input id="interpretasi" name="interpretasi" type="text"
                                                class="form-control {{ isset($interpretasi) ? ($interpretasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                placeholder="Interpretasi"
                                                value="{{ old('interpretasi', optional($data->indikator)->interpretasi) }}"
                                                readonly>
                                        </div>
                                    </div>

                                    @php
                                        $isImage =
                                            optional($data->indikator)->metode &&
                                            Str::startsWith(optional($data->indikator)->metode, 'public/');
                                    @endphp
                                    <div class="row mb-3">
                                        <label for="metode" class="col-sm-2 col-form-label">Metode / Rumus
                                            Perhitungan</label>
                                        <div class="col-sm-6">
                                            @if ($isImage)
                                                <img class="img-fluid rounded" height="250px" width="250px"
                                                    src="{{ Storage::url(optional($data->indikator)->metode) }}">
                                            @else
                                                <textarea name="metode" id="metode" class="form-control" disabled>{{ optional($data->indikator)->metode }}</textarea>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="ukuran" class="col-sm-2 col-form-label">Ukuran</label>
                                        <div class="col-sm-10">
                                            <input id="ukuran" name="ukuran" type="text"
                                                class="form-control {{ isset($ukuran) ? ($ukuran->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                placeholder="Ukuran"
                                                value="{{ old('ukuran', optional($data->indikator)->ukuran ?? optional($data->standar)->ukuran) }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                                        <div class="col-sm-10">
                                            <input id="satuan" name="satuan" type="text"
                                                class="form-control {{ isset($satuan) ? ($satuan->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                placeholder="Satuan"
                                                value="{{ old('satuan', optional($data->indikator)->satuan ?? optional($data->standar)->satuan) }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="klasifikasi_penyajian" class="col-sm-2 col-form-label">Klasifikasi
                                            Penyajian</label>
                                        <div class="col-sm-10">
                                            <textarea name="klasifikasi_penyajian"
                                                class="form-control {{ isset($klasifikasi_penyajian) ? ($klasifikasi_penyajian->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                style="height: 100px" spellcheck="false" placeholder="Klasifikasi Penyajian" readonly>{{ old('klasifikasi_penyajian', optional($data->indikator)->klasifikasi_penyajian) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="komposit" class="col-sm-2 col-form-label">Apakah indikator
                                            komposit?</label>
                                        <div class="col-sm-10">
                                            <div class="form-check"><input class="form-check-input" type="radio"
                                                    name="komposit" id="komposit1" value="1"
                                                    {{ old('komposit', optional($data->indikator)->komposit) == 1 ? 'checked' : '' }}
                                                    disabled>
                                                <label class="form-check-label" for="gridRadios1"> Ya </label>
                                            </div>
                                            <div class="form-check"><input class="form-check-input" type="radio"
                                                    name="komposit" id="komposit2" value="0"
                                                    {{ old('komposit', optional($data->indikator)->komposit) == 0 ||
                                                    empty(old('komposit', optional($data->indikator)->komposit))
                                                        ? 'checked'
                                                        : '' }}
                                                    disabled> <label class="form-check-label" for="gridRadios1"> Tidak
                                                </label></div>
                                        </div>
                                    </div>

                                    <section class="komposit-section">
                                        <div class="row mb-3">
                                            <label for="publikasi_indikator_pembangun"
                                                class="col-sm-2 col-form-label">Publikasi
                                                Ketersediaan
                                                Indikator Pembangun</label>
                                            <div class="col-sm-10">
                                                <input id="publikasi_indikator_pembangun"
                                                    name="publikasi_indikator_pembangun" type="text"
                                                    class="form-control {{ isset($publikasi_indikator_pembangun) ? ($publikasi_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                    value="{{ old('publikasi_indikator_pembangun', optional($data->indikator)->publikasi_indikator_pembangun) }}"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="nama_indikator_pembangun" class="col-sm-2 col-form-label">Nama
                                                Indikator
                                                Pembangun</label>
                                            <div class="col-sm-10">
                                                <textarea name="nama_indikator_pembangun"
                                                    class="form-control {{ isset($nama_indikator_pembangun) ? ($nama_indikator_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                    style="height: 100px" spellcheck="false" placeholder="Nama Indikator Pembangun" readonly>{{ old('nama_indikator_pembangun', optional($data->indikator)->nama_indikator_pembangun) }}</textarea>
                                                <small class="text-muted">Daftar nama dipisah menggunakan enter.</small>
                                            </div>
                                        </div>
                                    </section>
                                    <section class="no-komposit-section">
                                        <div class="row mb-3">
                                            <label for="nama_variabel_pembangun" class="col-sm-2 col-form-label">Nama
                                                Variabel
                                                Pembangun</label>
                                            <div class="col-sm-10">
                                                <textarea name="nama_variabel_pembangun"
                                                    class="form-control {{ isset($nama_variabel_pembangun) ? ($nama_variabel_pembangun->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                    style="height: 100px" spellcheck="false" placeholder="Nama Variabel Pembangun" readonly>{{ old('nama_variabel_pembangun', optional($data->indikator)->nama_variabel_pembangun) }}</textarea>
                                                <small class="text-muted">Daftar nama dipisah menggunakan enter.</small>
                                            </div>
                                        </div>
                                    </section>

                                    <div class="row mb-3">
                                        <label for="level_estimasi" class="col-sm-2 col-form-label">Level Estimasi</label>
                                        <div class="col-sm-10">
                                            <select
                                                class="form-control form-select {{ isset($level_estimasi) ? ($level_estimasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                name="level_estimasi" id="level_estimasi" disabled>
                                                <option value="nasional"
                                                    {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'nasional' ||
                                                    empty(old('level_estimasi', optional($data->indikator)->level_estimasi))
                                                        ? 'selected'
                                                        : '' }}>
                                                    Nasional
                                                </option>
                                                <option value="provinsi"
                                                    {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'provinsi' ? 'selected' : '' }}>
                                                    Provinsi
                                                </option>
                                                <option value="kabupaten"
                                                    {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'kabupaten' ? 'selected' : '' }}>
                                                    Kabupaten/kota</option>
                                                <option value="perangkat_daerah"
                                                    {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'perangkat_daerah' ? 'selected' : '' }}>
                                                    Perangkat Daerah</option>
                                                <option value="kecamatan"
                                                    {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'kecamatan' ? 'selected' : '' }}>
                                                    Kecamatan
                                                </option>
                                                <option value="kelurahan"
                                                    {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'kelurahan' ? 'selected' : '' }}>
                                                    Desa/Kelurahan</option>
                                                <option value="rt"
                                                    {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'rt' ? 'selected' : '' }}>
                                                    Rumah Tangga
                                                </option>
                                                <option value="individu"
                                                    {{ old('level_estimasi', optional($data->indikator)->level_estimasi) == 'individu' ? 'selected' : '' }}>
                                                    Individu
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="umum1" class="col-sm-2 col-form-label">Apakah kolom ini dapat
                                            diakses
                                            umum</label>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="umum"
                                                    id="umum1" value="1"
                                                    {{ old('umum', optional($data->indikator)->umum) == 1 || empty(old('umum', optional($data->indikator)->umum))
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
                                                    {{ old('umum', optional($data->indikator)->umum) == 0 ? 'checked' : '' }}
                                                    disabled>
                                                <label class="form-check-label" for="umum2">
                                                    Tidak
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row mb-3">
                                        <label for="nama" class="col-sm-2 col-form-label">Nama Variabel</label>
                                        <div class="col-sm-10">
                                            <input id="nama" name="nama" type="text"
                                                class="form-control {{ isset($nama) ? ($nama->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                placeholder="Nama Variabel" value="{{ old('nama', $data->nama_data) }}"
                                                readonly>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="alias" class="col-sm-2 col-form-label">Alias</label>
                                        <div class="col-sm-10">
                                            <input id="alias" name="alias" type="text"
                                                class="form-control {{ isset($alias) ? ($alias->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                placeholder="Alias"
                                                value="{{ old('alias', optional($data->variabel)->alias) }}" readonly>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="konsep" class="col-sm-2 col-form-label">Konsep</label>
                                        <div class="col-sm-10">
                                            <textarea name="konsep"
                                                class="form-control {{ isset($konsep) ? ($konsep->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                style="height: 100px" spellcheck="false" placeholder="Konsep" readonly>{{ old('konsep', optional($data->variabel)->konsep ?? optional($data->standar)->konsep) }}</textarea>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="definisi" class="col-sm-2 col-form-label">Definisi</label>
                                        <div class="col-sm-10">
                                            <input id="definisi" name="definisi" type="text"
                                                class="form-control {{ isset($definisi) ? ($definisi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                placeholder="Definisi"
                                                value="{{ old('definisi', optional($data->variabel)->definisi ?? optional($data->standar)->definisi) }}"
                                                readonly>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="referensi_pemilihan" class="col-sm-2 col-form-label">Referensi
                                            Pemilihan</label>
                                        <div class="col-sm-10">
                                            <input id="referensi_pemilihan" name="referensi_pemilihan" type="text"
                                                class="form-control {{ isset($referensi_pemilihan) ? ($referensi_pemilihan->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                placeholder="Referensi Pemilihan"
                                                value="{{ old('referensi_pemilihan', optional($data->variabel)->referensi_pemilihan) }}"
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
                                                value="{{ old('referensi_waktu', optional($data->variabel)->referensi_waktu) }}"
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
                                                    {{ old('tipe_data', optional($data->variabel)->tipe_data) == 'integer' ||
                                                    empty(optional($data->variabel)->tipe_data)
                                                        ? 'selected'
                                                        : '' }}
                                                    disabled>Integer</option>
                                                <option value="float"
                                                    {{ old('tipe_data', optional($data->variabel)->tipe_data) == 'float' ? 'selected' : '' }}>
                                                    Float</option>
                                                <option value="char"
                                                    {{ old('tipe_data', optional($data->variabel)->tipe_data) == 'char' ? 'selected' : '' }}>
                                                    Char</option>
                                                <option value="string"
                                                    {{ old('tipe_data', optional($data->variabel)->tipe_data) == 'string' ? 'selected' : '' }}>
                                                    String</option>
                                                <option value="array"
                                                    {{ old('tipe_data', optional($data->variabel)->tipe_data) == 'array' ? 'selected' : '' }}>
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
                                                value="{{ old('ukuran', optional($data->variabel)->ukuran ?? optional($data->standar)->ukuran) }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                                        <div class="col-sm-10">
                                            <input id="satuan" name="satuan" type="text"
                                                class="form-control {{ isset($satuan) ? ($satuan->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                placeholder="Satuan"
                                                value="{{ old('satuan', optional($data->variabel)->satuan ?? optional($data->standar)->satuan) }}"
                                                readonly>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="klasifikasi_isian" class="col-sm-2 col-form-label">Klasifikasi
                                            Isian</label>
                                        <div class="col-sm-10">
                                            <textarea name="klasifikasi_isian"
                                                class="form-control {{ isset($klasifikasi_isian) ? ($klasifikasi_isian->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                style="height: 100px" spellcheck="false" placeholder="Klasifikasi Isian" readonly>{{ old('klasifikasi_isian', optional($data->variabel)->klasifikasi_isian ?? optional($data->standar)->klasifikasi) }}</textarea>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="aturan_validasi" class="col-sm-2 col-form-label">Aturan
                                            Validasi</label>
                                        <div class="col-sm-10">
                                            <textarea name="aturan_validasi"
                                                class="form-control {{ isset($aturan_validasi) ? ($aturan_validasi->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                style="height: 100px" spellcheck="false" placeholder="Aturan Validasi" readonly>{{ old('aturan_validasi', optional($data->variabel)->aturan_validasi) }}</textarea>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="kalimat_pertanyaan" class="col-sm-2 col-form-label">Kalimat
                                            Pertanyaan</label>
                                        <div class="col-sm-10">
                                            <textarea name="kalimat_pertanyaan"
                                                class="form-control {{ isset($kalimat_pertanyaan) ? ($kalimat_pertanyaan->accepted ? 'is-valid' : 'is-invalid') : '' }}"
                                                style="height: 100px" spellcheck="false" placeholder="Kalimat Pertanyaan" readonly>{{ old('kalimat_pertanyaan', optional($data->variabel)->kalimat_pertanyaan) }}</textarea>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="umum1" class="col-sm-2 col-form-label">Apakah kolom ini dapat
                                            diakses
                                            umum</label>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="umum"
                                                    id="umum1" value="1"
                                                    {{ old('umum', optional($data->variabel)->umum) == 1 || empty(old('umum', optional($data->variabel)->umum))
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
                                                    {{ old('umum', optional($data->variabel)->umum) == 0 ? 'checked' : '' }}
                                                    disabled>
                                                <label class="form-check-label" for="umum2">
                                                    Tidak
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane" id="historytab">
                                @foreach ($histories as $dt)
                                    <nav style="--bs-breadcrumb-divider: '>';">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a
                                                    href="#">{{ \Carbon\Carbon::parse($dt->created_at)->format('d/m/Y H:i') }}</a>
                                            </li>
                                            <li class="breadcrumb-item"><a href=""><i
                                                        class="bi bi-clock-history"></i> {{ $dt->name }}</a></li>
                                            <li class="breadcrumb-item active">{{ $dt->description }} :
                                                "{{ $dt->nama_data }}"
                                            </li>
                                        </ol>
                                    </nav>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>


        </div>
    </section>
@endsection

@section('js')
    <script>
        @if ($data->jenis_data === 'indikator')
            $(function() {
                $('section.no-komposit-section').hide();
                $('section.komposit-section').hide();
                $('input[name="komposit"]').change(function() {
                    if ($('input[name="komposit"]:checked').val() == 1) {
                        $('section.komposit-section').show();
                        $('section.no-komposit-section').hide();
                    } else {
                        $('section.komposit-section').hide();
                        $('section.no-komposit-section').show();
                    }
                }).trigger('change');
            })
        @endif
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check if 'tab' parameter is present in the URL
            const activeTab = '{{ session('active_tab') }}';
            console.log(activeTab);
            if (activeTab === 'grafik') {
                // Activate the 'grafik' tab
                document.querySelector('#grafik-tab').click();
            }
        });
    </script>
@endsection

@push('js')
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
@endpush
