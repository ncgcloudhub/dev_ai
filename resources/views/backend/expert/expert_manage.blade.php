@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@component('admin.components.breadcrumb')
@slot('li_1') Pages @endslot
@slot('title') Starter  @endslot
@endcomponent

<div class="row">
    @foreach ($experts as $item)
    <div class="col-3">
 
    <div class="card mb-1">
    <div class="card-body">
        <a class="d-flex align-items-center" href="{{ route('expert.chat',$item->id) }}" role="button">
            <div class="flex-shrink-0">
                <img src="{{ URL::asset('backend/uploads/expert/' . $item->image) }}" alt="" class="avatar-xs rounded-circle">
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="fs-15 mb-1">{{$item->expert_name}}</h6>
                <p class="text-muted mb-0">{{$item->role}}</p>
            </div>
        </a>
    </div>
</div>
     

</div>
@endforeach
</div>


@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>

    <!-- fgEmojiPicker js -->
    <script src="{{ URL::asset('build/libs/fg-emoji-picker/fg-emoji-picker.min.js') }}"></script>

    <!-- chat init js -->
    <script src="{{ URL::asset('build/js/pages/chat.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
