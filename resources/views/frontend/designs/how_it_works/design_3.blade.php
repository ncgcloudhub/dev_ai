<section class="section bg-info py-5">
    <div class="container">  
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h1 class="mb-3 fw-semibold lh-base">How <span class="text-primary">it works</span> </h1>
                        <p class="text-muted">Experience our streamlined process: Register an account, select a template, input your brand details, and leverage advanced options to generate tailored content effortlessly.</p>
                    </div>
                </div>
                <!-- end col -->
            </div>
      
        <!--end row-->
        <div class="row">
            
            <div class="col-lg-3 col-md-6">
                <a href="{{ Auth::check() ? (Auth::user()->role == 'admin' ? route('admin.dashboard') : route('user.dashboard')) : route('login') }}">                                
                
                <div class="card shadow-lg h-100">
                    <div class="card-body p-4">
                        <h1 class="fw-bold display-5 ff-secondary mb-4 text-success position-relative">
                            <div class="job-icon-effect"></div>
                            <span>1</span>
                        </h1>
                        <h6 class="fs-17 mb-2">Register Account</h6>
                        <p class="text-muted mb-0 fs-15">First, You need to make a account.</p>
                    </div>
                </div>
            </a>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <a href="{{ auth()->check() ? route('template.manage') : route('login') }}">
                <div class="card shadow-lg h-100">
                    <div class="card-body p-4">
                        <h1 class="fw-bold display-5 ff-secondary mb-4 text-success position-relative">
                            <div class="job-icon-effect"></div>
                            <span>2</span>
                        </h1>
                        <h6 class="fs-17 mb-2">Select Template</h6>
                        <p class="text-muted mb-0 fs-15">Select the template first that you want to generate</p>
                    </div>
                </div>
            </a>
            </div>


            <div class="col-lg-3 col-md-6">
                <a href="{{ auth()->check() ? route('template.manage') : route('login') }}">
                <div class="card shadow-lg h-100">
                    <div class="card-body p-4">
                        <h1 class="fw-bold display-5 ff-secondary mb-4 text-success position-relative">
                            <div class="job-icon-effect"></div>
                            <span>3</span>
                        </h1>

                        <h6 class="fs-17 mb-2">Write prompt</h6>
                        <p class="text-muted mb-0 fs-15">Enter a few sentence about your brand and product</p>
                    </div>
                </div>
            </a>
            </div>


            <div class="col-lg-3 col-md-6">
                <a href="{{ auth()->check() ? route('template.manage') : route('login') }}">
                <div class="card shadow-lg h-100">
                    <div class="card-body p-4">
                        <h1 class="fw-bold display-5 ff-secondary mb-4 text-success position-relative">
                            <div class="job-icon-effect"></div>
                            <span>4</span>
                        </h1>
                        <h6 class="fs-17 mb-2">Select Advance option and Generate</h6>
                        <p class="text-muted mb-0 fs-15">Multile option for each campaign that you're working on</p>
                    </div>
                </div>
            </a>
            </div>
        </div>
    </div>
    <!--end container-->
</section>