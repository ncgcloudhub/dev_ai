@foreach($tools as $tool)
<div class="col-lg-4 product-item template-card {{ Str::slug($tool->educationtools_category->category_name) }}" data-search="{{ strtolower($tool->name . ' ' . $tool->description) }}">
    <div class="card explore-box card-animate">
        <div class="bookmark-icon position-absolute top-0 end-0 p-2">
            <button type="button" class="btn btn-icon" data-bs-toggle="button" aria-pressed="true">
                <i class="mdi mdi-cards-heart fs-16"></i>
            </button>
        </div>
        <div class="explore-place-bid-img">
            <img src="{{ $tool->image }}" alt="" class="card-img-top explore-img" />
            <div class="bg-overlay"></div>
            <div class="place-bid-btn">
                <a href="{{ route('frontend.free.education.view', ['slug' => $tool->slug]) }}" class="btn gradient-btn-6">
                    <i class="ri-auction-fill align-bottom me-1"></i>Explore
                </a>
                
            </div>
        </div>
        <div class="card-body">
            <p class="fw-medium mb-0 float-end favorite-wrapper">
                <button class="favorite-button" data-id="{{ $tool->id }}" style="border: none; background: none; cursor: pointer;">
                    <i class="{{ $tool->is_favorited ? 'mdi mdi-heart' : 'mdi mdi-heart-outline' }} text-danger align-middle"></i>
                </button>
               
            </p>
            <h5 class="mb-1 fs-16">
                <a href="{{ route('frontend.free.education.view', ['slug' => $tool->slug]) }}" class="text-body">{{ $tool->name }}</a>
            </h5>
            <p class="text-muted fs-14 mb-0">{{ $tool->description }}</p>
        </div>
    </div>
</div>
@endforeach