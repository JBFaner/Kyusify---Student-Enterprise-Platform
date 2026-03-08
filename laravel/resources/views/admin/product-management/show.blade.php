<x-admin-layout>
    <x-slot name="header">
        Product Management - Review
    </x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.products.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 text-gray-500 hover:text-violet-600 dark:text-gray-400 dark:hover:text-violet-400 hover:border-violet-200 dark:hover:border-violet-800 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Review Product</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Viewing details for {{ $product->name }}</p>
            </div>
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="px-4 py-2 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-violet-600 dark:hover:text-violet-400 transition-all font-medium text-sm flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Product
            </a>
            
            @if($product->status !== 'active')
            <form action="{{ route('admin.products.update', $product) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="name" value="{{ $product->name }}">
                <input type="hidden" name="price" value="{{ $product->price }}">
                <input type="hidden" name="stock" value="{{ $product->stock }}">
                <input type="hidden" name="status" value="active">
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
            <!-- Product Card -->
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl p-6 bg-white dark:bg-[#0B0A0F] shadow-sm flex flex-col items-center">
                <div class="w-full aspect-square rounded-2xl border-4 border-gray-50 dark:border-[#13111C] shadow-inner overflow-hidden relative mb-6 bg-gray-50 dark:bg-[#13111C] flex items-center justify-center">
                    @if($product->image_path)
                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    @endif
                </div>
                
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2 text-center">{{ $product->name }}</h2>
                <div class="flex space-x-2 mb-4">
                    @if ($product->status === 'active')
                        <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Active</span>
                    @elseif ($product->status === 'pending')
                        <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">Pending Review</span>
                    @else
                        <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Suspended</span>
                    @endif
                </div>

                <div class="w-full border-t border-gray-100 dark:border-gray-800/60 pt-4 grid grid-cols-2 gap-4 text-center">
                    <div class="p-3 bg-gray-50 dark:bg-[#13111C] rounded-xl border border-gray-100 dark:border-gray-800/50">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 font-medium">Price</p>
                        <p class="text-lg font-bold text-violet-600 dark:text-violet-400">₱{{ number_format($product->price, 2) }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-[#13111C] rounded-xl border border-gray-100 dark:border-gray-800/50">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 font-medium">Stock</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $product->stock }}</p>
                    </div>
                </div>
            </div>

            <!-- Enterprise Info -->
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl bg-white dark:bg-[#0B0A0F] shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#13111C]/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-4 h-4 mr-2 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Sold By
                    </h3>
                    <a href="{{ route('admin.enterprises.show', $product->enterprise_id) }}" class="text-xs text-violet-600 hover:text-violet-700 dark:text-violet-400 dark:hover:text-violet-300 transition-colors">View Enterprise</a>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center shrink-0 border border-violet-200 dark:border-violet-800">
                            <span class="text-violet-700 dark:text-violet-300 font-bold text-sm">{{ substr($product->enterprise->name ?? '?', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $product->enterprise->name ?? 'Unknown Enterprise' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Owner: {{ $product->enterprise->user->name ?? 'Unknown Student' }}</p>
                        </div>
                    </div>
                    @if($product->enterprise->is_student_verified)
                        <div class="mt-4 flex items-center text-xs font-semibold text-green-500 bg-green-50 dark:bg-green-900/20 p-2 rounded-lg">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Verified QCU Enterprise
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Description & Review Panel -->
        <div class="lg:col-span-2 space-y-6">
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl bg-white dark:bg-[#0B0A0F] shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#13111C]/50">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Product Description
                    </h3>
                </div>
                <div class="p-8">
                    <div class="p-6 rounded-xl bg-gray-50 dark:bg-[#13111C] border border-gray-100 dark:border-gray-800/60 text-sm text-gray-800 dark:text-gray-200 leading-relaxed min-h-[150px] whitespace-pre-wrap">{{ $product->description ?? 'No product description provided.' }}</div>
                </div>
            </div>
            
            <!-- Quick Review Actions (Full Form embedded via PUT) -->
            <div class="border border-gray-100 dark:border-gray-800 rounded-2xl bg-white dark:bg-[#0B0A0F] shadow-sm overflow-hidden mt-6">
                <div class="px-8 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#13111C]/50">
                     <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center">Moderation Center</h3>
                </div>
                <div class="p-8">
                     @if($product->status === 'pending')
                         <form action="{{ route('admin.products.update', $product) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $product->name }}">
                            <input type="hidden" name="price" value="{{ $product->price }}">
                            <input type="hidden" name="stock" value="{{ $product->stock }}">
                            
                            <div class="flex flex-wrap gap-4 pt-2">
                                 <button type="submit" name="status" value="active" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl shadow-lg shadow-green-500/30 transition-all font-semibold flex items-center justify-center flex-1">
                                     <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                     Approve Listing
                                 </button>
                                 <button type="submit" name="status" value="suspended" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl shadow-lg shadow-red-500/30 transition-all font-semibold flex items-center justify-center flex-1" onclick="return confirm('Suspend this product? It will be hidden from the storefront.');">
                                     <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                     Suspend Listing
                                 </button>
                            </div>
                         </form>
                     @else
                         <div class="text-center py-4">
                             @if($product->status === 'active')
                                 <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 text-green-500 mb-4">
                                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                 </div>
                                 <h4 class="text-lg font-bold text-gray-900 dark:text-white">Listing Approved</h4>
                                 <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">This product has been approved and is currently active on the storefront.</p>
                             @elseif($product->status === 'suspended')
                                 <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-500 mb-4">
                                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                 </div>
                                 <h4 class="text-lg font-bold text-gray-900 dark:text-white">Listing Suspended</h4>
                                 <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">This product has been suspended and hidden from the storefront.</p>
                             @elseif($product->status === 'inactive')
                                 <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500 mb-4">
                                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                 </div>
                                 <h4 class="text-lg font-bold text-gray-900 dark:text-white">Listing Inactive</h4>
                                 <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">This product has been hidden by the seller.</p>
                             @endif
                         </div>
                     @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
