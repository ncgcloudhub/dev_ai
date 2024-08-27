@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') User @endslot
@slot('title')Package @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Package Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packageGroupedByUser as $group)
                        <tr data-bs-toggle="modal" data-bs-target="#userPackageModal{{ $group->user_id }}">
                            <td>{{ $group->user_id }}</td>
                            <td>{{ $group->user->name }}</td>
                            <td>{{ $group->package_count }}</td>
                        </tr>

                        {{-- Modal for displaying package details --}}
                <div class="modal fade" id="userPackageModal{{ $group->user_id }}" tabindex="-1" aria-labelledby="userPackageModalLabel{{ $group->user_id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="userPackageModalLabel{{ $group->user_id }}">Package Details for {{ $group->user ? $group->user->name : 'No User Found' }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if($group->user)
                                <ul>
                                    @foreach($group->user->packageHistory as $history)
                                        <li>
                                            {{ $history->package->title }} - Purchased on: {{ $history->created_at->format('d/M/Y') }}
                        
                                            @if($loop->last) <!-- This checks if it's the first item in the loop -->
                                                <br>
                                                @php
                                                $purchaseDate = \Carbon\Carbon::parse($history->created_at);
                                                $now = \Carbon\Carbon::now();
                                                
                                                // Calculate the next renewal date
                                                // Assuming renewal is based on the purchase date
                                                $nextRenewalDate = $purchaseDate->copy();
                                                while ($nextRenewalDate->lte($now)) {
                                                    $nextRenewalDate->addMonth();
                                                }
                        
                                                // Calculate days remaining until the next renewal date
                                                $daysUntilRenewal = $now->diffInDays($nextRenewalDate);
                                            @endphp
                                            Days until renewal: {{ $daysUntilRenewal }} days left
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No packages found for this user.</p>
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
           
        </div>
    </div>
</div>

@endsection
@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

<!-- Include jQuery from CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>

</script>


@endsection
