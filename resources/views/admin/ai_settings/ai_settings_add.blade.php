@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Settings @endslot
@slot('title') Open AI Model  @endslot
@endcomponent


<div class="col-xxl-6">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Gutters</h4>
           
        </div><!-- end card header -->

        <div class="card-body">
          
            <div class="live-preview">
                <form method="POST" action="{{route('ai.settings.store')}}" class="row g-3">
                    @csrf
                   
                    <div class="col-md-12">
                        <label for="inputEmail4" class="form-label">Open AI Model</label>
                        <select class="form-select" name="openaimodel" id="openaimodel" aria-label="Floating label select example">
                            <option disabled selected="">Enter Open AI Model</option>
                            <option value="gpt-3.5-turbo-instruct">gpt-3.5-turbo-instruct</option>
                            <option value="gpt-3.5-turbo-0125">gpt-3.5-turbo-0125</option>
                            <option value="gpt-4-turbo">gpt-4-turbo</option>
                            <option value="gpt-4o">gpt-4o</option>
                            <option value="gpt-4">gpt-4</option>
                           
                            
                          </select>
                     
                    </div>
                    
                  
                    <div class="col-12">
                        <div class="text-end">
                            <input type="submit" class="btn btn-rounded gradient-btn-save mb-5" value="Save Settings">
                        </div>
                    </div>
                </form>
            </div>
            <div class="d-none code-view">

            </div>
        </div>
    </div>
</div> <!-- end col -->



@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
