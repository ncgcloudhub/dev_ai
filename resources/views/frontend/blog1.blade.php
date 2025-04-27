@extends('admin.layouts.master-without-nav')
@section('title', $seo->title)

@section('description', $seo->description)

@section('keywords', $seo->keywords)
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}">
<style>
    .blog-grid-card .blog-img {
        height: 230px;
        width: 100%;
    }
</style>
@endsection
@section('body')

    <body data-bs-spy="scroll" data-bs-target="#navbar-example">
    @endsection
    @section('content')
        <!-- Begin page -->
        <div class="layout-wrapper landing d-flex flex-column min-vh-100">
           @include('frontend.body.nav_frontend')

           <section class="section mx-4">
                <div class="profile-foreground position-relative mx-n4 mt-n4">
                    <div class="profile-wid-bg">
                        <img src="{{ URL::asset('build/images/profile-bg.jpg') }}" alt="" class="profile-wid-img" />
                    </div>
                </div>
                <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
                   
                </div>
            
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <div class="d-flex profile-wrapper">
                        
                                
                            </div>
                            <!-- Tab panes -->
                            <div class="tab-content pt-4 text-muted">
                                <div class="tab-pane active" id="overview-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-xxl-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title mb-5">Blogs</h5>
                                                   
                                                </div>
                                            </div>
            
                                           
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title mb-4">Follow Us!</h5>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <div>
                                                            <a href="{{$siteSettings->facebook}}" target="_blank" class="mx-1">
                                                                <img src="{{ asset('/backend/uploads/site/fb.png') }}" width="20px" alt="">
                                                            </a>
                                                            <a href="{{$siteSettings->instagram}}" target="_blank" class="mx-1">
                                                                <img src="{{ asset('backend/uploads/site/insta.png') }}" width="20px" alt="">
                                                            </a>
                                                            <a href="{{$siteSettings->linkedin}}" target="_blank" class="mx-1">
                                                                <img src="{{ asset('backend/uploads/site/In.png') }}" width="20px" alt="">
                                                            </a>
                                                            <a href="{{$siteSettings->youtube}}" target="_blank" class="mx-1">
                                                                <img src="{{ asset('backend/uploads/site/youtube.png') }}" width="20px" alt="">
                                                            </a>
                                                        </div>
                                                   
                                                    </div>
                                                </div><!-- end card body -->
                                            </div><!-- end card -->
            
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title mb-4">Category</h5>
                                                    <div class="d-flex flex-wrap gap-2 fs-15">
                                                        @foreach ($categories as $category)
                                                        <a href="{{ route('category.wise.blog', $category->category) }}" class="badge rounded-pill badge-gradient-purple">{{ $category->category }}</a>
                                                        @endforeach
                                                    </div>
                                                </div><!-- end card body -->
                                            </div><!-- end card -->
            
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-4">
                                                        <div class="flex-grow-1">
                                                            <h5 class="card-title mb-0">Popular Blogs</h5>
                                                        </div>
                                                    </div>
                                                    @foreach ($recents as $recent)
                                                    <div class="d-flex mb-4">
                                                        <div class="flex-shrink-0">
                                                            @if($recent->thumbnail_image)
                                                            <img src="{{ asset('storage/' . $recent->thumbnail_image) }}" alt=""
                                                                height="50" class="rounded" />
                                                            @else
                                                            <img src="{{ asset('build/images/blog.gif') }}" alt=""
                                                                height="50" class="rounded" />
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1 ms-3 overflow-hidden">
                                                            <a href="{{ url($recent->route) }}">
                                                                <h6 class="text-truncate fs-14">{{$recent->title}}</h6>
                                                            </a>
                                                            <p class="text-muted mb-0">{{ $recent->created_at->format('M d Y') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    
                                                </div>
                                                <!--end card-body-->
                                            </div>
                                            <!--end card-->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-9">
                                           
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card">
                                                        <div class="card-header align-items-center d-flex">
                                                            <h4 class="card-title mb-0  me-2">Blogs</h4>
                                                            <div class="flex-shrink-0 ms-auto">
                                                                <ul class="nav nav-pills justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0"
                                                                    role="tablist">
                                                                    <li class="nav-item">
                                                                        <a class="nav-link active" data-bs-toggle="tab" href="#today"
                                                                            role="tab">
                                                                            Grid
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" data-bs-toggle="tab" href="#weekly"
                                                                            role="tab">
                                                                            List
                                                                        </a>
                                                                    </li>
                                                                   
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            {{-- Search List --}}
                                                            <input type="text" class="form-control search"
                                                                placeholder="Search for Content Creator Tools">
                                                            <br>
                                                            <div class="tab-content text-muted">
                                                                <div class="tab-pane active" id="today" role="tabpanel">
                                                                    <div class="row">
                                                                        @foreach ($blog as $post)
                                                                        <div class="col-xxl-3 col-lg-6">
                                                                            <div class="card overflow-hidden blog-grid-card template-card" data-search="{{ strtolower($post->title . ' ' . $post->description) }}">
                                                                                <div class="position-relative overflow-hidden">
                                                                                    @if($post->thumbnail_image)
                                                                                        <img src="{{ asset('storage/' . $post->thumbnail_image) }}" alt="" class="blog-img object-fit-cover">
                                                                                    @else
                                                                                        <img src="{{ asset('build/images/blog.gif') }}" alt="" class="blog-img object-fit-cover">
                                                                                    @endif
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <h5 class="card-title">
                                                                                        <a href="{{ url($post->route) }}" class="text-reset">{{ $post->title ?? 'Untitled' }}</a>
                                                                                    </h5>
                                                                                    <p class="text-muted mb-2">
                                                                                        {{ Str::limit($post->description ?? 'No description available.', 50, '...') }}
                                                                                    </p>
                                                                                    <a href="{{ url($post->route) }}" class="link link-primary text-decoration-underline link-offset-1">
                                                                                        Read Post <i class="ri-arrow-right-up-line"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div><!--end col-->
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="weekly" role="tabpanel">
                                                                        @foreach ($blog as $post)
                                                                        <div class="card mb-0 template-card" data-search="{{ strtolower($post->title . ' ' . $post->description) }}">
                                                                            <div class="card-body">                
                                                                                <div class="d-lg-flex align-items-center">                    
                                                                                    <div class="flex-shrink-0">                        
                                                                                        <div class="avatar-sm rounded">
                                                                                            @if($post->thumbnail_image)
                                                                                                <img src="{{ asset('storage/' . $post->thumbnail_image) }}" alt="" class="member-img img-fluid d-block rounded">
                                                                                            @else
                                                                                                <img src="{{ asset('build/images/blog.gif') }}" alt="" class="member-img img-fluid d-block rounded" data-src="{{ asset('build/images/blog.gif') }}">
                                                                                            @endif
                                                                                
                                                                                        </div>            
                                                                                    </div>
                                                                                    <div class="ms-lg-3 my-3 my-lg-0">                        
                                                                                        <a href="pages-profile.html">
                                                                                            <h5 class="fs-16 mb-2"> <a href="{{ url($post->route) }}" class="text-reset">{{ $post->title ?? 'Untitled' }}</a>
                                                                                            </h5>
                                                                                        </a>                        
                                                                                        <p class="text-muted mb-0">{{ Str::limit($post->description ?? 'No description available.', 50, '...') }}
                                                                                        </p>       
                                                                                    </div>        
                                                                               </div>      
                                                                            </div> 
                                                                        </div>
                                                                        <br>
                                                                        @endforeach
                                                                </div>
                                                            </div>
                                                        </div><!-- end card body -->
                                                    </div><!-- end card -->
                                                </div><!-- end col -->
                                            </div><!-- end row -->
                                           
                                            
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                               
                            </div>
                            <!--end tab-content-->
                        </div>
                    </div>
                    <!--end col-->
                </div>
        </section>
        

            <!-- Start footer -->
            @include('frontend.body.footer_frontend')
            <!-- end footer -->

            <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

        </div>
        <!-- end layout wrapper -->
    @endsection
    @section('script')

    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/profile.init.js') }}"></script>

    {{-- SEARCH --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('.search');
            // Select all elements with the searchable data attribute
            const templateCards = document.querySelectorAll('.template-card');
            const noResultMessage = document.querySelector('.noresult');
        
            searchInput.addEventListener('keyup', function (event) {
                const searchTerm = event.target.value.trim().toLowerCase();
                let found = false;
        
                templateCards.forEach(function (card) {
                    const searchContent = card.dataset.search;
                    // Check if the search term is in the card's searchable content.
                    if (searchContent.includes(searchTerm)) {
                        // If this card is inside a grid column container, show that container.
                        const parentCol = card.closest('.col-xxl-3, .col-lg-6');
                        if (parentCol) {
                            parentCol.style.display = 'block';
                        } else {
                            // Otherwise, show the card directly (for list view).
                            card.style.display = 'block';
                        }
                        found = true;
                    } else {
                        // Hide the parent column if it exists.
                        const parentCol = card.closest('.col-xxl-3, .col-lg-6');
                        if (parentCol) {
                            parentCol.style.display = 'none';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
        
                // Toggle the "no result" message if needed.
                if (!found) {
                    noResultMessage.style.display = 'block';
                } else {
                    noResultMessage.style.display = 'none';
                }
            });
        });
        </script>
        
@endsection