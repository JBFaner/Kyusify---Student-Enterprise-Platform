<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-xl text-gray-900 dark:text-white">Inquiries Monitor</h2>
        </div>
    </x-slot>

    @if(session('success'))
    <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl px-4 py-3 text-sm text-green-700 dark:text-green-400 flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- ── Stats Row ──────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        @php
        $stats = [
            ['icon'=>'fa-comments',       'color'=>'text-violet-500',  'bg'=>'bg-violet-50 dark:bg-violet-900/20',  'value'=>$totalConversations,  'label'=>'Total'],
            ['icon'=>'fa-clock',          'color'=>'text-yellow-500',  'bg'=>'bg-yellow-50 dark:bg-yellow-900/20',  'value'=>$pendingConversations,'label'=>'Pending'],
            ['icon'=>'fa-reply',          'color'=>'text-blue-500',    'bg'=>'bg-blue-50 dark:bg-blue-900/20',      'value'=>$repliedConversations, 'label'=>'Replied'],
            ['icon'=>'fa-lock',           'color'=>'text-gray-500',    'bg'=>'bg-gray-50 dark:bg-gray-800',         'value'=>$closedConversations,  'label'=>'Closed'],
            ['icon'=>'fa-calendar-day',   'color'=>'text-emerald-500', 'bg'=>'bg-emerald-50 dark:bg-emerald-900/20','value'=>$todayConversations,   'label'=>'Today'],
            ['icon'=>'fa-envelope',       'color'=>'text-rose-500',    'bg'=>'bg-rose-50 dark:bg-rose-900/20',     'value'=>$totalMessages,         'label'=>'Messages'],
        ];
        @endphp
        @foreach($stats as $s)
        <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 {{ $s['bg'] }} {{ $s['color'] }} rounded-xl flex items-center justify-center shrink-0">
                <i class="fa-solid {{ $s['icon'] }}"></i>
            </div>
            <div>
                <p class="text-xl font-black text-gray-900 dark:text-white leading-none">{{ $s['value'] }}</p>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Main Split Layout ──────────────────────── --}}
    <div class="flex gap-5" style="height:calc(100vh - 240px); min-height:520px;">

        {{-- LEFT: Conversations Panel --}}
        <div class="w-[380px] flex-shrink-0 flex flex-col bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">

            {{-- Search + Filter --}}
            <div class="p-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30 space-y-3">
                <form method="GET" action="{{ route('admin.inquiries.index') }}" id="search-form">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search customer, seller, ID..."
                            class="w-full pl-8 pr-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white">
                    </div>
                </form>
                {{-- Filter Tabs --}}
                <div class="flex gap-1">
                    @foreach(['all'=>'All','pending'=>'Pending','replied'=>'Replied','closed'=>'Closed'] as $key=>$label)
                    <a href="{{ route('admin.inquiries.index', ['filter'=>$key,'search'=>$search]) }}"
                        class="flex-1 text-center text-[11px] font-bold py-1.5 rounded-lg transition-colors {{ $filter===$key ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Conversations List --}}
            <div class="flex-1 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($conversations as $convo)
                @php
                $statusClasses = ['pending'=>'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400','replied'=>'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400','closed'=>'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400'];
                @endphp
                <a href="{{ route('admin.inquiries.index', ['conversation'=>$convo->id,'filter'=>$filter,'search'=>$search]) }}"
                    class="flex items-start gap-3 p-4 hover:bg-violet-50 dark:hover:bg-violet-900/10 transition-colors {{ $activeConversation && $activeConversation->id===$convo->id ? 'bg-violet-50 dark:bg-violet-900/10 border-l-2 border-violet-600' : '' }}">
                    <div class="w-9 h-9 rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-600 flex items-center justify-center font-bold text-sm shrink-0">
                        {{ strtoupper(substr($convo->customer?->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1 mb-0.5">
                            <span class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $convo->customer?->name ?? 'Unknown' }}</span>
                            <span class="text-[10px] text-gray-400 shrink-0">{{ $convo->last_message_at?->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-violet-500 dark:text-violet-400 truncate mb-0.5">{{ $convo->enterprise?->name ?? '—' }}</p>
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs text-gray-400 truncate">{{ $convo->latestMessage?->message ?? 'No messages' }}</p>
                            <span class="shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $statusClasses[$convo->status] ?? '' }}">{{ ucfirst($convo->status) }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="p-10 text-center text-gray-400">
                    <i class="fa-regular fa-comment-dots text-3xl mb-2 block opacity-30"></i>
                    <p class="text-sm">No conversations found.</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($conversations->hasPages())
            <div class="p-3 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30 text-xs text-gray-400 flex justify-between items-center">
                <span>{{ $conversations->firstItem() }}–{{ $conversations->lastItem() }} of {{ $conversations->total() }}</span>
                <div class="flex gap-1">
                    @if($conversations->previousPageUrl())
                    <a href="{{ $conversations->previousPageUrl() }}" class="px-2 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50">‹</a>
                    @endif
                    @if($conversations->nextPageUrl())
                    <a href="{{ $conversations->nextPageUrl() }}" class="px-2 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50">›</a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT: Chat / Stats --}}
        <div class="flex-1 flex flex-col min-w-0 gap-5">

            @if($activeConversation)
            {{-- Chat View --}}
            <div class="flex-1 bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col min-h-0 overflow-hidden">

                {{-- Chat Header --}}
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30 flex items-center justify-between gap-4 shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center font-bold shrink-0">
                            {{ strtoupper(substr($activeConversation->customer?->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-gray-900 dark:text-white text-sm">{{ $activeConversation->customer?->name }}</span>
                                <span class="text-gray-400 text-xs">→</span>
                                <span class="font-semibold text-violet-600 dark:text-violet-400 text-sm">{{ $activeConversation->enterprise?->name }}</span>
                                @if($activeConversation->product)
                                    <span class="text-gray-400 text-xs">•</span>
                                    <a href="{{ route('product.show', $activeConversation->product->id) }}" class="text-xs text-gray-500 hover:text-violet-600 truncate max-w-[120px]">{{ $activeConversation->product->name }}</a>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Started {{ $activeConversation->created_at->format('M d, Y h:i A') }}
                                • Last activity {{ $activeConversation->last_message_at?->diffForHumans() ?? '—' }}
                            </p>
                        </div>
                    </div>
                    {{-- Moderation Actions --}}
                    <div class="flex items-center gap-2 shrink-0">
                        @php $sBadge = ['pending'=>'bg-yellow-100 text-yellow-700','replied'=>'bg-blue-100 text-blue-700','closed'=>'bg-gray-100 text-gray-600']; @endphp
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $sBadge[$activeConversation->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($activeConversation->status) }}</span>

                        @if($activeConversation->status !== 'closed')
                        <form method="POST" action="{{ route('admin.inquiries.close', $activeConversation->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 rounded-lg font-semibold transition-colors">
                                <i class="fa-solid fa-lock mr-1"></i> Close
                            </button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.inquiries.reopen', $activeConversation->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 border border-green-200 rounded-lg font-semibold transition-colors">
                                <i class="fa-solid fa-lock-open mr-1"></i> Reopen
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                {{-- Messages --}}
                <div id="admin-chat-messages" class="flex-1 overflow-y-auto p-5 space-y-3 bg-gray-50/30 dark:bg-gray-900/10">
                    @foreach($messages as $msg)
                    @php $isCustomer = $msg->sender_id === $activeConversation->customer_id; @endphp
                    <div class="flex {{ $isCustomer ? 'justify-start' : 'justify-end' }} gap-2 group">
                        @if($isCustomer)
                        <div class="w-7 h-7 rounded-full bg-violet-100 text-violet-600 text-xs font-bold flex items-center justify-center shrink-0 mt-1">
                            {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                        </div>
                        @endif
                        <div class="max-w-[65%]">
                            <div class="px-4 py-2.5 text-sm {{ $isCustomer ? 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 rounded-[14px_14px_14px_4px] border border-gray-100 dark:border-gray-700' : 'bg-violet-600 text-white rounded-[14px_14px_4px_14px]' }}">
                                {{ $msg->message }}
                            </div>
                            <div class="flex items-center gap-2 mt-1 {{ $isCustomer ? '' : 'justify-end' }}">
                                <span class="text-[10px] text-gray-400">{{ $msg->created_at->format('h:i A') }}</span>
                                {{-- Delete btn (admin only) --}}
                                <form method="POST" action="{{ route('admin.inquiries.message.delete', $msg->id) }}"
                                    onsubmit="return confirm('Delete this message?')"
                                    class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[10px] text-red-400 hover:text-red-600 font-medium">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if($messages->isEmpty())
                    <div class="text-center py-10 text-gray-400 text-sm">No messages in this conversation.</div>
                    @endif
                </div>

                {{-- Admin read-only footer --}}
                <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/20 text-xs text-center text-gray-400 shrink-0">
                    <i class="fa-solid fa-shield-halved text-violet-500 mr-1"></i>
                    Admin view — read-only. Hover over a message to delete it.
                </div>
            </div>

            @else
            {{-- No conversation selected + Seller Performance Table --}}
            <div class="flex-1 flex flex-col gap-5">
                <div class="flex-1 bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col items-center justify-center text-center p-8 text-gray-400">
                    <div class="w-20 h-20 bg-violet-50 dark:bg-violet-900/20 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-comments text-violet-300 dark:text-violet-700 text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-700 dark:text-gray-300 text-lg mb-2">Select a conversation</h3>
                    <p class="text-sm max-w-xs">Click any conversation from the list to view messages and take moderation actions.</p>
                </div>

                {{-- Seller Response Performance --}}
                @if($sellerStats->isNotEmpty())
                <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30">
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-chart-bar text-violet-500"></i> Seller Conversation Activity
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50/50 dark:bg-gray-800/30 text-xs uppercase font-bold tracking-wider text-gray-400">
                                <tr>
                                    <th class="px-4 py-3 text-left">Seller / Store</th>
                                    <th class="px-4 py-3 text-center">Total</th>
                                    <th class="px-4 py-3 text-center">Pending</th>
                                    <th class="px-4 py-3 text-center">Replied</th>
                                    <th class="px-4 py-3 text-center">Closed</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($sellerStats as $st)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-gray-900 dark:text-white">{{ $st['name'] }}</p>
                                        <p class="text-xs text-gray-400">{{ $st['seller'] }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-bold text-gray-900 dark:text-white">{{ $st['total'] }}</td>
                                    <td class="px-4 py-3 text-center"><span class="text-xs font-bold text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 px-2 py-0.5 rounded-full">{{ $st['pending'] }}</span></td>
                                    <td class="px-4 py-3 text-center"><span class="text-xs font-bold text-blue-600 bg-blue-50 dark:bg-blue-900/20 px-2 py-0.5 rounded-full">{{ $st['replied'] }}</span></td>
                                    <td class="px-4 py-3 text-center"><span class="text-xs font-bold text-gray-500 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-full">{{ $st['closed'] }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <script>
        // Auto-scroll chat to bottom
        const chatEl = document.getElementById('admin-chat-messages');
        if (chatEl) chatEl.scrollTop = chatEl.scrollHeight;
    </script>

</x-admin-layout>
