@extends('admin.layouts.master')
@section('title') Job Overview  @endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card mt-n4 mx-n4">
            <div class="bg-primary-subtle">
                <div class="card-body px-4 pb-4">
                    <div class="row mb-3">
                        <div class="col-md">
                            <div class="row align-items-center g-3">
                                <div class="col-md-auto">
                                    <div class="avatar-md">
                                        <div class="avatar-title bg-white rounded-circle">
                                            <img src="{{URL::asset('build/images/brands/slack.png')}}" alt="" class="avatar-xs">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div>
                                        <h4 class="fw-bold mb-3"> {{$job->job_title}}</h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div><i class="ri-building-line align-bottom me-1"></i>{{ $siteSettings->title }}</div>
                                            <div class="vr"></div>
                                            <div><i class="ri-map-pin-2-line align-bottom me-1"></i> NY, USA</div>
                                            <div class="vr"></div>
                                            <div>Last Date of Apply: <span class="fw-medium"> {{$job->last_date_of_apply}}</span></div>
                                            <div class="vr"></div>
                                            <div class="badge rounded-pill bg-success fs-12"> {{$job->job_type}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-auto">
                            <div class="hstack gap-1 flex-wrap mt-4 mt-md-0">
                                <button type="button" class="btn btn-icon btn-sm btn-ghost-warning fs-16">
                                    <i class="ri-star-fill"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-sm btn-ghost-primary fs-16">
                                    <i class="ri-share-line"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-sm btn-ghost-primary fs-16">
                                    <i class="ri-flag-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
        </div>
        <!-- end card -->
    </div>
    <!--end col-->
</div>
<!--end row-->

<div class="row mt-n5">
    <div class="col-xxl-9">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Job Description</h5>

                <p class="text-muted mb-2">{!! $job->description !!}</p>
                <div>
                    <h5 class="mb-3">Responsibilities of {{$job->job_title}}</h5>
                    <p class="text-muted">Provided below are the responsibilities of a {{$job->job_title}}:</p>
                    <p class="text-muted mb-2">{!! $job->responsibility !!}</p>
                </div>

                <div>
                    <h5 class="mb-3">Skill & Experience</h5>
                    <p class="text-muted mb-2">{!! $job->skills !!}</p>
                </div>

                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <h5 class="mb-0">Share this job:</h5>
                    </li>
                    <li class="list-inline-item">
                        <a href="#!" class="btn btn-icon btn-soft-info"><i class="ri-facebook-line"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#!" class="btn btn-icon btn-soft-success"><i class="ri-whatsapp-line"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#!" class="btn btn-icon btn-soft-secondary"><i class="ri-twitter-line"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#!" class="btn btn-icon btn-soft-danger"><i class="ri-mail-line"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xxl-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Job Overview</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-medium">Title</td>
                                <td>{{$job->job_title}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Company Name</td>
                                <td>{{ $siteSettings->title }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Location</td>
                                <td>NY, USA</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Time</td>
                                <td><span class="badge bg-success-subtle text-success">{{$job->job_type}}</span></td>
                            </tr>
                          
                            <tr>
                                <td class="fw-medium">Last Date</td>
                                <td>{{$job->last_date_of_apply}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Salary</td>
                                <td>${{$job->start_salary}} - ${{$job->last_salary}}</td>
                            </tr>
                            <tr>
                                <?php
                                $tags = explode(',', $job->tags);
                                ?>
                                <td class="fw-medium">Tags</td>
                                <td>
                                    @foreach ($tags as $item)
                                    <span class="badge bg-success-subtle text-success">{{$item}}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Vacancy</td>
                                <td>{{$job->no_of_vacancy}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--end table-->
                </div>
                <div class="mt-4 pt-2 hstack gap-2">
                    <a href="#!" class="btn btn-primary w-100">Apply Now</a>
                    <a href="#!" class="btn btn-soft-danger btn-icon custom-toggle flex-shrink-0" data-bs-toggle="button">
                        <span class="icon-on"><i class="ri-bookmark-line align-bottom"></i></span>
                        <span class="icon-off"><i class="ri-bookmark-3-fill align-bottom"></i></span>
                    </a>
                </div>
            </div>
        </div>
        <!--end card-->
   
        
    </div>
</div>

@endsection