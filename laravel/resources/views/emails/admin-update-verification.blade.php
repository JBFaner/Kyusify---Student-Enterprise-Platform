<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="{{ asset('images/kyusify-logo.ico') }}" type="image/x-icon">
    <title>Admin Update Verification Code</title>
</head>
<body style="font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f3f4f6; text-align: center; padding: 40px 20px;">
    <div style="max-width: 450px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
        <h1 style="color: #6d28d9; margin-top: 0; margin-bottom: 20px; font-size: 24px; font-weight: 800;">Profile Update Approval</h1>
        <p style="color: #4b5563; font-size: 16px; margin-bottom: 30px; line-height: 1.5;">Administrator <strong>{{ $updaterName }}</strong> is attempting to update your administrative account details on Kyusify. Please provide them with the following 6-digit code to verify and approve the changes.</p>
        
        <div style="font-size: 36px; font-weight: 800; letter-spacing: 8px; color: #4c1d95; background-color: #f5f3ff; border: 2px dashed #ddd6fe; padding: 25px 20px; border-radius: 12px; margin-bottom: 30px;">
            {{ $code }}
        </div>
        
        <p style="color: #9ca3af; font-size: 14px; line-height: 1.5; margin-bottom: 0;">This request and code will expire in 10 minutes. If you did not authorize this change, please ignore this email or contact the system administrator immediately.</p>
    </div>
</body>
</html>
