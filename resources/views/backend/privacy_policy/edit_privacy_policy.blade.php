@extends('admin.layouts.master')
@section('title') Edit Privacy Policy @endsection

@section('content')
@component('admin.components.breadcrumb')
@slot('li_1') <a href="{{route('manage.privacy.policy')}}">Privacy Policy</a> @endslot
@slot('title') Edit @endslot
@endcomponent


<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Manage Privacy Policy</h5>
            </div>
            <div class="card-body">
                <table id="alternative-pagination" class="table responsive align-middle table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl.</th>
                            <th scope="col">Details</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($privacy_policy as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {!! $item->details !!}
                            </td>
                            <td>
                                <div class="hstack gap-3 flex-wrap"> 
                                    <a href="{{ route('edit.privacy.policy', $item->id) }}" class="fs-15"><i class="ri-edit-2-line"></i></a> 
                                    <a href="{{ route('delete.privacy.policy',$item->id) }}" onclick="return confirm('Are you sure you want to delete this Policy')" class="link-danger fs-15"><i class="ri-delete-bin-line"></i></a> 
                                </div>
                            </td>
                          
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-xxl-6">
        <form method="POST" action="{{ route('update.privacy.policy') }}" class="row g-3">

            @csrf

            <input type="hidden" name="id" value="{{$privacy_policys->id}}">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Edit Privacy Policy</h4>
            </div><!-- end card header -->
    
            <div class="card-body">
                <div class="live-preview">
    
                        <div class="col-md-12">
                            <label class="form-label">Details</label>
                            <textarea name="details" value="{{$privacy_policys->details}}" class="form-control" id="myeditorinstance">{{$privacy_policys->details}}</textarea>
                        </div>
                </div>
            </div>
        </div>
    
        <div class="col-12">
            <div class="text-end">
                <input type="submit" class="btn btn-rounded gradient-btn-save mb-5" value="Update">
            </div>
        </div>
    </form>
    </div>
</div>

@endsection

@section('script')

<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/du2qkfycvbkcbexdcf9k9u0yv90n9kkoxtth5s6etdakoiru/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
     tinymce.init({
        selector: 'textarea#myeditorinstance',
        branding: false, // Removes "Build with TinyMCE"
        plugins: 'code table lists image media autosave emoticons fullscreen preview quickbars wordcount codesample',
        toolbar: 'undo redo | blocks fontsizeinput | bold italic backcolor emoticons | alignleft aligncenter alignright alignjustify blockquote | bullist numlist outdent indent | removeformat | code codesample fullscreen | image media | restoredraft preview quickimage wordcount',
        autosave_restore_when_empty: true,
        height: 400,
        statusbar: true, // Keep the status bar for resizing
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        },
        init_instance_callback: function (editor) {
            setTimeout(function () {
                document.querySelector('.tox-statusbar__path').style.display = 'none'; // Hide <p> indicator
            }, 100);
        }
    });
</script>

@include('admin.layouts.datatables')

@endsection