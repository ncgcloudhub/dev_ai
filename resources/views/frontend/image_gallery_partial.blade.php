@foreach ($images as $item)
<div class="element-item col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 photography" data-category="photography">
    <div class="gallery-box card">
        <div class="gallery-container">
            <a class="image-popup" href="{{ asset($item->image_url) }}" title="">
                <img class="gallery-img img-fluid mx-auto d-block" src="{{ asset($item->image_url) }}" alt="" />
                <div class="gallery-overlay">
                    <h5 class="overlay-caption">{{$item->prompt}}</h5>
                </div>
            </a>
        </div>
        <div class="box-content">
            <div class="d-flex align-items-center mt-1">
                <div class="flex-grow-1 text-muted">by <a href="" class="text-body text-truncate">{{$item->user->name}}</a></div>
            </div>
        </div>
    </div>
</div>
@endforeach