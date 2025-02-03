@extends('admin.layouts.master')
@section('title')
    @lang('translation.pricing')
@endsection
@section('content')
    @component('admin.components.breadcrumb')
    @slot('li_1') <a href="{{route('manage.pricing')}}">Pricing</a> @endslot
        @slot('title')
            Edit
        @endslot
    @endcomponent
 
    <div class="row">
        <div class="col-xxl-4 col-lg-6">
            <form method="POST" action="{{ route('pricing.update', ['pricingPlan' => $pricing_plan->id]) }}">
                @csrf
                @method('PUT')
                <div class="card pricing-box">
                    <div class="card-body bg-light m-2 p-4">
                    
                            <div class="flex-grow-1 d-flex align-items-center m-2">
                                <input class="form-control form-control-sm" type="text" name="title" placeholder="Enter Title" value="{{$pricing_plan->title}}">
                                <span class="ms-2">Title</span>
                            </div>
                            <div class="flex-grow-1 d-flex align-items-center m-2">
                                <input class="form-control form-control-sm" name="description" type="text" placeholder="Enter Description" value="{{$pricing_plan->description}}">
                                <span class="ms-2">Description</span>
                            </div>
                            <div class="ms-auto d-flex align-items-center m-2">
                                <h3 class="me-1">$</h3>
                                <input class="form-control form-control-sm me-1" step="any" value="{{ $pricing_plan->price }}" name="price" type="number" placeholder="Enter Price">
                                <div class="input-group">
                                    <input class="form-control form-control-sm" step="any" value="{{ $pricing_plan->discount }}" name="discount" type="number" placeholder="Enter Discount">
                                    <select class="form-select form-select-sm" name="discount_type">
                                        <option value="" {{ $pricing_plan->discount_type === '' ? 'selected' : '' }}>None</option>
                                        <option value="percentage" {{ $pricing_plan->discount_type === 'percentage' ? 'selected' : '' }}>%</option>
                                        <option value="flat" {{ $pricing_plan->discount_type === 'flat' ? 'selected' : '' }}>Flat</option>
                                        
                                    </select>
                                </div>
                                <input class="form-control form-control-sm" step="any" value="{{ $pricing_plan->discounted_price }}" name="discounted_price" type="number" placeholder="Discounted Price" readonly>
                            </div>
                    
                        <ul class="list-unstyled vstack gap-3">
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <input class="form-control form-control-sm" value="{{$pricing_plan->tokens}}" name="tokens" type="number" placeholder="Enter No.Tokens">
                                        <span class="ms-2">Tokens</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                                
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">71 AI Templates</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="71_ai_templates" type="checkbox" id="formCheck6" {{ $pricing_plan->{'71_ai_templates'} ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">AI Chat</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="ai_chat" type="checkbox" id="formCheck6" {{ $pricing_plan->ai_chat ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">AI Code</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="ai_code" type="checkbox" id="formCheck6" {{ $pricing_plan->ai_code ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">Text to Speech</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="text_to_speech" type="checkbox" id="formCheck6" {{ $pricing_plan->text_to_speech ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">Custom Templates</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="custom_templates" type="checkbox" id="formCheck6" {{ $pricing_plan->custom_templates ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">AI Blog Wizards</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="ai_blog_wizards" type="checkbox" id="formCheck6" {{ $pricing_plan->ai_blog_wizards ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <input class="form-control form-control-sm" type="number" placeholder="Enter No. Images" value="{{$pricing_plan->images}}" name="images">
                                        <span class="ms-2">Images</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                                
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">AI Images</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="ai_images" type="checkbox" id="formCheck6" {{ $pricing_plan->ai_images ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">Stable Diffusion</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="stable_diffusion" type="checkbox" id="formCheck6" {{ $pricing_plan->stable_diffusion ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <input class="form-control form-control-sm" type="number" name="speech_to_text" value="{{$pricing_plan->speech_to_text}}" placeholder="Enter No. Speech to Text">
                                        <span class="ms-2">Speech to Text</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">Live Support</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="live_support" type="checkbox" id="formCheck6" {{ $pricing_plan->live_support ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">Free Support</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="free_support" type="checkbox" id="formCheck6" {{ $pricing_plan->free_support ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <input class="form-control form-control-sm" type="text" placeholder="Enter Additional Features" value="{{$pricing_plan->additional_features}}" name="additional_features">
                                        <span class="ms-2">Additional Features</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                                
                            </li>
                            <li>
                                @php
                                    $selectedModels = explode(', ', $pricing_plan->open_id_model); // Convert the string to an array
                                @endphp

                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <select data-choices data-choices-removeItem multiple id="style" class="form-select form-select-sm mb-3" aria-label=".form-select-sm example" name="open_id_model[]">
                                            {{-- <option selected>Open AI Model</option> --}}
                                            <option value="o1" {{ in_array('o1', $selectedModels) ? 'selected' : '' }}>o1</option>
                                            <option value="o1-mini" {{ in_array('o1-mini', $selectedModels) ? 'selected' : '' }}>o1-mini</option>
                                            <option value="o3-mini" {{ in_array('o3-mini', $selectedModels) ? 'selected' : '' }}>o3-mini</option>
                                            <option value="gpt-4o" {{ in_array('gpt-4o', $selectedModels) ? 'selected' : '' }}>gpt-4o</option>
                                            <option value="gpt-4o-mini" {{ in_array('gpt-4o-mini', $selectedModels) ? 'selected' : '' }}>gpt-4o-mini</option>
                                            <option value="gpt-4-turbo" {{ in_array('gpt-4-turbo', $selectedModels) ? 'selected' : '' }}>gpt-4-turbo</option>
                                            <option value="gpt-4" {{ in_array('gpt-4', $selectedModels) ? 'selected' : '' }}>gpt-4</option>
                                            <option value="gpt-3.5-turbo" {{ in_array('gpt-3.5-turbo', $selectedModels) ? 'selected' : '' }}>gpt-3.5-turbo</option>
                                            <option value="gpt-3.5-turbo-instruct" {{ in_array('gpt-3.5-turbo-instruct', $selectedModels) ? 'selected' : '' }}>gpt-3.5-turbo-instruct</option>
                                        </select>
                                        <span class="ms-2">Open Ai Model</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                                
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <select class="form-select form-select-sm  mb-3" aria-label=".form-select-sm example" name="package_type">
                                            <option selected>{{$pricing_plan->package_type}}</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
                                        <span class="ms-2">Plan Type</span>
                                    </div>
                                </div>
                                
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">Popular Ribbon</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" value="yes" name="popular" type="checkbox" id="formCheck6" {{ $pricing_plan->popular === 'yes' ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="badge bg-success-subtle text-primary badge-border">Active</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="active" value="active" type="checkbox" role="switch" id="SwitchCheck1" {{ $pricing_plan->active === 'active' ? 'checked' : '' }}>
                                            
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <div class="mt-3 pt-2">
                                <button type="submit" class="btn btn-primary w-100">Update Plan</button>
                            </div>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
        <!--end col-->

    </div>
    <!--end row-->

@endsection
@section('script')
    <script src="{{ URL::asset('build/js/pages/pricing.init.js') }}"></script>
    <script src="{{ URL::asset('build/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>  
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Function to calculate discounted price based on input changes
            function calculateDiscountedPrice() {
                var price = parseFloat($('input[name="price"]').val());
                var discount = parseFloat($('input[name="discount"]').val());
                var discountType = $('select[name="discount_type"]').val();

                if (discountType === 'percentage') {
                    var discountedPrice = price - (price * (discount / 100));
                } else if (discountType === 'flat') {
                    var discountedPrice = price - discount;
                }

                $('input[name="discounted_price"]').val(discountedPrice.toFixed(2));
            }

            // Trigger calculation on change or keyup
            $('input[name="discount"], select[name="discount_type"]').on('change keyup', function() {
                calculateDiscountedPrice();
            });

            // Initial calculation on page load
            calculateDiscountedPrice();
        });
    </script>
@endsection
