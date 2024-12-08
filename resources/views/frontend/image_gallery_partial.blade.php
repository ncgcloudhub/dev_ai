@foreach ($images as $item)
    <div class="element-item col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 photography" data-category="photography">
        <div class="gallery-box card">
            <div class="gallery-container">
                <a class="image-popups" href="#" data-bs-toggle="modal" data-bs-target="#imageModal" title="{{ $item->prompt }}"
                    data-image-url="{{ $item->image_url }}" data-image-prompt="{{ $item->prompt }}" data-image-resolution="{{ $item->resolution }}">
                    <img class="gallery-img img-fluid mx-auto d-block" src="{{ $item->image_url }}" loading="lazy" alt="" />
                </a>
            </div>
        </div>

    @auth
        <button type="button" 
                class="btn btn-sm btn-outline-primary position-relative like-button {{ $item->liked_by_user ? 'ri-thumb-up-fill' : 'ri-thumb-up-line' }}" 
                data-image-id="{{ $item->id }}">
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">{{ $item->likes_count }}</span>
        </button>
    
        <button type="button" 
                class="btn btn-sm btn-outline-primary position-relative favorite-button {{ $item->favorited_by_user ? 'ri-heart-2-fill' : 'ri-heart-2-line' }}" 
                data-image-id="{{ $item->id }}">
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $item->favorites_count }}</span>
        </button>
    @else
        <a href="{{ route('login') }}" 
        class="btn btn-sm btn-outline-primary position-relative {{ $item->liked_by_user ? 'ri-thumb-up-fill' : 'ri-thumb-up-line' }}">
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">{{ $item->likes_count }}</span>
        </a>

        <a href="{{ route('login') }}" 
            class="btn btn-sm btn-outline-primary position-relative {{ $item->favorited_by_user ? 'ri-heart-2-fill' : 'ri-heart-2-line' }}">
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $item->favorites_count }}</span>
        </a>
    @endauth

        <a href="{{ $item->image_url }}" download="{{ $item->prompt }}.jpg" class="btn btn-sm btn-outline-secondary position-relative download-button">
            <i class="ri-download-line"></i>
        </a>

        <button type="button" class="btn gradient-btn-9 btn-sm share-button" data-image-url="{{ $item->image_url }}" data-image-prompt="{{ $item->prompt }}">
            <i class="ri-share-forward-fill"></i>
        </button>
    </div>
@endforeach

{{-- SHAREE --}}
<script>
    $(document).on('click', '.share-button', function() {
        var imageUrl = $(this).data('image-url');
        var promptText = $(this).data('image-prompt');

        // Construct the share message
        var shareMessage = promptText + ': ' + imageUrl;

        // Open share dialog based on the platform
        if (navigator.share) {
            navigator.share({
                title: promptText,
                text: shareMessage,
                url: imageUrl
            }).then(() => console.log('Successful share')).catch((error) => console.log('Error sharing', error));
        } else {
            // Fallback for browsers that do not support native share API
            var whatsappUrl = 'whatsapp://send?text=' + encodeURIComponent(shareMessage);
            window.open(whatsappUrl);
        }
    });
</script>

<script>
    $(document).on('click', '.image-popups', function() {
        const imageUrl = $(this).attr('data-image-url');
        const title = $(this).attr('title');
        const prompt = $(this).attr('data-image-prompt');
        const resolution = $(this).attr('data-image-resolution');
        
        // Update modal content
        $('#modalImage').attr('src', imageUrl);
        $('#imageModalLabel').text(title);
        $('#modalPrompt').text(prompt);
        $('#modalResolution').text(resolution);
        
        // Show the modal
        $('#imageModal').modal('show');
    });
</script>

{{-- LIKE --}}
<script>
   $(document).ready(function() {
    // Like button functionality
    $(document).off('click', '.like-button').on('click', '.like-button', function() {
        var imageId = $(this).data('image-id');
        var likeButton = $(this); 
        var likeCountBadge = likeButton.find('.badge');
        $.ajax({
            url: '/like',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { image_id: imageId },
            success: function(response) {
                // Update UI to reflect the new like status
                if (response.liked) {
                    // Image is liked
                    likeButton.toggleClass('ri-thumb-up-line ri-thumb-up-fill');
                    likeCountBadge.text(parseInt(likeCountBadge.text()) + 1);
                } else {
                    // Image is unliked
                    likeButton.removeClass('ri-thumb-up-fill').addClass('ri-thumb-up-line');
                    likeCountBadge.text(parseInt(likeCountBadge.text()) - 1);
                }
            },
            error: function(xhr) {
                // Handle errors
            }
        });
    });

    // Favorite button functionality
    $(document).off('click', '.favorite-button').on('click', '.favorite-button', function() {
        var imageId = $(this).data('image-id');
        var favoriteButton = $(this);
        var favoriteCountBadge = favoriteButton.find('.badge');
        $.ajax({
            url: '/favorite',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { image_id: imageId },
            success: function(response) {
                // Update UI to reflect the new favorite status
                if (response.favorited) {
                    // Image is favorited
                    favoriteButton.removeClass('ri-heart-2-line').addClass('ri-heart-2-fill');
                    favoriteCountBadge.text(parseInt(favoriteCountBadge.text()) + 1);
                } else {
                    // Image is unfavorited
                    favoriteButton.removeClass('ri-heart-2-fill').addClass('ri-heart-2-line');
                    favoriteCountBadge.text(parseInt(favoriteCountBadge.text()) - 1);
                }
            },
            error: function(xhr) {
                // Handle errors
            }
        });
    });
});

</script>

