@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Pages @endslot
@slot('title') Custom Category  @endslot
@endcomponent

<div class="row">
    <div class="col-xxl-6">
        <div class="card">
                <div class="card-body">
                    <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">Sl.</th>
                                <th scope="col">Category List</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sl = 1; // Initialize the variable outside the loop
                            @endphp
                            @foreach ($categories as $item)
                            <tr>
                                <td>{{ $sl++ }}</td> <!-- Increment the variable and display its value -->
                                  
                                <td><i class="{{ $item->category_icon }}"></i>  {{ $item->category_name }}</td>    
                                
                                <td>
                                    <div class="form-check form-switch form-switch-md" dir="ltr">
    
                                        @can('customTemplate.category.edit')
                                        <a href="{{route('custom.template.category.edit',$item->id)}}" class="text-primary d-inline-block edit-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"> <i class="ri-pencil-fill fs-16"></i> </a>
                                        @endcan

                                        @can('customTemplate.category.delete')
                                        <a href="{{route('custom.template.category.delete',$item->id)}}" onclick="return confirm('Are you sure you want to delete this Category')" class="text-danger d-inline-block remove-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"> <i class="ri-delete-bin-5-fill fs-16"></i> </a>
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

    <div class="col-xxl-6">
    
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Category Add</h4>
            </div><!-- end card header -->
    
            <div class="card-body">
    
                {{-- @include('admin.layouts.alerts') --}}
    
                <div class="live-preview">
                    <form  action="{{ route('custom.template.category.store') }}" method="post" class="row g-3">
                        @csrf
    
                        <div class="form-floating">
                            <input type="text" name="category_name" class="form-control" id="category_name" placeholder="Enter Category">
                            <label for="category_name">Category</label>
                        </div>
    
                        <div class="form-floating">
                            <input type="text" name="category_icon" class="form-control" id="category_icon" placeholder="Enter Icon">
                            <label for="icon">Enter Icon</label>
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
    </div>
    
</div>



@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

