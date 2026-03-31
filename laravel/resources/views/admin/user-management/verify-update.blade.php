<x-admin-layout>
    <x-slot name="header">
        User Management - Verification Required
    </x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 text-gray-500 hover:text-violet-600 dark:text-gray-400 dark:hover:text-violet-400 hover:border-violet-200 dark:hover:border-violet-800 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Security Verification</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Updating Admin profile for {{ $user->name }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-xl mx-auto mt-12 bg-white dark:bg-[#13111C] px-8 py-10 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 rounded-full bg-violet-100 dark:bg-violet-900/20 blur-3xl opacity-50 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 rounded-full bg-fuchsia-100 dark:bg-fuchsia-900/20 blur-3xl opacity-50 pointer-events-none"></div>

        <div class="relative z-10 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-violet-50 dark:bg-violet-900/20 flex items-center justify-center mb-6 ring-8 ring-violet-50/50 dark:ring-violet-900/10">
                <svg class="w-8 h-8 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Check Email</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                A 6-digit verification code has been sent to <strong>{{ $user->email }}</strong>. <br>
                Please enter it below to authorize this profile update.
            </p>

            <form action="{{ route('admin.users.confirm-update', $user) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="code" class="sr-only">Verification Code</label>
                    <input type="text" name="code" id="code" 
                           class="w-full text-center text-3xl tracking-[0.5em] font-bold py-4 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 text-violet-600 dark:text-violet-400 placeholder-gray-300 dark:placeholder-gray-700 transition-all @error('code') border-red-500 ring-4 ring-red-500/20 @enderror" 
                           placeholder="000000" maxlength="6" required autofocus autocomplete="off">
                    @error('code')
                        <p class="mt-3 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-base font-bold shadow-lg shadow-violet-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                    Verify and Update
                </button>
                
                <p class="mt-6 text-sm text-gray-400">
                    Never mind, <a href="{{ route('admin.users.edit', $user) }}" class="text-violet-600 dark:text-violet-400 hover:underline font-semibold">modify the update</a> instead.
                </p>
            </form>
        </div>
    </div>
</x-admin-layout>
