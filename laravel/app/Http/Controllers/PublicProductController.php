<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with(['enterprise', 'reviews.user'])->where('status', 'approved')->findOrFail($id);

        // Ensure the enterprise is also approved
        if ($product->enterprise->status !== 'approved') {
            abort(404, 'Product not found.');
        }

        $settings = Setting::all()->pluck('value', 'key');
        
        // Active Categories ordered by display_order
        $categories = \App\Models\Category::where('status', true)->orderBy('display_order')->get();

        return view('product.show', compact('product', 'settings', 'categories'));
    }
}
