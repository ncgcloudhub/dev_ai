<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upscale Image</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form id="upscaleForm" enctype="multipart/form-data">
        @csrf
        <label for="image">Select Image:</label>
        <input type="file" name="image" id="image" required>
        
        <label for="prompt">Prompt (Optional):</label>
        <input type="text" name="prompt" id="prompt">
        
        <label for="output_format">Output Format:</label>
        <select name="output_format" id="output_format">
            <option value="webp">WEBP</option>
            <option value="jpeg">JPEG</option>
            <option value="png">PNG</option>
        </select>
        <select name="upscale_type" required>
            <option value="conservative">Conservative</option>
            <option value="fast">Fast</option>
        </select>
        
        <button type="submit">Upscale Image</button>
    </form>

    <div id="result">
        <h3>Upscaled Image:</h3>
        <img id="upscaledImage" src="" alt="Upscaled Image" style="display: none; max-width: 100%;">
    </div>

    <script>
        $(document).ready(function() {
            $('#upscaleForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                let formData = new FormData(this);

                // Send AJAX request to the upscale endpoint
                $.ajax({
                    url: '/upscale', // Make sure this matches your route
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#upscaledImage').hide(); // Hide the image initially
                        alert('Upscaling image, please wait...');
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Display the upscaled image
                            $('#upscaledImage').attr('src', response.upscaled_image_url).show();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Upscale failed. Please try again.');
                        console.error(xhr.responseJSON);
                    }
                });
            });
        });
    </script>
</body>
</html>
