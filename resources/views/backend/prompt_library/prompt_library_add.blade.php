@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('prompt.manage')}}">Prompts</a> @endslot
@slot('title') Add Prompt @endslot
@endcomponent

<div class="col-xxl-6">
    <form method="POST" action="{{route('prompt.store')}}" class="row g-3">
        @csrf
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Prompt Library Add</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                
                    <div class="form-floating mb-3">
                        <input type="text" name="prompt_name" class="form-control" id="prompt_name" placeholder="Enter Template Name" required>
                        <label for="prompt_name" class="form-label">Prompt Name <span class="text-danger">*</span></label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="text" name="icon" class="form-control" id="icon" placeholder="Enter Icon">
                        <label for="icon" class="form-label">Icon</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" name="category_id" id="category_id" aria-label="Floating label select example" required>
                            <option value="" disabled selected="">Select Category</option>
                            @foreach ($categories as $item)
                            <option value="{{$item->id}}">{{$item->category_name}}</option>
                            @endforeach
                        </select>
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-floating mb-3">
                    <select class="form-select" name="subcategory_id" id="subcategory_id" aria-label="Floating label select example" required>
                        <option value="" disabled selected>Select Subcategory</option>
                    </select>
                    <label for="category_id" class="form-label">Subcategory <span class="text-danger">*</span></label>
                    </div>
                    
                    <div class="form-floating mb-3" data-bs-toggle="tooltip" data-bs-placement="right" title="Give a short description of the Template Name">
                        <textarea name="description" class="form-control" id="description" rows="3" placeholder="Enter description" ></textarea>
                        <label for="description">Description</label>
                    </div>

                    <div class="form-floating mb-3" data-bs-toggle="tooltip" data-bs-placement="right">
                        <textarea name="actual_prompt" class="form-control" id="actual_prompt" rows="3" placeholder="Enter actual_prompt" required></textarea>
                        <label for="actual_prompt">Actual Prompt <span class="text-danger">*</span></label>
                    </div>
               
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="text-end">
            <input type="submit" class="btn btn-rounded btn-primary mb-5 disabled-on-load" disabled value="Save">
        </div>
    </div>
</form>
</div>


@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>

{{-- SUB CATEGORY PROMPT--}}
<script>
    document.getElementById('category_id').addEventListener('change', function() {
    var categoryId = this.value;
    fetch('/prompt/subcategories/' + categoryId)
        .then(response => response.json())
        .then(data => {
            var subcategorySelect = document.getElementById('subcategory_id');
            subcategorySelect.innerHTML = '';
            data.forEach(subcategory => {
                var option = document.createElement('option');
                option.value = subcategory.id;
                option.text = subcategory.sub_category_name;
                subcategorySelect.appendChild(option);
            });
        });
});

</script>
@endsection