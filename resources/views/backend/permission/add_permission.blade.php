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
                    <label for="exampleInputEmail1" class="form-label">Group Name</label>
                    <select name="group_name" class="form-select" id="exampleFormControlSelect1" required>
                        <option selected="" disabled="">Select Group</option>
                        <option value="Greeting Card">Greeting Card</option>
                        <option value="Calendar">Calendar</option> 
                        <option value="Chattermate">Chattermate</option> 
                        <option value="AI Content Creator">AI Content Creator</option> 
                        <option value="Custom Template">Custom Template</option> 
                        <option value="Prompt Library">Prompt Library</option> 
                        <option value="Clever Experts">Clever Experts</option> 
                        <option value="Clever Image Creator">Clever Image Creator</option> 
                        <option value="Settings">Settings</option> 
                        <option value="Role & Permission">Role & Permission</option> 
                        <option value="Manage User & Admin">Manage User & Admin</option> 
                        <option value="Education">Education</option> 
                        <option value="Manage Newsletter">Manage Newsletter</option>  
                        <option value="Manage Referral">Manage Referral</option>  
                        <option value="Manage Page">Manage Page</option>  
                        <option value="Jobs">Jobs</option>  
                    </select>

                </div> 


	 <button type="submit" class="btn gradient-btn-save me-2">Save Changes </button>

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
