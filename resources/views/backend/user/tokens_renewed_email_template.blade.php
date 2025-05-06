<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Clever Creator AI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f1f1f1;
            color: #333;
            font-size: 15px;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .header {
            padding: 2em;
            background-color: #17bebb;
            color: #fff;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 600;
        }

        .content {
            padding: 2em 2.5em;
        }

        .content h2 {
            font-size: 22px;
            color: #000;
            font-weight: 600;
            margin-bottom: 0.5em;
        }

        .content p {
            margin: 0.5em 0 1.5em;
            color: #555;
        }

        .info-box {
            background: #f7fafa;
            padding: 1.5em;
            border-radius: 8px;
            margin-bottom: 1.5em;
        }

        .info-box h3 {
            margin: 0 0 0.5em;
            font-size: 18px;
            color: #000;
            font-weight: 500;
        }

        .info-box p {
            font-size: 16px;
            font-weight: 600;
            color: #17bebb;
            margin: 0;
        }

        .footer {
            background: #f9f9f9;
            padding: 1.5em;
            text-align: center;
            font-size: 14px;
            color: #999;
        }

        .footer p {
            margin: 0;
        }

        @media (max-width: 600px) {
            .content, .header, .footer {
                padding: 1.5em !important;
            }

            .info-box {
                padding: 1.2em;
            }
        }
    </style>
</head>
<body>

    <div class="email-container">
        <div class="header">
            <h1>Hi {{ $user->name }},</h1>
            <p style="margin-top: 8px;">Your subscription has been successfully renewed.</p>
        </div>

        <div class="content">
            <h2>You're all set!</h2>
            <p>We’ve renewed your tokens and credits. Here’s what have been renewed:</p>

            <div class="info-box">
                <h3>Tokens</h3>
                <p>{{ $tokens }}</p>
            </div>

            <div class="info-box">
                <h3>Credits</h3>
                <p>{{ $credits }}</p>
            </div>
        </div>

        <div class="footer">
            <p>Thanks for being part of Clever Creator AI!</p>
        </div>
    </div>

</body>
</html>
