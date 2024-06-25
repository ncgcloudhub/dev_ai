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
                                                            <!-- Trigger modal when image clicked -->
                                                            <a class="image-popups" href="#" data-bs-toggle="modal" data-bs-target="#imageModal" title="{{ $item->prompt }}"
                                                                data-image-url="{{ $item->image_url }}" data-image-prompt="{{ $item->prompt }}" data-image-resolution="{{ $item->resolution }}">
                                                                <img class="gallery-img img-fluid mx-auto d-block" src="{{ asset($item->image_url) }}" alt="" />
                                                                {{-- <div class="gallery-overlay">
                                                                    <h5 class="overlay-caption">{{$item->prompt}}</h5>
                                                                </div> --}}
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <!-- Buttons Group -->
                                                     <!-- Share Button -->
                                                    <button type="button" class="btn btn-info btn-sm share-button" data-image-url="{{ asset($item->image_url) }}" data-image-prompt="{{ $item->prompt }}">
                                                        <i class="ri-share-forward-fill"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-sm btn-outline-primary position-relative like-button {{ $item->liked_by_user ? 'ri-thumb-up-fill' : 'ri-thumb-up-line' }}" data-image-id="{{ $item->id }}">
                                                         <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success"> {{ $item->likes_count }}</span>
                                                    </button>

                                                    <button type="button" class="btn btn-sm btn-outline-primary position-relative favorite-button {{ $item->favorited_by_user ? 'ri-heart-2-fill' : 'ri-heart-2-line' }}" data-image-id="{{ $item->id }}">
                                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $item->favorites_count }}</span>
                                                    </button>
                                                    
                                                    
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

<!-- Modal -->
{{-- Image Description --}}
<div id="imageModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 overflow-hidden">
            <div class="row g-0">
              
                <div class="col-lg-5">
                    <div class="subscribe-modals-cover h-100 d-flex align-items-center justify-content-center">
                        <button type="button" class="btn btn-outline-secondary position-absolute start-0" id="prevButton">
                            <i class="ri-arrow-left-s-line"></i>
                        </button>
                        <img id="modalImage" src="" class="img-fluid w-100" alt="Image">
                        <button type="button" class="btn btn-outline-secondary position-absolute end-0" id="nextButton">
                            <i class="ri-arrow-right-s-line"></i>
                        </button>
                    </div>
                </div>
                <div class="col-lg-7 d-flex align-items-center">
                    <div class="modal-body p-5">
                        <p class="lh-base modal-title mb-2" id="imageModalLabel"></p>
                        <span class="text-muted mb-4" id="resolution"></span>
                    </div>
                    
                </div>
                <!-- Left button -->
              
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

    @endsection
    @section('script')
        <script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>
        <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>


        @section('script')

        {{-- SHAREE --}}
        <script>
            $(document).ready(function() {
                $('.share-button').click(function() {
                    var imageUrl = $(this).data('image-url');
                    var promptText = $(this).data('image-prompt');
        
                    // Construct the share message
                    var shareMessage = promptText + ': ' + imageUrl;
        
                    // Open share dialog based on the platform
                    if (navigator.share) {
                        navigator.share({
                            title: promptText,
                            text: shareMessage,
                            url: imageUrl
                        }).then(() => console.log('Successful share')).catch((error) => console.log('Error sharing', error));
                    } else {
                        // Fallback for browsers that do not support native share API
                        var whatsappUrl = 'whatsapp://send?text=' + encodeURIComponent(shareMessage);
                        window.open(whatsappUrl);
                    }
                });
            });
        </script>

        
        <script>
          document.querySelectorAll('.image-popups').forEach(item => {
        item.addEventListener('click', event => {
            const imageUrl = item.getAttribute('data-image-url');
            const title = item.getAttribute('title');
            const prompt = item.getAttribute('data-image-prompt');
            const resolution = item.getAttribute('data-image-resolution');
            
            // Update modal content
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModalLabel').innerText = title;
            document.getElementById('modalPrompt').innerText = prompt;
            document.getElementById('modalResolution').innerText = resolution;
            
            // Show the modal
            $('#imageModal').modal('show');
        });
    });
        </script>

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
        var likeButton = $(this); 
        var likeCountBadge = likeButton.find('.badge');

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
                    // Image is liked
                    likeButton.toggleClass('ri-thumb-up-line ri-thumb-up-fill');
                    likeCountBadge.text(parseInt(likeCountBadge.text()) + 1);
                } else {
                    // Image is unliked
                    likeButton.removeClass('ri-thumb-up-fill').addClass('ri-thumb-up-line');
                    likeCountBadge.text(parseInt(likeCountBadge.text()) - 1);
                }
            },
            error: function(xhr) {
                // Handle errors
            }
        });
    });


// Favorite
    $('.favorite-button').on('click', function() {
            var imageId = $(this).data('image-id');
            var favoriteButton = $(this);
            var favoriteCountBadge = favoriteButton.find('.badge');

            $.ajax({
                url: '/favorite',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { image_id: imageId },
                success: function(response) {
                    // Update UI to reflect the new favorite status
                    if (response.favorited) {
                        // Image is favorited
                        favoriteButton.removeClass('ri-heart-2-line').addClass(' ri-heart-2-fill');
                        favoriteCountBadge.text(parseInt(favoriteCountBadge.text()) + 1);
                    } else {
                        // Image is unfavorited
                        favoriteButton.removeClass(' ri-heart-2-fill').addClass('ri-heart-2-line');
                        favoriteCountBadge.text(parseInt(favoriteCountBadge.text()) - 1);
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
