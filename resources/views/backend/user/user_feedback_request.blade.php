@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
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
@include('admin.layouts.datatables')

@endsection
@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

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
