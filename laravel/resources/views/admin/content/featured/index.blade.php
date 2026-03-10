<x-admin-layout>
    <x-slot name="header">
        Content Management
    </x-slot>

    @include('admin.content.tabs')

    <div class="mb-6 mt-4">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Featured Products</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Select and manage products to highlight on the homepage and discover pages.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 mb-8 rounded-r-xl">
            <p class="text-sm text-green-700 dark:text-green-500">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Search & Add -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-[#13111C] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 p-6">
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Search Products</h4>
                
                <form action="{{ route('admin.content.featured.index') }}" method="GET" class="mb-6">
                    <div class="flex gap-2">
                        <input type="text" name="query" value="{{ $searchQuery }}" placeholder="Search by product or enterprise..." class="w-full px-4 py-2 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-violet-500 focus:border-violet-500 text-sm">
                        <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">Search</button>
                    </div>
                </form>

                @if($searchQuery)
                    <div class="space-y-3">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Search Results</p>
                        @forelse($searchResults as $product)
                            <div class="flex items-center justify-between p-3 border border-gray-100 dark:border-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-[#181622] transition-colors">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex-shrink-0 overflow-hidden">
                                        @if($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <h5 class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $product->name }}</h5>
                                        <p class="text-xs text-gray-500 truncate">{{ $product->enterprise->name }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('admin.content.featured.store', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-900/30 rounded-lg transition-colors" title="Feature Product">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">No unfeatured products found.</p>
                        @endforelse
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 rounded-full bg-violet-50 dark:bg-violet-900/20 flex items-center justify-center text-violet-500 mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <p class="text-sm text-gray-500">Search for products to add to the featured list.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Featured List -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#13111C] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 overflow-hidden" x-data="featuredList()">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#181622]/50">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Currently Featured ({{ $featuredProducts->count() }})</span>
                    <button type="button" @click="saveOrder" x-show="hasChanges" style="display: none;" class="text-sm bg-violet-600 hover:bg-violet-700 text-white px-3 py-1.5 rounded-lg transition-colors">
                        Save Order
                    </button>
                </div>
                
                <ul class="divide-y divide-gray-100 dark:divide-gray-800" id="featured-list">
                    @forelse($featuredProducts as $product)
                        <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-[#181622] transition-colors" data-id="{{ $product->id }}">
                            <div class="flex items-center gap-4 min-w-0">
                                <!-- Up/Down Arrows -->
                                <div class="flex flex-col gap-1 text-gray-400 shrink-0">
                                    <button type="button" @click="moveUp($event)" class="hover:text-violet-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg></button>
                                    <button type="button" @click="moveDown($event)" class="hover:text-violet-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg></button>
                                </div>
                                
                                <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 flex-shrink-0 overflow-hidden">
                                    @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>
                                    @endif
                                </div>
                                
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate" title="{{ $product->name }}">{{ $product->name }}</h4>
                                    <p class="text-xs text-violet-500 font-medium truncate">{{ $product->enterprise->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">₱{{ number_format($product->price, 2) }} • {{ $product->status }}</p>
                                </div>
                            </div>

                            <div class="flex items-center ml-4 shrink-0">
                                <form action="{{ route('admin.content.featured.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Remove from featured list?')" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Remove">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="px-6 py-12 text-center text-gray-500">
                            <div class="w-12 h-12 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-400 mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            </div>
                            <p>No featured products yet.<br>Search and add products to feature them.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('featuredList', () => ({
                hasChanges: false,
                moveUp(event) {
                    const li = event.target.closest('li');
                    const prev = li.previousElementSibling;
                    if (prev) {
                        li.parentNode.insertBefore(li, prev);
                        this.hasChanges = true;
                    }
                },
                moveDown(event) {
                    const li = event.target.closest('li');
                    const next = li.nextElementSibling;
                    if (next) {
                        li.parentNode.insertBefore(next, li);
                        this.hasChanges = true;
                    }
                },
                saveOrder() {
                    const ids = Array.from(document.querySelectorAll('#featured-list li')).map(li => li.dataset.id);
                    if(ids.length === 0) return;
                    
                    fetch('{{ route('admin.content.featured.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ ordered_ids: ids })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            this.hasChanges = false;
                            alert('Order saved successfully!');
                        }
                    });
                }
            }));
        });
    </script>
</x-admin-layout>
