@php
    $users = \App\Models\User::orderBy('id', 'desc')->get();
@endphp

<div class="card-body">
    <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
        <thead>
            <tr>
                <th scope="col">Sl.</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Email Verified</th>
                <th scope="col">Role</th>
                <th scope="col">Credits Used</th>
                <th scope="col">Tokens Used</th>
                <th scope="col">Action</th>
                <th scope="col">Block</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <a href="{{ route('user.details',$item->id) }}" class="fw-medium link-primary">{{$item->name}}({{$item->username}})</a>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-2">
                            <img src="{{ $item->photo ? asset('backend/uploads/user/' . $item->photo) : asset('build/images/users/avatar-1.jpg') }}" alt="" class="avatar-xs rounded-circle" />
                        </div>
                        <div class="flex-grow-1">{{$item->email}}</div>
                    </div>
                </td>

                
                            <td>
                                @if ($item->email_verified_at)
                                {{ \Carbon\Carbon::parse($item->email_verified_at)->format('F j, Y, g:i a') }}

                                @else
                                    -- 
                                    <form action="{{ route('user.send-verification-email', ['user' => $item->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm waves-effect waves-light" onclick="return confirm('Are you sure you want to send a verification email to this user?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Send Verification Email">
                                            <i class="ri-mail-send-line"></i>
                                        </button>
                                    </form>
                                @endif
                                
                            </td>

                            @if ($item->role == 'admin')
                           
                            <td> <span class="badge border border-danger text-danger">{{ $item->role }}</span></td>
                            @else
                            <td>{{ $item->role }}</td>
                            @endif

                            <td>{{ $item->credits_used }}</td>
                            <td>{{ $item->tokens_used }}</td>
             
              

                @if ($item->status == 'active')
                    <td>
                        <div class="form-check form-switch form-switch-md" dir="ltr">
                            <input type="checkbox" class="form-check-input active_button" id="customSwitchsizemd" data-user-id="{{ $item->id }}" checked>
                            <label class="form-check-label" for="customSwitchsizemd"></label>
                            
                            <!--Change Password-->
                            <a href="{{ route('admin.users.changePassword.view', ['user' => $item->id]) }}" class="text-primary d-inline-block edit-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Change Password"> <i class="ri-lock-2-fill fs-16"></i></a>

                            {{-- Delete User --}}
                            <form id="deleteForm" action="{{ route('admin.users.delete', ['user' => $item->id]) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger d-inline-block remove-item-btn" onclick="return confirm('Are you sure you want to delete this user?')" style="border: none; background: none;" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                    <i class="ri-delete-bin-7-fill fs-16"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                @else
                    <td>
                        <div class="form-check form-switch form-switch-md" dir="ltr">
                            <input type="checkbox" class="form-check-input active_button" id="customSwitchsizemd" data-user-id="{{ $item->id }}">
                            <label class="form-check-label" for="customSwitchsizemd"></label>

                            {{-- Delete User --}}
                            <form id="deleteForm" action="{{ route('admin.users.delete', ['user' => $item->id]) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger d-inline-block remove-item-btn" onclick="return confirm('Are you sure you want to delete this user?')" style="border: none; background: none;" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                    <i class="ri-delete-bin-7-fill fs-16"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                @endif
                <td>
                    <form id="blockForm{{ $item->id }}" action="{{ route('admin.users.block', ['user' => $item->id]) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="block" value="{{ $item->block ? 0 : 1 }}">
                        <input type="checkbox" id="blockCheckbox{{ $item->id }}" {{ $item->block ? 'checked' : '' }} onchange="document.getElementById('blockForm{{ $item->id }}').submit();">
                        {{-- <label for="blockCheckbox{{ $item->id }}">
                            {{ $item->block ? 'Unblock' : 'Block' }}
                        </label> --}}
                    </form>
                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>