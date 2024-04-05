@extends('user.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<div class="row">
    <div class="col">

        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">Good Morning, {{$user->name}}</h4>
                           
                        </div>
                        
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Total Templates</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        {{$templates_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$templates_count}}">0</span>
                                    </h4>
                                    <a href="{{route('template.manage')}}" class="link-secondary text-decoration-underline">View all templates
                                        </a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-dollar-circle text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Custom Templates</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                        {{$custom_templates_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$custom_templates_count}}">0</span></h4>
                                    <a href="{{ route('custom.template.manage')}}" class="link-secondary text-decoration-underline">View all custom
                                        templates</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-shopping-bag text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Images Left</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                        {{$user->images_left}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="   {{$user->images_left}}">0</span>
                                    </h4>
                                    <a href="{{route('all.package')}}" class="link-secondary text-decoration-underline">Upgrade Plan                          </a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-user-circle text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Images Generated</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        {{$user->images_generated}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target=" {{$user->images_generated}}">0</span>
                                    </h4>
                                    <a href="{{route('all.package')}}" class="link-secondary text-decoration-underline">See
                                        subscription</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-wallet text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                {{-- 2nd Row --}}
                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Words Generated</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        {{$user->words_generated}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target=" {{$user->words_generated}}">0</span></h4>
                                    <a href="#" class="link-secondary text-decoration-underline">View all
                                        templates</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-shopping-bag text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Words Left</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                        {{$user->words_left}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$user->words_left}}">0</span></h4>
                                    <a href="#" class="link-secondary text-decoration-underline">View all
                                        templates</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-shopping-bag text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->


            </div> <!-- end row-->

            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Latest Images</h4>
                        </div><!-- end card header -->

                        <div class="card-header p-0 border-0 bg-light-subtle">
                            <div class="row gallery-wrapper">
                      
                                @foreach ($images as $item)
                                  
                                <div class="element-item col-xxl-3 col-xl-4 col-sm-6 photography" data-category="photography">
                                    <div class="gallery-box card">
                                        <div class="gallery-container">
                                            <a class="image-popup" href="{{ asset($item->image) }}" title="">
                                                <img class="gallery-img img-fluid mx-auto" src="{{ asset($item->image) }}" alt="" />
                                                <div class="gallery-overlay">
                                                    <h5 class="overlay-caption">{{$item->prompt}}</h5>
                                                </div>
                                            </a>
                                        </div>
        
                                        <div class="box-content">
                                            <div class="d-flex align-items-center mt-1">
                                                <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">{{$item->user->name}}</a></div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <!-- end col -->
                                
                            </div>
                        </div><!-- end card header -->

                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-4">
                    <!-- card -->
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Our users across the Globe</h4>
                           
                        </div><!-- end card header -->

                        <!-- card body -->
                        <div class="card-body">

                            <div id="sales-by-locations" data-colors='["--vz-light", "--vz-secondary", "--vz-primary"]' style="height: 269px" dir="ltr"></div>

                            <div class="px-2 py-2 mt-1">
                               
                            @foreach ($usersByCountry as $item)
                               
                                <p class="mt-3 mb-1">{{$item->country}}<span class="float-end">{{ number_format(($item->total_users / $totalUsers) * 100, 0) }}%
                                </span>
                                </p>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: {{ number_format(($item->total_users / $totalUsers) * 100, 0) }}%" aria-valuenow="{{ number_format(($item->total_users / $totalUsers) * 100, 0) }}" aria-valuemin="0" aria-valuemax="{{ number_format(($item->total_users / $totalUsers) * 100, 0) }}">
                                    </div>
                                </div>
     
                             @endforeach
                              
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Trending Templates</h4>
                           
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                    <tbody>
                                        @foreach ($templates as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="text-center">
                                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                                            <i class="ri-store-2-fill me-1 align-center"></i>
                                                        </div>
                                                    </div>
                                                    
                                                    <div>
                                                        <h5 class="fs-14 my-1"><a href="apps-ecommerce-product-details" class="text-reset">{{$item->template_name}}</a></h5>
                                                        <span class="text-muted">{{ date('d M, Y', strtotime($item->created_at)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">{{$item->description}}</h5>
                                                <span class="text-muted">Description</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">{{$item->template_category->category_name}}</h5>
                                                <span class="text-muted">Category</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">{{$item->total_word_generated}}</h5>
                                                <span class="text-muted">Words Generated</span>
                                            </td>
                                        </tr>      
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Custom Templates</h4>
                         
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                                    <tbody>
                                        @foreach ($custom_templates as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="text-center">
                                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                                            <i class="ri-store-2-fill me-1 align-center"></i>
                                                        </div>
                                                    </div>
                                                    
                                                    <div>
                                                        <h5 class="fs-14 my-1"><a href="apps-ecommerce-product-details" class="text-reset">{{$item->template_name}}</a></h5>
                                                        <span class="text-muted">{{ date('d M, Y', strtotime($item->created_at)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">{{$item->description}}</h5>
                                                <span class="text-muted">Description</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">{{$item->template_category->category_name}}</h5>
                                                <span class="text-muted">Category</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">{{$item->total_word_generated}}</h5>
                                                <span class="text-muted">Words Generated</span>
                                            </td>
                                        </tr>      
                                        @endforeach
                                       
                                    </tbody>
                                </table><!-- end table -->
                            </div>

                        </div> <!-- .card-body-->
                    </div> <!-- .card-->
                </div> <!-- .col-->
            </div> <!-- end row-->

            <div class="row">
                <div class="col-xl-4">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Store Visits by Source</h4>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Download Report</a>
                                        <a class="dropdown-item" href="#">Export</a>
                                        <a class="dropdown-item" href="#">Import</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div id="store-visits-source" data-colors='["--vz-primary", "--vz-primary-rgb, 0.85", "--vz-primary-rgb, 0.70", "--vz-primary-rgb, 0.60", "--vz-primary-rgb, 0.45"]' class="apex-charts" dir="ltr"></div>
                        </div>
                    </div> <!-- .card-->
                </div> <!-- .col-->

                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Recent Orders</h4>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-soft-primary btn-sm">
                                    <i class="ri-file-list-3-line align-middle"></i> Generate Report
                                </button>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                    <thead class="text-muted table-light">
                                        <tr>
                                            <th scope="col">Order ID</th>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Product</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Vendor</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Rating</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <a href="apps-ecommerce-order-details" class="fw-medium link-primary">#VZ2112</a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <img src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" alt="" class="avatar-xs rounded-circle" />
                                                    </div>
                                                    <div class="flex-grow-1">Alex Smith</div>
                                                </div>
                                            </td>
                                            <td>Clothes</td>
                                            <td>
                                                <span class="text-success">$109.00</span>
                                            </td>
                                            <td>Zoetic Fashion</td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success">Paid</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 fw-medium mb-0">5.0<span class="text-muted fs-11 ms-1">(61
                                                        votes)</span></h5>
                                            </td>
                                        </tr><!-- end tr -->
                                        <tr>
                                            <td>
                                                <a href="apps-ecommerce-order-details" class="fw-medium link-primary">#VZ2111</a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <img src="{{ URL::asset('build/images/users/avatar-2.jpg') }}" alt="" class="avatar-xs rounded-circle" />
                                                    </div>
                                                    <div class="flex-grow-1">Jansh Brown</div>
                                                </div>
                                            </td>
                                            <td>Kitchen Storage</td>
                                            <td>
                                                <span class="text-success">$149.00</span>
                                            </td>
                                            <td>Micro Design</td>
                                            <td>
                                                <span class="badge bg-warning-subtle text-warning">Pending</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 fw-medium mb-0">4.5<span class="text-muted fs-11 ms-1">(61
                                                        votes)</span></h5>
                                            </td>
                                        </tr><!-- end tr -->
                                        <tr>
                                            <td>
                                                <a href="apps-ecommerce-order-details" class="fw-medium link-primary">#VZ2109</a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <img src="{{ URL::asset('build/images/users/avatar-3.jpg') }}" alt="" class="avatar-xs rounded-circle" />
                                                    </div>
                                                    <div class="flex-grow-1">Ayaan Bowen</div>
                                                </div>
                                            </td>
                                            <td>Bike Accessories</td>
                                            <td>
                                                <span class="text-success">$215.00</span>
                                            </td>
                                            <td>Nesta Technologies</td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success">Paid</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 fw-medium mb-0">4.9<span class="text-muted fs-11 ms-1">(89
                                                        votes)</span></h5>
                                            </td>
                                        </tr><!-- end tr -->
                                        <tr>
                                            <td>
                                                <a href="apps-ecommerce-order-details" class="fw-medium link-primary">#VZ2108</a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <img src="{{ URL::asset('build/images/users/avatar-4.jpg') }}" alt="" class="avatar-xs rounded-circle" />
                                                    </div>
                                                    <div class="flex-grow-1">Prezy Mark</div>
                                                </div>
                                            </td>
                                            <td>Furniture</td>
                                            <td>
                                                <span class="text-success">$199.00</span>
                                            </td>
                                            <td>Syntyce Solutions</td>
                                            <td>
                                                <span class="badge bg-danger-subtle text-danger">Unpaid</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 fw-medium mb-0">4.3<span class="text-muted fs-11 ms-1">(47
                                                        votes)</span></h5>
                                            </td>
                                        </tr><!-- end tr -->
                                        <tr>
                                            <td>
                                                <a href="apps-ecommerce-order-details" class="fw-medium link-primary">#VZ2107</a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <img src="{{ URL::asset('build/images/users/avatar-6.jpg') }}" alt="" class="avatar-xs rounded-circle" />
                                                    </div>
                                                    <div class="flex-grow-1">Vihan Hudda</div>
                                                </div>
                                            </td>
                                            <td>Bags and Wallets</td>
                                            <td>
                                                <span class="text-success">$330.00</span>
                                            </td>
                                            <td>iTest Factory</td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success">Paid</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 fw-medium mb-0">4.7<span class="text-muted fs-11 ms-1">(161
                                                        votes)</span></h5>
                                            </td>
                                        </tr><!-- end tr -->
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div>
                        </div>
                    </div> <!-- .card-->
                </div> <!-- .col-->
            </div> <!-- end row-->

        </div> <!-- end .h-100-->

    </div> <!-- end col -->

   
</div>


@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js')}}"></script>
<!-- dashboard init -->
<script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
