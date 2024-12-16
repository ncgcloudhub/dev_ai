<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erase Background</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <form id="erase-background-form" action="{{ route('stable.edit.erase') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" required>
       
        <label for="mask">Upload Mask:</label>
        <input type="file" name="mask" id="mask" required>
    
        <label for="output_format">Output Format:</label>
        <select name="output_format" id="output_format">
            <option value="webp">WEBP</option>
            <option value="png">PNG</option>
            <option value="jpg">JPG</option>
        </select>

      
        <button type="submit">Submit</button>
    </form>
    
    <div id="result-container">
        <img id="result-image" src="" alt="Generated Image">
    </div>
    <div id="loading-spinner">Loading...</div>

    <script>
        $(document).ready(function () {
            $('#erase-background-form').on('submit', function (e) {
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
                        responseType: 'blob' // Expect a binary response
                    },
                    success: function (blob) {
                        $('#loading-spinner').hide();

                        // Create a blob URL for the binary image data
                        const imageUrl = URL.createObjectURL(blob);

                        // Display the image in the <img> tag
                        $('#result-container').show();
                        $('#result-image').attr('src', imageUrl);
                    },
                    error: function (xhr) {
                        $('#loading-spinner').hide();
                        alert('An error occurred while processing the image.');
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>
