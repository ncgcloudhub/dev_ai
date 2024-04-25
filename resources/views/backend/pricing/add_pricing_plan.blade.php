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
            <div class="card pricing-box">
                <div class="card-body bg-light m-2 p-4">
                
                        <div class="flex-grow-1 d-flex align-items-center m-2">
                            <input class="form-control form-control-sm" type="text" placeholder="Enter Title">
                            <span class="ms-2">Title</span>
                        </div>
                        <div class="flex-grow-1 d-flex align-items-center m-2">
                            <input class="form-control form-control-sm" type="text" placeholder="Enter Description">
                            <span class="ms-2">Description</span>
                        </div>
                        <div class="ms-auto d-flex align-items-center m-2">
                            <h3 class="me-1">$</h3>
                            <input class="form-control form-control-sm me-1" type="text" placeholder="Enter Price">
                            <input class="form-control form-control-sm" type="text" placeholder="Enter Discounted Price">
                        </div>
                
                    <ul class="list-unstyled vstack gap-3">
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <input class="form-control form-control-sm" type="text" placeholder="Enter No.Tokens">
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
                                    <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <span class="ms-2">AI Chat</span>
                                </div>
                                <div class="form-check ms-3 align-self-center">
                                    <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <span class="ms-2">AI Code</span>
                                </div>
                                <div class="form-check ms-3 align-self-center">
                                    <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <span class="ms-2">Text to Speech</span>
                                </div>
                                <div class="form-check ms-3 align-self-center">
                                    <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <span class="ms-2">Custom Templates</span>
                                </div>
                                <div class="form-check ms-3 align-self-center">
                                    <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <span class="ms-2">AI Blog Wizards</span>
                                </div>
                                <div class="form-check ms-3 align-self-center">
                                    <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <input class="form-control form-control-sm" type="text" placeholder="Enter No. Images">
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
                                    <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <span class="ms-2">Stable Diffusion</span>
                                </div>
                                <div class="form-check ms-3 align-self-center">
                                    <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <input class="form-control form-control-sm" type="text" placeholder="Enter No. Speech to Text">
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
                                    <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <span class="ms-2">Free Support</span>
                                </div>
                                <div class="form-check ms-3 align-self-center">
                                    <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <input class="form-control form-control-sm" type="text" placeholder="Enter Additional Features">
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
                                    <select class="form-select form-select-sm  mb-3" aria-label=".form-select-sm example">
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
                                    <input class="form-check-input" type="checkbox" id="formCheck6" checked>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <span class="badge bg-primary-subtle text-primary badge-border">Active</span>
                                </div>
                                <div class="form-check ms-3 align-self-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck1" checked>
                                        <label class="form-check-label" for="SwitchCheck1">Switch Default</label>
                                    </div>
                                </div>
                            </div>
                        </li>
                    
                    </ul>
                </div>
            </div>
        </div>
        <!--end col-->

    </div>
    <!--end row-->

@endsection
@section('script')
    <script src="{{ URL::asset('build/js/pages/pricing.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
