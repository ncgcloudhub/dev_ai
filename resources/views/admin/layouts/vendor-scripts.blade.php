<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('build/js/plugins.js') }}"></script>

{{-- Sweetalerts --}}
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/sweetalerts.init.js') }}"></script>

{{-- Datatables --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

{{-- Select Multiple Tag --}}
<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>

<!-- tinymce | TEXT EDITOR -->
<script src="{{ asset('backend/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce.js') }}"></script>
<!--END tinymce TEXT EDITOR-->


{{-- CHAT STARt Scripts--}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

{{-- Attach FIle Icon Start--}}
<script>
    document.getElementById('icon').addEventListener('click', function() {
        document.getElementById('file_input').click();
    });
     // Listen for file input change event
     document.getElementById('file_input').addEventListener('change', function() {
        var fileInput = document.getElementById('file_input');
        var fileNameDisplay = document.getElementById('file_name_display');

        // Check if a file is selected
        if (fileInput.files.length > 0) {
            // Display the name of the selected file
            fileNameDisplay.textContent = "Selected File: " + fileInput.files[0].name;
        } else {
            // If no file is selected, clear the display
            fileNameDisplay.textContent = "";
        }
    });
</script>
{{-- Attach FIle Incon END --}}

{{-- FRONTEND SINGLE IMAGE --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#generateButton').click(function () {
            var prompt = $('#prompt').val();

            // Check if the prompt textarea is empty
            if (prompt.trim() === "") {
                // Show the error message
                $('#promptError').removeClass('d-none');
                return; // Stop the function here
            } else {
                // Hide the error message if prompt is not empty
                $('#promptError').addClass('d-none');
            }

            // Proceed with the generation process
            $('#generateButton').attr('disabled', true);
            $('#buttonText').text('Generating...');
            $('#loadingSpinner').removeClass('d-none');

            $.ajax({
                type: 'POST',
                url: '{{ route("generate.single.image") }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    prompt: prompt
                },
                success: function (response) {
                    $('#loadingSpinner').addClass('d-none');
                    $('#buttonText').text('Try again tomorrow');

                    if (response.message) {
                        $('#generatedImageContainer').html('<p>' + response.message + '</p>');
                    } else {
                        var imageUrl = response.data[0].url;
                        if (imageUrl) {
                            $('#generatedImageContainer').html('<img src="' + imageUrl + '" class="img-fluid" alt="Generated Image">');
                        } else {
                            $('#generatedImageContainer').html('<p>No image generated.</p>');
                        }

                        $('#generatedImageContainer').append('<p class="mt-3 text-warning">You have generated your image for today. Please come back tomorrow to generate a new one.</p>');
                    }
                },
                error: function (xhr, status, error) {
                    $('#loadingSpinner').addClass('d-none');
                    $('#generateButton').attr('disabled', false);
                    $('#buttonText').text('Generate');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>



{{-- NEWSLETTER --}}
<script>
    document.getElementById('newsletterForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Fetch form data
        var formData = new FormData(this);

        // Send form data asynchronously using fetch
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                // Optionally display a success message
                alert('Subscribed Successfully');
            } else {
                // Handle errors if any
                alert('Error occurred while subscribing');
            }
        })
        .catch(error => {
            console.error('Error occurred:', error);
            alert('Error occurred while subscribing');
        });

        // Prevent the form from being submitted again
        return false;
    });
</script>

{{-- Optimize Hammer --}}
<script>
function toggleOptimize() {
    const optimizeInput = document.getElementById("hiddenPromptOptimize");
    const optimizeIcon = document.getElementById("optimizeIcon").firstElementChild;

    // Toggle between optimized and non-optimized
    if (optimizeInput.value === "1") {
        optimizeInput.value = "0";
        console.log('on');
        optimizeIcon.classList.replace("ri-hammer-fill", "ri-hammer-line"); // Reset to default icon
    } else {
        optimizeInput.value = "1";
        console.log('off');
        optimizeIcon.classList.replace("ri-hammer-line", "ri-hammer-fill"); // Change to optimized icon
    }
}
</script>

<script>
    // Auto dismiss success alert after 3 seconds
    window.setTimeout(function() {
        $("#successAlert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 3000);
</script>



@yield('script')
@yield('script-bottom')
