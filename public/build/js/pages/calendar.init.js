/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Calendar init js
*/

var start_date = document.getElementById("event-start-date");
var timepicker1 = document.getElementById("timepicker1");
var timepicker2 = document.getElementById("timepicker2");
var date_range = null;
var T_check = null;
document.addEventListener("DOMContentLoaded", function () {
    flatPickrInit();
    var addEvent = new bootstrap.Modal(document.getElementById("event-modal"), {
        keyboard: false,
    });
    document.getElementById("event-modal");
    var modalTitle = document.getElementById("modal-title");
    var formEvent = document.getElementById("form-event");
    var selectedEvent = null;
    var forms = document.getElementsByClassName("needs-validation");
    /* initialize the calendar */

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var Draggable = FullCalendar.Draggable;
    var externalEventContainerEl = document.getElementById("external-events");
    var defaultEvents = [];

    // init draggable
    new Draggable(externalEventContainerEl, {
        itemSelector: ".external-event",
        eventData: function (eventEl) {
            return {
                id: Math.floor(Math.random() * 11000),
                title: eventEl.innerText,
                allDay: true,
                start: new Date(),
                className: eventEl.getAttribute("data-class"),
            };
        },
    });

    var calendarEl = document.getElementById("calendar");

    function addNewEvent(info) {
        document.getElementById("form-event").reset();
        document
            .getElementById("btn-delete-event")
            .setAttribute("hidden", true);
        addEvent.show();
        formEvent.classList.remove("was-validated");
        formEvent.reset();
        selectedEvent = null;
        modalTitle.innerText = "Add Event";
        newEventData = info;
        document
            .getElementById("edit-event-btn")
            .setAttribute("data-id", "new-event");
        document.getElementById("edit-event-btn").click();
        document.getElementById("edit-event-btn").setAttribute("hidden", true);
    }

    function getInitialView() {
        if (window.innerWidth >= 768 && window.innerWidth < 1200) {
            return "timeGridWeek";
        } else if (window.innerWidth <= 768) {
            return "listMonth";
        } else {
            return "dayGridMonth";
        }
    }

    var eventCategoryChoice = new Choices("#event-category", {
        searchEnabled: false,
    });

    var calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: "local",
        editable: true,
        droppable: true,
        selectable: true,
        navLinks: true,
        initialView: getInitialView(),
        themeSystem: "bootstrap",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
        },
        windowResize: function (view) {
            var newView = getInitialView();
            calendar.changeView(newView);
        },
        eventResize: function (info) {
            var indexOfSelectedEvent = defaultEvents.findIndex(function (x) {
                return x.id == info.event.id;
            });
            if (defaultEvents[indexOfSelectedEvent]) {
                defaultEvents[indexOfSelectedEvent].title = info.event.title;
                defaultEvents[indexOfSelectedEvent].start = info.event.start;
                defaultEvents[indexOfSelectedEvent].end = info.event.end
                    ? info.event.end
                    : null;
                defaultEvents[indexOfSelectedEvent].allDay = info.event.allDay;
                defaultEvents[indexOfSelectedEvent].className =
                    info.event.classNames[0];
                defaultEvents[indexOfSelectedEvent].description = info.event
                    ._def.extendedProps.description
                    ? info.event._def.extendedProps.description
                    : "";
                defaultEvents[indexOfSelectedEvent].s_time = info.event._def
                    .extendedProps.s_time
                    ? info.event._def.extendedProps.s_time
                    : "";
                defaultEvents[indexOfSelectedEvent].e_time = info.event._def
                    .extendedProps.e_time
                    ? info.event._def.extendedProps.e_time
                    : "";
                defaultEvents[indexOfSelectedEvent].location = info.event._def
                    .extendedProps.location
                    ? info.event._def.extendedProps.location
                    : "";
            }
            // upcomingEvent(defaultEvents);
        },
        eventClick: function (info) {
            document.getElementById("edit-event-btn").removeAttribute("hidden");
            document
                .getElementById("btn-save-event")
                .setAttribute("hidden", true);
            document
                .getElementById("edit-event-btn")
                .setAttribute("data-id", "edit-event");
            document.getElementById("edit-event-btn").innerHTML = "Edit";
            eventClicked();
            flatPickrInit();
            flatpicekrValueClear();
            addEvent.show();
            formEvent.reset();
            selectedEvent = info.event;

            // First Modal
            document.getElementById("modal-title").innerHTML = "";
            document.getElementById("event-location-tag").innerHTML =
                selectedEvent.extendedProps.location === undefined
                    ? "No Location"
                    : selectedEvent.extendedProps.location;
            document.getElementById("event-description-tag").innerHTML =
                selectedEvent.extendedProps.description === undefined
                    ? "No Description"
                    : selectedEvent.extendedProps.description;

            // Edit Modal
            document.getElementById("event-title").value = selectedEvent.title;
            document.getElementById("event-location").value =
                selectedEvent.extendedProps.location === undefined
                    ? "No Location"
                    : selectedEvent.extendedProps.location;
            document.getElementById("event-description").value =
                selectedEvent.extendedProps.description === undefined
                    ? "No Description"
                    : selectedEvent.extendedProps.description;
            document.getElementById("eventid").value = selectedEvent.id;

            if (selectedEvent.classNames[0]) {
                eventCategoryChoice.destroy();
                eventCategoryChoice = new Choices("#event-category", {
                    searchEnabled: false,
                });
                eventCategoryChoice.setChoiceByValue(
                    selectedEvent.classNames[0]
                );
            }
            var st_date = selectedEvent.start;
            var ed_date = selectedEvent.end;

            var date_r = function formatDate(date) {
                var d = new Date(date),
                    month = "" + (d.getMonth() + 1),
                    day = "" + d.getDate(),
                    year = d.getFullYear();
                if (month.length < 2) month = "0" + month;
                if (day.length < 2) day = "0" + day;
                return [year, month, day].join("-");
            };
            var updateDay = null;
            if (ed_date != null) {
                var endUpdateDay = new Date(ed_date);
                updateDay = endUpdateDay.setDate(endUpdateDay.getDate() - 1);
            }

            var r_date =
                ed_date == null
                    ? str_dt(st_date)
                    : str_dt(st_date) + " to " + str_dt(updateDay);
            var er_date =
                ed_date == null
                    ? date_r(st_date)
                    : date_r(st_date) + " to " + date_r(updateDay);

            flatpickr(start_date, {
                defaultDate: er_date,
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                mode: ed_date !== null ? "range" : "range",
                onChange: function (selectedDates, dateStr, instance) {
                    var date_range = dateStr;
                    var dates = date_range.split("to");
                    if (dates.length > 1) {
                        document
                            .getElementById("event-time")
                            .setAttribute("hidden", true);
                    } else {
                        document
                            .getElementById("timepicker1")
                            .parentNode.classList.remove("d-none");
                        document
                            .getElementById("timepicker1")
                            .classList.replace("d-none", "d-block");
                        document
                            .getElementById("timepicker2")
                            .parentNode.classList.remove("d-none");
                        document
                            .getElementById("timepicker2")
                            .classList.replace("d-none", "d-block");
                        document
                            .getElementById("event-time")
                            .removeAttribute("hidden");
                    }
                },
            });
            document.getElementById("event-start-date-tag").innerHTML = r_date;

            var gt_time = selectedEvent.extendedProps.s_time || "";
            var ed_time = selectedEvent.extendedProps.e_time || "";

            if (gt_time == ed_time) {
                document
                    .getElementById("event-time")
                    .setAttribute("hidden", true);
                flatpickr(document.getElementById("timepicker1"), {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                });
                flatpickr(document.getElementById("timepicker2"), {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                });
            } else {
                document.getElementById("event-time").removeAttribute("hidden");
                flatpickr(document.getElementById("timepicker1"), {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    defaultDate: gt_time,
                });

                flatpickr(document.getElementById("timepicker2"), {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    defaultDate: ed_time,
                });
                document.getElementById("event-timepicker1-tag").innerHTML =
                    gt_time;
                document.getElementById("event-timepicker2-tag").innerHTML =
                    ed_time;
            }
            newEventData = null;
            modalTitle.innerText = selectedEvent.title;

            // formEvent.classList.add("view-event");
            document
                .getElementById("btn-delete-event")
                .removeAttribute("hidden");
        },
        dateClick: function (info) {
            addNewEvent(info);
        },

        events: function (fetchInfo, successCallback, failureCallback) {
            // Fetch events from the server for the authenticated user
            $.ajax({
                url: "/events", // Adjust this to your actual route for fetching events
                method: "GET",
                success: function (response) {
                    var events = []; // Define events array here
                    if (Array.isArray(response)) {
                        response.forEach(function (event) {
                            events.push({
                                id: event.id,
                                title: event.title,
                                start: event.start,
                                end: event.end,
                                s_time: event.s_time,
                                e_time: event.e_time,
                                allDay: event.all_day,
                                className: event.category,
                                description: event.description,
                                location: event.location,
                            });
                        });
                        successCallback(events); // Pass the events to FullCalendar
                    } else {
                        console.error("Expected an array but got:", response);
                    }
                },

                error: function (error) {
                    console.error("Error fetching events:", error);
                    failureCallback(error);
                },
            });
        },

        eventReceive: function (info) {
            var newid = parseInt(info.event.id);
            var newEvent = {
                id: newid,
                title: info.event.title,
                start: info.event.start,
                allDay: info.event.allDay,
                className: info.event.classNames[0],
            };
            defaultEvents.push(newEvent);
            // upcomingEvent(defaultEvents);
        },
        eventDrop: function (info) {
            console.log("Event dropped:", info); // Check if triggered

            // Prepare the event data for the AJAX request
            var eventData = {
                id: info.event.id,
                title: info.event.title,
                start: info.event.start.toLocaleString(), // Format start date
                end: info.event.end ? info.event.end.toLocaleString() : null, // Format end date if exists
                all_day: info.event.allDay,
                category: info.event.classNames[0] || "", // Get the category from classNames
                location: info.event.extendedProps.location || "", // Get location from extendedProps
                description: info.event.extendedProps.description || "", // Get description from extendedProps
                _token: $('meta[name="csrf-token"]').attr("content"), // CSRF token for security
            };

            console.log("Sending AJAX request with data:", eventData); // Log data for verification

            $.ajax({
                url: "/events/drag/" + info.event.id, // Correct URL for updating the event
                method: "PUT", // Use PUT for updating
                data: eventData,
                success: function (response) {
                    console.log("Event updated successfully:", response);
                },
                error: function (error) {
                    console.error("Error updating event:", error);
                },
            });
        },
    });

    calendar.render();

    // upcomingEvent(defaultEvents);
    /*Add new event*/
    // Form to add new event
    formEvent.addEventListener("submit", function (ev) {
        ev.preventDefault();

        var updatedTitle = document.getElementById("event-title").value;
        var updatedCategory = document.getElementById("event-category").value;
        var timepicker1 = document.getElementById("timepicker1").value;
        var timepicker2 = document.getElementById("timepicker2").value;
        var updatedCategory = document.getElementById("event-category").value;
        var start_date = document
            .getElementById("event-start-date")
            .value.split("to");
        var updateStartDate = new Date(start_date[0].trim());
        var newdate = new Date(start_date[1]);
        newdate.setDate(newdate.getDate() + 1);
        var updateEndDate = start_date[1] ? newdate : "";

        var event_location = document.getElementById("event-location").value;
        var eventDescription =
            document.getElementById("event-description").value;
        var eventid = document.getElementById("eventid").value; // Ensure this is set correctly
        var all_day = start_date.length > 1;

        if (forms[0].checkValidity() === false) {
            forms[0].classList.add("was-validated");
        } else {
            var eventData = {
                title: updatedTitle,
                category: updatedCategory,
                start: updateStartDate,
                end: updateEndDate,
                all_day: all_day,
                timepicker1: timepicker1,
                timepicker2: timepicker2,
                location: event_location,
                description: eventDescription,
                _token: $('meta[name="csrf-token"]').attr("content"),
            };

            var requestType = selectedEvent ? "PUT" : "POST"; // Use PUT for updates
            var requestUrl = selectedEvent ? "/events/" + eventid : "/events"; // Adjust URL for updates

            $.ajax({
                url: requestUrl, // Your route to save events
                method: requestType,
                data: eventData,
                success: function (response) {
                    console.log("Event saved successfully:", response);

                    if (selectedEvent) {
                        // If updating, retrieve the existing event using its ID
                        var existingEvent = calendar.getEventById(eventid);
                        if (existingEvent) {
                            // Remove old event first
                            existingEvent.remove();
                        }

                        // Re-add the event with updated properties
                        var updatedEvent = {
                            id: response.id, // Get the ID from the backend response
                            title: updatedTitle,
                            start: updateStartDate,
                            end: updateEndDate,
                            allDay: all_day,
                            timepicker1: timepicker1,
                            timepicker2: timepicker2,
                            className: updatedCategory,
                            description: eventDescription,
                            location: event_location,
                        };
                        calendar.addEvent(updatedEvent); // Add the updated event to the calendar
                    } else {
                        // If adding new, create a new event
                        var newEvent = {
                            id: response.id, // Get ID from the backend
                            title: updatedTitle,
                            start: updateStartDate,
                            end: updateEndDate,
                            allDay: all_day,
                            timepicker1: timepicker1,
                            timepicker2: timepicker2,
                            className: updatedCategory,
                            description: eventDescription,
                            location: event_location,
                        };
                        calendar.addEvent(newEvent);
                    }

                    addEvent.hide(); // Close the modal
                },
                error: function (error) {
                    console.error("Error saving event:", error);
                },
            });
        }
    });

    // Define defaultEvents in a higher scope
    var defaultEvents = []; // Initialize as needed

    // Your existing code here...

    document
        .getElementById("btn-delete-event")
        .addEventListener("click", function (e) {
            if (selectedEvent) {
                // Make AJAX request to delete the event from the database
                $.ajax({
                    url: "/events/" + selectedEvent.id, // Your delete route
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ), // Include CSRF token
                    },
                    success: function (response) {
                        console.log("Event deleted successfully:", response);

                        // Remove the event from the calendar
                        selectedEvent.remove();

                        // Remove the event from your default events array
                        defaultEvents = defaultEvents.filter(
                            (event) => event.id !== selectedEvent.id
                        );
                        upcomingEvent(defaultEvents);

                        // Reset selectedEvent variable
                        selectedEvent = null;
                        addEvent.hide();
                    },
                    error: function (error) {
                        console.error("Error deleting event:", error);
                    },
                });
            }
        });

    document
        .getElementById("btn-new-event")
        .addEventListener("click", function (e) {
            flatpicekrValueClear();
            flatPickrInit();
            addNewEvent();
            document
                .getElementById("edit-event-btn")
                .setAttribute("data-id", "new-event");
            document.getElementById("edit-event-btn").click();
            document
                .getElementById("edit-event-btn")
                .setAttribute("hidden", true);
        });
});

function flatPickrInit() {
    var config = {
        enableTime: true,
        noCalendar: true,
    };
    var date_range = flatpickr(start_date, {
        enableTime: false,
        mode: "range",
        minDate: "today",
        onChange: function (selectedDates, dateStr, instance) {
            var date_range = dateStr;
            var dates = date_range.split("to");
            if (dates.length > 1) {
                document
                    .getElementById("event-time")
                    .setAttribute("hidden", true);
            } else {
                document
                    .getElementById("timepicker1")
                    .parentNode.classList.remove("d-none");
                document
                    .getElementById("timepicker1")
                    .classList.replace("d-none", "d-block");
                document
                    .getElementById("timepicker2")
                    .parentNode.classList.remove("d-none");
                document
                    .getElementById("timepicker2")
                    .classList.replace("d-none", "d-block");
                document.getElementById("event-time").removeAttribute("hidden");
            }
        },
    });
    flatpickr(timepicker1, config);
    flatpickr(timepicker2, config);
}

function flatpicekrValueClear() {
    start_date.flatpickr().clear();
    timepicker1.flatpickr().clear();
    timepicker2.flatpickr().clear();
}

function eventClicked() {
    document.getElementById("form-event").classList.add("view-event");
    document
        .getElementById("event-title")
        .classList.replace("d-block", "d-none");
    document
        .getElementById("event-category")
        .classList.replace("d-block", "d-none");
    document
        .getElementById("event-start-date")
        .parentNode.classList.add("d-none");
    document
        .getElementById("event-start-date")
        .classList.replace("d-block", "d-none");
    document.getElementById("event-time").setAttribute("hidden", true);
    document.getElementById("timepicker1").parentNode.classList.add("d-none");
    document
        .getElementById("timepicker1")
        .classList.replace("d-block", "d-none");
    document.getElementById("timepicker2").parentNode.classList.add("d-none");
    document
        .getElementById("timepicker2")
        .classList.replace("d-block", "d-none");
    document
        .getElementById("event-location")
        .classList.replace("d-block", "d-none");
    document
        .getElementById("event-description")
        .classList.replace("d-block", "d-none");
    document
        .getElementById("event-start-date-tag")
        .classList.replace("d-none", "d-block");
    document
        .getElementById("event-timepicker1-tag")
        .classList.replace("d-none", "d-block");
    document
        .getElementById("event-timepicker2-tag")
        .classList.replace("d-none", "d-block");
    document
        .getElementById("event-location-tag")
        .classList.replace("d-none", "d-block");
    document
        .getElementById("event-description-tag")
        .classList.replace("d-none", "d-block");
    document.getElementById("btn-save-event").setAttribute("hidden", true);
}

function editEvent(data) {
    var data_id = data.getAttribute("data-id");
    if (data_id == "new-event") {
        document.getElementById("modal-title").innerHTML = "";
        document.getElementById("modal-title").innerHTML = "Add Event";
        document.getElementById("btn-save-event").innerHTML = "Add Event";
        eventTyped();
    } else if (data_id == "edit-event") {
        data.innerHTML = "Cancel";
        data.setAttribute("data-id", "cancel-event");
        document.getElementById("btn-save-event").innerHTML = "Update Event";
        data.removeAttribute("hidden");
        eventTyped();
    } else {
        data.innerHTML = "Edit";
        data.setAttribute("data-id", "edit-event");
        eventClicked();
    }
}

function eventTyped() {
    document.getElementById("form-event").classList.remove("view-event");
    document
        .getElementById("event-title")
        .classList.replace("d-none", "d-block");
    document
        .getElementById("event-category")
        .classList.replace("d-none", "d-block");
    document
        .getElementById("event-start-date")
        .parentNode.classList.remove("d-none");
    document
        .getElementById("event-start-date")
        .classList.replace("d-none", "d-block");
    document
        .getElementById("timepicker1")
        .parentNode.classList.remove("d-none");
    document
        .getElementById("timepicker1")
        .classList.replace("d-none", "d-block");
    document
        .getElementById("timepicker2")
        .parentNode.classList.remove("d-none");
    document
        .getElementById("timepicker2")
        .classList.replace("d-none", "d-block");
    document
        .getElementById("event-location")
        .classList.replace("d-none", "d-block");
    document
        .getElementById("event-description")
        .classList.replace("d-none", "d-block");
    document
        .getElementById("event-start-date-tag")
        .classList.replace("d-block", "d-none");
    document
        .getElementById("event-timepicker1-tag")
        .classList.replace("d-block", "d-none");
    document
        .getElementById("event-timepicker2-tag")
        .classList.replace("d-block", "d-none");
    document
        .getElementById("event-location-tag")
        .classList.replace("d-block", "d-none");
    document
        .getElementById("event-description-tag")
        .classList.replace("d-block", "d-none");
    document.getElementById("btn-save-event").removeAttribute("hidden");
}

// upcoming Event
function upcomingEvent(a) {
    a.sort(function (o1, o2) {
        return new Date(o1.start) - new Date(o2.start);
    });
    document.getElementById("upcoming-event-list").innerHTML = null;
    Array.from(a).forEach(function (element) {
        var title = element.title;
        if (element.end) {
            endUpdatedDay = new Date(element.end);
            var updatedDay = endUpdatedDay.setDate(endUpdatedDay.getDate() - 1);
        }
        var e_dt = updatedDay ? updatedDay : undefined;
        if (e_dt == "Invalid Date" || e_dt == undefined) {
            e_dt = null;
        } else {
            const newDate = new Date(e_dt).toLocaleDateString("en", {
                year: "numeric",
                month: "numeric",
                day: "numeric",
            });
            e_dt = new Date(newDate)
                .toLocaleDateString("en-GB", {
                    day: "numeric",
                    month: "short",
                    year: "numeric",
                })
                .split(" ")
                .join(" ");
        }
        var st_date = element.start ? str_dt(element.start) : null;
        var ed_date = updatedDay ? str_dt(updatedDay) : null;
        if (st_date === ed_date) {
            e_dt = null;
        }
        var startDate = element.start;
        if (startDate === "Invalid Date" || startDate === undefined) {
            startDate = null;
        } else {
            const newDate = new Date(startDate).toLocaleDateString("en", {
                year: "numeric",
                month: "numeric",
                day: "numeric",
            });
            startDate = new Date(newDate)
                .toLocaleDateString("en-GB", {
                    day: "numeric",
                    month: "short",
                    year: "numeric",
                })
                .split(" ")
                .join(" ");
        }

        var end_dt = e_dt ? " to " + e_dt : "";
        var category = element.className.split("-");
        var description = element.description ? element.description : "";
        var e_time_s = tConvert(getTime(element.start));
        var e_time_e = tConvert(getTime(updatedDay));
        if (e_time_s == e_time_e) {
            var e_time_s = "Full day event";
            var e_time_e = null;
        }
        var e_time_e = e_time_e ? " to " + e_time_e : "";

        u_event =
            "<div class='card mb-3'>\
                        <div class='card-body'>\
                            <div class='d-flex mb-3'>\
                                <div class='flex-grow-1'><i class='mdi mdi-checkbox-blank-circle me-2 text-" +
            category[1] +
            "'></i><span class='fw-medium'>" +
            startDate +
            end_dt +
            " </span></div>\
                                <div class='flex-shrink-0'><small class='badge bg-primary-subtle text-primary ms-auto'>" +
            e_time_s +
            e_time_e +
            "</small></div>\
                            </div>\
                            <h6 class='card-title fs-16'> " +
            title +
            "</h6>\
                            <p class='text-muted text-truncate-two-lines mb-0'> " +
            description +
            "</p>\
                        </div>\
                    </div>";
        document.getElementById("upcoming-event-list").innerHTML += u_event;
    });
}

function getTime(params) {
    params = new Date(params);
    if (params.getHours() != null) {
        var hour = params.getHours();
        var minute = params.getMinutes() ? params.getMinutes() : 0;
        return hour + ":" + minute;
    }
}

function tConvert(time) {
    var t = time.split(":");
    var hours = t[0];
    var minutes = t[1];
    var newformat = hours >= 12 ? "PM" : "AM";
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? "0" + minutes : minutes;
    return hours + ":" + minutes + " " + newformat;
}

var str_dt = function formatDate(date) {
    var monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];
    var d = new Date(date),
        month = "" + monthNames[d.getMonth()],
        day = "" + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2) month = "0" + month;
    if (day.length < 2) day = "0" + day;
    return [day + " " + month, year].join(",");
};
