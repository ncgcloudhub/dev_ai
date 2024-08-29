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
                            <a class="nav-link active show" id="custom-v-pills-home-tab" data-bs-toggle="pill" href="#custom-v-pills-home" role="tab" aria-controls="custom-v-pills-home"
                                aria-selected="true">
                                <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                                How It Works</a>
                            <a class="nav-link" id="custom-v-pills-profile-tab" data-bs-toggle="pill" href="#custom-v-pills-profile" role="tab" aria-controls="custom-v-pills-profile"
                                aria-selected="false">
                                <i class="ri-user-2-line d-block fs-20 mb-1"></i>
                                SEO</a>
                         
                        </div>
                    </div> <!-- end col-->
                    <div class="col-lg-10">
                        <div class="tab-content text-muted mt-3 mt-lg-0">
                            <div class="tab-pane fade active show" id="custom-v-pills-home" role="tabpanel" aria-labelledby="custom-v-pills-home-tab">
                                <div class="d-flex mb-4">
                                    <form action="{{ route('user.update_design') }}" method="POST">
                                        @csrf
                                    
                                        @php
                                            // Check if 'how_it_works' exists in the sectionDesigns array
                                            $selectedDesign = isset($sectionDesigns['how_it_works']) ? $sectionDesigns['how_it_works']->selected_design : '';
                                        @endphp
                                    
                                        <div class="design-preview">
                                            <input type="radio" name="selected_design" value="design1" id="design1" {{ $selectedDesign == 'design1' ? 'checked' : '' }}>
                                            <label for="design1">
                                                @include('frontend.designs.how_it_works.design_1') <!-- Include design 1 preview -->
                                            </label>
                                        </div>
                                    
                                        <div class="design-preview">
                                            <input type="radio" name="selected_design" value="design2" id="design2" {{ $selectedDesign == 'design2' ? 'checked' : '' }}>
                                            <label for="design2">
                                                @include('frontend.designs.how_it_works.design_2') <!-- Include design 2 preview -->
                                            </label>
                                        </div>
                                    
                                        <div class="design-preview">
                                            <input type="radio" name="selected_design" value="design3" id="design3" {{ $selectedDesign == 'design3' ? 'checked' : '' }}>
                                            <label for="design3">
                                                @include('frontend.designs.how_it_works.design_3') <!-- Include design 3 preview -->
                                            </label>
                                        </div>
                                    
                                        <button class="btn btn-primary" type="submit">Save Design</button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="custom-v-pills-profile" role="tabpanel" aria-labelledby="custom-v-pills-profile-tab">
                                <div class="d-flex mb-4">
                                   Design 1
                                   Design 2
                                   Design 3
                                </div>
                               
                            </div><!--end tab-pane-->
    
    
    
    
                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row-->
            </div><!-- end card-body -->
        </div><!--end card-->
    </div><!--end col-->
    </div>






    
@endsection
