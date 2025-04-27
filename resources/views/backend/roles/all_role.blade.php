@extends('admin.layouts.master')
@section('title') All Roles @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Role @endslot
@slot('title')Manage @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-1">Role  <a href="{{ route('add.roles') }}" class="btn gradient-btn-add" title="Add Role"><i class="{{$buttonIcons['add']}}"></i></a></h5>
            </div>

            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">ID.</th>
                            <th scope="col">Role Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>
                                <a href="" class="fw-medium link-primary gradient-text-2">{{ $item->name }}</a>
                            </td>
                           
                            <td>
                                <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                    <a href="{{ route('edit.roles',$item->id) }}" class="gradient-btn-edit d-inline-block">
                                        <i class="{{$buttonIcons['edit']}}"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                    <a class="gradient-btn-delete d-inline-block" href="{{ route('delete.roles',$item->id) }}" onclick="return confirm('Are you sure you want to delete this Prompt')">
                                        <i class="{{$buttonIcons['delete']}}"></i>
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
@include('admin.layouts.datatables')

@endsection
@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
