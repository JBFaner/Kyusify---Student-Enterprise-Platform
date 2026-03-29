<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-[#13111C]">
    <head>
    <link rel="icon" href="{{ asset('images/kyusify-logo.ico') }}" type="image/x-icon">
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
                
                {{-- Masked email display --}}
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">We sent a 6-digit verification code to</p>
                @if(!empty($maskedEmail))
                    <p class="text-violet-600 dark:text-violet-400 font-semibold text-sm mb-6 bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-800/50 rounded-lg px-4 py-2 inline-block">
                        📧 {{ $maskedEmail }}
                    </p>
                @else
                    <p class="text-gray-400 text-sm mb-6">your registered email address</p>
                @endif

                {{-- Success message for resend --}}
                @if(session('resent'))
                    <div class="w-full mb-5 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-400 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        {{ session('resent') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('2fa.verify.store') }}" class="w-full space-y-6">
                    @csrf
                    
                    <div>
                        <label for="code" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 text-left">Verification Code</label>
                        <input id="code" type="text" name="code" value="{{ old('code') }}" required autofocus maxlength="6" pattern="\d{6}" placeholder="••••••" inputmode="numeric"
                            class="w-full px-4 py-4 text-center text-3xl tracking-[0.5em] font-bold bg-gray-50/50 dark:bg-[#13111C]/50 border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow shadow-inner @error('code') border-red-500 ring-1 ring-red-500 focus:ring-red-500 @enderror">
                        @error('code')
                            <p class="mt-2 text-sm text-red-500 text-left font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full px-8 py-3.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-violet-500/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-[#0B0A0F] flex justify-center items-center">
                            Verify & Authenticate
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Resend Code --}}
                <div class="mt-6 border-t border-gray-200 dark:border-gray-800 pt-6 w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Didn't receive it? Check spam or
                    </p>
                    <form method="POST" action="{{ route('2fa.resend') }}">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-violet-600 hover:text-violet-500 dark:text-violet-400 dark:hover:text-violet-300 transition-colors flex items-center gap-1.5 group">
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Resend Code
                        </button>
                    </form>
                </div>

                <p class="mt-4 text-xs text-gray-400 dark:text-gray-500">
                    Wrong account? <a href="{{ route('login') }}" class="font-semibold text-violet-600 hover:text-violet-500 dark:text-violet-400 transition-colors">Return to login</a>
                </p>
            </div>
        </div>
    </body>
</html>
