@extends('admin.layouts.master-without-nav')
@section('title')
   Templates
@endsection
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

    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form>
            <div class="row g-3 justify-content-center">
                <div class="col-xxl-5 col-sm-6">
                    <div class="search-box" id="search-tour">
                        <input type="text" class="form-control search"
                            placeholder="Search for order ID, customer, order status or something...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->
                
                <div class="col-xxl-2 col-sm-4">
                    <div>
                        <button type="button" class="btn btn-primary w-100" id="enter-button"
                            onclick="SearchData();"> <i
                                class="ri-search-fill me-1 align-bottom"></i>
                            Search
                        </button>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </form>
    </div>

    <div class="row template-row">
        @foreach ($promptLibrary as $item)
       
        <div class="col-md-6 p-3 template-card" data-search="{{ strtolower($item->prompt_name . ' ' . $item->description) }}">
            
           
            <div class="card shadow-lg h-100"> <!-- h-100 ensures the card fills the height -->
                <div class="card-body d-flex flex-column"> <!-- flex-column makes the card body stack vertically -->
                    <div class="d-flex mb-3">
                        <div class="ms-3 flex-grow-1">
                            <a href="{{ route('prompt.frontend.view', ['slug' => $item->slug]) }}">
                                <h5>{{$item->prompt_name}}</h5>
                            </a>
                            <ul class="list-inline text-muted mb-3">
                                <li class="list-inline-item">
                                    <span class="text-description">{{$item->description}}</span>
                                </li>
                            </ul>
                            <div class="mt-auto hstack gap-2"> <!-- mt-auto pushes this section to the bottom -->
                                <span class="badge bg-success-subtle text-success">{{$item->category->category_name}}</span>
                                <span class="badge bg-primary-subtle text-primary">{{$item->subcategory->sub_category_name}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
       
            </div>
        
            @endforeach
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


<script>
    $(document).ready(function() {
        $('.nav-link').on('click', function() {

            // Remove 'active' class from all nav links
        $('.nav-link').removeClass('active');
        
        // Add 'active' class to the clicked nav link
        $(this).addClass('active');
        
            var category = $(this).attr('id');
            if (category === 'All') {
            $('.template-card').show(); // Show all templates
        } else {
            $('.template-card').hide(); // Hide all templates initially
            $('.template-card[data-category="' + category + '"]').show(); // Show templates that match the selected category
        }
        });
    });
</script>

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
       
    @endsection
