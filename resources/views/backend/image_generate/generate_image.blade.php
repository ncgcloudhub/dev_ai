@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
@slot('title') Generate Image  @endslot
@endcomponent

<style>
    textarea {
    resize: none; /* Disable default resizing */
    overflow: hidden; /* Hide any overflow content */
}
</style>

<style>
    .position-relative {
        position: relative;
    }
    .position-absolute {
        position: absolute;
    }
    .btn-outline-secondary {
        background: rgba(255, 255, 255, 0.5); /* Optional: To make buttons more visible on the image */
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Generate Image</h4>

        <div class="d-flex flex-column flex-sm-row">
            <button id="imageGenerateTourButton" class="btn gradient-btn-8 text-white">Image Tour</button>
            <a href="{{ route('aicontentcreator.view', ['slug' => 'image-prompt-idea']) }}" class="btn gradient-btn-6 btn-load mb-2 mb-sm-0 me-sm-2">
                <span class="d-flex align-items-center">
                    <span class="spinner-grow" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </span>
                    <span class="ms-2">
                        Get Image Prompt Ideas
                    </span>
                </span>
            </a>
            <button type="button" class="btn gradient-btn-5" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                Prompt Library
            </button>
        </div>
    </div><!-- end card header -->
    
    <div class="card-body">
        @if($get_user->credits_left <= 0) 
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong> No Images Left! </strong> You don't have any <b>Images </b> left to generate!
        </div>
        @else 
       
        @endif
        
        <div class="live-preview">
                <div class="col-xxl-12 justify-content-center">
                   
                    <div class="card">
                        <button disabled type="button" class="btn gradient-btn-5 waves-effect waves-light col-md-2 m-2 @if($get_user->credits_left <= 0) btn-danger @else btn-primary @endif">
                            Credits Left <span class="credit-left badge ms-1 @if($get_user->credits_left <= 0) bg-dark @else bg-danger @endif">{{ $get_user->credits_left }}</span>
                        </button>
                        <div class="card-body">
                           
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills nav-justified col-md-2 mb-3 m-auto" role="tablist">
                                <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-home-1" role="tab">
                                            Dall-E 2
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light" id="model-select-tour">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#pill-justified-profile-1" role="tab">
                                        Dall-E 3
                                    </a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content text-muted">
                                {{-- Dalle 2 --}}
                                <div class="tab-pane" id="pill-justified-home-1" role="tabpanel">


                                    <!-- Base Example -->
                                    <form action="{{route('generate.image')}}" method="post" class="row g-3">
                                        @csrf
                                        {{-- For Value of Hammer(Optimize) --}}
                                        <input type="hidden" name="hiddenPromptOptimize" id="hiddenPromptOptimize_dalle2">
                                        <input type="hidden" name="dall_e_2"  value="dall_e_2">

                                        <div class="accordion accordion-flush col-xxl-6 m-auto mt-2" id="accordionFlushExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingOne">
                                                    <button class="accordion-button collapsed bg-secondary-subtle" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                                        Advance Settings
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                                                    data-bs-parent="#accordionFlushExample">
                                                    <div class="accordion-body">           
                                                    
                                                        <div class="row">
                                                            
                                                            <div class="col-md-3 mb-3">
                                                                <label for="input1">Image Style</label>
                                                            
                                                                <select name="style[]" class="form-control" data-choices data-choices-removeItem multiple id="style">
                                                                    <option value="natural">Natural</option>
                                                                    <option value="vivid">Vivid</option>
                                                                    <option value="none">NONE</option>
                                                                    <option value="cinematic">CINEMATIC</option>
                                                                    <option value="analog-film">ANALOG FILM</option>
                                                                    <option value="animation">ANIMATION</option>
                                                                    <option value="comic">COMIC</option>
                                                                    <option value="craft-clay">CRAFT CLAY</option>
                                                                    <option value="fantasy">FANTASY</option>
                                                                    <option value="line-art">LINE ART</option>
                                                                    <option value="cyberpunk">CYBERPUNK</option>
                                                                    <option value="pixel-art">PIXEL ART</option>
                                                                    <option value="photograph">PHOTOGRAPH</option>
                                                                    <option value="graffiti">GRAFFITI</option>
                                                                    <option value="game-gta">GAME GTA</option>
                                                                    <option value="3d-character">3D CHARACTER</option>
                                                                    <option value="baroque">BAROQUE</option>
                                                                    <option value="caricature">CARICATURE</option>
                                                                    <option value="colored-pencil">COLORED PENCIL</option>
                                                                    <option value="doddle-art">DODDLE ART</option>
                                                                    <option value="futurism">FUTURISM</option>
                                                                    <option value="sketch">SKETCH</option>
                                                                    <option value="surrealism">SURREALISM</option>
                                                                    <option value="sticker-designs">STICKER DESIGNS</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-md-3 mb-3">
                                                                <label for="input2">Image Quality</label>
                                                                <select name="quality" class="form-control" id="quality">
                                                                    <option disabled selected="">Enter Image Quality</option>
                                                                    <option value="standard">Standard</option>
                                                                    <option value="hd">HD</option>
                                                                </select>
                                                            </div>                                                        
                                                                                            
                                                            <div class="col-md-3 mb-3">
                                                                <label for="input3">Image Resolution</label>
                                                                <select name="image_res" class="form-control" id="image_res">
                                                                    <option disabled selected="">Enter Image Resolution</option>
                                                                    <option value="256x256">256x256</option>
                                                                    <option value="512x512">512x512</option>
                                                                    <option value="1024x1024">1024x1024</option>
                                                                </select>
                                                            </div>
                                                                                            
                                                            <div class="col-md-3 mb-3">
                                                                <label for="input4">No. of Result</label>
                                                                
                                                                <select name="no_of_result" class="form-control" id="no_of_result">
                                                                    <option disabled selected="">Enter no. of Images</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5">5</option>
                                                                    <option value="6">6</option>
                                                                    <option value="7">7</option>
                                                                    <option value="8">8</option>
                                                                    <option value="9">9</option>
                                                                    <option value="10">10</option>
                                                                </select>
                                                            </div>                                                                                           

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="row g-3 justify-content-center">
                                            <div class="col-xxl-5 col-sm-6">
                                                <div class="search-box">
                                                    <a title="Optimize Prompt" class="btn btn-link link-success btn-lg position-absolute top-50 translate-middle-y"
                                                    onclick="toggleOptimize('dalle2')" id="optimizeIcon_dalle2">
                                                     <i class="ri-hammer-line"></i>
                                                    </a>
                                                    <textarea class="form-control search" name="prompt" rows="1" id="prompt" placeholder="Write prompt to generate Image">{{ old('prompt', $content) }}</textarea>
                                                </div>
                                            </div>
                                            
                                                <!--end col-->
                                                
                                            <div class="col-xxl-1 col-sm-4">
                                                    <div>
                                                        <button class="btn btn-rounded gradient-btn-5 mb-2">Generate</button>
                                                    </div>
                                            </div>
                                                <!--end col-->
                                        </div>
                                            <!--end row-->
                                    </form>
                                   
                                </div>
                                {{-- Dalle 2 END --}}
                         
                                {{-- Dalle 3 Start --}}
                                <div class="tab-pane active" id="pill-justified-profile-1" role="tabpanel">
                                    <form id="form_dalle3" action="{{route('generate.image')}}" method="post" class="row g-3" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="hiddenPromptOptimize" id="hiddenPromptOptimize_dalle3">
                                        <input type="hidden" name="dall_e_3" value="dall_e_3">
                                    
                                        <div class="accordion accordion-flush col-xxl-6 m-auto mt-2" id="accordionFlushExample">
                                            <div class="accordion-item" id="advance-setting-tour">
                                                <h2 class="accordion-header" id="flush-headingOne">
                                                    <button class="accordion-button collapsed bg-secondary-subtle" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                                        Advance Settings
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                                                    data-bs-parent="#accordionFlushExample">
                                                    <div class="accordion-body">


                                                        <div class="row">
                                                            
                                                            <div class="col-md-3 mb-3">
                                                                <label for="input1">Image Style</label>
                                                                <select name="style[]" class="form-control" data-choices data-choices-removeItem multiple id="style">
                                                                
                                                                    <option value="natural">Natural</option>
                                                                    <option value="vivid">Vivid</option>
                                                                    <option value="none">NONE</option>
                                                                    <option value="cinematic">CINEMATIC</option>
                                                                    <option value="analog-film">ANALOG FILM</option>
                                                                    <option value="animation">ANIMATION</option>
                                                                    <option value="comic">COMIC</option>
                                                                    <option value="craft-clay">CRAFT CLAY</option>
                                                                    <option value="fantasy">FANTASY</option>
                                                                    <option value="line-art">LINE ART</option>
                                                                    <option value="cyberpunk">CYBERPUNK</option>
                                                                    <option value="pixel-art">PIXEL ART</option>
                                                                    <option value="photograph">PHOTOGRAPH</option>
                                                                    <option value="graffiti">GRAFFITI</option>
                                                                    <option value="game-gta">GAME GTA</option>
                                                                    <option value="3d-character">3D CHARACTER</option>
                                                                    <option value="baroque">BAROQUE</option>
                                                                    <option value="caricature">CARICATURE</option>
                                                                    <option value="colored-pencil">COLORED PENCIL</option>
                                                                    <option value="doddle-art">DODDLE ART</option>
                                                                    <option value="futurism">FUTURISM</option>
                                                                    <option value="sketch">SKETCH</option>
                                                                    <option value="surrealism">SURREALISM</option>
                                                                    <option value="sticker-designs">STICKER DESIGNS</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-md-3 mb-3">
                                                                <label for="input2">Image Quality</label>
                                                                <select name="quality" class="form-control" id="quality">
                                                                    <option disabled selected="">Enter Image Quality</option>
                                                                    <option value="standard">Standard</option>
                                                                    <option value="hd">HD</option>
                                                                </select>
                                                            </div>                                                        

                                                            
                                                            <div class="col-md-3 mb-3">
                                                                <label for="input3">Image Resolution</label>
                                                                <select name="image_res" class="form-control upgrade_option" id="image_res">
                                                                    <option disabled selected="">Enter Image Resolution</option>
                                                                    <option value="1024x1024">1024x1024</option>
                                                                    @if ($lastPackageId)
                                                                        <option value="1792x1024">1792x1024</option>
                                                                        <option value="1024x1792">1024x1792</option>
                                                                    @else
                                                                        <option value="upgrade">Upgrade to access more options</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-md-3 mb-3">
                                                                <label for="input4">No. of Result</label>
                                                                <select name="no_of_result" class="form-control" id="no_of_result">
                                                                    <option disabled selected="">Enter No. of Result</option>
                                                                    <option value="1">1</option>
                                                                    
                                                                </select>
                                                            </div>
                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Image Upload and Checkbox -->
                                        <div class="row g-3 justify-content-center">
                                            <div class="col-xxl-5 col-sm-6">
                                                <div class="form-check form-switch form-switch-md d-flex align-items-center mb-3" id="image-to-image-tour">
                                                    <input class="form-check-input me-2" type="checkbox" id="image_to_image" name="image_to_image">
                                                    <label class="form-check-label fw-bold" for="image_to_image">Generate from Image</label>
                                                </div>

                                                <div style="display: none;" class="form-group mt-2" id="image_upload">
                                                    <input type="file" class="form-control" name="custom_image" id="custom_image" aria-label="Upload image" onchange="previewImage(event)">
                                                </div>

                                                <!-- Image Preview Section -->
                                                <div id="image_preview" style="display: none; margin-top: 10px;">
                                                    <img id="preview_img" src="" alt="Image Preview" class="img-fluid rounded shadow-sm" style="max-width: 20%; height: auto;">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row g-3 justify-content-center">
                                            <div class="col-xxl-5 col-sm-6">
                                                <div class="search-box">
                                                    <a title="Optimize Prompt" class="btn btn-link link-success btn-lg position-absolute top-50 translate-middle-y"
                                                    onclick="toggleOptimize('dalle3')" id="optimizeIcon_dalle3">
                                                     <i class="ri-hammer-line"></i>
                                                    </a>
                                                    <textarea class="form-control search" name="prompt" rows="1" id="prompt" placeholder="Write prompt to generate Image">{{ old('prompt', $content) }}</textarea>
                                                </div>
                                            </div>
                                            
                                            <!--end col-->
                                            
                                            <div class="col-xxl-1 col-sm-4 d-flex align-items-center">
                                                <div>
                                                    <!-- Disable the button initially and include the spinner inside -->
                                                    <button id="generate-button-tour" class="btn btn-rounded gradient-btn-5 mb-2" disabled>
                                                        <span id="generate-button-text">Generate</span>
                                                        {{-- <div id="loading-spinner" class="spinner-border spinner-border-sm text-light ms-2" role="status" style="display: none;">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div> --}}
                                                    </button>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                    </form>
                                </div>
                                {{-- Dalle 3 END --}}

                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!--end col-->
               
                
                <div class="hstack flex-wrap gap-2 mb-3 mb-lg-0 d-none"  id="loader">
                    <button class="btn btn-outline-primary btn-load">
                        <span class="d-flex align-items-center">
                            <span class="spinner-border flex-shrink-0" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                            <span class="flex-grow-1 ms-2">
                                Loading...
                            </span>
                        </span>
                    </button>
                     <!-- Container for the Lottie animation -->
                    <div id="lottie-animation" style="width: 100px; height: 100px;"></div>
                </div>

                <x-jokes_common />
        
                <div id="image-container" class="d-flex justify-content-center">      
                
                </div>

        </div>
    </div>

    <div class="row gallery-wrapper">
        @foreach ($images as $item)
        <div class="element-item col-xxl-3 col-xl-4 col-sm-6 project designing development" data-category="designing development">
            <div class="gallery-box card">
                <div class="gallery-container">
                    <a class="gallery-link" href="{{ $item->image_url }}" title="{{ $item->prompt }}" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-url="{{ $item->image_url }}" data-image-prompt="{{ $item->prompt }}" data-image-resolution="{{ $item->resolution }}">
                        <img class="gallery-img img-fluid mx-auto" src="{{ $item->image_url }}" alt="" />
                        <div class="gallery-overlay">
                            <h5 class="overlay-caption">{{ $item->prompt }}</h5>
                        </div>
                    </a>
                </div>
                <div class="box-content text-center mt-2">
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center gap-3">
                        <!-- Download Button -->
                        <a href="{{ $item->image_url }}" download="{{ basename($item->image) }}" class="btn btn-outline-primary btn-icon waves-effect waves-light"> 
                            <i data-feather="download"></i>
                        </a>
    
                        <!-- Like Button -->
                        <button type="button" class="btn btn-sm btn-outline-primary position-relative like-button {{ $item->liked_by_user ? 'ri-thumb-up-fill' : 'ri-thumb-up-line' }}" data-image-id="{{ $item->id }}">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">{{ $item->likes_count }}</span>
                        </button>
    
                        <!-- Favorite Button -->
                        <button type="button" class="btn btn-sm btn-outline-primary position-relative favorite-button {{ $item->favorited_by_user ? 'ri-heart-2-fill' : 'ri-heart-2-line' }}" data-image-id="{{ $item->id }}">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $item->favorites_count }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    {{-- Image Description --}}
    <div id="imageModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 overflow-hidden">
                <div class="row g-0">
                
                    <div class="col-lg-5">
                        <div class="subscribe-modals-cover h-100 d-flex align-items-center justify-content-center">
                            <button type="button" class="btn btn-outline-secondary position-absolute start-0" id="prevButton">
                                <i class="ri-arrow-left-s-line"></i>
                            </button>
                            <img id="modalImage" src="" class="img-fluid w-100" alt="Image">
                            <button type="button" class="btn btn-outline-secondary position-absolute end-0" id="nextButton">
                                <i class="ri-arrow-right-s-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-7 d-flex align-items-center">
                        <div class="modal-body p-5">
                            <p class="lh-base modal-title mb-2" id="imageModalLabel"></p>
                            <span class="text-muted mb-4" id="resolution"></span>
                        </div>
                        
                    </div>
                    <!-- Left button -->
                
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const generateButton = document.getElementById('generate-button-tour');
        const spinner = document.getElementById('loading-spinner');
        
        generateButton.disabled = false; // Disable button initially
        spinner.style.display = 'inline-block'; // Show spinner initially
    });

    window.addEventListener("load", function() {
        const generateButton = document.getElementById('generate-button-tour');
        const spinner = document.getElementById('loading-spinner');
        
        generateButton.disabled = false; // Enable button when page is fully loaded
        spinner.style.display = 'none'; // Hide spinner when page is loaded
    });
</script>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview_img');
        const previewContainer = document.getElementById('image_preview');
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block'; // Show the preview container
            };
            
            reader.readAsDataURL(file); // Convert image to base64 URL
        } else {
            previewContainer.style.display = 'none'; // Hide the preview container if no image is selected
        }
    }
</script>

<script>
    document.getElementById('image_to_image').addEventListener('change', function() {
        const imageUpload = document.getElementById('image_upload');
        if (this.checked) {
            imageUpload.style.display = 'block';
        } else {
            imageUpload.style.display = 'none';
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selectElement = document.querySelector('.upgrade_option');
        selectElement.addEventListener('change', function() {
            var selectedValue = this.value;
            if (selectedValue === 'upgrade') {
                window.location.href = "{{ route('all.package') }}";
            }
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var imageModal = document.getElementById('imageModal');
        var prevButton = document.getElementById('prevButton');
        var nextButton = document.getElementById('nextButton');
        var images = {!! json_encode($images) !!}; // Assuming $images is an array of image objects
        var currentIndex = 0;

        // Function to update modal content based on current index
        function updateModalContent(index) {
            var imageUrl = images[index].image_url;
            var imagePrompt = images[index].prompt;
            var imageResolution = images[index].resolution;

            var modalImage = imageModal.querySelector('#modalImage');
            var modalTitle = imageModal.querySelector('.modal-title');
            var modalDescription = imageModal.querySelector('#resolution');

            modalImage.src = imageUrl;
            modalTitle.textContent = imagePrompt;
            modalDescription.textContent = imageResolution;
        }

        // Update modal content when modal is shown
        imageModal.addEventListener('show.bs.modal', function (event) {
            var triggerElement = event.relatedTarget; // Element that triggered the modal
            var imageUrl = triggerElement.getAttribute('data-image-url');

            // Find the index of the clicked image
            currentIndex = images.findIndex(image => image.image_url === imageUrl);

            // Update the modal content based on the current index
            updateModalContent(currentIndex);
        });

        // Previous button click event
        prevButton.addEventListener('click', function () {
            if (currentIndex > 0) {
                currentIndex--;
                updateModalContent(currentIndex);
            }
        });

        // Next button click event
        nextButton.addEventListener('click', function () {
            if (currentIndex < images.length - 1) {
                currentIndex++;
                updateModalContent(currentIndex);
            }
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
$(document).ready(function () {
    const form = document.getElementById('form_dalle3');
    const textarea = form?.querySelector('textarea[name="prompt"]');

    // Ensure form and textarea are present
    if (textarea && form) {
        const content = textarea.value.trim();
        if (content) {
            console.log("Textarea has content. Triggering the form submission...");

            // Trigger the submit handler
            $('form').trigger('submit');
        } else {
            console.log("Textarea is empty. Form will not be submitted.");
        }
    } else {
        console.error("Form or textarea not found.");
    }

    // Attach submit handler for form
    $('form').submit(function (event) {
        event.preventDefault(); // Prevent default form submission
        
        // Show the magic ball
        showMagicBall('image');

        // Create a FormData object
        var formData = new FormData(this);

        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: '/generate/image',
            data: formData,
            processData: false, // Prevent jQuery from automatically processing the data
            contentType: false,
            success: function (response) {
                // Hide the magic ball after content loads
                hideMagicBall();

                console.log(response);

                $('#image-container').empty(); // Clear previous images if any
                response.data.forEach(function (imageData) {
                    // Create an image element
                    var temp = `<a class="image-popup" href="${imageData.url}" title="">
                                    <img class="gallery-img img-fluid mx-auto" style="height: 512px; width:512px" src="${imageData.url}" alt="" />
                                </a>`;

                    // Append the image to the container
                    $('#image-container').append(temp);
                });

                // Initialize Glightbox
                const lightbox = GLightbox({
                    selector: '.image-popup',
                    touchNavigation: true,
                    loop: true
                });

                var credits_left = response.credit_left;
                $('.credit-left').text(credits_left);

                // Hide loader
                $('#loader').addClass('d-none');
            },
            error: function (xhr, status, error) {
                // Handle error response
                hideMagicBall();
                console.error(xhr.responseText);
                $('#loader').addClass('d-none');
            }
        });
    });
});

</script>


{{-- LIKE & FAVORITE FUNCTIONALITY --}}
<script>
    $(document).ready(function() {
        // Like button functionality
        $(document).off('click', '.like-button').on('click', '.like-button', function() {
            var imageId = $(this).data('image-id');
            var likeButton = $(this);
            var likeCountBadge = likeButton.find('.badge');
            $.ajax({
                url: '/like',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { image_id: imageId },
                success: function(response) {
                    // Update UI to reflect the new like status
                    if (response.liked) {
                        likeButton.toggleClass('ri-thumb-up-line ri-thumb-up-fill');
                        likeCountBadge.text(parseInt(likeCountBadge.text()) + 1);
                    } else {
                        likeButton.removeClass('ri-thumb-up-fill').addClass('ri-thumb-up-line');
                        likeCountBadge.text(parseInt(likeCountBadge.text()) - 1);
                    }
                },
                error: function(xhr) {
                    // Handle errors
                }
            });
        });

        // Favorite button functionality
        $(document).off('click', '.favorite-button').on('click', '.favorite-button', function() {
            var imageId = $(this).data('image-id');
            var favoriteButton = $(this);
            var favoriteCountBadge = favoriteButton.find('.badge');
            $.ajax({
                url: '/favorite',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { image_id: imageId },
                success: function(response) {
                    // Update UI to reflect the new favorite status
                    if (response.favorited) {
                        favoriteButton.removeClass('ri-heart-2-line').addClass('ri-heart-2-fill');
                        favoriteCountBadge.text(parseInt(favoriteCountBadge.text()) + 1);
                    } else {
                        favoriteButton.removeClass('ri-heart-2-fill').addClass('ri-heart-2-line');
                        favoriteCountBadge.text(parseInt(favoriteCountBadge.text()) - 1);
                    }
                },
                error: function(xhr) {
                    // Handle errors
                }
            });
        });
    });
</script>

@endsection