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
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="las la-home"></i> <span>Dashboard</span>
                    </a>
                </li>

                
                {{-- Calender --}}
                @can('calender.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('calender') ? 'active' : '' }}" href="{{route('calender')}}">
                        <i class="las la-calendar"></i> <span >Calender</span>
                    </a>
                </li>
                @endcan
                

                <li class="menu-title"><i class="ri-more-fill"></i> <span >AI Tools</span></li>

                {{-- EDUCATION --}}
                @can('education.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs(['manage.grade.subject', 'education.form', 'manage.education.tools', 'education.tools.category.add', 'education.tools.contents']) ? 'active' : '' }}"
                       href="#education" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs(['manage.grade.subject', 'education.form', 'manage.education.tools', 'education.tools.category.add', 'education.tools.contents']) ? 'true' : 'false' }}"
                       aria-controls="education">
                        <i class="las la-book-open"></i> <span>Education</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs(['manage.grade.subject', 'education.form', 'manage.education.tools', 'education.tools.category.add', 'education.tools.contents']) ? 'show' : '' }}" 
                         id="education">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    @can('education.manageGradeSubject')
                                    <li class="nav-item">
                                        <a href="{{ route('manage.grade.subject') }}" 
                                           class="nav-link sidebar-hover {{ request()->routeIs('manage.grade.subject') ? 'active' : '' }}">
                                            Manage Grade/Subject
                                        </a>
                                    </li>
                                    @endcan
                                    @can('education.educationWizard')
                                    <li class="nav-item">
                                        <a href="{{ route('education.form') }}" 
                                           class="nav-link sidebar-hover {{ request()->routeIs('education.form') ? 'active' : '' }}">
                                            Education Wizard
                                        </a>
                                    </li>
                                    @endcan
                                    @can('education.manageTools')
                                    <li class="nav-item">
                                        <a href="{{ route('manage.education.tools') }}" 
                                           class="nav-link sidebar-hover {{ request()->routeIs('manage.education.tools') ? 'active' : '' }}">
                                            Manage Tools
                                        </a>
                                    </li>
                                    @endcan
                                    <li class="nav-item">
                                        <a href="{{ route('education.tools.category.add') }}" 
                                           class="nav-link sidebar-hover {{ request()->routeIs('education.tools.category.add') ? 'active' : '' }}">
                                            Education Tools Category
                                        </a>
                                    </li>
                                    @can('education.library')
                                    <li class="nav-item">
                                        <a href="{{ route('education.tools.contents') }}" 
                                           class="nav-link sidebar-hover {{ request()->routeIs('education.tools.contents') ? 'active' : '' }}">
                                            Library
                                        </a>
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
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('aicontentcreator.*') ? 'active' : '' }}" href="#template" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('aicontentcreator.*') ? 'true' : 'false' }}" aria-controls="template">
                        <i class="lab la-blogger"></i> <span>AI Content Creator</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs('aicontentcreator.*') ? 'show' : '' }}" id="template">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                
                                    @can('aiContentCreator.category')
                                    <li class="nav-item">
                                        <a href="{{ route('aicontentcreator.category.add') }}" class="nav-link sidebar-hover {{ request()->routeIs('aicontentcreator.category.add') ? 'active' : '' }}">Content Categories</a>
                                    </li>
                                    @endcan
                
                                    @can('aiContentCreator.add')
                                    <li class="nav-item">
                                        <a href="{{ route('aicontentcreator.add') }}" class="nav-link sidebar-hover {{ request()->routeIs('aicontentcreator.add') ? 'active' : '' }}">Create New Content</a>
                                    </li>
                                    @endcan
                
                                    @can('aiContentCreator.manage')
                                    <li class="nav-item">
                                        <a href="{{ route('aicontentcreator.manage') }}" class="nav-link sidebar-hover {{ request()->routeIs('aicontentcreator.manage') ? 'active' : '' }}">Manage Content</a>
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
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('custom.template.*') ? 'active' : '' }}" href="#AITools" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('custom.template.*') ? 'true' : 'false' }}" aria-controls="AITools">
                        <i class="las la-pencil-ruler"></i> <span>Custom Template</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs('custom.template.*') ? 'show' : '' }}" id="AITools">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                
                                    @can('customTemplate.category')
                                    <li class="nav-item">
                                        <a href="{{ route('custom.template.category.add') }}" class="nav-link sidebar-hover {{ request()->routeIs('custom.template.category.add') ? 'active' : '' }}">Categories</a>
                                    </li>
                                    @endcan
                
                                    @can('customTemplate.add')
                                    <li class="nav-item">
                                        <a href="{{ route('custom.template.add') }}" class="nav-link sidebar-hover {{ request()->routeIs('custom.template.add') ? 'active' : '' }}">Add Template</a>
                                    </li>
                                    @endcan
                
                                    @can('customTemplate.manage')
                                    <li class="nav-item">
                                        <a href="{{ route('custom.template.manage') }}" class="nav-link sidebar-hover {{ request()->routeIs('custom.template.manage') ? 'active' : '' }}">Manage Template</a>
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
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('prompt.*') ? 'active' : '' }}" href="#prompt" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('prompt.*') ? 'true' : 'false' }}" aria-controls="prompt">
                        <i class="las la-pen"></i> <span>Prompt Library</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs('prompt.*') ? 'show' : '' }}" id="prompt">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                
                                    @can('promptLibrary.category')
                                    <li class="nav-item">
                                        <a href="{{ route('prompt.category.add') }}" class="nav-link sidebar-hover {{ request()->routeIs('prompt.category.add') ? 'active' : '' }}">Prompt Categories</a>
                                    </li>
                                    @endcan
                
                                    @can('promptLibrary.subcategory')
                                    <li class="nav-item">
                                        <a href="{{ route('prompt.subcategory.add') }}" class="nav-link sidebar-hover {{ request()->routeIs('prompt.subcategory.add') ? 'active' : '' }}">Prompt Sub-Categories</a>
                                    </li>
                                    @endcan
                
                                    @can('promptLibrary.add')
                                    <li class="nav-item">
                                        <a href="{{ route('prompt.add') }}" class="nav-link sidebar-hover {{ request()->routeIs('prompt.add') ? 'active' : '' }}">Add Prompt</a>
                                    </li>
                                    @endcan
                
                                    @can('promptLibrary.manage')
                                    <li class="nav-item">
                                        <a href="{{ route('prompt.manage') }}" class="nav-link sidebar-hover {{ request()->routeIs('prompt.manage') ? 'active' : '' }}">Manage prompt</a>
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
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs(['expert.add', 'expert.edit', 'chat']) ? 'active' : '' }}" href="#sidebarExpert" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs(['expert.add', 'expert.edit', 'chat']) ? 'true' : 'false' }}" aria-controls="sidebarExpert">
                        <i class="l las la-sms"></i> <span >Clever Experts</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs(['expert.add', 'expert.edit', 'chat']) ? 'show' : '' }}" id="sidebarExpert">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('cleverExpert.add')
                                    <li class="nav-item">
                                        <a href="{{route('expert.add')}}" class="nav-link sidebar-hover {{ request()->routeIs('expert.add') ? 'active' : '' }}" >Expert Creator</a>
                                    </li>
                                    @endcan
                                 
                                    @can('cleverExpert.manage')
                                    <li class="nav-item">
                                        <a href="{{route('chat')}}" class="nav-link sidebar-hover {{ request()->routeIs('chat') ? 'active' : '' }}" >AI Experts</a>
                                    </li>
                                    @endcan
                                  
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                @endcan

                {{-- Greeting Card --}}
                @can('greetingCard.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('greeting.card') ? 'active' : '' }}" 
                        href="{{ route('greeting.card') }}">
                        <i class="las la-id-card"></i> <span>Greeting Card</span>
                    </a>
                </li>
                @endcan

                {{-- Clever Image Creator --}}
                @can('cleverImageCreator.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs(['generate.image.view', 'stable.form', 'manage.dalle.image.admin', 'manage.favourite.image']) ? 'active' : '' }}" href="#generateImage" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs(['generate.image.view', 'stable.form', 'manage.dalle.image.admin', 'manage.favourite.image']) ? 'true' : 'false' }}" aria-controls="generateImage">
                        <i class="las la-image"></i> <span >Clever Image Creator</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs(['generate.image.view', 'stable.form', 'manage.dalle.image.admin', 'manage.favourite.image']) ? 'show' : '' }}" id="generateImage">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('cleverImageCreator.generate')
                                    <li class="nav-item">
                                        <a href="#" class="nav-link sidebar-hover {{ request()->routeIs(['generate.image.view', 'stable.form']) ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#loginModals">Generate Image</a>
                                    </li>
                                    @endcan
                                  
                                    @can('cleverImageCreator.manage')
                                    <li class="nav-item">
                                        <a href="{{route('manage.dalle.image.admin')}}" class="nav-link sidebar-hover {{ request()->routeIs('manage.dalle.image.admin') ? 'active' : '' }}" >Manage Image</a>
                                    </li>
                                    @endcan
                                  
                                    <li class="nav-item">
                                        <a href="{{route('manage.favourite.image')}}" class="nav-link sidebar-hover {{ request()->routeIs('manage.favourite.image') ? 'active' : '' }}" >Manage Favorite Image</a>
                                    </li>
                                   
                                </ul>
                            </div>
      
                        </div>
                    </div>
                </li>
                @endcan

                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover" href="#stableDiffusion" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="stableDiffusion">
                        <i class="las la-paint-brush"></i> <span>Stable Diffusion</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="stableDiffusion">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <!-- Upscale -->
                                    <li class="nav-item">
                                        <a href="{{ route('stable.upscale.form') }}" class="nav-link sidebar-hover">Upscale</a>
                                    </li>
                
                                    <!-- Edit -->
                                    <li class="nav-item">
                                        <a class="nav-link menu-link sidebar-hover" href="#stableEdit" data-bs-toggle="collapse"
                                            role="button" aria-expanded="false" aria-controls="stableEdit">Edit</a>
                                        <div class="collapse menu-dropdown" id="stableEdit">
                                            <ul class="nav nav-sm flex-column ms-3">
                                                <li class="nav-item">
                                                    <a href="/stable-edit-erase-form" class="nav-link sidebar-hover">Erase</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="/stable-edit-outpaint-form" class="nav-link sidebar-hover">Outpaint</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="/stable-edit-inpaint-form" class="nav-link sidebar-hover">Inpaint</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="/stable-edit-search-replace-form" class="nav-link sidebar-hover">Search and Replace</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="/stable-edit-search-recolor-form" class="nav-link sidebar-hover">Search and Recolor</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="/stable-edit-remove-bg-form" class="nav-link sidebar-hover">Remove Background</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('stable.edit.form') }}" class="nav-link sidebar-hover">Replace Background & Relight</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                
                                    <!-- Control -->
                                    <li class="nav-item">
                                        <a href="/stable-control-sketch-form" class="nav-link sidebar-hover">Control</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                
             

                <li class="menu-title"><i class="ri-more-fill"></i> <span >Settings</span></li>
               
                @can('settings.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs(['add.page.seo', 'ai.settings.add', 'site.settings.add', 'seo.settings.add', 'manage.faq', 'manage.privacy.policy', 'manage.terms.condition', 'manage.pricing', 'getDesign', 'manage.block', 'magic.ball.jokes']) ? 'active' : '' }}" href="#settings" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs(['add.page.seo', 'ai.settings.add', 'site.settings.add', 'seo.settings.add', 'manage.faq', 'manage.privacy.policy', 'manage.terms.condition', 'manage.pricing', 'getDesign', 'manage.block', 'magic.ball.jokes']) ? 'true' : 'false' }}" aria-controls="settings">
                        <i class="las la-cog"></i> <span >Settings</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs(['add.page.seo', 'ai.settings.add', 'site.settings.add', 'seo.settings.add', 'manage.faq', 'manage.privacy.policy', 'manage.terms.condition', 'manage.pricing', 'getDesign', 'manage.block', 'magic.ball.jokes']) ? 'show' : '' }}" id="settings">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('settings.pageSEOAdd')
                                    <li class="nav-item">
                                        <a href="{{route('add.page.seo')}}" class="nav-link sidebar-hover {{ request()->routeIs('add.page.seo') ? 'active' : '' }}" >Page SEO Add</a>
                                    </li>
                                    @endcan
                                 
                                    @can('settings.AISettings')
                                    <li class="nav-item">
                                        <a href="{{route('ai.settings.add')}}" class="nav-link sidebar-hover {{ request()->routeIs('ai.settings.add') ? 'active' : '' }}" >AI Settings</a>
                                    </li>
                                    @endcan
                                  
                                    @can('settings.siteSettings')
                                    <li class="nav-item">
                                        <a href="{{route('site.settings.add')}}" class="nav-link sidebar-hover {{ request()->routeIs('site.settings.add') ? 'active' : '' }}" >Site Settings</a>
                                    </li>
                                    @endcan
                                   
                                    @can('settings.SEOSettings')
                                    <li class="nav-item">
                                        <a href="{{route('seo.settings.add')}}" class="nav-link sidebar-hover {{ request()->routeIs('seo.settings.add') ? 'active' : '' }}" >SEO Settings</a>
                                    </li>
                                    @endcan
                                  
                                    @can('settings.FAQ')
                                    <li class="nav-item">
                                        <a href="{{route('manage.faq')}}" class="nav-link sidebar-hover {{ request()->routeIs('manage.faq') ? 'active' : '' }}">Manage FAQ</a>
                                    </li>
                                    @endcan
                                   
                                    @can('settings.privacyPolicy')
                                    <li class="nav-item">
                                        <a href="{{route('manage.privacy.policy')}}" class="nav-link sidebar-hover {{ request()->routeIs('manage.privacy.policy') ? 'active' : '' }}">Manage Privacy Policy</a>
                                    </li>
                                    @endcan
                                   
                                    @can('settings.termsAndConditions')
                                    <li class="nav-item">
                                        <a href="{{route('manage.terms.condition')}}" class="nav-link sidebar-hover {{ request()->routeIs('manage.terms.condition') ? 'active' : '' }}">Manage Terms & Conditions</a>
                                    </li>
                                    @endcan
                                  
                                    @can('settings.pricing')
                                    <li class="nav-item">
                                        <a href="{{route('manage.pricing')}}" class="nav-link sidebar-hover {{ request()->routeIs('manage.pricing') ? 'active' : '' }}">Manage Pricing</a>
                                    </li>
                                    @endcan
                                 
                                    @can('settings.frontEndDesign')
                                    <li class="nav-item">
                                        <a href="{{route('getDesign')}}" class="nav-link sidebar-hover {{ request()->routeIs('getDesign') ? 'active' : '' }}">Manage Design</a>
                                    </li>
                                    @endcan
                                  
                                    @can('settings.countryBlock')
                                    <li class="nav-item">
                                        <a href="{{route('manage.block')}}" class="nav-link sidebar-hover {{ request()->routeIs('manage.block') ? 'active' : '' }}">Manage Block</a>
                                    </li>
                                    @endcan
                                   
                                    {{-- @can must be added --}}
                                    <li class="nav-item">
                                        <a href="{{route('magic.ball.jokes')}}" class="nav-link sidebar-hover {{ request()->routeIs('magic.ball.jokes') ? 'active' : '' }}">Manage Jokes(Magic Ball)</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                @endcan

                {{-- Permission ROLE --}}
                @can('rolePermission.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs(['all.permission', 'all.roles', 'add.roles.permission', 'all.roles.permission']) ? 'active' : '' }}" href="#role" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs(['all.permission', 'all.roles', 'add.roles.permission', 'all.roles.permission']) ? 'true' : 'false' }}" aria-controls="role">
                        <i class=" lab la-blogger"></i> <span >Role & Permission</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs(['all.permission', 'all.roles', 'add.roles.permission', 'all.roles.permission']) ? 'show' : '' }}" id="role">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('rolePermission.permission')
                                    <li class="nav-item">
                                        <a href="{{ route('all.permission') }}" class="nav-link sidebar-hover {{ request()->routeIs('all.permission') ? 'active' : '' }}" >All Permission</a>
                                    </li>
                                    @endcan
                                   
                                    @can('rolePermission.roles')
                                    <li class="nav-item">
                                        <a href="{{ route('all.roles') }}" class="nav-link sidebar-hover {{ request()->routeIs('all.roles') ? 'active' : '' }}" >All Roles</a>
                                    </li>
                                    @endcan
                                   
                                    @can('rolePermission.roleInPermission')
                                    <li class="nav-item">
                                        <a href="{{ route('add.roles.permission') }}" class="nav-link sidebar-hover {{ request()->routeIs('add.roles.permission') ? 'active' : '' }}">Role in Permission </a>
                                    </li> 
                                    @endcan
                                   
                                    @can('rolePermission.roleInPermissionManage')
                                    <li class="nav-item">
                                        <a href="{{ route('all.roles.permission') }}" class="nav-link sidebar-hover {{ request()->routeIs('all.roles.permission') ? 'active' : '' }}">All Role in Permission </a>
                                    </li>
                                    @endcan
                                   
                                </ul>
                            </div>     
                        </div>
                    </div>
                </li>
                @endcan
              
                {{-- User & Admin --}}    
                @can('manageUser&Admin.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs(['manage.user', 'all.admin', 'admin.user.package.history', 'admin.user.feedback.request', 'send.email.form']) ? 'active' : '' }}" href="#user_admin" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs(['manage.user', 'all.admin', 'admin.user.package.history', 'admin.user.feedback.request', 'send.email.form']) ? 'true' : 'false' }}" aria-controls="user_admin">
                        <i class="las la-user-circle"></i> <span >Manage User & Admin</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs(['manage.user', 'all.admin', 'admin.user.package.history', 'admin.user.feedback.request', 'send.email.form']) ? 'show' : '' }}" id="user_admin">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('manageUser&Admin.manageUser')
                                    <li class="nav-item">
                                        <a href="{{route('manage.user')}}" class="nav-link sidebar-hover {{ request()->routeIs('manage.user') ? 'active' : '' }}" >Manage User</a>
                                    </li>
                                    @endcan
                                   
                                    @can('manageUser&Admin.manageAdmin')
                                    <li class="nav-item">
                                        <a href="{{route('all.admin')}}" class="nav-link sidebar-hover {{ request()->routeIs('all.admin') ? 'active' : '' }}" >Manage Admin</a>
                                    </li>
                                    @endcan
                                   
                                    @can('manageUser&Admin.manageuserPackage')
                                    <li class="nav-item">
                                        <a href="{{route('admin.user.package.history')}}" class="nav-link sidebar-hover {{ request()->routeIs('admin.user.package.history') ? 'active' : '' }}" >Manage User Package</a>
                                    </li>
                                    @endcan
                            
                                    @can('manageUser&Admin.manageFeedback')
                                    <li class="nav-item">
                                        <a href="{{route('admin.user.feedback.request')}}" class="nav-link sidebar-hover {{ request()->routeIs('admin.user.feedback.request') ? 'active' : '' }}" >Manage Feedback Request</a>
                                    </li>
                                    @endcan
                                   
                                    @can('manageUser&Admin.sendEmail')
                                    <li class="nav-item">
                                        <a href="{{route('send.email.form')}}" class="nav-link sidebar-hover {{ request()->routeIs('send.email.form') ? 'active' : '' }}" >Send Email</a>
                                    </li>
                                    @endcan
                                  
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                </li>
                @endcan
                
                {{-- Newsletter --}}
                @can('manageNewsletter.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('newsletter.manage') ? 'active' : '' }}" href="{{route('newsletter.manage')}}">
                        <i class="las la-newspaper"></i> <span >Manage Newsletter</span>
                    </a>
                </li> 
                @endcan
            
                {{-- REFERRAL --}}
                @can('manageRefferal.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('manage.referral') ? 'active' : '' }}" href="{{route('manage.referral')}}">
                        <i class="las la-share-square"></i> <span >Manage Refferal</span>
                    </a>
                </li>
                @endcan
             
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs('dynamic-pages.*') ? 'active' : '' }}" href="{{ route('dynamic-pages.index') }}">
                        <i class="lab la-wpforms"></i> <span >Manage Page</span>
                    </a>
                </li>
             
                {{-- JOB --}}
                @can('jobs.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link sidebar-hover {{ request()->routeIs(['add.job', 'manage.job', 'manage.job.applications']) ? 'active' : '' }}" href="#jobs" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs(['add.job', 'manage.job', 'manage.job.applications']) ? 'true' : 'false' }}" aria-controls="jobs">
                        <i class=" las la-user-tie"></i> <span >Jobs</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu {{ request()->routeIs(['add.job', 'manage.job', 'manage.job.applications']) ? 'show' : '' }}" id="jobs">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">

                                    @can('jobs.addJob')
                                        <li class="nav-item">
                                            <a href="{{route('add.job')}}" class="nav-link sidebar-hover {{ request()->routeIs('add.job') ? 'active' : '' }}" >Add Job</a>
                                        </li>
                                    @endcan
                                  
                                    @can('jobs.manageJobs')
                                        <li class="nav-item">
                                            <a href="{{route('manage.job')}}" class="nav-link sidebar-hover {{ request()->routeIs('manage.job') ? 'active' : '' }}" >Manage Job</a>
                                        </li>
                                    @endcan
                                 
                                    @can('jobs.manageJobApplication')
                                        <li class="nav-item">
                                            <a href="{{route('manage.job.applications')}}" class="nav-link sidebar-hover {{ request()->routeIs('manage.job.applications') ? 'active' : '' }}" >Manage Job Application</a>
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
