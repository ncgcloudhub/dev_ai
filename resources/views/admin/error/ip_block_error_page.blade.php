@extends('user.layouts.master-without-nav')

@section('title')
 IP Blocked
@endsection

@section('body')
<body>
@endsection
@section('content')

    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6">
                        <div class="card overflow-hidden">
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <img src="https://img.themesbrand.com/velzon/images/auth-offline.gif" alt="" height="210">
                                    <h3 class="mt-4 fw-semibold">Access Denied: IP Address Blocked</h3>
                                    <p class="text-muted mb-4 fs-14">Your IP address has been blocked due to security reasons. If you believe this is a mistake or need assistance, please contact our support team for further assistance.</p>
                                    <a class="btn btn-success btn-border" href="{{route('contact.us')}}">Contact Support</a>
                                    <a class="btn btn-primary btn-border" href="{{route('login')}}">Login</a>
                                   
                                </div>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->
    </div>
    <!-- end auth-page-wrapper -->

    @endsection
    @section('script')
    <!-- particles js -->
    <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
    <!-- particles app js -->
    <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
    @endsection