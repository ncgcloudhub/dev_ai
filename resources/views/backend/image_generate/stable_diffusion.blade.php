@extends('admin.layouts.master')
@section('title')
    @lang('translation.search-results')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">

@endsection
@section('content')
    @component('admin.components.breadcrumb')
        @slot('li_1')
            Pages
        @endslot
        @slot('title')
            Search Results
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row justify-content-center mb-4">
                        
                        <div class="col-lg-6">
                            <form id="imageForm" action="{{ route('stable.image') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="hiddenStyle" id="hiddenStyle">
                                <input type="hidden" name="hiddenImageFormat" id="hiddenImageFormat">
                                <input type="hidden" name="hiddenModelVersion" id="hiddenModelVersion">
                            <div class="row g-2">
                                <div class="col">
                                    <div class="position-relative mb-3">
                                        <input type="text" class="form-control form-control-lg bg-light border-light"
                                            placeholder="Enter your Prompt" name="prompt" id="prompt">
                                       
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button onclick="syncOffcanvasInput()" type="submit" class="btn btn-primary btn-lg waves-effect waves-light"><i
                                        class="mdi mdi-magnify me-1"></i> Generate</button>
                                </div>
                            </div>
                        </form>
                        </div>
                        <!--end col-->
                        <div class="col-lg-12" id="promptContainer" style="">
                            <h5 class="fs-16 fw-semibold text-center mb-0">
                                Showing results for "<span id="promptDisplay" class="text-primary fw-medium fst-italic">Prompt</span>"
                            </h5>
                            <div id="responseMessage"></div> <!-- For displaying success/error messages -->
                        </div>

                     
                            <div class="col-xl-3 col-lg-4 col-sm-6">
                                <label for="imageFormat" class="form-label">Select Image Format</label>
                                <select name="imageFormat" id="imageFormat" class="form-select" onchange="syncImageFormat()">
                                    <option value="" disabled selected>Select format</option>
                                    <option value="jpeg">JPEG</option>
                                    <option value="png">PNG</option>
                                    <option value="webp">WEBP</option>
                                </select>
                                <label for="modelVersion" class="form-label">Select Model Version</label>
                                <select name="modelVersion" id="modelVersion" class="form-select" onchange="syncModelVersion()">
                                    <option value="" disabled selected>Select Model</option>
                                    <option value="sd3-medium">sd3-medium</option>
                                    <option value="sd3-large-turbo">sd3-large-turbo</option>
                                    <option value="sd3-large">sd3-large</option>
                                    <option value="sd3.5-medium">sd3.5-medium</option>
                                    <option value="sd3.5-large-turbo">sd3.5-large-turbo</option>
                                    <option value="sd3.5-large">sd3.5-large</option>
                                </select>
                            </div>
                            <!--end col-->
                            <div class="col-xl-6 col-lg-4 col-sm-6">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <!-- Image Box 1 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Animation')" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/animation.jpg') }}" alt="Animation" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0">Animation</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 2 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Cinematic')" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/cinematic.jpg') }}" alt="Cinematic" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0">Cinematic</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 3 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Comic')" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/comic.jpg') }}" alt="Comic" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0">Comic</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 4 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Cyberpunk')" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/cyberpunk.jpg') }}" alt="Cyberpunk" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0">Cyberpunk</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 5 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Futurism')" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/futurism.jpeg') }}" alt="Futurism" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0">Futurism</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 6 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Doodle Art')" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/doodle_art.jpg') }}" alt="Doodle Art" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0">Doodle Art</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 7 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Graffiti')" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/graffiti.jpg') }}" alt="Graffiti" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0">Graffiti</p>
                                        </div>
                                    </div>
                            
                                    <!-- Image Box 8 -->
                                    <div class="col-3 mb-3">
                                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Sketch')" style="height: 150px;">
                                            <img src="{{ asset('build/images/stable/sketch.jpg') }}" alt="Sketch" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                            <p class="mb-0">Sketch</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!--end col-->
                           
                       
                    
                    </div>
                    <!--end row-->

                  

 <!-- top offcanvas -->
 <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel" style="min-height:46vh;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">Advance Setting</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row gallery-light">
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <label for="imageFormat" class="form-label">Select Image Format</label>
                <select name="imageFormat" id="imageFormat" class="form-select" onchange="syncImageFormat()">
                    <option value="jpeg">JPEG</option>
                    <option value="png">PNG</option>
                    <option value="webp">WEBP</option>
                </select>
            </div>
            <!--end col-->
            <div class="col-xl-6 col-lg-4 col-sm-6">
                <div class="d-flex flex-wrap justify-content-between">
                    <!-- Image Box 1 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center" onclick="selectStyle('Watercolor')">
                            <img src="https://via.placeholder.com/100" alt="style1" class="img-fluid mb-2">
                            <p>Style 1</p>
                        </div>
                    </div>
            
                    <!-- Image Box 2 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center" onclick="selectStyle('sketch')">
                            <img src="https://via.placeholder.com/100" alt="style2" class="img-fluid mb-2">
                            <p>Style 2</p>
                        </div>
                    </div>
            
                    <!-- Image Box 3 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center" onclick="selectStyle('Style 3')">
                            <img src="https://via.placeholder.com/100" alt="style3" class="img-fluid mb-2">
                            <p>Style 3</p>
                        </div>
                    </div>
            
                    <!-- Image Box 4 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center" onclick="selectStyle('Style 4')">
                            <img src="https://via.placeholder.com/100" alt="style4" class="img-fluid mb-2">
                            <p>Style 4</p>
                        </div>
                    </div>
            
                    <!-- Image Box 5 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center" onclick="selectStyle('Style 5')">
                            <img src="https://via.placeholder.com/100" alt="style5" class="img-fluid mb-2">
                            <p>Style 5</p>
                        </div>
                    </div>
            
                    <!-- Image Box 6 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center" onclick="selectStyle('Style 6')">
                            <img src="https://via.placeholder.com/100" alt="style6" class="img-fluid mb-2">
                            <p>Style 6</p>
                        </div>
                    </div>
            
                    <!-- Image Box 7 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center" onclick="selectStyle('Style 7')">
                            <img src="https://via.placeholder.com/100" alt="style7" class="img-fluid mb-2">
                            <p>Style 7</p>
                        </div>
                    </div>
            
                    <!-- Image Box 8 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center" onclick="selectStyle('Style 8')">
                            <img src="https://via.placeholder.com/100" alt="style8" class="img-fluid mb-2">
                            <p>Style 8</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <label for="modelVersion" class="form-label">Select Model Version</label>
                <select name="modelVersion" id="modelVersion" class="form-select" onchange="syncModelVersion()">
                    <option value="sd3-medium">sd3-medium</option>
                    <option value="sd3-large-turbo">sd3-large-turbo</option>
                    <option value="sd3-large">sd3-large</option>
                    <option value="sd3.5-medium">sd3.5-medium</option>
                    <option value="sd3.5-large-turbo">sd3.5-large-turbo</option>
                    <option value="sd3.5-large">sd3.5-large</option>
                </select>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
</div>



                </div>
                <div>
                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#all" role="tab" aria-selected="false">
                                <i class="ri-search-2-line text-muted align-bottom me-1"></i>Result
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" id="images-tab" href="#images" role="tab"
                                aria-selected="true">
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
                        <li class="nav-item ms-auto">
                            <div class="dropdown">
                                <a class="nav-link fw-medium text-reset mb-n1" href="#" role="button" id="dropdownMenuLink1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-settings-4-line align-middle me-1"></i> Settings
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">Advanced Settings</a></li>

                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="all" role="tabpanel">
                            <div id="imageContainer" class="pb-3">
                                
                            </div>

                        </div>

                        <div class="tab-pane" id="images" role="tabpanel">
                           
                            <div class="gallery-light">
                                <div class="row">
                                    {{-- @foreach($images as $item)
                                        <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="gallery-box card">
                                            <div class="gallery-container">
                                                <a class="image-popup" href="{{ $item->image_url }}" title="">
                                                    <img class="gallery-img img-fluid mx-auto"
                                                        src="{{ $item->image_url }}" alt="" />
                                                    <div class="gallery-overlay">
                                                        <h5 class="overlay-caption">Glasses and laptop from above</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="box-content">
                                                <div class="d-flex align-items-center mt-2">
                                                    <div class="flex-grow-1 text-muted">by <a href=""
                                                            class="text-body text-truncate">Ron Mackie</a></div>
                                                    <div class="flex-shrink-0">
                                                        <div class="d-flex gap-3">
                                                            <button type="button"
                                                                class="btn btn-sm fs-12 btn-link text-body text-decoration-none px-0">
                                                                <i
                                                                    class="ri-thumb-up-fill text-muted align-bottom me-1"></i>
                                                                2.2K
                                                            </button>
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
                                    @endforeach --}}
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
        
        $.ajax({
            url: $(this).attr('action'), // Use the form's action URL
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            success: function(response) {
               
                var promptValue = $('#prompt').val();
                $('#promptDisplay').text(promptValue); 

                // Show the prompt container
                $('#promptContainer').show();

                // Show success message
                $('#responseMessage').html('<p class="text-success">Image generated successfully!</p>');


                // Display the image based on image_url or image_base64
                if (response.image_url) {
                    $('#imageContainer').html('<img src="' + response.image_url + '" alt="Generated Image" style="max-width:100%;">');
                } else if (response.image_base64) {
                    $('#imageContainer').html('<img src="data:image/jpeg;base64,' + response.image_base64 + '" alt="Generated Image" style="max-width:100%;">');
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
    function selectStyle(styleName) {
    // Set the selected style value in the hidden input field
    document.getElementById('hiddenStyle').value = styleName;
    console.log('Selected Style: ' + styleName); // You can log it to see the selected style
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


@endsection
