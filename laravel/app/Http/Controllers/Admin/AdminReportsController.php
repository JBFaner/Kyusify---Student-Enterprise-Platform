<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminReportsController extends Controller
{
    public function index(Request $request)
    {
        // 1. Overview Dashboard Highlights
        $totalSales    = Order::where('status', 'completed')->sum('total_amount');
        $totalOrders   = Order::count();
        $totalRevenue  = Order::whereIn('status', ['completed', 'processing', 'ready'])->sum('total_amount');
        $activeSellers = Enterprise::where('status', 'active')->count();
        $newCustomers  = User::where('role', 'customer')
                             ->where('created_at', '>=', Carbon::now()->subDays(30))
                             ->count();
        $pendingOrders = Order::whereIn('status', ['pending', 'processing'])->count();

        // Orders over time (last 6 months)
        $ordersOverTime = [];
        for ($i = 5; $i >= 0; $i--) {
            $month   = Carbon::now()->subMonths($i);
            $orders  = Order::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count();
            $revenue = Order::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->where('status', 'completed')->sum('total_amount');
            $ordersOverTime[] = ['label' => $month->format('M Y'), 'orders' => $orders, 'revenue' => (float)$revenue];
        }

        // Top products by units sold — use 'units' field to avoid amCharts name collision
        $topProductsRaw = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as units'))
            ->groupBy('products.name')
            ->orderByDesc('units')
            ->limit(5)
            ->get();
        // Convert to array for JS
        $topProducts = $topProductsRaw->map(fn($p) => ['name' => $p->name, 'units' => (int)$p->units])->values()->toArray();

        // Order statuses for donut chart
        $orderStatuses = [
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'ready'      => Order::where('status', 'ready')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        // 2. Sales Reports Data — date range filter
        $dateFrom = $request->query('date_from');
        $dateTo   = $request->query('date_to');

        $salesQuery = Order::with('items')->orderBy('created_at', 'desc');
        if ($dateFrom) {
            $salesQuery->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $salesQuery->whereDate('created_at', '<=', $dateTo);
        }

        $allOrders = $salesQuery->get()->map(function ($order) {
            return [
                'date'         => $order->created_at->format('M d, Y'),
                'revenue'      => (float)$order->total_amount,
                'products_sold'=> $order->items->sum('quantity'),
            ];
        });

        $salesByDate = collect($allOrders)->groupBy('date')->map(function ($rows) {
            return [
                'date'          => $rows->first()['date'],
                'orders'        => $rows->count(),
                'products_sold' => $rows->sum('products_sold'),
                'revenue'       => $rows->sum('revenue'),
            ];
        })->values();

        // 3. Products Reports Data
        $productsReport = Product::with(['enterprise', 'reviews', 'orderItems'])->get()->map(function ($product) {
            return (object) [
                'name'        => $product->name,
                'seller_name' => $product->enterprise ? $product->enterprise->name : 'N/A',
                'orders'      => $product->orderItems->sum('quantity'),
                'revenue'     => $product->orderItems->sum('subtotal'),
                'rating'      => $product->reviews->avg('rating') ? number_format($product->reviews->avg('rating'), 1) : '0',
                'status'      => $product->status,
            ];
        })->sortByDesc('orders')->values();

        // 4. Seller Reports Data
        $sellersReport = Enterprise::with(['products.reviews', 'orders'])->get()->map(function ($seller) {
            $revenue    = $seller->orders->where('status', 'completed')->sum('total_amount');
            $allReviews = $seller->products->flatMap(fn($p) => $p->reviews);
            $avgRating  = $allReviews->count() > 0 ? $allReviews->avg('rating') : 0;
            return (object) [
                'name'           => $seller->name,
                'products_count' => $seller->products->count(),
                'orders_count'   => $seller->orders->count(),
                'revenue'        => $revenue,
                'rating'         => number_format($avgRating, 1),
                'status'         => $seller->status,
            ];
        })->sortByDesc('orders_count')->values();

        // 5. Customers Report Data — total_spent counts all orders (not just completed) so it shows real activity
        $customersReport = User::where('role', 'customer')->with('orders')->get()->map(function ($customer) {
            return (object) [
                'name'          => $customer->name,
                'orders_count'  => $customer->orders->count(),
                'total_spent'   => $customer->orders->sum('total_amount'), // all orders, not just completed
                'last_activity' => $customer->orders->count() > 0
                                    ? $customer->orders->sortByDesc('created_at')->first()->created_at->format('M d, Y')
                                    : $customer->updated_at->format('M d, Y'),
            ];
        })->sortByDesc('orders_count')->values();

        // 6. Transaction Logs Data
        $transactionLogs = Order::with('user')->orderBy('created_at', 'desc')->take(100)->get()->map(function ($order) {
            return (object) [
                'log_id'   => 'TXN-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'user'     => $order->user ? $order->user->name : 'Guest User',
                'action'   => 'Order Placed',
                'order_id' => '#' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'amount'   => $order->total_amount,
                'date'     => $order->created_at->format('M d, Y g:i A'),
            ];
        });

        // 7. Activity Logs Data (Simulated from real DB events)
        $activityLogs = collect();

        foreach (Order::with('user')->orderBy('created_at', 'desc')->take(30)->get() as $order) {
            $activityLogs->push((object)[
                'log_id'        => 'ACT-ORD-' . $order->id,
                'user'          => $order->user ? $order->user->name : 'System',
                'action'        => 'Placed an order (#' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . ')',
                'module'        => 'Checkout',
                'date'          => $order->created_at->format('M d, Y g:i A'),
                'raw_timestamp' => $order->created_at->timestamp,
            ]);
        }

        foreach (Product::with('enterprise.user')->orderBy('created_at', 'desc')->take(30)->get() as $product) {
            $userName = ($product->enterprise && $product->enterprise->user) ? $product->enterprise->user->name : 'Seller';
            $activityLogs->push((object)[
                'log_id'        => 'ACT-PRD-' . $product->id,
                'user'          => $userName,
                'action'        => 'Added product: ' . $product->name,
                'module'        => 'Products',
                'date'          => $product->created_at->format('M d, Y g:i A'),
                'raw_timestamp' => $product->created_at->timestamp,
            ]);
        }

        foreach (User::orderBy('created_at', 'desc')->take(30)->get() as $user) {
            $activityLogs->push((object)[
                'log_id'        => 'ACT-USR-' . $user->id,
                'user'          => $user->name,
                'action'        => 'Registered (' . ucfirst($user->role) . ')',
                'module'        => 'Authentication',
                'date'          => $user->created_at->format('M d, Y g:i A'),
                'raw_timestamp' => $user->created_at->timestamp,
            ]);
        }

        $activityLogs = $activityLogs->sortByDesc('raw_timestamp')->take(100)->values();

        return view('admin.reports-logs.index', compact(
            'totalSales', 'totalOrders', 'totalRevenue', 'activeSellers', 'newCustomers', 'pendingOrders',
            'ordersOverTime', 'topProducts', 'orderStatuses',
            'salesByDate', 'dateFrom', 'dateTo',
            'productsReport', 'sellersReport', 'customersReport',
            'transactionLogs', 'activityLogs'
        ));
    }
}
