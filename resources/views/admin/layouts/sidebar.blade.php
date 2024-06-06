<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{route('admin.dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('backend/uploads/site/light1.jpg') }}" alt="Header Logo Dark" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('backend/uploads/site/' . $siteSettings->header_logo_dark) }}" alt="Header Logo Dark" height="30">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{route('admin.dashboard')}}" class="logo logo-light">
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
                    <a class="nav-link menu-link" href="{{route('admin.dashboard')}}">
                        <i data-feather="home" class="icon-dual"></i> <span >Dashboard</span>
                    </a>
                </li>

                  {{-- Eid Card --}}
                  <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('eid.card')}}">
                        <i class=" las la-atom"></i> <span >Greeting Card</span>
                    </a>
                </li>


             


                <li class="menu-title"><i class="ri-more-fill"></i> <span >AI Tools</span></li>


                  {{-- Fixed Template --}}

                  <li class="nav-item">
                    <a class="nav-link menu-link" href="#template" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class=" lab la-blogger"></i> <span >Template</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="template">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    <li class="nav-item">
                                        <a href="{{route('template.category.add')}}" class="nav-link" >Categories</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('template.add')}}" class="nav-link" >Add Template</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('template.manage')}}" class="nav-link" >Manage Template</a>
                                    </li>
                                   
                                </ul>
                            </div>     
                        </div>
                    </div>
                </li>


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
                                        <a href="{{route('prompt.category.add')}}" class="nav-link" >Prompt Categories</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('prompt.subcategory.add')}}" class="nav-link" >Prompt Sub-Categories</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('prompt.add')}}" class="nav-link" >Add Prompt</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('prompt.manage')}}" class="nav-link" >Manage prompt</a>
                                    </li>
                                   
                                </ul>
                            </div>     
                        </div>
                    </div>
                </li>


                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarExpert" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarExpert">
                        <i class="l las la-sms"></i> <span >Chat</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="sidebarExpert">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{route('expert.add')}}" class="nav-link" >Expert</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('chat')}}" class="nav-link" >Lets Chat</a>
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
                                        <a href="{{route('generate.image.view')}}" class="nav-link" >Generate Image Using Dall-E</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('manage.dalle.image.admin')}}" class="nav-link" >Manage Image</a>
                                    </li>
                                   
                                </ul>
                            </div>
      
                        </div>
                    </div>
                </li>


                <li class="menu-title"><i class="ri-more-fill"></i> <span >Settings</span></li>
               
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#settings" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class="las la-cog"></i> <span >Settings</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="settings">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    <li class="nav-item">
                                        <a href="{{route('add.page.seo')}}" class="nav-link" >Page SEO Add</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{route('ai.settings.add')}}" class="nav-link" >AI Settings</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{route('site.settings.add')}}" class="nav-link" >Site Settings</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{route('seo.settings.add')}}" class="nav-link" >SEO Settings</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="{{route('manage.faq')}}" class="nav-link">Manage FAQ</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{route('manage.privacy.policy')}}" class="nav-link">Manage Privacy Policy</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="{{route('manage.terms.condition')}}" class="nav-link">Manage Terms & Conditions</a>
                                    </li>
                      


                                   
                                </ul>
                            </div>
                            
                            
                        </div>
                    </div>
                </li>

            
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('manage.user')}}">
                        <i class=" las la-user-circle"></i> <span >Manage User</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('manage.pricing')}}">
                        <i class="  las la-dollar-sign"></i> <span >Manage Pricing</span>
                    </a>
                </li>
            {{-- USER END--}}
             
            {{-- JOB --}}
                <li class="menu-title"><i class="ri-more-fill"></i> <span >JOB</span></li>
               
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#jobs" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class=" las la-user-tie"></i> <span >Jobs</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="jobs">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    <li class="nav-item">
                                        <a href="{{route('add.job')}}" class="nav-link" >Add Job</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('manage.job')}}" class="nav-link" >Manage Job</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('manage.job.applications')}}" class="nav-link" >Manage Job Application</a>
                                    </li>
                                   
                                </ul>
                            </div>
                            
                            
                        </div>
                    </div>
                </li>

            {{-- JOB END--}}

             
            
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
