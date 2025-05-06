<!DOCTYPE html>
<html>
<head>
    <title>{{ $toolName }} - Generated Content</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; }
        .content { margin-top: 20px; }
        img { 
            max-width: 860px; /* Set a fixed max-width */
            max-height: 640px; /* Optional: set a max-height */
            width: auto;
            height: auto;
            display: block; 
            margin: 10px 0; 
        }
    </style>
    
</head>
<body>
    <h1>{{ $toolName }}</h1>
    <div class="content">
        {!! $content !!}
    </div>
</body>
</html>