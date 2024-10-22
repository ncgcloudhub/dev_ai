@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('template.manage')}}">Education</a> @endslot
@slot('title') Manage Tools @endslot
@endcomponent



        <div class="row">
            <!-- Dynamic Product Items -->
            @foreach($educationContents as $item)
            <div class="card">
                <p>{{$item->topic}}</p>
            </div>
              
            @endforeach
        </div>


@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/nft-landing.init.js') }}"></script>
@endsection