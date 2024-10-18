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
                                data-slug="{{ $item->slug }}"  
                                data-id="{{ $item->id }}"  
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
                                    <div id="image_display"></div>
                                    <textarea class="form-control chat-input bg-light border-light auto-expand" id="message-input" placeholder="Type your message..." autocomplete="off"></textarea>
                                </div>
                                <div class="col-auto">
                                    <div class="chat-input-links ms-2">
                                        <div class="links-list-item">
                                            <button type="button" id="send-button" class="btn gradient-btn-5 chat-send waves-effect waves-light fs-13">
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

    <script src="{{ URL::asset('build/libs/fg-emoji-picker/fgEmojiPicker.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/chat.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>


    

    <!-- fgEmojiPicker js -->
    <script src="{{ URL::asset('build/libs/fg-emoji-picker/fgEmojiPicker.js') }}"></script>

    <!-- chat init js -->
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



    @include('admin.layouts.expert_chat_script')

@endsection
