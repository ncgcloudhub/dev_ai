<section class="section pb-0 hero-section" id="hero">
    <div id="banner" class="banner full-screen-mode parallax">
        <div class="container pr">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="banner-static">
                    <div class="banner-text py-5">
                        <div class="banner-cell py-5">
                            <h2 style="color: rgb(255, 255, 255)">
                                Get AI services Like
                                <span class="typer" id="some-id" data-delay="200" data-delim=":" data-words="AI Image Generation:AI Assistant:AI Blog Generation:AI Article Generate" data-colors="purple"></span>
                            </h2>
                            <h1 style="color: #e900ff; font-family: Shantell Sans, cursive;">Clever Creator AI</h1>
                            <p style="color: white">
                                Empower Your Creativity with Our AI: Generate Images, Craft Content, and Chat Seamlessly with Our OpenAI-Powered Assistant!
                            </p>

                            @if (Auth::check())
                            @else
                            <a href="{{ route('register') }}" class="btn gradient-btn-3 waves-effect waves-light">Sign Up for Free AI Services</a>
                            @endif

                            {{-- UI Card Start --}}
                            <div class="row row-cols-xxl-5 row-cols-lg-6 row-cols-1 justify-content-center py-5">
                                @php
                                    // Define routes and button labels
                                    $cards = [
                                        [
                                            'video' => $siteSettings->generate_image_webm,
                                            'route' => auth()->check() ? route('generate.image.view') : route('login'),
                                            'label' => 'Generate Images',
                                        ],
                                        [
                                            'video' => $siteSettings->generate_content_webm,
                                            'route' => auth()->check() ? route('aicontentcreator.manage') : route('login'),
                                            'label' => 'Generate Contents',
                                        ],
                                        [
                                            'video' => $siteSettings->prompt_library_webm,
                                            'route' => auth()->check() ? route('prompt.manage') : route('frontend.free.prompt.library'),
                                            'label' => 'Prompt Library',
                                        ],
                                        [
                                            'video' => $siteSettings->chat_bot_webm,
                                            'route' => auth()->check() ? route('main.chat.form') : route('login'),
                                            'label' => 'Chat Bot',
                                        ],
                                    ];
                                @endphp

                                @foreach ($cards as $card)
                                    @php
                                        $ext = pathinfo($card['video'], PATHINFO_EXTENSION);
                                        $videoUrl = Str::startsWith($card['video'], ['http', 'https'])
                                            ? $card['video']
                                            : config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $card['video'];
                                    @endphp
                                    <div class="col d-flex flex-column">
                                        <div class="card card-body glass flex-grow-1 d-flex flex-column justify-content-between card-background-common" style="min-height: 200px; position: relative; overflow: hidden;">
                                            @if ($ext === 'webm')
                                                <video autoplay loop muted playsinline
                                                    class="lazy-video w-100 h-100 position-absolute" 
                                                    src="{{ $videoUrl }}"
                                                    style="object-fit: cover; top: 0; left: 0; z-index: -1;">
                                                </video>
                                            @elseif ($ext === 'gif')
                                                <img src="{{ $videoUrl }}" class="lazy-video w-100 h-100 position-absolute" style="object-fit: cover; top: 0; left: 0; z-index: -1;" />
                                            @else
                                                {{-- fallback if needed --}}
                                            @endif
                                            <a href="{{ $card['route'] }}" class="btn gradient-btn-5 waves-effect waves-light mt-auto">
                                                {{ $card['label'] }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- UI Card End --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Lazy Load for Videos
    document.addEventListener("DOMContentLoaded", function () {
        const lazyVideos = document.querySelectorAll(".lazy-video");

        const observer = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const video = entry.target;
                        if (video.dataset.src) {
                            video.src = video.dataset.src;
                            video.removeAttribute("data-src");
                        }
                        video.load();
                        observer.unobserve(video);
                    }
                });
            },
            { rootMargin: "50px" }
        );

        lazyVideos.forEach((video) => {
            observer.observe(video);
        });
    });
</script>
