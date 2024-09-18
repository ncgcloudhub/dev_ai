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

        fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const imageUrl = URL.createObjectURL(file);

            // Create image element
            const img = document.createElement('img');
            img.src = imageUrl;
            img.style.maxWidth = '100px'; // Adjust image size as needed

            // Create remove button
            const removeBtn = document.createElement('button');
            removeBtn.textContent = 'X';
            removeBtn.classList.add('remove-btn');
            removeBtn.addEventListener('click', () => {
                // Clear the file input and image display
                fileInput.value = '';
                imageDisplay.innerHTML = '';
            });

            // Container for image and button
            const container = document.createElement('div');
            container.classList.add('image-container');
            container.appendChild(img);
            container.appendChild(removeBtn);

            // Display the image in image_display div
            imageDisplay.innerHTML = ''; // Clear any previous image
            imageDisplay.appendChild(container);
        }
        });


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

    fetch('/main/chat/send', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not OK');
        }

        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let receivedText = '';

        const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        let userMessageHTML = `<li class="chat-list right">
            <div class="conversation-list">
                <div class="user-chat-content">
                    <div class="ctext-wrap">
                        <div class="ctext-wrap-content">
                            <p class="mb-0 ctext-content">${message || file?.name || 'Pasted Image'}</p>
                        </div>
                    </div>
                    <div class="conversation-name"><small class="text-muted time">${currentTime}</small></div>
                </div>
            </div>
        </li>`;
        chatConversation.insertAdjacentHTML('beforeend', userMessageHTML);

        const assistantMessageId = `assistant-message-${Date.now()}`;
        let assistantMessageHTML = `<li class="chat-list left">
            <div class="conversation-list">
                <div class="chat-avatar">
                    <img src="{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}" alt="">
                </div>
                <div class="user-chat-content">
                    <div class="ctext-wrap">
                        <div class="ctext-wrap-content">
                            <p id="${assistantMessageId}" class="mb-0 ctext-content"></p>
                        </div>
                    </div>
                    <div class="conversation-name"><small class="text-muted time">${currentTime}</small></div>
                </div>
            </div>
        </li>`;
        chatConversation.insertAdjacentHTML('beforeend', assistantMessageHTML);

        // Re-run syntax highlighting on the new content
        chatConversation.querySelectorAll('pre code').forEach((block) => {
            hljs.highlightElement(block);
        });


        const assistantMessageElement = document.getElementById(assistantMessageId);

        function read() {
            reader.read().then(({ done, value }) => {
                if (done) {
                    console.log('Stream complete');
                    sendMessageBtn.disabled = false;
                    sendMessageBtn.innerHTML = '<i class="ri-send-plane-2-fill align-bottom"></i>';
                    return;
                }

                const chunk = decoder.decode(value, { stream: true });
                receivedText += chunk;

                console.log('Received chunk:', chunk);
                console.log('Received text so far:', receivedText);

                let lines = receivedText.split('\n');
                receivedText = lines.pop(); 

                for (const line of lines) {
                    if (line.startsWith('data: ')) {
                        const data = line.substring(6).trim();
                        if (data === '[DONE]') {
                            return;
                        }

                        try {
                            const content = JSON.parse(data);
                            if (content) {
                                // Decode newlines
                                const textContent = content.replace(/\\n/g, '\n').replace(/\\r/g, '\r');

                                // Log content before appending
                                console.log('Content to append:', textContent);

                                // Convert newlines to HTML line breaks
                                assistantMessageElement.innerHTML += textContent.replace(/\n/g, '<br>');

                                let conversationList = document.getElementById('users-conversation');
                                let lastMessage = conversationList.lastElementChild;
                                lastMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });

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
                            }
                        } catch (e) {
                            console.error('Error parsing data:', e);
                        }
                    }
                }

                read();
            });
        }

        read();
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

        let conversationList = document.getElementById('users-conversation');
        let lastMessage = conversationList.lastElementChild;
        lastMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });

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
    // Add this script in your main script file or where you handle dynamic content
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('copy-button')) {
        // Find the nearest ancestor with class 'code-block'
        const codeBlock = event.target.closest('.code-block');
        if (codeBlock) {
            // Find the <pre> element within the 'code-block' div
            const codeElement = codeBlock.querySelector('pre');
            if (codeElement) {
                const codeText = codeElement.textContent; // Use textContent to preserve line breaks
                
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
            } else {
                console.error('No <pre> element found.');
            }
        } else {
            console.error('No code-block ancestor found.');
        }
    }
});

</script>
    

{{-- GET/LOAD MESSAGES FROM SESSION --}}
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
            const { content, role, created_at, file_path, is_image } = message;

             // Format the message content
             const formattedContent = formatContent(content);

            let messageHTML = `
                <li class="chat-list ${role === 'user' ? 'right' : 'left'}">
                    <div class="conversation-list">
                        <div class="user-chat-content">
                            <div class="ctext-wrap">
                                <div class="ctext-wrap-content">
                                    ${content ? `<p class="mb-0 ctext-content">${formattedContent}</p>` : ''}
                                    ${is_image ? `<img src="/storage/${file_path}" alt="Image" style="max-width: 20%; height: auto;">` : ''}
                                    ${file_path && !is_image ? `<p class="mb-0 file-name">File: ${file_path.split('/').pop()}</p>` : ''}
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

        
    })
    .catch(error => {
        console.error('Error fetching messages:', error);
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
            const { content, role, created_at, file_path, is_image } = message;
            const messageHTML = `
                <li class="chat-list ${role === 'user' ? 'right' : 'left'}">
                    <div class="conversation-list">
                        <div class="user-chat-content">
                            <div class="ctext-wrap">
                                <div class="ctext-wrap-content">
                                    ${is_image ? `<img src="${file_path}" alt="Image" style="max-width: 20%; height: auto;">` : `<p class="mb-0 ctext-content">${formatContent(content)}</p>`}
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
       
    }

    // Optionally, trigger a click event on the first session to load messages on page load
    const firstSession = sessionList.querySelector('li');
    if (firstSession) {
        firstSession.click();
    }
    });

    function formatContent(content) {
    // Use marked.js to parse Markdown to HTML
    const renderer = new marked.Renderer();

    // Configure renderer to use highlight.js for code blocks
    renderer.code = function(code, language) {
        // Validate the language to prevent errors
        const validLanguage = hljs.getLanguage(language) ? language : 'plaintext';
        // Highlight the code using highlight.js
        const highlighted = hljs.highlight(validLanguage, code).value;
        // Return the highlighted code block
        return `<pre><code class="hljs ${validLanguage}">${highlighted}</code></pre>`;
    };

    // Set options for marked
    marked.setOptions({
        renderer: renderer,
        breaks: true, // Enable line breaks
        gfm: true,    // Enable GitHub Flavored Markdown
    });

    // Parse the content using marked.js
    let formattedContent = marked.parse(content);

    // Sanitize the HTML to prevent XSS attacks
    formattedContent = DOMPurify.sanitize(formattedContent);

    return formattedContent;
}


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