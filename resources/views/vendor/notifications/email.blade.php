<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Clever Creator!</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center; /* Center align text */
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #798b7a;
            color: white;
            padding: 20px;
        }
        .header img {
            height: 50px;
        }
        .content {
            padding: 30px;
        }
        .content h1 {
            color: #333333;
            margin-bottom: 10px;
        }
        .content p {
            color: #555555;
            line-height: 1.8;
            margin-bottom: 10px;
            text-align: center;
        }
        .button {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 15px 30px;
            background-color: #595f5a;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            color: #777777;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            {{-- <img src="{{URL::asset('build/images/logo-dark1.png')}}" alt="Clever Creator Logo"> --}}
            <h1 style="text-align: center">Welcome to Clever Creator!</h1>
        </div>
        <div class="content">
            <p>Hello Creative Mind,</p>
            <p>We're thrilled to have you on board! To get started, please verify your account by clicking the link below. This will confirm your email address and activate your account.</p>
            <a href="{{$actionUrl}}" class="button">Verify Your Account</a>
            <p>If the button above doesn't work, no worries! You can copy and paste the following link into your browser:</p>
            <p><code>{{$actionUrl}}</code></p>
            <p>If you're prompted to log in, simply enter your username or email, along with your password, and you'll be all set.</p>
            <p>If you have any questions or need assistance, don't hesitate to reach out. We're here to help!</p>
            <p>Happy Creating,<br>The Clever Creator Team</p>
        </div>
        <div class="footer">
            <p>Need assistance? Get in touch with us or <a href="{{route('contact.us')}}">Contact Us</a>.</p>
        </div>
    </div>
</body>
</html>
