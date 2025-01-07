<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-5">
                <h1 class="mb-3 ff-secondary fw-semibold lh-base">AI Image Generate</h1>
                <p class="text-muted">Try any Prompt</p>
            </div>

            <form id="generateImageForm" class="row g-3">
                @csrf
                <div class="row g-3 justify-content-center">
                    <div class="col-xxl-5 col-sm-6">
                        <div class="search-box">
                            <textarea class="form-control search" name="prompt" rows="1" id="prompt" placeholder="Write prompt to generate Image"></textarea>
                            <i class="ri-search-line search-icon"></i>
                        </div>
                        <!-- Error message container -->
                        <small id="promptError" class="text-danger d-none">Please write a prompt to generate the image.</small>
                    </div>
                    
                    <!--end col-->
                    <div class="col-xxl-1 col-sm-4">
                        <div>
                            <button type="button" id="generateButton" class="btn btn-rounded mb-2 gradient-btn-5">
                                <span id="buttonText">Generate</span>
                                <span id="loadingSpinner" class="spinner-border spinner-border-sm text-light ms-2 d-none" role="status"></span>
                            </button>
                        </div>
                    </div>
                    <!--end col-->
                </div>
            </form>

            <div id="generatedImageContainer" class="text-center mt-5">
                <!-- Placeholder for the generated image -->
            </div>

            <!-- Avatar Group with Tooltip -->
            <div class="d-flex justify-content-center mt-4">
                <div class="avatar-group">
                    @foreach ($images_slider as $item)
                        <a href="{{ $item->image_url }}" class="avatar-group-item image-popup" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $item->prompt }}">
                            <img src="{{ $item->image_url }}" alt="" class="rounded-circle avatar-xl" loading="lazy">
                        </a>
                    @endforeach
                    <a href="{{route('ai.image.gallery')}}" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-placement="top" title="more">
                        <div class="avatar-xl">
                            <div class="avatar-title rounded-circle">
                                More...
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div><!-- end card-body -->
    </div><!-- end card -->
</div><!--end col-->