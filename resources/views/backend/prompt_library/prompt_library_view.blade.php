@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
<style>
    .copy-icon {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #007bff;
    }
    </style>
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Dashboards @endslot
@slot('title') Dashboard @endslot
@endcomponent

<div class="row">
   
           <div class="col-xxl-6">

            <div class="card">
                <div class="card-body"> 
                    <div class="live-preview">
                            <div class="col-md-12">
                                <label for="language" class="form-label">Prompt Name</label>
                                <p class="fw-medium link-primary">{{$prompt_library->prompt_name}}</p>
                            </div>
                    </div>
                </div> 
            </div>

            <div class="card">
                <div class="card-body"> 
                    <div class="live-preview">
                            <div class="col-md-12">
                                <label for="language" class="form-label">Prompt Description</label>
                                <p class="fw-medium link-primary">{{$prompt_library->description}}</p>
                            </div>
                    </div>
                </div> 
            </div>

            <div class="card">
                <div class="card-body"> 
                    <div class="live-preview">
                            <div class="col-md-12">
                                <label for="language" class="form-label">Actual Prompt</label>
                                <p class="fw-medium link-primary" style="position: relative;">
                                    {{$prompt_library->actual_prompt}}
                                    <span class="copy-icon" onclick="copyText(this)">📋</span>
                                </p>

                            </div>
                    </div>
                </div> 
            </div>


           </div>
</div>
@endsection
@section('script')

<script>
    function copyText(element) {
        const textToCopy = element.parentElement.innerText.replace('📋', '').trim();
        const tempInput = document.createElement("textarea");
        tempInput.style = "position: absolute; left: -9999px";
        tempInput.value = textToCopy;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        alert("Text copied to clipboard");
    }
    </script>

<script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
<script src="{{ URL::asset('build/libs/quill/quill.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

{{-- Submit Form Editor --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
