<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ChatGPT-like Interface</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/github.min.css">
    <style>
        .message-content pre {
            background-color: #f6f8fa;
            border-radius: 6px;
            padding: 16px;
            margin: 12px 0;
            overflow-x: auto;
        }
        .message-content code {
            background-color: rgba(175,184,193,0.2);
            border-radius: 6px;
            padding: 0.2em 0.4em;
            font-size: 85%;
        }
        .message-content pre code {
            background-color: transparent;
            padding: 0;
            border-radius: 0;
        }
        .message-content table {
            border-collapse: collapse;
            width: 100%;
            margin: 12px 0;
        }
        .message-content th, .message-content td {
            border: 1px solid #dfe2e5;
            padding: 6px 13px;
        }
        .message-content tr:nth-child(2n) {
            background-color: #f6f8fa;
        }
        .message-content blockquote {
            border-left: 4px solid #dfe2e5;
            color: #6a737d;
            padding: 0 1em;
            margin: 0 0 16px 0;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex flex-col h-screen">
        <div class="flex-1 overflow-y-auto p-4" id="chat-container">
            <div class="max-w-3xl mx-auto space-y-4">
                <!-- Messages will appear here -->
            </div>
        </div>
        <div class="p-4 bg-white border-t">
            <div class="max-w-3xl mx-auto">
                <form id="chat-form" class="flex space-x-2">
                     @csrf
                    <input 
                        type="text" 
                        id="message-input" 
                        placeholder="Type your message..." 
                        class="flex-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        autocomplete="off"
                    >
                    <button 
                        type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>

   <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const chatContainer = document.getElementById('chat-container');
    let conversation = [];

    // Add a message to the chat
    function addMessage(role, content) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;
        
        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = `max-w-[85%] rounded-lg p-4 ${role === 'user' ? 'bg-blue-500 text-white' : 'bg-white border'}`;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.innerHTML = marked.parse(content);
        
        bubbleDiv.appendChild(contentDiv);
        messageDiv.appendChild(bubbleDiv);
        
        const messagesContainer = chatContainer.querySelector('.space-y-4');
        messagesContainer.appendChild(messageDiv);
        
        // Scroll to bottom
        chatContainer.scrollTop = chatContainer.scrollHeight;
        
        // Highlight code blocks
        document.querySelectorAll('pre code').forEach((block) => {
            hljs.highlightElement(block);
        });
    }

    // Handle form submission
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        // Add user message to UI
        addMessage('user', message);
        
        // Add to conversation history
        conversation.push({ role: 'user', content: message });
        
        // Clear input
        messageInput.value = '';
        
        // Create assistant message element
        const assistantDiv = document.createElement('div');
        assistantDiv.className = 'flex justify-start';
        
        const assistantBubble = document.createElement('div');
        assistantBubble.className = 'max-w-[85%] rounded-lg p-4 bg-white border';
        
        const assistantContent = document.createElement('div');
        assistantContent.className = 'message-content';
        
        assistantBubble.appendChild(assistantContent);
        assistantDiv.appendChild(assistantBubble);
        
        const messagesContainer = chatContainer.querySelector('.space-y-4');
        messagesContainer.appendChild(assistantDiv);
        
        // Scroll to bottom
        chatContainer.scrollTop = chatContainer.scrollHeight;
        
        // Stream response from OpenAI
        try {
            let fullResponse = '';
            
            // Using POST instead of GET with proper headers
            const response = await fetch('/chatss', {
                method: 'POST',
                headers: {
                    'Accept': 'text/event-stream',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    message: message,
                    conversation: conversation
                })
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            if (!response.body) {
                throw new Error('No response body received');
            }
            
            const reader = response.body.getReader();
            const decoder = new TextDecoder();
            let chunkCount = 0;
            
            while (true) {
                const { done, value } = await reader.read();
                chunkCount++;
                
                if (done) {
                    console.log(`Stream completed after ${chunkCount} chunks`);
                    break;
                }
                
                const chunk = decoder.decode(value);
                console.log(`Received chunk ${chunkCount}:`, chunk);
                
                const lines = chunk.split('\n\n').filter(line => line.trim());
                
                lines.forEach(line => {
                    if (line.startsWith('data:')) {
                        try {
                            const data = JSON.parse(line.substring(5).trim());
                            if (data.content) {
                                fullResponse += data.content;
                                assistantContent.innerHTML = marked.parse(fullResponse);
                                
                                // Scroll to bottom as content is added
                                chatContainer.scrollTop = chatContainer.scrollHeight;
                                
                                // Highlight code blocks
                                document.querySelectorAll('pre code').forEach((block) => {
                                    hljs.highlightElement(block);
                                });
                            }
                        } catch (e) {
                            console.error('Error parsing SSE data:', e);
                        }
                    }
                });
            }
            
            conversation.push({ role: 'assistant', content: fullResponse });
            console.log('Updated conversation:', conversation);
            
        } catch (error) {
            console.error('Error:', error);
            assistantContent.innerHTML = '<p class="text-red-500">An error occurred. Please try again.</p>';
            
            // Add retry button
            const retryButton = document.createElement('button');
            retryButton.className = 'mt-2 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600';
            retryButton.textContent = 'Retry';
            retryButton.onclick = () => chatForm.dispatchEvent(new Event('submit'));
            assistantContent.appendChild(retryButton);
        }
    });

    // Enable sending message with Enter key (but allow Shift+Enter for new lines)
    messageInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Auto-resize textarea as user types
    messageInput.addEventListener('input', () => {
        messageInput.style.height = 'auto';
        messageInput.style.height = `${Math.min(messageInput.scrollHeight, 150)}px`;
    });
</script>
</body>
</html>