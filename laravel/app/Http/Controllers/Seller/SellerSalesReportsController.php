<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerSalesReportsController extends Controller
{
    public function index(Request $request)
    {
        $enterprise = auth()->user()->enterprise;
        if (!$enterprise) {
            return redirect()->route('seller.profile.edit')->with('error', 'Please set up your business profile first.');
        }

        $enterpriseId = $enterprise->id;

        // ── Base query helper ─────────────────────────────────
        $base = fn() => Order::where('enterprise_id', $enterpriseId);

        // ── 1. Summary Cards ──────────────────────────────────
        $totalRevenue   = (clone $base())->whereIn('status', ['completed','processing','ready'])->sum('total_amount');
        $totalOrders    = (clone $base())->count();
        $totalCompleted = (clone $base())->where('status', 'completed')->count();
        $totalPending   = (clone $base())->whereIn('status', ['pending','processing'])->count();

        // Products sold (sum of items for this enterprise)
        $productsSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.enterprise_id', $enterpriseId)
            ->sum('order_items.quantity');

        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

        // ── 2. Revenue Over Time (6 months) ───────────────────
        $revenueOverTime = [];
        for ($i = 5; $i >= 0; $i--) {
            $month   = Carbon::now()->subMonths($i);
            $revenue = (clone $base())->whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->whereIn('status', ['completed'])->sum('total_amount');
            $orders  = (clone $base())->whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count();
            $revenueOverTime[] = ['label' => $month->format('M Y'), 'revenue' => (float)$revenue, 'orders' => $orders];
        }

        // ── 3. Order Status Distribution ──────────────────────
        $orderStatuses = [
            'pending'    => (clone $base())->where('status','pending')->count(),
            'processing' => (clone $base())->where('status','processing')->count(),
            'ready'      => (clone $base())->where('status','ready')->count(),
            'completed'  => (clone $base())->where('status','completed')->count(),
            'cancelled'  => (clone $base())->where('status','cancelled')->count(),
        ];

        // ── 4. Top Products by revenue ────────────────────────
        $topProducts = DB::table('order_items')
            ->join('orders',   'order_items.order_id',   '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.enterprise_id', $enterpriseId)
            ->select('products.name', DB::raw('SUM(order_items.quantity) as units'), DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('products.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get()
            ->map(fn($p) => ['name' => $p->name, 'units' => (int)$p->units, 'revenue' => (float)$p->revenue])
            ->values()
            ->toArray();

        // ── 5. Orders Report Table (filterable) ───────────────
        $dateFrom   = $request->query('date_from');
        $dateTo     = $request->query('date_to');
        $statusFilter = $request->query('status', '');

        $ordersQuery = (clone $base())->with(['user','items.product'])->orderBy('created_at','desc');
        if ($dateFrom) $ordersQuery->whereDate('created_at', '>=', $dateFrom);
        if ($dateTo)   $ordersQuery->whereDate('created_at', '<=', $dateTo);
        if ($statusFilter) $ordersQuery->where('status', $statusFilter);

        $ordersReport = $ordersQuery->get()->flatMap(function($order) {
            return $order->items->map(fn($item) => (object)[
                'order_id'      => '#'.str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'product_name'  => $item->product ? $item->product->name : 'Unknown',
                'customer_name' => $order->user ? $order->user->name : 'Guest',
                'quantity'      => $item->quantity,
                'total_price'   => $item->subtotal,
                'status'        => $order->status,
                'date'          => $order->created_at->format('M d, Y'),
            ]);
        });

        // ── 6. Product Sales Report ────────────────────────────
        $productSalesReport = Product::where('enterprise_id', $enterpriseId)
            ->with(['reviews', 'orderItems'])
            ->get()
            ->map(fn($p) => (object)[
                'name'          => $p->name,
                'orders'        => $p->orderItems->count(),
                'quantity_sold' => $p->orderItems->sum('quantity'),
                'revenue'       => $p->orderItems->sum('subtotal'),
                'rating'        => $p->reviews->count() > 0 ? number_format($p->reviews->avg('rating'), 1) : 'N/A',
            ])
            ->sortByDesc('revenue')
            ->values();

        // ── 7. Monthly Sales Report ────────────────────────────
        $monthlySales = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $mOrders   = (clone $base())->whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count();
            $mProducts = DB::table('order_items')
                ->join('orders','order_items.order_id','=','orders.id')
                ->where('orders.enterprise_id', $enterpriseId)
                ->whereYear('orders.created_at', $month->year)
                ->whereMonth('orders.created_at', $month->month)
                ->sum('order_items.quantity');
            $mRevenue = (clone $base())->whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->whereIn('status', ['completed'])->sum('total_amount');
            $monthlySales->push((object)[
                'month'         => $month->format('F Y'),
                'orders'        => $mOrders,
                'products_sold' => (int)$mProducts,
                'revenue'       => (float)$mRevenue,
            ]);
        }

        return view('seller.sales-reports.index', compact(
            'totalRevenue','totalOrders','totalCompleted','totalPending','productsSold','avgOrderValue',
            'revenueOverTime','orderStatuses','topProducts',
            'ordersReport','productSalesReport','monthlySales',
            'dateFrom','dateTo','statusFilter'
        ));
    }
}
