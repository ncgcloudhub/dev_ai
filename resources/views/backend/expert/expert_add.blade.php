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
@slot('title') Add @endslot
@endcomponent

<div class="col-xxl-6">
    <form method="POST" action="{{route('expert.store')}}" class="row g-3" enctype="multipart/form-data">
        @csrf
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Basic Information</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                
                    <div class="col-md-12">
                        <label for="expert_name" class="form-label">Expert Name <span class="text-danger">*</span></label>
                        <input type="text" name="expert_name" class="form-control mb-3" id="expert_name" placeholder="Enter Expert Name" required>
                    </div>

                    <div class="col-md-12">
                        <label for="character_name" class="form-label">Character Name <span class="text-danger">*</span></label>
                        <input type="text" name="character_name" class="form-control mb-3" id="character_name" placeholder="Enter Character Name" required>
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label">Short Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control mb-3" id="description" rows="3" placeholder="Enter Short Description" required></textarea>
                    </div>

                    <div class="col-md-12">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <input type="text" name="role" class="form-control mb-3" id="role" placeholder="Enter Role" required>
                    </div>

                    <div class="col-md-12">
                        <div class="col"> <label for="expertise" class="form-label">System Instruction <span class="text-danger">*</span></label></div>
                        <textarea name="expertise" class="form-control" id="expertise" rows="3" placeholder="Enter System Instruction" required></textarea>
                    </div>
               
            </div>
        </div>
    </div>

    {{-- 2nd Card --}}
    {{-- <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Train Expert</h4>
        </div><!-- end card header -->

        <div class="card-body custom-input-informations">
            <div class="live-preview">
                <div class="row">
                    <div class="col-2"> <label for="train_expert" class="form-label">Expertise</label></div>
                    <div class="col-3"><input type="file" name="train_upload" id="train_upload" multiple></div>
                </div>
                    <textarea name="train_expert" class="form-control" id="train_expert" rows="3" placeholder="Enter Expertise"></textarea>

            </div>
        </div>
    </div> --}}
    {{-- 2nd Card End --}}

    {{-- 3rd Card --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Profile Picture Selection</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <p class="text-muted">Upload Expert Image</p>
            <div class="avatar-xl mx-auto">
                <input type="file"
                class="filepond filepond-input-circle"
                name="image"
                accept="image/png, image/jpeg, image/gif"/>
            </div>

        </div>
        <!-- end card body -->
    </div>
    {{-- 3rd Card End --}}
    <div class="col-12">
        <div class="text-end">
            <input type="submit" class="btn btn-rounded gradient-btn-save mb-5 disabled-on-load" disabled value="Save">
        </div>
    </div>
</form>
</div>

@endsection



{{-- 
<script>
    $(document).ready(function(){
        $('#train_upload').change(function(){
            var files = $(this)[0].files;

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                // Check if the file is a TXT file
                if (file.type === "text/plain") {
                    reader.onload = (function(file) {
                        return function(e) {
                            // Append the content of TXT files to the textarea
                            $('#train_expert').val($('#train_expert').val() + '\n' + e.target.result);
                        };
                    })(file);

                    reader.readAsText(file);
                } else if (file.type === "application/msword" || file.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                    reader.onload = function(e) {
                        // Read the content of the DOCX file
                        var arrayBuffer = e.target.result;
                        mammoth.extractRawText({arrayBuffer: arrayBuffer})
                            .then(function(result){
                                // Append the extracted text content to the textarea
                                $('#train_expert').val($('#train_expert').val() + '\n' + result.value);
                            })
                            .catch(function(err){
                                console.log(err);
                            });
                    };

                    reader.readAsArrayBuffer(file);
                }
            }
        });
    });
</script> --}}





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