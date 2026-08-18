@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Edit Infografis</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Data Infografis</li>
            <li class="breadcrumb-item active">Edit Infografis</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Infografis</h5>
                    {{-- @php
                    dd($infografis->ti);
                    @endphp --}}

                    <!-- General Form Elements -->
                    <form action="{{ route('infografis.update',['id' => $infografis->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="image" class="col-sm-2 col-form-label">Gambar</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    name="image">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="title" class="col-sm-2 col-form-label">Judul</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    name="title" value="{{ old('title', $infografis->title) }}"
                                    placeholder="Masukkan Judul Infografis">

                                <!-- error message untuk title -->
                                @error('title')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <!-- ==== NEW: Tableau input (placed right below Title) ==== -->
                        <div class="row mb-3">
                            <label for="tableau" class="col-sm-2 col-form-label">URL Tableau (opsional)</label>
                            <div class="col-sm-10">
                                <textarea name="tableau" id="tableau" rows="3"
                                    class="form-control @error('tableau') is-invalid @enderror"
                                    placeholder="Masukkan isi tableau (opsional)">{{ old('tableau', $infografis->tableau) }}</textarea>

                                @error('tableau')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                                <small class="form-text text-muted mt-1">Boleh kosong — hanya untuk data tambahan berupa teks.</small>
                            </div>
                        </div>
                        <!-- ==== END NEW: Tableau input ==== -->

                        <div class="row mb-3">
                            <label for="content" class="col-sm-2 col-form-label">Konten</label>
                            <div class="col-sm-10">
                                <textarea class="tinymce-editor form-control @error('content') is-invalid @enderror"
                                    name="content" rows="5"
                                    placeholder="Masukkan Konten Infografis">{{ old('content', $infografis->content) }}</textarea>

                                <!-- error message untuk content -->
                                @error('content')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">UPDATE</button>
                            </div>
                        </div>

                    </form><!-- End General Form Elements -->

                </div>
            </div>

        </div>


    </div>
</section>

@push('js')
<script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'content' );
</script>
@endpush
@endsection
