@extends('admin.layouts.master')
@section('title') SEO Settings @endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
@slot('title') SEO Settings @endslot
@endcomponent

<div class="col-xxl-6">
    <form method="POST" action="{{route('seo.settings.store')}}" class="row g-3">
        @csrf
        @method('PUT')
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">SEO Settings</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">


                    <div class="col-md-12">
                        <label for="Banner Text" class="form-label">Description</label>
                        <input type="text" name="description" value="{{$seo->description}}" class="form-control mb-3" id="banner_text"  placeholder="Enter Description">
                    </div>

                    <div class="col-md-12">
                        <label for="footer_text" class="form-label">Keywords</label>
                        <input type="text" name="keywords" value="{{$seo->keywords}}" class="form-control mb-3" id="footer_text"  placeholder="Enter keywords">
                    </div>

                    <div class="col-md-12">
                        <label for="facebook" class="form-label">Canonical URL</label>
                        <input type="text" name="canonical_url" value="{{$seo->canonical_url}}" class="form-control mb-3" id="facebook" placeholder="Enter canonical url">
                    </div>

                    <div class="col-md-12">
                        <label for="sitemap_url" class="form-label">Sitemap URL</label>
                        <input type="text" name="sitemap_url" value="{{$seo->sitemap_url}}" class="form-control mb-3" id="sitemap_url" placeholder="Enter sitemap_url">
                    </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="text-end">
            <input type="submit" class="btn btn-rounded gradient-btn-save mb-5" value="Save">
        </div>
    </div>
</form>
</div>

@endsection



@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection