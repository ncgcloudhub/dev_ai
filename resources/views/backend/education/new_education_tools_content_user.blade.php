@php($hideTopbar = true)
@extends('admin.layouts.master')

@section('title') Edu Library @endsection
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet"/>

    {{-- Filter CSS --}}
    <link href="{{ URL::asset('build/libs/nouislider/nouislider.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">

<style>
    .no-topbar .page-content {
        margin-top: 20px !important;
        padding-top: 0 !important;
    }
    
</style>

@endsection

@section('content')

<div class="row">
    <h4 class="gradient-text-1-bold">Education Contents</h4>
    {{-- 1st Col --}}
    <div class="col-xxl-4">
    {{-- Filter Start --}}
        <div class="card">
            <div class="card-header">
               xxxxx
            </div>

            <div class="accordion accordion-flush filter-accordion">

                <div class="card-body border-bottom">
                    <div>
                        <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Grade/Class</p>
                        <ul class="list-unstyled mb-0 filter-list">
                            @foreach ($classes as $class)
                                <li>
                                    <a href="#" class="d-flex py-1 align-items-center class-item"
                                       data-id="{{ $class->id }}"
                                       data-grade="{{ $class->grade }}"
                                       data-subjects='@json($class->subjects)'>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-13 mb-0 listname">{{ $class->grade }}</h5>
                                        </div>
                                        @if ($class->subjects->count())
                                            <div class="flex-shrink-0 ms-2">
                                                <span class="badge bg-light text-muted">{{ $class->subjects->count() }}</span>
                                            </div>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        
                    </div>
                </div>

                <div class="card-body border-bottom">
                    <p class="text-muted text-uppercase fs-12 fw-medium mb-4">Price</p>

                    <div id="product-price-range"></div>
                    <div class="formCost d-flex gap-2 align-items-center mt-3">
                        <input class="form-control form-control-sm" type="text" id="minCost" value="0" /> <span class="fw-semibold text-muted">to</span> <input
                            class="form-control form-control-sm" type="text" id="maxCost" value="1000" />
                    </div>
                </div>

            </div>
        </div>
    {{-- Filter End --}}

     {{-- Swiper Slider --}}
     <div class="swiper cryptoSlider">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/img-01.jpg')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                    <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 19.29k </p>
                    <h5 class="text-primary"><i class="mdi mdi-ethereum"></i> 97.8 ETH </h5>
                    <h6 class="fs-15 mb-3"><a href="apps-nft-item-details" class="text-body">Abstract Face Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end -->
            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/img-01.jpg')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                    <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 19.29k </p>
                    <h5 class="text-primary"><i class="mdi mdi-ethereum"></i> 97.8 ETH </h5>
                    <h6 class="fs-15 mb-3"><a href="apps-nft-item-details" class="text-body">Abstract Face Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end -->
            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/img-01.jpg')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                    <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 19.29k </p>
                    <h5 class="text-primary"><i class="mdi mdi-ethereum"></i> 97.8 ETH </h5>
                    <h6 class="fs-15 mb-3"><a href="apps-nft-item-details" class="text-body">Abstract Face Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end -->
            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/img-01.jpg')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                    <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 19.29k </p>
                    <h5 class="text-primary"><i class="mdi mdi-ethereum"></i> 97.8 ETH </h5>
                    <h6 class="fs-15 mb-3"><a href="apps-nft-item-details" class="text-body">Abstract Face Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end -->

            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/img-01.jpg')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                    <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 19.29k </p>
                    <h5 class="text-primary"><i class="mdi mdi-ethereum"></i> 97.8 ETH </h5>
                    <h6 class="fs-15 mb-3"><a href="apps-nft-item-details" class="text-body">Abstract Face Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end -->

            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/img-01.jpg')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                    <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 19.29k </p>
                    <h5 class="text-primary"><i class="mdi mdi-ethereum"></i> 97.8 ETH </h5>
                    <h6 class="fs-15 mb-3"><a href="apps-nft-item-details" class="text-body">Abstract Face Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end -->

            
        </div>
    </div><!-- end swiper wrapper -->



    </div>
    

    {{-- 2nd Col --}}
    <div class="col-xxl-8">
        <div class="card">
            <div class="card-header border-0">
                <div class="row g-4">
                    <div class="col-sm-auto">
                        <div>
                            <a href="apps-ecommerce-add-product" class="btn btn-success" id="addproduct-btn"><i
                                    class="ri-add-line align-bottom me-1"></i> Add Product</a>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="d-flex justify-content-sm-end">
                            <div class="search-box ms-2">
                                <input type="text" class="form-control" id="searchProductList" placeholder="Search Products...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#productnav-all"
                                    role="tab">
                                    All <span class="badge bg-danger-subtle text-danger align-middle rounded-pill ms-1">12</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#productnav-published"
                                    role="tab">
                                    Published <span class="badge bg-danger-subtle text-danger align-middle rounded-pill ms-1">5</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#productnav-draft"
                                    role="tab">
                                    Draft
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <div id="selection-element">
                            <div class="my-n1 d-flex align-items-center text-muted">
                                Select <div id="select-content" class="text-body fw-semibold px-1"></div> Result <button type="button" class="btn btn-link link-danger p-0 ms-3" data-bs-toggle="modal" data-bs-target="#removeItemModal">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card header -->
            <div class="card-body">

                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="productnav-all" role="tabpanel">
                        <div id="table-product-list-all" class="table-card gridjs-border-none"></div>
                    </div>
                    <!-- end tab pane -->

                    <div class="tab-pane" id="productnav-published" role="tabpanel">
                        <div id="table-product-list-published" class="table-card gridjs-border-none"></div>
                    </div>
                    <!-- end tab pane -->

                    <div class="tab-pane" id="productnav-draft" role="tabpanel">
                        <div class="py-4 text-center">
                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                trigger="loop" colors="primary:#405189,secondary:#0ab39c"
                                style="width:72px;height:72px">
                            </lord-icon>
                            <h5 class="mt-4">Sorry! No Result Found</h5>
                        </div>
                    </div>
                    <!-- end tab pane -->
                </div>
                <!-- end tab content -->

            </div>
            <!-- end card body -->
        </div>



    </div>
</div>


{{-- Scripts --}}
@section('script')
    {{-- Caroseol Scripts --}}
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-crypto.init.js') }}"></script>

    {{-- Filter Scripts --}}
    <script src="{{ URL::asset('build/libs/nouislider/nouislider.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/wnumb/wNumb.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/gridjs/gridjs.umd.js') }}"></script>
    <script src="https://unpkg.com/gridjs/plugins/selection/dist/selection.umd.js"></script>
    <script src="{{ URL::asset('build/js/pages/cc_ecommerce-product-list.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    {{-- FOR SUBJECTS after clicking Class --}}
    <script>
        $(document).on('click', '.class-item', function (e) {
            e.preventDefault();
    
            const subjects = $(this).data('subjects');
    
            let tabList = $('.nav-tabs-custom'); // Adjust if you need a more specific selector
            tabList.empty(); // Clear existing tabs
    
            // Dynamically add subject tabs
            if (subjects.length > 0) {
                subjects.forEach(subject => {
                    tabList.append(`
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#subject-${subject.id}" role="tab">
                                ${subject.name}
                            </a>
                        </li>
                    `);
                });
            } else {
                tabList.append(`
                    <li class="nav-item">
                        <a class="nav-link fw-semibold disabled" href="#" role="tab">
                            No Subjects
                        </a>
                    </li>
                `);
            }
        });
    </script>
    
@endsection

@endsection

