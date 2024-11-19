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
                <li class="menu-title"><span>Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="{{route('admin.dashboard')}}">
                        <i class="las la-home"></i> <span >Dashboard</span>
                    </a>
                </li>

                {{-- Greeting Card --}}
                @can('greetingCard.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="{{route('greeting.card')}}">
                        <i class="las la-id-card"></i> <span >Greeting Card</span>
                    </a>
                </li>
                @endcan
               
                {{-- Calender --}}
                @can('calender.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="{{route('calender')}}">
                        <i class="las la-calendar"></i> <span >Calender</span>
                    </a>
                </li>
                @endcan
                

                <li class="menu-title"><i class="ri-more-fill"></i> <span >AI Tools</span></li>

                @can('chattermate.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="{{route('main.chat.form')}}">
                        <i class="lab la-rocketchat"></i> <span >ChatterMate</span>
                    </a>
                </li>
                @endcan

                {{-- EDUCATION --}}
                @can('education.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="#education" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class="las la-book-open"></i> <span >Education</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="education">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('education.manageGradeSubject')
                                    <li class="nav-item">
                                        <a href="{{route('manage.grade.subject')}}" class="nav-link sidebar-hover" >Manage Grade/Subject</a>
                                    </li>
                                    @endcan
                                   
                                    @can('education.educationWizard')
                                    <li class="nav-item">
                                        <a href="{{route('education.form')}}" class="nav-link sidebar-hover" >Education Wizard</a>
                                    </li>
                                    @endcan
                                  
                                    @can('education.manageTools')
                                    <li class="nav-item">
                                        <a href="{{route('manage.education.tools')}}" class="nav-link" >Manage Tools</a>
                                    </li>
                                    @endcan
                                   
                                    @can('education.library')
                                    <li class="nav-item">
                                        <a href="{{route('education.tools.contents')}}" class="nav-link" >Library</a>
                                    </li>
                                    @endcan
                                   
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                </li>
                @endcan
               

                {{-- Fixed Template --}}
                @can('aiContentCreator.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="#template" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class=" lab la-blogger"></i> <span>AI Content Creator</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="template">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    
                                    @can('aiContentCreator.category')
                                    <li class="nav-item">
                                        <a href="{{route('aicontentcreator.category.add')}}" class="nav-link sidebar-hover">Content Categories</a>
                                    </li>
                                    @endcan
                                  
                                    @can('aiContentCreator.add')
                                    <li class="nav-item">
                                        <a href="{{route('aicontentcreator.add')}}" class="nav-link sidebar-hover">Create New Content</a>
                                    </li>
                                    @endcan  
                                    
                                    @can('aiContentCreator.manage')
                                    <li class="nav-item">
                                        <a href="{{route('aicontentcreator.manage')}}" class="nav-link sidebar-hover">Manage Content</a>
                                    </li>
                                    @endcan
                                    
                                </ul>
                            </div>     
                        </div>
                    </div>
                </li>
                @endcan

                {{-- Custom Template --}}
                @can('customTemplate.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="#AITools" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class="las la-pencil-ruler"></i> <span >Custom Template</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="AITools">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                   
                                    @can('customTemplate.category')
                                    <li class="nav-item">
                                        <a href="{{route('custom.template.category.add')}}" class="nav-link sidebar-hover" >Categories</a>
                                    </li>
                                    @endcan
                                   
                                    @can('customTemplate.add')
                                    <li class="nav-item">
                                        <a href="{{route('custom.template.add')}}" class="nav-link sidebar-hover" >Add Template</a>
                                    </li>
                                    @endcan
                                   
                                    @can('customTemplate.manage')
                                    <li class="nav-item">
                                        <a href="{{route('custom.template.manage')}}" class="nav-link sidebar-hover" >Manage Template</a>
                                    </li>
                                    @endcan
                                   
                                </ul>
                            </div> 
                        </div>
                    </div>
                </li>
                @endcan

                {{-- Prompt Library --}}
                @can('promptLibrary.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="#prompt" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class="las la-pen"></i> <span >Prompt Library</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="prompt">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('promptLibrary.category')
                                    <li class="nav-item">
                                        <a href="{{route('prompt.category.add')}}" class="nav-link sidebar-hover" >Prompt Categories</a>
                                    </li>
                                    @endcan
                                  
                                    @can('promptLibrary.subcategory')
                                    <li class="nav-item">
                                        <a href="{{route('prompt.subcategory.add')}}" class="nav-link sidebar-hover" >Prompt Sub-Categories</a>
                                    </li>
                                    @endcan
                                  
                                    @can('promptLibrary.add')
                                    <li class="nav-item">
                                        <a href="{{route('prompt.add')}}" class="nav-link sidebar-hover" >Add Prompt</a>
                                    </li>
                                    @endcan
                                   
                                    @can('promptLibrary.manage')
                                    <li class="nav-item">
                                        <a href="{{route('prompt.manage')}}" class="nav-link sidebar-hover" >Manage prompt</a>
                                    </li>
                                    @endcan
                                
                                </ul>
                            </div>     
                        </div>
                    </div>
                </li>
                @endcan
              
                {{-- Clever Expert --}}
                @can('cleverExpert.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="#sidebarExpert" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarExpert">
                        <i class="l las la-sms"></i> <span >Clever Experts</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="sidebarExpert">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('cleverExpert.add')
                                    <li class="nav-item">
                                        <a href="{{route('expert.add')}}" class="nav-link sidebar-hover" >Expert Creator</a>
                                    </li>
                                    @endcan
                                 
                                    @can('cleverExpert.manage')
                                    <li class="nav-item">
                                        <a href="{{route('chat')}}" class="nav-link sidebar-hover" >AI Experts</a>
                                    </li>
                                    @endcan
                                  
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                @endcan
              
                @can('cleverImageCreator.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="#generateImage" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class="las la-image"></i> <span >Clever Image Creator</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="generateImage">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('cleverImageCreator.generate')
                                    <li class="nav-item">
                                        <a href="#" class="nav-link sidebar-hover" data-bs-toggle="modal" data-bs-target="#loginModals">Generate Image</a>
                                    </li>
                                    @endcan
                                  
                                    @can('cleverImageCreator.manage')
                                    <li class="nav-item">
                                        <a href="{{route('manage.dalle.image.admin')}}" class="nav-link sidebar-hover" >Manage Image</a>
                                    </li>
                                    @endcan
                                  
                                    <li class="nav-item">
                                        <a href="{{route('manage.favourite.image')}}" class="nav-link sidebar-hover" >Manage Favorite Image</a>
                                    </li>
                                   
                                </ul>
                            </div>
      
                        </div>
                    </div>
                </li>
                @endcan
             

                <li class="menu-title"><i class="ri-more-fill"></i> <span >Settings</span></li>
               
                @can('settings.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="#settings" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class="las la-cog"></i> <span >Settings</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="settings">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('settings.pageSEOAdd')
                                    <li class="nav-item">
                                        <a href="{{route('add.page.seo')}}" class="nav-link sidebar-hover" >Page SEO Add</a>
                                    </li>
                                    @endcan
                                 
                                    @can('settings.AISettings')
                                    <li class="nav-item">
                                        <a href="{{route('ai.settings.add')}}" class="nav-link sidebar-hover" >AI Settings</a>
                                    </li>
                                    @endcan
                                  
                                    @can('settings.siteSettings')
                                    <li class="nav-item">
                                        <a href="{{route('site.settings.add')}}" class="nav-link sidebar-hover" >Site Settings</a>
                                    </li>
                                    @endcan
                                   
                                    @can('settings.SEOSettings')
                                    <li class="nav-item">
                                        <a href="{{route('seo.settings.add')}}" class="nav-link sidebar-hover" >SEO Settings</a>
                                    </li>
                                    @endcan
                                  
                                    @can('settings.FAQ')
                                    <li class="nav-item">
                                        <a href="{{route('manage.faq')}}" class="nav-link sidebar-hover">Manage FAQ</a>
                                    </li>
                                    @endcan
                                   
                                    @can('settings.privacyPolicy')
                                    <li class="nav-item">
                                        <a href="{{route('manage.privacy.policy')}}" class="nav-link sidebar-hover">Manage Privacy Policy</a>
                                    </li>
                                    @endcan
                                   
                                    @can('settings.termsAndConditions')
                                    <li class="nav-item">
                                        <a href="{{route('manage.terms.condition')}}" class="nav-link sidebar-hover">Manage Terms & Conditions</a>
                                    </li>
                                    @endcan
                                  
                                    @can('settings.pricing')
                                    <li class="nav-item">
                                        <a href="{{route('manage.pricing')}}" class="nav-link sidebar-hover">Manage Pricing</a>
                                    </li>
                                    @endcan
                                 
                                    @can('settings.frontEndDesign')
                                    <li class="nav-item">
                                        <a href="{{route('getDesign')}}" class="nav-link sidebar-hover">Manage Design</a>
                                    </li>
                                    @endcan
                                  
                                    @can('settings.countryBlock')
                                    <li class="nav-item">
                                        <a href="{{route('manage.block')}}" class="nav-link sidebar-hover">Manage Block</a>
                                    </li>
                                    @endcan
                                   
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                </li>
                @endcan

                 {{-- Permission ROLE --}}
                
                 <li class="nav-item">
                    <a class="nav-link menu-link" href="#role" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class=" lab la-blogger"></i> <span >Role & Permission</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="role">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    {{-- @can('rolePermission.permission')
                                    <li class="nav-item">
                                        <a href="{{ route('all.permission') }}" class="nav-link" >All Permission</a>
                                    </li>
                                    @endcan --}}
                                   
                                    <li class="nav-item">
                                        <a href="{{ route('all.permission') }}" class="nav-link" >All Permission</a>
                                    </li>
                                   
                                   
                                    {{-- @can('rolePermission.roles')
                                    <li class="nav-item">
                                        <a href="{{ route('all.roles') }}" class="nav-link" >All Roles</a>
                                    </li>
                                    @endcan --}}
                                   
                                    <li class="nav-item">
                                        <a href="{{ route('all.roles') }}" class="nav-link" >All Roles</a>
                                    </li>
                                   
                                   
                                    
                                    <li class="nav-item">
                                        <a href="{{ route('add.roles.permission') }}" class="nav-link">Role in Permission </a>
                                    </li> 
                                   
                                    {{-- @can('rolePermission.roleInPermission')
                                    <li class="nav-item">
                                        <a href="{{ route('add.roles.permission') }}" class="nav-link">Role in Permission </a>
                                    </li> 
                                    @endcan --}}
                                   
                                    @can('rolePermission.roleInPermissionManage')
                                    <li class="nav-item">
                                        <a href="{{ route('all.roles.permission') }}" class="nav-link">All Role in Permission </a>
                                    </li>
                                    @endcan
                                   
                                </ul>
                            </div>     
                        </div>
                    </div>
                </li>
              
              
               
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="#user_admin" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class="las la-user-circle"></i> <span >Manage User & Admin</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="user_admin">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('manageUser&Admin.manageUser')
                                    <li class="nav-item">
                                        <a href="{{route('manage.user')}}" class="nav-link sidebar-hover" >Manage User</a>
                                    </li>
                                    @endcan
                                    
                                   
                                    <li class="nav-item">
                                        <a href="{{route('all.admin')}}" class="nav-link sidebar-hover" >Manage Admin</a>
                                    </li>
                                   
                                    {{-- @can('manageUser&Admin.manageAdmin')
                                    <li class="nav-item">
                                        <a href="{{route('all.admin')}}" class="nav-link sidebar-hover" >Manage Admin</a>
                                    </li>
                                    @endcan --}}
                                   
                                    @can('manageUser&Admin.manageuserPackage')
                                    <li class="nav-item">
                                        <a href="{{route('admin.user.package.history')}}" class="nav-link sidebar-hover" >Manage User Package</a>
                                    </li>
                                    @endcan
                            
                                    @can('manageUser&Admin.manageFeedback')
                                    <li class="nav-item">
                                        <a href="{{route('admin.user.feedback.request')}}" class="nav-link sidebar-hover" >Manage Feedback Request</a>
                                    </li>
                                    @endcan
                                   
                                    @can('manageUser&Admin.sendEmail')
                                    <li class="nav-item">
                                        <a href="{{route('send.email.form')}}" class="nav-link sidebar-hover" >Send Email</a>
                                    </li>
                                    @endcan
                                  
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                </li>
                
             

                {{-- Newsletter --}}
                @can('manageNewsletter.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="{{route('newsletter.manage')}}">
                        <i class="las la-newspaper"></i> <span >Manage Newsletter</span>
                    </a>
                </li> 
                @endcan
            
                {{-- REFERRAL --}}
                @can('manageRefferal.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="{{route('manage.referral')}}">
                        <i class="las la-share-square"></i> <span >Manage Refferal</span>
                    </a>
                </li>
                @endcan
             
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="{{ route('dynamic-pages.index') }}">
                        <i class="lab la-wpforms"></i> <span >Manage Page</span>
                    </a>
                </li>
             
                {{-- JOB --}}
                @can('jobs.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="#jobs" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="AITools">
                        <i class=" las la-user-tie"></i> <span >Jobs</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="jobs">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('jobs.addJob')
                                        <li class="nav-item">
                                            <a href="{{route('add.job')}}" class="nav-link sidebar-hover" >Add Job</a>
                                        </li>
                                    @endcan
                                  
                                    @can('jobs.manageJobs')
                                        <li class="nav-item">
                                            <a href="{{route('manage.job')}}" class="nav-link sidebar-hover" >Manage Job</a>
                                        </li>
                                    @endcan
                                 
                                    @can('jobs.manageJobApplication')
                                        <li class="nav-item">
                                            <a href="{{route('manage.job.applications')}}" class="nav-link sidebar-hover" >Manage Job Application</a>
                                        </li>
                                    @endcan
                                  
                                </ul>
                            </div>
                            
                            
                        </div>
                    </div>
                </li>
                @endcan
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
