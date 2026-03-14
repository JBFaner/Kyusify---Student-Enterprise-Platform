<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            if ($enterprise->logo_path) {
                Storage::disk('public')->delete($enterprise->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('enterprises/logos', 'public');
        }

        if ($request->hasFile('document')) {
            if ($enterprise->document_path) {
                Storage::disk('public')->delete($enterprise->document_path);
            }
            $validated['document_path'] = $request->file('document')->store('enterprises/documents', 'public');
        }

        if ($request->hasFile('store_branding')) {
            if ($enterprise->store_branding) {
                Storage::disk('public')->delete($enterprise->store_branding);
            }
            $validated['store_branding'] = $request->file('store_branding')->store('enterprises/branding', 'public');
        }

        // Remove file helper keys before updating database
        unset($validated['logo']);
        unset($validated['document']);

        $enterprise->update($validated);

        return redirect()->route('seller.profile.index')->with('success', 'Store profile updated successfully.');
    }
}
