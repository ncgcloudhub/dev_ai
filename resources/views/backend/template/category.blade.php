@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Pages @endslot
@slot('title') Starter  @endslot
@endcomponent

<div class="col-xxl-6">
   
           
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Category Add</h4>
        </div><!-- end card header -->

        <div class="card-body">

            @include('admin.layouts.alerts')

            <button type="button" class="btn btn-primary btn-sm" id="sa-position">Click me</button>

            <div class="live-preview">
                <form  action="{{ route('template.category.store') }}" method="post" class="row g-3">
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
   
 <div class="card">
    
        <div class="card-body pt-0">

            <h6 class="text-uppercase fw-semibold mt-4 mb-3 text-muted">Category List</h6>
            <ul class="list-group">
                @foreach ($categories as $item)
                <li class="list-group-item"><i class="{{$item->category_icon}}"></i> {{$item->category_name}}</li>
                @endforeach
            </ul>

        </div><!-- end cardbody -->
    </div> 
 
</div>


@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/sweetalerts.init.js') }}"></script>

@endsection
