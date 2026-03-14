<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Eager load product and its enterprise (store)
        $cartItems = $user->cartItems()->with('product.enterprise')->get();

        // Group by store name
        $groupedCart = $cartItems->groupBy(function($item) {
            return $item->product->enterprise->name;
        });

        return view('cart.index', compact('groupedCart'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $user = Auth::user();

        // Check if item already exists in cart
        /** @var \App\Models\CartItem|null $cartItem */
        $cartItem = $user->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem !== null) {
            // Update quantity, ensuring it doesn't exceed stock
            $newQuantity = min($cartItem->quantity + $validated['quantity'], $product->stock);
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new cart item
            $user->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        // Ensure user owns this item
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock,
        ]);

        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json(['success' => true, 'message' => 'Cart updated']);
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
