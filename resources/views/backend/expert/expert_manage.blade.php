@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('chat')}}">Expert</a> @endslot
@slot('title') All Experts  @endslot
@endcomponent

<div class="row">
    @foreach ($experts as $item)
    <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3"> <!-- Adjust column widths for mobile and larger screens -->
        <div class="card">
            <div class="card-body">
                <a class="d-flex align-items-center" href="{{ route('expert.chat', $item->slug) }}" role="button">
                    <div class="flex-shrink-0">
                        <img src="{{ URL::asset('backend/uploads/expert/' . $item->image) }}" alt="" class="avatar-xs rounded-circle">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-15 mb-1">{{$item->expert_name}}</h6>
                        <p class="text-muted mb-0">{{$item->role}}</p>
                    </div>
                </a>
                <!-- Add Edit and Delete icons -->
                <div class="mt-3 d-flex justify-content-end gap-2">
                    <a href="{{ route('expert.edit', $item->slug) }}" class="text-primary d-inline-block edit-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                        <i class="ri-pencil-fill fs-16"></i>
                    </a>
                    <a href="{{ route('expert.delete', $item->id) }}" onclick="return confirm('Are you sure you want to delete this expert?')" class="text-danger d-inline-block remove-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                        <i class="ri-delete-bin-5-fill fs-16"></i>
                    </a>
                    
                </div>
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
@endsection
