@extends('guest.layout')

@section('content')
<section class="uni-banner">
    <div class="container">
        <div class="uni-banner-text-area">
            <h1>Publikasi</h1>
            <ul>
                <li><a href="{{('/')}}">Beranda</a></li>
                <li>Publikasi</li>
            </ul>
        </div>
    </div>
</section>


<section class="blog-details pt-70 pb-100">
    <div class="container">
        <div class="row ">
            @foreach($publikasi as $pub)
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="blog-card bg-f9fbfe">
                    <div class="blog-card-img" style="height: 350px; width: auto">
                        <a href="{{ route('guest.publikasi.detail', ['id' => encrypt($pub->id)]) }}"><img
                                src="{{ Storage::url('public/blogs/').$pub->image }}" alt="image"></a>
                    </div>
                    <div class="blog-card-text-area">
                        <div class="blog-date">
                            <ul>
                                <li><i class="fas fa-user"></i>Dibuat Oleh Admin</a></li>
                                <li><i class="far fa-calendar-alt"></i> {{ date('d-M-Y H:i',
                                    strtotime($pub->created_at)) }}</li>
                            </ul>
                        </div>
                        <div class="row">
                            <div>
                                <h4><a href="{{ route('guest.publikasi.detail', ['id' => encrypt($pub->id)]) }}">{{
                                        $pub->title
                                        }}</a></h4>
                                {{-- <p>{{ strip_tags($publ->content) }}</p> --}}
                                <img style="height: 50px; width: auto" src="{{asset('assets-guest/images/pdf.png')}}"
                                    alt="image">

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach

        </div>
        {{-- <div class="paginations mt-30">
            <ul>
                <li><a class="active" href="blog.html">1</a></li>
                <li><a href="blog.html">2</a></li>
                <li><a href="blog.html">3</a></li>
                <li><a href="blog.html"><i class="fas fa-chevron-right"></i></a></li>
            </ul>
        </div> --}}
    </div>
</section>
@endsection