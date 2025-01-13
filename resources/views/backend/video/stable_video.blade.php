@extends('admin.layouts.master')
@section('title') Image to Video @endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="#">Video Generation</a> @endslot
@slot('title') Generate Animated Video @endslot
@endcomponent

<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Generate Animated Video</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('generate.video') }}" method="POST" enctype="multipart/form-data" id="videoGenerationForm">
                    @csrf
                    <div class="mb-3">
                        <label for="image" class="form-label">Select Image</label>
                        <input type="file" name="image" id="image" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="seed" class="form-label">Seed</label>
                        <input type="number" name="seed" id="seed" class="form-control" value="0">
                    </div>
                    <div class="mb-3">
                        <label for="cfg_scale" class="form-label">CFG Scale</label>
                        <input type="number" name="cfg_scale" id="cfg_scale" class="form-control" value="1.8" step="0.1">
                    </div>
                    <div class="mb-3">
                        <label for="motion_bucket_id" class="form-label">Motion Bucket ID</label>
                        <input type="number" name="motion_bucket_id" id="motion_bucket_id" class="form-control" value="127">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-rounded gradient-btn-save">Generate Video</button>
                    </div>
                </form>
              
            </div>
        </div>
    </div>

    <div class="col-xxl-6">
        <div class="card">
            <div id="videoContainer" style="margin-top: 20px; display: none;">
                <h5>Generated Video</h5>
                <video id="generatedVideo" controls>
                    <source id="videoSource" src="" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>


<script>
    document.getElementById('videoGenerationForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch("{{ route('generate.video') }}", {
                method: "POST",
                body: formData,
            });

            const result = await response.json();

            if (response.ok && result.id) {
                fetchVideo(result.id);
            } else {
                alert('Error: ' + (result.error?.message || 'Unknown error occurred'));
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    const apiKey = @json($apiKey);

    function fetchVideo(generationId) {
        const pollingInterval = 10000;

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
                if (data.finish_reason === 'SUCCESS') {
                    const videoBlob = new Blob([new Uint8Array(atob(data.video).split("").map(c => c.charCodeAt(0)))], { type: 'video/mp4' });
                    const videoUrl = URL.createObjectURL(videoBlob);

                    const videoElement = document.getElementById('generatedVideo');
                    const videoContainer = document.getElementById('videoContainer');
                    const videoSource = document.getElementById('videoSource');

                    videoSource.src = videoUrl;
                    videoElement.load();
                    videoContainer.style.display = 'block';

                    clearInterval(pollForVideo);
                } else if (data.status === 'in-progress') {
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
