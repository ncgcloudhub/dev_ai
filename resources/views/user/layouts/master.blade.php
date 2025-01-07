<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="gradient" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ $siteSettings->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Clever Creator" name="description" />
    <meta content="Clever_Creator" name="author" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}">
    @include('user.layouts.head-css')

</head>

@section('body')
    @include('user.layouts.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('user.layouts.topbar')
        @if (Auth::user()->role === 'user')
        @include('user.layouts.sidebar')
        @else
        @include('admin.layouts.sidebar') 
        @endif
        
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('user.layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    @include('user.layouts.customizer')

    <!-- JAVASCRIPT -->
    @include('admin.layouts.vendor-scripts')



</body>

</html>
