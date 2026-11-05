@extends('guest.layout')

@section('content')
@push('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .katalog-area .nice-select {
        display: none !important;
    }
    .select2-container {
        width: 100% !important;
    }
    .select2-container .select2-selection--single {
        height: 42px !important;
        border: 1px solid #adb5bd !important;
        border-radius: 6px !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px !important;
        padding-left: 12px !important;
        color: #212529 !important;
        font-size: 14px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
    .select2-dropdown {
        border: 1px solid #adb5bd !important;
        border-radius: 6px !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        font-size: 14px;
        z-index: 1050;
    }
    .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 4px;
        padding: 6px 10px;
    }
    .katalog-area .form-control {
        border: 1px solid #adb5bd !important;
        border-radius: 6px;
        height: 42px;
    }
    .katalog-area .btn {
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .katalog-area .nice-select {
        display: none !important;
    }
    .katalog-area select.form-select {
        display: block !important;
    }
</style>
@endpush
<div class="page-banner">
    <div class="container">
        <div class="page-banner-content text-center">
            <div class="page-banner-pill">
                <i class="fas fa-layer-group text-info"></i> Satu Data Kabupaten Madiun
            </div>
            <h1 class="page-banner-title">Dataset</h1>
            <p class="page-banner-subtitle">Pencarian Dataset Pemerintah Kabupaten Madiun</p>
        </div>
    </div>
</div>

<section class="katalog-area pt-40 pb-50 bg-light">
    <div class="container">
        <!-- Filter Card -->
        <div class="card shadow-sm border-0 mb-4 rounded-3">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('dataset') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-3 col-md-6 col-12">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-search me-1"></i> Cari Data</label>
                            <input type="text" name="q" class="form-control" placeholder="Kata kunci data..." value="{{ request('q') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-building me-1"></i> Organisasi / OPD</label>
                            <select class="form-select select2-searchable" name="org" data-placeholder="- Organisasi / OPD -">
                                <option value="">- Organisasi / OPD -</option>
                                @foreach($orgs as $org)
                                    @php
                                        $oName = is_array($org) ? ($org['name'] ?? $org['title']) : $org->nama_opd;
                                        $oTitle = is_array($org) ? ($org['title'] ?? $org['name']) : $org->nama_opd;
                                    @endphp
                                    <option value="{{ $oName }}" {{ request('org') == $oName ? 'selected' : '' }}>{{ $oTitle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 col-12">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-layer-group me-1"></i> Urusan</label>
                            <select class="form-select select2-searchable" name="group" data-placeholder="- Urusan -">
                                <option value="">- Urusan -</option>
                                @foreach($groups as $group)
                                    @php
                                        $gName = is_array($group) ? ($group['name'] ?? $group['title']) : $group;
                                        $gTitle = is_array($group) ? ($group['display_name'] ?? $group['title'] ?? $group['name']) : $group;
                                    @endphp
                                    <option value="{{ $gName }}" {{ request('group') == $gName ? 'selected' : '' }}>{{ $gTitle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 col-12">
                            <label class="form-label fw-semibold text-muted small"><i class="fas fa-bookmark me-1"></i> Sumber Referensi</label>
                            <select class="form-select select2-searchable" name="sumber" data-placeholder="- Sumber Referensi -">
                                <option value="">- Sumber Referensi -</option>
                                @foreach($sumberList as $sumber)
                                    <option value="{{ $sumber }}" {{ request('sumber') == $sumber ? 'selected' : '' }}>{{ $sumber }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-12 col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
                            @if(request()->filled('q') || request()->filled('org') || request()->filled('group') || request()->filled('sumber'))
                            <a href="{{ route('dataset') }}" class="btn btn-outline-secondary" title="Reset Pencarian"><i class="fas fa-redo"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Total Jumlah Dataset yang sudah disebarluaskan & Opsi Urutan -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="fas fa-database fa-lg"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Informasi Dataset Terbuka</span>
                    <span class="fw-bold text-dark fs-6">Total Jumlah Dataset: <span class="text-primary">{{ number_format($totalCount, 0, ',', '.') }}</span> Dataset</span>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2 ms-auto ms-md-0">
                <label for="select-sort" class="text-muted small text-nowrap mb-0 d-none d-sm-inline"><i class="fas fa-sort-amount-down me-1"></i> Urutkan:</label>
                <select id="select-sort" class="form-select form-select-sm" style="width: auto; min-width: 160px; height: 38px;" onchange="window.location.href = this.value;">
                    @php
                        $currSort = request('sort', 'newest');
                        $urlNewest = route('dataset', array_merge(request()->except('page'), ['sort' => 'newest']));
                        $urlOldest = route('dataset', array_merge(request()->except('page'), ['sort' => 'oldest']));
                        $urlTitleAsc = route('dataset', array_merge(request()->except('page'), ['sort' => 'title_string asc']));
                        $urlTitleDesc = route('dataset', array_merge(request()->except('page'), ['sort' => 'title_string desc']));
                    @endphp
                    <option value="{{ $urlNewest }}" {{ $currSort == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="{{ $urlOldest }}" {{ $currSort == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="{{ $urlTitleAsc }}" {{ $currSort == 'title_string asc' ? 'selected' : '' }}>Judul (A - Z)</option>
                    <option value="{{ $urlTitleDesc }}" {{ $currSort == 'title_string desc' ? 'selected' : '' }}>Judul (Z - A)</option>
                </select>
                @if(request()->filled('q') || request()->filled('org') || request()->filled('group') || request()->filled('sumber'))
                <span class="badge bg-light text-dark border px-2 py-2"><i class="fas fa-filter text-primary me-1"></i> Filter aktif</span>
                @endif
            </div>
        </div>

        <!-- Dataset Listing Card -->
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden mb-4">
            <div class="list-group list-group-flush" id="datasetList">
                @forelse($data as $d)
                @php
                    $orgTitle = !empty($d['organization']) ? (is_array($d['organization']) ? ($d['organization']['title'] ?? $d['organization']['name']) : $d['organization']) : null;
                    $year = $d['tahun'] ?? (!empty($d['metadata_created']) ? \Carbon\Carbon::parse($d['metadata_created'])->format('Y') : null);
                @endphp
                <div class="list-group-item p-3 p-md-4 border-bottom dataset-item">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div class="flex-grow-1">
                            <h5 class="mb-2">
                                <a class="text-primary text-decoration-none fw-semibold" href="{{ route('dataset.show', $d['id']) }}">
                                    {{ $d['title'] }} <i class="fas fa-external-link-alt small ms-1 text-primary-subtle"></i>
                                </a>
                            </h5>
                            <p class="text-muted small mb-2">{{ Str::words($d['notes'] ?? '', 30) }}</p>

                            <!-- Hierarki 1: Produsen Data (OPD) -->
                            @if($orgTitle)
                            <div class="d-flex align-items-center mb-2 text-muted small">
                                <i class="fas fa-building text-primary me-2"></i>
                                <span class="fw-semibold text-dark">{{ $orgTitle }}</span>
                            </div>
                            @endif

                            <!-- Hierarki 2: Atribut Teknis & Taksonomi Data -->
                            <div class="d-flex flex-wrap align-items-center gap-2 pt-1">
                                {{-- Format Berkas Badges --}}
                                @if(!empty($d['resources']) && count($d['resources']) > 0)
                                    @foreach($d['resources'] as $res)
                                        @if($loop->index >= 3) @break @endif
                                        @php
                                            $fmt = strtoupper($res['format'] ?? 'BERKAS');
                                        @endphp
                                        <span class="badge {{ $fmt == 'CSV' ? 'bg-info text-dark' : ($fmt == 'PDF' ? 'bg-danger text-white' : 'bg-success text-white') }} px-2 py-1">
                                            {{ $fmt }}
                                        </span>
                                    @endforeach
                                @endif

                                {{-- Tahun Badge --}}
                                @if($year)
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <i class="far fa-calendar-alt text-primary me-1"></i> {{ $year }}
                                </span>
                                @endif

                                {{-- Urusan / Kelompok Badge --}}
                                @if(!empty($d['groups']) && count($d['groups']) > 0)
                                    @foreach($d['groups'] as $group)
                                        @php
                                            $gTitle = is_array($group) ? ($group['display_name'] ?? $group['title'] ?? $group['name']) : $group;
                                        @endphp
                                        <span class="badge bg-light text-dark border px-2 py-1">
                                            <i class="fas fa-layer-group text-primary me-1"></i> {{ $gTitle }}
                                        </span>
                                    @endforeach
                                @endif

                                {{-- Sumber Referensi Badge --}}
                                @if(!empty($d['sumber_data']))
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                                    <i class="fas fa-bookmark me-1"></i> {{ $d['sumber_data'] }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Kolom Aksi & Jumlah Berkas -->
                        <div class="d-flex flex-row flex-md-column align-items-center align-items-md-end justify-content-between w-100 w-md-auto gap-2 pt-3 pt-md-0">
                            <span class="badge bg-light text-secondary border px-2 py-1">
                                <i class="far fa-file me-1 text-primary"></i> {{ $d['num_resources'] ?? count($d['resources'] ?? []) }} berkas
                            </span>
                            <a href="{{ route('dataset.show', $d['id']) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 text-nowrap d-inline-flex align-items-center">
                                <i class="fas fa-eye me-1"></i> Detail Data
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i>
                    <p class="mb-0">Tidak ada dataset yang ditemukan.</p>
                </div>
                @endforelse
            </div>
            @if($pages > 1)
            <div class="card-footer bg-white p-3">
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item {{ !$hasPrevPage ? 'disabled' : '' }}">
                            @if($hasPrevPage)
                            <a class="page-link" href="{{ route('dataset', array_merge(request()->input(), ['page' => $page - 1])) }}" rel="prev">« Sebelumnya</a>
                            @else
                            <span class="page-link">« Sebelumnya</span>
                            @endif
                        </li>
                        <li class="page-item">
                            <span class="page-link">Halaman {{ $page }} / {{ $pages }}</span>
                        </li>
                        <li class="page-item {{ !$hasNextPage ? 'disabled' : '' }}">
                            @if($hasNextPage)
                            <a class="page-link" href="{{ route('dataset', array_merge(request()->input(), ['page' => $page + 1])) }}" rel="next">Selanjutnya »</a>
                            @else
                            <span class="page-link">Selanjutnya »</span>
                            @endif
                        </li>
                    </ul>
                </nav>
            </div>
            @endif
        </div>
</section>

<!-- Modal -->
<div class="modal fade" id="modal-hapus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div style="background-color: red" class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="color: white">Hapus Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="color: white"
                    aria-label="Close"></button>
            </div>

        </div>
    </div>

</div>

<div class="modal fade" id="modalPreviewDataset" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 9999">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="text-center mx-auto loaders">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div class="dataset-info">
                <div class="row mb-2">
                    <div class="col-4">
                        <span class="font-weight-bold">Judul</span>
                    </div>
                    <div class="col-8">
                        <span class="dataset-title"></span>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-4">
                        <span class="font-weight-bold">Deskripsi</span>
                    </div>
                    <div class="col-8">
                        <p class="font-weight-lighter font-italic"><span class="dataset-description"></span></p>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-4">
                        <span class="font-weight-bold">Organisasi / OPD</span>
                    </div>
                    <div class="col-8">
                        <p><span class="dataset-organization"></span></p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-4">
                        <span class="font-weight-bold">Dipublikasi</span>
                    </div>
                    <div class="col-8">
                        <p><span class="mai-calendar"></span> <span class="dataset-created"></span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <span class="font-weight-bold">Terakhir dimodifikasi</span>
                    </div>
                    <div class="col-8">
                        <p><span class="mai-calendar"></span> <span class="dataset-modified"></span></p>
                    </div>
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
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Tutup</button>
                <a href="javascript:void(0)" target="_new" class="btn btn-outline-info btn-action">CKAN <span
                        class="mai-link"></span></a>
            </div>
        </div>
    </div>
</div>


<link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<style>
    a.action-preview:hover {
        cursor: pointer;
    }

    .modal-backdrop.fade.show {
        z-index: 9998;
    }

    @media (max-width: 576px) {
        .w-sm-100 {
            width: 100% !important;
        }
    }
</style>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        try {
            $('.select2-searchable').niceSelect('destroy');
            $('#select-sort').niceSelect('destroy');
        } catch(e) {}
        $('.select2-searchable').select2({
            allowClear: true,
            width: '100%'
        });
    });
</script>
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
{{-- <script>
    $(function() {
        console.log('saaasas');
            const url = new URL(window.location.href);
            $('#field-order-by').on('change', function() {
                url.searchParams.set('sort', $(this).val());
                window.location.href = url.toString();
            });

            const $modalPreview = $('#modalPreviewDataset');
            const $modalPreviewInfo = $modalPreview.find('div.dataset-info');
            const $modalPreviewTitle = $modalPreview.find('h5.modal-title');
            const $modalPreviewBody = $modalPreview.find('div.modal-body');
            const $modalPreviewLoaders = $modalPreview.find('div.loaders');

            $modalPreviewInfo.hide();

            $modalPreviewLoaders.hide();
            $modalPreview.on('show.bs.modal', function (e) {
               const row = $(e.relatedTarget);
                const rowInfo = {
                    url: row.data('datasetAjax'),
                    title: row.data('datasetTitle')
                };

                $modalPreviewTitle.text('Sedang memuat data...')
                $modalPreviewLoaders.show();

                $.ajax({
                    url: rowInfo.url,
                    success: datasetCallback,
                    dataType: 'json',
                    error: datasetError
                })
            });

            function datasetCallback(data, status, xhr) {
                $modalPreviewTitle.text('Informasi Dataset');
                $modalPreviewLoaders.hide();
                $modalPreviewBody.empty();

                $modalPreview.find('div.modal-footer>a').attr('href', data.link);

                const $previewData = $modalPreviewInfo.clone();
                $previewData.find('span.dataset-title').text(data.title);

                if (!data.description || data.description.length < 1) {
                    $previewData.find('span.dataset-description').parent().parent().parent().remove();
                } else {
                    $previewData.find('span.dataset-description').text(data.description);
                }

                $previewData.find('span.dataset-created').text(data.created);
                $previewData.find('span.dataset-modified').text(data.modified);

                if (typeof data.organization.title != 'undefined') {
                    $previewData.find('span.dataset-organization').text(data.organization.title);
                } else {
                    $previewData.find('span.dataset-organization').parent().parent().parent().remove();
                }

                if (data.resources.length > 0) {
                    let resources = [];
                    for (const resource of data.resources) {
                        resources.push([
                            resource.name,
                            resource.description ?? '-',
                            resource.created,
                            resource.format,
                            `<a href="${resource.url_download}" class="text-primary" target="_new" download><span class="mai-download"> Unduh</a> <br> <a href="${resource.url_preview}" target="_new"><span class="mai-eye"> Pratinjau</a>`
                        ]);
                    }
                    $previewData.find('table.table').DataTable({
                        data: resources,
                        filter: false,
                        paginate: false,
                        autoWidth: false,
                        ordering: false,
                    })
                } else {
                    $previewData.find('div.table-berkas').remove();
                }

                $previewData.show();
                $previewData.appendTo($modalPreviewBody);
            }

            function datasetError(xhr, status, err) {
                $modalPreviewTitle.text('Gagal memuat informasi dataset');
                $modalPreviewLoaders.hide();
                $modalPreviewBody.empty();

                let errorMessage = err;
                const response = xhr.responseJSON;
                if (response && response.error) {
                    errorMessage = response.error;
                }

                $modalPreviewBody.append(`<div class="alert alert-danger">Gagal memuat informasi dataset.<br/> Error: ${errorMessage}</div>`)
            }
        });
</script> --}}
<script src="{{asset('landing-assets/vendor/wow/wow.min.js')}}"></script>
<script>
    $(function() {
        $('.action-preview').on('click', function() {
            const $modalPreview = $('#modalPreviewDataset');
            const $modalPreviewInfo = $modalPreview.find('.dataset-info');
            const $modalPreviewTitle = $modalPreview.find('.modal-title');
            const $modalPreviewBody = $modalPreview.find('.modal-body');
            const $modalPreviewLoaders = $modalPreview.find('.loaders');

            $modalPreviewInfo.hide();
            $modalPreviewTitle.text('Sedang memuat data...');
            $modalPreviewLoaders.show();

            const datasetUrl = $(this).data('dataset-ajax');
            $.ajax({
                url: datasetUrl,
                success: function(data) {
                    $modalPreviewTitle.text('Informasi Dataset');
                    $modalPreviewLoaders.hide();
                    $modalPreviewBody.empty();

                    // Isi konten informasi dataset
                    $modalPreview.find('div.modal-footer>a').attr('href', data.link);
                    
                    const $previewData = $modalPreviewInfo.clone();
                    $previewData.find('span.dataset-title').text(data.title);
                    
                    if (!data.description || data.description.length < 1) {
                        $previewData.find('span.dataset-description').parent().parent().parent().remove(); } else {
                        $previewData.find('span.dataset-description').text(data.description); }
                        $previewData.find('span.dataset-created').text(data.created);
                        $previewData.find('span.dataset-modified').text(data.modified); if (typeof data.organization.title !='undefined' ) {
                        $previewData.find('span.dataset-organization').text(data.organization.title); } else {
                        $previewData.find('span.dataset-organization').parent().parent().parent().remove(); } if (data.resources.length> 0)
                        {
                        let resources = [];
                        for (const resource of data.resources) {
                        resources.push([
                        resource.name,
                        resource.description ?? '-',
                        resource.created,
                        resource.format,
                        `<a href="${resource.url_download}" class="text-primary" target="_new" download><span class="mai-download">
                                Unduh</a> <br> <a href="${resource.url_preview}" target="_new"><span class="mai-eye"> Pratinjau</a>`
                        ]);
                        }
                        $previewData.find('table.table').DataTable({
                        data: resources,
                        filter: false,
                        paginate: false,
                        autoWidth: false,
                        ordering: false,
                        })
                        } else {
                        $previewData.find('div.table-berkas').remove();
                        }
                    // Anda perlu mengganti ini dengan kode yang sesuai

                    $modalPreviewInfo.show();
                },
                error: function(xhr, status, error) {
                    $modalPreviewTitle.text('Gagal memuat informasi dataset');
                    $modalPreviewLoaders.hide();
                    $modalPreviewBody.empty();

                    const errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : error;
                    $modalPreviewBody.append(`<div class="alert alert-danger">Gagal memuat informasi dataset.<br/> Error: ${errorMessage}</div>`);
                }
            });
        });
    });
</script>
@endpush