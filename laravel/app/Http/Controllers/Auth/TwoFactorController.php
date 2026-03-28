<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TwoFactorController extends Controller
{
    /**
     * Display the 2FA verification view.
     */
    public function index()
    {
        if (!session()->has('auth_2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.2fa-verify');
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
            session()->forget('auth_2fa_user_id');

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
