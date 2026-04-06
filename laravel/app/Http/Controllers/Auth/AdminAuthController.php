<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\BrevoVerificationMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            if ($user->role !== 'admin') {
                return back()->withErrors([
                    'email' => 'You do not have administration access.',
                ]);
            }

            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            try {
                app(BrevoVerificationMailer::class)->sendVerificationCode($user->email, $code);
            } catch (\Throwable $e) {
                report($e);
                return back()->withErrors([
                    'email' => 'Unable to send verification code right now. Please try again in a moment.',
                ]);
            }

            Cache::put('2fa_code_' . $user->id, $code, now()->addMinutes(10));
            session(['auth_2fa_user_id' => $user->id, 'auth_2fa_email' => $user->email]);

            return redirect()->route('2fa.verify');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
