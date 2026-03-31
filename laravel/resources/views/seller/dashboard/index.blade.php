<x-seller-layout>
    <x-slot name="header">
        Dashboard Overview
    </x-slot>

    {{-- amCharts 5 CDN --}}
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    {{-- Pass PHP data to JS --}}
    <script>
        const sellerOrdersOverTime = @json($ordersOverTime);
        const sellerOrderStatuses  = @json($orderStatuses);
    </script>

    {{-- Welcome Banner --}}
    @if(Auth::user()->enterprise && Auth::user()->enterprise->status === 'pending')
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-900/50 rounded-2xl p-5 mb-6 flex items-start gap-4">
        <div class="bg-yellow-100 text-yellow-600 dark:bg-yellow-900/50 dark:text-yellow-400 p-3 rounded-xl shrink-0">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-yellow-800 dark:text-yellow-500">Pending Approval</h3>
            <p class="text-yellow-700 dark:text-yellow-600 mt-1 text-sm">Your enterprise is awaiting admin approval. Products are hidden until approved.</p>
        </div>
    </div>
    @endif

    {{-- ── Stat Cards ─────────────────────────────────────────── --}}
    <div data-tour="dashboard-summary-cards" class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        @php
        $cards = [
            ['icon'=>'fa-cubes',        'bg'=>'bg-blue-50 dark:bg-blue-900/20',     'color'=>'text-blue-500',    'badge'=>'+12%',    'badge_color'=>'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400', 'value'=>$listedProducts,              'label'=>'Listed Products'],
            ['icon'=>'fa-bag-shopping', 'bg'=>'bg-violet-50 dark:bg-violet-900/20', 'color'=>'text-violet-500', 'badge'=>'New!',     'badge_color'=>'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',     'value'=>$pendingOrders,               'label'=>'Pending Orders'],
            ['icon'=>'fa-peso-sign',    'bg'=>'bg-emerald-50 dark:bg-emerald-900/20','color'=>'text-emerald-500','badge'=>'Revenue',  'badge_color'=>'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400','value'=>'₱'.number_format($totalRevenue,2),'label'=>'Total Revenue'],
            ['icon'=>'fa-message',      'bg'=>'bg-orange-50 dark:bg-orange-900/20', 'color'=>'text-orange-500', 'badge'=>'0 Unread', 'badge_color'=>'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',    'value'=>$customerInquiries,           'label'=>'Customer Inquiries'],
        ];
        @endphp

        @foreach($cards as $c)
        <div class="bg-white dark:bg-[#13111C] p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm hover:-translate-y-1 transition-transform duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 {{ $c['bg'] }} {{ $c['color'] }} rounded-xl flex items-center justify-center">
                    <i class="fa-solid {{ $c['icon'] }} text-lg"></i>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 {{ $c['badge_color'] }} rounded-full">{{ $c['badge'] }}</span>
            </div>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-1">{{ $c['value'] }}</h3>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $c['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ── Charts Row ─────────────────────────────────────────── --}}
    <div data-tour="dashboard-charts" class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-7">

        {{-- Orders Over Time Area Chart --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Orders Over Time</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Last 6 months — orders &amp; revenue</p>
                </div>
                <span class="w-8 h-8 rounded-xl bg-violet-50 dark:bg-violet-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-chart-area text-violet-500 text-sm"></i>
                </span>
            </div>
            <div id="seller-chart-orders" style="height:220px;"></div>
        </div>

        {{-- Order Status Donut --}}
        <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Order Status</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Breakdown by status</p>
                </div>
                <span class="w-8 h-8 rounded-xl bg-fuchsia-50 dark:bg-fuchsia-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-chart-pie text-fuchsia-500 text-sm"></i>
                </span>
            </div>
            <div id="seller-chart-status" style="height:220px;"></div>
        </div>
    </div>

    {{-- ── Main Grid: Recent Orders + Sidebar ─────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Orders --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/30">
                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-truck-fast text-violet-500"></i> Recent Customer Orders
                </h3>
                <a href="{{ route('seller.orders.index') }}" class="text-sm font-semibold text-violet-600 hover:text-violet-700 dark:text-violet-400">View All</a>
            </div>
            @if($recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50/50 dark:bg-gray-800/30 text-xs uppercase font-bold tracking-wider text-gray-400">
                            <tr>
                                <th class="px-5 py-3 text-left">Order</th>
                                <th class="px-5 py-3 text-left">Customer</th>
                                <th class="px-5 py-3 text-left">Items</th>
                                <th class="px-5 py-3 text-left">Total</th>
                                <th class="px-5 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($recentOrders as $order)
                            @php
                                $badge = match($order->status) {
                                    'pending'    => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                    'ready'      => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                                    'completed'  => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    default      => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 cursor-pointer transition-colors"
                                onclick="window.location='{{ route('seller.orders.show', $order) }}'">
                                <td class="px-5 py-3.5 font-mono text-gray-900 dark:text-white font-semibold">#{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-gray-900 dark:text-white text-sm">{{ $order->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400">{{ $order->items->sum('quantity') }}</td>
                                <td class="px-5 py-3.5 font-mono font-bold text-gray-900 dark:text-white">₱{{ number_format($order->total_amount,2) }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badge }}">{{ ucfirst($order->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-14 h-14 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-box-open text-gray-300 dark:text-gray-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-1">No orders yet</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">List your products to start receiving orders.</p>
                    <a href="{{ route('seller.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold rounded-xl transition-colors">
                        <i class="fa-solid fa-plus"></i> Add Product
                    </a>
                </div>
            @endif
        </div>

        {{-- Sidebar: Notifications + Store Rating --}}
        <div class="flex flex-col gap-5">

            {{-- System Notifications --}}
            <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-bell text-violet-500"></i> System Notifications
                    </h3>
                </div>
                <div class="p-4">
                    <div class="flex items-start gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-800/30 rounded-xl transition-colors">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-500 rounded-lg shrink-0">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Welcome to Kyusify!</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Complete your business profile to verify your QCU student status.</p>
                            <span class="text-[10px] uppercase font-bold text-gray-400 mt-1.5 block">Just now</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Store Rating --}}
            <div class="bg-gradient-to-br from-violet-600 to-violet-900 rounded-2xl border border-violet-500 shadow-lg relative overflow-hidden p-6 text-white">
                <div class="absolute -right-6 -bottom-6 opacity-20">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-xs font-bold tracking-widest uppercase text-violet-200 mb-2">Store Rating</h3>
                    <div class="flex items-end gap-2 mb-3">
                        <span class="text-5xl font-black tracking-tighter">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-lg text-violet-300 pb-1">/ 5</span>
                    </div>
                    <div class="flex gap-1 mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($averageRating))
                                <i class="fa-solid fa-star text-yellow-400"></i>
                            @elseif($i == ceil($averageRating) && fmod($averageRating, 1) > 0)
                                <i class="fa-solid fa-star-half-stroke text-yellow-400"></i>
                            @else
                                <i class="fa-regular fa-star text-violet-400/50"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="text-xs text-violet-200 mt-2">
                        Based on <strong>{{ $totalReviews }}</strong> {{ Str::plural('review', $totalReviews) }}
                    </p>
                    @if($averageRating > 0)
                        <p class="mt-2 text-xs text-violet-100 font-medium">Keep up the great work!</p>
                    @else
                        <p class="mt-2 text-xs text-violet-200 italic">Deliver great service to earn your first review!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── amCharts JS ────────────────────────────────────────── --}}
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const violetColor = am5.color(0x7c3aed);
        const indigoColor = am5.color(0x6366f1);
        const textColor   = am5.color(0x9ca3af);
        const gridColor   = am5.color(0xe5e7eb);

        // ── 1. Orders Over Time ──────────────────────────────────────
        (function () {
            const root = am5.Root.new("seller-chart-orders");
            root.setThemes([am5themes_Animated.new(root)]);
            root._logo.dispose();

            const chart = root.container.children.push(
                am5xy.XYChart.new(root, { panX:false, panY:false, wheelX:"none", wheelY:"none" })
            );
            const xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "label",
                renderer: am5xy.AxisRendererX.new(root, { minGridDistance: 30 }),
            }));
            xAxis.get("renderer").labels.template.setAll({ fill: textColor, fontSize: 10 });
            xAxis.get("renderer").grid.template.setAll({ stroke: gridColor, strokeOpacity: 0.5 });

            const yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {}),
            }));
            yAxis.get("renderer").labels.template.setAll({ fill: textColor, fontSize: 10 });
            yAxis.get("renderer").grid.template.setAll({ stroke: gridColor, strokeOpacity: 0.4 });

            const makeLineSeries = (field, color, dashed) => {
                const s = chart.series.push(am5xy.SmoothedXLineSeries.new(root, {
                    name: field === 'orders' ? 'Orders' : 'Revenue (₱)',
                    xAxis, yAxis,
                    valueYField: field,
                    categoryXField: "label",
                    fill: color, stroke: color,
                    tooltip: am5.Tooltip.new(root, { labelText: "{name}: {valueY}" }),
                }));
                s.fills.template.setAll({ fillOpacity: 0.12, visible: true });
                s.strokes.template.setAll({ strokeWidth: 2.5, ...(dashed ? { strokeDasharray: [5,3] } : {}) });
                return s;
            };
            const s1 = makeLineSeries('orders', violetColor, false);
            const s2 = makeLineSeries('revenue', indigoColor, true);

            chart.set("cursor", am5xy.XYCursor.new(root, { behavior: "none" }));
            const legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.percent(50), x: am5.percent(50), marginTop: 8,
            }));
            legend.labels.template.setAll({ fill: textColor, fontSize: 11 });
            legend.data.setAll(chart.series.values);

            xAxis.data.setAll(sellerOrdersOverTime);
            s1.data.setAll(sellerOrdersOverTime);
            s2.data.setAll(sellerOrdersOverTime);
            s1.appear(1000); s2.appear(1000); chart.appear(1000, 100);
        })();

        // ── 2. Order Status Donut ─────────────────────────────────────
        (function () {
            const palette = {
                pending:    am5.color(0xf59e0b),
                processing: am5.color(0x3b82f6),
                ready:      am5.color(0x10b981),
                completed:  am5.color(0x22c55e),
                cancelled:  am5.color(0xef4444),
            };
            const data = Object.entries(sellerOrderStatuses).map(([s, c]) => ({
                status: s.charAt(0).toUpperCase() + s.slice(1),
                count: c,
                sliceSettings: { fill: palette[s] ?? am5.color(0x7c3aed) },
            }));

            const root = am5.Root.new("seller-chart-status");
            root.setThemes([am5themes_Animated.new(root)]);
            root._logo.dispose();

            const chart = root.container.children.push(
                am5percent.PieChart.new(root, { innerRadius: am5.percent(60), layout: root.verticalLayout })
            );
            const series = chart.series.push(am5percent.PieSeries.new(root, {
                valueField: "count", categoryField: "status", fillField: "sliceSettings",
            }));
            series.slices.template.setAll({
                templateField: "sliceSettings",
                strokeOpacity: 0,
                cornerRadiusInner: 8, cornerRadiusOuter: 8,
            });
            series.labels.template.setAll({ fill: textColor, fontSize: 10 });
            series.ticks.template.setAll({ stroke: textColor, strokeOpacity: 0.5 });

            const total = Object.values(sellerOrderStatuses).reduce((a,b) => a+b, 0);
            root.container.children.push(am5.Label.new(root, {
                text: `[bold #7c3aed fontSize:26px]${total}[/]\n[fontSize:10px #6b7280]Orders[/]`,
                textType: "radial",
                centerX: am5.percent(50), centerY: am5.percent(50),
                x: am5.percent(50), y: am5.percent(50),
                textAlign: "center",
            }));

            series.data.setAll(data.length ? data : [{ status: 'No Orders', count: 1, sliceSettings: { fill: am5.color(0x6b7280) } }]);
            series.appear(1000, 100);
        })();
    });
    </script>

</x-seller-layout>
