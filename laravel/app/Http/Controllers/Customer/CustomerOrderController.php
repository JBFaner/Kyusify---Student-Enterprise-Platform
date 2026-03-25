<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items.product.enterprise', 'enterprise'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.purchases.index', compact('orders'));
    }
}
