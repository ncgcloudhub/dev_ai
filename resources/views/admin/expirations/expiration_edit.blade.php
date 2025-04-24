@extends('admin.layouts.master')
@section('title') Expiration @endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
@slot('title') Expiration Create/View @endslot
@endcomponent

<div class="row">
    <div class="col-xxl-6">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Expires On</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expirations as $exp)
                <tr>
                    <td>{{ $exp->type }}</td>
                    <td>{{ $exp->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($exp->expires_on)->format('d M Y') }}</td>
                    <td>{{ $exp->notes }}</td>
                    <td>
                        <a href="{{ route('expirations.edit', $exp->id) }}" class="btn btn-sm gradient-btn-edit">Edit</a>
                        <form action="{{ route('expirations.destroy', $exp->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm gradient-btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                    
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>


        <div class="col-xxl-6">
            <h2>Edit Expiration</h2>
            <form action="{{ route('expirations.update', $expiration->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Type</label>
                    <input type="text" name="type" class="form-control" value="{{ $expiration->type }}" required>
                </div>
                <div class="mb-3">
                    <label>Name (optional)</label>
                    <input type="text" name="name" class="form-control" value="{{ $expiration->name }}">
                </div>
                <div class="mb-3">
                    <label>Expires On</label>
                    <input type="date" name="expires_on" class="form-control" value="{{ \Carbon\Carbon::parse($expiration->expires_on)->format('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control">{{ $expiration->notes }}</textarea>
                </div>
                <button class="btn gradient-btn-save">Update</button>
                <a href="{{ route('expirations.index') }}" class="btn gradient-btn-cancel">Cancel</a>
            </form>
        </div>

</div>
@endsection


@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>


@endsection