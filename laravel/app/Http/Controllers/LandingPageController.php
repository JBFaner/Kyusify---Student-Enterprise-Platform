<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;
use App\Models\Product;

class LandingPageController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('welcome', compact('settings'));
    }

    public function discover(Request $request)
    {
        $settings = Setting::all()->pluck('value', 'key');
        
        $query = Product::where('status', 'active')
            ->whereHas('enterprise', function($query) {
                $query->where('status', 'approved');
            });

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $products = $query->with('enterprise')->latest()->get();
        
        // Get unique categories for the filter
        $categories = Product::whereNotNull('category')->distinct()->pluck('category');

        return view('discover', compact('settings', 'products', 'categories'));
    }
}
