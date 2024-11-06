<style>
    .magic-ball-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3); /* Adjust transparency here */
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        z-index: 1050;
    }
    .magic-ball-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .magic-ball-gif {
        max-width: 90%;
        margin-bottom: 20px;
    }
    .d-none {
        display: none;
    }
</style>

<!-- resources/views/components/magic-ball.blade.php -->
<div id="magic-ball" class="magic-ball-overlay d-none">
    <div class="magic-ball-content">
        <img src="{{ asset('backend/giphy2.gif') }}" alt="Loading..." class="magic-ball-gif">
        <p id="joke-text">Fetching a joke...</p>
    </div>
</div>

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
        $('#magic-ball').removeClass('d-none');
        fetchJoke(category);
    }

    function hideMagicBall() {
        $('#magic-ball').addClass('d-none');
    }
</script>
