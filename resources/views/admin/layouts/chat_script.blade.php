<style>
    .btn i {
    pointer-events: none;  /* Prevents icon clicks from stopping the button's click */
    }

    #users-conversation {
  /* make sure its parent can handle sticky children */
  display: flex;
  flex-direction: column;
  overflow-y: auto;
  height: calc(100% - /* height of your input area */ 80px);
}
    /* styles for the AI loader bubble */
  .assistant.loading {
    display: inline-block;
    margin: 8px 0;
    padding: 10px 14px;
    background: #f1f3f5;
    border-radius: 12px;
    font-style: italic;
    color: #555;
    position: sticky;
    bottom: 0;
    margin-bottom: 1rem;
    z-index: 10;
  }

  /* simple dot‑animation */
  .assistant.loading .dot {
    animation: blink 1s infinite;
  }
  .assistant.loading .dot:nth-child(2) { animation-delay: 0.2s; }    
  .assistant.loading .dot:nth-child(3) { animation-delay: 0.4s; }   

  @keyframes blink {
    0%, 80%, 100% { opacity: 0; }
    40%          { opacity: 1; }
  }
</style>

<script>
$(document).ready(function() {
    // Function to auto-expand textarea
    $('.auto-expand').on('input', function () {
        this.style.height = 'auto'; // Reset height
        this.style.height = Math.min(this.scrollHeight, 150) + 'px'; // Limit the height to max-height
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

        function showLoadingBubble() {
        const loader = document.createElement('div');
        loader.id        = 'ai-loading';
        loader.className = 'assistant loading';
        loader.innerHTML = '🤖 AI is typing<span class="dot">.</span><span class="dot">.</span><span class="dot">.</span>';
        document.getElementById('users-conversation')
                .appendChild(loader);
        loader.scrollIntoView({ behavior: 'smooth', block: 'end' });
        }

        function removeLoadingBubble() {
        const loader = document.getElementById('ai-loading');
        if (loader) loader.remove();
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

        let selectedFiles = [];  // Store both attached and pasted images

    // Function to display images
    function displayImages() {
    imageDisplay.innerHTML = '';  // Clear current display
    fileNameDisplay.innerHTML = '';  // Clear file names

    selectedFiles.forEach((file, index) => {
        // Display the file name
        const fileNameDiv = document.createElement('div');
        fileNameDiv.textContent = "Selected File: " + file.name;
        fileNameDisplay.appendChild(fileNameDiv);

        // If the file is an image
        if (file.type.startsWith('image/')) {
            const imageUrl = URL.createObjectURL(file);

            // Create image element
            const img = document.createElement('img');
            img.src = imageUrl;
            img.style.maxWidth = '100px'; // Adjust image size as needed
            img.style.marginRight = '10px'; // Add spacing between images

            // Create remove button
            const removeBtn = document.createElement('button');
            removeBtn.textContent = 'X';
            removeBtn.classList.add('remove-btn');
            removeBtn.style.marginLeft = '5px'; // Space between image and button
            removeBtn.addEventListener('click', () => {
                // Remove the image from selectedFiles and refresh display
                selectedFiles.splice(index, 1);
                displayImages();
                if (selectedFiles.length === 0) {
                    fileInput.value = ''; // Clear the file input if no more images
                }
            });

            // Container for image and button
            const container = document.createElement('div');
            container.classList.add('image-container');
            container.style.display = 'inline-block'; // Align images horizontally
            container.style.marginBottom = '10px'; // Add spacing between rows
            container.appendChild(img);
            container.appendChild(removeBtn);

            // Append the image and remove button to image_display div
            imageDisplay.appendChild(container);
        } else {
            // For non-image files, just display the file name and a remove button
            fileNameDisplay.textContent = "Selected File: " + file.name;

            // Create remove button for file
            const removeFileBtn = document.createElement('button');
            removeFileBtn.textContent = 'X';
            removeFileBtn.classList.add('remove-btn');
            removeFileBtn.addEventListener('click', () => {
                // Clear the file input and file name display
                fileInput.value = '';
                fileNameDisplay.textContent = '';
            });

            // Append the remove button next to the file name display
            fileNameDisplay.appendChild(removeFileBtn);
        }
    });
}

// File input event handler
fileInput.addEventListener('change', function(event) {
    const files = event.target.files;
    const remainingSlots = 3 - selectedFiles.length;
    if (files.length > remainingSlots) {
        alert(`You can only attach ${remainingSlots} more images.`);
        return;
    }
     // Add selected files to the selectedFiles array
     Array.from(files).forEach(file => {
        if (selectedFiles.length < 3) {
            selectedFiles.push(file);
        }
    });
    // Update display
    displayImages();
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
    let imagePasted = false;

    for (let i = 0; i < clipboardItems.length; i++) {
        const item = clipboardItems[i];
        if (item.type.indexOf("image") !== -1) {
            const blob = item.getAsFile();
            const imageUrl = URL.createObjectURL(blob);

            // Create image element
            const img = document.createElement('img');
            img.src = imageUrl;
            img.style.maxWidth = '100px'; // Adjust image size as needed

            // Create remove button
            const removeBtn = document.createElement('button');
            removeBtn.textContent = 'X';
            removeBtn.classList.add('remove-btn');
            removeBtn.addEventListener('click', () => {
                // Clear the image display
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

            pastedImageFile = blob; // Store the pasted image
            imagePasted = true;
            break;
        }
    }

    if (imagePasted) {
        event.preventDefault(); // Prevent default paste for images only
    }
}

// Listen for paste events on messageInput
messageInput.addEventListener('paste', handleImagePaste);
            let abortController = null;  // To store the AbortController instance

           // Listen for click events on buttons
            chatConversation.addEventListener('click', function(event) {
                const speechButton = event.target.closest('.speech-btn');
                if (speechButton) {
                    const targetId = speechButton.getAttribute('data-target');
                    toggleReadAloud(speechButton, targetId); // Toggle read/stop on button click
                }

                if (event.target.closest('.copy-btn')) {
                    const targetId = event.target.closest('.copy-btn').getAttribute('data-target');
                    copyToClipboard(targetId); // Call the copyToClipboard function
                }
            });


            let currentSpeech = null;  // Global variable to store the current SpeechSynthesisUtterance
            let isReading = false;     // Flag to track if speech is ongoing

            function translateWithOpenAI(text, targetLang) {
                const apiKey = @json(config('app.openai_api_key')); // Fetch the API key from config
                const url = "https://api.openai.com/v1/chat/completions";

                const systemMessage = `You are a translation assistant. Translate the following text into ${targetLang}:`;

                const data = {
                    model: "gpt-4o", // or "gpt-3.5-turbo" for cost efficiency
                    messages: [
                        { role: "system", content: systemMessage },
                        { role: "user", content: text },
                    ],
                    temperature: 0.3, // Low temperature for accurate and consistent responses
                };

                return fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${apiKey}`,
                    },
                    body: JSON.stringify(data),
                })
                    .then((response) => response.json())
                    .then((result) => {
                        if (result.choices && result.choices.length > 0) {
                            return result.choices[0].message.content.trim();
                        }
                        throw new Error("Translation failed");
                    })
                    .catch((error) => {
                        console.error("Error translating with OpenAI:", error);
                        return "Translation failed. Please try again.";
                    });
            }


            document.addEventListener("click", function (event) {
                if (event.target && event.target.classList.contains("translate-btn")) {
                    const assistantMessageId = event.target.dataset.target;
                    const messageElement = document.getElementById(assistantMessageId);

                    if (messageElement) {
                        // Store original text if not already stored
                        if (!messageElement.dataset.originalText) {
                            messageElement.dataset.originalText = messageElement.innerHTML;
                        }

                        const originalText = messageElement.dataset.originalText; // Always use the stored original text

                        // Ask the user for the target language
                        const targetLang = prompt(
                            "Enter the target language (e.g., Bangla, Spanish, French):"
                        );

                        if (targetLang) {
                            translateWithOpenAI(originalText, targetLang)
                                .then((translatedText) => {
                                    const formattedOriginal = formatUserMessage(originalText);
                                    const formattedTranslated = formatUserMessage(translatedText);

                                    messageElement.innerHTML = `
                                        <div class="original-text">
                                            <p class="mb-0 ctext-content"><strong>Original:</strong></p>
                                            ${formattedOriginal} <!-- Always show the original text -->
                                        </div>
                                        <div class="translated-text">
                                            <p class="mb-0 ctext-content"><strong>Translated (${targetLang}):</strong></p>
                                            ${formattedTranslated} <!-- Display translated text -->
                                        </div>`;
                                })
                                .catch((error) => {
                                    console.error("Translation error:", error);
                                    messageElement.innerHTML = `<p class="mb-0 ctext-content text-danger">Translation error. Please try again later.</p>`;
                                });
                        }
                    }
                }
            });


            // Function to toggle reading aloud and stopping
            function toggleReadAloud(button, targetId) {
                if (isReading) {
                    // If currently reading, stop the speech
                    stopSpeech(button);
                } else {
                    // If not reading, start reading aloud
                    readAloud(button, targetId);
                }
            }

            // Function to read aloud the content
            function readAloud(button, targetId) {
                const contentElement = document.getElementById(targetId);
                const content = contentElement.textContent; // Get the content to read

                // Stop any ongoing speech before starting a new one
                window.speechSynthesis.cancel();

                // Create a new speech object
                currentSpeech = new SpeechSynthesisUtterance(content);
                window.speechSynthesis.speak(currentSpeech);

                // Update the button state
                button.innerHTML = '<i class="ri-stop-line"></i>'; // Change to stop icon
                button.style.backgroundColor = 'red';                  // Change background color to red
                button.style.borderColor = 'red';                      // Change border color to red
                isReading = true; // Set reading state to true

                // Handle the event when the speech ends
                currentSpeech.onend = function() {
                    stopSpeech(button);
                };
            }

            // Function to stop the ongoing speech
            function stopSpeech(button) {
                if (currentSpeech) {
                    window.speechSynthesis.cancel();  // Stop the speech
                    currentSpeech = null;  // Reset the speech object
                }

                // Update the button state back to 'read' mode
                button.innerHTML = '<i class="ri-volume-up-line"></i>'; // Change to read icon
                button.style.backgroundColor = '';                      // Reset background color
                button.style.borderColor = '';                          // Reset border color
                isReading = false; // Set reading state to false
            }

            function copyToClipboard(targetId) {
                const contentElement = document.getElementById(targetId);
                if (!contentElement) {
                    console.error('Element not found:', targetId);
                    return;
                }

                const content = contentElement.innerText; // Get the content to copy
                navigator.clipboard.writeText(content)
                    .then(() => {
                        toastr.success('Content copied to clipboard!');
                    })
                    .catch(err => {
                        console.error('Error copying content: ', err);
                    });
            }

            function sendMessage() {
    const message = messageInput.value.trim();
    const file = fileInput.files[0];

    // Check if already generating (toggle stop)
    if (sendMessageBtn.dataset.state === 'generating') {
        // Stop generation by aborting the request
        if (abortController) {
            abortController.abort();  // Stop the request
        }
        resetButton();  // Reset the button back to "Send"
        return;  // Stop execution here
    }

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

    // Disable the button, change the text to "Stop", and store the generating state
    sendMessageBtn.disabled = true; // Disable send button to prevent double sending
    sendMessageBtn.innerHTML = 'Stop';
    sendMessageBtn.dataset.state = 'generating';
    // Disable the "Enter" key in the message input
    messageInput.removeEventListener('keydown', handleEnterKey);
    // Create an AbortController instance
    abortController = new AbortController();
    showLoadingBubble();  // Show loading bubble

    let assistantMessageContent = ''; // Accumulate assistant's message content

    fetch('/main/chat/send', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        body: formData,
        signal: abortController.signal  // Attach the AbortController signal
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not OK');
        }

        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let receivedText = '';

        messageContent = formatUserMessage(message);

        const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        let userMessageHTML = `<li class="chat-list right">
            <div class="conversation-list">
                <div class="user-chat-content">
                    <div class="ctext-wrap-content text-start">
                        <p class="mb-0 ctext-content">${messageContent}</p>`;

            // Loop through attached files
            if (fileInput.files.length > 0) {
                Array.from(fileInput.files).forEach((file) => {
                    userMessageHTML += `<img src="${URL.createObjectURL(file)}" alt="Attached Image" style="max-width: 200px; max-height: 200px; margin: 5px;">`;
                });
            }

            // Handle pasted image if any
            if (pastedImageFile) {
                userMessageHTML += `<img src="${URL.createObjectURL(pastedImageFile)}" alt="Pasted Image" style="max-width: 200px; max-height: 200px; margin: 5px;">`;
            }

            userMessageHTML += `
                        </div>
                        <div class="conversation-name">
                            <small class="text-muted time">${currentTime}</small>
                        </div>
                    </div>
                </div>
            </li>`;

        // Append the generated HTML to the chat conversation
        chatConversation.insertAdjacentHTML('beforeend', userMessageHTML);

        const assistantMessageId = `assistant-message-${Date.now()}`;
        let assistantMessageHTML = `
        <li class="chat-list left">
            <div class="conversation-list">
                <div class="chat-avatar">
                    <img src="{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}" alt="">
                </div>
                <div class="user-chat-content">
                    <div class="ctext-wrap">
                        <div class="ctext-wrap-content">
                            <p id="${assistantMessageId}" class="mb-0 ctext-content"></p>
                            <button class="btn btn-success btn-sm speech-btn" data-target="${assistantMessageId}" title="Read aloud or stop">
                                <i class="ri-volume-up-line"></i> <!-- Initially a 'read' icon -->
                            </button>
                            <button class="btn btn-success btn-sm copy-btn" data-target="${assistantMessageId}" title="Copy to clipboard">
                                <i class="ri-file-copy-line"></i>
                            </button>
                              
                            <button class="btn btn-primary btn-sm regenerate-btn" 
                                    data-message-id="${assistantMessageId}" 
                                    data-original-message="${message}" 
                                    title="Regenerate">
                                <i class="ri-refresh-line"></i>
                            </button>
                            <button class="btn btn-info btn-sm translate-btn" 
                                    data-target="${assistantMessageId}" 
                                    title="Translate">
                                <i class="ri-translate"></i> Translate
                            </button>

                        </div>
                    </div>
                    <div class="conversation-name">
                        <small class="text-muted time">${currentTime}</small>
                    </div>
                </div>
            </div>
        </li>`;

        chatConversation.insertAdjacentHTML('beforeend', assistantMessageHTML);

        const assistantMessageElement = document.getElementById(assistantMessageId);

        let debounceTimer;
        const DEBOUNCE_DELAY = 100;

        function scheduleUpdate() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                updateAssistantMessage();
            }, DEBOUNCE_DELAY);
        }

        function updateAssistantMessage() {
            try {
                const formattedContent = formatContent(assistantMessageContent);
                assistantMessageElement.innerHTML = formattedContent;

                assistantMessageElement.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });

                let conversationList = document.getElementById('users-conversation');
                let lastMessage = conversationList.lastElementChild;
                lastMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });
            } catch (e) {
                console.error('Error parsing Markdown:', e);
            }
        }

        function read() {
            reader.read().then(({ done, value }) => {
                if (done) {
                    console.log('Stream complete');
                    updateAssistantMessage();
                    removeLoadingBubble();  // Remove loading bubble
                    resetButton();  // Reset the button back to "Send"
                    return;
                }

                const chunk = decoder.decode(value, { stream: true });
                receivedText += chunk;

                let lines = receivedText.split('\n');
                receivedText = lines.pop();

                for (const line of lines) {
                    if (line.startsWith('data: ')) {
                        const data = line.substring(6).trim();
                        if (data === '[DONE]') {
                            continue;
                        }

                        try {
                            const content = JSON.parse(data);
                            if (content.content) {
                                assistantMessageContent += content.content;
                                scheduleUpdate();

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
                            }
                        } catch (e) {
                            console.error('Error parsing data:', e);
                        }
                    }
                }

                // Enable the stop button once the response starts streaming
                sendMessageBtn.disabled = false;
                messageInput.addEventListener('keydown', handleEnterKey);
                read();
            });
        }

        read();

        // Clear the input after sending
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
        resetButton();  // Reset the button in case of error
    });
}

// Function to reset the send button back to its original state
function resetButton() {
    sendMessageBtn.innerHTML = '<i class="ri-send-plane-2-fill align-bottom"></i>';
    sendMessageBtn.dataset.state = 'idle';  // Reset state
    abortController = null;  // Clear the AbortController
    sendMessageBtn.disabled = false;  // Enable the button

    // Re-enable the "Enter" key in the message input
    messageInput.addEventListener('keydown', handleEnterKey);
}

// Handle "Enter" key press
function handleEnterKey(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
}

sendMessageBtn.addEventListener('click', sendMessage);
messageInput.addEventListener('keydown', handleEnterKey);
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
            const { content, role, created_at, file_path, is_image, id: messageId } = message;

             // Format the message content
             const formattedContent = formatUserMessage(content);


             let messageHTML = `
                <li class="chat-list ${role === 'user' ? 'right' : 'left'}">
                    <div class="conversation-list">
                        ${role !== 'user' ? `
                            <div class="chat-avatar">
                                <img src="{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}" alt="">
                            </div>
                        ` : ''}
                        <div class="user-chat-content">
                            <div class="ctext-wrap">
                                <div class="ctext-wrap-content text-start">
                                    ${content ? `<p id="message-content-${messageId}" class="mb-0 ctext-content">${formattedContent}</p>` : ''}
                                    ${is_image ? `
                                        <a href="javascript:void(0);" onclick="showImageModal('/storage/${file_path}')">
                                            <img src="/storage/${file_path}" alt="Image" style="max-width: 20%; height: auto;">
                                        </a>
                                    ` : ''}
                                    ${file_path && !is_image ? `<p class="mb-0 file-name">File: ${file_path.split('/').pop()}</p>` : ''}
                                    <button class="btn btn-success btn-sm speech-btn1" data-target="message-content-${messageId}" title="Read aloud or stop">
                                        <i class="ri-volume-up-line"></i> <!-- Initially a 'read' icon -->
                                    </button>
                                    <button class="btn btn-success btn-sm copy-btn1" data-target="message-content-${messageId}" title="Copy to clipboard">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>       
                    </div>
                    <div class="conversation-name">
                        <small class="text-muted time">${new Date(created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>       
                    </div>
                </li>
            `;

            chatConversation.insertAdjacentHTML('beforeend', messageHTML);
        });

        // Clear the session storage for the current session context
        sessionStorage.removeItem('currentSessionContext');

        // Store context in session storage
        sessionStorage.setItem('currentSessionContext', JSON.stringify(context));

        // Scroll to the bottom
        let conversationList = document.getElementById('users-conversation');
        let lastMessage = conversationList.lastElementChild;
        lastMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });
        
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
       
        // Scroll to the bottom of the conversation
        let conversationList = document.getElementById('users-conversation');
        let lastMessage = conversationList.lastElementChild;
        lastMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });
    }

    // Optionally, trigger a click event on the first session to load messages on page load
    const firstSession = sessionList.querySelector('li');
    if (firstSession) {
        firstSession.click();
        let conversationList = document.getElementById('users-conversation');
        let lastMessage = conversationList.lastElementChild;
        lastMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });
    }
    });

    function formatUserMessage(content) {
    // First, sanitize the input to prevent any unwanted HTML injections or XSS
    content = DOMPurify.sanitize(content);

    // Ensure any Markdown elements like *italic* or **bold** are converted properly
    const renderer = new marked.Renderer();
    
    // Override the renderer for code blocks to display as plain text
   

    marked.setOptions({
        renderer: renderer,
        breaks: true,  // Allow line breaks
        gfm: true,     // Allow GitHub Flavored Markdown
    });

    // Use marked.js to convert markdown to HTML
    let formattedContent = marked.parse(content);

    // Return the sanitized and converted content
    return formattedContent;
}


    function formatContent(content) {
    // Preprocess content to replace triple backticks with code block tags
    content = content.replace(/```(\w+)?([\s\S]*?)```/g, function(_, lang, code) {
        // If a language is provided, use it, otherwise default to 'plaintext'
        lang = lang ? lang.trim().toLowerCase() : 'plaintext';
        // Sanitize code to prevent XSS
        code = DOMPurify.sanitize(code);
        return `<pre><code class="hljs ${lang}">${code}</code></pre>`;
    });

    // Use marked.js to parse Markdown to HTML
    const renderer = new marked.Renderer();

    // Configure renderer to use highlight.js for code blocks
    renderer.code = function(code, language) {
        console.log('Language detected:', language); // Debugging line
        if (!language) {
            language = 'plaintext';
        } else {
            language = language.trim().toLowerCase();
            language = hljs.getLanguage(language) ? language : 'plaintext';
        }
        const highlighted = hljs.highlight(language, code).value;
        return `<pre><code class="hljs ${language}">${highlighted}</code></pre>`;
    };

    marked.setOptions({
        renderer: renderer,
        breaks: true, // Enable line breaks
        gfm: true,    // Enable GitHub Flavored Markdown
    });

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
    const chatConversation = document.getElementById('users-conversation');  // Added to clear chat content

    // Add event listener for delete buttons
    sessionList.addEventListener('click', function (event) {
        const deleteButton = event.target.closest('.delete-session-btn');
        if (deleteButton) {
            const sessionId = deleteButton.getAttribute('data-session-id');
            
            // Show confirmation dialog before deletion
            if (confirm("Are you sure you want to delete this session?")) {
                deleteSession(sessionId);
            }
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

                // Clear the chat conversation
                chatConversation.innerHTML = '';  // Clear the chat content
                // Check if there are any remaining sessions
                const remainingSessions = document.querySelectorAll('#session-list li');
                
                if (remainingSessions.length > 0) {
                // Click the latest session (first one in descending order)
                remainingSessions[0].click();
                } else {
                    // If no session exists, trigger the "New Chat" button
                    document.getElementById('main_new_session_btn').click();
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



    document.addEventListener('click', function(event) {
    // Check if the clicked element is the speech button
    if (event.target.closest('.speech-btn1')) {
        console.log("Speech button clicked"); // Confirm button click in console

        const targetId = event.target.closest('.speech-btn1').getAttribute('data-target');
        const messageElement = document.getElementById(targetId);
        
        // Check if the element exists and retrieve the text from its sibling `p` tag
        let messageText = '';
        if (messageElement) {
            const siblingParagraph = messageElement.nextElementSibling;
            if (siblingParagraph) {
                messageText = siblingParagraph.innerText;
            }
        }

        console.log("Target ID:", targetId); // Log the target ID
        console.log("Message Text:", messageText); // Log the retrieved message text

        const speechButton = event.target.closest('.speech-btn1');
        const isSpeaking = speechButton.classList.contains('speaking'); // Check if speech is currently playing
        console.log("Is Speaking:", isSpeaking); // Log speaking state

        if (isSpeaking) {
            console.log("Speech is currently playing, stopping speech...");
            // Stop speech if it's currently speaking
            speechButton.classList.remove('speaking');
            speechButton.innerHTML = `<i class="ri-volume-up-line"></i>`;  // Change icon back to "read aloud"
            window.speechSynthesis.cancel(); // Stop speaking
        } else {
            // Start reading aloud
            speechButton.classList.add('speaking');
            speechButton.innerHTML = `<i class="ri-volume-off-line"></i> Stop`;  // Change icon to "stop"
            
            const speech = new SpeechSynthesisUtterance(messageText);
            speech.lang = 'en-US';
            window.speechSynthesis.speak(speech);  // Start speaking
        }
    }

    // Check if the clicked element is the copy button
   if (event.target.closest('.copy-btn1')) {
        const targetId = event.target.closest('.copy-btn1').getAttribute('data-target');
        const messageElement = document.getElementById(targetId);

        if (!messageElement) return;

        // Get the entire content inside .ctext-wrap-content
        const fullMessage = messageElement.closest('.ctext-wrap-content').innerText.trim();

        console.log("Copy button clicked for Target ID:", targetId);
        console.log("Message Text to Copy:", fullMessage);

        // Copy to clipboard
        navigator.clipboard.writeText(fullMessage).then(function() {
            toastr.success('Content copied to clipboard!');
        }).catch(function(err) {
            console.error('Failed to copy text: ', err);
        });
    }
});


// REGENERATE
document.addEventListener('click', function(event) {
    if (event.target.closest('.regenerate-btn')) {
        const button = event.target.closest('.regenerate-btn');
        const messageId = button.getAttribute('data-message-id');
        const originalMessage = button.getAttribute('data-original-message'); // Fetch original message from the data attribute

        regenerateMessage(messageId, originalMessage);
    }
});

function regenerateMessage(messageId, originalMessage) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('message', originalMessage); // Add the original message
    formData.append('regenerate', true); // Indicate it's a regenerate request

    fetch('/main/chat/send', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        body: formData,
    })
    .then(response => {
        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let buffer = '';

        const messageElement = document.getElementById(messageId); // Get the message container
        if (!messageElement) {
            console.error(`Element with ID '${messageId}' not found.`);
            return;
        }

        let fullContent = ''; // Accumulate the full content here
        messageElement.innerHTML = ''; // Clear previous content before streaming

        function processChunk({ done, value }) {
            if (done) {
                console.log('Stream complete');
                // Final formatting of the complete content
                const formattedContent = formatContent(fullContent);
                messageElement.innerHTML = formattedContent;

                // Highlight code blocks within the message element
                messageElement.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });

                return;
            }

            buffer += decoder.decode(value, { stream: true });
            const lines = buffer.split('\n');
            buffer = lines.pop(); // Keep incomplete line in buffer for the next chunk

            for (const line of lines) {
                if (line.startsWith('data: ')) {
                    const data = line.slice(6).trim(); // Remove 'data: ' prefix
                    if (data === '[DONE]') {
                        console.log('Stream finished');
                        return;
                    }

                    try {
                        const json = JSON.parse(data);
                        if (json.content) {
                            fullContent += json.content; // Accumulate the content
                            // Optionally update the UI for a "live preview"
                            const formattedPreview = formatContent(fullContent);
                            messageElement.innerHTML = formattedPreview;

                            // Highlight code blocks
                            messageElement.querySelectorAll('pre code').forEach((block) => {
                                hljs.highlightElement(block);
                            });

                            // Scroll the last message into view
                            const conversationList = document.getElementById('users-conversation');
                            const lastMessage = conversationList.lastElementChild;
                            lastMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });
                        }
                    } catch (err) {
                        console.error('Error parsing JSON:', err, data);
                    }
                }
            }

            reader.read().then(processChunk);
        }

        reader.read().then(processChunk);
    })
    .catch(err => {
        console.error('Error during regenerate:', err);
        removeLoadingBubble();  // Remove loading bubble
        resetButton();
    })
    .finally(() => {
        removeLoadingBubble(); // Remove loading bubble
        isFirstMessage = false;
      });
}


</script>