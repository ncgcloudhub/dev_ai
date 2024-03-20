@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="/assets/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/libs/swiper/swiper.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/libs/quill/quill.min.css" rel="stylesheet" type="text/css" />
<meta name="csrf-token" content="{{ csrf_token() }}">


@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Dashboards @endslot
@slot('title') Dashboard @endslot
@endcomponent

<div class="row">
   
    <div id="chat-container">
        <div id="chat-messages"></div>
        <input type="text" id="message-input">
        <button id="send-button">Send</button>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#send-button').on('click', function () {
                sendMessage();
            });

            $('#message-input').on('keypress', function (e) {
                if (e.which === 13) {
                    sendMessage();
                }
            });

    
    function sendMessage() {
    var message = $('#message-input').val();
    // $('#message-input').val('');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'POST',
        url: '/chat',
        data: { message: message },
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
            // Log the response to the console
            console.log("Inside Success"+ response); 

            // Append user message and AI response to the chat container
            $('#chat-messages').append('<p>User: ' + message + '</p>');
            $('#chat-messages').append('<p>Assistant: ' + response + '</p>');
        },
        error: function (error) {
            console.error(error);
        }
    });
}

        });
    </script>
 
</div>
@endsection
@section('script')
<script src="{{ URL::asset('/assets/libs/@ckeditor/@ckeditor.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/quill/quill.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/form-editor.init.js') }}"></script>
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jsvectormap/jsvectormap.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/swiper/swiper.min.js')}}"></script>
<!-- dashboard init -->
<script src="{{ URL::asset('/assets/js/pages/dashboard-ecommerce.init.js') }}"></script>
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>




@endsection
