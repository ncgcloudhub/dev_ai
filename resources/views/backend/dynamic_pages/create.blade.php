@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Dynamic Page | ON PROCESS (SEO)</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('dynamic-pages.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="route">Route</label>
                                <input id="route" type="text" class="form-control @error('route') is-invalid @enderror" name="route" value="{{ old('route') }}" required>

                                @error('route')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea id="myeditorinstance" class="form-control @error('content') is-invalid @enderror" name="content" required>{{ old('content') }}</textarea>

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
                                                <input type="text" name="seo_title" value="" class="form-control mb-3" id="seo_title" placeholder="Enter Title">
                                            </div>
                        
                                            <div class="col-md-12 mb-3">
                                                <label for="keywords" class="form-label">Keywords</label>
                                                <input class="form-control" name="keywords" id="choices-text-unique-values" data-choices data-choices-text-unique-true type="text" value="Design, Remote"  />
                                            </div>
                        
                                            <div class="col-md-12">
                                                <label for="description" class="form-label">Description</label>
                                                <input type="text" name="description" value="" class="form-control mb-3" id="description" placeholder="Enter description">
                                            </div>
                                        </div>
                                    
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Create Page</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleInput = document.getElementById('title');
        const routeInput = document.getElementById('route');

        titleInput.addEventListener('input', function () {
            const slugifiedTitle = titleInput.value
                .toLowerCase()                    // Convert to lowercase
                .replace(/[^a-z0-9\s\/]/g, '')   // Allow letters, numbers, spaces, and slashes
                .replace(/\s+/g, '-')            // Replace spaces with dashes
                .trim();                         // Remove leading/trailing spaces

            routeInput.value = `/${slugifiedTitle}`; // Ensure the route starts with a single "/"
        });

        routeInput.addEventListener('input', function () {
            // Allow slashes but sanitize other characters
            routeInput.value = routeInput.value
                .replace(/[^a-z0-9\/\-]/g, '')  // Allow only letters, numbers, dashes, and slashes
                .replace(/\/+/g, '/');          // Replace multiple slashes with a single slash
        });
    });
</script>

@endsection

