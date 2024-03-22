@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Dalle @endslot
@slot('title')Manage @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Admin Manage Dalle</h5>
            </div>
            <div class="card-body">
                <table id="alternative-pagination" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SR No.</th>
                            <th>Image</th>
                            <th>Prompt</th>
                            <th>User ID/Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($images as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center fw-medium">
                                    <img src="{{ asset($item->image) }}" alt="" class="avatar-xxs me-2">
                                    {{-- <a href="javascript:void(0);" class="currency_name">Bitcoin (BTC)</a> --}}
                                </div>
                            </td>
                            <td>{{ $item->prompt }}</td>
                            <td>{{ $item->user->id }}/{{ $item->user->name }}</td>
                            <td>{{ $item->status }}</td>

                            @if ($item->status == 'active')
                            <td>
                                <button class="btn btn-sm btn-soft-success active_button" data-image-id="{{ $item->id }}">Activate</button>
                            </td>
                            @else
                            <td>
                                <button class="btn btn-sm btn-soft-info active_button" data-image-id="{{ $item->id }}">Inactivate</button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>

<!-- Include jQuery from CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('.active_button').click(function() {

            var imageId = $(this).data('image-id');

            console.log('Hello:' + imageId);
            // Send AJAX request to update the image status
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'POST',
                url: '/update/image/status',
                data: {
                    image_id: imageId
                },
                headers: {
                 'X-CSRF-TOKEN': csrfToken
                 },
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    // Optionally, update the UI to reflect the new status
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                    console.log('inisde Error');
                }
            });
        });
    });
</script>



@endsection
