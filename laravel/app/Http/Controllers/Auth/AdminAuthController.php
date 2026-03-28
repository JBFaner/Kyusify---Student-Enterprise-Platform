<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            if ($user->role !== 'admin') {
                return back()->withErrors([
                    'email' => 'You do not have administration access.',
                ]);
            }

            // Generate 2FA code
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store in Cache
            \Illuminate\Support\Facades\Cache::put('2fa_code_' . $user->id, $code, now()->addMinutes(10));
            
            // Store User ID in session
            session(['auth_2fa_user_id' => $user->id]);
            session(['auth_2fa_email' => $user->email]);
            
            // Send Email
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\TwoFactorCodeMail($code));
            
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
