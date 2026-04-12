<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessProfileController extends Controller
{
    public function index()
    {
        $enterprise = auth()->user()->enterprise;

        if (!$enterprise) {
            return redirect()->route('seller.profile.edit')
                ->with('info', 'Create your store profile to get started.');
        }

        return view('seller.profile.index', compact('enterprise'));
    }

    public function edit()
    {
        $enterprise = auth()->user()->enterprise;

        if (!$enterprise) {
            return view('seller.profile.setup-enterprise');
        }

        return view('seller.profile.edit', compact('enterprise'));
    }

    /**
     * Create an enterprise for sellers who have the seller role but no store record yet
     * (e.g. legacy accounts or admin-created users).
     */
    public function storeEnterprise(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'seller') {
            abort(403);
        }

        if ($user->enterprise) {
            return redirect()->route('seller.profile.edit');
        }

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
        ]);

        $enterprise = Enterprise::create([
            'user_id' => $user->id,
            'name' => $validated['business_name'],
            'status' => 'pending',
            'is_student_verified' => false,
        ]);

        NotificationHelper::notifyAdmins(
            'new_seller',
            'New Seller Registered',
            "{$user->name} created enterprise \"{$enterprise->name}\" and is awaiting verification.",
            route('admin.enterprises.index'),
            'bell'
        );

        return redirect()->route('seller.profile.edit')
            ->with('success', 'Store created. Complete your profile details below.');
    }

    public function update(Request $request)
    {
        $enterprise = auth()->user()->enterprise;

        $validated = $request->validate([
            'description' => 'required|string|max:1000',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'logo' => 'nullable|image|max:2048', // Max 2MB
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
            'store_branding' => 'nullable|image|max:5120', // Max 5MB for branding
        ]);

        if ($request->hasFile('logo')) {
            if ($enterprise->logo_path && str_starts_with($enterprise->logo_path, 'http')) {
                Storage::disk('cloudinary')->delete($this->getCloudinaryPublicId($enterprise->logo_path));
            }
            $path = $request->file('logo')->store('kyusify/logos', 'cloudinary');
            $validated['logo_path'] = Storage::disk('cloudinary')->url($path);
        }

        if ($request->hasFile('document')) {
            if ($enterprise->document_path && str_starts_with($enterprise->document_path, 'http')) {
                Storage::disk('cloudinary')->delete($this->getCloudinaryPublicId($enterprise->document_path));
            }
            $path = $request->file('document')->store('kyusify/documents', 'cloudinary');
            $validated['document_path'] = Storage::disk('cloudinary')->url($path);

            // Notify admins that a new verification document was uploaded
            NotificationHelper::notifyAdmins(
                'document_upload',
                'Student Verification Uploaded',
                "{$enterprise->name} has uploaded a new student verification document.",
                route('admin.enterprises.show', $enterprise->id),
                'user'
            );
        }

        if ($request->hasFile('store_branding')) {
            if ($enterprise->store_branding && str_starts_with($enterprise->store_branding, 'http')) {
                Storage::disk('cloudinary')->delete($this->getCloudinaryPublicId($enterprise->store_branding));
            }
            $path = $request->file('store_branding')->store('kyusify/branding', 'cloudinary');
            $validated['store_branding'] = Storage::disk('cloudinary')->url($path);
        }

        // Remove file helper keys before updating database
        unset($validated['logo']);
        unset($validated['document']);

        $enterprise->update($validated);

        return redirect()->route('seller.profile.index')->with('success', 'Store profile updated successfully.');
    }

    /**
     * Extract the Cloudinary public_id from a secure URL.
     */
    private function getCloudinaryPublicId(string $url): ?string
    {
        if (preg_match('/\/upload\/(?:v\d+\/)?(.+?)(?:\.[a-z]{2,4})?$/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
