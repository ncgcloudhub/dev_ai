<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form id="audioUploadForm" action="{{ route('transcribe') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="audio">Upload an Audio File (MP3/WAV):</label>
        <input type="file" name="audio" id="audio" accept=".mp3, .wav" required>
        <button type="submit">Upload & Transcribe</button>
    </form>
    
    <p>Transcription: <span id="transcription">...</span></p>

    <script>
        document.getElementById("audioUploadForm").addEventListener("submit", async function(event) {
            event.preventDefault();
        
            let formData = new FormData(this);
        
            let response = await fetch(this.action, {
                method: "POST",
                body: formData
            });
        
            let result = await response.json();
            document.getElementById("transcription").innerText = result.transcription ?? "Error transcribing.";
        });
        </script>
</body>
</html>