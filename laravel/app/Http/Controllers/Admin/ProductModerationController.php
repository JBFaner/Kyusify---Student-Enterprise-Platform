<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductModerationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = $request->query('query');

        $products = Product::with(['enterprise', 'category'])
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhereHas('enterprise', function ($q) use ($query) {
                      $q->where('name', 'like', "%{$query}%");
                  });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.content.moderation.index', compact('products', 'status', 'query'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,hidden,rejected',
        ]);

        $product->update([
            'status' => $request->status,
        ]);

        return back()->with('success', "Product status updated to {$request->status}.");
    }
}
