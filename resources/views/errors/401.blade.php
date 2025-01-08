{{-- @extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized')) --}}

@extends('guest.layout')

@section('content')
<section class="error ptb-100">
    <div class="container">
        <div class="error-content">
            <img src="assets-guest/images/error.png" alt="image">
            <h4>Sorry We Can`t Find That Page!</h4>
            <p>The page you are looking for was moved, removed, renamed or never existed.</p>
            <a class="default-button" href="{{url('/')}}">Take Me Home <i class="fas fa-angle-right"></i></a>
        </div>
    </div>
</section>
@endsection