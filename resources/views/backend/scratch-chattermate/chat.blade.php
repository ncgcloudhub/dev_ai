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
            white-space: pre-wrap;
            word-wrap: break-word;
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
        .conversation-item {
            transition: background-color 0.2s ease;
        }

        .delete-conversation-btn {
            transition: opacity 0.2s ease, color 0.2s ease;
        }

        .delete-conversation-btn:hover {
            color: #ef4444 !important; /* red-500 */
        }
        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0);
            }
        }
        /* Add these new styles to your existing style section */
        .code-block-container {
            position: relative;
        }
        .code-block-container:hover .copy-code-button {
            opacity: 1;
        }
        .copy-code-button {
            position: absolute;
            right: 8px;
            top: 8px;
            opacity: 0;
            transition: opacity 0.2s ease;
            background-color: #f6f8fa;
            border: 1px solid #e1e4e8;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 12px;
            cursor: pointer;
        }
        .copy-code-button:hover {
            background-color: #e1e4e8;
        }
        .copy-code-button.copied {
            background-color: #e6ffed;
            border-color: #2ea043;
            color: #2ea043;
        }
        
        /* New styles for textarea and input area */
        .input-area {
            position: relative;
            width: 100%;
        }
        #message-input {
            resize: none;
            min-height: 44px;
            max-height: 200px;
            overflow-y: auto;
            line-height: 1.5;
            padding-right: 60px; /* Space for the send button */
        }
        #send-button {
            position: absolute;
            right: 12px;
            bottom: 12px;
        }
        .user-message {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /* Message action buttons */
        .message-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 12px;
        }

        .message-action-btn {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 4px;
            border: 1px solid transparent;
        }

        .copy-all-button {
            background-color: #f3f4f6;
            border-color: #e5e7eb;
            color: #374151;
        }

        .copy-all-button:hover {
            background-color: #e5e7eb;
        }

        .regenerate-button {
            background-color: #3b82f6;
            color: white;
            border-color: #2563eb;
        }

        .regenerate-button:hover {
            background-color: #2563eb;
        }

        /* Existing messages might need some spacing adjustment */
        .bg-white.border {
            padding-bottom: 32px; /* Make space for buttons */
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        
        <!-- Sidebar -->
        <div class="sidebar bg-gray-900 text-white w-64 flex-shrink-0 fixed md:relative h-full z-10 sidebar-visible" id="sidebar">
            <div class="p-4 flex flex-col h-full">
                <a href="{{ Auth::check() ? (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard')) : route('login') }}" class="flex items-center justify-between w-full p-3 rounded-md border border-gray-700 hover:bg-gray-800 mb-4">
                    <span class="flex items-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 1.293a1 1 0 00-1.414 0l-7 7A1 1 0 003 9h1v7a1 1 0 001 1h4a1 1 0 001-1V13h2v3a1 1 0 001 1h4a1 1 0 001-1V9h1a1 1 0 00.707-1.707l-7-7z" />
                        </svg>
                        Dashboard
                    </span>
                </a>

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
                
                <div class="pt-2 border-t border-gray-700 relative">
                    <div id="dropdownTrigger" class="p-3 rounded-md hover:bg-gray-800 cursor-pointer flex items-center justify-between">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 01.993.883L11 3v1.071A7.002 7.002 0 0116.938 9H18a1 1 0 01.117 1.993L18 11h-1.071A7.002 7.002 0 0111 16.938V18a1 1 0 01-1.993.117L9 18v-1.071A7.002 7.002 0 013.062 11H2a1 1 0 01-.117-1.993L2 9h1.071A7.002 7.002 0 019 3.062V2a1 1 0 011-1zm0 5a3 3 0 100 6 3 3 0 000-6z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-200">
                                {{ $selectedModel ? \App\Models\AISettings::where('openaimodel', $selectedModel)->value('displayname') : 'Select AI Model' }}
                            </span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <form id="modelForm" action="{{ route('select-model') }}" method="POST" class="absolute bottom-full mb-2 left-0 w-full z-20 hidden" id="modelDropdown">
                        @csrf
                        <ul class="bg-gray-800 text-white rounded-md border border-gray-700 overflow-hidden shadow-lg">
                            @foreach ($aiModels as $model)
                                <li>
                                    <a href="#" 
                                    data-model="{{ $model['value'] }}"
                                    class="dropdown-item block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 {{ trim($selectedModel) === trim($model['value']) ? 'bg-gray-700 font-semibold' : '' }}">
                                        {{ $model['label'] }}
                                        @if(trim($selectedModel) === trim($model['value']))
                                            <span class="ml-2 text-green-400">🗸</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <input type="hidden" name="aiModel" id="aiModelInput" value="{{ $selectedModel }}">
                    </form>
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
                    <form id="chat-form" class="relative">
                        @csrf
                        <div class="input-area">
                            <textarea 
                                id="message-input" 
                                placeholder="Type your message..." 
                                class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                autocomplete="off"
                                rows="1"
                            ></textarea>
                            <button 
                                type="submit" 
                                id="send-button"
                                class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
                                disabled
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                           <strong>Clever Creator AI</strong> can make mistakes. Consider checking important information.
                        </p>
                    </form>
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
        const sendButton = document.getElementById('send-button');
        
        let currentConversationId = null;
        let conversation = [];
        let isWaitingForResponse = false;
        
        // Initialize the app
        document.addEventListener('DOMContentLoaded', () => {
            loadChatHistory();
            
            // Check if we have a conversation ID in the URL
            const urlParams = new URLSearchParams(window.location.search);
            const conversationId = urlParams.get('conversation');
            
            if (conversationId) {
                loadConversation(conversationId);
            }
            
            // Focus the textarea on page load
            messageInput.focus();
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
                            <div class="p-3 rounded-md hover:bg-gray-800 cursor-pointer flex items-center justify-between group conversation-item ${currentConversationId === chat.id ? 'bg-gray-800' : ''}" 
                                data-id="${chat.id}">
                                <div class="flex items-center flex-1 min-w-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="truncate">${chat.title}</span>
                                </div>
                                <button class="delete-conversation-btn opacity-0 group-hover:opacity-100 text-gray-400 hover:text-white transition-opacity" 
                                        data-id="${chat.id}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
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
            
            // Focus the input
            messageInput.focus();
        }
        
        // Add a message to the chat
       function addMessage(role, content) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;
        
        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = `max-w-[85%] rounded-lg p-4 ${role === 'user' ? 'bg-blue-500 text-white' : 'bg-white border'} relative`;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = role === 'user' ? 'user-message' : 'message-content';
        
        if (role === 'user') {
            // For user messages, preserve whitespace and newlines
            contentDiv.textContent = content;
        } else {
            // For assistant messages, process with Markdown
            contentDiv.innerHTML = marked.parse(content);
            
            // Create action buttons container
            const actionsDiv = document.createElement('div');
            actionsDiv.className = 'message-actions';
            
            // Copy entire message button
            const copyButton = document.createElement('button');
            copyButton.className = 'message-action-btn copy-all-button';
            copyButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                </svg>
                Copy
            `;

            // Copy full message handler
            copyButton.addEventListener('click', () => {
                const textToCopy = contentDiv.textContent;
                navigator.clipboard.writeText(textToCopy).then(() => {
                    copyButton.innerHTML = 'Copied!';
                    setTimeout(() => {
                        copyButton.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                            </svg>
                            Copy
                        `;
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy text:', err);
                    copyButton.textContent = 'Failed to copy!';
                });
            });

            actionsDiv.appendChild(copyButton);
            bubbleDiv.appendChild(actionsDiv);

            // Add copy buttons to code blocks
            addCopyButtonsToCodeBlocks();
            
            // Highlight code blocks
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightElement(block);
            });
        }
        
        bubbleDiv.appendChild(contentDiv);
        messageDiv.appendChild(bubbleDiv);
        
        const messagesContainer = chatContainer.querySelector('.space-y-4') || document.createElement('div');
        if (!chatContainer.querySelector('.space-y-4')) {
            messagesContainer.className = 'space-y-4';
            chatContainer.appendChild(messagesContainer);
        }
        messagesContainer.appendChild(messageDiv);
        
        // Scroll to bottom
        chatContainer.scrollTop = chatContainer.scrollHeight;
        
    }

        // Function to add copy buttons to all code blocks
        function addCopyButtonsToCodeBlocks() {
            document.querySelectorAll('pre').forEach((preElement) => {
                // Skip if we've already added a copy button
                if (preElement.querySelector('.copy-code-button')) return;
                
                // Create container div
                const container = document.createElement('div');
                container.className = 'code-block-container';
                
                // Wrap the pre element in the container
                preElement.parentNode.insertBefore(container, preElement);
                container.appendChild(preElement);
                
                // Create and add the copy button
                const copyButton = document.createElement('button');
                copyButton.className = 'copy-code-button';
                copyButton.textContent = 'Copy';
                copyButton.title = 'Copy to clipboard';
                container.appendChild(copyButton);
                
                // Get the code content
                const code = preElement.querySelector('code')?.innerText || preElement.innerText;
                
                // Add click event
                copyButton.addEventListener('click', () => {
                    navigator.clipboard.writeText(code).then(() => {
                        copyButton.textContent = 'Copied!';
                        copyButton.classList.add('copied');
                        setTimeout(() => {
                            copyButton.textContent = 'Copy';
                            copyButton.classList.remove('copied');
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy text: ', err);
                        copyButton.textContent = 'Failed';
                        setTimeout(() => {
                            copyButton.textContent = 'Copy';
                        }, 2000);
                    });
                });
            });
        }
        
        // Auto-resize textarea based on content
        function resizeTextarea() {
            messageInput.style.height = 'auto';
            messageInput.style.height = `${Math.min(messageInput.scrollHeight, 200)}px`;
        }
        
        // Handle form submission
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message || isWaitingForResponse) return;
            
            // Hide empty state if it's visible
            emptyState.style.display = 'none';
            
            // Add user message to UI
            addMessage('user', message);
            
            // Add to conversation history
            conversation.push({ role: 'user', content: message });
            
            // Clear input and reset textarea height
            messageInput.value = '';
            resizeTextarea();
            sendButton.disabled = true;
            
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
                            messages: []
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
                isWaitingForResponse = true;
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

                                    // Add copy buttons to any new code blocks that appeared
                                    addCopyButtonsToCodeBlocks();
                                    
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
            } finally {
                isWaitingForResponse = false;
                messageInput.focus();
            }
        });
        
        // New chat button handler
        newChatBtn.addEventListener('click', newConversation);
        
        // Example prompt handlers
        examplePrompts.forEach(prompt => {
            prompt.addEventListener('click', () => {
                messageInput.value = prompt.textContent.trim().replace(/"/g, '');
                resizeTextarea();
                sendButton.disabled = false;
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
            resizeTextarea();
            sendButton.disabled = messageInput.value.trim() === '';
        });

        // Handle paste events to maintain formatting
        messageInput.addEventListener('paste', (e) => {
            // Let the paste happen first
            setTimeout(() => {
                resizeTextarea();
                sendButton.disabled = messageInput.value.trim() === '';
            }, 0);
        });

        // Handle conversation deletion
        document.addEventListener('click', async (e) => {
            if (e.target.closest('.delete-conversation-btn')) {
                const button = e.target.closest('.delete-conversation-btn');
                const id = button.getAttribute('data-id');
                
                if (confirm('Are you sure you want to delete this conversation?')) {
                    try {
                        const response = await fetch(`/delete-conversation/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            // Remove the conversation from the sidebar
                            button.closest('.conversation-item').remove();
                            
                            // If we deleted the current conversation, start a new one
                            if (currentConversationId === id) {
                                newConversation();
                            }
                        } else {
                            alert('Failed to delete conversation');
                        }
                    } catch (error) {
                        console.error('Error deleting conversation:', error);
                        alert('Error deleting conversation');
                    }
                }
            }
        });
    </script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.getElementById('dropdownTrigger');
    const dropdown = document.getElementById('modelForm');
    const dropdownItems = document.querySelectorAll('.dropdown-item[data-model]');
    const modelInput = document.getElementById('aiModelInput');
    const modelForm = document.getElementById('modelForm');

    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });

    dropdownItems.forEach(function(item) {
        item.addEventListener('click', function(event) {
            event.preventDefault();
            const model = this.getAttribute('data-model');
            modelInput.value = model;
            modelForm.submit();
        });
    });

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target) && !trigger.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});
</script>


</body>
</html>