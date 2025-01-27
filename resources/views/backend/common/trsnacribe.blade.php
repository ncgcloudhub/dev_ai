<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <button id="record">🎙 Start Recording</button>
    <button id="stop" disabled>🛑 Stop Recording</button>
    <p>Transcription: <span id="transcription">...</span></p>
    
    <script>
    let mediaRecorder;
    let audioChunks = [];
    
    document.getElementById("record").addEventListener("click", async () => {
        let stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        mediaRecorder.start();
        document.getElementById("record").disabled = true;
        document.getElementById("stop").disabled = false;
    
        mediaRecorder.ondataavailable = event => {
            audioChunks.push(event.data);
        };
    
        mediaRecorder.onstop = async () => {
            let audioBlob = new Blob(audioChunks, { type: "audio/webm" }); // Default recording type
            let formData = new FormData();
            formData.append("audio", audioBlob, "recording.webm");
    
            let response = await fetch("{{ route('transcribe') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // Fix for 419 error
                },
                body: formData
            });
    
            let result = await response.json();
            document.getElementById("transcription").innerText = result.transcription ?? "Error transcribing.";
        };
    });
    
    document.getElementById("stop").addEventListener("click", () => {
        mediaRecorder.stop();
        document.getElementById("record").disabled = false;
        document.getElementById("stop").disabled = true;
    });
    </script>
</body>
</html>