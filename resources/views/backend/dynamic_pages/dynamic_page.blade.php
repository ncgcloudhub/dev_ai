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
@endsection