@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') User @endslot
@slot('title')Package @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="alternative-pagination" class="table responsive table-striped table-hover table-bordered text-center align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Module</th>
                            <th>Request</th>
                            <th>User Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sl = 1; // Initialize the variable outside the loop
                        @endphp
                        @foreach($feedbacks  as $item)
                            <tr>
                                <td>{{ $sl++ }}</td>
                               
                                <td>{{ $item->module }}</td>
                             
                                <td>{{ $item->text }}</td>
                              
                                <td>{{ $item->user->name }}</td>
                                <td id="status-{{ $item->id }}">{{ $item->status }}</td>
                                <td>
                                    <button class="change-status-btn" data-id="{{ $item->id }}" data-status="{{ $item->status }}">Change Status</button>
                                </td>
                            </tr>
                
                          
                        @endforeach
                    </tbody>
                </table>
                
                
            </div>
           
        </div>
    </div>
</div>

@endsection
@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

<!-- Include jQuery from CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.change-status-btn').on('click', function() {
            var id = $(this).data('id');
            var currentStatus = $(this).data('status');
            
            // Example new status, this could be dynamic or based on some logic
            var newStatus = currentStatus === 'Pending' ? 'Completed' : 'Pending';
            
            $.ajax({
                url: '{{ route('update.feedback-request-status') }}', // Your route to handle status update
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    status: newStatus
                },
                success: function(response) {
                    // Update the status in the table
                    $('#status-' + id).text(newStatus);
                    // Optionally update the button data-status attribute
                    $('[data-id="' + id + '"]').data('status', newStatus);
                },
                error: function(xhr) {
                    alert('An error occurred while updating the status.');
                }
            });
        });
    });
</script>



@endsection
