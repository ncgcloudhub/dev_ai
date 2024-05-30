@extends('admin.layouts.master-without-nav')
@section('title')
    @lang('translation.landing')
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
          
           <br><br>
<div class="container mt-5">
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
                        <h3 class="fw-medium link-primary">{{$item->template_name}}</h3>
                        <p style="height: 3em; overflow: hidden; color:black;" class="card-text customer_name">{{$item->description}}</p>
                
                        <a href="{{ route('frontend.free.template.view', ['slug' => $item->slug]) }}" class="btn btn-primary btn-sm">Generate</a>
                
                
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
       
    @endsection
