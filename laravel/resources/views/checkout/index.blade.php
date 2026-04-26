<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth bg-gray-50 dark:bg-[#13111C]">
<head>
    <link rel="icon" href="{{ asset('images/kyusify-logo.ico') }}" type="image/x-icon">
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

    <!-- Navbar -->
    <div class="fixed top-0 left-0 right-0 z-50 bg-[#0B0A0F] border-b border-gray-800">
        <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-3 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-8 h-8 object-contain rounded-lg">
                <span class="text-xl font-extrabold tracking-tight text-white hover:text-violet-300 transition-colors">Kyusify</span>
            </a>
            <div class="flex items-center space-x-6">
                <a href="{{ route('cart.index') }}" class="text-gray-300 hover:text-white font-medium px-2 py-2 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-sm"></i> Back to Cart
                </a>
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

                <!-- My Socials Box -->
                <div class="bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-2xl p-6 md:p-8 shadow-sm">
                    <h2 class="text-xl font-bold mb-2 text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-share-nodes text-violet-500"></i> My Socials
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">
                        Share your socials so the seller can reach you easily about your order. Enter the full link (URL) to your profile.
                    </p>

                    <div class="space-y-5">
                        <!-- Facebook -->
                        <div class="space-y-2">
                            <label for="social_facebook" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-brands fa-facebook text-blue-500 mr-1"></i> Facebook Profile Link
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-500">
                                    <i class="fa-brands fa-facebook text-lg"></i>
                                </span>
                                <input
                                    type="url"
                                    id="social_facebook"
                                    name="social_facebook"
                                    value="{{ old('social_facebook') }}"
                                    placeholder="https://facebook.com/yourname"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-shadow @error('social_facebook') border-red-500 @enderror"
                                >
                            </div>
                            @error('social_facebook')
                                <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Messenger -->
                        <div class="space-y-2">
                            <label for="social_messenger" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-brands fa-facebook-messenger text-violet-500 mr-1"></i> Messenger Link
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-violet-500">
                                    <i class="fa-brands fa-facebook-messenger text-lg"></i>
                                </span>
                                <input
                                    type="url"
                                    id="social_messenger"
                                    name="social_messenger"
                                    value="{{ old('social_messenger') }}"
                                    placeholder="https://m.me/yourname"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-shadow @error('social_messenger') border-red-500 @enderror"
                                >
                            </div>
                            @error('social_messenger')
                                <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-start gap-2 mt-2 p-3 bg-violet-50 dark:bg-violet-900/20 rounded-xl border border-violet-100 dark:border-violet-800/40">
                            <i class="fa-solid fa-circle-info text-violet-500 mt-0.5 shrink-0"></i>
                            <p class="text-xs text-violet-700 dark:text-violet-300 leading-relaxed">
                                Your social links will only be shared with the seller you're ordering from, so they can coordinate delivery with you.
                            </p>
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
                                        <img src="{{ \App\Helpers\ImageHelper::url($item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
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

                    <button id="checkout-submit-btn" type="submit" class="w-full bg-violet-600 hover:bg-violet-700 focus:ring-4 focus:ring-violet-500/20 text-white font-bold py-4 rounded-xl shadow-[0_8px_30px_rgb(139,92,246,0.3)] hover:shadow-[0_8px_30px_rgb(139,92,246,0.5)] transition-all hover:-translate-y-1 flex items-center justify-center gap-3">
                        Confirm Purchase <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-4 leading-relaxed">
                        <i class="fa-solid fa-lock text-[10px] mr-1"></i> Your information is secure. By confirming, your order will be sent to the student sellers immediately.
                    </p>
                </div>
            </div>
            
        </form>

    <script>
        // Only show "Processing..." AFTER the form passes all validation (submit event fires post-validation)
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            var btn = document.getElementById('checkout-submit-btn');
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Processing...';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        });
    </script>
    </main>
</body>
</html>
