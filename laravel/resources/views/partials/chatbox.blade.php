{{--
  Customer Chatbox Widget — include in store/show.blade.php
  Requires: $store (Enterprise model), Alpine.js, FontAwesome, csrf-token meta tag
--}}
@auth
<div
    x-data="chatbox({{ $store->id }}, '{{ addslashes($store->name) }}')"
    x-init="init()"
    class="fixed bottom-6 right-6 z-[9999] flex flex-col items-end gap-3 select-none"
    style="font-family:'Outfit',sans-serif;"
>
    {{-- Chat Window --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200 origin-bottom-right"
        x-transition:enter-start="opacity-0 scale-90 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90 translate-y-4"
        class="w-[340px] bg-white dark:bg-[#13111C] rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,0.25)] border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col"
        :style="minimized ? 'height:62px;overflow:hidden;' : 'height:500px;'"
        style="display:none;"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between gap-3 px-4 py-3.5 bg-violet-600 text-white shrink-0 cursor-pointer" @click="minimized && (minimized = false)">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold shrink-0">
                    {{ strtoupper(substr($store->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-sm truncate">{{ $store->name }}</p>
                    <p class="text-[10px] text-violet-200" x-text="status === 'closed' ? '🔒 Conversation closed' : '💬 Usually replies quickly'"></p>
                </div>
            </div>
            <div class="flex items-center gap-1 shrink-0" @click.stop>
                <button @click="minimized = !minimized" class="p-1.5 hover:bg-white/20 rounded-lg transition-colors" title="Minimize">
                    <i class="fa-solid text-xs" :class="minimized ? 'fa-chevron-up' : 'fa-minus'"></i>
                </button>
                <button @click="close()" class="p-1.5 hover:bg-white/20 rounded-lg transition-colors" title="Close">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
        </div>

        {{-- Body — Messages --}}
        <div x-show="!minimized" id="chatbox-messages"
            class="flex-1 overflow-y-auto p-4 space-y-2.5 bg-gray-50/50 dark:bg-gray-900/10"
            style="scroll-behavior:smooth;">

            {{-- Loading spinner --}}
            <div x-show="loading" class="flex justify-center py-6">
                <i class="fa-solid fa-circle-notch fa-spin text-violet-400 text-xl"></i>
            </div>

            {{-- Empty state --}}
            <div x-show="!loading && messages.length === 0" class="text-center py-6">
                <i class="fa-regular fa-comment-dots text-3xl text-gray-300 dark:text-gray-600 mb-2 block"></i>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Hi! How can we help you?</p>
                <p class="text-xs text-gray-400 mt-1">Tap a question below or type your own.</p>
            </div>

            {{-- Messages --}}
            <template x-for="m in messages" :key="m.id">
                <div :class="m.is_me ? 'flex justify-end' : 'flex justify-start gap-2'">
                    <template x-if="!m.is_me">
                        <div class="w-6 h-6 rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-600 text-xs font-bold flex items-center justify-center shrink-0 mt-1"
                            x-text="m.sender.charAt(0).toUpperCase()"></div>
                    </template>
                    <div class="max-w-[80%]">
                        <div class="px-3.5 py-2 text-sm"
                            :class="m.is_me
                                ? 'bg-violet-600 text-white rounded-[14px_14px_4px_14px]'
                                : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-[14px_14px_14px_4px] border border-gray-100 dark:border-gray-700'"
                            x-text="m.message"></div>
                        <div class="text-[10px] text-gray-400 mt-1" :class="m.is_me ? 'text-right' : ''"
                            x-text="m.created_at"></div>
                    </div>
                </div>
            </template>
            <div id="chatbox-bottom"></div>
        </div>

        {{-- Closed notice --}}
        <div x-show="!minimized && status === 'closed'" class="px-4 py-2 bg-gray-100 dark:bg-gray-800/50 text-xs text-center text-gray-500 shrink-0">
            <i class="fa-solid fa-lock mr-1"></i> This conversation is closed by the seller.
        </div>

        {{-- FAQ Question Buttons --}}
        <div x-show="!minimized && status !== 'closed' && quickReplies.length > 0"
            class="px-3 pt-2 pb-1 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-[#13111C] shrink-0">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Quick questions</p>
            <div class="flex flex-wrap gap-1.5">
                <template x-for="qr in quickReplies" :key="qr.id">
                    <button
                        @click="sendAutoReply(qr)"
                        :disabled="sending"
                        class="text-xs px-2.5 py-1.5 bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300 rounded-full border border-violet-200 dark:border-violet-700 hover:bg-violet-100 dark:hover:bg-violet-800/30 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                        x-text="qr.question">
                    </button>
                </template>
            </div>
        </div>

        {{-- Footer — Input --}}
        <div x-show="!minimized && status !== 'closed'"
            class="border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-[#13111C] shrink-0">
            <div class="flex items-end gap-2 px-3 pt-2.5 pb-2.5">
                <textarea
                    x-model="inputMsg"
                    @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                    rows="1"
                    placeholder="Type a message..."
                    class="flex-1 resize-none text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-violet-500 outline-none"
                    style="max-height:90px;"
                    @input="$el.style.height='auto'; $el.style.height=Math.min($el.scrollHeight,90)+'px'"
                ></textarea>
                <button @click="sendMessage()" :disabled="!inputMsg.trim() || sending"
                    class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors shrink-0"
                    :class="inputMsg.trim() && !sending ? 'bg-violet-600 hover:bg-violet-700 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-400 cursor-not-allowed'">
                    <i class="fa-solid fa-paper-plane text-xs" x-show="!sending"></i>
                    <i class="fa-solid fa-circle-notch fa-spin text-xs" x-show="sending"></i>
                </button>
            </div>
            <p class="text-center text-[10px] text-gray-400 pb-2">Powered by <span class="font-bold text-violet-500">Kyusify</span></p>
        </div>
    </div>

    {{-- Floating Trigger Button --}}
    <button @click="toggle()"
        class="w-14 h-14 rounded-full shadow-[0_8px_30px_rgba(124,58,237,0.45)] flex items-center justify-center transition-all duration-200 hover:scale-110 active:scale-95 relative"
        :class="open && !minimized ? 'bg-gray-700 dark:bg-gray-600' : 'bg-violet-600 hover:bg-violet-700'"
        title="Chat with seller">
        <i class="fa-solid text-white text-xl" :class="open && !minimized ? 'fa-xmark' : 'fa-comments'"></i>
        <span x-show="unreadCount > 0 && !open"
            class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white"
            x-text="unreadCount"></span>
    </button>
</div>

<script>
function chatbox(enterpriseId, storeName) {
    return {
        open: false,
        minimized: false,
        loading: false,
        sending: false,
        inputMsg: '',
        messages: [],
        quickReplies: [],
        conversationId: null,
        status: 'pending',
        lastId: 0,
        unreadCount: 0,
        pollInterval: null,

        init() {},

        toggle() {
            if (!this.open) {
                this.open = true;
                this.minimized = false;
                if (!this.conversationId) this.loadConversation();
            } else {
                this.minimized = !this.minimized;
            }
        },

        close() {
            this.open = false;
            this.minimized = false;
            if (this.pollInterval) clearInterval(this.pollInterval);
        },

        async loadConversation() {
            this.loading = true;
            try {
                const res = await fetch(`/inquiry/${enterpriseId}/start`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({}),
                });
                const data = await res.json();
                this.conversationId = data.conversation_id;
                this.status = data.status;
                this.messages = data.messages;
                this.quickReplies = data.quick_replies || [];
                this.lastId = data.messages.length ? data.messages[data.messages.length - 1].id : 0;
                this.$nextTick(() => this.scrollBottom());
                this.startPolling();
            } catch (e) {
                console.error('Chat load error:', e);
            } finally {
                this.loading = false;
            }
        },

        async sendMessage() {
            if (!this.inputMsg.trim() || this.sending) return;
            const msg = this.inputMsg.trim();
            this.inputMsg = '';
            this.sending = true;
            try {
                const res = await fetch(`/inquiry/${this.conversationId}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: msg }),
                });
                const data = await res.json();
                if (data.id) {
                    this.messages.push(data);
                    this.lastId = data.id;
                    this.$nextTick(() => this.scrollBottom());
                }
            } catch (e) {}
            this.sending = false;
        },

        // Called when customer clicks a FAQ question button
        async sendAutoReply(qr) {
            if (this.sending) return;
            this.sending = true;
            try {
                const res = await fetch(`/inquiry/${this.conversationId}/auto-reply`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ quick_reply_id: qr.id }),
                });
                const data = await res.json();
                if (data.messages) {
                    data.messages.forEach(m => {
                        this.messages.push(m);
                        this.lastId = m.id;
                    });
                    this.$nextTick(() => this.scrollBottom());
                }
            } catch (e) {}
            this.sending = false;
        },

        startPolling() {
            if (this.pollInterval) clearInterval(this.pollInterval);
            this.pollInterval = setInterval(async () => {
                if (!this.conversationId || !this.open || this.minimized) return;
                try {
                    const res = await fetch(`/inquiry/${this.conversationId}/poll?last_id=${this.lastId}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    this.status = data.status;
                    if (data.messages.length) {
                        data.messages.forEach(m => {
                            this.messages.push(m);
                            if (!m.is_me) this.unreadCount++;
                        });
                        this.lastId = data.messages[data.messages.length - 1].id;
                        this.unreadCount = 0;
                        this.$nextTick(() => this.scrollBottom());
                    }
                } catch(e) {}
            }, 4000);
        },

        scrollBottom() {
            const el = document.getElementById('chatbox-bottom');
            if (el) el.scrollIntoView({ behavior: 'smooth' });
        }
    };
}
</script>
@else
{{-- Guest prompt --}}
<div class="fixed bottom-6 right-6 z-[9999]" x-data="{ open: false }">
    <div x-show="open"
        x-transition:enter="transition ease-out duration-200 origin-bottom-right"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        class="mb-3 w-72 bg-white dark:bg-[#13111C] rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-5 text-center"
        style="display:none;">
        <i class="fa-solid fa-comments text-violet-500 text-2xl mb-3 block"></i>
        <h3 class="font-bold text-gray-900 dark:text-white mb-1">Chat with {{ $store->name }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Log in to send a message to the seller.</p>
        <a href="{{ route('login') }}" class="block w-full bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold py-2.5 rounded-xl transition-colors">
            Log in to Chat
        </a>
    </div>
    <button @click="open = !open"
        class="w-14 h-14 rounded-full bg-violet-600 hover:bg-violet-700 shadow-[0_8px_30px_rgba(124,58,237,0.45)] flex items-center justify-center transition-all hover:scale-110 active:scale-95">
        <i class="fa-solid fa-comments text-white text-xl"></i>
    </button>
</div>
@endauth
