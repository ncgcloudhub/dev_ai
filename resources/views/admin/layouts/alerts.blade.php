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
<div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show" role="alert">
    <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss=" alert" aria-label="Close"></button>
</div>
@endif

