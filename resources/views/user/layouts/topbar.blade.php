<style>
    .gradient-button {
    background: linear-gradient(to right, rgb(10, 179, 156), rgb(64, 81, 137))
}

</style>
<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="{{ route('home') }}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
                        </span>
                    </a>
                    <a href="{{ route('home') }}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="17">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

              

                <!-- MODEL -->
                <div class="p-3" id="select-model-tour"> 
                    @php
                    // Fetch the last package, AI models, and selected model
                    $data = getUserLastPackageAndModels();
                    $lastPackage = $data['lastPackage'];
                    $freePricingPlan = $data['freePricingPlan'];
                    $aiModels = $data['aiModels'];
                    $selectedModel = $data['selectedModel'];
                    $remainingDays = get_days_until_next_reset();
                    $user = Auth::user();

                    $notifications = auth()->user()->unreadNotifications()
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

                    @endphp
                
                    @if ($lastPackage)
                        <form id="modelForm" action="{{ route('select-model') }}" method="POST">
                            @csrf
                            <div class="dropdown">
                                <button class="btn dropdown-toggle gradient-btn-8 text-white" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $selectedModel ? $selectedModel : 'Select AI Model' }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach ($aiModels as $model)
                                        <li>
                                            <a class="dropdown-item {{ trim($selectedModel) === trim($model) ? 'active' : '' }}" href="#" data-model="{{ $model }}">
                                                {{ $model }} 
                                                {{ trim($selectedModel) === trim($model) ? '🗸' : '' }}
                                            </a>
                                        </li>
                                    @endforeach
                                    
                                </ul>
                            </div>
                            <input type="hidden" name="aiModel" id="aiModelInput" value="{{ $selectedModel }}">
                        </form>
                    @elseif ($freePricingPlan)
                        <form id="modelForm" action="{{ route('select-model') }}" method="POST">
                            @csrf
                            <div class="dropdown">
                                <button class="btn gradient-btn-8 dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $selectedModel ? $selectedModel : 'Select AI Model' }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach ($aiModels as $model)
                                        <li>
                                            <a class="dropdown-item {{ trim($selectedModel) === trim($model) ? 'active' : '' }}" href="#" data-model="{{ $model }}">
                                                {{ $model }} 
                                                {{ trim($selectedModel) === trim($model) ? '🗸' : '' }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <input type="hidden" name="aiModel" id="aiModelInput" value="{{ $selectedModel }}">
                        </form>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="ms-1 header-item d-none d-sm-flex">
                    <span class="badge gradient-bg-1" style="font-size: 0.8rem;">Your next renew is in {{$remainingDays}} days</span>
                </div>
                <div class="ms-1 header-item d-none d-sm-flex">
                    @if (!$user->phone || !$user->address)
                    <span class="badge gradient-bg-1" style="font-size: 0.8rem;">Your profile is incomplete. <a href="{{ route('edit.profile') }}">Complete it now</a>.</span>
                                          
                    @endif
                   
                </div>
                
                <!-- Fullscreen Button -->
                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                        <i class='bx bx-fullscreen fs-22'></i>
                    </button>
                </div>

                <!-- Light/Dark Mode Toggle -->
                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='bx bx-moon fs-22'></i>
                    </button>
                </div>

                 {{-- Notification Button --}}
                 <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                        <i class='bx bx-bell fs-22'></i>
                        @if(count($notifications) > 0)
                            <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">
                                {{ count($notifications) }}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                
                        <div class="dropdown-head gradient-bg bg-pattern rounded-top">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold text-white"> Notifications </h6>
                                    </div>
                                    <div class="col-auto dropdown-tabs">
                                        <span class="badge bg-light-subtle text-body fs-13">{{ count($notifications) }} New</span>
                                    </div>
                                </div>
                            </div>
                
                            <div class="px-2 pt-2">
                                <ul class="nav nav-tabs dropdown-tabs nav-tabs-custom" data-dropdown-tabs="true" id="notificationItemsTab" role="tablist">
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link active color" data-bs-toggle="tab" href="#all-noti-tab" role="tab" aria-selected="true">
                                            All ({{ count($notifications) }})
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                
                        <div class="tab-content position-relative" id="notificationItemsTabContent">
                            <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                <div data-simplebar style="max-height: 300px;" class="pe-2">
                                    @forelse($notifications as $key => $notification)
                                        <div class="text-reset notification-item d-block dropdown-item position-relative">
                                            <div class="d-flex">
                                                <div class="avatar-xs me-3 flex-shrink-0">
                                                    <span class="avatar-title bg-info-subtle text-info rounded-circle fs-16">
                                                        <i class="bx bx-badge-check"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <a href="{{ $notification->link ?? '#' }}" class="stretched-link">
                                                        <h6 class="mt-0 mb-2 lh-base">
                                                            {{ $notification->data['message'] }}
                                                        </h6>
                                                    </a>
                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                        <span><i class="mdi mdi-clock-outline"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                                    </p>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center p-3 text-muted">
                                            No new notifications.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Notification Dropdown -->
                
                @include('user.layouts.activity_log')
                <!-- User Dropdown -->
                <div class="dropdown ms-sm-3 header-item">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="{{ Auth::user()->photo ? asset('backend/uploads/user/' . Auth::user()->photo) : asset('build/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::user()->name }}</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- Dropdown Menu Items -->
                        <h6 class="dropdown-header">Welcome {{ Auth::user()->name }}!</h6>
                        <a class="dropdown-item" href="{{ route('edit.profile') }}"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
                        <a class="dropdown-item" href="{{ route('user.dashboard') }}" target="_blank"><i class="mdi mdi-home text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Dashboard</span></a>
                        <a class="dropdown-item" href="{{ route('home') }}" target="_blank"><i class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Front-End</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="pages-profile"><i class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Plan : <b>{{ $lastPackage->package->title ?? 'Free' }}</b></span></a>
                        <a data-bs-toggle="offcanvas"
                        data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas" class="dropdown-item"><i class="mdi mdi-spin mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Theme Customizer</span></a>
                        <a class="dropdown-item" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1"></i> <span>@lang('translation.logout')</span></a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
@push('scripts')
<script>
    document.getElementById('page-header-notifications-dropdown').addEventListener('click', function () {
        fetch("{{ route('notifications.markAsRead') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(response => response.json()).then(data => {
            if (data.success) {
                // Remove the notification count badge
                const badge = document.querySelector('.topbar-badge.bg-danger');
                if (badge) badge.style.display = 'none';
            }
        });
    });
</script>
@endpush


<script>
   document.addEventListener('DOMContentLoaded', function() {
    var modelForm = document.getElementById('modelForm');
    var dropdownItems = document.querySelectorAll('.dropdown-item[data-model]'); // Only select items with data-model attribute

    dropdownItems.forEach(function(item) {
        item.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior
            event.stopPropagation(); // Prevent other click events from firing
            
            var model = this.getAttribute('data-model');
            document.getElementById('aiModelInput').value = model;

            console.log('Model selected:', model); // Debugging line

            modelForm.submit(); // Submit the form
        });
    });
});

</script>
