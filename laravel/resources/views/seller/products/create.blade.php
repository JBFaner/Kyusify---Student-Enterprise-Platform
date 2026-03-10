<x-seller-layout>
    <x-slot name="header">
        Add Product
    </x-slot>

    <!-- Page Header & Back Button -->
    <div class="mb-8">
        <a href="{{ route('seller.products.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-violet-600 dark:text-gray-400 dark:hover:text-violet-400 transition-colors mb-4">
            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Inventory
        </a>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mb-2">Create New Product</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm max-w-2xl">Add a new item to your store's catalog. Fill in the details below to make it visible to QCU students.</p>
    </div>

    <!-- Main Form -->
    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data" class="space-y-8 pb-12">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- Left Column: Form Fields -->
            <div class="xl:col-span-2 space-y-6">
                
                <!-- Basic Info Card -->
                <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Basic Information</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. QCU IT Dept Lanyard" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('name') border-red-500 @enderror" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category <span class="text-red-500">*</span></label>
                            <select id="category_id" name="category_id" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('category_id') border-red-500 @enderror" required>
                                <option value="" disabled selected>Select a category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description <span class="text-red-500">*</span></label>
                            <textarea id="description" name="description" rows="5" class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow resize-none @error('description') border-red-500 @enderror" placeholder="Describe the item in detail..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing & Inventory Card -->
                <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Pricing & Inventory</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price (₱) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">₱</span>
                                </div>
                                <input type="number" step="0.01" id="price" name="price" value="{{ old('price') }}" placeholder="0.00" class="w-full pl-8 pr-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow font-mono @error('price') border-red-500 @enderror" required>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Available Stock <span class="text-red-500">*</span></label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow font-mono @error('stock') border-red-500 @enderror" required>
                            @error('stock')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Media, Status & Actions -->
            <div class="xl:col-span-1 space-y-6">
                
                <!-- Product Image -->
                <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Product Image</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">High-quality images increase your sales dramatically.</p>

                    <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-700 border-dashed rounded-xl overflow-hidden relative group">
                        <div class="absolute inset-0 bg-violet-50/50 dark:bg-violet-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="space-y-2 text-center relative z-10 w-full" x-data="{ fileName: '' }">
                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-violet-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true" x-show="!fileName">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center flex-col items-center">
                                <label for="image" class="relative cursor-pointer bg-white dark:bg-[#13111C] rounded-md font-medium text-violet-600 dark:text-violet-400 hover:text-violet-500 focus-within:outline-none px-2 py-1 z-20">
                                    <span x-text="fileName ? 'Change Image' : 'Upload a file'"></span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*" @change="fileName = $event.target.files[0].name">
                                </label>
                                <p class="pl-1 mt-1" x-show="!fileName">or drag and drop</p>
                                <p class="text-sm font-semibold text-violet-600 dark:text-violet-400 mt-2 break-all px-2" x-show="fileName" x-text="fileName"></p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold tracking-wider uppercase pt-2" x-show="!fileName">
                                PNG, JPG up to 2MB
                            </p>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Moderation Notice -->
                <div class="bg-violet-50 dark:bg-violet-900/20 p-6 sm:p-8 rounded-2xl border border-violet-100 dark:border-violet-800/50">
                    <div class="flex">
                        <svg class="h-6 w-6 text-violet-600 dark:text-violet-400 mt-0.5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-bold text-violet-900 dark:text-violet-100">Pending Review Process</h3>
                            <p class="mt-1 text-sm text-violet-700 dark:text-violet-300 leading-relaxed">By publishing this product, it will be placed in a <strong>Pending</strong> state. Kyusify administrators will review your listing before it becomes visible to students on the storefront. You can track its status in your Product Catalog.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Action -->
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center px-8 py-3.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-violet-500/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-[#13111C]">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Publish Product
                    </button>
                    
                    <a href="{{ route('seller.products.index') }}" class="w-full mt-3 flex justify-center items-center px-8 py-3.5 bg-white dark:bg-transparent border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-semibold transition-all duration-300">
                        Cancel
                    </a>
                </div>

            </div>
        </div>
    </form>
</x-seller-layout>
