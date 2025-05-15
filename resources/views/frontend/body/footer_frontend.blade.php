<footer class="custom-footer bg-dark py-5 position-relative">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mt-4">
                <div>
                    <div>
                        <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->footer_logo }}" class="card-logo " alt="logo light"
                height="40">
                    </div>
                    <div class="mt-4 fs-13">
                        <p>Empower Your Projects with AI</p>
                        <p class="ff-secondary">You can build any type of web application like eCommerce,
                            Image Generation, Content Creation, and Intelligent Chat Assistants </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 ms-lg-auto">
                <div class="row">
                    <div class="col-sm-4 mt-4">
                        <h5 class="text-white mb-0">Company</h5>
                        <div class="text-muted mt-3">
                            <ul class="list-unstyled ff-secondary footer-list fs-14">
                                {{-- <li><a href="pages-profile">About Us</a></li> --}}
                                <li><a href="{{route('privacy.policy')}}">Privacy Policy</a></li>
                                <li><a href="{{route('terms.condition')}}">Terms & Conditions</a></li>
                                <li><a href="{{route('ai.image.gallery')}}">Gallery</a></li>
                              
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4 mt-4">
                        <h5 class="text-white mb-0">AI Services</h5>
                        <div class="text-muted mt-3">
                            <ul class="list-unstyled ff-secondary footer-list fs-14">
                                <li><a href="{{route('generate.image.view')}}">Image generate</a></li>
                                <li><a href="{{route('aicontentcreator.manage')}}">Generate Content</a></li>
                                <li><a href="{{route('chat')}}">Chat</a></li>
                                
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4 mt-4">
                        <h5 class="text-white mb-0">Support</h5>
                        <div class="text-muted mt-3">
                            <ul class="list-unstyled ff-secondary footer-list fs-14">
                                <li><a href="#faq">FAQ</a></li>
                                <li><a href="{{route('contact.us')}}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 ms-lg-auto">
                <!-- Newsletter Section -->
                <div class="row mt-4" id="footer-newsletter">
                    <div>
                        <h5 class="text-white mb-3">Subscribe to Our Newsletter</h5>
                        <form action="{{ route('newsletter.store') }}#footer-newsletter" method="post">
                            @csrf
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Your Email Address" name="email" required>
                                <button class="btn gradient-btn-5" type="submit" id="footerSubmitButton" disabled>Subscribe</button>
                            </div>
                            <!-- Checkbox -->
                            <div class="mb-3 form-check mt-3">
                                <input type="checkbox" class="form-check-input" id="footerTermsAgreement" required>
                                <label class="form-check-label" for="footerTermsAgreement">
                                    By subscribing, I agree to Clever Creator's
                                    <a href="#" class="gradient-text-1" data-bs-toggle="modal" data-bs-target="#footerTermsModal">Consumer Terms</a> 
                                    and 
                                    <a href="#" class="gradient-text-1" data-bs-toggle="modal" data-bs-target="#footerPrivacyModal">Privacy Policy</a>.
                                </label>
                            </div>
                        </form>
                    </div>
                    @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                </div>
            </div>
            

        </div>

        <div class="row text-center text-sm-start align-items-center mt-5">
            <div class="col-sm-6">

                <div>
                    <p class="copy-rights mb-0">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © {{ $siteSettings->footer_text }}
                    </p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end mt-3 mt-sm-0">
                    <ul class="list-inline mb-0 footer-social-link">
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="avatar-xs d-block">
                                <div class="avatar-title rounded-circle">
                                    <i class="ri-facebook-fill"></i>
                                </div>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="avatar-xs d-block">
                                <div class="avatar-title rounded-circle">
                                    <i class="ri-github-fill"></i>
                                </div>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="avatar-xs d-block">
                                <div class="avatar-title rounded-circle">
                                    <i class="ri-linkedin-fill"></i>
                                </div>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="avatar-xs d-block">
                                <div class="avatar-title rounded-circle">
                                    <i class="ri-google-fill"></i>
                                </div>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="avatar-xs d-block">
                                <div class="avatar-title rounded-circle">
                                    <i class="ri-dribbble-line"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

       
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('footerTermsAgreement');
        const submitButton = document.getElementById('footerSubmitButton');

        checkbox.addEventListener('change', function () {
            submitButton.disabled = !checkbox.checked;
        });
    });
</script>
