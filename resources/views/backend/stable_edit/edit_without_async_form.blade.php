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
    <h3>Search & Recolor</h3>
    <form id="edit-background-form" action="{{ route('without.async.edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="subject_image">Upload Image:</label>
        <input type="file" name="subject_image" id="subject_image" >
        
        <label for="prompt">Prompt:</label>
        <input type="text" name="prompt" id="prompt" >
       
        <label for="select_prompt">Select Prompt:</label>
        <input type="text" name="select_prompt" id="select_prompt" >
        
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
                        // Check if the response contains image data
                        if (response.status === 'success' && response.image_data) {
                            // Convert the binary data to a Base64 string
                            var base64Image = "data:image/webp;base64," + response.image_data;

                            // Set the image source to the Base64 string
                            $("#result-image").attr("src", base64Image);

                            // Show the image and hide the loading spinner
                            $("#result-container").show();
                            $("#loading-spinner").hide();
                        } else {
                            // Handle any error in response
                            alert("Error generating the image.");
                        }
                    },
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
