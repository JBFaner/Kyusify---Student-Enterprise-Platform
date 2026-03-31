<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="{{ asset('images/kyusify-logo.ico') }}" type="image/x-icon">
    <title>Admin Deletion Verification Code</title>
</head>
<body style="font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f3f4f6; text-align: center; padding: 40px 20px;">
    <div style="max-width: 450px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-top: 4px solid #ef4444;">
        <h1 style="color: #dc2626; margin-top: 0; margin-bottom: 20px; font-size: 24px; font-weight: 800;">Dangerous Action Verification</h1>
        <p style="color: #4b5563; font-size: 16px; margin-bottom: 30px; line-height: 1.5;">Administrator <strong>{{ $updaterName }}</strong> is attempting to permanently <strong style="color: #dc2626;">delete</strong> your administrative account on Kyusify. If this is intentional, please provide them with the following 6-digit code to authorize the deletion.</p>
        
        <div style="font-size: 36px; font-weight: 800; letter-spacing: 8px; color: #991b1b; background-color: #fef2f2; border: 2px dashed #fca5a5; padding: 25px 20px; border-radius: 12px; margin-bottom: 30px;">
            {{ $code }}
        </div>
        
        <p style="color: #9ca3af; font-size: 14px; line-height: 1.5; margin-bottom: 0;">This code will expire in 10 minutes. If you did not authorize this change, please contact system administration immediately!</p>
    </div>
</body>
</html>
