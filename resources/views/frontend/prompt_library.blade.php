@extends('admin.layouts.master-without-nav')
@section('title')
   Prompt Library
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

            <!-- start Jobs -->
            <section class="section pb-0 hero-section mb-3" id="hero">
                <div class="container">
                    <div class="row align-items-stretch gy-4"> <!-- align-items-stretch ensures equal height for cards -->
                        @foreach ($promptLibrary as $item)
                        <div class="col-lg-6 d-flex"> <!-- Use d-flex to make the column a flex container -->
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
            </section>
            
            <!-- end Jobs -->

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
       
    @endsection
