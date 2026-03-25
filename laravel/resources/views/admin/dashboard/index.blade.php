<x-admin-layout>
    <x-slot name="header">
        Dashboard &amp; Analytics
    </x-slot>

    {{-- amCharts 5 CDN --}}
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    {{-- Pass PHP data to JS --}}
    <script>
        const ordersOverTime = @json($ordersOverTime);
        const orderStatuses  = @json($orderStatuses);
        const topProducts    = @json($topProducts);
        const isDark = document.documentElement.classList.contains('dark');
    </script>

    {{-- ── Stats row ───────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        @php
        $stats = [
            ['icon'=>'fa-users', 'color'=>'indigo',  'label'=>'Total Sellers',     'value'=> number_format($totalSellers)],
            ['icon'=>'fa-user-group','color'=>'sky', 'label'=>'Total Customers',   'value'=> number_format($totalCustomers)],
            ['icon'=>'fa-cubes',  'color'=>'fuchsia','label'=>'Total Products',    'value'=> number_format($totalProducts)],
            ['icon'=>'fa-store',  'color'=>'emerald','label'=>'Active Enterprises','value'=> number_format($totalEnterprises)],
            ['icon'=>'fa-cart-shopping','color'=>'violet','label'=>'Total Orders', 'value'=> number_format($totalOrders)],
            ['icon'=>'fa-peso-sign','color'=>'green','label'=>'Total Revenue',     'value'=>'₱'.number_format($totalRevenue,2)],
            ['icon'=>'fa-hourglass-half','color'=>'amber','label'=>'Pending Orders','value'=>number_format($pendingOrders)],
        ];
        $colorMap = [
            'indigo'  => ['bg'=>'bg-indigo-50  dark:bg-indigo-900/30',  'icon'=>'text-indigo-600  dark:text-indigo-400'],
            'sky'     => ['bg'=>'bg-sky-50     dark:bg-sky-900/30',     'icon'=>'text-sky-600     dark:text-sky-400'],
            'fuchsia' => ['bg'=>'bg-fuchsia-50 dark:bg-fuchsia-900/30', 'icon'=>'text-fuchsia-600 dark:text-fuchsia-400'],
            'emerald' => ['bg'=>'bg-emerald-50 dark:bg-emerald-900/30', 'icon'=>'text-emerald-600 dark:text-emerald-400'],
            'violet'  => ['bg'=>'bg-violet-50  dark:bg-violet-900/30',  'icon'=>'text-violet-600  dark:text-violet-400'],
            'green'   => ['bg'=>'bg-green-50   dark:bg-green-900/30',   'icon'=>'text-green-600   dark:text-green-400'],
            'amber'   => ['bg'=>'bg-amber-50   dark:bg-amber-900/30',   'icon'=>'text-amber-600   dark:text-amber-400'],
        ];
        @endphp

        @foreach($stats as $s)
        @php $c = $colorMap[$s['color']]; @endphp
        <div class="group relative bg-white dark:bg-[#0B0A0F] p-5 rounded-2xl border border-gray-100 dark:border-gray-800 hover:border-violet-400 dark:hover:border-violet-500 shadow-sm hover:shadow-lg hover:shadow-violet-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full {{ $c['bg'] }} blur-2xl opacity-60 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-11 h-11 {{ $c['bg'] }} {{ $c['icon'] }} rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid {{ $s['icon'] }} text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $s['label'] }}</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-0.5 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">{{ $s['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Charts row ──────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- Orders Over Time (area chart) --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Orders Over Time</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Last 6 months — orders &amp; completed revenue</p>
                </div>
                <span class="w-8 h-8 rounded-xl bg-violet-50 dark:bg-violet-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-chart-area text-violet-500 text-sm"></i>
                </span>
            </div>
            <div id="chart-orders-time" style="height:280px;"></div>
        </div>

        {{-- Order Status Donut --}}
        <div class="bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Order Status</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Breakdown by status</p>
                </div>
                <span class="w-8 h-8 rounded-xl bg-fuchsia-50 dark:bg-fuchsia-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-chart-pie text-fuchsia-500 text-sm"></i>
                </span>
            </div>
            <div id="chart-order-status" style="height:280px;"></div>
        </div>
    </div>

    {{-- Top Products bar chart --}}
    <div class="bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Top Products by Units Sold</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Products with the highest total order quantity</p>
            </div>
            <span class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center">
                <i class="fa-solid fa-chart-bar text-emerald-500 text-sm"></i>
            </span>
        </div>
        <div id="chart-top-products" style="height:220px;"></div>
    </div>

    {{-- ── Recent Orders table ─────────────────────────────────────── --}}
    <div class="bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Recent Orders</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Latest 8 orders across all stores</p>
            </div>
            <a href="{{ route('admin.enterprises.index') }}" class="text-xs text-violet-600 dark:text-violet-400 font-semibold hover:underline">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50/60 dark:bg-gray-900/30 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Order</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-left">Store</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-right">Amount</th>
                        <th class="px-6 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                    @forelse($recentOrders as $order)
                    @php
                        $badge = match($order->status) {
                            'completed'  => 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                            'cancelled'  => 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                            'ready'      => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400',
                            'processing' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400',
                            default      => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">#{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-full bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 dark:text-violet-400 text-xs font-bold shrink-0">
                                    {{ substr($order->user->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $order->user->name ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $order->enterprise->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $badge }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-gray-900 dark:text-white">₱{{ number_format($order->total_amount,2) }}</td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400 dark:text-gray-600">
                            <i class="fa-solid fa-inbox text-3xl mb-3 block"></i>
                            No orders yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── amCharts JS ─────────────────────────────────────────────── --}}
    <script>
    document.addEventListener("DOMContentLoaded", () => {

        const textColor   = isDark ? am5.color(0xa0aec0) : am5.color(0x6b7280);
        const gridColor   = isDark ? am5.color(0x1f2937) : am5.color(0xe5e7eb);
        const violetColor = am5.color(0x7c3aed);
        const indigoColor = am5.color(0x6366f1);

        // ── 1. Orders Over Time (XY Area Chart) ─────────────────────
        (function () {
            const root = am5.Root.new("chart-orders-time");
            root.setThemes([am5themes_Animated.new(root)]);
            root._logo.dispose();

            const chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false, panY: false, wheelX: "none", wheelY: "none",
                    layout: root.verticalLayout,
                })
            );

            const xAxis = chart.xAxes.push(
                am5xy.CategoryAxis.new(root, {
                    categoryField: "label",
                    renderer: am5xy.AxisRendererX.new(root, { minGridDistance: 30 }),
                    tooltip: am5.Tooltip.new(root, {}),
                })
            );
            xAxis.get("renderer").labels.template.setAll({ fill: textColor, fontSize: 11 });
            xAxis.get("renderer").grid.template.setAll({ stroke: gridColor, strokeOpacity: 0.5 });

            const yAxis = chart.yAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererY.new(root, {}),
                })
            );
            yAxis.get("renderer").labels.template.setAll({ fill: textColor, fontSize: 11 });
            yAxis.get("renderer").grid.template.setAll({ stroke: gridColor, strokeOpacity: 0.5 });

            // Orders series
            const ordersSeries = chart.series.push(
                am5xy.SmoothedXLineSeries.new(root, {
                    name: "Orders",
                    xAxis, yAxis,
                    valueYField: "orders",
                    categoryXField: "label",
                    tooltip: am5.Tooltip.new(root, { labelText: "{name}: {valueY}" }),
                    fill: violetColor,
                    stroke: violetColor,
                })
            );
            ordersSeries.fills.template.setAll({ fillOpacity: 0.15, visible: true });
            ordersSeries.strokes.template.setAll({ strokeWidth: 2.5 });

            // Revenue series
            const revSeries = chart.series.push(
                am5xy.SmoothedXLineSeries.new(root, {
                    name: "Revenue (₱)",
                    xAxis, yAxis,
                    valueYField: "revenue",
                    categoryXField: "label",
                    tooltip: am5.Tooltip.new(root, { labelText: "{name}: ₱{valueY}" }),
                    fill: indigoColor,
                    stroke: indigoColor,
                })
            );
            revSeries.fills.template.setAll({ fillOpacity: 0.1, visible: true });
            revSeries.strokes.template.setAll({ strokeWidth: 2, strokeDasharray: [5, 3] });

            chart.set("cursor", am5xy.XYCursor.new(root, { behavior: "none" }));

            const legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.percent(50), x: am5.percent(50),
                marginTop: 10,
            }));
            legend.labels.template.setAll({ fill: textColor, fontSize: 12 });
            legend.data.setAll(chart.series.values);

            xAxis.data.setAll(ordersOverTime);
            ordersSeries.data.setAll(ordersOverTime);
            revSeries.data.setAll(ordersOverTime);
            ordersSeries.appear(1000);
            revSeries.appear(1000);
            chart.appear(1000, 100);
        })();

        // ── 2. Order Status Donut ─────────────────────────────────────
        (function () {
            const statusColors = {
                pending:    am5.color(0xf59e0b),
                processing: am5.color(0x3b82f6),
                ready:      am5.color(0x10b981),
                completed:  am5.color(0x22c55e),
                cancelled:  am5.color(0xef4444),
            };

            const data = Object.entries(orderStatuses).map(([status, count]) => ({
                status: status.charAt(0).toUpperCase() + status.slice(1),
                count,
                sliceSettings: { fill: statusColors[status] ?? am5.color(0x8b5cf6) },
            }));

            const root = am5.Root.new("chart-order-status");
            root.setThemes([am5themes_Animated.new(root)]);
            root._logo.dispose();

            const chart = root.container.children.push(
                am5percent.PieChart.new(root, {
                    innerRadius: am5.percent(60),
                    layout: root.verticalLayout,
                })
            );

            const series = chart.series.push(
                am5percent.PieSeries.new(root, {
                    valueField: "count",
                    categoryField: "status",
                    fillField: "sliceSettings",
                })
            );
            series.slices.template.setAll({
                templateField: "sliceSettings",
                strokeOpacity: 0,
                cornerRadiusInner: 8,
                cornerRadiusOuter: 8,
            });
            series.labels.template.setAll({ fill: textColor, fontSize: 11 });
            series.ticks.template.setAll({ stroke: textColor });
            series.data.setAll(data.length ? data : [{ status: 'No Orders', count: 1, sliceSettings: { fill: am5.color(0x6b7280) } }]);

            // Center label
            const label = root.container.children.push(am5.Label.new(root, {
                text: `[bold #7c3aed fontSize:28px]${Object.values(orderStatuses).reduce((a,b)=>a+b,0)}[/]\n[fontSize:11px #6b7280]Total Orders[/]`,
                textType: "radial",
                centerX: am5.percent(50), centerY: am5.percent(50),
                x: am5.percent(50), y: am5.percent(50),
                textAlign: "center",
            }));

            series.appear(1000, 100);
        })();

        // ── 3. Top Products Horizontal Bar ────────────────────────────
        (function () {
            const root = am5.Root.new("chart-top-products");
            root.setThemes([am5themes_Animated.new(root)]);
            root._logo.dispose();

            const chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false, panY: false, wheelX: "none", wheelY: "none",
                    layout: root.verticalLayout,
                })
            );

            const yAxis = chart.yAxes.push(
                am5xy.CategoryAxis.new(root, {
                    categoryField: "name",
                    renderer: am5xy.AxisRendererY.new(root, {
                        inversed: true, minGridDistance: 20,
                    }),
                })
            );
            yAxis.get("renderer").labels.template.setAll({
                fill: textColor, fontSize: 12,
                maxWidth: 180, oversizedBehavior: "truncate",
            });
            yAxis.get("renderer").grid.template.setAll({ stroke: gridColor, strokeOpacity: 0.4 });

            const xAxis = chart.xAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererX.new(root, {}),
                    min: 0,
                })
            );
            xAxis.get("renderer").labels.template.setAll({ fill: textColor, fontSize: 11 });
            xAxis.get("renderer").grid.template.setAll({ stroke: gridColor, strokeOpacity: 0.4 });

            const series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                    xAxis, yAxis,
                    valueXField: "count",
                    categoryYField: "name",
                    tooltip: am5.Tooltip.new(root, { labelText: "{valueX} units sold" }),
                })
            );
            series.columns.template.setAll({
                height: am5.percent(60),
                cornerRadiusTR: 6, cornerRadiusBR: 6,
                fill: violetColor, stroke: am5.color(0x6d28d9),
                strokeOpacity: 0,
            });
            series.columns.template.adapters.add("fill", (fill, target) => {
                const colors = [0x7c3aed, 0x6366f1, 0x8b5cf6, 0xa78bfa, 0xc4b5fd];
                return am5.color(colors[series.columns.indexOf(target)] ?? 0x7c3aed);
            });

            chart.set("cursor", am5xy.XYCursor.new(root, { behavior: "none" }));

            const data = topProducts.length ? topProducts : [{ name: 'No data yet', count: 0 }];
            yAxis.data.setAll(data);
            series.data.setAll(data);
            series.appear(1000);
            chart.appear(1000, 100);
        })();

    });
    </script>

</x-admin-layout>
