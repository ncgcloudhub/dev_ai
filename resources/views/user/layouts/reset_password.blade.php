@extends('admin.layouts.master-without-nav')
@section('title')
    @lang('translation.password-create')
@endsection
@section('content')
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index" class="d-inline-block auth-logo">
                                    <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->header_logo_dark }}" alt="" height="30">
                                </a>
                            </div>
                        
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Reset Password</h5>
                                    <p class="text-muted">Please enter your email and choose a new password.</p>
                                </div>

                                <div class="p-2">
                                    <form method="POST" action="{{ route('password.store') }}">
                                        @csrf
                                
                                        <!-- Password Reset Token -->
                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">                               

                                        <!-- Email Address -->
                                        <div class="mb-4">
                                            <label class="form-label" for="email">Email: </label>
                                            <input id="email" class="block mt-1 w-100" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username">
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-4">
                                            <label class="form-label" for="password">Password: </label>
                                            <input id="password" class="block mt-1 w-100" type="password" name="password" required autocomplete="new-password">
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mb-4">
                                            <label class="form-label" for="password_confirmation">Confirm Password: </label>
                                            <input id="password_confirmation" class="block mt-1 w-100" type="password" name="password_confirmation" required autocomplete="new-password" >
                                        </div>

                                        <div class="flex items-center justify-end mt-4">
                                            <button class="btn btn-success">
                                                Reset Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Remembered your password? <a href="{{route ('login')}}" class="fw-semibold text-primary text-decoration-underline">Sign in</a></p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © {{ $siteSettings->title }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
@endsection
@section('script')
        <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>
@endsection
