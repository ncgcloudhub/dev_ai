@extends('user.layouts.master')
@section('title')
@lang('translation.details')
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1')
invoices
@endslot
@slot('title')
Invoice Details
@endslot
@endcomponent

<div class="row justify-content-center">
    <div class="col-xxl-9">
        <div class="card" id="demo">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-header border-bottom-dashed p-4">
                        <form method="post" action="{{ route('store.subscription.plan') }}">
                            @csrf
                        <input type="hidden" name="pricing_plan_id" value="{{ $pricingPlan->id }}">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <img src="{{ URL::asset('build/images/logo-dark.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="17">
                                <img src="{{ URL::asset('build/images/logo-light.png') }}" class="card-logo card-logo-light" alt="logo light" height="17">
                                <div class="mt-sm-5 mt-4">
                                    <h6 class="text-muted text-uppercase fw-semibold">Address</h6>
                                    <p class="text-muted mb-1" id="address-details">California, United States</p>
                                    <p class="text-muted mb-0" id="zip-code"><span>Zip-code:</span> 90201</p>
                                </div>
                            </div>
                            <div class="flex-shrink-0 mt-sm-0 mt-3">
                                <h6><span class="text-muted fw-normal">Legal Registration No:</span> <span id="legal-register-no">987654</span></h6>
                                <h6><span class="text-muted fw-normal">Email:</span><span id="email">support@clevercreator.com</span></h6>
                                <h6><span class="text-muted fw-normal">Website:</span> <a href="https://themesbrand.com/" target="_blank" class="link-primary" id="website">www.clevercreator.com</a></h6>
                                <h6 class="mb-0"><span class="text-muted fw-normal">Contact No:</span> <span id="contact-no"> +(01) 234 6789</span></h6>
                            </div>
                        </div>

                    </div>
                    <!--end card-header-->
                </div>
                <!--end col-->
                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-3 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Invoice No</p>
                                <h5 class="fs-14 mb-0">#CC<span id="invoice-no">
                                    {{ rand(10000000, 99999999) }}</span></h5>
                            </div>
                            <!--end col-->
                            <div class="col-lg-3 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                <h5 class="fs-14 mb-0"><span id="invoice-date">{{ date('j M, Y') }}</span> <small class="text-muted" id="invoice-time">{{ date('H:i') }}</small></h5>
                            </div>
                            <!--end col-->
                            <div class="col-lg-3 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Payment Status</p>
                                <span class="badge bg-danger-subtle text-danger fs-11" id="payment-status">Unpaid</span>
                            </div>
                            <!--end col-->
                            <div class="col-lg-3 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Total Amount</p>
                                <h5 class="fs-14 mb-0">$<span id="total-amount">@if ($pricingPlan->discounted_price)
                                    {{ $pricingPlan->discounted_price }}
                                @else
                                    {{ $pricingPlan->price }}
                                @endif</td></span></h5>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end col-->
                <div class="col-lg-12">
                    <div class="card-body p-4 border-top border-top-dashed">
                        <div class="row g-3">
                            <div class="col-6">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3">Billing</h6>
                                <p class="fw-medium mb-2" id="shipping-name">{{$user->name}}</p>
                                <p class="text-muted mb-1" id="shipping-address-line-1">{{$user->address}}</p>
                                <p class="text-muted mb-1"><span>Phone: </span><span id="shipping-phone-no">{{$user->phone}}</span></p>
                                <p class="text-muted mb-1"><span>Email: </span><span id="shipping-phone-no">{{$user->email}}</span></p>
                            </div>
                            <!--end col-->
                            <div class="col-6">
                               
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end col-->
                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                <thead>
                                    <tr class="table-active">
                                        <th scope="col" style="width: 50px;">#</th>
                                        <th scope="col">Package Details</th>
                                        <th scope="col">Rate</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col" class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="products-list">
                                    <tr>
                                        <th scope="row">01</th>
                                        <td class="text-start">
                                            <span class="fw-medium">{{$pricingPlan->title}}</span>
                                            <p class="text-muted mb-0">{{$pricingPlan->description}}</p>
                                        </td>
                                        <td>
                                            ${{ $pricingPlan->price }}
                                        </td>
                                        
                                        <td>01</td>
                                        <td class="text-end"> 
                                        @if ($pricingPlan->discounted_price)
                                            ${{ $pricingPlan->discounted_price }}
                                        @else
                                            ${{ $pricingPlan->price }}
                                        @endif</td>
                                    </tr>
                                </tbody>
                            </table>
                           
                        </div>
                        <div class="border-top border-top-dashed mt-2">
                            <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                <tbody>
                                    <tr>
                                        <td>Sub Total</td>
                                        <td class="text-end">                                     
                                            ${{ $pricingPlan->price }}
                                       </td>
                                    </tr>
                                   
                                    <tr>
                                        <?php
                                                 $discountedPrice = $pricingPlan->price - $pricingPlan->discounted_price;
                                            ?>
                                       <td>Discount <small class="text-muted">({{ round(($pricingPlan->discounted_price / $pricingPlan->price) * 100) }}%)</small></td>

                                        <td class="text-end">
                                            ${{ $discountedPrice }}</td>
                                        </tr>
                                  
                                    <tr class="border-top border-top-dashed fs-15">
                                        <th scope="row">Total Amount</th>
                                        <th class="text-end">
                                        @if ($pricingPlan->discounted_price)
                                            ${{ $pricingPlan->discounted_price }}
                                        @else
                                            ${{ $pricingPlan->price }}
                                        @endif</th>
                                    </tr>
                                </tbody>
                            </table>
                            <!--end table-->

                        </div>
                     
                        <div class="mt-4">
                            <div class="alert alert-primary">
                                <p class="mb-0"><span class="fw-semibold">NOTES:</span>
                                    <span id="note">All accounts are to be paid within 7 days from receipt of invoice. To be
                                        paid by cheque or
                                        credit card or direct payment online. If account is not paid within 7
                                        days the credits details supplied as confirmation of work undertaken
                                        will be charged the agreed quoted fee noted above.
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                            <button type="submit" class="btn btn-success">Continue <i class=" ri-arrow-right-fill align-bottom me-1"></i></button>
                            {{-- <a href="javascript:window.print()" class="btn btn-soft-primary"><i class="ri-printer-line align-bottom me-1"></i> Print</a>
                            <a href="javascript:void(0);" class="btn btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download</a> --}}
                        </div>

                    </form>
                    </div>
                    <!--end card-body-->
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->
</div>
<!--end row-->
@endsection
@section('script')
<script src="{{ URL::asset('build/js/pages/invoicedetails.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
