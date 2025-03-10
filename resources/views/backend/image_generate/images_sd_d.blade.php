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
            <!-- DALL-E Form -->
            <form action="" method="POST" enctype="multipart/form-data" id="dalleForm" class="image-form">
                @csrf
                <h1 id="dalleHeading" class="text-white d-flex align-items-center gap-4">
                    <strong>Text to Image | DALL-E</strong>
                    <a class="use-case-btn" data-bs-toggle="offcanvas" href="#dalleOffcanvas" role="button"><i class="bx bx-wrench"></i></a>
                </h1>
                <h2 id="dalleSubheading" class="gradient-text-3">Transform your Text into stunning images with DALL-E</h2>
                <p id="dalleParagraph">Elevate your creativity with DALL-E, an AI tool that converts text into high-quality images.</p>
                <textarea class="form-control search ps-5 mt-1" name="prompt" rows="1" id="dallePrompt" placeholder="Write prompt to generate Image"></textarea>
                <br>
                <button type="submit" class="btn gradient-btn-3">Generate</button>
            </form>

            <!-- Stable Diffusion Form (Hidden by Default) -->
            <form action="" method="POST" enctype="multipart/form-data" id="sdForm" class="image-form" style="display: none;">
                @csrf
                <h1 id="sdHeading" class="text-white d-flex align-items-center gap-4">
                    <strong>Text to Image | Stable Diffusion</strong>
                    <a class="use-case-btn" data-bs-toggle="offcanvas" href="#sdOffcanvas" role="button"><i class="bx bx-wrench"></i></a>
                </h1>
                <h2 id="sdSubheading" class="gradient-text-3">Transform your Text into stunning images with Stable Diffusion</h2>
                <p id="sdParagraph">Elevate your creativity with Stable Diffusion, an AI tool that converts text into high-quality images.</p>
                <textarea class="form-control search ps-5 mt-1" name="prompt" rows="1" id="sdPrompt" placeholder="Write prompt to generate Image"></textarea>
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
        </div>

        {{-- Offcanvas SD --}}
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sdOffcanvas" aria-labelledby="sdOffcanvasLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="sdOffcanvasLabel">Stable Diffusion Options</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Stable Diffusion specific options -->
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
        </div>
        
    </div>
</div>
@endsection

@section('script')

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

@endsection
