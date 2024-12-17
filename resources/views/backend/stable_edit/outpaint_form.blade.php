<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outpaint</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
<form id="outpaint-image-form" action="{{ route('stable.edit.outpaint') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="image">Upload Image:</label>
    <input type="file" name="image" id="image" required>

    <label for="left">Extend Left (px):</label>
    <input type="number" name="left" id="left" placeholder="e.g., 200 (optional)">
    
    <label for="right">Extend Right (px):</label>
    <input type="number" name="right" id="right" placeholder="e.g., 200 (optional)">
    
    <label for="down">Extend Down (px):</label>
    <input type="number" name="down" id="down" placeholder="e.g., 200 (optional)">
    
    <label for="up">Extend Up (px):</label>
    <input type="number" name="up" id="up" placeholder="e.g., 200 (optional)">
    

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
<div id="loading-spinner" style="display: none;">Loading...</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
    $('#outpaint-image-form').on('submit', function (event) {
        event.preventDefault();

        const left = $('#left').val() || 0; // Default to 0 if empty
        const right = $('#right').val() || 0; // Default to 0 if empty
        const up = $('#up').val() || 0; // Default to 0 if empty
        const down = $('#down').val() || 0; // Default to 0 if empty

        // Check if all values are zero
        if (left == 0 && right == 0 && up == 0 && down == 0) {
            alert('Please fill in at least one of the fields: left, right, up, or down.');
            return false;
        }

        const formData = new FormData(this);
        formData.set('left', left);
        formData.set('right', right);
        formData.set('up', up);
        formData.set('down', down);

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
