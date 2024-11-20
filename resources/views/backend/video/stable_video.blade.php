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
      // Function to fetch video result
function fetchVideo(generationId) {
    // Polling interval (in milliseconds)
    const pollingInterval = 10000; // 10 seconds

    // Start polling
    const pollForVideo = setInterval(() => {
        fetch(`https://api.stability.ai/v2beta/image-to-video/result/${generationId}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${apiKey}`,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Handle case when the video is ready
            if (data && data.finish_reason === 'SUCCESS') {
                console.log('Video is ready! Base64 video data:', data.video);

                // Decode the base64 video and display or download it
                const videoBlob = new Blob([new Uint8Array(atob(data.video).split("").map(c => c.charCodeAt(0)))], { type: 'video/mp4' });
                const videoUrl = URL.createObjectURL(videoBlob);

                // Display video in the browser (you can adjust this as needed)
                const videoElement = document.createElement('video');
                videoElement.src = videoUrl;
                videoElement.controls = true;
                document.body.appendChild(videoElement);

                // Stop polling after success
                clearInterval(pollForVideo);
            } else if (data && data.status === 'in-progress') {
                console.log('Video generation still in progress...');
            } else {
                console.error('Error fetching video:', data);
                clearInterval(pollForVideo);
            }
        })
        .catch(error => {
            console.error('Error fetching video:', error);
            clearInterval(pollForVideo);
        });
    }, pollingInterval);
}


    </script>
</body>
</html>

