@extends('admin.layouts.master')

@section('content')

<div class="row">
    <div class="col-xxl-4">
        <h5 class="mb-3">Grade & Subject</h5>
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Select <code>Grade/Class</code> to see respective content.</p>
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
                                                <button type="button" class="btn {{ $randomStyle }} waves-effect waves-light mb-2" 
                                                        data-subject-id="{{ $subject->id }}">
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
        </div><!-- end card-->
    </div><!-- end col -->

    
    {{-- Add Grade and Subject --}}
    <div class="col-xxl-8 d-flex" id="content-display">
       
    
    </div><!--end col-->
</div>

<!-- Modal for displaying content -->
<div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="contentModalLabel">Content Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modal-content-body">
          <!-- Content will be loaded here via AJAX -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.subject-buttons button').forEach(button => {
            button.addEventListener('click', function () {
                const subjectId = this.getAttribute('data-subject-id');
    
                fetch('{{ route('education.getContentsBySubject') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ subject_id: subjectId })
                })
                .then(response => response.json())
                .then(data => {
                    const contentDisplay = document.getElementById('content-display');
                    contentDisplay.innerHTML = ''; // Clear previous content
    
                    if (data.contents.length > 0) {
            data.contents.forEach(content => {
                const contentElement = document.createElement('div');
                contentElement.classList.add('col-12', 'col-md-6', 'col-lg-3');
                
                contentElement.innerHTML = `
                    <div class="card border-end">
                        <div class="card-body text-center">
                            <h5 class="mb-0">${content.topic}</h5>
                            <p class="text-muted">Web Developer</p>
                            <div class="d-flex gap-2 justify-content-center mb-3">
                                <button type="button" class="btn avatar-xs p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Google">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-google-line"></i>
                                    </span>
                                </button>
                                <button type="button" class="btn avatar-xs p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Linkedin">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-linkedin-line"></i>
                                    </span>
                                </button>
                                <button type="button" class="btn avatar-xs p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Dribbble">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-dribbble-fill"></i>
                                    </span>
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-success rounded-pill w-sm" onclick="fetchContent(${content.id})">
                                    <i class="ri-add-fill me-1 align-bottom"></i>Details
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                contentDisplay.appendChild(contentElement);
            });
                    } else {
                        contentDisplay.innerHTML = '<p>No content available for this subject.</p>';
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
    
    function fetchContent(contentId) {
        fetch('{{ route('education.getContentById') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ content_id: contentId })
        })
        .then(response => response.json())
        .then(data => {
            // Load the content into the modal
            document.getElementById('modal-content-body').innerHTML = `
                <h6>${data.content.tone}</h6>
                ${data.content.generated_content}
            `;
            // Show the modal
            var contentModal = new bootstrap.Modal(document.getElementById('contentModal'));
            contentModal.show();
        })
        .catch(error => console.error('Error:', error));
    }
    </script>


@endsection

