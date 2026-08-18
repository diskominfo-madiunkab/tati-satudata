@extends('pages.main.layout')
@section('content')
@include('sweetalert::alert')

<div class="pagetitle">
    <h1>Daftar Infografis</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Daftar Infografis</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Infografis</h5>

                    <a href="{{route('infografis.create')}}" class="btn btn-md btn-success mb-3 float-right">Tambah
                        Infografis</a>
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Gambar</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Konten</th>
                                <th scope="col">Tableau</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach($blogs as $blog)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td><img class="rounded" style="width: 150px"
                                        src="{{ Storage::url('public/blogs/').$blog->image }}" alt="image"></td>

                                <td>{{ $blog->title }}</td>
                                <td>{!! $blog->content !!}</td>
                                <td>
                                    @if(!empty($blog->tableau))
                                        <span class="badge bg-success">Tableau</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Ada</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('infografis.edit', ['id' => $blog->id]) }}"
                                        class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                        action="{{ route('infografis.delete', $blog->id) }}">
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i
                                                class="bi bi-trash"></i></button>
                                    </form>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->

                </div>
            </div>

        </div>
    </div>
</section>

@endsection
