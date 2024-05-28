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

<!-- tinymce | TEXT EDITOR -->
<script src="{{ asset('backend/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce.js') }}"></script>
<!--END tinymce TEXT EDITOR-->


{{-- CHAT STARt Scripts--}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
    $(document).ready(function() {
        // Function to auto-expand textarea
        $('.auto-expand').on('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });

    // Function to send message when Enter key is pressed
    $('.auto-expand').on('keydown', function(e) {
        if (e.which == 13 && !e.shiftKey) { // Check if Enter is pressed without Shift
            e.preventDefault(); // Prevent the default Enter behavior (adding a new line)
            sendMessage(); // Call the function to send the message
        }
    });
    
    </script>

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

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

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

    function sendMessage() {
        const message = messageInput.value.trim();
        const selectedModel = aiModelSelect.value;
        const file = fileInput.files[0];

        if (!message && !file) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData();
        formData.append('message', message);
        formData.append('ai_model', selectedModel);
        if (file) {
            formData.append('file', file);
        }

        sendMessageBtn.disabled = true;
        sendMessageBtn.innerHTML = 'Sending...';

        axios.post('/chat/send', formData, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'multipart/form-data',
            },
        })
        .then(response => {
            const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            let userMessageHTML = `<li class="chat-list right">
                <div class="conversation-list">
                    <div class="user-chat-content">
                        <div class="ctext-wrap">
                            <div class="ctext-wrap-content">
                                <p class="mb-0 ctext-content">${message || file.name}</p>`;

            if (file) {
                const fileType = file.type.split('/')[0];
                if (fileType === 'image') {
                    const imageUrl = URL.createObjectURL(file);
                    userMessageHTML += `<img style="width: 50px;" src="${imageUrl}" alt="Attached Image" class="attached-image">`;
                } else {
                    userMessageHTML += `<i class=" ri-file-2-fill">${file.name}</i>`;
                }
            }

            userMessageHTML += `</div>
                        </div>
                        <div class="conversation-name"><small class="text-muted time">${currentTime}</small></div>
                    </div>
                </div>
            </li>`;

            const assistantMessage = response.data.message;
            const formattedMessage = formatContent(assistantMessage);
            const assistantMessageHTML = `<li class="chat-list left">
                <div class="conversation-list">
                    <div class="chat-avatar">
                        <img src="{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}" alt="">
                    </div>
                    <div class="user-chat-content">
                        <div class="ctext-wrap">
                            <div class="ctext-wrap-content">
                                ${formattedMessage}
                            </div>
                        </div>
                        <div class="conversation-name"><small class="text-muted time">${currentTime}</small></div>
                    </div>
                </div>
            </li>`;

            chatConversation.insertAdjacentHTML('beforeend', userMessageHTML);
            chatConversation.insertAdjacentHTML('beforeend', assistantMessageHTML);
            scrollToBottom();

            messageInput.value = '';
            fileInput.value = '';
            fileNameDisplay.textContent = '';
        })
        .catch(error => {
            console.error(error);
            const errorMessageHTML = `<li class="chat-list right">
                <div class="conversation-list">
                    <div class="user-chat-content">
                        <div class="ctext-wrap">
                            <div class="ctext-wrap-content">
                                <p class="mb-0 ctext-content text-danger">Failed to send message. Please try again.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </li>`;
            chatConversation.insertAdjacentHTML('beforeend', errorMessageHTML);
            scrollToBottom();
        })
        .finally(() => {
            sendMessageBtn.disabled = false;
            sendMessageBtn.innerHTML = '<span class="d-none d-sm-inline-block me-2">Send</span> <i class="mdi mdi-send float-end"></i>';
        });
    }

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
            // Show the loading spinner
            $('#loadingSpinner').show();

            var prompt = $('#prompt').val();

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
                    // Hide the loading spinner
                    $('#loadingSpinner').hide();

                    if (response.message) {
                        // Display the message if the user has already generated an image today
                        $('#generatedImageContainer').html('<p>' + response.message + '</p>');
                    } else {
                        // Extract the URL of the generated image from the response
                        var imageUrl = response.data[0].url;
                        if (imageUrl) {
                            $('#generatedImageContainer').html('<img src="' + imageUrl + '" class="img-fluid" alt="Generated Image">');
                        } else {
                            $('#generatedImageContainer').html('<p>No image generated.</p>');
                        }
                    }
                },
                error: function (xhr, status, error) {
                    // Hide the loading spinner
                    $('#loadingSpinner').hide();

                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>


@yield('script')
@yield('script-bottom')
