/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: tour init js
*/

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

// if (document.querySelector('#logo-tour'))
//     tour.addStep({
//         title: 'Welcome Back !',
//         text: 'This is Step 1',
//         attachTo: {
//             element: '#logo-tour',
//             on: 'bottom'
//         },
//         buttons: [{
//             text: 'Next',
//             classes: 'btn btn-success',
//             action: tour.next
//         }]
//     });
// // end step 1

if (document.querySelector('#select-model-tour'))
    console.log(document.querySelector('#select-model-tour')); // This should log the element if it exists

    tour.addStep({
        title: 'Model',
        text: 'Select your desired model.',
        attachTo: {
            element: '#select-model-tour',
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
            }
        ]
    });
// end step 2

if (document.querySelector('#ai-professional-tour'))
    tour.addStep({
        title: 'AI Professional Bot',
        text: 'Our professional chat experts on different fields.',
        attachTo: {
            element: '#ai-professional-tour',
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
            }
        ]
    });

// end step 3
if (document.querySelector('#new-chat-tour'))
    tour.addStep({
        title: 'New Chat',
        text: 'Click to create new chat sessions.',
        attachTo: {
            element: '#new-chat-tour',
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
            }
        ]
    });
// end step 4
if (document.querySelector('#type-message-tour'))
    tour.addStep({
        title: 'Message',
        text: 'Ask anything.',
        attachTo: {
            element: '#type-message-tour',
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
            }
        ]
    });
// end step 4

// end step 3
if (document.querySelector('#send-tour'))
    tour.addStep({
        title: 'Send Button',
        text: 'Click to send the message.',
        attachTo: {
            element: '#send-tour',
            on: 'bottom'
        },
        buttons: [{
                text: 'Back',
                classes: 'btn btn-light',
                action: tour.back
            },
            {
                text: 'Thankyou !',
                classes: 'btn btn-success',
                action: tour.next
            }
        ]
    });
// end step 4

tour.start();