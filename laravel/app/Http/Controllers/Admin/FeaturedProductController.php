<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FeaturedProductController extends Controller
{
    public function index(Request $request)
    {
        $featuredProducts = Product::where('is_featured', true)
                                   ->orderBy('featured_order')
                                   ->with('enterprise')
                                   ->get();

        $searchQuery = $request->query('query');
        $searchResults = collect();

        if ($searchQuery) {
            $searchResults = Product::where('is_featured', false)
                ->where(function ($q) use ($searchQuery) {
                    $q->where('name', 'like', "%{$searchQuery}%")
                      ->orWhereHas('enterprise', function ($q) use ($searchQuery) {
                          $q->where('name', 'like', "%{$searchQuery}%");
                      });
                })
                ->with('enterprise')
                ->take(10)
                ->get();
        }

        return view('admin.content.featured.index', compact('featuredProducts', 'searchResults', 'searchQuery'));
    }

    public function store(Request $request, Product $product)
    {
        $product->update([
            'is_featured' => true,
            'featured_order' => Product::where('is_featured', true)->max('featured_order') + 1,
        ]);

        return redirect()->route('admin.content.featured.index')->with('success', 'Product added to featured list.');
    }

    public function destroy(Product $product)
    {
        $product->update([
            'is_featured' => false,
            'featured_order' => 0,
        ]);

        return redirect()->route('admin.content.featured.index')->with('success', 'Product removed from featured list.');
    }

    public function reorder(Request $request)
    {
        $orderedIds = $request->input('ordered_ids', []);
        
        foreach ($orderedIds as $index => $id) {
            Product::where('id', $id)->where('is_featured', true)->update(['featured_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
