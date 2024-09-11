@extends('admin.layouts.master-without-nav')
@section('title')
   Home
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Shantell+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

@endsection
@section('body')

<style>

    body, html {
    overflow-x: hidden; /* Prevent horizontal scroll */
}
    .banner{
	background: url({{ asset('build/images/banner1.jpg') }}) no-repeat center top;
	background-attachment:fixed;
	background-size:cover;
	height:auto;
	min-height:100%;
}

.banner .container, .banner .banner-static, .banner .col-lg-12 {
    height: 100%;
}
.banner-text {
    display: table;
    height: 100%;
    width: 100%;
    margin: 0 auto;
    text-align: center;
}
.banner-cell {
    display: table-cell;
    vertical-align: middle;
    color: #fff;
}
.banner-text h1 {
	font-family: 'Shantell Sans", cursive';
    letter-spacing: 2.7px;
    position: relative;
    display: inline-block;
	font-size: 74px;
    line-height: 100px;
	color:#fff;
	
}

.banner-text h2 {
	font-family: "Shantell Sans", cursive;
  font-optical-sizing: auto;
  font-weight: 500;
  font-style: normal;
  font-variation-settings:
    "BNCE" 0,
    "INFM" 0,
    "SPAC" 0;
    letter-spacing: 2.7px;
    position: relative;
	font-size: 37px;
    line-height: 100px;
	padding-bottom:25px;
}
.banner-text p{
	font-family: 'Roboto', sans-serif;
	font-size:18px;
	color:#ccc;
	padding-bottom:35px;
	margin:0px;
}

.glass{
/* From https://css.glass */
background: rgba(255, 255, 255, 0.41);
border-radius: 16px;
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(7.5px);
-webkit-backdrop-filter: blur(7.5px);
border: 1px solid rgba(255, 255, 255, 0.99);
}

.card-background-common {
    position: relative;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: white;
    border-radius: 15px;
    padding: 20px;
    overflow: hidden; /* Ensure the overlay stays within the card bounds */
}

.card-background-common::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent black overlay */
    z-index: 1; /* Ensure the overlay is on top of the image */
}

.card-background-common > * {
    position: relative;
    z-index: 2; /* Ensure the content is on top of the overlay */
}

.text-description {
    display: -webkit-box;
    -webkit-line-clamp: 2; /* Limits the text to 2 lines */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}


   .template-card:hover {
    transform: scale(.95);
    transition: transform 0.3s ease;
    
} 

.template-card:hover .card-body {
    background-color: #d4e9f0; /* Light blue background color */
}

/* parallex */
#stones {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%; /* Adjust width if needed */
    height: auto; /* Maintain aspect ratio */
    z-index: 5; /* Ensure it is behind other interactive elements */
    pointer-events: none; /* Allow clicks to pass through */
}


</style>

    <body data-bs-spy="scroll" data-bs-target="#navbar-example">
    @endsection
      
    
    @section('content')
    @if(session('success'))
    <div id="successAlert" class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
    </div>
@endif
        <!-- Begin page -->
        <div class="layout-wrapper landing">
           @include('frontend.body.nav_frontend')


           {{-- Parallex --}}
           <img src="{{ asset('frontend/parallex_images/stones.png') }}" id="stones">   
            
           
           <!-- start hero section -->
          @include('frontend.designs.banner_home.banner_parallex')
            <!-- end hero section -->


             {{-- SINGLE DALLE IMAGE GENERATE START--}}
             <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-5">
                            <h1 class="mb-3 ff-secondary fw-semibold lh-base">AI Image Generate</h1>
                            <p class="text-muted">Try any Prompt</p>
                        </div>
            
                        <form id="generateImageForm" class="row g-3">
                            @csrf
                            <div class="row g-3 justify-content-center">
                                <div class="col-xxl-5 col-sm-6">
                                    <div class="search-box">
                                        <textarea class="form-control search" name="prompt" rows="1" id="prompt" placeholder="Write prompt to generate Image"></textarea>
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                    <!-- Error message container -->
                                    <small id="promptError" class="text-danger d-none">Please write a prompt to generate the image.</small>
                                </div>
                                
                                <!--end col-->
                                <div class="col-xxl-1 col-sm-4">
                                    <div>
                                        <button type="button" id="generateButton" class="btn btn-rounded btn-primary mb-2">
                                            <span id="buttonText">Generate</span>
                                            <span id="loadingSpinner" class="spinner-border spinner-border-sm text-light ms-2 d-none" role="status"></span>
                                        </button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                        </form>
            
                        <div id="generatedImageContainer" class="text-center mt-5">
                            <!-- Placeholder for the generated image -->
                        </div>
            
                        <!-- Avatar Group with Tooltip -->
                        <div class="d-flex justify-content-center mt-4">
                            <div class="avatar-group">
                                @foreach ($images_slider as $item)
                                    <a href="{{ $item->image_url }}" class="avatar-group-item image-popup" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $item->prompt }}">
                                        <img src="{{ $item->image_url }}" alt="" class="rounded-circle avatar-xl">
                                    </a>
                                @endforeach
                                <a href="{{route('ai.image.gallery')}}" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-placement="top" title="more">
                                    <div class="avatar-xl">
                                        <div class="avatar-title rounded-circle">
                                            More...
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!--end col-->
            

     {{-- SINGLE DALLE IMAGE GENERATE END--}}


     {{-- How it Works --}}
            @if ($selectedDesign == 'design1')
                @include('frontend.designs.how_it_works.design_1')
            @elseif ($selectedDesign == 'design2')
                @include('frontend.designs.how_it_works.design_2')
            @elseif ($selectedDesign == 'design3')
                @include('frontend.designs.how_it_works.design_3')
            @else
                <!-- Fallback or default design if none is selected -->
                @include('frontend.designs.how_it_works.design_1')
            @endif

  


            {{-- AI Image Gallery Slider --}}

            <div class="col-lg-12">
                <div class="card">
                  
                    <div class="card-body">
                        <div class="text-center mb-5">
                            <h1 class="mb-3 ff-secondary fw-semibold lh-base">AI Image Gallery</h1>
                            <p class="text-muted">Images generated by our users from our Website</p>
                        </div>
    
                        <!-- Swiper -->
                        <div class="swiper effect-coverflow-swiper rounded pb-5">
                            <div class="swiper-wrapper">
                                @foreach ($images_slider as $item)
                                <div class="swiper-slide">
                                    <a class="image-popup" href="{{ $item->image_url }}" title="">
                                        <img class="gallery-img img-fluid mx-auto" src="{{ $item->image_url }}" alt="" />
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination swiper-pagination-dark"></div>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!--end col-->

            {{-- AI Image gallery Slider Ends --}}


            <!-- start services -->
            <section class="section bg-light py-5" id="features">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="text-center mb-5">
                                <h1 class="mb-3 ff-secondary fw-semibold lh-base">Awesome Features</h1>
                                <p class="text-muted">Elevate Your Digital Presence with Intelligent Solutions - Unleashing AI Chatbots,
                                    Multimedia Transformation, and Dynamic Content Generation</p>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="d-flex p-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm icon-effect">
                                        <div class="avatar-title bg-transparent text-success rounded-circle">
                                            <i class="ri-pencil-ruler-2-line fs-36"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-18">AI Chatbot Integration</h5>
                                    <p class="text-muted my-3 ff-secondary">Engage and assist your website visitors with TrionxAI's advanced AI chatbot, providing seamless communication and support.</p>
                                    <div>
                                        <a href="{{route('chat')}}" class="fs-13 fw-medium">Learn More <i
                                                class="ri-arrow-right-s-line align-bottom"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-lg-4">
                            <div class="d-flex p-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm icon-effect">
                                        <div class="avatar-title bg-transparent text-success rounded-circle">
                                            <i class="ri-palette-line fs-36"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-18">Text to Image Conversion</h5>
                                    <p class="text-muted my-3 ff-secondary">Transform textual content into captivating visuals effortlessly, as TrionxAI converts text to images, enhancing the visual appeal and accessibility of your information.</p>
                                    <div>
                                        <a href="{{route('generate.image.view')}}" class="fs-13 fw-medium">Learn More <i
                                            class="ri-arrow-right-s-line align-bottom"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-lg-4">
                            <div class="d-flex p-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm icon-effect">
                                        <div class="avatar-title bg-transparent text-success rounded-circle">
                                            <i class="ri-lightbulb-flash-line fs-36"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-18">Create Custom Template</h5>
                                    <p class="text-muted my-3 ff-secondary">Creating a custom template empowers users to generate personalized content through the OpenAI API, enhancing flexibility and customization in AI-driven creations.</p>
                                    <div>
                                        <a href="{{route('custom.template.manage')}}" class="fs-13 fw-medium">Learn More <i
                                                class="ri-arrow-right-s-line align-bottom"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-lg-4">
                            <div class="d-flex p-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm icon-effect">
                                        <div class="avatar-title bg-transparent text-success rounded-circle">
                                            <i class="ri-customer-service-line fs-36"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-18">AI Blog Generate</h5>
                                    <p class="text-muted my-3 ff-secondary">Awesome Support is the most versatile and
                                        feature-rich support plugin for all version.</p>
                                    <div>
                                        <a href="#" class="fs-13 fw-medium">Learn More <i
                                                class="ri-arrow-right-s-line align-bottom"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-lg-4">
                            <div class="d-flex p-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm icon-effect">
                                        <div class="avatar-title bg-transparent text-success rounded-circle">
                                            <i class="ri-stack-line fs-36"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-18">Generate Facebook ADs</h5>
                                    <p class="text-muted my-3 ff-secondary">You usually get a broad range of options to
                                        play with. This enables you to use a single theme across multiple.</p>
                                    <div>
                                        <a href="#" class="fs-13 fw-medium">Learn More <i
                                                class="ri-arrow-right-s-line align-bottom"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-lg-4">
                            <div class="d-flex p-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm icon-effect">
                                        <div class="avatar-title bg-transparent text-success rounded-circle">
                                            <i class="ri-settings-2-line fs-36"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-18">AI Grammer Checker</h5>
                                    <p class="text-muted my-3 ff-secondary">Personalise your own website, no matter what
                                        theme and what customization options.</p>
                                    <div>
                                        <a href="#" class="fs-13 fw-medium">Learn More <i
                                                class="ri-arrow-right-s-line align-bottom"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->

                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </section>
            <!-- end services -->

                      <!-- start client section -->
                      <div class="pt-5 mt-5">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
        
                                    <div class="text-center mt-5">
                                        <h5 class="fs-20">Trusted <span
                                                class="text-primary text-decoration-underline">by</span> the world's best</h5>
        
                                        <!-- Swiper -->
                                        <div class="swiper trusted-client-slider mt-sm-5 mt-4 mb-sm-5 mb-4" dir="ltr">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <div class="client-images">
                                                        <img src="{{ URL::asset('build/images/clients/amazon.svg') }}" alt="client-img"
                                                            class="mx-auto img-fluid d-block">
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="client-images">
                                                        <img src="{{ URL::asset('build/images/clients/walmart.svg') }}" alt="client-img"
                                                            class="mx-auto img-fluid d-block">
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="client-images">
                                                        <img src="{{ URL::asset('build/images/clients/lenovo.svg') }}" alt="client-img"
                                                            class="mx-auto img-fluid d-block">
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="client-images">
                                                        <img src="{{ URL::asset('build/images/clients/paypal.svg') }}" alt="client-img"
                                                            class="mx-auto img-fluid d-block">
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="client-images">
                                                        <img src="{{ URL::asset('build/images/clients/shopify.svg') }}" alt="client-img"
                                                            class="mx-auto img-fluid d-block">
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="client-images">
                                                        <img src="{{ URL::asset('build/images/clients/verizon.svg') }}" alt="client-img"
                                                            class="mx-auto img-fluid d-block">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end container -->
                    </div>
                    <!-- end client section -->

            <!-- start features -->
            <section class="section bg-light py-5">
                <div class="container">
                    <div class="row align-items-center gy-4">
                        <div class="col-lg-6 col-sm-7 mx-10">
                            <div>
                                <img src="https://ai.smsblastnet.com/public/uploads/media/lOF5TPvuGPcU0HBFb4vyvE11lvXufZemYwZBSgPq.jpg" class="img-fluid mx-auto" alt="about image">
                            </div>
                        </div>
                        <div class="col-lg-6 ">
                            <div class="text-muted">
                              
                                <h3 class="mb-3 fs-20">Huge collection of Templates</h3>
                                <p class="mb-4 ff-secondary fs-16">Select from the list of our fixed templates</p>

                                    <ul class="list-unstyled d-flex flex-wrap tt-two-col mt-4 mb-4">
                                        <li class="col-6 py-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open me-2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>Blog Content
                                        </li>
                                        <li class="col-6 py-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail me-2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>Email Template
                                        </li>
                                        <li class="col-6 py-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2 me-2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>Social Media
                                        </li>
                                        <li class="col-6 py-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video me-2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>Video Content
                                        </li>
                                        <li class="col-6 py-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor me-2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>Website Content
                                        </li>
                                        <li class="col-6 py-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smile me-2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>Fun &amp; Quote
                                        </li>
                                        <li class="col-6 py-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-code me-2"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>Medium Content
                                        </li>
                                        <li class="col-6 py-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-film me-2"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>Tik Tok
                                        </li>
                                        <li class="col-6 py-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram me-2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>Instagram
                                        </li>
                                        <li class="col-6 py-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-gift me-2"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg>Success Story
                                        </li>
                                    </ul>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-lg-4 product-item music crypto-card games">
                            <div class="card explore-box card-animate">
                                <div class="bookmark-icon position-absolute top-0 end-0 p-2">
                                    <button type="button" class="btn btn-icon active" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                                </div>
                                <div class="explore-place-bid-img">
                                    <img src="{{URL::asset('build/images/nft/ai.jpg')}}" alt="" class="card-img-top explore-img" />
                                    <div class="bg-overlay"></div>
                                    <div class="place-bid-btn">
                                        <a href="{{route('generate.image.view')}}" class="btn btn-primary"><i class="ri-auction-fill align-bottom me-1"></i> Generate Image</a>
                                    </div>
                                </div>
                               
                            </div>
                        </div>

                        <div class="col-lg-4 product-item music crypto-card games">
                            <div class="card explore-box card-animate">
                                <div class="bookmark-icon position-absolute top-0 end-0 p-2">
                                    <button type="button" class="btn btn-icon active" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                                </div>
                                <div class="explore-place-bid-img">
                                    <img src="{{URL::asset('build/images/nft/ai1.jpg')}}" alt="" class="card-img-top explore-img" />
                                    <div class="bg-overlay"></div>
                                    <div class="place-bid-btn">
                                        <a href="{{route('prompt.manage')}}" class="btn btn-primary"><i class="ri-auction-fill align-bottom me-1"></i>Prompt Library</a>
                                    </div>
                                </div>
                               
                            </div>
                        </div>

                        <div class="col-lg-4 product-item music crypto-card games">
                            <div class="card explore-box card-animate">
                                <div class="bookmark-icon position-absolute top-0 end-0 p-2">
                                    <button type="button" class="btn btn-icon active" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                                </div>
                                <div class="explore-place-bid-img">
                                    <img src="{{URL::asset('build/images/nft/ai2.jpg')}}" alt="" class="card-img-top explore-img" />
                                    <div class="bg-overlay"></div>
                                    <div class="place-bid-btn">
                                        <a href="{{route('template.manage')}}" class="btn btn-primary"><i class="ri-auction-fill align-bottom me-1"></i>Free AI Templates</a>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                        
                        
                       
                        
                        {{-- <div class="col-lg-3">
                            <div class="sticky-side-div">
                                <div class="card ribbon-box border shadow-none right">
                        
                                    <img src="{{URL::asset('build/images/nft/ai3.jpg')}}" alt="" class="img-fluid rounded">
                                    
                                </div>
                        
                            </div>
                        </div> --}}
                    </div>

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
                                <h4 class="text-white mb-0 fw-semibold">Create Your Contents with our Pre-defined Templates</h4>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-sm-auto">
                            <div>
                                <a href="{{ auth()->check() ? route('template.manage') : route('login') }}" target="_blank"
                                    class="btn bg-gradient btn-danger"><i
                                        class=" align-middle me-1"></i> Create Content</a>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </section>
            <!-- end cta -->

            

            <section class="section bg-light py-5">
                <div class="container">

                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="text-center mb-5">
                                <h1 class="mb-3 ff-secondary fw-semibold lh-base">AI Image Gallery</h1>
                                <p class="text-muted">Images generated by our users from our Website</p>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row gallery-wrapper">
                      
                        @foreach ($images as $item)
                          
                        <div class="element-item col-xxl-3 col-xl-4 col-sm-6 photography" data-category="photography">
                            <div class="gallery-box card">
                                <div class="gallery-container">
                                    <a class="image-popup" href="{{ asset($item->image_url) }}" title="">
                                        <img class="gallery-img img-fluid mx-auto" src="{{ asset($item->image_url) }}" alt="" />
                                        <div class="gallery-overlay">
                                            <h5 class="overlay-caption">{{$item->prompt}}</h5>
                                        </div>
                                    </a>
                                </div>

                                <div class="box-content">
                                    <div class="d-flex align-items-center mt-1">
                                        {{-- <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">{{$item->user->name}}</a></div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <!-- end col -->
                        
                    </div>
                    <!-- end row -->
                    <div class="mx-auto d-flex justify-content-center">
                        <a href="{{ route('ai.image.gallery') }}" class="btn btn-primary">Show More</a>
                    </div>
                    
                </div>
            </section>

            <!-- FrontEnd Templates -->
            <section class="section">
                <div class="container">
                    <div class="text-center mb-5">
                        <h1 class="mb-3 ff-secondary fw-semibold lh-base">Generate Contents</h1>
                        <p class="text-muted">Generate your contents easily with our pre-defined templates</p>
                    </div>
                    <div class="row align-items-center gy-4">
                       
                        <div class="row template-row">
                            @foreach ($templates as $item)
                            <div class="col-md-3 p-3 template-card" data-category="{{$item->category_id}}">
                                <div class="card" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                                    @auth
                                        <a href="{{route('template.view', ['slug' => $item->slug])}}" class="text-decoration-none">
                                    @else
                                        <a href="{{route('frontend.free.template.view', ['slug' => $item->slug])}}" class="text-decoration-none">
                                    @endauth
                                        <div class="card-body d-flex flex-column justify-content-between" style="height: 250px;"> <!-- Fixed height for the card -->
                                            <div class="mb-3">
                                                <div style="width: 42px; height: 42px; border-radius: 50%; background-color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 .125rem .3rem -0.0625rem rgba(0,0,0,.1),0 .275rem .75rem -0.0625rem rgba(249,248,249,.06)">
                                                    <img width="22px" src="/build/images/templates/{{$item->icon}}.png" alt="" class="img-fluid">
                                                </div>
                                                <h3 class="fw-medium link-primary">{{$item->template_name}}</h3>
                                                <p style="height: 3em; overflow: hidden; color:black" class="card-text customer_name">{{$item->description}}</p>
                                            </div>
                                            <div>
                                                <small class="text-muted">0 Words generated</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        
                        </div>
                        <div class="mx-auto d-flex justify-content-center">
                            @auth
                                <a href="{{ route('template.manage') }}" class="btn btn-primary">Show More</a> <!-- Redirect to user.prompt.library if user is a normal user -->
                            @else
                                <a href="{{ route('frontend.free.template') }}" class="btn btn-primary">Show More</a> <!-- Redirect to frontend.free.prompt.library if no one is logged in -->
                            @endauth
                        </div>
                    </div>
                    <!-- end row -->
                    <!-- end row -->
                </div>
                <!-- end container -->
            </section>
            <!-- end features -->

            {{-- Prompt library --}}
          @include('frontend.body.prompt_library_frontend')
            {{-- Prompt library end --}}


             <!-- start cta -->
             <section class="py-5 bg-primary position-relative">
                <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
                <div class="container">
                    <form method="post" action="{{ route('newsletter.store') }}">
                        @csrf
                        <div class="row align-items-center gy-4">
                            <div class="col-sm">
                                <div>
                                    <h4 class="text-white mb-0 fw-semibold">Get updated with the latest AI contents</h4>
                                </div>
                            </div>
                            <div class="col-sm-auto d-flex justify-content-end align-items-center">
                                <!-- Input with Placeholder -->
                                <div class="me-3 col-12 form-group">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter E-Mail">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn bg-gradient btn-danger">
                                        <i class="align-middle me-1"></i>Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <!-- end row -->
                </div>
                <!-- end container -->
            </section>
            <!-- end cta -->
          

            <!-- start plan -->
            @include('frontend.body.pricing_plans')
            <!-- end plan -->

            <!-- start faqs -->
            @include('frontend.body.faq', ['faqs' => $faqs])
            <!-- end faqs -->

            <!-- start contact -->
          @include('frontend.body.contact_us')
            <!-- end contact -->

            <!-- start cta -->
            <section class="py-5 bg-primary position-relative">
                <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
                <div class="container">
                    <div class="row align-items-center gy-4">
                        <div class="col-sm">
                            <div>
                                <h4 class="text-white mb-0 fw-semibold">Create Your Own Images</h4>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-sm-auto">
                            <div>
                                <a href="{{ auth()->check() ? route('generate.image.view') : route('login') }}" target="_blank"
                                    class="btn bg-gradient btn-danger"><i
                                        class="align-middle me-1"></i> Generate Image</a>
                            </div>
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

    <script>
        $(document).ready(function(){
            $('.banner-slider').slick({
                autoplay: true,
                autoplaySpeed: 5000,
                arrows: false,
                dots: true,
                fade: true,
                infinite: true,
                speed: 1000,
                slidesToShow: 1,
                adaptiveHeight: true
            });
        });
    </script>


        <script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>
        <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
        <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/swiper.init.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/job-lading.init.js') }}"></script>
        <script src="{{ URL::asset('build/js/all.js') }}"></script>

       
       {{-- Parallex --}}
       <script>
          
          window.addEventListener('scroll', function() {
    let stones = document.getElementById("stones");
    let footer = document.querySelector('.custom-footer'); // Ensure your footer has this class or ID

    // Get the positions
    let scrollTop = window.scrollY;
    let stonesHeight = stones.offsetHeight;
    let footerTop = footer.offsetTop;
    let windowHeight = window.innerHeight;

    // Calculate the bottom position of the stones image relative to the document
    let stonesBottom = scrollTop + stonesHeight;

    // Check if the stones image should stop at the footer
    if (stonesBottom > footerTop) {
        stones.style.top = (footerTop - stonesHeight) + 'px';
    } else {
        stones.style.top = scrollTop + 'px';
    }
});






        </script>
    @endsection
