@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Prompt Library @endslot
@slot('title')Manage @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-1">Manage Prompt Library</h5>
                <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Prompt Tags</button>
            </div>

            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form id="filterForm">
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-6">
                            <select class="form-select" name="category_id" id="category_id" aria-label="Floating label select example">
                                <option disabled selected="">Select Category</option>
                                @foreach ($categories as $item)
                                <option value="{{$item->id}}">{{$item->category_name}}</option>
                                @endforeach
                            </select>
                           
                        </div>
                        <!--end col-->
                        <div class="col-xxl-2 col-sm-6">
                            <select class="form-select" name="subcategory_id" id="subcategory_id" aria-label="Floating label select example">
                                <option disabled selected>Select Subcategory</option>
                            </select>
                            
                        </div>
                        <!--end col-->
                       
                        <div class="col-xxl-1 col-sm-4">
                            <div>
                                <button type="submit" class="btn btn-secondary w-100"> 
                                    <i class="ri-equalizer-fill me-1 align-bottom"></i> Filters
                                </button>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </form>
            </div>
            
            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">ID.</th>
                            <th scope="col">Prompt Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Category</th>
                            <th scope="col">Sub Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prompt_library as $index => $item)
                        <tr data-category="{{ $item->category->category_name }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('prompt.view', ['slug' => $item->slug]) }}" class="fw-medium link-primary">{{$item->prompt_name}}</a>
                            </td>
                             <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">{{$item->description}}</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{$item->category->category_name}}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{$item->subcategory->sub_category_name}}
                                </div>
                            </td>
                          

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Right Side Off Canvas --}}
 <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Popular Prompts Categories</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 overflow-hidden">
        <div data-simplebar style="height: calc(100vh - 112px);">
            <div class="acitivity-timeline p-4">
                @foreach ($prompt_library_category as $item)
                <span class="badge badge-label bg-primary category-label"><i class="mdi mdi-circle-medium"></i>{{$item->category_name}}</span>
            @endforeach
            
            </div>
        </div>
    </div>
    <div class="offcanvas-foorter border p-3 text-center">
        <a href="javascript:void(0);">View All Acitivity <i class="ri-arrow-right-s-line align-middle ms-1"></i></a>
    </div>
</div>


@endsection
@section('script')

<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/gallery.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<!-- Include jQuery from CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>
$(document).ready(function() {
    if (!$.fn.DataTable.isDataTable('#alternative-pagination')) {
        var table = $('#alternative-pagination').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [10, 25, 50, 75, 100],
            "pageLength": 10,
            "responsive": true,
            "autoWidth": false,
            "columnDefs": [
                { "orderable": false, "targets": [0, 4, 5] },
                { "className": "text-center", "targets": [0, 4, 5] }
            ]
        });
    }

});

</script>

{{-- OffCanvas Filter --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var offcanvasRight = document.getElementById('offcanvasRight');
        var offcanvasCategories = offcanvasRight.querySelectorAll('.acitivity-item');
        var tableRows = document.querySelectorAll('#alternative-pagination tbody tr');
        var categoryLabels = document.querySelectorAll('.category-label'); // Selecting category labels

        // Function to filter table rows based on category
        function filterTable(category) {
            tableRows.forEach(function (row) {
                if (category === 'All' || row.getAttribute('data-category') === category) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

       // Event listener for category selection
    categoryLabels.forEach(function (category) {
        category.addEventListener('click', function () {
            var selectedCategory = category.textContent.trim(); // Get text content of the category label
            filterTable(selectedCategory);

            // Remove 'active' class from all category labels
            categoryLabels.forEach(function (label) {
                label.classList.remove('bg-danger');
            });

            // Add 'active' class to the clicked category label
            category.classList.add('bg-danger');
        });
    });

    });
</script>




{{-- SUB CATEGORY PROMPT--}}
<script>
    document.getElementById('category_id').addEventListener('change', function() {
    var categoryId = this.value;
    fetch('/prompt/subcategories/' + categoryId)
        .then(response => response.json())
        .then(data => {
            var subcategorySelect = document.getElementById('subcategory_id');
            subcategorySelect.innerHTML = '';
            data.forEach(subcategory => {
                var option = document.createElement('option');
                option.value = subcategory.id;
                option.text = subcategory.sub_category_name;
                subcategorySelect.appendChild(option);
            });
        });
});

</script>

<script>
    document.getElementById('filterForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var categoryId = document.getElementById('category_id').value;
        var subcategoryId = document.getElementById('subcategory_id').value;
        
        fetch(`/prompt/filter-prompts?category_id=${categoryId}&subcategory_id=${subcategoryId}`)
            .then(response => response.json())
            .then(data => {
                var tableBody = document.querySelector('#alternative-pagination tbody');
                tableBody.innerHTML = '';
                data.forEach(function(item, index) {
                    var row = `<tr>
                        <td>${index + 1}</td>
                        <td><a href="/user/details/${item.id}" class="fw-medium link-primary">${item.prompt_name}</a></td>
                        <td><div class="d-flex align-items-center"><div class="flex-grow-1">${item.description}</div></div></td>
                        <td><div class="d-flex align-items-center">${item.category.category_name}</div></td>
                        <td><div class="d-flex align-items-center">${item.subcategory.sub_category_name}</div></td>
                        <td>
                            <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                <a href="/prompt/edit/${item.id}" class="text-primary d-inline-block edit-item-btn">
                                    <i class="ri-pencil-fill fs-16"></i>
                                </a>
                            </li>
                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                <a class="text-danger d-inline-block remove-item-btn" href="/prompt/delete/${item.id}" onclick="return confirm('Are you sure you want to delete this Prompt')">
                                    <i class="ri-delete-bin-5-fill fs-16"></i>
                                </a>
                            </li>
                        </td>
                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            });
    });
</script>


@endsection
