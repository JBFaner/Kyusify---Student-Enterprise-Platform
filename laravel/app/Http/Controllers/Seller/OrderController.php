<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
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

        // Latest conversations for this enterprise — shown in chat panel
        $recentConversations = Conversation::where('enterprise_id', $enterpriseId)
            ->with(['customer', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->take(6)
            ->get();

        return view('seller.orders.show', compact('order', 'recentConversations'));
    }

    public function update(Request $request, Order $order)
    {
        $enterpriseId = auth()->user()->enterprise->id ?? null;
        if ($order->enterprise_id !== $enterpriseId) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled',
        ]);

        $newStatus = $validated['status'];
        $oldStatus = $order->status;

        // Stock management:
        // When an order moves TO cancelled FROM a non-cancelled status → RESTORE stock
        // When an order moves FROM cancelled TO a non-cancelled status → DECREASE stock (undo restore)
        // When a fresh order becomes processing/ready/completed from pending → DECREASE stock ONCE
        // Guard: only decrease once (when leaving 'pending')
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            // Restore stock for all items
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            // Someone un-cancels — decrease stock again
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }
        } elseif ($oldStatus === 'pending' && in_array($newStatus, ['processing', 'ready', 'completed'])) {
            // First real status progression — decrease stock
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }
        }

        $order->load('items.product'); // Reload after potential stock changes
        $order->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Order status updated to ' . ucfirst($newStatus) . '.');
    }
}
