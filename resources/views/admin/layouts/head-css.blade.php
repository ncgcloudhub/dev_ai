@yield('css')
<!-- Layout config Js -->
<script src="{{ URL::asset('build/js/layout.js') }}"></script>
<!-- Bootstrap Css -->
<link href="{{ URL::asset('build/css/bootstrap.min.css') }}?v={{ filemtime(public_path('build/css/bootstrap.min.css')) }}" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('build/css/app.min.css') }}?v={{ filemtime(public_path('build/css/app.min.css')) }}"  rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{ URL::asset('build/css/custom.min.css') }}"  rel="stylesheet" type="text/css" />

<link href="{{ URL::asset('build/css/clevercreator.css') }}?v={{ filemtime(public_path('build/css/clevercreator.css')) }}" rel="stylesheet" type="text/css" />


<link href="{{ URL::asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">

@php
    $cssRules = '';
    foreach ($buttons as $button) {
        $classes = json_decode($button->classes, true);
        $bgColor = '';
        $gradientColor = '';

        // Extracting the colors from the background property
        if (isset($classes['background']) && preg_match_all('/#([a-f0-9]{6}|[a-f0-9]{3})/i', $classes['background'], $matches)) {
            if (isset($matches[0][0])) {
                $bgColor = $matches[0][0];
            }
            if (isset($matches[0][1])) {
                $gradientColor = $matches[0][1];
            }
        }

        $cssRules .= ".gradient-btn-{$button->button_type} { ";
        foreach ($classes as $property => $value) {
            $cssRules .= "{$property}: {$value}; ";
        }
        $cssRules .= "} ";

        // Adding hover effect by swapping background colors
        if ($bgColor && $gradientColor) {
            $cssRules .= ".gradient-btn-{$button->button_type}:hover { ";
            $cssRules .= "background: linear-gradient(45deg, {$gradientColor}, {$bgColor}); ";
            $cssRules .= "transform: scale(1.05); ";
            $cssRules .= "color: white; ";
            $cssRules .= "box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3); ";
            $cssRules .= "} ";
        }
    }
@endphp

<style>
    {!! $cssRules !!}
</style>
