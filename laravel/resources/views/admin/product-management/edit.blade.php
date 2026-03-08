<x-admin-layout>
    <x-slot name="header">
        Product Management - Edit
    </x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.products.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 text-gray-500 hover:text-violet-600 dark:text-gray-400 dark:hover:text-violet-400 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Product Details</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Owner: {{ $product->enterprise->name ?? 'Unknown' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-[#13111C] overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] rounded-2xl border border-gray-100 dark:border-gray-800/60 transition-colors duration-300">
        <div class="p-8">
            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Details -->
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                            <textarea name="description" id="description" rows="4" class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow resize-none @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price (₱) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow @error('price') border-red-500 @enderror">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock Available <span class="text-red-500">*</span></label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow @error('stock') border-red-500 @enderror">
                                @error('stock')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Setup and Admin Overrides -->
                    <div class="space-y-6">
                        <div class="p-6 border border-gray-100 dark:border-gray-800 rounded-xl bg-gray-50/50 dark:bg-gray-900/30">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Administration</h4>
                            
                            <div class="space-y-5">
                                <div class="p-4 bg-white dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                                    <h5 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Enterprise Information</h5>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 flex items-center">
                                        <svg class="h-4 w-4 mr-2 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $product->enterprise->name ?? 'Unknown Enterprise' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1 ml-6">Owner: {{ $product->enterprise->user->name ?? 'Unknown' }}</p>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Listing Status <span class="text-red-500">*</span></label>
                                    <select name="status" id="status" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow">
                                        <option value="pending" @selected(old('status', $product->status) == 'pending')>Pending Review</option>
                                        <option value="active" @selected(old('status', $product->status) == 'active')>Approved & Active</option>
                                        <option value="suspended" @selected(old('status', $product->status) == 'suspended')>Suspended (Policy Violation)</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-2">Suspended products are hidden from the storefront entirely.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-end space-x-3">
                    <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all text-sm font-medium shadow-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-semibold transition-all duration-300 shadow-lg shadow-violet-500/30">
                        Save Listings Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
