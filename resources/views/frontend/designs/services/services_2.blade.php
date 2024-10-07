<section class="section" id="services-section" style="background-image: url('https://www.sas.com/de_de/solutions/ai/composite/_jcr_content/par/styledcontainer_ced3/image.img.jpg/1620334713166.jpg'); background-size: cover; background-position: center; padding: 60px 0;">
    <style>
        #services-section {
            position: relative;
            color: white; /* Change text color for better visibility */
            padding: 60px 0; /* Adjust padding for spacing */
        }

        #services-section .container {
            position: relative;
            z-index: 1;
        }

        #services-section h3 {
            font-size: 2.5rem; /* Increase heading size */
            margin-bottom: 20px;
            font-weight: 700; /* Make heading bolder */
            text-align: center; /* Center align heading */
        }

        #services-section p {
            font-size: 1.2rem; /* Increase paragraph size */
            margin-bottom: 30px;
            text-align: center; /* Center align paragraph */
        }

        #services-section .card {
            background: rgba(24, 18, 18, 0.658); /* Darker semi-transparent background for cards */
            border: none;
            border-radius: 12px; /* Rounded corners */
            padding: 20px;
            margin: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center; /* Center align card content */
        }

        #services-section .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }

        #services-section .list-unstyled {
            display: flex;
            flex-wrap: wrap; /* Allow cards to wrap */
            justify-content: center; /* Center align cards */
            padding: 0;
        }

        #services-section .list-unstyled li {
            flex: 0 0 calc(33.33% - 30px); /* Three cards per row */
            margin: 15px; /* Space between cards */
            max-width: 300px; /* Limit maximum width */
        }

        #services-section .list-unstyled .card-content {
            display: flex; /* Use flexbox for alignment */
            align-items: center; /* Center align items vertically */
            justify-content: center; /* Center align items horizontally */
            text-align: left; /* Left align text */
        }

        #services-section .list-unstyled svg {
            margin-right: 10px; /* Space between icon and text */
            color: #007bff; /* Icon color */
            width: 40px; /* Increase icon size */
            height: 40px; /* Increase icon size */
        }

        .explore-box {
            text-align: center;
            margin-top: 30px; /* Space above the button */
        }

        .place-bid-btn .btn {
            background-color: #007bff; /* Primary button color */
            border: none;
            padding: 12px 20px; /* Button padding */
            border-radius: 5px; /* Button rounded corners */
            font-size: 1.125rem; /* Button font size */
            transition: background-color 0.3s;
        }

        .place-bid-btn .btn:hover {
            background-color: #0056b3; /* Darker button on hover */
        }
        

        @media (max-width: 768px) {
            #services-section .list-unstyled li {
                flex: 0 0 calc(50% - 20px); /* Two cards per row on smaller screens */
            }
        }

        @media (max-width: 576px) {
            #services-section .list-unstyled li {
                flex: 0 0 100%; /* One card per row on very small screens */
            }
        }
    </style>

    <div class="container">
        <h3 style="color: white" class="mb-3">Huge Collection of Templates</h3>
        <p class="mb-4">Select from the list of our fixed templates</p>

        <ul class="list-unstyled">
            <li>
                <div class="card">
                    <div class="card-content">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                        <h5 style="color: white">Blog Content</h5>
                    </div>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="card-content">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <h5 style="color: white">Email Template</h5>
                    </div>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="card-content">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2">
                            <circle cx="18" cy="5" r="3"></circle>
                            <circle cx="6" cy="12" r="3"></circle>
                            <circle cx="18" cy="19" r="3"></circle>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                        </svg>
                        <h5 style="color: white">Social Media</h5>
                    </div>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="card-content">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video">
                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                        </svg>
                        <h5 style="color: white">Video Content</h5>
                    </div>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="card-content">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y1="21"></line>
                        </svg>
                        <h5 style="color: white">Website Content</h5>
                    </div>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="card-content">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smile">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                        </svg>
                        <h5 style="color: white">User Experience</h5>
                    </div>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="card-content">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-palette">
                            <path d="M12 2C6.48 2 2 6.48 2 12c0 2.21 1.09 4.16 2.74 5.32C5.29 18.44 4 20 4 20h16s-1.29-1.56-2.74-2.68C20.91 16.16 22 14.21 22 12c0-5.52-4.48-10-10-10zm-1 15H9v-2h2v2zm2 0h-2v-2h2v2zm3.4-6c-.26 1.02-1.02 1.8-2.4 1.8-1.38 0-2.4-.78-2.4-1.8s1.02-1.8 2.4-1.8c1.38 0 2.14.78 2.4 1.8z"></path>
                        </svg>
                        <h5 style="color: white">Creative Design</h5>
                    </div>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="card-content">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                            <rect x="5" y="3" width="14" height="4" rx="2"></rect>
                            <rect x="5" y="11" width="14" height="4" rx="2"></rect>
                            <rect x="5" y="19" width="14" height="4" rx="2"></rect>
                        </svg>
                        <h5 style="color: white">Data Management</h5>
                    </div>
                </div>
            </li>
        </ul>

        <div class="explore-box">
            <a href="#!" class="place-bid-btn">
                <button class="btn btn-primary">Explore More</button>
            </a>
        </div>
    </div>
</section>
