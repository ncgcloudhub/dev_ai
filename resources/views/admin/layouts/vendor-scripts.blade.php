<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('build/js/plugins.js') }}"></script>

{{-- Sweetalerts --}}
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/sweetalerts.init.js') }}"></script>


<script src="{{ URL::asset('vendor/flasher/flasher-toastr.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


{{-- Select Multiple Tag --}}
<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>

<!-- tinymce | TEXT EDITOR -->
<script src="{{ asset('backend/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce.js') }}"></script>
<!--END tinymce TEXT EDITOR-->

<script>
    window.onload = function () {
        // Select all buttons with the class 'disabled-on-load'
        const buttons = document.querySelectorAll('.disabled-on-load');
        
        // Enable each button after the page is fully loaded
        buttons.forEach(button => {
            button.disabled = false;
        });
    };
</script>


{{-- CHAT STARt Scripts--}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

{{-- Attach FIle Icon Start--}}
<script>
    document.getElementById('icon').addEventListener('click', function() {
        document.getElementById('file_input').click();
    });
     // Listen for file input change event
     document.getElementById('file_input').addEventListener('change', function() {
        var fileInput = document.getElementById('file_input');
        var fileNameDisplay = document.getElementById('file_name_display');

        // Check if a file is selected
        if (fileInput.files.length > 0) {
            // Display the name of the selected file
            fileNameDisplay.textContent = "Selected File: " + fileInput.files[0].name;
        } else {
            // If no file is selected, clear the display
            fileNameDisplay.textContent = "";
        }
    });
</script>
{{-- Attach FIle Incon END --}}

{{-- FRONTEND SINGLE IMAGE --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#generateButton').click(function () {
            var prompt = $('#prompt').val();

            // Check if the prompt textarea is empty
            if (prompt.trim() === "") {
                // Show the error message
                $('#promptError').removeClass('d-none');
                return; // Stop the function here
            } else {
                // Hide the error message if prompt is not empty
                $('#promptError').addClass('d-none');
            }

            // Proceed with the generation process
            $('#generateButton').attr('disabled', true);
            $('#buttonText').text('Generating...');
            $('#loadingSpinner').removeClass('d-none');

            $.ajax({
                type: 'POST',
                url: '{{ route("generate.single.image") }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    prompt: prompt
                },
                success: function (response) {
                    $('#loadingSpinner').addClass('d-none');
                    $('#buttonText').text('Try again tomorrow');

                    if (response.message) {
                        $('#generatedImageContainer').html('<p>' + response.message + '</p>');
                    } else {
                        var imageUrl = response.data[0].url;
                        if (imageUrl) {
                            $('#generatedImageContainer').html('<img src="' + imageUrl + '" class="img-fluid" alt="Generated Image">');
                        } else {
                            $('#generatedImageContainer').html('<p>No image generated.</p>');
                        }

                        $('#generatedImageContainer').append('<p class="mt-3 text-warning">You have generated your image for today. Please come back tomorrow to generate a new one.</p>');
                    }
                },
                error: function (xhr, status, error) {
                    $('#loadingSpinner').addClass('d-none');
                    $('#generateButton').attr('disabled', false);
                    $('#buttonText').text('Generate');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>



{{-- NEWSLETTER --}}
<script>
    document.getElementById('newsletterForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Fetch form data
        var formData = new FormData(this);

        // Send form data asynchronously using fetch
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                // Optionally display a success message
                alert('Subscribed Successfully');
            } else {
                // Handle errors if any
                alert('Error occurred while subscribing');
            }
        })
        .catch(error => {
            console.error('Error occurred:', error);
            alert('Error occurred while subscribing');
        });

        // Prevent the form from being submitted again
        return false;
    });
</script>

{{-- Optimize Hammer --}}
<script>
    function toggleOptimize(suffix) {
        const optimizeInput = document.getElementById(`hiddenPromptOptimize_${suffix}`);
        const optimizeIcon = document.getElementById(`optimizeIcon_${suffix}`).firstElementChild;
    
        // Toggle between optimized and non-optimized
        if (optimizeInput.value === "1") {
            optimizeInput.value = "0";
            console.log('on');
            optimizeIcon.classList.replace("ri-hammer-fill", "ri-hammer-line"); // Reset to default icon
        } else {
            optimizeInput.value = "1";
            console.log('off');
            optimizeIcon.classList.replace("ri-hammer-line", "ri-hammer-fill"); // Change to optimized icon
        }
    }
</script>
    

<script>
    // Auto dismiss success alert after 3 seconds
    window.setTimeout(function() {
        $("#successAlert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 3000);
</script>


{{-- Copy Toaster button Velzon Default Gradient --}}
<script>
    document.querySelectorAll('.copy-toast-btn').forEach(button => {
    button.setAttribute('data-toast', '');
    button.setAttribute('data-toast-text', 'Content copied to clipboard!');
    button.setAttribute('data-toast-gravity', 'top');
    button.setAttribute('data-toast-position', 'right');
    button.setAttribute('data-toast-className', 'primary');
    button.setAttribute('data-toast-duration', '3000');
    button.setAttribute('data-toast-close', 'close');
    button.setAttribute('data-toast-style', 'style'); //Responsible for the gradient background
});

</script>

{{-- User Page Time --}}
<script>

let startTime = Date.now();
let timeSpent = 0;

window.addEventListener('beforeunload', function () {
    let endTime = Date.now();
    timeSpent = (endTime - startTime) / 1000; // Time in seconds

    let pageData = {
        time_spent: [
            {
                url: window.location.href,
                time_spent: timeSpent,
            },
        ],
    };

         // Use fetch to send data with CSRF token
    fetch('/save-time', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(pageData),
    })
    .then(response => {
        if (response.ok) {
            console.log('Time data sent successfully');
        } else {
            console.log('Failed to send time data');
        }
    })
    .catch(error => {
        console.error('Error sending time data:', error);
    });
    });
</script>

<script>
// COpy Prompt from modal (Image)
document.getElementById("copyPromptButton").addEventListener("click", function() {
    // Get the prompt text (adjust the selector based on where the prompt is located)
    var promptText = document.getElementById("imageModalLabel").innerText;

    // Use the Clipboard API to copy the text to the clipboard
    navigator.clipboard.writeText(promptText).then(function() {
        console.log("Copied Prompt: ", promptText);
    }).catch(function(err) {
        console.error("Failed to copy: ", err);
    });
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (!('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
            alert("Speech Recognition is not supported in this browser.");
            return;
        }

        const SpeechRecognitionAPI = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognitionAPI();
        recognition.continuous = true; 
        recognition.interimResults = false; // Final results only
        recognition.lang = "en-US";

        let activeInput = null; 
        let activeMicIcon = null; 
        let isRecording = false; 

        document.querySelectorAll(".speech-btn").forEach((button) => {
            button.addEventListener("click", function () {
                const inputField = this.previousElementSibling; 
                const micIcon = this.querySelector(".mic-icon");

                if (!isRecording) {
                    activeInput = inputField;
                    activeMicIcon = micIcon;
                    recognition.start();
                    isRecording = true;
                    micIcon.classList.replace("ri-mic-line", "ri-mic-fill");
                    micIcon.classList.add("text-danger");
                } else {
                    recognition.stop();
                    isRecording = false;
                    micIcon.classList.replace("ri-mic-fill", "ri-mic-line");
                    micIcon.classList.remove("text-danger");
                }
            });
        });

        recognition.onresult = (event) => {
            if (!activeInput) return;
            let transcript = event.results[event.results.length - 1][0].transcript; // Get the latest result
            activeInput.value += " " + transcript.trim(); // Append the new text
        };

        recognition.onend = () => {
            if (isRecording) {
                recognition.start(); // Restart if still active
            }
        };

        recognition.onerror = (event) => {
            console.error("Speech Recognition Error:", event.error);
        };
    });
</script>



@yield('script')
@yield('script-bottom')
