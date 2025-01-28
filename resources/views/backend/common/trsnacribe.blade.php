@extends('admin.layouts.master')
@section('title') AI Content Creator Category Add  @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('aicontentcreator.manage')}}">Content Creator Tools</a> @endslot
@slot('title') Category Add  @endslot
@endcomponent


<div class="row">

    <div class="col-xxl-6">
        <div class="card">

                <div class="card-body">
                    <button id="clearChat" class="btn btn-danger">Clear Memory</button>
                    <textarea class="form-control search" name="prompts" id="prompts"></textarea>
                    <button type="button" class="speech-btn">
                        <i class="mic-icon ri-mic-line fs-4"></i>
                    </button>
                    <audio id="speechAudio"></audio> 
                </div>


        </div>
    </div>

    
</div>


@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>

document.getElementById("clearChat").addEventListener("click", function() {
    fetch("/clear-chat", { method: "POST", headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content } })
    .then(() => alert("Chat memory cleared!"));
});


document.addEventListener("DOMContentLoaded", function () {
    if (!('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
        alert("Speech Recognition is not supported in this browser.");
        return;
    }

    const SpeechRecognitionAPI = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognitionAPI();
    recognition.continuous = false; // Stop when user stops manually
    recognition.interimResults = true;
    recognition.lang = "en-US";

    let activeInput = document.getElementById("prompts");
    let micButton = document.querySelector(".speech-btn");
    let micIcon = micButton.querySelector(".mic-icon");
    let isRecording = false;
    let finalTranscript = ""; // Store final transcript

    micButton.addEventListener("click", function () {
        if (!isRecording) {
            // Start recording
            finalTranscript = ""; // Clear old text
            recognition.start();
            isRecording = true;
            micIcon.classList.replace("ri-mic-line", "ri-mic-fill");
            micIcon.classList.add("text-danger");
        } else {
            // Stop recording
            recognition.stop();
            isRecording = false;
            micIcon.classList.replace("ri-mic-fill", "ri-mic-line");
            micIcon.classList.remove("text-danger");

            // Send text only after stopping
            if (finalTranscript.trim() !== "") {
                sendRequest(finalTranscript);
            }
        }
    });

    recognition.onresult = (event) => {
        let transcript = "";
        for (let i = event.resultIndex; i < event.results.length; i++) {
            transcript += event.results[i][0].transcript;
        }
        finalTranscript = transcript;
        activeInput.value = transcript; // Update textarea
    };

    recognition.onerror = (event) => {
        console.error("Speech Recognition Error:", event.error);
    };

    // Function to send request to Laravel
    function sendRequest(text) {
        fetch("/get-openai-response", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ prompt: text })
        })
        .then(response => response.json())
        .then(data => {
            console.log("AI Response:", data.response);
            readAloud(data.response); // Read AI's response aloud
        })
        .catch(error => console.error("Error:", error));
    }

    // Function to read AI response aloud
    function readAloud(text) {
        const speech = new SpeechSynthesisUtterance(text);
        speech.lang = "en-US";
        speech.rate = 1;
        speech.pitch = 1;
        window.speechSynthesis.speak(speech);
    }
});


</script>
@endsection

