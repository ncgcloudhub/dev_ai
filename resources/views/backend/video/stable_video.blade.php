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
                <strong>Text to Video</strong> 
                <span id="toggleButton" class="btn gradient-btn-3">Image to Video</span>
            </h1>            
                <h2 id="subheadingText" class="gradient-text-3">Transform your Text into stunning videos</h2>
                <p id="pText">Elevate your creativity with our AI tool that converts text and images into high-quality videos. Effortlessly generate engaging video content for presentations, social media, and more.</p>
        <form action="{{ route('generate.text_to_video') }}" method="POST" enctype="multipart/form-data" id="videoGenerationForm">
            @csrf
            <textarea class="form-control search ps-5 mt-1" name="prompt" rows="1" id="prompt" placeholder="Write prompt to generate Video"></textarea><br>
            <button type="submit" class="btn gradient-btn-3">Generate</button>
        </form>

        <!-- Image to Video Form (Initially Hidden) -->
        <form action="{{ route('generate.image_to_video') }}" method="POST" enctype="multipart/form-data" id="imageVideoForm" style="display: none;">
            @csrf
                <input type="file" name="image" id="image" class="form-control ps-5 mt-1 mb-3" accept="image/*" required>
                <small class="text-danger d-none" id="resolution-error">Please upload an image with one of the following resolutions: 1024x576, 576x1024, or 768x768.</small>
                <button type="submit" class="btn gradient-btn-3">Generate</button>
        </form>
         <!-- END Image to Video Form END (Initially Hidden) -->


        </div>
        <div class="col-md-6">
            <img src="https://img.freepik.com/free-photo/bright-neon-colors-shining-wild-chameleon_23-2151682784.jpg" alt="Before and After" class="before-after">
        </div>
    </div>
    <div class="text-center mt-5">
        <h3 class="text-white">Use Cases</h3>
        <p>Attain heightened levels of clarity and intricacy in your AI creations, photographs, and illustrations.</p>
        <div class="d-flex justify-content-center flex-wrap">
            <button class="use-case-btn">Portraits</button>
            <button class="use-case-btn">Illustration</button>
            <button class="use-case-btn">Landscapes</button>
            <button class="use-case-btn">Design</button>
            <button class="use-case-btn">Food</button>
            <button class="use-case-btn">More</button>
        </div>
    </div>
</div>
@endsection

@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>

    const apiKey = @json($apiKey); // Loaded from environment variable
    
    // Generate Video from Text
    document.getElementById('videoGenerationForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
       
        try {
            const response = await fetch("{{ route('generate.text_to_video') }}", {
                method: "POST",
                body: formData,
            });

            const result = await response.json();

            if (response.ok && result.generation_id) {
                fetchVideo(result.generation_id);
            } else {
                alert('Error: ' + (result.error?.message || 'Unknown error occurred'));
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });


    // Generate Image to VIDEO
    document.getElementById('imageVideoForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch("{{ route('generate.image_to_video') }}", {
                method: "POST",
                body: formData,
            });

            const result = await response.json();

            if (response.ok && result.generation_id) {
                fetchVideo(result.generation_id);
            } else {
                alert('Error: ' + (result.error?.message || 'Unknown error occurred'));
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
    
    // FETCH Video
    function fetchVideo(generationId) {
        // Polling interval (in milliseconds)
        const pollingInterval = 10000; // 10 seconds
    
        // Start polling
        const pollForVideo = setInterval(() => {
            fetch(`https://api.stability.ai/v2beta/image-to-video/result/${generationId}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${apiKey}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Handle case when the video is ready
                if (data && data.finish_reason === 'SUCCESS') {
                    console.log('Video is ready! Base64 video data:', data.video);
    
                    // Decode the base64 video and display or download it
                    const videoBlob = new Blob([new Uint8Array(atob(data.video).split("").map(c => c.charCodeAt(0)))], { type: 'video/mp4' });
                    const videoUrl = URL.createObjectURL(videoBlob);
                    
                    const imgElement = document.querySelector('.before-after');

                  if (imgElement) {
                    // Create a new video element
                    const videoElement = document.createElement('video');
                    videoElement.src = videoUrl;
                    videoElement.controls = true;
                    videoElement.autoplay = true;
                    videoElement.style.width = imgElement.clientWidth + 'px';
                    videoElement.style.height = 'auto'; // Maintain aspect ratio 

                    // Replace the image with the video
                    imgElement.replaceWith(videoElement);
                }
    
                    // Stop polling after success
                    clearInterval(pollForVideo);
                } else if (data && data.status === 'in-progress') {
                    console.log('Video generation still in progress...');
                } else {
                    console.error('Error fetching video:', data);
                    clearInterval(pollForVideo);
                }
            })
            .catch(error => {
                console.error('Error fetching video:', error);
                clearInterval(pollForVideo);
            });
        }, pollingInterval);
    }
    
    </script>

    {{-- Toggle Button for Form (Text/Image to video) --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleButton = document.getElementById("toggleButton");
            const headingText = document.getElementById("headingText").querySelector("strong");
            const subheadingText = document.getElementById("subheadingText");
            const pText = document.getElementById("pText");
            const textVideoForm = document.getElementById("videoGenerationForm");
            const imageVideoForm = document.getElementById("imageVideoForm");
            
            let isTextToVideo = true; // Tracks current mode
        
            toggleButton.addEventListener("click", function() {
                if (isTextToVideo) {
                    headingText.textContent = "Image to Video"; // Change heading text
                    subheadingText.textContent = "Transform your Images into stunning videos";
                    pText.textContent = "Please upload an image with one of the following resolutions: 1024x576, 576x1024, or 768x768."; 
                    toggleButton.textContent = "Text to Video";
                    textVideoForm.style.display = "none"; // Hide Text to Video form
                    imageVideoForm.style.display = "block"; // Show Image to Video form
                } else {
                    headingText.textContent = "Text to Video";
                    subheadingText.textContent = "Transform your Text into stunning videos";
                    pText.textContent = "Elevate your creativity with our AI tool that converts text and images into high-quality videos. Effortlessly generate engaging video content for presentations, social media, and more."; 
                    toggleButton.textContent = "Image to Video";
                    textVideoForm.style.display = "block"; // Show Text to Video form
                    imageVideoForm.style.display = "none"; // Hide Image to Video form
                }
                
                isTextToVideo = !isTextToVideo; // Toggle the state
            });
        });
    </script>

@endsection
