@extends('admin.layouts.master')
@section('title') Prompt View | {!! $prompt_library->prompt_name !!}@endsection
@section('css')
<link href="{{ URL::asset('build/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

<style>
    .copy-icon {
        position: absolute;
        right: 5%;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #ff0077;
        font-size: 1.5rem; /* Increased size */
        transition: transform 0.2s ease, color 0.2s ease;
    }
    
    .copy-icon:hover {
        transform: translateY(-50%) scale(1.2); /* Adds a zoom effect on hover */
        color: #ff3399; /* Slightly lighter shade on hover */
    }
</style>

@endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('prompt.manage')}}">Prompt Library</a> @endslot
@slot('title') {{$prompt_library->prompt_name}} @endslot
@endcomponent

<div class="row">
    <div class="col-xxl-6">
        <button id="promptLibraryDetailsTourButton" class="btn gradient-btn-tour text-white mb-3">Tour</button>

        <div class="card">
            <div class="card-body"> 
                
                <div class="live-preview">
                    <div class="col-md-12">
                        <label for="language" class="form-label">Prompt Name</label>
                        <p class="fw-medium link-primary gradient-text-2">{{$prompt_library->prompt_name}}</p>
                    </div>
                </div>
            </div> 
        </div>
        <div class="card">
            <div class="card-body"> 
                <div class="live-preview">
                    <div class="col-md-12">
                        <label for="language" class="form-label">System Instruction</label>
                        <p class="fw-medium link-primary gradient-text-2">
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
                        <p class="fw-medium link-primary gradient-text-2">{{$prompt_library->category->category_name}} /@if (is_null($prompt_library->sub_category_id))
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
                        <p class="fw-medium link-primary gradient-text-2">{{$prompt_library->description}}</p>
                    </div>
                </div>
            </div> 
        </div>
        <div class="card">
            <div class="card-body"> 
                <div class="live-preview">
                    <div class="col-md-12">
                        <label for="language" class="form-label">Actual Prompt</label>
                        <p class="fw-medium link-primary gradient-text-2" style="position: relative;">
                            {{$prompt_library->actual_prompt}}
                          
                        </p>
                        <span class="copy-icon copy-toast-btn" onclick="copyText(this)" title="Copy"><i class=" ri-file-copy-2-fill"></i></span>
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
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-primary btn-icon waves-effect waves-light" onclick="editExample({{ $item->id }})"><i class="ri-edit-line"></i></button>
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


   <!-- Edit Example Modal -->
<div class="modal fade" id="editExampleModal" tabindex="-1" aria-labelledby="editExampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editExampleForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExampleModalLabel">Edit Example</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="example-editor" class="form-label">Example Content</label>
                        <div id="example-editor" style="height: 200px;"></div>
                        <input type="hidden" name="example" id="example-content">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
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
                            <div class="d-flex gap-2 mt-3">
                                <button type="button" class="btn gradient-btn-add" title="Add Example" onclick="addExampleEditor()">
                                    <i class="la la-plus"></i> Add
                                </button>
                                <button id="remove-btn" type="button" class="btn gradient-btn-remove d-none" title="Remove Example" onclick="removeLastExampleEditor()">
                                    <i class="la la-minus"></i> Remove
                                </button>
                                <button type="submit" title="Save Example" class="btn gradient-btn-save">
                                    <i class="la la-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
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
                        <div class="col-md-9 mb-3" id="search-box-tour">
                            <textarea class="form-control chat-input bg-light border-light auto-expand" id="ask_ai" rows="1" placeholder="Type your message..." autocomplete="off">{{$prompt_library->actual_prompt}}</textarea>
                        </div>
                        <input type="hidden" id="sub_category_instruction" value="{{$prompt_library->subcategory->sub_category_instruction}}">
                        <input type="hidden" id="prompt_name" value="{{$prompt_library->prompt_name}}"> <!-- Added hidden input -->

                        <div class="col-md-3" id="generate-button-tour">
                            <button type="button" id="ask" class="btn gradient-btn-generate disabled-on-load" disabled><span class="d-none d-sm-inline-block me-2">Ask</span> <i class="mdi mdi-send float-end"></i></button>
                        </div>
                        {{-- Loader --}}
                        <div class="hstack flex-wrap gap-2 mb-3 mb-lg-0 d-none" id="loader">
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
                        <div class="col-md-12">
                            <!-- Rendered content for display -->
                            <div id="formattedContentDisplay" class="p-3 border rounded" style="min-height: 200px;">
                                <!-- Content will be injected here -->
                            </div>
                            
                            <!-- Optional Separator -->
                            <hr class="my-4">
                            
                            <!-- Example of statistics or other content below -->
                            <div id="additionalContent">
                                <!-- Add any additional content or statistics here -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12 text-end" id="copy-download-tour">
                            <button id="copyButton" class="btn gradient-btn-copy me-2 copy-toast-btn">
                                <i class="las la-copy"></i>
                            </button>
                            <button id="downloadButton" class="btn gradient-btn-download">
                                <i class="las la-download"></i>
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div> 
        </div>
    </div>
    
    
    
</div>

@endsection

@section('script')

<!-- Include Marked.js for parsing markdown -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<!-- Include DOMPurify for sanitizing HTML -->
<script src="https://cdn.jsdelivr.net/npm/dompurify@2.3.4/dist/purify.min.js"></script>
<!-- Include FileSaver.js for saving files -->
<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Function to copy text to clipboard
        window.copyText = function(element) {
            const textToCopy = element.parentElement.innerText.replace('📋', '').trim();
            const tempInput = document.createElement("textarea");
            tempInput.style = "position: absolute; left: -9999px";
            tempInput.value = textToCopy;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            // alert("Text copied to clipboard");
        };

        // Remove SimpleMDE initialization since we're using a div now

        // Function to auto-expand textareas (if still needed for other textareas)
        function autoExpand(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }

        document.querySelectorAll('.auto-expand').forEach(function(textarea) {
            textarea.addEventListener('input', function() {
                autoExpand(textarea);
            });
            autoExpand(textarea);
        });

        $('#ask').click(function() {
            var message = $('#ask_ai').val();
            var sub_category_instruction = $('#sub_category_instruction').val();
            var prompt_name = $('#prompt_name').val(); // Get the prompt name value
            $('#loader').removeClass('d-none');
            showMagicBall('facts', 'general');


            $.ajax({
                url: "{{ route('ask.ai.prompt') }}",
                type: "POST",
                data: {
                    message: message,
                    sub_category_instruction: sub_category_instruction,
                    prompt_name: prompt_name, // Pass prompt name to controller
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    hideMagicBall();

                    if (response == 0) {
                        alert('Please Upgrade Plan');
                        $('#loader').addClass('d-none');
                        return;
                    }

                    // Use marked.js to convert Markdown to HTML
                    let formattedContent = formatContent(response.message);

                    // Set the HTML content in the div
                    document.getElementById('formattedContentDisplay').innerHTML = formattedContent;

                    // Hide loader
                    $('#loader').addClass('d-none');
                },
                error: function(xhr) {
                    hideMagicBall();

                    console.error(xhr.responseText);
                    $('#loader').addClass('d-none');
                }
            });
        });


        function formatContent(content) {
        // Set options for marked.js
        marked.setOptions({
            breaks: true,  // Enable line breaks
            gfm: true      // Enable GitHub Flavored Markdown
        });

        // Parse Markdown to HTML without a custom renderer
        let formattedContent = marked.parse(content);

        // Sanitize the HTML to prevent XSS
        formattedContent = DOMPurify.sanitize(formattedContent);

        return formattedContent;
    }

        // Copy button click event
        $('#copyButton').click(function () {
            const editorContent = document.getElementById('formattedContentDisplay').innerText;
            const textArea = document.createElement('textarea');
            textArea.value = editorContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            // toastr.success('Content copied to clipboard!');
        });

        // Download button click event using FileSaver.js
        $('#downloadButton').click(function () {
            const editorContent = document.getElementById('formattedContentDisplay').innerText;
            const blob = new Blob([editorContent], { type: 'application/msword' });
            saveAs(blob, 'generated_content.doc');
        });

        // Function to add example editors with Quill (unchanged)
        window.addExampleEditor = function() {
        const container = document.getElementById('examples-container');
        const exampleEditor = document.createElement('div');
        exampleEditor.className = 'form-group mt-3';
        exampleEditor.innerHTML = `
            <div class="snow-editor" style="height: 200px;"></div>
            <input type="hidden" name="examples[]">
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

        // Show the Remove button when an editor is added
        document.getElementById('remove-btn').classList.remove('d-none');
    };

    window.removeLastExampleEditor = function() {
        const container = document.getElementById('examples-container');
        if (container.lastElementChild) {
            container.lastElementChild.remove();
        }
        // Hide the Remove button if no editors are left
        if (container.children.length === 0) {
            document.getElementById('remove-btn').classList.add('d-none');
        }
    };

    });
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    @foreach($prompt_library_examples as $item)
    var quill{{ $item->id }} = new Quill('#exampleContent{{ $item->id }}', {
        readOnly: true,
        theme: 'snow',
        modules: {
            toolbar: false // Disable toolbar for read-only mode
        }
    });

    // Set the editor content
    var content = {!! json_encode($item->example) !!};
    quill{{ $item->id }}.clipboard.dangerouslyPasteHTML(content);
    @endforeach
});

</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    @foreach($prompt_library_examples as $item)
    var quill{{ $item->id }} = new Quill('#exampleContent{{ $item->id }}', {
        readOnly: true,
        theme: 'snow',
        modules: {
            toolbar: false // Disable toolbar for read-only mode
        }
    });

    // Set the editor content
    var content = {!! json_encode($item->example) !!};
    quill{{ $item->id }}.clipboard.dangerouslyPasteHTML(content);
    @endforeach
});

function editExample(exampleId) {
    var quill = new Quill('#example-editor', {
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
    var exampleContent = document.getElementById(`exampleContent${exampleId}`).innerHTML;
    quill.clipboard.dangerouslyPasteHTML(exampleContent);

    var form = document.getElementById('editExampleForm');
    form.action = `/prompt-examples/${exampleId}`;

    var modal = new bootstrap.Modal(document.getElementById('editExampleModal'));
    modal.show();

    quill.on('text-change', function() {
        document.getElementById('example-content').value = quill.root.innerHTML;
    });
}

</script>
    

<script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
<script src="{{ URL::asset('build/libs/quill/quill.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
