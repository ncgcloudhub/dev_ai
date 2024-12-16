<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Remove Background</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Your form -->
    <form id="remove-background-form" action="{{ route('stable.remove.background') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="subject_image">Upload Image:</label>
        <input type="file" name="subject_image" id="subject_image" >
        
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
            $('#remove-background-form').on('submit', function (e) {
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
                        $('#loading-spinner').hide();

                        if (response.success) {
                            // Display the generated image
                            $('#result-image').attr('src', response.image_url);
                            $('#result-container').show();
                     
                    }},
                    error: function (xhr) {
                        // Handle error in image processing
                        alert('An error occurred while processing the image.');
                        console.error(xhr.responseText);
                        $('#loading-spinner').hide();
                    }
                });
            });
    
        });
    </script>
    
</body>
</html>
