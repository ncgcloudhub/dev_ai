@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Dynamic Page</div>

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
                                <small id="route-feedback" class="text-danger"></small>
                                
                                @error('route')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div class="mt-2">
                                    <label for="category">Choose Category</label>
                                    <!-- Static Keywords -->
                                    <span class="badge bg-primary keyword" data-keyword="home">Blog</span>
                                    <span class="badge bg-secondary keyword" data-keyword="about">AI</span>
                                    <span class="badge bg-success keyword" data-keyword="contact">Contact</span>
                                    <span class="badge bg-warning keyword" data-keyword="services">Services</span>
                                    <span class="badge bg-danger keyword" data-keyword="products">Products</span>
                                </div>
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
                                                <input class="form-control" name="keywords" id="choices-text-unique-values" data-choices data-choices-text-unique-true type="text" value="" placeholder="Enter Keywords">
                                            </div>
                                        
                                            <div class="col-md-12">
                                                <label for="description" class="form-label">Description</label>
                                                <input type="text" name="description" value="" class="form-control mb-3" id="description" placeholder="Enter description">
                                            </div>
                                        </div>
                                    
                                </div>
                            </div>
                            <button type="button" class="btn btn-info" id="generateSeoBtn">AI Generate</button>
                            <button type="submit" class="btn btn-primary">Create Page</button>
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
                            if (response.available) {
                                feedback.textContent = "This route is available.";
                                feedback.classList.remove('text-danger');
                                feedback.classList.add('text-success');
                            } else {
                                feedback.textContent = "This route is already taken.";
                                feedback.classList.remove('text-success');
                                feedback.classList.add('text-danger');
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


@endsection
