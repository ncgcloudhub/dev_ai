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
                    <textarea class="form-control search" name="prompts" id="prompts"></textarea>
                    <button type="button" class="speech-btn">
                        <i class="mic-icon ri-mic-line fs-4"></i>
                    </button>

                    <button type="button" id="sendText" class="btn btn-primary">Send</button>
                    <audio id="speechAudio"></audio> 
                </div>


        </div>
    </div>

    
</div>


@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    console.log(document.getElementById("prompts").value);

    const sendTextBtn = document.getElementById("sendText");
    const speechAudio = document.getElementById("speechAudio");
    const promptInput = document.getElementById("prompts");

    if (!sendTextBtn || !promptInput) {
        console.error("One or more elements not found!");
        return;
    }

    // Send text input when clicking "Send"
    sendTextBtn.addEventListener("click", function () {
        console.log("Sending text input to Laravel...");
        console.log(promptInput.value);
        sendRequest(promptInput.value);
    });

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

