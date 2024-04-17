@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Image @endslot
@slot('title') Generate Image  @endslot
@endcomponent


<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Generate Image</h4>
        <a href="{{ route('template.view', ['slug' => 'image-prompt-idea']) }}">
            <button type="button" class="btn btn-warning btn-load">
                <span class="d-flex align-items-center">
                    <span class="spinner-grow flex-shrink-0" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </span>
                    <span class="flex-grow-1 ms-2">
                       Get Image Prompt Ideas
                    </span>
                </span>
            </button>
        </a>
    </div><!-- end card header -->

    <div class="card-body">
        @if($get_user->images_left == 0) 
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong> No Images Left! </strong> You don't have any <b>Images </b> left to generate!
        </div>
        @else 
       
        @endif
        
        <div class="live-preview">
                <div class="col-xxl-12 justify-content-center">
                   
                    <div class="card">
                        <button type="button" class="btn btn-block waves-effect waves-light col-md-2 m-2 @if($get_user->images_left == 0) btn-danger @else btn-primary @endif">
                            Images Left <span class="images-left badge ms-1 @if($get_user->images_left == 0) bg-dark @else bg-danger @endif">{{ $get_user->images_left }}</span>
                        </button>
                        <div class="card-body">
                           
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills nav-justified col-md-2 mb-3 m-auto" role="tablist">
                                <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-home-1" role="tab">
                                            Dall-E 2
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#pill-justified-profile-1" role="tab">
                                        Dall-E 3
                                    </a>
                                </li>
                              
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content text-muted">
                                <div class="tab-pane" id="pill-justified-home-1" role="tabpanel">


                                    <!-- Base Example -->
                                    <form  action="{{route('generate.image')}}" method="post" class="row g-3">
                                        @csrf

                                        <input type="hidden" name="dall_e_2"  value="dall_e_2">
                                        <div class="accordion accordion-flush col-xxl-6 m-auto mt-2" id="accordionFlushExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingOne">
                                                    <button class="accordion-button collapsed bg-secondary-subtle" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                                        Advance Settings
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                                                    data-bs-parent="#accordionFlushExample">
                                                    <div class="accordion-body">
            

                                                    
                                                    <div class="row">
                                                        
                                                        <div class="col-md-3 mb-3">
                                                            <label for="input1">Image Style</label>
                                                            <select name="style" class="form-control" id="style">
                                                                <option disabled selected="">Enter Image Style</option>
                                                                <option value="natural">Natural</option>
                                                                <option value="vivid">Vivid</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-3 mb-3">
                                                            <label for="input2">Image Quality</label>
                                                            <select name="quality" class="form-control" id="quality">
                                                                <option disabled selected="">Enter Image Quality</option>
                                                                <option value="standard">Standard</option>
                                                                <option value="hd">HD</option>
                                                            </select>
                                                        </div>                                                        

                                                        
                                                        <div class="col-md-3 mb-3">
                                                            <label for="input3">Image Resolution</label>
                                                            <select name="image_res" class="form-control" id="image_res">
                                                                <option disabled selected="">Enter Image Resolution</option>
                                                                <option value="256x256">256x256</option>
                                                                <option value="512x512">512x512</option>
                                                                <option value="1024x1024">1024x1024</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-3 mb-3">
                                                            <label for="input4">No. of Result</label>
                                                         
                                                            <select name="no_of_result" class="form-control" id="no_of_result">
                                                                <option disabled selected="">Enter no. of Images</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                                <option value="10">10</option>
                                                            </select>
                                                        </div>
                                                        


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                 <div class="row g-3 justify-content-center">
                                        <div class="col-xxl-5 col-sm-6">
                                            <div class="search-box">
                                                <input type="text" class="form-control search" name="prompt" id="prompt"
                                                    placeholder="Write prompt to generate Image">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        
                                        <div class="col-xxl-1 col-sm-4">
                                            <div>
                                                <button class="btn btn-rounded btn-primary mb-2">Generate</button>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                                   
                                </div>


                               

                                    
                                <div class="tab-pane active" id="pill-justified-profile-1" role="tabpanel">
                                    <form  action="{{route('generate.image')}}" method="post" class="row g-3">
                                        @csrf
                                    <input type="hidden" name="dall_e_3" value="dall_e_3">

                                    <div class="accordion accordion-flush col-xxl-6 m-auto mt-2" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingOne">
                                                <button class="accordion-button collapsed bg-secondary-subtle" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                                    Advance Settings
                                                </button>
                                            </h2>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                                                data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">


                                                    <div class="row">
                                                        
                                                        <div class="col-md-3 mb-3">
                                                            <label for="input1">Image Style</label>
                                                            <select name="style" class="form-control" id="style">
                                                                <option disabled selected="">Enter Image Style</option>
                                                                <option value="natural">Natural</option>
                                                                <option value="vivid">Vivid</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-3 mb-3">
                                                            <label for="input2">Image Quality</label>
                                                            <select name="quality" class="form-control" id="quality">
                                                                <option disabled selected="">Enter Image Quality</option>
                                                                <option value="standard">Standard</option>
                                                                <option value="hd">HD</option>
                                                            </select>
                                                        </div>                                                        

                                                        
                                                        <div class="col-md-3 mb-3">
                                                            <label for="input3">Image Resolution</label>
                                                            <select name="image_res" class="form-control" id="image_res">
                                                                <option disabled selected="">Enter Image Resolution</option>
                                                                <option value="1024x1024">1024x1024</option>
                                                                <option value="1792x1024">1792x1024</option>
                                                                <option value="1024x1792">1024x1792</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-3 mb-3">
                                                            <label for="input4">No. of Result</label>
                                                            <select name="no_of_result" class="form-control" id="no_of_result">
                                                                <option disabled selected="">Enter No. of Result</option>
                                                                <option value="1">1</option>
                                                                
                                                            </select>
                                                        </div>
                                                        


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 justify-content-center">
                                        <div class="col-xxl-5 col-sm-6">
                                            <div class="search-box">
                                                <input type="text" class="form-control search" name="prompt" id="prompt"
                                                    placeholder="Write prompt to generate Image">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        
                                        <div class="col-xxl-1 col-sm-4">
                                            <div>
                                                <button class="btn btn-rounded btn-primary mb-2">Generate</button>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                </form>
                                </div>
                           
                              
                               
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!--end col-->
                <div class="spinner-border text-primary d-none" role="status" id="loader">
                    <span class="sr-only">Loading...</span>
                </div>

        </div>
        
        <div id="image-container">      
        
        </div>
    
    
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="">
                <div class="card-body">
                    <div class="row gallery-wrapper">
                        @foreach ($images as $item)
                        <div class="element-item col-xxl-3 col-xl-4 col-sm-6 project designing development"  data-category="designing development">
                            <div class="gallery-box card">
                                <div class="gallery-container">
                                    <a class="image-popup" href="{{ asset($item->image) }}" title="">
                                        <img class="gallery-img img-fluid mx-auto" src="{{ asset($item->image) }}" alt="" />
                                        <div class="gallery-overlay">
                                            <h5 class="overlay-caption">{{$item->prompt}}</h5>
                                        </div>
                                    </a>
                                </div>

                               
                            </div>
                        </div>
                        @endforeach
                    </div>
            </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            event.preventDefault(); // Prevent default form submission
            
            // Show loader
        $('#loader').removeClass('d-none');

            // Serialize form data
            var formData = $(this).serialize();
            
            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: '/generate/image',
                data: formData,
                success: function(response) {

                    console.log(response);
                    
                    $('#image-container').empty(); // Clear previous images if any
response.data.forEach(function(imageData) {
    // Create an image element
    var temp = `<a class="image-popup" href="${imageData.url}" title="">
                    <img class="gallery-img img-fluid mx-auto" style="height: 256px; width:256px" src="${imageData.url}" alt="" />
                </a>`;

    // Append the image to the container
    $('#image-container').append(temp);
});

// Initialize Glightbox
$(document).ready(function() {
    const lightbox = GLightbox({
        selector: '.image-popup',
        touchNavigation: true,
        loop: true
    });
});

              

                         var imagesLeft = response.images_left;
                         $('.images-left').text(imagesLeft);

                  // Hide loader
                  $('#loader').addClass('d-none');
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    // You may display an error message or perform any other actions here
                    console.error(xhr.responseText);
                    $('#loader').addClass('d-none');
                }
            });
        });
    });
</script>


@endsection


