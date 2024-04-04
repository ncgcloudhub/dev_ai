@extends('admin.layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('css')
<link href="{{ URL::asset('build/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />

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
                        <form id="generateForm"  action="{{route ('custom.template.generate')}}" method="post" class="row g-3">
                            @csrf
                            {{-- <input type="hidden" name="template_code" value="{{ $template->slug }}"> --}}
                            <div class="col-md-12">
                                <label for="language" class="form-label">Select Language</label>
                                <select class="form-select" name="language" id="language" aria-label="Floating label select example">
                                    <option disabled selected="">Enter Language</option>
                                    <option value="English">English</option>
                                    <option value="Bengali">Bengali</option>
                                    
                                  </select>
                             
                            </div>

                            @isset($inputTypes)
                            @foreach($inputTypes as $key => $type)
                                <div class="col-md-12">
                                     <label for="{{ $inputNames[$key] }}" class="form-label">{{ $inputLabels[$key] }}</label>
                                @if($type == 'text')
                                    <input type="text" name="{{ $inputNames[$key] }}" class="form-control" id="{{ $inputNames[$key] }}" placeholder="Enter {{ $inputLabels[$key] }}">
                                @elseif($type == 'textarea')
                                    <textarea class="form-control" name="{{ $inputNames[$key] }}" id="{{ $inputNames[$key] }}" rows="3"></textarea>
                                @endif
                                </div>

                                <div hidden class="col-md-12" data-prompt="{{ $customTemplate->prompt }}">
                                    <textarea class="form-control" name="prompt" id="VertimeassageInput" rows="3" placeholder="Enter your message">{{$customTemplate->prompt}}</textarea>
                                </div>
                            @endforeach
                            @endisset                                                
                           

                            <!-- Accordion Flush Example -->
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                            Advance Settings
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                                        data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">

            
                <div class="col-md-12">
                    <label for="max_result_length" class="form-label">Max Result Length</label>
                    <input type="range" name="max_result_length" class="form-range" id="max_result_length" min="10" max="4000" step="10" value="100">
                    <input type="number" name="max_result_length_value" class="form-control" id="max_result_length_value" min="10" max="4000" step="10" value="100">
                    
                </div>
                

                <div class="row">
                    <div class="col-md-6">
                        <label for="creative_level" class="form-label">Creative Level</label>
                        <select class="form-select" name="creative_level" id="creative_level" aria-label="Floating label select example" onchange="disableInputs()">
                            <option disabled selected="">No Creativity Level</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="tone" class="form-label">Choose a Tone</label>
                        <select class="form-select" name="tone" id="tone" aria-label="Floating label select example">
                            <option disabled selected="">Enter Tone</option>
                            <option value="Friendly">Friendly</option>
                            <option value="Luxury">Luxury</option>
                            <option value="Relaxed">Relaxed</option>
                            <option value="Professional">Professional</option>
                            <option value="Casual">Casual</option>
                            <option value="Excited">Excited</option>
                            <option value="Bold">Bold</option>
                            <option value="Masculine">Masculine</option>
                            <option value="Dramatic">Dramatic</option>
                    
                          </select>
                    
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <label for="temperature" class="form-label">Temperature (Creativity)</label>
                        <input type="range" name="temperature" class="form-range" id="temperature" min="0" max="1" step="0.01" value="0.00" >
                        <input type="number" name="temperature_value" class="form-control" id="temperature_value" min="0" max="1" step="0.01" value="0.00">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="top_p" class="form-label">Top P</label>
                        <input type="range" name="top_p" class="form-range" id="top_p" min="0" max="1" step="0.01" value="1.00" >
                        <input type="number" name="top_p_value" class="form-control" id="top_p_value" min="0" max="1" step="0.01" value="1.00">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="frequency_penalty" class="form-label">Frequency Penalty</label>
                        <input type="range" name="frequency_penalty" class="form-range" id="frequency_penalty" min="0" max="2" step="0.01" value="0.00">
                        <input type="number" name="frequency_penalty_value" class="form-control" id="frequency_penalty_value" min="0" max="2" step="0.01" value="0.00">

                    </div>

                    <div class="col-md-6">
                        <label for="presence_penalty" class="form-label">Presence Penalty</label>
                        <input type="range" name="presence_penalty" class="form-range" id="presence_penalty" min="0" max="2" step="0.01" value="0.00">
                        <input type="number" name="presence_penalty_value" class="form-control" id="presence_penalty_value" min="0" max="2" step="0.01" value="0.00">



                    </div>
                </div>


            </div>
        </div>
    </div>
    
</div>
<div class="col-12">
    <div class="text-end">
        <button class="btn btn-rounded btn-primary mb-5">Generate</button>
        {{-- <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Generate"> --}}
    </div>
</div>
                        </form>
                    </div>
                    
                </div>
            </div>
           </div>
           <div class="col">

            <div class="row mt-2">
                <div class="col-lg-12">
        
                
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Generated Content</h4>
                        </div><!-- end card header -->
        
                        <div class="card-body">
                            <textarea class="ifaz" id="myeditorinstance" readonly></textarea>

                            {{-- <div class="snow-editor" >
                                {{ $content }}
        
                            </div>  --}}
                        </div><!-- end card-body -->


                    </div><!-- end card -->
        
                 
                </div>
                <!-- end col -->
            </div>

           </div>

 
</div>
@endsection
@section('script')
<script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
<script src="{{ URL::asset('build/libs/quill/quill.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>


{{-- Submit Form Editor --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function disableInputs() {
        var creativeLevel = document.getElementById("creative_level").value;
        var temperatureInput = document.getElementById("temperature");
        var temperatureValueInput = document.getElementById("temperature_value");
        var topPInput = document.getElementById("top_p");
        var topPValueInput = document.getElementById("top_p_value");

        if (creativeLevel === "") {
            temperatureInput.disabled = false;
            temperatureValueInput.disabled = false;
            topPInput.disabled = false;
            topPValueInput.disabled = false;
        } else {
            temperatureInput.disabled = true;
            temperatureValueInput.disabled = true;
            topPInput.disabled = true;
            topPValueInput.disabled = true;
        }
    }
</script>

<script>
    $(document).ready(function() {
        // Update number input when the range slider changes
        $('#max_result_length').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(0); // Round to nearest integer
            $('#max_result_length_value').val(value);
        });
    
        // Update range slider when the number input changes
        $('#max_result_length_value').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(0); // Round to nearest integer
            if (!isNaN(value)) {
                $('#max_result_length').val(value);
            }
        });
    });
    </script>

<script>
    $(document).ready(function() {
        // Update number input when the range slider changes
        $('#presence_penalty').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            $('#presence_penalty_value').val(value);
        });
    
        // Update range slider when the number input changes
        $('#presence_penalty_value').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            if (!isNaN(value)) {
                $('#presence_penalty').val(value);
            }
        });
    });
    </script>

<script>
    $(document).ready(function() {
        // Update number input when the range slider changes
        $('#frequency_penalty').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            $('#frequency_penalty_value').val(value);
        });
    
        // Update range slider when the number input changes
        $('#frequency_penalty_value').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            if (!isNaN(value)) {
                $('#frequency_penalty').val(value);
            }
        });
    });
    </script>

<script>
    $(document).ready(function() {
        // Update number input when the range slider changes
        $('#top_p').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            $('#top_p_value').val(value);
        });
    
        // Update range slider when the number input changes
        $('#top_p_value').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            if (!isNaN(value)) {
                $('#top_p').val(value);
            }
        });
    });
    </script>

<script>
    $(document).ready(function() {
        // Update number input when the range slider changes
        $('#temperature').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            $('#temperature_value').val(value);
        });
    
        // Update range slider when the number input changes
        $('#temperature_value').on('input', function() {
            var value = parseFloat($(this).val()).toFixed(2);
            if (!isNaN(value)) {
                $('#temperature').val(value);
            }
        });
    });
    </script>

<script src="https://cdn.tiny.cloud/1/du2qkfycvbkcbexdcf9k9u0yv90n9kkoxtth5s6etdakoiru/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
    plugins: 'code table lists',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
  });
</script>

<script>
    $(document).ready(function () {
    $('#generateForm').submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (response) {
                //Generated content displays in a nice Format
                let formattedContent = '';

let lines = response.split('\n');

if (lines.some(line => line.trim().startsWith('*'))) {
    formattedContent += '<ul>';
    lines.forEach(line => {
        if (line.trim().startsWith('*')) {
            formattedContent += '<li>' + line.trim().substring(1).trim() + '</li>';
        } else {
            formattedContent += '<p>' + line.trim() + '</p>';
        }
    });
    formattedContent += '</ul>';
} else {
    formattedContent = '<p>' + lines.join('</p><p>') + '</p>';
}

tinymce.get('myeditorinstance').setContent(formattedContent);
},

            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                // Handle error if any
            }
        });
    });
});

</script>





@endsection
