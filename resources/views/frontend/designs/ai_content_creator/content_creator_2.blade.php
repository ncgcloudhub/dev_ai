<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="mb-3 ff-secondary fw-semibold lh-base">Generate Contentsssss</h1>
            <p class="text-muted">Generate your contents easily with our pre-defined templates</p>
        </div>
        <div class="row align-items-center gy-4">
           
            <div class="row template-row">
                @foreach ($templates as $item)
                <div class="col-md-3 p-3 template-card" data-category="{{$item->category_id}}">
                    <div class="card" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        @auth
                            <a href="{{route('template.view', ['slug' => $item->slug])}}" class="text-decoration-none">
                        @else
                            <a href="{{route('frontend.free.template.view', ['slug' => $item->slug])}}" class="text-decoration-none">
                        @endauth
                            <div class="card-body d-flex flex-column justify-content-between" style="height: 250px;"> <!-- Fixed height for the card -->
                                <div class="mb-3">
                                    <div style="width: 42px; height: 42px; border-radius: 50%; background-color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 .125rem .3rem -0.0625rem rgba(0,0,0,.1),0 .275rem .75rem -0.0625rem rgba(249,248,249,.06)">
                                        <img width="22px" src="/build/images/templates/{{$item->icon}}.png" alt="" class="img-fluid">
                                    </div>
                                    <h3 class="fw-medium link-primary">{{$item->template_name}}</h3>
                                    <p style="height: 3em; overflow: hidden; color:black" class="card-text customer_name">{{$item->description}}</p>
                                </div>
                                <div>
                                    <small class="text-muted">0 Words generated</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
            
            </div>
            <div class="mx-auto d-flex justify-content-center">
                @auth
                    <a href="{{ route('template.manage') }}" class="btn btn-primary">Show More</a> <!-- Redirect to user.prompt.library if user is a normal user -->
                @else
                    <a href="{{ route('frontend.free.template') }}" class="btn btn-primary">Show More</a> <!-- Redirect to frontend.free.prompt.library if no one is logged in -->
                @endauth
            </div>
        </div>
        <!-- end row -->
        <!-- end row -->
    </div>
    <!-- end container -->
</section>