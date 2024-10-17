@extends('admin.layouts.master')
@section('title')
@lang('translation.chat')
@endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css')}}">
@endsection
@section('content')

<!-- Custom Styles -->
<style>
    .bg-light-grey {
        background-color: rgba(var(--vz-primary-rgb),.15); /* Ash Grey Background */
        border-right: 1px solid rgba(0, 0, 0, 0.1); /* Subtle Border */
    }

    .chat-leftsidebar .btn {
        font-weight: 500;
        border-radius: 6px; /* Rounded buttons */
    }

    .chat-leftsidebar .chat-list li {
        padding: 8px 12px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .chat-leftsidebar .chat-list li.active {
        background-color: rgba(0, 123, 255, 0.1); /* Light blue for active chat */
    }

    .chat-leftsidebar .chat-list li:hover {
        background-color: rgba(0, 0, 0, 0.05); /* Soft hover effect */
    }

    .chat-leftsidebar .nav-tabs .nav-link.active {
        background-color: transparent;
        color: #007bff;
        border-bottom: 2px solid #007bff; /* Blue active tab underline */
    }

    .chat-leftsidebar .shadow-sm {
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.05); /* Subtle shadow for depth */
    }

    .auto-expand {
    max-height: 150px; /* Set maximum height for the textarea */
    resize: none; /* Disable resizing */
    overflow-y: auto; /* Allow scrolling when content exceeds max height */
    line-height: 1.5; /* Improve line spacing */
    width: 100%; /* Ensure full width of the textarea */
    padding: 10px; /* Padding for better spacing */
    }

    .chat-input-section .row.g-0 {
        display: flex;
        align-items: flex-start; /* Align items to the top */
    }

    .chat-input-section .col-auto {
        display: flex;
        align-items: center;
    }

    .chat-input-section .col {
        flex-grow: 1;
        display: flex;
        align-items: center;
    }

    textarea.form-control {
        height: auto; /* Allow height to adjust as needed */
        max-height: 150px; /* Limit the max height to avoid page expansion */
        width: 100%; /* Full width of the parent container */
        overflow-y: auto; /* Scroll when content exceeds the max height */
    }

    textarea.form-control:focus {
        outline: none; /* Optional: Remove the default focus outline */
    }

    textarea.form-control::-webkit-scrollbar {
        width: 6px; /* Custom scrollbar width */
    }

    textarea.form-control::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2); /* Custom scrollbar color */
        border-radius: 10px;
    }

    .image-container {
    display: inline-flex;
    align-items: center;
    margin-top: 10px;
}

.image-container img {
    max-width: 100px;
    margin-right: 10px;
}

.remove-btn {
    background-color: red;
    color: white;
    border: none;
    padding: 5px 8px;
    cursor: pointer;
    font-size: 12px;
}


</style>



<div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
    <div class="chat-leftsidebar border bg-light-grey" >
        <div class="px-4 pt-4 mb-4">
            <!-- AI Professional Bots Button -->
            <div id="ai-professional-tour" class="btn gradient-btn-6 d-grid mb-3" >
                <a class="text-white" href="{{route('chat')}}">AI Professional Bots</a>
            </div>
    
            <!-- New Chat Section -->
            <div class="d-flex align-items-start p-3 bg-white border rounded shadow-sm">
                <div class="flex-grow-1">
                    <h5 class="mb-0 text-primary fw-bold">New Chat</h5>
                </div>
                <div class="flex-shrink-0">
                    <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom" title="New Chat">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn gradient-btn-5 btn-sm" id="main_new_session_btn">
                            <i class="ri-add-line align-bottom"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs nav-tabs-custom nav-info nav-justified" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#chats" role="tab">
                    Chats
                </a>
            </li>
        </ul>
    
        <!-- Tab Content -->
        <div class="tab-content text-muted">
            <div class="tab-pane active" id="chats" role="tabpanel">
                <div class="chat-room-list pt-0" data-simplebar>
                    <!-- Chat Room List -->
                    <div class="chat-message-list">
                        <ul class="list-unstyled chat-list chat-user-list" id="session-list">
                            @foreach ($sessions as $item)
                                <li id="contact-id-{{ $item->session_token }}" data-name="direct-message" data-session-id="{{ $item->id }}" class="{{ $loop->first ? 'active' : '' }}">
                                    <a href="javascript: void(0);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="text-truncate mb-0">{{ $item->title }}</p>
                                            </div>
                                            <div class="d-flex">
                                                <button class="edit-session-btn btn btn-sm btn-info btn-icon waves-effect waves-light me-2" data-session-id="{{ $item->id }}">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <button class="delete-session-btn btn btn-sm btn-danger btn-icon waves-effect waves-light" data-session-id="{{ $item->id }}">
                                                    <i class="ri-delete-bin-5-line"></i>
                                                </button>
                                            </div>
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
                        <div class="row g-0 align-items-start">
                              <!-- File Selected Show (Now above the textarea) -->
                              <div id="file_name_display" class="col-12 mb-2"></div>
                              
                            <!-- Attachment Icon -->
                            <div class="col-auto">
                                <div class="chat-input-links me-2">
                                    <i id="icon" class="ri-attachment-line" style="cursor: pointer; font-size:22px;" title="Max file size is 20MB"></i>
                                    <input name="file" type="file" id="file_input" class="form-control" style="display: none;" accept=".txt,.pdf,.doc,.docx,.jpg,.jpeg,.png">
                                </div>
                            </div>
                    
                            <!-- Input field (textarea) with the send button on the right -->
                            <div class="col" id="type-message-tour">
                                <div class="chat-input-feedback">Please Enter a Message</div>
                                <textarea class="form-control chat-input bg-light border-light auto-expand" id="user_message_input" rows="1" placeholder="Type your message..." autocomplete="off"></textarea>
                            </div>
                    
                            <!-- Send Button -->
                            <div class="col-auto">
                                <div class="chat-input-links ms-2">
                                    <button type="button" id="main_send_message_btn" class="btn gradient-btn-5 chat-send waves-effect waves-light fs-13">
                                        <i class="ri-send-plane-2-fill align-bottom"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Image display should be placed below the row -->
                        
                                <div id="image_display" class="mt-2"></div>
                           
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
<script>
    // Make sure the variable is available globally
    window.seenTourSteps = {!! json_encode($seenTourSteps) !!};
</script>

    {{-- Tour --}}
    <script src="{{ URL::asset('build/libs/shepherd.js/js/shepherd.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/tour.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>


    <script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>

    <!-- fgEmojiPicker js -->
    <script src="{{ URL::asset('build/libs/fg-emoji-picker/fgEmojiPicker.js') }}"></script>

    <!-- chat init js -->
    <script src="{{ URL::asset('build/js/pages/chat.init.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Include marked.js -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <!-- Include highlight.js CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>

    <!-- Include DOMPurify for sanitizing HTML -->
    <script src="https://cdn.jsdelivr.net/npm/dompurify@2.4.0/dist/purify.min.js"></script>

    <script>
        // Initialize highlight.js
        hljs.highlightAll();
    </script>

    
    @include('admin.layouts.chat_script')

@endsection