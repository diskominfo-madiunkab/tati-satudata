@extends('guest.layout')

@section('header')
@push('style')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
<style>
    .bootstrap-tagsinput {
        margin: 0;
        width: 100%;
        padding: 0.5rem 0.75rem 0;
        font-size: 1rem;
        line-height: 1.25;
        transition: border-color 0.15s ease-in-out;
    }

    .bootstrap-tagsinput .tag {
        display: inline-block;
        background-color: #636c72;
        padding: 0 .4em .15em;
        border-radius: .25rem;
        margin-bottom: 0.4em;
    }

    .bootstrap-tagsinput .tag [data-role="remove"]:after {
        content: '\00d7';
    }

    .bootstrap-tagsinput input {
        margin-bottom: 0.5em;
    }

    .bootstrap-tagsinput.has-focus {
        background-color: #fff;
        border-color: #5cb3fd;
    }
</style>
@endpush
<section class="uni-banner">
    <div class="container">
        <div class="uni-banner-text-area">
            <h1>DATASET</h1>
            <ul>
                <li><a href="{{('/')}}">Home</a></li>
                <li>Pencarian Dataset</li>
            </ul>
        </div>
    </div>
</section>


<div class="login pt-20 pb-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="log-in-card">
                    {{-- ini --}}
                    <div class="default-section-title wow fadeInLeft">
                        <h1>Pencarian Dataset</h1>
                    </div>
                    <div class="login-form pr-20">
                        <form method="GET" action="{{ url('dataset') }}">
                            @csrf
                            <div class="row col-lg-12">
                                <div class="mb-2 col-lg-12">
                                    <input type="text" style="background-color:aliceblue" name='q' class="form-control"
                                        placeholder="Cari data" value="{{old('q', request()->q)}}">
                                </div>
                                <div class="col-lg-12">
                                    <p style="margin-left:1%">Filter</p>
                                </div>
                                <div class="mb-1 col-lg-12">
                                    <div class="form-group">
                                        <select class="form-control" name="org" aria-label="Default select example">
                                            <option value=""> - Organisasi / OPD - </option>
                                            @foreach($orgs as $org)
                                            <option value="{{$org['name']}}" {{request()->get('org') == $org['name'] ?
                                                'selected' :
                                                ''}}>{{$org['title']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-1 col-lg-12">
                                    <select class="form-control" name="group">
                                        <option value=""> - Kelompok - </option>
                                        @foreach($groups as $group)
                                        <option value="{{$group['name']}}" {{request()->get('group') == $group['name'] ?
                                            'selected'
                                            : ''}}>{{$group['display_name']}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <br>
                                <div class="mb-1 col-lg-12" style="margin-top: 30px">
                                    <input type="text" id="tags" name="tags" class="form-control" data-role="tagsinput"
                                        placeholder="Tambahkan tag" value="{{ old('tag', request()->tag) }}">
                                    <small class="form-text text-muted">Pisahkan tag dengan koma atau tekan
                                        enter</small>
                                </div>
                            </div>
                            <br>



                            <button class="default-button">Cari Dataset <i class="fas fa-search"></i></button>
                            @if(!empty(request()->all()))
                            <a style="margin-top: 20px" href="{{('dataset')}}" class="btn btn-danger">Reset Pencarian <i
                                    class="fas fa-arrows-rotate"></i></a>
                            @endif
                        </form>
                    </div>
                    {{-- ini --}}
                </div>
            </div>
        </div>
    </div>
</div>


<div class="login pt-0 pb-50">
    {{-- <div class="container"> --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="log-in-card">
                    {{-- ini --}}
                    {{-- <div class="login pb-50">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="log-in-card wow fadeInUpBig">
                                        <div
                                            class="card-header rounded-3 d-flex justify-content-between align-content-center col-lg-12">
                                            <h4 class="card-title col-lg-8">Ditemukan {{count($data)}} dataset</h4>
                                            <div class="form-group control-order-by col-lg-4">
                                                <label for="field-order-by">Urutkan hasil</label>
                                                <select style="border-width: 1px" id="field-order-by" name="sort"
                                                    class="form-control">
                                                    <option value="score desc, metadata_modified desc" {{request()->
                                                        get('sort') ==
                                                        'score desc, metadata_modified desc' ? 'selected' : ''}}>
                                                        Relevansi
                                                    </option>
                                                    <option value="title_string asc" {{request()->get('sort') ==
                                                        'title_string asc' ?
                                                        'selected' : ''}}>Name Ascending</option>
                                                    <option value="title_string desc" {{request()->get('sort') ==
                                                        'title_string desc' ?
                                                        'selected' : ''}}>Name Descending</option>
                                                    <option value="metadata_modified desc" {{request()->get('sort') ==
                                                        'metadata_modified desc' ? 'selected' : ''}}>Terakhir
                                                        Dimodifikasi</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    {{-- ini --}}

                    {{-- ini --}}
                    <div class="login pb-50">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="log-in-card">
                                        <div class="table-responsive">

                                            @if($pages > 1)
                                            <nav class="mt-2">
                                                <ul class="pagination justify-content-center">
                                                    <li class="page-item {{!$hasPrevPage ? 'disabled': ''}}">
                                                        @if($hasPrevPage)
                                                        <a class="page-link"
                                                            href="{{route('dataset', array_merge(request()->input(), ['page' => $page - 1]))}}"
                                                            rel="prev">« Sebelumnya</a>
                                                        @else
                                                        <span class="page-link">« Sebelumnya</span>
                                                        @endif
                                                    </li>

                                                    <li class="page-item">
                                                        <span class="page-link">Halaman - {{$page}} / {{$pages}}</span>
                                                    </li>

                                                    <li class="page-item {{ !$hasNextPage ? 'disabled' : '' }}">
                                                        @if($hasNextPage)
                                                        <a class="page-link"
                                                            href="{{route('dataset', array_merge(request()->input(), ['page' => $page + 1]))}}"
                                                            rel="prev">Selanjutnya »</a>
                                                        @else
                                                        <span class="page-link">Selanjutnya »</span>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </nav>
                                            @endif

                                            <table class="table table-stripped wow" id="tableDataset">
                                                <tbody>
                                                    @foreach($data as $d)
                                                    <tr class="wow fadeInRight">
                                                        <td>
                                                            {{-- <a href="javascript:void(0)"
                                                                class="btn btn-outline-danger btn-sm"
                                                                onclick='$("#modal-hapus").modal("show");'>{{$d['title']}}
                                                            </a> --}}
                                                            {{-- judul --}}
                                                            <h5><a class="text-primary"
                                                                    href="{{route('dataset.show', $d['id'])}}">{{$d['title']}}
                                                                    <i class="fas fa-link"></i></a></h5>
                                                            <p class="text-muted">{{Str::words($d['notes'], 30)}}
                                                            </p>
                                                            @if(!empty($d['organization']))
                                                            <p class="text-muted">
                                                                <i class="fas fa-building"></i>
                                                                <a href="{{url()->current()}}?org={{$d['organization']['name']}}"
                                                                    class="text-muted">{{$d['organization']['title']}}</a>
                                                            </p>
                                                            @endif

                                                            <div class="">
                                                                @foreach($d['resources'] as $res)
                                                                @if($loop->index >= 3) @break @endif
                                                                <span style="color: black"
                                                                    class="badge badge-success">{{$res['format']}}</span>
                                                                @endforeach

                                                                <span class="mx-2" title="Tahun data ini dipublikasi"
                                                                    data-published="{{$d['metadata_created']}}"><i
                                                                        class="far fa-calendar"></i>
                                                                    {{\Carbon\Carbon::parse($d['metadata_created'])->format('Y')}}</span>
                                                                @if(count($d['groups']) > 0)
                                                                <span class="mx-2"><i class="bi bi-tags"></i>
                                                                    @foreach($d['groups'] as $group)
                                                                    <a href="{{url()->current()}}?group={{$group['name']}}"
                                                                        class="text-muted">{{$group['display_name']}}@if(!$loop->last)</a>,
                                                                    @endif
                                                                    @endforeach
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="text-end" style="min-width: 170px;">
                                                            <div class="small text-muted mb-2"><i class="far fa-file me-1"></i>{{$d['num_resources']}} berkas</div>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-pill px-3" type="button" id="unduhDropdown{{$d['id']}}" data-bs-toggle="dropdown" data-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-download me-1"></i> Opsi Unduh
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="unduhDropdown{{$d['id']}}">
                                                                    @foreach($d['resources'] as $res)
                                                                        <li>
                                                                            <a class="dropdown-item small" href="{{$res['url_download']}}" target="_blank">
                                                                                <i class="fas fa-file-{{$res['format'] == 'CSV' ? 'csv' : ($res['format'] == 'XLSX' || $res['format'] == 'EXCEL' ? 'excel' : 'alt')}} text-{{$res['format'] == 'CSV' ? 'info' : ($res['format'] == 'XLSX' || $res['format'] == 'EXCEL' ? 'success' : 'primary')}} me-2"></i>
                                                                                Unduh Format {{$res['format'] ?: 'Berkas'}}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li>
                                                                        <a class="dropdown-item small text-primary" href="{{ route('api.v1.datasets.detail.web', $d['id']) }}" target="_blank">
                                                                            <i class="fas fa-code text-primary me-2"></i> Akses API JSON
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>

                                            {{-- @if(count($data) > 15) --}}
                                            <nav class="mt-2">
                                                <ul class="pagination justify-content-center">
                                                    <li class="page-item {{!$hasPrevPage ? 'disabled': ''}}">
                                                        @if($hasPrevPage)
                                                        <a class="page-link"
                                                            href="{{route('dataset', ['page' => $page - 1])}}"
                                                            rel="prev">« Sebelumnya</a>
                                                        @else
                                                        <span class="page-link">« Sebelumnya</span>
                                                        @endif
                                                    </li>

                                                    <li class="page-item">
                                                        <span class="page-link">Halaman - {{$page}} / {{$pages}}</span>
                                                    </li>

                                                    <li class="page-item {{!$hasNextPage }}">
                                                        @if($hasNextPage)
                                                        <a class="page-link"
                                                            href="{{route('dataset', ['page' => $page + 1])}}"
                                                            rel="prev">Selanjutnya »</a>
                                                        @else
                                                        <span class="page-link">Selanjutnya »</span>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </nav>
                                            {{-- @endif --}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ini --}}
                </div>
            </div>
        </div>

        {{--
    </div> --}}
</div>

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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script>
    $(document).ready(function() {
            $('#tags').tagsinput({
                trimValue: true,
                confirmKeys: [13, 44, 32],
                focusClass: 'my-focus-class'
            });

            $('.bootstrap-tagsinput input').on('focus', function() {
                $(this).closest('.bootstrap-tagsinput').addClass('has-focus');
            }).on('blur', function() {
                $(this).closest('.bootstrap-tagsinput').removeClass('has-focus');
            });
        });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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