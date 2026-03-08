<x-admin-layout>
    <x-slot name="header">
        User Management - Profile
    </x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 text-gray-500 hover:text-violet-600 dark:text-gray-400 dark:hover:text-violet-400 hover:border-violet-200 dark:hover:border-violet-800 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">User Profile</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Detailed information about {{ $user->name }}</p>
            </div>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-violet-600 dark:hover:text-violet-400 hover:border-violet-200 dark:hover:border-violet-800 text-sm font-medium transition-all duration-200 shadow-sm flex items-center shrink-0">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit User
            </a>
            
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-900/30 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/30 hover:border-red-300 dark:hover:border-red-800 text-sm font-medium transition-all duration-200 shadow-sm flex items-center shrink-0">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Account
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: User Card -->
        <div class="lg:col-span-1 border border-gray-100 dark:border-gray-800 rounded-2xl p-8 bg-white dark:bg-[#0B0A0F] shadow-sm flex flex-col items-center">
            <div class="relative mb-6">
                <div class="w-32 h-32 rounded-full border-4 border-white dark:border-[#13111C] shadow-lg overflow-hidden relative z-10">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=8b5cf6&color=fff&size=200" alt="{{ $user->name }}" class="w-full h-full object-cover">
                </div>
                <!-- Status Badge -->
                <div class="absolute bottom-1 right-2 w-5 h-5 rounded-full border-2 border-white dark:border-[#13111C] z-20 @if($user->status === 'active') bg-green-500 @elseif($user->status === 'pending') bg-yellow-500 @else bg-red-500 @endif" title="Status: {{ ucfirst($user->status) }}"></div>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $user->name }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6">{{ $user->email }}</p>
            
            <div class="w-full flex justify-center space-x-3 border-t border-gray-100 dark:border-gray-800/60 pt-6">
                <div class="text-center px-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Role</p>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-300">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div class="text-center px-4 border-l border-gray-100 dark:border-gray-800/60">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Status</p>
                    @if ($user->status === 'active')
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                            Active
                        </span>
                    @elseif ($user->status === 'pending')
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                            Pending
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Details & Activity -->
        <div class="lg:col-span-2 space-y-6">
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl bg-white dark:bg-[#0B0A0F] shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#13111C]/50">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Account Details
                    </h3>
                </div>
                <div class="p-8">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $user->email }} @if($user->email_verified_at) <span class="text-green-500 text-xs ml-1 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full inline-flex items-center"><svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Verified</span> @endif</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Role</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ ucfirst($user->role) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ ucfirst($user->status) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $user->created_at->format('M d, Y') }} ({{ $user->created_at->diffForHumans() }})</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $user->updated_at->format('M d, Y') }} ({{ $user->updated_at->diffForHumans() }})</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Optional: Activity Logs or Related Data Placeholder -->
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl bg-white dark:bg-[#0B0A0F] shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#13111C]/50">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Recent Activity
                    </h3>
                </div>
                <div class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    <p>No recent activity recorded for this user.</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
