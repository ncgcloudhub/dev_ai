@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
<style>
    .copy-icon {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #007bff;
    }
    </style>
@endsection
@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') Dashboards @endslot
@slot('title') Dashboard @endslot
@endcomponent

<div class="row">
   
           <div class="col-xxl-6">

            <div class="card">
                <div class="card-body"> 
                    <div class="live-preview">
                            <div class="col-md-12">
                                <label for="language" class="form-label">Prompt Name</label>
                                <p class="fw-medium link-primary">{{$prompt_library->prompt_name}}</p>
                            </div>
                    </div>
                </div> 
            </div>
            <div class="card">
                <div class="card-body"> 
                    <div class="live-preview">
                            <div class="col-md-12">
                                <label for="language" class="form-label">System Instruction</label>
                                <p class="fw-medium link-primary">
                                    @if (is_null($prompt_library->sub_category_id))
                                        --
                                    @else
                                    {{$prompt_library->subcategory->sub_category_instruction}}</p>
                                    @endif
                                 
                            </div>
                    </div>
                </div> 
            </div>
            <div class="card">
                <div class="card-body"> 
                    <div class="live-preview">
                            <div class="col-md-12">
                                <label for="language" class="form-label">Category / Sub-Category Name</label>
                                <p class="fw-medium link-primary">{{$prompt_library->category->category_name}} /@if (is_null($prompt_library->sub_category_id))
                                    --</p>
                                @else
                                {{$prompt_library->subcategory->sub_category_name}}</p>
                                @endif
                            </div>
                    </div>
                </div> 
            </div>

            <div class="card">
                <div class="card-body"> 
                    <div class="live-preview">
                            <div class="col-md-12">
                                <label for="language" class="form-label">Prompt Description</label>
                                <p class="fw-medium link-primary">{{$prompt_library->description}}</p>
                            </div>
                    </div>
                </div> 
            </div>

            <div class="card">
                <div class="card-body"> 
                    <div class="live-preview">
                            <div class="col-md-12">
                                <label for="language" class="form-label">Actual Prompt</label>
                                <p class="fw-medium link-primary" style="position: relative;">
                                    {{$prompt_library->actual_prompt}}
                                    <span class="copy-icon" onclick="copyText(this)">📋</span>
                                </p>

                            </div>
                    </div>
                </div> 
            </div>

            <!-- Display Existing Examples -->
<div class="card mt-3">
    <div class="card-body">
        <div class="live-preview">
            <div class="col-md-12">
                <label for="existing-examples" class="form-label">Existing Examples</label>
                <div id="existing-examples-container">
                    @foreach($prompt_library_examples as $item)
                        <div class="form-group mt-3">
                            <textarea class="form-control" rows="3" readonly>{{ $item->example }}</textarea>
                            <p class="text-muted">Status: {{ $item->active ? 'Active' : 'Inactive' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

            <!-- Container for adding examples -->
<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('prompt_examples.store', $prompt_library->id) }}" method="POST">
            @csrf
            <div class="live-preview">
                <div class="col-md-12">
                    <label for="examples" class="form-label">Examples</label>
                    <div id="examples-container">
                        <!-- Example editor boxes will be added here -->
                    </div>
                    <button type="button" class="btn btn-primary mt-3" onclick="addExampleEditor()">Add Example</button>
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-3">Save Examples</button>
        </form>
    </div>
</div>


           </div>
{{-- 2nd column --}}
<div class="col-xxl-6">

    <div class="card">
        <div class="card-body"> 
            <div class="live-preview">
                <div class="row">
                    <label for="language" class="form-label">Ask AI</label>
                    <div class="col-md-9 mb-3"> 
                        <textarea class="form-control chat-input bg-light border-light auto-expand" id="ask_ai" rows="1" placeholder="Type your message..." autocomplete="off">{{$prompt_library->actual_prompt}}</textarea>
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="ask" class="btn btn-primary"><span class="d-none d-sm-inline-block me-2">Ask</span> <i class="mdi mdi-send float-end"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9"> 
                        <textarea class="form-control chat-input bg-light border-light auto-expand" id="result1"  rows="3" placeholder="Your Result Here" autocomplete="off"></textarea>
                      
                    </div>
                </div> 
            </div>
           
        </div> 
    </div>
</div>


</div>
@endsection
@section('script')

<script>
    function copyText(element) {
        const textToCopy = element.parentElement.innerText.replace('📋', '').trim();
        const tempInput = document.createElement("textarea");
        tempInput.style = "position: absolute; left: -9999px";
        tempInput.value = textToCopy;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        alert("Text copied to clipboard");
    }
    </script>

<script>
    function addExampleEditor() {
        const container = document.getElementById('examples-container');
        const exampleEditor = document.createElement('div');
        exampleEditor.className = 'form-group mt-3';
        exampleEditor.innerHTML = `
            <textarea class="form-control" name="examples[]" rows="3" placeholder="Enter example text"></textarea>
            <button type="button" class="btn btn-danger mt-2" onclick="removeExampleEditor(this)">Remove</button>
        `;
        container.appendChild(exampleEditor);
    }

    function removeExampleEditor(button) {
        button.parentElement.remove();
    }

</script>

{{-- ASK AI --}}
<script>
    $(document).ready(function() {
        $('#ask').click(function() {
            var message = $('#ask_ai').val();
            $.ajax({
                url: "{{ route('ask.ai.prompt') }}",
                type: "POST",
                data: {
                    message: message,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#result1').html(response.message);
                },
                error: function(xhr) {
                    // Handle error
                }
            });
        });
    });
</script>


<script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
<script src="{{ URL::asset('build/libs/quill/quill.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

{{-- Submit Form Editor --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
