@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Admin @endslot
@slot('title')Manage @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-1">Admin <a href="{{ route('add.admin') }}" class="btn gradient-btn-11">Add</a></h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl.</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Email Verified</th>
                            <th scope="col">Role</th>
                            <th scope="col">Credits Used</th>
                            <th scope="col">Tokens Used</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alladmin as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>
                                <a href="{{ route('user.details', ['id' => $item->id, 'name' => Str::slug($item->name ?? $item->username ?? 'username')]) }}" 
                                    class="fw-medium link-primary gradient-text-2">
                                    {{$item->name}}({{$item->username}})</a>
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
        
                            <td>
                                @foreach ($item->roles as $role)
                                    <span class="badge badge-pill bg-danger">{{ $role->name }}</span>
                                @endforeach   
                               
                               </td>  
                            <td>{{ $item->credits_used }}</td>
                            <td>{{ $item->tokens_used }}</td>
                     
                            @if ($item->status == 'active')
                            <td>
                                <div class="form-check form-switch form-switch-md" dir="ltr">
                                    @can('manageUser&Admin.manageAdmin.statusChange')
                                        <input type="checkbox" class="form-check-input active_button" id="customSwitchsizemd" data-user-id="{{ $item->id }}" checked title="Change Status">
                                        <label class="form-check-label" for="customSwitchsizemd"></label>
                                    @endcan
                                  
                                    @can('manageUser&Admin.manageAdmin.edit')
                                        <a href="{{ route('edit.admin',$item->id) }}" class="btn gradient-btn-edit" title="Edit"> <i class="{{$buttonIcons['edit']}}"></i> </a>
                                    @endcan
                                  
                                    @can('manageUser&Admin.manageAdmin.delete')
                                         {{-- Delete User --}}
                                        <form id="deleteForm" action="{{ route('admin.users.delete', ['user' => $item->id]) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn gradient-btn-delete" onclick="return confirm('Are you sure you want to delete this user?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                <i class="{{$buttonIcons['delete']}}"></i>
                                            </button>
                                        </form>
                                    @endcan
                                   
                                </div>
                            </td>
                            @else
                            <td>
                                <div class="form-check form-switch form-switch-md" dir="ltr">
                                    <input type="checkbox" class="form-check-input active_button" id="customSwitchsizemd" data-user-id="{{ $item->id }}">
                                    <label class="form-check-label" for="customSwitchsizemd"></label>
        
                                    @can('manageUser&Admin.manageAdmin.delete')
                                         {{-- Delete User --}}
                                        <form id="deleteForm" action="{{ route('admin.users.delete', ['user' => $item->id]) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light" onclick="return confirm('Are you sure you want to delete this user?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                <i class="ri-delete-bin-3-fill"></i>
                                            </button>
                                        </form>
                                    @endcan
                                   
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.datatables')

@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
