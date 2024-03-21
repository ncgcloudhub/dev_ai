@extends('admin.layouts.master-without-nav')
@section('title')
    @lang('translation.landing')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
@endsection
@section('body')

    <body data-bs-spy="scroll" data-bs-target="#navbar-example">
    @endsection
    @section('content')
        <!-- Begin page -->
        <div class="layout-wrapper landing">
            <nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
                <div class="container">
                    <a class="navbar-brand" href="{{URL::asset('/index')}}">
                        <img src="{{ URL::asset('build/images/logo-dark.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="17">
                        <img src="{{ URL::asset('build/images/logo-light.png') }}" class="card-logo card-logo-light" alt="logo light"
                            height="17">
                    </a>
                    <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="mdi mdi-menu"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                            <li class="nav-item">
                                <a class="nav-link fs-15 active" href="#hero">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-15" href="#services">Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-15" href="#features">Features</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-15" href="#plans">Plans</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-15" href="#reviews">Reviews</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-15" href="#team">Team</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-15" href="#contact">Contact</a>
                            </li>
                        </ul>

                        

                        <div class="">
                            <a href="{{ route('login') }}"
                                class="btn btn-link fw-medium text-decoration-none text-dark">Sign
                                in</a>
                            <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                        </div>
                    </div>

                </div>
            </nav>
            <!-- end navbar -->
            <div class="vertical-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent.show"></div>

          

            <section class="section pb-0 hero-section" id="hero">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="">
                            <div class="card-body my-5">
                                <div class="row">
                                    <div class="col-lg-12">
                
                                        <div class="row gallery-wrapper">
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 project designing development"  data-category="designing development">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-1.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-1.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">Glasses and laptop from above</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">Ron Mackie</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 2.2K
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 1.3K
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 photography" data-category="photography">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-2.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-2.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">Working at a coffee shop</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">Nancy Martino</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 3.2K
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 1.1K
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 project development" data-category="development">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-3.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-3.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">Photo was taken in Beach</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">Elwood Arter</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 2.1K
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 1K
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 project designing" data-category="project designing">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-4.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-4.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">Drawing a sketch</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">Jason McQuaid</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 825
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 101
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 project designing" data-category="project designing">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-5.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-5.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">Working from home little spot</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">Henry Baird</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 632
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 95
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 photography" data-category="photography">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-6.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-6.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">Project discussion with team</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">Erica Kernan</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 3.4K
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 1.3k
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 project designing development"  data-category="designing development">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-7.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-7.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">Sunrise above a beach</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">James Ballard</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 735
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 150
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 photography" data-category="photography">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-8.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-8.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">Glasses and laptop from above</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">Ruby Griffin</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 1.5k
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 250
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 project designing development"  data-category="designing development">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-9.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-9.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">Dramatic clouds at the Golden Gate Bridge</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">Ron Mackie</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 2.2K
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 1.3K
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 project designing" data-category="project designing">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-10.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-10.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">Fun day at the Hill Station</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">Henry Baird</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 632
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 95
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 photography" data-category="photography">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-11.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-11.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">Cycling in the countryside</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">Nancy Martino</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 3.2K
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 1.1K
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                            <div class="element-item col-xxl-3 col-xl-4 col-sm-6 photography" data-category="photography">
                                                <div class="gallery-box card">
                                                    <div class="gallery-container">
                                                        <a class="image-popup" href="{{ URL::asset('build/images/small/img-12.jpg') }}" title="">
                                                            <img class="gallery-img img-fluid mx-auto" src="{{ URL::asset('build/images/small/img-12.jpg') }}" alt="" />
                                                            <div class="gallery-overlay">
                                                                <h5 class="overlay-caption">A mix of friends and strangers heading off to find an adventure.</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="box-content">
                                                        <div class="d-flex align-items-center mt-1">
                                                            <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">Erica Kernan</a></div>
                                                            <div class="flex-shrink-0">
                                                                <div class="d-flex gap-3">
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i> 3.4K
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                        <i class="ri-question-answer-fill text-muted align-bottom me-1"></i> 1.3k
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                        </div>
                                        <!-- end row -->
                                        <div class="text-center my-2">
                                            <a href="javascript:void(0);" class="text-primary"><i class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i> Load More </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                            </div>
                            <!-- ene card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
            </div>
            </section>
            <!-- end row -->



            <!-- Start footer -->
            <footer class="custom-footer bg-dark py-5 position-relative">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 mt-4">
                            <div>
                                <div>
                                    <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="logo light" height="17">
                                </div>
                                <div class="mt-4 fs-13">
                                    <p>Premium Multipurpose Admin & Dashboard Template</p>
                                    <p class="ff-secondary">You can build any type of web application like eCommerce,
                                        CRM, CMS, Project
                                        management apps, Admin Panels, etc using Velzon.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 ms-lg-auto">
                            <div class="row">
                                <div class="col-sm-4 mt-4">
                                    <h5 class="text-white mb-0">Company</h5>
                                    <div class="text-muted mt-3">
                                        <ul class="list-unstyled ff-secondary footer-list fs-14">
                                            <li><a href="pages-profile">About Us</a></li>
                                            <li><a href="pages-gallery">Gallery</a></li>
                                            <li><a href="apps-projects-overview">Projects</a></li>
                                            <li><a href="pages-timeline">Timeline</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-4 mt-4">
                                    <h5 class="text-white mb-0">Apps Pages</h5>
                                    <div class="text-muted mt-3">
                                        <ul class="list-unstyled ff-secondary footer-list fs-14">
                                            <li><a href="pages-pricing">Calendar</a></li>
                                            <li><a href="apps-mailbox">Mailbox</a></li>
                                            <li><a href="apps-chat">Chat</a></li>
                                            <li><a href="apps-crm-deals">Deals</a></li>
                                            <li><a href="apps-tasks-kanban">Kanban Board</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-4 mt-4">
                                    <h5 class="text-white mb-0">Support</h5>
                                    <div class="text-muted mt-3">
                                        <ul class="list-unstyled ff-secondary footer-list fs-14">
                                            <li><a href="pages-faqs">FAQ</a></li>
                                            <li><a href="pages-faqs">Contact</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row text-center text-sm-start align-items-center mt-5">
                        <div class="col-sm-6">

                            <div>
                                <p class="copy-rights mb-0">
                                    <script>
                                        document.write(new Date().getFullYear())
                                    </script> © Velzon - Themesbrand
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end mt-3 mt-sm-0">
                                <ul class="list-inline mb-0 footer-social-link">
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-facebook-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-github-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-linkedin-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-google-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-dribbble-line"></i>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end footer -->

            <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

        </div>
        <!-- end layout wrapper -->
    @endsection
    @section('script')
        <script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>
        <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>
    @endsection
