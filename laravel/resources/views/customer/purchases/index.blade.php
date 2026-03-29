<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth bg-gray-50 dark:bg-[#13111C]">
<head>
    <link rel="icon" href="{{ asset('images/kyusify-logo.ico') }}" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Kyusify') }} - My Purchases</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans text-gray-900 dark:text-gray-100 antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <div class="fixed top-0 left-0 right-0 z-50 bg-[#0B0A0F] border-b border-gray-800">
        <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-3 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-8 h-8 object-contain rounded-lg">
                <span class="text-xl font-extrabold tracking-tight text-white hover:text-violet-300 transition-colors">Kyusify</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="{{ route('discover') }}" class="text-gray-400 hover:text-white text-sm font-medium transition-colors">Discover</a>
                <a href="{{ route('cart.index') }}" class="text-gray-400 hover:text-white text-sm font-medium transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-bag-shopping"></i> Cart
                </a>
                <a href="{{ route('purchases.index') }}" class="text-violet-400 font-semibold text-sm border-b-2 border-violet-500 pb-0.5">
                    <i class="fa-solid fa-box-open mr-1"></i> My Purchases
                </a>
            </div>
        </div>
    </div>

    <main class="flex-1 max-w-4xl mx-auto w-full px-4 py-8 mt-20 pt-14">

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-box-open text-violet-500 text-lg"></i>
                </span>
                My Purchases
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Track all your orders and their current statuses.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @forelse($orders as $order)
            <!-- Order Card -->
            <div class="bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm mb-5 overflow-hidden hover:border-violet-300 dark:hover:border-violet-700 transition-colors">

                <!-- Order Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between px-6 py-4 bg-gray-50/70 dark:bg-gray-900/30 border-b border-gray-100 dark:border-gray-800 gap-3">
                    <div class="flex items-center gap-4">
                        <div>
                            <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Order</span>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h3>
                        </div>
                        <div class="h-8 w-px bg-gray-200 dark:bg-gray-800"></div>
                        <div>
                            <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Store</span>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $order->enterprise->name ?? 'Unknown Store' }}</p>
                        </div>
                        <div class="h-8 w-px bg-gray-200 dark:bg-gray-800"></div>
                        <div>
                            <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Date</span>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        @php
                            $statusMap = [
                                'pending'    => ['bg-yellow-50 text-yellow-700 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800', 'fa-clock',             'Pending'],
                                'processing' => ['bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800',       'fa-rotate',            'Processing'],
                                'ready'      => ['bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800', 'fa-truck', 'Ready for Pickup'],
                                'completed'  => ['bg-green-50 text-green-700 border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800',  'fa-circle-check',      'Completed'],
                                'cancelled'  => ['bg-red-50 text-red-700 border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800',              'fa-circle-xmark',      'Cancelled'],
                            ];
                            [$badgeClass, $icon, $label] = $statusMap[$order->status] ?? ['bg-gray-50 text-gray-600 border-gray-200', 'fa-question', ucfirst($order->status)];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border {{ $badgeClass }}">
                            <i class="fa-solid {{ $icon }}"></i>
                            {{ $label }}
                        </span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="divide-y divide-gray-100 dark:divide-gray-800/60">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 px-6 py-4">
                            <div class="w-14 h-14 bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden shrink-0 border border-gray-200 dark:border-gray-700">
                                @if($item->product && $item->product->image_path)
                                    <img src="{{ Storage::url($item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $item->product ? $item->product->name : 'Product no longer available' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    ₱{{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}
                                </p>
                            </div>
                            <div class="text-sm font-bold text-gray-900 dark:text-white font-mono shrink-0">
                                ₱{{ number_format($item->subtotal, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Footer -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-4 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-100 dark:border-gray-800 gap-3">
                    <!-- Status Timeline Hint -->
                    <div class="flex items-center gap-2">
                        @php
                            $steps = ['pending', 'processing', 'ready', 'completed'];
                            $currentStep = array_search($order->status, $steps);
                            $isCancelled = $order->status === 'cancelled';
                        @endphp
                        @if(!$isCancelled)
                            @foreach($steps as $i => $step)
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 rounded-full {{ $currentStep !== false && $i <= $currentStep ? 'bg-violet-500' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                                    @if(!$loop->last)
                                        <div class="w-6 h-px {{ $currentStep !== false && $i < $currentStep ? 'bg-violet-400' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                                    @endif
                                </div>
                            @endforeach
                            <span class="text-xs text-gray-400 dark:text-gray-500 ml-2">
                                @if($order->status === 'completed') Order delivered ✓
                                @elseif($order->status === 'ready') Seller is ready for pickup
                                @elseif($order->status === 'processing') Seller is preparing your order
                                @else Waiting for seller to confirm
                                @endif
                            </span>
                        @else
                            <span class="text-xs text-red-500 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-xmark"></i> This order was cancelled
                            </span>
                        @endif
                    </div>

                    <!-- Total -->
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total:</span>
                        <span class="text-lg font-black text-violet-600 dark:text-violet-400 font-mono">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-2xl">
                <div class="w-20 h-20 bg-violet-50 dark:bg-violet-900/20 rounded-full flex items-center justify-center mx-auto mb-5">
                    <i class="fa-solid fa-box-open text-violet-400 text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No purchases yet</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-6 text-sm">You haven't placed any orders yet. Start shopping to see your orders here.</p>
                <a href="{{ route('discover') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Browse Products
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif

    </main>
</body>
</html>
