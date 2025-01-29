<section class="section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center mb-5">
                    <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base">Find Your Best <span class="text-primary gradient-text-1">Education</span> Tools</h1>
                    <p class="text-muted">Get The Best Education Tools</p>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

        <div class="row">

            @include('frontend.common.education_tools')

            <div class="mx-auto d-flex justify-content-center">
                @auth                  
                    <a href="{{ route('manage.education.tools') }}" class="btn gradient-btn-4">Show More</a> <!-- Redirect to prompt.manage if user is admin -->
                @else
                    <a href="{{ route('frontend.free.education') }}" class="btn gradient-btn-4">Show More</a> <!-- Redirect to frontend.free.prompt.library if no one is logged in -->
                @endauth
            </div>
        </div>
    </div>
</section>