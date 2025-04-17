@extends('admin.layouts.master')
@section('title') Education Tools | {{$tool->name}} @endsection
@section('sidebar-size', 'sm') <!-- This sets the sidebar size for this page -->
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('manage.education.tools')}}">Education Tools</a> @endslot
@slot('title') {{$tool->name}} @endslot
@endcomponent

<div class="row">
    
    <div class="col-xxl-3">
        <button type="button" class="btn gradient-btn-5 mb-3" title="Go Back" onclick="history.back()">
            <i class="las la-arrow-left"></i>
        </button>
        <div class="explore-place-bid-img">
        <img src="{{ asset('storage/' . $tool->image) }}" alt="" class="card-img-top explore-img" />
        </div>
        
        <!--end card-->
        <div class="card mb-3 mt-3 border-color-purple">
            <div class="card-body">
                <div class="d-flex mb-3 align-items-center">
                    <h6 class="card-title mb-0 flex-grow-1 gradient-text-1-bold">Similar Tools</h6>
                    <a href="{{route('manage.education.tools')}}" class="ms-auto">
                        <span class="badge badge-gradient-purple">More Tools</span>
                    </a>
                </div>
                
                <ul class="list-unstyled vstack gap-3 mb-0">
                    @foreach ($similarTools as $similarTool)
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $similarTool->image) }}" alt="" class="avatar-xs rounded-3">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                  
                                    <h6 class="mb-1"><a href="{{ route('tool.show', ['id' => $similarTool->id, 'slug' => $similarTool->slug]) }}" class="gradient-text-1">{{ $similarTool->name }}</a></h6>
                                    <p class="text-muted mb-0">{{ $similarTool->description ?? 'Tool Description' }}</p>
                                </div>
                             
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="card border-color-purple">
            <div class="card-body">
                <h5 class="card-title mb-3 gradient-text-1-bold">Explore More Categories</h5>
                <div class="vstack gap-2">
                    <div class="hstack flex-wrap gap-2 fs-15">
                        @foreach ($categories as $item)
                        <a href="{{route('manage.education.tools')}}"><span class="badge badge-gradient-purple">{{$item->category_name}}</span></a>
                           
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    <!---end col-->
    <div class="col-xxl-9">
        <div class="card border-color-purple">
            <div class="card-body">
                <div class="row">
                    <div class="col"> 
                        <h3 class="mb-1 gradient-text-1-bold text-uppercase">{{$tool->name}}</h3>
                        <p class="gradient-text-2">{{$tool->description}}</p></div>
                    <div class="col">  
                        <div class="d-flex justify-content-end gap-2">

                            <button type="button" class="btn gradient-btn-5" id="clearInputsButton" title="Clear all the Input values">
                            <i class="las la-undo-alt"></i>Clear Inputs
                            </button>
                         
                    </div></div>
                    </div>
               
              <div class="row">
                <div class="col-10 bg-marketplace">
                    <form action="" method="POST" id="generate-content-form">
                        @csrf
                        <input type="hidden" name="tool_id" value="{{ $tool->id }}">
                    
                        <!-- Grade/Class Select Field -->
                        <div class="form-group mb-3">
                            <select class="form-select" name="grade_id" aria-label="Default select grade" required>
                                <option selected disabled value="">Select Grade/Class</option>
                                @foreach($classes as $item)
                                    <option value="{{ $item->id }}">{{ $item->grade }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select a valid grade/class.</div>
                        </div>
                    
                        <!-- Dynamic Input Fields -->
                        @foreach (json_decode($tool->input_types) as $index => $input_type)
                            <div class="form-group mb-3">
                                <label for="input_{{ $index }}">{{ json_decode($tool->input_labels)[$index] }}</label>
                    
                                @if ($input_type == 'textarea')
                                <div class="position-relative">
                                    <textarea class="form-control pe-5" id="input_{{ $index }}" name="input_{{ $index }}" rows="4"
                                        placeholder="{{ json_decode($tool->input_placeholders)[$index] }}" required></textarea>
                                    <button type="button" class="speech-btn btn btn-link position-absolute top-50 end-0 translate-middle-y">
                                        <i class="mic-icon ri-mic-line fs-4"></i>
                                    </button>
                                </div>
                                @else
                                <div class="position-relative">
                                    <input type="{{ $input_type }}" class="form-control pe-5" id="input_{{ $index }}" name="input_{{ $index }}"
                                        placeholder="{{ json_decode($tool->input_placeholders)[$index] }}" required>
                                    <button type="button" class="speech-btn btn btn-link position-absolute top-50 end-0 translate-middle-y">
                                        <i class="mic-icon ri-mic-line fs-4"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                        @endforeach

                        <!-- Image Inclusion Option -->
                        <div class="row mb-3">
                            <!-- Include Images -->
                            <div class="col-md-6">
                                <label for="include_images">Include Images?</label>
                                <select class="form-select" name="include_images" id="include_images" required>
                                    <option selected disabled value="">Select</option>
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>
                        
                            <!-- Image Model (Initially Hidden) -->
                            <div class="col-md-6 d-none" id="image-model-group">
                                <label for="image_model">Image Model</label>
                                <select class="form-select" name="image_model" id="image_model">
                                    <option selected disabled value="">Choose one</option>
                                    <option value="dalle2">DALL·E 2 (1 Credit)</option>
                                    <option value="dalle3">DALL·E 3 (2 Credits)</option>
                                    <option value="sd">Stable Diffusion (3 Credits)</option>
                                </select>
                            </div>
                        </div>
                        

                    
                        <!-- Submit Button -->
                        <button type="submit" class="btn gradient-btn-5 disabled-on-load" id="educationToolsGenerate" disabled>
                            <i class="ri-auction-fill align-bottom me-1"></i>Generate
                        </button>
                    </form>
                    
                </div>
                <div class="col-2">
                    <img src="/build/images/nft/edu_01.png" alt="" class="img-fluid">
                </div>
              </div>
               
            </div>
        </div>
        <!--end card-->
        <div class="card">
            <div class="card-header">
                <div>
                    <ul class="nav nav-pills nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#stream-output1" role="tab">
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
                    
                    <div class="tab-pane active" id="stream-output1" role="tabpanel">
                        <div id="stream-output"></div>
                        <div id="generation-status" style="margin-top: 10px; font-weight: bold;"></div>
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
                                                    <a href="pages-profile" class="fw-medium link-secondary">{{ $content->tool->name }}</a>
                                                </div>
                                            </div>
                                        </th>
                                        <td>{{ $content->prompt }}</td>
                                        <td>
                                            {{-- Display truncated content --}}
                                            {!! Str::words(strip_tags($content->formatted_content), 50, '...') !!}
                    
                                            {{-- Read More Button --}}
                                            <button type="button" class="btn btn-link text-primary p-0" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewFullContentModal{{ $content->id }}">
                                                Read More
                                            </button>
                                              {{-- ONLY SHOW SLIDE BUTTON FOR PRESENTATION TOOLS --}}
                                            {{-- @if(str_contains(strtolower($content->tool->slug), 'presentation') || 
                                                str_contains(strtolower($content->tool->slug), 'slide'))
                                                @if (!is_null(auth()->user()->google_token))
                                                <button type="button" class="btn btn-sm btn-success ms-1 generate-slides-btn" 
                                                        data-content-id="{{ $content->id }}"
                                                        data-content="{{ json_encode($content->content) }}">
                                                    Generate Slides
                                                </button>
                                                @else
                                                    <a href="{{ route('google.login') }}" class="btn btn-sm btn-warning ms-1">
                                                        Login with Google to Generate Slides
                                                    </a>
                                                @endif
                                            
                                            @endif --}}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    onclick="openToolContentEditorModal({{ $content->id }})">
                                                Edit
                                            </button>
                                            <a href="{{ route('toolContent.download', $content->id) }}" class="btn btn-sm btn-success">
                                                Download PDF
                                            </a>
                                            {{-- Modal for Full Content --}}
                                            <div class="modal fade" id="viewFullContentModal{{ $content->id }}" tabindex="-1" 
                                                aria-labelledby="viewFullContentModalLabel{{ $content->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewFullContentModalLabel{{ $content->id }}">
                                                            Full Content
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{-- Display full formatted content --}}
                                                        {!! $content->formatted_content !!}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                                        class="fw-medium link-secondary">{{ $content->tool->name }}</a>
                                                </div>
                                            </div>
                                        </th>
                                        <td>{{ $content->prompt }}</td>
                                        <td>
                                            {{-- Display truncated content --}}
                                            {!! Str::words(strip_tags($content->formatted_content), 50, '...') !!}
                    
                                            {{-- Read More Button --}}
                                            <button type="button" class="btn btn-link text-primary p-0"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewFullContentModalAll{{ $content->id }}">
                                                Read More
                                            </button>
                                            {{-- Modal for Full Content --}}
                                            <div class="modal fade" id="viewFullContentModalAll{{ $content->id }}" tabindex="-1"
                                                aria-labelledby="viewFullContentModalLabelAll{{ $content->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewFullContentModalLabelAll{{ $content->id }}">
                                                            Full Content
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{-- Display full formatted content --}}
                                                        {!! $content->formatted_content !!}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </td>
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

@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script>
    $(document).ready(function () {
        $('form').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            $('#generation-status').text('Generating...'); // Show generation status
            showMagicBall('image'); // Show loader

            let formData = $(this).serialize(); // Serialize form data

            $.ajax({
                type: 'POST',
                url: '{{ route("tools.generate.content") }}',
                data: formData,
                xhrFields: {
                    onprogress: function (event) {
                        const contentChunk = event.currentTarget.responseText;
                        $('#stream-output').html(contentChunk); // Stream response
                        hideMagicBall();
                    }
                },
                success: function (response) {
                    $('#generation-status').text('✅ Generation completed!');

                    // Now fetch the content_id and content_data stored in session
                    $.ajax({
                    type: 'GET',
                    url: '{{ route("tools.get.generated.content") }}', // You need to create this route
                    success: function (data) {
                       
                        const button = document.createElement('button');
                        button.className = 'btn btn-sm btn-success ms-1 generate-slides-btn';
                        button.setAttribute('type', 'button');
                        button.setAttribute('data-content-id', data.edu_tool_content_id);
                        button.dataset.content = data.edu_tool_content_data;
                        button.textContent = 'Generate Slides';

                        // document.querySelector('#generation-status').after(button);


                        $('#generation-status').after(generateSlidesButton); // Insert button after the status text
                    },
                    error: function (error) {
                        console.error('Error fetching content data:', error);
                    }
                });

                console.log('Content generation completed.');
                },
                error: function (error) {
                    hideMagicBall(); // Hide loader
                    $('#generation-status').text('❌ Failed to generate content.');
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

    // Clear all input values
    document.getElementById('clearInputsButton').addEventListener('click', function() {
            // Reset the form (clear inputs)
            document.getElementById('generate-content-form').reset();
    });

</script>

{{-- Slides --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.body.addEventListener('click', function (event) {
            const button = event.target.closest('.generate-slides-btn');
            if (!button) return;

            const contentId = button.getAttribute('data-content-id');
            // const content = JSON.parse(button.getAttribute('data-content'));
            const content = button.getAttribute('data-content');

            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...';
            button.disabled = true;

            fetch('/education/generate-slides', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    content_id: contentId,
                    content: content
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.presentationId) {
                        window.open(`https://docs.google.com/presentation/d/${data.presentationId}/edit`, '_blank');
                        Toastify({
                            text: "Slides generated successfully!",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#28a745",
                        }).showToast();
                    } else {
                        throw new Error(data.error || 'Failed to generate slides');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify({
                        text: error.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#dc3545",
                    }).showToast();
                })
                .finally(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        });
    });
</script>

<script>
    document.getElementById('include_images').addEventListener('change', function () {
        const imgGenGroup = document.getElementById('image-model-group');
        if (this.value === 'yes') {
            imgGenGroup.classList.remove('d-none');
        } else {
            imgGenGroup.classList.add('d-none');
            document.getElementById('image_model').value = '';
        }
    });
</script>



    

@endsection