@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <h4>DALL·E Generated Images</h4>

    <div class="modal fade" id="promptModal" tabindex="-1" aria-labelledby="promptModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="promptModalLabel">Prompt Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="promptForm">
                        <div class="mb-3">
                            <label for="promptText" class="form-label">Prompt</label>
                            <textarea class="form-control" id="promptText" name="prompt" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category (Image)</label>
                            <select class="form-select" id="category" name="category">
                                @foreach($prompt_category as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subcategory" class="form-label">Subcategory</label>
                            <select class="form-select" id="subcategory" name="subcategory">
                                @foreach ($prompt_sub_categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->sub_category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="promptName" class="form-label">Prompt Name</label>
                            <input type="text" class="form-control" id="promptName" name="prompt_name" placeholder="Enter prompt name">
                        </div>
                        <div class="mb-3">
                            <label for="details" class="form-label">Details</label>
                            <textarea class="form-control" id="details" name="details" rows="3" placeholder="Details will be fetched here"></textarea>
                        </div>
                        <button type="button" id="fetchDataButton" class="btn btn-primary">Fetch Details</button>
                        <button type="button" id="savePromptButton" class="btn btn-success">Save to Library</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table id="dalleImageTable" class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>SR No.</th>
                <th>Image</th>
                <th>Prompt</th>
                <th>User ID/Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    <button type="button" id="bulkFetchSaveButton" class="btn btn-warning">Bulk Fetch & Save</button>

</div>
@endsection

@push('styles')
<!-- DataTables Bootstrap 5 CSS -->
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
@endpush

@push('scripts')
<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>

<script>
$(document).ready(function () {
    console.log('Trying to initialize datatable...');

    $('#dalleImageTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.dalle.image.fetch') }}",
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'sr_no' },
            { data: 'image', orderable: false, searchable: false },
            { data: 'prompt' },
            { data: 'user' },
            { data: 'status' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>

<script>
   $(document).on('change', '.active_button', function() {
    var imageId = $(this).data('image-id');
    var toggleSwitch = $(this);

    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'POST',
        url: '/update/image/status',
        data: { image_id: imageId },
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(response) {
            if (toggleSwitch.is(':checked')) {
                toggleSwitch.closest('td').prev().text('active');
            } else {
                toggleSwitch.closest('td').prev().text('inactive');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('promptModal'));

    $(document).on('click', '.prompt-link', function (e) {
        e.preventDefault();

        const promptText = $(this).data('prompt');
        $('#promptText').val(promptText);
        $('#details').val('');
        $('#promptName').val('');

        const modal = new bootstrap.Modal(document.getElementById('promptModal'));
        modal.show();
    });


    document.getElementById('fetchDataButton').addEventListener('click', function () {
        const promptText = document.getElementById('promptText').value;
        fetch(`/prompt/generate/details`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ prompt: promptText })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('details').value = data.details;
            document.getElementById('promptName').value = data.promptName;
        });
    });

    document.getElementById('savePromptButton').addEventListener('click', function () {
        const promptText = document.getElementById('promptText').value;
        const category = document.getElementById('category').value;
        const subcategory = document.getElementById('subcategory').value;
        const details = document.getElementById('details').value;
        const promptName = document.getElementById('promptName').value;

            fetch(`/prompt/add/library`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ prompt: promptText, category, subcategory, details, prompt_name: promptName })
            }).then(response => {
                if (response.ok) {
                    alert('Prompt saved successfully!');
                    modal.hide();
                }
            });
        });
    });

    // Bulk fetch and save
    $(document).ready(function () {
    console.log("Bulk Save Script Loaded");

    // Select All
    $('#selectAll').on('change', function () {
        $('.prompt-checkbox').prop('checked', this.checked);
    });

    // Bulk Fetch & Save
    $('#bulkFetchSaveButton').on('click', function () {
        console.log("Bulk Save Button Clicked");

        const selectedPrompts = [];
        $('.prompt-checkbox:checked').each(function () {
            selectedPrompts.push({
                id: $(this).val(),
                prompt: $(this).data('prompt')
            });
        });

        console.log("Selected Prompts:", selectedPrompts);

        if (selectedPrompts.length === 0) {
            alert('Please select at least one prompt.');
            return;
        }

        fetch('/prompt/generate/bulk-details', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({ prompts: selectedPrompts.map(p => p.prompt) })
        })
        .then(res => res.json())
        .then(data => {
            console.log("Fetched Details:", data);

            const promptsWithDetails = selectedPrompts.map((p, index) => ({
                id: p.id,
                prompt: p.prompt,
                details: data[index].details,
                prompt_name: data[index].promptName,
                category: $('#category').val(),
                subcategory: data[index].subcategory
            }));

            return fetch('/prompt/add/bulk-library', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({ prompts: promptsWithDetails })
            });
        })
        .then(response => {
            console.log("Final Save Response", response);
            if (response.ok) {
                alert('All prompts have been saved successfully!');
                location.reload();
            }
        })
        .catch(error => {
            console.error("Error during bulk save:", error);
        });
    });
});


</script>

@include('admin.layouts.datatables')

@endpush
