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

