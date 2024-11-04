<!-- resources/views/components/magic-ball.blade.php -->
<div id="magic-ball" class="magic-ball-overlay d-none">
    <div class="magic-ball-content">
        <img src="{{ asset('backend/giphy.gif') }}" alt="Loading..." class="magic-ball-gif">
        <p id="joke-text">Fetching a joke...</p>
    </div>
</div>

<!-- Magic Ball Styles -->
@push('styles')
<style>
    .magic-ball-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6); /* Semi-transparent overlay */
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        z-index: 1050; /* Ensures overlay is above other elements */
    }
    .magic-ball-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .magic-ball-gif {
        max-width: 100px;
        margin-bottom: 20px;
    }
    .d-none {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
    function fetchJoke(category) {
        $.ajax({
            type: 'GET',
            url: `/fetch-joke/${category}`,
            success: function(response) {
                $('#joke-text').text(response.joke);
            }
        });
    }

    function showMagicBall(category) {
        $('#magic-ball').removeClass('d-none'); // Show overlay
        fetchJoke(category); // Fetch joke based on category
    }

    function hideMagicBall() {
        $('#magic-ball').addClass('d-none'); // Hide overlay
    }
</script>
@endpush
