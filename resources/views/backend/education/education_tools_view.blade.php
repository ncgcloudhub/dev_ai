@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('aicontentcreator.manage')}}">Education</a> @endslot
@slot('title') Manage Tools @endslot
@endcomponent


<div class="row">
    <div class="col-xxl-3">
        <div class="card" style="background-image: url('{{ asset('storage/' . $tool->image) }}'); background-size: cover; background-position: center; height: 200px; overflow: hidden;">
        </div>
        
        
        <!--end card-->
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex mb-3">
                    <h6 class="card-title mb-0 flex-grow-1">Similar Tools</h6>
                </div>
                <ul class="list-unstyled vstack gap-3 mb-0">
                    @foreach ($similarTools as $similarTool)
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $similarTool->image) }}" alt="" class="avatar-xs rounded-circle">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                  
                                    <h6 class="mb-1"><a href="  {{ route('tool.show', ['id' => $similarTool->id, 'slug' => $similarTool->slug]) }}" class="link-secondary">{{ $similarTool->name }}</a></h6>
                                    <p class="text-muted mb-0">{{ $similarTool->description ?? 'Tool Description' }}</p>
                                </div>
                             
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-3 text-center">
                    <button type="button" class="btn btn-primary">View more</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Explore More Categories</h5>
                <div class="vstack gap-2">
                    <div class="hstack flex-wrap gap-2 fs-15">
                        <div class="badge fw-medium bg-secondary-subtle text-secondary">UI/UX</div>
                        <div class="badge fw-medium bg-secondary-subtle text-secondary">Dashboard</div>
                        <div class="badge fw-medium bg-secondary-subtle text-secondary">Design</div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    <!---end col-->
    <div class="col-xxl-9">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">
                    <h6 class="mb-3 fw-semibold text-uppercase">{{$tool->name}}</h6>
                    <p>{{$tool->description}}</p>

                        <form action="" method="POST" id="generate-content-form">
                            @csrf
                            <input type="hidden" name="tool_id" value="{{ $tool->id }}">
                            <div class="form-group mb-3">
                            <select class="form-select" name="grade_id" data-choices aria-label="Default select grade">
                                <option selected="">Select Grade/Class</option>
                                @foreach($classes as $item)
                                    <option value="{{$item->id}}">{{$item->grade}}</option>
                                @endforeach
                            </select>
                            </div>
                
                            <!-- Loop through input types and labels -->
                            @foreach (json_decode($tool->input_types) as $index => $input_type)
                                <div class="form-group mb-3">
                                    <label for="input_{{ $index }}">{{ json_decode($tool->input_labels)[$index] }}</label>
                
                                    @if ($input_type == 'textarea')
                                        <textarea class="form-control" id="input_{{ $index }}" name="input_{{ $index }}" rows="4" placeholder="{{ json_decode($tool->input_placeholders)[$index] }}"></textarea>
                                    @else
                                        <input type="{{ $input_type }}" class="form-control" id="input_{{ $index }}" name="input_{{ $index }}" placeholder="{{ json_decode($tool->input_placeholders)[$index] }}">
                                    @endif
                                </div>
                            @endforeach
                
                            <!-- Submit Button -->
                            <button type="submit" class="btn gradient-btn-5 disabled-on-load" id="educationToolsGenerate" disabled>
                                <i class="ri-auction-fill align-bottom me-1"></i>Generate
                            </button>
                        </form>
  
                </div>
            </div>
        </div>
        <!--end card-->
        <div class="card">
            <div class="card-header">
                <div>
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#stream-output" role="tab">
                                Output
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#messages-1" role="tab">
                               Generated Content
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#profile-1" role="tab">
                                All Contents
                            </a>
                        </li>
                    </ul>
                    <!--end nav-->
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    
                    <div class="tab-pane active" id="stream-output" role="tabpanel">
                        
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="messages-1" role="tabpanel">
                        <div class="table-responsive table-card">
                            <table class="table align-middle mb-0">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th scope="col">Tool</th>
                                        <th scope="col">Prompt</th>
                                        <th scope="col">Content</th>
                                        <th scope="col">Action</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($toolContent as $content)
                                    <tr>
                                        <th scope="row">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ URL::asset('build/images/users/avatar-8.jpg') }}" alt=""
                                                    class="rounded-circle avatar-xxs">
                                                <div class="flex-grow-1 ms-2">
                                                    <a href="pages-profile"
                                                        class="fw-medium link-secondary">Tool ID: {{ $content->tool_id }}</a>
                                                </div>
                                            </div>
                                        </th>
                                        <td>{{ $content->prompt }}</td>
                                        <td>{{ $content->content }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="openToolContentEditorModal({{ $content->id }})">
                                                Edit
                                            </button>
                                        </td>
                                        
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!--end table-->
                        </div>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="profile-1" role="tabpanel">
                        <h6 class="card-title mb-4 pb-2">Time Entries</h6>
                        <div class="table-responsive table-card">
                            <table class="table align-middle mb-0">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th scope="col">Tool</th>
                                        <th scope="col">Prompt</th>
                                        <th scope="col">Content</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allToolContent as $content)
                                    <tr>
                                        <th scope="row">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ URL::asset('build/images/users/avatar-8.jpg') }}" alt=""
                                                    class="rounded-circle avatar-xxs">
                                                <div class="flex-grow-1 ms-2">
                                                    <a href="pages-profile"
                                                        class="fw-medium link-secondary">Tool ID: {{ $content->tool_id }}</a>
                                                </div>
                                            </div>
                                        </th>
                                        <td>{{ $content->prompt }}</td>
                                        <td>{{ $content->content }}</td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!--end table-->
                        </div>
                    </div>
                    <!--edn tab-pane-->

                </div>
                <!--end tab-content-->
            </div>
        </div>
        <!--end card-->
    </div>
    <!--end col-->
</div>
<!--end row-->
<div class="modal fade" id="editToolContentModal" tabindex="-1" aria-labelledby="editToolContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editToolContentModalLabel">Edit Tool Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editToolContentForm">
                    @csrf
                
                    <div class="mb-3">
                        <label for="editContent" class="form-label">Content</label>
                        <textarea class="form-control" id="editContent" name="content" rows="8" required></textarea>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="saveEditedToolContent()">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="inviteMembersModal" tabindex="-1" aria-labelledby="inviteMembersModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 ps-4 bg-primary-subtle">
                <h5 class="modal-title" id="inviteMembersModalLabel">Team Members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="search-box mb-3">
                    <input type="text" class="form-control bg-light border-light" placeholder="Search here...">
                    <i class="ri-search-line search-icon"></i>
                </div>

                <div class="mb-4 d-flex align-items-center">
                    <div class="me-2">
                        <h5 class="mb-0 fs-13">Members :</h5>
                    </div>
                    <div class="avatar-group justify-content-center">
                        <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-placement="top" title="Tonya Noble">
                            <div class="avatar-xs">
                                <img src="{{ URL::asset('build/images/users/avatar-10.jpg') }}" alt="" class="rounded-circle img-fluid">
                            </div>
                        </a>
                        <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-placement="top" title="Thomas Taylor">
                            <div class="avatar-xs">
                                <img src="{{ URL::asset('build/images/users/avatar-8.jpg') }}" alt="" class="rounded-circle img-fluid">
                            </div>
                        </a>
                        <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-placement="top" title="Nancy Martino">
                            <div class="avatar-xs">
                                <img src="{{ URL::asset('build/images/users/avatar-2.jpg') }}" alt="" class="rounded-circle img-fluid">
                            </div>
                        </a>
                    </div>
                </div>
                <div class="mx-n4 px-4" data-simplebar style="max-height: 225px;">
                    <div class="vstack gap-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs flex-shrink-0 me-3">
                                <img src="{{ URL::asset('build/images/users/avatar-2.jpg') }}" alt="" class="img-fluid rounded-circle">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-13 mb-0"><a href="javascript:void(0);"
                                        class="text-body d-block">Nancy Martino</a></h5>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-light btn-sm">Add</button>
                            </div>
                        </div>
                        <!-- end member item -->
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs flex-shrink-0 me-3">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    HB
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-13 mb-0"><a href="javascript:void(0);"
                                        class="text-body d-block">Henry Baird</a></h5>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-light btn-sm">Add</button>
                            </div>
                        </div>
                        <!-- end member item -->
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs flex-shrink-0 me-3">
                                <img src="{{ URL::asset('build/images/users/avatar-3.jpg') }}" alt="" class="img-fluid rounded-circle">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-13 mb-0"><a href="javascript:void(0);"
                                        class="text-body d-block">Frank Hook</a></h5>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-light btn-sm">Add</button>
                            </div>
                        </div>
                        <!-- end member item -->
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs flex-shrink-0 me-3">
                                <img src="{{ URL::asset('build/images/users/avatar-4.jpg') }}" alt="" class="img-fluid rounded-circle">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-13 mb-0"><a href="javascript:void(0);"
                                        class="text-body d-block">Jennifer Carter</a></h5>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-light btn-sm">Add</button>
                            </div>
                        </div>
                        <!-- end member item -->
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs flex-shrink-0 me-3">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    AC
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-13 mb-0"><a href="javascript:void(0);"
                                        class="text-body d-block">Alexis Clarke</a></h5>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-light btn-sm">Add</button>
                            </div>
                        </div>
                        <!-- end member item -->
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs flex-shrink-0 me-3">
                                <img src="{{ URL::asset('build/images/users/avatar-7.jpg') }}" alt="" class="img-fluid rounded-circle">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-13 mb-0"><a href="javascript:void(0);"
                                        class="text-body d-block">Joseph Parker</a></h5>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-light btn-sm">Add</button>
                            </div>
                        </div>
                        <!-- end member item -->
                    </div>
                    <!-- end list -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light w-xs" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary w-xs">Assigned</button>
            </div>
        </div>
        <!-- end modal-content -->
    </div>
    <!-- modal-dialog -->
</div>


{{-- <div class="row">
    <div class="col-lg-8">
        
        <h1>Generate for {{ $tool->name }}</h1>

        <form action="" method="POST" id="generate-content-form">
            @csrf
            <input type="hidden" name="tool_id" value="{{ $tool->id }}">
            <div class="form-group mb-3">
            <select class="form-select" name="grade_id" data-choices aria-label="Default select grade">
                <option selected="">Select Grade/Class</option>
                @foreach($classes as $item)
                    <option value="{{$item->id}}">{{$item->grade}}</option>
                @endforeach
            </select>
            </div>

            <!-- Loop through input types and labels -->
            @foreach (json_decode($tool->input_types) as $index => $input_type)
                <div class="form-group mb-3">
                    <label for="input_{{ $index }}">{{ json_decode($tool->input_labels)[$index] }}</label>

                    @if ($input_type == 'textarea')
                        <textarea class="form-control" id="input_{{ $index }}" name="input_{{ $index }}" rows="4" placeholder="{{ json_decode($tool->input_names)[$index] }}"></textarea>
                    @else
                        <input type="{{ $input_type }}" class="form-control" id="input_{{ $index }}" name="input_{{ $index }}" placeholder="{{ json_decode($tool->input_names)[$index] }}">
                    @endif
                </div>
            @endforeach

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">
                <i class="ri-auction-fill align-bottom me-1"></i>Generate
            </button>
        </form>


        <div id="stream-output"></div>
</div> --}}



@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script>
    $(document).ready(function () {
    $('form').on('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting the traditional way

        let formData = $(this).serialize(); // Collect the form data

        $.ajax({
            type: 'POST',
            url: '{{ route("tools.generate.content") }}', // Update with your route
            data: formData,
            xhrFields: {
                onprogress: function (event) {
                    const contentChunk = event.currentTarget.responseText;
                    $('#stream-output').html(contentChunk); // Update the stream output div
                }
            },
            success: function (response) {
                console.log('Content generation completed.');
            },
            error: function (error) {
                console.error('Error during content generation:', error);
            }
        });
    });
});



// Open modal and populate with content data
function openToolContentEditorModal(contentId) {
    fetch(`/education/toolContent/${contentId}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            document.getElementById('editContent').value = data.content;
            document.getElementById('editToolContentModal').setAttribute('data-content-id', contentId);

            // Show the modal
            var editModal = new bootstrap.Modal(document.getElementById('editToolContentModal'));
            editModal.show();
        })
        .catch(error => console.error('Error:', error));
}


// Save edited content via AJAX
function saveEditedToolContent() {
    const contentId = document.getElementById('editToolContentModal').getAttribute('data-content-id');
    const updatedContent = {
        content: document.getElementById('editContent').value
    };

    fetch(`/education/toolContent/${contentId}/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(updatedContent)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Content updated successfully!');
            var editModal = bootstrap.Modal.getInstance(document.getElementById('editToolContentModal'));
            editModal.hide();
            // Optionally, update the UI with the new content
        } else {
            alert('Failed to update content.');
        }
    })
    .catch(error => console.error('Error:', error));
}


</script>

@endsection