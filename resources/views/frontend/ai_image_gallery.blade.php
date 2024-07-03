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
                                            <form id="search-form" action="{{ route('ai.image.gallery') }}" method="GET">
                                                <input type="text" name="search" id="search" class="form-control" placeholder="Search by prompt" value="{{ request()->query('search') }}">
                                            </form>
                                            
                                            <br>
                                            <div class="row gallery-wrapper justify-content-center" id="image-container">
                                                @include('frontend.image_gallery_partial', ['images' => $images])
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

        <script>
            $(document).ready(function() {
                // Infinite scroll
                // var page = 1;
                // function loadMoreImages() {
                //     page++;
                //     $('.infinite-scroll-loader').show();
                //     $.ajax({
                //         url: '{{ route("ai.image.gallery") }}?page=' + page,
                //         type: 'GET',
                //         success: function(response) {
                //             $('#image-container').append(response);
                //             $('.infinite-scroll-loader').hide();
                //         }
                //     });
                // }

                // $(window).scroll(function() {
                //     if($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                //         loadMoreImages();
                //     }
                // });

                // Like button click event using event delegation
                $(document).on('click', '.like-button', function() {
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
                            if (response.liked) {
                                // Image is liked
                                likeButton.removeClass('ri-thumb-up-line').addClass('ri-thumb-up-fill');
                                likeCountBadge.text(parseInt(likeCountBadge.text()) + 1);
                            } else {
                                // Image is unliked
                                likeButton.removeClass('ri-thumb-up-fill').addClass('ri-thumb-up-line');
                                likeCountBadge.text(parseInt(likeCountBadge.text()) - 1);
                            }
                        },
                        error: function(xhr) {
                            console.error('Error liking image:', xhr);
                        }
                    });
                });

                // Favorite button click event using event delegation
                $(document).on('click', '.favorite-button', function() {
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
                            if (response.favorited) {
                                // Image is favorited
                                favoriteButton.removeClass('ri-heart-2-line').addClass('ri-heart-2-fill');
                                favoriteCountBadge.text(parseInt(favoriteCountBadge.text()) + 1);
                            } else {
                                // Image is unfavorited
                                favoriteButton.removeClass('ri-heart-2-fill').addClass('ri-heart-2-line');
                                favoriteCountBadge.text(parseInt(favoriteCountBadge.text()) - 1);
                            }
                        },
                        error: function(xhr) {
                            console.error('Error favoriting image:', xhr);
                        }
                    });
                });

                // Share functionality
                $(document).on('click', '.share-button', function() {
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

                // Image popups for modal
                $(document).on('click', '.image-popups', function() {
                    const imageUrl = $(this).attr('data-image-url');
                    const title = $(this).attr('title');
                    const prompt = $(this).attr('data-image-prompt');
                    const resolution = $(this).attr('data-image-resolution');
                    
                    // Update modal content
                    $('#modalImage').attr('src', imageUrl);
                    $('#imageModalLabel').text(title);
                    $('#modalPrompt').text(prompt);
                    $('#modalResolution').text(resolution);
                    
                    // Show the modal
                    $('#imageModal').modal('show');
                });

                // Search functionality
                $('#search').on('input', function() {
                    var searchText = $(this).val().toLowerCase();
                    $.ajax({
                        url: '{{ route("ai.image.gallery") }}',
                        type: 'GET',
                        data: { search: searchText },
                        success: function(response) {
                            $('#image-container').html(response);
                        }
                    });
                });
            });
        </script>

    @endsection
    
    @endsection
