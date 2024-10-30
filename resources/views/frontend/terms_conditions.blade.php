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

           
           @php
            $termConditions = \App\Models\TermsConditions::all();
            $latesttermConditions = \App\Models\TermsConditions::latest()->first();
            $lastUpdateDate = $latesttermConditions ? $latesttermConditions->created_at->format('d M, Y') : 'Unknown';
           @endphp


            <!-- start privacy & policy -->
            <section class="section" id="contact">
                <div class="container">
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
                    
                                    {{-- <div class="text-end">
                                        <a href="#!" class="btn btn-success">Accept</a>
                                        <a href="#!" class="btn btn-outline-danger"><i class="ri-close-line align-bottom me-1"></i> Decline</a>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                </div>
            </section>
            <!-- end privacy & policy -->

     

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
