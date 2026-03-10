<x-seller-layout>
    <x-slot name="header">
        Edit Product: {{ $product->name }}
    </x-slot>

    <!-- Page Header & Back Button -->
    <div class="mb-8 border-b border-gray-100 dark:border-gray-800 pb-5">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('seller.products.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-violet-600 dark:text-gray-400 dark:hover:text-violet-400 transition-colors mb-4">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Inventory
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mb-2 line-clamp-1 flex items-center gap-3">
                    {{ $product->name }}
                    @if($product->status === 'approved')
                        <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold uppercase tracking-wider flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span> Live
                        </span>
                    @elseif($product->status === 'pending')
                        <span class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-xs font-bold uppercase tracking-wider flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span> Pending
                        </span>
                    @elseif($product->status === 'hidden')
                        <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400 text-xs font-bold uppercase tracking-wider flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5"></span> Hidden
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-xs font-bold uppercase tracking-wider flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> Rejected
                        </span>
                    @endif
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm max-w-2xl">Update pricing, inventory, or visibility settings for this product.</p>
            </div>
            
            <div class="hidden sm:block">
                @if($product->image_path)
                    <div class="h-16 w-16 rounded-xl overflow-hidden bg-gray-100 dark:bg-[#13111C] border border-gray-200 dark:border-gray-800/60 shadow-sm">
                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data" class="space-y-8 pb-12">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- Left Column: Form Fields -->
            <div class="xl:col-span-2 space-y-6">
                
                <!-- Basic Info Card -->
                <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Basic Information</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g. QCU IT Dept Lanyard" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('name') border-red-500 @enderror" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category <span class="text-red-500">*</span></label>
                            <select id="category_id" name="category_id" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('category_id') border-red-500 @enderror" required>
                                <option value="" disabled selected>Select a category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                            <textarea id="description" name="description" rows="5" class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow resize-none @error('description') border-red-500 @enderror" placeholder="Describe the item in detail..." required>{{ old('description', $product->description) }}</textarea>
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
                                <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price) }}" placeholder="0.00" class="w-full pl-8 pr-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow font-mono @error('price') border-red-500 @enderror" required>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Available Stock <span class="text-red-500">*</span></label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow font-mono @error('stock') border-red-500 @enderror" required>
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
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Product Image</h3>
                    
                    @if($product->image_path)
                        <div class="mb-4 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 relative group aspect-video sm:aspect-auto">
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-sm font-semibold tracking-wide flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Replace Image
                                </span>
                            </div>
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" accept="image/*">
                        </div>
                    @else
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
                    @endif
                    @error('image')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Visibility Status -->
                <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Publishing Status</h3>
                    
                    <div class="space-y-4">
                        <label class="relative flex cursor-pointer rounded-lg border bg-white dark:bg-[#0B0A0F] p-4 shadow-sm focus:outline-none 
                            has-[:checked]:border-violet-500 has-[:checked]:ring-1 has-[:checked]:ring-violet-500
                            border-gray-200 dark:border-gray-800">
                            <input type="radio" name="status" value="active" class="sr-only" {{ in_array(old('status', $product->status), ['approved', 'pending']) ? 'checked' : '' }}>
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                        <span class="h-2 w-2 rounded-full {{ in_array($product->status, ['approved', 'pending']) ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></span> Visible
                                    </span>
                                    <span class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400">Available to customers (upon approval)</span>
                                </span>
                            </span>
                            <svg class="h-5 w-5 text-violet-600 dark:text-violet-400 hidden has-[:checked]:block" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </label>

                        <label class="relative flex cursor-pointer rounded-lg border bg-white dark:bg-[#0B0A0F] p-4 shadow-sm focus:outline-none 
                            has-[:checked]:border-violet-500 has-[:checked]:ring-1 has-[:checked]:ring-violet-500
                            border-gray-200 dark:border-gray-800">
                            <input type="radio" name="status" value="inactive" class="sr-only" {{ old('status', $product->status) === 'hidden' ? 'checked' : '' }}>
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                        <span class="h-2 w-2 rounded-full bg-gray-400 mr-2"></span> Hidden 
                                        <span class="ml-2 px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 uppercase">Draft</span>
                                    </span>
                                    <span class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400">Hidden from the storefront</span>
                                </span>
                            </span>
                            <svg class="h-5 w-5 text-violet-600 dark:text-violet-400 hidden has-[:checked]:block" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </label>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Action -->
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center px-8 py-3.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-violet-500/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-[#13111C]">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Update Product
                    </button>
                    
                    <a href="{{ route('seller.products.index') }}" class="w-full mt-3 flex justify-center items-center px-8 py-3.5 bg-white dark:bg-transparent border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-semibold transition-all duration-300">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-seller-layout>
