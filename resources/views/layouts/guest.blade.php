<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!--Bootstrap Css-->
    <link rel="stylesheet" type="text/css" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <!--Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{asset('frontend/css/font-awesome.min.css')}}">

    <!--Animeted Css-->
    <link rel="stylesheet" href="{{asset('frontend/css/animate.min.css')}}" />
    <!-- Custom Css -->
    <link rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />
    <link rel="stylesheet" href="{{asset('frontend/css/file-upload.css')}}" />
    <!-- toastr alert -->
    <link rel="stylesheet" href="{{asset('notification_assets/css/toastr.min.css')}}" />
    @stack('css')

</head>

<body>

    <!-- Start Content -->
    @yield('content')
    <!-- End Content -->

    <!-- Start Footer -->
    @include('includes.footer')
    <!-- End Footer -->

    <!-------Plugin js------->
    <script src="{{asset('jquery/jquery.min.js')}}"></script>
    <script src="{{asset('jquery/popper.min.js')}}"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>


    <script src="{{asset('frontend/js/script.js')}}"></script>
    <script src="{{asset('frontend/js/file-upload.js')}}"></script>

    <script src="{{ asset('backend/assets/js/logout.js') }}"></script>
    <!-- toastr alert -->
    <script src="{{asset('notification_assets/js/toastr.min.js')}}"></script>
    <!-- sweet alert -->
    <script src="{{asset('notification_assets/js/sweetalert.min.js')}}"></script>

    @include('layouts.toster-script')
    @stack('modals')

    @stack('scripts')




</body>

</html>
