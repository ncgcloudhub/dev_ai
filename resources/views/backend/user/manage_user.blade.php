@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') User @endslot
@slot('title')Manage @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Manage User</h5>
                <a href="{{route('user.export')}}">Download</a>
            </div>
            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl.</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">IP Address</th>
                            <th scope="col">Email Verified</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('user.details',$item->id) }}" class="fw-medium link-primary">{{$item->name}}</a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2">
                                        <img src="{{ $item->photo ? asset('backend/uploads/user/' . $item->photo) : asset('build/images/users/avatar-1.jpg') }}" alt="" class="avatar-xs rounded-circle" />
                                    </div>
                                    <div class="flex-grow-1">{{$item->email}}</div>
                                </div>
                            </td>

                            <td>{{$item->ip_address}}</td>
                                        <td>
                                            @if ($item->email_verified_at)
                                            {{ \Carbon\Carbon::parse($item->email_verified_at)->format('F j, Y, g:i a') }}
    
                                            @else
                                                --
                                            @endif
                                            
                                            <td>
                          
                            <td>{{ $item->status }}</td>

                            @if ($item->status == 'active')
                                <td>
                                    <div class="form-check form-switch form-switch-md" dir="ltr">
                                        <input type="checkbox" class="form-check-input active_button" id="customSwitchsizemd" data-user-id="{{ $item->id }}" checked>
                                        <label class="form-check-label" for="customSwitchsizemd"></label>
                                        
                                        <!--Change Password-->
                                        <a href="{{ route('admin.users.changePassword.view', ['user' => $item->id]) }}" class="btn btn-primary btn-sm waves-effect waves-light d-inline-block"><i class="ri-lock-2-fill"></i></a>

                                        {{-- Delete User --}}
                                        <form id="deleteForm" action="{{ route('admin.users.delete', ['user' => $item->id]) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light" onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="ri-delete-bin-7-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div class="form-check form-switch form-switch-md" dir="ltr">
                                        <input type="checkbox" class="form-check-input active_button" id="customSwitchsizemd" data-user-id="{{ $item->id }}">
                                        <label class="form-check-label" for="customSwitchsizemd"></label>

                                        {{-- Delete User --}}
                                        <form id="deleteForm" action="{{ route('admin.users.delete', ['user' => $item->id]) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light" onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="ri-delete-bin-3-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
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

<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>
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


@endsection
