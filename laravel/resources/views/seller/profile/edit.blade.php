<x-seller-layout>
    <x-slot name="header">
        Business Profile
    </x-slot>

    <!-- Page Header & Status Banner -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mb-2">Manage Business Profile</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm max-w-2xl">Update your storefront details, contact information, and ensure your QCU student verification represents your enterprise accurately.</p>
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

    <form method="POST" action="{{ route('seller.profile.update') }}" enctype="multipart/form-data" class="space-y-8 pb-12">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Branding (Logo) & Status -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Branding Card -->
                <div class="bg-white dark:bg-[#13111C] p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Store Branding</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">This logo will be displayed on all your product pages.</p>
                    
                    <div class="flex flex-col items-center">
                        <div class="relative group cursor-pointer w-32 h-32 mb-4">
                            @if($enterprise->logo_path)
                                <img src="{{ Storage::url($enterprise->logo_path) }}" alt="{{ $enterprise->name }}" class="w-full h-full rounded-full object-cover border-4 border-white dark:border-[#13111C] shadow-lg ring-2 ring-violet-100 dark:ring-violet-900/30">
                            @else
                                <div class="w-full h-full rounded-full bg-violet-50 dark:bg-violet-900/20 flex flex-col items-center justify-center border-2 border-dashed border-violet-200 dark:border-violet-800/50 text-violet-500 overflow-hidden group-hover:bg-violet-100 dark:group-hover:bg-violet-900/40 transition-colors">
                                    <svg class="h-8 w-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[10px] font-bold tracking-wider uppercase">Upload Logo</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/40 rounded-full flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="h-6 w-6 text-white mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16V4a2 2 0 012-2h14a2 2 0 012 2v12m-6-6l-4-4m0 0l-4 4m4-4v12" />
                                </svg>
                                <span class="text-white text-xs font-semibold">Change</span>
                            </div>
                            <input type="file" name="logo" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" accept="image/jpeg,image/png,image/jpg">
                        </div>
                        <p class="text-xs text-gray-400 text-center uppercase font-semibold tracking-wider">Suggested: 500x500px <br> JPG, PNG max 2MB</p>
                        @error('logo')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                <!-- Store Branding (Cover Photo) -->
                <div class="bg-white dark:bg-[#13111C] p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 mt-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Store Branding (Cover)</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">A banner image for your storefront.</p>
                    
                    <div class="flex flex-col items-center">
                        <div class="relative group cursor-pointer w-full h-32 mb-4 rounded-xl overflow-hidden">
                            @if($enterprise->store_branding)
                                <img src="{{ Storage::url($enterprise->store_branding) }}" alt="Store Branding" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-violet-50 dark:bg-violet-900/20 flex flex-col items-center justify-center border-2 border-dashed border-violet-200 dark:border-violet-800/50 text-violet-500 group-hover:bg-violet-100 dark:group-hover:bg-violet-900/40 transition-colors">
                                    <svg class="h-8 w-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span class="text-[10px] font-bold tracking-wider uppercase">Upload Banner</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs font-semibold">Change Cover</span>
                            </div>
                            <input type="file" name="store_branding" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" accept="image/jpeg,image/png,image/jpg">
                        </div>
                        <p class="text-xs text-gray-400 text-center uppercase font-semibold tracking-wider">Suggested: 1200x400px <br> max 5MB</p>
                        @error('store_branding')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
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

            <!-- Right Column: Profile Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Business Details</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Display Name <span class="text-red-500">*</span></label>
                            <input type="text" value="{{ $enterprise->name }}" disabled class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 dark:text-gray-400 cursor-not-allowed cursor-pointer focus:ring-0">
                            <p class="mt-1.5 text-xs text-gray-500 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Contact administrators to change your official business name.
                            </p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Business Description <span class="text-red-500">*</span></label>
                            <textarea id="description" name="description" rows="4" required class="w-full px-4 py-3 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow resize-none @error('description') border-red-500 @enderror" placeholder="Describe what your enterprise sells, your mission, and what makes your products unique to QCU students.">{{ old('description', $enterprise->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Business Support Email <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $enterprise->contact_email) }}" required placeholder="support@yourstore.com" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('contact_email') border-red-500 @enderror">
                                </div>
                                @error('contact_email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contact Phone <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $enterprise->contact_phone) }}" required placeholder="09XX XXX XXXX" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('contact_phone') border-red-500 @enderror">
                                </div>
                                @error('contact_phone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Document Card -->
                <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Student Verification <span class="text-red-500">*</span></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">Because Kyusify is exclusively for the Quezon City University community, we need to verify your student status. Please upload a clear photo or scan of your QCU Student ID or current study load.</p>
                    
                    <div>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-700 border-dashed rounded-xl overflow-hidden relative group">
                            <!-- Overlay background changing on hover -->
                            <div class="absolute inset-0 bg-violet-50/50 dark:bg-violet-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="space-y-2 text-center relative z-10 w-full" x-data="{ docName: '' }">
                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-violet-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true" x-show="!docName">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center flex-col items-center">
                                    <label for="document" class="relative cursor-pointer bg-white dark:bg-[#13111C] rounded-md font-medium text-violet-600 dark:text-violet-400 hover:text-violet-500 focus-within:outline-none px-2 py-1 z-20">
                                        <span x-text="docName ? 'Change File' : 'Upload a file'"></span>
                                        <input id="document" name="document" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" @change="docName = $event.target.files[0].name">
                                    </label>
                                    <p class="pl-1 mt-1" x-show="!docName">or drag and drop</p>
                                    <p class="text-sm font-semibold text-violet-600 dark:text-violet-400 mt-2 break-all px-2" x-show="docName" x-text="docName"></p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold tracking-wider uppercase pt-2" x-show="!docName">
                                    PDF, PNG, JPG up to 5MB
                                </p>
                            </div>
                        </div>
                        
                        @if($enterprise->document_path)
                            <div class="mt-4 flex items-center p-3 bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300 rounded-xl border border-violet-100 dark:border-violet-800/50">
                                <svg class="w-5 h-5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <div class="flex-1 text-sm font-semibold truncate">
                                    Document Uploaded ({{ basename($enterprise->document_path) }})
                                </div>
                                <a href="{{ Storage::url($enterprise->document_path) }}" target="_blank" class="text-xs font-bold uppercase tracking-wider hover:text-violet-900 dark:hover:text-white transition-colors bg-white/50 dark:bg-black/20 px-3 py-1.5 rounded-lg ml-2 border border-violet-200 dark:border-violet-700/50">View</a>
                            </div>
                        @endif

                        @error('document')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-8 py-3.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-violet-500/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-[#13111C]">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Save Business Profile
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-seller-layout>
