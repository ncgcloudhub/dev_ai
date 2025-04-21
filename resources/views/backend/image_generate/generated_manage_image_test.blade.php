@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <h4>DALL·E Generated Images</h4>

    <table id="dalleImageTable" class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>SR No.</th>
                <th>Image</th>
                <th>Prompt</th>
                <th>User ID/Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('styles')
<!-- DataTables Bootstrap 5 CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
@endpush

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>

<script>
$(document).ready(function () {
    console.log('Trying to initialize datatable...');

    $('#dalleImageTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.dalle.image.fetch') }}",
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'sr_no' },
            { data: 'image', orderable: false, searchable: false },
            { data: 'prompt' },
            { data: 'user' },
            { data: 'status' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
