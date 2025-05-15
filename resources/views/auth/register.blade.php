@extends('admin.layouts.master-without-nav')
@section('title')
@lang('translation.signup')
@endsection
@section('content')

<script src="https://www.google.com/recaptcha/api.js"></script>

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
                                        <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->header_logo_dark }}" alt="" height="40">
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
                                        <h5 class="gradient-text-2">Create New Account</h5>
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

                                    <div class="signin-other-title mt-4 text-center">
                                        <h3 class="fs-13 title gradient-text-1">Or Sign Up With</h3>
                                    </div>

                                    <div class="p-2 mt-2">
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
                                                    <label class="form-label" for="password-input">Password<span class="text-danger">*</span></label>
                                                    <div class="position-relative auth-pass-inputgroup">
                                                        <input type="password" name="password" class="form-control pe-5 password-input" onpaste="return false" placeholder="Enter password" id="password-input" aria-describedby="passwordInput" required>
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                        <div class="invalid-feedback" id="password-invalid-feedback">Please enter a valid password</div>
                                                    </div>
                                                </div>
                                    
                                                <div class="mb-3">
                                                    <label class="form-label" for="password-input-confirm">Confirm Password<span class="text-danger">*</span></label>
                                                    <div class="position-relative auth-pass-inputgroup">
                                                        <input type="password" name="password_confirmation" class="form-control pe-5 password-input" onpaste="return false" placeholder="Confirm password" id="password-input-confirm" aria-describedby="passwordInput" required>
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon-confirm"><i class="ri-eye-fill align-middle"></i></button>
                                                        <div class="invalid-feedback" id="confirm-password-feedback">Passwords do not match.</div>
                                                    </div>
                                                </div>
                                    
                                                <input type="hidden" name="ref" value="{{ request()->query('ref') }}">
                                            </div>
                                    
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="termsAgreement" required>
                                                <label class="form-check-label" for="termsAgreement">
                                                    By continuing, I agree to Clever Creator's
                                                    <a href="#" class="gradient-text-1" data-bs-toggle="modal" data-bs-target="#termsModal">Consumer Terms</a> 
                                                    and 
                                                    <a href="#" class="gradient-text-1" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>.
                                                </label>
                                            </div>
                                    
                                            <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                                <h5 class="fs-13">Password must contain:</h5>
                                                <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8 characters</b></p>
                                                <p id="pass-lower" class="invalid fs-12 mb-2">At <b>least one lowercase</b> letter (a-z)</p>
                                                <p id="pass-upper" class="invalid fs-12 mb-2">At <b>least one uppercase</b> letter (A-Z)</p>
                                                <p id="pass-number" class="invalid fs-12 mb-0">At <b>least one number</b> (0-9)</p>
                                            </div>
                                            
                                            {{-- <div class="mb-3">
                                                <div class="g-recaptcha" data-sitekey="6LfHmYEqAAAAAAYUx-spoFfDCDt2gXRVcIcIQ3TR"></div>
                                                @if($errors->has('g-recaptcha-response'))
                                                    <div class="alert alert-danger">{{ $errors->first('g-recaptcha-response') }}</div>
                                                @endif
                                            </div> --}}

                                    
                                            <button type="submit" class="btn gradient-btn-8 w-100 g-recaptcha" id="signUpButton" disabled   data-sitekey="6LdVl4EqAAAAAC5LVhDSc5Cx2L6UaV7-uNm7jqRb" 
                                            data-callback='onSubmit' 
                                            data-action='submit'>Sign Up</button>
                                        </form>
                                    
                                        <script>
                                            const passwordInput = document.getElementById('password-input');
                                            const confirmPasswordInput = document.getElementById('password-input-confirm');
                                            const signUpButton = document.getElementById('signUpButton');
                                            const passwordFeedback = document.getElementById('password-invalid-feedback');
                                            const confirmPasswordFeedback = document.getElementById('confirm-password-feedback');
                                    
                                            const passLength = document.getElementById('pass-length');
                                            const passLower = document.getElementById('pass-lower');
                                            const passUpper = document.getElementById('pass-upper');
                                            const passNumber = document.getElementById('pass-number');
                                    
                                            passwordInput.addEventListener('input', validatePassword);
                                            confirmPasswordInput.addEventListener('input', validatePasswordsMatch);
                                    
                                            function validatePassword() {
                                                const passwordValue = passwordInput.value;
                                                let isValid = true;
                                    
                                                // Check password length
                                                if (passwordValue.length >= 8) {
                                                    passLength.classList.remove('invalid');
                                                    passLength.classList.add('valid');
                                                } else {
                                                    passLength.classList.add('invalid');
                                                    isValid = false;
                                                }
                                    
                                                // Check for lowercase
                                                if (/[a-z]/.test(passwordValue)) {
                                                    passLower.classList.remove('invalid');
                                                    passLower.classList.add('valid');
                                                } else {
                                                    passLower.classList.add('invalid');
                                                    isValid = false;
                                                }
                                    
                                                // Check for uppercase
                                                if (/[A-Z]/.test(passwordValue)) {
                                                    passUpper.classList.remove('invalid');
                                                    passUpper.classList.add('valid');
                                                } else {
                                                    passUpper.classList.add('invalid');
                                                    isValid = false;
                                                }
                                    
                                                // Check for number
                                                if (/[0-9]/.test(passwordValue)) {
                                                    passNumber.classList.remove('invalid');
                                                    passNumber.classList.add('valid');
                                                } else {
                                                    passNumber.classList.add('invalid');
                                                    isValid = false;
                                                }
                                    
                                                if (!isValid) {
                                                    passwordFeedback.innerHTML = "Password does not meet requirements.";
                                                    passwordFeedback.style.display = 'block';
                                                    signUpButton.disabled = true;
                                                } else {
                                                    passwordFeedback.style.display = 'none';
                                                    validatePasswordsMatch();
                                                }
                                            }
                                    
                                            function validatePasswordsMatch() {
                                                const passwordValue = passwordInput.value;
                                                const confirmPasswordValue = confirmPasswordInput.value;
                                    
                                                if (passwordValue !== confirmPasswordValue) {
                                                    confirmPasswordFeedback.innerHTML = "Passwords do not match.";
                                                    confirmPasswordFeedback.style.display = 'block';
                                                    signUpButton.disabled = true;
                                                } else {
                                                    confirmPasswordFeedback.style.display = 'none';
                                                    // Only enable the submit button if the password meets all requirements and the passwords match
                                                    if (passwordFeedback.style.display === 'none' && confirmPasswordValue !== '') {
                                                        signUpButton.disabled = false;
                                                    }
                                                }
                                            }
                                    
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
                                <p class="mb-0">Already have an account ? <a href="{{ route('login') }}" class="fw-semibold gradient-text-2 text-decoration-underline"> Signin </a> </p>
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

        @php
            $termConditions = \App\Models\TermsConditions::all();
            $latesttermConditions = \App\Models\TermsConditions::latest()->first();
            $lastUpdateDate = $latesttermConditions ? $latesttermConditions->created_at->format('d M, Y') : 'Unknown';
        @endphp
         <!-- Terms and Conditions Modal -->
         <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="termsModalTitle">Terms and Conditions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <section class="section" id="contact">
                            <div class="row justify-content-center">
                                <div class="col-lg-10">
                                    <div class="card">
                                        <div class="bg-primary-subtle position-relative">
                                            <div class="card-body p-5">
                                                <div class="text-center">
                                                    <h3 class="fw-semibold">Term & Conditions</h3>
                                                    <p class="mb-0 text-muted">Last update:  {{ $lastUpdateDate }}</p>
                                                </div>
                                            </div>
                                            <div class="shape">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="1440" height="60" preserveAspectRatio="none" viewBox="0 0 1440 60">
                                                    <g mask="url(&quot;#SvgjsMask1001&quot;)" fill="none">
                                                        <path d="M 0,4 C 144,13 432,48 720,49 C 1008,50 1296,17 1440,9L1440 60L0 60z" style="fill: var(--vz-secondary-bg);"></path>
                                                    </g>
                                                    <defs>
                                                        <mask id="SvgjsMask1001">
                                                            <rect width="1440" height="60" fill="#ffffff"></rect>
                                                        </mask>
                                                    </defs>
                                                </svg>
                                            </div>
                                        </div>
            
                                        <div class="card-body p-4">
                                            <div>
                                                <h5>Welcome to {{ $siteSettings->title }}!</h5>
                                              
                                            </div>
            
                                            <div>
                                                @foreach ($termConditions as $item)
                                                    {!! $item->details !!}    
                                                @endforeach
                                                
                                            </div>
                            
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                        </section>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        @php
            $privacy_policy = \App\Models\PrivacyPolicy::orderBy('id', 'asc')->get();
            $latestPrivacyPolicy = \App\Models\PrivacyPolicy::latest()->first();
            $lastUpdateDate = $latestPrivacyPolicy ? $latestPrivacyPolicy->created_at->format('d M, Y') : 'Unknown';
        @endphp
        <!-- Privacy Policy Modal -->
        <div class="modal fade" id="privacyModal" tabindex="-1" role="dialog" aria-labelledby="privacyModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="privacyModalTitle">Privacy Policy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <section class="section" id="contact">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-lg-10">
                                        <div class="card">
                                            <div class="bg-primary-subtle position-relative">
                                                <div class="card-body p-5">
                                                    <div class="text-center">
                                                        <h3 class="fw-semibold">Privacy Policy</h3>
                                                        <p class="mb-0 text-muted">Last update: {{ $lastUpdateDate }}</p>
                                                    </div>
                                                </div>
                                                <div class="shape">
                                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="1440" height="60" preserveAspectRatio="none" viewBox="0 0 1440 60">
                                                        <g mask="url(&quot;#SvgjsMask1001&quot;)" fill="none">
                                                            <path d="M 0,4 C 144,13 432,48 720,49 C 1008,50 1296,17 1440,9L1440 60L0 60z" style="fill: var(--vz-secondary-bg);"></path>
                                                        </g>
                                                        <defs>
                                                            <mask id="SvgjsMask1001">
                                                                <rect width="1440" height="60" fill="#ffffff"></rect>
                                                            </mask>
                                                        </defs>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <i data-feather="check-circle" class="text-success icon-dual-success icon-xs"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5>Privacy Policy for Clever Creator</h5>
                                                        @foreach ($privacy_policy as $item)
                                                            {!! $item->details !!}
                                                        @endforeach
                                                    </div>
                                                </div>
                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </section>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        @endsection
        @section('script')
            <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
            <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
            <script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>
            <script src="{{ URL::asset('build/js/pages/passowrd-create.init.js') }}"></script>

            <script>
                document.getElementById('termsAgreement').addEventListener('change', function() {
                    document.getElementById('signUpButton').disabled = !this.checked;
                });
            </script>

<script>
    function onSubmit(token) {
      document.getElementById("registrationForm").submit();
    }
  </script>
            
        @endsection

