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
            <h1 class="text-white"><strong>Text/Image to Video</strong></h1>
                <h2 class="gradient-text-3">Transform your Text & Images into stunning videos</h2>
                <p>Elevate your creativity with our AI tool that converts text and images into high-quality videos. Effortlessly generate engaging video content for presentations, social media, and more.</p>
        <form action="{{ route('generate.video') }}" method="POST" enctype="multipart/form-data" id="videoGenerationForm">
            @csrf
            <textarea class="form-control search ps-5 mt-1" name="prompt" rows="1" id="prompt" placeholder="Write prompt to generate Video"></textarea><br>
            <button type="submit" class="btn gradient-btn-3">Generate</button>
        </form>
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
    
    
        document.querySelector('form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
    
        try {
            const response = await fetch("{{ route('generate.image_to_video') }}", {
                method: "POST",
                body: formData,
            });
            const result = await response.json();
    
            if (response.ok) {
                console.log('video generated id:', result.generation_id);
    
                // Poll for video result
                fetchVideo(result.generation_id);
            } else {
                console.error('Error:', result.error);
            }
        } catch (error) {
            console.error('Request failed:', error);
        }
    });
    
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

@endsection
