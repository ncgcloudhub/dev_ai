// Initialize a tour with given steps
function initializeTour(steps) {
    var tour = new Shepherd.Tour({
        defaultStepOptions: {
            cancelIcon: {
                enabled: true
            },
            classes: 'shadow-md bg-purple-dark',
            scrollTo: {
                behavior: 'smooth',
                block: 'center'
            }
        },
        useModalOverlay: {
            enabled: true
        },
    });

    // Add steps
    steps.forEach(step => {
        tour.addStep({
            id: step.id,
            title: step.title,
            text: step.text,
            attachTo: {
                element: step.attachTo,
                on: 'bottom'
            },
            buttons: [{
                text: 'Back',
                classes: 'btn btn-light',
                action: tour.back
            },
            {
                text: 'Next',
                classes: 'btn btn-success',
                action: tour.next
            }]
        });
    });

    // Start the tour
    tour.start();
}

// Function to handle button click and start the tour
function setupTourButton(buttonId, steps) {
    document.getElementById(buttonId).addEventListener('click', function() {
        initializeTour(steps);
    });
}

// Set up the tours for different pages
document.addEventListener('DOMContentLoaded', function() {
    // Define tour steps for the template management page
    var templateSteps = [
        { id: 'search-tour', title: 'Search Box', text: 'Enter your desired template here.', attachTo: '#search-tour' },
        { id: 'enter-button', title: 'Search Button', text: 'Press to filter the templates.', attachTo: '#enter-button' },
        { id: 'category-tour', title: 'Category', text: 'Click to filter respective category.', attachTo: '#category-tour' },
        { id: 'category-details', title: 'Category Details', text: 'Click to generate contents.', attachTo: '#category-details' }
    ];

    // Define tour steps for the image generation page
    var imageGenerateSteps = [
        { id: 'upload-image-tour', title: 'Upload Image', text: 'Upload your image here.', attachTo: '#upload-image-tour' },
        { id: 'edit-image-tour', title: 'Edit Image', text: 'Edit your image settings.', attachTo: '#edit-image-tour' },
        { id: 'generate-button-tour', title: 'Generate', text: 'Click to generate the image.', attachTo: '#generate-button-tour' }
    ];

    // Set up tour buttons
    setupTourButton('templateManageTourButton', templateSteps);
    setupTourButton('imageGenerateTourButton', imageGenerateSteps);
});
