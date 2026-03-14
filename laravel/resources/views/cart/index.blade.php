<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-[#13111C]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kyusify') }} - Shopping Bag</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Alpine instance for UI manipulation -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased h-full">

        <div class="fixed top-0 left-0 right-0 z-50 bg-[#0B0A0F] border-b border-gray-800 hidden md:block">
            <div class="bg-violet-600 text-white py-2 px-4 text-center text-sm font-medium tracking-wide">
                Welcome to Kyusify! The student enterprise platform for QCU.
            </div>
            
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-4 flex items-center justify-between">
                <a href="{{ route('landing') }}" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg p-0.5">
                        <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-white hover:text-gray-200 transition-colors">Kyusify</span>
                </a>
               
                <div class="flex items-center space-x-6">
                    <a href="{{ route('discover') }}" class="text-gray-300 hover:text-white font-medium px-2 py-2 transition-colors">Discover</a>
                    <a href="{{ route('cart.index') }}" class="text-white hover:text-violet-400 font-medium transition-colors flex items-center">
                        <svg class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Bag
                    </a>
                </div>
            </div>
        </div>

        <main class="max-w-4xl mx-auto px-4 py-8 md:py-32" x-data="cartCheckout()">
            <h1 class="text-3xl font-bold mb-8 flex items-center gap-3">
                <svg class="w-8 h-8 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Your Shopping Bag
            </h1>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <div class="lg:col-span-2 space-y-6">
                    @forelse($groupedCart as $storeName => $items)
                        <div class="bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm">
                            <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                                <h3 class="font-bold flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ $storeName }}
                                </h3>
                            </div>
                            
                            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($items as $item)
                                    <div class="p-6 flex items-start gap-4">
                                        <!-- Checkbox for checkout -->
                                        <div class="pt-1">
                                            <input type="checkbox" 
                                                   class="w-5 h-5 border-gray-300 rounded text-violet-600 focus:ring-violet-500 bg-gray-50 dark:bg-[#13111C] dark:border-gray-700 item-checkbox"
                                                   data-price="{{ $item->product->price }}"
                                                   data-id="{{ $item->id }}"
                                                   x-model="checkedItems[{{ $item->id }}]"
                                                   x-on:change="calculateTotal()"
                                            >
                                        </div>
                                        
                                        <!-- Product Image -->
                                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden shrink-0 border border-gray-200 dark:border-gray-700">
                                            @if($item->product->image_path)
                                                <img src="{{ Storage::url($item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1">
                                            <h4 class="font-bold text-lg mb-1">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 mb-2">{{ $item->product->description }}</p>
                                            <div class="text-violet-600 dark:text-violet-400 font-bold mb-3 font-mono">
                                                ₱{{ number_format($item->product->price, 2) }}
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <!-- Quantity Adjuster -->
                                                <div class="flex items-center border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden bg-gray-50 dark:bg-[#0B0A0F]">
                                                    <button type="button" 
                                                            class="px-3 py-1 text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-800"
                                                            x-on:click="updateQty({{ $item->id }}, -1, {{ $item->product->stock }}, {{ $item->product->price }})">
                                                        -
                                                    </button>
                                                    <input type="number" 
                                                           id="qty-{{ $item->id }}"
                                                           value="{{ $item->quantity }}" 
                                                           class="w-12 text-center bg-transparent border-0 text-sm font-medium focus:ring-0 p-0"
                                                           readonly>
                                                    <button type="button" 
                                                            class="px-3 py-1 text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-800"
                                                            x-on:click="updateQty({{ $item->id }}, 1, {{ $item->product->stock }}, {{ $item->product->price }})">
                                                        +
                                                    </button>
                                                </div>

                                                <!-- Delete Button -->
                                                <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-2" title="Remove item">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-2xl">
                            <div class="w-24 h-24 bg-gray-50 dark:bg-[#0B0A0F] rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold mb-2">Your bag is empty</h2>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">Looks like you haven't added anything to your cart yet.</p>
                            <a href="{{ route('discover') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-medium px-6 py-3 rounded-xl transition-colors">Start Shopping</a>
                        </div>
                    @endforelse
                </div>

                <!-- Order Summary Sidebar -->
                <div class="bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sticky top-32">
                    <h3 class="text-lg font-bold mb-6">Order Summary</h3>
                    
                    <div class="space-y-4 text-sm mb-6">
                        <div class="flex justify-between items-center text-gray-500 dark:text-gray-400">
                            <span>Selected Items</span>
                            <span x-text="selectedCount">0</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-500 dark:text-gray-400">
                            <span>Subtotal</span>
                            <span class="font-mono">₱<span x-text="subtotal.toFixed(2)">0.00</span></span>
                        </div>
                        <div class="border-t border-gray-100 dark:border-gray-800 pt-4 flex justify-between items-center font-bold text-lg">
                            <span>Total</span>
                            <span class="text-violet-600 dark:text-violet-400 font-mono">₱<span x-text="total.toFixed(2)">0.00</span></span>
                        </div>
                    </div>

                    <button type="button" 
                            class="w-full bg-black dark:bg-white text-white dark:text-black font-bold py-4 rounded-xl shadow-lg transition-all hover:scale-[1.02] disabled:opacity-50 disabled:hover:scale-100 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                            x-bind:disabled="selectedCount === 0"
                            >
                        Proceed to Checkout
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </main>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('cartCheckout', () => ({
                    checkedItems: {},
                    selectedCount: 0,
                    subtotal: 0,
                    total: 0,
                    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                    init() {
                        // Initialize all items as unchecked, or true if you want default checked.
                        document.querySelectorAll('.item-checkbox').forEach(box => {
                            this.checkedItems[box.dataset.id] = false;
                        });
                    },

                    calculateTotal() {
                        let currentSubtotal = 0;
                        let count = 0;
                        
                        document.querySelectorAll('.item-checkbox').forEach(box => {
                            const id = box.dataset.id;
                            if (this.checkedItems[id]) {
                                const price = parseFloat(box.dataset.price);
                                const qty = parseInt(document.getElementById('qty-' + id).value);
                                currentSubtotal += (price * qty);
                                count += qty;
                            }
                        });

                        this.subtotal = currentSubtotal;
                        this.total = currentSubtotal; // Add tax/shipping logic here if needed
                        this.selectedCount = count;
                    },

                    async updateQty(id, delta, max, price) {
                        const input = document.getElementById('qty-' + id);
                        let val = parseInt(input.value) + delta;
                        
                        // Bounds check
                        if (val < 1) val = 1;
                        if (val > max) val = max;
                        
                        if(input.value != val) {
                            input.value = val;
                            this.calculateTotal(); // Update UI immediately

                            // Persist to backend
                            try {
                                await fetch(`/cart/${id}`, {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': this.csrfToken,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ quantity: val })
                                });
                            } catch (e) {
                                console.error('Failed to update cart quantity', e);
                            }
                        }
                    }
                }))
            })
        </script>
    </body>
</html>
