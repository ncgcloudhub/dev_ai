@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('aicontentcreator.manage')}}">Education</a> @endslot
@slot('title') Manage Tools @endslot
@endcomponent
<a href="{{ route('add.education.tools') }}" class="btn btn-lg gradient-btn-3 my-1">Add</a>

<section class="py-5 gradient-background-1 position-relative">
    <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-sm">
                <div>
                    <h4 class="text-white mb-0 fw-semibold">Create Your Contents with our Pre-defined Tools</h4>
                </div>
            </div>

        </div>

    </div>

</section>

<section class="section bg-light" id="marketplace">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2 class="mb-3 fw-bold lh-base">Explore Products</h2>
                    <p class="text-muted mb-4">Collection widgets specialize in displaying many elements of the same type, such as a collection of pictures from a collection of articles.</p>
                    
                 
                    <ul class="nav nav-pills filter-btns justify-content-center" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium active" type="button" data-filter="all">All Items</button>
                        </li>
                        @foreach($categories as $category)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-medium" type="button" data-filter="{{ Str::slug($category) }}">{{ $category }}</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($tools as $tool)
            <div class="col-lg-4 product-item {{ Str::slug($tool->category) }}">
                <div class="card explore-box card-animate">
                    <div class="bookmark-icon position-absolute top-0 end-0 p-2">
                        <button type="button" class="btn btn-icon" data-bs-toggle="button" aria-pressed="true">
                            <i class="mdi mdi-cards-heart fs-16"></i>
                        </button>
                    </div>
                    <div class="explore-place-bid-img">
                        <img src="{{ asset('storage/' . $tool->image) }}" alt="" class="card-img-top explore-img" />
                        <div class="bg-overlay"></div>
                        <div class="place-bid-btn">
                            <a href="{{ route('tool.show', $tool->id) }}" class="btn btn-primary">
                                <i class="ri-auction-fill align-bottom me-1"></i>Explore
                            </a>
                            <a href="{{ route('tools.edit', $tool->id) }}" class="btn btn-warning">
                                <i class="ri-edit-2-fill align-bottom me-1"></i>Edit
                            </a>
                            <form action="{{ route('tools.destroy', $tool->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this tool?');">
                                    <i class="ri-delete-bin-5-fill align-bottom me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="fw-medium mb-0 float-end favorite-wrapper">
                            <button class="favorite-button" data-id="{{ $tool->id }}" style="border: none; background: none; cursor: pointer;">
                                <i class="{{ $tool->is_favorited ? 'mdi mdi-heart' : 'mdi mdi-heart-outline' }} text-danger align-middle"></i>
                            </button>
                            19.29k <!-- This can be dynamically generated if needed -->
                        </p>
                        <h5 class="mb-1 fs-16">
                            <a href="apps-nft-item-details.html" class="text-body">{{ $tool->name }}</a>
                        </h5>
                        <p class="text-muted fs-14 mb-0">{{ $tool->description }}</p>
                    </div>
                    
                    <div class="card-footer border-top border-top-dashed">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 fs-14">
                                <i class="ri-price-tag-3-fill text-warning align-bottom me-1"></i> Highest: <span class="fw-medium">{{ rand(10, 500) }}ETH</span>
                            </div>
                            <h5 class="flex-shrink-0 fs-14 text-primary mb-0">{{ rand(5, 450) }} ETH</h5>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        
        
        </div>
    </div>
</section>


<button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>


@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/nft-landing.init.js') }}"></script>

<script>
    $(document).ready(function() {
    $('.favorite-button').on('click', function() {
        let button = $(this);
        let icon = button.find('i');
        let toolsId = button.data('id'); // Get the tools ID
        let isFavorited = icon.hasClass('mdi-heart'); // Check if already favorited
        
        $.ajax({
            url: '{{ route('toggle.favorite') }}', // Use the named route for the URL
            method: 'POST',
            data: {
                tools_id: toolsId, // The ID of the tool being favorited
                _token: '{{ csrf_token() }}' // CSRF token for security
            },
            success: function(response) {
                if (response.success) {
                    // Get the icon inside the button
                    let icon = button.find('i'); // Use `button` from the closure

                    // Toggle heart icon based on action
                    if (response.action === 'added') {
                        icon.removeClass('mdi-heart-outline').addClass('mdi-heart'); // Filled heart
                    } else {
                        icon.removeClass('mdi-heart').addClass('mdi-heart-outline'); // Hollow heart
                    }
                }
            },

            error: function() {
                alert('Failed to toggle favorite. Please try again.');
            }
        });
    });
});

</script>
@endsection