<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-[#13111C]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kyusify') }} - Seller Registration</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased h-full flex items-center justify-center p-4">
        
        <div class="w-full max-w-4xl bg-white dark:bg-[#0B0A0F] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 overflow-hidden flex flex-col md:flex-row">
            
            <!-- Branding Panel -->
            <div class="md:w-5/12 bg-gradient-to-br from-violet-600 to-violet-900 p-10 text-white flex flex-col justify-between relative overflow-hidden">
                <!-- Decorative Circle -->
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-violet-400/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 hidden md:block">
                    <div class="flex items-center space-x-2 mb-8">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-violet-700 font-bold text-xl tracking-tighter">K</span>
                        </div>
                        <span class="text-2xl font-bold tracking-tight">Kyusify</span>
                    </div>
                </div>

                <div class="relative z-10 mb-8 md:mb-0 text-center md:text-left">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6 mx-auto md:mx-0 backdrop-blur-sm border border-white/10">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-4 tracking-tight">Launch your student enterprise.</h2>
                    <p class="text-violet-100 leading-relaxed text-sm">Join the Kyusify marketplace tailored exclusively for QCU student entrepreneurs. Start selling your products to the campus community today.</p>
                </div>
                
                <div class="relative z-10 text-sm text-violet-200/60 hidden md:block">
                    &copy; {{ date('Y') }} Quezon City University
                </div>
            </div>

            <!-- Form Panel -->
            <div class="md:w-7/12 p-8 md:p-12 relative">
                <!-- Mobile Branding (visible only on small screens) -->
                <div class="md:hidden flex items-center justify-center space-x-2 mb-8">
                    <div class="w-10 h-10 bg-gradient-to-br from-violet-600 to-violet-500 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-xl tracking-tighter">K</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Kyusify</span>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight mb-2 text-center md:text-left">Apply as a Seller</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-8 text-center md:text-left">Enter your details and business information to create your seller account.</p>

                <form method="POST" action="/seller/register" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name <span class="text-red-500">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">QCU Email Address <span class="text-red-500">*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="student@qcu.edu.ph" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Business Name -->
                    <div>
                        <label for="business_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Business / Enterprise Name <span class="text-red-500">*</span></label>
                        <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}" required placeholder="e.g. QCU Tech Merchandise" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('business_name') border-red-500 @enderror">
                        @error('business_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">This will be your storefront name displayed to customers.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password <span class="text-red-500">*</span></label>
                            <input id="password" type="password" name="password" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
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
                </form>
            </div>
        </div>

    </body>
</html>
