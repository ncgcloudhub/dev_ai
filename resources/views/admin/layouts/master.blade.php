<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ $siteSettings->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', $seo->description)" />
    <meta name="keywords" content="@yield('keywords', 'Default keywords if not provided in specific page')" />
    <meta content="Clever_Creator" name="author" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}">

    @section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection

{{-- Tour --}}
<link href="{{ URL::asset('build/libs/shepherd.js/css/shepherd.css')}}" rel="stylesheet" type="text/css" />

    @include('admin.layouts.head-css')

    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

@section('body')
<style>
    .gradient-button {
    background: linear-gradient(to right, rgb(10, 179, 156), rgb(64, 81, 137))
}

</style>

    @include('admin.layouts.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @if (Auth::user()->role === 'admin')
        @include('admin.layouts.topbar')
        @else
        @include('user.layouts.topbar') 
        @endif
       
       
        @if (Auth::user()->role === 'admin')
        @include('admin.layouts.sidebar')
        @else
        @include('user.layouts.sidebar') 
        @endif
        
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                                 
            @include('admin.layouts.alerts')
            
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('admin.layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    @include('admin.layouts.customizer')

    <!-- JAVASCRIPT -->
    @include('admin.layouts.vendor-scripts')

        {{-- Tour --}}
        <script src="{{ URL::asset('build/libs/shepherd.js/js/shepherd.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/tour_custom.init.js') }}"></script>
        {{-- <script src="{{ URL::asset('build/js/app.js') }}"></script> --}}
</body>

</html>
