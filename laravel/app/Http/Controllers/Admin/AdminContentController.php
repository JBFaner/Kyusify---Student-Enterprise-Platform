<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminContentController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.content.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string',
            'about_text' => 'nullable|string',
            'homepage_announcement' => 'nullable|string',
            'homepage_banner_cta_text' => 'nullable|string|max:255',
            'homepage_banner_cta_link' => 'nullable|string|max:255',
            'homepage_banner_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'hero_title', 'hero_subtitle', 'about_text',
            'homepage_announcement', 'homepage_banner_cta_text', 'homepage_banner_cta_link'
        ]);

        if ($request->hasFile('homepage_banner_image')) {
            $data['homepage_banner_image'] = $request->file('homepage_banner_image')->store('banners', 'public');
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.content.index')->with('success', 'Landing page content updated successfully.');
    }

    public function removeBanner()
    {
        $setting = Setting::where('key', 'homepage_banner_image')->first();
        if ($setting) {
            if ($setting->value) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($setting->value);
            }
            $setting->delete();
        }

        return redirect()->route('admin.content.index')->with('success', 'Promotional banner image removed.');
    }

    public function discoverySettings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.content.discovery', compact('settings'));
    }

    public function updateDiscoverySettings(Request $request)
    {
        $request->validate([
            'discover_items_per_page' => 'required|integer|min:4|max:100',
            'discover_default_sort' => 'required|string|in:newest,popular,bestselling',
            'discover_enable_pagination' => 'boolean',
        ]);

        $validated = $request->except(['_token', '_method']);
        $validated['discover_enable_pagination'] = $request->has('discover_enable_pagination') ? '1' : '0';

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.content.discovery')->with('success', 'Discovery settings updated successfully.');
    }
}
