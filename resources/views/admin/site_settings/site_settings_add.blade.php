@extends('admin.layouts.master')
@section('title') Site Settings @endsection
@section('css')
<link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('build/libs/filepond/filepond.min.css') }}" type="text/css" />
<link rel="stylesheet"
    href="{{ URL::asset('build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">
@endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
@slot('title') Site Settings @endslot
@endcomponent

<div class="row">
<div class="col-xxl-6">
    <form method="POST" action="{{route('site.settings.store')}}" class="row g-3" enctype="multipart/form-data">
        @csrf
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Site Settings</h4>
        </div><!-- end card header -->

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
                                <img style="border-radius: 50%" src="{{ asset('backend/uploads/site/' . $setting->favicon) }}" alt="Current Favicon" width="100px" class="img-fluid"/>
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
                                <img style="border-radius: 50%" src="{{ asset('backend/uploads/site/' . $setting->watermark) }}" alt="Current watermark" width="100px" class="img-fluid"/>
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
                                    <img style="border-radius: 50%" src="{{ asset('backend/uploads/site/' . $setting->header_logo_light) }}" alt="Current Favicon" width="100px" class="img-fluid"/>
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
                                    <img style="border-radius: 50%" src="{{ asset('backend/uploads/site/' . $setting->header_logo_dark) }}" alt="Current Favicon" width="100px" class="img-fluid"/>
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
                
                        </div>
                        <!-- end card body -->
                    </div>
                    
                    <div class="col-md-12">
                        <label for="footer_text" class="form-label">Footer Text</label>
                        <input type="text" name="footer_text" class="form-control mb-3" id="footer_text" value="{{$setting->footer_text}}" placeholder="Enter Role">
                    </div>

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

                    <div class="col-md-12">
                        <label for="domain" class="form-label">Domain Expiry Date</label>
                        <input type="datetime-local" name="domain" class="form-control mb-3" id="domain" 
                               value="{{ old('domain', \Carbon\Carbon::parse($setting->domain)->format('Y-m-d\TH:i')) }}">
                    </div>
                    
                    <div class="col-md-12">
                        <label for="hosting" class="form-label">Hosting Expiry Date</label>
                        <input type="datetime-local" name="hosting" class="form-control mb-3" id="hosting" 
                               value="{{ old('hosting', \Carbon\Carbon::parse($setting->hosting)->format('Y-m-d\TH:i')) }}">
                    </div>
                
                    <div class="col-md-12">
                        <label for="ssl" class="form-label">SSL Expiry Date</label>
                        <input type="datetime-local" name="ssl" class="form-control mb-3" id="ssl" 
                               value="{{ old('ssl', \Carbon\Carbon::parse($setting->ssl)->format('Y-m-d\TH:i')) }}">
                    </div>
               
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="text-end">
            <button type="submit" class="btn btn-rounded gradient-btn-save mb-5" title="Save">
                <i class="{{$buttonIcons['save']}}"></i>
            </button>
        </div>
    </div>
</form>
</div>

<div class="col-xxl-6">
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
    </div><!-- end card -->
    
    {{-- Subscription Carry Over --}}
    <div class="card">
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
    </div><!-- end card -->
</div>
</div>
@endsection


@section('script')
<script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ URL::asset('build/libs/filepond/filepond.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}">
</script>
<script
    src="{{ URL::asset('build/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}">
</script>
<script
    src="{{ URL::asset('build/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}">
</script>
<script src="{{ URL::asset('build/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js') }}"></script>

<script src="{{ URL::asset('build/js/pages/form-file-upload.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.3/mammoth.browser.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}


{{-- HEX PAss Generator --}}
<script>
    document.getElementById('generateHexPassBtn').addEventListener('click', function() {
        const hex = generateHexPassword();
        document.getElementById('hex_pass').value = hex;
    });

    function generateHexPassword() {
        let hex = '';
        const characters = 'abcdef0123456789';
        for (let i = 0; i < 16; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            hex += characters[randomIndex];
        }
        return hex;
    }
</script>
@endsection