@extends('admin.layouts.master')
@section('title') Edit Roles ({{ $roles->name }}) @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Role & Permission @endslot
@slot('title') Edit Permission @endslot
@endcomponent

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>


      <!-- middle wrapper start -->
      <div class="col-md-8 col-xl-8 middle-wrapper">
        <div class="row">
         <div class="card">
          <div class="card-body">

       
			<h6 class="card-title">Edit Roles   </h6>

      <form id="myForm" method="POST" action="{{ route('update.roles') }}" class="forms-sample">
           @csrf
   
     <input type="hidden" name="id" value="{{ $roles->id }}">
   
           <div class="form-group mb-3">
    <label for="exampleInputEmail1" class="form-label">Roles Name   </label>
      <input type="text" name="name" class="form-control" value="{{ $roles->name }}" >
   
           </div>
   
      <button type="submit" class="btn gradient-btn-save me-2">Save Changes </button>
   
         </form>

          </div>
        </div>

        </div>
      </div>
     

@endsection


@section('script')
  <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection