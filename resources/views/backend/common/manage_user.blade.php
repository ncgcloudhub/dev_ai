@php
    use Carbon\Carbon;
    $users = \App\Models\User::orderBy('id', 'desc')->get();
@endphp

<div class="card-body">
    <form id="bulkActionForm" action="" method="POST">
        @csrf
        <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th scope="col">Sl.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Email Verified</th>
                    <th scope="col">Last Seen</th>
                    <th scope="col">Role</th>
                    <th scope="col">Country</th>
                    <th scope="col">IP</th>
                    <th scope="col">Block</th>
                    <th scope="col">Credits Used</th>
                    <th scope="col">Tokens Used</th>
                    <th scope="col">Change Password</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $item)
                <tr>
                    <td>
                        <input type="checkbox" name="user_ids[]" class="select-checkbox" value="{{ $item->id }}">
                    </td>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <a href="{{ route('user.details', ['id' => $item->id]) }}" 
                            class="fw-medium link-primary gradient-text-2">
                             {{ $item->name }} ({{ $item->username }})
                         </a>                         
                    </td>
                    <td>{{ $item->email }}</td>
                    <td>
                        @if ($item->email_verified_at)
                        {{ \Carbon\Carbon::parse($item->email_verified_at)->format('F j, Y, g:i a') }}

                        @else
                            -- 
                            {{-- <form action="{{ route('user.send-verification-email', ['user' => $item->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm waves-effect waves-light" onclick="return confirm('Are you sure you want to send a verification email to this user?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Send Verification Email">
                                    <i class="ri-mail-send-line"></i>
                                </button>
                            </form> --}}
                        @endif
                        
                    </td>
                    <td>
                        @if ($item->last_seen)
                            @if (Carbon::parse($item->last_seen)->diffInMinutes() < 5)
                                <span class="text-success">Active Now</span>
                                <span class="spinner-grow flex-shrink-0" role="status">

                                </span>
                            @else
                                Last seen {{ Carbon::parse($item->last_seen)->diffForHumans() }}
                            @endif
                        @else
                            Never Active
                        @endif
                    </td>
                    <td>
                        {!! $item->role == 'admin' 
                            ? '<span class="badge border border-danger text-danger">Admin</span>' 
                            : e($item->role) !!}
                    </td>
                    <td>{{ $item->country }}</td>
                    <td>{{ $item->ipaddress }}</td>
                    <td>
                        @if($item->block)
                            <span class="badge border border-danger text-danger">Blocked</span>
                        @else
                            <span class="badge border border-success text-success">Active</span>
                        @endif
                    </td>
                    <td>{{ $item->credits_used }}</td>
                    <td>{{ $item->tokens_used }}</td>
                    <td>
                        <!-- Change Password -->
                        @can('manageUser&Admin.manageUser.changePassword')
                            <a href="{{ route('admin.users.changePassword.view', ['user' => $item->id]) }}" class="text-primary d-inline-block edit-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Change Password">
                                <i class="ri-lock-2-fill fs-16"></i>
                            </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Bulk Action Buttons -->
        <div class="bulk-actions mt-3">
            @can('manageUser&Admin.manageUser.block')
                <button type="button" id="bulkBlock" class="btn btn-warning" title="Block">
                    <i class="la la-lock"></i>
                </button>
            @endcan
           
            @can('manageUser&Admin.manageUser.statusChange')
                <button type="button" id="bulkStatusChange" class="btn btn-info" title="Status Change">
                    <i class="la la-exchange-alt"></i>
                </button>
            @endcan
          
            @can('manageUser&Admin.manageUser.delete')
                <button type="button" id="bulkDelete" class="btn btn-danger" title="Delete">
                    <i class="la la-trash"></i>
                </button>
            @endcan
          
            @can('manageUser&Admin.manageUser.sendVerificationMail')
                <button type="button" id="bulkVerifyEmail" class="btn btn-success" title="Send Email Verification">
                    <i class="ri-mail-send-line"></i>
                </button>
            @endcan
          
        </div>
    </form>
</div>
@include('admin.layouts.datatables')


<script>
    document.getElementById('selectAll').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('.select-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});

let bulkDeleteButton = document.getElementById('bulkDelete');
let bulkBlockButton = document.getElementById('bulkBlock');
let bulkStatusChangeButton = document.getElementById('bulkStatusChange');
let bulkVerifyEmailButton = document.getElementById('bulkVerifyEmail');

bulkDeleteButton.addEventListener('click', function() {
    if (confirm('Are you sure you want to delete the selected users?')) {
        document.getElementById('bulkActionForm').action = '{{ route("admin.users.bulkDelete") }}';
        document.getElementById('bulkActionForm').submit();
    }
});

bulkBlockButton.addEventListener('click', function() {
    if (confirm('Are you sure you want to block/unblock the selected users?')) {
        document.getElementById('bulkActionForm').action = '{{ route("admin.users.bulkBlock") }}';
        document.getElementById('bulkActionForm').submit();
    }
});

bulkStatusChangeButton.addEventListener('click', function() {
    if (confirm('Are you sure you want to change the status of the selected users?')) {
        document.getElementById('bulkActionForm').action = '{{ route("admin.users.bulkStatusChange") }}';
        document.getElementById('bulkActionForm').submit();
    }
});

bulkVerifyEmailButton.addEventListener('click', function() {
    if (confirm('Are you sure you want to send verification emails to the selected users?')) {
        document.getElementById('bulkActionForm').action = '{{ route("admin.users.bulkVerifyEmail") }}'; // New route for bulk verification
        document.getElementById('bulkActionForm').submit();
    }
});

</script>