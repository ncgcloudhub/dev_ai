@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="/assets/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/build/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('/build/libs/filepond/filepond.min.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ URL::asset('/build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">
@endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1')  Site Settings @endslot
@slot('title') Add @endslot
@endcomponent

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
               
            </div>
        </div>
    </div>


    
    <div class="col-12">
        <div class="text-end">
            <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Save">
        </div>
    </div>
</form>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.3/mammoth.browser.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection