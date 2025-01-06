@extends('admin.layouts.master')
@section('title', $seo->title)

@section('description', $seo->description)

@section('keywords', $seo->keywords)
@section('content')
@php
    // Define the button styles array at the top of the view file
    $buttonStyles = [
        'btn-outline-primary',
        'btn-outline-success',
        'btn-outline-warning',
        'btn-outline-info',
        'btn-outline-secondary',
        // Add more styles as needed
    ];
@endphp
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

                                <!-- Edit Grade Button -->
                                @can('education.manageGradeSubject.gradeEdit')
                                    <button type="button" class="btn btn-link p-0 edit-grade-btn" data-bs-toggle="modal" data-bs-target="#editGradeModal-{{ $item->id }}" aria-label="Edit Grade">
                                        <i class="ri-edit-line fs-16"></i>
                                    </button>
                                @endcan
                              
                                <!-- Delete Grade Button -->
                                @can('education.manageGradeSubject.gradeDelete')
                                    <button type="button" class="btn btn-link p-0 delete-grade-btn" data-bs-toggle="modal" data-bs-target="#deleteGradeModal-{{ $item->id }}" aria-label="Delete Grade">
                                        <i class="ri-delete-bin-line fs-16"></i>
                                    </button>
                                @endcan
                             
                            </a>
                            @endforeach
                        </div>
                    </div>
                
                    <div class="col-lg-9">
                        <div class="tab-content text-muted mt-3 mt-lg-0">
                            @foreach ($classes as $index => $item)
                            <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}" 
                                 id="custom-v-pills-{{ $item->id }}" 
                                 role="tabpanel" 
                                 aria-labelledby="custom-v-pills-{{ $item->id }}-tab">
                                 
                                @php
                                $buttonStyles = [
                                    'btn-outline-primary',
                                    'btn-outline-success',
                                    'btn-outline-warning',
                                    'btn-outline-info',
                                    'btn-outline-secondary',
                                ];
                                @endphp
                
                                @if ($item->subjects->isNotEmpty())
                                    <div class="subject-buttons">
                                        @foreach ($item->subjects as $subject)
                                            @php
                                            $randomStyle = $buttonStyles[array_rand($buttonStyles)];
                                            @endphp
                                            <button type="button" class="btn {{ $randomStyle }} waves-effect waves-light mb-2">
                                                {{ $subject->name }}

                                                <!-- Edit Subject Button -->
                                                @can('education.manageGradeSubject.subjectEdit')
                                                    <button type="button" class="btn btn-link p-0 edit-subject-btn" data-bs-toggle="modal" data-bs-target="#editSubjectModal-{{ $subject->id }}" aria-label="Edit Subject">
                                                        <i class="ri-edit-line fs-14"></i>
                                                    </button>
                                                @endcan
                                               
                                                <!-- Delete Subject Button -->
                                                @can('education.manageGradeSubject.subjectDelete')
                                                    <button type="button" class="btn btn-link p-0 delete-subject-btn" data-bs-toggle="modal" data-bs-target="#deleteSubjectModal-{{ $subject->id }}" aria-label="Delete Subject">
                                                        <i class="ri-delete-bin-line fs-14"></i>
                                                    </button>
                                                @endcan
                                              
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <p>No subjects allocated for this class.</p>
                                @endif
                            </div>
                
                            <!-- Edit Grade Modal -->
                            <div class="modal fade" id="editGradeModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editGradeLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editGradeLabel">Edit Grade</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('update.grade', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="grade-name-{{ $item->id }}" class="form-label">Grade Name</label>
                                                    <input type="text" class="form-control" id="grade-name-{{ $item->id }}" name="grade" value="{{ $item->grade }}">
                                                </div>
                                                <button type="submit" class="btn gradient-btn-save">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <!-- Edit Subject Modal -->
                            @foreach ($item->subjects as $subject)
                            <div class="modal fade" id="editSubjectModal-{{ $subject->id }}" tabindex="-1" aria-labelledby="editSubjectLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSubjectLabel">Edit Subject</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('update.subject', $subject->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="subject-name-{{ $subject->id }}" class="form-label">Subject Name</label>
                                                    <input type="text" class="form-control" id="subject-name-{{ $subject->id }}" name="subject" value="{{ $subject->name }}">
                                                </div>
                                                <button type="submit" class="btn gradient-btn-save">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Delete Grade Modal -->
                            <div class="modal fade" id="deleteGradeModal-{{ $item->id }}" tabindex="-1" aria-labelledby="deleteGradeLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteGradeLabel">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this grade? This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('delete.grade', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Subject Modal -->
                            <div class="modal fade" id="deleteSubjectModal-{{ $subject->id }}" tabindex="-1" aria-labelledby="deleteSubjectLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteSubjectLabel">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this subject? This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('delete.subject', $subject->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            @endforeach
                        </div>
                    </div>
                </div>
                
                
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
                                            <input type="text" name="grade" class="form-control mb-3" id="grade" placeholder="Enter Name" required>
                                        </div>
                                    <div class="text-end">
                                        <input type="submit" class="btn btn-rounded gradient-btn-save mb-5 disabled-on-load" disabled value="Add">
                                    </div>
                               
                                </form>
                            </div>

                            <div class="tab-pane fade" id="custom-v-pills-add-subject" role="tabpanel" aria-labelledby="custom-v-pills-profile-tab">
                               
                                <form method="POST" action="{{ route('store.grade.class') }}" class="row g-3">
                                    @csrf
                                    <input type="hidden" value="grade" name="subject_form">
                                
                                    <label for="Banner Text" class="form-label">Grade/Class</label>
                                    <select class="form-select" name="grade_id" data-choices aria-label="Default select grade" required>
                                        <option selected="" value="">Select Grade/Class</option>
                                        @foreach($classes as $item)
                                            <option value="{{ $item->id }}">{{ $item->grade }}</option>
                                        @endforeach
                                    </select>
                                    
                                    <div class="col-md-12">
                                        <label for="title" class="form-label">Subject</label>
                                        <input type="text" name="subject" class="form-control mb-3" id="subject" placeholder="Enter Subject Name" required>
                                    </div>
                                    
                                    <div class="text-end">
                                        <input type="submit" class="btn btn-rounded gradient-btn-save mb-5 disabled-on-load" disabled value="Add">
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

