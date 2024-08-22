<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('build/js/plugins.js') }}"></script>

{{-- Sweetalerts --}}
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/sweetalerts.init.js') }}"></script>

{{-- Datatables --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

{{-- Select Multiple Tag --}}
<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>

<!-- tinymce | TEXT EDITOR -->
<script src="{{ asset('backend/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce.js') }}"></script>
<!--END tinymce TEXT EDITOR-->


{{-- CHAT STARt Scripts--}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        
    const messageInput = document.getElementById('user_message_input');
    const sendMessageBtn = document.getElementById('send_message_btn');
    const fileInput = document.getElementById('file_input');
    const chatConversation = document.getElementById('users-conversation');
    const chatContainer = document.getElementById('chat-conversation');
    const aiModelSelect = document.getElementById('ai_model_select');
    const fileNameDisplay = document.getElementById('file_name_display');
    const newSessionBtn = document.getElementById('new_session_btn');
    const imageDisplay = document.getElementById('image_display');

    let pastedImageFile = null;

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // CHECK SESSION
    const checkUserSession = () => {
    // Make an HTTP GET request to your Laravel backend
    axios.get('/chat/check-session')
        .then(response => {
            // Handle the response
            if (response.data.hasSession) {
              
            } else {
                newSessionBtn.click();
              
            }
        })
        .catch(error => {
            // Handle any errors
            console.error('Error checking user session:', error);
        });
    };


    // NEW SESSION
    newSessionBtn.addEventListener('click', function () {
        axios.post('/chat/new-session')
            .then(response => {
                if (response.data.success) {
                    // Clear the chat UI
                    chatConversation.innerHTML = '';
                    // Clear input fields
                    messageInput.value = '';
                    fileInput.value = '';
                    fileNameDisplay.textContent = '';
                    // Scroll to bottom
                    scrollToBottom();
                }
            })
            .catch(error => {
                console.error('Failed to start a new session:', error);
            });
    });

     // Call the function to check user session when needed
    checkUserSession();
     

    // window.addEventListener('beforeunload', function () {
    //     axios.post('/clear-session', {})
    //         .then(response => {
    //             console.log('Session cleared successfully');
    //         })
    //         .catch(error => {
    //             console.error('Failed to clear session:', error);
    //         });
    // });

    fileInput.addEventListener('change', function () {
        const fileName = fileInput.files[0].name;
        fileNameDisplay.textContent = `Selected file: ${fileName}`;
    });

  // Function to handle image paste
  function handleImagePaste(event) {
        const clipboardItems = event.clipboardData.items;
        for (let i = 0; i < clipboardItems.length; i++) {
            const item = clipboardItems[i];
            if (item.type.indexOf("image") !== -1) {
                const blob = item.getAsFile();
                pastedImageFile = blob;
                const imageUrl = URL.createObjectURL(blob);

                // Display the pasted image in image_display div
                const img = document.createElement('img');
                img.src = imageUrl;
                img.style.maxWidth = '20%'; // Optional: Adjust image size
                imageDisplay.innerHTML = ''; // Clear any previous image
                imageDisplay.appendChild(img);

                // Stop further processing to prevent multiple image pastes
                event.preventDefault();
                break;
            }
        }
    }

    // Listen for paste events on messageInput
    messageInput.addEventListener('paste', handleImagePaste);

    sendMessageBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
        }
    });


    function formatContent(content) {
        const lines = content.split('\n');
        let formattedContent = '';

        if (lines.length === 1) {
            formattedContent = `<p style="font-family: Calibri;">${lines[0]}</p>`;
        } else if (lines[0].includes('```') && lines[lines.length - 1].includes('```')) {
            const codeContent = lines.slice(1, -1).join('\n');
            formattedContent = `<pre style="background-color: #f5f5f5; padding: 10px; border-radius: 5px; font-family: monospace;">${codeContent}</pre>`;
        } else if (lines.some(line => line.trim().startsWith('*'))) {
            formattedContent += '<ul style="font-family: Calibri;">';
            lines.forEach(line => {
                if (line.trim().startsWith('*')) {
                    formattedContent += '<li>' + line.trim().substring(1).trim() + '</li>';
                } else {
                    formattedContent += '<p>' + line.trim() + '</p>';
                }
            });
            formattedContent += '</ul>';
        } else {
            formattedContent = '<p style="font-family: Calibri; white-space: pre-wrap; word-wrap: break-word;">' + lines.join('</p><p style="font-family: Calibri; white-space: pre-wrap; word-wrap: break-word;">') + '</p>';
        }

        return formattedContent;
    }
    });


</script>

{{-- CHAT END Scripts--}}


{{-- TEST GET MESSAGES --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const sessionSelect = document.getElementById('session');
    const chatConversation = document.getElementById('users-conversation');

    sessionSelect.addEventListener('change', function() {
        const sessionId = sessionSelect.value;

        axios.get(`/chat/sessions/${sessionId}/messages`)
            .then(response => {
                const messages = response.data;

                // Clear current chat
                chatConversation.innerHTML = '';

// Display fetched messages
messages.forEach(message => {
                    let content = '';
                    let role = '';

                    if (message.message) {
                        content = message.message;
                        role = 'user';
                    } else if (message.reply) {
                        content = message.reply;
                        role = 'assistant'; // Adjust this to match your classes for assistant messages
                    }

                    const messageHTML = `
                        <li class="chat-list ${role === 'user' ? 'right' : 'left'}">
                            <div class="conversation-list">
                                <div class="user-chat-content">
                                    <div class="ctext-wrap">
                                        <div class="ctext-wrap-content">
                                            <p class="mb-0 ctext-content">${content}</p>
                                        </div>
                                    </div>
                                    <div class="conversation-name">
                                        <small class="text-muted time">${new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                                    </div>
                                </div>
                            </div>
                        </li>
                    `;
                    chatConversation.insertAdjacentHTML('beforeend', messageHTML);
                });

                // Scroll to bottom of the chat
                chatConversation.scrollTop = chatConversation.scrollHeight;
            })
            .catch(error => {
                console.error(error);
            });
    });

    // Optionally, trigger a change event on page load to load messages for the first session
    if (sessionSelect.options.length > 0) {
        sessionSelect.dispatchEvent(new Event('change'));
    }
});

</script>
    




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

<script>
    // Auto dismiss success alert after 3 seconds
    window.setTimeout(function() {
        $("#successAlert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 3000);
</script>



@yield('script')
@yield('script-bottom')
