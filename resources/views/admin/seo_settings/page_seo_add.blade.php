@extends('admin.layouts.master')
@section('title') Page SEO Add @endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
@slot('title') Page SEO Add @endslot
@endcomponent

<div class="col-xxl-6">
    <form method="POST" action="{{route('page.seo.store')}}" class="row g-3">
        @csrf
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Page SEO</h4>
            </div>
    
            <div class="card-body">
                <div class="live-preview">
                    <div class="col-md-12 mb-3">
                        <label for="Banner Text" class="form-label">Route</label>
                        <select id="route-select" class="form-select" name="route_name" data-choices aria-label="Default select example">
                            <option selected="">Select Route</option>
                            @foreach($routes as $route)
                                <option value="{{ $route['name'] }}">{{ $route['name'] }} ({{ $route['url'] }})</option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="col-md-12">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" value="" class="form-control mb-3" id="title" placeholder="Enter Title">
                    </div>
    
                    <div class="col-md-12 mb-3">
                        <label for="keywords" class="form-label">Keywords</label>
                        <input class="form-control" name="keywords" id="keywords" type="text" value="Design, Remote" />
                    </div>
    
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" value="" class="form-control mb-3" id="description" placeholder="Enter description">
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-12">
            <div class="text-end">
                <button type="submit" class="btn btn-rounded gradient-btn-save mb-5" title="Save">
                    <i class="{{$buttonIcons['save']}}"></i>
                </button>
            </div>
        </div>
    </form>
    
</div>

@endsection



@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>
   document.getElementById('route-select').addEventListener('change', function () {
    const route = this.value;
    if (route) {
        fetch(`/get-seo-details?route=${encodeURIComponent(route)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('title').value = data.page.title || '';
                    document.getElementById('keywords').value = data.page.keywords || '';
                    document.getElementById('description').value = data.page.description || '';
                } else {
                    document.getElementById('title').value = '';
                    document.getElementById('keywords').value = '';
                    document.getElementById('description').value = '';
                }
            })
            .catch(error => console.error('Error fetching SEO data:', error));
    }
});

</script>

@endsection