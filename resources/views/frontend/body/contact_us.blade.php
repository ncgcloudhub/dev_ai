<section class="section" id="contact">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h3 class="mb-3 fw-semibold">Get In Touch</h3>
                    <p class="text-muted mb-4 ff-secondary">For more information about our AI-powered services and solutions, please reach out through our contact form below. We're committed to empowering your projects with cutting-edge AI technology.</p>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row gy-4">
            <div class="col-lg-4">
                <div>
                    {{-- <div class="mt-4">
                        <h5 class="fs-13 text-muted text-uppercase">Office Address 1:</h5>
                        <div class="ff-secondary fw-semibold">4461 Cedar Street Moro, <br />AR 72368</div>
                    </div>
                    <div class="mt-4">
                        <h5 class="fs-13 text-muted text-uppercase">Office Address 2:</h5>
                        <div class="ff-secondary fw-semibold">2467 Swick Hill Street <br />New Orleans, LA
                        </div>
                    </div> --}}
                    <div class="mt-4">
                        <h5 class="fs-13 text-muted text-uppercase">Working Hours:</h5>
                        <div class="ff-secondary fw-semibold">9:00am to 6:00pm</div>
                    </div>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-8">
                <div>
                    <form method="POST" action="{{ route('send.email') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <label for="name" class="form-label fs-13">Name</label>
                                    <input name="name" id="name" type="text"
                                        class="form-control bg-light border-light" placeholder="Your name*">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <label for="email" class="form-label fs-13">Email</label>
                                    <input name="email" id="email" type="email"
                                        class="form-control bg-light border-light" placeholder="Your email*">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="subject" class="form-label fs-13">Subject</label>
                                    <input type="text" class="form-control bg-light border-light" id="subject"
                                        name="subject" placeholder="Your Subject.." />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="comments" class="form-label fs-13">Message</label>
                                    <textarea name="comments" id="comments" rows="3" class="form-control bg-light border-light"
                                        placeholder="Your message..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="agreeCheckbox">
                                    <label class="form-check-label" for="agreeCheckbox">
                                        I agree to the <a href="#">Terms and Conditions</a> & I am not a robot.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-end">
                                <button type="submit" id="submitBtn" name="send" class="submitBnt btn gradient-btn-5"  
                                    data-sitekey="6LdVl4EqAAAAAC5LVhDSc5Cx2L6UaV7-uNm7jqRb" 
                                    data-callback="onSubmit" 
                                    data-action="submit" disabled>
                                    Send Message
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- end row -->
    </div>
    <!-- end container -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const checkbox = document.getElementById('agreeCheckbox');
            const submitButton = document.getElementById('submitBtn');
    
            // Initially disable the button
            submitButton.disabled = true;
    
            // Enable/disable button based on checkbox
            checkbox.addEventListener('change', function () {
                submitButton.disabled = !this.checked;
            });
        });
    </script>
</section>