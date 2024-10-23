@extends('admin.layouts.master-without-nav')
@section('title')
   Templates
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('body')

<body data-bs-spy="scroll" data-bs-target="#navbar-example">
   
    <style>
        .template-card:hover {
         transform: scale(.95);
         transition: transform 0.3s ease;
         
     } 
     
     .template-card:hover .card-body {
         background: linear-gradient(45deg, #ffffff, #f2e6f7);
     }
     
     
     </style>

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
                            placeholder="Search for Templates">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->
               
            </div>
            <!--end row-->
        </form>
    </div>

    <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3 justify-content-center" role="tablist">
        <li class="nav-item" id="category-tour">
            <a class="nav-link n1 active All py-3" data-bs-toggle="tab" id="All"
                href="#home1" role="tab" aria-selected="true">
                <i class="ri-store-2-fill me-1 align-bottom"></i> All Templates
            </a>
        </li>
        @foreach ($templatecategories as $item)
            <li class="nav-item">
                <a class="nav-link n1 py-3 {{$item->category_name}}" data-bs-toggle="tab" id="{{$item->id}}"
                    href="#{{$item->id}}" role="tab" aria-selected="false">
                    <i class="{{$item->category_icon}}"></i> {{$item->category_name}}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="row template-row">
        @foreach ($templates as $item)
       
        <div class="col-md-3 p-3 template-card" data-category="{{$item->category_id}}" data-search="{{ strtolower($item->template_name . ' ' . $item->description) }}">
            
           
                <div class="card" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <a href="{{ route('frontend.free.template.view', ['slug' => $item->slug]) }}">
                    <div class="card-body">
                        <div style="width: 42px; height: 42px; border-radius: 50%; background-color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 .125rem .3rem -0.0625rem rgba(0,0,0,.1),0 .275rem .75rem -0.0625rem rgba(249,248,249,.06)">
                            {{-- <i style="font-size: 24px; color: #333;" class="{{$item->icon}}"></i> --}}
                            <img width="22px" src="/build/images/templates/{{$item->icon}}.png" alt="" class="img-fluid">
                        </div>
                        <h3 class="fw-medium link-primary gradient-text-1">{{$item->template_name}}</h3>
                        <p style="height: 3em; overflow: hidden; color:black;" class="card-text customer_name">{{$item->description}}</p>
                
                        <a href="{{ route('frontend.free.template.view', ['slug' => $item->slug]) }}" class="btn gradient-btn-9 btn-sm">Generate</a>
                
                
                        <ul class="list-inline hstack gap-2 mb-0">
                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                                {{-- <a href="apps-ecommerce-order-details" class="text-primary d-inline-block">
                                    <i class="ri-eye-fill fs-16"></i>
                                </a> --}}
                            </li>
                            <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                {{-- <a href="#showModal" data-bs-toggle="modal" class="text-primary d-inline-block edit-item-btn">
                                    <i class="ri-pencil-fill fs-16"></i>
                                </a> --}}
                            </li>
                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                {{-- <a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" href="#deleteOrder">
                                    <i class="ri-delete-bin-5-fill fs-16"></i>
                                </a> --}}
                            </li>
                        </ul>
                    </div>
                </a>
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
        $('.n1').on('click', function() {

            // Remove 'active' class from all nav links
        $('.n1').removeClass('active');
        
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
