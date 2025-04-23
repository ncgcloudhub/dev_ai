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
                </tr>
            </thead>
            <tbody>
                @foreach($expirations as $exp)
                <tr>
                    <td>{{ $exp->type }}</td>
                    <td>{{ $exp->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($exp->expires_on)->format('d M Y') }}</td>
                    <td>{{ $exp->notes }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>


        <div class="col-xxl-6">

            <h2>Add Expiration Date</h2>
            <form action="{{ route('expirations.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Type</label>
                    <input type="text" name="type" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Name (optional)</label>
                    <input type="text" name="name" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Expires On</label>
                    <input type="date" name="expires_on" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>
                <button class="btn btn-success">Save</button>
            </form>
        </div>

</div>
@endsection


@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>


@endsection