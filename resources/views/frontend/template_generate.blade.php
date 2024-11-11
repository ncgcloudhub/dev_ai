@extends('admin.layouts.master-without-nav')
@section('title', $Template->page_title)

@section('description', $Template->page_description)

@section('keywords', $Template->page_tagging)

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

            <!-- Breadcrumb -->
            <div class="col-xxl-12 mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active breadcrumb-title" aria-current="page"><u>{{ $Template->template_name }}</u></li>
                    </ol>
                </nav>
            </div>
            
            <!-- End Token Information -->
            <div class="col-xxl-6">
             <div class="card">
                
         
                 <div class="card-body">
                   
                     <div class="live-preview">
                         <form id="generateForm"  action="{{route ('frontend.free.aicontentcreator.generate')}}" method="post" class="row g-3">
                             @csrf
                             <input type="hidden" name="template_id" value="{{ $Template->id }}">
                             <div class="col-md-12">
                                 <label for="language" class="form-label">Select Language</label>
                                 <select class="form-select" name="language" id="language" aria-label="Floating label select example">
                                     <option disabled selected="">Enter Language</option>
                                     <option value="English">English</option>
                                     <option value="Bengali">Bengali</option>
                                     
                                   </select>
                              
                             </div>
 
                             @isset($inputTypes)
                             @foreach($inputTypes as $key => $type)
                                 <div class="col-md-12">
                                      <label for="{{ $inputNames[$key] }}" class="form-label">{{ $inputLabels[$key] }}</label>
                                 @if($type == 'text')
                                     <input type="text" name="{{ $inputNames[$key] }}" class="form-control" id="{{ $inputNames[$key] }}" placeholder="Enter {{ $inputLabels[$key] }}">
                                 @elseif($type == 'textarea')
                                     <textarea class="form-control" name="{{ $inputNames[$key] }}" id="{{ $inputNames[$key] }}" rows="3"></textarea>
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
                                 <label class="form-check-label" for="SwitchCheck1">Use Emoji</label>
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
                                                <input type="range" name="max_result_length" class="form-range" id="max_result_length" min="10" max="4000" step="10" value="100">
                                                <input type="number" name="max_result_length_value" class="form-control" id="max_result_length_value" min="10" max="4000" step="10" value="100">
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

                             <div class="col-12">
                                <div class="text-end">
                                    <button id="generateBtn" class="btn btn-rounded gradient-btn-5 mb-5">Generate</button>
                                </div>
                            </div>

                         </form>

                         <!-- Loading Spinner -->
                        <div id="loadingSpinner" style="display:none;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                     </div>
                     
                 </div>
             </div>
            </div>

            <div class="col">
 
                 <!-- Add the Download Content button -->
                
                 <button id="copyButton" class="btn text-white gradient-btn-9 mx-1">
                    <i class="las la-copy"></i>
                </button>
                <button id="downloadButton" class="btn text-white gradient-btn-9 mx-1">
                    <i class="las la-download"></i>
                </button>
                
                 <div class="row mt-2">
                     <div class="col-lg-12">
                         <div class="card">
                             <div class="card-header">
                                 <h4 class="card-title mb-0">Generated Content</h4>
                             </div><!-- end card header -->
                             <div class="card-body">
                                 <textarea class="ifaz" id="myeditorinstance" readonly></textarea>
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
                     </div>
                     <!-- end col -->
                 </div>
                 
                 <div class="text-end">
                     @if($Template->slug == 'image-prompt-idea') {{-- Assuming template_id for the specific template is 78 --}}
                         <!-- Add the Generate Image button -->
                         <a href="{{route('generate.image.view')}}" id="downloadButton" class="btn btn-warning">Generate Image Now</a>
                     @endif
                 </div>
 
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
            $(document).ready(function() {
                // Retrieve and update the token count display on page load
                let remainingTokens = parseInt(localStorage.getItem('remainingTokens')) || 2000;
                $('#tokenCount').text(remainingTokens); // Update token count display
        
                // Function to adjust the button based on token count
                function adjustButton() {
                    if (remainingTokens <= 10) {
                        $('#generateBtn')
                            .removeClass('btn-primary')
                            .addClass('btn-success')
                            .text('Sign Up for Free')
                            .attr('onclick', "window.location.href='{{ route('register') }}';");
                    } else {
                        $('#generateBtn')
                            .removeClass('btn-success')
                            .addClass('btn-primary')
                            .text('Generate')
                            .removeAttr('onclick');
                    }
                }
        
                // Call adjustButton on page load to set the button appropriately
                adjustButton();
        
                function adjustToNearestLower10(value) {
                    return Math.floor(value / 10) * 10;
                }
        
                // Update number input when the range slider changes
                $('#max_result_length').on('input', function() {
                    var value = parseFloat($(this).val()).toFixed(0); // Round to nearest integer
        
                    if (value > remainingTokens) {
                        alert('You have exceeded the available tokens.');
                        value = adjustToNearestLower10(remainingTokens);
                        $(this).val(value);
                    }
        
                    $('#max_result_length_value').val(value);
                });
        
                // Update range slider when the number input changes
                $('#max_result_length_value').on('input', function() {
                    var value = parseFloat($(this).val()).toFixed(0); // Round to nearest integer
        
                    if (value > remainingTokens) {
                        alert('You have exceeded the available tokens.');
                        value = adjustToNearestLower10(remainingTokens);
                        $(this).val(value);
                    }
        
                    $('#max_result_length').val(value);
                });
        
                // Submit form event handler
                $('#generateForm').submit(function(event) {
                    event.preventDefault();
        
                    // Show loading spinner
        
                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        success: function(response) {
                            // Hide loading spinner
        
                            // Update remaining tokens
                            remainingTokens -= response.completionTokens;
                            localStorage.setItem('remainingTokens', remainingTokens);
        
                            // Update token count display
                            $('#tokenCount').text(remainingTokens);
        
                            // Adjust the button based on the new token count
                            adjustButton();
        
                            // Update content and other details on the page
                        },
                        error: function(xhr, status, error) {
                            // Hide loading spinner
                            // Handle errors properly
                            console.error(xhr.responseText);
                        }
                    });
                });
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

        <script src="https://cdn.tiny.cloud/1/du2qkfycvbkcbexdcf9k9u0yv90n9kkoxtth5s6etdakoiru/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

        <script>
            tinymce.init({
                selector: 'textarea#myeditorinstance',
                plugins: 'code table lists',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
            });


            $(document).ready(function () {
            $('#generateForm').submit(function (event) {
                event.preventDefault();

                // Show the spinner
                $('#loadingSpinner').show();

                let userSignedIn = {{ Auth::check() ? 'true' : 'false' }};
                let tokensUsedToday = parseInt(localStorage.getItem('tokensUsedToday')) || 0;

                if (!userSignedIn && tokensUsedToday >= 2000) {
                    $('#loadingSpinner').hide();
                    alert('You have reached the daily limit of 2000 tokens. Please try again tomorrow.');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (response) {
                        // Hide the spinner
                        $('#loadingSpinner').hide();

                        if (response == 0) {
                            alert('Please Upgrade Plan');
                            return;
                        }

                        if (!userSignedIn) {
                            tokensUsedToday += response.completionTokens;
                            localStorage.setItem('tokensUsedToday', tokensUsedToday);
                        }

                        let formattedContent = '';
                        let lines = response.content.split('\n');

                        if (lines.some(line => line.trim().startsWith('*'))) {
                            formattedContent += '<ul>';
                            lines.forEach(line => {
                                if (line.trim().startsWith('*')) {
                                    formattedContent += '<li>' + line.trim().substring(1).trim() + '</li>';
                                } else {
                                    formattedContent += '<p>' + line.trim() + '</p>';
                                }
                            });
                            formattedContent += '</ul>';
                        } else {
                            formattedContent = '<p>' + lines.join('</p><p>') + '</p>';
                        }

                        tinymce.get('myeditorinstance').setContent(formattedContent);

                        $('#numTokens').text(response.completionTokens);
                        $('#numWords').text(response.num_words);
                        $('#numCharacters').text(response.num_characters);
                    },
                    error: function (xhr, status, error) {
                        // Hide the spinner
                        $('#loadingSpinner').hide();
                        console.error(xhr.responseText);
                    }
                });
            });

            // Reset the token count at midnight
            function resetTokenCount() {
                let now = new Date();
                let millisTillMidnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 0, 0) - now;
                setTimeout(function() {
                    localStorage.setItem('tokensUsedToday', '0');
                    resetTokenCount();
                }, millisTillMidnight);
            }

            resetTokenCount();

                // Copy button click event
                $('#copyButton').click(function () {
                    const editorContent = tinymce.get('myeditorinstance').getContent({ format: 'text' }); // Get content without HTML tags
                    const textArea = document.createElement('textarea');
                    textArea.value = editorContent;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('Content copied to clipboard!');
                });

                // Download button click event
                $('#downloadButton').click(function () {
                    const editorContent = tinymce.get('myeditorinstance').getContent({ format: 'text' }); // Get content without HTML tags

                    const blob = new Blob([editorContent], { type: 'application/msword' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'generated_content.docx';
                    document.body.appendChild(a);
                    a.click();
                    setTimeout(() => {
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    }, 0);
                });

            });
        </script>     
@endsection