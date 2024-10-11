<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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

        const messageInput = document.getElementById('message-input');
        const sendMessageBtn = document.getElementById('send-button');
        const fileInput = document.getElementById('file_input');
        const chatConversation = document.getElementById('users-conversation');
        const chatContainer = document.getElementById('chat-conversation');
        const fileNameDisplay = document.getElementById('file_name_display');
        const newSessionBtn = document.getElementById('main_new_session_btn');
        const imageDisplay = document.getElementById('image_display');
        let pastedImageFile = null;
        const sessionList = document.getElementById('session-list');
        let isFirstMessage = true;


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
            alert('Content copied to clipboard!'); // Show success message
        })
        .catch(err => {
            console.error('Error copying content: ', err);
        });
}


function sendMessage() {

    console.log('sendMessage called'); // Debug log

    const expert = $('#expert_id_selected').val();
    const message = messageInput.value.trim();
    const file = fileInput.files[0];

    // Check if already generating (toggle stop)
    if (sendMessageBtn.dataset.state === 'generating') {
        if (abortController) {
            abortController.abort();  // Stop the request
        }
        resetButton();  // Reset the button back to "Send"
        return;
    }

    if (!message && !file && !pastedImageFile) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('message', message);
    formData.append('expert', expert);

    if (file) {
        formData.append('file', file);
    } else if (pastedImageFile) {
        formData.append('file', pastedImageFile, 'pasted_image.png');
    }

    // Disable the button, change the text to "Stop", and store the generating state
    sendMessageBtn.disabled = false;
    sendMessageBtn.innerHTML = 'Stop';
    sendMessageBtn.dataset.state = 'generating';

    // Create an AbortController instance
    abortController = new AbortController();

    let assistantMessageContent = ''; // Accumulate assistant's message content

    fetch('/chat/reply', {
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

        const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        let usermsg = formatContent(message);
        let userMessageHTML = `<li class="chat-list right">
            <div class="conversation-list">
                <div class="user-chat-content">
                    <div class="ctext-wrap">
                        <div class="ctext-wrap-content">
                            <p class="mb-0 ctext-content">${usermsg || file?.name || 'Pasted Image'}</p>
                        </div>
                    </div>
                    <div class="conversation-name"><small class="text-muted time">${currentTime}</small></div>
                </div>
            </div>
        </li>`;
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
                    resetButton();
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
                            if (content) {
                                assistantMessageContent += content;
                                scheduleUpdate();

                                let conversationList = document.getElementById('users-conversation');
                                let lastMessage = conversationList.lastElementChild;
                                lastMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });

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
        resetButton();
    });
}



// Function to reset the send button back to its original state
function resetButton() {
    sendMessageBtn.innerHTML = '<i class="ri-send-plane-2-fill align-bottom"></i>';
    sendMessageBtn.dataset.state = 'idle';  // Reset state
    abortController = null;  // Clear the AbortController
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
    

{{-- GET/LOAD MESSAGES FROM SESSION --}}
<script>
   
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
  


<script>
   
    function selectExpert(expertId) {
    // Find the selected expert's list item based on the expert ID
    var selectedExpert = $("li[data-name='direct-message'][onclick*='" + expertId + "']");

    // Extract the expert's details from the list item
    var expertName = selectedExpert.find(".text-truncate").text();
    var expertImage = selectedExpert.find(".userprofile").attr("src");
    var expertRole = selectedExpert.data('role');  // Assuming role is passed as data attribute
    var expertSlug = selectedExpert.data('slug');  // Assuming slug is passed as data attribute

    // Update the selected expert's name in the chat topbar
    $(".user-chat-topbar .username").text(expertName);

    // Update the selected expert's role in the chat topbar
    $(".user-chat-topbar .userStatus").text(expertRole);

    // Update the selected expert's image in the chat topbar
    $(".user-chat-topbar .avatar-xs").attr("src", expertImage);

    // Clear the conversation area for the selected expert
    $('#users-conversation').empty();

    // Store the selected expert's ID (if needed for other logic)
    $('#expert_id_selected').val(expertId);

    // Update the URL to include the expert's slug
    var newUrl = `/chat/expert/${expertSlug}`;
    window.history.pushState({path: newUrl}, '', newUrl);
    }


</script>