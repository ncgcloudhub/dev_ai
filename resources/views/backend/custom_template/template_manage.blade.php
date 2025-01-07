@extends('admin.layouts.master')
@section('title') @lang('translation.orders') @endsection
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
                   
                </div>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form>
                    <div class="row g-3 justify-content-center">
                        <div class="col-xxl-5 col-sm-6">
                            <div class="search-box">
                                <input type="text" class="form-control search"
                                    placeholder="Search for order ID, customer, order status or something...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <!--end col-->
                        
                        <div class="col-xxl-1 col-sm-4">
                            <div>
                                <button type="button" class="btn btn-primary w-100"
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
                    <ul class="nav nav-tabs nav-pills nav-tabs-custom nav-success mb-3 justify-content-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link n1 active All py-3" data-bs-toggle="tab" id="All"
                                href="#home1" role="tab" aria-selected="true">
                                <i class="ri-store-2-fill me-1 align-bottom"></i> All Templates
                            </a>
                        </li>
                        @foreach ($customtemplatecategories as $item)
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
                           
                            <div class="col-md-3 p-3 template-card" data-category="{{$item->category_id}}">
                                
                                <div class="card" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                                    <div class="card-body">
                                        <div style="width: 42px; height: 42px; border-radius: 50%; background-color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 .125rem .3rem -0.0625rem rgba(0,0,0,.1),0 .275rem .75rem -0.0625rem rgba(249,248,249,.06)">
                                            {{-- <i style="font-size: 24px; color: #333;" class="{{$item->icon}}"></i> --}}
                                            <img width="22px" src="/build/images/templates/{{$item->icon}}.png" alt="" class="img-fluid">
                                        </div>
                                        <h3 ><a href="{{ route('custom.template.view', ['id' => $item->id]) }}" class="fw-medium link-primary">{{$item->template_name}}</a></h3>
                                        <p style="height: 3em; overflow: hidden;" class="card-text customer_name">{{$item->description}}</p>
                                       
                                        <small class="text-muted">0 Words generated</small>
                                      
                                        <ul class="list-inline hstack gap-2 mb-0">
                                           
                                            @can('customTemplate.edit')
                                            <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                <a href="{{route('custom.template.edit',$item->slug)}}" class="text-primary d-inline-block edit-item-btn">
                                                    <i class="ri-pencil-fill fs-16"></i>
                                                </a>
                                            </li>  
                                            @endcan

                                            @can('customTemplate.delete')
                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                                <a href="{{route('custom.template.delete',$item->id)}}" onclick="return confirm('Are you sure you want to delete this Template')" class="text-danger d-inline-block remove-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"> <i class="ri-delete-bin-5-fill fs-16"></i> </a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
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
                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                <p class="text-muted">We've searched more than 150+ Orders We did
                                    not find any
                                    orders for you search.</p>
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

<script src="build/libs/list.js/list.js.min.js"></script>
        <script src="build/libs/list.pagination.js/list.pagination.js.min.js"></script>

        <!--ecommerce-customer init js -->
        <script src="build/js/pages/ecommerce-order.init.js"></script>


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
        } else {
            $('.template-card').hide(); // Hide all templates initially
            $('.template-card[data-category="' + category + '"]').show(); // Show templates that match the selected category
        }
        });
    });
</script>

@endsection

