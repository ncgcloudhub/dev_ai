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

<form method="POST" action="{{ route('dynamic-pages.update', $dynamicPage->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="project-title-input">Page Title</label>
                        <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $dynamicPage->title) }}" required autofocus>

                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="route">Route</label>
                        <input id="route" type="text" class="form-control @error('route') is-invalid @enderror" name="route" value="{{ old('route', $dynamicPage->route) }}" required>
                        <small id="route-feedback" class="text-danger"></small>
                        
                        @error('route')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="mt-2">
                            <label for="category">Choose Category</label>
                            <!-- Static Keywords -->
                            <span class="badge bg-primary keyword" data-keyword="blog">Blog</span>
                            <span class="badge bg-secondary keyword" data-keyword="content">Content</span>
                            <span class="badge bg-success keyword" data-keyword="contact">Contact</span>
                            <span class="badge bg-warning keyword" data-keyword="services">Services</span>
                            <span class="badge bg-danger keyword" data-keyword="products">Products</span>
                            <span class="badge bg-info keyword" id="dynamic-category">{{ old('category', $dynamicPage->category) }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="project-thumbnail-img">Thumbnail Image</label>
                        @if($dynamicPage->thumbnail_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $dynamicPage->thumbnail_image) }}" class="img-thumbnail" alt="Thumbnail" style="max-width: 150px;">
                            </div>
                        @endif
                        <input class="form-control" id="project-thumbnail-img" type="file" accept="image/png, image/gif, image/jpeg" name="thumbnail_image">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="project-banner-img">Banner Image</label>
                        @if($dynamicPage->banner_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $dynamicPage->banner_image) }}" class="img-thumbnail" alt="Banner" style="max-width: 150px;">
                            </div>
                        @endif
                        <input class="form-control" id="project-banner-img" type="file" accept="image/png, image/gif, image/jpeg" name="banner_image">
                    </div>

                    <div class="mb-3">
                        <label for="content">Content</label>
                        <textarea id="myeditorinstance" class="form-control @error('content') is-invalid @enderror" name="content" required>{{ old('content', $dynamicPage->content) }}</textarea>

                        @error('content')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

     <!-- Attachments -->
     <div class="mb-3">
        <label for="attached_files" class="form-label">Attachments</label>
        <input name="attached_files[]" type="file" multiple class="form-control" id="attached_files">

        <!-- Display existing attachments if available -->
        @if(!empty($attachments))
            <div class="mt-3">
                <h5>Existing Attachments:</h5>
                @foreach($attachments as $attachment)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $attachment) }}" target="_blank">{{ basename($attachment) }}</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
        </div>
        <!-- end col -->

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Privacy</h5>
                </div>
                <div class="card-body">
                    <div>
                        <label for="choices-status-input" class="form-label">Status</label>
                        <select class="form-select" id="choices-status-input" name="page_status">
                            <option value="inprogress" @if($dynamicPage->status == 'inprogress') selected @endif>Inprogress</option>
                            <option value="completed" @if($dynamicPage->status == 'completed') selected @endif>Completed</option>
                        </select>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tags</h5>
                </div>
                <div class="card-body">
                    <div>
                        <label for="choices-text-input" class="form-label">Relevant Tags</label>
                        <input type="text" name="tags" class="form-control" value="{{ old('tags', $dynamicPage->tags) }}" placeholder="Enter Tags (Separated by comma)">
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page SEO</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="seo_title" class="form-label">Title</label>
                        <input type="text" name="seo_title" value="{{ old('seo_title', $dynamicPage->seo_title) }}" class="form-control mb-3" id="seo_title" placeholder="Enter Title">
                    </div>
                    <div class="mb-3">
                        <label for="keywords" class="form-label">Keywords</label>
                        <input class="form-control" name="keywords" id="choices-text-unique-values" type="text" value="{{ old('keywords', $dynamicPage->keywords) }}" placeholder="Enter Keywords">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" value="{{ old('description', $dynamicPage->description) }}" class="form-control mb-3" id="description" placeholder="Enter description">
                        <input type="text" name="status" id="" value="draft" hidden>
                    </div>
                    <button type="button" class="btn gradient-btn-5" id="generateSeoBtn">AI Generate</button>

                </div>
                <!-- end card body -->
            </div>

            <div class="text-end mb-4">
                <button type="submit" class="btn btn-primary w-sm">Save Changes</button>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
</form>


@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/du2qkfycvbkcbexdcf9k9u0yv90n9kkoxtth5s6etdakoiru/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#myeditorinstance',
        branding: false, // Removes "Build with TinyMCE"
        plugins: 'code table lists image media autosave emoticons fullscreen preview quickbars wordcount codesample',
        toolbar: 'undo redo | blocks fontsizeinput | bold italic backcolor emoticons | alignleft aligncenter alignright alignjustify blockquote | bullist numlist outdent indent | removeformat | code codesample fullscreen | image media | restoredraft preview quickimage wordcount',
        autosave_restore_when_empty: true,
        height: 400,
        statusbar: true, // Keep the status bar for resizing
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        },
        init_instance_callback: function (editor) {
            setTimeout(function () {
                document.querySelector('.tox-statusbar__path').style.display = 'none'; // Hide <p> indicator
            }, 100);
        }
    });
</script>
@endsection
