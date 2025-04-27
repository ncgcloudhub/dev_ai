@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Email @endslot
@slot('title')Manage @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Emails</th>
                        <th>Subject</th>
                        <th>Body</th>
                        <th>Date Sent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sentEmails as $email)
                        <tr>
                            <td>
                                @foreach (json_decode($email->user_emails) as $userEmail)
                                    <span class="btn text-white badge-gradient-primary mx-1">{{ $userEmail }}</span>
                                @endforeach
                            </td>
                            
                            <td>{{ $email->subject }}</td>
                            <td>  
                                <a href="#" class="email-body-link" data-bs-toggle="modal" data-bs-target="#emailBodyModal" 
                                   data-body="{{ $email->body }}">
                                   {{ Str::limit(html_entity_decode(strip_tags($email->body)), 50) }}
                                </a>
                            </td>
                            
                            <td>{{ $email->created_at->format('M d Y \a\t g A') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="emailBodyModal" tabindex="-1" aria-labelledby="emailBodyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailBodyModalLabel">Email Body</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBodyContent">
                <!-- Body content will be injected here dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.datatables')

@endsection


@section('script')

<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>
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

    // Event delegation for the toggle button
    $(document).on('click', '.active_button', function() {
        var userId = $(this).data('user-id');
        var toggleSwitch = $(this);

        // Send AJAX request to update the image status
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: '/user/update/status',
            data: {
                user_id: userId
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                // Handle success response
                console.log(response);

                // Update the status text in the table cell
                if (toggleSwitch.is(':checked')) {
                    toggleSwitch.closest('td').prev().text('active');
                } else {
                    toggleSwitch.closest('td').prev().text('inactive');
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(error);
                console.log('inside Error');
            }
        });
    });
});

</script>

{{-- For Modal Email Body --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    var emailLinks = document.querySelectorAll('.email-body-link');

    emailLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            // Retrieve the raw HTML content from the data attribute
            var emailBody = this.getAttribute('data-body');

            // Directly set the raw HTML as innerHTML for proper formatting in the modal
            document.getElementById('modalBodyContent').innerHTML = emailBody;
        });
    });
});
</script>

@endsection