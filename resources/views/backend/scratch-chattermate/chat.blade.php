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
        .sidebar {
            transition: transform 0.3s ease;
        }
        .sidebar-hidden {
            transform: translateX(-100%);
        }
        .sidebar-visible {
            transform: translateX(0);
        }
        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-gray-900 text-white w-64 flex-shrink-0 fixed md:relative h-full z-10 sidebar-visible" id="sidebar">
            <div class="p-4 flex flex-col h-full">
                <button id="new-chat-btn" class="flex items-center justify-between w-full p-3 rounded-md border border-gray-700 hover:bg-gray-800 mb-4">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        New chat
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <div class="flex-1 overflow-y-auto" id="chat-history">
                    <!-- Chat history will be loaded here -->
                    <div class="space-y-2">
                        <div class="p-3 rounded-md hover:bg-gray-800 cursor-pointer flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                            </svg>
                            <span class="truncate">Loading chats...</span>
                        </div>
                    </div>
                </div>
                
                <div class="pt-2 border-t border-gray-700">
                    <div class="p-3 rounded-md hover:bg-gray-800 cursor-pointer flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        <span>Settings</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <!-- Mobile header with menu button -->
            <div class="md:hidden bg-gray-800 text-white p-4 flex items-center">
                <button id="sidebar-toggle" class="mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-xl font-semibold">Chat</h1>
            </div>
            
            <!-- Chat area -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-50" id="chat-container">
                <div class="max-w-3xl mx-auto space-y-4">
                    <!-- Messages will appear here -->
                    <div class="flex justify-center items-center h-full" id="empty-state">
                        <div class="text-center p-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <h2 class="text-xl font-semibold text-gray-600 mt-4">Start a new conversation</h2>
                            <p class="text-gray-500 mt-2">Ask anything or try one of these examples:</p>
                            <div class="mt-6 space-y-3">
                                <button class="bg-white border rounded-lg p-3 w-full text-left hover:bg-gray-100 example-prompt">
                                    "Explain quantum computing in simple terms"
                                </button>
                                <button class="bg-white border rounded-lg p-3 w-full text-left hover:bg-gray-100 example-prompt">
                                    "Got any creative ideas for a 10 year old's birthday?"
                                </button>
                                <button class="bg-white border rounded-lg p-3 w-full text-left hover:bg-gray-100 example-prompt">
                                    "How do I make an HTTP request in JavaScript?"
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Input area -->
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
                    <p class="text-xs text-gray-500 mt-2 text-center">
                       <strong>Clever Creator AI</strong> can make mistakes. Consider checking important information.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const chatContainer = document.getElementById('chat-container');
        const newChatBtn = document.getElementById('new-chat-btn');
        const chatHistory = document.getElementById('chat-history');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const emptyState = document.getElementById('empty-state');
        const examplePrompts = document.querySelectorAll('.example-prompt');
        
        let currentConversationId = null;
        let conversation = [];
        
        // Initialize the app
        document.addEventListener('DOMContentLoaded', () => {
            loadChatHistory();
            
            // Check if we have a conversation ID in the URL
            const urlParams = new URLSearchParams(window.location.search);
            const conversationId = urlParams.get('conversation');
            
            if (conversationId) {
                loadConversation(conversationId);
            }
        });
        
        // Toggle sidebar on mobile
        sidebarToggle?.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-hidden');
            sidebar.classList.toggle('sidebar-visible');
        });
        
        // Load chat history from server
        function loadChatHistory() {
            fetch('/get-chats', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error loading chat history:', data.error);
                    return;
                }
                
                chatHistory.innerHTML = `
                    <div class="space-y-2">
                        ${data.map(chat => `
                            <div class="p-3 rounded-md hover:bg-gray-800 cursor-pointer flex items-center conversation-item ${currentConversationId === chat.id ? 'bg-gray-800' : ''}" 
                                 data-id="${chat.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                </svg>
                                <span class="truncate">${chat.title}</span>
                            </div>
                        `).join('')}
                    </div>
                `;
                
                // Add click handlers to conversation items
                document.querySelectorAll('.conversation-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const id = item.getAttribute('data-id');
                        loadConversation(id);
                        
                        // Update URL
                        window.history.pushState({}, '', `?conversation=${id}`);
                        
                        // Close sidebar on mobile
                        if (window.innerWidth < 768) {
                            sidebar.classList.add('sidebar-hidden');
                            sidebar.classList.remove('sidebar-visible');
                        }
                    });
                });
            })
            .catch(error => {
                console.error('Error loading chat history:', error);
            });
        }
        
        // Load a specific conversation
        function loadConversation(id) {
            fetch(`/get-conversation/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error loading conversation:', data.error);
                    return;
                }
                
                // Update UI
                const messagesContainer = chatContainer.querySelector('.space-y-4');
                messagesContainer.innerHTML = '';
                
                data.messages.forEach(message => {
                    addMessage(message.role, message.content);
                });
                
                // Update conversation state
                currentConversationId = id;
                conversation = data.messages;
                
                // Hide empty state
                emptyState.style.display = 'none';
                
                // Update active conversation in sidebar
                document.querySelectorAll('.conversation-item').forEach(item => {
                    if (item.getAttribute('data-id') === id.toString()) {
                        item.classList.add('bg-gray-800');
                    } else {
                        item.classList.remove('bg-gray-800');
                    }
                });
                
                // Scroll to bottom
                chatContainer.scrollTop = chatContainer.scrollHeight;
            })
            .catch(error => {
                console.error('Error loading conversation:', error);
            });
        }
        
        // Start a new conversation
        function newConversation() {
            // Clear the chat container
            const messagesContainer = chatContainer.querySelector('.space-y-4');
            messagesContainer.innerHTML = '';
            
            // Reset conversation state
            currentConversationId = null;
            conversation = [];
            
            // Show empty state
            emptyState.style.display = 'block';
            
            // Update URL
            window.history.pushState({}, '', window.location.pathname);
            
            // Remove active class from all conversation items
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.remove('bg-gray-800');
            });
        }
        
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
            
            // Hide empty state if it's visible
            emptyState.style.display = 'none';
            
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
            
            // Create a new conversation if this is the first message
            if (!currentConversationId && conversation.length === 1) {
                try {
                    const response = await fetch('/save-chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            title: 'New Chat',
                            messages: conversation
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        currentConversationId = data.conversation_id;
                        loadChatHistory();
                    }
                } catch (error) {
                    console.error('Error creating new conversation:', error);
                }
            }
            
            // Stream response from OpenAI
            try {
                let fullResponse = '';
                
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
                        conversation: conversation,
                        conversation_id: currentConversationId
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
                
                // Reload chat history to update titles
                loadChatHistory();
                
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
        
        // New chat button handler
        newChatBtn.addEventListener('click', newConversation);
        
        // Example prompt handlers
        examplePrompts.forEach(prompt => {
            prompt.addEventListener('click', () => {
                messageInput.value = prompt.textContent.trim().replace(/"/g, '');
                chatForm.dispatchEvent(new Event('submit'));
            });
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