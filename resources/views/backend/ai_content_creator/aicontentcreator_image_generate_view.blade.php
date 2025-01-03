@extends('admin.layouts.master')
@section('title') Extract Prompt @endsection
@section('css')
<link href="{{ URL::asset('build/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('aicontentcreator.manage')}}">Templates</a> @endslot
@slot('title') Extract Prompt and Generate Image @endslot
@endcomponent

<div class="row">
    <div class="col-xxl-6">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary text-white">
                <h4 class="card-title mb-0">Extract Prompt and Generate Image</h4>
            </div>
            <div class="card-body">
                {{-- <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-outline-danger" id="clearInputsButton" title="Clear all the Input values">
                        <i class="las la-undo-alt"></i> Clear Inputs
                    </button>
                </div> --}}
                <form id="generateForm" action="{{ route('extract.image') }}" method="post" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="language" class="form-label">Select Language</label>
                        <select class="form-select" name="language" id="language" aria-label="Floating label select example">
                            <option disabled>Enter Language</option>
                            <option value="English" selected>English</option>
                            <option value="Arabic">Arabic</option>
                            <option value="Bengali">Bengali</option>
                            <option value="Chinese (Simplified)">Chinese (Simplified)</option>
                            <option value="Chinese (Traditional)">Chinese (Traditional)</option>
                            <option value="Dutch">Dutch</option>
                            <option value="French">French</option>
                            <option value="German">German</option>
                            <option value="Hindi">Hindi</option>
                            <option value="Indonesian">Indonesian</option>
                            <option value="Italian">Italian</option>
                            <option value="Japanese">Japanese</option>
                            <option value="Korean">Korean</option>
                            <option value="Polish">Polish</option>
                            <option value="Portuguese">Portuguese</option>
                            <option value="Russian">Russian</option>
                            <option value="Spanish">Spanish</option>
                            <option value="Swahili">Swahili</option>
                            <option value="Tamil">Tamil</option>
                            <option value="Turkish">Turkish</option>
                            <option value="Vietnamese">Vietnamese</option>
                        </select>
                        <small class="form-text text-muted">English is selected by default</small>
                    </div>
                    <div class="col-md-6">
                        <label for="custom_image" class="form-label">Upload Image</label>
                        <input type="file" class="form-control" name="custom_image" id="custom_image" aria-label="Upload image" onchange="previewImage(event)">
                        <div id="image_preview" class="mt-3" style="display: none;">
                            <img id="preview_img" src="" alt="Image Preview" class="img-fluid rounded shadow-sm" style="max-width: 100%; height: auto;">
                        </div>
                    </div>
                    <div class="col-12 text-center my-3 d-none" id="loader">
                        <button class="btn btn-outline-primary btn-load">
                            <span class="d-flex align-items-center">
                                <span class="spinner-border flex-shrink-0" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                                <span class="flex-grow-1 ms-2">Loading...</span>
                            </span>
                        </button>
                    </div>
                    <div class="col-12 text-end">
                        <button id="generateButton" class="btn btn-primary btn-rounded text-white" type="button">Extract Prompt</button>
                    </div>
                </form>
                <div id="extractedContent" class="mt-4"></div>
                <div id="imageGenerateSection" class="mt-4 text-center" style="display: none;">
                    <button id="generateImageButton" class="btn btn-success btn-rounded">Generate Image</button>
                </div>

                <div id="videoContainer" style="margin-top: 20px; display: none;">
                    <h2>Generated Video</h2>
                    <video id="generatedVideo" controls>
                        <source id="videoSource" src="" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
               
            </div>
        </div>
    </div>

    <div class="col-xxl-6">
        <div class="card shadow-sm">
            <div id="generatedImage" class="mt-4 text-center mb-3">
                <!-- Generated image will be displayed here -->
            </div>
        </div>
    </div>
    
</div>


@endsection
@section('script')
<script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
<script src="{{ URL::asset('build/libs/quill/quill.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>


{{-- Submit Form Editor --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.getElementById('clearInputsButton').addEventListener('click', function() {
        // Reset the form (clear inputs)
        document.getElementById('generateForm').reset();

        // Optionally reset select inputs to default options
        document.getElementById('language').value = 'English'; // Default language
        document.getElementById('points').value = '1'; // Default variations

        // For additional input types, clear manually if needed
    });
</script>



<!-- Include SimpleMDE JS -->
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<!-- Include FileSaver.js for saving files -->
<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>

<!-- Include Marked.js for parsing markdown -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<!-- Include DOMPurify for sanitizing HTML -->
<script src="https://cdn.jsdelivr.net/npm/dompurify@2.3.4/dist/purify.min.js"></script>



{{-- New Script --}}
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Preview Image Functionality
    function previewImage(event) {
        const imagePreview = document.getElementById("image_preview");
        const previewImg = document.getElementById("preview_img");
        previewImg.src = URL.createObjectURL(event.target.files[0]);
        imagePreview.style.display = "block";
    }

    let extractedPrompt = ""; // To store the extracted prompt for the second button

    // First Button: Extract Prompt from Image
    $("#generateButton").on("click", function (e) {
        e.preventDefault();
        const formData = new FormData($("#generateForm")[0]);
        $("#loader").removeClass("d-none"); // Show loader

        $.ajax({
            url: "{{ route('extract.image') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $("#loader").addClass("d-none"); // Hide loader
                if (response.content) {
                    extractedPrompt = response.content; // Store the extracted prompt
                    $("#extractedContent").html(`
                        <div class="alert alert-success" role="alert">
                            <strong>Extracted Content:</strong> ${extractedPrompt}
                        </div>
                    `);

                    // Show the generate image button
                    $("#imageGenerateSection").show();
                } else {
                    $("#extractedContent").html(`
                        <div class="alert alert-warning" role="alert">
                            Failed to extract content from the image.
                        </div>
                    `);
                }
            },
            error: function (xhr) {
                $("#loader").addClass("d-none"); // Hide loader
                $("#extractedContent").html(`
                    <div class="alert alert-danger" role="alert">
                        An error occurred: ${xhr.responseJSON?.error || "Unknown error"}
                    </div>
                `);
            }
        });
    });

    // Second Button: Generate Image
    $("#generateImageButton").on("click", function () {
        if (!extractedPrompt) {
            alert("Please extract the prompt first.");
            return;
        }

        const generateRoute = "{{ route('stable.image') }}"; // Update with the correct route name

        $.ajax({
            url: generateRoute,
            type: "POST",
            data: {
                prompt: extractedPrompt,
                hiddenStyle: "Realistic", // Example: Add additional fields if required
                hiddenImageFormat: "jpeg",
                _token: "{{ csrf_token() }}" // CSRF token for Laravel
            },
            success: function (response) {
                if (response.image_url) {
                    $("#generatedImage").html(`
                        <div class="alert alert-success" role="alert">
                            Image generated successfully!
                        </div>
                        <img src="${response.image_url}" alt="Generated Image" class="img-fluid rounded shadow-sm" style="height: 650px; width: 650px">
                        <a href="${response.image_url}" download="generated-image.png" class="btn btn-success">Download Image</a>
                        <button id="generateVideoButton" class="btn btn-primary mt-3">Generate Video</button>
                    `);

                // Add click event for Generate Video button
                $("#generateVideoButton").on("click", function () {
                    const videoRoute = "{{ route('generate.video') }}";

                    const formDatas = new FormData();

                   // Check if image_url exists
                    if (response.image_url) {
                        formDatas.append("image_url", response.image_url); // Pass image URL to backend
                    } else {
                        alert("Image URL not found. Please generate an image first.");
                        return;
                    }

                    formDatas.append("_token", "{{ csrf_token() }}");

                    $.ajax({
                        url: videoRoute,
                        type: "POST",
                        data: formDatas,
                        processData: false,
                        contentType: false,
                        success: function (videoResponse) {
                            if (videoResponse.id) {
                                $("#generatedImage").append(`
                                    <div class="alert alert-success mt-3" role="alert">
                                        Video generation successful! ID: ${videoResponse.id}
                                    </div>
                                    <button id="generateVideoButton" class="btn btn-primary mt-3">Generate Video</button>
                                `);

                                 // Call fetchVideo with the generation ID
                                fetchVideo(videoResponse.generation_id);

                            } else {
                                $("#generatedImage").append(`
                                    <div class="alert alert-warning mt-3" role="alert">
                                        Failed to generate video.
                                    </div>
                                `);
                            }
                        },
                        error: function (xhr) {
                            $("#generatedImage").append(`
                                <div class="alert alert-danger mt-3" role="alert">
                                    An error occurred while generating the video: ${xhr.responseJSON?.error || "Unknown error"}
                                </div>
                            `);
                        }
                    });
                });

                } else {
                    $("#generatedImage").html(`
                        <div class="alert alert-warning" role="alert">
                            Failed to generate image.
                        </div>
                    `);
                }
            },
            error: function (xhr) {
                $("#generatedImage").html(`
                    <div class="alert alert-danger" role="alert">
                        An error occurred: ${xhr.responseJSON?.error || "Unknown error"}
                    </div>
                `);
            }
        });
    });

    const apiKey = @json($apiKey);
    
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

                // Display video in the browser
                const videoElement = document.createElement('video');
                videoElement.src = videoUrl;
                videoElement.controls = true;
                videoElement.classList.add("img-fluid", "rounded", "shadow-sm", "mt-3");
                videoElement.style.maxWidth = "650px";
                $("#generatedImage").append(videoElement);

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