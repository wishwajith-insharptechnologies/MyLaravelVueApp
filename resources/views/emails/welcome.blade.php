<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e0e0e0;
        }
        .header {
            text-align: center;
        }
        .header img {
            width: 100px;
        }
        .content {
            margin: 20px 0;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            color: #333333;
        }
        .password-box {
            display: inline-block;
            margin: 20px 0;
            padding: 10px;
            background-color: #ffefe0;
            border: 1px solid #e0e0e0;
            font-weight: bold;
            font-size: 18px;
            color: #ff4500;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999999;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #999999;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="logo.png" alt="Company Logo">
        </div>
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            <p>Thank you for choosing our service! We appreciate your recent registration.</p>
            <p>Your system-generated password is:</p>
            <div class="password-box">{{ $password }}</div>
            <p>Please log in and change your password at your earliest convenience.</p>
            <p><a href="{{ config('app.url') }}/login">Login here</a></p>
        </div>
        <div class="footer">
            <p>If you have any questions or encounter any difficulties, our dedicated customer support team is here to assist you. You can reach them at <a href="mailto:support@example.com">support@example.com</a>.</p>
            <p>Thank you!</p>

        </div>
    </div>
</body>
</html>
