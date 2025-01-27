@extends('admin.layouts.master')
@section('title') Edit Dynamic Pages @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Dynamic Pages @endslot
@slot('title') Edit Page ({{$dynamicPage->title}}) @endslot
@endcomponent

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Edit Dynamic Page ({{$dynamicPage->title}})</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('dynamic-pages.update', $dynamicPage->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $dynamicPage->title) }}" required autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="route">Route</label>
                                <input id="route" type="text" class="form-control @error('route') is-invalid @enderror" name="route" value="{{ old('route', $dynamicPage->route) }}" required>

                                @error('route')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea id="myeditorinstance" class="form-control @error('content') is-invalid @enderror" name="content" required>{{ old('content', $dynamicPage->content) }}</textarea>

                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="card border card-border-info mt-3">
                                <div class="card-body">
                                    <div>
                                        <h5 class="mt-0">Page SEO</h5>
                                        <div class="col-md-12 mt-3">
                                            <label for="seo_title" class="form-label">Title</label>
                                            <input type="text" name="seo_title" value="{{ old('seo_title', $dynamicPage->seo_title) }}" class="form-control mb-3" id="seo_title" placeholder="Enter Title">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="keywords" class="form-label">Keywords</label>
                                            <input class="form-control" name="keywords" id="choices-text-unique-values" data-choices data-choices-text-unique-true type="text" value="{{ old('keywords', $dynamicPage->keywords) }}" />
                                        </div>

                                        <div class="col-md-12">
                                            <label for="description" class="form-label">Description</label>
                                            <input type="text" name="description" value="{{ old('description', $dynamicPage->description) }}" class="form-control mb-3" id="description" placeholder="Enter description">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn gradient-btn-save">Update Page</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/du2qkfycvbkcbexdcf9k9u0yv90n9kkoxtth5s6etdakoiru/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#myeditorinstance',
        plugins: 'code table lists image media',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code | image media',
        height: 400,
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
</script>
@endsection
