@extends('admin.layouts.master')
@section('title') Send E-Mail Form @endsection
@section('css')

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') User @endslot
@slot('title')Email @endslot
@endcomponent

<style>
    /* Ensure the dropdown appears above the TinyMCE editor */
.choices__list--dropdown {
    z-index: 1050; /* Adjust the z-index to a higher value */
}

/* Adjust z-index for TinyMCE toolbar */
.tox-tinymce {
    z-index: 1;
}

</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            @can('manageUser&Admin.sendEmail.manage')
            <div class="card-header">
                <h5 class="card-title mb-1"><a href="{{ route('manage.email.send') }}" class="btn gradient-btn-11">Manage Email</a></h5>
            </div>
            @endcan
           
            <div class="card-body">
                <form action="{{ route('emails.send') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="user_email">Select Users:</label>
                        <select name="user_id[]" class="form-control" data-choices multiple id="user_email">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                       
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject:</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="body">Message Body:</label>
                        <textarea id="tinymceExample" name="body" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn gradient-btn-3">Send Email</button>
                </form>
            </div>
            

        </div>
    </div>
</div>

@endsection

@section('script')


<script src="{{ URL::asset('build/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>


@endsection