<!DOCTYPE html>
<html>
<head>
    <title>Flipbook Created</title>
</head>
<body>
    <h2>Flipbook Created Successfully!</h2>
    <p><strong>View Flipbook:</strong> <a href="{{ $url }}" target="_blank">{{ $url }}</a></p>
    <h3>Embed Code:</h3>
    <textarea style="width: 100%; height: 100px;">{!! $embed !!}</textarea>

    <h3>Preview:</h3>
    {!! $embed !!}
</body>
</html>
