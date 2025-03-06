@extends('user.layouts.master')
@section('title')
    @lang('translation.pricing')
@endsection
@section('content')
    @component('admin.components.breadcrumb')
    @slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
        @slot('title')
            Manage Stripe Subscription
        @endslot
    @endcomponent


    <h2>Subscription Summary</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Total Subscriptions</th>
                <th>Active Subscriptions</th>
                <th>Renew Count</th>
                <th>Package Name</th>
                <th>Package Price</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summary as $index => $data)
            <tr>
                <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#subscriptionModal{{ $index }}">
                        {{ $data['user']->name }}
                    </a>
                </td>
                <td>{{ $data['user']->email }}</td>
                <td>{{ $data['total_subscriptions'] }}</td>
                <td>{{ $data['active_subscriptions'] }}</td>
                <td>{{ $data['renew_count'] }}</td>
                <td>{{ $data['package_name'] }}</td>
                <td>{{ $data['package_price'] }}</td>
                <td>{{ $data['end_date'] }}</td>
            </tr>
            
            <!-- Subscription Details Modal -->
            <div class="modal fade" id="subscriptionModal{{ $index }}" tabindex="-1" aria-labelledby="subscriptionModalLabel{{ $index }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="subscriptionModalLabel{{ $index }}">Subscription Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Name:</strong> {{ $data['user']->name }}</p>
                            <p><strong>Email:</strong> {{ $data['user']->email }}</p>
                            <p><strong>Total Subscriptions:</strong> {{ $data['total_subscriptions'] }}</p>
                            <p><strong>Active Subscriptions:</strong> {{ $data['active_subscriptions'] }}</p>
                            <p><strong>Renew Count:</strong> {{ $data['renew_count'] }}</p>
                            <p><strong>Package Name:</strong> {{ $data['package_name'] }}</p>
                            <p><strong>Package Price:</strong> {{ $data['package_price'] }}</p>
                            <p><strong>End Date:</strong> {{ $data['end_date'] }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
        </tbody>
    </table>

@endsection


