@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('aicontentcreator.manage')}}">Education</a> @endslot
@slot('title') Manage Tools @endslot
@endcomponent


    <!-- start wallet -->
    <section class="section" id="wallet">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h2 class="mb-3 fw-bold lh-base">Contents</h2>
                        <p class="text-muted">A non-fungible token is a non-interchangeable unit of data stored on a blockchain, a form of digital ledger, that can be sold and traded.</p>
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->

            <div class="row g-4">
                @foreach ($tools as $item)
               
                     
                @endforeach
               
            </div><!-- end row -->
        </div><!-- end container -->
    </section><!-- end wallet -->

  
@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/nft-landing.init.js') }}"></script>
@endsection