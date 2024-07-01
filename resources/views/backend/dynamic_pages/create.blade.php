@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Dynamic Page | ON PROCESS (SEO)</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('dynamic-pages.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="route">Route</label>
                                <input id="route" type="text" class="form-control @error('route') is-invalid @enderror" name="route" value="{{ old('route') }}" required>

                                @error('route')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">Content (Blade Template Name)</label>
                                <input id="content" type="text" class="form-control @error('content') is-invalid @enderror" name="content" value="{{ old('content') }}" required>

                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="card border card-border-info mt-3">
                                <div class="card-body">
                                   
                                      
                                        <div>
                                            <h5 class="mt-0">Page SEO</h5>
                                            <div class="col-md-12 mt-3">
                                                <label for="title" class="form-label">Title</label>
                                                <input type="text" name="title" value="" class="form-control mb-3" id="title"  placeholder="Enter Title">
                                            </div>
                        
                                            <div class="col-md-12 mb-3">
                                                <label for="keywords" class="form-label">Keywords</label>
                                                <input class="form-control" name="keywords" id="choices-text-unique-values" data-choices data-choices-text-unique-true type="text" value="Design, Remote"  />
                                            </div>
                        
                                            <div class="col-md-12">
                                                <label for="description" class="form-label">Description</label>
                                                <input type="text" name="description" value="" class="form-control mb-3" id="description" placeholder="Enter description">
                                            </div>
                                        </div>
                                    
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Create Page</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

<script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>

@endsection
