<footer class="custom-footer bg-dark py-5 position-relative">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mt-4">
                <div>
                    <div>
                        <img src="{{ URL::asset('build/images/logo-light1.png') }}" class="card-logo " alt="logo light"
                height="40">
                    </div>
                    <div class="mt-4 fs-13">
                        <p>Empower Your Projects with AI</p>
                        <p class="ff-secondary">You can build any type of web application like eCommerce,
                            Image Generation, Content Creation, and Intelligent Chat Assistants </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 ms-lg-auto">
                <div class="row">
                    <div class="col-sm-4 mt-4">
                        <h5 class="text-white mb-0">Company</h5>
                        <div class="text-muted mt-3">
                            <ul class="list-unstyled ff-secondary footer-list fs-14">
                                <li><a href="pages-profile">About Us</a></li>
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
                                <li><a href="{{route('template.manage')}}">Generate Content</a></li>
                                <li><a href="{{route('chat')}}">Chat</a></li>
                                
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4 mt-4">
                        <h5 class="text-white mb-0">Support</h5>
                        <div class="text-muted mt-3">
                            <ul class="list-unstyled ff-secondary footer-list fs-14">
                                <li><a href="pages-faqs">FAQ</a></li>
                                <li><a href="{{route('contact.us')}}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row text-center text-sm-start align-items-center mt-5">
            <div class="col-sm-6">

                <div>
                    <p class="copy-rights mb-0">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © {{ $siteSettings->title }}
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