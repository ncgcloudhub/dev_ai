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
                                <input class="form-control form-control-sm me-1" name="price" type="number" placeholder="Enter Price">
                                <input class="form-control form-control-sm" name="discounted_price" type="number" placeholder="Enter Discounted Price">
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
                                        <span class="ms-2">71 AI Templates</span>
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
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
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
                                <button type="submit" class="btn btn-primary w-100">Change Plan</button>
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
@endsection
