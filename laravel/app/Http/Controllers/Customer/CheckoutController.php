<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $selectedIds = $request->query('items', []);
        
        if (empty($selectedIds)) {
            return redirect()->route('cart.index')->with('error', 'No items selected for checkout.');
        }

        // Fetch selected cart items
        $cartItems = CartItem::where('user_id', auth()->id())
            ->whereIn('id', $selectedIds)
            ->with(['product.enterprise'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Selected items are no longer available.');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $total = $subtotal; // Add shipping/tax logic here if needed

        return view('checkout.index', compact('cartItems', 'subtotal', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:cart_items,id',
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'contact_number' => 'required|string|max:50',
            'payment_method' => 'required|string',
        ]);

        $cartItems = CartItem::where('user_id', auth()->id())
            ->whereIn('id', $validated['items'])
            ->with(['product'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items found for checkout.');
        }

        // Group items by enterprise
        $groupedItems = $cartItems->groupBy(function($item) {
            return $item->product->enterprise_id;
        });

        DB::beginTransaction();

        try {
            foreach ($groupedItems as $enterpriseId => $items) {
                // Calculate enterprise order total
                $enterpriseTotal = $items->sum(function ($item) {
                    return $item->quantity * $item->product->price;
                });

                // Create Order
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'enterprise_id' => $enterpriseId,
                    'status' => 'pending',
                    'shipping_name' => $validated['shipping_name'],
                    'shipping_address' => $validated['shipping_address'],
                    'contact_number' => $validated['contact_number'],
                    'payment_method' => $validated['payment_method'],
                    'total_amount' => $enterpriseTotal,
                ]);

                // Create Order Items
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->product->price,
                        'subtotal' => $item->quantity * $item->product->price,
                    ]);
                    
                    // Delete cart item after processing
                    $item->delete();
                }
            }

            DB::commit();

            return redirect()->route('cart.index')->with('success', 'Your order was successfully placed! Transaction complete.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while processing your checkout: ' . $e->getMessage())->withInput();
        }
    }
}
