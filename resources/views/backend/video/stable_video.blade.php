<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image to Video</title>
</head>
<body>
    <h1>Generate Animated Video</h1>
    <form action="{{ route('generate.video') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="image">Select Image:</label>
        <input type="file" name="image" id="image" required>
        <br>
        <label for="seed">Seed:</label>
        <input type="number" name="seed" id="seed" value="0">
        <br>
        <label for="cfg_scale">CFG Scale:</label>
        <input type="number" name="cfg_scale" id="cfg_scale" value="1.8" step="0.1">
        <br>
        <label for="motion_bucket_id">Motion Bucket ID:</label>
        <input type="number" name="motion_bucket_id" id="motion_bucket_id" value="127">
        <br>
        <button type="submit">Generate Video</button>
    </form>
</body>
</html>
