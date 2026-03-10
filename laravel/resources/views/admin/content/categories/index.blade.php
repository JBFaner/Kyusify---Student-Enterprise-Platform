<x-admin-layout>
    <x-slot name="header">
        Content Management
    </x-slot>

    @include('admin.content.tabs')

    <div class="mb-6 mt-4 flex justify-between items-center">
        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Product Categories</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage the categories available for sellers to use.</p>
        </div>
        <button onclick="document.getElementById('add-modal').classList.remove('hidden')" class="bg-violet-600 hover:bg-violet-700 text-white font-medium py-2.5 px-5 rounded-xl text-sm transition-colors shadow-lg shadow-violet-500/30">
            + Add Category
        </button>
    </div>

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 mb-8 rounded-r-xl">
            <ul class="list-disc pl-5 text-sm text-red-700 dark:text-red-500">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-[#13111C] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60 overflow-hidden" x-data="categoryList()">
        
        <form @submit.prevent="saveOrder" id="reorder-form">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#181622]/50">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Display Order</span>
                <button type="submit" x-show="hasChanges" style="display: none;" class="text-sm bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-3 py-1.5 rounded-lg transition-colors">
                    Save Order
                </button>
            </div>
            
            <ul class="divide-y divide-gray-100 dark:divide-gray-800" id="category-list">
                @forelse($categories as $category)
                    <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-[#181622] transition-colors" data-id="{{ $category->id }}">
                        <div class="flex items-center gap-4">
                            <!-- Drag Handle / Up Down Arrows -->
                            <div class="flex flex-col gap-1 text-gray-400">
                                <button type="button" @click="moveUp($event)" class="hover:text-violet-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg></button>
                                <button type="button" @click="moveDown($event)" class="hover:text-violet-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg></button>
                            </div>
                            
                            <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 dark:text-violet-400">
                                @if($category->icon)
                                    <i class="fa-fw {{ $category->icon }} text-lg"></i>
                                @else
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z" /></svg>
                                @endif
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $category->name }}</h4>
                                <p class="text-xs text-gray-500">{{ Str::limit($category->description, 50) ?: 'No description' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            @if($category->status)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800/50">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700">Inactive</span>
                            @endif

                            <div class="flex items-center gap-2">
                                <button type="button" onclick="editCategory({{ $category->toJson() }})" class="p-2 text-gray-400 hover:text-violet-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                
                                <button type="button" onclick="deleteCategory({{ $category->id }})" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-12 text-center text-gray-500">
                        No categories found. Create one to get started.
                    </li>
                @endforelse
            </ul>
        </form>
    </div>

    @push('modals')
    <!-- Modals -->
    <!-- Add Modal -->
    <div id="add-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 lg:p-8 bg-gray-900/80 backdrop-blur-sm" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0;" onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-white dark:bg-[#13111C] rounded-2xl shadow-slate-900/50 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] border border-gray-100 dark:border-gray-800 w-full max-w-lg m-auto overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center shrink-0">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Category</h3>
                <button type="button" onclick="document.getElementById('add-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="overflow-y-auto p-6 flex-1">
                <form action="{{ route('admin.content.categories.store') }}" method="POST" class="space-y-4 text-gray-900 dark:text-white">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full px-4 py-2 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-violet-500 focus:border-violet-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full px-4 py-2 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-violet-500 focus:border-violet-500 text-sm placeholder-gray-400" placeholder="Optional brief description"></textarea>
                    </div>
                    <div class="flex items-center mt-2">
                        <input type="checkbox" name="status" id="add-status" value="1" checked class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 dark:bg-[#0B0A0F] dark:border-gray-700">
                        <label for="add-status" class="ml-2 block text-sm font-medium">Active</label>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white font-medium py-2 px-6 rounded-lg text-sm transition-colors shadow-lg shadow-violet-500/30">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0;" onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-white dark:bg-[#13111C] rounded-2xl shadow-slate-900/50 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] border border-gray-100 dark:border-gray-800 w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center shrink-0">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Category</h3>
                <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form id="edit-form" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit-name" required class="w-full px-4 py-2 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-violet-500 focus:border-violet-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" id="edit-description" rows="2" class="w-full px-4 py-2 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-violet-500 focus:border-violet-500 text-sm"></textarea>
                </div>
                <div class="flex items-center mt-2">
                    <input type="checkbox" name="status" id="edit-status" value="1" class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 dark:bg-[#0B0A0F] dark:border-gray-700">
                    <label for="edit-status" class="ml-2 block text-sm font-medium">Active</label>
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white font-medium py-2 px-6 rounded-lg text-sm transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    @endpush

    <!-- Delete Form (Hidden) -->
    <form id="delete-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function editCategory(category) {
            document.getElementById('edit-name').value = category.name;
            document.getElementById('edit-description').value = category.description || '';
            document.getElementById('edit-status').checked = category.status == 1;
            
            document.getElementById('edit-form').action = `/admin/content/categories/${category.id}`;
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function deleteCategory(id) {
            if(confirm('Are you sure you want to delete this category?')) {
                const form = document.getElementById('delete-form');
                form.action = `/admin/content/categories/${id}`;
                form.submit();
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('categoryList', () => ({
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
                    const ids = Array.from(document.querySelectorAll('#category-list li')).map(li => li.dataset.id);
                    
                    fetch('{{ route('admin.content.categories.reorder') }}', {
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
