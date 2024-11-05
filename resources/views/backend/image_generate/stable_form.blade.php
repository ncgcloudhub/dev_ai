<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Image</title>
</head>
<body>
    <h1>Generate Image</h1>
    <form id="imageForm" action="{{ route('stable.image') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="prompt">Prompt:</label>
        <input type="text" name="prompt" id="prompt" required>
    
        <label for="width">Width (optional):</label>
        <input type="number" name="width" id="width" placeholder="512">
    
        <label for="height">Height (optional):</label>
        <input type="number" name="height" id="height" placeholder="512">
    
        <label for="steps">Steps (optional):</label>
        <input type="number" name="steps" id="steps" placeholder="50">
    
        <button type="submit">Generate Image</button>
    </form>

    <div id="responseMessage"></div>
    <div id="imageContainer"></div> <!-- Container to display the image -->
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#imageForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        
        $.ajax({
            url: $(this).attr('action'), // Use the form's action URL
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            success: function(response) {
                $('#responseMessage').html('<p>Image generated successfully!</p>');

                // Display the image based on image_url or image_base64
                if (response.image_url) {
                    $('#imageContainer').html('<img src="' + response.image_url + '" alt="Generated Image" style="max-width:100%;">');
                } else if (response.image_base64) {
                    $('#imageContainer').html('<img src="data:image/jpeg;base64,' + response.image_base64 + '" alt="Generated Image" style="max-width:100%;">');
                }
            },

            error: function(xhr) {
                // Handle errors
                $('#responseMessage').html('<p>Error generating image. Please try again.</p>');
            }
        });
    });
});
</script>


</html>
