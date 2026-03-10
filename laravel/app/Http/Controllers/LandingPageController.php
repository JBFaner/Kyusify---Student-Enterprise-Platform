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
        
        // Active Categories ordered by display_order
        $categories = \App\Models\Category::where('status', true)->orderBy('display_order')->get();

        // Featured Products
        $featuredProducts = Product::where('status', 'approved')
            ->where('is_featured', true)
            ->whereHas('enterprise', function($query) {
                $query->where('status', 'approved');
            })
            ->with(['enterprise', 'category'])
            ->orderBy('featured_order')
            ->get();

        // Base Product Query
        $query = Product::where('status', 'approved')
            ->whereHas('enterprise', function($query) {
                $query->where('status', 'approved');
            })
            ->with(['enterprise', 'category']);

        // Filter by Category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category); // Assuming category relation is by ID now
        }

        // Search text
        if ($request->has('query') && $request->query('query') != '') {
            $search = $request->query('query');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('enterprise', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sort = $settings['discover_default_sort'] ?? 'newest';
        if ($sort === 'newest') {
            $query->latest();
        } elseif ($sort === 'popular') {
            // Dummy implementation for popular - just random or by stock for now
            $query->orderBy('stock', 'desc');
        } elseif ($sort === 'bestselling') {
            // Dummy implementation for bestselling - just price descending for now
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        // Pagination
        $perPage = (int) ($settings['discover_items_per_page'] ?? 12);
        $enablePagination = ($settings['discover_enable_pagination'] ?? '1') === '1';

        if ($enablePagination) {
            $products = $query->paginate($perPage)->withQueryString();
        } else {
            // If pagination is disabled, we'll just get a chunk or all (limited to a reasonable amount)
            $products = $query->take(50)->get();
            // To maintain compatibility with views that might use $products->links(), 
            // returning a Collection doesn't have links(). We'll just handle it in the view.
        }

        return view('discover', compact('settings', 'products', 'categories', 'featuredProducts'));
    }
}
