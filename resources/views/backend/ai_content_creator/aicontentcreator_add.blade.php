@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('aicontentcreator.manage')}}">Content Creator Tools</a> @endslot
@slot('title') Add Content Creator Tools @endslot
@endcomponent

<div class="col-xxl-6">
    <form method="POST" action="{{route('aicontentcreator.store')}}" class="row g-3">
        @csrf
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Basic Information</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                
                    <div class="form-floating mb-3">
                        <input type="text" name="template_name" class="form-control" id="template_name" placeholder="Enter Content Creator Tool Name" required>
                        <label for="template_name" class="form-label">Content Creator Tool Name <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="icon" class="form-control" id="icon" placeholder="Enter Icon">
                        <label for="icon" class="form-label">Icon</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="category_id" id="category_id" aria-label="Floating label select example" required>
                            <option value="" disabled selected>Select Category</option>
                            @foreach ($categories as $item)
                                <option value="{{$item->id}}">{{$item->category_name}}</option>
                            @endforeach
                        </select>
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                    </div>
                  
                    <div class="form-floating mb-3" data-bs-toggle="tooltip" data-bs-placement="right" title="Give a short description of the Content Creator Tool Name">
                        <textarea name="description" class="form-control" id="description" rows="3" placeholder="Enter description" ></textarea>
                        <label for="description">Description</label>
                    </div>
               
            </div>
        </div>
    </div>

    {{-- 2nd Card --}}
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Input Information</h4>
            <a name="add" id="add" class="btn bg-gradient-dark mb-0"><i class="las la-plus" aria-hidden="true"></i>Add</a>

        </div><!-- end card header -->

        <div class="card-body custom-input-informations">
            <div class="live-preview">
                <div class="row">
                    <div class="col-md-3">
                        <label for="input_types" class="form-label">Input Type <span class="text-danger">*</span></label>
                        <select class="form-select" name="input_types[]" id="input_types" aria-label="Floating label select example" required>
                            <option value="" disabled selected>Select Input Type</option>
                            <option value="text">Input Field</option>
                            <option value="textarea">Textarea Field</option>
                            <option value="attachment">Attachment</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="input_names" class="form-label">Input Name <span class="text-danger">*</span></label>
                        <input type="text" name="input_names[]" placeholder="Type input name" onchange="generateInputNames(true)" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="input_label" class="form-label">Input Label <span class="text-danger">*</span></label>
                        <input type="text" name="input_labels[]" placeholder="Type input label" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="input_placeholders" class="form-label">Input Placeholder <span class="text-danger">*</span></label>
                        <input type="text" name="input_placeholders[]" placeholder="Type input placeholder" class="form-control" required>
                    </div>
                </div>
            
                <div id="template_info" class="input-informations">
                    <!-- Additional input fields will be appended here -->
                </div>
            </div>
        </div>
    </div>
    {{-- 2nd Card End --}}

    {{-- 3rd Card --}}
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Prompt Information</h4>
            <div class="mb-4 hint d-none">
                <label class="form-label">Input Variables</label>
                <div class="mb-1 input_names_prompts"></div>
                <small>*Click on variable to set the user input of it in your prompts</small>
            </div>
        </div><!-- end card header -->
        <div class="card-body">
            <div class="live-preview">
                <label for="custom_prompt" class="form-label">Custom Prompt <span class="text-danger">*</span></label>
                <div class="col-md-12">
                    <textarea class="form-control" name="prompt" id="VertimeassageInput" rows="3" placeholder="Enter your message" required></textarea>
                </div>
                
            </div>
        </div>
    </div>
    {{-- 3rd Card End --}}
    {{-- 4th Card --}}
    <div class="card border card-border-primary">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Links</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                    <div class="form-floating mb-3">
                        <input type="text" name="blog_link" class="form-control" id="blog_link" placeholder="Enter Link">
                        <label for="blog_link" class="form-label">Blog Redirection Link</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="video_link" class="form-control" id="video_link" placeholder="Enter Link">
                        <label for="video_link" class="form-label">Video Embedded Link</label>
                    </div>
            </div>
        </div>
    </div>

    {{-- 4th Card End --}}
    <div class="col-12">
        <div class="text-end">
            <input type="submit" class="btn btn-rounded gradient-btn-save mb-5" value="Save">
        </div>
    </div>
</form>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        var additionalInputs = `
            <div class="row">
                <div class="col-md-3">
                    <label for="input_types" class="form-label">Input Type</label>
                    <select class="form-select" name="input_types[]" id="input_types" aria-label="Floating label select example">
                        <option value="text">Input Field</option>
                        <option value="textarea">Textarea Field</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="input_names" class="form-label">Input Name</label>
                    <input type="text" name="input_names[]" onchange="generateInputNames(true)" placeholder="Type input name" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="input_label" class="form-label">Input Label</label>
                    <input type="text" name="input_labels[]" placeholder="Type input label" class="form-control" required>
                </div>
                <div class="col-md-3">
                        <label for="input_placeholders" class="form-label">Input Placeholder</label>
                        <input type="text" name="input_placeholders[]" placeholder="Type input placeholder" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-link px-0 fw-medium remove-row" onclick="removeRow(this)">
                        <div class="d-flex align-items-center">
                            <i data-feather="minus"></i>
                            <span>Remove</span>
                        </div>
                    </button>
                </div>
            </div>`;

            $("#add").click(function(){
		$("#template_info").append(additionalInputs);
		
	  });
        // Append the additional inputs to the target container
        // document.querySelector('.custom-input-informations').insertAdjacentHTML('beforeend', additionalInputs);

        // Move the "Add More" button to the end
        // document.getElementById('inputRow').appendChild(document.getElementById('inputrow'));
    });

function removeRow(button) {
    // Find the parent row and remove it
    var row = $(button).closest('.row');
    row.remove();
}

// TEST
function generateInputNames() {
    // Clear previous content
    $('.input_names_prompts').empty();

    // Loop through each input name and append it to the display div
    $('input[name="input_names[]"]').each(function() {
        var inputName = $(this).val();
        if (inputName.trim() !== "") {
            var span = $('<span class="badge bg-info"> </span>');
            span.text(inputName);
            span.click(function() {
                appendToPrompt(inputName);
            });
            $('.input_names_prompts').append(span);
        }
    });

    // Show the hint if there are input names, hide it otherwise
    var hintDiv = $('.hint');
    if ($('.input_names_prompts').children().length > 0) {
        hintDiv.removeClass('d-none');
    } else {
        hintDiv.addClass('d-none');
    }
}

function appendToPrompt(inputName) {
    var promptTextarea = $('#VertimeassageInput');
    var promptText = promptTextarea.val().trim();
    if (promptText !== "") {
        promptText += " {" + inputName + "}";
    } else {
        promptText = "{" + inputName + "}";
    }
    promptTextarea.val(promptText);
}
</script>




@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection