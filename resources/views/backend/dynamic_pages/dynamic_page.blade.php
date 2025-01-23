@extends('admin.layouts.master-without-nav')
@section('title', $page->title)

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
                    {!! $page->content !!}
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