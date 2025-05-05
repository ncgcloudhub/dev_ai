<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="gradient" data-sidebar-size="@yield('sidebar-size', 'lg')" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ $siteSettings->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', $seo->description)" />
    <meta name="keywords" content="@yield('keywords', 'Default keywords if not provided in specific page')" />
    <meta content="Clever_Creator" name="author" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('backend/uploads/site/' . $siteSettings->favicon) }}">



{{-- Tour --}}
<link href="{{ URL::asset('build/libs/shepherd.js/css/shepherd.css')}}" rel="stylesheet" type="text/css" />

    @include('admin.layouts.head-css')

    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

@section('body')
<style>
    .gradient-button {
    background: linear-gradient(to right, rgb(10, 179, 156), rgb(64, 81, 137))
}

</style>

    {{-- Magic Ball --}}
    <x-jokes_common />
   
    @include('admin.layouts.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @if (!isset($hideTopbar) || !$hideTopbar)
        @if (Auth::user()->role === 'admin')
        @include('admin.layouts.topbar')
        @else
        @include('user.layouts.topbar') 
        @endif
        @endif
       
       
        @if (Auth::user()->role === 'admin')
        @include('admin.layouts.sidebar')
        @else
        @include('user.layouts.sidebar') 
        @endif
        
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content @if(isset($hideTopbar) && $hideTopbar) no-topbar @endif"">
            <div class="page-content">
                                 
            @include('admin.layouts.alerts')
            
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('admin.layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
<!-- Generate Image Modal -->
<div id="loginModals" class="modal fade" tabindex="-1" aria-hidden="true" style="display:none">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            
            <!-- Stable Diffusion Card with Background Image -->
            <a href="{{ route('generate.image.view') }}" class="modal-body login-modal p-5" style="background-image: url('https://cdn.analyticsvidhya.com/wp-content/uploads/2024/07/Dalle-3--scaled.jpg'); background-size: cover; background-position: center; height: 220px;">
                
            </a>

            <!-- DALL-E Card with Background Image -->
            <a href="{{ route('stable.form') }}" class="modal-body p-5" style="background-image: url('https://cdn.arstechnica.net/wp-content/uploads/2024/02/sd3.jpg'); background-size: cover; background-position: center; height: 220px;">

            </a>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

    @include('admin.layouts.customizer')


    
    {{-- Modal --}}

    @php
    // Check if $prompt_library is defined or passed
    if (!isset($prompt_library)) {
        // Fallback: Retrieve a default set of prompt_library records
        $prompt_library = \App\Models\PromptLibrary::orderby('id', 'asc')->limit(50)->get();
    } elseif ($prompt_library instanceof \Illuminate\Database\Eloquent\Model) {
        // If it's a single record, wrap it in a collection
        $prompt_library = collect([$prompt_library]);
    }
    @endphp

    <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Prompt Library</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="fs-15">Generate your images in the best format</h6>
                    @foreach ($prompt_library as $item)
                    <div class="card card-height-100" onclick="populatePromptField('{{ addslashes($item->actual_prompt) }}')">
                        <div class="d-flex">
                            <div class="flex-grow-1 p-2">
                                
                                <a href="javascript:void(0);" 
                                onclick="populatePromptField('{{$item->actual_prompt}}'); 
                               ">
                                 <h5 class="mb-3">{{$item->prompt_name}}</h5>
                             </a>
                                
                                <p class="mb-0 text-muted" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <span class="badge bg-light text-success mb-0">{{ substr($item->actual_prompt, 0, 65) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- <div class="d-flex mt-2">
                        <div class="flex-shrink-0">
                            <i class="ri-checkbox-circle-fill text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <a href="{{ route('prompt.view', ['slug' => $item->slug]) }}">
                                <p class="text-muted mb-0">{{$item->prompt_name}}</p>
                            </a>
                        </div>
                    </div>     --}}
                    @endforeach

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <a class="btn btn-primary" href="{{route('prompt.manage')}}">Get More Prompts</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- JAVASCRIPT -->
    @include('admin.layouts.vendor-scripts')

        {{-- Tour --}}
        <script src="{{ URL::asset('build/libs/shepherd.js/js/shepherd.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/tour_custom.init.js') }}"></script>
        {{-- <script src="{{ URL::asset('build/js/app.js') }}"></script> --}}

        @stack('scripts')
        <script>
            function populatePromptField(actualPrompt) {
            // Get all textareas with the id 'prompt' (even though using the same id multiple times is not recommended)
            const promptTextareas = document.querySelectorAll('#prompt');
        
            promptTextareas.forEach(function(promptTextarea) {
                if (promptTextarea) {
                    promptTextarea.value = actualPrompt; // Set the value directly to the textarea
                    promptTextarea.focus(); // Optional: Focus on the textarea for user feedback
                    console.log('Textarea populated with prompt');
                } else {
                    console.error('Textarea with id="prompt" not found.');
                }
            });
            $('#exampleModalScrollable').modal('hide');
        }
        
        // Populate the prompt field with the Examples
        document.getElementById('populateInputsButton').addEventListener('click', function() {
    const inputs = document.querySelectorAll('input[type="text"], textarea');
    inputs.forEach(input => {
        input.value = input.placeholder;  // Populate input fields with placeholder values
    });
    });
        </script>

        


</body>

</html>
