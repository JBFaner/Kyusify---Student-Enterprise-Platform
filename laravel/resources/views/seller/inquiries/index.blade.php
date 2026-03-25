<x-seller-layout>
    <x-slot name="header">Inquiries</x-slot>

    <style>
        #inbox-panel { height: calc(100vh - 140px); }
        #chat-panel   { height: calc(100vh - 140px); }
        #chat-messages { scroll-behavior: smooth; }
        .msg-bubble-me   { background: #7c3aed; color:#fff; border-radius:18px 18px 4px 18px; }
        .msg-bubble-them { background: #f3f4f6; color:#111; border-radius:18px 18px 18px 4px; }
        .dark .msg-bubble-them { background:#1f2937; color:#e5e7eb; }
        .convo-card.active { background:#ede9fe; border-color:#7c3aed; }
        .dark .convo-card.active { background:#2e1065; border-color:#7c3aed; }
    </style>

    <div class="flex gap-0 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-sm bg-white dark:bg-[#13111C]" style="height:calc(100vh - 108px);">

        {{-- ── LEFT: Inbox List ──────────────────────────────── --}}
        <div id="inbox-panel" class="w-[320px] flex-shrink-0 flex flex-col border-r border-gray-200 dark:border-gray-800">

            {{-- Header --}}
            <div class="p-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30 flex items-center justify-between gap-3">
                <h2 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-comments text-violet-500"></i> Inbox
                    @if($unreadCount > 0)
                        <span class="bg-violet-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </h2>
                <a href="{{ route('seller.quick-replies.index') }}" class="text-xs text-violet-600 hover:underline font-semibold shrink-0">⚡ Quick Replies</a>
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('seller.inquiries.index') }}" class="p-3 border-b border-gray-100 dark:border-gray-800">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" placeholder="Search customer..." value="{{ request('search') }}"
                        class="w-full pl-8 pr-3 py-2 text-sm bg-gray-100 dark:bg-gray-800 border-0 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white">
                </div>
            </form>

            {{-- Filter Tabs --}}
            <div class="flex text-xs font-bold overflow-x-auto border-b border-gray-100 dark:border-gray-800">
                @php
                $tabs = [
                    'all'     => ['label'=>'All',     'count'=>$allCount],
                    'unread'  => ['label'=>'Unread',  'count'=>$unreadCount],
                    'pending' => ['label'=>'Pending', 'count'=>$pendingCount],
                    'replied' => ['label'=>'Replied', 'count'=>$repliedCount],
                    'closed'  => ['label'=>'Closed',  'count'=>$closedCount],
                ];
                @endphp
                @foreach($tabs as $key => $tab)
                <a href="{{ route('seller.inquiries.index', ['filter'=>$key, 'search'=>request('search')]) }}"
                   class="flex-shrink-0 px-3 py-2.5 border-b-2 transition-colors {{ $filter === $key ? 'border-violet-600 text-violet-600' : 'border-transparent text-gray-500 hover:text-gray-800 dark:hover:text-gray-200' }}">
                    {{ $tab['label'] }}
                    @if($tab['count'] > 0)
                        <span class="ml-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 px-1.5 py-0.5 rounded-full text-[10px]">{{ $tab['count'] }}</span>
                    @endif
                </a>
                @endforeach
            </div>

            {{-- Conversation List --}}
            <div class="flex-1 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($conversations as $convo)
                    @php $unread = $convo->unreadCount(auth()->id()); @endphp
                    <a href="{{ route('seller.inquiries.index', ['conversation'=>$convo->id, 'filter'=>$filter]) }}"
                       class="convo-card flex items-start gap-3 p-4 hover:bg-violet-50 dark:hover:bg-violet-900/10 transition-colors border border-transparent {{ $activeConversation && $activeConversation->id === $convo->id ? 'active' : '' }}">
                        {{-- Avatar --}}
                        <div class="w-10 h-10 rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 flex items-center justify-center font-bold text-sm shrink-0 relative">
                            {{ strtoupper(substr($convo->customer->name, 0, 1)) }}
                            @if($unread > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 bg-violet-600 rounded-full border-2 border-white dark:border-[#13111C] text-white text-[9px] font-black flex items-center justify-center">{{ $unread }}</span>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                <span class="text-sm font-bold text-gray-900 dark:text-white truncate {{ $unread ? 'text-violet-700 dark:text-violet-300' : '' }}">{{ $convo->customer->name }}</span>
                                <span class="text-[10px] text-gray-400 shrink-0">{{ $convo->last_message_at ? $convo->last_message_at->diffForHumans() : '' }}</span>
                            </div>
                            @if($convo->product)
                                <div class="text-[10px] text-violet-600 dark:text-violet-400 mb-0.5 truncate">re: {{ $convo->product->name }}</div>
                            @endif
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate {{ $unread ? 'font-semibold text-gray-700 dark:text-gray-200' : '' }}">
                                {{ $convo->latestMessage?->message ?? 'No messages yet' }}
                            </p>
                        </div>
                        {{-- Status Badge --}}
                        <div class="shrink-0">
                            @php $statusClasses = ['pending'=>'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400','replied'=>'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400','closed'=>'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400']; @endphp
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $statusClasses[$convo->status] ?? '' }}">{{ ucfirst($convo->status) }}</span>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-gray-400 dark:text-gray-500">
                        <i class="fa-regular fa-comment-dots text-4xl mb-3 block"></i>
                        <p class="text-sm font-medium">No conversations yet.</p>
                        <p class="text-xs mt-1">When customers message you, they'll appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── RIGHT: Chat Window ────────────────────────────── --}}
        <div id="chat-panel" class="flex-1 flex flex-col min-w-0">

            @if($activeConversation)
            {{-- Chat Header --}}
            <div class="p-4 border-b border-gray-100 dark:border-gray-800 bg-white dark:bg-[#13111C] flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-600 flex items-center justify-center font-bold shrink-0">
                        {{ strtoupper(substr($activeConversation->customer->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $activeConversation->customer->name }}</h3>
                        @if($activeConversation->product)
                            <p class="text-xs text-violet-600 truncate">
                                <i class="fa-solid fa-tag text-[10px]"></i>
                                <a href="{{ route('product.show', $activeConversation->product->id) }}" class="hover:underline">{{ $activeConversation->product->name }}</a>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    @php $statusClasses = ['pending'=>'bg-yellow-100 text-yellow-700','replied'=>'bg-green-100 text-green-700','closed'=>'bg-gray-100 text-gray-500']; @endphp
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $statusClasses[$activeConversation->status] ?? '' }}">{{ ucfirst($activeConversation->status) }}</span>
                    @if($activeConversation->status !== 'closed')
                        <form method="POST" action="{{ route('seller.inquiries.close', $activeConversation->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs px-3 py-1.5 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors font-semibold">
                                <i class="fa-solid fa-xmark mr-1"></i> Close
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Messages --}}
            <div id="chat-messages" class="flex-1 overflow-y-auto p-5 space-y-3 bg-gray-50/30 dark:bg-gray-900/10">
                @foreach($messages as $msg)
                    @php $isMe = $msg->sender_id !== $activeConversation->customer_id; @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} gap-2">
                        @if(!$isMe)
                            <div class="w-7 h-7 rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-600 flex items-center justify-center text-xs font-bold shrink-0 mt-1">
                                {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="max-w-[70%]">
                            <div class="px-4 py-2.5 text-sm {{ $isMe ? 'msg-bubble-me' : 'msg-bubble-them' }}">{{ $msg->message }}</div>
                            <div class="text-[10px] text-gray-400 mt-1 {{ $isMe ? 'text-right' : 'text-left' }}">{{ $msg->created_at->format('h:i A') }}</div>
                        </div>
                    </div>
                @endforeach
                <div id="chat-bottom"></div>
            </div>

            {{-- Quick Replies --}}
            @if($quickReplies->isNotEmpty() && $activeConversation->status !== 'closed')
            <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-[#13111C] flex gap-2 overflow-x-auto hide-scroll">
                @foreach($quickReplies as $qr)
                <button type="button" onclick="document.getElementById('reply-input').value='{{ addslashes($qr->message) }}'; document.getElementById('reply-input').focus();"
                    class="shrink-0 text-xs px-3 py-1.5 bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300 rounded-full border border-violet-200 dark:border-violet-800 hover:bg-violet-100 transition-colors font-medium">
                    {{ $qr->message }}
                </button>
                @endforeach
            </div>
            @endif

            {{-- Reply Input --}}
            @if($activeConversation->status !== 'closed')
            <form id="reply-form" method="POST" action="{{ route('seller.inquiries.reply', $activeConversation->id) }}"
                class="p-4 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-[#13111C] flex gap-3 items-end">
                @csrf
                <textarea id="reply-input" name="message" rows="1" placeholder="Type a message..." required
                    class="flex-1 resize-none rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all"
                    onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();this.closest('form').submit();}"></textarea>
                <button type="submit"
                    class="flex-shrink-0 bg-violet-600 hover:bg-violet-700 text-white w-11 h-11 rounded-xl flex items-center justify-center transition-colors shadow-sm">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                </button>
            </form>
            @else
            <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/20 text-center text-sm text-gray-400">
                <i class="fa-solid fa-lock mr-1"></i> This conversation is closed.
            </div>
            @endif

            @else
            {{-- Empty State --}}
            <div class="flex-1 flex flex-col items-center justify-center text-center p-8 text-gray-400 dark:text-gray-500">
                <div class="w-20 h-20 bg-violet-50 dark:bg-violet-900/20 rounded-full flex items-center justify-center mb-5">
                    <i class="fa-solid fa-comments text-violet-300 dark:text-violet-700 text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-2">Select a conversation</h3>
                <p class="text-sm max-w-xs">Click a conversation from the inbox to view and reply to messages.</p>
            </div>
            @endif
        </div>
    </div>

    <style>
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style:none; scrollbar-width:none; }
    </style>

    <script>
        // Auto-scroll to bottom on load
        const bottom = document.getElementById('chat-bottom');
        if (bottom) bottom.scrollIntoView();

        // Auto-resize textarea
        const ta = document.getElementById('reply-input');
        if (ta) {
            ta.addEventListener('input', () => {
                ta.style.height = 'auto';
                ta.style.height = Math.min(ta.scrollHeight, 120) + 'px';
            });
        }

        @if($activeConversation)
        // Poll for new messages every 4 seconds
        let lastId = {{ $messages->last()?->id ?? 0 }};
        const convId = {{ $activeConversation->id }};
        const userId = {{ auth()->id() }};

        const messagesEl = document.getElementById('chat-messages');

        function appendMessage(m) {
            const div = document.createElement('div');
            div.className = `flex ${m.is_me ? 'justify-end' : 'justify-start'} gap-2`;
            const bubble = m.is_me
                ? `<div class="max-w-[70%]"><div class="px-4 py-2.5 text-sm msg-bubble-me">${escapeHtml(m.message)}</div><div class="text-[10px] text-gray-400 mt-1 text-right">${m.created_at}</div></div>`
                : `<div class="w-7 h-7 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center text-xs font-bold shrink-0 mt-1">${m.sender.charAt(0).toUpperCase()}</div><div class="max-w-[70%]"><div class="px-4 py-2.5 text-sm msg-bubble-them">${escapeHtml(m.message)}</div><div class="text-[10px] text-gray-400 mt-1">${m.created_at}</div></div>`;
            div.innerHTML = bubble;
            const chatBottom = document.getElementById('chat-bottom');
            messagesEl.insertBefore(div, chatBottom);
            chatBottom.scrollIntoView({ behavior: 'smooth' });
        }

        function escapeHtml(text) {
            const d = document.createElement('div'); d.textContent = text; return d.innerHTML;
        }

        setInterval(async () => {
            try {
                const res = await fetch(`/seller/inquiries/${convId}/messages`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                data.messages.forEach(m => {
                    if (m.id > lastId) { appendMessage(m); lastId = m.id; }
                });
            } catch(e) {}
        }, 4000);
        @endif
    </script>

</x-seller-layout>
