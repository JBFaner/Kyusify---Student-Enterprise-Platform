<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $enterprise = auth()->user()->enterprise;
        
        if (!$enterprise) {
            return redirect()->route('seller.dashboard')->with('error', 'Please complete your business profile first.');
        }

        $query = $enterprise->products();

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        if (!auth()->user()->enterprise) {
            return redirect()->route('seller.dashboard')->with('error', 'Please complete your business profile first.');
        }
        $categories = \App\Models\Category::where('status', true)->orderBy('display_order')->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');
        $data['status'] = 'pending';
        
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        auth()->user()->enterprise->products()->create($data);

        return redirect()->route('seller.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        if ($product->enterprise_id !== auth()->user()->enterprise->id) {
            abort(403);
        }
        return view('seller.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if ($product->enterprise_id !== auth()->user()->enterprise->id) {
            abort(403);
        }
        $categories = \App\Models\Category::where('status', true)->orderBy('display_order')->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->enterprise_id !== auth()->user()->enterprise->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', 'status']);
        
        // If the seller toggles the status, we map 'active' to either keep it approved (if it was) or make it pending.
        // If they toggle it off, we set it to 'hidden'.
        if ($request->has('status')) {
            if ($request->status == 'active') {
                $data['status'] = $product->status === 'approved' ? 'approved' : 'pending';
            } else {
                $data['status'] = 'hidden';
            }
        }

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('seller.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->enterprise_id !== auth()->user()->enterprise->id) {
            abort(403);
        }

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Product deleted successfully.');
    }
}
