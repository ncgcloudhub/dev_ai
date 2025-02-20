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
            </div>
        </div>

        <!-- Button Preview Section -->
        <div class="col-md-6">
            <h4>Button Previews</h4>
            <button id="previewButton-save" class="btn">Save</button>
            <button id="previewButton-edit" class="btn">Edit</button>
            <button id="previewButton-delete" class="btn">Delete</button>
            <button id="previewButton-cancel" class="btn">Cancel</button>
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
        let previewButton = document.getElementById(`previewButton-${buttonId}`);

        previewButton.style.background = `linear-gradient(45deg, ${bgColor}, ${gradientColor})`;
        previewButton.style.borderRadius = `${borderRadius}px`;
        previewButton.style.boxShadow = `0 4px ${shadowIntensity}px rgba(0, 0, 0, 0.2)`;
        previewButton.textContent = buttonText;
        previewButton.style.color = "white";
        previewButton.style.border = "none";
        previewButton.style.padding = "10px 20px";
        previewButton.style.transition = "box-shadow 0.3s ease, transform 0.3s ease";
    }

    document.querySelectorAll("input").forEach(input => {
        input.addEventListener("input", function() {
            let buttonId = this.dataset.button;
            updateButtonStyle(buttonId);
        });
    });

    document.querySelectorAll(".save-button-style").forEach(button => {
        button.addEventListener("click", function() {
            alert(`Button style for ${this.dataset.button} saved!`);
            // Here, you can use AJAX to save the button styles in the database
        });
    });

    // Initialize button styles
    ["save", "edit", "delete", "cancel"].forEach(buttonId => updateButtonStyle(buttonId));
});
</script>
@endsection



