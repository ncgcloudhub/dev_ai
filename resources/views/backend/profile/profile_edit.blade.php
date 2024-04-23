@extends('admin.layouts.master')
@section('title')
    @lang('translation.settings')
@endsection
@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ URL::asset('build/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
            {{-- <div class="overlay-content">
                <div class="text-end p-3">
                    <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                        <input id="profile-foreground-img-file-input" type="file" class="profile-foreground-img-file-input">
                        <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light">
                            <i class="ri-image-edit-line align-bottom me-1"></i> Change Cover
                        </label>
                    </div>
                </div>
            </div> --}}
        </div>
</div>

    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <form method="POST" action="{{ route('update.profile.photo') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                                <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                                    <img src="{{ Auth::user()->photo ? asset('backend/uploads/user/' . Auth::user()->photo) : asset('build/images/users/avatar-1.jpg') }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image" width="200" height="200">
                                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                        <input id="profile-img-file-input" name="profile_photo" type="file" class="profile-img-file-input">
                                        <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                            <span class="avatar-title rounded-circle bg-light text-body">
                                                <i class="ri-camera-fill"></i>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <h5 class="fs-16 mb-1">{{$user->name}}</h5>
                        <p class="text-muted mb-0">Lead Designer / Developer</p>
                    </div>
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
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i>
                                Change Password
                            </a>
                        </li>
                    
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="{{ route('update.profile') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="profile_id" value="{{$user->id}}">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstnameInput" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter your Name" value="{{$user->name}}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="lastnameInput" class="form-label">User
                                                Name</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                placeholder="Enter your Username" value="{{$user->name}}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phonenumberInput" class="form-label">Phone
                                                Number</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                placeholder="Enter your phone number" value="{{$user->phone}}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="emailInput" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                placeholder="Enter your address" value="{{$user->address}}">
                                        </div>
                                    </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="emailInput" class="form-label">Country</label>
                                        <select name="country" class="form-control" id="country">
                                            <option disabled>Select Country</option>
                                            <option value="Bangladesh" {{ $user->country == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                            <option value="USA" {{ $user->country == 'USA' ? 'selected' : '' }}>USA</option>
                                            <option value="Germany" {{ $user->country == 'Germany' ? 'selected' : '' }}>Germany</option>
                                            <option value="India" {{ $user->country == 'India' ? 'selected' : '' }}>India</option>
                                            <option value="France" {{ $user->country == 'France' ? 'selected' : '' }}>France</option>
                                        </select>
                                    </div>
                                </div>
                                

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="emailInput" class="form-label">E-Mail</label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                   placeholder="Enter your email" value="{{$user->email}}" disabled>
                                        </div>
                                    </div>
                                    
                                <!--end col-->
                                   
                                <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <button type="button" class="btn btn-soft-secondary">Cancel</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('put')               

                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="oldpasswordInput" class="form-label">Current
                                               </label>
                                            <input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="newpasswordInput" class="form-label">New
                                                Password*</label>
                                            <input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">Confirm
                                                Password*</label>
                                            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <a href="javascript:void(0);"
                                                class="link-primary text-decoration-underline">Forgot
                                                Password ?</a>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Change
                                                Password</button>
                                                @if (session('status') === 'password-updated')
                                                <p
                                                    x-data="{ show: true }"
                                                    x-show="show"
                                                    x-transition
                                                    x-init="setTimeout(() => show = false, 2000)"
                                                    class="text-sm text-gray-600"
                                                >{{ __('Saved.') }}</p>
                                            @endif
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
    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
