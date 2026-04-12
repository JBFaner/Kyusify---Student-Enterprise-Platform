<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminUpdateVerificationMail;
use App\Mail\AdminDeleteVerificationMail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.user-management.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user-management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'seller', 'customer'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'pending'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.user-management.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.user-management.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'seller', 'customer'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'pending'])],
        ]);

        $passwordUpdate = null;
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $passwordUpdate = Hash::make($request->password);
        }

        // Intercept updates directed at Administrator accounts
        if ($user->role === 'admin') {
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            Cache::put('admin_update_2fa_' . $user->id, $code, now()->addMinutes(10));
            
            session(['admin_update_data_' . $user->id => [
                'validated' => $validated,
                'password' => $passwordUpdate,
            ]]);

            Mail::to($user->email)->send(new AdminUpdateVerificationMail($code, auth()->user()->name));

            return redirect()->route('admin.users.verify-update', $user->id)
                ->with('info', 'A verification code has been sent to ' . $user->email . '. Entering the code is required to apply the updates.');
        }

        // Direct update for non-admin accounts
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        if ($passwordUpdate) {
            $user->update([
                'password' => $passwordUpdate,
            ]);
        }

        return redirect()->route('admin.users.show', $user->id)->with('success', 'User updated successfully.');
    }

    /**
     * Show the verification form to finalize an Admin update
     */
    public function showVerifyUpdate(User $user)
    {
        // Only allow access if an update session exists
        if (!session()->has('admin_update_data_' . $user->id)) {
            return redirect()->route('admin.users.index')->with('error', 'No pending update found for this user.');
        }

        return view('admin.user-management.verify-update', compact('user'));
    }

    /**
     * Confirm the verification code and apply the Admin update
     */
    public function confirmUpdate(Request $request, User $user)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $cachedCode = Cache::get('admin_update_2fa_' . $user->id);

        if (!$cachedCode || $cachedCode !== $request->code) {
            return back()->withErrors(['code' => 'The verification code is invalid or has expired. Please request a new one by making the update again.']);
        }

        $sessionData = session('admin_update_data_' . $user->id);
        
        if (!$sessionData) {
            return redirect()->route('admin.users.index')->with('error', 'Session expired. Please start the update again.');
        }

        $validated = $sessionData['validated'];
        $passwordUpdate = $sessionData['password'];

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        if ($passwordUpdate) {
            $user->update([
                'password' => $passwordUpdate,
            ]);
        }

        // Clear verification data
        Cache::forget('admin_update_2fa_' . $user->id);
        session()->forget('admin_update_data_' . $user->id);

        return redirect()->route('admin.users.show', $user->id)->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting oneself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Intercept deletes directed at Administrator accounts
        if ($user->role === 'admin') {
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            Cache::put('admin_delete_2fa_' . $user->id, $code, now()->addMinutes(10));
            
            session(['admin_delete_data_' . $user->id => true]);

            Mail::to($user->email)->send(new AdminDeleteVerificationMail($code, auth()->user()->name));

            return redirect()->route('admin.users.verify-delete', $user)
                ->with('info', 'A verification code has been sent to ' . $user->email . '. Entering the code is required to authorize the deletion of this account.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Show email verification step before deleting an administrator account.
     */
    public function showVerifyDelete(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        if ($user->role !== 'admin') {
            return redirect()->route('admin.users.show', $user)
                ->with('error', 'Email verification for deletion only applies to administrator accounts.');
        }

        if (! session()->has('admin_delete_data_' . $user->id)) {
            return redirect()->route('admin.users.show', $user)
                ->with('error', 'No pending deletion. Open the profile and choose Delete Account again.');
        }

        return view('admin.user-management.verify-delete', compact('user'));
    }

    /**
     * Confirm code and permanently delete an administrator account.
     */
    public function confirmDelete(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        if ($user->role !== 'admin') {
            return redirect()->route('admin.users.show', $user)->with('error', 'Invalid deletion request.');
        }

        if (! session()->has('admin_delete_data_' . $user->id)) {
            return redirect()->route('admin.users.show', $user)
                ->with('error', 'No pending deletion. Please start again from the user profile.');
        }

        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $cachedCode = Cache::get('admin_delete_2fa_' . $user->id);

        if (! $cachedCode || $cachedCode !== $request->code) {
            return back()->withErrors(['code' => 'The verification code is invalid or has expired. Request a new code from the user profile.']);
        }

        Cache::forget('admin_delete_2fa_' . $user->id);
        session()->forget('admin_delete_data_' . $user->id);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Administrator account deleted successfully.');
    }

    /**
     * Reset seller onboarding completion state.
     */
    public function resetOnboarding(User $user)
    {
        if ($user->role !== 'seller' || !$user->enterprise) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Onboarding reset is only available for seller accounts with an enterprise profile.');
        }

        $user->enterprise->update([
            'onboarding_tour_completed' => false,
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Seller onboarding tour has been reset.');
    }
}
