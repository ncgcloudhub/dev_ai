@extends('admin.layouts.master')
@section('title') AI Content Creator Category Add  @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('aicontentcreator.manage')}}">Content Creator Tools</a> @endslot
@slot('title') Category Add  @endslot
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
    
                                        @can('aiContentCreator.category.edit')
                                        <a href="{{route('aicontentcreator.category.edit',$item->id)}}" class="gradient-btn-edit d-inline-block edit-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"> <i class="{{$buttonIcons['edit']}}"></i> </a>
                                        @endcan
                                        
                                        @can('aiContentCreator.category.delete')
                                        <a href="{{route('aicontentcreator.category.delete',$item->id)}}" onclick="return confirm('Are you sure you want to delete this Category')" class="gradient-btn-delete d-inline-block remove-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"> <i class="{{$buttonIcons['delete']}}"></i> </a>
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
                    <form  action="{{ route('aicontentcreator.category.store') }}" method="post" class="row g-3">
                        @csrf
    
                        <div class="form-floating">
                            <input type="text" name="category_name" class="form-control" id="category_name" placeholder="Enter Category" required>
                            <label for="category_name">Category <span class="text-danger">*</span></label>
                        </div>
    
                        <div class="form-floating">
                            <input type="text" name="category_icon" class="form-control" id="category_icon" placeholder="Enter Icon">
                            <label for="icon">Enter Icon</label>
                        </div>
    
                        <div class="col-12">
                            <div class="text-end">
                                <button title="Save" class="btn btn-rounded gradient-btn-save mb-2 disabled-on-load" disabled><i class="{{$buttonIcons['save']}}"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
    
            </div>
        </div>
    </div>
    
</div>

@include('admin.layouts.datatables')

@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

