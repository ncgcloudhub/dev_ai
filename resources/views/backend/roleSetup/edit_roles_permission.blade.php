@extends('admin.layouts.master')
@section('title') Edit Roles In Permission ({{ $role->name }}) @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Role & Permission @endslot
@slot('title') Edit Permission @endslot
@endcomponent

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<style type="text/css">
  .form-check-label{
    text-transform: capitalize;
  }
</style>

      <!-- middle wrapper start -->
      <div class="col-md-8 col-xl-8 middle-wrapper">
        <div class="row">
         <div class="card">
          <div class="card-body">

       
			<h6 class="card-title">Edit Roles   </h6>

      <form id="myForm" method="POST" action="{{ route('admin.roles.update', $role->id) }}" class="forms-sample">
        @csrf
        
    
        <div class="form-group mb-3">
            <label for="exampleInputEmail1" class="form-label">Roles Name</label>
            <h3>{{ $role->name }}</h3>
        </div>
    
        <div class="form-check mb-2">
            <input type="checkbox" class="form-check-input" id="checkDefaultmain">
            <label class="form-check-label" for="checkDefaultmain">
                Permission All
            </label>
        </div>
    
        <hr>
    
        @foreach($permission_groups as $group)
            <div class="row">
                <div class="col-3">
                    @php
                        $permissions = App\Models\User::getPermissionByGroupName($group->group_name);
                    @endphp
    
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="checkDefault{{ $loop->index }}" {{ App\Models\User::roleHasPermissions($role, $permissions) ? 'checked' : '' }}>
                        <label class="form-check-label" for="checkDefault{{ $loop->index }}">
                            {{ $group->group_name }}
                        </label>
                    </div>
                </div>
    
                <div class="col-9">
                    @foreach($permissions as $permission)
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" name="permission[]" id="checkDefault{{ $permission->id }}" value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkDefault{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    @endforeach
                    <br>
                </div>
            </div>
        @endforeach
    
        <button type="submit" class="btn gradient-btn-save me-2">Save Changes</button>
    </form>
    


          </div>
        </div>

        </div>
      </div>
    
    
@endsection

@section('script')
  <script src="{{ URL::asset('build/js/app.js') }}"></script>

  <script type="text/javascript">
        
    $('#checkDefaultmain').click(function(){
      if ($(this).is(':checked')) {
        $('input[ type= checkbox]').prop('checked',true);
      }else{
         $('input[ type= checkbox]').prop('checked',false);
      }
    });
</script>  
@endsection
