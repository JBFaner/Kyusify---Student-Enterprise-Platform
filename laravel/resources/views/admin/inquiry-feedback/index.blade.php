<x-admin-layout>
    <x-slot name="header">
        Inquiry & Feedback
    </x-slot>

    <div class="bg-white dark:bg-[#13111C] overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] rounded-2xl border border-gray-100 dark:border-gray-800/60 transition-colors duration-300">
        <div class="p-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight mb-8">Customer Inquiries & Platform Feedback</h3>
            
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl p-12 text-center bg-[#FAFAFA] dark:bg-[#0B0A0F] shadow-inner">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-violet-50 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400 mb-6 shadow-sm">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">No active inquiries</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">All customer inquiries and feedback have been resolved. Great job keeping the platform clean!</p>
            </div>
        </div>
    </div>
</x-admin-layout>
