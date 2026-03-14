<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $enterprise = auth()->user()->enterprise;
        
        if (!$enterprise) {
            return redirect()->route('seller.profile.edit')->with('error', 'Please set up your store profile first.');
        }

        // Dashboard Statistics
        $listedProducts = Product::where('enterprise_id', $enterprise->id)->count();
        $pendingOrders = Order::where('enterprise_id', $enterprise->id)->where('status', 'pending')->count();
        $totalRevenue = Order::where('enterprise_id', $enterprise->id)->whereIn('status', ['completed'])->sum('total_amount');
        
        // Unread/Inquiries could be mapped to messages later, leaving as 0 for now
        $customerInquiries = 0; 
        
        // Store Rating
        $averageRating = Review::whereHas('product', function($q) use ($enterprise) {
            $q->where('enterprise_id', $enterprise->id);
        })->avg('rating') ?? 0;

        // Recent Orders
        $recentOrders = Order::where('enterprise_id', $enterprise->id)
            ->with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard.index', compact(
            'listedProducts', 
            'pendingOrders', 
            'totalRevenue', 
            'customerInquiries', 
            'averageRating', 
            'recentOrders'
        ));
    }
}
