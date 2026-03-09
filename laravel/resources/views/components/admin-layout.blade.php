<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kyusify') }} - Admin</title>

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
        <aside :class="sidebarOpen ? 'w-64' : 'w-[80px]'" class="flex-shrink-0 bg-violet-950 border-r border-violet-900 transition-all duration-300 ease-in-out z-20 flex flex-col shadow-2xl">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-5 border-b border-violet-900/50 transition-all duration-300">
                <div class="flex items-center space-x-3 overflow-hidden" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-x-[-20px]" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-lg shadow-violet-500/30 shrink-0 overflow-hidden p-0.5">
                        <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">Kyusify</h1>
                        <p class="text-[10px] font-semibold tracking-widest uppercase text-violet-300">Admin Portal</p>
                    </div>
                </div>
                <!-- Toggle Button -->
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-violet-400 hover:text-white hover:bg-violet-800/50 focus:outline-none transition-all duration-200" :class="!sidebarOpen ? 'mx-auto' : ''">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" x-show="!sidebarOpen" style="display: none;" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" x-show="sidebarOpen" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar Links -->
            <nav class="flex-1 overflow-y-auto overflow-x-hidden py-6 px-3 space-y-2">
                
                <p x-show="sidebarOpen" x-transition.opacity class="px-4 text-xs font-semibold text-violet-300/70 uppercase tracking-wider mb-2 mt-4 first:mt-0">Menu</p>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative {{ request()->routeIs('admin.dashboard') ? 'bg-white text-violet-950 shadow-lg font-bold' : 'text-violet-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-violet-900' : 'text-violet-400 group-hover:text-white transition-colors duration-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Dashboard</span>
                    <!-- Tooltip -->
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-white text-violet-950 font-semibold shadow-xl border border-violet-100 text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Dashboard</div>
                </a>

                <!-- User Management -->
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative {{ request()->routeIs('admin.users.*') ? 'bg-white text-violet-950 shadow-lg font-bold' : 'text-violet-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-violet-900' : 'text-violet-400 group-hover:text-white transition-colors duration-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Users</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-white text-violet-950 font-semibold shadow-xl border border-violet-100 text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Users</div>
                </a>

                <!-- Enterprise Management -->
                <a href="{{ route('admin.enterprises.index') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative {{ request()->routeIs('admin.enterprises.*') ? 'bg-white text-violet-950 shadow-lg font-bold' : 'text-violet-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.enterprises.*') ? 'text-violet-900' : 'text-violet-400 group-hover:text-white transition-colors duration-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Enterprises</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-white text-violet-950 font-semibold shadow-xl border border-violet-100 text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Enterprises</div>
                </a>

                <!-- Product Management -->
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative {{ request()->routeIs('admin.products.*') ? 'bg-white text-violet-950 shadow-lg font-bold' : 'text-violet-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.products.*') ? 'text-violet-900' : 'text-violet-400 group-hover:text-white transition-colors duration-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Products</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-white text-violet-950 font-semibold shadow-xl border border-violet-100 text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Products</div>
                </a>

                <p x-show="sidebarOpen" x-transition.opacity class="px-4 text-xs font-semibold text-violet-300/70 uppercase tracking-wider mb-2 mt-6">System</p>

                <!-- Inquiry & Feedback -->
                <a href="{{ route('admin.inquiries.index') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative {{ request()->routeIs('admin.inquiries.*') ? 'bg-white text-violet-950 shadow-lg font-bold' : 'text-violet-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.inquiries.*') ? 'text-violet-900' : 'text-violet-400 group-hover:text-white transition-colors duration-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Inquiries</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-white text-violet-950 font-semibold shadow-xl border border-violet-100 text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Inquiries</div>
                </a>

                <!-- Content / Platform -->
                <a href="{{ route('admin.content.index') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative {{ request()->routeIs('admin.content.*') ? 'bg-white text-violet-950 shadow-lg font-bold' : 'text-violet-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.content.*') ? 'text-violet-900' : 'text-violet-400 group-hover:text-white transition-colors duration-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Content</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-white text-violet-950 font-semibold shadow-xl border border-violet-100 text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Content</div>
                </a>

                <!-- Reports & Logs -->
                <a href="{{ route('admin.reports.index') }}" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative {{ request()->routeIs('admin.reports.*') ? 'bg-white text-violet-950 shadow-lg font-bold' : 'text-violet-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.reports.*') ? 'text-violet-900' : 'text-violet-400 group-hover:text-white transition-colors duration-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.200ms>Reports</span>
                    <div x-show="!sidebarOpen" class="absolute left-14 bg-white text-violet-950 font-semibold shadow-xl border border-violet-100 text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">Reports</div>
                </a>
            </nav>
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
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 border-2 border-white dark:border-[#13111C] rounded-full"></span>
                    </button>
                    
                    <!-- Admin Profile Dropdown Dummy -->
                    <div class="flex items-center space-x-3 cursor-pointer group">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-violet-600 to-indigo-600 p-[2px] shadow-lg shadow-violet-500/20">
                            <div class="w-full h-full rounded-full bg-white dark:bg-gray-800 border border-white dark:border-gray-800 overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name=Admin+User&background=random&color=fff" alt="Admin" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Administrator</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">admin@kyusify.com</p>
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
