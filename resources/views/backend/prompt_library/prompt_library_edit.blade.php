@extends('admin.layouts.master')
@section('title') Prompt Edit  @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1')  Template @endslot
@slot('title') Add @endslot
@endcomponent

<div class="row">
    <div class="col">
        <h5 class="mb-3">Custom Vertical Tabs</h5>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active show" id="custom-v-pills-home-tab" data-bs-toggle="pill" href="#custom-v-pills-home" role="tab" aria-controls="custom-v-pills-home"
                                aria-selected="true">
                                <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                                Basic Information</a>
                            <a class="nav-link" id="custom-v-pills-profile-tab" data-bs-toggle="pill" href="#custom-v-pills-profile" role="tab" aria-controls="custom-v-pills-profile"
                                aria-selected="false">
                                <i class="ri-user-2-line d-block fs-20 mb-1"></i>
                                SEO</a>
                         
                        </div>
                    </div> <!-- end col-->
                    <div class="col-lg-10">
                        <div class="tab-content text-muted mt-3 mt-lg-0">
                            <div class="tab-pane fade active show" id="custom-v-pills-home" role="tabpanel" aria-labelledby="custom-v-pills-home-tab">
                                <div class="d-flex mb-4">
                                    <form method="POST" action="{{route('prompt.update')}}" class="row g-3">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$category->id}}">  
                                    <div class="card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0 flex-grow-1">Prompt Library Edit</h4>
                                        </div><!-- end card header -->
                                
                                        <div class="card-body">
                                            <div class="live-preview">
                                                
                                                    <div class="form-floating mb-3">
                                                        <input type="text" name="prompt_name" value="{{$category->prompt_name}}" class="form-control" id="prompt_name" placeholder="Enter Template Name">
                                                        <label for="prompt_name" class="form-label">Prompt Name</label>
                                                    </div>
                                                    
                                                    <div class="form-floating mb-3">
                                                        <input type="text" name="icon" value="{{$category->icon}}" class="form-control" id="icon" placeholder="Enter Icon">
                                                        <label for="icon" class="form-label">Icon</label>
                                                    </div>
                                
                                                    <div class="form-floating mb-3">
                                                        <select class="form-select" name="category_id" id="category_id" aria-label="Floating label select example">
                                                            <option value="{{$category->category_id}}" selected="">{{$category->category->category_name}}</option>
                                                            @foreach ($categories as $item)
                                                            <option value="{{$item->id}}">{{$item->category_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="category_id" class="form-label">Category</label>
                                                    </div>
                                
                                                    <div class="form-floating mb-3">
                                                    <select class="form-select" name="subcategory_id" id="subcategory_id" aria-label="Floating label select example">
                                                        <option value="{{$category->sub_category_id}}" selected>{{$category->subcategory->sub_category_name}}</option>
                                                    </select>
                                                    <label for="category_id" class="form-label">Subcategory</label>
                                                    </div>
                                                    
                                                    <div class="form-floating mb-3" data-bs-toggle="tooltip" data-bs-placement="right" title="Give a short description of the Template Name">
                                                        <textarea name="description" class="form-control" id="description" rows="3" placeholder="Enter description">{{$category->description}}</textarea>
                                                        <label for="description">Description</label>
                                                    </div>
                                
                                                    <div class="form-floating mb-3" data-bs-toggle="tooltip" data-bs-placement="right">
                                                        <textarea name="actual_prompt" class="form-control" id="actual_prompt" rows="3" placeholder="Enter actual_prompt" >{{$category->actual_prompt}}</textarea>
                                                        <label for="actual_prompt">Actual Prompt</label>
                                                    </div>
                                
                                                     <!-- Checkbox for adding to frontend -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="inFrontEndCheckbox" id="inFrontEndCheckbox" {{ $category->inFrontEnd == 'yes' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inFrontEndCheckbox">
                                            Add to frontend
                                        </label>
                                    </div>
                                
                                    <!-- Hidden input field to store the value -->
                                    <input type="hidden" name="inFrontEnd" id="inFrontEnd" value="{{ $category->inFrontEnd }}">
                                               
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-12">
                                        <div class="text-end">
                                            <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Update">
                                        </div>
                                    </div>
                                </form>
                                </div>
                               
                            </div><!--end tab-pane-->
    
    
                            <div class="tab-pane fade" id="custom-v-pills-profile" role="tabpanel" aria-labelledby="custom-v-pills-profile-tab">
                                <div class="d-flex mb-4">
                                    <form method="POST" action="{{ route('prompt.seo.update') }}" class="row g-3">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $category->id }}">
                                    
                                        <!-- Page Title -->
                                        <div class="col-12">
                                            <label for="page_title" class="form-label">Page Title</label>
                                            <input type="text" class="form-control" id="page_title" name="page_title" value="{{ $category->page_title ?? '' }}" placeholder="Enter Page Title">
                                        </div>
                                    
                                        <!-- Page Description -->
                                        <div class="col-12">
                                            <label for="page_description" class="form-label">Page Description</label>
                                            <textarea class="form-control" id="page_description" name="page_description" rows="4" placeholder="Enter Page Description">{{ $category->page_description ?? '' }}</textarea>
                                        </div>
                                    
                                        <!-- Page Tagging -->
                                        <div class="col-12">
                                            <label for="page_tagging" class="form-label">Page Tagging</label>
                                            <textarea class="form-control" id="page_tagging" name="page_tagging" rows="3" placeholder="Enter Page Tags">{{ $category->page_tagging ?? '' }}</textarea>
                                        </div>
                                    
                                        <div class="col-12">
                                            <div class="text-end">
                                                <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Update">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                               
                            </div><!--end tab-pane-->
    
    
    
    
                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row-->
            </div><!-- end card-body -->
        </div><!--end card-->
    </div><!--end col-->
    </div>





<div class="col-xxl-6">
   
</div>


@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script>
    document.getElementById('inFrontEndCheckbox').addEventListener('change', function() {
        // Update the hidden input value based on checkbox state
        document.getElementById('inFrontEnd').value = this.checked ? 'yes' : 'no';
    });
</script>

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