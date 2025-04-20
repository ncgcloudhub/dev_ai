@extends('admin.layouts.master')
@section('title')
    @lang('translation.pricing')
@endsection
@section('content')
    @component('admin.components.breadcrumb')
    @slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
        @slot('title')
            Pricing
        @endslot
    @endcomponent

<form action="{{ route('stripe.balance.report') }}" method="GET">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" required>
    
    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" required>
    
    <button type="submit" class="btn gradient-btn-add">Generate Report</button>
</form>

<h2>Stripe Balance Report ({{ date('Y-m-d', $startDate) }} - {{ date('Y-m-d', $endDate) }})</h2>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions->data as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>${{ number_format($transaction->amount / 100, 2) }}</td>
                <td>{{ $transaction->type }}</td>
                <td>{{ $transaction->status }}</td>
                <td>{{ date('Y-m-d', $transaction->created) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
