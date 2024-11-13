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
            <option value="greeting_card" {{ $permission->group_name == 'greeting_card' ? 'selected' : '' }}>Greeting Card</option>
            <option value="calendar" {{ $permission->group_name == 'calendar' ? 'selected' : '' }}>Calendar</option>
            <option value="chattermate" {{ $permission->group_name == 'chattermate' ? 'selected' : '' }}>Chattermate</option>
            <option value="ai_content_creator" {{ $permission->group_name == 'ai_content_creator' ? 'selected' : '' }}>AI Content Creator</option>
            <option value="custom_template" {{ $permission->group_name == 'custom_template' ? 'selected' : '' }}>Custom Template</option>
            <option value="prompt_library" {{ $permission->group_name == 'prompt_library' ? 'selected' : '' }}>Prompt Library</option>
            <option value="clever_expert" {{ $permission->group_name == 'clever_expert' ? 'selected' : '' }}>Clever Experts</option>
            <option value="clever_image_creator" {{ $permission->group_name == 'clever_image_creator' ? 'selected' : '' }}>Clever Image Creator</option>
            <option value="settings" {{ $permission->group_name == 'settings' ? 'selected' : '' }}>Settings</option>
            <option value="role_and_permission" {{ $permission->group_name == 'role_and_permission' ? 'selected' : '' }}>Role & Permission</option>
            <option value="manage_user_and_admin" {{ $permission->group_name == 'manage_user_and_admin' ? 'selected' : '' }}>Manage User & Admin</option>
            <option value="education" {{ $permission->group_name == 'education' ? 'selected' : '' }}>Education</option>
            <option value="manage_newsletter" {{ $permission->group_name == 'manage_newsletter' ? 'selected' : '' }}>Manage Newsletter</option>
            <option value="manage_referral" {{ $permission->group_name == 'manage_referral' ? 'selected' : '' }}>Manage Referral</option>
            <option value="manage_page" {{ $permission->group_name == 'manage_page' ? 'selected' : '' }}>Manage Page</option>
            <option value="jobs" {{ $permission->group_name == 'jobs' ? 'selected' : '' }}>Jobs</option>
            
        </select>

            </div> 


 <button type="submit" class="btn btn-primary me-2">Save Changes </button>

        </form>

          </div>
        </div>




        </div>
      </div>
     

@endsection
