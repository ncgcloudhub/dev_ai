@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')

 <!--Swiper slider css-->
 <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('content')

<div class="row">
    {{-- First Col --}}
    <div class="col-xl-8 col-md-12">
        <div class="card overflow-hidden" style="border-color: #be06af">
            <div class="card-body bg-marketplace d-flex">
                <div class="flex-grow-1">
                    <h4 class="fs-18 lh-base mb-0" id="greeting"></h4>
                    <h4 class="gradient-text-1-bold">{{$user->name}}</h4>
                    <p class="mb-0 mt-2 pt-1 gradient-text-2">Empowering creativity with AI-driven content generation and innovative design tools.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="" class="btn gradient-btn-3">Chat Now</a>
                        <a href="" class="btn gradient-btn-2">Create Your Imagination</a>
                    </div>
                </div>
                
                <img src="/build/images/nft/das_1.png" alt="" class="img-fluid">
            </div>
        </div>

        {{-- 1st col 2nd row --}}
        <div class="card overflow-hidden shadow-none">
            <div class="card-body bg-success-subtle text-success fw-semibold d-flex gradient-bg">
                <marquee class="fs-14">
                    Clever Creator.ai is an innovative AI-powered platform that generates creative content, designs, and ideas, empowering users with efficient tools for dynamic, high-quality content creation and innovation.
                </marquee>
            </div>
        </div>

        {{-- 1st col 3rd row --}}
        <div class="swiper marketplace-swiper rounded gallery-light">
            <div class="d-flex pt-2 pb-4">
                <h5 class="card-title gradient-text-1-bold fs-18 mb-1">Education Tools</h5>
            </div>
            <div class="swiper-wrapper">
                @foreach ($eduTools as $tool)
                <div class="swiper-slide">
                    <div class="card explore-box card-animate rounded">
                        <div class="explore-place-bid-img">
                            <img src="{{ asset('storage/' . $tool->image) }}" alt=""
                                class="img-fluid card-img-top explore-img" />
                            <div class="bg-overlay"></div>
                            <div class="place-bid-btn">
                                <a href="{{ route('tool.show', ['id' => $tool->id, 'slug' => $tool->slug]) }}" class="btn btn-primary"><i
                                        class="ri-auction-fill align-bottom me-1"></i>Explore</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i>
                                Editor's Choice</p>
                            <h5 class="mb-1"><a href="apps-nft-item-details" class="text-body">{{ $tool->name }}</a>
                            </h5>
                            <p class="text-muted mb-0">{{ $tool->description }}</p>
                        </div>
                       
                    </div>
                </div>
                @endforeach
              
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>

        {{-- 1st col 4th row --}}
        <div class="row">
            <div class="col-xl-6 col-md-12"><div class="card" style="border-color: #be06af">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row position-relative">
                        <img src="/build/images/nft/friends.png" class="flex-shrink-0 mb-3 mb-md-0 me-md-3 avatar-xl rounded" alt="...">
                        <div>
                            <h5 class="mt-0 gradient-text-1-bold">Referral Link</h5>
                            <p>Share this link to your friends to get more Free tokens and credits.</p>
                            <a onclick="copyText(this)" class="btn gradient-btn-3">{{$user->referral_link}}</a>
                        </div>
                    </div>
                </div>
                
        </div></div>
            <div class="col-xl-3 col-md-6"><div class="card" style="border-color: #be06af">
            <div class="card-body">
                <div class="d-flex position-relative">
                    <div>
                        <h5 class="mt-0 gradient-text-1-bold">Tokens Left</h5>
                        <p>Used for generating AI Content Creator, Chat, Prompting and many more!</p>
                        <a href="javascript:void(0);" class="btn gradient-btn-3">{{$user->tokens_left}}</a>
                    </div>
                </div>
            </div>
        </div></div>
            <div class="col-xl-3 col-md-6"><div class="card" style="border-color: #be06af">
            <div class="card-body">
                
                    <div>
                        <h5 class="mt-0 gradient-text-1-bold">Credits left</h5>
                        <p>Used for generating High Quality Images in Dalle and Stable Diffusion!</p>
                        <a href="javascript:void(0);" class="btn gradient-btn-3"> {{$user->credits_left}}</a>
                    </div>
                
            </div>
        </div></div>
        </div>

    </div> 

    {{-- Second Col --}}
    <div class="col-xl-4 col-md-12">
        <div class="card explore-box card-animate">
            <img src="/build/images/nft/das_cc_02.gif" alt="" class="card-img-top explore-img">
        </div>

        {{-- 2nd col 2nd row --}}
        <div class="card" style="border-color: #be06af">
            <div class="card-body">
                <div class="d-flex position-relative">
                    <img src="/build/images/nft/das_2.png" class="flex-shrink-0 me-3 avatar-xl rounded" alt="...">
                    <div>
                        <h5 class="mt-0 gradient-text-1-bold">AI Content Creator</h5>
                        <p>AI content creator tools provide ready-to-use templates, making it effortless to generate captivating content, saving time while ensuring creativity and consistency across various formats.</p>
                        <a href="javascript:void(0);" class="btn gradient-btn-3">Generate Content</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                
                    <img src="/build/images/nft/car.png"  alt="..." class="img-fluid">
                    <a href="" class="btn gradient-btn-3-square d-grid">Dalle</a>
            
            </div>

            <div class="col">
                
               
                    <img src="/build/images/nft/dog.png"  alt="..." class="img-fluid">
                    <a href="" class="btn gradient-btn-3-square d-grid">Stable Diffusion</a>
            
        </div>
        </div>

    </div> 

</div>


@endsection
@section('script')
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- dashboard init -->
<script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>

 <!-- Marketplace init -->
 <script src="{{ URL::asset('build/js/pages/dashboard-nft.init.js') }}"></script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>


<script type="text/javascript" async
  src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML">
</script>
<script type="text/javascript">
  MathJax.Hub.Config({
    tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
  });
</script>



<script>
    $(document).ready(function() {
        $('#liked-images-table').DataTable({
            "responsive": true,
            "autoWidth": false
        });

        $('#favourite-images-table').DataTable({
            "responsive": true,
            "autoWidth": false
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var greetingElement = document.getElementById('greeting');
        var currentTime = new Date();
        var currentHour = currentTime.getHours();
        var greeting;

        if (currentHour < 12) {
            greeting = "Good Morning";
        } else if (currentHour < 14) {
            greeting = "Good Noon";
        } else if (currentHour < 18) {
            greeting = "Good Afternoon";
        } else {
            greeting = "Good Evening";
        }

        var currentGreetingText = greetingElement.textContent;
        var name = currentGreetingText.replace(/^Good (Morning|Noon|Afternoon|Evening), /, '');
        greetingElement.textContent = greeting + ", " + name;
    });
</script>

<script>
    function copyText(element) {
        var text = element.textContent; // Use textContent to get the text inside
        navigator.clipboard.writeText(text).then(() => {
            alert(`"${text}" copied to clipboard!`);
        }).catch(err => {
            console.error('Error copying text: ', err);
        });
    }
    </script>

@endsection
