<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-[#13111C]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kyusify') }} - Register</title>

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
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg overflow-hidden p-0.5">
                            <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-2xl font-bold tracking-tight">Kyusify</span>
                    </div>
                </div>

                <div class="relative z-10">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6 mx-auto md:mx-0 backdrop-blur-sm border border-white/10">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-4 tracking-tight">Join the community.</h2>
                    <p class="text-violet-100 leading-relaxed text-sm">Create an account to track your orders, leave reviews, and enjoy a seamless shopping experience.</p>
                </div>
                
                <div class="relative z-10 text-sm text-violet-200/60 hidden md:block">
                    &copy; {{ date('Y') }} Quezon City University
                </div>
            </div>

            <!-- Form Panel -->
            <div class="md:w-7/12 p-8 md:p-12 relative">
                <!-- Mobile Branding (visible only on small screens) -->
                <div class="md:hidden flex items-center justify-center space-x-2 mb-8">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg overflow-hidden p-0.5 border border-gray-100">
                        <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Kyusify</span>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight mb-2 text-center md:text-left">Create an account</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-8 text-center md:text-left">Enter your details below to get started.</p>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    
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
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address <span class="text-red-500">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="student@qcu.edu.ph" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
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
                        <a class="text-sm font-medium text-violet-600 hover:text-violet-500 dark:text-violet-400 dark:hover:text-violet-300 transition-colors" href="{{ route('login') }}">
                            Already have an account? Log in
                        </a>
                        
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-violet-500/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-[#0B0A0F]">
                            Create Account
                        </button>
                    </div>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200 dark:border-gray-800"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white dark:bg-[#0B0A0F] text-gray-500">Or sign up with</span>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <button type="button" onclick="alert('Google Auth integration coming soon!')" class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-200 dark:border-gray-800 rounded-xl shadow-sm bg-white dark:bg-[#13111C] text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#222] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                                <svg class="w-5 h-5" aria-hidden="true" viewBox="0 0 24 24">
                                    <path d="M12.0003 4.75C13.7703 4.75 15.3553 5.36 16.6053 6.54998L20.0303 3.125C17.9502 1.19 15.2353 0 12.0003 0C7.31028 0 3.25527 2.69 1.25024 6.65L5.31028 9.79C6.26028 6.84 8.86528 4.75 12.0003 4.75Z" fill="#EA4335"/>
                                    <path d="M23.49 12.275C23.49 11.49 23.415 10.73 23.3 10H12V14.51H18.47C18.18 15.99 17.34 17.25 16.08 18.1L20.08 21.2C22.42 19.05 23.49 15.92 23.49 12.275Z" fill="#4285F4"/>
                                    <path d="M5.26498 14.2949C5.02498 13.5649 4.88501 12.7949 4.88501 11.9949C4.88501 11.1949 5.01998 10.4249 5.26498 9.6949L1.20996 6.55496C0.43996 8.16496 0 9.9949 0 11.9949C0 13.9949 0.43996 15.8249 1.20996 17.4349L5.26498 14.2949Z" fill="#FBBC05"/>
                                    <path d="M12.0004 24.0001C15.2404 24.0001 17.9654 22.935 19.9454 21.095L15.9454 18.0001C14.8704 18.7201 13.5554 19.1601 12.0004 19.1601C8.8654 19.1601 6.26038 17.0701 5.31038 14.1201L1.25037 17.2601C3.25537 21.2201 7.3104 24.0001 12.0004 24.0001Z" fill="#34A853"/>
                                </svg>
                                <span class="sr-only">Sign up with Google</span>
                            </button>

                            <button type="button" onclick="alert('Facebook Auth integration coming soon!')" class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-200 dark:border-gray-800 rounded-xl shadow-sm bg-white dark:bg-[#13111C] text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#222] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                                <svg class="w-5 h-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="sr-only">Sign up with Facebook</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </body>
</html>
