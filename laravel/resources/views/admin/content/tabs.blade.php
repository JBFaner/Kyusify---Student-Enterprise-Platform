    <!-- Sub Navigation Tabs -->
    <div class="mb-8 border-b border-gray-200 dark:border-gray-800 overflow-x-auto">
        <nav class="-mb-px flex space-x-8 min-w-max" aria-label="Tabs">
            <a href="{{ route('admin.content.index') }}" 
               class="{{ request()->routeIs('admin.content.index') ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Homepage Content
            </a>
            <a href="{{ route('admin.content.categories.index') }}" 
               class="{{ request()->routeIs('admin.content.categories.*') ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Categories
            </a>
            <a href="{{ route('admin.content.featured.index') }}" 
               class="{{ request()->routeIs('admin.content.featured.*') ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Featured Products
            </a>
            <a href="{{ route('admin.content.discovery') }}" 
               class="{{ request()->routeIs('admin.content.discovery') ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Discovery Settings
            </a>
            <a href="{{ route('admin.content.moderation.index') }}" 
               class="{{ request()->routeIs('admin.content.moderation.*') ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Product Moderation
            </a>
        </nav>
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
