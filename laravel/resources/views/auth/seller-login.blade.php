<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-[#13111C]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kyusify') }} - Seller Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased h-full flex items-center justify-center p-4">
        
        <div class="w-full max-w-4xl bg-white dark:bg-[#0B0A0F] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 overflow-hidden flex flex-col md:flex-row">
            
            <!-- Branding Panel -->
            <div class="md:w-5/12 bg-gradient-to-br from-violet-600 to-violet-900 p-10 text-white flex flex-col justify-between relative overflow-hidden hidden md:flex">
                <!-- Decorative Circle -->
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-violet-400/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-2 mb-8">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-violet-700 font-bold text-xl tracking-tighter">K</span>
                        </div>
                        <span class="text-2xl font-bold tracking-tight">Kyusify</span>
                    </div>
                </div>

                <div class="relative z-10">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6 mx-auto md:mx-0 backdrop-blur-sm border border-white/10">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-4 tracking-tight">Welcome back to Seller Central.</h2>
                    <p class="text-violet-100 leading-relaxed text-sm">Manage your products, engage with customers, and grow your student business on Kyusify.</p>
                </div>
                
                <div class="relative z-10 text-sm text-violet-200/60">
                    &copy; {{ date('Y') }} Quezon City University
                </div>
            </div>

            <!-- Form Panel -->
            <div class="md:w-7/12 p-8 md:p-12 relative flex flex-col justify-center">
                <!-- Mobile Branding (visible only on small screens) -->
                <div class="md:hidden flex flex-col items-center justify-center space-y-4 mb-8">
                    <div class="w-12 h-12 bg-gradient-to-br from-violet-600 to-violet-500 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-2xl tracking-tighter">K</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Kyusify Seller</span>
                </div>

                <div class="mb-10 text-center md:text-left">
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">Sign in to your account</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Access your business dashboard and manage orders.</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $error }}
                            </p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="/seller/login" class="space-y-6">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="student@qcu.edu.ph" class="w-full px-4 py-3 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('email') border-red-500 @enderror">
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-violet-600 hover:text-violet-500 dark:text-violet-400 transition-colors">Forgot password?</a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required class="w-full px-4 py-3 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('password') border-red-500 @enderror">
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 dark:bg-[#13111C] dark:border-gray-700">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Remember me
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full px-8 py-3.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-violet-500/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-[#0B0A0F] flex justify-center items-center">
                            Sign in to Dashboard
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Not a seller on Kyusify yet? 
                    <a href="{{ route('seller.register') }}" class="font-bold text-violet-600 hover:text-violet-500 dark:text-violet-400 transition-colors">Apply now</a>
                </div>
            </div>
        </div>

    </body>
</html>
