@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('template.manage')}}">Education</a> @endslot
@slot('title') Manage Tools @endslot
@endcomponent
<a href="{{ route('add.education.tools') }}" class="btn btn-lg gradient-btn-3 my-1">Add</a>

<section class="py-5 gradient-background-1 position-relative">
    <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-sm">
                <div>
                    <h4 class="text-white mb-0 fw-semibold">Create Your Contents with our Pre-defined Tools</h4>
                </div>
            </div>
            <!-- end col -->
           
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>

<!-- start wallet -->
<section class="section" id="wallet">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2 class="mb-3 fw-bold lh-base">Education Tools</h2>
                    <p class="text-muted">A non-fungible token is a non-interchangeable unit of data stored on a blockchain, a form of digital ledger, that can be sold and traded.</p>
                </div>
            </div><!-- end col -->
        </div><!-- end row -->

        <div class="row g-4">
            @foreach ($tools as $item)
           
            <div class="col-lg-4">
                <div class="card text-center border shadow-none">
                    <div class="card-body">
                        <img src="{{URL::asset('build/images/nft/wallet/metamask.png')}}" alt="" height="55" class="mb-3 pb-2">
                        <h5>{{$item->name}}</h5>
                        <p class="text-muted fs-14 pb-1">{{$item->description}}.</p>
                        <a href="#!" class="btn btn-soft-info mb-4">Explore</a>
                        <ul class="list-inline hstack gap-2">
                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                                {{-- <a href="apps-ecommerce-order-details" class="text-primary d-inline-block">
                                    <i class="ri-eye-fill fs-16"></i>
                                </a> --}}
                            </li>
                            <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                <a href="#" class="text-primary d-inline-block edit-item-btn">
                                    <i class="ri-pencil-fill fs-16"></i>
                                </a>
                            </li>
                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                <a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" href="#">
                                    <i class="ri-delete-bin-5-fill fs-16"></i>
                                </a>
                            </li>
                        </ul>
                        {{-- <div class="card-footer border-top border-top-dashed">
                            <div class="d-flex align-items-center">
                                <a href="#" class="btn gradient-btn-1 me-2 flex-shrink-0 fs-14 text-primary mb-0 btn-sm">Edit</a>
                                <a href="#" class="btn btn-soft-danger flex-shrink-0 fs-14 text-primary mb-0 btn-sm">Delete</a>
                            
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div><!-- end col -->
                 
            @endforeach
           
        </div><!-- end row -->
    </div><!-- end container -->
</section><!-- end wallet -->

<!-- start marketplace -->
<section class="section bg-light" id="marketplace">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2 class="mb-3 fw-bold lh-base">Explore Products</h2>
                    <p class="text-muted mb-4">Collection widgets specialize in displaying many elements of the same type, such as a collection of pictures from a collection of articles.</p>
                    <ul class="nav nav-pills filter-btns justify-content-center" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium active" type="button" data-filter="all">All Items</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" type="button" data-filter="artwork">Artwork</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" type="button" data-filter="music">Music</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" type="button" data-filter="games">Games</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" type="button" data-filter="crypto-card">Crypto Card</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" type="button" data-filter="3d-style">3d Style</button>
                        </li>
                    </ul>
                </div>
            </div><!-- end col -->
        </div><!-- end row -->
        <div class="row">
            <div class="col-lg-4 product-item artwork crypto-card 3d-style">
                <div class="card explore-box card-animate">
                    <div class="bookmark-icon position-absolute top-0 end-0 p-2">
                        <button type="button" class="btn btn-icon" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                    </div>
                    <div class="explore-place-bid-img">
                        <img src="{{URL::asset('build/images/nft/img-03.jpg')}}" alt="" class="card-img-top explore-img" />
                        <div class="bg-overlay"></div>
                        <div class="place-bid-btn">
                            <a href="#!" class="btn btn-primary"><i class="ri-auction-fill align-bottom me-1"></i> Place Bid</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 19.29k </p>
                        <h5 class="mb-1 fs-16"><a href="apps-nft-item-details" class="text-body">Creative Filtered Portrait</a></h5>
                        <p class="text-muted fs-14 mb-0">Photography</p>
                    </div>
                    <div class="card-footer border-top border-top-dashed">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 fs-14">
                                <i class="ri-price-tag-3-fill text-warning align-bottom me-1"></i> Highest: <span class="fw-medium">75.3ETH</span>
                            </div>
                            <h5 class="flex-shrink-0 fs-14 text-primary mb-0">67.36 ETH</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 product-item music crypto-card games">
                <div class="card explore-box card-animate">
                    <div class="bookmark-icon position-absolute top-0 end-0 p-2">
                        <button type="button" class="btn btn-icon active" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                    </div>
                    <div class="explore-place-bid-img">
                        <img src="{{URL::asset('build/images/nft/img-02.jpg')}}" alt="" class="card-img-top explore-img" />
                        <div class="bg-overlay"></div>
                        <div class="place-bid-btn">
                            <a href="#!" class="btn btn-primary"><i class="ri-auction-fill align-bottom me-1"></i> Place Bid</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 23.63k </p>
                        <h5 class="mb-1 fs-16"><a href="apps-nft-item-details" class="text-body">The Chirstoper</a></h5>
                        <p class="text-muted fs-14 mb-0">Music</p>
                    </div>
                    <div class="card-footer border-top border-top-dashed">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 fs-14">
                                <i class="ri-price-tag-3-fill text-warning align-bottom me-1"></i> Highest: <span class="fw-medium">412.30ETH</span>
                            </div>
                            <h5 class="flex-shrink-0 fs-14 text-primary mb-0">394.7 ETH</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 product-item artwork music games">
                <div class="card explore-box card-animate">
                    <div class="bookmark-icon position-absolute top-0 end-0 p-2">
                        <button type="button" class="btn btn-icon active" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                    </div>
                    <div class="explore-place-bid-img">
                        <img src="https://img.themesbrand.com/velzon/images/img-4.gif" alt="" class="card-img-top explore-img" />
                        <div class="bg-overlay"></div>
                        <div class="place-bid-btn">
                            <a href="#!" class="btn btn-primary"><i class="ri-auction-fill align-bottom me-1"></i> Place Bid</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 15.93k </p>
                        <h5 class="mb-1 fs-16"><a href="apps-nft-item-details" class="text-body">Evolved Reality</a></h5>
                        <p class="text-muted fs-14 mb-0">Video</p>
                    </div>
                    <div class="card-footer border-top border-top-dashed">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 fs-14">
                                <i class="ri-price-tag-3-fill text-warning align-bottom me-1"></i> Highest: <span class="fw-medium">2.75ETH</span>
                            </div>
                            <h5 class="flex-shrink-0 fs-14 text-primary mb-0">3.167 ETH</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 product-item crypto-card 3d-style">
                <div class="card explore-box card-animate">
                    <div class="bookmark-icon position-absolute top-0 end-0 p-2">
                        <button type="button" class="btn btn-icon active" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                    </div>
                    <div class="explore-place-bid-img">
                        <img src="{{URL::asset('build/images/nft/img-01.jpg')}}" alt="" class="card-img-top explore-img" />
                        <div class="bg-overlay"></div>
                        <div class="place-bid-btn">
                            <a href="#!" class="btn btn-primary"><i class="ri-auction-fill align-bottom me-1"></i> Place Bid</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 14.85k </p>
                        <h5 class="mb-1 fs-16"><a href="apps-nft-item-details" class="text-body">Abstract Face Painting</a></h5>
                        <p class="text-muted fs-14 mb-0">Collectibles</p>
                    </div>
                    <div class="card-footer border-top border-top-dashed">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 fs-14">
                                <i class="ri-price-tag-3-fill text-warning align-bottom me-1"></i> Highest: <span class="fw-medium">122.34ETH</span>
                            </div>
                            <h5 class="flex-shrink-0 fs-14 text-primary mb-0">97.8 ETH</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 product-item games music 3d-style">
                <div class="card explore-box card-animate">
                    <div class="bookmark-icon position-absolute top-0 end-0 p-2">
                        <button type="button" class="btn btn-icon active" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                    </div>
                    <div class="explore-place-bid-img">
                        <img src="{{URL::asset('build/images/nft/img-05.jpg')}}" alt="" class="card-img-top explore-img" />
                        <div class="bg-overlay"></div>
                        <div class="place-bid-btn">
                            <a href="#!" class="btn btn-primary"><i class="ri-auction-fill align-bottom me-1"></i> Place Bid</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 64.10k </p>
                        <h5 class="mb-1 fs-16"><a href="apps-nft-item-details" class="text-body">Long-tailed Macaque</a></h5>
                        <p class="text-muted fs-14 mb-0">Artwork</p>
                    </div>
                    <div class="card-footer border-top border-top-dashed">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 fs-14">
                                <i class="ri-price-tag-3-fill text-warning align-bottom me-1"></i> Highest: <span class="fw-medium">874.01ETH</span>
                            </div>
                            <h5 class="flex-shrink-0 fs-14 text-primary mb-0">745.14 ETH</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 product-item artwork music crypto-card">
                <div class="card explore-box card-animate">
                    <div class="bookmark-icon position-absolute top-0 end-0 p-2">
                        <button type="button" class="btn btn-icon" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                    </div>
                    <div class="explore-place-bid-img">
                        <img src="{{URL::asset('build/images/nft/img-06.jpg')}}" alt="" class="card-img-top explore-img" />
                        <div class="bg-overlay"></div>
                        <div class="place-bid-btn">
                            <a href="#!" class="btn btn-primary"><i class="ri-auction-fill align-bottom me-1"></i> Place Bid</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> 36.42k </p>
                        <h5 class="mb-1 fs-16"><a href="apps-nft-item-details" class="text-body">Robotic Body Art</a></h5>
                        <p class="text-muted fs-14 mb-0">Artwork</p>
                    </div>
                    <div class="card-footer border-top border-top-dashed">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 fs-14">
                                <i class="ri-price-tag-3-fill text-warning align-bottom me-1"></i> Highest: <span class="fw-medium">41.658 ETH</span>
                            </div>
                            <h5 class="flex-shrink-0 fs-14 text-primary mb-0">34.81 ETH</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end container -->
</section>
<!-- end marketplace -->


<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->

@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/nft-landing.init.js') }}"></script>
@endsection