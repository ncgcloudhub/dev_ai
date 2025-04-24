@extends('admin.layouts.master')
@section('title') My Feedback @endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('newsletter.manage')}}">Feedback</a> @endslot
@slot('title')Manage @endslot
@endcomponent

<div class="container">
    <h3 class="gradient-text-1-bold mb-3">My Feedback</h3>

    @if ($feedbacks->count())
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Message</th>
                        <th>Date Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($feedbacks as $index => $feedback)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-primary">{{ ucfirst($feedback->type) }}</span></td>
                            <td>{{ $feedback->message }}</td>
                            <td>{{ $feedback->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            You haven't submitted any feedback yet.
        </div>
    @endif
</div>
@include('admin.layouts.datatables')

@endsection

@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection