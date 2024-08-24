// Function to initialize and start a tour with given steps
function initializeTour(steps) {
    var tour = new Shepherd.Tour({
        defaultStepOptions: {
            cancelIcon: {
                enabled: true,
            },
            classes: "shadow-md bg-purple-dark",
            scrollTo: {
                behavior: "smooth",
                block: "center",
            },
        },
        useModalOverlay: true,
    });

    // Add steps to the tour
    steps.forEach((step) => {
        tour.addStep({
            id: step.id,
            title: step.title,
            text: step.text,
            attachTo: {
                element: step.attachTo,
                on: "bottom",
            },
            buttons: [
                {
                    text: "Back",
                    classes: "btn btn-light",
                    action: tour.back,
                },
                {
                    text: "Next",
                    classes: "btn btn-success",
                    action: tour.next,
                },
            ],
        });
    });

    // Start the tour
    tour.start();
}

// Function to handle button click and start the tour
function setupTourButton(buttonId, steps) {
    var button = document.getElementById(buttonId);
    if (button) {
        button.addEventListener("click", function () {
            initializeTour(steps);
        });
    }
}

// Set up the tours for different pages
document.addEventListener("DOMContentLoaded", function () {
    // Define tour steps for the template management page
    var templateSteps = [
        {
            id: "search-tour",
            title: "Search Box",
            text: "Enter your desired template here.",
            attachTo: "#search-tour",
        },
        {
            id: "enter-button",
            title: "Search Button",
            text: "Press to filter the templates.",
            attachTo: "#enter-button",
        },
        {
            id: "category-tour",
            title: "Category",
            text: "Click to filter respective category.",
            attachTo: "#category-tour",
        },
        {
            id: "category-details",
            title: "Category Details",
            text: "Click to generate contents.",
            attachTo: "#category-details",
        },
    ];

    // Define tour steps for the image generation page
    var imageGenerateSteps = [
        {
            id: "model-select-tour",
            title: "Model",
            text: "Select your model.",
            attachTo: "#model-select-tour",
        },
        {
            id: "advance-setting-tour",
            title: "Advance Setting",
            text: "Add resolution, style, quality to get custom images.",
            attachTo: "#advance-setting-tour",
        },
        {
            id: "image-to-image-tour",
            title: "Similar Image",
            text: "Toggle to create similar image after you upload yours.",
            attachTo: "#image-to-image-tour",
        },
        {
            id: "search-box-tour",
            title: "Search Box",
            text: "Type your imagination.",
            attachTo: "#search-box-tour",
        },
        {
            id: "generate-button-tour",
            title: "Generate Button",
            text: "Click to see your imagination coming to life.",
            attachTo: "#generate-button-tour",
        },
    ];

    var promptLibrarySteps = [
        {
            id: "filter-by-tags-tour",
            title: "Tags",
            text: "Click on any tag to get filtered prompts.",
            attachTo: "#filter-by-tags-tour",
        },
        {
            id: "advance-filter-tour",
            title: "Advance Filter",
            text: "Select from wide range of categories to get filtered prompts.",
            attachTo: "#advance-filter-tour",
        },
        {
            id: "prompt-details-tour",
            title: "Prompts",
            text: "Click on respective prompt to generate contents.",
            attachTo: "#prompt-details-tour",
        },
    ];

    var promptLibraryDetailsSteps = [
        {
            id: "search-box-tour",
            title: "Search Box",
            text: "Replace Topic inside the brackets with your real topic.",
            attachTo: "#search-box-tour",
        },
        {
            id: "generate-button-tour",
            title: "Ask Button",
            text: "Click to genearte your contents.",
            attachTo: "#generate-button-tour",
        },
        {
            id: "copy-download-tour",
            title: "Copy and Download",
            text: "Click to copy and download generated contents.",
            attachTo: "#copy-download-tour",
        },
    ];

    var individualtemplateSteps = [
        {
            id: "select-language-tour",
            title: "Language",
            text: "Select Your preferred Language",
            attachTo: "#select-language-tour",
        },
        {
            id: "content-tour",
            title: "Content",
            text: "Write your contents here to generate with the Help of AI.",
            attachTo: "#content-tour",
        },
        {
            id: "generated-content",
            title: "Generated Content",
            text: "Your generated content will show up here.",
            attachTo: "#generated-content",
        },
    ];

    // Set up the tour buttons if they exist
    setupTourButton("templateManageTourButton", templateSteps);
    setupTourButton("templateDetailsTourButton", individualtemplateSteps);
    setupTourButton("imageGenerateTourButton", imageGenerateSteps);
    setupTourButton("promptLibraryTourButton", promptLibrarySteps);
    setupTourButton(
        "promptLibraryDetailsTourButton",
        promptLibraryDetailsSteps
    );
});
