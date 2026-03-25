<x-seller-layout>
    <x-slot name="header">Quick Replies</x-slot>

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Set up FAQ pairs. Customers see the <strong>question</strong> as a button in the chatbox.
                    Clicking it instantly sends both their question and your pre-written answer.
                </p>
            </div>
            <a href="{{ route('seller.inquiries.index') }}" class="text-sm text-violet-600 hover:underline font-semibold flex items-center gap-1 shrink-0 ml-4">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back to Inbox
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl px-4 py-3 text-sm text-green-700 dark:text-green-400 flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        {{-- How it works --}}
        <div class="bg-violet-50 dark:bg-violet-900/15 border border-violet-200 dark:border-violet-800/50 rounded-2xl p-5">
            <h4 class="text-sm font-bold text-violet-800 dark:text-violet-300 flex items-center gap-2 mb-3">
                <i class="fa-solid fa-circle-info"></i> How it works
            </h4>
            <div class="flex items-start gap-6 text-sm text-violet-700 dark:text-violet-400">
                <div class="flex-1">
                    <div class="font-bold mb-1">① Customer sees</div>
                    <div class="bg-white dark:bg-[#13111C] border border-violet-200 dark:border-violet-800 rounded-full px-3 py-1.5 text-xs inline-block">Is this product available?</div>
                </div>
                <div class="text-violet-400 dark:text-violet-600 pt-4">→</div>
                <div class="flex-1">
                    <div class="font-bold mb-1">② Clicks it → auto-reply</div>
                    <div class="bg-violet-600 text-white rounded-2xl px-3 py-1.5 text-xs inline-block">Yes, it is! Order now.</div>
                </div>
            </div>
        </div>

        {{-- Add New --}}
        <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800 p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-plus-circle text-violet-500"></i> Add FAQ Quick Reply
            </h3>
            <form method="POST" action="{{ route('seller.quick-replies.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                        <i class="fa-regular fa-circle-question text-violet-500 mr-1"></i> Question (customer button label)
                    </label>
                    <input type="text" name="question" placeholder="e.g. Is this product available?"
                        value="{{ old('question') }}" required maxlength="255"
                        class="w-full border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    @error('question')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                        <i class="fa-solid fa-reply text-green-500 mr-1"></i> Auto-Reply (seller's answer, sent instantly)
                    </label>
                    <textarea name="answer" placeholder="e.g. Yes, this product is available! You can order it now through the order page." required maxlength="1000" rows="2"
                        class="w-full border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-violet-500 resize-none">{{ old('answer') }}</textarea>
                    @error('answer')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Add FAQ Reply
                    </button>
                </div>
            </form>
        </div>

        {{-- Existing Quick Replies --}}
        <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-yellow-500"></i> FAQ Pairs
                    <span class="text-xs font-normal text-gray-400">({{ $quickReplies->count() }})</span>
                </h3>
                <p class="text-xs text-gray-400">Drag to reorder</p>
            </div>

            @if($quickReplies->isNotEmpty())
            <ul id="quick-replies-list" class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($quickReplies as $qr)
                <li class="group hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors" data-id="{{ $qr->id }}"
                    x-data="{ editing: false }">

                    {{-- View mode --}}
                    <div x-show="!editing" class="flex items-start gap-3 px-5 py-4">
                        <span class="cursor-grab text-gray-300 dark:text-gray-600 group-hover:text-gray-400 select-none text-lg pt-1">⠿</span>
                        <div class="flex-1 min-w-0">
                            {{-- Question --}}
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="shrink-0 text-[10px] font-bold uppercase text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30 px-2 py-0.5 rounded-full">Q</span>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $qr->question }}</p>
                            </div>
                            {{-- Answer --}}
                            <div class="flex items-start gap-2">
                                <span class="shrink-0 text-[10px] font-bold uppercase text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full mt-0.5">A</span>
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ $qr->answer }}</p>
                            </div>
                        </div>
                        {{-- Actions --}}
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0 pt-1">
                            <button @click="editing = true" class="p-1.5 text-gray-400 hover:text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-900/20 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form method="POST" action="{{ route('seller.quick-replies.destroy', $qr->id) }}" onsubmit="return confirm('Delete this FAQ reply?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Edit mode --}}
                    <div x-show="editing" style="display:none;" class="px-5 py-4 bg-violet-50/50 dark:bg-violet-900/10">
                        <form method="POST" action="{{ route('seller.quick-replies.update', $qr->id) }}" class="space-y-3">
                            @csrf @method('PUT')
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                                    <i class="fa-regular fa-circle-question text-violet-500 mr-1"></i> Question
                                </label>
                                <input type="text" name="question" value="{{ $qr->question }}" required maxlength="255"
                                    class="w-full border border-violet-300 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                                    <i class="fa-solid fa-reply text-green-500 mr-1"></i> Auto-Reply Answer
                                </label>
                                <textarea name="answer" required maxlength="1000" rows="2"
                                    class="w-full border border-violet-300 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 resize-none">{{ $qr->answer }}</textarea>
                            </div>
                            <div class="flex gap-2 justify-end">
                                <button type="button" @click="editing = false" class="text-xs text-gray-500 hover:text-gray-700 px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">Cancel</button>
                                <button type="submit" class="text-xs bg-violet-600 text-white px-4 py-1.5 rounded-lg font-bold hover:bg-violet-700 transition-colors">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <div class="p-12 text-center text-gray-400">
                <i class="fa-solid fa-comments text-3xl mb-3 block opacity-30"></i>
                <p class="text-sm font-medium">No FAQ replies yet.</p>
                <p class="text-xs mt-1">Add question/answer pairs above to help customers get instant answers.</p>
            </div>
            @endif
        </div>

        {{-- Preview --}}
        @if($quickReplies->isNotEmpty())
        <div class="bg-gray-50 dark:bg-gray-900/20 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                Preview — Customer chatbox FAQ buttons
            </h4>
            <div class="flex flex-wrap gap-2">
                @foreach($quickReplies as $qr)
                <span class="text-xs px-3 py-1.5 bg-white dark:bg-[#13111C] text-violet-700 dark:text-violet-300 rounded-full border border-violet-200 dark:border-violet-800 font-medium shadow-sm cursor-default">
                    <i class="fa-regular fa-circle-question mr-1 text-[10px]"></i>{{ $qr->question }}
                </span>
                @endforeach
            </div>
            <p class="text-xs text-gray-400 mt-3">Customers click these buttons → their question + your answer appear instantly in the chat.</p>
        </div>
        @endif

    </div>

    {{-- SortableJS for drag reorder --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        const list = document.getElementById('quick-replies-list');
        if (list) {
            Sortable.create(list, {
                handle: '.cursor-grab',
                animation: 150,
                onEnd: async function() {
                    const order = [...list.querySelectorAll('li')].map(li => li.dataset.id);
                    await fetch('{{ route('seller.quick-replies.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ order }),
                    });
                }
            });
        }
    </script>

</x-seller-layout>
