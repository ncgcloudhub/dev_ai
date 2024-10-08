@extends('admin.layouts.master')

@section('content')

@php
    $images_slider = App\Models\DalleImageGenerate::where('resolution', '1024x1024')->where('status', 'active')->inRandomOrder()->limit(14)->get();

    foreach ($images_slider as $image) {
        $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
    }


    $images = App\Models\DalleImageGenerate::where('status', 'active')->inRandomOrder()->limit(16)->get();

    foreach ($images as $image) {
        $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
    }

    $templates = App\Models\Template::where('inFrontEnd', 'yes')->inRandomOrder()->limit(8)->get();
    $promptLibrary = App\Models\PromptLibrary::where('inFrontEnd', 'yes')->inRandomOrder()->limit(8)->get();

@endphp

<div class="row">
    <div class="col">
        <h5 class="mb-3">Section Design Frontend</h5>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active show" id="custom-v-pills-banner-tab" data-bs-toggle="pill" href="#custom-v-pills-banner" role="tab" aria-controls="custom-v-pills-banner"
                            aria-selected="false">
                            <i class="ri-user-2-line d-block fs-20 mb-1"></i>
                            Home Banner</a>

                            <a class="nav-link" id="custom-v-pills-image_generate-tab" data-bs-toggle="pill" href="#custom-v-pills-image_generate" role="tab" aria-controls="custom-v-pills-home"
                            aria-selected="true">
                            <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                            Dall E Image Generate</a>

                            <a class="nav-link" id="custom-v-pills-home-tab" data-bs-toggle="pill" href="#custom-v-pills-home" role="tab" aria-controls="custom-v-pills-home"
                                aria-selected="true">
                                <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                                How It Works
                            </a>

                            <a class="nav-link" id="custom-v-pills-image_slider-tab" data-bs-toggle="pill" href="#custom-v-pills-image_slider" role="tab" aria-controls="custom-v-pills-image_slider"
                            aria-selected="true">
                            <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                            Image Gallery Slider</a>

                            <a class="nav-link" id="custom-v-pills-features-tab" data-bs-toggle="pill" href="#custom-v-pills-features" role="tab" aria-controls="custom-v-pills-features"
                            aria-selected="true">
                            <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                            Features</a>

                            <a class="nav-link" id="custom-v-pills-services-tab" data-bs-toggle="pill" href="#custom-v-pills-services" role="tab" aria-controls="custom-v-pills-services"
                            aria-selected="true">
                            <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                            Services</a>

                            <a class="nav-link" id="custom-v-pills-image_gallery-tab" data-bs-toggle="pill" href="#custom-v-pills-image_gallery" role="tab" aria-controls="custom-v-pills-image_gallery"
                            aria-selected="true">
                            <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                            Image Gallery</a>

                            <a class="nav-link" id="custom-v-pills-content_creator-tab" data-bs-toggle="pill" href="#custom-v-pills-content_creator" role="tab" aria-controls="custom-v-pills-content_creator"
                            aria-selected="true">
                            <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                            AI Content Creator</a>

                            <a class="nav-link" id="custom-v-pills-prompt_library-tab" data-bs-toggle="pill" href="#custom-v-pills-prompt_library" role="tab" aria-controls="custom-v-pills-content_creator"
                            aria-selected="true">
                            <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                            Prompt Library</a>
                         
                        </div>

                    </div> <!-- end col-->

                    <div class="col-lg-10">
                        <div class="tab-content text-muted mt-3 mt-lg-0">
                            <!-- How It Works Tab -->
                            <div class="tab-pane fade" id="custom-v-pills-home" role="tabpanel" aria-labelledby="custom-v-pills-home-tab">
                                <div class="d-flex mb-4">
                                    <form action="{{ route('user.update_design') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="how_it_works" name="how_it_works">
                                        @php
                                            $selectedDesign = isset($sectionDesigns['how_it_works']) ? $sectionDesigns['how_it_works']->selected_design : '';
                                        @endphp
                                    
                                        <div class="design-preview">
                                            <input type="radio" name="how_it_works_design" value="design1" id="how_design1" {{ $selectedDesign == 'design1' ? 'checked' : '' }}>
                                            <label for="how_design1">
                                                @include('frontend.designs.how_it_works.design_1') <!-- Include design 1 preview -->
                                            </label>
                                        </div>
                                    
                                        <div class="design-preview">
                                            <input type="radio" name="how_it_works_design" value="design2" id="how_design2" {{ $selectedDesign == 'design2' ? 'checked' : '' }}>
                                            <label for="how_design2">
                                                @include('frontend.designs.how_it_works.design_2') <!-- Include design 2 preview -->
                                            </label>
                                        </div>
                                    
                                        <div class="design-preview">
                                            <input type="radio" name="how_it_works_design" value="design3" id="how_design3" {{ $selectedDesign == 'design3' ? 'checked' : '' }}>
                                            <label for="how_design3">
                                                @include('frontend.designs.how_it_works.design_3') <!-- Include design 3 preview -->
                                            </label>
                                        </div>
                                    
                                        <button class="btn btn-primary" type="submit">Save Design</button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Banner Tab -->
                            <div class="tab-pane fade active show" id="custom-v-pills-banner" role="tabpanel" aria-labelledby="custom-v-pills-banner-tab">
                                <div class="d-flex mb-4">
                                    <form action="{{ route('user.update_design') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="banner" name="banner">
                                        @php
                                            $selectedDesign = isset($sectionDesigns['banner']) ? $sectionDesigns['banner']->selected_design : '';
                                        @endphp
                                    
                                        <div class="design-preview {{ $selectedDesign === 'design1' ? 'border card-border-success' : '' }}">
                                            <input type="radio" name="banner_design" value="design1" id="banner_design1" {{ $selectedDesign == 'design1' ? 'checked' : '' }}>
                                            <label for="banner_design1">
                                                @include('frontend.designs.banner_home.banner_deafult') <!-- Include design 1 preview -->
                                            </label>
                                        </div>
                                    
                                        <div class="design-preview {{ $selectedDesign === 'design2' ? 'border card-border-success' : '' }}">
                                            <input type="radio" name="banner_design" value="design2" id="banner_design2" {{ $selectedDesign == 'design2' ? 'checked' : '' }}>
                                            <label for="banner_design2">
                                                @include('frontend.designs.banner_home.banner_parallex') <!-- Include design 2 preview -->
                                            </label>
                                        </div>
                                    
                                        <button class="btn btn-primary" type="submit">Save Design</button>
                                    </form>
                                </div>
                            </div><!--end tab-pane-->

                            <!-- Features Tab -->
                            <div class="tab-pane fade" id="custom-v-pills-features" role="tabpanel" aria-labelledby="custom-v-pills-features-tab">
                                <div class="d-flex mb-4">
                                    <form action="{{ route('user.update_design') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="features" name="features">
                                        @php
                                            $selectedDesign = isset($sectionDesigns['features']) ? $sectionDesigns['features']->selected_design : '';
                                        @endphp
                                    
                                        <div class="design-preview">
                                            <input type="radio" name="features_design" value="design1" id="feature1" {{ $selectedDesign == 'design1' ? 'checked' : '' }}>
                                            <label for="feature1">
                                                @include('frontend.designs.features.feature_1') <!-- Include design 1 preview -->
                                            </label>
                                        </div>
                                    
                                        <div class="design-preview">
                                            <input type="radio" name="features_design" value="design2" id="feature2" {{ $selectedDesign == 'design2' ? 'checked' : '' }}>
                                            <label for="feature2">
                                                @include('frontend.designs.features.feature_2') <!-- Include design 2 preview -->
                                            </label>
                                        </div>
                                    
                                        <button class="btn btn-primary" type="submit">Save Design</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Services Tab -->
                            <div class="tab-pane fade" id="custom-v-pills-services" role="tabpanel" aria-labelledby="custom-v-pills-services-tab">
                                <div class="d-flex mb-4">
                                    <form action="{{ route('user.update_design') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="services" name="services">
                                        @php
                                            $selectedDesign = isset($sectionDesigns['services']) ? $sectionDesigns['services']->selected_design : '';
                                        @endphp
                                    
                                        <div class="design-preview">
                                            <input type="radio" name="services_design" value="design1" id="service1" {{ $selectedDesign == 'design1' ? 'checked' : '' }}>
                                            <label for="service1">
                                                @include('frontend.designs.services.services_1') <!-- Include design 1 preview -->
                                            </label>
                                        </div>
                                    
                                        <div class="design-preview">
                                            <input type="radio" name="services_design" value="design2" id="service2" {{ $selectedDesign == 'design2' ? 'checked' : '' }}>
                                            <label for="service2">
                                                @include('frontend.designs.services.services_2') <!-- Include design 2 preview -->
                                            </label>
                                        </div>
                                    
                                        <button class="btn btn-primary" type="submit">Save Design</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Image Generate Tab -->
                            <div class="tab-pane fade" id="custom-v-pills-image_generate" role="tabpanel" aria-labelledby="custom-v-pills-image_generate-tab">
                                <div class="d-flex mb-4">
                                    <form action="{{ route('user.update_design') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="image_generate" name="image_generate">
                                        @php
                                            $selectedDesign = isset($sectionDesigns['image_generate']) ? $sectionDesigns['image_generate']->selected_design : '';
                                        @endphp
                                    
                                        <div class="col">

                                            <div class="design-preview">
                                                <input type="radio" name="image_generate_design" value="design1" id="image_generate1" {{ $selectedDesign == 'design1' ? 'checked' : '' }}>
                                                <label for="image_generate1">
                                                    @include('frontend.designs.image_generate.image_generate_1') <!-- Include design 1 preview -->
                                                </label>
                                            </div>

                                            <div class="design-preview">
                                                <input type="radio" name="image_generate_design" value="design2" id="image_generate2" {{ $selectedDesign == 'design2' ? 'checked' : '' }}>
                                                <label for="image_generate2">
                                                    @include('frontend.designs.image_generate.image_generate_2') <!-- Include design 2 preview -->
                                                </label>
                                            </div>

                                            <button class="btn btn-primary" type="submit">Save Design</button>
                                            
                                        </div>
                                    
                                        
                                    </form>
                                </div>
                            </div>


                            <!-- Image Gallery Slider Tab -->
                            <div class="tab-pane fade" id="custom-v-pills-image_slider" role="tabpanel" aria-labelledby="custom-v-pills-image_slider-tab">
                                <div class="d-flex mb-4">
                                    <form action="{{ route('user.update_design') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="image_slider" name="image_slider">
                                        @php
                                            $selectedDesign = isset($sectionDesigns['image_slider']) ? $sectionDesigns['image_slider']->selected_design : '';
                                        @endphp
                                    
                                      

                                            <div class="design-preview">
                                                <input type="radio" name="image_slider_design" value="design1" id="image_slider1" {{ $selectedDesign == 'design1' ? 'checked' : '' }}>
                                                <label for="image_slider1">
                                                    @include('frontend.designs.ai_image_gallery_slider.image_slider_1') <!-- Include design 1 preview -->
                                                </label>
                                            </div>

                                            <div class="design-preview">
                                                <input type="radio" name="image_slider_design" value="design2" id="image_slider2" {{ $selectedDesign == 'design2' ? 'checked' : '' }}>
                                                <label for="image_slider2">
                                                    @include('frontend.designs.ai_image_gallery_slider.image_slider_2') <!-- Include design 2 preview -->
                                                </label>
                                            </div>

                                            <button class="btn btn-primary" type="submit">Save Design</button>
                                            
                                       
                                    
                                        
                                    </form>
                                </div>
                            </div>

                            <!-- Image Gallery Tab -->
                            <div class="tab-pane fade" id="custom-v-pills-image_gallery" role="tabpanel" aria-labelledby="custom-v-pills-image_gallery-tab">
                                <div class="d-flex mb-4">
                                    <form action="{{ route('user.update_design') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="image_gallery" name="image_gallery">
                                        @php
                                            $selectedDesign = isset($sectionDesigns['image_gallery']) ? $sectionDesigns['image_gallery']->selected_design : '';
                                        @endphp
                                    
                                            <div class="design-preview">
                                                <input type="radio" name="image_gallery_design" value="design1" id="image_gallery1" {{ $selectedDesign == 'design1' ? 'checked' : '' }}>
                                                <label for="image_gallery1">
                                                    @include('frontend.designs.ai_image_gallery.gallery_1') <!-- Include design 1 preview -->
                                                </label>
                                            </div>

                                            <div class="design-preview">
                                                <input type="radio" name="image_gallery_design" value="design2" id="image_gallery2" {{ $selectedDesign == 'design2' ? 'checked' : '' }}>
                                                <label for="image_gallery2">
                                                    @include('frontend.designs.ai_image_gallery.gallery_2') <!-- Include design 2 preview -->
                                                </label>
                                            </div>

                                            <button class="btn btn-primary" type="submit">Save Design</button>
         
                                        
                                    </form>
                                </div>
                            </div>


                            <!-- AI Content Creator Tab -->
                            <div class="tab-pane fade" id="custom-v-pills-content_creator" role="tabpanel" aria-labelledby="custom-v-pills-content_creator-tab">
                                <div class="d-flex mb-4">
                                    <form action="{{ route('user.update_design') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="content_creator" name="content_creator">
                                        @php
                                            $selectedDesign = isset($sectionDesigns['content_creator']) ? $sectionDesigns['content_creator']->selected_design : '';
                                        @endphp
                                    
                                            <div class="design-preview">
                                                <input type="radio" name="content_creator_design" value="design1" id="content_creator1" {{ $selectedDesign == 'design1' ? 'checked' : '' }}>
                                                <label for="content_creator1">
                                                    @include('frontend.designs.ai_content_creator.content_creator_1') <!-- Include design 1 preview -->
                                                </label>
                                            </div>

                                            <div class="design-preview">
                                                <input type="radio" name="content_creator_design" value="design2" id="content_creator2" {{ $selectedDesign == 'design2' ? 'checked' : '' }}>
                                                <label for="content_creator2">
                                                    @include('frontend.designs.ai_content_creator.content_creator_2') <!-- Include design 2 preview -->
                                                </label>
                                            </div>

                                            <button class="btn btn-primary" type="submit">Save Design</button>

                                    </form>
                                </div>
                            </div>

                             <!-- Prompt Library Tab -->
                             <div class="tab-pane fade" id="custom-v-pills-prompt_library" role="tabpanel" aria-labelledby="custom-v-pills-prompt_library-tab">
                                <div class="d-flex mb-4">
                                    <form action="{{ route('user.update_design') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="prompt_library" name="prompt_library">
                                        @php
                                            $selectedDesign = isset($sectionDesigns['prompt_library']) ? $sectionDesigns['prompt_library']->selected_design : '';
                                        @endphp
                                    
                                            <div class="design-preview">
                                                <input type="radio" name="prompt_library_design" value="design1" id="prompt_library1" {{ $selectedDesign == 'design1' ? 'checked' : '' }}>
                                                <label for="prompt_library1">
                                                    @include('frontend.designs.prompt_library.prompt_library_1') <!-- Include design 1 preview -->
                                                </label>
                                            </div>

                                            <div class="design-preview">
                                                <input type="radio" name="prompt_library_design" value="design2" id="prompt_library2" {{ $selectedDesign == 'design2' ? 'checked' : '' }}>
                                                <label for="prompt_library2">
                                                    @include('frontend.designs.prompt_library.prompt_library_2') <!-- Include design 2 preview -->
                                                </label>
                                            </div>

                                            <button class="btn btn-primary" type="submit">Save Design</button>

                                    </form>
                                </div>
                            </div>



                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row-->
            </div><!-- end card-body -->
        </div><!--end card-->
    </div><!--end col-->
</div>

@endsection