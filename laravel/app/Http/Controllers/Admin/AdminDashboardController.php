<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enterprise;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ── Stats Cards ──────────────────────────────────────────────
        $totalSellers   = User::where('role', 'seller')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts  = Product::count();
        $totalEnterprises = Enterprise::count();
        $totalOrders    = Order::count();
        $totalRevenue   = Order::where('status', 'completed')->sum('total_amount');
        $pendingOrders  = Order::where('status', 'pending')->count();

        // ── Orders Over Time (last 6 months) ─────────────────────────
        $ordersOverTime = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Order::whereYear('created_at', $month->year)
                          ->whereMonth('created_at', $month->month)
                          ->count();
            $revenue = Order::whereYear('created_at', $month->year)
                            ->whereMonth('created_at', $month->month)
                            ->where('status', 'completed')
                            ->sum('total_amount');
            $ordersOverTime->push([
                'label'   => $month->format('M Y'),
                'orders'  => $count,
                'revenue' => (float) $revenue,
            ]);
        }

        // ── Order Status Breakdown ────────────────────────────────────
        $orderStatuses = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // ── Top 5 Products by Orders ──────────────────────────────────
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get()
            ->map(fn($i) => [
                'name'  => $i->product ? $i->product->name : 'Unknown',
                'count' => (int) $i->total_sold,
            ]);

        // ── Recent Orders ─────────────────────────────────────────────
        $recentOrders = Order::with(['user:id,name,email', 'enterprise:id,name'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalSellers', 'totalCustomers', 'totalProducts', 'totalEnterprises',
            'totalOrders', 'totalRevenue', 'pendingOrders',
            'ordersOverTime', 'orderStatuses', 'topProducts', 'recentOrders'
        ));
    }
}
