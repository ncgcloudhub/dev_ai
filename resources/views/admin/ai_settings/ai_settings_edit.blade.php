@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1')  Dashboard @endslot
@slot('title') Open AI Edit @endslot
@endcomponent

<a href="{{route('ai.settings.add')}}" class="btn gradient-btn-add waves-effect waves-light mb-3" data-text="Add Menu" title="Add AI Model"><span><i class="{{$buttonIcons['add']}}"></i></span></a>

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
                                    <button class="btn btn-sm {{ $item->status ? 'gradient-btn-save' : 'gradient-btn-delete' }} toggle-status" 
                                        data-id="{{ $item->id }}">
                                    {{ $item->status ? 'Active' : 'Inactive' }}
                                    </button>
                                    <a href="{{ route('ai.settings.edit', $item->id) }}" class="gradient-btn-edit fs-15" title="Edit"><i class="{{$buttonIcons['edit']}}"></i></a> 
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
    <form method="POST" action="{{ route('ai.settings.update') }}" class="row g-3" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$model->id}}">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Add Menu</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                
                    <div class="col-md-12">
                        <label for="openaimodel" class="form-label">Open AI Model</label>
                        <input type="text" name="openaimodel" value="{{$model->openaimodel}}" class="form-control" id="openaimodel" placeholder="Enter Item Name">
                    </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="text-end">
            <button type="submit" class="btn gradient-btn-edit" title="Update"><i class="{{$buttonIcons['save']}}"></i>
        </div>
    </div>
</form>
</div>
</div>

@include('admin.layouts.datatables')

@endsection

@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script>
    $(document).ready(function () {
        $('.toggle-status').on('click', function () {
            var button = $(this);
            var id = button.data('id');
    
            $.ajax({
                url: "{{ route('ai.settings.toggle-status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function (response) {
                    if (response.success) {
                        if (response.status) {
                            button.removeClass('btn-danger').addClass('btn-success').text('Active');
                        } else {
                            button.removeClass('btn-success').addClass('btn-danger').text('Inactive');
                        }
                    }
                }
            });
        });
    });
    </script>
    
@endsection