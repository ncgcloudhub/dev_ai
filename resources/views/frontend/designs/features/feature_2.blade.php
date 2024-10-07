<section class="pb-0" id="features">
    <style>
        #features {
            position: relative;
            width: 100%;
            height: auto; /* Adjust height for mobile */
            overflow: hidden;
            padding: 30px 0; /* Add some padding */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        #features img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            pointer-events: none;
        }

        .feature-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
        }

        .feature-content h1 {
            font-size: 2.5em;
            color: #fff;
            text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.7);
        }

        .feature-box {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            padding: 20px;
            text-align: left;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%;
        }

        .feature-box h5 {
            font-size: 1.5em;
            color: #fff;
        }

        .feature-box p {
            color: #ddd;
        }

        .icon-effect {
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            width: 60px;
            height: 60px;
        }

        .icon-effect i {
            font-size: 36px;
            color: #4caf50;
        }

        .feature-box-container {
            height: auto; /* Adjust height for content */
            width: 100%; 
            max-width: 350px;
            margin-bottom: 20px; /* Add margin for spacing between cards */
        }

        /* Adjust layout for smaller screens */
        @media (max-width: 768px) {
            .row {
                flex-direction: column; /* Stack cards vertically */
                align-items: center;
            }

            .feature-content h1 {
                font-size: 1.8em; /* Smaller heading on mobile */
            }

            .feature-box {
                padding: 15px; /* Reduce padding for smaller screens */
            }

            .icon-effect {
                width: 50px;
                height: 50px;
            }

            .icon-effect i {
                font-size: 30px;
            }

            .feature-box h5 {
                font-size: 1.2em;
            }

            .feature-box p {
                font-size: 0.9em;
            }
        }
    </style>

    <img src="https://www.microsoft.com/en-us/research/uploads/prod/2023/03/AI_Microsoft_Research_Header_1920x720.png" alt="Background" id="bg">

    <div class="feature-content">
        <h1 class="ff-secondary fw-semibold">Awesome Features</h1>
        <p class="text-muted">Elevate Your Digital Presence with Intelligent Solutions - Unleashing AI Chatbots, Multimedia Transformation, and Dynamic Content Generation</p>

        <div class="row g-3">
            <!-- AI Chatbot Integration -->
            <div class="col-lg-4 feature-box-container">
                <div class="feature-box">
                    <div class="d-flex align-items-center">
                        <div class="icon-effect">
                            <i class="ri-pencil-ruler-2-line"></i>
                        </div>
                        <div class="ms-3">
                            <h5>AI Chatbot Integration</h5>
                            <p>Engage and assist your website visitors with advanced AI chatbot communication.</p>
                            <a href="{{ route('chat') }}" class="text-white">Learn More <i class="ri-arrow-right-s-line"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Text to Image Conversion -->
            <div class="col-lg-4 feature-box-container">
                <div class="feature-box">
                    <div class="d-flex align-items-center">
                        <div class="icon-effect">
                            <i class="ri-palette-line"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Text to Image Conversion</h5>
                            <p>Transform textual content into captivating visuals effortlessly with TrionxAI.</p>
                            <a href="{{ route('generate.image.view') }}" class="text-white">Learn More <i class="ri-arrow-right-s-line"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Custom Template -->
            <div class="col-lg-4 feature-box-container">
                <div class="feature-box">
                    <div class="d-flex align-items-center">
                        <div class="icon-effect">
                            <i class="ri-lightbulb-flash-line"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Create Custom Template</h5>
                            <p>Create personalized content with the OpenAI API, offering flexible customization.</p>
                            <a href="{{ route('custom.template.manage') }}" class="text-white">Learn More <i class="ri-arrow-right-s-line"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row -->
        <div class="row g-3 mt-4">
            <!-- AI Blog Generate -->
            <div class="col-lg-4 feature-box-container">
                <div class="feature-box">
                    <div class="d-flex align-items-center">
                        <div class="icon-effect avatar-title">
                            <i class="ri-customer-service-line fs-36"></i>
                        </div>
                        <div class="ms-3">
                            <h5>AI Blog Generate</h5>
                            <p class="text-muted my-3 ff-secondary">Awesome Support is the most versatile and feature-rich support plugin for all version.</p>
                            <a href="#" class="text-white">Learn More <i class="ri-arrow-right-s-line"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generate Facebook Ads -->
            <div class="col-lg-4 feature-box-container">
                <div class="feature-box">
                    <div class="d-flex align-items-center">
                        <div class="icon-effect avatar-title">
                            <i class="ri-stack-line fs-36"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Generate Facebook Ads</h5>
                            <p class="text-muted my-3 ff-secondary">You usually get a broad range of options to play with. This enables you to use a single theme across multiple.</p>
                            <a href="#" class="text-white">Learn More <i class="ri-arrow-right-s-line"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Grammar Checker -->
            <div class="col-lg-4 feature-box-container">
                <div class="feature-box">
                    <div class="d-flex align-items-center">
                        <div class="icon-effect avatar-title">
                            <i class="ri-settings-2-line fs-36"></i>
                        </div>
                        <div class="ms-3">
                            <h5>AI Grammar Checker</h5>
                            <p class="text-muted my-3 ff-secondary">Personalize your own website, no matter what theme and what customization options.</p>
                            <a href="#" class="text-white">Learn More <i class="ri-arrow-right-s-line"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let bg = document.getElementById("bg");

        window.addEventListener('scroll', function () {
            var value = window.scrollY;
            bg.style.top = value * 0.5 + 'px';  // Parallax effect for background
        });
    </script>
</section>
