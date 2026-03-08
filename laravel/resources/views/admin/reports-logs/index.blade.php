<x-admin-layout>
    <x-slot name="header">
        Reports & Logs
    </x-slot>

    <div class="bg-white dark:bg-[#13111C] overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] rounded-2xl border border-gray-100 dark:border-gray-800/60 transition-colors duration-300">
        <div class="p-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight mb-8">System Reports & Activity Logs</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-5 border border-gray-100 dark:border-gray-800 rounded-xl bg-gray-50/50 dark:bg-[#0B0A0F] hover:shadow-md hover:border-violet-200 dark:hover:border-violet-800 transition-all duration-300 group">
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white text-base group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Transaction Logs</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Detailed view of recent platform transactions and seller activities.</p>
                    </div>
                    <button class="px-5 py-2.5 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-violet-600 dark:hover:text-violet-400 hover:border-violet-300 dark:hover:border-violet-700 shadow-sm transition-all shrink-0">View Logs</button>
                </div>

                <div class="flex items-center justify-between p-5 border border-gray-100 dark:border-gray-800 rounded-xl bg-gray-50/50 dark:bg-[#0B0A0F] hover:shadow-md hover:border-violet-200 dark:hover:border-violet-800 transition-all duration-300 group">
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white text-base group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">System Activity</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitor overall platform usage, login attempts, and unusual events.</p>
                    </div>
                    <button class="px-5 py-2.5 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-violet-600 dark:hover:text-violet-400 hover:border-violet-300 dark:hover:border-violet-700 shadow-sm transition-all shrink-0">View Logs</button>
                </div>
            </div>
            
        </div>
    </div>
</x-admin-layout>
