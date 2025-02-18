@extends('admin.layouts.master')
@section('title') Dynamic Buttons @endsection

@section('css')
<link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('build/libs/filepond/filepond.min.css') }}" type="text/css" />
<link rel="stylesheet"
    href="{{ URL::asset('build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">
@endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
@slot('title') Dynamic Buttons @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        @php
            $groupedButtons = $buttonStyles->groupBy('button_type');
        @endphp

        @foreach($groupedButtons as $type => $buttons)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ ucfirst($type) }} Buttons</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Button Class</th>
                                <th>Selected</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($buttons as $style)
                                <tr>
                                    <td>
                                        <button type="button" class="{{ $style->class_name }} btn-lg">
                                            {{ ucfirst($type) }}
                                        </button>
                                    </td>
                                    <td>{{ $style->class_name }}</td>
                                    <td>
                                        <form action="{{ route('admin.button-styles.update') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="button_type" value="{{ $type }}">
                                            <input type="hidden" name="class_name" value="{{ $style->class_name }}">
                                            <button type="submit" class="btn btn-sm {{ $style->is_selected ? 'btn-success' : 'btn-light' }}">
                                                {{ $style->is_selected ? 'Selected' : 'Select' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $style->id }}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $style->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.button-styles.edit') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $style->id }}">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Button</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="class_name">Button Class</label>
                                                        <input type="text" name="class_name" class="form-control" value="{{ $style->class_name }}" required>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach   
    </div>

    <!-- Add New Button -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Button</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.button-styles.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label for="button_type">Button Type</label>
                            <input type="text" name="button_type" class="form-control" placeholder="e.g., Submit, Reset, Custom" required>
                        </div>
    
                        <div class="col-md-6">
                            <label for="class_name">Button Class</label>
                            <input type="text" name="class_name" class="form-control" placeholder="e.g., btn btn-success" required>
                        </div>
    
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Add Button</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
