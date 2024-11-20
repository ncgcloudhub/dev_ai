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

    <!-- Display the video after it's generated -->
    <div id="videoContainer" style="margin-top: 20px; display: none;">
        <h2>Generated Video</h2>
        <video id="generatedVideo" controls>
            <source id="videoSource" src="" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <script>
        // Handle form submission with AJAX
        const form = document.querySelector('form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault(); // Prevent default form submission
            
            const formData = new FormData(form);
            const response = await fetch("{{ route('generate.video') }}", {
                method: "POST",
                body: formData,
            });
            
            const result = await response.json();

            if (response.ok && result.id) {
                // Successfully generated video, now fetch the video result
                fetchVideo(result.id);
            } else {
                alert('Error: ' + result.error.message);
            }
        });


        const apiKey = @json($apiKey); // Loaded from environment variable

// Log the apiKey to verify it's loaded
console.log('API Key:', apiKey);

        // Fetch video result based on generation ID
        function fetchVideo(generationId) {
    fetch(`https://api.stability.ai/v2beta/image-to-video/result/${generationId}`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${apiKey}`,
            'Accept': 'video/*'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.video_url) {
            // Handle successful response with video URL
            console.log('Video URL:', data.video_url);
            // You can use the video URL to show the video or download it
        } else {
            // Handle the case where there's no video URL in the response
            console.error('No video URL found in the response:', data);
        }
    })
    .catch(error => {
        // Handle any errors in the fetch or response
        console.error('Error fetching video:', error);
    });
}

    </script>
</body>
</html>

