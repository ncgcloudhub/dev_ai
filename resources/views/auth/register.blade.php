@extends('admin.layouts.master-without-nav')
@section('title')
@lang('translation.signup')
@endsection
@section('content')

        <div class="auth-page-wrapper pt-5">
            <!-- auth page bg -->
            <div class="auth-one-bg-position auth-one-bg"  id="auth-particles">
                <div class="bg-overlay"></div>

                <div class="shape">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
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
                                    <a href="{{route('home')}}" class="d-inline-block auth-logo">
                                        <img src="{{ asset('backend/uploads/site/' . $siteSettings->header_logo_dark) }}" alt="" height="40">
                                    </a>
                                </div>
                                {{-- <p class="mt-3 fs-15 fw-medium">Premium Admin & Dashboard Template</p> --}}
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="card mt-4">

                                <div class="card-body p-4">
                                    <div class="text-center mt-2">
                                        <h5 class="text-primary">Create New Account</h5>
                                        <p class="text-muted">Get started with Clever Creator</p>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('google.login') }}" class="btn btn-danger btn-icon waves-effect waves-light w-100">
                                            <i class="ri-google-fill fs-16 mx-2"></i> Sign Up with Google
                                        </a>
                                    </div>
                                  
                                    <div class="mt-2">
                                        <a href="{{ route('github.login') }}" class="btn btn-dark btn-icon waves-effect waves-light w-100">
                                            <i class="ri-github-fill fs-16 mx-2"></i> Sign Up with GitHub
                                        </a>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <form method="POST" class="needs-validation" novalidate action="{{ route('register') }}" id="registrationForm">
                                            @csrf
                                        
                                            <div class="mb-3">
                                                <label for="useremail" class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" id="useremail" placeholder="Enter email address" required>
                                                <div class="invalid-feedback">Please enter an email</div>
                                                @if($errors->has('email'))
                                                    <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                                        <i class="ri-error-warning-line label-icon"></i><strong>Error</strong> -{{ $errors->first('email') }}
                                                    </div>
                                                @endif
                                            </div>
                                        
                                            <div id="otherFields" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" id="username" placeholder="Enter your name" required>
                                                    <div class="invalid-feedback">Please enter your name</div>
                                                </div>
                                        
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                                    <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" required>
                                                    <div class="invalid-feedback">Please enter username</div>
                                                </div>
                                        
                                                <div class="mb-3">
                                                    <label class="form-label" for="password-input">Password<span class="text-danger">*</span></label>
                                                    <div class="position-relative auth-pass-inputgroup">
                                                        <input type="password" name="password" class="form-control pe-5 password-input" onpaste="return false" placeholder="Enter password" id="password-input" aria-describedby="passwordInput" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                        <div class="invalid-feedback">Please enter password</div>
                                                    </div>
                                                </div>
                                        
                                                <div class="mb-3">
                                                    <label class="form-label" for="password-input-confirm">Confirm Password<span class="text-danger">*</span></label>
                                                    <div class="position-relative auth-pass-inputgroup">
                                                        <input type="password" name="password_confirmation" class="form-control pe-5 password-input" onpaste="return false" placeholder="Enter password" id="password-input-confirm" aria-describedby="passwordInput" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon-confirm"><i class="ri-eye-fill align-middle"></i></button>
                                                        <div class="invalid-feedback">Please enter password</div>
                                                    </div>
                                                </div>
                                        
                                                <input type="hidden" name="ref" value="{{ request()->query('ref') }}">
                                            </div>
                                        
                                            <button type="submit" class="btn btn-success w-100">Sign Up</button>
                                        </form>
                                        
                                        <script>
                                            document.getElementById('useremail').addEventListener('input', function() {
                                                var email = this.value.trim();
                                                var otherFields = document.getElementById('otherFields');
                                        
                                                if (email !== '') {
                                                    otherFields.style.display = 'block';
                                                } else {
                                                    otherFields.style.display = 'none';
                                                }
                                            });
                                        </script>
                                        

                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->

                            <div class="mt-4 text-center">
                                
                         @include('admin.layouts.alerts')
                                <p class="mb-0">Already have an account ? <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
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
                                <p class="mb-0 text-muted">&copy; <script>document.write(new Date().getFullYear())</script> Clever Creator. Crafted with <i class="mdi mdi-heart text-danger"></i> by Clever Creator</p>
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
            <script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>
            <script src="{{ URL::asset('build/js/pages/passowrd-create.init.js') }}"></script>
        @endsection

