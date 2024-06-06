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
            @include('frontend.body.nav_frontend')
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
                                            <div class="row gallery-wrapper justify-content-center" id="image-container">
                                                @foreach ($images as $item)
                                                <div class="element-item col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 photography" data-category="photography">
                                                    <div class="gallery-box card">
                                                        <div class="gallery-container">
                                                            <a class="image-popup" href="{{ asset($item->image_url) }}" title="">
                                                                <img class="gallery-img img-fluid mx-auto d-block" src="{{ asset($item->image_url) }}" alt="" />
                                                               
                                                                <div class="gallery-overlay">
                                                                    <h5 class="overlay-caption">{{$item->prompt}}</h5>
                                                                    
                                                                </div>
                                                            </a>
                                                        </div>
                                                      
                                                        
                                                    </div>
                                                    <button class="like-button" data-image-id="{{ $item->id }}">Like</button>
                                                </div>
                                                @endforeach
                                            </div>
                                            
                                          <!-- Loader element -->
                                          <div class="infinite-scroll-loader" style="display: none;">
                                            <div class="text-center my-2">
                                                <i class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i> Loading...
                                            </div>
                                        </div>
                                            
                                        </div>
                                    </div>
                                    <!-- end row -->
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                </div>
            </section>
            
            <!-- end row -->



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
        <script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>
        <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>

        @section('script')
        <script>
            var page = 1; // initialize page number
    
            function loadMoreImages() {
                page++; // increment page number
                $('.infinite-scroll-loader').show(); // show loader
                $.ajax({
                    url: '{{ route("ai.image.gallery") }}?page=' + page, // replace 'your_route_name' with the actual route name
                    type: 'GET',
                    success: function (response) {
                        $('#image-container').append(response);
                        $('.infinite-scroll-loader').hide(); // hide loader after images are loaded
                    }
                });
            }
    
            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() == $(document).height()) {
                    loadMoreImages();
                }
            });
    
            // Initial load for the first page
            loadMoreImages();
        </script>

{{-- LIKE --}}
        <script>
            $(document).ready(function() {
    $('.like-button').on('click', function() {
        var imageId = $(this).data('image-id');
        $.ajax({
            url: '/like',
            method: 'POST',
            headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
            data: { image_id: imageId },
            success: function(response) {
                // Update UI to reflect the new like status
                if (response.liked) {
                    $('.like-button[data-image-id="' + imageId + '"]').text('Unlike');
                } else {
                    $('.like-button[data-image-id="' + imageId + '"]').text('Like');
                }
            },
            error: function(xhr) {
                // Handle errors
            }
        });
    });
});

        </script>
    @endsection
    
    
    
    

    @endsection
