@extends('admin.layouts.master')
@section('title') Prompt Category Edit @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('prompt.manage')}}">Prompts</a> @endslot
@slot('title') Category Edit | {{$category->category_name}} @endslot
@endcomponent


<a href="{{ route('prompt.category.add') }}" class="btn waves-effect waves-light gradient-btn-add mb-3" title="Add Prompt Category"><i class="{{$buttonIcons['add']}}"></i></a>

<div class="row">
    <div class="col-xxl-6">
        <div class="card">
   
            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl.</th>
                            <th scope="col">Category Name</th>
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
                            <td>{{ $item->category_name }}</td>    
                            <td>
                                <div class="form-check form-switch form-switch-md" dir="ltr">

                                    <a href="{{route('prompt.category.edit',$item->id)}}" class="gradient-btn-edit d-inline-block" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="{{$buttonIcons['edit']}}"></i></a>

                                    <a href="{{route('prompt.category.delete',$item->id)}}" onclick="return confirm('Are you sure you want to delete this Customer')" class="gradient-btn-delete d-inline-block" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="{{$buttonIcons['delete']}}"></i> </a>

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
                <h4 class="card-title mb-0 flex-grow-1">Prompt Library Category Edit</h4>
            </div><!-- end card header -->
    
            <div class="card-body">
    
                {{-- @include('admin.layouts.alerts') --}}
    
                <div class="live-preview">
                    <form  action="{{ route('prompt.category.update') }}" method="post" class="row g-3">
                        @csrf
                        <input type="hidden" name="id" value="{{$category->id}}">  
                        <div class="form-floating">
                            <input type="text" name="category_name" class="form-control" value="{{$category->category_name}}" id="category_name" placeholder="Enter Category" required>
                            <label for="category_name">Category <span class="text-danger">*</span></label>
                        </div>
    
                        <div class="form-floating">
                            <input type="text" name="category_icon" class="form-control" value="{{$category->category_icon}}" id="category_icon" placeholder="Enter Icon">
                            <label for="icon">Enter Icon</label>
                        </div>
    
                        <div class="col-12">
                            <div class="text-end">
                                <button class="btn btn-rounded gradient-btn-save mb-2 disabled-on-load" disabled title="Update"><i class="{{$buttonIcons['save']}}"></i></button>
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

