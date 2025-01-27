@extends('admin.layouts.master-without-nav')
@section('title', $seo->title)

@section('description', $seo->description)

@section('keywords', $seo->keywords)
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('body')

<body data-bs-spy="scroll" data-bs-target="#navbar-example">

    <style>
        .card-hover {
        transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .card-hover:hover {
            background: linear-gradient(45deg, #ffffff, #eedef5); /* Light color on hover */
            transform: translateY(-5px); /* Slight lift effect on hover */
            cursor: pointer;
        }

    </style>

@endsection
@section('content')
    <!-- Begin page -->
    <div class="layout-wrapper landing d-flex flex-column min-vh-100">
        @include('frontend.body.nav_frontend')
          
        <br><br><br>

        <div class="flex-grow-1">
            <div class="container mt-5">
                <div class="card-body border border-dashed border-end-0 border-start-0 mb-3">
                    <form>
                        <div class="row g-3 justify-content-center">
                            <div class="col-xxl-5 col-sm-6">
                                <div class="search-box" id="search-tour">
                                    <input type="text" class="form-control search"
                                        placeholder="Search for Prompts">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <!--end col-->

                        </div>
                        <!--end row-->
                    </form>
                </div>

                {{-- <div class="card-body border border-dashed border-end-0 border-start-0">
                    <form id="filterForm">
                        <div class="row g-3 justify-content-center">
                            <div class="col-xxl-5 col-sm-6">
                                <select class="form-select" name="category_id" id="category_id" aria-label="Select Category">
                                    <option disabled selected>Select Category</option>
                                    @foreach ($categories as $item)
                                        <option value="{{$item->id}}">{{$item->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-3 col-sm-6">
                                <select class="form-select" name="subcategory_id" id="subcategory_id" aria-label="Select Subcategory">
                                    <option disabled selected>Select Subcategory</option>
                                </select>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-2 col-sm-4">
                                <div>
                                    <button type="submit" class="btn text-white gradient-btn-3 mx-2"> 
                                        <i class="ri-equalizer-fill me-1 align-bottom"></i> Filter
                                    </button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div> --}}
                

                <div class="row template-row">
                    @foreach ($promptLibrary as $item)
                        <div class="col-md-6 p-3 template-card" 
                             data-search="{{ strtolower($item->prompt_name . ' ' . $item->description) }}"
                             data-category-id="{{ $item->category->id }}" 
                             data-subcategory-id="{{ $item->subcategory->id }}">
                             
                            <a href="{{ route('prompt.frontend.view', ['slug' => $item->slug]) }}" class="text-decoration-none">
                                <div class="card shadow-lg h-100 card-hover"> <!-- h-100 ensures the card fills the height -->
                                    <div class="card-body d-flex flex-column"> <!-- flex-column makes the card body stack vertically -->
                                        <div class="d-flex">
                                            <div class="ms-3 flex-grow-1">
                                                <h5 class="text-dark">{{$item->prompt_name}}</h5>
                                                <ul class="list-inline text-muted mb-3">
                                                    <li class="list-inline-item">
                                                        <span class="text-description">{{$item->description}}</span>
                                                    </li>
                                                </ul>
                                                <div class="mt-auto hstack gap-2"> <!-- mt-auto pushes this section to the bottom -->
                                                    <span class="badge bg-success-subtle gradient-text-1">{{$item->category->category_name}}</span>
                                                    <span class="badge bg-primary-subtle gradient-text-2">{{$item->subcategory->sub_category_name}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <h2 class="noresult text-center" style="display: none;">No results found.</h2>
               
                @if (Auth::check())
                @else
                <div class="mx-auto d-flex justify-content-center mb-3">
                    <a href="{{ route('register') }}" class="btn gradient-btn-5">Sign Up to Access more Prompt for FREE </a> <!-- Redirect to user.prompt.library if user is a normal user -->
                </div>
                @endif
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


{{-- SEARCH --}}
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
    document.addEventListener('DOMContentLoaded', function () {
        // Handle category change to update subcategories
        document.getElementById('category_id').addEventListener('change', function() {
            var categoryId = this.value;
            fetch('/prompt/subcategories/' + categoryId)
                .then(response => response.json())
                .then(data => {
                    var subcategorySelect = document.getElementById('subcategory_id');
                    subcategorySelect.innerHTML = '<option disabled selected>Select Subcategory</option>';
                    data.forEach(subcategory => {
                        var option = document.createElement('option');
                        option.value = subcategory.id;
                        option.text = subcategory.sub_category_name;
                        subcategorySelect.appendChild(option);
                    });
                });
        });

        // Handle filter form submission
        document.getElementById('filterForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var categoryId = document.getElementById('category_id').value;
            var subcategoryId = document.getElementById('subcategory_id').value;

            // Filter the prompt cards based on selected category and subcategory
            var templateCards = document.querySelectorAll('.template-card');
            templateCards.forEach(function(card) {
                var cardCategoryId = card.dataset.categoryId;
                var cardSubcategoryId = card.dataset.subcategoryId;

                if ((categoryId === '' || cardCategoryId === categoryId) &&
                    (subcategoryId === '' || cardSubcategoryId === subcategoryId)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Handle search functionality
        const searchInput = document.querySelector('.search');
        const templateCards = document.querySelectorAll('.template-card');
        const noResultMessage = document.querySelector('.noresult');

        searchInput.addEventListener('keyup', function(event) {
            const searchTerm = event.target.value.trim().toLowerCase();
            let found = false;

            templateCards.forEach(function(card) {
                const searchContent = card.dataset.search.toLowerCase();
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

       
@endsection
