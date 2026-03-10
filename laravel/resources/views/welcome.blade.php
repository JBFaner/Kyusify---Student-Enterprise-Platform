<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kyusify') }} &mdash; QCU Student Marketplace</title>
    
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
        .hero-pattern {
            background-image: radial-gradient(rgba(124, 58, 237, 0.15) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .text-gradient {
            background: linear-gradient(135deg, #a78bfa 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
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
                <div class="flex-shrink-0 cursor-pointer flex items-center gap-3" onclick="window.scrollTo(0, 0)">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center border-2 border-white/20 overflow-hidden p-0.5">
                        <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify" class="w-full h-full object-contain">
                    </div>
                    <span class="text-white font-bold text-2xl tracking-tight">Kyusify</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('discover') }}" class="text-gray-300 hover:text-white font-medium transition-colors">Discover</a>
                    
                    @if(request()->routeIs('landing'))
                        <a href="#about" @click.prevent="document.querySelector('#about').scrollIntoView({ behavior: 'smooth' })" class="text-gray-300 hover:text-white font-medium transition-colors cursor-pointer">About</a>
                    @else
                        <a href="{{ url('/#about') }}" class="text-gray-300 hover:text-white font-medium transition-colors">About</a>
                    @endif

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
                <a href="{{ route('discover') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-white font-medium text-lg">Discover</a>
                
                @if(request()->routeIs('landing'))
                    <a href="#about" @click.prevent="mobileMenuOpen = false; document.querySelector('#about').scrollIntoView({ behavior: 'smooth' })" class="block text-gray-300 hover:text-white font-medium text-lg">About</a>
                @else
                    <a href="{{ url('/#about') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-white font-medium text-lg">About</a>
                @endif
                
                <button @click="alert('Pricing plans will be implemented in a future update!'); mobileMenuOpen = false" class="block text-gray-300 hover:text-white font-medium text-lg text-left w-full">Pricing</button>
                <div class="pt-4 border-t border-white/10 flex flex-col gap-3">
                    <a href="{{ route('seller.login') }}" class="block text-center text-gray-300 hover:text-white font-medium py-2">Log in</a>
                    <a href="{{ route('seller.register') }}" class="btn-gumroad-violet px-6 py-3 text-center block w-full">Start Selling</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-violet-950">
        <div class="absolute inset-0 hero-pattern z-0 opacity-40"></div>
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-3/4 max-w-3xl h-64 bg-violet-600/30 blur-[120px] rounded-full point-events-none z-0"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 text-center animate-on-scroll opacity-0 translate-y-8 transition-all duration-700 ease-out">
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tight leading-[1.1] mb-6">
                {{ $settings['hero_title'] ?? 'Go from student to store owner.' }}
            </h1>
            <p class="text-xl md:text-2xl text-gray-400 max-w-3xl mx-auto mb-10 font-medium leading-relaxed">
                {{ $settings['hero_subtitle'] ?? 'Turn your creative ideas into a real business. Sell your products, digital goods, and services directly to the QCU community.' }}
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('seller.register') }}" class="btn-gumroad-violet px-8 py-4 text-lg w-full sm:w-auto">Start selling today</a>
                <a href="{{ route('discover') }}" class="btn-gumroad px-8 py-4 text-lg w-full sm:w-auto bg-white hover:bg-gray-100">Explore products</a>
            </div>
            <p class="mt-4 text-sm text-gray-500 font-medium tracking-wide">Free to join for QCU students.</p>

            @if(isset($settings['about_text']) && !empty($settings['about_text']))
            <div class="mt-16 inline-block bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-md max-w-2xl mx-auto">
                <p class="text-sm md:text-base text-gray-300">{{ $settings['about_text'] }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Platform Preview Intro -->
    <section class="py-20 bg-black border-y border-white/5 animate-on-scroll opacity-0 translate-y-8 transition-all duration-700 ease-out">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-2xl md:text-4xl font-bold mb-4">See how easy it is to manage your student business.</h2>
            <p class="text-gray-400 text-lg mb-12">Create your store, list your products, and start accepting orders in minutes. It's everything you need, all in one place.</p>
            
            <!-- Interactive mockup of the seller dashboard -->
            <div x-data="{ tab: 'dashboard' }" class="relative mx-auto rounded-xl border border-gray-200 bg-[#FAFAFA] shadow-2xl overflow-hidden aspect-[4/3] md:aspect-video text-left flex flex-col">
                <!-- Topbar -->
                <div class="h-10 md:h-12 border-b border-gray-200 flex items-center justify-between px-3 md:px-4 bg-white/80 backdrop-blur-md">
                    <div class="flex gap-1.5 leading-none">
                        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-violet-600 to-indigo-600 p-[1.5px] shadow-sm">
                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-[9px] font-bold text-violet-700">JD</div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 hidden sm:inline-block">Juan Dela Cruz</span>
                    </div>
                </div>
                <!-- Body -->
                <div class="flex flex-1 overflow-hidden">
                    <!-- Sidebar -->
                    <div class="w-16 sm:w-1/4 md:w-1/5 bg-violet-950 border-r border-violet-900 py-4 flex flex-col z-10 shadow-lg">
                        <div class="px-2 sm:px-4 mb-6 flex items-center justify-center sm:justify-start gap-2">
                            <div class="w-8 h-8 md:w-6 md:h-6 bg-white rounded flex items-center justify-center shrink-0 p-[2px]">
                                <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-full h-full object-contain">
                            </div>
                            <span class="text-white font-bold text-sm hidden sm:block">Kyusify Seller</span>
                        </div>
                        <nav class="flex-1 space-y-1 px-2">
                            <button @click="tab = 'dashboard'" :class="tab === 'dashboard' ? 'bg-white text-violet-950 font-bold shadow-lg' : 'text-violet-200 hover:bg-white/10 hover:text-white'" class="w-full text-left px-3 py-3 md:py-2.5 rounded-lg text-xs md:text-sm transition-all duration-200 flex items-center justify-center sm:justify-start gap-3">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                                <span class="hidden sm:block">Dashboard</span>
                            </button>
                            <button @click="tab = 'products'" :class="tab === 'products' ? 'bg-white text-violet-950 font-bold shadow-lg' : 'text-violet-200 hover:bg-white/10 hover:text-white'" class="w-full text-left px-3 py-3 md:py-2.5 rounded-lg text-xs md:text-sm transition-all duration-200 flex items-center justify-center sm:justify-start gap-3">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                <span class="hidden sm:block">Products</span>
                            </button>
                            <button @click="tab = 'orders'" :class="tab === 'orders' ? 'bg-white text-violet-950 font-bold shadow-lg' : 'text-violet-200 hover:bg-white/10 hover:text-white'" class="w-full text-left px-3 py-3 md:py-2.5 rounded-lg text-xs md:text-sm transition-all duration-200 flex items-center justify-center sm:justify-start gap-3">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                <span class="hidden sm:block">Orders</span>
                            </button>
                        </nav>
                    </div>
                    <!-- Main Content Area -->
                    <div class="flex-1 bg-[#FAFAFA] overflow-y-auto p-4 md:p-8 custom-scrollbar">
                        <!-- Dashboard View -->
                        <div x-show="tab === 'dashboard'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <h3 class="text-gray-900 font-bold text-xl md:text-2xl mb-6">Overview</h3>
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="bg-white border border-gray-200 rounded-xl p-4 md:p-5 hover:border-violet-300 transition-colors shadow-sm">
                                    <div class="text-gray-500 text-xs font-bold tracking-wider mb-2">TOTAL REVENUE</div>
                                    <div class="text-gray-900 font-black text-2xl md:text-3xl">₱4,250.00</div>
                                    <div class="text-emerald-700 text-xs font-bold mt-2 bg-emerald-50 inline-block px-2 py-0.5 rounded border border-emerald-100">+15% this week</div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-xl p-4 md:p-5 hover:border-violet-300 transition-colors shadow-sm">
                                    <div class="text-gray-500 text-xs font-bold tracking-wider mb-2">PENDING ORDERS</div>
                                    <div class="text-gray-900 font-black text-2xl md:text-3xl">5</div>
                                    <div class="text-amber-700 text-xs font-bold mt-2 bg-amber-50 inline-block px-2 py-0.5 rounded border border-amber-100">Requires action</div>
                                </div>
                            </div>
                            <h4 class="text-gray-900 font-bold text-lg mb-4">Recent Sales</h4>
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                                <div class="flex flex-col md:flex-row md:items-center justify-between p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <div class="mb-2 md:mb-0">
                                        <div class="text-gray-900 font-bold text-sm">QCU Lanyard (Maroon) x2</div>
                                        <div class="text-gray-500 text-xs">Today, 10:42 AM</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-900 font-bold text-sm">₱100.00</span>
                                        <span class="text-amber-700 text-xs font-bold bg-amber-50 px-2.5 py-1 rounded border border-amber-200">Processing</span>
                                    </div>
                                </div>
                                <div class="flex flex-col md:flex-row md:items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                                    <div class="mb-2 md:mb-0">
                                        <div class="text-gray-900 font-bold text-sm">Digital IT Reviewer 2026</div>
                                        <div class="text-gray-500 text-xs">Yesterday, 4:15 PM</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-900 font-bold text-sm">₱50.00</span>
                                        <span class="text-emerald-700 text-xs font-bold bg-emerald-50 px-2.5 py-1 rounded border border-emerald-200">Fulfilled</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products View -->
                        <div x-show="tab === 'products'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
                                <h3 class="text-gray-900 font-bold text-xl md:text-2xl">Products</h3>
                                <button onclick="alert('This is an interactive preview. Sign up to add products!')" class="bg-violet-600 hover:bg-violet-700 text-white font-bold text-sm px-4 py-2 rounded-lg transition-colors inline-block w-max shadow-sm shadow-violet-500/30">
                                    + Add Product
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex gap-4 hover:border-violet-300 transition-colors">
                                    <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 border border-gray-100 rounded-lg flex items-center justify-center shrink-0 text-gray-400">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <div class="text-gray-900 text-base font-bold mb-1">QCU Polo Shirt</div>
                                        <div class="text-gray-500 text-xs font-medium mb-2">Apparel • 12 in stock</div>
                                        <div class="text-violet-600 text-sm font-bold">₱450.00</div>
                                    </div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex gap-4 hover:border-violet-300 transition-colors">
                                    <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 border border-gray-100 rounded-lg flex items-center justify-center shrink-0 text-gray-400">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <div class="text-gray-900 text-base font-bold mb-1">BSIT Reviewer</div>
                                        <div class="text-gray-500 text-xs font-medium mb-2">Digital • Unlimited</div>
                                        <div class="text-violet-600 text-sm font-bold">₱50.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Orders View -->
                        <div x-show="tab === 'orders'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <h3 class="text-gray-900 font-bold text-xl md:text-2xl mb-6">Orders</h3>
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-x-auto">
                                <table class="w-full text-left text-sm min-w-[400px]">
                                    <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                                        <tr>
                                            <th class="p-4 font-bold">Order ID</th>
                                            <th class="p-4 font-bold">Customer</th>
                                            <th class="p-4 font-bold">Total</th>
                                            <th class="p-4 font-bold">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 text-gray-700">
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-4 font-mono text-xs text-violet-600">#ORD-991</td>
                                            <td class="p-4 font-semibold text-gray-900">Maria S.</td>
                                            <td class="p-4 font-medium">₱500.00</td>
                                            <td class="p-4"><span class="text-amber-700 text-xs font-bold bg-amber-50 px-2.5 py-1 rounded border border-amber-200 whitespace-nowrap">Pending</span></td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-4 font-mono text-xs text-violet-600">#ORD-990</td>
                                            <td class="p-4 font-semibold text-gray-900">Mark J.</td>
                                            <td class="p-4 font-medium">₱100.00</td>
                                            <td class="p-4"><span class="text-blue-700 text-xs font-bold bg-blue-50 px-2.5 py-1 rounded border border-blue-200 whitespace-nowrap">Processing</span></td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-4 font-mono text-xs text-violet-600">#ORD-989</td>
                                            <td class="p-4 font-semibold text-gray-900">Sarah C.</td>
                                            <td class="p-4 font-medium">₱450.00</td>
                                            <td class="p-4"><span class="text-emerald-700 text-xs font-bold bg-emerald-50 px-2.5 py-1 rounded border border-emerald-200 whitespace-nowrap">Completed</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="about" class="py-24 bg-[#0a0a0a] animate-on-scroll opacity-0 translate-y-8 transition-all duration-700 ease-out">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-12">
                <!-- Feature 1 -->
                <div>
                    <div class="w-12 h-12 rounded-xl bg-violet-900 border border-violet-700 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Sell your student products</h3>
                    <p class="text-gray-400 leading-relaxed">Whether it’s campus merch, digital arts, or your famous baked goods, put your products in front of the people who matter most.</p>
                </div>
                
                <!-- Feature 2 -->
                <div>
                    <div class="w-12 h-12 rounded-xl bg-violet-900 border border-violet-700 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Reach your campus community</h3>
                    <p class="text-gray-400 leading-relaxed">Stop struggling to find customers. Kyusify connects your business directly with the thousands of students and faculty right here at QCU.</p>
                </div>

                <!-- Feature 3 -->
                <div>
                    <div class="w-12 h-12 rounded-xl bg-violet-900 border border-violet-700 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Manage orders easily</h3>
                    <p class="text-gray-400 leading-relaxed">No more messy DMs or lost transactions. Track your sales, view pending orders, and manage your inventory from a clean, simple dashboard.</p>
                </div>

                <!-- Feature 4 -->
                <div>
                    <div class="w-12 h-12 rounded-xl bg-violet-900 border border-violet-700 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Build your student brand</h3>
                    <p class="text-gray-400 leading-relaxed">Create a professional storefront that makes your business look legit. Add your logo, customize your banner, and stand out.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Motivational Section -->
    <section class="py-24 bg-violet-950 relative overflow-hidden animate-on-scroll opacity-0 translate-y-8 transition-all duration-700 ease-out">
        <div class="absolute inset-0 hero-pattern z-0 opacity-20"></div>
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight mb-8">Don't just study business.<br>Build one.</h2>
            <p class="text-xl md:text-2xl text-violet-200 font-medium leading-relaxed mb-0">Stop waiting for the perfect opportunity. You already have the ideas, the talent, and the drive. Kyusify gives you the tools to take control, launch your brand, and make your first sale today.</p>
        </div>
    </section>

    <!-- Final Call to Action -->
    <section class="py-24 bg-[#0a0a0a] border-t border-[#222] animate-on-scroll opacity-0 translate-y-8 transition-all duration-700 ease-out">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-black tracking-tight mb-6">Turn your student ideas into a real business.</h2>
            <p class="text-lg text-gray-400 mb-10">Join hundreds of other QCU creators who are already selling on Kyusify. It takes less than five minutes to open your store.</p>
            <a href="{{ route('seller.register') }}" class="btn-gumroad px-10 py-5 text-xl bg-white hover:bg-gray-100 uppercase tracking-widest inline-block">Open your store now</a>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', 'translate-y-8');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.animate-on-scroll').forEach((el) => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
