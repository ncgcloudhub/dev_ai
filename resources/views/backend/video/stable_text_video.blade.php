<form action="{{ route('generate.image_to_video') }}" method="POST">
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

<script>
    document.querySelector('form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);

    try {
        const response = await fetch("{{ route('generate.image_to_video') }}", {
            method: "POST",
            body: formData,
        });
        const result = await response.json();

        if (response.ok) {
            console.log('Image generated:', result.image_url);

            // Poll for video result
            pollForVideo(result.generation_id);
        } else {
            console.error('Error:', result.error);
        }
    } catch (error) {
        console.error('Request failed:', error);
    }
});

function pollForVideo(generationId) {
    const interval = setInterval(async () => {
        const response = await fetch(`/video-result/${generationId}`);
        const result = await response.json();

        if (result.status === 200) {
            clearInterval(interval);
            document.getElementById('generatedVideo').src = result.video_url;
            document.getElementById('videoContainer').style.display = 'block';
        } else if (result.status === 202) {
            console.log(result.message);
        } else {
            clearInterval(interval);
            console.error('Error:', result.error);
        }
    }, 10000); // Poll every 10 seconds
}

</script>
