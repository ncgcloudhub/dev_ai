@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Job Application @endslot
@slot('title')Manage @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-1">Manage Applications</h5>
            </div>
            
            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl.</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Applied Date</th>
                            <th scope="col">User ID</th>
                            <th scope="col">Resume (CV)</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobApplications as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('user.details',$item->user_id) }}" class="fw-medium link-primary">{{$item->full_name}}</a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                   
                                    <div class="flex-grow-1">{{$item->email}}</div>
                                </div>
                            </td>
                            <td>  {{ \Carbon\Carbon::parse($item->created_at)->format('F j, Y, g:i a') }}</td>

                            <td>{{$item->user->id}}</td>
                            <td><a href="{{ route('download.cv', ['id' => $item->id]) }}" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-file-download-fill"></i></a>
                            </td>
                                      
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.datatables')

@endsection
@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script>
$(document).ready(function() {
    if (!$.fn.DataTable.isDataTable('#alternative-pagination')) {
        var table = $('#alternative-pagination').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [10, 25, 50, 75, 100],
            "pageLength": 10,
            "responsive": true,
            "autoWidth": false,
            "columnDefs": [
                { "orderable": false, "targets": [0, 4, 5] },
                { "className": "text-center", "targets": [0, 4, 5] }
            ]
        });
    }    
});

</script>
@endsection