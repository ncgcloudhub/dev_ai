<section class="section bg-light" id="plans">
    <div class="bg-overlay bg-overlay-pattern"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h3 class="mb-3 fw-semibold">Choose the plan that's right for you</h3>
                    <p class="text-muted mb-4">Simple pricing. No hidden fees. Advanced features for you
                        business.</p>

                    <div class="d-flex justify-content-center align-items-center">
                        <div>
                            <h5 class="fs-14 mb-0">Month</h5>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

        <div class="row gy-4 justify-content-center">
            <div class="col-lg-4">
                <div class="card plan-box mb-0">
                    <div class="card-body bg-light m-2 p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fw-semibold">Starter</h5>
                            </div>
                            <div class="ms-auto">
                                <h2 class="month mb-0 text-success">Free</h2>
                            </div>
                        </div>
    
                        <p class="text-muted">The perfect way to get started and get used to our tools.</p>
                        <ul class="list-unstyled vstack gap-3">
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>Chat GPT 4</b> Open AI Model
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>Dall-E 3</b> Image Generate
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>75</b> AI Templates
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Custom Templates
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>50</b> Images per week
                                    </div>
                                </div>
                            </li>
                           
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>5000</b> Words
                                    </div>
                                </div>
                            </li>
                         
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>Unlimited</b> Login
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                       Free Support
                                    </div>
                                </div>
                            </li>
                          
                        </ul>

                        @if (Auth::check())

                            <div class="mt-3 pt-2">
                                <a href="javascript:void(0);" class="btn btn-success disabled w-100">Your Current Plan</a>
                            </div>   

                        @else

                            <div class="mt-3 pt-2">
                                <a href="{{route('register')}}" class="btn btn-success w-100">Sign Up for Free</a>
                            </div>
                                              
                        @endif

                    </div>
                </div>
            </div>
            <!--end col-->
           
        </div>
        <!--end row-->
    </div>
    <!-- end container -->
</section>