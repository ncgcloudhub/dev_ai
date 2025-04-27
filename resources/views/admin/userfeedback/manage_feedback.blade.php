@extends('admin.layouts.master')
@section('title') User Feedback @endsection
@section('content')

@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('newsletter.manage')}}">Feedback</a> @endslot
@slot('title') Manage @endslot
@endcomponent

<div class="container">
    <h3 class="gradient-text-1-bold mb-3">All User Feedback</h3>

    @if ($feedbacks->count())
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>User Message</th>
                        <th>Date Submitted</th>
                        <th>Admin Status</th>
                        <th>Admin Comment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($feedbacks as $index => $feedback)
                        <tr>
                            <form action="{{ route('admin.feedback.update', $feedback->id) }}" method="POST">
                                @csrf
                                @method('POST')
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $feedback->user->name ?? 'N/A' }}</td>
                                <td>{{ $feedback->user->email ?? 'N/A' }}</td>
                                <td><span class="badge badge-gradient-purple">{{ ucfirst($feedback->type) }}</span></td>
                                <td>{{ $feedback->message }}</td>
                                <td>{{ $feedback->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <select name="admin_status" class="form-select form-select-sm">
                                        <option value="">Select</option>
                                        <option value="pending" {{ $feedback->admin_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $feedback->admin_status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="resolved" {{ $feedback->admin_status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea name="admin_comment" class="form-control form-control-sm" rows="2" placeholder="Enter comment">{{ $feedback->admin_comment }}</textarea>
                                </td>                                
                                <td>
                                    <button type="submit" class="btn gradient-btn-save btn-sm" title="Update"><i class="{{$buttonIcons['save']}}"></i></button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            No feedback has been submitted yet.
        </div>
    @endif
</div>

@include('admin.layouts.datatables')
@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
