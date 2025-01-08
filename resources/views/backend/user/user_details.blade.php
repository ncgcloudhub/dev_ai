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
Profile | {{$user->name}}
@endslot
@endcomponent

<div class="position-relative mx-n4 mt-n4">
    <div class="profile-wid-bg profile-setting-img">
        <img src="{{ URL::asset('build/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
       
    </div>
</div>

<div class="row">
    <div class="col-xxl-4">
        <div class="card mt-n5">
            <div class="card-body p-4">
                <div class="text-center">
                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                        <img src="{{ $user->photo ? asset('backend/uploads/user/' . $user->photo) : asset('build/images/users/avatar-1.jpg') }}"
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

            <div class="row">
                <div class="col-xl-6">
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


                <div class="col-xl-6">
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

                <div class="col-xl-6">
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
                
                <div class="col-xl-6">
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

            <div class="row">
                <div class="col-xl-6">
                    <!-- card -->
                    <div class="card card-animate bg-success-subtle shadow-lg">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Tokens Used</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        {{$user->tokens_used}}
                                    </h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-6">
                    <!-- card -->
                    <div class="card card-animate bg-danger-subtle shadow-lg">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Tokens Left</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        {{$user->tokens_left}}
                                    </h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-6">
                    <!-- card -->
                    <div class="card card-animate bg-success-subtle shadow-lg">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Credits Used</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        {{$user->credits_used}}
                                    </h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-6">
                    <!-- card -->
                    <div class="card card-animate bg-danger-subtle shadow-lg">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Credits Left</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        {{$user->credits_left}}
                                    </h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                {{-- Modal --}}
                @can('manageUser&Admin.manageUser.userEdit')
                    <div>
                        <button type="button" class="btn gradient-btn-9" data-bs-toggle="modal" data-bs-target="#signupModals">Edit</button>
                    </div>
                @endcan
              
                <div class="col-xl-4 col-md-6">
                    
                    <div id="signupModals" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 overflow-hidden">
                                
                                <div class="modal-body">
                                    <form method="POST" action="{{route('update.user.stats', $user->id)}}" class="row g-3" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT') <!-- Add this line to spoof PUT method -->
                                        <input type="hidden" name="id" value="{{$user->id}}">
                                      
                                        <div class="mb-3">
                                            <label for="tokens_left" class="form-label">Tokens Left</label>
                                            <input type="text" class="form-control" name="tokens_left" id="tokens_left" value="{{$user->tokens_left}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="credits_left" class="form-label">Credits Left</label>
                                            <input type="text" class="form-control" name="credits_left" id="credits_left" value="{{$user->credits_left}}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="form-control" name="role" id="role">
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                            </select>
                                        </div>
                                        
                                       
                                        <div class="text-end">
                                            <button type="submit" class="btn gradient-btn-save">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- end col -->
                {{-- Modal End --}}


            </div> 

            </div>
        </div>
        <!--end card-->
    </div>
    <!--end col-->
    <div class="col-xxl-8">
        <div class="card mt-xxl-n5">
            <div class="card-header">
                <ul class="nav nav-pills nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                            <i class="fas fa-home"></i>
                            Personal Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#images" role="tab">
                            <i class="fas fa-home"></i>
                            Images Generated
                        </a>
                    </li>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#userActivityLog" role="tab">
                            <i class="fas fa-home"></i>
                            Activity Log
                        </a>
                    </li>
                    @endif
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

                <div class="tab-content">
                    <div class="tab-pane" id="images" role="tabpanel">
                        @foreach ($images as $item)
                        <a class="gallery-link" href="{{ $item->image_url }}" title="{{ $item->prompt }}" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-url="{{ $item->image_url }}" data-image-prompt="{{ $item->prompt }}" data-image-resolution="{{ $item->resolution }}">
                           <img class="gallery-img img-fluid mx-auto" src="{{ $item->image_url }}" alt="" />
                           <div class="gallery-overlay">
                              <h5 class="overlay-caption">{{ $item->prompt }}</h5>
                           </div>
                        </a>
                        @endforeach
                    </div>
                    <!--end tab-pane-->
                </div>

                {{-- Activity Log --}}
                <div class="tab-content">
                    <div class="tab-pane active" id="userActivityLog" role="tabpanel">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width: 20%;">Action</th>
                                    <th style="width: 50%;">Details</th>
                                    <th style="width: 30%;">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $item)
                                    <tr>
                                        <td>{{ $item->action }}</td>
                                        <td>{{ $item->details }}</td>
                                        <td>{{ $item->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!--end tab-pane-->
                </div>

            </div>
        </div>
    </div>
    <!--end col-->
</div>


@endsection

