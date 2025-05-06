@extends('admin.layouts.master')
@section('title') Manage Jokes @endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="#">Jokes/Facts</a> @endslot
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
                                <th>Type</th>
                                <th>Category</th>
                                <th>Joke</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jokes as $joke)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $joke->type }}</td>
                            <td> <span class="badge gradient-btn-tour">{{ $joke->category }}</span></td>
                            <td>{{ $joke->content }}</td>
                            <td>
                                <div class="form-check form-switch form-switch-md" dir="ltr">
                                        <a href="javascript:void(0);" 
                                            class="btn gradient-btn-edit d-inline-block" 
                                            data-id="{{ $joke->id }}" 
                                            data-category="{{ $joke->category }}" 
                                            data-content="{{ $joke->content }}" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="Edit" 
                                            onclick="openEditModal(this)">
                                            <i class="{{ $buttonIcons['edit'] }}"></i>
                                        </a>
                                 
                                        <a href="{{route('jokes.delete',$joke->id)}}" onclick="return confirm('Are you sure you want to delete this Joke?')" class="btn gradient-btn-delete mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                            <i class="{{ $buttonIcons['delete'] }}"></i>
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
                    <h4 class="card-title mb-0 flex-grow-1">Add Joke/Facts</h4>
                </div><!-- end card header -->
    
                <div class="card-body">
                    <div class="live-preview">
                        <!-- Category select dropdown -->
                        <div class="row">
                            <!-- Category select dropdown -->
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option selected value="Jokes">Jokes</option>
                                    <option value="Facts">Facts</option>
                                  
                                    <!-- Add other categories here as needed -->
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option selected value="general">General</option>
                                    <option value="programming">Programming</option>
                                    <option value="funny">Funny</option>
                                    <option value="tech">Tech</option>
                                    <option value="image">Image</option>
                                    <option value="education">Education</option>
                                    <!-- Add other categories here as needed -->
                                </select>
                            </div>
    
                            <!-- Points select dropdown -->
                            <div class="col-md-4 mb-3">
                                <label for="points" class="form-label">Number of Jokes/Facts</label>
                                <select class="form-control" id="points" name="points" required>
                                    <option selected value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="5">5</option>
                                    <option value="7">7</option>
                                    <option value="10">10</option>
                                    <!-- Add other point values here as needed -->
                                </select>
                            </div>
                        </div>
                        
                        <!-- Joke content input -->
                        <div class="mb-3">
                            <label for="content" class="form-label">Details you want in your content (Not mandatory)</label>
                            <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                        </div>
                    </div>
                </div><!-- end card body -->
                
                <div class="card-footer">
                    <button type="button" class="btn gradient-btn-generate" id="aiGenerateBtn" title="Generate Jokes Using AI"><i class="{{ $buttonIcons['generate'] }}"></i></button>
                    <button type="submit" class="btn gradient-btn-save" title="Save"><i class="{{ $buttonIcons['save'] }}"></i></button>
                </div><!-- end card footer -->
            </div><!-- end card -->
        </form>

         {{-- List Of Jokes (After Output) --}}
         <form id="jokeForm" style="display: none;">
        @csrf
        <input type="hidden" id="joke_content_input" value="">

        <div class="form-group">
            <label for="joke_points">Select Joke Points:</label>
            <div id="jokePointsContainer"></div>
        </div>
        <button type="submit" class="btn gradient-btn-save">Save</button>
    </form>
    </div>
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

@include('admin.layouts.datatables')

@endsection

@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

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
                type: $('#type').val(),
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

$(document).ready(function() {
    $('#aiGenerateBtn').click(function() {
        // Show the magic ball
        showMagicBall('facts', 'general');

        var type = $('#type').val();
        var category = $('#category').val();
        var points = $('#points').val();
        var content = $('#content').val(); // Get the content from the textarea

        // Check if both category and points are selected
        if (!category || !points) {
            alert('Please select both category and points.');
            return;
        }

        // Send data via AJAX, including the content
        $.ajax({
            url: '{{ route("jokes.ai.generate") }}', // Route for AI generation
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                type: type,
                category: category,
                points: points,
                content: content  // Add the content to the AJAX data
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

    var selectedCategory = $('#category').val();
    var selectedtype = $('#type').val();

        // Send selected points via AJAX to store in the database
        $.ajax({
            url: '{{ route("jokes.store") }}', // Route to store the joke
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                type: selectedtype,
                category: selectedCategory,
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