@php($hideTopbar = true)
@extends('admin.layouts.master')

@section('title') Edu Library @endsection
@section('css')
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet"/>

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
    <div class="col-xxl-8">
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

                
            </div><!-- end swiper wrapper -->
        </div>


    </div>
    

    {{-- 2nd Col --}}
    <div class="col-xxl-4">
    </div>
</div>


{{-- Scripts --}}
@section('script')
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-crypto.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

@endsection

