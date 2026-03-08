<x-admin-layout>
    <x-slot name="header">
        Platform Content
    </x-slot>

    <div class="bg-white dark:bg-[#13111C] overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] rounded-2xl border border-gray-100 dark:border-gray-800/60 transition-colors duration-300">
        <div class="p-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight mb-8">Manage Homepage & Content</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Announcements Board -->
                <div class="border border-gray-100 dark:border-gray-800 rounded-2xl p-6 bg-white dark:bg-[#0B0A0F] shadow-sm hover:shadow-lg hover:shadow-violet-500/5 transition-all duration-300 group">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center text-lg">
                        <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        Announcements
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">Post new announcements, events, or important guidelines for students and sellers on the platform.</p>
                    <button class="w-full px-5 py-2.5 bg-gray-50 dark:bg-[#13111C] text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:text-violet-600 dark:hover:text-violet-400 hover:border-violet-200 dark:hover:border-violet-800 transition-all text-sm font-semibold shadow-sm">Manage Announcements</button>
                </div>

                <!-- Banners Board -->
                <div class="border border-gray-100 dark:border-gray-800 rounded-2xl p-6 bg-white dark:bg-[#0B0A0F] shadow-sm hover:shadow-lg hover:shadow-violet-500/5 transition-all duration-300 group">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center text-lg">
                        <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        Promotional Banners
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">Upload and arrange striking visual banners showcased on the platform's landing page to highlight events.</p>
                    <button class="w-full px-5 py-2.5 bg-gray-50 dark:bg-[#13111C] text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:text-violet-600 dark:hover:text-violet-400 hover:border-violet-200 dark:hover:border-violet-800 transition-all text-sm font-semibold shadow-sm">Manage Banners</button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
