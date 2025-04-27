@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Admin @endslot
@slot('title') Add @endslot
@endcomponent

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

          <!-- middle wrapper start -->
          <div class="col-md-12 col-xl-12 middle-wrapper">
            <div class="row">
             <div class="card">
              <div class="card-body">

      <form id="myForm" method="POST" action="{{ route('store.admin') }}" class="forms-sample">
				@csrf


				<div class="form-group mb-3">
 <label for="exampleInputEmail1" class="form-label">Admin User Name   </label>
	 <input type="text" name="username" class="form-control" > 
				</div>

                <div class="form-group mb-3">
 <label for="exampleInputEmail1" class="form-label">Admin Name   </label>
     <input type="text" name="name" class="form-control" > 
                </div>

                <div class="form-group mb-3">
 <label for="exampleInputEmail1" class="form-label">Admin Email   </label>
     <input type="email" name="email" class="form-control" > 
                </div>


                <div class="form-group mb-3">
 <label for="exampleInputEmail1" class="form-label">Admin Phone   </label>
     <input type="text" name="phone" class="form-control" > 
                </div>



                <div class="form-group mb-3">
 <label for="exampleInputEmail1" class="form-label">Admin Address   </label>
     <input type="text" name="address" class="form-control" > 
                </div>

   <div class="form-group mb-3">
 <label for="exampleInputEmail1" class="form-label">Admin Password   </label>
     <input type="password" name="password" class="form-control" > 
                </div>


                <div class="form-group mb-3">
 <label for="exampleInputEmail1" class="form-label">Role Name    </label>
   <select name="roles" class="form-select" id="exampleFormControlSelect1">
                <option selected="" disabled="">Select Role</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
                </div>


	 <button type="submit" class="btn gradient-btn-save me-2" title="Save"><i class="{{$buttonIcons['save']}}"></i></button>

			</form>
              </div>
            </div>

            </div>
          </div>
          <!-- middle wrapper end -->
          <!-- right wrapper start -->

@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
