<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Search and Replace</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Your form -->
    <form id="search-and-replace-form" action="{{ route('stable.search.replace') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="subject_image">Upload Image:</label>
        <input type="file" name="subject_image" id="subject_image" required>
    
        <label for="prompt">Prompt: (What you want to replace it with)</label>
        <input type="text" name="prompt" id="prompt" required placeholder="golden retriever in a field">
    
        <label for="search_prompt">Search Prompt: (short description of what to inpaint in the image)</label>
        <input type="text" name="search_prompt" id="search_prompt" required placeholder="dog">
    
        <label for="output_format">Output Format:</label>
        <select name="output_format" id="output_format" required>
            <option value="webp">WEBP</option>
            <option value="png">PNG</option>
            <option value="jpg">JPG</option>
        </select>
    
        <button type="submit">Submit</button>
    </form>
    
    <div id="result-container" style="display: none;">
        <img id="result-image" src="" alt="Generated Image" />
    </div>
    <div id="loading-spinner" style="display: none;">Loading...</div>
    
    

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
                        $('#loading-spinner').hide(); // Hide the spinner

                        // Create a blob URL for the binary image data
                        const imageUrl = URL.createObjectURL(blob);

                        // Display the image in the <img> tag
                        $('#result-container').show();
                        $('#result-image').attr('src', imageUrl);
                    },
                    error: function (xhr) {
                        alert('An error occurred while processing the image.');
                        console.error(xhr.responseText);
                        $('#loading-spinner').hide(); // Hide the loading spinner
                    }
                });
            });
    
        });
    </script>
    
</body>
</html>
