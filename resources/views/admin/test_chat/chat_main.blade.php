@extends('admin.layouts.master')
@section('title')
@lang('translation.chat')
@endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css')}}">

@endsection
@section('content')


<div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
    <div class="chat-leftsidebar border">
        <div class="px-4 pt-4 mb-4">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <h5 class="mb-0">New Chat</h5>
                </div>
                <div class="flex-shrink-0">
                    <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom" title="New Chat">

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-soft-primary btn-sm" id="main_new_session_btn">
                            <i class="ri-add-line align-bottom"></i>
                        </button>
                    </div>
                </div>
            </div>
           
        </div> <!-- .p-4 -->

        <ul class="nav nav-tabs nav-tabs-custom nav-info nav-justified" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#chats" role="tab">
                    Chats
                </a>
            </li>
        
        </ul>

        <div class="tab-content text-muted">
            <div class="tab-pane active" id="chats" role="tabpanel">
                <div class="chat-room-list pt-3" data-simplebar>
                    <div class="d-flex align-items-center px-4 mb-2">
                      
                      
                    </div>

                    <div class="chat-message-list">

                        <ul class="list-unstyled chat-list chat-user-list" id="session-list">
                            @foreach ($sessions as $item)
                            <li id="contact-id-1" data-name="direct-message" class="">                    
                                <a href="javascript: void(0);"> 
                                    <div class="d-flex align-items-center">
        
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-truncate mb-0">{{$item->session_token}}</p>
                                        </div>               
                                    </div>  
                                </a>     
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
            
        </div>
        <!-- end tab contact -->
    </div>
    <!-- end chat leftsidebar -->
    <!-- Start User chat -->
    <div class="user-chat w-100 overflow-hidden border">

        <div class="chat-content d-lg-flex">
            <!-- start chat conversation section -->
            <div class="w-100 overflow-hidden position-relative">
                <!-- conversation user -->
                <div class="position-relative">


                    <div class="position-relative" id="users-chat">
                        <div class="p-3 user-chat-topbar">
                            <div class="row align-items-center">
                                <div class="col-sm-4 col-8">
                                   
                                </div>
                                <div class="col-sm-8 col-4">
                                    <ul class="list-inline user-chat-nav text-end mb-0">
                                  

                                        <li class="list-inline-item m-0">
                                            
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <!-- end chat user head -->
                        <div class="chat-conversation p-3 p-lg-4 " id="chat-conversation" data-simplebar>
                            <div id="elmLoader">
                                {{-- <div class="spinner-border text-primary avatar-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div> --}}
                            </div>
                            <ul class="list-unstyled chat-conversation-list" id="users-conversation">

                            </ul>
                            <!-- end chat-conversation-list -->
                        </div>
                       
                    </div>

                    <div class="chat-input-section p-3 p-lg-4">

                            <div class="row g-0 align-items-center">
                                {{-- File Selected Show --}}
                                <div id="file_name_display"></div>
                                <div class="col-auto">
                                   
                                    <div class="chat-input-links me-2">
                                       
                                        <div class="links-list-item"> 
                                        {{-- Attachement Icon --}}
                                        <i id="icon" class="ri-attachment-line" style="cursor: pointer; font-size:22px;"></i>
                                        <input name="file" type="file" id="file_input" class="form-control" style="display: none;" accept=".txt,.pdf,.doc,.docx,.jpg,.jpeg,.png">
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div id="image_display"></div>
                                    <div class="chat-input-feedback">
                                        Please Enter a Message
                                    </div>
                                    <textarea class="form-control chat-input bg-light border-light auto-expand" id="user_message_input" rows="1" placeholder="Type your message..." autocomplete="off"></textarea>
                                </div>
                                <div class="col-auto">
                                    <div class="chat-input-links ms-2">
                                        <div class="links-list-item">
                                            <button type="button" id="main_send_message_btn" class="btn btn-success chat-send waves-effect waves-light fs-13">
                                                <i class="ri-send-plane-2-fill align-bottom"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        
                    </div>

                    <div class="replyCard">
                        <div class="card mb-0">
                            <div class="card-body py-3">
                                <div class="replymessage-block mb-0 d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <h5 class="conversation-name"></h5>
                                        <p class="mb-0"></p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <button type="button" id="close_toggle" class="btn btn-sm btn-link mt-n2 me-n3 fs-18">
                                            <i class="bx bx-x align-middle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end chat-wrapper -->

@endsection
@section('script')
<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>

    <!-- fgEmojiPicker js -->
    <script src="{{ URL::asset('build/libs/fg-emoji-picker/fgEmojiPicker.js') }}"></script>

    <!-- chat init js -->
    <script src="{{ URL::asset('build/js/pages/chat.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

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

        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const messageInput = document.getElementById('user_message_input');
        const sendMessageBtn = document.getElementById('main_send_message_btn');
        const fileInput = document.getElementById('file_input');
        const chatConversation = document.getElementById('users-conversation');
        const chatContainer = document.getElementById('chat-conversation');
        const fileNameDisplay = document.getElementById('file_name_display');
        const newSessionBtn = document.getElementById('main_new_session_btn');
        const imageDisplay = document.getElementById('image_display');
        let pastedImageFile = null;
        
        const sessionList = document.getElementById('session-list');


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
        axios.post('/main/new-session')
            .then(response => {
                if (response.data.success) {
                    
                    const newSessionId = response.data.session_id;
                    console.log('newSessionId', newSessionId);
                    
                     // Create new session list item
                     const li = document.createElement('li');
                    li.id = `contact-id-${newSessionId}`;
                    li.dataset.name = "direct-message";
                    li.innerHTML = `
                        <a href="javascript: void(0);"> 
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-truncate mb-0">${newSessionId}</p>
                                </div>               
                            </div>  
                        </a>
                    `;
                    sessionList.appendChild(li);


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

    function sendMessage() {
        const message = messageInput.value.trim();
    
        const file = fileInput.files[0];

        if (!message && !file && !pastedImageFile) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData();
        formData.append('message', message);
      
        if (file) {
            formData.append('file', file);
        } else if (pastedImageFile) {
            formData.append('file', pastedImageFile, 'pasted_image.png'); // Name the file appropriately
        }

        sendMessageBtn.disabled = true;
        sendMessageBtn.innerHTML = 'Sending...';

        axios.post('/main/chat/send', formData, {
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
                                <p class="mb-0 ctext-content">${message || file?.name || 'Pasted Image'}</p>`;

            if (file || pastedImageFile) {
                const fileType = (file || pastedImageFile).type.split('/')[0];
                if (fileType === 'image') {
                    const imageUrl = URL.createObjectURL(file || pastedImageFile);
                    userMessageHTML += `<img style="width: 50px;" src="${imageUrl}" alt="Attached Image" class="attached-image">`;
                } else {
                    userMessageHTML += `<i class=" ri-file-2-fill">${file?.name || 'Pasted Image'}</i>`;
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
            imageDisplay.innerHTML = ''; // Clear pasted image display
            pastedImageFile = null; // Reset pasted image file
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


});

</script>

<script>
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
</script>



@endsection
