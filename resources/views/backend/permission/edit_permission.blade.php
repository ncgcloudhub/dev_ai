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
            <option value="Greeting Card" {{ $permission->group_name == 'Greeting Card' ? 'selected' : '' }}>Greeting Card</option>
            <option value="Calendar" {{ $permission->group_name == 'Calendar' ? 'selected' : '' }}>Calendar</option>
            <option value="Chattermate" {{ $permission->group_name == 'Chattermate' ? 'selected' : '' }}>Chattermate</option>
            <option value="AI Content Creator" {{ $permission->group_name == 'AI Content Creator' ? 'selected' : '' }}>AI Content Creator</option>
            <option value="Custom Template" {{ $permission->group_name == 'Custom Template' ? 'selected' : '' }}>Custom Template</option>
            <option value="Prompt Library" {{ $permission->group_name == 'Prompt Library' ? 'selected' : '' }}>Prompt Library</option>
            <option value="Clever Experts" {{ $permission->group_name == 'Clever Experts' ? 'selected' : '' }}>Clever Experts</option>
            <option value="Clever Image Creator" {{ $permission->group_name == 'Clever Image Creator' ? 'selected' : '' }}>Clever Image Creator</option>
            <option value="Settings" {{ $permission->group_name == 'Settings' ? 'selected' : '' }}>Settings</option>
            <option value="Role & Permission" {{ $permission->group_name == 'Role & Permission' ? 'selected' : '' }}>Role & Permission</option>
            <option value="Manage User & Admin" {{ $permission->group_name == 'Manage User & Admin' ? 'selected' : '' }}>Manage User & Admin</option>
            <option value="Education" {{ $permission->group_name == 'Education' ? 'selected' : '' }}>Education</option>
            <option value="Manage Newsletter" {{ $permission->group_name == 'Manage Newsletter' ? 'selected' : '' }}>Manage Newsletter</option>
            <option value="Manage Referral" {{ $permission->group_name == 'Manage Referral' ? 'selected' : '' }}>Manage Referral</option>
            <option value="Manage Page" {{ $permission->group_name == 'Manage Page' ? 'selected' : '' }}>Manage Page</option>
            <option value="Jobs" {{ $permission->group_name == 'Jobs' ? 'selected' : '' }}>Jobs</option>
            
        </select>

            </div> 


 <button type="submit" class="btn btn-primary me-2">Save Changes </button>

        </form>

          </div>
        </div>




        </div>
      </div>
     

@endsection
