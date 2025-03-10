@extends('admin.layouts.master')
@section('title') Image to Video @endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="#">Image to Video Generation</a> @endslot
@slot('title') Generate Animated Video @endslot
@endcomponent

<style>
    body {
        background: linear-gradient(to right, #1a0a24, #3a0750);
        color: white;
        font-family: Arial, sans-serif;
    }

    .image-box.selected {
    border: 2px solid #96004b; /* Blue border */
    background-color: rgba(0, 123, 255, 0.1);
    }


</style>

<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 id="headingText" class="text-white d-flex align-items-center gap-4">
                <strong>Text to Images</strong> 
            </h1>            
                <h2 id="subheadingText" class="gradient-text-3">Transform your Text into stunning images</h2>
                <p id="pText">Elevate your creativity with our AI tool that converts text and images into high-quality videos. Effortlessly generate engaging video content for presentations, social media, and more.</p>
                <form action="{{ route('generate.image.sd.dalle')}}" method="POST" enctype="multipart/form-data" id="imageGenerationForm">
                    @csrf
                    <textarea class="form-control search ps-5 mt-1" name="prompt" rows="1" id="prompt" placeholder="Write prompt to generate Video"></textarea>
                
                    <!-- Hidden inputs to store the selected options -->
                    <input type="hidden" name="hiddenModelVersion" id="hiddenModelVersion">
                    <input type="hidden" name="hiddenImageFormat" id="hiddenImageFormat">
                    <input type="hidden" name="hiddenStyle" id="hiddenStyle">
                    <input type="hidden" name="hiddenQuality" id="hiddenQuality">
                    <input type="hidden" name="hiddenResolution" id="hiddenResolution">
                    <input type="hidden" id="hiddenStyleCommon" name="hiddenstyleCommon" value="">
                
                    <br>
                    <button type="submit" class="btn gradient-btn-3">Generate</button>
                </form>
                

        </div>
        <div class="col-md-6">
            <img src="https://img.freepik.com/free-photo/view-chameleon-with-bright-neon-colors_23-2151682699.jpg" alt="Before and After" class="before-after">
        </div>
    </div>
    <div class="text-center mt-5">
        <h3 class="text-white">Use Cases</h3>
        <p>Attain heightened levels of clarity and intricacy in your AI creations, photographs, and illustrations.</p>
        <div class="d-flex justify-content-center flex-wrap">
            <a class="use-case-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                Model
            </a>
            <button class="use-case-btn">Illustration</button>
            <button class="use-case-btn">Landscapes</button>
            <button class="use-case-btn">Design</button>
            <button class="use-case-btn">Food</button>
            <button class="use-case-btn">More</button>
        </div>

        {{-- Offcanvas --}}
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Recent Acitivity</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div data-simplebar style="height: calc(100vh - 112px);">
            <div class="offcanvas-body p-4 overflow-hidden">
                <select name="modelVersion" id="modelVersion" class="form-select" onchange="toggleOptions()" data-choices>
                    <option value="" disabled selected>Select Model</option>
                    <optgroup label="Stable Diffusion">
                        <option value="sd3-medium">sd3-medium</option>
                        <option value="sd3-large-turbo">sd3-large-turbo</option>
                        <option value="sd3-large">sd3-large</option>
                        <option value="sd3.5-medium">sd3.5-medium</option>
                        <option value="sd3.5-large-turbo">sd3.5-large-turbo</option>
                        <option value="sd3.5-large">sd3.5-large</option>
                        <option value="sd-ultra">SD Ultra</option>
                        <option value="sd-core">SD Core</option>
                    </optgroup>
                    <optgroup label="DALL·E">
                        <option value="dalle">DALL·E</option>
                    </optgroup>
                </select>

                <div id="sdOptions">
                    <select name="imageFormat" id="imageFormat" class="form-select" data-choices>
                        <option value="" disabled selected>Select format</option>
                        <option value="jpeg">JPEG</option>
                        <option value="png">PNG</option>
                        <option value="webp">WEBP</option>
                    </select>
                
                    <select name="style" class="form-select" id="style" data-choices>
                        <option disabled selected value="">Select Style</option>
                        <option value="natural">Natural</option>
                        <option value="vivid">Vivid</option>
                        <option value="none">NONE</option>
                        <option value="cinematic">CINEMATIC</option>
                    </select>
                </div>
               
                <div id="dalleOptions">
                    <select name="quality" class="form-select" id="quality" data-choices>
                        <option disabled selected value="">Enter Image Quality</option>
                        <option value="standard">Standard</option>
                        <option value="hd">HD</option>
                    </select>
                
                    <select name="image_res" class="form-select" id="image_res" data-choices>
                        <option disabled selected value="">Enter Image Resolution</option>
                        <option value="256x256">256x256</option>
                        <option value="512x512">512x512</option>
                        <option value="1024x1024">1024x1024</option>
                    </select>
                </div>

                <div class="d-flex flex-wrap justify-content-between">
                    <!-- Image Box 1 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Animation', this)" style="height: 150px;">
                            <img src="{{ asset('build/images/stable/animation.jpg') }}" alt="Animation" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;" loading="lazy">
                            <p class="mb-0 gradient-text-1-bold">Animation</p>
                        </div>
                    </div>
                    <!-- Image Box 1 -->
                  
                    <!-- Image Box 2 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Cinematic', this)" style="height: 150px;">
                            <img src="{{ asset('build/images/stable/cinematic.jpg') }}" alt="Cinematic" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;" loading="lazy">
                            <p class="mb-0 gradient-text-1-bold">Cinematic</p>
                        </div>
                    </div>
            
                    <!-- Image Box 3 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Comic', this)" style="height: 150px;">
                            <img src="{{ asset('build/images/stable/comic.jpg') }}" alt="Comic" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;" loading="lazy">
                            <p class="mb-0 gradient-text-1-bold">Comic</p>
                        </div>
                    </div>
            
                    <!-- Image Box 4 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Cyberpunk', this)" style="height: 150px;">
                            <img src="{{ asset('build/images/stable/cyberpunk.jpg') }}" alt="Cyberpunk" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;" loading="lazy">
                            <p class="mb-0 gradient-text-1-bold">Cyberpunk</p>
                        </div>
                    </div>
            
                    <!-- Image Box 5 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Futurism', this)" style="height: 150px;">
                            <img src="{{ asset('build/images/stable/futurism.jpeg') }}" alt="Futurism" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;" loading="lazy">
                            <p class="mb-0 gradient-text-1-bold">Futurism</p>
                        </div>
                    </div>
            
                    <!-- Image Box 6 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Doodle Art', this)" style="height: 150px;">
                            <img src="{{ asset('build/images/stable/doodle_art.jpg') }}" alt="Doodle Art" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;" loading="lazy">
                            <p class="mb-0 gradient-text-1-bold">Doodle Art</p>
                        </div>
                    </div>
            
                    <!-- Image Box 7 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Graffiti', this)" style="height: 150px;">
                            <img src="{{ asset('build/images/stable/graffiti.jpg') }}" alt="Graffiti" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;" loading="lazy">
                            <p class="mb-0 gradient-text-1-bold">Graffiti</p>
                        </div>
                    </div>
            
                    <!-- Image Box 8 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Sketch', this)" style="height: 150px;">
                            <img src="{{ asset('build/images/stable/sketch.jpg') }}" alt="Sketch" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;" loading="lazy">
                            <p class="mb-0 gradient-text-1-bold">Sketch</p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="offcanvas-foorter border-top p-3 text-center">
                <a href="javascript:void(0);">View All Activity <i class="ri-arrow-right-s-line align-middle ms-1"></i></a>
            </div>
        </div>
        
    </div>
</div>
@endsection

@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    function syncSelection(dropdownId, hiddenInputId) {
        document.getElementById(dropdownId).addEventListener("change", function () {
            document.getElementById(hiddenInputId).value = this.value;
        });
    }

    syncSelection("modelVersion", "hiddenModelVersion");
    syncSelection("imageFormat", "hiddenImageFormat");
    syncSelection("style", "hiddenStyle");
    syncSelection("quality", "hiddenQuality");
    syncSelection("image_res", "hiddenResolution");

});

function selectStyle(style, element) {
    // Update hidden input field with selected style
    document.getElementById("hiddenStyleCommon").value = style;

    // Remove 'selected' class from all image boxes
    document.querySelectorAll(".image-box").forEach(box => {
        box.classList.remove("selected");
    });

    // Add 'selected' class to the clicked image box
    element.classList.add("selected");
    }

</script>

<script>
    function toggleOptions() {
        let model = document.getElementById('modelVersion').value;
        let sdOptions = document.getElementById('sdOptions');
        let dalleOptions = document.getElementById('dalleOptions');

        if (model === 'dalle') {
            dalleOptions.style.display = 'block';
            sdOptions.style.display = 'none';
        } else {
            dalleOptions.style.display = 'none';
            sdOptions.style.display = 'block';
        }
    }

    // Run on page load in case of pre-selection
    toggleOptions();
</script>

@endsection
