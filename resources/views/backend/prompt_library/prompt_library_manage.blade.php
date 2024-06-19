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
                <a href="{{ route('prompt.export') }}" class="btn btn-primary">Export Prompt Library</a>
                <form id="myForm" method="POST" action="{{ route('import.store') }}" class="forms-sample"   enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                         <input type="file" name="import_file" class="form-control" >
                    </div>
    
    
    
    
      <button type="submit" class="btn btn-inverse-warning">Import </button>
    
                </form>
    
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
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prompt_library as $index => $item)
                        <tr>
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
                                    {{-- {{$item->subcategory->sub_category_name}} --}}
                                </div>
                            </td>
                            <td>
                                <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                    <a href="{{route('prompt.edit',$item->id)}}" class="text-primary d-inline-block edit-item-btn">
                                        <i class="ri-pencil-fill fs-16"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                    <a class="text-danger d-inline-block remove-item-btn" href="{{route('prompt.delete',$item->id)}}" onclick="return confirm('Are you sure you want to delete this Prompt')">
                                        <i class="ri-delete-bin-5-fill fs-16"></i>
                                    </a>
                                </li>
                            </td>
                           

                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

    // Event delegation for the toggle button
    $(document).on('click', '.active_button', function() {
        var userId = $(this).data('user-id');
        var toggleSwitch = $(this);

        // Send AJAX request to update the image status
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: '/user/update/status',
            data: {
                user_id: userId
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                // Handle success response
                console.log(response);

                // Update the status text in the table cell
                if (toggleSwitch.is(':checked')) {
                    toggleSwitch.closest('td').prev().text('active');
                } else {
                    toggleSwitch.closest('td').prev().text('inactive');
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(error);
                console.log('inside Error');
            }
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
