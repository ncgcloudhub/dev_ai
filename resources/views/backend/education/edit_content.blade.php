@extends('admin.layouts.master')
@section('title', $seo->title)

@section('description', $seo->description)

@section('keywords', $seo->keywords)
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Education</h4>
                <a href="{{ route('user_generated_education_content') }}" class="btn btn-warning fw-bold text-dark shadow">
                    Show Generated Contents
                </a>
            </div><!-- end card header -->
            
            <div class="card-body form-steps">
                <form class="vertical-navs-step" method="POST" action="{{ route('education.content.update') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{$content->id}}">
                    <div class="row gy-5">
                        <div class="col-lg-3">
                            <div class="nav flex-column custom-nav nav-pills" role="tablist"
                                aria-orientation="vertical">
                                <button class="nav-link active" id="v-pills-bill-info-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-info" type="button" role="tab"
                                    aria-controls="v-pills-bill-info" aria-selected="true">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 1
                                    </span>
                                    Basic Info
                                </button>
                                <button class="nav-link" id="v-pills-bill-address-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-address" type="button" role="tab"
                                    aria-controls="v-pills-bill-address" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 2
                                    </span>
                                    Class Materials Info
                                </button>
                                <button class="nav-link" id="v-pills-payment-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-payment" type="button" role="tab"
                                    aria-controls="v-pills-payment" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 3
                                    </span>
                                    Topic Reference
                                </button>
                                <button class="nav-link" id="v-pills-finish-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-finish" type="button" role="tab"
                                    aria-controls="v-pills-finish" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 4
                                    </span>
                                    Finish
                                </button>
                            </div>
                            <!-- end nav -->
                        </div> <!-- end col-->
                        <div class="col-lg-6">
                            <div class="px-lg-4">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="v-pills-bill-info" role="tabpanel"
                                        aria-labelledby="v-pills-bill-info-tab">
                                        <div>
                                            <h5>Basic Info</h5>
                                            <p class="text-muted">Fill all information below</p>
                                        </div>

                                        <div>
                                            <div class="row g-3">

                                                <div class="col-sm-6">
                                                    <label class="form-label">Grade/Class</label>
                                                    <select id="grade_id" class="form-select" name="grade_id" data-choices aria-label="Default select grade">
                                                        @foreach($classes as $item)
                                                            <option value="{{ $item->id }}" {{ $item->id == $content->grade_id ? 'selected' : '' }}>
                                                                {{ $item->grade }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    
                                                    <div class="invalid-feedback">Please enter a first name</div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label">Subject</label>
                                                    <select id="subject_id" class="form-select" name="subject_id" data-choices aria-label="Default select subject">
                                                        <option value="{{ $content->subject_id }}" {{ $content->subject_id == $content->subject_id ? 'selected' : '' }}>
                                                            {{ $content->subject->name }}
                                                        </option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a first name</div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">Age</label>
                                                    <select class="form-select" name="age" data-choices aria-label="Default select age">
                                                        @for ($i = 4; $i <= 20; $i++)
                                                            <option value="{{ $i }}" {{ $content->age == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                

                                                <div class="col-sm-6">
                                                    <label class="form-label">Content Difficulty Level</label>
                                                    <select class="form-select" name="difficulty_level" data-choices aria-label="Default select difficulty">
                                                        <option value="Easy" {{ $content->difficulty_level == 'Easy' ? 'selected' : '' }}>Easy</option>
                                                        <option value="Medium" {{ $content->difficulty_level == 'Medium' ? 'selected' : '' }}>Medium</option>
                                                        <option value="Difficult" {{ $content->difficulty_level == 'Difficult' ? 'selected' : '' }}>Difficult</option>
                                                        <option value="Exceptional" {{ $content->difficulty_level == 'Exceptional' ? 'selected' : '' }}>Exceptional</option>
                                                    </select>
                                                    
                                                    <div class="invalid-feedback">Please enter a first name</div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">Tone</label>
                                                    <select class="form-select" name="tone" data-choices aria-label="Default select tone">
                                                        <option value="Kids" {{ $content->tone == 'Kids' ? 'selected' : '' }}>Kids</option>
                                                        <option value="Adult" {{ $content->tone == 'Adult' ? 'selected' : '' }}>Adult</option>
                                                    </select>
                                                    
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">Persona</label>
                                                    <select class="form-select" name="persona" data-choices aria-label="Default select persona">
                                                        <option value="Very Simple" {{ $content->persona == 'Very Simple' ? 'selected' : '' }}>Very Simple</option>
                                                        <option value="Step by Step guide" {{ $content->persona == 'Step by Step guide' ? 'selected' : '' }}>Step by Step guide</option>
                                                        <option value="Simple" {{ $content->persona == 'Simple' ? 'selected' : '' }}>Simple</option>
                                                        <option value="Somewhat Difficulty" {{ $content->persona == 'Somewhat Difficulty' ? 'selected' : '' }}>Somewhat Difficulty</option>
                                                    </select>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button"
                                                class="btn btn-success btn-label right ms-auto nexttab"
                                                data-nexttab="v-pills-bill-address-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go
                                                to Additional Info</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                    <div class="tab-pane fade" id="v-pills-bill-address" role="tabpanel"
                                        aria-labelledby="v-pills-bill-address-tab">
                                        <div>
                                            <h5>Class Materials Info</h5>
                                            <p class="text-muted">Fill all information below</p>
                                        </div>

                                            <div>
                                                <div class="row g-3">
                                                    <!-- Topic Field -->
                                                    <div class="col-12">
                                                        <label for="topic" class="form-label" title="What is the Topic of the Content">Topic</label>
                                                        <input type="text" class="form-control" id="topic" name="topic" value="{{ old('topic', $content->topic ?? '') }}" placeholder="Briefly state the main topic" required>
                                                        <div class="invalid-feedback">What is the Content Topic about?</div>
                                                    </div>

                                                    <!-- Topic Description (Optional) -->
                                                    <div class="col-12">
                                                        <label for="additional_details" class="form-label" title="Give a little more brief about the Topic">Topic Description
                                                            <span class="text-muted">(Optional)</span>
                                                        </label>
                                                        <textarea class="form-control" id="additional_details" name="additional_details" rows="2" placeholder="Provide a short description or key points to cover">{{ old('additional_details', $content->additional_details ?? '') }}</textarea>
                                                    </div>  

                                                    <!-- Content Type Selection -->
                                                    <div class="col-6">
                                                        <label for="content_type" class="form-label" title="What is the type of the Content?">Content Type</label>
                                                        <input class="form-select" list="contentOption" id="content_type" name="content_type" placeholder="Select Content Type" aria-label="Select or type Content Type" value="{{ old('content_type', $content->content_type ?? '') }}">
                                                        <datalist id="contentOption">
                                                            <option value="story" {{ old('content_type', $content->content_type ?? '') == 'story' ? 'selected' : '' }}>
                                                            <option value="book" {{ old('content_type', $content->content_type ?? '') == 'book' ? 'selected' : '' }}>
                                                            <option value="passage" {{ old('content_type', $content->content_type ?? '') == 'passage' ? 'selected' : '' }}>
                                                            <option value="poem" {{ old('content_type', $content->content_type ?? '') == 'poem' ? 'selected' : '' }}>
                                                            <option value="worksheet" {{ old('content_type', $content->content_type ?? '') == 'worksheet' ? 'selected' : '' }}>
                                                        </datalist>
                                                    </div>

                                                    <!-- Language Style Selection -->
                                                    <div class="col-6">
                                                        <label for="language_style" class="form-label" title="What is the Language Style?">Language Style</label>
                                                        <input class="form-select" list="languageOption" id="language_style" name="language_style" placeholder="Select Language Style" aria-label="Select or type Language Style" value="{{ old('language_style', $content->language_style ?? '') }}">
                                                        <datalist id="languageOption">
                                                            <option value="Simple (suitable for young readers)" {{ old('language_style', $content->language_style ?? '') == 'Simple (suitable for young readers)' ? 'selected' : '' }}>
                                                            <option value="Conversational" {{ old('language_style', $content->language_style ?? '') == 'Conversational' ? 'selected' : '' }}>
                                                            <option value="Formal" {{ old('language_style', $content->language_style ?? '') == 'Formal' ? 'selected' : '' }}>
                                                            <option value="Academic" {{ old('language_style', $content->language_style ?? '') == 'Academic' ? 'selected' : '' }}>                                                            
                                                        </datalist>
                                                    </div>

                                                    <!-- Desired Length Selection -->
                                                    <div class="col-12">
                                                        <label for="desired_length" class="form-label" title="What is the desired length of the Content?">Desired Length</label>
                                                        <input class="form-select" list="lengthOption" id="desired_length" name="desired_length" placeholder="Choose your desired length for the content" aria-label="Select or type your Desired Length" value="{{ old('desired_length', $content->desired_length ?? '') }}">
                                                        <datalist id="lengthOption">
                                                            <option value="Short (1-2 pages)" {{ old('desired_length', $content->desired_length ?? '') == 'Short (1-2 pages)' ? 'selected' : '' }}>
                                                            <option value="Medium (3-5 pages)" {{ old('desired_length', $content->desired_length ?? '') == 'Medium (3-5 pages)' ? 'selected' : '' }}>
                                                            <option value="Long (6+ pages)" {{ old('desired_length', $content->desired_length ?? '') == 'Long (6+ pages)' ? 'selected' : '' }}>
                                                        </datalist>
                                                    </div>

                                                    <!-- Checkbox for Question Generation -->
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="generate_questions" class="form-check-label">
                                                                <input type="checkbox" id="generate_questions" name="generate_questions" value="1" {{ old('generate_questions', $content->generate_questions ?? 0) == 1 ? 'checked' : '' }} onchange="toggleQuestionFields()"> 
                                                                Generate Questions Based on Content
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Question Generation Fields (Hidden by default) -->
                                                    <div id="question_fields" style="display: {{ old('generate_questions', $content->generate_questions ?? 0) ? 'block' : 'none' }}">
                                                        <div class="row g-3">
                                                            <!-- Number of Questions -->
                                                            <div class="col-4">
                                                                <label for="number_of_questions" title="How many questions do you want to include?">Number of Questions</label>
                                                                <input type="number" id="number_of_questions" name="number_of_questions" class="form-control" value="{{ old('number_of_questions', $content->number_of_questions ?? '') }}">
                                                            </div>

                                                            <!-- Type of Questions -->
                                                            <div class="col-4">
                                                                <label for="question_type" class="form-label" title="What should be the Question Type?">Question Type</label>
                                                                <input class="form-select" list="questionTypeOption" id="question_type" name="question_type" placeholder="Choose Question Type" aria-label="Choose Question Type" value="{{ old('question_type', $content->question_type ?? '') }}">
                                                                <datalist id="questionTypeOption">
                                                                    <option value="Multiple Choice" {{ old('question_type', $content->question_type ?? '') == 'Multiple Choice' ? 'selected' : '' }}>
                                                                    <option value="True/False" {{ old('question_type', $content->question_type ?? '') == 'True/False' ? 'selected' : '' }}>
                                                                    <option value="Short Answer" {{ old('question_type', $content->question_type ?? '') == 'Short Answer' ? 'selected' : '' }}>
                                                                    <option value="Matching" {{ old('question_type', $content->question_type ?? '') == 'Matching' ? 'selected' : '' }}>
                                                                    <option value="Fill-in-the-Blank" {{ old('question_type', $content->question_type ?? '') == 'Fill-in-the-Blank' ? 'selected' : '' }}>
                                                                    <option value="Essay" {{ old('question_type', $content->question_type ?? '') == 'Essay' ? 'selected' : '' }}>
                                                                </datalist>
                                                            </div>

                                                            <!-- Difficulty Level -->
                                                            <div class="col-4">
                                                                <label for="question_difficulty_level" title="What should be the difficulty level of the questions?">Difficulty Level</label>
                                                                <select id="question_difficulty_level" name="question_difficulty_level" class="form-select">
                                                                    <option value="easy" {{ old('question_difficulty_level', $content->question_difficulty_level ?? '') == 'easy' ? 'selected' : '' }}>Easy</option>
                                                                    <option value="medium" {{ old('question_difficulty_level', $content->question_difficulty_level ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                                                                    <option value="hard" {{ old('question_difficulty_level', $content->question_difficulty_level ?? '') == 'hard' ? 'selected' : '' }}>Hard</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Checkbox for Answer Generation -->
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="generate_answers" class="form-check-label">
                                                                <input type="checkbox" id="generate_answers" name="generate_answers" value="1" {{ old('generate_answers', $content->generate_answers ?? 0) == 1 ? 'checked' : '' }}> 
                                                                Generate Answers for the Questions
                                                            </label>
                                                        </div>
                                                    </div>

                                                 <!-- Checkbox to toggle image generation -->
                                                    <div class="form-group">
                                                        <label for="generate_images">
                                                            <input type="checkbox" id="generate_images" name="generate_images" value="1" {{ old('generate_images', $content->generate_images ?? false) ? 'checked' : '' }} onchange="toggleImageFields()"> 
                                                            Generate Images Based on Content
                                                        </label>
                                                    </div>

                                                    <!-- Hidden fields for image generation, shown only if checkbox is checked -->
                                                    <div id="image_fields" style="display: {{ old('generate_images', $content->generate_images ?? false) ? 'block' : 'none' }};">
                                                        <div class="row g-3">
                                                            
                                                            <!-- Type of Image Dropdown and Manual Input -->
                                                            <div class="col-4">
                                                                <label for="image_type" class="form-label" title="What type of Image you want to Generate">Type of Image</label>
                                                                <input class="form-select" list="imageTypeOption" id="image_type" name="image_type" placeholder="Select Image Type" aria-label="Select Image Type" 
                                                                    value="{{ old('image_type', $content->image_type ?? '') }}">
                                                                <datalist id="imageTypeOption">
                                                                    <option value="Illustrations related to the story or topic">
                                                                    <option value="Diagrams or charts">
                                                                    <option value="Mathematical figures or graphs">
                                                                    <option value="Scientific illustrations">
                                                                    <option value="Customized images based on specific descriptions">
                                                                </datalist>
                                                            </div>

                                                            <!-- Number of Image -->
                                                            <div class="col-2">
                                                                <label for="number_of_images" title="How many Images you want to Generate">Number of Images</label>
                                                                <input type="number" id="number_of_images" name="number_of_images" class="form-control" 
                                                                    value="{{ old('number_of_images', $content->number_of_images ?? 1) }}">
                                                            </div>

                                                            <!-- Image Placement -->
                                                            <div class="col-3">
                                                                <label for="image_placement" class="form-label" title="What place you want to include the Image">Placement of Image</label>
                                                                <input class="form-select" list="imagePlacementOption" id="image_placement" name="image_placement" placeholder="Select Image Placement" aria-label="Select Image Placement" 
                                                                    value="{{ old('image_placement', $content->image_placement ?? '') }}">
                                                                <datalist id="imagePlacementOption">
                                                                    <option value="Beginning of the content">
                                                                    <option value="Throughout the content">
                                                                    <option value="End of the content">
                                                                    <option value="Next to related text or paragraphs">
                                                                </datalist>
                                                            </div>

                                                            <!-- Image Style -->
                                                            <div class="col-3">
                                                                <label for="image_style" class="form-label" title="What should be the Style of the Image">Image Style</label>
                                                                <input class="form-select" list="imageStyleOption" id="image_style" name="image_style" placeholder="Select Image Style" aria-label="Select Image Style" 
                                                                    value="{{ old('image_style', $content->image_style ?? '') }}">
                                                                <datalist id="imageStyleOption">
                                                                    <option value="Cartoon / Animated">
                                                                    <option value="Realistic">
                                                                    <option value="Sketches">
                                                                    <option value="Infographics">
                                                                </datalist>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button" class="btn btn-light btn-label previestab"
                                                data-previous="v-pills-bill-info-tab"><i
                                                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                Back to Basic Info</button>
                                            <button type="button"
                                                class="btn btn-success btn-label right ms-auto nexttab"
                                                data-nexttab="v-pills-payment-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go
                                                to Reference</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                    <div class="tab-pane fade" id="v-pills-payment" role="tabpanel"
                                        aria-labelledby="v-pills-payment-tab">
                                        <div>
                                            <h5>Topic Reference</h5>
                                            <p class="text-muted">Fill all information below</p>
                                        </div>

                                        <div>
                                            <div class="row gy-3">
                                               
                                                <div class="col-12">
                                                    <label for="address2" class="form-label">Examples</label>
                                                    <input type="text" class="form-control" id="examples" name="examples"
                                                        placeholder="{{$content->examples}}" />
                                                </div>
                                                <div class="col-12">
                                                    <label for="address2" class="form-label">Reference</label>
                                                    <input type="file" class="form-control" id="reference" name="reference"
                                                        placeholder="{{$content->reference}}" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button" class="btn btn-light btn-label previestab"
                                                data-previous="v-pills-bill-address-tab"><i
                                                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                Back to Reference</button>
                                            <button type="button"
                                                class="btn btn-success btn-label right ms-auto nexttab generateButton"
                                                data-nexttab="v-pills-finish-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                Re-Generate Content</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                    <div class="tab-pane fade" id="v-pills-finish" role="tabpanel"
                                        aria-labelledby="v-pills-finish-tab">
                                        <div class="text-center pt-4 pb-2 center-content">
                                            <div class="mb-4">
                                                <lord-icon id="almost"
                                                    src="https://cdn.lordicon.com/gzdzdtov.json"
                                                    trigger="loop"
                                                    state="loop-cycle"
                                                    style="width:100px;height:100px">
                                                </lord-icon>
                                            </div>
                                            <h5 id="h55">Almost there !</h5>
                                            <p class="text-muted chunkss" id="chunkss">Your content is almost ready.</p>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                </div>
                                <!-- end tab content -->
                            </div>
                        </div>
                        <!-- end col -->

                    </div>
                    <!-- end row -->
                </form>
            </div>
        </div>
        <!-- end -->
    </div>
    <!-- end col -->
</div>

    <script src="{{ URL::asset('build/js/pages/form-wizard.init.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Function to toggle visibility of question fields
        function toggleQuestionFields() {
            const questionFields = document.getElementById('question_fields');
            questionFields.style.display = document.getElementById('generate_questions').checked ? 'block' : 'none';
        }
        toggleQuestionFields(); // Ensure the state is correct on page load

        // Function to toggle visibility of image generation fields
    function toggleImageFields() {
        const imageFields = document.getElementById('image_fields');
        imageFields.style.display = document.getElementById('generate_images').checked ? 'block' : 'none';
    }
    toggleImageFields(); // Ensure the state is correct on page load
    </script>

    <script>
        $(document).ready(function() {
            $('#grade_id').on('change', function() {
                var gradeId = $(this).val();
                
                if (gradeId) {
                    $.ajax({
                        url: '/education/get-subjects/' + gradeId,
                        type: 'GET',
                        success: function(data) {
                            $('#subject_id').empty(); // Clear the subjects dropdown
                            $('#subject_id').append('<option selected>Select Subject</option>');
                            
                            // Populate subjects based on response
                            $.each(data, function(key, subject) {
                                $('#subject_id').append('<option value="' + subject.id + '">' + subject.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#subject_id').empty(); // Clear the subjects dropdown if no grade selected
                    $('#subject_id').append('<option selected>Select Subject</option>');
                }
            });
        });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".vertical-navs-step");
    const finishTab = document.getElementById("v-pills-finish");

    const generateButton = document.querySelector(".generateButton");
generateButton.addEventListener("click", function () {
    const formData = new FormData(form);
    fetch(form.getAttribute("action"), {
        method: form.getAttribute("method"),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => {
            const reader = response.body.getReader();
            const decoder = new TextDecoder();
            let content = "";  // Variable to store the entire content

            const processStream = ({ done, value }) => {
                if (done) {
                    // Hide loading icon once done
                    const lordIconElement = document.getElementById('almost');
                    if (lordIconElement) {
                        lordIconElement.style.display = 'none';
                    }

                    // Change h5 content to "Your content is ready"
                    const h5Element = document.querySelector("#h55");
                    if (h5Element) {
                        h5Element.textContent = "Your content is ready!";
                    }

                    // Replace the p tag content with the full content
                    const pElement = document.querySelector("#chunkss");
                    if (pElement) {
                        pElement.innerHTML = content;  // Replacing the entire content once streaming is done
                    }
                    
                    console.log("Stream complete");
                    return;
                }

                const parentDiv = document.querySelector('.center-content');
                if (parentDiv) {
                    parentDiv.classList.remove('text-center');
                    parentDiv.classList.remove('pt-4');
                }   
                // Append new chunks to content
                content += decoder.decode(value, { stream: true });

                // Optionally: Show the content progressively while streaming (uncomment if you want to update it progressively)
                document.querySelector("#chunkss").innerHTML = content;

                // Continue reading more chunks
                return reader.read().then(processStream);
            };

            return reader.read().then(processStream);
        })
    .catch(error => {
        console.error('Error:', error);
        const h5Element = finishTab.querySelector("h5");
        h5Element.textContent = "There was an error processing your request.";
    });
});

});

    </script>


@endsection