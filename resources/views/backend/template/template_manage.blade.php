@extends('admin.layouts.master')
@section('title', $seo->title)

@section('description', $seo->description)

@section('keywords', $seo->keywords)
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Templates @endslot
@slot('title') All Templates @endslot
@endcomponent

<style>
   .template-card:hover {
    transform: scale(.95);
    transition: transform 0.3s ease;
    
} 

.template-card:hover .card-body {
    background-color: #d4e9f0; /* Light blue background color */
}

</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card" id="orderList">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Templates</h5>
                   
                   <button id="templateManageTourButton" class="btn gradient-button text-white">Template Tour</button>


                   
                </div>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form>
                    <div class="row g-3 justify-content-center">
                        <div class="col-xxl-5 col-sm-6">
                            <div class="search-box" id="search-tour">
                                <input type="text" class="form-control search"
                                    placeholder="Search for order ID, customer, order status or something...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <!--end col-->
                        
                        <div class="col-xxl-1 col-sm-4">
                            <div>
                                <button type="button" class="btn btn-primary w-100" id="enter-button"
                                    onclick="SearchData();"> <i
                                        class="ri-search-fill me-1 align-bottom"></i>
                                    Search
                                </button>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </form>
            </div>
            <div class="card-body pt-0">
                <div>
                    <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3 justify-content-center" role="tablist">
                        <li class="nav-item" id="category-tour">
                            <a class="nav-link n1 active All py-3" data-bs-toggle="tab" id="All"
                                href="#home1" role="tab" aria-selected="true">
                                <i class="ri-store-2-fill me-1 align-bottom"></i> All Templates
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link requeste py-3" data-bs-toggle="tab" id="requeste"
                                href="#requested" role="tab" aria-selected="false">
                                <i class="ri-store-2-fill me-1 align-bottom"></i> requested Templates
                            </a>
                        </li>

                        @foreach ($templatecategories as $item)
                            <li class="nav-item">
                                <a class="nav-link n1 py-3 {{$item->category_name}}" data-bs-toggle="tab" id="{{$item->id}}"
                                    href="#{{$item->id}}" role="tab" aria-selected="false">
                                    <i class="{{$item->category_icon}}"></i> {{$item->category_name}}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="table-card m-1">
                        <div class="row template-row">
                            @foreach ($templates as $item)
                            <div class="col-md-3 p-3 template-card" data-id="{{ $item->id }}" data-category="{{$item->category_id}}" data-search="{{ strtolower($item->template_name . ' ' . $item->description) }}" @if ($loop->first) id="category-details" @endif>
                               
                                <div class="card" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                                    <a href="{{ route('template.view', ['slug' => $item->slug]) }}">
                                        <div class="card-body">
                                            <div style="width: 42px; height: 42px; border-radius: 50%; background-color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 .125rem .3rem -0.0625rem rgba(0,0,0,.1),0 .275rem .75rem -0.0625rem rgba(249,248,249,.06)">
                                                <img width="22px" src="/build/images/templates/{{$item->icon}}.png" alt="" class="img-fluid">
                                            </div>
                                            <h3 class="fw-medium link-primary">{{$item->template_name}}</h3>
                                            <p style="height: 3em; overflow: hidden; color:black;" class="card-text customer_name">{{$item->description}}</p>
                                            <small class="text-muted">{{$item->total_word_generated}} Words generated</small>
                                            <div dir="ltr">
                                                @php
                                                    $userRating = $userRatings[$item->id] ?? null;
                                                @endphp
                                                <div id="rater-onhover-{{$item->id}}" class="align-middle" data-user-rating="{{ $userRating }}"></div>
                                                <span class="ratingnum badge bg-info align-middle ms-2">{{ number_format($item->averageRating(), 1) }} stars</span>
                                            </div>
                                            @if(auth()->user()->role == 'admin')
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                                                        {{-- <a href="apps-ecommerce-order-details" class="text-primary d-inline-block">
                                                            <i class="ri-eye-fill fs-16"></i>
                                                        </a> --}}
                                                    </li>
                                                    <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                        <a href="{{route('template.edit',$item->id)}}" class="text-primary d-inline-block edit-item-btn">
                                                            <i class="ri-pencil-fill fs-16"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                                        {{-- <a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" href="#deleteOrder">
                                                            <i class="ri-delete-bin-5-fill fs-16"></i>
                                                        </a> --}}
                                                    </li>
                                                </ul>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                            </div>


                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                        trigger="loop" colors="primary:#405189,secondary:#0ab39c"
                                        style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Sorry! No Templates Found</h5>
                                    <p class="text-muted">We've searched more than 71+ Templates. We did not find any templates matching your search.</p>

                                    <form action="{{ route('template.module.feedback') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="feedbackText">Your Feedback</label>
                                            <input type="text" class="form-control" id="feedbackText" name="text" placeholder="Enter your feedback" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                                    </form>
                                    
                                </div>
                            </div>



                            <div class="requested" style="display: none">
                                <div class="text-center">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Module</th>
                                                    <th>Feedback</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($userRequestFeedbacks as $feedback)
                                                    <tr>
                                                        <td>{{ $feedback->module }}</td>
                                                        <td>{{ $feedback->text }}</td>
                                                        <td>{{ $feedback->status }}</td>
                                                        <td>{{ $feedback->created_at->format('Y-m-d') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    
                                </div>
                            </div>
                            
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="pagination-wrap hstack gap-2">
                            <a class="page-item pagination-prev disabled" href="#">
                                Previous
                            </a>
                            <ul class="pagination listjs-pagination mb-0"></ul>
                            <a class="page-item pagination-next" href="#">
                                Next
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade flip" id="deleteOrder" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body p-5 text-center">
                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json"
                                    trigger="loop" colors="primary:#405189,secondary:#f06548"
                                    style="width:90px;height:90px"></lord-icon>
                                <div class="mt-4 text-center">
                                    <h4>You are about to delete a order ?</h4>
                                    <p class="text-muted fs-15 mb-4">Deleting your order will remove
                                        all of
                                        your information from our database.</p>
                                    <div class="hstack gap-2 justify-content-center remove">
                                        <button
                                            class="btn btn-link link-success fw-medium text-decoration-none"
                                            data-bs-dismiss="modal"><i
                                                class="ri-close-line me-1 align-middle"></i>
                                            Close</button>
                                        <button class="btn btn-danger" id="delete-record">Yes,
                                            Delete It</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end modal -->
            </div>
        </div>

    </div>
    <!--end col-->
</div>
<!--end row-->

@endsection
@section('script')



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/rater-js/index.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/rating.init.js') }}"></script>
<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>


<!--ecommerce-customer init js -->
<script src="{{ URL::asset('build/js/pages/ecommerce-order.init.js') }}"></script>
 
<script src="{{ URL::asset('build/js/app.js') }}"></script>


<script>
    $(document).ready(function() {
        $('.n1').on('click', function() {

            // Remove 'active' class from all nav links
        $('.n1').removeClass('active');
        
        // Add 'active' class to the clicked nav link
        $(this).addClass('active');
        
            var category = $(this).attr('id');
            if (category === 'All') {
            $('.template-card').show(); // Show all templates
            $('.requested').hide();
        } else {
            $('.template-card').hide(); // Hide all templates initially
            $('.template-card[data-category="' + category + '"]').show(); 
            $('.requested').hide();// Show templates that match the selected category
        }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.requeste').on('click', function() {

            // Remove 'active' class from all nav links
        $('.n1').removeClass('active');
        
        // Add 'active' class to the clicked nav link
        $(this).addClass('active');
        
            var requested = $(this).attr('id');
            if (requested === 'requeste') {
            $('.requested').show(); // Show all templates
            $('.template-card').hide();
        } else {
            $('.template-card').hide(); // Hide all templates initially
            $('.template-card[data-category="' + category + '"]').hide(); // Show templates that match the selected category
            $('.requested').show();
        }
        });
    });
</script>

{{-- SEARCH --}}
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

@endsection

