<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    /**
     * Mask an email for display: jbfaner8@gmail.com -> jbfa****@gmail.com
     */
    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email);
        $masked = substr($local, 0, 4) . str_repeat('*', max(strlen($local) - 4, 4));
        return $masked . '@' . $domain;
    }

    /**
     * Display the 2FA verification view.
     */
    public function index()
    {
        if (!session()->has('auth_2fa_user_id')) {
            return redirect()->route('login');
        }

        $email = session('auth_2fa_email', '');
        $maskedEmail = $email ? $this->maskEmail($email) : '';

        return view('auth.2fa-verify', compact('maskedEmail'));
    }

    /**
     * Handle code resend request.
     */
    public function resend(Request $request)
    {
        $userId = session('auth_2fa_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        // Generate fresh code
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('2fa_code_' . $user->id, $code, now()->addMinutes(10));

        Mail::to($user->email)->send(new TwoFactorCodeMail($code));

        return back()->with('resent', 'A new verification code has been sent to your email.');
    }

    /**
     * Handle an incoming 2FA verification request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $userId = session('auth_2fa_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $cachedCode = Cache::get('2fa_code_' . $userId);

        if ($cachedCode && $cachedCode == $request->code) {
            // Success! Log the user in.
            Auth::loginUsingId($userId);
            
            // Clean up
            Cache::forget('2fa_code_' . $userId);
            session()->forget(['auth_2fa_user_id', 'auth_2fa_email']);

            // Redirect based on role
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->role === 'seller') {
                return redirect()->intended(route('seller.dashboard'));
            }

            return redirect()->intended(route('discover'));
        }

        // Failed verification
        return back()->withInput()->withErrors([
            'code' => 'The provided verification code is incorrect or has expired.',
        ]);
    }
}
