<section class="section bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center mb-5">
                    <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base">Find Your Best <span class="text-primary">Prompt</span> Library</h1>
                    <p class="text-muted">Get the best Prompt Library to Make Your Work Faster</p>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

        <div class="row align-items-center gy-4">
            @foreach ($promptLibrary as $item)
            <div class="col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <div class="d-flex">
                           
                            <div class="ms-3 flex-grow-1">
                                <a href="{{ route('prompt.frontend.view', ['slug' => $item->slug]) }}">
                                    <h5>{{$item->prompt_name}}</h5>
                                </a>
                                <ul class="list-inline text-muted mb-3">
                                    <li class="list-inline-item">
                                        <span class="text-description">{{$item->description}}</span>
                                    </li>
                                </ul>
                                <div class="hstack gap-2">
                                    <span class="badge bg-success-subtle text-success">{{$item->category->category_name}}</span>
                                    <span class="badge bg-primary-subtle text-primary">{{$item->subcategory->sub_category_name}}</span>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="mx-auto d-flex justify-content-center">
                @auth
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('prompt.manage') }}" class="btn btn-primary">Show More</a> <!-- Redirect to prompt.manage if user is admin -->
                    @elseif(Auth::user()->role == 'user')
                        <a href="{{ route('user.prompt.library') }}" class="btn btn-primary">Show More</a> <!-- Redirect to user.prompt.library if user is a normal user -->
                    @endif
                @else
                    <a href="{{ route('frontend.free.prompt.library') }}" class="btn btn-primary">Show More</a> <!-- Redirect to frontend.free.prompt.library if no one is logged in -->
                @endauth
            </div>
            
            
        </div>
    </div>
</section>