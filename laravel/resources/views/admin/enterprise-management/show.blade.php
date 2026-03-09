<x-admin-layout>
    <x-slot name="header">
        Enterprise Management - Review
    </x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.enterprises.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 text-gray-500 hover:text-violet-600 dark:text-gray-400 dark:hover:text-violet-400 hover:border-violet-200 dark:hover:border-violet-800 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Review Enterprise Profile</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Viewing details for {{ $enterprise->name }}</p>
            </div>
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('admin.enterprises.edit', $enterprise) }}" class="px-4 py-2 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-violet-600 dark:hover:text-violet-400 transition-all font-medium text-sm flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Details
            </a>
            
            <!-- Quick Approve Action -->
            @if($enterprise->status !== 'approved')
            <form action="{{ route('admin.enterprises.update', $enterprise) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="name" value="{{ $enterprise->name }}">
                <input type="hidden" name="status" value="approved">
                @if($enterprise->is_student_verified)
                <input type="hidden" name="is_student_verified" value="1">
                @endif
                <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl shadow-lg shadow-green-500/30 transition-all text-sm font-semibold flex items-center shrink-0">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Direct Approve
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar context -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Enterprise Card -->
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl p-8 bg-white dark:bg-[#0B0A0F] shadow-sm flex flex-col items-center relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-tr from-violet-600 to-indigo-600 z-0"></div>
                
                <div class="w-24 h-24 rounded-2xl border-4 border-white dark:border-[#0B0A0F] shadow-lg overflow-hidden relative z-10 mt-6 bg-white flex items-center justify-center">
                    @if($enterprise->logo_path)
                        <img src="{{ asset($enterprise->logo_path) }}" alt="{{ $enterprise->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl font-bold text-violet-600">{{ substr($enterprise->name, 0, 1) }}</span>
                    @endif
                </div>
                
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-4 mb-1 text-center">{{ $enterprise->name }}</h2>
                <div class="flex space-x-2 mt-2">
                    @if ($enterprise->status === 'approved')
                        <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Approved</span>
                    @elseif ($enterprise->status === 'pending')
                        <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">Pending Review</span>
                    @else
                        <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Rejected</span>
                    @endif
                </div>

                <div class="w-full border-t border-gray-100 dark:border-gray-800/60 mt-6 pt-6 grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 leading-tight">Student Status</p>
                        @if($enterprise->is_student_verified)
                            <span class="text-green-500 font-semibold text-sm flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Verified
                            </span>
                        @else
                            <span class="text-red-500 dark:text-red-400 font-semibold text-sm flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Unverified
                            </span>
                        @endif
                    </div>
                    <div class="border-l border-gray-100 dark:border-gray-800/60">
                         <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 leading-tight">Submitted</p>
                         <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $enterprise->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Owner Info -->
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl bg-white dark:bg-[#0B0A0F] shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#13111C]/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-4 h-4 mr-2 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Student Owner
                    </h3>
                    <a href="{{ route('admin.users.show', $enterprise->user_id) }}" class="text-xs text-violet-600 hover:text-violet-700 dark:text-violet-400 dark:hover:text-violet-300 transition-colors">View Profile</a>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center shrink-0 border border-violet-200 dark:border-violet-800">
                            <span class="text-violet-700 dark:text-violet-300 font-bold text-sm">{{ substr($enterprise->user->name ?? '?', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $enterprise->user->name ?? 'Unknown Student' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $enterprise->user->email ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description & Review Panel -->
        <div class="lg:col-span-2 space-y-6">
            @if($enterprise->store_branding)
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl bg-white dark:bg-[#0B0A0F] shadow-sm overflow-hidden h-48 sm:h-64 relative">
                <img src="{{ Storage::url($enterprise->store_branding) }}" alt="Store Branding" class="w-full h-full object-cover">
                <div class="absolute inset-x-0 bottom-0 py-2 px-4 bg-gradient-to-t from-black/60 to-transparent">
                    <span class="text-white text-xs font-semibold tracking-wider uppercase drop-shadow-md">Store Branding Banner</span>
                </div>
            </div>
            @endif
            
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl bg-white dark:bg-[#0B0A0F] shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#13111C]/50">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Business Information
                    </h3>
                </div>
                <div class="p-8">
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description / Pitch</h4>
                            <div class="p-5 rounded-xl bg-gray-50 dark:bg-[#13111C] border border-gray-100 dark:border-gray-800/60 text-sm text-gray-800 dark:text-gray-200 leading-relaxed min-h-[100px]">
                                {{ $enterprise->description ?? 'No business description provided by the student.' }}
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Proof of Student Identity (QCU ID/COR)</h4>
                            @if($enterprise->document_path)
                                <div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300 rounded-xl border border-violet-100 dark:border-violet-800/50 gap-4">
                                    <div class="flex items-center min-w-0">
                                        <svg class="w-6 h-6 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <div class="text-sm font-semibold truncate">
                                            Student ID / COR Document
                                        </div>
                                    </div>
                                    <div class="flex space-x-2 shrink-0">
                                        <a href="{{ Storage::url($enterprise->document_path) }}" target="_blank" class="text-xs font-bold uppercase tracking-wider text-violet-700 hover:text-violet-900 dark:text-violet-300 dark:hover:text-white transition-colors bg-white/60 dark:bg-black/40 hover:bg-white dark:hover:bg-black/60 px-4 py-2 rounded-lg border border-violet-200 dark:border-violet-700/50 flex items-center shadow-sm">
                                            View
                                        </a>
                                        <form action="{{ route('admin.enterprises.update', $enterprise) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" value="{{ $enterprise->name }}">
                                            <input type="hidden" name="status" value="{{ $enterprise->status }}">
                                            @if($enterprise->is_student_verified)
                                                <button type="submit" class="text-xs font-bold uppercase tracking-wider text-red-600 hover:text-red-700 dark:text-red-400 transition-colors bg-red-50 dark:bg-red-900/20 hover:bg-red-100 px-4 py-2 rounded-lg border border-red-200 dark:border-red-800/50 flex items-center shadow-sm">
                                                    Revoke
                                                </button>
                                            @else
                                                <input type="hidden" name="is_student_verified" value="1">
                                                <button type="submit" class="text-xs font-bold uppercase tracking-wider text-green-700 hover:text-green-800 dark:text-green-400 transition-colors bg-green-50 dark:bg-green-900/20 hover:bg-green-100 px-4 py-2 rounded-lg border border-green-200 dark:border-green-800/50 flex items-center shadow-sm">
                                                    Verify
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl p-6 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">No document uploaded.</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">The student has not yet provided verification documents.</p>
                                    
                                    <form action="{{ route('admin.enterprises.update', $enterprise) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $enterprise->name }}">
                                        <input type="hidden" name="status" value="{{ $enterprise->status }}">
                                        @if($enterprise->is_student_verified)
                                            <button type="submit" class="text-xs font-bold uppercase tracking-wider text-red-600 hover:text-red-700 dark:text-red-400 transition-colors bg-red-50 dark:bg-red-900/20 hover:bg-red-100 px-4 py-2 rounded-lg border border-red-200 dark:border-red-800/50 inline-flex items-center shadow-sm">
                                                Revoke Manual Verification
                                            </button>
                                        @else
                                            <input type="hidden" name="is_student_verified" value="1">
                                            <button type="submit" class="text-xs font-bold uppercase tracking-wider text-green-700 hover:text-green-800 dark:text-green-400 transition-colors bg-green-50 dark:bg-green-900/20 hover:bg-green-100 px-4 py-2 rounded-lg border border-green-200 dark:border-green-800/50 inline-flex items-center shadow-sm">
                                                Verify Manually
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Review Actions (Full Form embedded via PUT) -->
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl bg-white dark:bg-[#0B0A0F] shadow-sm overflow-hidden mt-6">
                <div class="px-8 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#13111C]/50">
                     <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center">Admin Review Decision</h3>
                </div>
                <div class="p-8">
                     @if($enterprise->status === 'pending')
                         <form action="{{ route('admin.enterprises.update', $enterprise) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $enterprise->name }}">
                            @if($enterprise->is_student_verified)
                                <input type="hidden" name="is_student_verified" value="1">
                            @endif
                            <!-- Passing along required fields safely -->
                            
                            <div class="flex flex-wrap gap-4 pt-2">
                                 <button type="submit" name="status" value="approved" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl shadow-lg shadow-green-500/30 transition-all font-semibold flex items-center justify-center flex-1">
                                     <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                     Approve Enterprise
                                 </button>
                                 <button type="submit" name="status" value="rejected" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl shadow-lg shadow-red-500/30 transition-all font-semibold flex items-center justify-center flex-1">
                                     <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                     Reject Enterprise
                                 </button>
                            </div>
                         </form>
                     @else
                         <div class="text-center py-4">
                             @if($enterprise->status === 'approved')
                                 <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 text-green-500 mb-4">
                                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                 </div>
                                 <h4 class="text-lg font-bold text-gray-900 dark:text-white">Enterprise Approved</h4>
                                 <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">This enterprise has been approved and is active on the storefront.</p>
                             @elseif($enterprise->status === 'rejected')
                                 <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-500 mb-4">
                                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                 </div>
                                 <h4 class="text-lg font-bold text-gray-900 dark:text-white">Enterprise Rejected</h4>
                                 <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">This enterprise application was rejected.</p>
                             @endif
                         </div>
                     @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
