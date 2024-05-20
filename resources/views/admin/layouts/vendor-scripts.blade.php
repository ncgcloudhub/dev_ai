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
    const fileInput = document.getElementById('file_input'); // File input
    const chatConversation = document.getElementById('users-conversation');
    const chatContainer = document.getElementById('chat-conversation');

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Function to send message
    function sendMessage() {
        const message = messageInput.value.trim();
        const selectedModel = document.getElementById('ai_model_select').value; // Get the selected AI model
        console.log(selectedModel);
        document.getElementById('file_name_display').innerHTML = '';
        const file = fileInput.files[0];

        if (!message && !file) return; // Prevent sending empty message without file

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData();
        formData.append('message', message);
        if (file) {
            formData.append('file', file);
        }

        // Include selected AI model in the payload
        formData.append('ai_model', selectedModel);

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

            const newMessage = `<li class="chat-list right">
                <div class="conversation-list">
                    <div class="user-chat-content">
                        <div class="ctext-wrap">
                            <div class="ctext-wrap-content">
                                <p class="mb-0 ctext-content">${message}</p>
                            </div>
                        </div>
                        <div class="conversation-name"><small class="text-muted time">${currentTime}</small></div>
                    </div>
                </div>
            </li>`;

            const assistantMessage = response.data.message;

            const faviconUrl = "{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}";
            const newReply = `<li class="chat-list left">
                            <div class="conversation-list">
                                <div class="chat-avatar">
                                    <img src="${faviconUrl}" alt="">
                                </div>
                                <div class="user-chat-content">
                                    <div class="ctext-wrap">
                                        <div class="ctext-wrap-content">
                                            <p class="mb-0 ctext-content">${assistantMessage}</p>
                                        </div>
                                        <div class="dropdown align-self-start message-box-drop">
                                            <a class="dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="ri-more-2-fill"></i>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#"><i
                                                        class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="conversation-name"><small class="text-muted time">${currentTime}</small>
                                        <span class="text-success check-message-icon"><i
                                                class="ri-check-double-line align-bottom"></i></span></div>
                                </div>
                            </div>
                        </li>`;

            chatConversation.insertAdjacentHTML('beforeend', newMessage);
            chatConversation.insertAdjacentHTML('beforeend', newReply);
            scrollToBottom();

            messageInput.value = ''; // Clear input field after sending message
            fileInput.value = ''; // Clear file input after sending message
        })
        .catch(error => {
            console.error(error);
            const errorMessage = `<li class="chat-list right">
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
            chatConversation.insertAdjacentHTML('beforeend', errorMessage);
            scrollToBottom();
        })
        .finally(() => {
            sendMessageBtn.disabled = false;
            sendMessageBtn.innerHTML = '<span class="d-none d-sm-inline-block me-2">Send</span> <i class="mdi mdi-send float-end"></i>';
        });
    }

    // Event listener for Enter key press
    messageInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault(); // Prevent default behavior (adding a new line)
            sendMessage(); // Call the sendMessage function
        }
    });

    // Event listener for Send button click
    sendMessageBtn.addEventListener('click', function () {
        sendMessage(); // Call the sendMessage function
    });
});
</script>


</script>
{{-- CHAT END Scripts--}}

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

@yield('script')
@yield('script-bottom')
