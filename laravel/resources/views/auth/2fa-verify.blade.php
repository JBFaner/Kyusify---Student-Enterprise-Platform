<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-[#13111C]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kyusify') }} - Two-Factor Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased h-full flex items-center justify-center p-4 relative">
        <x-auth-interactive-background />
        
        <div class="w-full max-w-lg bg-white/80 dark:bg-[#0B0A0F]/80 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.2)] border border-white/20 dark:border-gray-800/60 overflow-hidden relative z-10 transition-colors duration-300">
            
            <div class="p-8 md:p-12 relative flex flex-col items-center justify-center text-center">
                
                <div class="w-16 h-16 bg-violet-100 dark:bg-violet-900/30 rounded-2xl flex items-center justify-center shadow-inner mb-6 border border-violet-200 dark:border-violet-800/50">
                    <svg class="w-8 h-8 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">Check your email</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">We've sent a 6-digit verification code to your email address. It will expire in 10 minutes.</p>

                <form method="POST" action="{{ route('2fa.verify.store') }}" class="w-full space-y-6">
                    @csrf
                    
                    <div>
                        <label for="code" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 text-left">Verification Code</label>
                        <input id="code" type="text" name="code" value="{{ old('code') }}" required autofocus maxlength="6" pattern="\d{6}" placeholder="••••••" class="w-full px-4 py-4 text-center text-3xl tracking-[0.5em] font-bold bg-gray-50/50 dark:bg-[#13111C]/50 border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow shadow-inner @error('code') border-red-500 ring-1 ring-red-500 focus:ring-red-500 @enderror">
                        @error('code')
                            <p class="mt-2 text-sm text-red-500 text-left font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full px-8 py-3.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-violet-500/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-[#0B0A0F] flex justify-center items-center">
                            Verify & Authenticate
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-sm text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-800 pt-6 w-full">
                    Didn't receive the code? Check your spam folder or 
                    <a href="{{ route('login') }}" class="font-bold text-violet-600 hover:text-violet-500 dark:text-violet-400 transition-colors">return to login</a>.
                </div>
            </div>
        </div>
    </body>
</html>
