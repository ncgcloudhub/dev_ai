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
                <li class="nav-item active">
                    <a class="nav-link fs-15" href="{{ route('home') }}">Home</a>
                </li>

                <!-- Dropdown for Services -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fs-15" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Services
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('home') }}#features">Features</a></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}#plans">Plans</a></li>
                        @auth
                            <li><a class="dropdown-item" href="{{ route('aicontentcreator.manage') }}">AI Content Creator</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('frontend.free.aicontentcreator') }}">AI Content Creator</a></li>
                        @endauth
                        @auth
                            @if (Auth::user()->role == 'admin')
                                <li><a class="dropdown-item" href="{{ route('prompt.manage') }}">Prompt Library</a></li>
                            @elseif (Auth::user()->role == 'user')
                                <li><a class="dropdown-item" href="{{ route('user.prompt.library') }}">Prompt Library</a></li>
                            @endif
                        @else
                            <li><a class="dropdown-item" href="{{ route('frontend.free.prompt.library') }}">Prompt Library</a></li>
                        @endauth
                    </ul>
                </li>

                <!-- AI Image Gallery Link -->
                <li class="nav-item">
                    <a class="nav-link fs-15" href="{{ route('ai.image.gallery') }}">AI Image Gallery</a>
                </li>

                <!-- Dropdown for Company -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fs-15" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                    <a href="{{ route('register') }}" class="btn gradient-btn-4">Sign Up</a>
                  

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
    var dropdowns = document.querySelectorAll('.dropdown-menu');

    dropdowns.forEach(function (dropdown) {
        dropdown.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevents the click event from propagating to the toggler button
        });
    });

    // Prevent the toggler from collapsing when clicking inside the dropdown menu
    document.querySelectorAll('.navbar-nav .dropdown-toggle').forEach(function (dropdownToggle) {
        dropdownToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            toggler.setAttribute('aria-expanded', 'true'); // Keeps the navbar expanded
        });
    });
});


</script>