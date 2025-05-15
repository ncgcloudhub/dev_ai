@extends('admin.layouts.master')
@section('title') Site Settings @endsection
@section('css')
<link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('build/libs/filepond/filepond.min.css') }}" type="text/css" />
<link rel="stylesheet"
    href="{{ URL::asset('build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">@endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
@slot('title') Site Settings @endslot
@endcomponent

<div class="row">
    <div class="col-xxl-6">
        <form method="POST" action="{{route('site.settings.store')}}" class="row g-3" enctype="multipart/form-data">
            @csrf
            
            {{-- Tab Navigation --}}
            <div class="card mb-4">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#site-settings" role="tab">
                                <i class="ri-global-line fs-20"></i> Site
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#social-settings" role="tab">
                                <i class="ri-share-line fs-20"></i> Socials
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#frontend-settings" role="tab">
                                <i class="ri-paint-brush-line fs-20"></i> Frontend
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#dashboard-settings" role="tab">
                                <i class="ri-dashboard-line fs-20"></i> Dashboard
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Tab Content --}}
            <div class="tab-content">
                
                {{-- Site Settings Tab --}}
                <div class="tab-pane active" id="site-settings" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                           <div class="live-preview">
                
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Favicon Picture Selection</h4>
                                    </div><!-- end card header -->
                                    
                                    <div class="card-body">
                                    
                                        <div class="avatar-xl mx-auto">
                                            <input type="file"
                                                class="filepond filepond-input-circle"
                                                name="favicon"
                                                accept="image/png, image/jpeg, image/gif"/>
                                        </div>
                                
                                        <!-- Display current favicon image -->
                                        <div class="mt-3">
                                            @if($setting->favicon)
                                                <img style="border-radius: 50%" src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $setting->favicon }}" alt="Current Favicon" width="100px" class="img-fluid"/>
                                            @else
                                                <p></p>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Watermark (Images)</h4>
                                    </div><!-- end card header -->
                                    
                                    <div class="card-body">
                                    
                                        <div class="avatar-xl mx-auto">
                                            <input type="file"
                                                class="filepond filepond-input-circle"
                                                name="watermark"
                                                accept="image/png, image/jpeg, image/gif"/>
                                        </div>
                                
                                        <!-- Display current watermark image -->
                                        <div class="mt-3">
                                            @if($setting->watermark)
                                                <img style="border-radius: 50%" src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $setting->watermark }}" alt="Current watermark" width="100px" class="img-fluid"/>
                                            @else
                                                <p></p>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>

                                    <div class="col-md-12">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control mb-3" id="title" value="{{$setting->title}}" placeholder="Enter Character Name">
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Header Logo when Background is Light</h4>
                                        </div><!-- end card header -->
                                
                                        <div class="card-body">
                                        
                                            <div class="avatar-xl mx-auto">
                                                <input type="file"
                                                class="filepond filepond-input-circle"
                                                name="header_logo_light"
                                                accept="image/png, image/jpeg, image/gif"/>
                                            </div>
                                            <!-- Display current favicon image -->
                                            <div class="mt-3">
                                                @if($setting->header_logo_light)
                                                    <img style="border-radius: 50%" src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $setting->header_logo_light }}" alt="Current Favicon" width="100px" class="img-fluid"/>
                                                @else
                                                    <p></p>
                                                @endif
                                            </div>
                                
                                        </div>
                                        <!-- end card body -->
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Header Logo when Background is Dark</h4>
                                        </div><!-- end card header -->
                                
                                        <div class="card-body">
                                        
                                            <div class="avatar-xl mx-auto">
                                                <input type="file"
                                                class="filepond filepond-input-circle"
                                                name="header_logo_dark"
                                                accept="image/png, image/jpeg, image/gif"/>
                                            </div>

                                            <div class="mt-3">
                                                @if($setting->header_logo_dark)
                                                    <img style="border-radius: 50%" src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $setting->header_logo_dark }}" alt="Current Favicon" width="100px" class="img-fluid"/>
                                                @else
                                                    <p></p>
                                                @endif
                                            </div>
                                
                                        </div>
                                        <!-- end card body -->
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Banner Image</h4>
                                        </div><!-- end card header -->
                                
                                        <div class="card-body">
                                        
                                            <div class="avatar-xl mx-auto">
                                                <input type="file"
                                                class="filepond filepond-input-circle"
                                                name="banner_img"
                                                accept="image/png, image/jpeg, image/gif"/>
                                            </div>

                                             <div class="mt-3">
                                                @if($setting->banner_img)
                                                    <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $setting->banner_img }}" alt="Current Favicon" width="200px" class="img-fluid"/>
                                                @else
                                                    <p></p>
                                                @endif
                                            </div>
                                
                                        </div>
                                        <!-- end card body -->
                                    </div>

                                    <div class="col-md-12">
                                        <label for="Banner Text" class="form-label">Banner Text</label>
                                        <input type="text" name="banner_text" class="form-control mb-3" id="banner_text" value="{{$setting->banner_text}}" placeholder="Enter Role">
                                    </div>

                                    {{-- Magic Ball --}}
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Magic Ball (Loading Screen)</h4>
                                        </div><!-- end card header -->

                                        <div class="card-body">
                                        
                                            <div class="avatar-xl mx-auto">
                                                <input type="file"
                                                class="filepond filepond-input-circle"
                                                name="magic_ball"
                                                accept="image/png, image/jpeg, image/gif"/>
                                            </div>

                                             <div class="mt-3">
                                               @if ($siteSettings->magic_ball)
                                                    @php
                                                        $ext = pathinfo($siteSettings->magic_ball, PATHINFO_EXTENSION);
                                                    @endphp

                                                    @if ($ext === 'webm')
                                                        <video autoplay loop muted playsinline class="magic-ball-video">
                                                            <source src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->magic_ball }}" type="video/webm">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @elseif ($ext === 'gif')
                                                        <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->magic_ball }}" width="200px" alt="Loading..." class="magic-ball-gif">
                                                    @endif
                                                @endif
                                            </div>

                                        </div>
                                        <!-- end card body -->
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Footer Logo</h4>
                                        </div><!-- end card header -->
                                
                                        <div class="card-body">
                                        
                                            <div class="avatar-xl mx-auto">
                                                <input type="file"
                                                class="filepond filepond-input-circle"
                                                name="footer_logo"
                                                accept="image/png, image/jpeg, image/gif"/>
                                            </div>

                                            <div class="mt-3">
                                                @if($setting->footer_logo)
                                                    <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $setting->footer_logo }}" alt="Current Favicon" width="100px" class="img-fluid"/>
                                                @else
                                                    <p></p>
                                                @endif
                                            </div>
                                
                                        </div>
                                        <!-- end card body -->
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label for="footer_text" class="form-label">Footer Text</label>
                                        <input type="text" name="footer_text" class="form-control mb-3" id="footer_text" value="{{$setting->footer_text}}" placeholder="Enter Role">
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Social Settings Tab --}}
                <div class="tab-pane" id="social-settings" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="facebook" class="form-label">Facebook</label>
                                    <input type="text" name="facebook" class="form-control mb-3" id="facebook" placeholder="Enter Role">
                                </div>

                                <div class="col-md-12">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" name="instagram" class="form-control mb-3" id="instagram" placeholder="Enter Role">
                                </div>

                                <div class="col-md-12">
                                    <label for="youtube" class="form-label">Youtube</label>
                                    <input type="text" name="youtube" class="form-control mb-3" id="youtube" placeholder="Enter Role">
                                </div>

                                <div class="col-md-12">
                                    <label for="linkedin" class="form-label">LinkedIn</label>
                                    <input type="text" name="linkedin" class="form-control mb-3" id="linkedin" placeholder="Enter Role">
                                </div>

                                <div class="col-md-12">
                                    <label for="twitter" class="form-label">Twitter</label>
                                    <input type="text" name="twitter" class="form-control mb-3" id="twitter" placeholder="Enter Role">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Frontend Settings Tab --}}
                <div class="tab-pane" id="frontend-settings" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">

                                {{-- Banner Image --}}
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Banner Image</h4>
                                    </div><!-- end card header -->
                            
                                    <div class="card-body">
                                    
                                        <div class="avatar-xl mx-auto">
                                            <input type="file"
                                            class="filepond filepond-input-circle"
                                            name="banner_img"
                                            accept="image/png, image/jpeg, image/gif"/>
                                        </div>

                                            <div class="mt-3">
                                            @if($setting->banner_img)
                                                <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $setting->banner_img }}" alt="Current Favicon" width="200px" class="img-fluid"/>
                                            @else
                                                <p></p>
                                            @endif
                                        </div>
                            
                                    </div>
                                    <!-- end card body -->
                                </div>

                                {{-- Webm/gif for generate images --}}
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Generate Image Webm/gif</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">
                                        <div class="avatar-xl mx-auto">
                                            <input type="file"
                                            class="filepond filepond-input-circle"
                                            name="generate_image_webm"
                                            accept="image/gif, video/webm"/>
                                        </div>
                                            <div class="mt-3">
                                            @if ($siteSettings->generate_image_webm)
                                                @php
                                                    $ext = pathinfo($siteSettings->generate_image_webm, PATHINFO_EXTENSION);
                                                @endphp

                                                @if ($ext === 'webm')
                                                    <video autoplay loop muted playsinline class="generate-image-video">
                                                        <source src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->generate_image_webm }}" type="video/webm">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @elseif ($ext === 'gif')
                                                    <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->generate_image_webm }}" width="200px" alt="Loading..." class="generate-image-webm">
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>

                                {{-- Webm/gif for generate contents --}}
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Generate Content Webm/gif</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">
                                        <div class="avatar-xl mx-auto">
                                            <input type="file"
                                            class="filepond filepond-input-circle"
                                            name="generate_content_webm"
                                            accept="image/gif, video/webm"/>
                                        </div>
                                            <div class="mt-3">
                                            @if ($siteSettings->generate_content_webm)
                                                @php
                                                    $ext = pathinfo($siteSettings->generate_content_webm, PATHINFO_EXTENSION);
                                                @endphp

                                                @if ($ext === 'webm')
                                                    <video autoplay loop muted playsinline class="generate-content-video">
                                                        <source src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->generate_content_webm }}" type="video/webm">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @elseif ($ext === 'gif')
                                                    <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->generate_content_webm }}" width="200px" alt="Loading..." class="generate-content-webm">
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>

                                {{-- Webm/gif for prompt library --}}
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Prompt Library Webm/gif</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">
                                        <div class="avatar-xl mx-auto">
                                            <input type="file"
                                            class="filepond filepond-input-circle"
                                            name="prompt_library_webm"
                                            accept="image/gif, video/webm"/>
                                        </div>
                                            <div class="mt-3">
                                            @if ($siteSettings->prompt_library_webm)
                                                @php
                                                    $ext = pathinfo($siteSettings->prompt_library_webm, PATHINFO_EXTENSION);
                                                @endphp

                                                @if ($ext === 'webm')
                                                    <video autoplay loop muted playsinline class="prompt-library-video">
                                                        <source src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->prompt_library_webm }}" type="video/webm">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @elseif ($ext === 'gif')
                                                    <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->prompt_library_webm }}" width="200px" alt="Loading..." class="prompt-library-webm">
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>

                                {{-- Webm/gif for chatbot --}}
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Chat Bot Webm/gif</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">
                                        <div class="avatar-xl mx-auto">
                                            <input type="file"
                                            class="filepond filepond-input-circle"
                                            name="chat_bot_webm"
                                            accept="image/gif, video/webm"/>
                                        </div>
                                            <div class="mt-3">
                                            @if ($siteSettings->chat_bot_webm)
                                                @php
                                                    $ext = pathinfo($siteSettings->chat_bot_webm, PATHINFO_EXTENSION);
                                                @endphp

                                                @if ($ext === 'webm')
                                                    <video autoplay loop muted playsinline class="chat-bot-video">
                                                        <source src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->chat_bot_webm }}" type="video/webm">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @elseif ($ext === 'gif')
                                                    <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->chat_bot_webm }}" width="200px" alt="Loading..." class="chat-bot-webm">
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                
                                {{-- Add more frontend fields --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dashboard Settings Tab --}}
                <div class="tab-pane" id="dashboard-settings" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="dashboard_theme" class="form-label">Dashboard Theme</label>
                                    <select class="form-select" name="dashboard_theme" id="dashboard_theme">
                                        <option value="light" {{ ($setting->dashboard_theme ?? '') === 'light' ? 'selected' : '' }}>Light</option>
                                        <option value="dark" {{ ($setting->dashboard_theme ?? '') === 'dark' ? 'selected' : '' }}>Dark</option>
                                        <option value="system" {{ ($setting->dashboard_theme ?? '') === 'system' ? 'selected' : '' }}>System Default</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="analytics_enabled" 
                                               name="analytics_enabled" {{ ($setting->analytics_enabled ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="analytics_enabled">
                                            Enable Analytics Tracking
                                        </label>
                                    </div>
                                </div>
                                
                                {{-- Add more dashboard fields --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="col-12 mt-4">
                <div class="text-end">
                    <button type="submit" class="btn btn-rounded gradient-btn-save mb-5" title="Save">
                        <i class="{{$buttonIcons['save']}}"></i> Save All Settings
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Right Column (Existing Hex Password and Subscription Sections) --}}
    <div class="col-xxl-6">
        {{-- Your existing right column content --}}
        <div class="card">
                    <div class="card-header">
            <h4 class="card-title">Generate Hex Password</h4>
        </div><!-- end card header -->

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('hexPass.store') }}">
                @csrf

                <!-- HexPass Input -->
                <div class="mb-3">
                    <label for="hex_pass" class="form-label">Hex Password</label>
                    <input type="text" class="form-control" id="hex_pass" name="hex_pass" value="{{ $setting->hex_pass }}">
                    @error('hex_pass')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <p style="font-style: italic">* First Generate a Hex Key and then Save</p>

                <!-- Generate HexPass Button -->
                <button type="submit" class="btn btn-rounded gradient-btn-generate mb-5" title="Generate">
                    <i class="{{$buttonIcons['generate']}}"></i>
                </button>

                <button type="submit" class="btn btn-rounded gradient-btn-save mb-5" title="Save">
                    <i class="{{$buttonIcons['save']}}"></i>
                </button>
            </form>
        </div><!-- end card-body -->
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title">Subscription Carry Over</h4>
            </div><!-- end card header -->

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.settings.rollover.update') }}">
                    @csrf
                
                    <div class="mb-4">
                        <label class="form-label"><strong>Renewal Behavior for Tokens & Credits</strong></label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rollover_enabled" id="carry_over" value="1" {{ old('rollover_enabled', $setting->rollover_enabled) ? 'checked' : '' }}>
                            <label class="form-check-label" for="carry_over">Carry Over (+=)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rollover_enabled" id="replace" value="0" {{ !old('rollover_enabled', $setting->rollover_enabled) ? 'checked' : '' }}>
                            <label class="form-check-label" for="replace">Replace (=)</label>
                        </div>
                    </div>
                
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
                
            </div><!-- end card-body -->
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Your existing scripts --}}
<script>
    // Initialize Bootstrap tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="tab"]'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
</script>
@endsection