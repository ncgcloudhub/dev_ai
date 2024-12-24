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
@slot('li_1') <a href="{{route('chat')}}">Expert</a> @endslot
@slot('title') Edit | {{$expert->expert_name}} @endslot
@endcomponent

<div class="col-xxl-6">
    <form method="POST" action="{{ route('expert.update', $expert->id) }}" class="row g-3" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Basic Information</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    
                    <div class="col-md-12">
                        <label for="expert_name" class="form-label">Expert Name <span class="text-danger">*</span></label>
                        <input type="text" name="expert_name" class="form-control mb-3" id="expert_name" placeholder="Enter Expert Name" value="{{ $expert->expert_name }}" required>
                    </div>

                    <div class="col-md-12">
                        <label for="character_name" class="form-label">Character Name <span class="text-danger">*</span></label>
                        <input type="text" name="character_name" class="form-control mb-3" id="character_name" placeholder="Enter Character Name" value="{{ $expert->character_name }}" required>
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label">Short Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control mb-3" id="description" rows="3" placeholder="Enter Short Description" required>{{ $expert->description }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <input type="text" name="role" class="form-control mb-3" id="role" placeholder="Enter Role" value="{{ $expert->role }}" required>
                    </div>

                    <div class="col-md-12">
                        <div class="col"> <label for="expertise" class="form-label">System Instruction <span class="text-danger">*</span></label></div>
                        <textarea name="expertise" class="form-control" id="expertise" rows="3" placeholder="Enter System Instruction" required>{{ $expert->expertise }}</textarea>
                    </div>
                   
                </div>
            </div>
        </div>

        {{-- Profile Picture Card --}}
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Profile Picture Selection</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <p class="text-muted">Upload Expert Image</p>
                <div class="avatar-xl mx-auto">
                    @if($expert->image)
                        <img src="{{ asset('backend/uploads/expert/' . $expert->image) }}" alt="Profile Picture" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px;">
                    @endif
                    <input type="file"
                    class="filepond filepond-input-circle"
                    name="image"
                    accept="image/png, image/jpeg, image/gif"/>
                </div>

            </div>
            <!-- end card body -->
        </div>
        {{-- Profile Picture Card End --}}

        <div class="col-12">
            <div class="text-end">
                <input type="submit" class="btn btn-rounded btn-primary mb-5 disabled-on-load" disabled value="Update">
            </div>
        </div>
    </form>
</div>

@endsection

@section('script')
<script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ URL::asset('build/libs/filepond/filepond.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js') }}"></script>

<script src="{{ URL::asset('build/js/pages/form-file-upload.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
