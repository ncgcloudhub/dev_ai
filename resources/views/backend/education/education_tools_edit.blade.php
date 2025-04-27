@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('manage.education.tools')}}">Education Tools</a> @endslot
@slot('title') Edit Tools | {{$tool->name}} @endslot
@endcomponent



<div class="row">
    <div class="col-xxl-8"> 
        {{-- <a href="{{ route('manage.education.tools') }}">manage</a> --}}
        <form method="POST" action="{{ route('tools.update', $tool->id) }}" class="row g-3" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Basic Information</h4>
                </div>
        
                <div class="card-body">
                    <div class="live-preview">
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter Template Name" value="{{ $tool->name }}" required>
                            <label for="name" class="form-label">Tools Name <span class="text-danger">*</span></label>
                        </div>
        
                        <div class="form-floating mb-3">
                            <select class="form-select" name="category_id" id="category_id" aria-label="Floating label select example">
                                <option value="{{$tool->category_id}}" selected="">{{$tool->educationtools_category->category_name}}</option>
                                @foreach ($categories as $item)
                                <option value="{{$item->id}}">{{$item->category_name}}</option>
                                @endforeach
                            </select>
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        </div>
        
                        <div class="form-floating mb-3">
                            <textarea name="description" class="form-control" id="description" rows="3" placeholder="Enter description" required>{{ $tool->description }}</textarea>
                            <label for="description">Description <span class="text-danger">*</span></label>
                        </div>
        
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" name="image" class="form-control" id="image" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
        
            {{-- 2nd Card for Input Information --}}
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Input Information</h4>
                </div>
                <div class="card-body custom-input-informations">
                    <div class="live-preview">
                        <div class="row">
                            <!-- Loop through input types, names, labels, placeholders -->
                            @foreach (json_decode($tool->input_types) as $key => $type)
                            <div class="row input-row">
                                <div class="col-md-3">
                                    <label for="input_types" class="form-label">Input Type <span class="text-danger">*</span></label>
                                    <select class="form-select" name="input_types[]" aria-label="Floating label select example" required>
                                        <option value="text" {{ $type == 'text' ? 'selected' : '' }}>Input Field</option>
                                        <option value="textarea" {{ $type == 'textarea' ? 'selected' : '' }}>Textarea Field</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="input_names" class="form-label">Input Name <span class="text-danger">*</span></label>
                                    <input type="text" name="input_names[]" placeholder="Type input name" onchange="generateInputNames(true)" class="form-control" value="{{ json_decode($tool->input_names)[$key] }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="input_labels" class="form-label">Input Label <span class="text-danger">*</span></label>
                                    <input type="text" name="input_labels[]" placeholder="Type input label" class="form-control" value="{{ json_decode($tool->input_labels)[$key] }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="input_placeholders" class="form-label">Input Placeholder <span class="text-danger">*</span></label>
                                    <input type="text" name="input_placeholders[]" placeholder="Type input placeholder" class="form-control" value="{{ json_decode($tool->input_placeholders)[$key] }}" required>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-link px-0 fw-medium remove-row" onclick="removeRow(this)">
                                        <div class="d-flex align-items-center">
                                            <i data-feather="minus"></i>
                                            <span>Remove</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    
                        <a name="add" id="add" class="btn bg-gradient-dark mb-0"><i class="las la-plus" aria-hidden="true"></i>Add</a>
                        <div id="template_info" class="input-informations">
                            <!-- Additional input fields will be appended here -->
                        </div>
                    </div>
                </div>
            </div>
        
            {{-- 3rd Card for Prompt Information --}}
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Prompt Information</h4>
                    <div class="mb-4 hint d-none">
                        <label class="form-label">Input Variables</label>
                        <div class="mb-1 input_names_prompts"></div>
                        <small>*Click on variable to set the user input of it in your prompts</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="live-preview">
                        <label for="custom_prompt" class="form-label">Custom Prompt <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <textarea class="form-control" name="prompt" id="VertimeassageInput" rows="3" placeholder="Enter your message" required>{{ $tool->prompt }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        
            <label for="generate_questions">
                <input type="checkbox" id="popular" name="popular" value="1" {{ $tool->popular ? 'checked' : '' }}> 
                Popular
            </label>
    
            
            <div class="col-12">
                <div class="text-end">
                    <button type="submit" class="btn btn-rounded gradient-btn-save mb-5" title="Update">
                        <i class="{{$buttonIcons['save']}}"></i>
                    </button>
                    
                </div>
            </div>
        </form>
        
    </div>
    
    <div class="col-xxl-4">
        <div class="card">
            <form method="POST" action="{{ route('edu.tools.seo.update') }}" class="row g-3">
                @csrf
                <input type="hidden" name="id" value="{{ $tool->id }}">
                <input type="hidden" id="modelType" value="education">
            <!-- Page Title -->
                <div class="col-12">
                    <label for="page_title" class="form-label">Page Title</label>
                    <input type="text" class="form-control" id="page_title" name="page_title" value="{{ $tool->page_title ?? '' }}" placeholder="Enter Page Title">
                </div>
            
                <!-- Page Description -->
                <div class="col-12">
                    <label for="page_description" class="form-label">Page Description</label>
                    <textarea class="form-control" id="page_description" name="page_description" rows="4" placeholder="Enter Page Description">{{ $tool->page_description ?? '' }}</textarea>
                </div>
            
                <!-- Page Tagging -->
                <div class="col-12">
                    <label for="page_tagging" class="form-label">Page Tagging</label>
                    <textarea class="form-control" id="page_tagging" name="page_tagging" rows="3" placeholder="Enter Page Tags">{{ $tool->page_tagging ?? '' }}</textarea>
                </div>
            
                <div class="col-12">
                    <div class="text-end">
                        <button type="button" class="btn btn-rounded gradient-btn-generate mb-5" id="populateBtn" title="Generate SEO Using AI"><i class="{{$buttonIcons['generate']}}"></i></button>
            
                        <button type="submit" class="btn btn-rounded gradient-btn-save mb-5" title="Update">
                            <i class="{{$buttonIcons['save']}}"></i>
                        </button>
                        
                    </div>
                </div>
            </form>
        </div>
    
    </div>
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



{{-- needs to be in master(vendor scripts as redundant) --}}

// SEO with AI
$(document).ready(function() {
    $('#populateBtn').on('click', function() {
        let modelType = $('#modelType').val(); // Dynamically get model type
        let recordId = $('input[name="id"]').val(); 

        $.ajax({
            url: '/ai-content-creator/seo/fetch/' + recordId + '/' + modelType,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                // Populate the form fields with the data from the response
                $('#page_title').val(response.seo_title);
                $('#page_description').val(response.seo_description);
                $('#page_tagging').val(response.seo_tags);
            } else {
                alert(response.message);
            }
            },
            error: function() {
                alert('Error fetching the template details.');
            }
        });
    });
});
</script>




@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection