@extends('pages.main.layout')

@php
    if ($data->relationLoaded('verifikasi')) {
        $variables = ['berkas'];
        foreach ($variables as $var) {
            $$var = $data->verifikasi->firstWhere('category', $var);
        }
    }
@endphp

@section('content')
    <div class="pagetitle">
        <h1>Verifikasi Berkas - {{ $data->nama_data }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Daftar Verifikasi Data</li>
                <li class="breadcrumb-item active">{{ $data->nama_data }}</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                {{-- @php
            dd($$var->comment);
            @endphp --}}
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Berkas Data</div>
                        <div class="table-responsive">
                            <table class="table table-stripped datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Tgl. Unggah</th>
                                        @if ($data->status_id == \App\Models\Data::STATUS_PROSES_VERIFIKASI)
                                            <th>Aksi</th>
                                        @endif
                                        @if (isset($$var) && !empty($$var->comment))
                                            <th style="width: 200px">Komentar</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($existingBerkas as $k => $berkas)
                                        @php

                                            $v = $data->verifikasi->firstWhere('field', $berkas['id']);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ $berkas['previewUrl'] }}"
                                                    target="_new">{{ $berkas['name'] ?? '-' }} <i
                                                        class="bi bi-link"></i></a>
                                                {{-- <p>{{$berkas['officeLive']}}</p> --}}


                                                <!-- End Column Chart -->
                                            </td>
                                            <td>{{ $berkas['created_at'] ? $berkas['created_at']->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            @if ($data->status_id == \App\Models\Data::STATUS_PROSES_VERIFIKASI)
                                                <td>
                                                    {{-- <div class="btn-group-sm">
                                            <button
                                                class="btn @if ($data->status_id == \App\Models\Data::STATUS_REVISI || $data->status_id == \App\Models\Data::STATUS_PROSES_VERIFIKASI) btn-actions btn-accept @endif btn-sm {{$v && $v->accepted ? 'btn-success' : 'btn-outline-success'}}"
                                                data-name="{{$berkas['id']}}">Setuju <i
                                                    class="bi bi-check"></i></button>
                                            <button
                                                class="btn @if ($data->status_id == \App\Models\Data::STATUS_REVISI || $data->status_id == \App\Models\Data::STATUS_PROSES_VERIFIKASI) btn-actions btn-reject @endif btn-sm {{$v && !$v->accepted ? 'btn-danger' : 'btn-outline-danger'}}"
                                                data-name="{{$berkas['id']}}">Revisi <i class="bi bi-x"></i></button>
                                            <button class="btn btn-comment btn-sm btn-outline-primary"
                                                data-name="{{$berkas['id']}}"><i class="bi bi-chat-dots"></i>
                                                Komentar</button>
                                        </div> --}}
                                                    <div class="btn-group-sm">

                                                        {{-- @php
                                            dd($v->comment);
                                            @endphp --}}

                                                        <button
                                                            class="btn @if ($data->status_id == \App\Models\Data::STATUS_PROSES_VERIFIKASI) btn-actions btn-accept @endif btn-sm {{ $v && $v->accepted ? 'btn-success' : 'btn-outline-success' }}"
                                                            data-name="{{ $berkas['id'] }}">Setuju <i
                                                                class="bi bi-check"></i></button>
                                                        <button
                                                            class="btn @if ($data->status_id == \App\Models\Data::STATUS_PROSES_VERIFIKASI) btn-actions btn-reject @endif btn-sm {{ $v && !$v->accepted ? 'btn-danger' : 'btn-outline-danger' }}"
                                                            data-name="{{ $berkas['id'] }}">Revisi <i
                                                                class="bi bi-x"></i></button>

                                                        {{-- <button class="btn btn-comment btn-sm btn-outline-primary"
                                                data-name="{{$berkas['id']}}"><i class="bi bi-chat-dots"></i>
                                                Komentar</button> --}}


                                                    </div>
                                                </td>
                                            @endif
                                            <td style="width: 200px">
                                                @if (isset($v) && !empty($v->comment))
                                                    {{ $v->comment }}
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($berkas['fileType'] == 'XLSX')
                                            <tr>
                                                <td></td>
                                                <td colspan="4">
                                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link active" id="home-tab"
                                                                data-bs-toggle="tab"
                                                                data-bs-target="#home{{ $berkas['id'] }}" type="button"
                                                                role="tab" aria-controls="home"
                                                                aria-selected="true">Preview</button>
                                                        </li>
                                                        @if ($existingData)
                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="profile-tab"
                                                                    data-bs-toggle="tab"
                                                                    data-bs-target="#profile{{ $berkas['id'] }}"
                                                                    type="button" role="tab" aria-controls="profile"
                                                                    aria-selected="false">Grafik</button>
                                                            </li>
                                                        @endif
                                                    </ul>

                                                    <div class="tab-content pt-2" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="home{{ $berkas['id'] }}"
                                                            role="tabpanel" aria-labelledby="home-tab">
                                                            <iframe src="{{ $berkas['officeLive'] }}" frameborder="0"
                                                                style="width:100%;min-height:640px;"></iframe>
                                                        </div>
                                                        <div class="tab-pane fade" id="profile{{ $berkas['id'] }}"
                                                            role="tabpanel" aria-labelledby="profile-tab">
                                                            @if ($existingData)
                                                                <!-- Column Chart -->
                                                                <h5 class="card-title">Grafik Data {{ $data->nama_data }}
                                                                </h5>
                                                                <div class="row">
                                                                    <div class="col-md-9">
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
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        @foreach ($tables as $k => $table)
                                                                            <div class="card" style="margin-bottom: 55%">
                                                                                <div class="card-body">
                                                                                    <form
                                                                                        id="grafikForm{{ $k }}"
                                                                                        action="{{ route('chart.storeDataByFilter') }}"
                                                                                        method="POST"
                                                                                        enctype="multipart/form-data">
                                                                                        @csrf
                                                                                        <input type="text" name="id_data"
                                                                                            id="id_data"
                                                                                            value="{{ $data->id }}"
                                                                                            hidden>
                                                                                        <input type="text"
                                                                                            name="id_table" id="id_table"
                                                                                            value="{{ $table['table']['id'] }}"
                                                                                            hidden>
                                                                                        <h5 class="card-title">Filter</h5>

                                                                                        <label
                                                                                            for="dropdown_axis_x_{{ $table['table']['id'] }}">Axis
                                                                                            X</label>
                                                                                        <select name="axis_x"
                                                                                            id="dropdown_axis_x_{{ $k }}"
                                                                                            class="form-select select2 select-axis-x"
                                                                                            aria-label="Default select example">
                                                                                            <option value="0">-- Data
                                                                                                Tunggal --</option>
                                                                                            @foreach ($table['headers'] as $header)
                                                                                                {{-- @if (empty(sizeOf($existingData)))
                                                            <option value="">--Tidak Ada Data--</option>
                                                            @else --}}
                                                                                                <option
                                                                                                    value="{{ $header->id }}"
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
                                                                                                <option
                                                                                                    value="{{ $header->id }}"
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
                                                                                                    <option
                                                                                                        value="{{ $header->id }}"
                                                                                                        @if ($existingData->isNotEmpty() && isset($existingData[$k]) && $existingData[$k]->kategori == $header->id) selected @endif>
                                                                                                        {{ $header->header }}
                                                                                                    </option>
                                                                                                @endif
                                                                                                {{-- @endif --}}
                                                                                            @endforeach
                                                                                        </select>

                                                                                        <br>
                                                                                        @if (auth()->user()->hasRole('produsen'))
                                                                                            <button type="submit"
                                                                                                id="btn-submit{{ $k }}"
                                                                                                class="btn btn-success">
                                                                                                Tampilkan
                                                                                            </button>
                                                                                        @endif
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>


                                                                {{-- <div id="columnChart{{ $loop->index }}"></div>
                                                                        <div id ="lineChart{{ $loop->index }}"></div> --}}
                                                            @endif
                                                        </div>
                                                    </div><!-- End Default Tabs -->
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                            @if ($data->status_id == 7)
                                <a href="{{ auth()->user()->hasAnyRole('produsen') ? url('/data_produsen/verifikasi/revisi') : url('/data_walidata/verifikasi/revisi') }}"
                                    {{-- href="{{ url()->previous() }}"  --}} class="btn btn-outline-secondary"><i
                                        class="bi bi-arrow-left"></i>
                                    Kembali</a>
                            @else
                                <a href="{{ auth()->user()->hasAnyRole('produsen') ? url('/data_produsen/verifikasi') : url('/data_walidata/verifikasi') }}"
                                    {{-- href="{{ url()->previous() }}"  --}} class="btn btn-outline-secondary"><i
                                        class="bi bi-arrow-left"></i>
                                    Kembali</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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

                console.log(data)

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

@push('js')
@endpush

@push('js')
    <script>
        $(function() {
            $('button.btn-actions').on('click', function(e) {
                e.preventDefault();
                let isAccept = $(this).hasClass('btn-accept');
                let isReject = $(this).hasClass('btn-reject');
                if (isAccept || isReject) {
                    Swal.fire({
                        title: 'Apakah Anda yakin ingin mengkonfirmasi?',
                        showCancelButton: true,
                        confirmButtonText: isAccept ? 'Ya, Setuju' : 'Ya, Revisi',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: isAccept ? '#28a745' : '#dc3545',
                        cancelButtonColor: '#6c757d',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                    url: '{{ route('verifikasi.verify', $data->id) }}',
                                    method: 'PATCH',
                                    data: {
                                        category: 'berkas',
                                        accepted: isAccept ? 1 : 0,
                                        field: $(this).data('name')
                                    }
                                })
                                .then((r) => {
                                    let inputValue = '';
                                    if (r.comment) {
                                        inputValue = r.comment;
                                    }
                                    Swal.fire({
                                        title: 'Komentar untuk field ini',
                                        input: 'textarea',
                                        inputValue: inputValue,
                                        inputAttributes: {
                                            autocapitalize: 'off',
                                            spellCheck: false,
                                        },
                                        showCancelButton: true,
                                        confirmButtonText: 'Simpan',
                                        showLoaderOnConfirm: true,
                                        preConfirm: (comment) => {
                                            return $.post(
                                                    '{{ route('verifikasi.komentar', $data->id) }}', {
                                                        field: $(this).data('name'),
                                                        comment: comment,
                                                        category: 'berkas'
                                                    })
                                                .then(response => {
                                                    if (!response.ok) {
                                                        throw new Error(response
                                                            .message)
                                                    }
                                                    return response;
                                                })
                                                .catch(error => {
                                                    Swal.showValidationMessage(
                                                        `Request gagal: ${error}`
                                                    )
                                                })
                                        },
                                        allowOutsideClick: () => !Swal.isLoading()
                                    }).then((result) => {
                                        console.log(result);
                                        Toast.fire({
                                            icon: result.value.ok ? 'success' :
                                                'error',
                                            title: result.value.message
                                        });
                                        location
                                            .reload(); // Merefresh halaman setelah memberikan komentar
                                    });
                                })
                                .catch(() => Toast.fire({
                                    icon: 'error',
                                    title: 'Gagal menyimpan perubahan'
                                }));
                        }
                    });
                } else {
                    $.ajax({
                            url: '{{ route('verifikasi.verify', $data->id) }}',
                            method: 'PATCH',
                            data: {
                                category: 'berkas',
                                accepted: null,
                                field: $(this).data('name')
                            }
                        })
                        .then((r) => {
                            Toast.fire({
                                icon: r.ok ? 'success' : 'error',
                                title: r.message
                            });
                            location.reload(); // Merefresh halaman setelah memberikan komentar
                        })
                        .catch(() => Toast.fire({
                            icon: 'error',
                            title: 'Gagal menyimpan perubahan'
                        }));
                }
            });
        });
    </script>
@endpush
