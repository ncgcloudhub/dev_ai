
<section class="section pb-0 hero-section" id="hero">
    <div class="container">
        <div class="row">
            @php
            // Fetch all jobs from the Job model
            $jobs = \App\Models\Job::all();
             @endphp
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row justify-content-center mb-4">
                            <div class="col-lg-6">
                                <div class="row g-2">
                                    
                                    <div class="col-auto">
                                        <div class="search-box">
                                            <input type="text" class="form-control search bg-light border-light" id="searchJob" autocomplete="off" placeholder="Search for jobs...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                          
                        </div>
                        <!--end row-->
    
                      
                    </div>
                  
                    <div class="card-body p-4">
                        <div class="tab-content text-muted">
                    
                            <div class="tab-pane active" id="news" role="tabpanel">
                                <div class="row" id="jobList">
                                    @foreach ($jobs as $item)
                    
                                    <div class="col-lg-6 job-item"> <!-- Assign 'job-item' class to each job card -->
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="d-sm-flex">
                                                    <div class="flex-grow-1 ms-sm-4 mt-3 mt-sm-0">
                                                        <ul class="list-inline mb-2">
                                                            <li class="list-inline-item"><span class="badge bg-success-subtle text-success fs-11">{{$item->job_type}}</span></li>
                                                        </ul>
                                                        <h5><a href="javascript:void(0);">{{$item->job_title}} </a> 
                                                            <a class="btn btn-outline-primary btn-border" href=""><i class="ri-add-line align-bottom"></i></a>
                                                        </h5>
                                                        <ul class="list-inline mb-0">
                                                            <li class="list-inline-item"><i class="ri-user-3-fill text-success align-middle me-1"></i>{{$item->no_of_vacancy}}</li>
                                                            <li class="list-inline-item"><i class="ri-calendar-2-fill text-success align-middle me-1"></i>{{$item->last_date_of_apply}}</li>
                                                            <li class="list-inline-item"><i class="ri-map-pin-2-fill text-success align-middle me-1"></i>{{$item->state}}, {{$item->country}}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end card-->
                                    </div>
                    
                                    @endforeach
                                </div>
                                <!--end row-->
                    
                            </div>
                            <!--end tab-pane-->
                        </div>
                        <!--end tab-content-->
                    
                    </div>
                    <!--end card-body-->
                    </div>
                    <!--end card -->
                    </div>
                    <!--end card -->
                    </div>
                    </div>




<!-- jQuery from a CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#searchJob').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            $('.job-item').each(function() {
                var jobTitle = $(this).find('h5 a').text().toLowerCase();
                if (jobTitle.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>

                    
</section>