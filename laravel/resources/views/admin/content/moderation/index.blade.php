<x-admin-layout>
    <x-slot name="header">
        Content Management
    </x-slot>

    @include('admin.content.tabs')

    <div class="mb-6 mt-4 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Product Moderation</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Review, approve, hide, or remove products submitted by sellers.</p>
        </div>
        
        <form action="{{ route('admin.content.moderation.index') }}" method="GET" class="flex items-center gap-3">
            <select name="status" onchange="this.form.submit()" class="px-4 py-2 border border-gray-200 dark:border-gray-800 rounded-xl bg-white dark:bg-[#0B0A0F] text-sm focus:ring-violet-500 focus:border-violet-500 shadow-sm cursor-pointer">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="hidden" {{ $status === 'hidden' ? 'selected' : '' }}>Hidden</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <div class="relative w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="query" value="{{ $query }}" placeholder="Search products..." class="w-full pl-10 px-4 py-2 border border-gray-200 dark:border-gray-800 rounded-xl bg-white dark:bg-[#0B0A0F] text-sm focus:ring-violet-500 focus:border-violet-500 shadow-sm">
            </div>
            @if($query || $status !== 'all')
                <a href="{{ route('admin.content.moderation.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 mb-8 rounded-r-xl">
            <p class="text-sm text-green-700 dark:text-green-500">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-[#13111C] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800/80 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-4 rounded-tl-xl font-bold">Product</th>
                        <th scope="col" class="px-6 py-4 font-bold">Category</th>
                        <th scope="col" class="px-6 py-4 font-bold">Price / Stock</th>
                        <th scope="col" class="px-6 py-4 font-bold">Enterprise</th>
                        <th scope="col" class="px-6 py-4 font-bold">Status</th>
                        <th scope="col" class="px-6 py-4 rounded-tr-xl font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 shrink-0">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-100 dark:border-gray-800 shrink-0 bg-gray-100 dark:bg-gray-800">
                                        @if($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-500">Added {{ $product->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $product->category->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-gray-900 dark:text-white">₱{{ number_format($product->price, 2) }}</span>
                                <span class="block text-xs text-gray-500">Stock: {{ $product->stock }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $product->enterprise->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800/50">Approved</span>
                                @elseif($product->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500 border border-yellow-200 dark:border-yellow-800/50">Pending</span>
                                @elseif($product->status === 'hidden')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700">Hidden</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800/50">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2" x-data="{ open: false }">
                                    @if($product->status !== 'approved')
                                    <form action="{{ route('admin.content.moderation.update', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="text-xs px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/40 rounded-lg transition-colors font-medium">Approve</button>
                                    </form>
                                    @endif

                                    <div class="relative">
                                        <button @click="open = !open" @click.away="open = false" class="text-xs px-3 py-1.5 bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 rounded-lg transition-colors font-medium flex items-center gap-1">
                                            More
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </button>
                                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-36 bg-white dark:bg-[#13111C] rounded-xl shadow-lg border border-gray-100 dark:border-gray-800 z-50 py-1 overflow-hidden" style="display: none;">
                                            @if($product->status !== 'hidden')
                                            <form action="{{ route('admin.content.moderation.update', $product) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="hidden">
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800">Hide Product</button>
                                            </form>
                                            @endif
                                            @if($product->status !== 'rejected')
                                            <form action="{{ route('admin.content.moderation.update', $product) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-500 dark:hover:bg-red-900/20" onclick="return confirm('Refuse this product?')">Reject Product</button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="w-16 h-16 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-400 mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                </div>
                                <p class="text-sm">No products found for the selected filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
