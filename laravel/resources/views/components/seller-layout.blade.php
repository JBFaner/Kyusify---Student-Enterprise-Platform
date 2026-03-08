<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kyusify') }} - Seller Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        /* Custom scrollbar for modern feel */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-gray-100 transition-colors duration-300" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-[80px]'" class="flex-shrink-0 bg-white dark:bg-[#13111C] border-r border-gray-100 dark:border-gray-800 transition-all duration-300 ease-in-out z-20 flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)] dark:shadow-[4px_0_24px_rgba(0,0,0,0.2)]">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-5 border-b border-gray-100 dark:border-gray-800/60 transition-all duration-300">
                <div class="flex items-center space-x-3 overflow-hidden" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-x-[-20px]" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-600 to-violet-900 flex items-center justify-center shadow-lg shadow-violet-500/30 shrink-0">
                        <span class="text-white font-bold text-lg leading-none">K</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 tracking-tight">Kyusify</h1>
                        <p class="text-[10px] font-semibold tracking-widest uppercase text-violet-600 dark:text-violet-400">Seller Portal</p>
                    </div>
                </div>
                <!-- Toggle Button -->
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-gray-400 hover:text-violet-600 hover:bg-violet-50 dark:hover:text-violet-400 dark:hover:bg-violet-500/10 focus:outline-none transition-all duration-200" :class="!sidebarOpen ? 'mx-auto' : ''">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" x-show="!sidebarOpen" style="display: none;" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" x-show="sidebarOpen" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar Links -->
            <nav class="flex-1 overflow-y-auto overflow-x-hidden py-6 px-3 space-y-2">
                
                <p x-show="sidebarOpen" x-transition.opacity class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2 mt-4 first:mt-0">Store</p>

                <!-- Dashboard -->
                <a href="{{ route('seller.dashboard') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative {{ request()->routeIs('seller.dashboard') ? 'bg-gradient-to-r from-violet-600 to-violet-500 text-white shadow-lg shadow-violet-500/25' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('seller.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-violet-500 dark:text-gray-500 dark:group-hover:text-violet-400 transition-colors duration-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Dashboard</span>
                    <!-- Tooltip -->
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Dashboard</div>
                </a>

                <!-- Business Profile -->
                <a href="{{ route('seller.profile.edit') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative {{ request()->routeIs('seller.profile.*') ? 'bg-gradient-to-r from-violet-600 to-violet-500 text-white shadow-lg shadow-violet-500/25' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('seller.profile.*') ? 'text-white' : 'text-gray-400 group-hover:text-violet-500 dark:text-gray-500 dark:group-hover:text-violet-400 transition-colors duration-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Business Profile</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Business Profile</div>
                </a>

                <!-- Products -->
                <a href="{{ route('seller.products.index') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative {{ request()->routeIs('seller.products.*') ? 'bg-gradient-to-r from-violet-600 to-violet-500 text-white shadow-lg shadow-violet-500/25' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('seller.products.*') ? 'text-white' : 'text-gray-400 group-hover:text-violet-500 dark:text-gray-500 dark:group-hover:text-violet-400 transition-colors duration-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Products</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Products</div>
                </a>

                <!-- Orders -->
                <a href="{{ route('seller.orders.index') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative {{ request()->routeIs('seller.orders.*') ? 'bg-gradient-to-r from-violet-600 to-violet-500 text-white shadow-lg shadow-violet-500/25' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('seller.orders.*') ? 'text-white' : 'text-gray-400 group-hover:text-violet-500 dark:text-gray-500 dark:group-hover:text-violet-400 transition-colors duration-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Orders</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Orders</div>
                </a>

                <p x-show="sidebarOpen" x-transition.opacity class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2 mt-6">Insights & Support</p>

                <!-- Inquiries -->
                <a href="{{ route('seller.dashboard') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-violet-500 dark:text-gray-500 dark:group-hover:text-violet-400 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Inquiries</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Inquiries</div>
                </a>

                <!-- Feedback -->
                <a href="{{ route('seller.dashboard') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-violet-500 dark:text-gray-500 dark:group-hover:text-violet-400 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Feedback</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Feedback</div>
                </a>

                <!-- Sales Reports -->
                <a href="{{ route('seller.dashboard') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-violet-500 dark:text-gray-500 dark:group-hover:text-violet-400 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Sales Reports</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Sales Reports</div>
                </a>
            </nav>
            
            <!-- Profile / Logout Area -->
            <div class="px-3 py-4 border-t border-gray-100 dark:border-gray-800/60 transition-all duration-300">
                <form method="POST" action="{{ route('seller.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400" :class="!sidebarOpen ? 'justify-center' : ''">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-red-500 dark:text-gray-500 dark:group-hover:text-red-400 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-200" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Log Out</span>
                        <div x-show="!sidebarOpen" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Log Out</div>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 bg-[#FAFAFA] dark:bg-[#0B0A0F] transition-colors duration-300 relative">
            
            <!-- Top Header -->
            <header class="h-20 flex items-center justify-between px-8 bg-white/80 dark:bg-[#13111C]/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800/60 sticky top-0 z-10 transition-colors duration-300">
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white tracking-tight">
                        {{ $header ?? 'Overview' }}
                    </h2>
                </div>
                
                <div class="flex items-center space-x-6">
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-violet-600 border-2 border-white dark:border-[#13111C] rounded-full"></span>
                    </button>
                    
                    <!-- Seller Profile Dropdown Dummy -->
                    <div class="flex items-center space-x-3 cursor-pointer group">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-violet-600 to-indigo-600 p-[2px] shadow-lg shadow-violet-500/20">
                            <div class="w-full h-full rounded-full bg-white dark:bg-gray-800 border border-white dark:border-gray-800 overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'S') }}&background=random&color=fff" alt="Seller" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">{{ auth()->user()->name ?? 'Seller' }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ auth()->user()->email ?? 'seller@kyusify.com' }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-auto p-8">
                <div class="max-w-7xl mx-auto opacity-0 animate-[fadeIn_0.5s_ease-out_forwards]">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    <!-- Toast Notification System -->
    @if (session()->has('success') || session()->has('error'))
        <div x-data="{ show: true, type: '{{ session()->has('success') ? 'success' : 'error' }}', message: '{{ session('success') ?? session('error') }}' }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-10"
             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 translate-x-10"
             class="fixed top-6 right-6 z-50 rounded-2xl p-4 shadow-[0_8px_30px_rgb(0,0,0,0.12)] border w-full max-w-sm flex items-start space-x-3 bg-white dark:bg-[#13111C] border-gray-100 dark:border-gray-800/60 transition-all duration-300">
            
            <div class="flex-shrink-0 mt-0.5">
                <!-- Success Icon -->
                <div x-show="type === 'success'" class="w-8 h-8 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <!-- Error Icon -->
                <div x-show="type === 'error'" class="w-8 h-8 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
            </div>
            
            <div class="flex-1 w-0 pt-1.5">
                <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="type === 'success' ? 'Success!' : 'Error!'"></p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="message"></p>
            </div>
            
            <div class="ml-4 flex-shrink-0 flex pt-1">
                <button @click="show = false" class="bg-transparent rounded-lg p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors focus:outline-none">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Need AlpineJS for the sidebar toggle -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
