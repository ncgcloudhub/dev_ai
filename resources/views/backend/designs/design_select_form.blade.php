@extends('admin.layouts.master')

@section('content')

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

                            <a class="nav-link" id="custom-v-pills-home-tab" data-bs-toggle="pill" href="#custom-v-pills-home" role="tab" aria-controls="custom-v-pills-home"
                                aria-selected="true">
                                <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                                How It Works
                            </a>

                            <a class="nav-link" id="custom-v-pills-features-tab" data-bs-toggle="pill" href="#custom-v-pills-features" role="tab" aria-controls="custom-v-pills-features"
                            aria-selected="true">
                            <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                            Features</a>

                            <a class="nav-link" id="custom-v-pills-services-tab" data-bs-toggle="pill" href="#custom-v-pills-services" role="tab" aria-controls="custom-v-pills-services"
                            aria-selected="true">
                            <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                            Services</a>
                         
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

                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row-->
            </div><!-- end card-body -->
        </div><!--end card-->
    </div><!--end col-->
</div>

@endsection
