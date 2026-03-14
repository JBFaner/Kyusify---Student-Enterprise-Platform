<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kyusify') }} &mdash; {{ $product->name }}</title>
    
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
                        <div class="absolute inset-y-0 left-0 pl-3 md:pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-violet-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" name="query" placeholder="Search products, services, stores..." class="w-full pl-10 md:pl-12 pr-4 py-2.5 md:py-3 bg-[#181622] border-transparent focus:bg-[#0B0A0F] focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 rounded-2xl text-sm md:text-base text-white transition-all duration-300">
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
            <a href="{{ route('discover') }}" class="whitespace-nowrap flex py-1 items-center gap-2 text-sm font-semibold transition-colors text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white border-b-2 border-transparent">
                <i class="fa-solid fa-layer-group text-lg"></i>
                All
            </a>
            @foreach($categories as $category)
                <a href="{{ route('discover', ['category' => $category->id]) }}" class="whitespace-nowrap py-1 flex items-center gap-2 text-sm font-semibold transition-colors border-b-2 {{ $product->category_id == $category->id ? 'text-violet-600 dark:text-violet-400 border-violet-600 dark:border-violet-400' : 'text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white border-transparent' }}">
                    @if($category->icon)
                        <i class="{{ $category->icon }} text-lg"></i>
                    @endif
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen pb-24 pt-8 md:pt-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs -->
            <nav class="flex mb-8 text-sm font-medium text-gray-500 dark:text-gray-400">
                <a href="{{ route('discover') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition-colors">Discover</a>
                <span class="mx-2">/</span>
                <a href="{{ route('store.show', $product->enterprise->id) }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition-colors">{{ $product->enterprise->name }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900 dark:text-white truncate max-w-[200px] sm:max-w-xs">{{ $product->name }}</span>
            </nav>

            @php
                // Pre-calculate review stats for the page (exclude reported unless owned by user)
                $allReviews = $product->reviews->filter(function ($review) {
                    return !$review->is_reported || (auth()->check() && $review->user_id === auth()->id());
                });
                $totalReviews = $allReviews->count();
                $averageRating = $totalReviews > 0 ? round((float) $allReviews->avg('rating'), 1) : 0;
                
                $ratingBreakdown = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
                foreach($allReviews as $r) {
                    if (isset($ratingBreakdown[$r->rating])) {
                        $ratingBreakdown[$r->rating]++;
                    }
                }
                
                $ratingPercentages = [];
                foreach($ratingBreakdown as $star => $count) {
                    $ratingPercentages[$star] = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                }

                $userExistingReview = auth()->check() ? $allReviews->where('user_id', auth()->id())->first() : null;
            @endphp

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <!-- Left Column (Image & Description) -->
                <div class="w-full lg:w-[60%] flex flex-col gap-8">
                    
                    <!-- Product Image -->
                    <div class="rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-800 shadow-sm bg-gray-50 dark:bg-[#0B0A0F]">
                        <div class="aspect-w-4 aspect-h-3 w-full">
                            @if($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 min-h-[300px]">
                                    <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Description -->
                    <div class="mt-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-800 pb-2">Description</h2>
                        @if($product->description)
                            <div class="prose prose-lg dark:prose-invert text-gray-700 dark:text-gray-300 whitespace-pre-line max-w-none text-base">
                                {{ $product->description }}
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-[#13111C] p-6 rounded-2xl border border-gray-100 dark:border-gray-800 text-center">
                                <p class="text-gray-500 dark:text-gray-400 italic font-medium">No description provided for this product.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column (Details, Cart, Ratings, Comments) -->
                <div class="w-full lg:w-[40%] flex flex-col pt-2 md:pl-4 lg:sticky lg:top-[120px]">
                    
                    <!-- Store Name & Global Rating Placeholder -->
                    <div class="flex items-center justify-between mb-4 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <a href="{{ route('store.show', $product->enterprise->id) }}" class="flex items-center gap-3 inline-flex hover:opacity-80 transition-opacity w-max">
                            <div class="w-8 h-8 rounded-full border border-gray-200 dark:border-gray-700 overflow-hidden bg-white">
                                @if($product->enterprise->logo_path)
                                    <img src="{{ Storage::url($product->enterprise->logo_path) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <span class="font-bold text-gray-700 dark:text-gray-300 hover:underline">{{ $product->enterprise->name }}</span>
                        </a>
                        <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-800/50 px-2 py-1 rounded-full">
                            <i class="fa-solid fa-star text-yellow-400 text-xs"></i>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $totalReviews > 0 ? number_format($averageRating, 1) : 'New' }}</span>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mb-4 leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Price & Stock -->
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <span class="text-3xl font-black text-gray-900 dark:text-white">₱{{ number_format($product->price, 2) }}</span>
                        @if($product->stock > 0)
                            <span class="text-sm font-bold text-green-600 dark:text-green-400">In Stock ({{ $product->stock }})</span>
                        @else
                            <span class="text-sm font-bold text-red-600 dark:text-red-400">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Add to Cart Form -->
                    <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-800">
                        @if(session('success'))
                            <div class="mb-4 p-3 bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 rounded-xl text-sm font-bold flex items-center gap-2">
                                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('cart.add', $product) }}" method="POST" id="add-to-cart-form" x-data="{ qty: 1, max: {{ $product->stock }} }">
                            @csrf
                            <div class="flex items-center gap-4 mb-4" x-show="max > 0">
                                <label class="font-bold text-gray-700 dark:text-gray-300">Quantity:</label>
                                <div class="flex items-center border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden bg-gray-50 dark:bg-[#0B0A0F]">
                                    <button type="button" class="px-3 py-1.5 text-gray-500 hover:bg-gray-200 dark:hover:bg-[#181622] transition-colors" @click="if(qty > 1) qty--">-</button>
                                    <input type="number" name="quantity" x-model="qty" class="w-12 text-center bg-transparent border-0 text-sm font-medium focus:ring-0 p-0" readonly>
                                    <button type="button" class="px-3 py-1.5 text-gray-500 hover:bg-gray-200 dark:hover:bg-[#181622] transition-colors" @click="if(qty < max) qty++">+</button>
                                </div>
                            </div>

                            @auth
                                <button type="submit" id="add-to-cart-btn" class="w-full py-3.5 px-8 text-lg btn-gumroad-violet flex justify-center items-center gap-3 relative overflow-hidden {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-cart-plus"></i> Add to cart
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="w-full py-3.5 px-8 text-lg btn-gumroad-violet flex justify-center items-center gap-3 block text-center mt-2 {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                    Log in to Add to Cart
                                </a>
                            @endauth
                        </form>
                    </div>

                    <!-- Ratings Visual (Gumroad style) -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-900 dark:text-white">Ratings</h3>
                            <div class="flex items-center gap-1.5 text-sm">
                                <i class="fa-solid fa-star text-black dark:text-white text-xs"></i>
                                <span class="font-bold">{{ number_format($averageRating, 1) }}</span>
                                <span class="text-gray-500">({{ $totalReviews }} {{ Str::plural('rating', $totalReviews) }})</span>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm">
                            @for($star = 5; $star >= 1; $star--)
                            <div class="flex items-center gap-3">
                                <span class="w-12 text-gray-600 dark:text-gray-400">{{ $star }} {{ Str::plural('star', $star) }}</span>
                                <div class="flex-1 h-2.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <div class="h-full bg-violet-500" style="width: {{ $ratingPercentages[$star] }}%"></div>
                                </div>
                                <span class="w-8 text-right text-gray-500">{{ $ratingPercentages[$star] }}%</span>
                            </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Input Fields for Review -->
                    <div class="mb-8 pt-6 border-t border-gray-200 dark:border-gray-800">
                        @if(session('error'))
                            <div class="mb-4 text-sm font-bold text-red-500">{{ session('error') }}</div>
                        @endif

                        @auth
                            @if(!$userExistingReview)
                                <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Write a review</label>
                                <form action="{{ route('review.store', $product) }}" method="POST" x-data="{ rating: 0, hover: 0 }" class="space-y-3">
                                    @csrf
                                    <!-- Star Selector -->
                                    <div class="flex gap-1 text-2xl text-gray-300 dark:text-gray-700 cursor-pointer w-max" @mouseleave="hover = 0">
                                        <template x-for="i in 5">
                                            <i class="fa-solid fa-star transition-colors"
                                               :class="{ 'text-yellow-400': i <= (hover || rating) }"
                                               @mouseover="hover = i"
                                               @click="rating = i"></i>
                                        </template>
                                    </div>
                                    <input type="hidden" name="rating" x-model="rating" required>
                                    
                                    <div class="flex gap-2">
                                        <input type="text" name="comment" placeholder="Share your thoughts (optional)..." class="flex-1 px-4 py-2 bg-gray-50 dark:bg-[#13111C] border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-sm text-gray-900 dark:text-white transition-shadow">
                                        <button type="submit" class="px-4 py-2 bg-black dark:bg-white text-white dark:text-black hover:bg-gray-800 dark:hover:bg-gray-200 rounded-lg text-sm font-bold transition-colors shadow-sm disabled:opacity-50" x-bind:disabled="rating === 0">
                                            Comment
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="p-4 bg-gray-50 dark:bg-[#181622] rounded-xl border border-gray-200 dark:border-gray-800">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">You have already reviewed this product. Scroll down to edit your review.</p>
                                </div>
                            @endif
                        @else
                            <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Write a review</label>
                            <div class="flex gap-2">
                                <input type="text" placeholder="Log in to write a review" disabled class="flex-1 px-4 py-2 bg-gray-100 dark:bg-[#181622] border border-gray-200 dark:border-gray-800 rounded-lg text-sm text-gray-500 cursor-not-allowed">
                                <a href="{{ route('login') }}" class="px-4 py-2 bg-black dark:bg-white text-white dark:text-black hover:bg-gray-800 dark:hover:bg-gray-200 rounded-lg text-sm font-bold transition-colors shadow-sm items-center flex">
                                    Log in
                                </a>
                            </div>
                        @endauth
                    </div>

                    <!-- Reviews List -->
                    <div class="space-y-6">
                        @if($allReviews->count() === 0)
                            <div class="bg-gray-50 dark:bg-[#13111C] p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm text-center">
                                <div class="text-4xl mb-2 opacity-50">💭</div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">No reviews yet</p>
                                <p class="text-xs text-gray-500">Be the first to share your thoughts!</p>
                            </div>
                        @else
                            @foreach($allReviews as $review)
                                <div x-data="{ editing: false, editRating: {{ $review->rating }}, hoverEdit: 0 }" class="pb-6 border-b border-gray-100 dark:border-gray-800 last:border-0 last:pb-0 relative group">
                                    
                                    <!-- 3 dots options menu (hover state) -->
                                    <div class="absolute top-0 right-0 opacity-0 group-hover:opacity-100 transition-opacity" x-data="{ openMenu: false }">
                                        <button @click.prevent="openMenu = !openMenu" @click.outside="openMenu = false" class="p-1 text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                                            <i class="fa-solid fa-ellipsis-vertical text-lg px-2"></i>
                                        </button>
                                        <div x-show="openMenu" x-transition.opacity style="display: none;" class="absolute right-0 mt-1 w-32 bg-white dark:bg-[#13111C] shadow-lg border border-gray-200 dark:border-gray-800 rounded-xl py-1 z-10 text-sm font-medium overflow-hidden">
                                            @auth
                                                @if(auth()->id() === $review->user_id)
                                                    <button @click="editing = true; openMenu = false" class="w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                        Edit
                                                    </button>
                                                @else
                                                    <form method="POST" action="{{ route('review.report', $review) }}" onsubmit="return confirm('Report this review?');" class="block w-full">
                                                        @csrf
                                                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                            Report
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                    Log in to Report
                                                </a>
                                            @endauth
                                        </div>
                                    </div>

                                    <!-- Read Mode -->
                                    <div x-show="!editing">
                                        <div class="flex items-center gap-2 mb-2 pr-8">
                                            <div class="flex text-black dark:text-white text-[10px] gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fa-solid fa-star text-yellow-400"></i>
                                                    @else
                                                        <i class="fa-regular fa-star text-gray-300 dark:text-gray-700"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            @if($review->created_at->diffInDays() < 3)
                                                <span class="text-[10px] font-bold text-white bg-violet-600 px-1.5 py-0.5 rounded leading-none uppercase tracking-wide">New</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-800 dark:text-gray-200 mb-3 leading-relaxed pr-8">
                                            {{ $review->comment ?? 'No comment provided.' }}
                                        </p>
                                        
                                        <!-- Seller Reply (if exists) -->
                                        @if($review->seller_reply)
                                        <div class="mb-3 pl-3 border-l-2 border-violet-400 bg-violet-50 dark:bg-violet-900/10 p-2.5 rounded-r-lg">
                                            <div class="flex items-center gap-1 mb-1">
                                                <i class="fa-solid fa-reply text-violet-500 text-[10px]"></i>
                                                <span class="text-[11px] font-bold text-violet-700 dark:text-violet-400 uppercase tracking-widest">Seller Reply</span>
                                            </div>
                                            <p class="text-xs text-gray-700 dark:text-gray-300">{{ $review->seller_reply }}</p>
                                        </div>
                                        @endif

                                        <div class="flex items-center gap-2 text-xs">
                                            <div class="w-5 h-5 rounded-full border border-gray-200 dark:border-gray-700 flex items-center justify-center font-bold overflow-hidden bg-white text-gray-700">
                                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $review->user->name }}</span>
                                            <i class="fa-solid fa-circle-check text-green-500" title="Verified Buyer"></i>
                                            <span class="text-gray-400 ml-auto">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <!-- Edit Mode -->
                                    <div x-show="editing" style="display: none;" class="bg-gray-50 dark:bg-[#181622] p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                                        <form action="{{ route('review.update', $review) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            
                                            <!-- Edit Stars -->
                                            <div class="flex gap-1 text-xl text-gray-300 dark:text-gray-700 cursor-pointer w-max mb-3" @mouseleave="hoverEdit = 0">
                                                <template x-for="i in 5">
                                                    <i class="fa-solid fa-star transition-colors"
                                                       :class="{ 'text-yellow-400': i <= (hoverEdit || editRating) }"
                                                       @mouseover="hoverEdit = i"
                                                       @click="editRating = i"></i>
                                                </template>
                                            </div>
                                            <input type="hidden" name="rating" x-model="editRating" required>
                                            
                                            <textarea name="comment" rows="2" class="w-full px-3 py-2 bg-white dark:bg-[#13111C] border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-sm text-gray-900 dark:text-white mb-3">{{ $review->comment }}</textarea>
                                            
                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="editing = false" class="px-3 py-1.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white">Cancel</button>
                                                <button type="submit" class="px-4 py-1.5 bg-violet-600 hover:bg-violet-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

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

    <!-- Flying Cart Animation Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('add-to-cart-form');
            const btn = document.getElementById('add-to-cart-btn');
            const cartIcon = document.getElementById('nav-cart-btn');

            if (form && btn && cartIcon) {
                form.addEventListener('submit', function(e) {
                    // Make sure it doesn't immediately submit if we want to animate first
                    e.preventDefault(); 
                    
                    // Create floating clone
                    const flyingItem = document.createElement('div');
                    flyingItem.style.position = 'fixed';
                    flyingItem.style.zIndex = '99999';
                    flyingItem.style.width = '24px';
                    flyingItem.style.height = '24px';
                    flyingItem.style.backgroundColor = '#8b5cf6'; // Violet theme
                    flyingItem.style.borderRadius = '50%';
                    flyingItem.style.pointerEvents = 'none';
                    flyingItem.style.boxShadow = '0 0 15px rgba(139, 92, 246, 0.5)';
                    flyingItem.style.transition = 'all 0.6s cubic-bezier(0.25, 1, 0.5, 1)';
                    
                    const btnRect = btn.getBoundingClientRect();
                    const cartRect = cartIcon.getBoundingClientRect();

                    // Start position relative to cursor/button center
                    flyingItem.style.left = (btnRect.left + btnRect.width / 2 - 12) + 'px';
                    flyingItem.style.top = (btnRect.top + btnRect.height / 2 - 12) + 'px';
                    flyingItem.style.transform = 'scale(1)';

                    document.body.appendChild(flyingItem);

                    // Trigger animation in next frame
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            flyingItem.style.left = (cartRect.left + cartRect.width / 2 - 12) + 'px';
                            flyingItem.style.top = (cartRect.top + cartRect.height / 2 - 12) + 'px';
                            flyingItem.style.transform = 'scale(0.3)';
                            flyingItem.style.opacity = '0';
                        });
                    });

                    // Resume form submission after delay matching transition
                    setTimeout(() => {
                        flyingItem.remove();
                        // Add an indicator to the bag before page reloads
                        const badge = document.createElement('span');
                        badge.className = 'absolute top-1 right-1 w-2.5 h-2.5 bg-violet-600 border-2 border-black rounded-full animate-ping';
                        cartIcon.appendChild(badge);

                        form.submit();
                    }, 650);
                });
            }
        });
    </script>
</body>
</html>
