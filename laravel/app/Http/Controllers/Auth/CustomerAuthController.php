<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BrevoVerificationMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class CustomerAuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.customer-register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'customer',
                    'status' => 'active',
                ]);

                $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                app(BrevoVerificationMailer::class)->sendVerificationCode($user->email, $code);

                Cache::put('2fa_code_' . $user->id, $code, now()->addMinutes(10));
                session(['auth_2fa_user_id' => $user->id, 'auth_2fa_email' => $user->email]);

                return redirect()->route('2fa.verify')->with('success', 'Registration successful. Please verify the code sent to your email.');
            });
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->withErrors([
                'email' => 'Registration failed while sending verification code. Please try again.',
            ]);
        }
    }

    public function showLoginForm()
    {
        return view('auth.customer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
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

        return redirect('/');
    }
}
