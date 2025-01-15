@extends('admin.layouts.master')
@section('title') Manage Jokes @endsection
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


    <div class="col-xxl-6">
        <div class="card">
    
                <div class="card-body">
                    <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Category</th>
                                <th>Joke</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jokes as $joke)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td> <span class="badge bg-info">{{ $joke->category }}</span></td>
                            <td>{{ $joke->content }}</td>
                            <td>
                                <div class="form-check form-switch form-switch-md" dir="ltr">
                                        <a href="javascript:void(0);" 
                                            class="text-primary d-inline-block edit-item-btn" 
                                            data-id="{{ $joke->id }}" 
                                            data-category="{{ $joke->category }}" 
                                            data-content="{{ $joke->content }}" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="Edit" 
                                            onclick="openEditModal(this)">
                                            <i class="ri-pencil-fill fs-16"></i>
                                        </a>
                                 
                                        <a href="{{route('jokes.delete',$joke->id)}}" onclick="return confirm('Are you sure you want to delete this Joke?')" class="text-danger mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                            <i class="ri-delete-bin-5-fill fs-16"></i> 
                                        </a>
                                </div>
                            </td>    
                        </tr>
                    @endforeach
                        </tbody>
                    </table>
                </div>
    
    
        </div>
    </div>
    
    <div class="col-xxl-6">
        <form method="POST" action="{{ route('jokes.store') }}" class="row g-3">
            @csrf
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Add Joke</h4>
                </div><!-- end card header -->
    
                <div class="card-body">
                    <div class="live-preview">
                        <!-- Category select dropdown -->
                        <div class="row">
                            <!-- Category select dropdown -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="general">General</option>
                                    <option value="programming">Programming</option>
                                    <option value="funny">Funny</option>
                                    <option value="tech">Tech</option>
                                    <!-- Add other categories here as needed -->
                                </select>
                            </div>
    
                            <!-- Points select dropdown -->
                            <div class="col-md-6 mb-3">
                                <label for="points" class="form-label">Points</label>
                                <select class="form-control" id="points" name="points" required>
                                    <option value="">Select Points</option>
                                    <option value="1">1 Point</option>
                                    <option value="2">2 Points</option>
                                    <option value="3">3 Points</option>
                                    <option value="5">5 Points</option>
                                    <!-- Add other point values here as needed -->
                                </select>
                            </div>
                        </div>
                        
                        <!-- Joke content input -->
                        <div class="mb-3">
                            <label for="content" class="form-label">Joke Content</label>
                            <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                        </div>
                    </div>
                </div><!-- end card body -->
                
                <div class="card-footer">
                    <button type="button" class="btn gradient-btn-5" id="aiGenerateBtn">AI Generate</button>
                    <button type="submit" class="btn gradient-btn-save">Save Joke</button>
                </div><!-- end card footer -->
            </div><!-- end card -->
        </form>
    </div>

    {{-- List Of Jokes (After Output) --}}
    <form id="jokeForm" style="display: none;">
        @csrf
        <input type="hidden" id="joke_content_input" value="">

        <div class="form-group">
            <label for="joke_points">Select Joke Points:</label>
            <div id="jokePointsContainer"></div>
        </div>
        <button type="submit" class="btn gradient-btn-save">Save Joke</button>
    </form>

    </div>

<!-- Edit Joke Modal -->
<div id="editJokeModal" class="modal fade" tabindex="-1" aria-labelledby="editJokeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJokeModalLabel">Edit Joke</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editJokeForm">
                    @csrf
                    <input type="hidden" id="editJokeId" name="id">
                    <div class="mb-3">
                        
                    </div>
                    <div class="mb-3">
                        <label for="editContent" class="form-label">Joke Content</label>
                        <textarea class="form-control" id="editContent" name="content" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn gradient-btn-save">Update Joke</button>
                    </div>
                </form>
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


{{--JOKES Store Script --}}

<script>
    $(document).ready(function() {
        // Handle form submission
        $('#jokesForm').submit(function(event) {
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
                    toastr.success('Joke added successfully!');
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

<script>
    function openEditModal(button) {
    const jokeId = $(button).data('id');
    const category = $(button).data('category');
    const content = $(button).data('content');

    $('#editJokeId').val(jokeId);
    $('#editCategory').val(category);
    $('#editContent').val(content);

    $('#editJokeModal').modal('show');
}

</script>

<script>
    $(document).ready(function() {
    $('#editJokeForm').submit(function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = {
            _token: $('input[name="_token"]').val(),
            id: $('#editJokeId').val(),
            category: $('#editCategory').val(),
            content: $('#editContent').val(),
        };

        $.ajax({
            type: 'POST',
            url: "{{ route('jokes.update') }}", // Update this route to match your controller's update method
            data: formData,
            success: function(response) {
                toastr.success('Joke updated successfully!');
                $('#editJokeModal').modal('hide'); // Close the modal
                location.reload(); // Reload the page or dynamically update the joke list
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + xhr.responseJSON.message);
            }
        });
    });
});

// Jokes
$(document).ready(function() {
    $('#aiGenerateBtn').click(function() {
         // Show the magic ball
         showMagicBall('image');
 
        var category = $('#category').val();
        var points = $('#points').val();

        // Check if both category and points are selected
        if (!category || !points) {
            alert('Please select both category and points.');
            return;
        }

        // Send data via AJAX
        $.ajax({
            url: '{{ route("jokes.ai.generate") }}', // Route for AI generation
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                category: category,
                points: points
            },
            success: function(response) {
                // Hide the magic ball after content loads
                hideMagicBall();
                var pointsContainer = $('#jokePointsContainer');
                    pointsContainer.empty();  // Clear previous checkboxes

                    // Loop through the points and create checkboxes
                    response.points
                        .filter(point => point.trim() !== '') // Exclude empty lines
                        .forEach(function(point, index) {
                            var checkbox = `
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="${point}" id="point${index}" name="points[]">
                                    <label class="form-check-label" for="point${index}">
                                        ${point}
                                    </label>
                                </div>
                            `;
                            pointsContainer.append(checkbox);
                        });

                    // Show the form with checkboxes
                    $('#jokeForm').show();
            },
            error: function(xhr, status, error) {
                hideMagicBall();

                // Handle the error
                alert('There was an error. Please try again.');
            }
        });
    });
});


// Save the Jokes From Selected List

 $('#jokeForm').submit(function(e) {
        e.preventDefault();

        // Collect selected points
        var selectedPoints = [];
        $('input[name="points[]"]:checked').each(function() {
            selectedPoints.push($(this).val());
        });

        console.log('Selected Points:', selectedPoints);

        // Combine selected points into a single string (or use another method if needed)
        // Remove numbers (e.g., "1.", "2.") from each joke
        var sanitizedPoints = selectedPoints.map(point => point.replace(/^\d+\.\s*/, ''));
        var jokeContent = sanitizedPoints.join("\n");


    // Set the hidden input value with the combined joke content
    $('#joke_content_input').val(jokeContent);

        // Send selected points via AJAX to store in the database
        $.ajax({
            url: '{{ route("jokes.store") }}', // Route to store the joke
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                points: selectedPoints,
                joke_content: jokeContent,
            },
            success: function(response) {
                // Handle success (show success message, etc.)
                alert('Joke saved successfully!');
            },
            error: function(xhr, status, error) {
                // Handle error
                alert('There was an error. Please try again.');
            }
        });
    });


</script>

@endsection