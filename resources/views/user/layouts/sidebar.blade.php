<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{route('user.dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('backend/uploads/site/light1.jpg') }}" alt="Header Logo Dark" height="22">              
            </span>
            <span class="logo-lg">
                <img src="{{ asset('backend/uploads/site/' . $siteSettings->header_logo_dark) }}" alt="Header Logo Dark" height="30">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{route('user.dashboard')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('backend/uploads/site/light2.jpg') }}" alt="Header Logo Light" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('backend/uploads/site/' . $siteSettings->header_logo_light) }}" alt="Header Logo Light" height="40">
            </span>
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
                    <a class="nav-link menu-link" href="{{route('user.dashboard')}}">
                        <i data-feather="home" class="icon-dual"></i> <span >Dashboard</span>
                    </a>
                </li>


             


                <li class="menu-title"><i class="ri-more-fill"></i> <span >AI Tools</span></li>

                {{-- Favorites --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#favorite" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class="las la-pencil-ruler"></i> <span >Favorites</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="favorite">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    <li class="nav-item">
                                        <a href="{{route('manage.favourite.image')}}" class="nav-link" >Manage Favorite Image</a>
                                    </li>
                                  
                                   
                                </ul>
                            </div>
                            
                            
                        </div>
                    </div>
                </li>


                {{-- Fixed Template --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('template.manage')}}">
                        <i class="lab la-blogger"></i> <span >Template</span>
                    </a>
                </li>

                 {{-- Eid Card --}}
                @if(Auth::user()->can('eid.card'))
                 <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('eid.card')}}">
                        <i class=" las la-atom"></i> <span >Greeting Card</span>
                    </a>
                </li>
                @endif

                {{-- Custom Template --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#AITools" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class="las la-pencil-ruler"></i> <span >Custom Template</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="AITools">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{route('custom.template.category.add')}}" class="nav-link" >Categories</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('custom.template.add')}}" class="nav-link" >Add Template</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('custom.template.manage')}}" class="nav-link" >Manage Template</a>
                                    </li>
                                   
                                </ul>
                            </div>
                            
                            
                        </div>
                    </div>
                </li>

                {{-- Prompt Library --}}
                @if(Auth::user()->can('add.prompt'))
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#prompt" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class="las la-pen"></i> <span >Prompt Library</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="prompt">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    <li class="nav-item">
                                        <a href="{{route('user.prompt.library')}}" class="nav-link" >Manage Prompt</a>
                                    </li>
                                </ul>
                            </div>     
                        </div>
                    </div>
                </li>
                @endif


                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarExpert" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarExpert">
                        <i class="l las la-sms"></i> <span >AI Chat</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="sidebarExpert">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    
                                    <li class="nav-item">
                                        <a href="{{route('chat')}}" class="nav-link" >Chat with AI Assistant</a>
                                    </li>
                                  
                                </ul>
                            </div>
                            
                            
                        </div>
                    </div>
                </li>

            

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#generateImage" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class="las la-pencil-ruler"></i> <span >Generate Image</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="generateImage">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    <li class="nav-item">
                                        <a href="{{route('generate.image.view')}}" class="nav-link" >Generate with Dall-E</a>
                                    </li>
                                  
                                   
                                </ul>
                            </div>
                            
                            
                        </div>
                    </div>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span >Subscriptions</span></li>

                 {{-- Subscription --}}
                 <li class="nav-item">
                    <a class="nav-link menu-link" href="#subscription" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="subscription">
                        <i class="l las la-sms"></i> <span >Subscription</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="subscription">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{route('all.package')}}" class="nav-link" >Buy Package</a>
                                    </li>
                                  
                                </ul>
                            </div>
                            
                            
                        </div>
                    </div>
                </li>
                 {{-- End Subscription --}}
               
            </ul>

              <!-- Help Button -->
              <div class="text-center mt-4">
                <a href="{{route('contact.us')}}" class="btn btn-primary btn-block">
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
