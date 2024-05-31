@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Pages @endslot
@slot('title') Prompt Sub-Category  @endslot
@endcomponent

<div class="col-xxl-6">
   
           
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Prompt Library Sub-Category Add</h4>
        </div><!-- end card header -->

        <div class="card-body">

            {{-- @include('admin.layouts.alerts') --}}

            <div class="live-preview">
                <form  action="{{ route('prompt.subcategory.store') }}" method="post" class="row g-3">
                    @csrf

                    <div class="form-floating mb-3">
                        <select class="form-select" name="category_id" id="category_id" aria-label="Floating label select example">
                            <option disabled selected="">Select Category</option>
                            @foreach ($categories as $item)
                            <option value="{{$item->id}}">{{$item->category_name}}</option>
                            @endforeach
                        </select>
                        <label for="category_id" class="form-label">Category</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" name="sub_category_name" class="form-control" id="sub_category_name" placeholder="Enter Name">
                        <label for="category_name">Sub-Category Name</label>
                    </div>

                    <div class="form-floating">
                        
                        <textarea type="text" name="sub_category_instruction" class="form-control" id="sub_category_instruction" placeholder="Enter Instruction"></textarea>
                        <label for="icon">Sub-Category Instruction</label>
                    </div>
                                    
                    <div class="col-12">
                        <div class="text-end">
                            <button class="btn btn-rounded btn-primary mb-2">Save</button>
                        </div>
                    </div>
                </form>
            </div>
      
        </div>
    </div>
   
 <div class="card">
    
        <div class="card-body pt-0">

            <h6 class="text-uppercase fw-semibold mt-4 mb-3 text-muted">Sub-Category List</h6>
            <ul class="list-group">
                @foreach ($subcategories as $item)
                <li class="list-group-item">{{$item->sub_category_name}}</li>
                @endforeach
            </ul>

        </div><!-- end cardbody -->
    </div> 
 
</div>


@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

