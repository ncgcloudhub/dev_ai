@extends('admin.layouts.master')
@section('title') Block Country @endsection


@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('manage.block')}}">Country</a> @endslot
@slot('title') Block @endslot
@endcomponent

<div class="row">


<div class="col-xxl-6">
    <div class="card">

            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl.</th>
                            <th scope="col">Country Code</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sl = 1; // Initialize the variable outside the loop
                        @endphp
                        @foreach ($countries as $item)
                        <tr>
                            <td>{{ $sl++ }}</td> <!-- Increment the variable and display its value -->
                              
                            <td>{{ $item->country_code }}</td>    
                            
                            <td>
                                <div class="form-check form-switch form-switch-md" dir="ltr">

                                    @can('settings.countryBlock.edit')
                                        <a href="{{route('block.countries.edit',$item->id)}}" class="text-primary d-inline-block edit-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"> <i class="ri-pencil-fill fs-16"></i> </a>
                                    @endcan
                                   
                                    @can('settings.countryBlock.delete')
                                        <form action="{{ route('block.countries.delete', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this Category?')" class="text-danger d-inline-block remove-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" style="background:none; border:none; padding:0; cursor:pointer;">
                                                <i class="ri-delete-bin-5-fill fs-16"></i>
                                            </button>
                                        </form>
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
    <form method="POST" action="{{route('country.block.store')}}" class="row g-3">
        @csrf
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Block Country</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                
                    <div class="col-md-12">
                        <label for="country_code" class="form-label">Country Code</label>
                        <input type="text" name="country_code" class="form-control mb-3" id="country_code" placeholder="Enter Country Code" required>
                    </div>

                  
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="text-end">
            <input type="submit" class="btn btn-rounded gradient-btn-save mb-5" value="Save">
        </div>
    </div>
</form>
</div>
</div>

@endsection

@include('admin.layouts.datatables')

@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection