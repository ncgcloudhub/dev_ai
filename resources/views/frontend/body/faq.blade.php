<section class="section" id="faq">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h3 class="mb-3 fw-semibold">Frequently Asked Questions</h3>
                    <p class="text-muted mb-4 ff-secondary">If you can not find answer to your question in our
                        FAQ, you can
                        always contact us or email us. We will answer you shortly!</p>

                    <div class="hstack gap-2 justify-content-center">
                        <button type="button" class="btn btn-primary btn-label rounded-pill">
                            <a href="mailto:clevercreatorai@gmail.com" class="text-white text-decoration-none">
                                <i class="ri-mail-line label-icon align-middle rounded-pill fs-16 me-2"></i> Email Us
                            </a>
                        </button> 
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row g-lg-5 g-4 d-flex justify-content-center">
            <div class="col-lg-6">
                <div class="d-flex align-items-center mb-2">
                    <div class="flex-shrink-0 me-1">
                        <i class="ri-question-line fs-24 align-middle text-success me-1"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-0 fw-semibold">General Questions</h5>
                    </div>
                </div>
                <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box" id="genques-accordion">
                    @foreach ($faqs as $index => $item)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="genques-heading{{$index}}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapse{{$index}}" aria-expanded="false" aria-controls="genques-collapse{{$index}}">
                                   {{$item->question}}
                                </button>
                            </h2>
                            <div id="genques-collapse{{$index}}" class="accordion-collapse collapse" aria-labelledby="genques-heading{{$index}}" data-bs-parent="#genques-accordion">
                                <div class="accordion-body ff-secondary">
                                    {{$item->answer}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!--end accordion-->

            </div>
            <!-- end col -->
           
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>