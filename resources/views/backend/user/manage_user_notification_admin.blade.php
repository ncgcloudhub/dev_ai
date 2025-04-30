@extends('admin.layouts.master')
@section('title') Manage Users Notification @endsection
@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') User @endslot
@slot('title')Manage Notification @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
          
            <div class="card-body">
                <form id="bulkActionForm" action="" method="POST">
                    @csrf
                    <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Email</th>
                                <th scope="col">Notification Message</th>
                                <th scope="col">Time</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notifications as $notification)
                            <tr>
                                <td>{{ $notification->notifiable_id }}</td>
                                <td>{{ optional($notification->notifiable)->email }}</td>
                                <td>{{ $notification->data['message'] ?? 'No message' }}</td>
                                <td>{{ $notification->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
            
                    <!-- Bulk Action Buttons -->
                    <div class="bulk-actions mt-3">

                      
                    </div>
                </form>
            </div>
            @include('admin.layouts.datatables')

        </div>
    </div>
</div>

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
