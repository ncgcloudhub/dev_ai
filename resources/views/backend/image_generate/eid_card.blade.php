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


<div class="container">

    @if($get_user->images_left == 0) 
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong> No Images Left! </strong> You don't have any <b>Images </b> left to generate!
    </div>
    @else 
   
    @endif

    <div style="background-image: url('https://media.istockphoto.com/id/1371499009/vector/islamic-wallpaper-for-eid-card.jpg?s=612x612&w=0&k=20&c=p1qU0b5JBJ8uXtyXnb7dmj96FjpLAFWXmnXbEYGHbfM=')" class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Popular Holiday Card</h4>
             <button type="button" class="btn waves-effect waves-light @if($get_user->images_left == 0) btn-danger @else btn-primary @endif">
            Images Left <span class="images-left badge ms-1 @if($get_user->images_left == 0) bg-dark @else bg-danger @endif">{{ $get_user->images_left }}</span>
            </button>
        </div><!-- end card header -->
    
        <div class="card-body">
    
            <div class="live-preview">
                    <div class="col-xxl-12 justify-content-center">
    
                        <form  action="{{route('generate.eid.card')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-2 mb-3">
                                    <label for="from" class="form-label">From</label>
                                    <input name="from" id="from" type="text" class="form-control">
                                   
                                </div>
                                
                                <div class="col-2 mb-3">
                                    <label for="to" class="form-label">To</label>
                                    <input name="to" id="to" type="text" class="form-control">
                                   
                                </div>
                               
                                <div class="col-2 mb-3">
                                    <label for="card_style" class="form-label">Card Style</label>
                                    <select name="card_select" class="form-control" id="card_select">
                                        <option disabled selected>Select Card Style</option>
                                        <option value="Family">Family</option>
                                        <option value="Friends">Friends</option>
                                        <option value="Siblings">Siblings</option>
                                        <option value="Relatives">Relatives</option>
                                        <option value="Colleague">Colleague</option>
                                        <option value="Co-Worker">Co-Worker</option>
                                        <option value="Boss">Boss</option>
                                        <option value="Teacher">Teacher</option>
                                        <option value="Mentor">Mentor</option>
                                        <option value="Neighbor">Neighbor</option>
                                        <option value="Acquaintance">Acquaintance</option>
                                        <option value="Coach">Coach</option>
                                        <option value="Student">Student</option>
                                        <option value="Client">Client</option>
                                        <option value="Customer">Customer</option>
                                        <option value="Partner">Partner</option>
                                        <option value="Spouse">Spouse</option>
                                        <option value="Boyfriend">Boyfriend</option>
                                        <option value="Girlfriend">Girlfriend</option>
                                        <option value="Fiancé">Fiancé</option>
                                        <option value="Husband">Husband</option>
                                        <option value="Wife">Wife</option>
                                        <option value="Father">Father</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Brother">Brother</option>
                                        <option value="Sister">Sister</option>
                                        <option value="Grandparent">Grandparent</option>
                                        <option value="Grandchild">Grandchild</option>
                                        <option value="Aunt">Aunt</option>
                                        <option value="Uncle">Uncle</option>
                                        <option value="Cousin">Cousin</option>
                                        <option value="Nephew">Nephew</option>
                                        <option value="Niece">Niece</option>
                                        <option value="In-Law">In-Law</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                

                                <div class="col-3 mb-3">
                                    <label for="holidays" class="form-label">Holiday Name</label>
                                    <select name="holidays" class="form-control" id="holidays">
                                        <option disabled selected="">Select Holiday</option>
                                        <optgroup label="Bangladesh">
                                            <option value="Independence Day">Independence Day</option>
                                            <option value="Victory Day">Victory Day</option>
                                            <option value="Eid-ul-Fitr">Eid-ul-Fitr</option>
                                            <option value="Eid-ul-Adha">Eid-ul-Adha</option>
                                            <option value="Language Martyrs' Day">Language Martyrs' Day</option>
                                            <option value="Bangla New Year">Bangla New Year</option>
                                            <option value="National Mourning Day">National Mourning Day</option>
                                            <option value="Shaheed Day">Shaheed Day</option>
                                            <option value="Bengali Language Day">Bengali Language Day</option>
                                            <option value="Shadhinota Dibosh">Shadhinota Dibosh</option>
                                        </optgroup>
                                        <optgroup label="USA">
                                            <option value="New Year's Day">New Year's Day</option>
                                            <option value="Memorial Day">Memorial Day</option>
                                            <option value="Independence Day">Independence Day</option>
                                            <option value="Labor Day">Labor Day</option>
                                            <option value="Thanksgiving Day">Thanksgiving Day</option>
                                            <option value="Christmas Day">Christmas Day</option>
                                            <option value="New Year's Eve">New Year's Eve</option>
                                            <option value="Easter Sunday">Easter Sunday</option>
                                            <option value="Good Friday">Good Friday</option>
                                            <option value="Mother's Day">Mother's Day</option>
                                            <option value="Father's Day">Father's Day</option>
                                            <option value="Halloween">Halloween</option>
                                            <option value="Veterans Day">Veterans Day</option>
                                            <option value="Black Friday">Black Friday</option>
                                            <option value="Cyber Monday">Cyber Monday</option>
                                           
                                        </optgroup>
                                    </select>
                                </div>
                                
                                
                                <div class="col-md-3 mb-2 d-flex align-items-end">
                                    <button class="btn btn-rounded btn-primary mb-2">Create Card</button>
                                </div>
                            </div><!-- end row -->
                            <div id="error-msg" style="color: red;"></div>
                        </form>
    
                        </div><!-- end card -->
                    </div><!--end col-->
                    <div class="spinner-border text-primary d-none" role="status" id="loader">
                        <span class="sr-only">Loading...</span>
                    </div>
    
            </div>
    
            <div id="image-container" class="d-flex justify-content-center">
    
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
                        <img class="gallery-img img-fluid mx-auto" style="height: 512px; width:512px" src="${imageUrl}" alt="" />
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

<script>
    // Function to validate input length
    function validateInput(inputId) {
        var input = document.getElementById(inputId);
        var inputValue = input.value;
        if (inputValue.length > 4) {
            document.getElementById("error-msg").innerText = "Input should be up to 4 characters.";
            input.value = inputValue.substring(0, 4); // Truncate input to 4 characters
        } else {
            document.getElementById("error-msg").innerText = ""; // Clear error message
        }
    }

    // Add event listeners to both input fields
    document.getElementById("from").addEventListener("input", function() {
        validateInput("from");
    });

    document.getElementById("to").addEventListener("input", function() {
        validateInput("to");
    });
</script>


@endsection


