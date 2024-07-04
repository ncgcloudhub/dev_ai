@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
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

        <h6 class="card-title">Add Permission   </h6>

 <form id="myForm" method="POST" action="{{ route('update.permission') }}" class="forms-sample">
            @csrf

<input type="hidden" name="id" value="{{ $permission->id }}">

            <div class="form-group mb-3">
<label for="exampleInputEmail1" class="form-label">Permission Name   </label>
 <input type="text" name="name" class="form-control" value="{{ $permission->name }}" >

            </div>

                <div class="form-group mb-3">
<label for="exampleInputEmail1" class="form-label">Group Name   </label>
           <select name="group_name" class="form-select" id="exampleFormControlSelect1">
            <option selected="" disabled="">Select Group</option>
            <option value="template" {{ $permission->group_name == 'template' ? 'selected' : '' }}>Template</option>
            <option value="custom_template" {{ $permission->group_name == 'custom_template' ? 'selected' : '' }}>Custom Template</option> 
            <option value="prompt_library" {{ $permission->group_name == 'prompt_library' ? 'selected' : '' }}>Prompt Library</option> 
            <option value="chat" {{ $permission->group_name == 'chat' ? 'selected' : '' }}>Chat</option> 
            <option value="image" {{ $permission->group_name == 'image' ? 'selected' : '' }}>Image </option> 
            <option value="settings" {{ $permission->group_name == 'settings' ? 'selected' : '' }}>Settings </option> 
            <option value="user" {{ $permission->group_name == 'user' ? 'selected' : '' }}>User</option> 
            <option value="pricing" {{ $permission->group_name == 'pricing' ? 'selected' : '' }}>Pricing</option> 
            <option value="newsletter" {{ $permission->group_name == 'newsletter' ? 'selected' : '' }}>Newsletter</option> 
            <option value="refferal" {{ $permission->group_name == 'refferal' ? 'selected' : '' }}>Refferal</option> 
            <option value="page" {{ $permission->group_name == 'page' ? 'selected' : '' }}>Page</option> 
            <option value="job" {{ $permission->group_name == 'job' ? 'selected' : '' }}>Job</option> 
            <option value="role" {{ $permission->group_name == 'role' ? 'selected' : '' }}>Role & Permission </option>  
        </select>

            </div> 


 <button type="submit" class="btn btn-primary me-2">Save Changes </button>

        </form>

          </div>
        </div>




        </div>
      </div>
     

@endsection
