<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
                cloudinary()->destroy($this->getCloudinaryPublicId($enterprise->logo_path));
            }
            $uploaded = cloudinary()->upload($request->file('logo')->getRealPath(), [
                'folder' => 'kyusify/logos',
            ]);
            $validated['logo_path'] = $uploaded->getSecurePath();
        }

        if ($request->hasFile('document')) {
            if ($enterprise->document_path && str_starts_with($enterprise->document_path, 'http')) {
                cloudinary()->destroy($this->getCloudinaryPublicId($enterprise->document_path));
            }
            $uploaded = cloudinary()->upload($request->file('document')->getRealPath(), [
                'folder'        => 'kyusify/documents',
                'resource_type' => 'auto', // handles PDFs as well as images
            ]);
            $validated['document_path'] = $uploaded->getSecurePath();

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
                cloudinary()->destroy($this->getCloudinaryPublicId($enterprise->store_branding));
            }
            $uploaded = cloudinary()->upload($request->file('store_branding')->getRealPath(), [
                'folder' => 'kyusify/branding',
            ]);
            $validated['store_branding'] = $uploaded->getSecurePath();
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
