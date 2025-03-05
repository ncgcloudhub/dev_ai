@extends('admin.layouts.master')
@section('title') Dynamic Buttons @endsection

@section('css')
<link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('build/libs/filepond/filepond.min.css') }}" type="text/css" />
<link rel="stylesheet"
    href="{{ URL::asset('build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">
@endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('dashboard')}}">Dashboard</a> @endslot
@slot('title') Dynamic Buttons @endslot
@endcomponent

<div class="container">
    <h2>Button Designer</h2>

    <div class="row">
        <!-- Button Customization Cards -->
        <div class="col-md-6">
            <div class="accordion" id="buttonAccordion">
                <!-- Save Button Card -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#saveButtonCard">
                            Save Button
                        </button>
                    </h2>
                    <div id="saveButtonCard" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'save'])
                        </div>
                    </div>
                </div>

                <!-- Edit Button Card -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#editButtonCard">
                            Edit Button
                        </button>
                    </h2>
                    <div id="editButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'edit'])
                        </div>
                    </div>
                </div>

                <!-- View Button Card -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#viewButtonCard">
                            View Button
                        </button>
                    </h2>
                    <div id="viewButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'view'])
                        </div>
                    </div>
                </div>

                <!-- Delete Button Card -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#deleteButtonCard">
                            Delete Button
                        </button>
                    </h2>
                    <div id="deleteButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'delete'])
                        </div>
                    </div>
                </div>

                <!-- Cancel Button Card -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#cancelButtonCard">
                            Cancel Button
                        </button>
                    </h2>
                    <div id="cancelButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'cancel'])
                        </div>
                    </div>
                </div>

                  <!-- Generate Button Card -->
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#generateButtonCard">
                            Generate Button
                        </button>
                    </h2>
                    <div id="generateButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'generate'])
                        </div>
                    </div>
                </div>

                 <!-- Add Button Card -->
                 <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#addButtonCard">
                            Add Button
                        </button>
                    </h2>
                    <div id="addButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'add'])
                        </div>
                    </div>
                </div>

                 <!-- Remove Button Card -->
                 <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#removeButtonCard">
                            Remove Button
                        </button>
                    </h2>
                    <div id="removeButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'remove'])
                        </div>
                    </div>
                </div>

                 <!-- Copy Button Card -->
                 <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#copyButtonCard">
                            Copy Button
                        </button>
                    </h2>
                    <div id="copyButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'copy'])
                        </div>
                    </div>
                </div>

                 <!-- Download Button Card -->
                 <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#downloadButtonCard">
                            Download Button
                        </button>
                    </h2>
                    <div id="downloadButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'download'])
                        </div>
                    </div>
                </div>

                 <!-- Import Button Card -->
                 <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#importButtonCard">
                            Import Button
                        </button>
                    </h2>
                    <div id="importButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'import'])
                        </div>
                    </div>
                </div>

                 <!-- Export Button Card -->
                 <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#exportButtonCard">
                            Export Button
                        </button>
                    </h2>
                    <div id="exportButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'export'])
                        </div>
                    </div>
                </div>

                 <!-- Tour Button Card -->
                 <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#tourButtonCard">
                            Tour Button
                        </button>
                    </h2>
                    <div id="tourButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'tour'])
                        </div>
                    </div>
                </div>

                 <!-- Search Button Card -->
                 <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#searchButtonCard">
                            Search Button
                        </button>
                    </h2>
                    <div id="searchButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'search'])
                        </div>
                    </div>
                </div>

                 <!-- Filter Button Card -->
                 <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#filterButtonCard">
                            Filter Button
                        </button>
                    </h2>
                    <div id="filterButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'filter'])
                        </div>
                    </div>
                </div>

                 <!-- Others Button Card -->
                 <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#othersButtonCard">
                            Others Button
                        </button>
                    </h2>
                    <div id="othersButtonCard" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @include('backend.dynamic_buttons.partial_button_form', ['buttonId' => 'others'])
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Button Preview Section -->
        <div class="col-md-6">
            <h4>Button Previews</h4>
            <div class="row">
                <div class="col-3 mb-2"><button id="previewButton-save" class="btn btn-primary w-100">Save</button></div>
                <div class="col-3 mb-2"><button id="previewButton-edit" class="btn btn-warning w-100">Edit</button></div>
                <div class="col-3 mb-2"><button id="previewButton-view" class="btn btn-info w-100">View</button></div>
                <div class="col-3 mb-2"><button id="previewButton-delete" class="btn btn-danger w-100">Delete</button></div>
        
                <div class="col-3 mb-2"><button id="previewButton-cancel" class="btn btn-secondary w-100">Cancel</button></div>
                <div class="col-3 mb-2"><button id="previewButton-generate" class="btn btn-success w-100">Generate</button></div>
                <div class="col-3 mb-2"><button id="previewButton-add" class="btn btn-primary w-100">Add</button></div>
                <div class="col-3 mb-2"><button id="previewButton-remove" class="btn btn-dark w-100">Remove</button></div>
        
                <div class="col-3 mb-2"><button id="previewButton-copy" class="btn btn-info w-100">Copy</button></div>
                <div class="col-3 mb-2"><button id="previewButton-download" class="btn btn-success w-100">Download</button></div>
                <div class="col-3 mb-2"><button id="previewButton-import" class="btn btn-secondary w-100">Import</button></div>
                <div class="col-3 mb-2"><button id="previewButton-export" class="btn btn-warning w-100">Export</button></div>

                <div class="col-3 mb-2"><button id="previewButton-tour" class="btn btn-info w-100">Tour</button></div>
                <div class="col-3 mb-2"><button id="previewButton-search" class="btn btn-success w-100">Search</button></div>
                <div class="col-3 mb-2"><button id="previewButton-filter" class="btn btn-secondary w-100">Filter</button></div>
                <div class="col-3 mb-2"><button id="previewButton-others" class="btn btn-warning w-100">Others</button></div>
            </div>
        </div>
        
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function updateButtonStyle(buttonId) {
        let bgColor = document.getElementById(`bgColor-${buttonId}`).value;
        let gradientColor = document.getElementById(`gradientColor-${buttonId}`).value;
        let borderRadius = document.getElementById(`borderRadius-${buttonId}`).value;
        let shadowIntensity = document.getElementById(`shadowIntensity-${buttonId}`).value;
        let buttonText = document.getElementById(`buttonText-${buttonId}`).value;
        let icon = document.getElementById(`icon-${buttonId}`).value;
        let previewButton = document.getElementById(`previewButton-${buttonId}`);

        previewButton.style.background = `linear-gradient(45deg, ${bgColor}, ${gradientColor})`;
        previewButton.style.borderRadius = `${borderRadius}px`;
        previewButton.style.boxShadow = `0 4px ${shadowIntensity}px rgba(0, 0, 0, 0.2)`;
        previewButton.innerHTML = `<i class="fa ${icon}"></i> ${buttonText}`;
        previewButton.style.color = "white";
        previewButton.style.border = "none";
        previewButton.style.padding = "10px 20px";
        previewButton.style.transition = "box-shadow 0.3s ease, transform 0.3s ease";
    }

    // Update button style on input change
    document.querySelectorAll("input, select").forEach(input => {
        input.addEventListener("input", function() {
            let buttonId = this.dataset.button;
            updateButtonStyle(buttonId);
        });
    });

    // Save button style
    document.querySelectorAll(".save-button-style").forEach(button => {
        button.addEventListener("click", function() {
            let buttonId = this.dataset.button;

            let bgColor = document.getElementById(`bgColor-${buttonId}`).value;
            let gradientColor = document.getElementById(`gradientColor-${buttonId}`).value;
            let borderRadius = document.getElementById(`borderRadius-${buttonId}`).value;
            let shadowIntensity = document.getElementById(`shadowIntensity-${buttonId}`).value;
            let buttonText = document.getElementById(`buttonText-${buttonId}`).value;
            let icon = document.getElementById(`icon-${buttonId}`).value;

            let classes = JSON.stringify({
                "background": `linear-gradient(45deg, ${bgColor}, ${gradientColor})`,
                "border-radius": `${borderRadius}px`,  // ✅ Correct (kebab-case)
                "box-shadow": `0 4px ${shadowIntensity}px rgba(0, 0, 0, 0.2)`,
                "color": "white",
                "border": "none",
                "padding": "10px 20px",
                "transition": "box-shadow 0.3s ease, transform 0.3s ease",
            });


            fetch('/button-styles/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    button_type: buttonId,
                    icon: icon,
                    classes: classes,
                }),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });

    // Initialize button styles
    ["save", "edit", "view", "delete", "cancel", "generate", "add", "remove", "copy", "download", "import", "export", "tour", "search", "filter", "others"].forEach(buttonId => updateButtonStyle(buttonId));
});
</script>
@endsection



