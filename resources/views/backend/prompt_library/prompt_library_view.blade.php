@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
<style>
    .copy-icon {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #007bff;
    }
</style>
@endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Prompt Library @endslot
@slot('title') {{$prompt_library->prompt_name}} @endslot
@endcomponent

<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-body"> 
                <div class="live-preview">
                    <div class="col-md-12">
                        <label for="language" class="form-label">Prompt Name</label>
                        <p class="fw-medium link-primary">{{$prompt_library->prompt_name}}</p>
                    </div>
                </div>
            </div> 
        </div>
        <div class="card">
            <div class="card-body"> 
                <div class="live-preview">
                    <div class="col-md-12">
                        <label for="language" class="form-label">System Instruction</label>
                        <p class="fw-medium link-primary">
                            @if (is_null($prompt_library->sub_category_id))
                                --
                            @else
                            {{$prompt_library->subcategory->sub_category_instruction}}</p>
                            @endif
                    </div>
                </div>
            </div> 
        </div>
        <div class="card">
            <div class="card-body"> 
                <div class="live-preview">
                    <div class="col-md-12">
                        <label for="language" class="form-label">Category / Sub-Category Name</label>
                        <p class="fw-medium link-primary">{{$prompt_library->category->category_name}} /@if (is_null($prompt_library->sub_category_id))
                            --</p>
                        @else
                        {{$prompt_library->subcategory->sub_category_name}}</p>
                        @endif
                    </div>
                </div>
            </div> 
        </div>
        <div class="card">
            <div class="card-body"> 
                <div class="live-preview">
                    <div class="col-md-12">
                        <label for="language" class="form-label">Prompt Description</label>
                        <p class="fw-medium link-primary">{{$prompt_library->description}}</p>
                    </div>
                </div>
            </div> 
        </div>
        <div class="card">
            <div class="card-body"> 
                <div class="live-preview">
                    <div class="col-md-12">
                        <label for="language" class="form-label">Actual Prompt</label>
                        <p class="fw-medium link-primary" style="position: relative;">
                            {{$prompt_library->actual_prompt}}
                            <span class="copy-icon" onclick="copyText(this)">📋</span>
                        </p>
                    </div>
                </div>
            </div> 
        </div>

       
              <!-- Display Existing Examples -->
              <div class="card mt-3">
                <div class="card-body">
                    <div class="live-preview">
                        <div class="col-md-12">
                            <label for="existing-examples" class="form-label">Existing Examples</label>
                            <div id="existing-examples-container">
                                @foreach($prompt_library_examples as $item)
                                <div class="example-item mb-3">
                                    <div class="snow-editor" style="height: 200px;" id="exampleContent{{ $item->id }}">
                                        {!! $item->example !!}
                                    </div>
                                    <div class="mt-2">
                                        <!-- Delete Button -->
                                        <form method="POST" action="{{ route('prompt.example.delete', $item->id) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>
                                        </form>
                                        
                                        
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            

        @if (Auth::check() && Auth::user()->hasRole('admin'))
        <!-- Container for adding examples -->
        <div class="card mt-3">
            <div class="card-body">
                <form action="{{ route('prompt_examples.store', $prompt_library->id) }}" method="POST">
                    @csrf
                    <div class="live-preview">
                        <div class="col-md-12">
                            <label for="examples" class="form-label">Examples</label>
                            <div id="examples-container">
                                <!-- Example editor boxes will be added here -->
                            </div>
                            <button type="button" class="btn btn-primary mt-3" onclick="addExampleEditor()">Add Example</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3">Save Examples</button>
                </form>
            </div>
        </div>
        @endif
      
    </div>
    {{-- 2nd column --}}
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-body"> 
                <div class="live-preview">
                    <div class="row">
                        <label for="language" class="form-label">Ask AI</label>
                        <div class="col-md-9 mb-3"> 
                            <textarea class="form-control chat-input bg-light border-light auto-expand" id="ask_ai" rows="1" placeholder="Type your message..." autocomplete="off">{{$prompt_library->actual_prompt}}</textarea>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="ask" class="btn btn-primary"><span class="d-none d-sm-inline-block me-2">Ask</span> <i class="mdi mdi-send float-end"></i></button>
                        </div>
                        {{-- Loader --}}
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
                        {{-- Loader END --}}
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-9"> 
                            <textarea class="form-control chat-input bg-light border-light auto-expand" id="result1"  rows="3" placeholder="Your Result Here" autocomplete="off"></textarea>
                        </div>
                    </div> 
                </div>
            </div> 
        </div>
    </div>
</div>

@endsection

@section('script')


<script>
    document.addEventListener('DOMContentLoaded', function() {
        function copyText(element) {
            const textToCopy = element.parentElement.innerText.replace('📋', '').trim();
            const tempInput = document.createElement("textarea");
            tempInput.style = "position: absolute; left: -9999px";
            tempInput.value = textToCopy;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert("Text copied to clipboard");
        }

        $('#ask').click(function() {
            var message = $('#ask_ai').val();
            
        // Show loader
        $('#loader').removeClass('d-none');

            $.ajax({
                url: "{{ route('ask.ai.prompt') }}",
                type: "POST",
                data: {
                    message: message,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#result1').html(response.message);
                    
                // Hide loader
                $('#loader').addClass('d-none');

                },
                error: function(xhr) {
                    // Handle error
                }
            });
        });

        window.addExampleEditor = function() {
    const container = document.getElementById('examples-container');
    const exampleEditor = document.createElement('div');
    exampleEditor.className = 'form-group mt-3';
    exampleEditor.innerHTML = `
        <div class="snow-editor" style="height: 200px;"></div>
        <input type="hidden" name="examples[]">
        <button type="button" class="btn btn-danger mt-2" onclick="removeExampleEditor(this)">Remove</button>
    `;
    container.appendChild(exampleEditor);

    // Initialize Quill editor with default configuration
    const quill = new Quill(exampleEditor.querySelector('.snow-editor'), {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'font': [] }, { 'size': [] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'header': '1' }, { 'header': '2' }, 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }, { 'align': [] }],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });


    // Sync Quill content to the hidden input field
    quill.on('text-change', function() {
        const editorContent = exampleEditor.querySelector('.snow-editor').innerHTML;
        exampleEditor.querySelector('input[name="examples[]"]').value = editorContent;
    });

        };

        window.removeExampleEditor = function(button) {
    button.parentElement.remove();
};
    });
</script>

<script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
<script src="{{ URL::asset('build/libs/quill/quill.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
