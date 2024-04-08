@extends('user.layouts.master')
@section('title')
    @lang('translation.pricing')
@endsection
@section('content')
    @component('admin.components.breadcrumb')
        @slot('li_1')
            Subscription
        @endslot
        @slot('title')
            Pricing
        @endslot
    @endcomponent
    <div class="row justify-content-center mt-4">
        <div class="col-lg-5">
            <div class="text-center mb-4">
                <h4 class="fw-semibold fs-22">Plans & Pricing</h4>
                <p class="text-muted mb-4 fs-15">Simple pricing. No hidden fees. Advanced features for you business.</p>

                <div class="d-inline-flex">
                    <ul class="nav nav-pills arrow-navtabs plan-nav rounded mb-3 p-1" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold active" id="month-tab" data-bs-toggle="pill"
                                data-bs-target="#month" type="button" role="tab" aria-selected="true">Monthly</button>
                        </li>
                      
                    </ul>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

   
        <div class="row justify-content-center">
            <div class="col-xxl-3 col-lg-6">
                <div class="card pricing-box">
                    <div class="card-body bg-light m-2 p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fw-semibold">Starter</h5>
                            </div>
                            <div class="ms-auto">
                                <h2 class="month mb-0">Free</h2>
                            </div>
                        </div>
    
                        <p class="text-muted">The perfect way to get started and get used to our tools.</p>
                        <ul class="list-unstyled vstack gap-3">
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>Chat GPT 4</b> Open AI Model
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>Dall-E 3</b> Image Generate
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>75</b> AI Templates
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Custom Templates
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>50</b> Images per week
                                    </div>
                                </div>
                            </li>
                           
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>5000</b> Words
                                    </div>
                                </div>
                            </li>
                         
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>Unlimited</b> Login
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                       Free Support
                                    </div>
                                </div>
                            </li>
                           
                            
                           
                        </ul>
                        <div class="mt-3 pt-2">
                            <a href="javascript:void(0);" class="btn btn-success disabled w-100">Your Current Plan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!--end row-->

@endsection
@section('script')
    <script src="{{ URL::asset('build/js/pages/pricing.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
