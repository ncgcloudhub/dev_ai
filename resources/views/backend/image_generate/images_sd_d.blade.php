@extends('admin.layouts.master')
@section('title') Image to Video @endsection
@section('sidebar-size', 'sm') <!-- This sets the sidebar size for this page -->
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
@endsection
@section('content')

<style>
    body {
        background: linear-gradient(to right, #1a0a24, #3a0750);
        color: white;
        font-family: Arial, sans-serif;
    }
    
    .offcanvas {
        background: linear-gradient(to right, #1a0a24, #3a0750);
    }

    /* .image-box.selected {
    border: 2px solid #96004b; 
    background-color: rgba(0, 123, 255, 0.1);
    } */

    .selected-background {
        background: linear-gradient(to right, #ac13ac, #3a0750); /* Choose a color you like for the selected card */
    }

    .model-switcher .model-btn {
        min-width: 150px;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .model-switcher .model-btn.active {
        background: linear-gradient(90deg, #7b2ff7, #f107a3);
        color: white;
        border-color: transparent;
    }

</style>

<div class="container-fluid py-5 px-5">
    <div class="row">
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
                <input type="hidden" name="hiddenPromptOptimize" id="hiddenPromptOptimize_dalle3">
                <div class="d-flex gap-2 align-items-center w-100">
                    <div class="search-box position-relative flex-grow-1">
                        <a title="Optimize Prompt" class="btn btn-optimize position-absolute top-50 translate-middle-y"
                        onclick="toggleOptimize('dalle3')" id="optimizeIcon_dalle3">
                            <i class="ri-hammer-line fs-4"></i>
                        </a>
                        <textarea class="form-control search ps-5 mt-1" name="prompt" rows="1" id="dallePrompt" placeholder="Write prompt to generate Image"></textarea>
                        <button type="button" class="speech-btn btn btn-link position-absolute top-50 end-0 translate-middle-y">
                            <i class="mic-icon ri-mic-line fs-4"></i>
                        </button>
                    </div>
                    <button type="submit" class="btn gradient-btn-3"><i class="bx bxs-magic-wand fs-4"></i></button>
                </div>
                <br>
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
                <input type="hidden" name="hiddenPromptOptimize" id="hiddenPromptOptimize_sd">
                <input type="hidden" name="hiddenStyle" id="hiddenStyle">
                <input type="hidden" name="mode" id="mode" value="text-to-image">
                <div class="d-flex gap-2 align-items-center w-100">
                    <div class="search-box position-relative flex-grow-1">
                        <a title="Optimize Prompt" class="btn btn-optimize position-absolute top-50 translate-middle-y"
                        onclick="toggleOptimize('sd')" id="optimizeIcon_sd">
                            <i class="ri-hammer-line fs-4"></i>
                        </a>
                        <textarea class="form-control search ps-5 mt-1" name="prompt" rows="1" id="sdPrompt" placeholder="Write prompt to generate Image"></textarea>
                        <button type="button" class="speech-btn btn btn-link position-absolute top-50 end-0 translate-middle-y">
                            <i class="mic-icon ri-mic-line fs-4"></i>
                        </button>
                    </div>
                    <button type="submit" class="btn gradient-btn-3"><i class="bx bxs-magic-wand fs-4"></i></button>
                </div>
                <br>
            </form>
                     
            {{-- USE CASES --}}
            <h3 class="text-white">Use Cases</h3>
            <p>Attain heightened levels of clarity and intricacy in your AI creations, photographs, and illustrations.</p>
            <div class="model-switcher text-center my-4">
                <p class="text-light mb-2 fw-semibold">Select Image Generation Model:</p>
                <div class="btn-group toggle-model-buttons" role="group" aria-label="AI Model Switcher">
                    <button type="button" class="btn btn-outline-light model-btn active" data-target="dalleForm">
                        🎨 DALL·E
                    </button>
                    <button type="button" class="btn btn-outline-light model-btn" data-target="sdForm">
                        🧠 Stable Diffusion
                    </button>
                </div>
            </div>
            <br>
        </div>

        <div class="col-md-6" id="image-container">    
            <img id="before_after_img" src="https://img.freepik.com/free-photo/view-chameleon-with-bright-neon-colors_23-2151682699.jpg"
                alt="Before and After"
                class="before-after img-fluid rounded shadow-sm"
                style="max-width: 100%; height: auto;">
                <br><br>
                <blockquote class="blockquote rounded mb-0">
                    <p class="text-white mb-2">This image features a chameleon surrounded by vibrant, colorful foliage. The chameleon is displaying a striking combination of blue and purple hues, with the surrounding plants also displaying vivid pink and blue lighting, creating a surreal and visually captivating scene.</p>
                    <footer class="blockquote-footer mt-0"><cite title="Image Prompt">Prompt</cite></footer>
                </blockquote>
        </div>

        <div class="col">
            {{-- Gallery Loaded START --}}
            <h3 class="text-white">Generated Images</h3>
            <div class="gallery-light">
                <div class="row">
                    @foreach($images as $item)
                        <div class="col-xl-2 col-lg-4 col-sm-6">
                        <div class="gallery-box card">
                            <div class="gallery-container">
                                <a class="image-popup" href="{{ $item->image_url }}" title="">
                                    <img class="gallery-img img-fluid mx-auto"
                                        src="{{ $item->image_url }}" alt="" loading="lazy" />
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
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    @endforeach
                </div>
                <!--end row-->
            </div>
            {{-- Gallery Loaded END --}}
        </div>
        
    </div>

    <div class="text-center mt-5">
        {{-- Offcanvas Dalle--}}
        <div class="offcanvas offcanvas-start" tabindex="-1" id="dalleOffcanvas" aria-labelledby="dalleOffcanvasLabel">
            <div class="offcanvas-header border-bottom bg-dark">
                <h5 class="offcanvas-title text-white fs-4" id="dalleOffcanvasLabel">
                    <i class="fas fa-palette me-2"></i>DALL-E Advanced Settings
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            
            <div class="offcanvas-body bg-light">
                <div class="mb-4">
                    <h6 class="text-dark mb-3 fw-bold"><i class="fas fa-brush me-2"></i>Style Options</h6>
                    <div class="form-group mb-3">
                        <label class="form-label text-muted small mb-2">Select Art Styles (Multiple selection allowed)</label>
                        <select name="style[]" class="form-select form-select-sm border-secondary" 
                                data-choices data-choices-removeItem multiple id="style"
                                style="min-height: 120px">
                            <optgroup label="Basic Styles">
                                <option value="natural">Natural</option>
                                <option value="vivid">Vivid</option>
                                <option value="none">None</option>
                            </optgroup>
                            <optgroup label="Artistic Styles">
                                <option value="cinematic">Cinematic</option>
                                <option value="analog-film">Analog Film</option>
                                <option value="animation">Animation</option>
                                <option value="comic">Comic</option>
                                <option value="craft-clay">Craft Clay</option>
                                <option value="fantasy">Fantasy</option>
                                <option value="line-art">Line Art</option>
                                <option value="cyberpunk">Cyberpunk</option>
                                <option value="pixel-art">Pixel Art</option>
                                <option value="photograph">Photograph</option>
                            </optgroup>
                        </select>
                        <small class="form-text text-muted">Combine different styles for unique effects</small>
                    </div>
                </div>
                <hr>
                <div class="pt-4">
                    <h6 class="text-dark mb-3 fw-bold"><i class="fas fa-cog me-2"></i>Image Settings</h6>
                    
                    <div class="form-group mb-4">
                        <label class="form-label text-muted small mb-2">Output Quality</label>
                        <select name="quality" class="form-select border-secondary" id="quality" data-choices>
                            <option value="standard" selected>Standard Quality</option>
                            <option value="hd">HD Quality</option>
                        </select>
                        <small class="form-text text-muted">HD uses 2x credits</small>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="form-label text-muted small mb-2">Image Resolution</label>
                        <select name="image_res" class="form-select border-secondary upgrade_option" id="image_res" data-choices>
                            @if(Auth::user()->role === 'admin')
                                <!-- Admin sees all options -->
                                <option value="1024x1024">Square (1024×1024)</option>
                                <option value="1792x1024">Landscape (1792×1024)</option>
                                <option value="1024x1792">Portrait (1024×1792)</option>
                            @elseif($lastPackageId)
                                <!-- Paid User Options -->
                                <option value="1024x1024">Square (1024×1024)</option>
                                <option value="1792x1024">Landscape (1792×1024)</option>
                                <option value="1024x1792">Portrait (1024×1792)</option>
                            @else
                                <!-- Free User Options -->
                                <option value="1024x1024" selected>Square (1024×1024) - Free</option>
                                <option disabled class="text-muted">
                                    🔒 Upgrade for Landscape/Portrait Resolutions
                                </option>
                            @endif
                        </select>
                        <small class="form-text text-muted">
                            @if(Auth::user()->role === 'admin')
                                Admins have access to all resolutions
                            @elseif($lastPackageId)
                                Higher resolutions use more credits
                            @else
                                Free users get standard square resolution
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Offcanvas SD --}}
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sdOffcanvas" aria-labelledby="sdOffcanvasLabel">
            <div class="offcanvas-header border-bottom bg-dark">
                <h5 class="offcanvas-title text-white fs-4" id="sdOffcanvasLabel">
                    <i class="fas fa-sliders-h me-2"></i>Stable Diffusion Settings
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body bg-light">
                <!-- Model Version -->
                <div class="mb-4">
                    <h6 class="text-dark mb-3 fw-bold"><i class="fas fa-cogs me-2"></i>Model & Format</h6>

                    <div class="form-group mb-3">
                        <label class="form-label text-muted small mb-2">Model Version</label>
                        <select name="modelVersion" id="modelVersion" class="form-select form-select-sm border-secondary" data-choices onchange="syncModelVersion()">
                            <option value="sd3.5-medium" selected>sd3.5-medium</option>
                            <option value="sd3.5-large-turbo">sd3.5-large-turbo</option>
                            <option value="sd3.5-large">sd3.5-large</option>
                            <option value="sd-ultra">SD Ultra</option>
                            <option value="sd-core">SD Core</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label text-muted small mb-2">Image Format</label>
                        <select name="imageFormat" id="imageFormat" class="form-select form-select-sm border-secondary" data-choices onchange="syncImageFormat()">
                            <option value="png" selected>PNG</option>
                            <option value="jpeg">JPEG</option>
                            <option value="webp">WEBP</option>
                        </select>
                    </div>
                </div>

                <hr>

                <!-- Styles -->
                <div class="pt-4">
                    <h6 class="text-dark mb-3 fw-bold"><i class="fas fa-palette me-2"></i>Choose a Style</h6>
                    <div class="row g-2">
                        @php
                            $styles = [
                                ['name' => 'Animation', 'image' => 'animation.jpg'],
                                ['name' => 'Cinematic', 'image' => 'cinematic.jpg'],
                                ['name' => 'Comic', 'image' => 'comic.jpg'],
                                ['name' => 'Cyberpunk', 'image' => 'cyberpunk.jpg'],
                                ['name' => 'Futurism', 'image' => 'futurism.jpeg'],
                                ['name' => 'Doodle Art', 'image' => 'doodle_art.jpg'],
                                ['name' => 'Graffiti', 'image' => 'graffiti.jpg'],
                                ['name' => 'Sketch', 'image' => 'sketch.jpg'],
                            ];
                        @endphp

                        @foreach($styles as $style)
                            <div class="col-6 col-md-3">
                                <div class="image-box border p-2 text-center bg-dark-subtle rounded shadow-sm"
                                    onclick="selectStyle('{{ $style['name'] }}', this)"
                                    style="cursor: pointer; height: 150px;">
                                    <img src="{{ asset('build/images/stable/' . $style['image']) }}"
                                        alt="{{ $style['name'] }}"
                                        class="img-fluid mb-2 rounded"
                                        style="height: 100px; width: 100%; object-fit: cover;"
                                        loading="lazy">
                                    <p class="mb-0 text-dark small fw-semibold">{{ $style['name'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <small class="form-text text-muted mt-2 d-block">Click on a style to apply it</small>
                </div>
            </div>
        </div>

        
    </div>
</div>
@endsection

@section('script')

    <script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        document.querySelectorAll('.model-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active state from all
                document.querySelectorAll('.model-btn').forEach(btn => btn.classList.remove('active'));

                // Set active on clicked
                button.classList.add('active');

                // Get target form ID
                const target = button.getAttribute('data-target');

                // Show the target form, hide the others
                document.querySelectorAll('.image-form').forEach(form => {
                    form.style.display = form.id === target ? 'block' : 'none';
                });
            });
        });
    </script>

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
                showMagicBall('facts', 'image');

            
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
                                        </a>
                                        `;

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
                showMagicBall('facts', 'image');

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
                        
                        // Clear previous images
                        $('#image-container').empty();

                        if (response.image_url) {
                            // Append the image with GLightbox support
                            var imageElement = `
                                <a class="image-popup" href="${response.image_url}" title="Generated Image">
                                    <img class="gallery-img img-fluid mx-auto" style="height: 283px; width:283px" src="${response.image_url}" alt="Generated Image" />
                                </a>
                                <p>${response.prompt}</p>
                            `;
                            $('#image-container').append(imageElement);

                        } else if (response.image_base64) {
                            hideMagicBall();
                            
                            var base64Image = `data:image/jpeg;base64,${response.image_base64}`;
                            var imageElement = `
                                <a class="image-popup" href="${base64Image}" title="Generated Image">
                                    <img class="gallery-img img-fluid mx-auto" style="height: 283px; width:283px" src="${base64Image}" alt="Generated Image" />
                                </a>
                                <p>${response.prompt}</p>
                            `;
                            $('#image-container').append(imageElement);
                        }
                        // Initialize GLightbox (Reinitialize after new images are added)
                        const lightbox = GLightbox({
                            selector: '.image-popup',
                            touchNavigation: true,
                            loop: true
                        });
                    },
        
                    error: function(jqXHR, textStatus, errorThrown) {
                        hideMagicBall();

                        let errorMessage = 'An error occurred.';

                        if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                            errorMessage = jqXHR.responseJSON.error;
                        } else if (jqXHR.responseText) {
                            try {
                                const parsed = JSON.parse(jqXHR.responseText);
                                errorMessage = parsed.error || parsed.message || errorThrown;
                            } catch (e) {
                                errorMessage = errorThrown || 'A network error occurred.';
                            }
                        } else {
                            errorMessage = errorThrown || 'A network error occurred.';
                        }

                        toastr.error(errorMessage);
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
