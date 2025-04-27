@extends('admin.layouts.master')
@section('title') Add Roles @endsection
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

                <form id="myForm" method="POST" action="{{ route('store.roles') }}" class="forms-sample">
                    @csrf
    
    
                    <div class="form-group mb-3">
     <label for="exampleInputEmail1" class="form-label">Roles Name   </label>
         <input type="text" name="name" class="form-control" >
    
                    </div>
    
    
    
         <button type="submit" class="btn gradient-btn-save me-2" title="Save"><i class="{{$buttonIcons['save']}}"></i> </button>
    
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
