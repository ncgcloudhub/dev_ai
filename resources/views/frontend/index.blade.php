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
           {{-- <img src="{{ asset('frontend/parallex_images/stones.png') }}" id="stones">    --}}
            
           
            {{-- How it Works --}}
            @if ($banner == 'design1')
                @include('frontend.designs.banner_home.banner_deafult')
            @elseif ($banner == 'design2')
                @include('frontend.designs.banner_home.banner_parallex')
            @else
                <!-- Fallback or default design if none is selected -->
                @include('frontend.designs.banner_home.banner_deafult')
            @endif


            {{-- SINGLE DALLE IMAGE GENERATE START--}}
            @if ($image_generate == 'design1')
                @include('frontend.designs.image_generate.image_generate_1')
            @elseif ($image_generate == 'design2')
                @include('frontend.designs.image_generate.image_generate_2')
            @else
                <!-- Fallback or default design if none is selected -->
                @include('frontend.designs.image_generate.image_generate_1')
            @endif
            {{-- SINGLE DALLE IMAGE GENERATE END--}}


            {{-- How it Works --}}
            @if ($how_it_works == 'design1')
                @include('frontend.designs.how_it_works.design_1')
            @elseif ($how_it_works == 'design2')
                @include('frontend.designs.how_it_works.design_2')
            @elseif ($how_it_works == 'design3')
                @include('frontend.designs.how_it_works.design_3')
            @else
                <!-- Fallback or default design if none is selected -->
                @include('frontend.designs.how_it_works.design_1')
            @endif



            {{-- AI Image Gallery Slider --}}

            @if ($image_slider == 'design1')
                @include('frontend.designs.ai_image_gallery_slider.image_slider_1')
            @elseif ($image_slider == 'design2')
                @include('frontend.designs.ai_image_gallery_slider.image_slider_2')
            @else
                <!-- Fallback or default design if none is selected -->
                @include('frontend.designs.ai_image_gallery_slider.image_slider_1')
            @endif

            {{-- AI Image gallery Slider Ends --}}




            <!-- start features -->
           
            @if ($features == 'design1')
                @include('frontend.designs.features.feature_1')
            @elseif ($features == 'design2')
                @include('frontend.designs.features.feature_2')
            @else
                <!-- Fallback or default design if none is selected -->
                @include('frontend.designs.features.feature_1')
            @endif
            <!-- end features -->

            <!-- start client section -->
            <div class="pt-5 mt-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="text-center mt-5">
                                <h5 class="fs-20">Trusted <span
                                        class="gradient-text-1 text-decoration-underline">by</span> the world's best</h5>

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

           
            <!-- start services -->
            @if ($services == 'design1')
                @include('frontend.designs.services.services_1')
            @elseif ($services == 'design2')
                @include('frontend.designs.services.services_2')
            @else
                <!-- Fallback or default design if none is selected -->
                @include('frontend.designs.services.services_1')
            @endif
            <!-- end services -->


            <!-- start cta -->
            <section class="py-5 gradient-background-1 position-relative">
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
                                <a href="{{ auth()->check() ? route('aicontentcreator.manage') : route('login') }}" target="_blank"
                                    class="btn gradient-btn-10"><i
                                        class="align-middle me-1"></i> Create Content</a>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </section>
            <!-- end cta -->

            
            {{-- AI Image Gallery --}}
            @if ($image_gallery == 'design1')
                @include('frontend.designs.ai_image_gallery.gallery_1')
            @elseif ($image_gallery == 'design2')
                @include('frontend.designs.ai_image_gallery.gallery_2')
            @else
                <!-- Fallback or default design if none is selected -->
                @include('frontend.designs.ai_image_gallery.gallery_1')
            @endif
            {{-- AI Image Gallery END --}}


            <!-- FrontEnd Templates -->
            @if ($content_creator == 'design1')
                @include('frontend.designs.ai_content_creator.content_creator_1')
            @elseif ($content_creator == 'design2')
                @include('frontend.designs.ai_content_creator.content_creator_2')
            @else
                <!-- Fallback or default design if none is selected -->
                @include('frontend.designs.ai_content_creator.content_creator_1')
            @endif
            <!-- end features -->


            {{-- Prompt library --}}
            @if ($prompt_library == 'design1')
                @include('frontend.designs.prompt_library.prompt_library_1')
            @elseif ($prompt_library == 'design2')
                @include('frontend.designs.prompt_library.prompt_library_2')
            @else
                <!-- Fallback or default design if none is selected -->
                @include('frontend.designs.prompt_library.prompt_library_1')
            @endif
            {{-- Prompt library end --}}


            <!-- start cta -->
            <section class="py-5 gradient-background-1 position-relative">
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
                                <div class="me-3 col-md-12 form-group">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter E-Mail">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn gradient-btn-10">
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
            <section class="py-5 gradient-background-1 position-relative">
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
                                    class="btn gradient-btn-10"><i
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
            let footer = document.querySelector('.custom-footer'); // Ensure this matches your footer's class or ID
        
            // Get the positions
            let scrollTop = window.scrollY;
            let stonesHeight = stones.offsetHeight;
            let stonesWidth = stones.offsetWidth;
            let footerTop = footer.offsetTop;
            let windowHeight = window.innerHeight;
        
            // Zoom effect parameters
            let zoomFactor = 1 + (scrollTop / 1000); // Adjust zoom speed here
            stones.style.transform = `scale(${zoomFactor})`;
        
            // Calculate the bottom position of the stones image relative to the document
            let stonesBottom = scrollTop + stonesHeight * zoomFactor;
        
            // Check if the stones image should stop at the footer
            if (stonesBottom > footerTop) {
                // Calculate the adjusted top position considering zoom
                stones.style.top = (footerTop - stonesHeight * zoomFactor) + 'px';
            } else {
                stones.style.top = scrollTop + 'px';
            }
        });
        </script>
        
    @endsection
