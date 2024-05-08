@extends('admin.layouts.master')

@section('title', $seo->title)

@section('description', $seo->description)

@section('keywords', $seo->keywords)

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Jobs @endslot
@slot('title') New Job @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="{{ route('job.store') }}" method="POST">
                @csrf
                <div class="card-header">
                    <h5 class="card-title mb-0">Create Job</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div>
                                <label for="job-title-Input" class="form-label">Job Title <span class="text-danger">*</span></label>
                                <input type="text" name="job_title" class="form-control" id="job-title-Input" placeholder="Enter job title" required />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="job-position-Input" class="form-label">Job Position <span class="text-danger">*</span></label>
                                <input type="text" name="job_position" class="form-control" id="job-position-Input" placeholder="Enter job position" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="job-category-Input" class="form-label">Job Category <span class="text-danger">*</span></label>
                                <select class="form-control" name="job_category" data-choices name="job-category-Input">
                                    <option value="">Select Category</option>
                                    <option value="Accounting & Finance">Accounting & Finance</option>
                                    <option value="Purchasing Manager">Purchasing Manager</option>
                                    <option value="Education & training">Education & training</option>
                                    <option value="Marketing & Advertising">Marketing & Advertising</option>
                                    <option value="It / Software Jobs">It / Software Jobs</option>
                                    <option value="Digital Marketing">Digital Marketing</option>
                                    <option value="Administrative Officer">Administrative Officer</option>
                                    <option value="Government Jobs">Government Jobs</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="job-type-Input" class="form-label">Job Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="job_type" data-choices name="job-type-Input">
                                    <option value="">Select job type</option>
                                    <option value="Full Time">Full Time</option>
                                    <option value="Part Time">Part Time</option>
                                    <option value="Freelance">Freelance</option>
                                    <option value="Internship">Internship</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div>
                                <label for="description-field" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" id="description-field" rows="3" placeholder="Enter description" ></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div>
                                <label for="vancancy-Input" class="form-label">No. of Vacancy <span class="text-danger">*</span></label>
                                <input type="number" name="no_of_vacancy" class="form-control" id="vancancy-Input" placeholder="No. of vacancy"  />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <label for="experience-Input" class="form-label">Experience <span class="text-danger">*</span></label>
                                <select class="form-control" name="experience" data-choices name="experience-Input">
                                    <option value="">Select Experience</option>
                                    <option value="0 Year">0 Year</option>
                                    <option value="2 Years">2 Years</option>
                                    <option value="3 Years">3 Years</option>
                                    <option value="4 Years">4 Years</option>
                                    <option value="5 Years">5 Years</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div>
                                <label for="last-apply-date-Input" class="form-label">Last Date of Apply <span class="text-danger">*</span></label>
                                <input type="date" name="last_date_of_apply" class="form-control" id="last_date_of_apply-Input" data-provider="flatpickr" placeholder="Select date"  />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div>
                                <label for="close-date-Input" class="form-label">Close Date <span class="text-danger">*</span></label>
                                <input type="date" name="close_date" class="form-control" id="close-date-Input" data-provider="flatpickr" placeholder="Select date"  />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div>
                                <label for="start-salary-Input" class="form-label">Start Salary</label>
                                <input type="number" name="start_salary" class="form-control" id="start-salary-Input" placeholder="Enter start salary"  />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div>
                                <label for="last-salary-Input" class="form-label">Last Salary</label>
                                <input type="number" name="last_salary" class="form-control" id="last-salary-Input" placeholder="Enter end salary"  />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div>
                                <label for="country-Input" class="form-label">Country <span class="text-danger">*</span></label>
                                <input type="text" name="country" class="form-control" id="country-Input" placeholder="Enter country"  />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div>
                                <label for="city-Input" class="form-label">State <span class="text-danger">*</span></label>
                                <input type="text" name="state" class="form-control" id="city-Input" placeholder="Enter city"  />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div>
                                <label for="skills-field" class="form-label">Skills <span class="text-danger">*</span></label>
                                <textarea name="skills" class="form-control" id="tinymceExample" rows="10"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div>
                                <label for="responsibility-field" class="form-label">Responsibility <span class="text-danger">*</span></label>
                                <textarea name="responsibility" class="form-control" id="tinymceExample" rows="10"></textarea>
                            </div>
                        </div>

                       

                        <div class="col-lg-12">
                            <div>
                                <label for="website-field" class="form-label">Tags</label>
                                <input class="form-control" name="tags" id="choices-text-unique-values" data-choices data-choices-text-unique-true type="text" value="Design, Remote"  />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="hstack justify-content-start gap-2">
                                <button type="submit" class="btn btn-primary">Add Job</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('script')

<!-- App js -->
<script src="{{URL::asset('build/js/app.js')}}"></script>
@endsection
