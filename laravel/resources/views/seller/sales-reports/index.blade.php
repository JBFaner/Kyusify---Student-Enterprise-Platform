<x-seller-layout>
    <x-slot name="header">Sales Reports</x-slot>

    {{-- amCharts 5 --}}
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <script>
        const revenueOverTime = @json($revenueOverTime);
        const orderStatuses   = @json($orderStatuses);
        const topProducts     = @json($topProducts);
        const isDark = document.documentElement.classList.contains('dark');

        function exportCSV(tableId, filename) {
            const table = document.getElementById(tableId);
            const rows  = Array.from(table.querySelectorAll('tr'));
            const lines = rows.map(row =>
                Array.from(row.querySelectorAll('th,td'))
                    .map(cell => '"' + cell.innerText.replace(/₱/g,'').replace(/"/g,'""').trim() + '"')
                    .join(',')
            );
            const blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename + '.csv';
            link.click();
        }

        function exportPDF(title, tableId) {
            const table = document.getElementById(tableId);
            const now   = new Date().toLocaleString('en-PH', { dateStyle:'long', timeStyle:'short' });
            const win = window.open('', '_blank');
            win.document.write(`<!DOCTYPE html><html><head>
    <link rel="icon" href="{{ asset('images/kyusify-logo.ico') }}" type="image/x-icon"><meta charset="UTF-8"><title>${title}</title>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
                *{margin:0;padding:0;box-sizing:border-box;}
                body{font-family:'Inter',sans-serif;color:#111;background:#fff;padding:48px 56px;font-size:13px;}
                .header{display:flex;justify-content:space-between;align-items:flex-start;border-bottom:2px solid #7c3aed;padding-bottom:18px;margin-bottom:24px;}
                .logo-block h1{font-size:22px;font-weight:700;color:#7c3aed;}
                .logo-block p{font-size:11px;color:#6b7280;margin-top:2px;}
                .meta{text-align:right;}
                .meta h2{font-size:16px;font-weight:700;color:#1f2937;}
                .meta p{font-size:11px;color:#6b7280;margin-top:3px;}
                table{width:100%;border-collapse:collapse;margin-top:8px;}
                thead tr{background:#7c3aed;}
                thead th{color:#fff;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.5px;padding:10px 14px;text-align:left;}
                tbody tr:nth-child(even){background:#f5f3ff;}
                tbody td{padding:9px 14px;font-size:12px;color:#374151;border-bottom:1px solid #e5e7eb;}
                tfoot tr{background:#ede9fe;}
                tfoot td{padding:10px 14px;font-size:12px;font-weight:700;color:#5b21b6;border-top:2px solid #7c3aed;}
                .footer{margin-top:40px;border-top:1px solid #e5e7eb;padding-top:14px;display:flex;justify-content:space-between;font-size:10px;color:#9ca3af;}
                @media print{body{padding:24px 32px;} @page{margin:12mm;}}
            </style></head><body>
            <div class="header">
                <div class="logo-block"><h1>&#9670; Kyusify</h1><p>Seller Analytics Dashboard &mdash; Sales Report</p></div>
                <div class="meta"><h2>${title}</h2><p>Generated: ${now}</p></div>
            </div>
            ${table.outerHTML}
            <div class="footer"><span>Kyusify &mdash; Confidential</span><span>Auto-generated system report</span></div>
            </body></html>`);
            win.document.close();
            setTimeout(() => win.print(), 500);
        }

        function liveSearch(inputId, tableId) {
            document.getElementById(inputId).addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
                });
            });
        }
    </script>

    {{-- ── Summary Cards ───────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        @php
        $cards = [
            ['icon'=>'fa-peso-sign',      'color'=>'violet',  'label'=>'Total Revenue',        'value'=>'₱'.number_format($totalRevenue,2)],
            ['icon'=>'fa-cart-shopping',  'color'=>'sky',     'label'=>'Total Orders',          'value'=>number_format($totalOrders)],
            ['icon'=>'fa-boxes-stacked',  'color'=>'fuchsia', 'label'=>'Products Sold',         'value'=>number_format($productsSold)],
            ['icon'=>'fa-chart-line',     'color'=>'indigo',  'label'=>'Avg Order Value',       'value'=>'₱'.number_format($avgOrderValue,2)],
            ['icon'=>'fa-hourglass-half', 'color'=>'amber',   'label'=>'Pending Orders',        'value'=>number_format($totalPending)],
            ['icon'=>'fa-circle-check',   'color'=>'emerald', 'label'=>'Completed Orders',      'value'=>number_format($totalCompleted)],
        ];
        $cm = [
            'violet'  => ['bg'=>'bg-violet-50 dark:bg-violet-900/30',   'ic'=>'text-violet-600'],
            'sky'     => ['bg'=>'bg-sky-50 dark:bg-sky-900/30',         'ic'=>'text-sky-600'],
            'fuchsia' => ['bg'=>'bg-fuchsia-50 dark:bg-fuchsia-900/30', 'ic'=>'text-fuchsia-600'],
            'indigo'  => ['bg'=>'bg-indigo-50 dark:bg-indigo-900/30',   'ic'=>'text-indigo-600'],
            'amber'   => ['bg'=>'bg-amber-50 dark:bg-amber-900/30',     'ic'=>'text-amber-600'],
            'emerald' => ['bg'=>'bg-emerald-50 dark:bg-emerald-900/30', 'ic'=>'text-emerald-600'],
        ];
        @endphp
        @foreach($cards as $card)
        @php $c = $cm[$card['color']]; @endphp
        <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800/60 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 {{ $c['bg'] }} {{ $c['ic'] }} rounded-xl flex items-center justify-center shrink-0">
                <i class="fa-solid {{ $card['icon'] }} text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $card['label'] }}</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $card['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Revenue Analytics ───────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Revenue Over Time (wide) --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800/60 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-5">Revenue &amp; Orders Over Time</h3>
            <div id="chart-revenue" style="height:280px;"></div>
        </div>
        {{-- Status Donut --}}
        <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800/60 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-5">Order Status Distribution</h3>
            <div id="chart-status" style="height:280px;"></div>
        </div>
    </div>

    {{-- Top Products chart --}}
    <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800/60 shadow-sm p-6 mb-8">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-5">Top Selling Products (by Revenue)</h3>
        <div id="chart-top-products" style="height:240px;"></div>
    </div>

    {{-- ── Filter Bar for Orders Table ─────────────────────────────────── --}}
    <form method="GET" action="{{ route('seller.reports.index') }}" class="flex flex-wrap items-end gap-3 mb-4">
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">From</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm text-gray-800 dark:text-gray-100 bg-white dark:bg-[#13111C] focus:ring-2 focus:ring-violet-400 outline-none">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">To</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm text-gray-800 dark:text-gray-100 bg-white dark:bg-[#13111C] focus:ring-2 focus:ring-violet-400 outline-none">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status</label>
            <select name="status" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm text-gray-800 dark:text-gray-100 bg-white dark:bg-[#13111C] focus:ring-2 focus:ring-violet-400 outline-none">
                <option value="">All Statuses</option>
                @foreach(['pending','processing','ready','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ $statusFilter === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-5 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-lg transition-colors">Filter</button>
        @if($dateFrom || $dateTo || $statusFilter)
        <a href="{{ route('seller.reports.index') }}" class="px-4 py-2 text-sm text-gray-500 border border-gray-200 dark:border-gray-700 rounded-lg hover:text-gray-700 dark:text-gray-400">Clear</a>
        @endif
    </form>

    {{-- ── Section helper: table card ─────────────────────────────────── --}}
    {{-- 3. Orders Report Table --}}
    <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800/60 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50/50 dark:bg-white/[.02]">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Orders Report</h3>
            <div class="flex items-center gap-2 flex-wrap">
                <input id="search-orders" type="text" placeholder="Search…" class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-xs bg-white dark:bg-[#13111C] text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-violet-400 outline-none w-44">
                <button onclick="exportCSV('ordersTable','Orders_Report')" class="inline-flex items-center gap-1 px-3 py-1.5 border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#13111C] rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 hover:text-emerald-600 transition-colors">
                    <i class="fa-solid fa-file-csv"></i> CSV
                </button>
                <button onclick="exportPDF('Orders Report','ordersTable')" class="inline-flex items-center gap-1 px-3 py-1.5 border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#13111C] rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 hover:text-red-600 transition-colors">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="ordersTable">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-5 py-3">Order ID</th>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Qty</th>
                        <th class="px-5 py-3">Total</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60">
                    @forelse($ordersReport as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20">
                        <td class="px-5 py-3 font-mono text-xs text-violet-600 dark:text-violet-400 font-bold">{{ $row->order_id }}</td>
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $row->product_name }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $row->customer_name }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $row->quantity }}</td>
                        <td class="px-5 py-3 font-mono font-bold text-gray-900 dark:text-white">₱{{ number_format($row->total_price,2) }}</td>
                        <td class="px-5 py-3">
                            @php $sc=['pending'=>'amber','processing'=>'sky','ready'=>'indigo','completed'=>'green','cancelled'=>'red']; $col=$sc[$row->status]??'gray'; @endphp
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-{{ $col }}-100 text-{{ $col }}-700 dark:bg-{{ $col }}-900/20">{{ $row->status }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $row->date }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No orders found for the selected filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 4. Product Sales Report --}}
    <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800/60 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50/50 dark:bg-white/[.02]">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Product Sales</h3>
            <div class="flex items-center gap-2">
                <input id="search-products" type="text" placeholder="Search…" class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-xs bg-white dark:bg-[#13111C] text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-violet-400 outline-none w-44">
                <button onclick="exportCSV('productsTable','Product_Sales_Report')" class="inline-flex items-center gap-1 px-3 py-1.5 border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#13111C] rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 hover:text-emerald-600 transition-colors"><i class="fa-solid fa-file-csv"></i> CSV</button>
                <button onclick="exportPDF('Product Sales','productsTable')" class="inline-flex items-center gap-1 px-3 py-1.5 border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#13111C] rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 hover:text-red-600 transition-colors"><i class="fa-solid fa-file-pdf"></i> PDF</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="productsTable">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-5 py-3">Product Name</th>
                        <th class="px-5 py-3">Orders</th>
                        <th class="px-5 py-3">Qty Sold</th>
                        <th class="px-5 py-3">Revenue</th>
                        <th class="px-5 py-3">Rating</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60">
                    @forelse($productSalesReport as $prod)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20">
                        <td class="px-5 py-3 font-bold text-gray-900 dark:text-white">{{ $prod->name }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $prod->orders }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $prod->quantity_sold }}</td>
                        <td class="px-5 py-3 font-mono font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($prod->revenue,2) }}</td>
                        <td class="px-5 py-3 text-amber-500"><i class="fa-solid fa-star text-xs"></i> {{ $prod->rating }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">No products yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 5. Monthly Sales Report --}}
    <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800/60 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50/50 dark:bg-white/[.02]">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Monthly Sales (Last 12 Months)</h3>
            <div class="flex items-center gap-2">
                <button onclick="exportCSV('monthlyTable','Monthly_Sales_Report')" class="inline-flex items-center gap-1 px-3 py-1.5 border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#13111C] rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 hover:text-emerald-600 transition-colors"><i class="fa-solid fa-file-csv"></i> CSV</button>
                <button onclick="exportPDF('Monthly Sales','monthlyTable')" class="inline-flex items-center gap-1 px-3 py-1.5 border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#13111C] rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 hover:text-red-600 transition-colors"><i class="fa-solid fa-file-pdf"></i> PDF</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="monthlyTable">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-5 py-3">Month</th>
                        <th class="px-5 py-3">Orders</th>
                        <th class="px-5 py-3">Products Sold</th>
                        <th class="px-5 py-3 text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60">
                    @foreach($monthlySales as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20">
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $row->month }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $row->orders }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $row->products_sold }}</td>
                        <td class="px-5 py-3 text-right font-mono font-bold {{ $row->revenue > 0 ? 'text-emerald-600' : 'text-gray-400' }}">₱{{ number_format($row->revenue,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-violet-50 dark:bg-violet-900/20 border-t-2 border-violet-200 dark:border-violet-800 font-bold">
                    <tr>
                        <td class="px-5 py-4 text-gray-900 dark:text-white">12-Month Total</td>
                        <td class="px-5 py-4 text-gray-900 dark:text-white">{{ $monthlySales->sum('orders') }}</td>
                        <td class="px-5 py-4 text-gray-900 dark:text-white">{{ $monthlySales->sum('products_sold') }}</td>
                        <td class="px-5 py-4 text-right text-violet-700 dark:text-violet-300">₱{{ number_format($monthlySales->sum('revenue'),2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Charts JS --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const textColor   = isDark ? am5.color(0xa0aec0) : am5.color(0x6b7280);
        const gridColor   = isDark ? am5.color(0x1f2937) : am5.color(0xe5e7eb);
        const violet      = am5.color(0x7c3aed);
        const indigo      = am5.color(0x6366f1);

        setTimeout(() => {
            // 1. Revenue Over Time
            (function() {
                const root  = am5.Root.new('chart-revenue');
                root.setThemes([am5themes_Animated.new(root)]);
                root._logo.dispose();
                const chart = root.container.children.push(am5xy.XYChart.new(root, { layout:root.verticalLayout }));
                const xa = chart.xAxes.push(am5xy.CategoryAxis.new(root, { categoryField:'label', renderer:am5xy.AxisRendererX.new(root,{minGridDistance:30}) }));
                xa.get('renderer').labels.template.setAll({ fill:textColor, fontSize:11 });
                xa.get('renderer').grid.template.setAll({ stroke:gridColor, strokeOpacity:0.5 });
                const ya = chart.yAxes.push(am5xy.ValueAxis.new(root, { renderer:am5xy.AxisRendererY.new(root,{}) }));
                ya.get('renderer').labels.template.setAll({ fill:textColor, fontSize:11 });
                ya.get('renderer').grid.template.setAll({ stroke:gridColor, strokeOpacity:0.5 });
                const revSeries = chart.series.push(am5xy.SmoothedXLineSeries.new(root, { name:'Revenue (₱)', xAxis:xa, yAxis:ya, valueYField:'revenue', categoryXField:'label', fill:violet, stroke:violet, tooltip:am5.Tooltip.new(root,{labelText:'₱{valueY}'}) }));
                revSeries.fills.template.setAll({ visible:true, fillOpacity:0.15 });
                revSeries.strokes.template.setAll({ strokeWidth:2.5 });
                const ordSeries = chart.series.push(am5xy.SmoothedXLineSeries.new(root, { name:'Orders', xAxis:xa, yAxis:ya, valueYField:'orders', categoryXField:'label', fill:indigo, stroke:indigo, tooltip:am5.Tooltip.new(root,{labelText:'{valueY} orders'}) }));
                ordSeries.fills.template.setAll({ visible:true, fillOpacity:0.08 });
                ordSeries.strokes.template.setAll({ strokeWidth:2, strokeDasharray:[5,3] });
                chart.set('cursor', am5xy.XYCursor.new(root, {behavior:'none'}));
                const legend = chart.children.push(am5.Legend.new(root, { centerX:am5.percent(50), x:am5.percent(50), marginTop:8 }));
                legend.labels.template.setAll({ fill:textColor, fontSize:12 });
                legend.data.setAll(chart.series.values);
                const data = revenueOverTime.length ? revenueOverTime : [{label:'N/A',revenue:0,orders:0}];
                xa.data.setAll(data); revSeries.data.setAll(data); ordSeries.data.setAll(data);
                revSeries.appear(1000); chart.appear(1000,100);
            })();

            // 2. Status Donut
            (function() {
                const sc = { pending:am5.color(0xf59e0b), processing:am5.color(0x3b82f6), ready:am5.color(0x10b981), completed:am5.color(0x22c55e), cancelled:am5.color(0xef4444) };
                const data = Object.entries(orderStatuses).map(([s,c]) => ({ status:s.charAt(0).toUpperCase()+s.slice(1), count:c, sliceSettings:{ fill:sc[s]??violet } }));
                const root  = am5.Root.new('chart-status');
                root.setThemes([am5themes_Animated.new(root)]);
                root._logo.dispose();
                const chart  = root.container.children.push(am5percent.PieChart.new(root, { innerRadius:am5.percent(60), layout:root.verticalLayout }));
                const series = chart.series.push(am5percent.PieSeries.new(root, { valueField:'count', categoryField:'status', fillField:'sliceSettings' }));
                series.slices.template.setAll({ templateField:'sliceSettings', strokeOpacity:0, cornerRadiusInner:6, cornerRadiusOuter:6 });
                series.labels.template.setAll({ fill:textColor, fontSize:11 });
                series.ticks.template.setAll({ stroke:textColor });
                const total = Object.values(orderStatuses).reduce((a,b)=>a+b,0);
                series.data.setAll(total>0 ? data : [{status:'No Orders',count:1,sliceSettings:{fill:am5.color(0x9ca3af)}}]);
                root.container.children.push(am5.Label.new(root, { text:`[bold #7c3aed fontSize:24px]${total}[/]\n[fontSize:11px #6b7280]Total[/]`, textType:'radial', centerX:am5.percent(50), centerY:am5.percent(50), x:am5.percent(50), y:am5.percent(50), textAlign:'center' }));
                series.appear(1000,100);
            })();

            // 3. Top Products bar
            (function() {
                const root  = am5.Root.new('chart-top-products');
                root.setThemes([am5themes_Animated.new(root)]);
                root._logo.dispose();
                const chart = root.container.children.push(am5xy.XYChart.new(root, { layout:root.verticalLayout }));
                const ya = chart.yAxes.push(am5xy.CategoryAxis.new(root, { categoryField:'name', renderer:am5xy.AxisRendererY.new(root,{inversed:true,minGridDistance:20}) }));
                ya.get('renderer').labels.template.setAll({ fill:textColor, fontSize:11, maxWidth:150, oversizedBehavior:'truncate' });
                ya.get('renderer').grid.template.setAll({ stroke:gridColor, strokeOpacity:0.4 });
                const xa = chart.xAxes.push(am5xy.ValueAxis.new(root, { renderer:am5xy.AxisRendererX.new(root,{}), min:0 }));
                xa.get('renderer').labels.template.setAll({ fill:textColor, fontSize:11 });
                xa.get('renderer').grid.template.setAll({ stroke:gridColor, strokeOpacity:0.4 });
                const series = chart.series.push(am5xy.ColumnSeries.new(root, { xAxis:xa, yAxis:ya, valueXField:'revenue', categoryYField:'name', tooltip:am5.Tooltip.new(root,{labelText:'₱{valueX}'}) }));
                series.columns.template.setAll({ height:am5.percent(60), cornerRadiusTR:5, cornerRadiusBR:5, fill:violet, strokeOpacity:0 });
                chart.set('cursor', am5xy.XYCursor.new(root,{behavior:'none'}));
                const data = topProducts.length ? topProducts : [{name:'No data',revenue:0}];
                ya.data.setAll(data); series.data.setAll(data);
                series.appear(1000); chart.appear(1000,100);
            })();
        }, 150);

        liveSearch('search-orders',   'ordersTable');
        liveSearch('search-products', 'productsTable');
    });
    </script>
</x-seller-layout>
