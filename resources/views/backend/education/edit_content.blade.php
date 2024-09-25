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
                                                        <option value="4" {{ $content->age == 4 ? 'selected' : '' }}>4</option>
                                                        <option value="5" {{ $content->age == 5 ? 'selected' : '' }}>5</option>
                                                        <option value="6" {{ $content->age == 6 ? 'selected' : '' }}>6</option>
                                                        <option value="7" {{ $content->age == 7 ? 'selected' : '' }}>7</option>
                                                        <option value="8" {{ $content->age == 8 ? 'selected' : '' }}>8</option>
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
                                                <div class="col-12">
                                                    <label for="address" class="form-label">Topic</label>
                                                    <input type="text" class="form-control" id="topic" name="topic"
                                                        placeholder="{{$content->topic}}" required value="{{$content->topic}}">
                                                    <div class="invalid-feedback">What is the Content Topic about?</div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="additional_details" class="form-label">Additional Details Prompt
                                                        <span class="text-muted">(Optional)</span>
                                                    </label>
                                                    <textarea class="form-control" id="additional_details" name="additional_details" rows="2" 
                                                              placeholder="{{$content->additional_details}}"></textarea>
                                                </div>  
                                                
                                                <div class="col-12">
                                                    <label for="negative_word" class="form-label">Negative Word <span class="text-muted">(Optional)</span></label>
                                                    <input type="text" class="form-control" id="negative_words" name="negative_words"
                                                        placeholder="{{$content->negative_words}}" required>
                                                    <div class="invalid-feedback">Which words you don't want to include in your content?</div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="question_type" class="form-label">Question Type <span class="text-muted">(Optional)</span></label>
                                                    <input type="text" class="form-control" id="question_type" name="question_type"
                                                        placeholder="{{$content->question_type}}" required>
                                                    <div class="invalid-feedback">What is the question type?</div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="points" class="form-label">How many Questions to Include? <span class="text-muted">(Optional)</span></label>
                                                    <input type="text" class="form-control" id="points" name="points"
                                                        placeholder="{{$content->points}}" required>
                                                    <div class="invalid-feedback">How many points to include?</div>
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