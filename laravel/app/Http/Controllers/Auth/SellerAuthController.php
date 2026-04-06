<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enterprise;
use App\Services\BrevoVerificationMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class SellerAuthController extends Controller
{
    public function showRegistrationForm()
    {
        // Logged-in sellers already have access; send them to dashboard
        if (auth()->check() && auth()->user()->role === 'seller') {
            return redirect()->route('seller.dashboard');
        }
        return view('auth.seller-register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'business_name' => 'required|string|max:255',
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'seller',
                    'status' => 'active',
                ]);

                Enterprise::create([
                    'user_id' => $user->id,
                    'name' => $validated['business_name'],
                    'status' => 'pending',
                    'is_student_verified' => false,
                ]);

                \App\Helpers\NotificationHelper::notifyAdmins(
                    'new_seller',
                    'New Seller Registered',
                    "{$validated['name']} registered enterprise \"{$validated['business_name']}\" and is awaiting verification.",
                    route('admin.enterprises.index'),
                    'bell'
                );

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
        return view('auth.seller-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            if ($user->role !== 'seller') {
                return back()->withErrors([
                    'email' => 'You do not have seller access.',
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

            \Illuminate\Support\Facades\Cache::put('2fa_code_' . $user->id, $code, now()->addMinutes(10));
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

        return redirect('/'); // Redirect home or to login
    }

    public function upgradeToSeller(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
        ]);

        $user->update([
            'role' => 'seller'
        ]);

        Enterprise::create([
            'user_id' => $user->id,
            'name' => $validated['business_name'],
            'status' => 'pending', 
            'is_student_verified' => false,
        ]);

        \App\Helpers\NotificationHelper::notifyAdmins(
            'new_seller',
            'User Upgraded to Seller',
            "{$user->name} upgraded their account to seller with enterprise \"{$validated['business_name']}\" and is awaiting verification.",
            route('admin.enterprises.index'),
            'bell'
        );

        return redirect()->route('seller.dashboard')->with('success', 'You have successfully upgraded to a Seller Account!');
    }
}
