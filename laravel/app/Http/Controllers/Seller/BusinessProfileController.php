<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\NotificationHelper;

class BusinessProfileController extends Controller
{
    public function index()
    {
        $enterprise = auth()->user()->enterprise;
        
        if (!$enterprise) {
            // Provide a base template object if they literally haven't been created yet,
            // but the registration flow creates it.
            abort(404, 'Business profile not found.');
        }

        return view('seller.profile.index', compact('enterprise'));
    }

    public function edit()
    {
        $enterprise = auth()->user()->enterprise;
        
        if (!$enterprise) {
            abort(404, 'Business profile not found.');
        }

        return view('seller.profile.edit', compact('enterprise'));
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
