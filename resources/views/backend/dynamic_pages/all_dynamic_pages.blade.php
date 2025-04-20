@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Settings @endslot
@slot('title')Manage Page @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                @can('managePage.add')
                    <a href="{{ route('dynamic-pages.create') }}" class="btn gradient-btn-11">Create Page</a>    
                @endcan            
            </div>
            
            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl.</th>
                            <th scope="col">Title</th>
                            <th scope="col">Route</th>
                            <th scope="col">Content</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dynamicPage as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ url($item->route) }}" class="dynamic-page-link gradient-text-2">{{ $item->title }}</a>
                            </td>
                            <td>{{ $item->route }}</td>
                            <td>{{ Str::limit(strip_tags($item->content), 200) }}</td>
                            <td>
                                @if($item->page_status == 'completed')
                                    <span class="badge bg-success">{{ $item->page_status }}</span>
                                @elseif($item->page_status == 'inprogress')
                                    <span class="badge bg-danger">{{ $item->page_status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item->page_status }}</span>
                                @endif
                            </td>
                            
                            <td>
                                @can('managePage.edit')
                                    <a href="{{ route('dynamic-pages.edit', $item->id) }}" class="text-primary d-inline-block edit-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <i class="ri-pencil-fill fs-16"></i> 
                                    </a>
                                @endcan
                               
                                @can('managePage.delete')
                                    <form action="{{ route('dynamic-pages.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger d-inline-block remove-item-btn" onclick="return confirm('Are you sure you want to delete this Page?')" style="border: none; background: none;" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                            <i class="ri-delete-bin-5-fill fs-16"></i> 
                                        </button>
                                    </form>    
                                @endcan
                                
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
