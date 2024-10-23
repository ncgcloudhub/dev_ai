<style>
    .card-hover {
    transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .card-hover:hover {
        background: linear-gradient(45deg, #ffffff, #e0c0ee); /* Light color on hover */
        transform: translateY(-5px); /* Slight lift effect on hover */
        cursor: pointer;
    }

</style>

<section class="section bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center mb-5">
                    <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base">Find Your Best <span class="text-primary gradient-text-1">Prompt</span> Library</h1>
                    <p class="text-muted">Get the best Prompt Library to Make Your Work Faster</p>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

        <div class="row align-items-center gy-4">
            @foreach ($promptLibrary as $item)
                <div class="col-lg-6">
                    <a href="{{ Auth::check() && Auth::user()->role == 'admin' || Auth::check() && Auth::user()->role == 'user' ? route('prompt.view', ['slug' => $item->slug]) : route('prompt.frontend.view', ['slug' => $item->slug]) }}" class="text-decoration-none">
                        <div class="card shadow-lg card-hover">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="ms-3 flex-grow-1">
                                        <h5 class="text-dark">{{$item->prompt_name}}</h5>
                                        <ul class="list-inline text-muted mb-3">
                                            <li class="list-inline-item">
                                                <span class="text-description">{{$item->description}}</span>
                                            </li>
                                        </ul>
                                        <div class="hstack gap-2">
                                            <span class="badge bg-success-subtle gradient-text-1">{{$item->category->category_name}}</span>
                                            <span class="badge bg-primary-subtle gradient-text-2">{{$item->subcategory->sub_category_name}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach

            <div class="mx-auto d-flex justify-content-center">
                @auth                  
                    <a href="{{ route('prompt.manage') }}" class="btn gradient-btn-4">Show More</a> <!-- Redirect to prompt.manage if user is admin -->
                @else
                    <a href="{{ route('frontend.free.prompt.library') }}" class="btn gradient-btn-4">Show More</a> <!-- Redirect to frontend.free.prompt.library if no one is logged in -->
                @endauth
            </div>
            
            
        </div>
    </div>
</section>