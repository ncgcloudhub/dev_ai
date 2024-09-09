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