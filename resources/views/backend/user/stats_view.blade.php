@extends('admin.layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') User @endslot
@slot('title')Stats @endslot
@endcomponent


<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">User Chart</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="gradient_chart"
                data-total-users="{{ $totalUsers }}"
                data-verified-emails="{{ $verifiedEmails }}"
                data-unverified-emails="{{ $unverifiedEmails }}"
                data-active-users="{{ $activeUsers }}"
                data-inactive-users="{{ $inactiveUsers }}"
                data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-secondary", "--vz-info"]'
                class="apex-charts" dir="ltr"></div>          
            </div>
        </div>
    </div>


    <div class="col">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">User Country</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="country_chart"
                    data-countries="{{ json_encode($usersByCountry->keys()) }}"
                    data-country-counts="{{ json_encode($usersByCountry->values()) }}"
                    data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-secondary", "--vz-info"]'
                    class="apex-charts" dir="ltr"></div>
            </div>
        </div><!-- end card-body -->
    </div>

    {{-- TOkens/Credits --}}
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">User Tokens/Credits</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="usage_chart"
                data-credits-used="{{ $totalCreditsUsed }}"
                data-tokens-used="{{ $totalTokensUsed }}"
                data-images-generated="{{ $totalImagesGenerated }}"
                data-colors='["--vz-primary", "--vz-success", "--vz-warning"]'
                class="apex-charts" dir="ltr"></div>
            
            </div>
        </div><!-- end card-body -->
    </div>
</div>


@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/clever-creator-pie.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
