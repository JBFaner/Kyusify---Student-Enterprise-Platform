<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth bg-gray-50 dark:bg-[#13111C]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kyusify') }} - Checkout</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans text-gray-900 dark:text-gray-100 antialiased min-h-screen flex flex-col">

    <div class="fixed top-0 left-0 right-0 z-50 bg-[#0B0A0F] border-b border-gray-800">
        <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-4 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg p-0.5">
                    <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-2xl font-bold tracking-tight text-white hover:text-gray-200 transition-colors">Kyusify</span>
            </a>
            
            <div class="flex items-center space-x-6">
                <a href="{{ route('cart.index') }}" class="text-gray-300 hover:text-white font-medium px-2 py-2 transition-colors">Back to Cart</a>
            </div>
        </div>
    </div>

    <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8 mt-20 pt-12 md:pt-16">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <i class="fa-solid fa-credit-card text-violet-500"></i> Secure Checkout
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Complete your contact and shipping information to finalize your order.</p>
        </div>

        @if($errors->any())
            <div class="mb-8 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 rounded-xl">
                <div class="font-bold flex items-center gap-2 mb-2">
                    <i class="fa-solid fa-circle-exclamation"></i> Please fix the following errors:
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf
            
            <!-- Hidden Selected Items list -->
            @foreach($cartItems as $item)
                <input type="hidden" name="items[]" value="{{ $item->id }}">
            @endforeach

            <div class="lg:col-span-7 space-y-8">
                <!-- Shipping details box -->
                <div class="bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-2xl p-6 md:p-8 shadow-sm">
                    <h2 class="text-xl font-bold mb-6 pb-4 border-b border-gray-100 dark:border-gray-800 text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-truck-fast text-violet-500"></i> Shipping Information
                    </h2>
                    
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label for="shipping_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Recipient Name *</label>
                                <input type="text" id="shipping_name" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required class="w-full px-4 py-3 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-shadow">
                            </div>
                            
                            <div class="space-y-2">
                                <label for="contact_number" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Contact Number *</label>
                                <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required placeholder="e.g. 0912 345 6789" class="w-full px-4 py-3 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-shadow">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="shipping_address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Delivery Address *</label>
                            <textarea id="shipping_address" name="shipping_address" rows="3" required placeholder="Specify your building, room number, or meetup place inside QCU..." class="w-full px-4 py-3 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-shadow resize-none">{{ old('shipping_address') }}</textarea>
                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">Most sellers deliver inside campus grounds. Be as specific as possible.</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Details Box -->
                <div class="bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-2xl p-6 md:p-8 shadow-sm">
                    <h2 class="text-xl font-bold mb-6 pb-4 border-b border-gray-100 dark:border-gray-800 text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-wallet text-violet-500"></i> Payment Method
                    </h2>

                    <div class="space-y-3">
                        <label class="relative flex cursor-pointer rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#13111C] p-4 shadow-sm focus-within:ring-2 focus-within:ring-violet-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <input type="radio" name="payment_method" value="cash_on_delivery" checked class="sr-only" aria-labelledby="payment-cod">
                            <div class="flex items-center h-5 mt-0.5">
                                <div class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 bg-white flex items-center justify-center shrink-0">
                                    <div class="w-2.5 h-2.5 rounded-full bg-violet-600 block"></div>
                                </div>
                            </div>
                            <div class="ml-4 flex flex-col">
                                <span id="payment-cod" class="font-bold text-gray-900 dark:text-white">Cash on Delivery (COD)</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Pay directly when the item is handed over to you on campus.</span>
                            </div>
                        </label>

                        <!-- Stubs for future payment methods -->
                        <div class="opacity-50 relative flex rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#13111C] p-4">
                            <div class="flex items-center h-5 mt-0.5">
                                <div class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 bg-white shrink-0"></div>
                            </div>
                            <div class="ml-4 flex flex-col">
                                <span class="font-bold text-gray-900 dark:text-white items-center flex gap-2">GCash <span class="text-[10px] bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-300 px-2 py-0.5 rounded font-bold uppercase tracking-wider">Coming Soon</span></span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Not supported yet by the selected store(s).</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Confirmation Sidebar -->
            <div class="lg:col-span-5 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm sticky top-24 overflow-hidden">
                <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/20">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-basket-shopping text-violet-500"></i> Review Your Cart
                    </h2>
                </div>

                <div class="p-6 md:p-8 max-h-[40vh] overflow-y-auto">
                    <div class="space-y-6">
                        @foreach($cartItems as $item)
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden shrink-0 border border-gray-200 dark:border-gray-700 relative">
                                    @if($item->product->image_path)
                                        <img src="{{ Storage::url($item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                    <!-- Badge for quantity -->
                                    <span class="absolute -top-2 -right-2 bg-gray-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white dark:border-[#0B0A0F]">
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-white line-clamp-2 leading-snug">{{ $item->product->name }}</h4>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1 border-l-2 border-violet-500 pl-2 ml-1">Store: {{ $item->product->enterprise->name ?? 'Unknown Store' }}</div>
                                </div>
                                <div class="font-bold text-gray-900 dark:text-white shrink-0 font-mono text-sm">
                                    ₱{{ number_format($item->product->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-6 md:p-8 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-100 dark:border-gray-800">
                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex justify-between items-center text-gray-500 dark:text-gray-400 font-medium">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span class="font-mono text-gray-900 dark:text-white">₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-500 dark:text-gray-400 font-medium">
                            <span>Platform Fee</span>
                            <span class="font-mono text-green-600 dark:text-green-400 uppercase font-black tracking-wider text-xs">Free</span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4 flex justify-between items-center">
                            <span class="font-bold text-lg text-gray-900 dark:text-white">Total Amount</span>
                            <span class="font-black text-2xl text-violet-600 dark:text-violet-400 font-mono flex items-center gap-1">
                                <span class="text-lg">₱</span>{{ number_format($total, 2) }}
                            </span>
                        </div>
                    </div>

                    <button type="submit" onclick="this.innerHTML='<i class=\'fa-solid fa-circle-notch fa-spin\'></i> Processing...'; setTimeout(() => { this.disabled=true; }, 50)" class="w-full bg-violet-600 hover:bg-violet-700 focus:ring-4 focus:ring-violet-500/20 text-white font-bold py-4 rounded-xl shadow-[0_8px_30px_rgb(139,92,246,0.3)] hover:shadow-[0_8px_30px_rgb(139,92,246,0.5)] transition-all hover:-translate-y-1 flex items-center justify-center gap-3">
                        Confirm Purchase <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-4 leading-relaxed">
                        <i class="fa-solid fa-lock text-[10px] mr-1"></i> Your information is secure. By confirming, your order will be sent to the student sellers immediately.
                    </p>
                </div>
            </div>
            
        </form>
    </main>
</body>
</html>
