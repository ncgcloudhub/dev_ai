@php($hideTopbar = true)
@extends('admin.layouts.master')

@section('title') Edu Library @endsection
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet"/>

    {{-- Filter CSS --}}
    <link href="{{ URL::asset('build/libs/nouislider/nouislider.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">

<style>
    .no-topbar .page-content {
        margin-top: 20px !important;
        padding-top: 0 !important;
    }
    
</style>

@endsection

@section('content')

<div class="row">
    <h4 class="gradient-text-1-bold">Education Library</h4>
    {{-- 1st Col --}}
    <div class="col-xxl-4">
    {{-- Filter Start --}}
        <div class="card border-color-purple">
            <div class="card-header border-color-purple">
                Grade/Class
            </div>

            <div class="accordion accordion-flush filter-accordion">

                <div class="card-body bg-marketplace">
                    <div>
                        <ul class="list-unstyled mb-0 filter-list">
                            @foreach ($classes as $index => $class)
                                <li>
                                    <a href="#" class="d-flex py-1 align-items-center class-item {{ $loop->first ? 'active' : '' }}"
                                    data-id="{{ $class->id }}"
                                    data-grade="{{ $class->grade }}"
                                    data-subjects='@json($class->subjects)'>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-13 mb-0 listname gradient-text-2">{{ $class->grade }}</h5>
                                        </div>
                                        @if ($class->subjects->count())
                                            <div class="flex-shrink-0 ms-2">
                                                <span class="badge gradient-bg text-white">{{ $class->subjects->count() }}</span>
                                            </div>
                                        @endif
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                        
                    </div>
                </div>

            </div>
        </div>
    {{-- Filter End --}}

    {{-- SUBJECTS Start --}}
    <div class="row">
        <h5 class="mt-3 gradient-text-1-bold">Subjects</h5>
        @foreach ($subjects as $subject)
        <div class="col-xxl-4">
            <div class="card gradient-bg text-white text-center p-3">
                <blockquote class="card-blockquote m-0">
                    <p><strong>{{ $subject->name }}</strong></p>
                    <footer class="blockquote-footer text-white font-size-12 mt-0 mb-0">
                        Total Contents: {{ $subject->contents_count }}
                    </footer>
                </blockquote>
            </div>
        </div>
    @endforeach
    </div>
    {{-- Subjects END --}}

    <div class="card border-color-purple">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row position-relative">
                <img src="/build/images/nft/friends.png" class="flex-shrink-0 mb-3 mb-md-0 me-md-3 avatar-xl rounded" alt="...">
                <div>
                    <h5 class="mt-0 gradient-text-1-bold">Referral Link</h5>
                    <p>Share this link to your friends to get <strong>$100</strong> worth of Tokens and Credits.</p>
                    <a onclick="copyText(this)" class="btn gradient-btn-3"></a>
                </div>
            </div>
        </div>
        
    </div>

    {{-- Authors and writer start--}}
    <div class="row">
    <h5 class="mt-3 gradient-text-1-bold">Authors and Writers</h5>
    @foreach ($authors as $author)
    <div class="col-xxl-6">
        <div class="card card-body border-color-purple author-card" 
             data-author-id="{{ $author->id }}" 
             data-author-name="{{ $author->name }}">
            <div class="d-flex mb-4 align-items-center">
                <div class="flex-shrink-0">
                    <img src="{{ asset('build/images/users/avatar-5.jpg') }}" alt="" class="avatar-sm rounded-circle">
                </div>
                <div class="flex-grow-1 ms-2">
                    <h5 class="card-title mb-1 gradient-text-2">Author: {{ $author->name }}</h5>
                    <p class="text-muted mb-0">({{ $author->role ?? 'Author' }})</p>
                </div>
            </div>
            <a href="javascript:void(0)" class="btn btn gradient-btn-3 btn-sm">See Details</a>
        </div>
    </div>
@endforeach




    </div>
    {{-- authors and writers END --}}
    


    </div>
    

    {{-- 2nd Col --}}
    <div class="col-xxl-8">
        <div id="contentDisplay"></div>
        <div class="card">
            <div class="d-flex justify-content-sm-end">
                <div class="search-box ms-2">
                    <input type="text" class="form-control border-color-purple" id="searchContent" placeholder="Search Edu Contents...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>

            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                          
                        </ul>
                    </div>
                    <div class="col-auto">
                        <div id="selection-element">
                            <div class="my-n1 d-flex align-items-center text-muted">
                                Select <div id="select-content" class="text-body fw-semibold px-1"></div> Result <button type="button" class="btn btn-link link-danger p-0 ms-3" data-bs-toggle="modal" data-bs-target="#removeItemModal">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card header -->
            <div class="card-body">

                <div class="tab-content text-muted">
                  
                </div>
                <!-- end tab content -->

            </div>
            <!-- end card body -->
        </div>


        {{-- Swiper Slider --}}
     <div class="swiper cryptoSlider">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/book-cover-2.png')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                  
                    <h6 class="fs-15 mb-3 gradient-text-2"><a href="apps-nft-item-details" class="text-body">Clever Creator Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end -->
            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/book-cover-2.png')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                  
                    <h6 class="fs-15 mb-3 gradient-text-2"><a href="apps-nft-item-details" class="text-body">Clever Creator Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end -->
            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/book-cover-2.png')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                  
                    <h6 class="fs-15 mb-3 gradient-text-2"><a href="apps-nft-item-details" class="text-body">Clever Creator Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end -->
            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/book-cover-2.png')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                  
                    <h6 class="fs-15 mb-3 gradient-text-2"><a href="apps-nft-item-details" class="text-body">Clever Creator Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end -->

            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/book-cover-2.png')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                  
                    <h6 class="fs-15 mb-3 gradient-text-2"><a href="apps-nft-item-details" class="text-body">Clever Creator Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end -->

            <div class="swiper-slide">
               <div class="card explore-box card-animate">
                <div class="position-relative rounded overflow-hidden">
                    <img src="{{URL::asset('build/images/nft/book-cover-2.png')}}" alt="" class="card-img-top explore-img">
                    <div class="discount-time">
                        <h5 id="auction-time-1" class="mb-0 text-white"></h5>
                    </div>
                </div>
                <div class="card-body">
                  
                    <h6 class="fs-15 mb-3 gradient-text-2"><a href="apps-nft-item-details" class="text-body">Clever Creator Painting</a></h6>
                    <div>
                        <span class="text-muted float-end">Available: 436</span>
                        <span class="text-muted">Sold: 4187</span>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 67%;" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- end --> 
        </div>
    </div><!-- end swiper wrapper -->


    </div>
</div>

{{-- Modal For View Contnt --}}
<div class="modal fade bs-example-modal-lg modal-dialog-scrollable" id="contentModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Content Details <span id="created" class="badge bg-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div>
                <img width="200px" height="200px" src="" alt="Generated Image" class="img-fluid" id="modal-image">
            </div>
            <div class="modal-body" id="modal-content-body">
                
            </div>
            <div class="modal-footer">
                  <button id="mark-complete-btn" type="button" class="btn btn-secondary incomplete" onclick="markAsComplete(contentId, this)">
                    Mark as Complete
                </button>               
                <a id="download-link" href="#">
                    <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Download">
                        <i class="ri-download-line"></i> Download
                    </button>
                </a>
                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1 align-middle"></i> Close
                </a>
            </div>
            
            
        </div>
    </div>
</div>

{{-- Scripts --}}
@section('script')
    {{-- Caroseol Scripts --}}
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-crypto.init.js') }}"></script>

    {{-- Filter Scripts --}}
    <script src="{{ URL::asset('build/libs/nouislider/nouislider.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/wnumb/wNumb.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/gridjs/gridjs.umd.js') }}"></script>
    <script src="https://unpkg.com/gridjs/plugins/selection/dist/selection.umd.js"></script>
    <script src="{{ URL::asset('build/js/pages/cc_ecommerce-product-list.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    {{-- FOR SUBJECTS after clicking Class --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Load subject tabs
            document.querySelectorAll('.class-item').forEach(classItem => {
                classItem.addEventListener('click', function () {
                    const subjects = this.getAttribute('data-subjects');
                    const parsedSubjects = JSON.parse(subjects);
        
                    const tabList = document.querySelector('.nav-tabs-custom');
                    const tabContent = document.querySelector('.tab-content');
                    tabList.innerHTML = '';
                    tabContent.innerHTML = '';
        
                    if (parsedSubjects.length > 0) {
                        parsedSubjects.forEach((subject, index) => {
                            const li = document.createElement('li');
                            li.classList.add('nav-item');

                            li.innerHTML = `
                                <a class="nav-link subject-tab gradient-text-2 ${index === 0 ? 'active' : ''}" 
                                data-subject-id="${subject.id}" 
                                data-bs-toggle="pill" 
                                href="#" 
                                role="tab">
                                ${subject.name}
                                </a>
                            `;
                            tabList.appendChild(li);
                        });

                        // Auto-click the first subject tab
                        const firstSubjectTab = tabList.querySelector('.subject-tab');
                        if (firstSubjectTab) {
                            firstSubjectTab.click();
                        }

                    } else {
                        tabList.innerHTML = `
                            <li class="nav-item">
                                <span class="nav-link fw-semibold disabled">No Subjects</span>
                            </li>
                        `;
                    }
                });
            });
        
            // Handle subject tab click
            document.querySelector('.nav-tabs-custom').addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('subject-tab')) {
                    const subjectId = e.target.getAttribute('data-subject-id');
        
                    // Show loading spinner
                    const contentDisplay = document.querySelector('.tab-content');
                    contentDisplay.innerHTML = `
                        <div class="d-flex justify-content-center my-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `;
        
                    // Fetch contents
                    fetch('{{ route('education.getContentsBySubject.library') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ subject_id: subjectId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        contentDisplay.innerHTML = ''; // Clear loader
        
                        if (data.contents.length > 0) {
                            const row = document.createElement('div');
                            row.classList.add('row', 'g-4');
        
                            data.contents.forEach(content => {
                                const col = document.createElement('div');
                                col.classList.add('col-12', 'col-md-6', 'col-xl-3');
        
                                const createdAt = new Date(content.created_at).toLocaleDateString('en-US', {
                                    day: 'numeric', month: 'short', year: 'numeric'
                                });
        
                                const downloadUrl = `{{ url('education/content') }}/${content.id}/download`;
                                
                                col.innerHTML = `
                                    <div class="card card-height-100 shadow-sm border-0 position-relative overflow-hidden">
                                        <div class="position-relative">
                                            <img class="card-img-top img-fluid" src="{{URL::asset('build/images/nft/book-cover-1.png')}}" alt="Card image cap" style="height: 180px; object-fit: cover;">
                                            <span class="badge gradient-bg position-absolute top-0 start-0 m-2 px-3 py-1">
                                                <i class="ri-book-2-line me-1 align-middle"></i> ${content.subject.name}
                                            </span>
                                        </div>
                                        
                                        <div class="card-body">
                                            <h6 class="gradient-text-2 mb-2">${content.topic}</h6>
                                            <p class="text-muted mb-1">
                                                <i class="ri-book-2-line me-1 align-middle"></i> ${content.subject.name}
                                            </p>
                                            <p class="text-muted">
                                                <i class="ri-calendar-line me-1 align-middle"></i> ${createdAt}
                                            </p>

                                            <div class="form-check mb-1">
                                                <input class="form-check-input include-grade" type="checkbox" id="include-grade-${content.id}" checked>
                                                <label class="form-check-label gradient-text-2" for="include-grade-${content.id}">Include Grade</label>
                                            </div>
                                            <div class="form-check mb-1">
                                                <input class="form-check-input include-subject" type="checkbox" id="include-subject-${content.id}" checked>
                                                <label class="form-check-label gradient-text-2" for="include-subject-${content.id}">Include Subject</label>
                                            </div>
                                            <div class="form-check mb-3">
                                            <input class="form-check-input include-date" type="checkbox" id="include-date-${content.id}" checked onchange="toggleLabelStyle(this)">
                                            <label class="form-check-label gradient-text-2" for="include-date-${content.id}">Include Date</label>
                                        </div>

                                        </div>

                                        <div class="card-footer gradient-bg d-flex px-2 py-1">
                                            <button class="btn text-white p-1" onclick="downloadContent(${content.id})">
                                                <i class="ri-download-line"></i>
                                            </button>
                                            <button class="btn text-white ms-2 p-1" onclick="fetchContent(${content.id})">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </div>

                                    </div>
                                `;

        
                                row.appendChild(col);
                            });
        
                            contentDisplay.appendChild(row);
                        } else {
                            contentDisplay.innerHTML = `
                                <div class="text-center text-muted py-5">
                                    <i class="ri-folder-warning-line display-4"></i>
                                    <p class="mt-3">No content available for this subject.</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        contentDisplay.innerHTML = `
                            <div class="text-center text-danger py-5">
                                <i class="ri-error-warning-line display-4"></i>
                                <p class="mt-3">Error loading content.</p>
                            </div>
                        `;
                    });
                }
            });

        // For active class item
        const firstClassItem = document.querySelector('.class-item');
                if (firstClassItem) {
                    firstClassItem.click();
                }

        });

        // Modal Script for Content
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
                <h6 class="fs-15">${data.content.topic}</h6>
                ${data.content.generated_content}
            `;

            const createdAt = new Date(data.content.created_at).toLocaleDateString('en-US', {
            day: 'numeric', month: 'short', year: 'numeric'
            });

            document.getElementById('created').innerHTML = `
            ${createdAt}
            `;

            const modalImage = document.getElementById('modal-image');
            if (data.content.image_url) {
                modalImage.src = data.content.image_url; // Set the image URL
                modalImage.alt = data.content.topic; // Optionally set the alt text
                modalImage.classList.remove('d-none'); // Show the image
            } else {
                modalImage.classList.add('d-none'); // Hide the image if no URL is available
            }

            // Set the download link with the appropriate URL
            const downloadLink = document.getElementById('download-link');
            const downloadUrl = `{{ url('education/content') }}/${contentId}/download`; // Dynamic download URL
            downloadLink.setAttribute('href', downloadUrl);

            // Update the Mark as Complete button with the correct onclick event
            const markCompleteButton = document.getElementById('mark-complete-btn');
            markCompleteButton.setAttribute('onclick', `markAsComplete(${contentId})`);

            // Show the modal
            var contentModal = new bootstrap.Modal(document.getElementById('contentModal'));
            contentModal.show();
        })
        .catch(error => console.error('Error:', error));
    }
        // Function to handle downloading with options
        function downloadContent(contentId) {
            const includeGrade = document.getElementById(`include-grade-${contentId}`).checked;
            const includeSubject = document.getElementById(`include-subject-${contentId}`).checked;
            const includeDate = document.getElementById(`include-date-${contentId}`).checked;

            const queryString = new URLSearchParams({
                include_grade: includeGrade ? 1 : 0,
                include_subject: includeSubject ? 1 : 0,
                include_date: includeDate ? 1 : 0
            }).toString();

            const downloadUrl = `{{ url('education/content') }}/${contentId}/download?${queryString}`;
            window.location.href = downloadUrl;
        }


    </script>
        

        <script>

    let searchTimeout = null;

    document.getElementById('searchContent').addEventListener('input', function () {
        const query = this.value.trim();

        // Debounce the input (wait 300ms after last key press)
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (query.length > 0) {
                fetch(`/education/library/search?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        renderContents(data.contents);
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                        renderContents([]); // Show no results
                    });
            } else {
                // If input is cleared, clear content
                renderContents([]);
            }
        }, 300);
    });

    function renderContents(contents) {
        const contentDisplay = document.getElementById('contentDisplay');
        contentDisplay.innerHTML = '';

        if (contents.length > 0) {
            const row = document.createElement('div');
            row.classList.add('row', 'g-4');

            contents.forEach(content => {
                const col = document.createElement('div');
                col.classList.add('col-12', 'col-md-6', 'col-xl-3');

                const createdAt = new Date(content.created_at).toLocaleDateString('en-US', {
                    day: 'numeric', month: 'short', year: 'numeric'
                });

                                                    col.innerHTML = `
                                    <div class="card card-height-100 shadow-sm border-0 position-relative overflow-hidden">
                                        <div class="position-relative">
                                            <img class="card-img-top img-fluid" src="{{URL::asset('build/images/nft/book-cover-1.png')}}" alt="Card image cap" style="height: 180px; object-fit: cover;">
                                            <span class="badge gradient-bg position-absolute top-0 start-0 m-2 px-3 py-1">
                                                <i class="ri-book-2-line me-1 align-middle"></i> ${content.subject.name}
                                            </span>
                                        </div>
                                        
                                        <div class="card-body">
                                            <h6 class="gradient-text-2 mb-2">${content.topic}</h6>
                                            <p class="text-muted mb-1">
                                                <i class="ri-book-2-line me-1 align-middle"></i> ${content.subject.name}
                                            </p>
                                            <p class="text-muted">
                                                <i class="ri-calendar-line me-1 align-middle"></i> ${createdAt}
                                            </p>

                                            <div class="form-check mb-1">
                                                <input class="form-check-input include-grade" type="checkbox" id="include-grade-${content.id}" checked>
                                                <label class="form-check-label gradient-text-2" for="include-grade-${content.id}">Include Grade</label>
                                            </div>
                                            <div class="form-check mb-1">
                                                <input class="form-check-input include-subject" type="checkbox" id="include-subject-${content.id}" checked>
                                                <label class="form-check-label gradient-text-2" for="include-subject-${content.id}">Include Subject</label>
                                            </div>
                                            <div class="form-check mb-3">
                                            <input class="form-check-input include-date" type="checkbox" id="include-date-${content.id}" checked onchange="toggleLabelStyle(this)">
                                            <label class="form-check-label gradient-text-2" for="include-date-${content.id}">Include Date</label>
                                        </div>

                                        </div>

                                        <div class="card-footer gradient-bg d-flex px-2 py-1">
                                            <button class="btn text-white p-1" onclick="downloadContent(${content.id})">
                                                <i class="ri-download-line"></i>
                                            </button>
                                            <button class="btn text-white ms-2 p-1" onclick="fetchContent(${content.id})">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </div>

                                    </div>
                                `;

                row.appendChild(col);
            });

            contentDisplay.appendChild(row);
        } else {
            contentDisplay.innerHTML = `<div class="text-center text-muted">No contents found.</div>`;
        }
    }


    // Author wise filter START
    $('.author-card').on('click', function () {
    const authorId = $(this).data('author-id');
    const authorName = $(this).data('author-name');

    // Show loading spinner
    const contentDisplay = document.querySelector('.tab-content');
                    contentDisplay.innerHTML = `
                        <div class="d-flex justify-content-center my-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `;
        
    // Fetch subjects and contents by this author
    $.ajax({
        url: '/education/get-author-subjects/' + authorId,
        method: 'GET',
        success: function (response) {
            const subjects = response.subjects;

            // Build subject tabs
            let tabsHtml = '';
            let contentHtml = '';

            subjects.forEach((subject, index) => {
                const activeClass = index === 0 ? 'active' : '';
                const showClass = index === 0 ? 'show active' : '';

                tabsHtml += `
                    <li class="nav-item">
                        <a class="nav-link gradient-text-2 ${activeClass}" data-bs-toggle="tab" href="#subject-${subject.id}" role="tab">
                            ${subject.name}
                        </a>
                    </li>
                `;

                let contentItems = '';
                subject.education_contents.forEach(content => {
                    const createdAt = new Date(content.created_at).toLocaleDateString('en-US', {
                    day: 'numeric', month: 'short', year: 'numeric'
                    });

                    contentItems += `
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card card-height-100 shadow-sm border-0 position-relative overflow-hidden">
                                <div class="position-relative">
                                    <img class="card-img-top img-fluid" src="{{URL::asset('build/images/nft/book-cover-1.png')}}" alt="Card image cap" style="height: 180px; object-fit: cover;">
                                    <span class="badge gradient-bg position-absolute top-0 start-0 m-2 px-3 py-1">
                                        <i class="ri-book-2-line me-1 align-middle"></i> ${content.subject.name}
                                    </span>
                                </div>
                                
                                <div class="card-body">
                                    <h6 class="gradient-text-2 mb-2">${content.topic}</h6>
                                    <p class="text-muted mb-1">
                                        <i class="ri-book-2-line me-1 align-middle"></i> ${content.subject.name}
                                    </p>
                                    <p class="text-muted">
                                        <i class="ri-calendar-line me-1 align-middle"></i> ${createdAt}
                                    </p>

                                    <div class="form-check mb-1">
                                        <input class="form-check-input include-grade" type="checkbox" id="include-grade-${content.id}" checked>
                                        <label class="form-check-label gradient-text-2" for="include-grade-${content.id}">Include Grade</label>
                                    </div>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input include-subject" type="checkbox" id="include-subject-${content.id}" checked>
                                        <label class="form-check-label gradient-text-2" for="include-subject-${content.id}">Include Subject</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input include-date" type="checkbox" id="include-date-${content.id}" checked onchange="toggleLabelStyle(this)">
                                        <label class="form-check-label gradient-text-2" for="include-date-${content.id}">Include Date</label>
                                    </div>
                                </div>

                                <div class="card-footer gradient-bg d-flex px-2 py-1">
                                    <button class="btn text-white p-1" onclick="downloadContent(${content.id})">
                                        <i class="ri-download-line"></i>
                                    </button>
                                    <button class="btn text-white ms-2 p-1" onclick="fetchContent(${content.id})">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });

                contentHtml += `
                    <div class="tab-pane fade ${showClass}" id="subject-${subject.id}" role="tabpanel">
                        <div class="row g-4">
                            ${contentItems}
                        </div>
                    </div>
                `;

            });

            $('.nav-tabs-custom').html(tabsHtml);
            $('.tab-content').html(contentHtml);
            $('#select-content').text(authorName);
        }
    });
});


    // Author wise filter END
        </script>
    
@endsection

@endsection

