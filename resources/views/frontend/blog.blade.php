@extends('admin.layouts.master-without-nav')
@section('title', $seo->title)

@section('description', $seo->description)

@section('keywords', $seo->keywords)
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
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

           <section class="section">
            <div class="container">
                <h2>{{$title}}</h2>
                <br>
                <div class="row">
                    @foreach ($blog as $post)
                        <div class="col-xxl-3 col-lg-6">
                            <div class="card overflow-hidden blog-grid-card">
                                <div class="position-relative overflow-hidden">
                                    <!-- Display the thumbnail image dynamically -->
                                    @if($post->thumbnail_image)
                                        <img src="{{ asset('storage/' . $post->thumbnail_image) }}" alt="" class="blog-img object-fit-cover">
                                    @else
                                        <img src="{{ asset('build/images/blog.gif') }}" alt="" class="blog-img object-fit-cover" data-src="{{ asset('build/images/blog.gif') }}">
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

                </div><!--end row-->
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

        <script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>
       
    @endsection
