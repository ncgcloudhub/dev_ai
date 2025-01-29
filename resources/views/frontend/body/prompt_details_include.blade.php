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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

<section class="section pb-0 hero-section" id="hero">
    <div class="container">

         <!-- Token Information -->
         <div class="col-xxl-12 mb-3">
            <div class="alert gradient-background-3 text-center">
                <strong>You have <span id="tokenCount"></span> free tokens left.</strong> Experience the power of AI with features like image generation, ready-made templates, chat assistants, and more. Remember, these tokens are limited, so <strong><a href="{{ route('register') }}"><u>Sign up now for FREE</u></a></strong>
                to unlock your creativity!
                <br>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div class="col-xxl-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active breadcrumb-title" aria-current="page"><u>{{$prompt_library->prompt_name}}</u></li>
                </ol>
            </nav>
        </div>

<div class="row">
    <div class="col-xxl-6">
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
                            <button type="button" class="btn btn-primary mt-3" onclick="addExampleEditor()">
                                <i class="la la-plus"></i>
                              </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3">
                        <i class="la la-save"></i>
                      </button>
                </form>
            </div>
        </div>
        @endif
      
    </div>
    {{-- 2nd column --}}
    <div class="col-xxl-6">
        <div class="card border card-border-primary">
            <div class="card-body"> 
                <div class="live-preview">
                    <div class="row">
                        <label for="language" class="form-label">Ask AI</label>
                        <div class="col-md-9 mb-3">
                            <textarea class="form-control chat-input bg-light border-light auto-expand" id="ask_ai" rows="1" placeholder="Type your message..." autocomplete="off">{{$prompt_library->actual_prompt}}</textarea>
                        </div>
                        <input type="hidden" id="sub_category_instruction" value="{{$prompt_library->subcategory->sub_category_instruction}}">

                        <div class="col-md-3">
                            <button type="button" id="ask" class="btn gradient-btn-5"><span class="d-none d-sm-inline-block me-2">Ask</span> <i class="mdi mdi-send float-end"></i></button>
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
                            <textarea id="myeditorinstance" readonly></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-end" id="copy-download-tour">
                            <button id="copyButton" class="btn gradient-btn-9 me-2">
                                <i class="las la-copy"></i>
                            </button>
                            <button id="downloadButton" class="btn gradient-btn-9">
                                <i class="las la-download"></i>
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div> 
        </div>
    </div>
    
    
    
</div>
</div>

@section('script')

<!-- Include SimpleMDE JS -->
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<!-- Include FileSaver.js for saving files -->
<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        let remainingTokens = parseInt(localStorage.getItem('remainingTokens')) || 2000;
        $('#tokenCount').text(remainingTokens); // Update token count display

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
        alert("Text copied to clipboard");
    };

    var simplemde = new SimpleMDE({ 
        element: document.getElementById("myeditorinstance"),
        spellChecker: false,
        toolbar: false,
        status: false,
        readOnly: true
    });

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
        $('#loader').removeClass('d-none');

        let userSignedIn = {{ Auth::check() ? 'true' : 'false' }};
        let tokensUsedToday = parseInt(localStorage.getItem('tokensUsedToday')) || 0;

        if (!userSignedIn && tokensUsedToday >= 2000) {
            $('#loader').addClass('d-none');
            alert('You have reached the daily limit of 100 tokens. Please try again tomorrow.');
            return;
        }

        $.ajax({
            url: "{{ route('ask.ai.prompt') }}",
            type: "POST",
            data: {
                message: message,
                sub_category_instruction: sub_category_instruction,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {

                remainingTokens -= response.completionTokens;
                localStorage.setItem('remainingTokens', remainingTokens);

                 // Update token count display
                 $('#tokenCount').text(remainingTokens);
                 
                var strippedContent = response.message
                    .replace(/[#*]+/g, '')  // Remove # and * characters
                    .replace(/(!\[.*?\]\(.*?\))/g, ''); // Remove markdown images

                setTimeout(function() {
                    simplemde.value(strippedContent);
                    simplemde.codemirror.refresh(); // Force update
                    $('#loader').addClass('d-none');
                }, 100); // Add a delay before setting the content

                if (!userSignedIn) {
                    tokensUsedToday += response.completionTokens; // Adjust if response.completionTokens is provided
                    localStorage.setItem('tokensUsedToday', tokensUsedToday);
                }
            },
            error: function(xhr) {
                // Hide the spinner
                $('#loader').addClass('d-none');
                console.error(xhr.responseText);
            }
        });
    });

    // Reset the token count at midnight
    function resetTokenCount() {
        let now = new Date();
        let millisTillMidnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 0, 0) - now;
        setTimeout(function () {
            localStorage.setItem('tokensUsedToday', '0');
            resetTokenCount();
        }, millisTillMidnight);
    }

    resetTokenCount();


    window.addExampleEditor = function() {
        const container = document.getElementById('examples-container');
        const exampleEditor = document.createElement('div');
        exampleEditor.className = 'form-group mt-3';
        exampleEditor.innerHTML = `
            <div class="snow-editor" style="height: 200px;"></div>
            <input type="hidden" name="examples[]">
            <button type="button" class="btn btn-danger mt-2" onclick="removeExampleEditor(this)">
                <i class="la la-minus"></i>
            </button>

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

        window.removeExampleEditor = function(button) {
        button.parentElement.remove();
    };
    };
    

    $('#copyButton').click(function () {
        const editorContent = simplemde.value();
        const textArea = document.createElement('textarea');
        textArea.value = editorContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        toastr.success('Content copied to clipboard!');
    });

    $('#downloadButton').click(function () {
        const editorContent = simplemde.value();
        const blob = new Blob([editorContent], { type: 'application/msword' });
        saveAs(blob, 'generated_content.doc');
    });
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
</section>