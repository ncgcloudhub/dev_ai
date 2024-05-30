@extends('user.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
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
                <div class="col-xl-2 col-md-4">
                    <!-- card -->
                    <div class="card card-animate bg-info-subtle shadow-lg">
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
                                        <i class=" bx bx-receipt text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-2 col-md-4">
                    <!-- card -->
                    <div class="card card-animate bg-info-subtle shadow-lg">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Total Chatbots</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        {{$chatbot_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$chatbot_count}}">0</span>
                                    </h4>
                                    <a href="{{route('chat')}}" class="link-secondary text-decoration-underline">View all Chat Experts
                                        </a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-chat text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-2 col-md-4">
                    <!-- card -->
                    <div class="card card-animate bg-info-subtle shadow-lg">
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
                                        <i class=" bx bx-copy-alt text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-2 col-md-4">
                    <!-- card -->
                    <div class="card card-animate bg-success-subtle shadow-lg">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Credits Used</p>
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
                                    <a href="{{route('generate.image.view')}}" class="link-secondary text-decoration-underline">Generate Image</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class=" bx bx-image-add text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-2 col-md-4">
                    <!-- card -->
                    <div class="card card-animate bg-danger-subtle shadow-lg">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Credits Left</p>
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
                                    <a href="{{route('generate.image.view')}}" class="link-secondary text-decoration-underline">Generate Image</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-danger-subtle rounded fs-3">
                                        <i class=" bx bx-images text-danger"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                {{-- 2nd Row --}}
                <div class="col-xl-2 col-md-4">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body bg-success-subtle shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Tokens Used</p>
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
                                    <a href="{{route('template.manage')}}" class="link-secondary text-decoration-underline">View all
                                        templates</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class=" bx bx-highlight text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-2 col-md-4">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body bg-danger-subtle shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Tokens Left</p>
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
                                    <a href="{{route('template.manage')}}" class="link-secondary text-decoration-underline">View all
                                        templates</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-danger-subtle rounded fs-3">
                                        <i class=" bx bx-pencil text-danger"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->


            </div> <!-- end row-->

            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Latest Images</h4>
                        </div><!-- end card header -->

                        <div class="card-header p-0 border-0 bg-light-subtle">
                            <div class="row gallery-wrapper">
                      
                                @foreach ($images as $item)
                                  
                                <div class="element-item col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 photography" data-category="photography">
                                    <div class="gallery-box card">
                                        <div class="gallery-container">
                                            <a class="image-popup" href="{{ asset($item->image_url) }}" title="">
                                                <img class="gallery-img img-fluid mx-auto d-block" src="{{ asset($item->image_url) }}" alt="" />
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

                {{-- CHAT START--}}
                <div class="col-xxl-4 col-lg-6">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Chat</h4>
                            
                            <div class="form-group mb-0 ms-auto">
                                <label for="ai_model_select" class="form-label">Select Session</label>
                                <select class="form-select" id="session">
                                    @foreach ($sessions as $item)
                                   
                                    <option value="{{$item->id}}">{{$item->id}}</option>
                                         
                                    @endforeach
                                 
                                </select>
                            </div>
                            <div class="form-group mb-0 ms-auto">
                                <label for="ai_model_select" class="form-label">Select AI Model:</label>
                                <select class="form-select" id="ai_model_select">
                                    <option value="">Use Default</option>
                                    <option value="gpt-3.5-turbo">GPT-3.5</option>
                                    <option value="gpt-4">GPT-4</option>
                                    <option value="gpt-4o">GPT-4o</option>
                                </select>
                            </div>
                        </div>
                
                        <div class="card-body p-0">
                            <div id="chat-conversation" class="chat-conversation p-3" data-simplebar style="height: 400px;">
                                <ul class="list-unstyled chat-conversation-list chat-sm" id="users-conversation">
                                    <!-- Messages will be appended here -->
                                </ul>
                            </div>
                            <div class="border-top border-top-dashed">
                                <div class="row g-2 mx-3 mt-2 mb-3">
                                    <div id="file_name_display"></div>
                                    <div class="col-auto">
                                        <i id="icon" class="ri-attachment-line" style="cursor: pointer; font-size:22px;"></i>
                                        <input name="file" type="file" id="file_input" class="form-control" style="display: none;" accept=".txt,.pdf,.doc,.docx,.jpg,.jpeg,.png">
                
                                    </div>
                                    <div class="col">
                                        <div class="position-relative">
                                            <textarea class="form-control chat-input bg-light border-light auto-expand" id="user_message_input" rows="1" placeholder="Type your message..." autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" id="send_message_btn" class="btn btn-primary"><span class="d-none d-sm-inline-block me-2">Send</span> <i class="mdi mdi-send float-end"></i></button>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" id="new_session_btn" class="btn btn-secondary">New Session</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        {{-- CHAT END --}}
        
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
                                                        <h5 class="fs-14 my-1"><a href="{{ route('template.view', ['slug' => $item->slug]) }}" class="text-reset">{{$item->template_name}}</a></h5>
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


        </div> <!-- end .h-100-->

    </div> <!-- end col -->

   
</div>


@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/swiper.init.js') }}"></script>

<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js')}}"></script>

<!-- dashboard init -->
<script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
