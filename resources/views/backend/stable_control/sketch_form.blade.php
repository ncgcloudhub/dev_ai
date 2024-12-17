<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SD Control Sketch</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form id="control-sketch-form" action="{{ route('stable.control.sketch') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="image">Upload Sketch:</label>
        <input type="file" name="image" id="image" required>
    
        <label for="prompt">Prompt:</label>
        <input type="text" name="prompt" id="prompt" placeholder="e.g., a medieval castle on a hill" required>
    
        <label for="control_strength">Control Strength (0.0 - 1.0):</label>
        <input type="number" name="control_strength" id="control_strength" step="0.1" min="0" max="1" required>
    
        <label for="output_format">Output Format:</label>
        <select name="output_format" id="output_format">
            <option value="webp">WEBP</option>
            <option value="png">PNG</option>
            <option value="jpg">JPG</option>
        </select>

        <label for="control_type">Control Type:</label>
        <select name="control_type" id="control_type" required>
            <option value="sketch">Sketch</option>
            <option value="structure">Structure</option>
            <option value="style">Style</option>
        </select>
    
        <button type="submit">Submit</button>
    </form>
    
    <div id="result-container" style="display: none;">
        <img id="result-image" src="" alt="Generated Image" />
    </div>
    <div id="loading-spinner" style="display: none;">Loading...</div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#control-sketch-form').on('submit', function (e) {
                e.preventDefault();
    
                const formData = new FormData(this);
                $('#loading-spinner').show();
    
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (blob) {
                        const imageUrl = URL.createObjectURL(blob);
                        $('#result-container').show();
                        $('#result-image').attr('src', imageUrl);
                        $('#loading-spinner').hide();
                    },
                    error: function (xhr) {
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
