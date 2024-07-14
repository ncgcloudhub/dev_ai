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
                            <li id="contact-id-{{ $item->session_token }}" data-name="direct-message" data-session-id="{{ $item->id }}" class="{{ $loop->first ? 'active' : '' }}">
                                <a href="javascript: void(0);">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-truncate mb-0">{{ $item->title }}</p>
                                        </div>
                                      
                                        <button class="edit-session-btn btn btn-sm btn-info btn-icon waves-effect waves-light" data-session-id="{{ $item->id }}">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <button class="delete-session-btn btn btn-sm btn-danger btn-icon waves-effect waves-light" data-session-id="{{ $item->id }}">
                                            <i class="ri-delete-bin-5-line"></i>
                                        </button>
                                       
                                    </div>
                                </a>
                            </li>
                        @endforeach
                        
                        </ul>
     
                    </div>

                </div>
            </div>
            <div class="tab-pane" id="contacts" role="tabpanel">
                <div class="chat-room-list pt-3" data-simplebar style="max-height: calc(100vh - 305px);">
                    <div class="sort-contact">
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
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 d-block d-lg-none me-3">
                                            <a href="javascript: void(0);" class="user-chat-remove fs-18 p-1"><i class="ri-arrow-left-s-line align-bottom"></i></a>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 chat-user-img online user-own-img align-self-center me-3 ms-0">
                                                    <img src="{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}" class="rounded-circle avatar-xs" alt="">
                                                    <span class="user-status"></span>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate mb-0 fs-16"><a class="text-reset username" data-bs-toggle="offcanvas" href="#userProfileCanvasExample" aria-controls="userProfileCanvasExample"></a>Clever Chat</h5>
                                                    <p class="text-truncate text-muted fs-14 mb-0 userStatus"><small></small></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>

                        </div>

                        <!-- end chat user head -->
                        <div class="chat-conversation p-3 p-lg-4 " id="chat-conversation" data-simplebar>
                            <div id="elmLoader">
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
                                            @if(auth()->user()->role == 'admin')
                                            <i id="icon" class="ri-attachment-line" style="cursor: pointer; font-size:22px;"></i>
                                            @endif
                                            <input name="file" type="file" id="file_input" class="form-control" style="display: none;" accept=".txt,.pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                
                                            </div>
                                        </div>
                                    </div>
                               


                                <div class="col">

                                    <div class="chat-input-feedback">
                                        Please Enter a Message
                                    </div>
                                    <div id="image_display"></div>
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
    let isFirstMessage = true;

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function summarizeMessage(message) {
        return message.length > 20 ? message.substring(0, 20) + '...' : message;
    }

    const checkUserSession = () => {
        axios.get('/chat/check-session')
            .then(response => {
                if (!response.data.hasSession) {
                    newSessionBtn.click();
                }
            })
            .catch(error => {
                console.error('Error checking user session:', error);
            });
    };

    checkUserSession();

    function setActiveSession(sessionId) {
        const previousActive = sessionList.querySelector('li.active');
        if (previousActive) {
            previousActive.classList.remove('active');
        }

        const newActive = sessionList.querySelector(`li[data-session-id='${sessionId}']`);
        if (newActive) {
            newActive.classList.add('active');
        }
    }

    newSessionBtn.addEventListener('click', function () {
        axios.post('/main/new-session')
            .then(response => {
                if (response.data.success) {
                    const newSessionId = response.data.session_id;
                    const li = document.createElement('li');
                    li.id = `contact-id-${newSessionId}`;
                    li.dataset.name = "direct-message";
                    li.dataset.sessionId = newSessionId;
                    li.classList.add('active');
                    li.innerHTML = `
                        <a href="javascript: void(0);">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-truncate mb-0">New Chat</p>
                                </div>
                                <button class="edit-session-btn btn btn-sm btn-info btn-icon waves-effect waves-light" data-session-id="${newSessionId}">
                                        <i class="ri-pencil-line"></i>
                                </button>
                                <button class="delete-session-btn btn btn-sm btn-danger btn-icon waves-effect waves-light" data-session-id="${newSessionId}">
                                    <i class="ri-delete-bin-5-line"></i>
                                </button>
                            </div>
                        </a>
                    `;

                    sessionList.appendChild(li);
                    chatConversation.innerHTML = '';
                    messageInput.value = '';
                    fileInput.value = '';
                    fileNameDisplay.textContent = '';
                    scrollToBottom();

                    setActiveSession(newSessionId);
                    isFirstMessage = true;
                }
            })
            .catch(error => {
                console.error('Failed to start a new session:', error);
            });
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

                    // Create image element
                    const img = document.createElement('img');
                    img.src = imageUrl;
                    img.style.maxWidth = '10%'; // Adjust image size as needed

                    // Create remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.textContent = 'X';
                    removeBtn.classList.add('remove-btn');
                    removeBtn.addEventListener('click', () => {
                        // Remove image and reset input
                        imageDisplay.innerHTML = '';
                        pastedImageFile = null; // Reset pastedImageFile
                    });

                    // Container for image and button
                    const container = document.createElement('div');
                    container.classList.add('image-container');
                    container.appendChild(img);
                    container.appendChild(removeBtn);

                    // Display the pasted image in image_display div
                    imageDisplay.innerHTML = ''; // Clear any previous image
                    imageDisplay.appendChild(container);

                    // Stop further processing to prevent multiple image pastes
                    event.preventDefault();
                    break;
                }
            }
        }

        // Listen for paste events on messageInput
        messageInput.addEventListener('paste', handleImagePaste);

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
        formData.append('file', pastedImageFile, 'pasted_image.png');
    }

    if (isFirstMessage) {
        const chatTitle = summarizeMessage(message || file?.name || 'Pasted Image');
        formData.append('title', chatTitle);
    }

    sendMessageBtn.disabled = true;
    sendMessageBtn.innerHTML = '<i class="mdi mdi-spin mdi-loading"></i>';

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

        if (isFirstMessage) {
            const chatTitle = summarizeMessage(message || file?.name || 'Pasted Image');
            const activeSession = sessionList.querySelector('li.active');
            if (activeSession) {
                activeSession.querySelector('p').textContent = chatTitle;
            }
            isFirstMessage = false;
        }

        messageInput.value = '';
        fileInput.value = '';
        fileNameDisplay.textContent = '';
        imageDisplay.innerHTML = '';
        pastedImageFile = null;
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
        sendMessageBtn.innerHTML = '<i class="ri-send-plane-2-fill align-bottom"></i>';
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
        formattedContent = `
            <div class="code-block" style="position: relative;">
                <pre style="background-color: #272822; color: #f8f8f2; padding: 10px; border-radius: 5px; font-family: monospace; white-space: pre;">${codeContent}</pre>
                <button class="copy-button" style="position: absolute; top: 5px; right: 10px; background-color: #555; color: #fff; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Copy</button>
            </div>
        `;
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
        lines.forEach(line => {
            if (line.trim().startsWith('###')) {
                formattedContent += `<p style="font-weight: bold; font-family: Calibri;">${line.trim().substring(3).trim()}</p>`;
            } else {
                formattedContent += '<p style="font-family: Calibri; white-space: pre-wrap; word-wrap: break-word;">' + line.trim() + '</p>';
            }
        });
    }

    // Replace code blocks with a styled pre element
    formattedContent = formattedContent.replace(/```([\s\S]*?)```/g, (match, code) => {
        return `
            <div class="code-block" style="position: relative;">
                <pre style="background-color: #272822; color: #f8f8f2; padding: 10px; border-radius: 5px; font-family: monospace; white-space: pre;">${code}</pre>
                <button class="copy-button" style="position: absolute; top: 5px; right: 10px; background-color: #555; color: #fff; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Copy</button>
            </div>
        `;
    });

    return formattedContent;
}


    // Add this script in your main script file or where you handle dynamic content
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('copy-button')) {
            const codeElement = event.target.previousElementSibling; // Assuming the button is placed after the <pre> element
            const codeText = codeElement.innerText.trim(); // Use innerText to preserve line breaks
            
            navigator.clipboard.writeText(codeText)
                .then(() => {
                    event.target.textContent = 'Copied!';
                    setTimeout(() => {
                        event.target.textContent = 'Copy';
                    }, 2000);
                })
                .catch(err => {
                    console.error('Failed to copy:', err);
                });
        }
    });


    </script>
    

{{-- GET MESSAGES FROM SESSION --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const sessionList = document.getElementById('session-list');
    const chatConversation = document.getElementById('users-conversation');
    const userStatus = document.querySelector('.userStatus small');

    // Load the last chat session's messages from session storage
    const savedContext = sessionStorage.getItem('currentSessionContext');
    if (savedContext) {
        const context = JSON.parse(savedContext);
        loadMessagesFromContext(context);
    }

    sessionList.addEventListener('click', function(event) {
        const target = event.target.closest('li');
        
        const sessionTitle = target.querySelector('p').textContent; // Get the session title

        // Set the session title in the header
        if (userStatus) {
            userStatus.textContent = sessionTitle;
        }

        if (!target) return;

        const sessionId = target.dataset.sessionId;
        setActiveSession(sessionId);

        axios.get(`/chat/sessions/${sessionId}/messages`)
            .then(response => {
                const messages = response.data.messages;
                const context = response.data.context;

                // Clear current chat
                chatConversation.innerHTML = '';

                // Display fetched messages
                messages.forEach(message => {
                    const { content, role, created_at } = message;

                    const messageHTML = `
                        <li class="chat-list ${role === 'user' ? 'right' : 'left'}">
                            <div class="conversation-list">
                                <div class="user-chat-content">
                                    <div class="ctext-wrap">
                                        <div class="ctext-wrap-content">
                                            <p class="mb-0 ctext-content">${formatContent(content)}</p>
                                        </div>
                                    </div>
                                    <div class="conversation-name">
                                        <small class="text-muted time">${new Date(created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                                    </div>
                                </div>
                            </div>
                        </li>
                    `;
                    chatConversation.insertAdjacentHTML('beforeend', messageHTML);
                });

                // Clear the session storage for the current session context
                sessionStorage.removeItem('currentSessionContext');

                // Store context in session storage
                sessionStorage.setItem('currentSessionContext', JSON.stringify(context));

                // Scroll to bottom of the chat
                chatConversation.scrollTop = chatConversation.scrollHeight;
            })
            .catch(error => {
                console.error(error);
            });
    });

    // Function to set the active session
    function setActiveSession(sessionId) {
        const previousActive = sessionList.querySelector('li.active');
        if (previousActive) {
            previousActive.classList.remove('active');
        }

        const newActive = sessionList.querySelector(`li[data-session-id='${sessionId}']`);
        if (newActive) {
            newActive.classList.add('active');
        }
    }

    function loadMessagesFromContext(context) {
        if (!context || !context.messages) return;

        chatConversation.innerHTML = '';
        context.messages.forEach(message => {
            const { content, role, created_at } = message;
            const messageHTML = `
                <li class="chat-list ${role === 'user' ? 'right' : 'left'}">
                    <div class="conversation-list">
                        <div class="user-chat-content">
                            <div class="ctext-wrap">
                                <div class="ctext-wrap-content">
                                    <p class="mb-0 ctext-content">${formatContent(content)}</p>
                                </div>
                            </div>
                            <div class="conversation-name">
                                <small class="text-muted time">${new Date(created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                            </div>
                        </div>
                    </div>
                </li>
            `;
            chatConversation.insertAdjacentHTML('beforeend', messageHTML);
        });
        chatConversation.scrollTop = chatConversation.scrollHeight;
    }

    // Optionally, trigger a click event on the first session to load messages on page load
    const firstSession = sessionList.querySelector('li');
    if (firstSession) {
        firstSession.click();
    }
});


</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  
{{-- DELETE SESSION --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sessionList = document.getElementById('session-list');

        // Add event listener for delete buttons
        sessionList.addEventListener('click', function (event) {
            const deleteButton = event.target.closest('.delete-session-btn');
            if (deleteButton) {
                const sessionId = deleteButton.getAttribute('data-session-id');
                deleteSession(sessionId);
            }

            const editButton = event.target.closest('.edit-session-btn');
            if (editButton) {
                const sessionId = editButton.getAttribute('data-session-id');
                editSession(sessionId);
            }
        });

        function deleteSession(sessionId) {
            axios.post(`/main/session/delete`, {
                session_id: sessionId
            })
            .then(response => {
                if (response.data.success) {
                    // Remove the session item from the UI
                    const sessionItem = document.querySelector(`li[data-session-id='${sessionId}']`);
                    if (sessionItem) {
                        sessionItem.remove();
                    }
                } else {
                    console.error('Failed to delete session');
                }
            })
            .catch(error => {
                console.error('Error deleting session:', error);
            });
        }

        function editSession(sessionId) {
            // Get the current session item and title
            const sessionItem = document.querySelector(`li[data-session-id='${sessionId}']`);
            const sessionTitle = sessionItem.querySelector('p').textContent;
            console.error(sessionTitle);

            // Open a prompt to edit the session title
            const newTitle = prompt("Edit Session Title:", sessionTitle);
            if (newTitle) {
                axios.post(`/main/session/edit`, {
                    session_id: sessionId,
                    new_title: newTitle
                })
                .then(response => {
                    if (response.data.success) {
                        // Update the session title in the UI
                        sessionItem.querySelector('p').textContent = newTitle;
                    } else {
                        console.error('Failed to edit session');
                    }
                })
                .catch(error => {
                    console.error('Error editing session:', error);
                });
            }
        }
    });
</script>

@endsection