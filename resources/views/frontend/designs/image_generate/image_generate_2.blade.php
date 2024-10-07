<section class="section" style="background-image: url('https://images.ctfassets.net/kftzwdyauwt9/f698e023-3373-4385-a039a7370270/29fdcfdd52fe5cf2b38deff2af34648f/VALERIECloudAstronaut.png?w=3840&q=90&fm=webp'); background-size: cover; background-position: center;">
        <div class="card-body" style="background-color: rgba(250, 250, 250, 0); border-radius: 15px;">
            <div class="text-center mb-5">
                <h1 class="mb-3 ff-secondary fw-semibold lh-base text-primary">AI Image Generator</h1>
                <p class="text-muted">Try any prompt to create your image.</p>
            </div>

            <form id="generateImageForm" class="row g-3">
                @csrf
                <div class="row g-3 justify-content-center">
                    <div class="col-xxl-5 col-sm-6">
                        <div class="search-box">
                            <textarea class="form-control search" name="prompt" rows="1" id="prompt" placeholder="Write prompt to generate Image" style="border-radius: 10px;"></textarea>
                            <i class="ri-search-line search-icon"></i>
                        </div>
                        <!-- Error message container -->
                        <small id="promptError" class="text-danger d-none">Please write a prompt to generate the image.</small>
                    </div>

                    <!--end col-->
                    <div class="col-xxl-1 col-sm-4">
                        <div>
                            <button type="button" id="generateButton" class="btn btn-rounded btn-primary mb-2" style="border-radius: 10px; padding: 10px 20px;">
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
                            <img src="{{ $item->image_url }}" alt="" class="rounded-circle avatar-xl">
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
    
    </section>

<style>
    .card {
       background-size: cover;
        background-position: center;
        border: none;
        border-radius: 15px;
    }
    .card-body {
        background-color: rgba(255, 255, 255, 0.8); /* White background with transparency */
        border-radius: 15px;
        padding: 2rem;
    }
    .search-box {
        position: relative;
    }
    .search-icon {
        position: absolute;
        right: 10px;
        top: 10px;
        color: #999;
    }
</style>
