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
                                    <button type="submit" class="btn gradient-btn-save">Update</button>
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
                        {{-- <p class="text-muted mb-0">Lead Designer / Developer</p> --}}
                        <p class="mb-1">Referral Link <strong class="text-muted text-info"> {{$user->referral_link}}</strong></p>
                           
                    </div>
                </div>
            </div>
            <!--end card-->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{$user->name}}'s Statistics</h5>
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
    
                </div> 
    
                </div>
            </div>
        
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-pills nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i>
                                Personal Details
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#purchaseHistory" role="tab">
                                <i class="fas fa-home"></i>
                                Purchase History
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#stripeHistory" role="tab">
                                <i class="fas fa-home"></i>
                                Stripe Subscriptions
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
                                                placeholder="Enter your Username" value="{{$user->username}}">
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
                                        <label for="emailInput" class="form-label">Region/Country</label>
                                        <input type="text" class="form-control" id="country" name="country"
                                                placeholder="Region/Country" value="{{$user->country}}" disabled>
                    
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
                                            <button type="submit" class="btn gradient-btn-save">Update</button>
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
                                            <button type="submit" class="btn gradient-btn-save">Change
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
                      
                        {{-- Purchase History --}}
                        {{-- <div class="tab-pane" id="purchaseHistory" role="tabpanel">
                            @if($packageHistory->isNotEmpty())
                            <div class="row">
                                <div class="col-xxl-8">
                                    <div class="card card-height-100">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0 flex-grow-1">Recent Purchase</h4>
                                        </div><!-- end card header -->
                                        <div class="card-body pt-0">
                                            <ul class="list-group list-group-flush border-dashed">
                                                @foreach($packageHistory as $history)
                                                <li class="list-group-item ps-0">
                                                    <div class="card {{ $loop->last ? 'card-light' : '' }}">
                                                    <div class="row align-items-center g-3">
                                                        <div class="col-auto">
                                                            <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                                                @php
                                                                $day = $history->created_at->format('d');
                                                                $monthYear = $history->created_at->format('M Y');
                                                                @endphp
                                                                <div class="text-center">
                                                                    <h5 class="mb-0">{{ $day }}</h5>
                                                                    <div class="text-muted">{{ $monthYear }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <h5 class="text-muted mt-0 mb-1 fs-13">{{ $history->package->title }}</h5>
                                                            <a href="#" class="text-reset fs-14 mb-0">{{ $history->package->description }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                    <!-- end row -->
                                                </li><!-- end -->
                                                @endforeach
                                            </ul><!-- end -->
                                            
                                            <!-- Display countdown for the first paid package -->
                                            @if($daysUntilNextReset)
                                            <div class="alert alert-info rounded-top alert-solid alert-label-icon border-0 rounded-0 m-0 d-flex align-items-center" role="alert">
                                                <i class="ri-error-warning-line label-icon"></i>
                                                <div class="flex-grow-1 text-truncate">
                                                    Your package will be renewed in <b>{{ $daysUntilNextReset }}</b> days | {{$renewalDate}}
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <a href="{{route('all.package')}}" class="text-reset text-decoration-underline"><b>See more packages</b></a>
                                                </div>
                                            </div>

                                            @endif
                                            
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div> <!-- end col-->
                            </div>
                            @else
                            <div class="card bg-primary">
                                <div class="card-body p-0">
                                    <div class="alert alert-success rounded-top alert-solid alert-label-icon border-0 rounded-0 m-0 d-flex align-items-center" role="alert">
                                        <i class="ri-error-warning-line label-icon"></i>
                                        <div class="flex-grow-1 text-truncate">
                                            Your free trial will be renewed in <b>{{ $daysUntilNextReset }}</b> days.
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="{{route('all.package')}}" class="text-reset text-decoration-underline"><b>Upgrade</b></a>
                                        </div>
                                    </div>
                        
                                    <div class="row align-items-end">
                                        <div class="col-sm-8">
                                            <div class="p-3">
                                                <p class="fs-16 lh-base text-white">Upgrade your plan from a <span class="fw-semibold">{{$freePricingPlan->title}}</span>, to ‘Premium Plan’ <i class="mdi mdi-arrow-right"></i></p>
                                                <div class="mt-3">
                                                    <a href="{{route('all.package')}}" class="btn btn-info">Upgrade
                                                        Account!</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="px-3">
                                                <img src="assets/images/user-illustarator-1.png" class="img-fluid" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card-body-->
                            </div>
                            @endif
                        </div> --}}

                        {{-- Stripe --}}
                        <div class="tab-pane" id="stripeHistory" role="tabpanel">
                            <h2>My Subscriptions</h2>
                            @if ($subscriptions->count() > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Plan Name</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Start Date</th>
                                            <th>Next Billing Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subscriptions as $subscription)
                                        @php      
                                            $product = \Stripe\Product::retrieve($subscription->items->data[0]->price->product);
                                            $price = \Stripe\Price::retrieve($subscription->items->data[0]->price->id);
                                            $amount = number_format($price->unit_amount / 100, 2) . ' ' . strtoupper($price->currency);

                                            // Find the invoice related to this subscription
                                            $invoice = collect($invoices)->firstWhere('subscription', $subscription->id);
                                        @endphp
                                       
                                        <tr>
                                            <td>
                                                @if ($invoice && $invoice->hosted_invoice_url)
                                                    <a href="{{ $invoice->hosted_invoice_url }}" target="_blank">
                                                        {{ $product->name }}
                                                    </a>
                                                @else
                                                    {{ $product->name }}
                                                @endif
                                            </td>
                                            <td>{{ $amount }}</td>
                                            <td>
                                                @if ($subscription->status === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif ($subscription->status === 'canceled')
                                                    <span class="badge bg-danger">Canceled</span>
                                                @else
                                                    <span class="badge bg-warning">{{ ucfirst($subscription->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::createFromTimestamp($subscription->created)->format('d M Y') }}</td>
                                            <td>
                                                @if ($subscription->current_period_end)
                                                    {{ \Carbon\Carbon::createFromTimestamp($subscription->current_period_end)->format('d M Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if ($subscription->status === 'active')
                                                    <form action="{{ route('subscription.cancel', $subscription->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this subscription?');">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- Fetch product & price ID for repurchasing --}}
                                                    @php
                                                        $priceId = $subscription->items->data[0]->price->id ?? 'default_price_id';
                                                        $productId = $subscription->items->data[0]->price->product ?? 'default_product_id';
                                                    @endphp
                                                  
                                                    <a href="{{ route('checkout', [
                                                        'id' => $subscription->id,
                                                        'prod_id' => $productId,
                                                        'price_id' => $priceId
                                                    ]) }}" class="btn btn-primary btn-sm">
                                                        Buy Again
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>You have no active subscriptions.</p>
                            @endif
                        
                        </div>

                        {{-- History END --}}
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
