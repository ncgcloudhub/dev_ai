@extends('admin.layouts.master')
@section('title')
    @lang('translation.search-results')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
    <style>
        /* Add this CSS for the selected background color */
        .selected-background {
            background: linear-gradient(45deg, #9293e0, #db9dd4); /* Choose a color you like for the selected card */
            color: #e0e0e0; /* Adjust the text color if needed for better contrast */
        }
    </style>
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Image @endslot
@slot('title') Stable Diffusion @endslot
@endcomponent


    <div class="row">

       
        <div class="col-lg-12">
           <!-- Positioning Wrapper for Absolute Positioning -->
       <!-- Positioning Wrapper for Absolute Positioning -->
       <div class="position-relative">
        <div class="row justify-content-center mb-4">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h4 class="card-title mb-2 mb-md-0">Generate Image</h4>
                <div class="d-flex flex-column flex-sm-row">
                    <a href="{{ route('aicontentcreator.view', ['slug' => 'image-prompt-idea']) }}" class="btn gradient-btn-6 btn-load mb-3">
                        <span class="d-flex align-items-center">
                            <span class="spinner-grow" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                            <span>Get Image Prompt Ideas</span>
                        </span>
                    </a>
                    <button type="button" class="btn gradient-btn-5 mb-3" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                        Prompt Library
                    </button>
                </div>
            </div><!-- end card header -->
        
            <div class="col-lg-6 col-12">
                <form id="imageForm" action="{{ route('stable.image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="hiddenStyle" id="hiddenStyle">
                    <input type="hidden" name="hiddenImageFormat" id="hiddenImageFormat">
                    <input type="hidden" name="hiddenModelVersion" id="hiddenModelVersion">
                    <input type="hidden" name="hiddenPromptOptimize" id="hiddenPromptOptimize_sd">
        
                    <div class="row g-2">
                        <!-- Search Box -->
                        <div class="col-12 col-md-9 order-1 order-md-1">
                            <div class="search-box position-relative">
                                <a title="Optimize Prompt" class="btn btn-link link-success btn-lg position-absolute top-50 translate-middle-y"
                                   onclick="toggleOptimize('sd')" id="optimizeIcon_sd">
                                    <i class="ri-hammer-line"></i>
                                </a>
                                <textarea class="form-control search ps-5 mt-1" name="prompt" rows="1" id="prompt" placeholder="Write prompt to generate Image"></textarea>
                            </div>
                        </div>
        
                        <!-- Generate Button -->
                        <div class="col-12 col-md-3 text-end order-2 order-md-2">
                            <button 
                            onclick="syncOffcanvasInput()" 
                            type="submit" 
                            class="btn gradient-btn-7 btn-lg waves-effect waves-light w-100 disabled-on-load" 
                            disabled>
                            <i class="mdi mdi-magnify me-1"></i> Generate
                        </button>
                        </div>
                    </div>
                </form>
            </div>
            <!--end col-->
        </div>
        

                    <div class="row justify-content-center p-3" style="background: linear-gradient(45deg, #ffffff, #d4b2d0); border-radius: 12px; padding: 3px;">
                            <div class="col-xl-3 col-lg-4 col-sm-6" style="background: linear-gradient(45deg, #b7aee7, #dfc6dc); border-radius: 12px; padding: 15px;">

                                <label for="modelVersion" class="form-label">Select Model Version</label>
                                <select name="modelVersion" id="modelVersion" class="form-select" data-choices onchange="syncModelVersion()">
                                    <option value="" disabled selected>Select Model</option>
                                    <option value="sd3-medium">sd3-medium</option>
                                    <option value="sd3-large-turbo">sd3-large-turbo</option>
                                    <option value="sd3-large">sd3-large</option>
                                    <option value="sd3.5-medium">sd3.5-medium</option>
                                    <option value="sd3.5-large-turbo">sd3.5-large-turbo</option>
                                    <option value="sd3.5-large">sd3.5-large</option>
                                    <option value="sd-ultra">SD Ultra</option>
                                    <option value="sd-core">SD Core</option>
                                </select>


                                <label for="imageFormat" class="form-label">Select Image Format</label>
                                <select name="imageFormat" id="imageFormat" class="form-select" data-choices onchange="syncImageFormat()">
                                    <option value="" disabled selected>Select format</option>
                                    <option value="jpeg">JPEG</option>
                                    <option value="png">PNG</option>
                                    <option value="webp">WEBP</option>
                                </select>
                                
                                
                            </div>
                        
                            <!--end col-->
                            <div class="col-xl-6 col-lg-4 col-sm-6" data-simplebar data-simplebar-auto-hide="false" style="max-height: 220px;">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <!-- Image Box 1 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Animation', this)" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/animation.jpg') }}" alt="Animation" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0 gradient-text-1-bold">Animation</p>
                                        </div>
                                    </div>
                                    <!-- Image Box 1 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Animation', this)" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/animation.jpg') }}" alt="Animation" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0 gradient-text-1-bold">Animation</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 2 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Cinematic', this)" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/cinematic.jpg') }}" alt="Cinematic" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0 gradient-text-1-bold">Cinematic</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 3 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Comic', this)" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/comic.jpg') }}" alt="Comic" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0 gradient-text-1-bold">Comic</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 4 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Cyberpunk', this)" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/cyberpunk.jpg') }}" alt="Cyberpunk" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0 gradient-text-1-bold">Cyberpunk</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 5 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Futurism', this)" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/futurism.jpeg') }}" alt="Futurism" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0 gradient-text-1-bold">Futurism</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 6 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Doodle Art', this)" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/doodle_art.jpg') }}" alt="Doodle Art" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0 gradient-text-1-bold">Doodle Art</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 7 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Graffiti', this)" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/graffiti.jpg') }}" alt="Graffiti" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0 gradient-text-1-bold">Graffiti</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 8 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Sketch', this)" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/sketch.jpg') }}" alt="Sketch" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0 gradient-text-1-bold">Sketch</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                            <!--end col-->
                           
                   
                <div>
                    <ul class="nav nav-tabs nav-tabs-custom justify-content-center" role="tablist">
                        <li class="nav-item" id="result-li">
                            <a class="nav-link" data-bs-toggle="tab" href="#all" role="tab" aria-selected="false">
                                <i class="ri-search-2-line text-muted align-bottom me-1"></i>Result
                            </a>
                        </li>
                        <li class="nav-item active" id="image-li">
                            <a class="nav-link" data-bs-toggle="tab" id="images-tab" href="#images" role="tab"
                                aria-selected="false">
                                <i class="ri-image-fill text-muted align-bottom me-1"></i> Images
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#news" role="tab" aria-selected="false">
                                <i class="ri-list-unordered text-muted align-bottom me-1"></i>Examples
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#video" role="tab" aria-selected="false">
                                <i class="ri-video-line text-muted align-bottom me-1"></i> Videos
                            </a>
                        </li>
                        
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content text-muted">
                        <div class="tab-pane" id="all" role="tabpanel">
                            <div id="imageContainer" class="pb-3">
                                
                            </div>

                        </div>

                        <div class="tab-pane active" id="images" role="tabpanel">
                           
                            <div class="gallery-light">
                                <div class="row">
                                    @foreach($images as $item)
                                        <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="gallery-box card">
                                            <div class="gallery-container">
                                                <a class="image-popup" href="{{ $item->image_url }}" title="">
                                                    <img class="gallery-img img-fluid mx-auto"
                                                        src="{{ $item->image_url }}" alt="" />
                                                    <div class="gallery-overlay">
                                                        <h5 class="overlay-caption">{{ $item->prompt }}</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="box-content">
                                                <div class="d-flex align-items-center mt-2">
                                                 
                                                    <div class="flex-shrink-0">
                                                        <div class="d-flex gap-3">
                                                        <button type="button"
                                                            class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0 like-button"
                                                            data-image-id="{{ $item->id }}">
                                                            <i class="ri-thumb-up-fill text-muted align-bottom me-1"></i>
                                                            <span class="like-count">{{ $item->likes_count ?? 0 }}</span>
                                                        </button>
                                                        <a href="{{ $item->image_url }}" download="{{ basename($item->image_url) }}"
                                                            class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0 download-button"
                                                            onclick="incrementDownloadCount({{ $item->id }})">
                                                             <i class="ri-download-fill text-muted align-bottom me-1"></i> <span class="download-count">{{ $item->downloads }} </span>
                                                         </a>
                                                                                                                 
                                                            <button type="button"
                                                                class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                <i
                                                                    class="ri-question-answer-fill text-muted align-bottom me-1"></i>
                                                                1.3K
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!--end row-->
                                <div class="mt-4">
                                    <ul class="pagination pagination-separated justify-content-center mb-0">
                                        <li class="page-item disabled">
                                            <a href="javascript:void(0);" class="page-link"><i
                                                    class="mdi mdi-chevron-left"></i></a>
                                        </li>
                                        <li class="page-item active">
                                            <a href="javascript:void(0);" class="page-link">1</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript:void(0);" class="page-link">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript:void(0);" class="page-link">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript:void(0);" class="page-link">4</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript:void(0);" class="page-link">5</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript:void(0);" class="page-link"><i
                                                    class="mdi mdi-chevron-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="news" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-1.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-success-subtle text-success fs-11">Business</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">A mix of friends and strangers heading
                                                            off to find an adventure</a></h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            James Ballard</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            23 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-2.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-warning-subtle text-warning fs-11">Development</span>
                                                        </li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">How to get creative in your work ?</a>
                                                    </h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Ruby Griffin</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            23 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-3.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-info-subtle text-info fs-11">Fashion</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">How to become a best sale marketer in
                                                            a year!</a></h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Elwood Arter</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            23 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-4.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-primary-subtle text-primary fs-11">Product</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">Manage white space in responsive
                                                            layouts ?</a></h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Nancy Martino</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            23 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-5.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-success-subtle text-success fs-11">Business</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">Stack designer Olivia Murphy offers
                                                            freelancing advice</a></h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Erica Kernan</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            11 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-6.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-danger-subtle text-danger fs-11">Design</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">A day in the of a professional fashion
                                                            designer</a></h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Jason McQuaid</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            14 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-7.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-danger-subtle text-danger fs-11">Design</span></li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">Design your apps in your own way</a>
                                                    </h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Henry Baird</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            19 Nov, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-sm-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('build/images/small/img-8.jpg') }}" alt="" width="115"
                                                        class="rounded-1" />
                                                </div>
                                                <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                    <ul class="list-inline mb-2">
                                                        <li class="list-inline-item"><span
                                                                class="badge bg-warning-subtle text-warning fs-11">Development</span>
                                                        </li>
                                                    </ul>
                                                    <h5><a href="javascript:void(0);">How apps is changing the IT world</a>
                                                    </h5>
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item"><i
                                                                class="ri-user-3-fill text-success align-middle me-1"></i>
                                                            Elwood Arter</li>
                                                        <li class="list-inline-item"><i
                                                                class="ri-calendar-2-fill text-success align-middle me-1"></i>
                                                            10 Aug, 2021</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->

                            <div class="mt-4">
                                <ul class="pagination pagination-separated justify-content-center mb-0">
                                    <li class="page-item disabled">
                                        <a href="javascript:void(0);" class="page-link"><i
                                                class="mdi mdi-chevron-left"></i></a>
                                    </li>
                                    <li class="page-item active">
                                        <a href="javascript:void(0);" class="page-link">1</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="javascript:void(0);" class="page-link">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="javascript:void(0);" class="page-link">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="javascript:void(0);" class="page-link">4</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="javascript:void(0);" class="page-link">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="javascript:void(0);" class="page-link"><i
                                                class="mdi mdi-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="video" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12 video-list">
                                    <div class="list-element">
                                        <h5 class="mb-1"><a href="javascript:void(0);">Admin dashboard templates
                                                - Material Design for Velzon</a></h5>
                                        <p class="text-success">https://themesbrand.com/velzon/index.html</p>
                                        <div class="d-flex flex-column flex-sm-row">
                                            <div class="flex-shrink-0">
                                                <iframe src="https://www.youtube.com/embed/GfSZtaoc5bw"
                                                    title="YouTube video" allowfullscreen class="rounded"></iframe>
                                            </div>
                                            <div class="flex-grow-1 ms-sm-3 mt-2 mt-sm-0">
                                                <p class="text-muted mb-0">Velzon admin is super flexible, powerful, clean,
                                                    modern & responsive admin template based on <b>bootstrap 5</b> stable
                                                    with unlimited possibilities. You can simply change to any layout or
                                                    mode by changing a couple of lines of code. You can start small and
                                                    large projects or update design in your existing project using Velzon it
                                                    is very quick and easy as it is beautiful, adroit, and delivers the
                                                    ultimate user experience.</p>
                                                <div class="border border-dashed mb-1 mt-3"></div>
                                                <ul
                                                    class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-thumb-up-line align-middle me-1"></i>335</li>
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-question-answer-line align-middle me-1"></i>102</li>
                                                    <li class="list-inline-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-user-line"></i>
                                                            </div>
                                                            <div class="flex-grow-1 fs-13 ms-1">
                                                                <span class="fw-medium">Themesbrand</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end list-element-->

                                    <div class="list-element mt-4">
                                        <h5 class="mb-1"><a href="javascript:void(0);">Create Responsive Admin
                                                Dashboard using Html CSS</a></h5>
                                        <p class="text-success">https://themesbrand.com/velzon/index.html</p>
                                        <div class="d-flex flex-column flex-sm-row">
                                            <div class="flex-shrink-0">
                                                <iframe src="https://www.youtube.com/embed/Z-fV2lGKnnU"
                                                    title="YouTube video" allowfullscreen class="rounded"></iframe>
                                            </div>
                                            <div class="flex-grow-1 ms-sm-3 mt-2 mt-sm-0">
                                                <p class="text-muted mb-0">Velzon admin is super flexible, powerful, clean,
                                                    modern & responsive admin template based on <b>bootstrap 5</b> stable
                                                    with unlimited possibilities. You can simply change to any layout or
                                                    mode by changing a couple of lines of code. You can start small and
                                                    large projects or update design in your existing project using Velzon it
                                                    is very quick and easy as it is beautiful, adroit, and delivers the
                                                    ultimate user experience.</p>
                                                <div class="border border-dashed mb-1 mt-3"></div>
                                                <ul
                                                    class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-thumb-up-line align-middle me-1"></i>485</li>
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-question-answer-line align-middle me-1"></i>167</li>
                                                    <li class="list-inline-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-user-line"></i>
                                                            </div>
                                                            <div class="flex-grow-1 fs-13 ms-1">
                                                                <span class="fw-medium">Themesbrand</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end list-element-->

                                    <div class="list-element mt-4">
                                        <h5 class="mb-1"><a href="javascript:void(0);">Velzon - The Most Popular
                                                Bootstrap 5 HTML, Angular & React Js Admin</a></h5>
                                        <p class="text-success">https://themesbrand.com/velzon/index.html</p>
                                        <div class="d-flex flex-column flex-sm-row">
                                            <div class="flex-shrink-0">
                                                <iframe src="https://www.youtube.com/embed/2RZQN_ko0iU"
                                                    title="YouTube video" allowfullscreen class="rounded"></iframe>
                                            </div>
                                            <div class="flex-grow-1 ms-sm-3 mt-2 mt-sm-0">
                                                <p class="text-muted mb-0">Velzon admin is super flexible, powerful, clean,
                                                    modern & responsive admin template based on <b>bootstrap 5</b> stable
                                                    with unlimited possibilities. You can simply change to any layout or
                                                    mode by changing a couple of lines of code. You can start small and
                                                    large projects or update design in your existing project using Velzon it
                                                    is very quick and easy as it is beautiful, adroit, and delivers the
                                                    ultimate user experience.</p>
                                                <div class="border border-dashed mb-1 mt-3"></div>
                                                <ul
                                                    class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-thumb-up-line align-middle me-1"></i>122</li>
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-question-answer-line align-middle me-1"></i>51</li>
                                                    <li class="list-inline-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-user-line"></i>
                                                            </div>
                                                            <div class="flex-grow-1 fs-13 ms-1">
                                                                <span class="fw-medium">Themesbrand</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end list-element-->

                                    <div class="list-element mt-4">
                                        <h5 class="mb-1"><a href="javascript:void(0);">Velzon Admin Dashboard
                                                (website analytics) with Bootstrap 5</a></h5>
                                        <p class="text-success">https://themesbrand.com/velzon/index.html</p>
                                        <div class="d-flex flex-column flex-sm-row">
                                            <div class="flex-shrink-0">
                                                <iframe src="https://www.youtube.com/embed/Z-fV2lGKnnU"
                                                    title="YouTube video" allowfullscreen class="rounded"></iframe>
                                            </div>
                                            <div class="flex-grow-1 ms-sm-3 mt-2 mt-sm-0">
                                                <p class="text-muted mb-0">Velzon admin is super flexible, powerful, clean,
                                                    modern & responsive admin template based on <b>bootstrap 5</b> stable
                                                    with unlimited possibilities. You can simply change to any layout or
                                                    mode by changing a couple of lines of code. You can start small and
                                                    large projects or update design in your existing project using Velzon it
                                                    is very quick and easy as it is beautiful, adroit, and delivers the
                                                    ultimate user experience.</p>
                                                <div class="border border-dashed mb-1 mt-3"></div>
                                                <ul
                                                    class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-thumb-up-line align-middle me-1"></i>485</li>
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-question-answer-line align-middle me-1"></i>69</li>
                                                    <li class="list-inline-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-user-line"></i>
                                                            </div>
                                                            <div class="flex-grow-1 fs-13 ms-1">
                                                                <span class="fw-medium">Themesbrand</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end list-element-->

                                    <div class="list-element mt-4">
                                        <h5 class="mb-1"><a href="javascript:void(0);">Dashboard Admin Basics -
                                                YouTube</a></h5>
                                        <p class="text-success">https://themesbrand.com/velzon/index.html</p>
                                        <div class="d-flex flex-column flex-sm-row">
                                            <div class="flex-shrink-0">
                                                <iframe src="https://www.youtube.com/embed/1y_kfWUCFDQ"
                                                    title="YouTube video" allowfullscreen class="rounded"></iframe>
                                            </div>
                                            <div class="flex-grow-1 ms-sm-3 mt-2 mt-sm-0">
                                                <p class="text-muted mb-0">Velzon admin is super flexible, powerful, clean,
                                                    modern & responsive admin template based on <b>bootstrap 5</b> stable
                                                    with unlimited possibilities. You can simply change to any layout or
                                                    mode by changing a couple of lines of code. You can start small and
                                                    large projects or update design in your existing project using Velzon it
                                                    is very quick and easy as it is beautiful, adroit, and delivers the
                                                    ultimate user experience.</p>
                                                <div class="border border-dashed mb-1 mt-3"></div>
                                                <ul
                                                    class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-thumb-up-line align-middle me-1"></i>58</li>
                                                    <li class="list-inline-item me-3"><i
                                                            class="ri-question-answer-line align-middle me-1"></i>24</li>
                                                    <li class="list-inline-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-user-line"></i>
                                                            </div>
                                                            <div class="flex-grow-1 fs-13 ms-1">
                                                                <span class="fw-medium">Themesbrand</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end list-element-->

                                </div>
                                <!--end col-->
                                <div class="text-center">
                                    <button id="loadmore" class="btn btn-link text-success mt-2"><i
                                            class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i> Load more
                                    </button>
                                </div>
                            </div>
                            <!--end row-->
                        </div>
                        <!--end tab-pane-->
                    </div>
                    <!--end tab-content-->

                </div>
                <!--end card-body-->
            </div>
            <!--end card -->
        </div>
        <!--end card -->
    </div>
    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>

    <!-- swiper js -->
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- search-result init js -->
    <script src="{{ URL::asset('build/js/pages/search-result.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
$(document).ready(function() {
    $('#imageForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

         // Show the magic ball
         showMagicBall('Image');

                    $('#result-li').addClass('active');
                    $('#image-li').removeClass('active');

                    // Show the images tab content and hide the result content
                    $('#all').addClass('active').removeClass('fade');
                    $('#images').removeClass('active').addClass('fade');

        $.ajax({
            url: $(this).attr('action'), // Use the form's action URL
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            success: function(response) {
                
                hideMagicBall();
                
                var promptValue = $('#prompt').val();
                $('#promptDisplay').text(promptValue); 
            
                // Display the image based on image_url or image_base64
                if (response.image_url) {
                    $('#imageContainer').html(`
                        <img src="${response.image_url}" alt="Generated Image" style="max-width:100%;">
                        <p>${response.prompt}</p>
                    `);
                        

                } else if (response.image_base64) {
                    $('#imageContainer').html('<img src="data:image/jpeg;base64,' + response.image_base64 + '" alt="Generated Image" style="max-width:100%;">');

                    $('#result-li').addClass('active');
                    $('#image-li').removeClass('active');

                    // Show the images tab content and hide the result content
                    $('#all').addClass('active').removeClass('fade');
                    $('#images').removeClass('active').addClass('fade');
                                }
            },

            error: function(xhr) {
                // Handle errors
                $('#responseMessage').html('<p>Error generating image. Please try again.</p>');
            }
        });
    });
});
</script>

<script>
    $(document).ready(function() {
        // Function to resize textarea
        function resizeTextarea() {
            $(this).css('height', 'auto').css('height', this.scrollHeight + 'px');
        }

        // Call the resizeTextarea function on textarea input
        $('textarea').each(resizeTextarea).on('input', resizeTextarea);
    });
</script>

<script>
    function selectStyle(styleName, element) {
    // Set the selected style value in the hidden input field
    document.getElementById('hiddenStyle').value = styleName;
    console.log('Selected Style: ' + styleName); // You can log it to see the selected style
    
    // Remove 'selected-border' class from all image boxes
    const imageBoxes = document.querySelectorAll('.image-box');
    imageBoxes.forEach(box => box.classList.remove('selected-background'));
    
    // Add 'selected-border' class to the clicked element only
    element.classList.add('selected-background');
    }

    function syncImageFormat() {
    // Get the selected image format from the dropdown
    const imageFormat = document.getElementById('imageFormat').value;

    // Set this value in the hidden input field 'hiddenType'
    document.getElementById('hiddenImageFormat').value = imageFormat;

    console.log('Selected Image Format: ' + imageFormat);  // For debugging
}

function syncModelVersion() {
    // Get the selected image format from the dropdown
    const modelVersion = document.getElementById('modelVersion').value;

    // Set this value in the hidden input field 'hiddenType'
    document.getElementById('hiddenModelVersion').value = modelVersion;

    console.log('Selected Model Version: ' + modelVersion);  // For debugging
}

// function toggleOptimize() {
//         const optimizeInput = document.getElementById("hiddenPromptOptimize");
//         const optimizeIcon = document.getElementById("optimizeIcon").firstElementChild;

//         // Toggle between optimized and non-optimized
//         if (optimizeInput.value === "1") {
//             optimizeInput.value = "0";
//             optimizeIcon.classList.replace("ri-hammer-fill", "ri-hammer-line"); // Reset to default icon
//         } else {
//             optimizeInput.value = "1";
//             optimizeIcon.classList.replace("ri-hammer-line", "ri-hammer-fill"); // Change to optimized icon
//         }
//     }

    function syncOffcanvasInput() {
        // Get the value from the offcanvas input
        const selectedStyle = document.getElementById('hiddenStyle').value;
        const selectedImageFormat = document.getElementById('hiddenImageFormat').value;
        const selectedModelVersion = document.getElementById('hiddenModelVersion').value;
        
        // Set this value in the hidden input field in the form
        document.getElementById('hiddenStyle').value = selectedStyle;
        document.getElementById('hiddenImageFormat').value = selectedImageFormat;
        document.getElementById('hiddenModelVersion').value = selectedModelVersion;
    }
</script>

<script>
    $(document).on('click', '.like-button', function() {
        let button = $(this);
        let imageId = button.data('image-id');

        $.ajax({
            url: '/stable-diffusion-like-image',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                image_id: imageId
            },
            success: function(response) {
                if (response.status === 'liked') {
                    button.find('.like-count').text(response.likes_count);
                    button.addClass('liked');
                    button.find('i').removeClass('ri-thumb-up-fill text-muted').addClass('ri-thumb-up-fill text-primary'); 
                } else if (response.status === 'unliked') {
                    button.find('.like-count').text(response.likes_count);
                    button.removeClass('liked');
                    button.find('i').removeClass('ri-thumb-up-fill text-primary').addClass('ri-thumb-up-fill text-muted');
                }
            }
        });
    });
</script>

<script>
    function incrementDownloadCount(imageId) {
        fetch(`/increment-stable-download/${imageId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).catch(error => console.error('Download count increment failed:', error));
    }
    </script>
    


@endsection
