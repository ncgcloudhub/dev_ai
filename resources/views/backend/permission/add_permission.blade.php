@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Role & Permission @endslot
@slot('title') Add Permission @endslot
@endcomponent

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

          <!-- middle wrapper start -->
          <div class="col-md-8 col-xl-8 middle-wrapper">
            <div class="row">
             <div class="card">
              <div class="card-body">

	 <form id="myForm" method="POST" action="{{ route('store.permission') }}" class="forms-sample">
				@csrf


				<div class="form-group mb-3">
                    <label for="exampleInputEmail1" class="form-label">Permission Name   </label>
                        <input type="text" name="name" class="form-control" >
				</div>

                <div class="form-group mb-3">
                    <label for="exampleInputEmail1" class="form-label">Group Name   </label>
                    <select name="group_name" class="form-select" id="exampleFormControlSelect1">
                        <option selected="" disabled="">Select Group</option>
                        <option value="template">Template</option>
                        <option value="custom_template">Custom Template</option> 
                        <option value="prompt_library">Prompt Library</option> 
                        <option value="chat">Chat</option> 
                        <option value="image">Image</option> 
                        <option value="settings">Settings</option> 
                        <option value="user">User</option> 
                        <option value="pricing">Pricing</option> 
                        <option value="newsletter">Newsletter</option> 
                        <option value="refferal">Refferal</option> 
                        <option value="page">Page</option> 
                        <option value="job">Job</option> 
                        <option value="role">Role & Permission </option>  
                    </select>

                </div> 


	 <button type="submit" class="btn btn-primary me-2">Save Changes </button>

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

<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                amenitis_name: {
                    required : true,
                },
                
            },
            messages :{
                amenitis_name: {
                    required : 'Please Enter Amenities Name',
                }, 
                 
            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
    
</script>


@endsection
