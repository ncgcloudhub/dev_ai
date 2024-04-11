<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ $siteSettings->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Clever Creator" name="description" />
    <meta content="Clever_Creator" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}">

    @section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection

    @include('admin.layouts.head-css')

    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

@section('body')
    @include('admin.layouts.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('admin.layouts.topbar')
       
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

</body>


</html>
