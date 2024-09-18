@extends('admin.layouts.master')

@section('content')

<div class="row">
    <div class="col-xxl-6">
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
    
    
    
    <div class="col-xxl-5">
        <div class="card">
            <div class="row g-0">
                <div class="col-lg-6">
                    <div class="card-body border-end">
                        <div class="search-box">
                            <input type="text" class="form-control bg-light border-light" autocomplete="off"
                                id="searchList" placeholder="Search candidate...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                        <div data-simplebar style="max-height: 190px" class="px-3 mx-n3">
                            <ul class="list-unstyled mb-0 pt-2" id="candidate-list">
                                <li>
                                    <a href="javascript:void(0);" class="d-flex align-items-center py-2">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <img src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" alt=""
                                                    class="img-fluid rounded-circle candidate-img">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-13 mb-1 text-truncate"><span class="candidate-name">Anna
                                                    Adame</span> <span class="text-muted fw-normal">@Anna</span></h5>
                                            <div class="d-none candidate-position">Web Developer</div>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="javascript:void(0);" class="d-flex align-items-center py-2">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <img src="{{ URL::asset('build/images/users/avatar-2.jpg') }}" alt=""
                                                    class="img-fluid rounded-circle candidate-img">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-13 mb-1 text-truncate"><span
                                                    class="candidate-name">Patricia Cavin</span> <span
                                                    class="text-muted fw-normal">@Patricia</span></h5>
                                            <div class="d-none candidate-position">Web Developer</div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="d-flex align-items-center py-2">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <img src="{{ URL::asset('build/images/users/avatar-3.jpg') }}" alt=""
                                                    class="img-fluid rounded-circle candidate-img">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-13 mb-1 text-truncate"><span class="candidate-name">Jason
                                                    Tran</span> <span class="text-muted fw-normal">@Jason</span></h5>
                                            <div class="d-none candidate-position">Magento Developer</div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="d-flex align-items-center py-2">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <img src="{{ URL::asset('build/images/users/avatar-4.jpg') }}" alt=""
                                                    class="img-fluid rounded-circle candidate-img">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-13 mb-1 text-truncate"><span class="candidate-name">Cheryl
                                                    Moore</span> <span class="text-muted fw-normal">@Cheryl</span>
                                            </h5>
                                            <div class="d-none candidate-position">Product Designer</div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="d-flex align-items-center py-2">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <img src="{{ URL::asset('build/images/users/avatar-5.jpg') }}" alt=""
                                                    class="img-fluid rounded-circle candidate-img">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-13 mb-1 text-truncate"><span
                                                    class="candidate-name">Jennifer Bailey</span> <span
                                                    class="text-muted fw-normal">@Jennifer</span></h5>
                                            <div class="d-none candidate-position">Marketing Director</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-body text-center">
                        <div class="avatar-md mb-3 mx-auto">
                            <img src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" alt="" id="candidate-img"
                                class="img-thumbnail rounded-circle shadow-none">
                        </div>

                        <h5 id="candidate-name" class="mb-0">Anna Adame</h5>
                        <p id="candidate-position" class="text-muted">Web Developer</p>

                        <div class="d-flex gap-2 justify-content-center mb-3">
                            <button type="button" class="btn avatar-xs p-0" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Google">
                                <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="ri-google-line"></i>
                                </span>
                            </button>

                            <button type="button" class="btn avatar-xs p-0" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Linkedin">
                                <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="ri-linkedin-line"></i>
                                </span>
                            </button>
                            <button type="button" class="btn avatar-xs p-0" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Dribbble">
                                <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="ri-dribbble-fill"></i>
                                </span>
                            </button>
                        </div>

                        <div>
                            <button type="button" class="btn btn-success rounded-pill w-sm"><i
                                    class="ri-add-fill me-1 align-bottom"></i> Follow</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end card -->
    </div>

    
    {{-- Add Grade and Subject --}}
    <div class="col-xxl-6" id="content-display">
       
    
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
                            contentElement.classList.add('content-item');
                            contentElement.innerHTML = `
                                <button class="btn btn-outline-primary mb-2" onclick="fetchContent(${content.id})">
                                    ${content.tone}
                                </button>
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

