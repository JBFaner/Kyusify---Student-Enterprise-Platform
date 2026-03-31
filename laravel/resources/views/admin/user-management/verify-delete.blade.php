<x-admin-layout>
    <x-slot name="header">
        User Management - Deletion Verification
    </x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 hover:border-red-200 dark:hover:border-red-800 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Destructive Security Verification</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Permanently removing Admin profile for {{ $user->name }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-xl mx-auto mt-12 bg-white dark:bg-[#13111C] px-8 py-10 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-red-100 dark:border-red-900/40 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 rounded-full bg-red-100 dark:bg-red-900/20 blur-3xl opacity-50 pointer-events-none"></div>

        <div class="relative z-10 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center mb-6 ring-8 ring-red-50/50 dark:ring-red-900/10">
                <svg class="w-8 h-8 text-red-600 dark:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Check Email</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                A 6-digit confirmation code has been sent to <strong>{{ $user->email }}</strong>. <br>
                Please enter it below to authorize this <strong class="text-red-500">permanent deletion</strong>.
            </p>

            <form action="{{ route('admin.users.confirm-delete', $user) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="code" class="sr-only">Verification Code</label>
                    <input type="text" name="code" id="code" 
                           class="w-full text-center text-3xl tracking-[0.5em] font-bold py-4 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-red-500/20 focus:border-red-500 text-red-600 dark:text-red-400 placeholder-gray-300 dark:placeholder-gray-700 transition-all @error('code') border-red-500 ring-4 ring-red-500/20 @enderror" 
                           placeholder="000000" maxlength="6" required autofocus autocomplete="off">
                    @error('code')
                        <p class="mt-3 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white rounded-xl text-base font-bold shadow-lg shadow-red-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                    Permanently Delete Admin
                </button>
                
                <p class="mt-6 text-sm text-gray-400">
                    Changed your mind? <a href="{{ route('admin.users.index') }}" class="text-red-600 dark:text-red-400 hover:underline font-semibold">Cancel and return</a>.
                </p>
            </form>
        </div>
    </div>
</x-admin-layout>
