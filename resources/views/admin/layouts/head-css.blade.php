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
