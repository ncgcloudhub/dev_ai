@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Prompt Sub-Category @endslot
@slot('title') Edit | {{$subcategory->sub_category_name}}  @endslot
@endcomponent

<a href="{{ route('prompt.subcategory.add') }}" class="btn waves-effect waves-light btn-primary mb-3">Add Sub Category
</a>


<div class="row">

    <div class="col-xxl-6">
        <div class="card">
        
                <div class="card-body">
                    <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">Sl.</th>
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
                                <td>{{ $item->sub_category_name }}</td>    
                                <td>
                                    <div class="form-check form-switch form-switch-md" dir="ltr">
    
                                        <a href="{{route('prompt.subcategory.edit',$item->id)}}" class="btn btn-primary btn-sm waves-effect waves-light d-inline-block" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="ri-edit-2-fill"></i></a>
    
                                        <a href="{{route('prompt.subcategory.delete',$item->id)}}" onclick="return confirm('Are you sure you want to delete this Customer')" class="btn btn-danger btn-sm waves-effect waves-light d-inline-block" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="ri-delete-bin-line"></i></a>
    
                                    </div>
                                </td>    
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

        </div>
    </div>


    <div class="col-xxl-6">
    
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Prompt Library Sub-Category Edit</h4>
            </div><!-- end card header -->
    
            <div class="card-body">
    
                {{-- @include('admin.layouts.alerts') --}}
    
                <div class="live-preview">
                    <form  action="{{ route('prompt.subcategory.update') }}" method="post" class="row g-3">
                        @csrf
                        <input type="hidden" name="id" value="{{$subcategory->id}}">  
                        <div class="form-floating mb-3">
                            <select class="form-select" name="category_id" id="category_id" aria-label="Floating label select example">
                                <option value="{{$subcategory->category_id}}" selected>{{$subcategory->category->category_name}}</option>
                                @foreach ($categories as $item)
                                <option value="{{$item->id}}">{{$item->category_name}}</option>
                                @endforeach
                            </select>
                            <label for="category_id" class="form-label">Category</label>
                        </div>
    
                        <div class="form-floating">
                            <input type="text" name="sub_category_name" value="{{$subcategory->sub_category_name}}" class="form-control" id="sub_category_name" placeholder="Enter Name">
                            <label for="category_name">Sub-Category Name</label>
                        </div>
    
                        <div class="form-floating">
    
                            <textarea type="text" name="sub_category_instruction" value="{{$subcategory->sub_category_instruction}}" class="form-control" id="sub_category_instruction" placeholder="Enter Instruction"></textarea>
                            <label for="icon">Sub-Category Instruction</label>
                        </div>
    
                        <div class="col-12">
                            <div class="text-end">
                                <button class="btn btn-rounded btn-primary mb-2">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
    
            </div>
        </div>
    </div>
    
    </div>
</div>

@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

