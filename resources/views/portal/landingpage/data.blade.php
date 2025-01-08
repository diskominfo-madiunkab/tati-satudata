@extends('portal.landingpage.layout')

@section('header')
<div class="container">
    <div class="page-banner home-banner h-75 py-4">
        <div class="row align-items-center flex-wrap-reverse">
            <div class="col-md-12 py-0 my-0 px-5 wow fadeInLeft">
                <h1 class="mb-4">Pencarian Dataset</h1>
                <form>
                    <div class="d-flex mb-4 flex-lg-row flex-column">
                        <input class="form-control" placeholder="Cari data" name="q" value="{{old('q', request()->q)}}">
                        <select class="form-control select2 form-select w-25 w-sm-100" name="org">
                            <option value=""> - Organisasi / OPD - </option>
                            @foreach($orgs as $org)
                            <option value="{{$org['name']}}" {{request()->get('org') == $org['name'] ? 'selected' :
                                ''}}>{{$org['title']}}</option>
                            @endforeach
                        </select>
                        <select class="form-control select2 form-select w-25 w-sm-100" name="group">
                            <option value=""> - Kelompok - </option>
                            @foreach($groups as $group)
                            <option value="{{$group['name']}}" {{request()->get('group') == $group['name'] ? 'selected'
                                : ''}}>{{$group['display_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-outline-primary btn-split">
                        Cari Data
                        <div class="fab"><span class="mai-search"></span></div>
                    </button>

                    @if(!empty(request()->all()))
                    <a href="{{route('dataset')}}" class="btn btn-outline-danger rounded-3">
                        Reset Pencarian
                        <div class="fab d-inline"><span class="mai-refresh"></span></div>
                    </a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="page-section pt-2">
    <div class="container">
        <div class="row pt-0">
            <div class="col-lg-12">
                <div class="card wow fadeInUpBig">
                    <div class="card-body">
                        <div class="card-header rounded-3 d-flex justify-content-between align-content-center">
                            <h4 class="card-title">Ditemukan {{count($data)}} dataset</h4>
                            <div class="form-select form-group control-order-by">
                                <label for="field-order-by">Urutkan hasil</label>
                                <select id="field-order-by" name="sort" class="form-control">
                                    <option value="score desc, metadata_modified desc" {{request()->get('sort') ==
                                        'score desc, metadata_modified desc' ? 'selected' : ''}}>
                                        Relevansi
                                    </option>
                                    <option value="title_string asc" {{request()->get('sort') == 'title_string asc' ?
                                        'selected' : ''}}>Name Ascending</option>
                                    <option value="title_string desc" {{request()->get('sort') == 'title_string desc' ?
                                        'selected' : ''}}>Name Descending</option>
                                    <option value="metadata_modified desc" {{request()->get('sort') ==
                                        'metadata_modified desc' ? 'selected' : ''}}>Terakhir Dimodifikasi</option>
                                </select>
                            </div>
                        </div>
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
                                            <h5><a data-dataset-title="{{$d['title']}}" data-toggle="modal"
                                                    data-target="#modalPreviewDataset"
                                                    data-dataset-ajax="{{route('dataset.detail', $d['id'])}}"
                                                    class="action-preview text-primary">{{$d['title']}} <i
                                                        class="bi bi-link"></i></a></h5>
                                            <p class="text-muted">{{Str::words($d['notes'], 30)}}</p>
                                            @if(!empty($d['organization']))
                                            <p class="text-muted">
                                                <i class="bi bi-building"></i>
                                                <a href="{{url()->current()}}?org={{$d['organization']['name']}}"
                                                    class="text-muted">{{$d['organization']['title']}}</a>
                                            </p>
                                            @endif

                                            <div class="">
                                                @foreach($d['resources'] as $res)
                                                @if($loop->index >= 3) @break @endif
                                                <span class="badge badge-secondary">{{$res['format']}}</span>
                                                @endforeach

                                                <span class="mx-2" title="Tahun data ini dipublikasi"
                                                    data-published="{{$d['metadata_created']}}"><i
                                                        class="bi bi-calendar-fill"></i>
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
                                        <td class="d-none d-md-block">
                                            <i class="bi bi-files"></i> {{$d['num_resources']}} berkas
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                            @if(count($data) > 15)
                            <nav class="mt-2">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item {{!$hasPrevPage ? 'disabled': ''}}">
                                        @if($hasPrevPage)
                                        <a class="page-link" href="{{route('dataset', ['page' => $page - 1])}}"
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
                                        <a class="page-link" href="{{route('dataset', ['page' => $page + 1])}}"
                                            rel="prev">Selanjutnya »</a>
                                        @else
                                        <span class="page-link">Selanjutnya »</span>
                                        @endif
                                    </li>
                                </ul>
                            </nav>
                            @endif

                        </div>
                    </div>
                </div>
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
@endsection

@push('js')
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script>
    $(function() {
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
</script>
@endpush

@push('css')
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
@endpush