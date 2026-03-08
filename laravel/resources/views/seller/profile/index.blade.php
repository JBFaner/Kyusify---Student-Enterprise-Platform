<x-seller-layout>
    <x-slot name="header">
        Business Profile
    </x-slot>

    <!-- Page Header & Status Banner -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mb-2">Business Profile</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm max-w-2xl">View your storefront details, contact information, and verification status.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('seller.profile.edit') }}" class="px-4 py-2 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl shadow-lg shadow-violet-500/30 transition-all text-sm font-semibold flex items-center shrink-0">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Status Alert Based on Enterprise status -->
    @if ($enterprise->status === 'pending')
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 mb-8 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-400">Account Pending Review</h3>
                    <p class="text-sm text-yellow-700 dark:text-yellow-500 mt-1">Your store is currently under review by Kyusify Administrators. Please ensure all your contact details and verification documents are uploaded to expedite this process.</p>
                </div>
            </div>
        </div>
    @elseif ($enterprise->status === 'approved')
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 mb-8 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-green-800 dark:text-green-400">Account Active</h3>
                    <p class="text-sm text-green-700 dark:text-green-500 mt-1">Your business profile is approved and active. Updates here will reflect immediately on your public storefront.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
        <!-- Left Column: Branding (Logo) & Status -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Branding Card -->
            <div class="bg-white dark:bg-[#13111C] p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Store Branding</h3>
                
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 mb-4">
                        @if($enterprise->logo_path)
                            <img src="{{ Storage::url($enterprise->logo_path) }}" alt="{{ $enterprise->name }}" class="w-full h-full rounded-full object-cover border-4 border-white dark:border-[#13111C] shadow-lg ring-2 ring-violet-100 dark:ring-violet-900/30">
                        @else
                            <div class="w-full h-full rounded-full bg-violet-50 dark:bg-violet-900/20 flex flex-col items-center justify-center border-2 border-dashed border-violet-200 dark:border-violet-800/50 text-violet-500">
                                <span class="text-3xl font-bold uppercase tracking-wider">{{ substr($enterprise->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Admin Verification Status -->
            <div class="bg-white dark:bg-[#13111C] p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Verification Check</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="mt-0.5 flex-shrink-0">
                            @if($enterprise->status !== 'rejected')
                                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Account Created</p>
                            <p class="text-xs text-gray-500">Completed on {{ $enterprise->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="mt-0.5 flex-shrink-0">
                            @if($enterprise->is_student_verified)
                                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg class="h-5 w-5 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">QCU Student Status</p>
                            <p class="text-xs text-gray-500">
                                {{ $enterprise->is_student_verified ? 'Verified by Admin' : 'Pending Verification' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Profile Display -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info Card -->
            <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Business Details</h3>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Display Name</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $enterprise->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Business Description</p>
                        <div class="p-4 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl text-gray-800 dark:text-gray-200 min-h-[100px] whitespace-pre-wrap">{{ $enterprise->description ?? 'No business description provided.' }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100 dark:border-gray-800/60">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Business Support Email</p>
                            <div class="flex items-center text-gray-900 dark:text-white font-medium">
                                <svg class="h-5 w-5 text-violet-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $enterprise->contact_email ?? 'Not provided' }}
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Contact Phone</p>
                            <div class="flex items-center text-gray-900 dark:text-white font-medium">
                                <svg class="h-5 w-5 text-violet-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $enterprise->contact_phone ?? 'Not provided' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verification Document Card -->
            <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Student Identity Document</h3>
                
                <div class="mt-4">
                    @if($enterprise->document_path)
                        <div class="flex items-center p-4 bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300 rounded-xl border border-violet-100 dark:border-violet-800/50 justify-between">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <div class="text-sm font-semibold truncate">
                                    Document Uploaded ({{ basename($enterprise->document_path) }})
                                </div>
                            </div>
                            <a href="{{ Storage::url($enterprise->document_path) }}" target="_blank" class="text-xs font-bold uppercase tracking-wider text-violet-700 hover:text-violet-900 dark:text-violet-300 dark:hover:text-white transition-colors bg-white/60 dark:bg-black/40 hover:bg-white dark:hover:bg-black/60 px-4 py-2 rounded-lg border border-violet-200 dark:border-violet-700/50 flex items-center shadow-sm">
                                View
                            </a>
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl flex items-center">
                            <svg class="h-6 w-6 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">No verification document has been uploaded yet.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-seller-layout>
