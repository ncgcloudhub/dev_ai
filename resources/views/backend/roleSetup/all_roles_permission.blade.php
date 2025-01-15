@extends('admin.layouts.master')
@section('title') All Roles In Permission @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Role in Permission @endslot
@slot('title')Manage @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-1">Role in Permission <a href="{{ route('add.roles.permission') }}" class="btn gradient-btn-9">Add</a></h5>
               
            </div>

         
            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">ID.</th>
                            <th scope="col">Role Name</th>
                            <th scope="col">Users Count</th> <!-- New column for user count -->
                            <th scope="col">Users</th> <!-- New column for user names -->
                            <th scope="col">Permission </th> 
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>
                                <a href="" class="fw-medium link-primary">{{ $item->name }}</a>
                            </td>
                            <td>{{ $item->users_count }}</td> <!-- Display user count -->
                            <td>
                                @if($item->users->isNotEmpty())
                                    <ul>
                                        @foreach($item->users as $user)
                                            <li>{{ $user->name }}</li> <!-- Display user name -->
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">No users assigned</span>
                                @endif
                            </td>
                            <td>
                                @foreach($item->permissions as $perm)
                                <span class="badge bg-danger">{{ $perm->name }}</span>
                                @endforeach
                              </td> 
                           
                            <td>
                                <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                    <a href="{{ route('admin.edit.roles',$item->id) }}" class="text-primary d-inline-block edit-item-btn">
                                        <i class="ri-pencil-fill fs-16"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                    <a class="text-danger d-inline-block remove-item-btn" href="{{ route('admin.delete.roles',$item->id) }}" onclick="return confirm('Are you sure you want to delete this Prompt')">
                                        <i class="ri-delete-bin-5-fill fs-16"></i>
                                    </a>
                                </li>
                            </td>
                           

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

<!-- Include jQuery from CDN -->
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> --}}


@endsection
