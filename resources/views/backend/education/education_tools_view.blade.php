@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('template.manage')}}">Education</a> @endslot
@slot('title') Manage Tools @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <form action="" method="POST" id="generate-content-form">
            @csrf
            <h1>Generate for {{ $tool->name }}</h1>

            <div class="form-group mb-3">
            <select class="form-select" name="grade_id" data-choices aria-label="Default select grade">
                <option selected="">Select Grade/Class</option>
                @foreach($classes as $item)
                    <option value="{{$item->id}}">{{$item->grade}}</option>
                @endforeach
            </select>
            </div>

            <!-- Loop through input types and labels -->
            @foreach (json_decode($tool->input_types) as $index => $input_type)
                <div class="form-group mb-3">
                    <label for="input_{{ $index }}">{{ json_decode($tool->input_labels)[$index] }}</label>

                    @if ($input_type == 'textarea')
                        <textarea class="form-control" id="input_{{ $index }}" name="input_{{ $index }}" rows="4" placeholder="{{ json_decode($tool->input_names)[$index] }}"></textarea>
                    @else
                        <input type="{{ $input_type }}" class="form-control" id="input_{{ $index }}" name="input_{{ $index }}" placeholder="{{ json_decode($tool->input_names)[$index] }}">
                    @endif
                </div>
            @endforeach

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">
                <i class="ri-auction-fill align-bottom me-1"></i>Generate
            </button>
        </form>


        <div id="stream-output"></div>
</div>



@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/nft-landing.init.js') }}"></script>

<script>
    $(document).ready(function () {
    $('form').on('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting the traditional way

        let formData = $(this).serialize(); // Collect the form data

        $.ajax({
            type: 'POST',
            url: '{{ route("tools.generate.content") }}', // Update with your route
            data: formData,
            xhrFields: {
                onprogress: function (event) {
                    const contentChunk = event.currentTarget.responseText;
                    $('#stream-output').html(contentChunk); // Update the stream output div
                }
            },
            success: function (response) {
                console.log('Content generation completed.');
            },
            error: function (error) {
                console.error('Error during content generation:', error);
            }
        });
    });
});

</script>

@endsection