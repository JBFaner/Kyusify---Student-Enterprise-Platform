<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $customerInquiries = 0;

        // Store Rating
        $averageRating = Review::whereHas('product', function($q) use ($enterprise) {
            $q->where('enterprise_id', $enterprise->id);
        })->avg('rating') ?? 0;

        $totalReviews = Review::whereHas('product', function($q) use ($enterprise) {
            $q->where('enterprise_id', $enterprise->id);
        })->count();

        // Recent Orders
        $recentOrders = Order::where('enterprise_id', $enterprise->id)
            ->with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // ── Chart: Orders Over Time (last 6 months) ──────────────────
        $ordersOverTime = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Order::where('enterprise_id', $enterprise->id)
                          ->whereYear('created_at', $month->year)
                          ->whereMonth('created_at', $month->month)
                          ->count();
            $revenue = Order::where('enterprise_id', $enterprise->id)
                            ->whereYear('created_at', $month->year)
                            ->whereMonth('created_at', $month->month)
                            ->where('status', 'completed')
                            ->sum('total_amount');
            $ordersOverTime->push([
                'label'   => $month->format('M Y'),
                'orders'  => $count,
                'revenue' => (float) $revenue,
            ]);
        }

        // ── Chart: Order Status Breakdown ────────────────────────────
        $orderStatuses = Order::where('enterprise_id', $enterprise->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('seller.dashboard.index', compact(
            'listedProducts', 
            'pendingOrders', 
            'totalRevenue', 
            'customerInquiries', 
            'averageRating',
            'totalReviews',
            'recentOrders',
            'ordersOverTime',
            'orderStatuses'
        ));
    }
}
