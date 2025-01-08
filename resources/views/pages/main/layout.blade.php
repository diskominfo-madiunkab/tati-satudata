<!DOCTYPE html>
<html lang="en">

@include('pages.partial.header')

<body>
    {{-- @php
    dd(auth()->user()->hasRole('pembina'));
    @endphp --}}
    @include('pages.partial.navbar')

    @if(Auth::user()->role_id == 1)
    @include('pages.partial.sidebar-administrator')
    @elseif(Auth::user()->role_id == '2' || Auth::user()->role_id == 4 || Auth::user()->role_id == 5 ||
    auth()->user()->hasRole('walidatapendukung'))
    @include('pages.partial.sidebar-walidata')
    @elseif(Auth::user()->role_id == '3')
    @include('pages.partial.sidebar-produsen')
    @endif

    {{-- content --}}
    <main id="main" class="main">
        @yield('content')
    </main>

    @include('pages.partial.footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script>
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}});const Toast = Swal.mixin({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, didOpen: (toast) => {toast.addEventListener('mouseenter', Swal.stopTimer);toast.addEventListener('mouseleave', Swal.resumeTimer)}});
    </script>
    <script src="{{ asset('js/ckeditor.js') }}"></script>
    <!-- Vendor JS Files -->
    <script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/chart.js/chart.min.js')}}"></script>
    <script src="{{asset('assets/vendor/echarts/echarts.min.js')}}"></script>
    <script src="{{asset('assets/vendor/quill/quill.min.js')}}"></script>
    <script src="{{asset('assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
    <script src="{{asset('assets/vendor/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>

    <!-- Template Main JS File -->
    <script src="{{asset('assets/js/main.js')}}"></script>
    @stack('js')

</body>

</html>