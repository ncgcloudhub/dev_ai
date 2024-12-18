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
@slot('li_1') <a href="#">Jokes</a> @endslot
@slot('title')Manage @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Manage Jokes <button type="button" class="btn gradient-btn-8" data-bs-toggle="modal" data-bs-target="#addJokeModal">Add Joke</button></h5>
                
            </div>
            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Joke</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jokes as $joke)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td> <span class="badge bg-info">{{ $joke->category }}</span></td>
                            <td>{{ $joke->content }}</td>
                            <td>{{ $joke->created_at }}</td>
                            
                            
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Default Modals -->

<div id="addJokeModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Add Joke</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <form id="jokeForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Joke Content</label>
                            <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary ">Save Joke</button>
                    </div>
                </form>
            </div>
           

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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


{{--JOKES Store Script --}}

<script>
    $(document).ready(function() {
        // Handle form submission
        $('#jokeForm').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get form data
            let formData = {
                _token: $('input[name="_token"]').val(),
                category: $('#category').val(),
                content: $('#content').val(),
            };

            // Submit the data via AJAX
            $.ajax({
                type: 'POST',
                url: "{{ route('jokes.store') }}",
                data: formData,
                success: function(response) {
                    alert('Joke added successfully!');
                    $('#jokeForm')[0].reset(); // Reset the form
                    location.reload(); // Reload the page or update the joke list dynamically
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + xhr.responseJSON.message);
                }
            });
        });
    });
</script>


@endsection