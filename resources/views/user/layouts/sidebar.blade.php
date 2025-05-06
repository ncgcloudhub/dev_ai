<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo -->
        <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
            <span class="logo-sm fw-bold fs-6 text-dark">CC AI</span>
            <span class="logo-lg fw-bold fs-4 text-dark">Clever Creator AI</span>
        </a>
        <!-- Light Logo -->
        <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
            <span class="logo-sm fw-bold fs-6 text-light">CC AI</span>
            <span class="logo-lg fw-bold fs-4 text-light">Clever Creator AI</span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>@lang('translation.menu')</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{route('user.dashboard')}}">
                        <i class="las la-home"></i> <span >Dashboard</span>
                    </a>
                </li>


                <li class="menu-title"><i class="ri-more-fill"></i> <span >AI Tools</span></li>

                {{-- Favorites --}}
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs(['manage.favourite.image']) ? 'active' : '' }}" href="#favorite" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs(['manage.favourite.image']) ? 'true' : 'false' }}" aria-controls="favorite">
                        <i class="las la-star"></i> <span >Favorites</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs(['manage.favourite.image']) ? 'show' : '' }}" id="favorite">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    <li class="nav-item">
                                        <a href="{{route('manage.favourite.image')}}" class="nav-link sidebar-hover {{ request()->routeIs('manage.favourite.image') ? 'active' : '' }}" >Manage Favorite Image</a>
                                    </li>
                                  
                                   
                                </ul>
                            </div>
       
                        </div>
                    </div>
                </li>

                {{-- EDUCATION --}}
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs(['education.form', 'manage.education.tools', 'education.tools.contents']) ? 'active' : '' }}" href="#education" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs(['education.form', 'manage.education.tools', 'education.tools.contents']) ? 'true' : 'false' }}" aria-controls="education">
                        <i class="las la-graduation-cap"></i> <span>Education</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs(['education.form', 'manage.education.tools', 'education.tools.contents']) ? 'show' : '' }}" id="education">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('education.form') }}" class="nav-link sidebar-hover {{ request()->routeIs('education.form') ? 'active' : '' }}">Education Wizard</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('manage.education.tools') }}" class="nav-link sidebar-hover {{ request()->routeIs('manage.education.tools') ? 'active' : '' }}">Education Tools</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('education.tools.contents') }}" class="nav-link sidebar-hover {{ request()->routeIs('education.tools.contents') ? 'active' : '' }}">Library</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                {{-- Fixed Template --}}
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('aicontentcreator.manage') ? 'active' : '' }}" href="{{ route('aicontentcreator.manage') }}">
                        <i class="las la-file-alt"></i> <span>AI Content Creator</span>
                    </a>
                </li>
                

                {{-- AI CHAT --}}
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('main.chat.form') ? 'active' : '' }}" href="{{ route('main.chat.form') }}">
                        <i class="lab la-rocketchat"></i> 
                        <span>ChatterMate</span>
                        <span class="badge bg-danger ms-2">AI Chat</span>
                    </a>
                </li>
                      

                 {{-- Greeting Card --}}
                 <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('greeting.card') ? 'active' : '' }}" href="{{ route('greeting.card') }}">
                        <i class="las la-id-card"></i> <span>Greeting Card</span>
                    </a>
                </li>
                

                {{-- Custom Template --}}
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs(['custom.template.category.add', 'custom.template.add', 'custom.template.manage']) ? 'active' : '' }}" href="#AITools" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs(['custom.template.category.add', 'custom.template.add', 'custom.template.manage']) ? 'true' : 'false' }}" aria-controls="AITools">
                        <i class="las la-pencil-ruler"></i> <span>Custom Template</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs(['custom.template.category.add', 'custom.template.add', 'custom.template.manage']) ? 'show' : '' }}" id="AITools">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('custom.template.category.add') }}" class="nav-link sidebar-hover {{ request()->routeIs('custom.template.category.add') ? 'active' : '' }}">Categories</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('custom.template.add') }}" class="nav-link sidebar-hover {{ request()->routeIs('custom.template.add') ? 'active' : '' }}">Add Template</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('custom.template.manage') }}" class="nav-link sidebar-hover {{ request()->routeIs('custom.template.manage') ? 'active' : '' }}">Manage Template</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                {{-- Prompt Library --}}
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('prompt.manage') ? 'active' : '' }}" href="#prompt" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('prompt.manage') ? 'true' : 'false' }}" aria-controls="prompt">
                        <i class="las la-pen"></i> <span>Prompt Library</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs('prompt.manage') ? 'show' : '' }}" id="prompt">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('prompt.manage') }}" class="nav-link sidebar-hover {{ request()->routeIs('prompt.manage') ? 'active' : '' }}">Manage Prompt</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>


                {{-- <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('chat') ? 'active' : '' }}" href="#sidebarExpert" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('chat') ? 'true' : 'false' }}" aria-controls="sidebarExpert">
                        <i class="las la-sms"></i> <span>AI Chat</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs('chat') ? 'show' : '' }}" id="sidebarExpert">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('chat') }}" class="nav-link sidebar-hover {{ request()->routeIs('chat') ? 'active' : '' }}">AI Professional Bots</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li> --}}

                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('generate.image.view') ? 'active' : '' }}" href="#generateImage" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('generate.image.view') ? 'true' : 'false' }}" aria-controls="generateImage">
                        <i class="las la-image"></i> <span>Generate Image</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs('generate.image.view') ? 'show' : '' }}" id="generateImage">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('generate.image.view') }}" class="nav-link sidebar-hover {{ request()->routeIs('generate.image.view') ? 'active' : '' }}">Generate with Dall-E</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                @php
                    $allowedEmails = ['fahmidh26@gmail.com', 'ifaz.alam1@gmail.com', 'clevercreatorai@gmail.com', 'ifazalam69@gmail.com', 'ifaz.alam@statait.com', 'ifazalam9@gmail.com', 'metaversetech07@gmail.com'];
                @endphp

                @if (auth()->check() && in_array(auth()->user()->email, $allowedEmails))
                    <li class="menu-title"><i class="ri-more-fill"></i> <span>Subscriptions</span></li>

                    {{-- Subscription --}}
                    <li class="nav-item">
                        <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('all.package') ? 'active' : '' }}" href="#subscription" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ request()->routeIs('all.package') ? 'true' : 'false' }}" aria-controls="subscription">
                            <i class="ri-price-tag-3-line"></i> <span>Subscription</span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs('all.package') ? 'show' : '' }}" id="subscription">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('all.package') }}" class="nav-link sidebar-hover {{ request()->routeIs('all.package') ? 'active' : '' }}">Buy Package</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    {{-- End Subscription --}}
                @endif

                <li class="menu-title"><i class="ri-more-fill"></i> <span>Others</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('user.feedback.index') ? 'active' : '' }}" href="{{ route('user.feedback.index') }}">
                        <i class="las la-id-card"></i> <span>My Feedback</span>
                    </a>
                </li>
               
            </ul>

              <!-- Help Button -->
              <div class="text-center mt-4">
                <a href="{{route('contact.us')}}" class="btn gradient-btn-5 btn-block">
                    <i class="ri-question-fill"></i> Help
                </a>
            </div>
            <!-- End Help Button -->

        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
