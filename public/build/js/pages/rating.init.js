if (document.querySelector("#basic-rater"))
    var basicRating = raterJs({
        starSize: 22,
        rating: 3,
        element: document.querySelector("#basic-rater"),
        rateCallback: function rateCallback(rating, done) {
            this.setRating(rating);
            done();
        },
    });

// rater-step
if (document.querySelector("#rater-step"))
    var starRatingStep = raterJs({
        starSize: 22,
        rating: 1.5,
        element: document.querySelector("#rater-step"),
        rateCallback: function rateCallback(rating, done) {
            this.setRating(rating);
            done();
        },
    });

// rater-message
var messageDataService = {
    rate: function (rating) {
        return {
            then: function (callback) {
                setTimeout(function () {
                    callback(Math.random() * 5);
                }, 1000);
            },
        };
    },
};

if (document.querySelector("#rater-message"))
    var starRatingmessage = raterJs({
        isBusyText: "Rating in progress. Please wait...",
        starSize: 22,
        element: document.querySelector("#rater-message"),
        rateCallback: function rateCallback(rating, done) {
            starRatingmessage.setRating(rating);
            messageDataService.rate().then(function (avgRating) {
                starRatingmessage.setRating(avgRating);
                done();
            });
        },
    });

// rater-unlimitedstar
if (document.querySelector("#rater-unlimitedstar"))
    var starRatingunlimited = raterJs({
        max: 16,
        readOnly: true,
        rating: 4.4,
        element: document.querySelector("#rater-unlimitedstar"),
    });

// rater-onhover
document
    .querySelectorAll('[id^="rater-onhover-"]')
    .forEach(function (ratingElement) {
        var templateId = ratingElement.id.replace("rater-onhover-", "");
        var userRating = ratingElement.getAttribute("data-user-rating");
        var starRatinghover = raterJs({
            starSize: 22,
            rating: userRating ? parseInt(userRating) : 5,
            readOnly: !!userRating, // Make readOnly if user has already rated
            element: ratingElement,
            rateCallback: function rateCallback(rating, done) {
                this.setRating(rating);
                done();

                // AJAX request to submit the rating
                $.ajax({
                    url: "/rate-template",
                    type: "POST",
                    data: {
                        _token: document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        template_id: templateId,
                        rating: rating,
                    },
                    success: function (response) {
                        console.log("Rating submitted successfully:", response);
                        alert("Rating submitted!");
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error(
                            "Failed to submit rating:",
                            status,
                            error
                        );
                        console.log(xhr.responseText);
                        alert("Failed to submit rating");
                    },
                });
            },
            onHover: function (currentIndex, currentRating) {
                ratingElement.parentElement.querySelector(
                    ".ratingnum"
                ).textContent = currentIndex;
            },
            onLeave: function (currentIndex, currentRating) {
                ratingElement.parentElement.querySelector(
                    ".ratingnum"
                ).textContent = currentRating;
            },
        });
    });

// rater-reset
if (document.querySelector("#raterreset"))
    var starRatingreset = raterJs({
        starSize: 22,
        rating: 2,
        element: document.querySelector("#raterreset"),
        rateCallback: function rateCallback(rating, done) {
            this.setRating(rating);
            done();
        },
    });

if (document.querySelector("#raterreset-button"))
    document.querySelector("#raterreset-button").addEventListener(
        "click",
        function () {
            starRatingreset.clear();
        },
        false
    );
