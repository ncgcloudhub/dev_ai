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
                <div class="p-3"> 
                    @php
                    // Fetch the last package, AI models, and selected model
                    $data = getUserLastPackageAndModels();
                    $lastPackage = $data['lastPackage'];
                    $freePricingPlan = $data['freePricingPlan'];
                    $aiModels = $data['aiModels'];
                    $selectedModel = $data['selectedModel'];
                    @endphp
                
                    @if ($lastPackage)
                        <form id="modelForm" action="{{ route('select-model') }}" method="POST">
                            @csrf
                            <div class="dropdown">
                                <button class="btn dropdown-toggle gradient-button text-white" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
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
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
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
                <!-- Search Dropdown (Mobile) -->
                <div class="dropdown d-md-none topbar-head-dropdown header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-search fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                        <form class="p-3">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
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
                @include('user.layouts.activity_log')
                <!-- User Dropdown -->
                <div class="dropdown ms-sm-3 header-item topbar-user">
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

<!-- Remove Notification Modal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
