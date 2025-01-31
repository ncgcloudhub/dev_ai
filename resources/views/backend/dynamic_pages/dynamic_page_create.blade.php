@extends('admin.layouts.master')
@section('title') Create Dynamic Pages @endsection
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Dynamic Pages @endslot
@slot('title') Create Page @endslot
@endcomponent

<form method="POST" action="{{ route('dynamic-pages.store') }}" enctype="multipart/form-data">
    @csrf
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="project-title-input">Page Title</label>
                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autofocus>

                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="route">Route</label>
                    <input id="route" type="text" class="form-control @error('route') is-invalid @enderror" name="route" value="{{ old('route') }}" required>
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
                        <span class="badge bg-info keyword" id="dynamic-category"></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="project-thumbnail-img">Thumbnail Image</label>
                    <input class="form-control" id="project-thumbnail-img" type="file"
                        accept="image/png, image/gif, image/jpeg" name="thumbnail_image">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="project-banner-img">Banner Image</label>
                    <input class="form-control" id="project-banner-img" type="file"
                        accept="image/png, image/gif, image/jpeg" name="banner_image">
                </div>

                <div class="mb-3">
                    <label for="content">Content</label>
                            <textarea id="myeditorinstance" class="form-control @error('content') is-invalid @enderror" name="content" required>{{ old('content') }}</textarea>

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

        {{-- <input name="attached_files[]" type="file" multiple> --}}
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
                        <option value="inprogress" selected>Inprogress</option>
                        <option value="completed">Completed</option>
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
                    <input type="text" name="tags" class="form-control" placeholder="Enter Tags (Separated by comma)">
                    
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
                    <input type="text" name="seo_title" value="" class="form-control mb-3" id="seo_title" placeholder="Enter Title">
                </div>
                <div class="mb-3">
                    <label for="keywords" class="form-label">Keywords</label>
                    <input class="form-control" name="keywords" id="choices-text-unique-values"  type="text" value="" placeholder="Enter Keywords">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" value="" class="form-control mb-3" id="description" placeholder="Enter description">
                    <input type="text" name="status" id="" value="draft" hidden>
                </div>
                <button type="button" class="btn gradient-btn-5" id="generateSeoBtn">AI Generate</button>

            </div>
            <!-- end card body -->
        </div>
        <div class="text-end mb-4">
            <button type="submit" class="btn gradient-btn-save">Create Page</button>
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
</form>

@endsection

@section('script')
<script src="{{ URL::asset('build/js/pages/project-create.init.js') }}"></script>
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

<script>
    // Existing SEO Generation Script
    $(document).ready(function () {
        $('#generateSeoBtn').on('click', function () {
            let title = $('#title').val().trim(); // Get the SEO title value

            if (!title) {
                alert('Please enter a title to generate SEO content.');
                return;
            }

            $.ajax({
                url: '/dynamic-pages/seo/generate', // Adjust the URL to your route
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { title: title },
                success: function (response) {
                    if (response.success) {
                        // Populate the SEO fields
                        $('#seo_title').val(response.seo_title);
                        $('#choices-text-unique-values').val(response.seo_tags); // Assuming "keywords" are tags
                        $('#description').val(response.seo_description);
                    } else {
                        alert(response.message || 'Failed to generate SEO content.');
                    }
                },
                error: function () {
                    alert('Error generating SEO content. Please try again.');
                }
            });
        });
    });

    // New Script: Route Availability Check
    document.addEventListener('DOMContentLoaded', function () {
        const routeInput = document.getElementById('route');
        const feedback = document.createElement('small');
        feedback.id = 'route-feedback';
        feedback.className = 'form-text mt-1';
        routeInput.parentNode.appendChild(feedback); // Add feedback below the input field

        let debounceTimer;

        routeInput.addEventListener('input', function () {
            const route = routeInput.value.trim();

            // Sanitize input
            routeInput.value = route.replace(/[^a-zA-Z0-9\/\-]/g, '').replace(/\/+/g, '/');

            if (route.length > 0) {
                // Clear the existing debounce timer
                clearTimeout(debounceTimer);

                // Set a new debounce timer
                debounceTimer = setTimeout(() => {
                    // Make AJAX request to check route availability
                    $.ajax({
                        url: '/dynamic-pages/check-route', // Adjust to your actual route for checking
                        method: 'GET',
                        data: { route: route },
                        success: function (response) {
                            if (response.status === 'taken') {
                                feedback.textContent = response.message; // This route is already taken in the database
                                feedback.classList.remove('text-success');
                                feedback.classList.add('text-danger');
                            } else if (response.status === 'declared') {
                                feedback.textContent = response.message; // This route is already declared in route files
                                feedback.classList.remove('text-success');
                                feedback.classList.add('text-warning');
                            } else {
                                feedback.textContent = response.message; // This route is available
                                feedback.classList.remove('text-danger', 'text-warning');
                                feedback.classList.add('text-success');
                            }
                        },
                        error: function () {
                            feedback.textContent = "Error checking route availability.";
                            feedback.classList.remove('text-success');
                            feedback.classList.add('text-danger');
                        }
                    });
                }, 300); // 300ms debounce delay
            } else {
                feedback.textContent = ""; // Clear feedback if input is empty
            }
        });
    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const routeInput = document.getElementById('route');
        const keywords = document.querySelectorAll('.keyword');

        keywords.forEach(keyword => {
            keyword.addEventListener('click', function () {
                const selectedKeyword = this.getAttribute('data-keyword');
                const currentValue = routeInput.value;

                // Always append the keyword with a leading /
                routeInput.value = currentValue + `/${selectedKeyword}`;
            });
        });
    });
</script>

<script>
    document.getElementById('title').addEventListener('input', function() {
        var title = this.value.trim();
        var slug = title.toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]/g, '');
        
        // Update the dynamic category slug
        var dynamicCategory = document.getElementById('dynamic-category');
        dynamicCategory.textContent = slug ? slug : 'Enter Title to Generate Slug';
        dynamicCategory.setAttribute('data-keyword', slug);
    });

    // Add event listener to make the dynamically generated category clickable
    document.getElementById('dynamic-category').addEventListener('click', function() {
        var selectedKeyword = this.getAttribute('data-keyword');
        alert('Category chosen: ' + selectedKeyword);
        // You can add your category selection logic here
    });
</script>



@endsection
