@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Jobs @endslot
@slot('title') Job Lists @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h6 class="card-title mb-0 flex-grow-1">Search Jobs</h6>
                    <div class="flex-shrink-0">
                        <a class="btn btn-primary" href="{{ route('add.job') }}"><i class="ri-add-line align-bottom me-1"></i> Create New Job</a>
                        
                    </div>
                </div>

                <div class="row mt-3 gy-3">
                    <div class="col-xxl-10 col-md-6">
                        <div class="search-box">
                            <input type="text" class="form-control search bg-light border-light" id="searchJob" autocomplete="off" placeholder="Search for jobs or companies...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-md-6">
                        <div class="input-light">
                            <select class="form-control" data-choices data-choices-search-false name="choices-single-default" id="idStatus">
                                <option value="All">All Selected</option>
                                <option value="Newest" selected>Newest</option>
                                <option value="Popular">Popular</option>
                                <option value="Oldest">Oldest</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-12 d-none" id="found-job-alert">
                        <div class="alert alert-success mb-0 text-center" role="alert">
                            <strong id="total-result">0</strong> jobs found
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xxl-9">
       
        @foreach ($jobs as $item)
   
        <div class="card joblist-card">
            <div class="card-body">
                <div class="d-flex mb-4">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-light rounded">
                            <img src="" alt="" class="avatar-xxs companyLogo-img">
                        </div>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <img src="" alt="" class="d-none cover-img">
                        <a href="#!"><h5 class="job-title">{{$item->job_title}}</h5></a>
                        <p class="company-name text-muted mb-0">{{$item->job_position}}</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-ghost-primary btn-icon custom-toggle" data-bs-toggle="button">
                            <span class="icon-on"><i class="ri-bookmark-line"></i></span>
                            <span class="icon-off"><i class="ri-bookmark-fill"></i></span>
                        </button>
                    </div>
                </div>
                <p class="text-muted job-description">{{$item->description}}</p>
                <div>
                    @php
                        $tagsArray = explode(',', $item->tags);
                    @endphp

                    @foreach($tagsArray as $tag)
                        <span class="badge bg-primary-subtle text-primary me-1">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            <div class="card-footer border-top-dashed">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div><i class="ri-briefcase-2-line align-bottom me-1"></i> <span class="job-type">{{$item->job_type}}</span></div>
                    <div class="d-none"><span class="job-experience"></span></div>
                    <div><i class="ri-map-pin-2-line align-bottom me-1"></i> <span class="job-location">{{$item->state}}</span></div>
                    <div><i class="ri-user-3-line align-bottom me-1"></i>{{$item->no_of_vacancy}} Positions</div>
                    <div><i class="ri-time-line align-bottom me-1"></i> <span class="job-postdate"> {{$item->close_date}} </span></div>
                    <div><a href="{{ route('job.details', $item->slug) }}" class="btn btn-primary viewjob-list">View More <i class="ri-arrow-right-line align-bottom ms-1"></i></a></div>
                </div>
            </div>
        </div>
                 
        @endforeach

    </div>
    <!--end col-->

</div>

<div class="modal fade" id="CreateJobModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <form id="createjob-form" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="id-field" />
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <div class="px-1 pt-1">
                                <div class="modal-team-cover position-relative mb-0 mt-n4 mx-n4 rounded-top overflow-hidden">
                                    <img src="{{URL::asset('build/images/small/img-9.jpg')}}" alt="" id="modal-cover-img" class="img-fluid">

                                    <div class="d-flex position-absolute start-0 end-0 top-0 p-3">
                                        <div class="flex-grow-1">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Create New Job</h5>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="d-flex gap-3 align-items-center">
                                                <div>
                                                    <label for="cover-image-input" class="mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Select Cover Image">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                                <i class="ri-image-fill"></i>
                                                            </div>
                                                        </div>
                                                    </label>
                                                    <input class="form-control d-none" value="" id="cover-image-input" type="file" accept="image/png, image/gif, image/jpeg">
                                                </div>
                                                <button type="button" class="btn-close btn-close-white" id="close-jobListModal" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mb-4 mt-n5 pt-2">
                                <div class="position-relative d-inline-block">
                                    <div class="position-absolute bottom-0 end-0">
                                        <label for="companylogo-image-input" class="mb-0" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                            <div class="avatar-xs cursor-pointer">
                                                <div class="avatar-title bg-light border rounded-circle text-muted">
                                                    <i class="ri-image-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                        <input class="form-control d-none" value="" id="companylogo-image-input" type="file" accept="image/png, image/gif, image/jpeg">
                                    </div>
                                    <div class="avatar-lg p-1">
                                        <div class="avatar-title bg-light rounded-circle">
                                            <img src="{{URL::asset('build/images/users/multi-user.jpg')}}" id="companylogo-img" class="avatar-md rounded-circle object-fit-cover" />
                                        </div>
                                    </div>
                                </div>
                                <h5 class="fs-13 mt-3">Company Logo</h5>
                            </div>
                            <div>
                                <label for="jobtitle-field" class="form-label">Job Title</label>
                                <input type="text" id="jobtitle-field" class="form-control" placeholder="Enter job title" required />
                                <div class="invalid-feedback">Please enter a job title.</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="companyname-field" class="form-label">Company Name</label>
                                <input type="text" id="companyname-field" class="form-control" placeholder="Enter company name" required />
                                <div class="invalid-feedback">Please enter a company name.</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="job_type-field" class="form-label">Job Type</label>
                                <select class="form-select" id="job_type-field" required>
                                    <option value="Full Time">Full Time</option>
                                    <option value="Part Time">Part Time</option>
                                    <option value="Freelance">Freelance</option>
                                    <option value="Internship">Internship</option>
                                </select>
                                <div class="invalid-feedback">Please select a job type.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div>
                                <label for="experience-field" class="form-label">Experience</label>
                                <input type="text" id="experience-field" class="form-control" placeholder="Enter experience" required />
                                <div class="invalid-feedback">Please enter a job experience.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div>
                                <label for="location-field" class="form-label">Location</label>
                                <input type="text" id="location-field" class="form-control" placeholder="Enter location" required />
                                <div class="invalid-feedback">Please enter a location.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div>
                                <label for="Salary-field" class="form-label">Salary</label>
                                <input type="number" id="Salary-field" class="form-control" placeholder="Enter salary" />
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div>
                                <label for="website-field" class="form-label">Tags</label>
                                <input class="form-control" id="taginput-choices" data-choices data-choices-text-unique-true type="text" value="Design, Remote" />
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div>
                                <label for="description-field" class="form-label">Description</label>
                                <textarea class="form-control" id="description-field" rows="3" placeholder="Enter description" required></textarea>
                                <div class="invalid-feedback">Please enter a description.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="add-btn">Add Job</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end add modal-->


@endsection
@section('script')
<!-- apexcharts -->
<script src="{{URL::asset('build/libs/apexcharts/apexcharts.min.js')}}"></script>

<script src="{{URL::asset('build/js/pages/job-list.init.js')}}"></script>

<!-- App js -->
<script src="{{URL::asset('build/js/app.js')}}"></script>

<script>
    $(document).ready(function() {
        $('#searchJob').on('keyup', function() {
            var value = $(this).val().trim().toLowerCase(); // Trim whitespace from the search value
            if (value === '') { // If search value is empty
                $('.joblist-card').show(); // Show all job cards
                $('#found-job-alert').addClass('d-none'); // Hide the notification
                return; // Exit the function
            }

            var matches = 0;
            $('.joblist-card').each(function() {
                var text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(value) > -1);
                if ($(this).is(':visible')) {
                    matches++;
                }
            });
            $('#total-result').text(matches);
            $('#found-job-alert').toggleClass('d-none', matches == 0);
        });
    });
</script>





@endsection