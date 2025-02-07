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
                            <div class="d-flex">
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
            <div class="row g-3 justify-content-center my-3">
                <div class="col">
                    <div class="d-flex align-items-center mb-3">
                        <!-- Items per page dropdown -->
                        <div class="me-3">
                            <select id="itemsPerPage" class="form-select w-auto">
                                <option value="1" selected>1</option>
                                <option value="2">2</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="9999">All</option> <!-- Large value for "All" -->
                            </select>
                        </div>
            
                        <!-- Search box takes full width -->
                        <div class="flex-grow-1 position-relative">
                            <input id="searchInput" 
                                   type="text" 
                                   class="form-control search border-color-purple w-100" 
                                   placeholder="Search for Templates">
                            <i class="ri-search-line search-icon color-purple position-absolute top-50 end-0 translate-middle-y me-2"></i>
                        </div>
                    </div>               
                </div>
                <!--end col-->
            </div>
            
                <!--end row-->

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
        
        <div class="row" id="toolGrid">
            @foreach($tools as $tool)
                <div class="col-lg-4 product-item template-card {{ Str::slug($tool->educationtools_category->category_name) }}" 
                     data-search="{{ strtolower($tool->name . ' ' . $tool->description) }}">
                    <div class="card explore-box card-animate">
                        <div class="explore-place-bid-img">
                            <img src="{{ asset('storage/' . $tool->image) }}?v={{ $tool->image_version }}" 
                                 alt="" class="card-img-top explore-img" loading="lazy" />
                        </div>
                        <div class="card-body">
                            <h5 class="mb-1 fs-16">
                                <a href="{{ route('tool.show', ['id' => $tool->id, 'slug' => $tool->slug]) }}" 
                                   class="text-body">{{ $tool->name }}</a>
                            </h5>
                            <p class="text-muted fs-14 mb-0">{{ $tool->description }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div id="paginationControls" class="pagination justify-content-center mt-3">
            <!-- Pagination buttons will be dynamically inserted here -->
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
                            <div class="d-flex">
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

{{-- <script>
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
</script> --}}

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


<script>
   $(document).ready(function () {
    let itemsPerPage = 1; // Default items to show
    let currentPage = 1; // Current page

    function updateGrid() {
        let searchValue = $("#searchInput").val().toLowerCase();
        let items = $(".product-item");
        let totalVisibleItems = 0;

        // Hide all items first
        items.hide();

        // Filter items based on search
        items.each(function () {
            let matchesSearch = $(this).attr("data-search").toLowerCase().includes(searchValue);
            if (matchesSearch) {
                totalVisibleItems++; // Count total visible items for pagination
            }
        });

        // Calculate the range of items to show for the current page
        let startIndex = (currentPage - 1) * itemsPerPage;
        let endIndex = startIndex + itemsPerPage;
        let visibleCount = 0;

        // Show items within the current page range
        items.each(function () {
            if ($(this).attr("data-search").toLowerCase().includes(searchValue)) {
                if (visibleCount >= startIndex && visibleCount < endIndex) {
                    $(this).show();
                }
                visibleCount++;
            }
        });

        // Update pagination controls
        updatePaginationControls(totalVisibleItems);
    }

    function updatePaginationControls(totalVisibleItems) {
        let totalPages = Math.ceil(totalVisibleItems / itemsPerPage);
        let paginationHtml = '';

        if (totalPages > 1) {
            paginationHtml += `<button class="page-item page-link" ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})">Previous</button>`;
            for (let i = 1; i <= totalPages; i++) {
                paginationHtml += `<button class="page-item page-link ${currentPage === i ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
            }
            paginationHtml += `<button class="page-item page-link" ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})">Next</button>`;
        }

        $("#paginationControls").html(paginationHtml);
    }

    // Change page function
    window.changePage = function (page) {
        currentPage = page;
        updateGrid();
    };

    // Search functionality
    $("#searchInput").on("keyup", function () {
        currentPage = 1; // Reset to first page on search
        updateGrid();
    });

    // Change items per page
    $("#itemsPerPage").on("change", function () {
        itemsPerPage = parseInt($(this).val());
        currentPage = 1; // Reset to first page on items per page change
        updateGrid();
    });

    // Initialize on page load
    updateGrid();
});
    </script>
    
@endsection