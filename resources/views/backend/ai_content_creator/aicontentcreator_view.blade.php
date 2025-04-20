@extends('admin.layouts.master')
@section('title') {{$Template->template_name}} @endsection
@section('css')
<link href="{{ URL::asset('build/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('aicontentcreator.manage')}}">Content Creator Tools</a> @endslot
@slot('title') {{$Template->template_name}} @endslot
@endcomponent


<button type="button" class="btn gradient-btn-cancel" onclick="history.back()">
    <i class="las la-arrow-left"></i>
</button>

<button id="templateDetailsTourButton" class="btn gradient-btn-tour text-white my-2" title="Get a Tour of this page to know it better">Content Creator Tools Tour</button>

<div class="row">
   
           <div class="col-xxl-6">
            <div class="card">
               
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn gradient-btn-cancel" id="clearInputsButton" title="Clear all the Input values">
                            <i class="las la-undo-alt"></i> Clear Inputs
                        </button>
                        {{-- <button type="button" class="btn gradient-btn-5" id="populateInputsButton" title="Populate inputs with placeholder values">
                            <i class="las la-magic"></i> Populate Inputs
                        </button> --}}
                    </div>
                    
                    <div class="live-preview ">
                        <form id="generateForm"  action="{{route ('aicontentcreator.generate')}}" method="post" class="row g-3">
                            @csrf
                            <input type="hidden" name="template_id" value="{{ $Template->id }}">


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

                            <div class="col-md-6">
                                <label for="points" class="form-label">Number of Variations</label>
                                <select class="form-select" name="points" id="points" aria-label="Floating label select example">
                                    <option disabled>Enter Variations</option>
                                    <option value="1" selected>1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                            
                            @isset($inputTypes)
                                @foreach($inputTypes as $key => $type)
                                    <div class="col-md-12" @if ($loop->first) id="content-tour" @endif>
                                        <label for="{{ $inputNames[$key] }}" class="form-label">{{ $inputLabels[$key] }}</label>
                                        
                                        @if($type == 'text')
                                            <!-- Input Field -->
                                            <div class="position-relative">
                                                <input type="text" name="{{ $inputNames[$key] }}" class="form-control pe-5" id="{{ $inputNames[$key] }}" 
                                                    placeholder="{{ $inputPlaceholders[$key] ?? $inputLabels[$key] }}" required>
                                                <button type="button" class="speech-btn btn btn-link position-absolute top-50 end-0 translate-middle-y">
                                                    <i class="mic-icon ri-mic-line fs-4"></i>
                                                </button>
                                            </div>
                                            
                                        @elseif($type == 'textarea')
                                            <!-- Textarea Field -->
                                            <textarea class="form-control" name="{{ $inputNames[$key] }}" id="{{ $inputNames[$key] }}" placeholder="{{ $inputPlaceholders[$key] ?? $inputLabels[$key] }}" rows="3" required></textarea>
                                            <button type="button" class="speech-btn btn btn-link position-absolute top-50 end-0 translate-middle-y">
                                                <i class="mic-icon ri-mic-line fs-4"></i>
                                            </button>
                                        @elseif($type == 'attachment')
                                            <!-- File Input Field -->
                                            <input type="file" name="attachment" class="form-control" id="{{ $inputNames[$key] }}" required accept=".pdf,.doc,.docx,.txt" onchange="validateFileType(this)">
                                            <div id="file-error" style="color: red; display: none;">Only PDF, DOC, DOCX, and TXT files are allowed.</div>
                                        @elseif($type == 'select')
                                            <!-- Select Field -->
                                            <select class="form-select" name="{{ $inputNames[$key] }}" id="{{ $inputNames[$key] }}" required>
                                                @foreach(explode(',', $inputOptions[$key]) as $option)
                                                    <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                        
                                    </div>

                                    <div hidden class="col-md-12" data-prompt="{{ $Template->prompt }}">
                                        <textarea class="form-control" name="prompt" id="VertimeassageInput" rows="3" placeholder="Enter your message">{{ $Template->prompt }}</textarea>
                                    </div>
                                @endforeach
                            @endisset
                                                

                            <!-- Use Emoji -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="emoji" value="1" id="SwitchCheck1">
                                <label class="form-check-label" for="SwitchCheck1" title="">Use Emoji</label>
                            </div>
                            {{-- End Use Emoji --}}

                            <!-- Accordion Flush Example -->
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button collapsed gradient-background-5" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                            Advance Settings
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                                        data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">

            
                                            <div class="col-md-12">
                                                <label for="max_result_length" class="form-label">Max Result Length</label>
                                                <input type="range" name="max_result_length" class="form-range" id="max_result_length" min="3900" max="8000" step="10" value="3900">
                                                <input type="number" name="max_result_length_value" class="form-control" id="max_result_length_value" min="3900" max="8000" step="10" value="3900">
                                                
                                            </div>
                

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="creative_level" class="form-label">Creative Level</label>
                                                    <select class="form-select" name="creative_level" id="creative_level" aria-label="Floating label select example" onchange="disableInputs()">
                                                        <option disabled selected="">No Creativity Level</option>
                                                        <option value="High">High</option>
                                                        <option value="Medium">Medium</option>
                                                        <option value="Low">Low</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="tone" class="form-label">Choose a Tone</label>
                                                    <select class="form-select" name="tone" id="tone" aria-label="Floating label select example">
                                                        <option disabled selected="">Enter Tone</option>
                                                        <option value="Friendly">Friendly</option>
                                                        <option value="Luxury">Luxury</option>
                                                        <option value="Relaxed">Relaxed</option>
                                                        <option value="Professional">Professional</option>
                                                        <option value="Casual">Casual</option>
                                                        <option value="Excited">Excited</option>
                                                        <option value="Bold">Bold</option>
                                                        <option value="Masculine">Masculine</option>
                                                        <option value="Dramatic">Dramatic</option>
                                                        <option value="Academic">Academic</option>
                                                
                                                    </select>
                                                
                                                </div>
                                            </div>

                                            @if($Template->slug == 'image-prompt-idea') 
                                            @else
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="temperature" class="form-label">Temperature (Creativity)</label>
                                                    <input type="range" name="temperature" class="form-range" id="temperature" min="0" max="1" step="0.01" value="0.00" >
                                                    <input type="number" name="temperature_value" class="form-control" id="temperature_value" min="0" max="1" step="0.01" value="0.00">
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label for="top_p" class="form-label">Top P</label>
                                                    <input type="range" name="top_p" class="form-range" id="top_p" min="0" max="1" step="0.01" value="1.00" >
                                                    <input type="number" name="top_p_value" class="form-control" id="top_p_value" min="0" max="1" step="0.01" value="1.00">
                                                </div>
                                            </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="frequency_penalty" class="form-label">Frequency Penalty</label>
                                                    <input type="range" name="frequency_penalty" class="form-range" id="frequency_penalty" min="0" max="2" step="0.01" value="0.00">
                                                    <input type="number" name="frequency_penalty_value" class="form-control" id="frequency_penalty_value" min="0" max="2" step="0.01" value="0.00">

                                                </div>

                                                <div class="col-md-6">
                                                    <label for="presence_penalty" class="form-label">Presence Penalty</label>
                                                    <input type="range" name="presence_penalty" class="form-range" id="presence_penalty" min="0" max="2" step="0.01" value="0.00">
                                                    <input type="number" name="presence_penalty_value" class="form-control" id="presence_penalty_value" min="0" max="2" step="0.01" value="0.00">
                                                </div>

                                                @if($Template->slug == 'image-prompt-idea')               
                                                <div class="col-md-6">
                                                    <label for="style" class="form-label">Image Style</label>
                                                    <select class="form-select" name="style" id="style" aria-label="Floating label select example">
                                                        <option disabled selected="">Enter Style</option>
                                                        <option value="natural">Natural</option>
                                                        <option value="vivid">Vivid</option>
                                                        <option value="none">NONE</option>
                                                        <option value="cinematic">CINEMATIC</option>
                                                        <option value="analog-film">ANALOG FILM</option>
                                                        <option value="animation">ANIMATION</option>
                                                        <option value="comic">COMIC</option>
                                                        <option value="craft-clay">CRAFT CLAY</option>
                                                        <option value="fantasy">FANTASY</option>
                                                        <option value="line-art">LINE ART</option>
                                                        <option value="cyberpunk">CYBERPUNK</option>
                                                        <option value="pixel-art">PIXEL ART</option>
                                                        <option value="photograph">PHOTOGRAPH</option>
                                                        <option value="graffiti">GRAFFITI</option>
                                                        <option value="game-gta">GAME GTA</option>
                                                        <option value="3d-character">3D CHARACTER</option>
                                                        <option value="baroque">BAROQUE</option>
                                                        <option value="caricature">CARICATURE</option>
                                                        <option value="colored-pencil">COLORED PENCIL</option>
                                                        <option value="doddle-art">DODDLE ART</option>
                                                        <option value="futurism">FUTURISM</option>
                                                        <option value="sketch">SKETCH</option>
                                                        <option value="surrealism">SURREALISM</option>
                                                        <option value="sticker-designs">STICKER DESIGNS</option>
                                                    </select>
                                                
                                                </div>
                                                @endif 

                                            </div>
                                        </div>
                                    </div>
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
                            <button id="generateButton" class="btn btn-rounded text-white gradient-btn-generate mx-1 mb-4 disabled-on-load" disabled>Generate</button>
                            {{-- <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Generate"> --}}
                        </div>
                    </div>
                        </form>
                    </div>
                    
                    <!-- Custom Ratio Video -->
                    <div class="ratio" style="--vz-aspect-ratio: 50%;">
                        <iframe src="{{$Template->video_link}}" title="YouTube video" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
           </div>

           <div class="col">
               <!-- Wrapper to place buttons side by side -->
               <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Copy Content Button -->
                <button id="copyButton" class="btn text-white gradient-btn-copy copy-toast-btn" title="Copy the generated Content">
                    <i class="las la-copy"></i>
                </button>
            
                <!-- Dropdown for file type selection -->
                <div class="dropdown">
                    <button id="downloadButton" class="btn text-white gradient-btn-download dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Download the generated Content">
                        <i class="las la-download"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="downloadButton">
                        <li><a class="dropdown-item" href="#" id="downloadAsPdf">Download As PDF</a></li>
                        <li><a class="dropdown-item" href="#" id="downloadAsDoc">Download As DOC</a></li>
                    </ul>
                </div>
            
                <!-- History Button -->
                <button id="generatedContents" type="button" class="btn gradient-btn-others text-white" data-bs-toggle="modal" data-bs-target="#subscribeModals">
                    {{$Template->template_name}} History
                </button>
            </div>
            


                 {{-- Generated Contents Modal --}}
           <div id="subscribeModals" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-lg-7">
                            <div class="modal-body p-5">
                                <h2 class="lh-base">Give us a s<span class="text-danger">AI</span>cond <span class="text-danger"> Fetching your contents</span>!</h2>
                                <p class="text-muted mb-4"></p>

                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="subscribe-modals-cover h-100">
                                <img src="https://aicontentfy.com/hubfs/Blog/23173f76-4d66-4847-8ee8-58ce8d575518.jpg" alt="" class="h-100 w-100 object-fit-cover" style="clip-path: polygon(100% 0%, 100% 100%, 100% 100%, 0% 100%, 25% 50%, 0% 0%);">
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <div id="detailsModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-3" style="border-color: #4CAF50; overflow: hidden;">
                    <div class="modal-header">
                        <h5 class="modal-title">Content Full Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Dynamic content will be populated here -->
                    </div>
                </div>
            </div>
        </div>
        

                
                
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

                                 <!-- Separator (horizontal line or margin) -->
                                <hr class="my-4"> <!-- You can replace this with margin if you prefer -->
                            
                                <div class="mt-2">
                                    <p><strong>Statistics:</strong></p>
                                    <ul>
                                        <li>Number of Tokens: <span id="numTokens"></span></li>
                                        <li>Number of Words: <span id="numWords"></span></li>
                                        <li>Number of Characters: <span id="numCharacters"></span></li>
                                    </ul>
                                </div>
                            </div><!-- end card-body -->  
                            
                            
                        </div><!-- end card -->
                        <iframe src="{{$Template->blog_link}}" width="100%" height="300px" style="border: none; overflow: hidden;"></iframe>
                        <hr>
                        <h4 class="mt-2"> Read more details about {{$Template->template_name}}<a href="{{$Template->blog_link}}" target="_blank" class="link gradient-text-2"> Click Here <i class=" ri-arrow-right-s-line"></i></a></h4>
                       
                    </div>
                    <!-- end col -->
                </div>
                
                
                <div class="text-end">
                    @if($Template->slug == 'image-prompt-idea')
                    <a href="{{ route('generate.image.view') }}" 
                       id="generateBtnCopyContext" 
                       class="btn gradient-btn-generate"
                       data-content="{{ $content ?? '' }}">Generate Image Now</a>
                    @endif
                
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
    document.getElementById('generateBtnCopyContext').addEventListener('click', function (event) {
    event.preventDefault();

    const button = event.currentTarget;
    const content = document.querySelector('#formattedContentDisplay').innerText.trim(); // Adjust selector as needed
    const route = button.getAttribute('href');

    // Redirect with content as a query parameter
    window.location.href = `${route}?content=${encodeURIComponent(content)}`;
});

</script>

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

<script>
    $(document).ready(function() {
        // Update number input when the range slider changes
        $('#max_result_length').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(0); // Round to nearest integer
            $('#max_result_length_value').val(value);
        });
    
        // Update range slider when the number input changes
        $('#max_result_length_value').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(0); // Round to nearest integer
            if (!isNaN(value)) {
                $('#max_result_length').val(value);
            }
        });
    });
    </script>

<script>
    $(document).ready(function() {
        // Update number input when the range slider changes
        $('#presence_penalty').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            $('#presence_penalty_value').val(value);
        });
    
        // Update range slider when the number input changes
        $('#presence_penalty_value').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            if (!isNaN(value)) {
                $('#presence_penalty').val(value);
            }
        });
    });
    </script>

<script>
    $(document).ready(function() {
        // Update number input when the range slider changes
        $('#frequency_penalty').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            $('#frequency_penalty_value').val(value);
        });
    
        // Update range slider when the number input changes
        $('#frequency_penalty_value').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            if (!isNaN(value)) {
                $('#frequency_penalty').val(value);
            }
        });
    });
    </script>

<script>
    $(document).ready(function() {
        // Update number input when the range slider changes
        $('#top_p').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            $('#top_p_value').val(value);
        });
    
        // Update range slider when the number input changes
        $('#top_p_value').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            if (!isNaN(value)) {
                $('#top_p').val(value);
            }
        });
    });
    </script>

<script>
    $(document).ready(function() {
        // Update number input when the range slider changes
        $('#temperature').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            $('#temperature_value').val(value);
        });
    
        // Update range slider when the number input changes
        $('#temperature_value').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            if (!isNaN(value)) {
                $('#temperature').val(value);
            }
        });
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


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('#generateForm');
        const loader = document.getElementById('loader');
        const formattedContentDisplay = document.getElementById('formattedContentDisplay');
        const sendMessageBtn = document.getElementById('generateButton');
        let abortController = null;  // To store the AbortController instance
    
        // Format content using marked.js and DOMPurify
        function formatContent(content) {
            // Set options for marked.js
            marked.setOptions({
                breaks: true,  // Enable line breaks
                gfm: true      // Enable GitHub Flavored Markdown
            });
    
            // Parse Markdown to HTML
            let formattedContent = marked.parse(content);
    
            // Sanitize the HTML to prevent XSS
            formattedContent = DOMPurify.sanitize(formattedContent);
    
            return formattedContent;
        }
    
        // Function to reset the send button back to its original state
        function resetButton() {
            loader.classList.add('d-none');
            sendMessageBtn.innerHTML = 'Generate';
            sendMessageBtn.disabled = false;
            sendMessageBtn.dataset.state = 'idle';  // Reset state
            abortController = null;  // Clear the AbortController
        }
    
        // Copy button click event
        document.getElementById('copyButton').addEventListener('click', function () {
            const editorContent = formattedContentDisplay.innerText;
            const textArea = document.createElement('textarea');
            textArea.value = editorContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            // toastr.success('Content copied to clipboard!');
        });
    
      // Listen for click event on 'Download As PDF' option
      document.getElementById('downloadAsPdf').addEventListener('click', function () {
    const editorContent = formattedContentDisplay; // Reference to the content you want to download (it can be the entire element or innerHTML)
    
    // Set up PDF options for better styling and appearance
    const options = {
        margin:       [10, 10, 10, 10],  // Set margins (top, left, bottom, right)
        filename:     'generated_content.pdf', // Filename for the PDF
        html2canvas:  { scale: 2 },      // Increase resolution for better quality (scale factor)
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }, // Set paper format to A4 and portrait orientation
        pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] } // Prevent unnecessary page breaks
    };
    
    // Use html2pdf to convert HTML content into PDF with custom options
    html2pdf()
        .from(editorContent)  // Pass the content to be converted into PDF
        .set(options)          // Apply custom options
        .save();               // Trigger the save as PDF
});


// Listen for click event on 'Download As DOC' option
document.getElementById('downloadAsDoc').addEventListener('click', function () {
    const editorContent = formattedContentDisplay.innerText;
    
    // Create a new Blob for DOCX
    const blob = new Blob([editorContent], { type: 'application/msword' });
    
    // Use FileSaver.js to save the blob as a DOC file
    saveAs(blob, 'generated_content.doc');
});

    
        form.addEventListener('submit', function (event) {
            event.preventDefault();
      // Show the magic ball
      showMagicBall('facts', 'general');
      // Check if already generating (toggle stop)
            if (sendMessageBtn.dataset.state === 'generating') {
                // Stop generation by aborting the request
                if (abortController) {
                    abortController.abort();  // Stop the request
                    resetButton();  // Reset the button back to "Generate"
                }
                return;  // Stop execution here
            }
    
            // Show loader
            loader.classList.remove('d-none');
    
            // Disable the button, change the text to "Stop", and store the generating state
            sendMessageBtn.disabled = false;
            sendMessageBtn.innerHTML = 'Stop';
            sendMessageBtn.dataset.state = 'generating';
    
            // Create an AbortController instance
            abortController = new AbortController();
    
            const formData = new FormData(form);
            fetch(form.getAttribute('action'), {
                method: form.getAttribute('method'),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData,
                signal: abortController.signal  // Pass the signal to fetch
            })
            .then(response => {
                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let content = ''; // Variable to store streamed content
                let stats = {};   // Variable to store stats
                hideMagicBall();
                const processStream = ({ done, value }) => {
                    if (done) {
                        // Hide loader after streaming is complete
                        resetButton();
                      
                        // Display the stats in the spans
                        document.getElementById('numTokens').innerText = stats.totalTokens;
                        document.getElementById('numWords').innerText = stats.num_words;
                        document.getElementById('numCharacters').innerText = stats.num_characters;
    
                        return;
                    }
    
                    // Decode the chunk
                    const chunk = decoder.decode(value, { stream: true });
    
                    // Check if it's JSON (indicating stats) or regular content
                    if (chunk.startsWith('{') && chunk.endsWith('}')) {
                        try {
                            stats = JSON.parse(chunk); // Parse statistics
                        } catch (e) {
                            console.error('Error parsing stats:', e);
                        }
                    } else {
                        // If it's not JSON, assume it's part of the content
                        content += chunk;
                        formattedContentDisplay.innerHTML = content; // Format the streamed content
                    }
    
                    return reader.read().then(processStream); // Continue reading chunks
                };
    
                return reader.read().then(processStream);
            })
            .catch(error => {
                if (abortController.signal.aborted) {
                    console.log('Request aborted');
                } else {
                    console.error('Error:', error);
                }
                loader.classList.add('d-none');
                hideMagicBall();
                resetButton();  // Reset the button state in case of error or abort
            });
        });
    });
</script>

{{-- Generated Content Modal Populate --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('generatedContents').addEventListener('click', function () {
            const templateId = '{{ $Template->id }}'; // Pass the template ID dynamically

            // AJAX call to fetch template content
            fetch(`/ai-content-creator/getcontentbyuser/${templateId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch template content.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                alert(data.error);
                return;
            }

            // Populate the modal with content
            const modalBody = document.querySelector('#subscribeModals .modal-body');
           const contentListHtml = data.content_list.map((content, index) => `
            <div class="card card-body">
                                        <div class="d-flex mb-4 align-items-center">
                                            <div class="flex-grow-1 ms-2">
                                                <h5 class="card-title mb-1">${content.generated_content.substring(0, 100)}...</h5>
                                                <p class="text-muted mb-0">${content.created_at}</p>
                                            </div>
                                        </div>
                                     <a href="javascript:void(0)" 
                                        class="btn gradient-btn-6 text-white my-2 btn-sm see-details-btn" 
                                        data-index="${index}" 
                                        data-full-content="${encodeURIComponent(content.generated_content)}">
                                        See Details
                                     </a>
                                    </div> `
           ).join('');

            modalBody.innerHTML = `
                <h2>Latest Contents of <span class="text-danger">${data.template_name}<span></h2>
                <div>${contentListHtml}</div>
            `;

            // Add event listener to handle "See Details" click
            document.querySelectorAll('.see-details-btn').forEach(button => {
            button.addEventListener('click', function () {
                const fullContent = decodeURIComponent(this.getAttribute('data-full-content')); // Get full content
                const createdAt = this.parentElement.querySelector('.text-muted').innerText; // Optional: Get created_at

                // Populate the details modal
                const detailsModalBody = document.querySelector('#detailsModal .modal-body');
                detailsModalBody.innerHTML = `
                    <div>${fullContent}</div> <!-- Rendered Markdown HTML -->
                    <p class="text-muted">Created At: ${createdAt}</p>
                `;

                // Show the details modal
                const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                detailsModal.show();
            });
        });

        })
                .catch(error => {
                    console.error(error);
                    alert('An error occurred while loading the content.');
                });
        });
    });
</script>

<script>
    function validateFileType(input) {
        const allowedExtensions = ['pdf', 'doc', 'docx', 'txt'];
        const fileName = input.value.split('.').pop().toLowerCase();
        
        if (!allowedExtensions.includes(fileName)) {
            document.getElementById('file-error').style.display = 'block';
            input.value = ''; // Reset the input field
        } else {
            document.getElementById('file-error').style.display = 'none';
        }
    }
</script>

@endsection