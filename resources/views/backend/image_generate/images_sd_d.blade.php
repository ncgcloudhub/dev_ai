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

</style>

<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 id="headingText" class="text-white d-flex align-items-center gap-4">
                <strong>Text to Images</strong> 
            </h1>            
                <h2 id="subheadingText" class="gradient-text-3">Transform your Text into stunning images</h2>
                <p id="pText">Elevate your creativity with our AI tool that converts text and images into high-quality videos. Effortlessly generate engaging video content for presentations, social media, and more.</p>
        <form action="{{ route('generate.text_to_video') }}" method="POST" enctype="multipart/form-data" id="videoGenerationForm">
            @csrf
            <textarea class="form-control search ps-5 mt-1" name="prompt" rows="1" id="prompt" placeholder="Write prompt to generate Video"></textarea><br>
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
        
                <select name="style[]" class="form-select" id="style" data-choices>
                    <option disabled selected value="">Select Style</option>
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
            <div class="offcanvas-foorter border-top p-3 text-center">
                <a href="javascript:void(0);">View All Activity <i class="ri-arrow-right-s-line align-middle ms-1"></i></a>
            </div>
        </div>
        
    </div>
</div>
@endsection

@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
