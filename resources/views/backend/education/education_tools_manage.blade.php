@extends('admin.layouts.master')
@section('title') Education Tools @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('manage.education.tools')}}">Education Tools</a> @endslot
@slot('title') Manage Tools @endslot
@endcomponent



<section class="py-3 gradient-background-1 position-relative">
        <div class="row align-items-center">
            <div class="col-sm">
                <div>
                    <h4 class="text-white mb-0 fw-semibold text-center mb-2">Empower Your Classroom with AI-Driven Tools @if(Auth::user()->role === 'admin')
                        @can('education.manageTools.add')
                            <a href="{{ route('add.education.tools') }}" class="badge badge-gradient-purple">ADD TOOL</a>
                        @endcan
                    @endif</h4>
                    <p class="text-white text-center">A suite of innovative, time-saving tools designed to transform your teaching experience. From crafting comprehensive lesson plans and unpacking educational standards to generating dynamic group activities, these resources are tailored to meet your specific needs. Unlock the potential of AI to foster creativity, improve efficiency, and maximize student engagement—all in one place! </p>
                </div>
            </div>
        </div>
</section>

<div class="row">
    <div class="col-xl-2 col-md-12 mb-3 mt-3">
        <div class="card mb-3 mt-3">
            <div class="card-body">
                <div class="d-flex mb-3 align-items-center">
                    <h6 class="card-title mb-0 flex-grow-1 gradient-text-1-bold">New Tools</h6>
                </div>
                <ul class="list-unstyled vstack gap-3 mb-0">
                    @foreach ($newTools as $newTool)
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $newTool->image) }}" alt="" class="avatar-xs rounded-3">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                  
                                    <h6 class="mb-1"><a href="{{ route('tool.show', ['id' => $newTool->id, 'slug' => $newTool->slug]) }}" class="gradient-text-1">{{ $newTool->name }}</a></h6>
                                    <p class="text-muted mb-0">{{ $newTool->description ?? 'Tool Description' }}</p>
                                </div>
                             
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-md-12 px-3">
        <div class="row justify-content-center">
            <form>
                <div class="row g-3 justify-content-center my-3">
                    <div class="col">
                        <div class="search-box" id="search-tour">
                            <input type="text" class="form-control search border-color-purple"
                                placeholder="Search for Templates">
                            <i class="ri-search-line search-icon color-purple"></i>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </form>

            <div class="noresult my-2" style="display: none">
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                        trigger="loop" colors="primary:#405189,secondary:#0ab39c"
                        style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Sorry! No Tools Found</h5>
                    <p class="text-muted">We've searched all tools related to education. We did not find any tools matching your search.</p>

                    <form action="{{ route('template.module.feedback') }}" method="POST">
                        @csrf
                        <div class="form-group d-flex gap-2">
                            <input type="text" class="form-control border-color-purple" id="feedbackText" name="text" placeholder="Enter your feedback" required>
                            <button type="submit" class="btn gradient-btn-3">Submit</button>
                        </div>
                    </form>
                    
                </div>
            </div>

            {{-- 2nd Col START--}}
            <div class="col-xl-12 col-md-12">
                <div class="text-center mb-3">
                    <ul class="nav nav-pills filter-btns justify-content-center" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium active" type="button" data-filter="all">All Items</button>
                        </li>
                        @foreach($categories as $category)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-medium" type="button" data-filter="{{ Str::slug($category->category_name) }}">{{ $category->category_name }}</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            {{-- 2nd Col END--}}
        </div>

        <div class="row">
            @foreach($tools as $tool)
            <div class="col-lg-4 product-item template-card {{ Str::slug($tool->educationtools_category->category_name) }}" data-search="{{ strtolower($tool->name . ' ' . $tool->description) }}">
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
                            <a href="{{ route('tool.show', ['id' => $tool->id, 'slug' => $tool->slug]) }}" class="btn btn-primary">
                                <i class="ri-auction-fill align-bottom me-1"></i>Explore
                            </a>
                            @if(Auth::user()->role === 'admin')
                                @can('education.manageTools.edit')
                                    <a href="{{ route('tools.edit', $tool->id) }}" class="btn btn-warning">
                                        <i class="ri-edit-2-fill align-bottom me-1"></i>Edit
                                    </a>
                                @endcan
                            
                                @can('education.manageTools.delete')
                                    <form action="{{ route('tools.destroy', $tool->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this tool?');">
                                            <i class="ri-delete-bin-5-fill align-bottom me-1"></i>Delete
                                        </button>
                                    </form>
                                @endcan
                           
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="fw-medium mb-0 float-end favorite-wrapper">
                            <button class="favorite-button" data-id="{{ $tool->id }}" style="border: none; background: none; cursor: pointer;">
                                <i class="{{ $tool->is_favorited ? 'mdi mdi-heart' : 'mdi mdi-heart-outline' }} text-danger align-middle"></i>
                            </button>
                           
                        </p>
                        <h5 class="mb-1 fs-16">
                            <a href="{{ route('tool.show', ['id' => $tool->id, 'slug' => $tool->slug]) }}" class="text-body">{{ $tool->name }}</a>
                        </h5>
                        <p class="text-muted fs-14 mb-0">{{ $tool->description }}</p>
                    </div>
                    
                    {{-- <div class="card-footer border-top border-top-dashed">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 fs-14">
                                <i class="ri-price-tag-3-fill text-warning align-bottom me-1"></i> Highest: <span class="fw-medium">{{ rand(10, 500) }}ETH</span>
                            </div>
                            <h5 class="flex-shrink-0 fs-14 text-primary mb-0">{{ rand(5, 450) }} ETH</h5>
                        </div>
                    </div> --}}
                </div>
            </div>
        @endforeach
        </div>
    </div>

    <div class="col-xl-2 col-md-12 mb-3 mt-3">
        <div class="card mb-3 mt-3">
            <div class="card-body">
                <div class="d-flex mb-3 align-items-center">
                    <h6 class="card-title mb-0 flex-grow-1 gradient-text-1-bold">Popular Tools</h6>
                </div>
                
                <ul class="list-unstyled vstack gap-3 mb-0">
                    @foreach ($popularTools as $popularTool)
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $popularTool->image) }}" alt="" class="avatar-xs rounded-3">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                  
                                    <h6 class="mb-1"><a href="{{ route('tool.show', ['id' => $popularTool->id, 'slug' => $popularTool->slug]) }}" class="gradient-text-1">{{ $popularTool->name }}</a></h6>
                                    <p class="text-muted mb-0">{{ $popularTool->description ?? 'Tool Description' }}</p>
                                </div>
                             
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>

@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/nft-landing.init.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.querySelector('.search');
        const templateCards = document.querySelectorAll('.template-card');
        const noResultMessage = document.querySelector('.noresult');

        searchInput.addEventListener('keyup', function (event) {
            const searchTerm = event.target.value.trim().toLowerCase();
            let found = false;

            templateCards.forEach(function (card) {
                const searchContent = card.dataset.search;
                if (searchContent.includes(searchTerm)) {
                    card.style.display = 'block';
                    found = true;
                } else {
                    card.style.display = 'none';
                }
            });

            if (!found) {
                noResultMessage.style.display = 'block';
            } else {
                noResultMessage.style.display = 'none';
            }
        });
    });
</script>

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