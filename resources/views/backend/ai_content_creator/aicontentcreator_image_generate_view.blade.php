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


<button type="button" class="btn gradient-btn-5" onclick="history.back()">
    <i class="las la-arrow-left"></i>
</button>

<button id="templateDetailsTourButton" class="btn gradient-btn-6 text-white my-2" title="Get a Tour of this page to know it better">Template View Tour</button>

<div class="row">
   
           <div class="col-xxl-6">
            <div class="card">
               
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn gradient-btn-5" id="clearInputsButton" title="Clear all the Input values">
                            <i class="las la-undo-alt"></i> Clear Inputs
                        </button>
                    </div>
                    
                    <div class="live-preview ">
                        <form id="generateForm" action="{{ route('extract.image') }}" method="post" enctype="multipart/form-data" class="row g-3">
                            @csrf
                        
                          
                            <div class="col-md-6" id="select-language-tour">
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

                              <!-- Image Upload and Checkbox -->
                                <div class="col-md-6">

                                    <div class="form-group mt-2" id="image_upload">
                                        <input type="file" class="form-control" name="custom_image" id="custom_image" aria-label="Upload image" onchange="previewImage(event)">
                                    </div>

                                    <!-- Image Preview Section -->
                                    <div id="image_preview" style="display: none; margin-top: 10px;">
                                        <img id="preview_img" src="" alt="Image Preview" class="img-fluid rounded shadow-sm" style="max-width: 20%; height: auto;">
                                    </div>
                                </div>
                            



                                                                           
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
                            </div>

                            <div class="col-12">
                                <div class="text-end">
                                    <button id="generateButton" class="btn btn-rounded text-white gradient-btn-5 mx-1 mb-4" type="button">Extract Prompt</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Display the extracted content -->
                    <div id="extractedContent" style="margin-top: 20px;"></div>

                    <!-- Button to generate the image -->
                    <div id="imageGenerateSection" style="margin-top: 20px; display: none;">
                        <button id="generateImageButton" class="btn btn-rounded btn-success">Generate Image</button>
                    </div>

                    <!-- Container to display the generated image -->
                    <div id="generatedImage" style="margin-top: 20px;"></div>
                    
                    <!-- Custom Ratio Video -->
                    <div class="ratio" style="--vz-aspect-ratio: 50%;">
                        <iframe src="" title="YouTube video" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
           </div>

           <div class="col">
               <!-- Wrapper to place buttons side by side -->
                
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="card border border-primary">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Generated Content <button type="button" class="btn btn-outline-secondary">                            
                                    Tokens Left: <span class="badge gradient-background-2 ms-1" id="tokensLeft">{{ Auth::user()->tokens_left }}</span>
                            </button></h4>
                        
                            </div><!-- end card header -->

                            <div class="card-body" id="generated-content">
                                <!-- Rendered content for display -->
                                <div id="formattedContentDisplay" class="mt-3"></div>
                            
                            </div>
                            
                            
                            
                        </div><!-- end card -->
                        <h4> Read more details about <a href="" target="_blank" class="link gradient-text-2"> Click Here <i class=" ri-arrow-right-s-line"></i></a></h4>
                       
                    </div>
                    <!-- end col -->
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

<script>
    function disableInputs() {
        var creativeLevel = document.getElementById("creative_level").value;
        var temperatureInput = document.getElementById("temperature");
        var temperatureValueInput = document.getElementById("temperature_value");
        var topPInput = document.getElementById("top_p");
        var topPValueInput = document.getElementById("top_p_value");

        if (creativeLevel === "") {
            temperatureInput.disabled = false;
            temperatureValueInput.disabled = false;
            topPInput.disabled = false;
            topPValueInput.disabled = false;
        } else {
            temperatureInput.disabled = true;
            temperatureValueInput.disabled = true;
            topPInput.disabled = true;
            topPValueInput.disabled = true;
        }
    }
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
                        <img src="${response.image_url}" alt="Generated Image" class="img-fluid rounded shadow-sm" style="max-width: 100%; height: auto;">
                    `);
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

</script>

@endsection