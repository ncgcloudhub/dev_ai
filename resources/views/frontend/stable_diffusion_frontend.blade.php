@extends('admin.layouts.master-without-nav')
@section('title', $seo->title)

@section('description', $seo->description)

@section('keywords', $seo->keywords)
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('body')

<body data-bs-spy="scroll" data-bs-target="#navbar-example">
    @endsection
    @section('content')

    <!-- Begin page -->
    <div class="layout-wrapper landing">
        @include('frontend.body.nav_frontend')

        <section class=" position-relative" style="background-image: url('{{ asset('storage/stable.jpg') }}'); background-size: cover; background-position: center; height:500px">
            
        </section>
        
        {{-- <section>
            <img src="{{ asset('storage/stable.jpg') }}" height="auto" style="background-size: cover" alt="">
        </section> --}}

        <!-- start hero section -->
        <section 
    class="section job-hero-section pb-0" 
    id="hero">
    <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-6">
                        <div>
                            <h1 class="display-6 fw-bold text-capitalize mb-3 lh-base">Find your next job and build your dream here</h1>
                            <p class="lead text-muted fw-normal lh-base mb-4">Find jobs, create trackable resumes and enrich your applications. Carefully crafted after analyzing the needs of different industries.</p>
                            <form action="#" class="job-panel-filter">
                                <div class="row g-md-0 g-2">
                                    <div class="col-md-4">
                                        <div>
                                            <input type="search" id="job-title" class="form-control filter-input-box" placeholder="Job, Company name...">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-4">
                                        <div>
                                            <select class="form-control" data-choices>
                                                <option value="">Select job type</option>
                                                <option value="Full Time">Full Time</option>
                                                <option value="Part Time">Part Time</option>
                                                <option value="Freelance">Freelance</option>
                                                <option value="Internship">Internship</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-4">
                                        <div class="h-100">
                                            <button class="btn btn-primary submit-btn w-100 h-100" type="submit"><i class="ri-search-2-line align-bottom me-1"></i> Find Job</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>

                            <ul class="treding-keywords list-inline mb-0 mt-3 fs-13">
                                <li class="list-inline-item text-danger fw-semibold"><i class="mdi mdi-tag-multiple-outline align-middle"></i> Trending Keywords:</li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Design,</a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Development,</a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Manager,</a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Senior</a></li>
                            </ul>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-lg-4">
                        <div class="position-relative home-img text-center mt-5 mt-lg-0">
                            <div class="card p-3 rounded shadow-lg inquiry-box">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm flex-shrink-0 me-3">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded fs-18">
                                            <i class="ri-mail-send-line"></i>
                                        </div>
                                    </div>
                                    <h5 class="fs-15 lh-base mb-0">Cooking Your Imagination</h5>
                                </div>
                            </div>

                            <img src="https://preview.redd.it/stable-diffusion-3-v0-wvyxbi3fniqc1.png?width=1018&format=png&auto=webp&s=5a9c57565ab752c8432a58785448ef3b3df6fa22" alt="" class="user-img">

                            <div class="circle-effect">
                                <div class="circle"></div>
                                <div class="circle2"></div>
                                <div class="circle3"></div>
                                <div class="circle4"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end hero section -->

        <section class="section bg-light" id="categories">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="text-center mb-5">
                            <h2 class="mb-3 fw-bold lh-base">Trending All Categories</h2>
                            <p class="text-muted">The process of creating an NFT may cost less than a dollar, but the process of selling it can cost up to a thousand dollars. For example, Allen Gannett, a software developer.</p>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Swiper -->
                        <div class="swiper mySwiper pb-4">
                            <div class="swiper-wrapper">
                                @foreach ($images->chunk(4) as $imageChunk) <!-- Group images into chunks of 4 -->
                                <div class="swiper-slide">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row g-1 mb-3">
                                                <!-- First Column with two images -->
                                                <div class="col-6">
                                                    @foreach ($imageChunk as $image) <!-- Loop through each image chunk -->
                                                        <img src="{{ $image->image_url }}" alt="Image" class="img-fluid rounded {{ $loop->iteration == 1 ? '' : 'mt-1' }}">
                                                    @endforeach
                                                </div>
                                                <!-- Second Column with two images -->
                                                <div class="col-6">
                                                    @foreach ($imageChunk as $image) <!-- Loop through each image chunk -->
                                                        <img src="{{ $image->image_url }}" alt="Image" class="img-fluid rounded {{ $loop->iteration == 1 ? 'mb-1' : '' }}">
                                                    @endforeach
                                                </div>
                                            </div>
                                            <a href="#!" class="float-end fs-14"> View All <i class="ri-arrow-right-line align-bottom"></i></a>
                                            <h5 class="mb-0 fs-15">
                                                <a href="#!" class="text-body">Artwork <span class="badge bg-secondary-subtle text-secondary">206</span></a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                            <div class="swiper-pagination swiper-pagination-dark"></div>
                        </div>
                    </div>
                </div>
            </div><!-- end container -->
        </section>

        <!-- start features -->
        <section class="section">
            <div class="container">
                <div class="row align-items-center justify-content-lg-between justify-content-center gy-4">
                    <div class="col-lg-5 col-sm-7">
                        <div class="about-img-section mb-5 mb-lg-0 text-center">
                            <div class="card rounded shadow-lg inquiry-box d-none d-lg-block">
                                <div class="card-body d-flex align-items-center">
                                    <div class="avatar-sm flex-shrink-0 me-3">
                                        <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-18">
                                            <i class="ri-briefcase-2-line"></i>
                                        </div>
                                    </div>
                                    <h5 class="fs-15 lh-base mb-0">Search Over <span class="text-secondary fw-semibold">1,00,000+</span> Jobs</h5>
                                </div>
                            </div>

                            <div class="card feedback-box">
                                <div class="card-body d-flex shadow-lg">
                                    <div class="flex-shrink-0 me-3">
                                        <img src="{{URL::asset('build/images/users/avatar-10.jpg')}}" alt="" class="avatar-sm rounded-circle">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fs-14 lh-base mb-0">Tonya Noble</h5>
                                        <p class="text-muted fs-11 mb-1">UI/UX Designer</p>

                                        <div class="text-warning">
                                            <i class="ri-star-s-fill"></i>
                                            <i class="ri-star-s-fill"></i>
                                            <i class="ri-star-s-fill"></i>
                                            <i class="ri-star-s-fill"></i>
                                            <i class="ri-star-s-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <img src="{{URL::asset('build/images/about.jpg')}}" alt="" class="img-fluid mx-auto rounded-3" />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-muted">
                            <h1 class="mb-3 lh-base">Find Your <span class="text-primary">Dream Job</span> in One Place</h1>
                            <p class="ff-secondary fs-16 mb-2">The first step in finding your <b>dream job </b> is deciding where to look for first-hand insight. Contact professionals who are already working in industries or positions that interest you.</p>
                            <p class="ff-secondary fs-16">Schedule informational interviews and phone calls or ask for the opportunity to shadow them on the job.</p>

                            <div class="vstack gap-2 mb-4 pb-1">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2">
                                        <div class="avatar-xs icon-effect">
                                            <div class="avatar-title bg-transparent text-success rounded-circle h2">
                                                <i class="ri-check-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0">Dynamic Content</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2">
                                        <div class="avatar-xs icon-effect">
                                            <div class="avatar-title bg-transparent text-success rounded-circle h2">
                                                <i class="ri-check-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0">Setup plugin's information.</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2">
                                        <div class="avatar-xs icon-effect">
                                            <div class="avatar-title bg-transparent text-success rounded-circle h2">
                                                <i class="ri-check-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0">Themes customization information</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <a href="#!" class="btn btn-primary">Find Your Jobs <i class="ri-arrow-right-line align-bottom ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end features -->


        <!-- start cta -->
        <section class="py-5 bg-primary position-relative">
            <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-sm">
                        <div>
                            <h4 class="text-white mb-2">Ready to Started?</h4>
                            <p class="text-white-50 mb-0">Create new account and refer your friend</p>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-sm-auto">
                        <div>
                            <a href="#!" class="btn bg-gradient btn-danger">Create Free Account</a>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end cta -->

        <!-- start find jobs -->
        <section class="section">
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="text-muted mt-5 mt-lg-0">
                            <h5 class="fs-12 text-uppercase text-success">Hot Featured Company</h5>
                            <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base">Get <span class="text-primary">10,000+</span> Featured Companies</h1>
                            <p class="ff-secondary mb-2">The demand for content writing services is growing. This is because content is required in almost every industry. <b>Many companies have discovered how effective content marketing is.</b> This is a major reason for this increase in demand.</p>
                            <p class="mb-4 ff-secondary">A Content Writer is a professional who writes informative and engaging articles to help brands showcase their products.</p>

                            <div class="mt-4">
                                <a href="index" class="btn btn-primary">View More Companies <i class="ri-arrow-right-line align-middle ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-lg-4 col-sm-7 col-10 ms-lg-auto mx-auto order-1 order-lg-2">
                        <div>
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <button type="button" class="btn btn-icon btn-soft-primary float-end" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                                    <div class="avatar-sm mb-4">
                                        <div class="avatar-title bg-secondary-subtle rounded">
                                            <img src="{{URL::asset('build/images/companies/img-1.png')}}" alt="" class="avatar-xxs">
                                        </div>
                                    </div>
                                    <a href="#!">
                                        <h5>New Web designer</h5>
                                    </a>
                                    <p class="text-muted">Themesbrand</p>

                                    <div class="d-flex gap-4 mb-3">
                                        <div>
                                            <i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> Escondido,California
                                        </div>

                                        <div>
                                            <i class="ri-time-line text-primary me-1 align-bottom"></i> 3 min ago
                                        </div>
                                    </div>

                                    <p class="text-muted">As a Product Designer, you will work within a Product Delivery Team fused with UX, engineering, product and data talent.</p>

                                    <div class="hstack gap-2">
                                        <span class="badge bg-success-subtle text-success">Full Time</span>
                                        <span class="badge bg-primary-subtle text-primary">Freelance</span>
                                        <span class="badge bg-danger-subtle text-danger">Urgent</span>
                                    </div>

                                    <div class="mt-4 hstack gap-2">
                                        <a href="#!" class="btn btn-soft-primary w-100">Apply Job</a>
                                        <a href="#!" class="btn btn-soft-success w-100">Overview</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-lg bg-info mb-0 features-company-widgets rounded-3">
                                <div class="card-body">
                                    <h5 class="text-white fs-16 mb-4">10,000+ Featured Companies</h5>

                                    <div class="d-flex gap-1">
                                        <a href="javascript: void(0);" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-light bg-opacity-25 rounded-circle">
                                                    <img src="{{URL::asset('build/images/companies/img-5.png')}}" alt="" height="15">
                                                </div>
                                            </div>
                                        </a>
                                        <a href="javascript: void(0);" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-light bg-opacity-25 rounded-circle">
                                                    <img src="{{URL::asset('build/images/companies/img-2.png')}}" alt="" height="15">
                                                </div>
                                            </div>
                                        </a>
                                        <a href="javascript: void(0);" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-light bg-opacity-25 rounded-circle">
                                                    <img src="{{URL::asset('build/images/companies/img-8.png')}}" alt="" height="15">
                                                </div>
                                            </div>
                                        </a>
                                        <a href="javascript: void(0);" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-light bg-opacity-25 rounded-circle">
                                                    <img src="{{URL::asset('build/images/companies/img-7.png')}}" alt="" height="15">
                                                </div>
                                            </div>
                                        </a>
                                        <a href="javascript: void(0);" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="More Companies">
                                            <div class="avatar-xs">
                                                <div class="avatar-title fs-11 rounded-circle bg-light bg-opacity-25 text-white">
                                                    1k+
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end find jobs -->

        <!-- start cta -->
        <section class="py-5 bg-primary position-relative">
            <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-sm">
                        <div>
                            <h4 class="text-white fw-semibold">Get New Jobs Notification!</h4>
                            <p class="text-white text-opacity-75 mb-0">Subscribe & get all related jobs notification.</p>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-sm-auto">
                        <button class="btn btn-danger" type="button">Subscribe Now <i class="ri-arrow-right-line align-bottom"></i></button>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end cta -->

        <!-- Start footer -->
        @include('frontend.body.footer_frontend')

        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-info btn-icon landing-back-top" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

    </div>
    <!-- end layout wrapper -->

    @endsection
    @section('script')
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/job-lading.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/nft-landing.init.js') }}"></script>
    @endsection
