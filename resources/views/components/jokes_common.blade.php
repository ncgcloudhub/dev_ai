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
        @if ($siteSettings->magic_ball)
        <img src="{{ config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $siteSettings->magic_ball }}" alt="Loading..." class="magic-ball-gif">
        @endif
       
        <p id="joke-text" class="fs-5 gradient-bg-1 p-3 rounded-3 mx-3">
            Fetching a joke...
        </p>
    </div>
</div>

<script>
function fetchContent(type = '', category = '') {
    let url = '/fetch-content';

    if (type || category) {
        let typeParam = type ? encodeURIComponent(type) : 'null';
        let categoryParam = category ? encodeURIComponent(category) : 'null';
        url += `/${typeParam}/${categoryParam}`;
    }

    $.ajax({
        type: 'GET',
        url: url,
        success: function(response) {
            $('#joke-text').text(response.content);
        }
    });
}

function showMagicBall(type = '', category = '') {
    $('#magic-ball').removeClass('d-none');
    fetchContent(type, category);
}

function hideMagicBall() {
    $('#magic-ball').addClass('d-none');
}

</script>

