@extends('user.layouts.master')
@section('title')
    @lang('translation.pricing')
@endsection
@section('content')
    @component('admin.components.breadcrumb')
    @slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
        @slot('title')
            Pricing
        @endslot
    @endcomponent

    <div class="row justify-content-center mt-4">
 
        <div class="col-lg-5">
            <div class="text-center mb-4">
                <h4 class="fw-semibold fs-22">Plans & Pricing       
                          
                </h4>
                <p class="text-muted mb-4 fs-15">Simple pricing. No hidden fees. Advanced features for you business.</p>

                <div class="d-inline-flex">
                    <ul class="nav nav-pills arrow-navtabs plan-nav rounded mb-3 p-1" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold active" id="month-tab" data-bs-toggle="pill"
                                data-bs-target="#month" type="button" role="tab" aria-selected="true">Monthly</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="annual-tab" data-bs-toggle="pill"
                                data-bs-target="#annual" type="button" role="tab" aria-selected="false">Annually <span
                                    class="badge bg-danger">{{$highestDiscount}}% Off</span></button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <div class="row" >
        @foreach ($monthlyPlans as $item)
            <div class="col-xxl-3 col-lg-6 month">
                <div class="card pricing-box ribbon-box right">
                    <div class="card-header">
                        <h2 class="mb-0">{{$item->title}}  
                        </h2>
                    </div>
                    <div class="card-body bg-light m-2 p-4">
                        @if ($item->popular === 'yes')
                            <div class="ribbon-two ribbon-two-success"><span>Popular</span></div>
                        @else
                        @endif
                        
                        <div class="d-flex align-items-center mb-0">
                         
                            <div class="ms-auto">
                                @if ($item->discount_type == 'percentage')
                                    <h2 class="month mb-0"><small class="fs-16"><del>${{ $item->price }}</del></small> ${{ $item->discounted_price }}</h2>
                                   
                                    <span class="badge bg-danger">{{ $item->discount }}% off</span>
                                  
                                
                                  
                                @elseif ($item->discount_type == 'flat')
                                    <h2 class="month mb-0"><small class="fs-16"><del class="text-danger">${{ $item->price }}</del></small> ${{ $item->discounted_price }}</h2>
                                   
                                   <h3> <span class="badge bg-danger">${{ $item->discount }} off</span></h3>
                                @elseif ($item->discount_type == NULL)
                                    <h2 class="month mb-0"><small class="fs-16"></small> ${{ $item->price }}</h2>
                                @endif
                            </div>
                        </div>

                        <p class="text-muted">{{$item->description}}</p>
                        <ul class="list-unstyled vstack gap-3">
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->tokens ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->tokens ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{$item->tokens}}</b> Tokens
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->{'71_ai_templates'} ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->{'71_ai_templates'} ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{$totalTemplates}}</b> Ai Templates
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->ai_chat ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->ai_chat ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Ai Chat
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->ai_code ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->ai_code ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Ai Code
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->text_to_speech ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->text_to_speech ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Text to Speech
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->custom_templates ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->custom_templates ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Custom Templates
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->ai_blog_wizards ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->ai_blog_wizards ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Ai Blog Wizards
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->images ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->images ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{$item->images}}</b> Credits
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->ai_images ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->ai_images ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Ai Images
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->stable_diffusion ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->stable_diffusion ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Stable Diffusion
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->speech_to_text ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->speech_to_text ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{$item->speech_to_text}}</b> Speech to Text
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->live_support ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->live_support ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Live Support
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->free_support ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->free_support ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Free Support
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{ $item->open_id_model ? 'text-success' : 'text-danger' }} me-1">
                                        <i class="{{ $item->open_id_model ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                    {{$item->open_id_model}}
                                    </div>
                                </div>
                            </li>

                            @if ($item->additional_features)
                                @php
                                    $features = explode(',', $item->additional_features);
                            @endphp
                            @foreach ($features as $feature)
                                    <li>
                                    <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                    {{ $feature}}
                                    </div>
                                </div>
                                    </li>
                            @endforeach
                            @endif

                        <li>
                            @if ($item->active === 'active')
                            <span class="badge border border-success text-success fs-6">{{$item->active}}</span>
                            @else
                            <span class="badge border border-danger text-danger fs-6">{{$item->active}}</span>
                            @endif
                        
                        </li>

                        </ul>
                        <div class="d-flex">
                            @if ($item->slug === 'free_monthly' && $lastPackageId === null)
                            <a href="javascript:void(0);" class="btn btn-success w-100" disabled>Your Current Plan</a>
                        @elseif (intval($lastPackageId) === intval($item->id))
                            <a href="javascript:void(0);" class="btn btn-success w-100" disabled>Your Current Plan</a>
                        @else
                            @if ($item->active === "active")
                                <a href="{{ route('checkout', [
                                    'id' => $item->id,
                                    'prod_id' => $item->stripe_prod_id ?? 'prod_Rpzyi6JwMveusr',
                                    'price_id' => $item->stripe_price_id ?? 'price_1QwJyoFLEYsrNPjMzmjyYXVx'
                                ]) }}" class="btn btn-primary w-100 me-2 buy-package-btn">
                                    <i class="ri-shopping-cart-fill"></i> Buy Package
                                </a>
                            @else
                                <a href="#" class="btn btn-secondary w-100 me-2 buy-package-btn" disabled>
                                    <i class="ri-shopping-cart-fill"></i> Inactive Package
                                </a>
                            @endif
                        @endif
                        
                        </div>
                        

                    </div>
                </div>
            </div>
        @endforeach
              
        @foreach ($yearlyPlans as $item)
        
        <div class="col-xxl-3 col-lg-6 annual">
            <div class="card pricing-box ribbon-box right">
                <div class="card-header">
                    <h2 class="mb-0">{{$item->title}}  
                      
                    </h2>
                </div>
                <div class="card-body bg-light m-2 p-4">
                    @if ($item->popular === 'yes')
                        <div class="ribbon-two ribbon-two-success"><span>Popular</span></div>
                    @else
                    @endif
                    
                    <div class="d-flex align-items-center mb-0">
                      
                        <div class="ms-auto">
                            @if ($item->discount_type == 'percentage')
                        <h2 class="annual mb-0"><small class="fs-16"><del class="text-danger">${{ $item->price }}</del></small> ${{ $item->discounted_price }}</h2>
                       
                        <h3><span class="badge bg-danger">{{ $item->discount }}% off</span></h3>
                   
                    @elseif ($item->discount_type == 'flat')
                        <h2 class="annual mb-0"><small class="fs-16"><del class="text-danger">${{ $item->price }}</del></small> ${{ $item->discounted_price }}</h2>
                       
                       <h3> <span class="badge bg-danger">${{ $item->discount }} off</span></h3>
                    @elseif ($item->discount_type == NULL)
                        <h2 class="annual mb-0"><small class="fs-16"></small> ${{ $item->price }}</h2>
                    @endif
                           
                        </div>
                    </div>

                    <p class="text-muted">{{$item->description}}</p>
                    <ul class="list-unstyled vstack gap-3">
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->tokens ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->tokens ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <b>{{$item->tokens}}</b> Tokens
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->{'71_ai_templates'} ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->{'71_ai_templates'} ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <b>{{$totalTemplates}}</b> Ai Templates
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->ai_chat ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->ai_chat ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Ai Chat
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->ai_code ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->ai_code ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Ai Code
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->text_to_speech ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->text_to_speech ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Text to Speech
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->custom_templates ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->custom_templates ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Custom Templates
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->ai_blog_wizards ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->ai_blog_wizards ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Ai Blog Wizards
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->images ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->images ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <b>{{$item->images}}</b> Credits
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->ai_images ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->ai_images ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Ai Images
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->stable_diffusion ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->stable_diffusion ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Stable Diffusion
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->speech_to_text ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->speech_to_text ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <b>{{$item->speech_to_text}}</b> Speech to Text
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->live_support ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->live_support ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Live Support
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->free_support ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->free_support ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Free Support
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 {{ $item->open_id_model ? 'text-success' : 'text-danger' }} me-1">
                                    <i class="{{ $item->open_id_model ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' }} fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    {{$item->open_id_model}}
                                </div>
                            </div>
                        </li>

                        @if ($item->additional_features)
                            @php
                                $features = explode(',', $item->additional_features);
                        @endphp
                        @foreach ($features as $feature)
                                <li>
                                <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                {{ $feature}}
                                </div>
                            </div>
                                </li>
                        @endforeach
                        @endif

                    <li>
                        @if ($item->active === 'active')
                        <span class="badge border border-success text-success fs-6">{{$item->active}}</span>
                        @else
                        <span class="badge border border-danger text-danger fs-6">{{$item->active}}</span>
                        @endif
                    
                    </li>

                    </ul>
                    <div class="d-flex">
                        @if (intval($lastPackageId) === intval($item->id))
                            <a href="javascript:void(0);" class="btn btn-success w-100" disabled>Your Current Plan</a>
                        @else
                            @if ($item->active === "active")
                            {{-- <a href="{{ route('purchase.package', ['pricingPlanId' => $item->id]) }}" class="btn btn-primary w-100 me-2 buy-package-btn">
                                <i class="ri-shopping-cart-fill"></i> Buy Package
                            </a> --}}
                            <a href="{{ route('checkout', [
                                'id' => $item->id,
                                'prod_id' => $item->stripe_prod_id ?? 'prod_Rpzyi6JwMveusr',
                                'price_id' => $item->stripe_price_id ?? 'price_1QwJyoFLEYsrNPjMzmjyYXVx'
                            ]) }}" class="btn btn-primary w-100 me-2 buy-package-btn">
                                <i class="ri-shopping-cart-fill"></i> Buy Package
                            </a>
                            @else
                                <a href="#" class="btn btn-primary w-100 me-2 buy-package-btn" disabled>
                                    <i class="ri-shopping-cart-fill"></i>Inactive Package
                                </a>
                            @endif
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
        @endsection
@section('script')
    <script src="{{ URL::asset('build/js/pages/pricing.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
