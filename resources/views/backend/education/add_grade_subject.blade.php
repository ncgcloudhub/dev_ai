@extends('admin.layouts.master')

@section('content')

<div class="row">
    <div class="col-xxl-6">
        <h5 class="mb-3">Grade & Subject</h5>
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Select <code>Grade/Class</code> to see respective subjects .</p>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center" role="tablist" aria-orientation="vertical">
                            @foreach ($classes as $index => $item)
                            <a class="nav-link {{ $loop->first ? 'active show' : '' }}" 
                               id="custom-v-pills-{{ $item->id }}-tab" 
                               data-bs-toggle="pill" 
                               href="#custom-v-pills-{{ $item->id }}" 
                               role="tab" 
                               aria-controls="custom-v-pills-{{ $item->id }}" 
                               aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                                {{ $item->grade }}
                            </a>
                        @endforeach
                        
                        </div>
                    </div> <!-- end col-->
                    <div class="col-lg-9">
                        <div class="tab-content text-muted mt-3 mt-lg-0">
                            @foreach ($classes as $index => $item)
                            <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}" 
                                 id="custom-v-pills-{{ $item->id }}" 
                                 role="tabpanel" 
                                 aria-labelledby="custom-v-pills-{{ $item->id }}-tab">
                            
                                 @php
                                 // Array of possible button styles
                                 $buttonStyles = [
                                     'btn-outline-primary',
                                     'btn-outline-success',
                                     'btn-outline-warning',
                                     'btn-outline-info',
                                     'btn-outline-secondary',
                                     // Add more styles as needed
                                 ];
                             @endphp
                             
                             @if ($item->subjects->isNotEmpty())
                                 <div class="subject-buttons">
                                     @foreach ($item->subjects as $subject)
                                         @php
                                             // Pick a random style from the array
                                             $randomStyle = $buttonStyles[array_rand($buttonStyles)];
                                         @endphp
                                         <button type="button" class="btn {{ $randomStyle }} waves-effect waves-light mb-2">
                                             {{ $subject->name }}
                                         </button>
                                     @endforeach
                                 </div>
                             @else
                                 <p>No subjects allocated for this class.</p>
                             @endif
                             
                        
                               
                            </div>
                        @endforeach
                        

                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row-->
            </div><!-- end card-body -->
        </div><!--end card-->
    </div><!--end col-->
    
    
    {{-- Add Grade and Subject --}}
    <div class="col-xxl-6">
        <h5 class="mb-3">Add Grade & Subject</h5>
        <div class="card">
            <div class="card-body">
              
                <div class="row">
                    <div class="col-lg-3">
                        <div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active show" id="custom-v-pills-grade-tab" data-bs-toggle="pill" href="#custom-v-pills-add-grade" role="tab" aria-controls="custom-v-pills-add-grade"
                                aria-selected="true">
                                <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                                Grade/Class</a>
                            <a class="nav-link" id="custom-v-pills-profile-tab" data-bs-toggle="pill" href="#custom-v-pills-add-subject" role="tab" aria-controls="custom-v-pills-add-subject"
                                aria-selected="false">
                                <i class="ri-user-2-line d-block fs-20 mb-1"></i>
                                Subject</a>
                           
                        </div>
                    </div> <!-- end col-->
                    <div class="col-lg-9">
                        <div class="tab-content text-muted mt-3 mt-lg-0">
                            <div class="tab-pane fade active show" id="custom-v-pills-add-grade" role="tabpanel" aria-labelledby="custom-v-pills-home-tab">
                                
                                <form method="POST" action="{{route('store.grade.class')}}" class="row g-3">
                                    @csrf
                                    <input type="hidden" value="grade" name="grade_form">
                                        <div class="col-md-12">
                                            <label for="title" class="form-label">Grade/Class Name</label>
                                            <input type="text" name="grade" class="form-control mb-3" id="grade" placeholder="Enter Name">
                                        </div>
                                    <div class="text-end">
                                        <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Add">
                                    </div>
                               
                                </form>
                            </div>

                            <div class="tab-pane fade" id="custom-v-pills-add-subject" role="tabpanel" aria-labelledby="custom-v-pills-profile-tab">
                               
                                <form method="POST" action="{{route('store.grade.class')}}" class="row g-3">
                                    @csrf
                                    <input type="hidden" value="grade" name="subject_form">

                                    <label for="Banner Text" class="form-label">Grade/Class</label>
                                    <select class="form-select" name="grade_id" data-choices aria-label="Default select grade">
                                        <option selected="">Select Grade/Class</option>
                                        @foreach($classes as $item)
                                            <option value="{{$item->id}}">{{$item->grade}}</option>
                                        @endforeach
                                    </select>
                                        <div class="col-md-12">
                                            <label for="title" class="form-label">Subject</label>
                                            <input type="text" name="subject" class="form-control mb-3" id="subject" placeholder="Enter Subject Name">
                                        </div>
                                    <div class="text-end">
                                        <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Add">
                                    </div>
                               
                                 </form>
                            </div><!--end tab-pane-->
                          
                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row-->
            </div><!-- end card-body -->
        </div><!--end card-->
    </div><!--end col-->
</div>
@endsection



























@extends('admin.layouts.master')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Vertical nav Steps</h4>
            </div><!-- end card header -->
            <div class="card-body form-steps">
                <form class="vertical-navs-step">
                    <div class="row gy-5">
                        <div class="col-lg-3">
                            <div class="nav flex-column custom-nav nav-pills" role="tablist"
                                aria-orientation="vertical">
                                <button class="nav-link active done" id="v-pills-bill-info-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-info" type="button" role="tab"
                                    aria-controls="v-pills-bill-info" aria-selected="true">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 1
                                    </span>
                                    Basic Info
                                </button>
                                <button class="nav-link " id="v-pills-bill-address-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-address" type="button" role="tab"
                                    aria-controls="v-pills-bill-address" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 2
                                    </span>
                                    Additional Details
                                </button>
                                <button class="nav-link" id="v-pills-payment-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-payment" type="button" role="tab"
                                    aria-controls="v-pills-payment" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 3
                                    </span>
                                    Reference
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
                                                    <select class="form-select" name="grade_id" data-choices aria-label="Default select grade">
                                                        <option selected="">Select Grade/Class</option>
                                                        @foreach($classes as $item)
                                                            <option value="{{$item->id}}">{{$item->grade}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a first name</div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">Age</label>
                                                    <select class="form-select" name="age" data-choices aria-label="Default select age">
                                                        <option selected="">Choose Age</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        
                                                    </select>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label class="form-label">Content Difficulty Level</label>
                                                    <select class="form-select" name="difficult_level" data-choices aria-label="Default select difficulty">
                                                        <option selected="">Select Level</option>
                                                        <option value="4">Easy</option>
                                                        <option value="Medium">Medium</option>
                                                        <option value="Difficult">Difficult</option>
                                                        <option value="Exceptional">Exceptional</option>
                                                       
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a first name</div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">Tone</label>
                                                    <select class="form-select" name="tone" data-choices aria-label="Default select tone">
                                                        <option selected="">Choose Tone</option>
                                                        <option value="Kids">Kids</option>
                                                        <option value="Adult">Adult</option>
                                                     
                                                        
                                                    </select>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">Persona</label>
                                                    <select class="form-select" name="persona" data-choices aria-label="Default select persona">
                                                        <option selected="">Choose Persona</option>
                                                        <option value="Kids">Very Simple</option>
                                                        <option value="Adult">Step by Step guide</option>
                                                        <option value="Adult">Simple</option>
                                                        <option value="Adult">Somewhat Difficulty</option>
                                                     
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button"
                                                class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                data-nexttab="v-pills-bill-address-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Move to Step 2</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                    <div class="tab-pane fade" id="v-pills-bill-address" role="tabpanel"
                                        aria-labelledby="v-pills-bill-address-tab">
                                        <div>
                                            <h5>Additional Info</h5>
                                            <p class="text-muted">Fill all information below</p>
                                        </div>

                                        <div>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label for="address" class="form-label">Topic</label>
                                                    <input type="text" class="form-control" id="topic" name="topic"
                                                        placeholder="1234 Main St" required>
                                                    <div class="invalid-feedback">What is the Content Topic about?</div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="address2" class="form-label">Additional Details Prompt<span
                                                            class="text-muted">(Optional)</span></label>
                                                    <input type="text" class="form-control" id="additional_details" name="additional_details"
                                                        placeholder="More details about the topic" />
                                                </div>
  
                                            </div>

                                        </div>
                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button" class="btn btn-light btn-label previestab"
                                                data-previous="v-pills-bill-info-tab"><i
                                                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                Back to Basic Info</button>
                                            <button type="button"
                                                class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                data-nexttab="v-pills-payment-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go
                                                Proceed to Step 3</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                    <div class="tab-pane fade" id="v-pills-payment" role="tabpanel"
                                        aria-labelledby="v-pills-payment-tab">
                                        <div>
                                            <h5>References</h5>
                                            <p class="text-muted">Fill all information below</p>
                                        </div>

                                        <div>

                                            <div class="row gy-3">
                                               
                                                <div class="col-12">
                                                    <label for="address2" class="form-label">Examples</label>
                                                    <input type="text" class="form-control" id="examples" name="examples"
                                                        placeholder="More details about the topic" />
                                                </div>
                                                <div class="col-12">
                                                    <label for="address2" class="form-label">Reference</label>
                                                    <input type="file" class="form-control" id="reference" name="reference"
                                                        placeholder="Reference" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button" class="btn btn-light btn-label previestab"
                                                data-previous="v-pills-bill-address-tab"><i
                                                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                Back to Additional Details</button>
                                            <button type="button"
                                                class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                data-nexttab="v-pills-finish-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                               Generate Content</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                    <div class="tab-pane fade" id="v-pills-finish" role="tabpanel"
                                        aria-labelledby="v-pills-finish-tab">
                                        <div class="text-center pt-4 pb-2">

                                            <div class="mb-4">
                                                <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop"
                                                    colors="primary:#25a0e2,secondary:#00bd9d"
                                                    style="width:120px;height:120px"></lord-icon>
                                            </div>
                                            <h5>Your Order is Completed !</h5>
                                            <p class="text-muted">You Will receive an order confirmation email with
                                                details of your order.</p>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                </div>
                                <!-- end tab content -->
                            </div>
                        </div>
                        <!-- end col -->

                        <div class="col-lg-3">
                         
                          
                        </div>
                    </div>
                    <!-- end row -->
                </form>
            </div>
        </div>
        <!-- end -->
    </div>
    <!-- end col -->
</div>

@endsection