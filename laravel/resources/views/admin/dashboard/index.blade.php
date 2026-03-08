<x-admin-layout>
    <x-slot name="header">
        Dashboard & Analytics
    </x-slot>

    <div class="bg-white dark:bg-[#13111C] overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] rounded-2xl border border-gray-100 dark:border-gray-800/60 transition-colors duration-300">
        <div class="p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Platform Overview</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Real-time metrics and system status</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stats Cards -->
                <div class="relative group bg-white dark:bg-[#0B0A0F] p-6 rounded-2xl border border-gray-100 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-400 shadow-sm hover:shadow-xl hover:shadow-violet-500/10 transition-all duration-300 overflow-hidden text-center cursor-pointer">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 dark:bg-indigo-900/20 rounded-full blur-2xl group-hover:bg-violet-100 dark:group-hover:bg-violet-900/30 transition-colors duration-500"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:text-violet-600 group-hover:bg-violet-50 transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Sellers</p>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1 mb-2 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">0</p>
                    </div>
                </div>

                <div class="relative group bg-white dark:bg-[#0B0A0F] p-6 rounded-2xl border border-gray-100 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-400 shadow-sm hover:shadow-xl hover:shadow-violet-500/10 transition-all duration-300 overflow-hidden text-center cursor-pointer">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-sky-50 dark:bg-sky-900/20 rounded-full blur-2xl group-hover:bg-violet-100 dark:group-hover:bg-violet-900/30 transition-colors duration-500"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-12 h-12 bg-sky-50 text-sky-600 dark:bg-sky-900/50 dark:text-sky-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:text-violet-600 group-hover:bg-violet-50 transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Customers</p>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1 mb-2 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">0</p>
                    </div>
                </div>

                <div class="relative group bg-white dark:bg-[#0B0A0F] p-6 rounded-2xl border border-gray-100 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-400 shadow-sm hover:shadow-xl hover:shadow-violet-500/10 transition-all duration-300 overflow-hidden text-center cursor-pointer">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-fuchsia-50 dark:bg-fuchsia-900/20 rounded-full blur-2xl group-hover:bg-violet-100 dark:group-hover:bg-violet-900/30 transition-colors duration-500"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-12 h-12 bg-fuchsia-50 text-fuchsia-600 dark:bg-fuchsia-900/50 dark:text-fuchsia-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:text-violet-600 group-hover:bg-violet-50 transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Products</p>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1 mb-2 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">0</p>
                    </div>
                </div>

                <div class="relative group bg-white dark:bg-[#0B0A0F] p-6 rounded-2xl border border-gray-100 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-400 shadow-sm hover:shadow-xl hover:shadow-violet-500/10 transition-all duration-300 overflow-hidden text-center cursor-pointer">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-50 dark:bg-emerald-900/20 rounded-full blur-2xl group-hover:bg-violet-100 dark:group-hover:bg-violet-900/30 transition-colors duration-500"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:text-violet-600 group-hover:bg-violet-50 transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Enterprises</p>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1 mb-2 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">0</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-admin-layout>
