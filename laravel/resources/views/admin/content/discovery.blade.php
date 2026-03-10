<x-admin-layout>
    <x-slot name="header">
        Content Management
    </x-slot>

    @include('admin.content.tabs')

    <div class="mb-6 mt-4">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Product Discovery Settings</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Configure how products are displayed to users on the discover page.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 mb-8 rounded-r-xl">
            <p class="text-sm text-green-700 dark:text-green-500">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 max-w-4xl">
        <form method="POST" action="{{ route('admin.content.discovery.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="discover_items_per_page" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Items Per Page <span class="text-red-500">*</span></label>
                <input type="number" id="discover_items_per_page" name="discover_items_per_page" min="4" max="100" value="{{ old('discover_items_per_page', $settings['discover_items_per_page'] ?? '12') }}" required class="w-full md:w-1/3 px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('discover_items_per_page') border-red-500 @enderror">
                <p class="mt-1.5 text-xs text-gray-500">Number of products to show before paginating.</p>
                @error('discover_items_per_page')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="discover_default_sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Default Sorting <span class="text-red-500">*</span></label>
                <select id="discover_default_sort" name="discover_default_sort" required class="w-full md:w-1/3 px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('discover_default_sort') border-red-500 @enderror">
                    <option value="newest" {{ (old('discover_default_sort', $settings['discover_default_sort'] ?? '') === 'newest') ? 'selected' : '' }}>Newest Arrivals</option>
                    <option value="popular" {{ (old('discover_default_sort', $settings['discover_default_sort'] ?? '') === 'popular') ? 'selected' : '' }}>Most Popular (Views)</option>
                    <option value="bestselling" {{ (old('discover_default_sort', $settings['discover_default_sort'] ?? '') === 'bestselling') ? 'selected' : '' }}>Best Selling (Orders)</option>
                </select>
                <p class="mt-1.5 text-xs text-gray-500">The default sorting order for products shown on the discover page.</p>
                @error('discover_default_sort')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <div class="flex items-center">
                    <input type="checkbox" id="discover_enable_pagination" name="discover_enable_pagination" value="1" {{ (old('discover_enable_pagination', $settings['discover_enable_pagination'] ?? '1') == '1') ? 'checked' : '' }} class="w-4 h-4 text-violet-600 bg-gray-50 border-gray-300 rounded focus:ring-violet-500 dark:focus:ring-violet-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-[#0B0A0F] dark:border-gray-800">
                    <label for="discover_enable_pagination" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Enable Pagination</label>
                </div>
                <p class="mt-1.5 ml-6 text-xs text-gray-500">If disabled, a "Load More" button or infinite scroll may be used (depending on frontend implementation) instead of explicit page numbers.</p>
            </div>

            <div class="pt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-sm font-medium shadow-lg shadow-violet-500/30 transition-all duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
