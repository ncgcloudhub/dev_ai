@extends('admin.layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Image @endslot
@slot('title') Eid Card  @endslot
@endcomponent


<div style="background-image: url('https://as1.ftcdn.net/v2/jpg/04/92/65/14/1000_F_492651409_NjJ2qPkoBdA4DJ1NHIdnaQ7DZUz8fbh6.jpg')" class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Popular Eid Card</h4>
        <button type="button" class="images-left btn btn-outline-primary">
            Images Left <span class="badge bg-danger ms-1">{{ $get_user->images_left }}</span>
        </button>
    </div><!-- end card header -->

    <div class="card-body">
      
        <div class="live-preview">
                <div class="col-xxl-12 justify-content-center">
                   
                    <form  action="{{route('generate.eid.card')}}" method="post">
                        @csrf
                            <div class="row">
                                <div class="col mb-3">
                                    <select name="card_select" class="form-control" id="card_select">
                                        <option disabled selected="">Select Card Style</option>
                                        <option value="genarate an eid card of family">Family</option>
                                        <option value="genarate an eid card of friends">Freinds</option>
                                        <option value="genarate an eid card of car">Cars</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-rounded btn-primary mb-2">Generate</button>
                                </div>
                    </form>
                         
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

{{-- <div class="container">
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
</div> --}}


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
                url: '/eid/card/generate',
                data: formData,
                success: function(response) {

                    console.log(response);
                    
                    $('#image-container').empty(); // Clear previous images if any
                   
                  // Check if responseData.data is an array and not empty
    if (Array.isArray(response.data) && response.data.length > 0) {
        // Access the URL of the first image in the data array
        var imageUrl = response.data[0].url;

        // Create an image element
        var temp = `<a class="image-popup" href="${imageUrl}" title="">
                        <img class="gallery-img img-fluid mx-auto" style="height: 256px; width:256px" src="${imageUrl}" alt="" />
                    </a>`;

        // Append the image to the container
        $('#image-container').append(temp);
    } else {
        // Handle case where no image data is returned
        console.log('No image data returned');
    }



// Initialize Glightbox
$(document).ready(function() {
    const lightbox = GLightbox({
        selector: '.image-popup',
        touchNavigation: true,
        loop: true
    });
});

                         var imagesLeft = response.images_left;
                         console.log("Images Left: " + imagesLeft);
                         $('.images-left').html("Images Left: " + imagesLeft);

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

