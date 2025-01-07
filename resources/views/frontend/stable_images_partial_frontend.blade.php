@foreach ($stableImages as $item)
<div class="element-item col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 photography" data-category="photography">
    <div class="gallery-box card">
        <div class="gallery-container">
            <a class="image-popups" href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                title="{{ $item->prompt }}" data-image-url="{{ $item->image_url }}" data-image-prompt="{{ $item->prompt }}">
                <img class="gallery-img img-fluid mx-auto d-block" src="{{ $item->image_url }}" alt="{{ $item->prompt }}" loading="lazy" />
            </a>
        </div>
    </div>

        <button type="button" class="btn btn-sm btn-outline-primary position-relative like-button {{ $item->liked_by_user ? 'ri-thumb-up-fill' : 'ri-thumb-up-line' }}" data-image-id="{{ $item->id }}">
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">{{ $item->likes_count }}</span>
        </button>

        <a href="{{ $item->image_url }}" download="{{ $item->prompt }}.jpg" class="btn btn-sm btn-outline-secondary position-relative download-button">
            <i class="ri-download-line"></i>
        </a>

        <button type="button" class="btn gradient-btn-9 btn-sm share-button" data-image-url="{{ $item->image_url }}" data-image-prompt="{{ $item->prompt }}">
            <i class="ri-share-forward-fill"></i>
        </button>
    </div>
@endforeach