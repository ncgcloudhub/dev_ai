@extends('admin.layouts.master')
@section('title')
@lang('translation.chat')
@endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')


<div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
    <div class="chat-leftsidebar border">
        <div class="px-4 pt-4">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <h5 class="mb-1">Chats</h5>
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
                        <div class="flex-grow-1">
                            <h4 class="mb-0 fs-11 text-muted text-uppercase">Direct Messages</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom" title="New Message">

                                <!-- Button trigger modal -->
                                <button id="new-message-button" type="button" class="btn btn-soft-primary btn-sm">
                                    <i class="ri-add-line align-bottom"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="chat-message-list">

                        <ul class="list-unstyled chat-list chat-user-list" id="userList">
                            @foreach ($experts as $item)
                            <li class="{{ $expert_selected_id == $item->id ? 'active' : '' }}" 
                                data-name="direct-message" 
                                data-role="{{ $item->role }}" 
                                onclick="selectExpert('{{ $item->id }}')">  <!-- Use expert ID in onclick -->
                                <a href="javascript: void(0);">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 chat-user-img online align-self-center me-2 ms-0">       
                                            <div class="avatar-xxs">  
                                                <img src="{{ URL::asset('backend/uploads/expert/' . $item->image) }}" 
                                                     class="rounded-circle img-fluid userprofile" 
                                                     alt="">
                                                <span class="user-status"></span>                        
                                            </div>                  
                                        </div>                 
                                        <div class="flex-grow-1 overflow-hidden">    
                                            <p class="text-truncate mb-0">{{ $item->expert_name }}</p> 
                                        </div>
                                    </div> 
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        
                        <input type="hidden" name="expert_id_selected" id="expert_id_selected" value="{{$expert_selected_id}}">
                    </div>

 
                    <!-- End chat-message-list -->
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
                                                    <img src="{{ URL::asset('backend/uploads/expert/' . $expert_selected->image) }}" class="rounded-circle avatar-xs" alt="">
                                                    <span class="user-status"></span>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate mb-0 fs-16"><a class="text-reset username">{{ $expert_selected->expert_name}}</a></h5>
                                                    <p class="text-truncate text-muted fs-14 mb-0 userStatus"><small>{{ $expert_selected->role}}</small></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             
                            </div>

                        </div>
                        <!-- end chat user head -->
                        <div class="chat-conversation p-3 p-lg-4 " id="chat-conversation" data-simplebar>
                            {{-- <div id="elmLoader">
                                <div class="spinner-border text-primary avatar-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div> --}}
                            <ul class="list-unstyled chat-conversation-list" id="users-conversation">

                            </ul>
                            <!-- end chat-conversation-list -->
                        </div>
                        <div class="alert alert-warning alert-dismissible copyclipboard-alert px-4 fade show " id="copyClipBoard" role="alert">
                            Message copied
                        </div>
                    </div>

                    <div class="position-relative" id="channel-chat">                       
                        <!-- end chat user head -->
                        <div class="chat-conversation p-3 p-lg-4" id="chat-conversation" data-simplebar>
                        </div>
                    </div>
                    <!-- end chat-conversation -->


                    <div class="chat-input-section p-3 p-lg-4">

                            <div class="row g-0 align-items-center">
                               {{-- File Selected Show --}}
                               <div id="file_name_display"></div>

                               
                               <div class="col-auto">
                                   <div class="chat-input-links me-2">
                                       <div class="links-list-item"> 
                                       {{-- Attachement Icon --}}
                                       
                                       <i id="icon" class="ri-attachment-line" style="cursor: pointer; font-size:22px;" title="Max file size is 20MB"></i>
                                      
                                       <input name="file" type="file" id="file_input" class="form-control" style="display: none;" accept=".txt,.pdf,.doc,.docx,.jpg,.jpeg,.png">
                                           
                                       </div>
                                   </div>
                               </div>

                                <div class="col">
                                    <div class="chat-input-feedback">
                                        Please Enter a Message
                                    </div>
                                    <textarea class="form-control chat-input bg-light border-light auto-expand" id="message-input" placeholder="Type your message..." autocomplete="off"></textarea>
                                </div>
                                <div class="col-auto">
                                    <div class="chat-input-links ms-2">
                                        <div class="links-list-item">
                                            <button type="button" id="send-button" class="btn btn-success chat-send waves-effect waves-light fs-13">
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


   <script>
$(document).ready(function() {
    // Function to auto-expand textarea
    $('.auto-expand').on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Function to send message when Enter key is pressed
    $('.auto-expand').on('keydown', function(e) {
        if (e.which == 13 && !e.shiftKey) { // Check if Enter is pressed without Shift
            e.preventDefault(); // Prevent the default Enter behavior (adding a new line)
            sendMessage(); // Call the function to send the message
        }
    });

    // Attach click event to send button
    $('#send-button').on('click', function() {
        sendMessage();
    });
});
    
// NEW CONVERSATION
// Event listener for the "New Message" button click
$('#new-message-button').click(function() {
        // Example message and user data (you can customize this)
        var message = "This is a new message";
        var userName = "John Doe";
        var userImage = "path/to/user/image.jpg";
        var timestamp = "09:08 am";

        // Construct the HTML for the new message
        var newMessageHTML = `<li id="" data-name="channel" class="active">
                                <a href="javascript: void(0);" class="unread-msg-user">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 chat-user-img align-self-center me-2 ms-0">
                                            <div class="avatar-xxs">
                                                <div class="avatar-title bg-light rounded-circle text-body">#</div> 
                                            </div>   
                                        </div>   
                                        <div class="flex-grow-1 overflow-hidden"> 
                                            <p class="text-truncate mb-0">Landing Design</p>     
                                        </div>   
                                            <div>
                                                <div class="flex-shrink-0 ms-2"><span class="badge bg-dark-subtle text-body rounded p-1">7</span>
                                                </div>
                                            </div>
                                    </div>           
                                </a>        
                             </li>`;

        // Append the new message to the conversation list
        $('#channelList').append(newMessageHTML);
    });
// NEW CONVERSTATION END


        
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
            // Handle bold text marked with **
            if (line.includes('**')) {
                line = line.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            }

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

    function displayMessage(message, reply, image) {
    let formattedMessage = formatContent(message);
    let formattedReply = formatContent(reply);

    $('#users-conversation').append(`
        <li class="chat-list right"> 
            <div class="conversation-list">
                <div class="user-chat-content">
                    <div class="ctext-wrap">
                        <div class="ctext-wrap-content">
                            <p class="mb-0 ctext-content">${formattedMessage}</p>
                        </div>
                        <div class="dropdown align-self-start message-box-drop"> 
                            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ri-more-2-fill"></i></a> 
                            <div class="dropdown-menu"> 
                             
                                <a class="dropdown-item copy-message" href="#"><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>
                               
                            </div>
                        </div>
                    </div>
                    <div class="conversation-name">
                        <span class="d-none name">Frank Thomas</span>
                        <small class="text-muted time">09:08 am</small> 
                        <span class="text-success check-message-icon"><i class="bx bx-check-double"></i></span>
                    </div>
                </div>    
            </div>    
        </li>
        <li class="chat-list left">   
            <div class="conversation-list">
                <div class="chat-avatar">
                    <img src="{{ URL::asset('backend/uploads/expert/') }}/${image}" alt="">
                </div>
                <div class="user-chat-content">
                    <div class="ctext-wrap">
                        <div class="ctext-wrap-content">
                            <p class="mb-0 ctext-content">${formattedReply}</p>
                        </div>
                        <div class="dropdown align-self-start message-box-drop">                
                            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ri-more-2-fill"></i></a>     
                            <div class="dropdown-menu">
                              
                                <a class="dropdown-item copy-message" href="#"><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>   
                                
                            </div>    
                        </div>
                    </div>
                    <div class="conversation-name">
                        <span class="d-none name">Lisa Parker</span>
                        <small class="text-muted time">09:07 am</small> 
                        <span class="text-success check-message-icon"><i class="bx bx-check-double"></i></span>
                    </div>
                </div>              
            </div>           
        </li>
    `);

    // Scroll to the last message
    let conversationList = document.getElementById('users-conversation');
    let lastMessage = conversationList.lastElementChild;
    lastMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });
}



function sendMessage() {
    console.log('sendMessage called'); // Debug log

    var message = $('#message-input').val();
    if (!message.trim()) return; // Prevent empty messages

    var expert = $('#expert_id_selected').val();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Disable the send button to prevent multiple clicks
    $('#send-button').prop('disabled', true);

    $.ajax({
        type: 'POST',
        url: '/chat/reply',
        data: { message: message, expert: expert },
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
            var reply = response.content;
            var image = response.expert_image;

            displayMessage(message, reply, image);

            // Clear the input field
            $('#message-input').val('');
        },
        error: function (error) {
            console.error(error);
        },
        complete: function() {
            // Re-enable the send button after the request completes
            $('#send-button').prop('disabled', false);
        }
    });
}



function selectExpert(expertId) {
    // Find the selected expert's list item based on the expert ID
    var selectedExpert = $("li[data-name='direct-message'][onclick*='" + expertId + "']");

    // Extract the expert's details from the list item
    var expertName = selectedExpert.find(".text-truncate").text();
    var expertImage = selectedExpert.find(".userprofile").attr("src");
    var expertRole = selectedExpert.data('role');  // Assuming role is passed as data attribute

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
}

</script>

@section('script')
<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>

    <script src="{{ URL::asset('build/libs/fg-emoji-picker/fgEmojiPicker.js') }}"></script>

    <script src="{{ asset('build/js/pages/chat.init.js') }}"></script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
