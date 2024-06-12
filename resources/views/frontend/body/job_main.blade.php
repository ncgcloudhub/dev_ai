
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
                    
                                    <div class="col-lg-6 job-item" data-job-id="{{ $item->id }}"> <!-- Assign 'job-item' class to each job card -->
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
        // When a job-item is clicked
        $('.job-item').on('click', function() {
      var jobId = $(this).data('job-id'); // Get the unique identifier of the clicked job item

      // Make an AJAX request to fetch additional details for the clicked job
      $.ajax({
        url: '/jobs/' + jobId, // Replace '/jobs/' with the actual endpoint to fetch job details
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          // Update the modal content with the details received from the server
          $('#modalJobTitle').text(response.job_title);
          $('#modalJobDetails').html(response.description);
          $('#modalJobSkills').html(response.skills);
          $('#modalJobResponsibility').html(response.responsibility);
          $('#modalJobVacancy').html(response.no_of_vacancy);
          $('#modalJobType').html(response.job_type);
          $('#modalJobPosition').html(response.job_position);
          $('#modalJobLastDateOfApply').html(response.last_date_of_apply);
          $('#modalJobCloseDate').html(response.close_date);
          $('#modalJobExperience').html(response.experience);

          // Show the modal
          $('#jobModal').modal('show');
        },
        error: function(xhr, status, error) {
          // Handle error
          console.error(error);
        }
      });
    });


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

<!-- Modal -->
<div class="modal fade bs-example-modal-xl" id="jobModal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalJobTitle"></h4>
                <h5 class="modal-title" id="modalJobPosition"> </h5>
                <span class="badge badge-label bg-primary" id="modalJobType"><i class="mdi mdi-circle-medium"></i></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-8">
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-secondary">
                                Vacancy <span class="badge bg-success ms-1" id="modalJobVacancy"></span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary">
                                Experience <span class="badge bg-success ms-1" id="modalJobExperience"></span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary">
                                Last Application Date <span class="badge bg-danger ms-1" id="modalJobLastDateOfApply"></span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary">
                                Close Date <span class="badge bg-danger ms-1" id="modalJobCloseDate"></span>
                            </button>
                        </div>

                    <h6 class="fs-15">Details</h6>
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-checkbox-circle-fill text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="text-muted mb-0" id="modalJobDetails"></p>
                        </div>
                    </div>
                    
                    <h6 class="fs-16 my-3">Skills</h6>
                    <div class="d-flex mt-2">
                        <div class="flex-shrink-0">
                            <i class="ri-checkbox-circle-fill text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-2 ">
                            <p class="text-muted mb-0" id="modalJobSkills"></p>
                        </div>
                    </div>
    
                    <h6 class="fs-16 my-3">Responsibility</h6>
                    <div class="d-flex mt-2">
                        <div class="flex-shrink-0">
                            <i class="ri-checkbox-circle-fill text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-2 ">
                            <p class="text-muted mb-0" id="modalJobResponsibility"></p>
                        </div>
                    </div>
                </div>


                    <div class="col-4">
                        <div class="modal-body login-modal p-5">
                            <h5 class="text-white fs-20">Apply Now</h5>
                            @guest
                            <p class="text-white-50 mb-4">Don't have an account?</p>
                            <div class="vstack gap-2 justify-content-center">
                                <a href="{{ route('google.login') }}" class="btn btn-light">
                                    <i class="ri-google-fill align-bottom text-danger"></i> Google
                                </a>
                                <a href="/login" class="btn btn-info"><i class="ri-sign-up-fill align-bottom"></i> Sign Up</a>
                               
                            </div> 
                            @endguest
                        </div>
                       
                            
                        <div class="modal-body p-5">
                           @auth
                           <h5 class="mb-3">Fill up the Details</h5>
                           <form method="POST" action="{{ route('job.apply') }}" enctype="multipart/form-data"  onsubmit="showSuccessModal()">
                            @csrf
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" placeholder="Enter your name" value="{{ auth()->user()->name }}" name="fullName" required>
                            </div>
                            <div class="mb-3">
                                <label for="emailInput" class="form-label">Email address</label>
                                <input type="email" class="form-control" name="email" id="emailInput" placeholder="Enter your email" value="{{ auth()->user()->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="phone" class="form-control" name="phone" id="phone" placeholder="Enter your phone" value="{{ auth()->user()->phone }}" >
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="address" class="form-control" name="address" id="address" placeholder="Enter your address" value="{{ auth()->user()->address }}" >
                            </div>
                            <div class="mb-3">
                                <input type="file" name="cv" class="form-control" placeholder="Upload CV">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="checkTerms">
                                <label class="form-check-label" for="checkTerms">I agree to the <span class="fw-semibold"><a href="">Terms of Service</a></span> and <a href="">Privacy Policy</a></label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </form>                        
                           @endauth
                        </div>
                    </div>
                
                </div>      
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

</section>