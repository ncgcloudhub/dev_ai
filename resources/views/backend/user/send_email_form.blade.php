@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') User @endslot
@slot('title')Email @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="{{route('emails.send')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="user_email">Select Users:</label>
                   
                    <select name="user_emails[]" class="form-control" data-choices multiple id="style">
                        @foreach($users as $user)
                            <option value="{{ $user->email }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <small>Select multiple users by holding Ctrl (Windows) or Command (Mac)</small>
                </div>
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="body">Message Body:</label>
                   
                </div>
                <textarea id="tinymceExample" name="body" rows="5"></textarea>
                <button type="submit" class="btn btn-primary">Send Email</button>
            </form>
            
            

        </div>
    </div>
</div>

@endsection
@section('script')


<script src="{{ URL::asset('build/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>



@endsection