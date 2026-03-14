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

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #FAFAFA; color: #111; }
        .dark body { background-color: #0B0A0F; color: #fff; }
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
        .btn-gumroad-violet-nav {
            background-color: #8b5cf6;
            color: #fff;
            border: 2px solid #000;
            box-shadow: 4px 4px 0px #fff;
            transition: transform 0.1s, box-shadow 0.1s;
            border-radius: 8px;
            font-weight: 700;
        }
        .btn-gumroad-violet-nav:hover {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px #fff;
            background-color: #7c3aed;
        }
        .card-gumroad {
            background-color: #fff;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        }
        .card-gumroad:hover {
            transform: translateY(-4px);
            border-color: #8b5cf6;
            box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.15);
        }
        .dark .card-gumroad {
            background-color: #13111C;
            border-color: #1f2937;
        }
        .dark .card-gumroad:hover { border-color: #8b5cf6; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #475569; }

        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased selection:bg-violet-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    @if(!empty($settings['homepage_announcement']))
        <div class="bg-violet-600 text-white text-sm font-medium py-2 px-4 text-center">
            {{ $settings['homepage_announcement'] }}
        </div>
    @endif

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 w-full bg-black border-b border-[#222] transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                <!-- Logo -->
                <a href="{{ route('landing') }}" class="flex-shrink-0 cursor-pointer flex items-center gap-3">
                    <img src="{{ asset('images/kyusify-logo.png') }}" class="h-10 w-10 object-contain drop-shadow-sm bg-white rounded p-1" alt="Kyusify Logo">
                    <span class="text-white font-bold text-2xl tracking-tight hidden sm:block">Kyusify</span>
                </a>

                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl mx-auto">
                    <form action="{{ route('discover') }}" method="GET" class="relative group">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <div class="absolute inset-y-0 left-0 pl-3 md:pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-violet-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" name="query" value="{{ request('query') }}" placeholder="Search products, services, stores..." class="w-full pl-10 md:pl-12 pr-4 py-2.5 md:py-3 bg-[#181622] border-transparent focus:bg-[#0B0A0F] focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 rounded-2xl text-sm md:text-base text-white transition-all duration-300">
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex flex-shrink-0 items-center justify-end gap-2 sm:gap-4 flex-wrap">
                    @guest
                        <a href="{{ route('login') }}" class="hidden md:block text-gray-300 hover:text-white font-medium px-2 py-2 transition-colors whitespace-nowrap">Log in</a>
                    @endguest

                    <a href="{{ route('seller.register') }}" class="btn-gumroad-violet-nav px-4 sm:px-6 py-2 sm:py-2.5 text-sm sm:text-base whitespace-nowrap hidden min-[400px]:block">Start Selling</a>

                    <a href="{{ route('cart.index') }}" id="nav-cart-btn" class="p-2.5 text-gray-300 hover:text-violet-400 hover:bg-violet-900/20 rounded-xl transition-colors relative block">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        @auth
                            @if(auth()->user()->cartItems()->count() > 0)
                                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-violet-600 border-2 border-black rounded-full" id="nav-cart-badge"></span>
                            @endif
                        @endauth
                    </a>
                    
                    @auth
                        <!-- Profile Dropdown -->
                        <div x-data="{ openProfile: false }" class="relative z-50">
                            <button @click="openProfile = !openProfile" @click.outside="openProfile = false" class="flex items-center gap-2 bg-[#181622] hover:bg-[#222] border border-gray-800 rounded-full py-1.5 px-1.5 pr-4 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-violet-600 flex flex-shrink-0 items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="text-white text-sm font-medium hidden sm:block truncate max-w-[100px]">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            
                            <div x-show="openProfile" 
                                 x-transition:enter="transition ease-out duration-100" 
                                 x-transition:enter-start="transform opacity-0 scale-95" 
                                 x-transition:enter-end="transform opacity-100 scale-100" 
                                 x-transition:leave="transition ease-in duration-75" 
                                 x-transition:leave-start="transform opacity-100 scale-100" 
                                 x-transition:leave-end="transform opacity-0 scale-95" 
                                 class="absolute right-0 mt-2 w-48 bg-[#181622] rounded-xl shadow-lg border border-gray-800 py-1 z-50 text-left" style="display: none;">
                                @if(auth()->user()->role === 'seller')
                                    <a href="{{ route('seller.dashboard') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#2a2833] hover:text-white transition-colors">Seller Dashboard</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-400 hover:bg-[#2a2833] hover:text-red-300 transition-colors">
                                        Log out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Horizontal Category Bar -->
    <div class="sticky top-[81px] z-40 bg-white/85 dark:bg-[#13111C]/85 backdrop-blur-md px-4 sm:px-6 lg:px-8 transition-colors duration-300 border-b border-gray-200 dark:border-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center overflow-x-auto hide-scroll py-3 gap-6">
            <a href="{{ route('discover') }}" class="whitespace-nowrap flex py-1 items-center gap-2 text-sm font-semibold transition-colors border-b-2 {{ !request('category') ? 'text-violet-600 dark:text-violet-400 border-violet-600 dark:border-violet-400' : 'text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white border-transparent' }}">
                <i class="fa-solid fa-layer-group text-lg"></i>
                All
            </a>
            @foreach($categories as $category)
                <a href="{{ route('discover', ['category' => $category->id]) }}" class="whitespace-nowrap py-1 flex items-center gap-2 text-sm font-semibold transition-colors border-b-2 {{ request('category') == $category->id ? 'text-violet-600 dark:text-violet-400 border-violet-600 dark:border-violet-400' : 'text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white border-transparent' }}">
                    @if($category->icon)
                        <i class="{{ $category->icon }} text-lg"></i>
                    @endif
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen pb-24 pt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Promotional Banner (If set in admin) -->
            @if(!empty($settings['homepage_banner_image']))
                <div class="mb-12 rounded-3xl overflow-hidden relative group h-64 md:h-80 lg:h-96 w-full bg-gray-900 shadow-2xl">
                    <img src="{{ Storage::url($settings['homepage_banner_image']) }}" alt="Promo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full flex flex-col sm:flex-row justify-between sm:items-end gap-6">
                        <div>
                            @if(!empty($settings['homepage_banner_cta_text']))
                                <a href="{{ $settings['homepage_banner_cta_link'] ?? '#' }}" class="inline-flex items-center gap-2 bg-white text-black hover:bg-gray-100 font-bold px-6 py-3 rounded-xl transition-transform hover:-translate-y-1">
                                    {{ $settings['homepage_banner_cta_text'] }}
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div> <!-- End top container -->

        <!-- Featured Products -->
        @if($featuredProducts->count() > 0 && !request('query') && !request('category'))
            <style>
                .hide-scrollbar::-webkit-scrollbar { display: none; }
                .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            </style>
            <div class="bg-violet-50 dark:bg-violet-900/10 py-16 mb-16 border-y border-violet-100 dark:border-violet-900 relative overflow-hidden"
                 x-data="{
                     current: 1,
                     total: {{ $featuredProducts->count() }},
                     updateCurrent() {
                         const slider = this.$refs.slider;
                         const scrollLeft = slider.scrollLeft;
                         const cardWidth = slider.firstElementChild.offsetWidth;
                         const gap = parseFloat(window.getComputedStyle(slider).gap) || 0;
                         const index = Math.round(scrollLeft / (cardWidth + gap));
                         this.current = index + 1;
                     },
                     next() {
                         const slider = this.$refs.slider;
                         const cardWidth = slider.firstElementChild.offsetWidth;
                         const gap = parseFloat(window.getComputedStyle(slider).gap) || 0;
                         slider.scrollBy({ left: cardWidth + gap, behavior: 'smooth' });
                     },
                     prev() {
                         const slider = this.$refs.slider;
                         const cardWidth = slider.firstElementChild.offsetWidth;
                         const gap = parseFloat(window.getComputedStyle(slider).gap) || 0;
                         slider.scrollBy({ left: -(cardWidth + gap), behavior: 'smooth' });
                     }
                 }">
                <div class="absolute inset-0 bg-[radial-gradient(rgba(124,58,237,0.15)_1px,transparent_1px)] bg-[size:32px_32px] opacity-40 dark:opacity-20"></div>
                <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                            <span class="text-violet-600 dark:text-violet-400"><i class="fa-solid fa-star"></i></span>
                            Featured Products
                        </h2>
                        <!-- Pagination Controls -->
                        <div class="flex items-center gap-4 text-gray-900 dark:text-white font-bold" x-show="total > 1" style="display: none;" x-transition>
                            <button @click="prev" :class="{'opacity-30 cursor-not-allowed': current <= 1}" class="hover:text-violet-600 transition-colors">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            </button>
                            <span class="text-sm md:text-base tracking-widest"><span x-text="current"></span> / <span x-text="total"></span></span>
                            <button @click="next" :class="{'opacity-30 cursor-not-allowed': current >= total}" class="hover:text-violet-600 transition-colors">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m-7 7H3" /></svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex gap-8 overflow-x-auto snap-x snap-mandatory pb-4 hide-scrollbar" x-ref="slider" @scroll.passive="updateCurrent">
                        @foreach($featuredProducts as $product)
                            <!-- Featured Product Horizontal Card -->
                            <div class="snap-start w-full xl:w-[calc(50%-1rem)] flex-shrink-0">
                                <a href="{{ route('product.show', $product->id) }}" class="card-gumroad overflow-hidden flex flex-row group h-full block h-56 md:h-64 lg:h-72">
                                    <div class="w-1/2 lg:w-3/5 h-full bg-gray-100 dark:bg-[#0B0A0F] border-r border-gray-200 dark:border-gray-800 relative overflow-hidden flex-shrink-0">
                                        @if($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-6 md:p-8 flex flex-col flex-1 bg-white dark:bg-[#13111C] justify-between relative">
                                        <div>
                                            <h3 class="font-bold text-xl md:text-2xl text-gray-900 dark:text-white mb-3 line-clamp-2 leading-tight group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">{{ $product->name }}</h3>
                                            <div class="flex items-center gap-2 mb-4">
                                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-700 overflow-hidden flex-shrink-0">
                                                    @if($product->enterprise && $product->enterprise->logo_path)
                                                        <img src="{{ Storage::url($product->enterprise->logo_path) }}" class="w-full h-full object-cover">
                                                    @endif
                                                </div>
                                                <object><a href="{{ route('store.show', $product->enterprise->id) }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate hover:text-violet-600 hover:underline cursor-pointer">{{ $product->enterprise->name ?? 'Store' }}</a></object>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-auto flex items-center justify-between">
                                            <!-- Price Tag Style -->
                                            <div class="relative bg-violet-600 text-white font-black px-4 py-1.5 md:py-2 text-base md:text-lg inline-block">
                                                ₱{{ number_format($product->price, 2) }}
                                                <div class="absolute right-[-10px] top-0 bottom-0 w-0 h-0 border-y-[18px] md:border-y-[22px] border-y-transparent border-l-[10px] border-l-violet-600"></div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> <!-- Re-open container -->

            <!-- Search / Filter Results Header -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-gray-200 dark:border-gray-800/60 pb-4 mt-8">
                <div>
                    @if(request('query'))
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Search results for "<span class="text-violet-600">{{ request('query') }}</span>"</h2>
                    @elseif(request('category'))
                        @php $currentCat = $categories->firstWhere('id', request('category')); @endphp
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $currentCat ? $currentCat->name : 'Category' }} Products</h2>
                    @else
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">All Products</h2>
                    @endif
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Showing {{ $products->count() }} items</p>
                </div>
                
                <!-- If we wanted a sort dropdown on the frontend, we could add it here -->
            </div>

            <!-- Standard Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
                    @foreach($products as $product)
                        <a href="{{ route('product.show', $product->id) }}" class="card-gumroad overflow-hidden flex flex-col group h-full block">
                            <div class="w-full h-56 bg-gray-100 dark:bg-[#0B0A0F] border-b border-gray-200 dark:border-gray-800 relative overflow-hidden">
                                @if($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-gray-700 shadow-md group-hover:bg-violet-600 group-hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </div>
                            </div>
                            <div class="p-5 flex flex-col flex-1 bg-white dark:bg-[#13111C]">
                                <h3 class="font-bold text-base text-gray-900 dark:text-white mb-2 line-clamp-2 leading-tight group-hover:text-violet-600 transition-colors">{{ $product->name }}</h3>
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-4 h-4 rounded-full bg-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-700 overflow-hidden flex-shrink-0">
                                        @if($product->enterprise && $product->enterprise->logo_path)
                                            <img src="{{ Storage::url($product->enterprise->logo_path) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <object><a href="{{ route('store.show', $product->enterprise->id) }}" class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate hover:text-violet-600 hover:underline">{{ $product->enterprise->name ?? 'Store' }}</a></object>
                                </div>
                                <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-100 dark:border-gray-800/60">
                                    <span class="font-black text-lg text-gray-900 dark:text-white">₱{{ number_format($product->price, 2) }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if(method_exists($products, 'links') && $products->hasPages())
                    <div class="my-12 flex justify-center">
                        <div class="bg-white dark:bg-[#13111C] p-2 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800/60 inline-flex">
                            {{ $products->links('pagination::tailwind') }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-24 px-6 border-2 border-dashed border-gray-300 dark:border-[#333] rounded-3xl bg-white dark:bg-[#13111C]">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-[#1a1a1a] rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-200 dark:border-[#333]">
                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No products found.</h3>
                    <p class="text-gray-500 dark:text-gray-400">We couldn't find any products matching your criteria.</p>
                </div>
            @endif

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-black py-12 border-t border-[#222]">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 flex flex-col lg:flex-row justify-between items-center gap-8">
            <a href="{{ route('landing') }}" class="flex items-center gap-3 cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center border border-white/20 overflow-hidden p-0.5">
                    <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-white font-black text-2xl tracking-tight">Kyusify</span>
            </a>
            
            <div class="flex gap-6 md:gap-8 font-medium text-gray-400">
                <a href="{{ route('discover') }}" class="hover:text-white transition-colors">Discover</a>
                <a href="{{ route('landing') }}#about" class="hover:text-white transition-colors">About</a>
                <a href="javascript:alert('Pricing info not yet available')" class="hover:text-white transition-colors">Pricing</a>
            </div>

            <p class="text-gray-500 text-sm text-center md:text-right">
                &copy; {{ date('Y') }} Quezon City University Student Enterprise Platform.
            </p>
        </div>
    </footer>

</body>
</html>
