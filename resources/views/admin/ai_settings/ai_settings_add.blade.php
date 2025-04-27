@extends('admin.layouts.master')
@section('title') Open AI Settings Add @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
@slot('title') Open AI Settings  @endslot
@endcomponent

<div class="row">

    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Manage Open AI Models</h5>
            </div>
            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl.</th>
                            <th scope="col">Model Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($models as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{$item->openaimodel}}</td>

                            <td>
                                <div class="hstack gap-3 flex-wrap"> 
                                    <a href="{{ route('ai.settings.edit', $item->id) }}" title="Edit" class="gradient-btn-edit fs-15"><i class="{{$buttonIcons['edit']}}"></i></a> 
                                    <a href="{{ route('ai.settings.delete', $item->id) }}" onclick="return confirm('Are you sure you want to delete this Model')" class="gradient-btn-delete fs-15" title="Delete"><i class="{{$buttonIcons['delete']}}"></i></a>
                                </div>
                            </td>
                          
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


<div class="col-xxl-6">
    <form method="POST" action="{{ route('ai.settings.store') }}" class="row g-3" enctype="multipart/form-data">
        @csrf
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Add Open AI Model</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                
                    <div class="col-md-12">
                        <label for="openaimodel" class="form-label">Open AI Model</label>
                        <input type="text" name="openaimodel" class="form-control" id="openaimodel" placeholder="Enter Model Name" required>
                    </div>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="text-end">
            <button type="submit" class="btn gradient-btn-save" title="Save"><i class="{{$buttonIcons['save']}}"></i>
        </div>
    </div>
</form>
</div>
</div>
@include('admin.layouts.datatables')

@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
