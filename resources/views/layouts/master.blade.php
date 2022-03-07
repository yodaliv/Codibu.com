<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
          content="Volgh –  Bootstrap 4 Responsive Application Admin panel Theme Ui Kit & Premium Dashboard Design Modern Flat HTML Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
          content="analytics dashboard, bootstrap 4 web app admin template, bootstrap admin panel, bootstrap admin template, bootstrap dashboard, bootstrap panel, Application dashboard design, dashboard design template, dashboard jquery clean html, dashboard template theme, dashboard responsive ui, html admin backend template ui kit, html flat dashboard template, it admin dashboard ui, premium modern html template">
    <link href="{{ URL::asset('assets/css/loader.css')}}" rel="stylesheet"/>
    @include('layouts.head')
</head>

<body>
<!-- GLOBAL-LOADER -->
<div id="global-loader">
    <img src="{{asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader">
</div>
<!-- /GLOBAL-LOADER -->
<!-- PAGE -->
<div class="page">
    <div class="page-main">
        @include('layouts.header')
        @include('layouts.horizontal-main')
        @include('layouts.mobile-header')
        <div class="container app-content" style="min-height: 85vh">
            <div class="">
                @yield('page-header')
                @include('layouts.success-error-message')
                @yield('content')
            </div>
        </div>
        @auth
            @include('layouts.sidebar')
        @endauth
        @include('layouts.footer')
    </div>
@include('layouts.footer-scripts')
</body>
</html>
