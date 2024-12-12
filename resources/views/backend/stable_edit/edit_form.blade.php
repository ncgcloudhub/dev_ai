<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Background</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Your form -->
    <form id="edit-background-form" action="{{ route('edit.background') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="subject_image">Upload Image:</label>
        <input type="file" name="subject_image" id="subject_image" >
       
        <label for="bg_reference">Background Reference:</label>
        <input type="file" name="bg_reference" id="bg_reference" >
        
        <label for="background_prompt">Background Prompt:</label>
        <input type="text" name="background_prompt" id="background_prompt" >
        
        <label for="output_format">Output Format:</label>
        <select name="output_format" id="output_format">
            <option value="webp">WEBP</option>
            <option value="png">PNG</option>
            <option value="jpg">JPG</option>
        </select>
    
        <button type="submit">Submit</button>
    </form>
    
    <div id="result-container" style="display: none;">
        <img id="result-image" src="" alt="Generated Image" />
    </div>
    <div id="loading-spinner">Loading...</div>
    

    <!-- Your script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Handle form submission for editing background
            $('#edit-background-form').on('submit', function (e) {
                e.preventDefault();
    
                // Prepare form data (including the image file)
                const formData = new FormData(this);
    
                // Show loading indicator (optional)
                $('#loading-spinner').show();
    
                // Send AJAX request to the server
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                 
                            // If the request is still in progress, start polling
                            pollGenerationStatus(response.generation_id);
                     
                    },
                    error: function (xhr) {
                        // Handle error in image processing
                        alert('An error occurred while processing the image.');
                        console.error(xhr.responseText);
                        $('#loading-spinner').hide();
                    }
                });
            });
    
            // Function to poll the status of the image generation
            function pollGenerationStatus(generationId) {
                const interval = setInterval(() => {
                    $.ajax({
                        url: '/check-generation-status',  // Your API route to check status
                        type: 'POST',
                        data: {
                            generation_id: generationId,
                            _token: $('input[name="_token"]').val(),  // CSRF token for security
                        },
                        success: function (response) {
                            if (response.status === 'in-progress') {
                    // Generation is still in progress, log or handle as needed
                    console.log('Generation is in progress...');
                } else if (response.status === 'success') {
                    // Generation is complete, display the result
                    $('#result-container').show();
                    $('#result-image').attr('src', response.image_url);
                    $('#loading-spinner').hide(); // Hide loading spinner
                    clearInterval(interval); // Stop polling
                } else {
                    // Handle unexpected statuses or errors from the response
                    console.error('Unexpected response status:', response);
                    $('#loading-spinner').hide();
                    clearInterval(interval); // Stop polling
                }
                          
                        },
                        error: function (xhr) {
                            // Handle error during polling
                            clearInterval(interval);
                            alert('An error occurred while checking generation status.');
                            console.error(xhr.responseText);
                        }
                    });
                }, 10000);  // Poll every 10 seconds
            }
        });
    </script>
    
</body>
</html>
