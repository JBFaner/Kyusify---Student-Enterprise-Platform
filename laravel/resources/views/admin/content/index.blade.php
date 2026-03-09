<x-admin-layout>
    <x-slot name="header">
        Content Management
    </x-slot>

    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Landing Page Content</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Update the text displayed on the public landing page.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 mb-8 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 dark:text-green-500">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 max-w-4xl">
        <form method="POST" action="{{ route('admin.content.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="hero_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hero Title <span class="text-red-500">*</span></label>
                <input type="text" id="hero_title" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? 'The Marketplace for QCU Entrepreneurs') }}" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('hero_title') border-red-500 @enderror">
                <p class="mt-1.5 text-xs text-gray-500">The main prominent text displayed at the top of the landing page.</p>
                @error('hero_title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hero Subtitle <span class="text-red-500">*</span></label>
                <textarea id="hero_subtitle" name="hero_subtitle" rows="3" required class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow resize-none @error('hero_subtitle') border-red-500 @enderror">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? 'Discover unique products, services, and innovations crafted by Quezon City University students.') }}</textarea>
                <p class="mt-1.5 text-xs text-gray-500">The supporting text below the main title.</p>
                @error('hero_subtitle')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="about_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">About Section Text</label>
                <textarea id="about_text" name="about_text" rows="4" class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow resize-none @error('about_text') border-red-500 @enderror">{{ old('about_text', $settings['about_text'] ?? 'Support the local QCU student community by purchasing directly from student-run enterprises.') }}</textarea>
                <p class="mt-1.5 text-xs text-gray-500">Optional text for an 'About' or 'Why Buy from Us' section.</p>
                @error('about_text')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-sm font-medium shadow-lg shadow-violet-500/30 transition-all duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
