<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $enterprise = auth()->user()->enterprise;
        if (!$enterprise) {
            return redirect()->route('seller.profile.edit')->with('error', 'Please set up your business profile first.');
        }

        $query = Order::where('enterprise_id', $enterprise->id)->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Assuming searching by Order ID or Customer Name
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $enterpriseId = auth()->user()->enterprise->id ?? null;
        if ($order->enterprise_id !== $enterpriseId) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['user', 'items.product']);

        return view('seller.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $enterpriseId = auth()->user()->enterprise->id ?? null;
        if ($order->enterprise_id !== $enterpriseId) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled'
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Order status updated to ' . ucfirst($validated['status']) . '.');
    }
}
