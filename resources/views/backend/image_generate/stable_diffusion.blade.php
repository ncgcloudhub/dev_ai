@extends('admin.layouts.master')
@section('title')
    @lang('translation.search-results')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">

@endsection
@section('content')
    @component('admin.components.breadcrumb')
        @slot('li_1')
            Pages
        @endslot
        @slot('title')
            Search Results
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row justify-content-center mb-4">
                        
                        <div class="col-lg-6">
                            <form id="imageForm" action="{{ route('stable.image') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                            <div class="row g-2">
                                <div class="col">
                                    <div class="position-relative mb-3">
                                        <input type="text" class="form-control form-control-lg bg-light border-light"
                                            placeholder="Search here.." name="prompt" id="prompt">
                                        {{-- <a class="btn btn-link link-success btn-lg position-absolute end-0 top-0"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                                            aria-controls="offcanvasExample"><i class="ri-mic-fill"></i></a> --}}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light"><i
                                        class="mdi mdi-magnify me-1"></i> Generate</button>
                                </div>
                            </div>
                        </form>
                        </div>
                        <!--end col-->
                        <div class="col-lg-12" id="promptContainer" style="display: none;">
                            <h5 class="fs-16 fw-semibold text-center mb-0">
                                Showing results for "<span id="promptDisplay" class="text-primary fw-medium fst-italic">Prompt</span>"
                            </h5>
                            <div id="responseMessage"></div> <!-- For displaying success/error messages -->
                        </div>
                    
                    </div>
                    <!--end row-->

                    <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasExample"
                        aria-labelledby="offcanvasExampleLabel">
                        <div class="offcanvas-body">
                            <button type="button" class="btn-close text-reset float-end" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                            <div class="d-flex flex-column h-100 justify-content-center align-items-center">
                                <div class="search-voice">
                                    <i class="ri-mic-fill align-middle"></i>
                                    <span class="voice-wave"></span>
                                    <span class="voice-wave"></span>
                                    <span class="voice-wave"></span>
                                </div>
                                <h4>Talk to me, what can I do for you?</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#all" role="tab" aria-selected="false">
                                <i class="ri-search-2-line text-muted align-bottom me-1"></i>Result
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" id="images-tab" href="#images" role="tab"
                                aria-selected="true">
                                <i class="ri-image-fill text-muted align-bottom me-1"></i> Images
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#news" role="tab" aria-selected="false">
                                <i class="ri-list-unordered text-muted align-bottom me-1"></i>Examples
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#video" role="tab" aria-selected="false">
                                <i class="ri-video-line text-muted align-bottom me-1"></i> Videos
                            </a>
                        </li>
                        <li class="nav-item ms-auto">
                            <div class="dropdown">
                                <a class="nav-link fw-medium text-reset mb-n1" href="#" role="button" id="dropdownMenuLink1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-settings-4-line align-middle me-1"></i> Settings
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                    <li><a class="dropdown-item" href="#">Advanced Search</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="all" role="tabpanel">
                            <div id="imageContainer" class="pb-3">
                                
                            </div>

                        </div>

                        <div class="tab-pane" id="images" role="tabpanel">
                           
                            <div class="gallery-light">
                                <div class="row">
                                    @foreach($images as $item)
                                        <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="gallery-box card">
                                            <div class="gallery-container">
                                                <a class="image-popup" href="{{ $item->image_url }}" title="">
                                                    <img class="gallery-img img-fluid mx-auto"
                                                        src="{{ $item->image_url }}" alt="" />
                                                    <div class="gallery-overlay">
                                                        <h5 class="overlay-caption">Glasses and laptop from above</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="box-content">
                                                <div class="d-flex align-items-center mt-2">
                                                    <div class="flex-grow-1 text-muted">by <a href=""
                                                            class="text-body text-truncate">Ron Mackie</a></div>
                                                    <div class="flex-shrink-0">
                                                        <div class="d-flex gap-3">
                                                            <button type="button"
                                                                class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                <i
                                                                    class="ri-thumb-up-fill text-muted align-bottom me-1"></i>
                                                                2.2K
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                <i
                                                                    class="ri-question-answer-fill text-muted align-bottom me-1"></i>
                                                                1.3K
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!--end row-->
                                <div class="mt-4">
                                    <ul class="pagination pagination-separated justify-content-center mb-0">
                                        <li class="page-item disabled">
                                            <a href="javascript:void(0);" class="page-link"><i
                                                    class="mdi mdi-chevron-left"></i></a>
                                        </li>
                                        <li class="page-item active">
                                            <a href="javascript:void(0);" class="page-link">1</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript:void(0);" class="page-link">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript:void(0);" class="page-link">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript:void(0);" class="page-link">4</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript:void(0);" class="page-link">5</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript:void(0);" class="page-link"><i
                                                    class="mdi mdi-chevron-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="news" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-1.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-success-subtle text-success fs-11">Business</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">A mix of friends and strangers heading
                                                            off to find an adventure</a></h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            James Ballard</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            23 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-2.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-warning-subtle text-warning fs-11">Development</span>
                                                        </li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">How to get creative in your work ?</a>
                                                    </h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Ruby Griffin</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            23 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-3.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-info-subtle text-info fs-11">Fashion</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">How to become a best sale marketer in
                                                            a year!</a></h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Elwood Arter</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            23 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-4.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-primary-subtle text-primary fs-11">Product</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">Manage white space in responsive
                                                            layouts ?</a></h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Nancy Martino</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            23 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-5.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-success-subtle text-success fs-11">Business</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">Stack designer Olivia Murphy offers
                                                            freelancing advice</a></h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Erica Kernan</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            11 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-6.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-danger-subtle text-danger fs-11">Design</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">A day in the of a professional fashion
                                                            designer</a></h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Jason McQuaid</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            14 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-7.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-danger-subtle text-danger fs-11">Design</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">Design your apps in your own way</a>
                                                    </h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Henry Baird</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            19 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-8.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-warning-subtle text-warning fs-11">Development</span>
                                                        </li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">How apps is changing the IT world</a>
                                                    </h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Elwood Arter</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            10 Aug, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->

                            <div class="mt-4">
                                <ul class="pagination pagination-separated justify-content-center mb-0">
                                    <li class="page-item disabled">
                                        <a href="javascript:void(0);" class="page-link"><i
                                                class="mdi mdi-chevron-left"></i></a>
                                    </li>
                                    <li class="page-item active">
                                        <a href="javascript:void(0);" class="page-link">1</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="javascript:void(0);" class="page-link">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="javascript:void(0);" class="page-link">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="javascript:void(0);" class="page-link">4</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="javascript:void(0);" class="page-link">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="javascript:void(0);" class="page-link"><i
                                                class="mdi mdi-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="video" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12 video-list">
                                    <div class="list-element">
                                        <h5 class="mb-1"><a href="javascript:void(0);">Admin dashboard templates
                                                - Material Design for Velzon</a></h5>
                                        <p class="text-success">https://themesbrand.com/velzon/index.html</p>
                                        <div class="d-flex flex-column flex-sm-row">
                                            <div class="flex-shrink-0">
                                                <iframe src="https://www.youtube.com/embed/GfSZtaoc5bw"
                                                    title="YouTube video" allowfullscreen class="rounded"></iframe>
                                            </div>
                                            <div class="flex-grow-1 ms-sm-3 mt-2 mt-sm-0">
                                                <p class="text-muted mb-0">Velzon admin is super flexible, powerful, clean,
                                                    modern & responsive admin template based on <b>bootstrap 5</b> stable
                                                    with unlimited possibilities. You can simply change to any layout or
                                                    mode by changing a couple of lines of code. You can start small and
                                                    large projects or update design in your existing project using Velzon it
                                                    is very quick and easy as it is beautiful, adroit, and delivers the
                                                    ultimate user experience.</p>
                                                <div class="border border-dashed mb-1 mt-3"></div>
                                                <ul
                                                    class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-thumb-up-line align-middle me-1"></i>335</li>
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-question-answer-line align-middle me-1"></i>102</li>
                                                    <li class="list-inline-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-user-line"></i>
                                                            </div>
                                                            <div class="flex-grow-1 fs-13 ms-1">
                                                                <span class="fw-medium">Themesbrand</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end list-element-->

                                    <div class="list-element mt-4">
                                        <h5 class="mb-1"><a href="javascript:void(0);">Create Responsive Admin
                                                Dashboard using Html CSS</a></h5>
                                        <p class="text-success">https://themesbrand.com/velzon/index.html</p>
                                        <div class="d-flex flex-column flex-sm-row">
                                            <div class="flex-shrink-0">
                                                <iframe src="https://www.youtube.com/embed/Z-fV2lGKnnU"
                                                    title="YouTube video" allowfullscreen class="rounded"></iframe>
                                            </div>
                                            <div class="flex-grow-1 ms-sm-3 mt-2 mt-sm-0">
                                                <p class="text-muted mb-0">Velzon admin is super flexible, powerful, clean,
                                                    modern & responsive admin template based on <b>bootstrap 5</b> stable
                                                    with unlimited possibilities. You can simply change to any layout or
                                                    mode by changing a couple of lines of code. You can start small and
                                                    large projects or update design in your existing project using Velzon it
                                                    is very quick and easy as it is beautiful, adroit, and delivers the
                                                    ultimate user experience.</p>
                                                <div class="border border-dashed mb-1 mt-3"></div>
                                                <ul
                                                    class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-thumb-up-line align-middle me-1"></i>485</li>
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-question-answer-line align-middle me-1"></i>167</li>
                                                    <li class="list-inline-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-user-line"></i>
                                                            </div>
                                                            <div class="flex-grow-1 fs-13 ms-1">
                                                                <span class="fw-medium">Themesbrand</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end list-element-->

                                    <div class="list-element mt-4">
                                        <h5 class="mb-1"><a href="javascript:void(0);">Velzon - The Most Popular
                                                Bootstrap 5 HTML, Angular & React Js Admin</a></h5>
                                        <p class="text-success">https://themesbrand.com/velzon/index.html</p>
                                        <div class="d-flex flex-column flex-sm-row">
                                            <div class="flex-shrink-0">
                                                <iframe src="https://www.youtube.com/embed/2RZQN_ko0iU"
                                                    title="YouTube video" allowfullscreen class="rounded"></iframe>
                                            </div>
                                            <div class="flex-grow-1 ms-sm-3 mt-2 mt-sm-0">
                                                <p class="text-muted mb-0">Velzon admin is super flexible, powerful, clean,
                                                    modern & responsive admin template based on <b>bootstrap 5</b> stable
                                                    with unlimited possibilities. You can simply change to any layout or
                                                    mode by changing a couple of lines of code. You can start small and
                                                    large projects or update design in your existing project using Velzon it
                                                    is very quick and easy as it is beautiful, adroit, and delivers the
                                                    ultimate user experience.</p>
                                                <div class="border border-dashed mb-1 mt-3"></div>
                                                <ul
                                                    class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-thumb-up-line align-middle me-1"></i>122</li>
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-question-answer-line align-middle me-1"></i>51</li>
                                                    <li class="list-inline-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-user-line"></i>
                                                            </div>
                                                            <div class="flex-grow-1 fs-13 ms-1">
                                                                <span class="fw-medium">Themesbrand</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end list-element-->

                                    <div class="list-element mt-4">
                                        <h5 class="mb-1"><a href="javascript:void(0);">Velzon Admin Dashboard
                                                (website analytics) with Bootstrap 5</a></h5>
                                        <p class="text-success">https://themesbrand.com/velzon/index.html</p>
                                        <div class="d-flex flex-column flex-sm-row">
                                            <div class="flex-shrink-0">
                                                <iframe src="https://www.youtube.com/embed/Z-fV2lGKnnU"
                                                    title="YouTube video" allowfullscreen class="rounded"></iframe>
                                            </div>
                                            <div class="flex-grow-1 ms-sm-3 mt-2 mt-sm-0">
                                                <p class="text-muted mb-0">Velzon admin is super flexible, powerful, clean,
                                                    modern & responsive admin template based on <b>bootstrap 5</b> stable
                                                    with unlimited possibilities. You can simply change to any layout or
                                                    mode by changing a couple of lines of code. You can start small and
                                                    large projects or update design in your existing project using Velzon it
                                                    is very quick and easy as it is beautiful, adroit, and delivers the
                                                    ultimate user experience.</p>
                                                <div class="border border-dashed mb-1 mt-3"></div>
                                                <ul
                                                    class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-thumb-up-line align-middle me-1"></i>485</li>
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-question-answer-line align-middle me-1"></i>69</li>
                                                    <li class="list-inline-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-user-line"></i>
                                                            </div>
                                                            <div class="flex-grow-1 fs-13 ms-1">
                                                                <span class="fw-medium">Themesbrand</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end list-element-->

                                    <div class="list-element mt-4">
                                        <h5 class="mb-1"><a href="javascript:void(0);">Dashboard Admin Basics -
                                                YouTube</a></h5>
                                        <p class="text-success">https://themesbrand.com/velzon/index.html</p>
                                        <div class="d-flex flex-column flex-sm-row">
                                            <div class="flex-shrink-0">
                                                <iframe src="https://www.youtube.com/embed/1y_kfWUCFDQ"
                                                    title="YouTube video" allowfullscreen class="rounded"></iframe>
                                            </div>
                                            <div class="flex-grow-1 ms-sm-3 mt-2 mt-sm-0">
                                                <p class="text-muted mb-0">Velzon admin is super flexible, powerful, clean,
                                                    modern & responsive admin template based on <b>bootstrap 5</b> stable
                                                    with unlimited possibilities. You can simply change to any layout or
                                                    mode by changing a couple of lines of code. You can start small and
                                                    large projects or update design in your existing project using Velzon it
                                                    is very quick and easy as it is beautiful, adroit, and delivers the
                                                    ultimate user experience.</p>
                                                <div class="border border-dashed mb-1 mt-3"></div>
                                                <ul
                                                    class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-thumb-up-line align-middle me-1"></i>58</li>
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-question-answer-line align-middle me-1"></i>24</li>
                                                    <li class="list-inline-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-user-line"></i>
                                                            </div>
                                                            <div class="flex-grow-1 fs-13 ms-1">
                                                                <span class="fw-medium">Themesbrand</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end list-element-->

                                </div>
                                <!--end col-->
                                <div class="text-center">
                                    <button id="loadmore" class="btn btn-link text-success mt-2"><i
                                            class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i> Load more
                                    </button>
                                </div>
                            </div>
                            <!--end row-->
                        </div>
                        <!--end tab-pane-->
                    </div>
                    <!--end tab-content-->

                </div>
                <!--end card-body-->
            </div>
            <!--end card -->
        </div>
        <!--end card -->
    </div>
    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>

    <!-- swiper js -->
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- search-result init js -->
    <script src="{{ URL::asset('build/js/pages/search-result.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#imageForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        
        $.ajax({
            url: $(this).attr('action'), // Use the form's action URL
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            success: function(response) {
               
                var promptValue = $('#prompt').val();
                $('#promptDisplay').text(promptValue); 

                // Show the prompt container
                $('#promptContainer').show();

                // Show success message
                $('#responseMessage').html('<p class="text-success">Image generated successfully!</p>');


                // Display the image based on image_url or image_base64
                if (response.image_url) {
                    $('#imageContainer').html('<img src="' + response.image_url + '" alt="Generated Image" style="max-width:100%;">');
                } else if (response.image_base64) {
                    $('#imageContainer').html('<img src="data:image/jpeg;base64,' + response.image_base64 + '" alt="Generated Image" style="max-width:100%;">');
                }
            },

            error: function(xhr) {
                // Handle errors
                $('#responseMessage').html('<p>Error generating image. Please try again.</p>');
            }
        });
    });
});
</script>

@endsection
