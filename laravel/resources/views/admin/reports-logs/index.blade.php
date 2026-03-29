<x-admin-layout>
    <x-slot name="header">Reports &amp; Logs</x-slot>

    {{-- amCharts 5 CDN --}}
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    {{-- Pass PHP data to JS --}}
    <script>
        const ordersOverTime = @json($ordersOverTime);
        const orderStatuses  = @json($orderStatuses);
        const topProducts    = @json($topProducts);
        const isDark = document.documentElement.classList.contains('dark');

        /* ── CSV Export (plain text cells to avoid Excel ### issue) ─── */
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

        /* ── Professional PDF (new window, print-ready) ─────────────── */
        function exportPDF(reportTitle, tableId) {
            const table = document.getElementById(tableId);
            const now   = new Date().toLocaleString('en-PH', { dateStyle:'long', timeStyle:'short' });
            const win = window.open('', '_blank');
            win.document.write(`<!DOCTYPE html><html><head>
    <link rel="icon" href="{{ asset('images/kyusify-logo.ico') }}" type="image/x-icon"><meta charset="UTF-8">
            <title>${reportTitle}</title>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
                *{margin:0;padding:0;box-sizing:border-box;}
                body{font-family:'Inter',sans-serif;color:#111;background:#fff;padding:48px 56px;font-size:13px;}
                .header{display:flex;justify-content:space-between;align-items:flex-start;border-bottom:2px solid #7c3aed;padding-bottom:18px;margin-bottom:24px;}
                .logo-block h1{font-size:22px;font-weight:700;color:#7c3aed;letter-spacing:-0.5px;}
                .logo-block p{font-size:11px;color:#6b7280;margin-top:2px;}
                .meta{text-align:right;}
                .meta h2{font-size:16px;font-weight:700;color:#1f2937;}
                .meta p{font-size:11px;color:#6b7280;margin-top:3px;}
                .info-row{display:flex;gap:40px;margin-bottom:24px;padding:14px 18px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;}
                .info-row div{display:flex;flex-direction:column;gap:2px;}
                .info-row .label{font-size:10px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;}
                .info-row .val{font-size:13px;font-weight:600;color:#1f2937;}
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
                <div class="logo-block">
                    <h1>&#9670; Kyusify</h1>
                    <p>Student Enterprise Marketplace — Admin Portal</p>
                </div>
                <div class="meta">
                    <h2>${reportTitle}</h2>
                    <p>Generated: ${now}</p>
                </div>
            </div>
            ${table.outerHTML}
            <div class="footer">
                <span>Kyusify &mdash; Confidential</span>
                <span>Auto-generated system report &mdash; do not alter manually</span>
            </div>
            </body></html>`);
            win.document.close();
            setTimeout(() => win.print(), 500);
        }

        /* ── Live search helper ───────────────────────────────────────── */
        function liveSearch(inputId, tableId) {
            document.getElementById(inputId).addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const rows  = document.querySelectorAll('#' + tableId + ' tbody tr');
                rows.forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
                });
            });
        }
    </script>

    <style>
        /* ── Print: hide everything except .print-table scope ── */
        @@media print {
            body > * { display: none !important; }
            .kyusify-print-zone { display: block !important; }
        }
        .tab-btn { transition: all .2s; }
        .tab-btn.active { background: #ede9fe; color: #6d28d9; font-weight: 700; border-bottom: 2px solid #7c3aed; }
        .dark .tab-btn.active { background: rgba(109,40,217,.2); color: #a78bfa; }
    </style>

    <div x-data="{ activeTab: '{{ request()->query('tab', 'overview') }}' }">

        {{-- Tab Bar --}}
        <div class="flex overflow-x-auto border-b border-gray-200 dark:border-gray-800 mb-8 space-x-0.5">
            @php
            $tabs = [
                ['id'=>'overview',     'name'=>'Overview',          'icon'=>'fa-chart-pie'],
                ['id'=>'sales',        'name'=>'Sales Reports',     'icon'=>'fa-peso-sign'],
                ['id'=>'products',     'name'=>'Products',          'icon'=>'fa-box'],
                ['id'=>'sellers',      'name'=>'Sellers',           'icon'=>'fa-store'],
                ['id'=>'customers',    'name'=>'Customers',         'icon'=>'fa-users'],
                ['id'=>'transactions', 'name'=>'Transaction Logs',  'icon'=>'fa-money-bill-transfer'],
                ['id'=>'activity',     'name'=>'Activity Logs',     'icon'=>'fa-list'],
            ];
            $activeTab = request()->query('tab','overview');
            @endphp
            @foreach($tabs as $tab)
            <button
                @click="activeTab = '{{ $tab['id'] }}'"
                :class="activeTab === '{{ $tab['id'] }}' ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300 font-bold border-b-2 border-violet-600' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/50'"
                class="px-4 py-3 text-sm font-medium flex items-center gap-2 whitespace-nowrap transition-all rounded-t-md">
                <i class="fa-solid {{ $tab['icon'] }} text-xs"></i> {{ $tab['name'] }}
            </button>
            @endforeach
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- 1. OVERVIEW TAB --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-show="activeTab === 'overview'" x-transition.opacity>
            {{-- Stats row --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                @php
                $stats = [
                    ['icon'=>'fa-peso-sign',      'color'=>'violet',  'label'=>'Total Revenue',           'value'=>'₱'.number_format($totalRevenue,2)],
                    ['icon'=>'fa-cart-shopping',  'color'=>'sky',     'label'=>'Total Orders',             'value'=>number_format($totalOrders)],
                    ['icon'=>'fa-money-bill-wave','color'=>'emerald', 'label'=>'Total Sales (Completed)',  'value'=>'₱'.number_format($totalSales,2)],
                    ['icon'=>'fa-store',          'color'=>'fuchsia', 'label'=>'Active Sellers',           'value'=>number_format($activeSellers)],
                    ['icon'=>'fa-user-plus',      'color'=>'indigo',  'label'=>'New Customers (30d)',      'value'=>number_format($newCustomers)],
                    ['icon'=>'fa-hourglass-half', 'color'=>'amber',   'label'=>'Pending Orders',           'value'=>number_format($pendingOrders)],
                ];
                $cm = [
                    'violet'  => ['bg'=>'bg-violet-50  dark:bg-violet-900/30',  'ic'=>'text-violet-600'],
                    'sky'     => ['bg'=>'bg-sky-50     dark:bg-sky-900/30',     'ic'=>'text-sky-600'],
                    'emerald' => ['bg'=>'bg-emerald-50 dark:bg-emerald-900/30', 'ic'=>'text-emerald-600'],
                    'fuchsia' => ['bg'=>'bg-fuchsia-50 dark:bg-fuchsia-900/30', 'ic'=>'text-fuchsia-600'],
                    'indigo'  => ['bg'=>'bg-indigo-50  dark:bg-indigo-900/30',  'ic'=>'text-indigo-600'],
                    'amber'   => ['bg'=>'bg-amber-50   dark:bg-amber-900/30',   'ic'=>'text-amber-600'],
                ];
                @endphp
                @foreach($stats as $s)
                @php $c = $cm[$s['color']]; @endphp
                <div class="bg-white dark:bg-[#0B0A0F] p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center gap-4">
                    <div class="w-11 h-11 {{ $c['bg'] }} {{ $c['ic'] }} rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid {{ $s['icon'] }} text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $s['label'] }}</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $s['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Charts row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2 bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5">Sales &amp; Orders Over Time</h3>
                    <div id="chart-orders-time" style="height:300px;"></div>
                </div>
                <div class="bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5">Order Status Distribution</h3>
                    <div id="chart-order-status" style="height:300px;"></div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5">Top 5 Products by Units Sold</h3>
                <div id="chart-top-products" style="height:280px;"></div>
            </div>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- 2. SALES REPORTS TAB --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-show="activeTab === 'sales'" x-transition.opacity style="display:none;">
            {{-- Date Range Filter --}}
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-3 mb-6">
                <input type="hidden" name="tab" value="sales">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                        class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm text-gray-800 dark:text-gray-100 bg-white dark:bg-[#0B0A0F] focus:ring-2 focus:ring-violet-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Date To</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                        class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm text-gray-800 dark:text-gray-100 bg-white dark:bg-[#0B0A0F] focus:ring-2 focus:ring-violet-400 outline-none">
                </div>
                <button type="submit" class="px-5 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-lg transition-colors">Apply Filter</button>
                @if($dateFrom || $dateTo)
                <a href="{{ route('admin.reports.index', ['tab'=>'sales']) }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg dark:text-gray-400">Clear</a>
                @endif
            </form>

            {{-- Export buttons --}}
            <div class="flex justify-end gap-2 mb-4">
                <button onclick="exportCSV('salesTable','Kyusify_Sales_Report')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-emerald-600 shadow-sm transition-all">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </button>
                <button onclick="exportPDF('Sales Report','salesTable')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-red-600 shadow-sm transition-all">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </button>
            </div>

            <div class="bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left" id="salesTable">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Orders</th>
                                <th class="px-6 py-4">Products Sold</th>
                                <th class="px-6 py-4 text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($salesByDate as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $row['date'] }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $row['orders'] }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $row['products_sold'] }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">₱{{ number_format($row['revenue'], 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400">No sales data for this period.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-violet-50 dark:bg-violet-900/20 font-bold border-t-2 border-violet-200 dark:border-violet-800">
                            <tr>
                                <td class="px-6 py-4 text-gray-900 dark:text-white">Total</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white">{{ collect($salesByDate)->sum('orders') }}</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white">{{ collect($salesByDate)->sum('products_sold') }}</td>
                                <td class="px-6 py-4 text-right text-violet-700 dark:text-violet-300">₱{{ number_format(collect($salesByDate)->sum('revenue'), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- 3. PRODUCTS TAB --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-show="activeTab === 'products'" x-transition.opacity style="display:none;">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <input id="search-products" type="text" placeholder="Search products, sellers, status…"
                    class="w-full sm:w-72 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-[#0B0A0F] text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-violet-400 outline-none">
                <div class="flex gap-2 shrink-0">
                    <button onclick="exportCSV('productsTable','Kyusify_Products_Report')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-emerald-600 shadow-sm transition-all">
                        <i class="fa-solid fa-file-csv"></i> CSV
                    </button>
                    <button onclick="exportPDF('Products Report','productsTable')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-red-600 shadow-sm transition-all">
                        <i class="fa-solid fa-file-pdf"></i> PDF
                    </button>
                </div>
            </div>
            <div class="bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left" id="productsTable">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">Product Name</th>
                                <th class="px-6 py-4">Seller</th>
                                <th class="px-6 py-4">Orders (Qty)</th>
                                <th class="px-6 py-4">Revenue</th>
                                <th class="px-6 py-4">Rating</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($productsReport as $prod)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $prod->name }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $prod->seller_name }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $prod->orders }}</td>
                                <td class="px-6 py-4 font-mono font-medium text-gray-900 dark:text-white">₱{{ number_format($prod->revenue, 2) }}</td>
                                <td class="px-6 py-4 text-amber-500"><i class="fa-solid fa-star text-xs"></i> {{ $prod->rating > 0 ? $prod->rating : 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 text-[10px] font-bold uppercase rounded {{ $prod->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $prod->status }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- 4. SELLERS TAB --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-show="activeTab === 'sellers'" x-transition.opacity style="display:none;">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <input id="search-sellers" type="text" placeholder="Search sellers…"
                    class="w-full sm:w-72 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-[#0B0A0F] text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-violet-400 outline-none">
                <div class="flex gap-2 shrink-0">
                    <button onclick="exportCSV('sellersTable','Kyusify_Sellers_Report')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-emerald-600 shadow-sm transition-all">
                        <i class="fa-solid fa-file-csv"></i> CSV
                    </button>
                    <button onclick="exportPDF('Sellers Report','sellersTable')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-red-600 shadow-sm transition-all">
                        <i class="fa-solid fa-file-pdf"></i> PDF
                    </button>
                </div>
            </div>
            <div class="bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left" id="sellersTable">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">Enterprise Name</th>
                                <th class="px-6 py-4">Products Listed</th>
                                <th class="px-6 py-4">Total Orders</th>
                                <th class="px-6 py-4">Revenue</th>
                                <th class="px-6 py-4">Avg Rating</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($sellersReport as $seller)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $seller->name }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $seller->products_count }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $seller->orders_count }}</td>
                                <td class="px-6 py-4 font-mono font-medium text-emerald-600 dark:text-emerald-400">₱{{ number_format($seller->revenue, 2) }}</td>
                                <td class="px-6 py-4 text-amber-500"><i class="fa-solid fa-star text-xs"></i> {{ $seller->rating > 0 ? $seller->rating : 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 text-[10px] font-bold uppercase rounded {{ $seller->status == 'active' ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700' }}">{{ $seller->status }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- 5. CUSTOMERS TAB --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-show="activeTab === 'customers'" x-transition.opacity style="display:none;">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <input id="search-customers" type="text" placeholder="Search customers…"
                    class="w-full sm:w-72 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-[#0B0A0F] text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-violet-400 outline-none">
                <div class="flex gap-2 shrink-0">
                    <button onclick="exportCSV('customersTable','Kyusify_Customers_Report')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-emerald-600 shadow-sm transition-all">
                        <i class="fa-solid fa-file-csv"></i> CSV
                    </button>
                    <button onclick="exportPDF('Customers Report','customersTable')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-red-600 shadow-sm transition-all">
                        <i class="fa-solid fa-file-pdf"></i> PDF
                    </button>
                </div>
            </div>
            <div class="bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left" id="customersTable">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">Customer Name</th>
                                <th class="px-6 py-4">Orders Placed</th>
                                <th class="px-6 py-4">Total Spent</th>
                                <th class="px-6 py-4">Last Activity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($customersReport as $customer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $customer->name }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $customer->orders_count }}</td>
                                <td class="px-6 py-4 font-mono font-medium text-gray-900 dark:text-white">₱{{ number_format($customer->total_spent, 2) }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $customer->last_activity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- 6. TRANSACTION LOGS TAB --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-show="activeTab === 'transactions'" x-transition.opacity style="display:none;">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <input id="search-transactions" type="text" placeholder="Search by user, order ID…"
                    class="w-full sm:w-72 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-[#0B0A0F] text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-violet-400 outline-none">
                <div class="flex gap-2 shrink-0">
                    <button onclick="exportCSV('transactionsTable','Kyusify_Transaction_Logs')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-emerald-600 shadow-sm transition-all">
                        <i class="fa-solid fa-file-csv"></i> CSV
                    </button>
                    <button onclick="exportPDF('Transaction Logs','transactionsTable')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-red-600 shadow-sm transition-all">
                        <i class="fa-solid fa-file-pdf"></i> PDF
                    </button>
                </div>
            </div>
            <div class="bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left" id="transactionsTable">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">Log ID</th>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Action</th>
                                <th class="px-6 py-4">Order ID</th>
                                <th class="px-6 py-4 text-right">Amount</th>
                                <th class="px-6 py-4">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($transactionLogs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                                <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $log->log_id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $log->user }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300"><i class="fa-solid fa-cart-arrow-down text-emerald-500 mr-1"></i>{{ $log->action }}</td>
                                <td class="px-6 py-4 font-bold text-violet-600 dark:text-violet-400">{{ $log->order_id }}</td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-gray-900 dark:text-white">₱{{ number_format($log->amount, 2) }}</td>
                                <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $log->date }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">No transactions recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- 7. ACTIVITY LOGS TAB --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-show="activeTab === 'activity'" x-transition.opacity style="display:none;">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <input id="search-activity" type="text" placeholder="Search by user, action, module…"
                    class="w-full sm:w-72 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-[#0B0A0F] text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-violet-400 outline-none">
                <div class="flex gap-2 shrink-0">
                    <button onclick="exportCSV('activityTable','Kyusify_Activity_Logs')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-emerald-600 shadow-sm transition-all">
                        <i class="fa-solid fa-file-csv"></i> CSV
                    </button>
                    <button onclick="exportPDF('Activity Logs','activityTable')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-red-600 shadow-sm transition-all">
                        <i class="fa-solid fa-file-pdf"></i> PDF
                    </button>
                </div>
            </div>
            <div class="bg-white dark:bg-[#0B0A0F] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left" id="activityTable">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">Log ID</th>
                                <th class="px-6 py-4">Module</th>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Action</th>
                                <th class="px-6 py-4">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($activityLogs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                                <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $log->log_id }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 text-[10px] font-bold uppercase rounded border
                                        {{ $log->module == 'Checkout'
                                            ? 'border-sky-200 text-sky-700 bg-sky-50 dark:bg-sky-900/20'
                                            : ($log->module == 'Products'
                                                ? 'border-fuchsia-200 text-fuchsia-700 bg-fuchsia-50 dark:bg-fuchsia-900/20'
                                                : 'border-gray-200 text-gray-600 bg-gray-50 dark:bg-gray-800') }}">
                                        {{ $log->module }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $log->user }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $log->action }}</td>
                                <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $log->date }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">No activity recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>{{-- /x-data --}}

    {{-- ── Charts JS (amCharts 5) ───────────────────────────────────── --}}
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const textColor   = isDark ? am5.color(0xa0aec0) : am5.color(0x6b7280);
        const gridColor   = isDark ? am5.color(0x1f2937) : am5.color(0xe5e7eb);
        const violetColor = am5.color(0x7c3aed);
        const indigoColor = am5.color(0x6366f1);

        setTimeout(() => {

            // 1. Orders Over Time
            (function () {
                const root = am5.Root.new("chart-orders-time");
                root.setThemes([am5themes_Animated.new(root)]);
                root._logo.dispose();
                const chart = root.container.children.push(am5xy.XYChart.new(root, { panX:false, panY:false, wheelX:"none", wheelY:"none", layout:root.verticalLayout }));
                const xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, { categoryField:"label", renderer:am5xy.AxisRendererX.new(root,{minGridDistance:30}), tooltip:am5.Tooltip.new(root,{}) }));
                xAxis.get("renderer").labels.template.setAll({ fill:textColor, fontSize:11 });
                xAxis.get("renderer").grid.template.setAll({ stroke:gridColor, strokeOpacity:0.5 });
                const yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, { renderer:am5xy.AxisRendererY.new(root,{}) }));
                yAxis.get("renderer").labels.template.setAll({ fill:textColor, fontSize:11 });
                yAxis.get("renderer").grid.template.setAll({ stroke:gridColor, strokeOpacity:0.5 });
                const os = chart.series.push(am5xy.SmoothedXLineSeries.new(root, { name:"Orders", xAxis, yAxis, valueYField:"orders", categoryXField:"label", tooltip:am5.Tooltip.new(root,{labelText:"{name}: {valueY}"}), fill:violetColor, stroke:violetColor }));
                os.fills.template.setAll({ fillOpacity:0.15, visible:true });
                os.strokes.template.setAll({ strokeWidth:2.5 });
                const rs = chart.series.push(am5xy.SmoothedXLineSeries.new(root, { name:"Revenue (₱)", xAxis, yAxis, valueYField:"revenue", categoryXField:"label", tooltip:am5.Tooltip.new(root,{labelText:"{name}: ₱{valueY}"}), fill:indigoColor, stroke:indigoColor }));
                rs.fills.template.setAll({ fillOpacity:0.1, visible:true });
                rs.strokes.template.setAll({ strokeWidth:2, strokeDasharray:[5,3] });
                chart.set("cursor", am5xy.XYCursor.new(root, {behavior:"none"}));
                const legend = chart.children.push(am5.Legend.new(root, { centerX:am5.percent(50), x:am5.percent(50), marginTop:10 }));
                legend.labels.template.setAll({ fill:textColor, fontSize:12 });
                legend.data.setAll(chart.series.values);
                const data = ordersOverTime.length ? ordersOverTime : [{label:'No Data',orders:0,revenue:0}];
                xAxis.data.setAll(data); os.data.setAll(data); rs.data.setAll(data);
                os.appear(1000); rs.appear(1000); chart.appear(1000,100);
            })();

            // 2. Order Status Donut
            (function () {
                const sc = { pending:am5.color(0xf59e0b), processing:am5.color(0x3b82f6), ready:am5.color(0x10b981), completed:am5.color(0x22c55e), cancelled:am5.color(0xef4444) };
                const data = Object.entries(orderStatuses).map(([s,c]) => ({ status:s.charAt(0).toUpperCase()+s.slice(1), count:c, sliceSettings:{ fill:sc[s]??am5.color(0x8b5cf6) } }));
                const root = am5.Root.new("chart-order-status");
                root.setThemes([am5themes_Animated.new(root)]);
                root._logo.dispose();
                const chart = root.container.children.push(am5percent.PieChart.new(root, { innerRadius:am5.percent(60), layout:root.verticalLayout }));
                const series = chart.series.push(am5percent.PieSeries.new(root, { valueField:"count", categoryField:"status", fillField:"sliceSettings" }));
                series.slices.template.setAll({ templateField:"sliceSettings", strokeOpacity:0, cornerRadiusInner:8, cornerRadiusOuter:8 });
                series.labels.template.setAll({ fill:textColor, fontSize:11 });
                series.ticks.template.setAll({ stroke:textColor });
                const total = Object.values(orderStatuses).reduce((a,b)=>a+b,0);
                series.data.setAll(total>0 ? data : [{status:'No Orders',count:1,sliceSettings:{fill:am5.color(0x6b7280)}}]);
                root.container.children.push(am5.Label.new(root, { text:`[bold #7c3aed fontSize:26px]${total}[/]\n[fontSize:11px #6b7280]Total Orders[/]`, textType:"radial", centerX:am5.percent(50), centerY:am5.percent(50), x:am5.percent(50), y:am5.percent(50), textAlign:"center" }));
                series.appear(1000,100);
            })();

            // 3. Top Products Horizontal Bar — valueXField uses 'units' now
            (function () {
                const root = am5.Root.new("chart-top-products");
                root.setThemes([am5themes_Animated.new(root)]);
                root._logo.dispose();
                const chart = root.container.children.push(am5xy.XYChart.new(root, { panX:false, panY:false, wheelX:"none", wheelY:"none", layout:root.verticalLayout }));
                const yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, { categoryField:"name", renderer:am5xy.AxisRendererY.new(root,{inversed:true,minGridDistance:20}) }));
                yAxis.get("renderer").labels.template.setAll({ fill:textColor, fontSize:12, maxWidth:180, oversizedBehavior:"truncate" });
                yAxis.get("renderer").grid.template.setAll({ stroke:gridColor, strokeOpacity:0.4 });
                const xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, { renderer:am5xy.AxisRendererX.new(root,{}), min:0 }));
                xAxis.get("renderer").labels.template.setAll({ fill:textColor, fontSize:11 });
                xAxis.get("renderer").grid.template.setAll({ stroke:gridColor, strokeOpacity:0.4 });
                const series = chart.series.push(am5xy.ColumnSeries.new(root, { xAxis, yAxis, valueXField:"units", categoryYField:"name", tooltip:am5.Tooltip.new(root,{labelText:"{valueX} units sold"}) }));
                series.columns.template.setAll({ height:am5.percent(60), cornerRadiusTR:6, cornerRadiusBR:6, fill:violetColor, strokeOpacity:0 });
                series.columns.template.adapters.add("fill", (fill, target) => am5.color([0x7c3aed,0x6366f1,0x8b5cf6,0xa78bfa,0xc4b5fd][series.columns.indexOf(target)]??0x7c3aed));
                chart.set("cursor", am5xy.XYCursor.new(root,{behavior:"none"}));
                const data = topProducts.length ? topProducts : [{name:'No data',units:0}];
                yAxis.data.setAll(data);
                series.data.setAll(data);
                series.appear(1000); chart.appear(1000,100);
            })();

        }, 150);

        // Live search bindings
        liveSearch('search-products',     'productsTable');
        liveSearch('search-sellers',      'sellersTable');
        liveSearch('search-customers',    'customersTable');
        liveSearch('search-transactions', 'transactionsTable');
        liveSearch('search-activity',     'activityTable');
    });
    </script>
</x-admin-layout>
