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
            @foreach ($summary as $data)
            <tr>
                <td>{{ $data['user']->name }}</td>
                <td>{{ $data['user']->email }}</td>
                <td>{{ $data['total_subscriptions'] }}</td>
                <td>{{ $data['active_subscriptions'] }}</td>
                <td>{{ $data['renew_count'] }}</td>
                <td>{{ $data['package_name'] }}</td>
                <td>{{ $data['package_price'] }}</td>
                <td>{{ $data['end_date'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endsection


