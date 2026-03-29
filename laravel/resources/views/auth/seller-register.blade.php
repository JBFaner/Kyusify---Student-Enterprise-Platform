<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-[#13111C]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kyusify') }} - Apply as Seller</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased h-full flex items-center justify-center p-4 relative">
        <x-auth-interactive-background />
        
        <div class="w-full max-w-4xl bg-white/80 dark:bg-[#0B0A0F]/80 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.2)] border border-white/20 dark:border-gray-800/60 overflow-hidden flex flex-col md:flex-row relative z-10 transition-colors duration-300">
            
            <!-- Branding Panel (desktop only) -->
            <div class="md:w-5/12 bg-fuchsia-950/80 dark:bg-fuchsia-950/80 backdrop-blur-md p-10 text-white flex-col justify-between relative overflow-hidden hidden md:flex">
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-violet-400/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-2 mb-8">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg overflow-hidden p-0.5">
                            <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-2xl font-bold tracking-tight">Kyusify</span>
                    </div>
                </div>

                <div class="relative z-10 mb-8 md:mb-0">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm border border-white/10">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-4 tracking-tight">Upgrade to Seller.</h2>
                    <p class="text-violet-100 leading-relaxed text-sm">You already have a Kyusify account. Just add your enterprise name and you're ready to start selling to the QCU community.</p>
                </div>
                
                <div class="relative z-10 text-sm text-violet-200/60 hidden md:block">
                    &copy; {{ date('Y') }} Quezon City University
                </div>
            </div>

            <!-- Form Panel -->
            <div class="md:w-7/12 p-8 md:p-12 relative">
                <!-- Mobile Branding -->
                <div class="md:hidden flex items-center justify-center space-x-2 mb-8">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg overflow-hidden p-0.5 border border-gray-100">
                        <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Kyusify</span>
                </div>

                @if(auth()->check())
                    {{-- Logged in customer upgrading to seller --}}
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight mb-1 text-center md:text-left">Apply as a Seller</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-8 text-center md:text-left">Your account info is pre-filled. Just add your enterprise name.</p>

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-sm text-red-600 dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seller.upgrade') }}" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Name (pre-filled, disabled) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                <input type="text" value="{{ auth()->user()->name }}" disabled
                                    class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            </div>

                            <!-- Email (pre-filled, disabled) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                <input type="email" value="{{ auth()->user()->email }}" disabled
                                    class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Business Name (required input) -->
                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Business / Enterprise Name <span class="text-red-500">*</span>
                            </label>
                            <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}" required
                                placeholder="e.g. QCU Tech Merchandise"
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('business_name') border-red-500 @enderror">
                            @error('business_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">This will be your storefront name displayed to customers.</p>
                        </div>

                        <!-- Password (disabled — cannot type, cannot copy) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                            <div class="relative">
                                <input type="password" value="••••••••" disabled oncopy="return false" oncut="return false"
                                    class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-400 cursor-not-allowed select-none">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 bg-gray-200 dark:bg-gray-800 rounded px-2 py-0.5">locked</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Your existing password is used. You cannot change it here.</p>
                        </div>

                        <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <a href="{{ route('discover') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                ← Back to Discover
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-violet-500/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-[#0B0A0F]">
                                Upgrade to Seller
                            </button>
                        </div>
                    </form>

                @else
                    {{-- Guest — show the original full seller registration form --}}
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight mb-2 text-center md:text-left">Apply as a Seller</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-8 text-center md:text-left">Enter your details and business information to create your seller account.</p>

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-sm text-red-600 dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="/seller/register" class="space-y-5">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name <span class="text-red-500">*</span></label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('name') border-red-500 @enderror">
                                @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">QCU Email Address <span class="text-red-500">*</span></label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="student@qcu.edu.ph" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('email') border-red-500 @enderror">
                                @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Business / Enterprise Name <span class="text-red-500">*</span></label>
                            <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}" required placeholder="e.g. QCU Tech Merchandise" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('business_name') border-red-500 @enderror">
                            @error('business_name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            <p class="mt-2 text-xs text-gray-500">This will be your storefront name displayed to customers.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password <span class="text-red-500">*</span></label>
                                <input id="password" type="password" name="password" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('password') border-red-500 @enderror">
                                @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow">
                            </div>
                        </div>

                        <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <a class="text-sm font-medium text-violet-600 hover:text-violet-500 dark:text-violet-400 dark:hover:text-violet-300 transition-colors" href="{{ route('seller.login') }}">
                                Already have an account? Log in
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-violet-500/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-[#0B0A0F]">
                                Create Account
                            </button>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800 text-center text-sm text-gray-500 dark:text-gray-400">
                            Already a customer? 
                            <a href="{{ route('login') }}" class="font-bold text-violet-600 hover:text-violet-500 dark:text-violet-400 transition-colors">Log in first, then apply as seller</a>
                        </div>
                    </form>
                @endif
            </div>
        </div>

    </body>
</html>
