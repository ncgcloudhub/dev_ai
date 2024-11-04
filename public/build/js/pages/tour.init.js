document.addEventListener('DOMContentLoaded', function() {
    var seenSteps = window.seenTourSteps || [];

    // Function to save seen steps to the server and update the database
    function saveSeenSteps() {
        fetch('/save-seen-tour-steps', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ seenTourSteps: seenSteps })
        });
    }

    function markStepAsSeen(stepId) {
        if (!seenSteps.includes(stepId)) {
            seenSteps.push(stepId);
            saveSeenSteps(); // Updates the database
        }
    }

    var tour = new Shepherd.Tour({
        defaultStepOptions: {
            cancelIcon: { enabled: true },
            classes: 'shadow-md bg-purple-dark',
            scrollTo: { behavior: 'smooth', block: 'center' }
        },
        useModalOverlay: { enabled: true }
    });

    function addTourStep(id, title, text, attachToSelector) {
        if (document.querySelector(attachToSelector) && !seenSteps.includes(id)) {
            tour.addStep({
                id: id,
                title: title,
                text: text,
                attachTo: { element: attachToSelector, on: 'bottom' },
                buttons: [
                    { text: 'Back', classes: 'btn btn-light', action: tour.back },
                    {
                        text: 'Next', classes: 'btn btn-success', 
                        action: function() {
                            markStepAsSeen(id);
                            tour.next();
                        }
                    }
                ]
            });
        }
    }

    // Define tour steps
    addTourStep('logo-tour', 'Welcome Back !', 'This is Step 1', '#logo-tour');
    addTourStep('select-model-tour', 'Model', 'Select your desired model.', '#select-model-tour');
    addTourStep('ai-professional-tour', 'AI Professional Bot', 'Our professional chat experts on different fields.', '#ai-professional-tour');
    addTourStep('new-chat-tour', 'New Chat', 'Click to create new chat sessions.', '#new-chat-tour');
    addTourStep('type-message-tour', 'Message', 'Ask anything.', '#type-message-tour');
    addTourStep('send-tour', 'Send Button', 'Click to send the message.', '#send-tour');

    // Start the tour
    tour.start();
});
