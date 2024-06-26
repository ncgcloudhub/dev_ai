@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css" />
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />


@endsection
@section('content')

<div class="row">
    <div class="col">

        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1" id="greeting">Good Morning, {{$user->name}}</h4>
                        </div>
                     
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <!-- card -->
                    <div class="card card-animate" style="background-image: url('https://science.osti.gov/-/media/Initiatives/images/ai_banner.jpg?h=320&w=905&la=en&hash=8F62F5794F19B008A2812A1C2B4421B59252ED230302C24709FDDDDA3453A5D1'); background-size: cover; background-position: center; color: white;">
                        <div class="card-body" style="background-color: rgba(0, 0, 0, 0.6); border-radius: 10px;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-white-50 mb-0">Image Generator</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <a href="{{route('generate.image.view')}}" class="text-decoration-underline text-white-50">
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-white">Generate Images</h4>
                                    </a>
                                </div>
                               
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
                
                <div class="col-xl-4 col-md-6">
                    <!-- card -->
                    <div class="card card-animate" style="background-image: url('https://media.licdn.com/dms/image/D4E12AQGSXjHoeq6Qvg/article-cover_image-shrink_720_1280/0/1717001374666?e=2147483647&v=beta&t=3uSOkrUT70seoRg0UZI44Hgl7GX45m3eTx_ziIQZZ3o'); background-size: cover; background-position: center; color: white;">
                        <div class="card-body" style="background-color: rgba(0, 0, 0, 0.6); border-radius: 10px;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-white-50 mb-0">Prompt Library</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <a href="{{route('prompt.manage')}}" class="text-decoration-underline text-white-50">
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-white">Prompt Library</h4>
                                    </a>
                                </div>
                               
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
                
                 <div class="col-xl-4 col-md-6">
                    <!-- card -->
                    <div class="card card-animate" style="background-image: url('https://imgv3.fotor.com/images/blog-richtext-image/generated-art-image-of-a-lake-with-moutains-and-trees.png'); background-size: cover; background-position: center; color: white;">
                        <div class="card-body" style="background-color: rgba(0, 0, 0, 0.6); border-radius: 10px;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-white-50 mb-0">Image Prompt Idea</p>
                                </div>
                             
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                <a href="{{ route('template.view', ['slug' => 'image-prompt-idea']) }}" class="text-decoration-underline text-white-50">  <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-white">Image Prompt Ideas</h4></a>
                                </div>
                              
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div>
                

                <div class="col-auto">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body bg-primary-subtle shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Total Images <br> Generated</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-primary fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        {{$user->images_generated}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div class="flex-grow-1 overflow-hidden">
                                    @if($user->credits_left == 0)
                                    <a href="{{ route('all.package') }}" class="link-secondary text-decoration-underline">Upgrade Plan</a>
                                @else
                                    <a href="{{ route('generate.image.view') }}" class="link-secondary text-decoration-underline">Generate Image</a>
                                @endif
                                
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class=" bx bx-images text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-auto">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body bg-primary-subtle shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Total Words <br> Generated</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-primary fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        {{$user->words_generated}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div class="flex-grow-1 overflow-hidden">
                                    @if($user->tokens_left == 0)
                                    <a href="{{ route('all.package') }}" class="link-secondary text-decoration-underline">Upgrade Plan</a>
                                @else
                                    <a href="{{ route('template.manage') }}" class="link-secondary text-decoration-underline">Manage Template</a>
                                @endif
                                   
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class=" bx bx-pencil text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-auto">
                    <!-- card -->
                    <div class="card card-animate bg-danger-subtle shadow-lg">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Credits Used</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        {{$user->credits_used}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div class="flex-grow-1 overflow-hidden">
                                   
                                    <a href="{{route('generate.image.view')}}" class="link-secondary text-decoration-underline">Generate <br> Image</a>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-danger-subtle rounded fs-3">
                                        <i class=" bx bx-image-add text-danger"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-auto">
                    <!-- card -->
                    <div class="card card-animate bg-success-subtle shadow-lg">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Credits Left</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                        {{$user->credits_left}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div class="flex-grow-1 overflow-hidden">
                                   
                                    <a href="{{route('generate.image.view')}}" class="link-secondary text-decoration-underline">Generate <br> Image</a>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class=" bx bx-image text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                {{-- 2nd Row --}}
                <div class="col-auto">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body bg-danger-subtle shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Tokens Used</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        {{$user->tokens_used}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div class="flex-grow-1 overflow-hidden">
                                  
                                    <a href="{{route('template.manage')}}" class="link-secondary text-decoration-underline">View all <br>
                                        templates</a>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-danger-subtle rounded fs-3">
                                        <i class=" bx bx-highlight text-danger"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-auto">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body bg-success-subtle shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Tokens Left</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                        {{$user->tokens_left}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div class="flex-grow-1 overflow-hidden">
                                   
                                    <a href="{{route('template.manage')}}" class="link-secondary text-decoration-underline">View all <br>
                                        templates</a>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class=" bx bx-pencil text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-auto">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body bg-secondary-subtle shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Total Templates</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-secondary fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        {{$templates_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                 
                                    <a href="{{route('template.manage')}}" class="link-secondary text-decoration-underline">View all <br> templates
                                        </a>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-secondary-subtle rounded fs-3">
                                        <i class="bx bx-dollar-circle text-secondary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-auto">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body bg-secondary-subtle shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Custom Templates</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-secondary fs-14 mb-0">
                                        <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                        {{$custom_templates_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                   
                                    <a href="{{ route('custom.template.manage')}}" class="link-secondary text-decoration-underline">View all <br> custom
                                        templates</a>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-secondary-subtle rounded fs-3">
                                        <i class="bx bx-shopping-bag text-secondary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-auto">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body bg-secondary-subtle shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                       Total <br> Users</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-muted fs-14 mb-0">
                                        {{$totalUsers}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                  
                                    <a href="{{route('manage.user')}}" class="link-secondary text-decoration-underline">Manage User</a>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-secondary-subtle rounded fs-3">
                                        <i class="bx bx-wallet text-secondary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

            </div> <!-- end row-->

            <div class="row">
            
                {{-- CHAT START--}}
                <div class="col-xxl-6 col-lg-6">
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
                                        <!-- Display area for the image -->
                                        <div id="image_display"></div>
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
                {{-- ------------------------------------------------------------------------------------------------------- --}}
                {{-- Likes --}}
                <div class="col-xxl-6 col-lg-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Liked Images  <span class="badge bg-success-subtle text-success">{{ $totalLikes }}
                            </span></h4>
                        
                        </div><!-- end card header -->

                        <div class="card-body">
                            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Likes</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach($images as $image)
                                    <tr>
                                        <td width="5%">{{ $loop->iteration }}</td>
                                        <td>
                                            <img height="50px" width="50px" class="gallery-img img-fluid mx-auto" src="{{ $image->image_url }}" alt="{{ $image->prompt }}" />
                                        </td>
                                        <td>{{ $image->likes_count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody><!-- end tbody -->
                            </table>
                            
                        </div>
                    </div> <!-- .card-->
                </div> <!-- .col-->


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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Recent Users  <span class="badge bg-success-subtle text-success">{{ $totalUsers }}
                            </span></h4>
                           
                        </div><!-- end card header -->

                        <div class="card-body">
                            <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Sl.</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">IP Address</th>
                                        <th scope="col">Email Verified</th>
                                        <th scope="col">Images generated</th>
                                        <th scope="col">Words Generated</th>
                                        <th scope="col">Registered Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sl = 1;
                                    @endphp
                                    @foreach ($allUsers as $item)

                                    <tr>
                                        <td width="5%">{{ $sl++ }}</td>
                                        <td>
                                            <a href="{{ route('user.details',$item->id) }}" class="fw-medium link-primary">{{$item->name}}</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">{{$item->email}}</div>
                                            </div>
                                        </td>
                                        <td>{{$item->phone}}</td>
                                        <td>{{$item->ipaddress}}</td>
                                        <td>
                                            @if ($item->email_verified_at)
                                            {{ \Carbon\Carbon::parse($item->email_verified_at)->format('F j, Y, g:i a') }}
    
                                            @else
                                                --
                                            @endif
                                            
                                        <td>
                                            <span class="text-success">{{$item->images_generated}}</span>
                                        </td>
                                        <td>
                                            <span class="text-success">  {{$item->words_generated}}</span>
                                          </td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">{{ \Carbon\Carbon::parse($item->created_at)->format('jS, M y') }}
                                            </span>
                                        </td>
                                    </tr><!-- end tr -->

                                    @endforeach
                                </tbody><!-- end tbody -->
                            </table>
                            
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>


<script type="text/javascript" async
  src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML">
</script>
<script type="text/javascript">
  MathJax.Hub.Config({
    tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
  });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var greetingElement = document.getElementById('greeting');
        var currentTime = new Date();
        var currentHour = currentTime.getHours();
        var greeting;

        if (currentHour < 12) {
            greeting = "Good Morning";
        } else if (currentHour < 14) {
            greeting = "Good Noon";
        } else if (currentHour < 18) {
            greeting = "Good Afternoon";
        } else {
            greeting = "Good Evening";
        }

        var currentGreetingText = greetingElement.textContent;
        var name = currentGreetingText.replace(/^Good (Morning|Noon|Afternoon|Evening), /, '');
        greetingElement.textContent = greeting + ", " + name;
    });
</script>

@endsection
