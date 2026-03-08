<x-seller-layout>
    <x-slot name="header">
        Order Details
    </x-slot>

    <!-- Header Actions -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('seller.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Orders
        </a>
    </div>

    <!-- Order Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Items and Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Order Items Card -->
            <div class="bg-white dark:bg-[#13111C] shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] rounded-2xl border border-gray-100 dark:border-gray-800/60 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800/60 flex justify-between items-center bg-gray-50/50 dark:bg-white/[0.02]">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Order Items</h3>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $order->items->count() }} Items</span>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800/60">
                    @foreach($order->items as $item)
                        <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <!-- Product Image -->
                            <div class="h-16 w-16 flex-shrink-0 rounded-xl bg-gray-100 dark:bg-gray-800 overflow-hidden border border-gray-200 dark:border-gray-700">
                                @if($item->product && $item->product->image_path)
                                    <img src="{{ Storage::url($item->product->image_path) }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-400">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $item->product ? $item->product->name : 'Unknown Product' }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">₱{{ number_format($item->unit_price, 2) }} x {{ $item->quantity }}</p>
                            </div>
                            
                            <!-- Subtotal -->
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">₱{{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Order Total -->
                <div class="bg-gray-50/50 dark:bg-[#13111C] px-6 py-5 border-t border-gray-100 dark:border-gray-800/60 flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</span>
                    <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-violet-600 to-indigo-600 dark:from-violet-400 dark:to-indigo-400">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Customer Notes -->
            @if($order->notes)
            <div class="bg-white dark:bg-[#13111C] shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] rounded-2xl border border-gray-100 dark:border-gray-800/60 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Customer Notes</h3>
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/10 rounded-xl border border-yellow-100 dark:border-yellow-900/30">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">{{ $order->notes }}</p>
                </div>
            </div>
            @endif

        </div>

        <!-- Right Column: Status & Customer Info -->
        <div class="space-y-6 border-t lg:border-t-0 pt-6 lg:pt-0 border-gray-100 dark:border-gray-800">
            
            <!-- Order Status Manager -->
            <div class="bg-white dark:bg-[#13111C] shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] rounded-2xl border border-gray-100 dark:border-gray-800/60 p-6 overflow-hidden relative">
                <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
                    <svg class="w-24 h-24 text-violet-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                
                <form action="{{ route('seller.orders.update', $order) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Update Status</label>
                        <select id="status" name="status" class="block w-full pl-3 pr-10 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500 shadow-sm transition-all appearance-none">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready for Pickup</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-[#13111C] shadow-lg shadow-violet-500/30 transition-all duration-300">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Customer Details -->
            <div class="bg-white dark:bg-[#13111C] shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] rounded-2xl border border-gray-100 dark:border-gray-800/60 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Customer
                </h3>
                <div class="flex items-center space-x-4 mb-4">
                    <div class="h-10 w-10 rounded-full bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 dark:text-violet-400 font-bold text-sm shrink-0">
                        {{ substr($order->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->user->email }}</p>
                    </div>
                </div>
                
                @if($order->payment_method)
                <div class="pt-4 border-t border-gray-100 dark:border-gray-800 border-dashed">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Payment Method</p>
                    <p class="text-sm text-gray-800 dark:text-gray-200">{{ ucfirst($order->payment_method) }}</p>
                </div>
                @endif
            </div>

        </div>

    </div>
</x-seller-layout>
