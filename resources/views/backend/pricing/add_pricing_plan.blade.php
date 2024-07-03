@extends('admin.layouts.master')
@section('title')
    @lang('translation.pricing')
@endsection
@section('content')
    @component('admin.components.breadcrumb')
        @slot('li_1')
            Add
        @endslot
        @slot('title')
            Pricing
        @endslot
    @endcomponent
 
    <div class="row">
        <div class="col-xxl-4 col-lg-6">
            <form action="{{ route('store.pricing.plan') }}" method="POST">
                    @csrf
                <div class="card pricing-box">
                    <div class="card-body bg-light m-2 p-4">
                    
                            <div class="flex-grow-1 d-flex align-items-center m-2">
                                <input class="form-control form-control-sm" type="text" name="title" placeholder="Enter Title">
                                <span class="ms-2">Title</span>
                            </div>
                            <div class="flex-grow-1 d-flex align-items-center m-2">
                                <input class="form-control form-control-sm" name="description" type="text" placeholder="Enter Description">
                                <span class="ms-2">Description</span>
                            </div>
                            <div class="ms-auto d-flex align-items-center m-2">
                                <h3 class="me-1">$</h3>
                                <input class="form-control form-control-sm me-1" step="any" name="price" type="number" placeholder="Enter Price">
                                
                                <div class="input-group">
                                    <input class="form-control form-control-sm" step="any" name="discount" type="number" placeholder="Enter Discount">
                                    <select class="form-select form-select-sm" name="discount_type">
                                        <option value="percentage">%</option>
                                        <option value="flat">Flat</option>
                                    </select>
                                </div>
                                
                                <input class="form-control form-control-sm" step="any" name="discounted_price" type="number" placeholder="Discounted Price" readonly>
                            </div>
                            
                    
                        <ul class="list-unstyled vstack gap-3">
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <input class="form-control form-control-sm" name="tokens" type="number" placeholder="Enter No.Tokens">
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
                                        <span class="ms-2">{{$totalTemplates}} AI Templates</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="71_ai_templates" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">AI Chat</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="ai_chat" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">AI Code</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="ai_code" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">Text to Speech</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="text_to_speech" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">Custom Templates</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="custom_templates" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">AI Blog Wizards</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="ai_blog_wizards" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <input class="form-control form-control-sm" type="number" placeholder="Enter No. Images" name="images">
                                        <span class="ms-2">Credits</span>
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
                                        <input class="form-check-input" name="ai_images" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">Stable Diffusion</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="stable_diffusion" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <input class="form-control form-control-sm" type="number" name="speech_to_text" placeholder="Enter No. Speech to Text">
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
                                        <input class="form-check-input" name="live_support" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <span class="ms-2">Free Support</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" name="free_support" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <input class="form-control form-control-sm" type="text" placeholder="Enter Additional Features" name="additional_features">
                                        <span class="ms-2">Additional Features</span>
                                    </div>
                                    <div class="form-check ms-3 align-self-center">
                                        <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                    </div>
                                </div>
                                
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <select class="form-select form-select-sm  mb-3" aria-label=".form-select-sm example" name="open_id_model">
                                            <option selected>Open Open AI Model</option>
                                            <option value="gpt-4o">gpt-4o</option>
                                            <option value="gpt-4-turbo">gpt-4-turbo</option>
                                            <option value="gpt-4">gpt-4</option>
                                            <option value="gpt-3.5-turbo">gpt-3.5-turbo</option>
                                            <option value="gpt-3.5-turbo-instruct">gpt-3.5-turbo-instruct</option>
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
                                            <option selected>Select Plan Type</option>
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
                                        <input class="form-check-input" value="yes" name="popular" type="checkbox" id="formCheck6" checked>
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
                                            <input class="form-check-input" name="active" value="active" type="checkbox" role="switch" id="SwitchCheck1" checked>
                                            
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <div class="mt-3 pt-2">
                                <button type="submit" class="btn btn-primary w-100">Add Plan</button>
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

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('input[name="discount"], select[name="discount_type"]').on('change keyup', function() {
                var price = parseFloat($('input[name="price"]').val());
                var discount = parseFloat($('input[name="discount"]').val());
                var discountType = $('select[name="discount_type"]').val();
    
                if (discountType === 'percentage') {
                    var discountedPrice = price - (price * (discount / 100));
                } else if (discountType === 'flat') {
                    var discountedPrice = price - discount;
                }
    
                $('input[name="discounted_price"]').val(discountedPrice.toFixed(2));
            });
        });
    </script>
    
@endsection
