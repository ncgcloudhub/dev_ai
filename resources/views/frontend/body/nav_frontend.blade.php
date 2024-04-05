<nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="{{URL::asset('/')}}">
            <img src="{{ URL::asset('build/images/logo-dark1.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="30">
            <img src="{{ URL::asset('build/images/logo-light1.png') }}" class="card-logo card-logo-light" alt="logo light"
                height="40">
        </a>
        <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <i class="mdi mdi-menu"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                <li class="nav-item">
                    <a class="nav-link fs-15 active" href="{{route('home')}}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-15" href="#services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-15" href="#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-15" href="#plans">Plans</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-15" href="{{route('ai.image.gallery')}}">AI Image Gallery</a>
                </li>
             
                <li class="nav-item">
                    <a class="nav-link fs-15" href="{{route('contact.us')}}">Contact</a>
                </li>
            </ul>

            

            <div class="">
                @if (Auth::check())
                @if (Auth::user()->role === 'admin')
                    <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary">Dashboard</a>
                @elseif (Auth::user()->role === 'user')
                    <a href="{{ url('/user/dashboard') }}" class="btn btn-primary">Dashboard</a>
                @endif
                @else
                <a href="{{ route('login') }}" class="btn btn-link fw-medium text-decoration-none text-dark">Sign in</a>

                <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                @endif
            </div>
        </div>

    </div>
</nav>
<!-- end navbar -->
<div class="vertical-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent.show"></div>