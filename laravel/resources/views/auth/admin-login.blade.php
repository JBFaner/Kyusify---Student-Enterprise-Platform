<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-[#13111C]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kyusify') }} - Admin Portal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased h-full flex items-center justify-center p-4">
        
        <div class="w-full max-w-4xl bg-white dark:bg-[#0B0A0F] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 overflow-hidden flex flex-col md:flex-row">
            
            <!-- Branding Panel -->
            <div class="md:w-5/12 bg-gradient-to-br from-gray-900 to-black p-10 text-white flex flex-col justify-between relative overflow-hidden hidden md:flex">
                <!-- Decorative Circle -->
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-violet-600/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-2 mb-8">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg overflow-hidden p-0.5">
                            <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-2xl font-bold tracking-tight">Kyusify Admin</span>
                    </div>
                </div>

                <div class="relative z-10">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6 mx-auto md:mx-0 backdrop-blur-sm border border-white/5">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-4 tracking-tight">Platform Administration</h2>
                    <p class="text-gray-300 leading-relaxed text-sm">Oversee enterprises, moderate products, and manage the student platform securely.</p>
                </div>
                
                <div class="relative z-10 text-sm text-gray-500">
                    &copy; {{ date('Y') }} Quezon City University
                </div>
            </div>

            <!-- Form Panel -->
            <div class="md:w-7/12 p-8 md:p-12 relative flex flex-col justify-center">
                <!-- Mobile Branding (visible only on small screens) -->
                <div class="md:hidden flex flex-col items-center justify-center space-y-4 mb-8">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg overflow-hidden p-0.5 border border-gray-100">
                        <img src="{{ asset('images/kyusify-logo.png') }}" alt="Kyusify Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Kyusify Admin</span>
                </div>

                <div class="mb-10 text-center md:text-left">
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">Administrator Access</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Please sign in with your administrative credentials.</p>
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

                <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('email') border-red-500 @enderror">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                        <input id="password" type="password" name="password" required class="w-full px-4 py-3 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('password') border-red-500 @enderror">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full px-8 py-3.5 bg-gray-900 hover:bg-black dark:bg-white dark:hover:bg-gray-100 text-white dark:text-gray-900 rounded-xl text-sm font-bold shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 dark:focus:ring-offset-[#0B0A0F] flex justify-center items-center">
                            Secure Login
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    <a href="{{ url('/') }}" class="font-medium hover:text-gray-900 dark:hover:text-white transition-colors">&larr; Back to Main Site</a>
                </div>
            </div>
        </div>

    </body>
</html>
