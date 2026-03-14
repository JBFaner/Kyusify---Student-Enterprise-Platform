<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicStoreController extends Controller
{
    public function show($id)
    {
        // Load the approved enterprise
        $store = Enterprise::where('status', 'approved')->findOrFail($id);

        $settings = Setting::all()->pluck('value', 'key');
        
        // Active Categories ordered by display_order
        $categories = \App\Models\Category::where('status', true)->orderBy('display_order')->get();

        // Paginate products belonging to this store
        $products = $store->products()
            ->where('status', 'approved')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('store.show', compact('store', 'products', 'settings', 'categories'));
    }
}
