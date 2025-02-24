<form action="{{ route('generate.text_to_video') }}" method="POST">
    @csrf
    <label for="prompt">Text Prompt:</label>
    <input type="text" name="prompt" id="prompt" required>
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

const apiKey = @json($apiKey); // Loaded from environment variable


    document.querySelector('form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);

    try {
        const response = await fetch("{{ route('generate.text_to_video') }}", {
            method: "POST",
            body: formData,
        });
        const result = await response.json();

        if (response.ok) {
            console.log('video generated id:', result.generation_id);

            // Poll for video result
            fetchVideo(result.generation_id);
        } else {
            console.error('Error:', result.error);
        }
    } catch (error) {
        console.error('Request failed:', error);
    }
});

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
