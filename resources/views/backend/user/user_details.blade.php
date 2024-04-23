@extends('admin.layouts.master')
@section('title')
@lang('translation.team')
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1')
User
@endslot
@slot('title')
{{$user->name}}
@endslot
@endcomponent

<div class="position-relative mx-n4 mt-n4">
    <div class="profile-wid-bg profile-setting-img">
        <img src="{{ URL::asset('build/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
       
    </div>
</div>

<div class="row">
    <div class="col-xxl-3">
        <div class="card mt-n5">
            <div class="card-body p-4">
                <div class="text-center">
                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                        <img src="@if (Auth::user()->avatar != '') {{ URL::asset('images/' . Auth::user()->avatar) }}@else{{ URL::asset('build/images/users/avatar-1.jpg') }} @endif"
                            class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                      
                    </div>
                    <h5 class="fs-16 mb-1">{{$user->name}}</h5>
                    <p class="text-muted mb-0">{{$user->status}}</p>
                </div>
            </div>
        </div>
        <!--end card-->
   
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">User Statistics</h5>
                    </div>
                    
                </div>


                <div class="col-xl-12">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body bg-success-subtle shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Words Generated</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        {{$user->words_generated}}
                                    </h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->


                <div class="col-xl-12">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body bg-danger-subtle shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Words Left</p>
                                </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-danger fs-14 mb-0">
                                    {{$user->words_left}}
                                </h5>
                            </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-12">
                    <!-- card -->
                    <div class="card card-animate bg-success-subtle shadow-lg">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Images Generated</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        {{$user->images_generated}}
                                    </h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
                
                <div class="col-xl-12">
                    <!-- card -->
                    <div class="card card-animate bg-danger-subtle shadow-lg">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Images Left</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        {{$user->images_left}}
                                    </h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

            </div>
        </div>
        <!--end card-->
    </div>
    <!--end col-->
    <div class="col-xxl-9">
        <div class="card mt-xxl-n5">
            <div class="card-header">
                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                            <i class="fas fa-home"></i>
                            Personal Details
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                        <form action="javascript:void(0);">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="firstnameInput" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="firstnameInput" disabled
                                            value="{{$user->name}}">
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">User
                                            Name</label>
                                            <input type="text" class="form-control" disabled
                                            value="{{$user->username}}">
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="phonenumberInput" class="form-label">Phone
                                            Number</label>
                                        <input type="text" class="form-control" id="phonenumberInput" disabled
                                           value="{{$user->phone}}">
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="emailInput" class="form-label">Email
                                            Address</label>
                                        <input type="email" class="form-control" id="emailInput" disabled
                                            placeholder="Enter your email" value="{{$user->email}}">
                                    </div>
                                </div>
                                <!--end col-->
                               
                               
                               
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="cityInput" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="cityInput" placeholder="City" disabled
                                           value="{{$user->address}}" />
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="countryInput" class="form-label">Country</label>
                                        <input type="text" class="form-control" id="countryInput" disabled
                                            placeholder="Country"  value="{{$user->country}}" />
                                    </div>
                                </div>
                                <!--end col-->

                            </div>
                            <!--end row-->
                        </form>
                    </div>
                    <!--end tab-pane-->
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>


@endsection

