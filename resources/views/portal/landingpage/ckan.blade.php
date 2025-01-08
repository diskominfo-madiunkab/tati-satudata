@extends('portal.landingpage.layout')

@section('header')

    <div class="container">
        <div class="page-banner home-banner">
            <div class="row align-items-center flex-wrap-reverse h-100">
                <div class="col-md-6 py-5 wow fadeInLeft">
                    <h1 class="mb-4">Ckan</h1>
                    <!-- <p class="text-lg text-grey mb-5">Di sini Anda bisa akses koleksi data dan artikel terlengkap di Kabupaten Madiun dengan cepat, mudah, dan akurat dibantu berbagai fitur bermanfaat.</p> -->
                    <!-- <a href="#cari" data-role="smoothscroll" class="btn btn-primary btn-split">Cari Data <div class="fab"><span class="mai-play"></span></div></a> -->
                </div>
                <div class="col-md-6 py-5 wow zoomIn">
                    <div class="img-fluid text-center">
                        <img src="../landing-assets/img/banner_image_1.svg" alt="">
                    </div>
                </div>
            </div>
            <a href="#cari" class="btn-scroll" data-role="smoothscroll"><span class="mai-arrow-down"></span></a>
        </div>
    </div>

@endsection
