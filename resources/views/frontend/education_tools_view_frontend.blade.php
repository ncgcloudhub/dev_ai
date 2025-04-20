@extends('admin.layouts.master-without-nav')
@section('title', $tool->name)

@section('description', $tool->description)

@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ URL::asset('build/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('body')

<style>
    .breadcrumb-title {
    font-weight: bold;
    font-size: 1.5rem; /* Adjust the size as needed */
}

</style>

    <body data-bs-spy="scroll" data-bs-target="#navbar-example">
    @endsection
    @section('content')
        <!-- Begin page -->
        <div class="layout-wrapper landing">
           @include('frontend.body.nav_frontend')

<br><br>
<br><br>
<div class="container">
    <div class="row justify-content-center"> 
        <div class="row">

             <!-- Token Information -->
             <div class="col-xxl-12 mb-3">
                <div class="alert gradient-background-3 text-center">
                    <strong>You have <span id="tokenCount"></span> free tokens left.</strong> Experience the power of AI with features like image generation, ready-made templates, chat assistants, and more. Remember, these tokens are limited, so <strong><a href="{{ route('register') }}"><u>Sign up now for FREE</u></a></strong>
                    to unlock your creativity!
                    <br>
                </div>
            </div>

            <div class="col-xxl-12">
                <div class="card border-color-purple">
                    <div class="card-body">
                        <div class="row">
                            <div class="col"> 
                                <h3 class="mb-1 gradient-text-1-bold text-uppercase">{{$tool->name}}</h3>
                                <p class="gradient-text-2">{{$tool->description}}</p></div>
                            <div class="col">  
                                <div class="d-flex justify-content-end gap-2">
        
                                    <button type="button" class="btn gradient-btn-5" id="clearInputsButton" title="Clear all the Input values">
                                    <i class="las la-undo-alt"></i>Clear Inputs
                                    </button>
                                    
                                    {{-- <button type="button" class="btn gradient-btn-5" id="populateInputsButton" title="Populate inputs with placeholder values">
                                    <i class="las la-magic"></i>Populate Inputs
                                    </button> --}}
                            </div></div>
                            </div>
                       
                      <div class="row">
                        <div class="col-10 bg-marketplace">
                            <form action="" method="POST" id="generate-content-form">
                                @csrf
                                <input type="hidden" name="tool_id" value="{{ $tool->id }}">
                            
                                <!-- Grade/Class Select Field -->
                                <div class="form-group mb-3">
                                    <select class="form-select" name="grade_id" aria-label="Default select grade" required>
                                        <option selected disabled value="">Select Grade/Class</option>
                                        @foreach($classes as $item)
                                            <option value="{{ $item->id }}">{{ $item->grade }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a valid grade/class.</div>
                                </div>
                            
                                <!-- Dynamic Input Fields -->
                                @foreach (json_decode($tool->input_types) as $index => $input_type)
                                    <div class="form-group mb-3">
                                        <label for="input_{{ $index }}">{{ json_decode($tool->input_labels)[$index] }}</label>
                            
                                        @if ($input_type == 'textarea')
                                            <textarea class="form-control" id="input_{{ $index }}" name="input_{{ $index }}" rows="4" 
                                                placeholder="{{ json_decode($tool->input_placeholders)[$index] }}" required></textarea>
                                                <button type="button" class="speech-btn btn btn-link">
                                                    <i class="mic-icon ri-mic-line fs-4"></i>
                                                </button>
                                        @else
                                            <input type="{{ $input_type }}" class="form-control" id="input_{{ $index }}" name="input_{{ $index }}" 
                                                placeholder="{{ json_decode($tool->input_placeholders)[$index] }}" required>
                                            <button type="button" class="speech-btn btn btn-link">
                                                <i class="mic-icon ri-mic-line fs-4"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            
                                <!-- Submit Button -->
                                <button type="submit" class="btn gradient-btn-5 disabled-on-load" id="educationToolsGenerate" disabled>
                                    <i class="ri-auction-fill align-bottom me-1"></i>Generate
                                </button>
                            </form>
                            
                        </div>
                        <div class="col-2">
                            <img src="/build/images/nft/edu_01.png" alt="" class="img-fluid">
                        </div>
                      </div>
                       
                    </div>
                </div>
                <!--end card-->
                <div class="card">
                    <div class="card-header">
                        <div>
                            <ul class="nav nav-pills nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#stream-output" role="tab">
                                        Output
                                    </a>
                                </li>
                             
                            </ul>
                            <!--end nav-->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="stream-output" role="tabpanel">
                                
                            </div>
                        </div>
                        <!--end tab-content-->
                    </div>
                </div>
                <!--end card-->
            </div>
        </div>
    </div>
</div>

            <!-- Start footer -->
            @include('frontend.body.footer_frontend')
            <!-- end footer -->

            <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

        </div>
        <!-- end layout wrapper -->
    @endsection


@section('script')

        <script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>

        <script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
        <script src="{{ URL::asset('build/libs/quill/quill.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script>

        {{-- Submit Form Editor --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function () {
            $('form').on('submit', function (e) {
                e.preventDefault(); // Prevent the form from submitting the traditional way
        
                showMagicBall('Facts', 'education');

        
                let formData = $(this).serialize(); // Collect the form data
        
                $.ajax({
                    type: 'POST',
                    url: '{{ route("frontend.free.education.generate") }}', // Update with your route
                    data: formData,
                    xhrFields: {
                        onprogress: function (event) {
                            const contentChunk = event.currentTarget.responseText;
                            $('#stream-output').html(contentChunk); // Update the stream output div
                            hideMagicBall();
                        }
                    },
                   
        
                    success: function (response) {
                        console.log('Content generation completed.');
                    },
                    error: function (error) {
                        hideMagicBall();
                        console.error('Error during content generation:', error);
                    }
                });
            });
        });
        
        
        
        // Open modal and populate with content data
        function openToolContentEditorModal(contentId) {
            fetch(`/education/toolContent/${contentId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    document.getElementById('editContent').value = data.content;
                    document.getElementById('editToolContentModal').setAttribute('data-content-id', contentId);
        
                    // Show the modal
                    var editModal = new bootstrap.Modal(document.getElementById('editToolContentModal'));
                    editModal.show();
                })
                .catch(error => console.error('Error:', error));
        }
        
        
        // Save edited content via AJAX
        function saveEditedToolContent() {
            const contentId = document.getElementById('editToolContentModal').getAttribute('data-content-id');
            const updatedContent = {
                content: document.getElementById('editContent').value
            };
        
            fetch(`/education/toolContent/${contentId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(updatedContent)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Content updated successfully!');
                    var editModal = bootstrap.Modal.getInstance(document.getElementById('editToolContentModal'));
                    editModal.hide();
                    // Optionally, update the UI with the new content
                } else {
                    alert('Failed to update content.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        // Clear all input values
        document.getElementById('clearInputsButton').addEventListener('click', function() {
                // Reset the form (clear inputs)
                document.getElementById('generate-content-form').reset();
         });
        
        
        </script>
@endsection