@extends('admin.layouts.master')
@section('title') Prompt Sub-Category Add  @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('prompt.manage')}}">Prompts</a> @endslot
@slot('title') Sub-Category Add  @endslot
@endcomponent

<div class="row g-4">
    <div class="col-12 col-xxl-6">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="alternative-pagination" class="table align-middle table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">Sl.</th>
                                <th scope="col">Category Name</th>
                                <th scope="col">Sub Category Name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sl = 1; // Initialize the variable outside the loop
                            @endphp
                            @foreach ($subcategories as $item)
                            <tr>
                                <td>{{ $sl++ }}</td> <!-- Increment the variable and display its value -->
                                <td>{{ $item->category->category_name }}</td>   
                                <td>{{ $item->sub_category_name }}</td>    
                                
                                <td>
                                    <div class="d-flex">

                                        @can('promptLibrary.subcategory.edit')
                                        <a href="{{route('prompt.subcategory.edit',$item->id)}}" class="gradient-btn-edit mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"> 
                                            <i class="{{$buttonIcons['edit']}}"></i>
                                        </a>
                                        @endcan
                                     
                                        @can('promptLibrary.subcategory.delete')
                                        <a href="{{route('prompt.subcategory.delete',$item->id)}}" onclick="return confirm('Are you sure you want to delete this Subcategory?')" class="gradient-btn-delete mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                            <i class="{{$buttonIcons['delete']}}"></i>
                                        </a>
                                        @endcan
                                      
                                    </div>
                                </td>    
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xxl-6">
        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row">
                <h4 class="card-title mb-2 mb-md-0 flex-grow-1 text-md-left">
                    Prompt Library Sub-Category Add
                    <a href="{{ route('prompt.manage') }}" class="btn text-white gradient-btn-11 mx-1">Library</a>
                    <a href="{{ route('prompt.category.add') }}" class="btn text-white gradient-btn-11 mx-1">Category</a>
                </h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <form action="{{ route('prompt.subcategory.store') }}" method="post" class="row g-3">
                        @csrf

                        <div class="form-floating mb-3">
                            <select class="form-select" name="category_id" id="category_id" aria-label="Floating label select example" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach ($categories as $item)
                                    <option value="{{$item->id}}">{{$item->category_name}}</option>
                                @endforeach
                            </select>
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="sub_category_name" class="form-control" id="sub_category_name" placeholder="Enter Sub-Category Name" required>
                            <label for="sub_category_name">Sub-Category Name <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea type="text" name="sub_category_instruction" class="form-control" id="sub_category_instruction" placeholder="Enter Instruction"></textarea>
                            <label for="sub_category_instruction">Sub-Category Instruction</label>
                        </div>

                        <div class="col-12">
                            <div class="text-end">
                                <button class="btn btn-rounded gradient-btn-save mb-2 disabled-on-load" title="Save" disabled><i class="{{$buttonIcons['save']}}"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- end card body -->
        </div>
    </div>
</div>

@include('admin.layouts.datatables')


@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection