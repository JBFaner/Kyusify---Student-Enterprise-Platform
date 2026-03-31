<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Expired - Kyusify</title>
    <link rel="icon" href="{{ asset('images/kyusify-logo.ico') }}" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|outfit:400,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen border-t-4 border-violet-600 font-sans selection:bg-violet-500 selection:text-white bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-gray-100 flex items-center justify-center p-4">
    <div class="max-w-xl w-full text-center relative z-10">
        
        <!-- Animated Background Blobs -->
        <div class="fixed top-1/4 left-1/4 w-96 h-96 bg-violet-400/20 dark:bg-violet-900/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob pointer-events-none -z-10"></div>
        <div class="fixed top-1/3 right-1/4 w-96 h-96 bg-fuchsia-400/20 dark:bg-fuchsia-900/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000 pointer-events-none -z-10"></div>

        <div class="bg-white dark:bg-[#13111C] p-10 md:p-14 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 relative overflow-hidden backdrop-blur-xl">
            <!-- Icon -->
            <div class="mx-auto w-20 h-20 rounded-2xl bg-violet-50 dark:bg-violet-900/20 flex flex-col items-center justify-center mb-8 border border-violet-100 dark:border-violet-800/50 rotate-3 transition-transform hover:rotate-0 duration-300">
                <svg class="w-10 h-10 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h1 class="font-outfit text-7xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-violet-600 to-fuchsia-500 mb-2">419</h1>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Page Expired</h2>
            
            <p class="text-gray-500 dark:text-gray-400 mb-10 leading-relaxed text-sm md:text-base px-4">
                The page you were looking at has expired for security reasons, usually because you were inactive for too long.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <button onclick="window.location.reload()" class="w-full sm:w-auto px-8 py-3.5 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all font-semibold shadow-sm focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 flex justify-center items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh Page
                </button>
                <a href="{{ route('landing') }}" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl font-bold shadow-lg shadow-violet-500/30 transition-all transform hover:-translate-y-0.5 focus:ring-4 focus:ring-violet-500/50 flex justify-center items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
        
        <div class="mt-8 text-sm text-gray-400 dark:text-gray-500">
            &copy; {{ date('Y') }} Kyusify. All rights reserved.
        </div>
    </div>
</body>
</html>
