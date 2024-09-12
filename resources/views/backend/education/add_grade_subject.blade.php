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