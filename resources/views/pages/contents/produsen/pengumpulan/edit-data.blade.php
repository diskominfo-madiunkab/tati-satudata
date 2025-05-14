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
                    @if (auth()->user()->hasAnyRole('produsen') && ($data->status_id == 10 || $data->status_id == 7))
                        <div class="card">
                            <div class="row">
                                <div class="card-body">
                                    <div class="card-title">Berkas Data
                                        <h6>Sebelum mengunggah berkas harap unduh template untuk
                                            mengunggah berkas <a href="" class="btn btn-md btn-info"
                                                data-bs-toggle="modal" data-bs-target="#basicModal">disini!</a>
                                        </h6>
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
