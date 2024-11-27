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
@slot('li_1') <a href="{{route('aicontentcreator.manage')}}">Templates</a> @endslot
@slot('title') {{$Template->template_name}} @endslot
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
                                    <input type="text" name="{{ $inputNames[$key] }}" class="form-control" id="{{ $inputNames[$key] }}" placeholder="{{ $inputPlaceholders[$key] ?? $inputLabels[$key] }}">
                                @elseif($type == 'textarea')
                                    <textarea class="form-control" name="{{ $inputNames[$key] }}" id="{{ $inputNames[$key] }}" placeholder="{{ $inputPlaceholders[$key] ?? $inputLabels[$key] }}" rows="3"></textarea>
                                @endif
                                </div>

                                <div hidden class="col-md-12" data-prompt="{{ $Template->prompt }}">
                                    <textarea class="form-control" name="prompt" id="VertimeassageInput" rows="3" placeholder="Enter your message">{{$Template->prompt}}</textarea>
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
                                        <button class="accordion-button collapsed bg-secondary-subtle" type="button" data-bs-toggle="collapse"
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
                            <button id="generateButton" class="btn btn-rounded text-white gradient-btn-5 mx-1 mb-4">Generate</button>
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
                <!-- Add the Download Content button -->
                <button id="copyButton" class="btn text-white gradient-btn-5 mx-1" title="Copy the generated Content">
                    <i class="las la-copy"></i>
                </button>
                <button id="downloadButton" class="btn text-white gradient-btn-5 mx-1" title="Download the generated Content">
                    <i class="las la-download"></i>
                </button>
                
                
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
                        <h4> Read more details about {{$Template->template_name}}<a href="{{$Template->blog_link}}" target="_blank" class="link gradient-text-2"> Click Here <i class=" ri-arrow-right-s-line"></i></a></h4>
                       
                    </div>
                    <!-- end col -->
                </div>
                
                
                <div class="text-end">
                    @if($Template->slug == 'image-prompt-idea') {{-- Assuming template_id for the specific template is 78 --}}
                        <!-- Add the Generate Image button -->
                        <a href="{{route('generate.image.view')}}" id="downloadButton" class="btn gradient-btn-6">Generate Image Now</a>
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
            alert('Content copied to clipboard!');
        });
    
        // Download button click event using FileSaver.js
        document.getElementById('downloadButton').addEventListener('click', function () {
            const editorContent = formattedContentDisplay.innerText;
    
            // Create a new Blob with the content
            const blob = new Blob([editorContent], { type: 'application/msword' });
    
            // Use FileSaver.js to save the blob as a file
            saveAs(blob, 'generated_content.doc');
        });
    
        form.addEventListener('submit', function (event) {
            event.preventDefault();
    
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
    
                const processStream = ({ done, value }) => {
                    if (done) {
                        // Hide loader after streaming is complete
                        resetButton();
    
                        // Display the stats in the spans
                        document.getElementById('numTokens').innerText = stats.completionTokens;
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
                        formattedContentDisplay.innerHTML = formatContent(content); // Format the streamed content
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
                resetButton();  // Reset the button state in case of error or abort
            });
        });
    });
</script>

@endsection