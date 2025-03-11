@extends('admin.layouts.master')
@section('title') Image to Video @endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
@endsection
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

    .selected-background {
            background: linear-gradient(45deg, #9293e0, #db9dd4); /* Choose a color you like for the selected card */
            color: #e0e0e0; /* Adjust the text color if needed for better contrast */
    }
</style>

<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <!-- DALL-E Form -->
            <form action="{{route('generate.image.dalle')}}" method="POST" enctype="multipart/form-data" id="dalleForm" class="image-form">
                @csrf
                <h1 id="dalleHeading" class="text-white d-flex align-items-center gap-4">
                    <strong>Text to Image | DALL-E</strong>
                    <a class="use-case-btn" data-bs-toggle="offcanvas" href="#dalleOffcanvas" role="button"><i class="bx bx-wrench"></i></a>
                </h1>
                <h2 id="dalleSubheading" class="gradient-text-3">Transform your Text into stunning images with DALL-E</h2>
                <p id="dalleParagraph">Elevate your creativity with DALL-E, an AI tool that converts text into high-quality images.</p>
                {{-- Fields Dalle--}}
                <textarea class="form-control search ps-5 mt-1" name="prompt" rows="1" id="dallePrompt" placeholder="Write prompt to generate Image"></textarea>
                <br>
                <button type="submit" class="btn gradient-btn-3">Generate</button>

                
            </form>

            <!-- Stable Diffusion Form (Hidden by Default) -->
            <form action="{{ route('stable.image') }}" method="POST" enctype="multipart/form-data" id="sdForm" class="image-form" style="display: none;">
                @csrf
                <h1 id="sdHeading" class="text-white d-flex align-items-center gap-4">
                    <strong>Text to Image | Stable Diffusion</strong>
                    <a class="use-case-btn" data-bs-toggle="offcanvas" href="#sdOffcanvas" role="button"><i class="bx bx-wrench"></i></a>
                </h1>
                <h2 id="sdSubheading" class="gradient-text-3">Transform your Text into stunning images with Stable Diffusion</h2>
                <p id="sdParagraph">Elevate your creativity with Stable Diffusion, an AI tool that converts text into high-quality images.</p>
                
                {{-- Fields SD--}}
                <input type="hidden" name="hiddenStyle" id="hiddenStyle">
                <textarea class="form-control search ps-5 mt-1" name="prompt" rows="1" id="sdPrompt" placeholder="Write prompt to generate Image"></textarea>
                <br>
                <button type="submit" class="btn gradient-btn-3">Generate</button>
            </form>
                            

        </div>
        <div class="col-md-6" id="image-container">    
                <img id="before_after_img" src="https://img.freepik.com/free-photo/view-chameleon-with-bright-neon-colors_23-2151682699.jpg"
                    alt="Before and After"
                    class="before-after img-fluid rounded shadow-sm"
                    style="max-width: 100%; height: auto;">
        </div>
        
    </div>
    <div class="text-center mt-5">
        <h3 class="text-white">Use Cases</h3>
        <p>Attain heightened levels of clarity and intricacy in your AI creations, photographs, and illustrations.</p>
        <div class="d-flex justify-content-center flex-wrap">
            <button class="use-case-btn" data-target="dalleForm">DALL-E</button>
            <button class="use-case-btn" data-target="sdForm">Stable Diffusion</button>
            <button class="use-case-btn" data-target="designForm">Design</button>
            <button class="use-case-btn" data-target="foodForm">Food</button>
            <button class="use-case-btn" data-target="moreForm">More</button>
        </div>

        {{-- Offcanvas Dalle--}}
        <div class="offcanvas offcanvas-start" tabindex="-1" id="dalleOffcanvas" aria-labelledby="dalleOffcanvasLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="dalleOffcanvasLabel">DALL-E Options</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- DALL-E specific options -->
                <select name="style[]" class="form-select" data-choices data-choices-removeItem multiple id="style">                                                   
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

                <select name="quality" class="form-select" id="quality" data-choices>
                    <option disabled selected="">Enter Image Quality</option>
                    <option value="standard">Standard</option>
                    <option value="hd">HD</option>
                </select>

                <select name="image_res" class="form-select upgrade_option" id="image_res" data-choices>
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
        </div>

        {{-- Offcanvas SD --}}
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sdOffcanvas" aria-labelledby="sdOffcanvasLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="sdOffcanvasLabel">Stable Diffusion Options</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Stable Diffusion specific options -->
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

                <select name="imageFormat" id="imageFormat" class="form-select" data-choices onchange="syncImageFormat()">
                    <option value="" disabled selected>Select format</option>
                    <option value="jpeg">JPEG</option>
                    <option value="png">PNG</option>
                    <option value="webp">WEBP</option>
                </select>

                {{-- Style SD--}}
                <div class="d-flex flex-wrap justify-content-between">
                    <!-- Image Box 1 -->
                    <div class="col-3 mb-3">
                        <div class="image-box border p-2 text-center d-flex flex-column align-items-center justify-content-between" onclick="selectStyle('Animation', this)" style="height: 150px;">
                            <img src="{{ asset('build/images/stable/animation.jpg') }}" alt="Animation" class="img-fluid mb-2" style="height: 100px; width: 100%; object-fit: cover;" loading="lazy">
                            <p class="mb-0 gradient-text-1-bold">Animation</p>
                        </div>
                    </div>
            
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
        
    </div>
</div>
@endsection

@section('script')

<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<!-- JavaScript to Toggle Forms -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll(".use-case-btn");
        const forms = document.querySelectorAll(".image-form");

        buttons.forEach(button => {
            button.addEventListener("click", function () {
                const targetForm = this.getAttribute("data-target");

                if (targetForm) {
                    // Hide all forms
                    forms.forEach(form => form.style.display = "none");

                    // Show the selected form
                    const formToShow = document.getElementById(targetForm);
                    if (formToShow) {
                        formToShow.style.display = "block";
                    }
                }
            });
        });
    });
</script>

{{-- Dalle SCRIPTS Start--}}
<script>
    $(document).ready(function() {

        $('#dalleForm').submit(function(event) {
            event.preventDefault(); // Prevent default form submission
            
             // Show the magic ball
             showMagicBall('image');

        
            // Create a FormData object
            var formData = new FormData(this);

            // Manually append the offcanvas input values
            formData.append('quality', $('#quality').val());
            formData.append('image_res', $('#image_res').val());

            // For multiple select (style[])
            var selectedStyles = $('#style').val();
            if (selectedStyles) {
                selectedStyles.forEach(function(style) {
                    formData.append('style[]', style);
                });
            }

            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: '/image/generate/dalle',
                data: formData,
                processData: false, // Prevent jQuery from automatically processing the data
                contentType: false,
                success: function(response) {
                   // Hide the magic ball after content loads
                   hideMagicBall();

                    console.log(response);
                 
                    $('#image-container').empty(); // Clear previous images if any
                    response.data.forEach(function(imageData) {
                        // Create an image element
                        var temp = `<a class="image-popup" href="${imageData.url}" title="">
                                        <img class="gallery-img img-fluid mx-auto" style="height: 283px; width:283px" src="${imageData.url}" alt="" />
                                    </a>`;

                        // Append the image to the container
                        $('#image-container').append(temp);
                    });

                    // Initialize Glightbox
                    $(document).ready(function() {
                        const lightbox = GLightbox({
                            selector: '.image-popup',
                            touchNavigation: true,
                            loop: true
                        });
                    });

                    // Initialize Glightbox
                    $(document).ready(function() {
                        const lightbox = GLightbox({
                            selector: '.image-popup',
                            touchNavigation: true,
                            loop: true
                        });
                    });
              
                         var credits_left = response.credit_left;
                         $('.credit-left').text(credits_left);

                },
                error: function(xhr, status, error) {
                    // Handle error response
                     // Hide the magic ball after content loads
                    hideMagicBall();
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
{{-- Dalle SCRIPTS END--}}

{{-- SD SCRIPTS Start --}}
<script>
    $(document).ready(function() {
    
        $('#sdForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
    
             // Show the magic ball
             showMagicBall('Image');

            var formData = new FormData(this);
            // Manually append the offcanvas input values
            formData.append('modelVersion', $('#modelVersion').val());
            formData.append('imageFormat', $('#imageFormat').val());
            
            
            $.ajax({
                url: $(this).attr('action'), // Use the form's action URL
                type: 'POST',
                data: formData, // Send FormData
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Ensure the correct content type for files
                
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
                        hideMagicBall();
                        $('#imageContainer').html('<img src="data:image/jpeg;base64,' + response.image_base64 + '" alt="Generated Image" style="max-width:100%;">');
                    }
                },
    
                error: function(xhr) {
                    hideMagicBall();
                    // Handle errors
                    $('#responseMessage').html('<p>Error generating image. Please try again.</p>');
                }
            });
        });
    });

    // SD Style Selection
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
    </script>
{{-- SD SCRIPTS END --}}

@endsection
