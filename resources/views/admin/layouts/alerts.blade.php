@if(session('success'))
<!-- Success Alert -->
<div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success</strong> - {{ session('success') }}
</div>

<script>
    // Automatically close the success alert after 1 second
    setTimeout(function(){
        $('#successAlert').fadeOut();
    }, 3000);
</script>
@endif

@if(session('warning'))
<!-- Warning Alert -->
<div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show" role="alert">
    <i class="ri-alert-line label-icon"></i><strong>Success</strong> - {{ session('warning') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


@if(session('danger'))
<!-- Danger Alert -->
<div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
    <i class="ri-error-warning-line label-icon"></i><strong>Error</strong> - {{ session('danger') }}
    <button type="button" class="btn-close" data-bs-dismiss=" alert" aria-label="Close"></button>
</div>
@endif
