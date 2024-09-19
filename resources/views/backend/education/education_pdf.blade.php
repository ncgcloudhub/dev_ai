<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin-top: 20px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
        }
        .content-text {
            white-space: pre-wrap; /* Preserve line breaks and spaces */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $content->topic }}</h1>
            <p>Generated on: {{ now()->toFormattedDateString() }}</p>
        </div>

        <div class="content">
            <h2>Grade: {{ $content->gradeClass->grade }}</h2>
            <h2>Subject: {{ $content->subject->name }}</h2>
            <div class="content-text">
                {!! $content->generated_content !!}
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Clever Creator</p>
        </div>
    </div>
</body>
</html>
