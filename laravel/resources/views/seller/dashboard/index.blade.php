<x-seller-layout>
    <x-slot name="header">
        Dashboard Overview
    </x-slot>

    <!-- Welcome Banner Alerts -->
    @if(Auth::user()->enterprise && Auth::user()->enterprise->status === 'pending')
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-900/50 rounded-2xl p-6 mb-8 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 text-yellow-500/10 dark:text-yellow-500/5">
            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="relative z-10 flex items-start space-x-4">
            <div class="bg-yellow-100 text-yellow-600 dark:bg-yellow-900/50 dark:text-yellow-400 p-3 rounded-xl shadow-sm shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-500 tracking-tight">Your Action Required: Pending Approval</h3>
                <p class="text-yellow-700 dark:text-yellow-600 mt-1 mb-3 text-sm max-w-2xl">Welcome to Kyusify! To start selling to the QCU campus, an Administrator must approve your enterprise application. You may prepare and list your products now, but they will not be visible to customers until approval.</p>
                <div class="flex space-x-3">
                    <a href="#" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                        Complete Store Profile
                    </a>
                    <a href="#" class="inline-flex items-center px-4 py-2 bg-white dark:bg-[#13111C] border border-yellow-200 dark:border-yellow-900/50 text-yellow-700 dark:text-yellow-500 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 text-sm font-semibold rounded-lg transition-colors">
                        View Application Status
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-[#13111C] p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 transition-transform duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 text-blue-500 dark:text-blue-400 rounded-xl flex items-center justify-center border border-blue-100 dark:border-blue-900/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">+12%</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1 tracking-tight">{{ $listedProducts }}</h3>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Listed Products</p>
        </div>

        <div class="bg-white dark:bg-[#13111C] p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 transition-transform duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-violet-50 dark:bg-violet-900/20 text-violet-500 dark:text-violet-400 rounded-xl flex items-center justify-center border border-violet-100 dark:border-violet-900/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 rounded-full">New!</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1 tracking-tight">{{ $pendingOrders }}</h3>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pending Orders</p>
        </div>

        <div class="bg-white dark:bg-[#13111C] p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 transition-transform duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 dark:text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-100 dark:border-emerald-900/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">+₱0 This Week</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1 tracking-tight">₱{{ number_format($totalRevenue, 2) }}</h3>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Revenue</p>
        </div>

        <div class="bg-white dark:bg-[#13111C] p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 transition-transform duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-50 dark:bg-orange-900/20 text-orange-500 dark:text-orange-400 rounded-xl flex items-center justify-center border border-orange-100 dark:border-orange-900/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 rounded-full">0 Unread</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1 tracking-tight">{{ $customerInquiries }}</h3>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Customer Inquiries</p>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white dark:bg-[#13111C] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800/60 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/30">
                <h3 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white flex items-center">
                    <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                    </svg>
                    Recent Customer Orders
                </h3>
                <a href="{{ route('seller.orders.index') }}" class="text-sm font-semibold text-violet-600 hover:text-violet-700 dark:text-violet-400 dark:hover:text-violet-300 transition-colors">View All</a>
            </div>
            @if($recentOrders->count() > 0)
                <div class="flex-1 w-full flex flex-col">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 dark:bg-gray-800/30 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider">
                                    <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">Order ID</th>
                                    <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">Customer</th>
                                    <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">Items</th>
                                    <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">Total</th>
                                    <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors cursor-pointer" onclick="window.location='{{ route('seller.orders.show', $order) }}'">
                                        <td class="px-6 py-4 font-mono font-medium text-gray-900 dark:text-white">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $order->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $order->items->sum('quantity') }} items</td>
                                        <td class="px-6 py-4 font-mono font-bold text-gray-900 dark:text-white">₱{{ number_format($order->total_amount, 2) }}</td>
                                        <td class="px-6 py-4">
                                            @if($order->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                    Pending
                                                </span>
                                            @elseif($order->status === 'processing')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                    Processing
                                                </span>
                                            @elseif($order->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    Completed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="flex-1 p-8 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mb-4 border border-gray-100 dark:border-gray-800">
                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white mb-2">No active orders yet</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mb-6">List your first products to start generating interest and taking orders from fellow QCU students.</p>
                    <a href="{{ route('seller.products.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-violet-500/30 transition-all duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add a New Product
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Actions & Notifications -->
        <div class="flex flex-col space-y-6">
            <!-- Notifications Panel -->
            <div class="bg-white dark:bg-[#13111C] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 overflow-hidden flex-1">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800/60 bg-gray-50/50 dark:bg-gray-900/30 flex items-center justify-between">
                    <h3 class="text-base font-bold tracking-tight text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        System Notifications
                    </h3>
                </div>
                <div class="p-0">
                    <div class="divide-y divide-gray-100 dark:divide-gray-800/60">
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors flex items-start space-x-3 cursor-pointer group">
                             <div class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-500 rounded-lg shrink-0 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/40 transition-colors">
                                 <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                             </div>
                             <div>
                                 <p class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Welcome to Kyusify!</p>
                                 <p class="text-xs text-gray-500 dark:text-gray-400">Complete your business profile to verify your QCU student status.</p>
                                 <span class="text-[10px] uppercase font-bold text-gray-400 mt-2 block">Just now</span>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Store Rating Summary -->
            <div class="bg-gradient-to-br from-violet-600 to-violet-900 rounded-2xl shadow-lg border border-violet-500 overflow-hidden relative p-6 text-white">
                <div class="absolute -right-6 -bottom-6 opacity-20">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-sm font-bold tracking-wider uppercase text-violet-200 mb-1">Store Rating</h3>
                    <div class="flex items-end space-x-2">
                        <span class="text-4xl font-bold tracking-tighter">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-lg text-violet-300 pb-1">/ 5</span>
                    </div>
                    <div class="flex mt-3 space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($averageRating))
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            @else
                            <svg class="w-5 h-5 text-violet-400/50" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            @endif
                        @endfor
                    </div>
                    @if($averageRating > 0)
                    <p class="mt-4 text-xs font-medium text-violet-100">Keep up the great work!</p>
                    @else
                    <p class="mt-4 text-xs font-medium text-violet-100 italic">"No ratings yet. Deliver great service to get your first review!"</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-seller-layout>
