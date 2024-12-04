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
@slot('li_1') <a href="{{route('newsletter.manage')}}">Newsletter</a> @endslot
@slot('title')Manage @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Manage Newsletter</h5>
            </div>
            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                          
                            <th>Registered</th>
                            <th>IP Address</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedNewsletter as $email => $group)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $email }} <span class="badge bg-info">{{ $group['count'] }}</span></td>
                            
                            <td>
                                @if ($group['isRegistered'])
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-danger">No</span>
                                @endif
                            </td>
                            <td>
                                <!-- Display all IPs associated with this email -->
                                @foreach ($group['data'] as $item)
                                    <span>{{ $item->ipaddress }}</span><br>
                                @endforeach
                            </td>
                            <td>
                                <!-- Display all created_at dates associated with this email -->
                                @foreach ($group['data'] as $item)
                                    <span>{{ \Carbon\Carbon::parse($item->created_at)->format('F j, Y, g:i a') }}</span><br>
                                @endforeach
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

<script>
$(document).ready(function () {
    // Check if DataTable is already initialized
    if ($.fn.DataTable.isDataTable('#alternative-pagination')) {
        $('#alternative-pagination').DataTable().destroy(); // Destroy the existing instance
    }

    // Initialize the DataTable
    $('#alternative-pagination').DataTable({
        responsive: true,
        pageLength: 10, // Default number of rows to display
        lengthMenu: [10, 20, 50, 100], // Options for rows per page
        dom: '<"row"<"col-md-6"l><"col-md-6"Bf>>' + // Length menu, buttons, and search box in the header
             '<"row"<"col-md-12"tr>>' + // Table
             '<"row"<"col-md-6"i><"col-md-6"p>>', // Info and pagination in the footer
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Newsletter Data',
                text: 'Export to Excel',
                className: 'btn btn-success'
            },
        ]
    });
});

</script>

@endsection