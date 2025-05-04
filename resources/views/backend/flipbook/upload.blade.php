<!DOCTYPE html>
<html>
<head>
    <title>Upload PDF for Flipbook</title>
</head>
<body>
    <h2>Upload PDF to Create Flipbook</h2>

    @if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            @if (session('error_details'))
                <li><strong>API Response:</strong> {{ session('error_details') }}</li>
            @endif
        </ul>
    </div>
@endif


    <form action="{{ route('flipbooks.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="pdf" required>
        <button type="submit">Upload & Create Flipbook</button>
    </form>
</body>
</html>
