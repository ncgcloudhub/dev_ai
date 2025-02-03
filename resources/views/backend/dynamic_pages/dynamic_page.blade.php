@extends('admin.layouts.master-without-nav')
@section('title', $page->seo_title)

@section('description', $page->description)

@section('keywords', $page->keywords)
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .dynamic-content img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* Fix indentation for ordered lists in dynamic content */
        .dynamic-content ol,
        .dynamic-content ul {
            margin-left: 20px; /* Adjust as needed for better spacing */
            padding-left: 20px; /* Indentation */
        }

        .dynamic-content li {
            line-height: 1.6; /* Optional: Improve readability */
        }

        /* Make the table responsive */
        .dynamic-content {
            margin-left: auto;
            margin-right: auto;
            padding: 10px; /* Add padding for better spacing */
        }

        /* Style the table */
        .dynamic-content table {
            border-collapse: collapse;
            width: 100%; /* Ensure table takes full width of container */
            text-align: left;
        }

        .dynamic-content th,
        .dynamic-content td {
            border: 1px solid #ddd; /* Add border to table cells */
            padding: 8px; /* Add padding inside cells for readability */
        }

        .dynamic-content th {
            background-color: #f8f9fa; /* Light gray background for headers */
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .dynamic-content table {
                font-size: 14px; /* Reduce font size for mobile */
            }
            .dynamic-content th,
            .dynamic-content td {
                padding: 6px; /* Adjust padding for smaller screens */
            }
        }

    </style>
@endsection

@section('body')
    <body data-bs-spy="scroll" data-bs-target="#navbar-example">
@endsection

@section('content')
    <!-- Begin page -->
    <div class="layout-wrapper landing">
        @include('frontend.body.nav_frontend')

        <section class="section" id="contact">
            <div class="container">
                <div class="row justify-content-center dynamic-content">
                    <div class="col-xl-9 col-lg-8">
                        <div class="text-center mb-4">
                            <h1 class="mb-2">{{$page->title}}</h1>
                            <p class="text-muted mb-4">{{$page->description}}</p>
                        
                        </div>
                        @if($page->banner_image)
                            <img style="height: 400px !important; width: 100% !important" src="{{ asset('storage/' . $page->banner_image) }}" alt="" class="img-thumbnail">
                        @endif
                        {!! $page->content !!}
                        <div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
                            <!-- Dynamically display tags -->
                            @if($page->tags)
                                @foreach(explode(',', $page->tags) as $tag)
                                    <span class="badge bg-primary-subtle text-primary">{{ trim($tag) }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        {{-- Attachments --}}
                        <div class="card">
                            <div class="card-header align-items-center d-flex border-bottom-dashed">
                                <h4 class="card-title mb-0 flex-grow-1">Attachments</h4>
                            </div>

                            <div class="card-body">

                                <div class="vstack gap-2">
                                    <div class="border rounded border-dashed p-2">
                            @php
                                $attachments = json_decode($page->attached_files, true); // Decode JSON
                            @endphp

                            @if($attachments)
                            @foreach($attachments as $attachment)
                            @php
                                $fileName = basename($attachment); // Extract file name
                                $filePath = 'storage/' . $attachment; // Adjust the path
                            @endphp

                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-light text-primary rounded fs-24">
                                            <i class="ri-folder-zip-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="fs-13 mb-1">
                                        <a href="{{ asset($filePath) }}" target="_blank" class="text-body text-truncate d-block">{{ $fileName }}</a>
                                    </h5>
                                </div>
                                <div class="flex-shrink-0 ms-2">
                                    <a href="{{ asset($filePath) }}" download class="btn btn-icon text-muted btn-sm fs-18">
                                        <i class="ri-download-2-line"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                            @else
                                <p>No attachments available.</p>
                            @endif
                                    </div>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>

                        {{-- Recent Blogs --}}
                        <div class="card">
                            <div class="card-header align-items-center d-flex border-bottom-dashed">
                                <h4 class="card-title mb-0 flex-grow-1">Recent Blogs</h4>
                            </div>

                            <div class="card-body">
                                <div data-simplebar="init" style="height: 235px;" class="mx-n3 px-3 simplebar-scrollable-y"><div class="simplebar-wrapper" style="margin: 0px -16px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px 16px;">
                                    <div class="vstack gap-3">
                                   @foreach ($recents as $recent)
                                   <div class="d-flex align-items-center">
                                    <div class="avatar-xs flex-shrink-0 me-3">
                                        <img src="{{ asset('storage/' . $recent->thumbnail_image) }}" alt="blog_thumbnail_image" class="img-fluid rounded-circle">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">{{$recent->title}}</a></h5>
                                    </div>
                                </div>
                                   @endforeach
                                    </div>
                                    <!-- end list -->
                                </div></div></div></div>
                                <div class="simplebar-placeholder" style="width: 292px; height: 284px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 194px; transform: translate3d(0px, 0px, 0px); display: block;"></div></div></div>
                            </div>
                            <!-- end card body -->
                        </div>

                        {{-- Category Wise --}}
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h5 class="card-title flex-grow-1 mb-0">Popular Creators</h5>
                                <a href="apps-nft-creators.html" type="button" class="btn btn-soft-primary btn-sm flex-shrink-0">
                                    See All <i class="ri-arrow-right-line align-bottom"></i>
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="swiper collection-slider swiper-initialized swiper-horizontal swiper-backface-hidden">
                                    <div class="swiper-wrapper" id="swiper-wrapper-27138b10f86aa6df7" aria-live="off" style="transition-duration: 0ms; transform: translate3d(-810px, 0px, 0px); transition-delay: 0ms;">
                                        
                                        
                                        
                                        
                                    <div class="swiper-slide swiper-slide-next" role="group" aria-label="4 / 4" data-swiper-slide-index="3" style="width: 260px; margin-right: 10px;">
                                            <div class="d-flex">
                                                <div class="flex-shink-0">
                                                    <img src="assets/images/users/avatar-1.jpg" alt="" class="avatar-sm object-fit-cover rounded">
                                                </div>
                                                <div class="ms-3 flex-grow-1">
                                                    <a href="pages-profile.html">
                                                        <h5 class="mb-1">Glen Matney</h5>
                                                    </a>
                                                    <p class="text-muted mb-0"><i class="mdi mdi-ethereum text-primary fs-15"></i> 49,031 ETH</p>
                                                </div>
                                                <div>
                                                    <div class="dropdown float-end">
                                                        <button class="btn btn-ghost-primary btn-icon dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill align-middle fs-16"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="javascript:void(0);">View</a></li>
                                                            <li><a class="dropdown-item" href="javascript:void(0);">Share</a></li>
                                                            <li><a class="dropdown-item" href="#!">Report</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><div class="swiper-slide" role="group" aria-label="1 / 4" data-swiper-slide-index="0" style="width: 260px; margin-right: 10px;">
                                            <div class="d-flex">
                                                <div class="flex-shink-0">
                                                    <img src="assets/images/nft/img-02.jpg" alt="" class="avatar-sm object-fit-cover rounded">
                                                </div>
                                                <div class="ms-3 flex-grow-1">
                                                    <a href="pages-profile.html">
                                                        <h5 class="mb-1">Alexis Clarke</h5>
                                                    </a>
                                                    <p class="text-muted mb-0"><i class="mdi mdi-ethereum text-primary fs-15"></i> 81,369 ETH</p>
                                                </div>
                                                <div>
                                                    <div class="dropdown float-end">
                                                        <button class="btn btn-ghost-primary btn-icon dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill align-middle fs-16"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="javascript:void(0);">View</a></li>
                                                            <li><a class="dropdown-item" href="javascript:void(0);">Share</a></li>
                                                            <li><a class="dropdown-item" href="#!">Report</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><div class="swiper-slide swiper-slide-prev" role="group" aria-label="2 / 4" data-swiper-slide-index="1" style="width: 260px; margin-right: 10px;">
                                            <div class="d-flex">
                                                <div class="flex-shink-0">
                                                    <img src="assets/images/nft/img-01.jpg" alt="" class="avatar-sm object-fit-cover rounded">
                                                </div>
                                                <div class="ms-3 flex-grow-1">
                                                    <a href="pages-profile.html">
                                                        <h5 class="mb-1">Timothy Smith</h5>
                                                    </a>
                                                    <p class="text-muted mb-0"><i class="mdi mdi-ethereum text-primary fs-15"></i> 4,754 ETH</p>
                                                </div>
                                                <div>
                                                    <div class="dropdown float-end">
                                                        <button class="btn btn-ghost-primary btn-icon dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill align-middle fs-16"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="javascript:void(0);">View</a></li>
                                                            <li><a class="dropdown-item" href="javascript:void(0);">Share</a></li>
                                                            <li><a class="dropdown-item" href="#!">Report</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><div class="swiper-slide swiper-slide-active" role="group" aria-label="3 / 4" data-swiper-slide-index="2" style="width: 260px; margin-right: 10px;">
                                            <div class="d-flex">
                                                <div class="flex-shink-0">
                                                    <img src="assets/images/nft/img-04.jpg" alt="" class="avatar-sm object-fit-cover rounded">
                                                </div>
                                                <div class="ms-3 flex-grow-1">
                                                    <a href="pages-profile.html">
                                                        <h5 class="mb-1">Herbert Stokes</h5>
                                                    </a>
                                                    <p class="text-muted mb-0"><i class="mdi mdi-ethereum text-primary fs-15"></i> 68,945 ETH</p>
                                                </div>
                                                <div>
                                                    <div class="dropdown float-end">
                                                        <button class="btn btn-ghost-primary btn-icon dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill align-middle fs-16"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="javascript:void(0);">View</a></li>
                                                            <li><a class="dropdown-item" href="javascript:void(0);">Share</a></li>
                                                            <li><a class="dropdown-item" href="#!">Report</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div></div>
                                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                                <!--end swiper-->
                            </div>
                        </div>

                        {{-- Similar Blog --}}
                         <div class="card">
                            <div class="card-header align-items-center d-flex border-bottom-dashed">
                                <h4 class="card-title mb-0 flex-grow-1">Relevant Blogs</h4>
                            </div>

                            <div class="card-body">
                                <div data-simplebar="init" style="height: 235px;" class="mx-n3 px-3 simplebar-scrollable-y"><div class="simplebar-wrapper" style="margin: 0px -16px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px 16px;">
                                    <div class="vstack gap-3">
                                   @foreach ($relatedPages as $relevant)
                                   <div class="d-flex align-items-center">
                                    <div class="avatar-xs flex-shrink-0 me-3">
                                        <img src="{{ asset('storage/' . $relevant->thumbnail_image) }}" alt="blog_thumbnail_image" class="img-fluid rounded-circle">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">{{$relevant->title}}</a></h5>
                                    </div>
                                </div>
                                   @endforeach
                                    </div>
                                    <!-- end list -->
                                </div></div></div></div>
                                <div class="simplebar-placeholder" style="width: 292px; height: 284px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 194px; transform: translate3d(0px, 0px, 0px); display: block;"></div></div></div>
                            </div>
                            <!-- end card body -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    
        <!-- Start footer -->
        @include('frontend.body.footer_frontend')
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
    <script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-nft.init.js') }}"></script>
     <!--Swiper slider js-->
     <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
@endsection