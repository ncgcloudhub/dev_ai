<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ $siteSettings->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <meta name="description" content="@yield('description', $seo->description)" />
    <meta name="keywords" content="@yield('keywords', 'Default keywords if not provided in specific page')" />

    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Canonical URL -->
    {{-- <link rel="canonical" href="{{ $seo->canonical_url }}">
        
    <!-- Sitemap URL -->
    <link rel="sitemap" type="application/xml" href="{{ $seo->sitemap_url }}" /> --}}

    <meta content="Clever_Creator" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}">

    <!-- Open Graph meta tags for sharing -->
    <meta property="og:title" content="Clever Creator" />
    <meta property="og:description" content="Clever Creator" />
    <meta property="og:image" content="{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}" />
    

    @include('admin.layouts.head-css')
  </head>

    @yield('body')

    @yield('content')

    @include('admin.layouts.vendor-scripts')
    </body>
</html>
