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
          
           <br><br><br>

    
<div class="container mt-5">

    <div class="col-md-12 px-3">
        <div class="row justify-content-center">
            <form>
                <div class="row g-3 justify-content-center my-3">
                    <div class="col">
                        <div class="search-box" id="search-tour">
                            <input type="text" class="form-control search border-color-purple"
                                placeholder="Search for Templates">
                            <i class="ri-search-line search-icon color-purple"></i>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </form>

            <div class="noresult my-2" style="display: none">
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                        trigger="loop" colors="primary:#405189,secondary:#0ab39c"
                        style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Sorry! No Tools Found</h5>
                    <p class="text-muted">We've searched all tools related to education. We did not find any tools matching your search.</p>

                    <form action="{{ route('template.module.feedback') }}" method="POST">
                        @csrf
                        <div class="form-group d-flex gap-2">
                            <input type="text" class="form-control border-color-purple" id="feedbackText" name="text" placeholder="Enter your feedback" required>
                            <button type="submit" class="btn gradient-btn-3">Submit</button>
                        </div>
                    </form>
                    
                </div>
            </div>

            {{-- 2nd Col START--}}
            <div class="col-xl-12 col-md-12">
                <div class="text-center mb-3">
                    <ul class="nav nav-pills filter-btns justify-content-center" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium active" type="button" data-filter="all">All Items</button>
                        </li>
                        @foreach($categories as $category)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-medium" type="button" data-filter="{{ Str::slug($category->category_name) }}">{{ $category->category_name }}</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            {{-- 2nd Col END--}}
        </div>

        <div class="row">
            @foreach($tools as $tool)
            <div class="col-lg-4 product-item template-card {{ Str::slug($tool->educationtools_category->category_name) }}" data-search="{{ strtolower($tool->name . ' ' . $tool->description) }}">
                <div class="card explore-box card-animate">
                    <div class="bookmark-icon position-absolute top-0 end-0 p-2">
                        <button type="button" class="btn btn-icon" data-bs-toggle="button" aria-pressed="true">
                            <i class="mdi mdi-cards-heart fs-16"></i>
                        </button>
                    </div>
                    <div class="explore-place-bid-img">
                        <img src="{{ asset('storage/' . $tool->image) }}" alt="" class="card-img-top explore-img" />
                        <div class="bg-overlay"></div>
                        <div class="place-bid-btn">
                            <a href="{{ route('frontend.free.education.view', ['id' => $tool->id, 'slug' => $tool->slug]) }}" class="btn btn-primary">
                                <i class="ri-auction-fill align-bottom me-1"></i>Explore
                            </a>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="fw-medium mb-0 float-end favorite-wrapper">
                            <button class="favorite-button" data-id="{{ $tool->id }}" style="border: none; background: none; cursor: pointer;">
                                <i class="{{ $tool->is_favorited ? 'mdi mdi-heart' : 'mdi mdi-heart-outline' }} text-danger align-middle"></i>
                            </button>
                           
                        </p>
                        <h5 class="mb-1 fs-16">
                            <a href="{{ route('tool.show', ['id' => $tool->id, 'slug' => $tool->slug]) }}" class="text-body">{{ $tool->name }}</a>
                        </h5>
                        <p class="text-muted fs-14 mb-0">{{ $tool->description }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

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
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/nft-landing.init.js') }}"></script>

<script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/rater-js/index.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/rating.init.js') }}"></script>
<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>


<!--ecommerce-customer init js -->
<script src="{{ URL::asset('build/js/pages/ecommerce-order.init.js') }}"></script>
 
<script src="{{ URL::asset('build/js/app.js') }}"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('.search');
            const templateCards = document.querySelectorAll('.template-card');
            const noResultMessage = document.querySelector('.noresult');

            searchInput.addEventListener('keyup', function (event) {
                const searchTerm = event.target.value.trim().toLowerCase();
                let found = false;

                templateCards.forEach(function (card) {
                    const searchContent = card.dataset.search;
                    if (searchContent.includes(searchTerm)) {
                        card.style.display = 'block';
                        found = true;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (!found) {
                    noResultMessage.style.display = 'block';
                } else {
                    noResultMessage.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
        $('.favorite-button').on('click', function() {
            let button = $(this);
            let icon = button.find('i');
            let toolsId = button.data('id'); // Get the tools ID
            let isFavorited = icon.hasClass('mdi-heart'); // Check if already favorited
            
            $.ajax({
                url: '{{ route('toggle.favorite') }}', // Use the named route for the URL
                method: 'POST',
                data: {
                    tools_id: toolsId, // The ID of the tool being favorited
                    _token: '{{ csrf_token() }}' // CSRF token for security
                },
                success: function(response) {
                    if (response.success) {
                        // Get the icon inside the button
                        let icon = button.find('i'); // Use `button` from the closure

                        // Toggle heart icon based on action
                        if (response.action === 'added') {
                            icon.removeClass('mdi-heart-outline').addClass('mdi-heart'); // Filled heart
                        } else {
                            icon.removeClass('mdi-heart').addClass('mdi-heart-outline'); // Hollow heart
                        }
                    }
                },

                error: function() {
                    alert('Failed to toggle favorite. Please try again.');
                }
            });
        });
        });

    </script>
       
@endsection
