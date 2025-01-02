@extends('admin.layouts.master-without-nav')
@section('title', $seo->title)

@section('description', $seo->description)

@section('keywords', $seo->keywords)
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
@endsection
@section('body')

<style>
    #imageModal .btn-link {
        z-index: 10;
        position: absolute;
        bottom: 20px;
        right: 20px;
    }
</style>

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
                                                <div class="row">
                                                    <div class="col">
                                                        <select class="form-select mb-3" name="resolution" aria-label="Filter By Resolution">
                                                            <option value="" selected>Filter By Resolution</option>
                                                            <option value="1024x1024">1024x1024</option>
                                                            <option value="256x256">256x256</option>
                                                            <option value="512x512">512x512</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <select class="form-select mb-3" name="style" aria-label="Filter By Style">
                                                            <option value="" selected>Filter By Style</option>
                                                            <option value="natural">Natural</option>
                                                            <option value="vivid">Vivid</option>
                                                            <option value="none">NONE</option>
                                                            <option value="cinematic">CINEMATIC</option>
                                                            <option value="analog-film">ANALOG FILM</option>
                                                            <option value="animation">ANIMATION</option>
                                                            <option value="comic">COMIC</option>
                                                            <option value="craft-clay">CRAFT CLAY</option>
                                                            <option value="fantasy">FANTASY</option>
                                                            <option value="line-art">LINE ART</option>
                                                            <option value="cyberpunk">CYBERPUNK</option>
                                                            <option value="pixel-art">PIXEL ART</option>
                                                            <option value="photograph">PHOTOGRAPH</option>
                                                            <option value="graffiti">GRAFFITI</option>
                                                            <option value="game-gta">GAME GTA</option>
                                                            <option value="3d-character">3D CHARACTER</option>
                                                            <option value="baroque">BAROQUE</option>
                                                            <option value="caricature">CARICATURE</option>
                                                            <option value="colored-pencil">COLORED PENCIL</option>
                                                            <option value="doddle-art">DODDLE ART</option>
                                                            <option value="futurism">FUTURISM</option>
                                                            <option value="sketch">SKETCH</option>
                                                            <option value="surrealism">SURREALISM</option>
                                                            <option value="sticker-designs">STICKER DESIGNS</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" name="search" id="search" class="form-control" placeholder="Search by prompt" value="{{ request()->query('search') }}">
                                                    </div>
                                                </div>
                                            </form>
                                            
                                            <br>
                                            <div class="row gallery-wrapper justify-content-center" id="image-container">
                                                @include('frontend.stable_images_partial_frontend', ['stableImages' => $stableImages])
                                            </div>
                                            <div class="row gallery-wrapper justify-content-center" id="image-container1">
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
                                <button type="button" class="btn btn-outline-secondary" id="fullscreenButton">
                                    <i class="ri-fullscreen-line"></i> Fullscreen
                                </button>
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
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

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
        var images = []; // To hold the list of images
        var currentIndex = -1; // To track the current image index

        function updateModalContent(image) {
            $('#modalImage').attr('src', image.url);
            $('#imageModalLabel').text(image.title);
            $('#resolution').text(image.resolution);
        }

        $(document).on('click', '.image-popups', function() {
            images = $('.image-popups').map(function() {
                return {
                    url: $(this).data('image-url'),
                    title: $(this).attr('title'),
                    resolution: $(this).data('image-resolution')
                };
            }).get();

            currentIndex = $('.image-popups').index(this);
            const image = images[currentIndex];
            updateModalContent(image);

            // Show the modal
            $('#imageModal').modal('show');
        });

        $('#prevButton').click(function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateModalContent(images[currentIndex]);
            }
        });

        $('#nextButton').click(function() {
            if (currentIndex < images.length - 1) {
                currentIndex++;
                updateModalContent(images[currentIndex]);
            }
        });
    </script>


    <script>
        $(document).ready(function() {
            var page = 1; // initialize page number
            var isLoading = false; // flag to prevent multiple simultaneous AJAX requests
            var hasMoreImages = true; // flag to check if there are more images to load
            let debounceTimer;

            function loadMoreImages() {
                if (isLoading || !hasMoreImages) return; // exit if already loading or no more images
                isLoading = true; // set loading flag

                $('.infinite-scroll-loader').show(); // show loader
                $.ajax({
                    url: '{{ route("ai.image.gallery") }}',
                    type: 'GET',
                    data: {
                        page: page,
                        search: $('#search').val(),
                        resolution: $('select[name="resolution"]').val(),
                        style: $('select[name="style"]').val()
                    },
                    success: function(response) {
                        console.log(response); // Debug response structure
                        if (!response.imagesPartial && !response.stableImagesPartial) {
                            hasMoreImages = false; // No more images to load
                        } else {
                            if (response.imagesPartial) {
                                $('#image-container1').append(response.imagesPartial);
                            }
                            if (response.stableImagesPartial) {
                                $('#image-container').append(response.stableImagesPartial);
                            }
                            page++; // Increment page
                        }
                        $('.infinite-scroll-loader').hide(); // Hide loader
                        isLoading = false; // Reset loading flag
                    }
                });

            }

                $(window).scroll(function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function() {
                        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300) {
                            loadMoreImages();
                        }
                    }, 200); // Adjust debounce time as needed
                });


            $('#search, select[name="resolution"], select[name="style"]').on('change input', function() {
            var searchText = $('#search').val().toLowerCase();
            var resolution = $('select[name="resolution"]').val();
            var style = $('select[name="style"]').val();
            
            $.ajax({
                url: '{{ route("ai.image.gallery") }}',
                type: 'GET',
                data: {
                    search: searchText,
                    resolution: resolution,
                    style: style
                },
                success: function(response) {
                    $('#image-container').html(response.stableImagesPartial);
                    $('#image-container1').html(response.imagesPartial);
                    page = 1; // reset page number
                    hasMoreImages = true; // reset has more images flag
                }
            });
            });

                // Initial load of images only if it's not an AJAX request
                // if (!window.location.href.includes('?')) {
                //         loadMoreImages();
                //     }

            });
    </script>


    {{-- LIKE --}}
    <script>
        $(document).ready(function() {
            // Like button functionality
            $(document).off('click', '.like-button').on('click', '.like-button', function() {
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

            // Favorite button functionality
            $(document).off('click', '.favorite-button').on('click', '.favorite-button', function() {
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
                            favoriteButton.removeClass('ri-heart-2-line').addClass('ri-heart-2-fill');
                            favoriteCountBadge.text(parseInt(favoriteCountBadge.text()) + 1);
                        } else {
                            // Image is unfavorited
                            favoriteButton.removeClass('ri-heart-2-fill').addClass('ri-heart-2-line');
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

<script>
    $(document).on('click', '#fullscreenButton', function() {
        const modalImage = document.getElementById('modalImage');
        if (modalImage.requestFullscreen) {
            modalImage.requestFullscreen();
        } else if (modalImage.mozRequestFullScreen) { // Firefox
            modalImage.mozRequestFullScreen();
        } else if (modalImage.webkitRequestFullscreen) { // Chrome, Safari and Opera
            modalImage.webkitRequestFullscreen();
        } else if (modalImage.msRequestFullscreen) { // IE/Edge
            modalImage.msRequestFullscreen();
        }
    });
</script>

@endsection
    
@endsection