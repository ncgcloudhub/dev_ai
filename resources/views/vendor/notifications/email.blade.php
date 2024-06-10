<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #ffeb3b;
            text-align: center;
            padding: 20px;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content h1 {
            color: #333333;
        }
        .content p {
            color: #555555;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #333333;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="data:image/jpeg;base64,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" alt="Your Logo">
        </div>
        <div class="content">
            <h1>Welcome!</h1>
            <img src="https://www.bypeople.com/wp-content/uploads/2015/07/low-poly-online-background-generator-460x230.jpg" alt="Welcome Image" style="max-width: 100%;">
            <p>We're excited to have you get started! First you need to confirm your account. Just click the button below.</p>
            <a href="{{$actionUrl}}" class="button">Confirm Your Account</a>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. <a href="{{$actionUrl}}" style="color: #007bff;">voluptate velit esse cillum dolore eu fugiat nulla pariatur enim ad minim veniam.</a></p>
            <p>If you have any questions. Please feel free to inform - We're always ready to help out.</p>
            <p>Cheers,<br>The Team Name.</p>
        </div>
        <div class="footer">
            <p>Get In Touch</p>
        </div>
    </div>
</body>
</html>
