<x-seller-layout>
    <x-slot name="header">
        Access Denied
    </x-slot>

    <div class="max-w-3xl mx-auto py-24 px-4 sm:px-6 lg:px-8 text-center mt-12 bg-white dark:bg-[#13111C] p-6 lg:p-12 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6 shadow-inner ring-8 ring-yellow-50 dark:ring-yellow-900/10">
            <svg class="h-10 w-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-4">Account Pending Review</h2>
        
        <p class="text-lg text-gray-500 dark:text-gray-400 max-w-xl mx-auto mb-10 leading-relaxed">
            Your store is currently under review by Kyusify Administrators. This module is restricted until your account is fully verified. <br><br>
            Please ensure all your contact details and Student Verification documents are uploaded in your Store Profile to expedite the process.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('seller.dashboard') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent rounded-xl shadow-lg shadow-violet-500/20 text-sm font-bold text-white bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-all duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Return to Dashboard
            </a>
            
            <a href="{{ route('seller.profile.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-300">
                <svg class="w-4 h-4 mr-2 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Manage Profile
            </a>
        </div>
    </div>
</x-seller-layout>
