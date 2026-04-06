<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoVerificationMailer
{
    public function sendVerificationCode(string $toEmail, string $code): void
    {
        $apiKey = config('services.brevo.key');
        $fromAddress = config('services.brevo.from_address');
        $fromName = config('services.brevo.from_name');

        if (!$apiKey) {
            throw new \RuntimeException('BREVO_API_KEY is not configured.');
        }

        if (!$fromAddress) {
            throw new \RuntimeException('BREVO_FROM_ADDRESS (or MAIL_FROM_ADDRESS) is not configured.');
        }

        $subject = 'Your Kyusify Verification Code';
        $html = $this->buildHtml($code);
        $text = "Your Kyusify verification code is: {$code}";

        $response = Http::withHeaders([
            'api-key' => $apiKey,
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => $fromName,
                'email' => $fromAddress,
            ],
            'to' => [
                ['email' => $toEmail],
            ],
            'subject' => $subject,
            'htmlContent' => $html,
            'textContent' => $text,
        ]);

        if (!$response->successful()) {
            $msg = $response->json('message') ?? $response->body();
            throw new \RuntimeException('Brevo email send failed: ' . $msg);
        }
    }

    private function buildHtml(string $code): string
    {
        $safeCode = e($code);

        return <<<HTML
<div style="font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial; background:#f6f5ff; padding:24px;">
  <div style="max-width:560px; margin:0 auto; background:#ffffff; border:1px solid #ece9ff; border-radius:16px; overflow:hidden;">
    <div style="padding:18px 20px; background:linear-gradient(90deg,#7c3aed,#8b5cf6); color:#fff;">
      <div style="font-weight:800; letter-spacing:-0.01em;">Kyusify Security</div>
      <div style="opacity:.9; font-size:13px;">Verification Code</div>
    </div>
    <div style="padding:22px 20px; color:#2b2b2b;">
      <p style="margin:0 0 10px; font-size:14px; line-height:1.6;">
        Use this 6-digit code to complete your sign-in / registration:
      </p>
      <div style="display:inline-block; padding:12px 16px; border-radius:12px; background:#f4f0ff; border:1px solid #e9e2ff; font-size:28px; letter-spacing:0.35em; font-weight:800; color:#4c1d95;">
        {$safeCode}
      </div>
      <p style="margin:14px 0 0; font-size:12px; color:#6b7280; line-height:1.6;">
        If you didn’t request this, you can ignore this email.
      </p>
    </div>
  </div>
</div>
HTML;
    }
}

