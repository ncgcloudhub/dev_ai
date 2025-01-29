<nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ URL::asset('/') }}">
            <img src="{{ URL::asset('build/images/logo-dark1.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="30">
            <img src="{{ URL::asset('build/images/logo-light1.png') }}" class="card-logo card-logo-light" alt="logo light" height="40">
        </a>
        <button id="navbarToggler" class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <i class="mdi mdi-menu"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                <li class="nav-item">
                    <a class="nav-link fs-15 {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>

                <!-- Dropdown for Services -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fs-15 {{ request()->routeIs('home') ? 'active' : '' }}" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Services
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                        <li><a class="dropdown-item" href="{{ route('home') }}#features">Features</a></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}#plans">Plans</a></li>
                        @auth
                            <li><a class="dropdown-item" href="{{ route('aicontentcreator.manage') }}">AI Content Creator</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('frontend.free.aicontentcreator') }}">AI Content Creator</a></li>
                        @endauth
                        @auth
                            @if (Auth::user()->role == 'admin' || Auth::user()->role == 'user')
                                <li><a class="dropdown-item" href="{{ route('prompt.manage') }}">Prompt Library</a></li>
                            @endif
                        @else
                            <li><a class="dropdown-item" href="{{ route('frontend.free.prompt.library') }}">Prompt Library</a></li>
                        @endauth
                    </ul>
                </li>

                <!-- Dropdown for Resources, Nested Dropdown -->
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fs-15" href="#" id="resourcesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Resources
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="resourcesDropdown">
                        <li class="dropdown dropend">
                            <a class="dropdown-item d-flex justify-content-between" href="#" id="blogDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Blog
                                <span class="mx-3">&gt;</span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="blogDropdown">
                                <li><a class="dropdown-item" href="#">AI Content Creator</a></li>
                                <li><a class="dropdown-item" href="#">Education Tools</a></li>
                            </ul>
                        </li>
                        <li><a class="dropdown-item" href="#">Learn AI</a></li>
                        <li><a class="dropdown-item" href="#">etc...</a></li>
                    </ul>
                </li> --}}
                

                <!-- AI Image Gallery Link -->
                <li class="nav-item">
                    <a class="nav-link fs-15 {{ request()->routeIs('ai.image.gallery') ? 'active' : '' }}" href="{{ route('ai.image.gallery') }}">AI Image Gallery</a>
                </li>

                {{-- Mega Menu --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fs-15" href="#" id="megamenuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        AI Content Creator
                    </a>
                    <div class="dropdown-menu p-4" aria-labelledby="megamenuDropdown" style="min-width: 800px; max-height: 400px; overflow-y: auto;">
                        <div class="row">
                            @php
                                use App\Models\Template;
                
                                $templates = Template::orderby('id', 'asc')->get(); // Get the templates from the database
                                $chunks = $templates->chunk(ceil($templates->count() / 3)); // Divide items into 3 roughly equal parts
                            @endphp
                        
                            @foreach ($chunks as $chunk)
                                <div class="col-md-4">
                                    @foreach ($chunk as $item)
                                        <a class="dropdown-item" href="{{ route('aicontentcreator.view', ['slug' => $item->slug]) }}">
                                            {{ $item->template_name ?? 'Default Item' }}
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </li>
                

                <!-- Menu for Company -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fs-15 {{ request()->routeIs('all.jobs') || request()->routeIs('contact.us') || request()->routeIs('privacy.policy') || request()->routeIs('terms.condition') ? 'active' : '' }}" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Company
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                        <li><a class="dropdown-item" href="{{ route('all.jobs') }}">Career</a></li>
                        <li><a class="dropdown-item" href="{{ route('contact.us') }}">Contact</a></li>
                        <li><a class="dropdown-item" href="{{ route('privacy.policy') }}">Privacy Policy</a></li>
                        <li><a class="dropdown-item" href="{{ route('terms.condition') }}">Terms & Conditions</a></li>
                    </ul>
                </li>
            </ul>

             <!-- Authenticated User Links -->
             <div class="">
                @if (Auth::check())
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ url('/admin/dashboard') }}" class="btn gradient-btn-4">Dashboard</a>
                    @elseif (Auth::user()->role === 'user')
                        <a href="{{ url('/user/dashboard') }}" class="btn gradient-btn-4">Dashboard</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-link fw-medium text-decoration-none text-dark">Sign in</a>
                    <a href="{{ route('register') }}" class="btn gradient-btn-4">Sign Up for Free AI Services</a>
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- Vertical overlay for mobile -->
<div class="vertical-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var toggler = document.getElementById('navbarToggler');
    var dropdowns = document.querySelectorAll('.dropdown');
    
    // Ensure only one dropdown is open at a time
    dropdowns.forEach(function (dropdown) {
        dropdown.addEventListener('show.bs.dropdown', function () {
            // Close all other open dropdowns
            dropdowns.forEach(function (otherDropdown) {
                if (otherDropdown !== dropdown) {
                    var bsDropdown = bootstrap.Dropdown.getInstance(otherDropdown.querySelector('.dropdown-toggle'));
                    if (bsDropdown) {
                        bsDropdown.hide();
                    }
                }
            });
        });
    });

    // Prevent navbar from collapsing when clicking inside the dropdown
    var dropdownMenus = document.querySelectorAll('.dropdown-menu');
    dropdownMenus.forEach(function (dropdownMenu) {
        dropdownMenu.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent the click event from propagating to the navbar toggler
        });
    });

    // Prevent the toggler from collapsing when clicking on the dropdown button
    document.querySelectorAll('.navbar-nav .dropdown-toggle').forEach(function (dropdownToggle) {
        dropdownToggle.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent the click event from collapsing the navbar
            toggler.setAttribute('aria-expanded', 'true'); // Ensure navbar stays expanded
        });
    });
});

</script>

