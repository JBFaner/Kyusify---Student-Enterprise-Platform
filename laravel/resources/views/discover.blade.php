<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kyusify') }} &mdash; Discover</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/kyusify-logo.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #000; color: #fff; }
        .btn-gumroad {
            background-color: #fce7f3;
            color: #000;
            border: 2px solid #000;
            box-shadow: 4px 4px 0px #000;
            transition: transform 0.1s, box-shadow 0.1s;
            border-radius: 8px;
            font-weight: 700;
        }
        .btn-gumroad:hover {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px #000;
            background-color: #fbcfe8;
        }
        .btn-gumroad-violet {
            background-color: #8b5cf6;
            color: #fff;
            border: 2px solid #000;
            box-shadow: 4px 4px 0px #000;
            transition: transform 0.1s, box-shadow 0.1s;
            border-radius: 8px;
            font-weight: 700;
        }
        .btn-gumroad-violet:hover {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px #000;
            background-color: #7c3aed;
        }
        .card-gumroad {
            background-color: #1a1a1a;
            border: 2px solid #333;
            border-radius: 12px;
            transition: transform 0.2s, border-color 0.2s;
        }
        .card-gumroad:hover {
            transform: translateY(-4px);
            border-color: #8b5cf6;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #000; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: #8b5cf6; }
    </style>
</head>
<body class="antialiased selection:bg-violet-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 top-0 transition-all duration-300 bg-black/80 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ route('landing') }}" class="flex-shrink-0 cursor-pointer flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center border-2 border-white/20 overflow-hidden p-0.5">
                        <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify" class="w-full h-full object-contain">
                    </div>
                    <span class="text-white font-bold text-2xl tracking-tight">Kyusify</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('discover') }}" class="text-violet-400 font-bold transition-colors">Discover</a>
                    <a href="{{ url('/#about') }}" class="text-gray-300 hover:text-white font-medium transition-colors">About</a>
                    <button onclick="alert('Pricing plans will be implemented in a future update!')" class="text-gray-300 hover:text-white font-medium transition-colors">Pricing</button>
                    <a href="{{ route('seller.login') }}" class="text-gray-300 hover:text-white font-medium transition-colors">Log in</a>
                    <a href="{{ route('seller.register') }}" class="btn-gumroad-violet px-6 py-2.5">Start Selling</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-300 hover:text-white focus:outline-none">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" style="display: none;" x-transition class="md:hidden bg-[#111] border-b border-white/10 absolute w-full left-0 top-20">
            <div class="px-6 pt-4 pb-6 space-y-4">
                <a href="{{ route('discover') }}" @click="mobileMenuOpen = false" class="block text-violet-400 font-bold text-lg">Discover</a>
                <a href="{{ url('/#about') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-white font-medium text-lg">About</a>
                <button @click="alert('Pricing plans will be implemented in a future update!'); mobileMenuOpen = false" class="block text-gray-300 hover:text-white font-medium text-lg text-left w-full">Pricing</button>
                <div class="pt-4 border-t border-white/10 flex flex-col gap-3">
                    <a href="{{ route('seller.login') }}" class="block text-center text-gray-300 hover:text-white font-medium py-2">Log in</a>
                    <a href="{{ route('seller.register') }}" class="btn-gumroad-violet px-6 py-3 text-center block w-full">Start Selling</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Discover Section -->
    <section class="min-h-screen pt-32 pb-24 bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            
            <div class="mb-12 border-b border-[#222] pb-6">
                <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-4">Discover</h1>
                <p class="text-gray-400 text-lg md:text-xl max-w-2xl">Find the best physical products, digital goods, and services created by QCU students.</p>
            </div>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar Filters -->
                <div class="w-full md:w-64 flex-shrink-0">
                    <div class="sticky top-28 bg-[#151515] border border-[#333] rounded-xl p-5">
                        <h3 class="font-bold text-lg mb-4 text-white">Categories</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('discover') }}" class="block px-3 py-2 rounded-lg transition-colors {{ !request('category') ? 'bg-violet-600/20 text-violet-400 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                                    All Products
                                </a>
                            </li>
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('discover', ['category' => $category]) }}" class="block px-3 py-2 rounded-lg transition-colors {{ request('category') === $category ? 'bg-violet-600/20 text-violet-400 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                                        {{ $category }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="flex-1">
                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <div class="card-gumroad overflow-hidden flex flex-col group cursor-pointer h-full">
                                    <!-- Product Image -->
                                    <div class="w-full h-48 bg-gray-900 border-b border-[#333] relative overflow-hidden">
                                        @if($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-700 bg-[#111]">
                                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                            @if($product->enterprise && $product->enterprise->store_branding)
                                                <img src="{{ Storage::url($product->enterprise->store_branding) }}" alt="{{ $product->enterprise->name }}" class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-overlay group-hover:scale-105 transition-transform duration-500">
                                            @endif
                                        @endif
                                        <div class="absolute top-3 left-3 bg-violet-600 text-white text-xs font-bold px-2 py-1 rounded">
                                            {{ $product->category ?? 'Digital' }}
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-5 flex flex-col flex-1 relative">
                                        <h3 class="font-bold text-lg text-white mb-1 line-clamp-2 leading-tight">{{ $product->name }}</h3>
                                        
                                        <div class="flex items-center gap-2 mb-4 mt-1">
                                            <div class="w-5 h-5 rounded-full bg-gray-800 border border-gray-700 overflow-hidden flex-shrink-0">
                                                @if($product->enterprise && $product->enterprise->logo_path)
                                                    <img src="{{ Storage::url($product->enterprise->logo_path) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-violet-900 flex items-center justify-center text-[8px] font-bold text-white">{{ substr($product->enterprise->name ?? 'A', 0, 1) }}</div>
                                                @endif
                                            </div>
                                            <span class="text-sm text-gray-400 hover:text-white transition-colors truncate">{{ $product->enterprise->name ?? 'Unknown Store' }}</span>
                                        </div>

                                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-[#333]">
                                            <span class="font-bold text-xl text-white">₱{{ number_format($product->price, 2) }}</span>
                                            <button class="bg-white/10 hover:bg-white/20 text-white p-2 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-24 px-6 border-2 border-dashed border-[#333] rounded-2xl bg-[#111]">
                            <div class="w-16 h-16 bg-[#1a1a1a] rounded-full flex items-center justify-center mx-auto mb-4 border border-[#333]">
                                <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">No products found.</h3>
                            <p class="text-gray-400">Try selecting a different category or check back later.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black py-12 border-t border-[#222]">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center overflow-hidden p-0.5">
                    <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify" class="w-full h-full object-contain">
                </div>
                <span class="text-white font-bold text-xl tracking-tight">Kyusify</span>
            </div>
            <p class="text-gray-500 text-sm text-center md:text-left">
                &copy; {{ date('Y') }} Quezon City University Student Enterprise Platform.
            </p>
            <div class="flex gap-4">
                <a href="#" class="text-gray-500 hover:text-white transition-colors">Terms</a>
                <a href="#" class="text-gray-500 hover:text-white transition-colors">Privacy</a>
            </div>
        </div>
    </footer>

</body>
</html>
