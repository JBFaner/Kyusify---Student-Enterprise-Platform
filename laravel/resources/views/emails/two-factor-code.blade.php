<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="{{ asset('images/kyusify-logo.ico') }}" type="image/x-icon">
    <title>Verification Code</title>
</head>
<body style="font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f3f4f6; text-align: center; padding: 40px 20px;">
    <div style="max-width: 450px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
        <h1 style="color: #6d28d9; margin-top: 0; margin-bottom: 20px; font-size: 24px; font-weight: 800;">Kyusify Security</h1>
        <p style="color: #4b5563; font-size: 16px; margin-bottom: 30px; line-height: 1.5;">You are attempting to log in to Kyusify. Please use the following 6-digit code to verify your identity.</p>
        
        <div style="font-size: 36px; font-weight: 800; letter-spacing: 8px; color: #4c1d95; background-color: #f5f3ff; border: 2px dashed #ddd6fe; padding: 25px 20px; border-radius: 12px; margin-bottom: 30px;">
            {{ $code }}
        </div>
        
        <p style="color: #9ca3af; font-size: 14px; line-height: 1.5; margin-bottom: 0;">This code will expire in 10 minutes. If you did not request this, please ignore this email and secure your password.</p>
    </div>
</body>
</html>
