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
@slot('title') Dynamic Buttons @endslot
@endcomponent

<div class="row">
<div class="col-xxl-6">
    @foreach(['save', 'add', 'edit'] as $type)
    <form action="{{ route('admin.button-styles.update') }}" method="POST">
        @csrf
        <input type="hidden" name="button_type" value="{{ $type }}">

        <div class="mb-3">
            <label>{{ ucfirst($type) }} Button</label>
            <div class="d-flex gap-3 flex-wrap">
                @foreach($buttonStyles->where('button_type', $type) as $style)
                    <label class="btn-option">
                        <input type="radio" name="class_name" value="{{ $style->class_name }}" 
                            {{ $style->is_selected ? 'checked' : '' }} hidden>
                        <button type="button" class="{{ $style->class_name }} btn-lg preview-btn">
                            {{ ucfirst($type) }}
                        </button>
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
    @endforeach
</div>

</div>
@endsection

<style>
    .btn-option {
        cursor: pointer;
    }
    .btn-option input:checked + .preview-btn {
        border: 3px solid black; /* Highlight selected */
    }
</style>

@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.3/mammoth.browser.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script>
    document.querySelectorAll('.btn-option').forEach(option => {
        option.addEventListener('click', () => {
            option.querySelector('input').checked = true;
        });
    });
</script>

@endsection