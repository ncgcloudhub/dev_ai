@extends('admin.layouts.master')
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
            </tr>
        </thead>
        <tbody>
            @foreach ($summary as $index => $data)
            <tr>
                <td>{{ $data['user']->name }}</td>
                <td>{{ $data['user']->email }}</td>
                <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#userModal{{ $index }}">
                        {{ $data['total_subscriptions'] }}
                    </a>
                </td>
            </tr>
        
            <!-- Modal -->
            <div class="modal fade" id="userModal{{ $index }}" tabindex="-1" aria-labelledby="userModalLabel{{ $index }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userModalLabel{{ $index }}">
                                Subscriptions for {{ $data['user']->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @if(count($data['subscriptions']))
                                <ul class="list-group">
                                    @foreach($data['subscriptions'] as $sub)
                                        <li class="list-group-item d-flex justify-content-between flex-column align-items-start mb-2">
                                            <div>
                                                <strong>{{ $sub['package_name'] }}</strong><br>
                                                Price: {{ $sub['package_price'] }}<br>
                                                Purchased: {{ $sub['purchased_on'] }}<br>
                                                Renewal: {{ $sub['renewal_date'] }}
                                            </div>
                                            <div class="mt-2 d-flex flex-wrap gap-2">
                                                @if($sub['invoice_number'])
                                                    <span class="badge bg-primary">Invoice: {{ $sub['invoice_number'] }}</span>
                                                @endif
                                                @if($sub['invoice_url'])
                                                    <a href="{{ $sub['invoice_url'] }}" target="_blank" class="btn btn-sm btn-success">
                                                        Download Invoice
                                                    </a>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No subscriptions found.</p>
                            @endif
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


@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection