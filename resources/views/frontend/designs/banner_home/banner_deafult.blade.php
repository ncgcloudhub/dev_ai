<section class="section pb-0 hero-section" id="hero">

    <div id="banner" class="banner full-screen-mode parallax">
        <div class="container pr">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="banner-static">  
                    <div class="banner-text py-5">
                        <div class="banner-cell py-5">
                            <h2 style="color: rgb(255, 255, 255)">
                               Get AI services Like
                                <span
                                    class="typer"
                                    id="some-id"
                                    data-delay="200"
                                    data-delim=":"
                                    data-words="AI Image Generation:AI Assistant:AI Blog Generation: AI Article Generate"
                                    data-colors="purple"
                                ></span>
                            </h2>
                            <h1 style="color: #e900ff; font-family: Shantell Sans, cursive;">Clever Creator</h1>
                            <p style="color: white">
                                Empower Your Creativity with Our AI: Generate Images, Craft Content, and Chat Seamlessly with Our OpenAI-Powered Assistant!
                            </p>
                            
                            @if (Auth::check())
                            
                            @else
                          
                            <a href="{{ route('register') }}" class="btn gradient-btn-3 waves-effect waves-light">Sign Up for Free AI Services</a>
                                                 
                            @endif                           

                            {{-- UI Card Start --}}
                            <div class="row row-cols-xxl-5 row-cols-lg-6 row-cols-1 justify-content-center py-5">
                                <div class="col d-flex flex-column">
                                    <div class="card card-body glass flex-grow-1 d-flex flex-column justify-content-between card-background-common" style="min-height: 200px; position: relative; overflow: hidden;">
                                        <video autoplay loop muted playsinline class="w-100 h-100 position-absolute" style="object-fit: cover; top: 0; left: 0; z-index: -1;">
                                            <source src="{{ asset('build/images/bb5.webm') }}" type="video/webm">
                                            Your browser does not support the video tag.
                                        </video>
                                        <a href="{{ auth()->check() ? route('generate.image.view') : route('login') }}" class="btn waves-effect waves-light mt-auto gradient-btn-5">Generate Images</a>
                                    </div>
                                </div><!-- end col -->
                            
                                <div class="col d-flex flex-column position-relative">
                                    <div class="card card-body glass flex-grow-1 d-flex flex-column justify-content-between card-background-common" style="min-height: 200px; position: relative; overflow: hidden;">
                                        <video autoplay loop muted playsinline class="w-100 h-100 position-absolute" style="object-fit: cover; top: 0; left: 0; z-index: -1;">
                                            <source src="{{ asset('build/images/bb1.webm') }}" type="video/webm">
                                            Your browser does not support the video tag.
                                        </video>
                                        <!-- Your card content here -->
                                        <a href="{{ auth()->check() ? route('aicontentcreator.manage') : route('login') }}" class="btn gradient-btn-5 waves-effect waves-light mt-auto">Generate Contents</a>
                                      
                                    </div>
                                </div><!-- end col -->
                                
                            
                                <div class="col d-flex flex-column">
                                    <div class="card card-body glass flex-grow-1 d-flex flex-column justify-content-between card-background-common" style="min-height: 200px; position: relative; overflow: hidden;">
                                        <video autoplay loop muted playsinline class="w-100 h-100 position-absolute" style="object-fit: cover; top: 0; left: 0; z-index: -1;">
                                            <source src="{{ asset('build/images/bb4.webm') }}" type="video/webm">
                                            Your browser does not support the video tag.
                                        </video>
                                        @auth                                                 
                                            <a href="{{ route('prompt.manage') }}" class="btn gradient-btn-5 waves-effect waves-light mt-auto">Prompt Library</a> 
                                        @else
                                            <a href="{{ route('frontend.free.prompt.library') }}" class="btn gradient-btn-5 waves-effect waves-light mt-auto">Prompt Library</a> <!-- Redirect to frontend.free.prompt.library if no one is logged in -->
                                        @endauth

                                    </div>
                                </div><!-- end col -->
                            
                                <div class="col d-flex flex-column">
                                    <div class="card card-body glass flex-grow-1 d-flex flex-column justify-content-between card-background-common" style="min-height: 200px; position: relative; overflow: hidden;">
                                        <video autoplay loop muted playsinline class="w-100 h-100 position-absolute" style="object-fit: cover; top: 0; left: 0; z-index: -1;">
                                            <source src="{{ asset('build/images/bb3.webm') }}" type="video/webm">
                                            Your browser does not support the video tag.
                                        </video>
                                        <a href="{{ auth()->check() ? route('main.chat.form') : route('login') }}" class="btn gradient-btn-5 waves-effect waves-light mt-auto">Chat Bot</a>
                                    </div>
                                </div><!-- end col -->
                            
                            </div><!-- end row -->
                            
                            {{-- UI Card End --}}

                        </div>
                        <!-- end banner-cell -->
                    </div>
                    <!-- end banner-text -->
                </div>
                <!-- end banner-static -->
            </div>
            <!-- end col -->
        </div>
        <!-- end container -->
    </div>
    <!-- end banner -->
    
    
  
</section>