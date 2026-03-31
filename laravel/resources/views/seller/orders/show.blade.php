<x-seller-layout>
    <x-slot name="header">Order Details</x-slot>

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('seller.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Orders
        </a>
    </div>

    {{-- ── Cancel Confirmation Modal ─────────────────────────────── --}}
    <div id="cancelModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCancelModal()"></div>
        {{-- Modal Card --}}
        <div class="relative bg-white dark:bg-[#13111C] rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 w-full max-w-md p-8 z-10 animate-[fadeIn_0.2s_ease-out]">
            {{-- Icon --}}
            <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-red-50 dark:bg-red-900/20 mb-5">
                <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">Cancel This Order?</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-8 leading-relaxed">
                This will <strong class="text-red-600 dark:text-red-400">cancel</strong> Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} and restore the stock for all items. This action cannot be undone from this page.
            </p>
            <div class="flex items-center gap-3">
                <button onclick="closeCancelModal()" class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    Keep Order
                </button>
                {{-- This submits the actual form with status=cancelled --}}
                <button onclick="confirmCancel()" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold shadow-lg shadow-red-500/30 transition-all">
                    Yes, Cancel Order
                </button>
            </div>
        </div>
    </div>

    {{-- ── Order Content ────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Items + Chat Panel --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Order Items Card --}}
            <div class="bg-white dark:bg-[#13111C] shadow-sm rounded-2xl border border-gray-100 dark:border-gray-800/60 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800/60 flex justify-between items-center bg-gray-50/50 dark:bg-white/[0.02]">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Order Items</h3>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $order->items->count() }} Items</span>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800/60">
                    @foreach($order->items as $item)
                    <div class="p-6 flex items-center gap-4">
                        <div class="h-16 w-16 shrink-0 rounded-xl bg-gray-100 dark:bg-gray-800 overflow-hidden border border-gray-200 dark:border-gray-700">
                            @if($item->product && $item->product->image_path)
                                <img src="{{ \App\Helpers\ImageHelper::url($item->product->image_path) }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-gray-400">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $item->product ? $item->product->name : 'Unknown Product' }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">₱{{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}</p>
                        </div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white shrink-0">₱{{ number_format($item->subtotal, 2) }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 py-5 border-t border-gray-100 dark:border-gray-800/60 flex justify-between items-center bg-gray-50/50 dark:bg-white/[0.02]">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</span>
                    <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-violet-600 to-indigo-600">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            @if($order->notes)
            <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800/60 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Customer Notes</h3>
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/10 rounded-xl border border-yellow-100 dark:border-yellow-900/30">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">{{ $order->notes }}</p>
                </div>
            </div>
            @endif

            {{-- ── Recent Inquiries / Chat Panel ─────────────────── --}}
            <div class="bg-white dark:bg-[#13111C] rounded-2xl border border-gray-100 dark:border-gray-800/60 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800/60 flex items-center justify-between bg-gray-50/50 dark:bg-white/[0.02]">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-message text-violet-500"></i> Recent Customer Inquiries
                    </h3>
                    <a href="{{ route('seller.inquiries.index') }}" class="text-xs text-violet-600 dark:text-violet-400 font-semibold hover:underline">View All</a>
                </div>
                @forelse($recentConversations as $conv)
                <a href="{{ route('seller.inquiries.index', ['conversation' => $conv->id]) }}"
                   class="flex items-start gap-4 px-6 py-4 border-b border-gray-100 dark:border-gray-800/60 hover:bg-violet-50/50 dark:hover:bg-violet-900/10 transition-colors group last:border-b-0">
                    {{-- Avatar --}}
                    <div class="w-10 h-10 rounded-full bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 dark:text-violet-400 font-bold text-sm shrink-0 group-hover:ring-2 ring-violet-400 transition-all">
                        {{ strtoupper(substr($conv->customer->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline gap-2">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $conv->customer->name ?? 'Customer' }}</p>
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 shrink-0 whitespace-nowrap">{{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : '' }}</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ $conv->latestMessage->message ?? 'No messages yet.' }}</p>
                    </div>
                    {{-- Status badge --}}
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase rounded
                        {{ $conv->status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/20' :
                           ($conv->status === 'replied' ? 'bg-green-100 text-green-700 dark:bg-green-900/20' :
                           'bg-gray-100 text-gray-500 dark:bg-gray-800') }}">
                        {{ $conv->status }}
                    </span>
                </a>
                @empty
                <div class="px-6 py-10 text-center">
                    <div class="w-12 h-12 mx-auto rounded-xl bg-violet-50 dark:bg-violet-900/20 flex items-center justify-center mb-3">
                        <i class="fa-regular fa-comment-dots text-xl text-violet-400"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">No customer inquiries yet</p>
                    <p class="text-xs text-gray-400 mt-1">Messages from customers will appear here</p>
                </div>
                @endforelse
            </div>

        </div>

        {{-- RIGHT: Status + Customer + Socials --}}
        <div class="space-y-6">

            {{-- Order Status Manager --}}
            <div class="bg-white dark:bg-[#13111C] shadow-sm rounded-2xl border border-gray-100 dark:border-gray-800/60 p-6 overflow-hidden relative">
                <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
                    <svg class="w-24 h-24 text-violet-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>

                @if($order->status === 'cancelled')
                {{-- Cancelled state: locked UI --}}
                <div class="mb-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-center">
                    <i class="fa-solid fa-ban text-red-500 text-2xl mb-2"></i>
                    <p class="text-sm font-bold text-red-700 dark:text-red-400">Order Cancelled</p>
                    <p class="text-xs text-red-500 dark:text-red-400 mt-1">Stock has been restored for all items.</p>
                </div>
                <form action="{{ route('seller.orders.update', $order) }}" method="POST">
                    @csrf @method('PUT')
                    <button type="button" disabled
                        class="w-full flex justify-center py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-gray-300 dark:bg-gray-700 cursor-not-allowed opacity-50">
                        <i class="fa-solid fa-ban mr-2"></i> Cancelled
                    </button>
                </form>

                @else
                {{-- Active order UI --}}
                <form id="statusForm" action="{{ route('seller.orders.update', $order) }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" id="statusInput" value="{{ $order->status }}">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Update Status</label>
                        <select id="statusSelect" onchange="handleStatusChange(this.value)"
                            class="block w-full pl-3 pr-10 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500 shadow-sm appearance-none">
                            @foreach(['pending'=>'Pending','processing'=>'Processing','ready'=>'Ready for Pickup','completed'=>'Completed','cancelled'=>'Cancelled'] as $val => $label)
                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" id="updateBtn" onclick="trySubmitStatus()"
                        class="w-full flex justify-center py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-violet-500 shadow-lg shadow-violet-500/30 transition-all">
                        Update Status
                    </button>
                </form>
                @endif
            </div>

            {{-- Customer Details --}}
            <div class="bg-white dark:bg-[#13111C] shadow-sm rounded-2xl border border-gray-100 dark:border-gray-800/60 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Customer
                </h3>
                <div class="flex items-center space-x-4 mb-4">
                    <div class="h-10 w-10 rounded-full bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 dark:text-violet-400 font-bold text-sm shrink-0">
                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->user->email }}</p>
                    </div>
                </div>
                @if($order->payment_method)
                <div class="pt-4 border-t border-dashed border-gray-100 dark:border-gray-800">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Payment Method</p>
                    <p class="text-sm text-gray-800 dark:text-gray-200">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                </div>
                @endif
            </div>

            {{-- Buyer Socials --}}
            @if($order->social_facebook || $order->social_messenger)
            <div class="bg-white dark:bg-[#13111C] shadow-sm rounded-2xl border border-gray-100 dark:border-gray-800/60 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-share-nodes text-violet-500"></i> Contact Buyer Via Socials
                </h3>
                <div class="space-y-3">
                    @if($order->social_facebook)
                    <a href="{{ $order->social_facebook }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center gap-3 w-full px-4 py-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/40 rounded-xl text-blue-700 dark:text-blue-300 text-sm font-semibold hover:bg-blue-100 transition-colors">
                        <i class="fa-brands fa-facebook text-xl text-blue-500"></i>
                        <span>Open Facebook Profile</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs ml-auto opacity-60"></i>
                    </a>
                    @endif
                    @if($order->social_messenger)
                    <a href="{{ $order->social_messenger }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center gap-3 w-full px-4 py-3 bg-violet-50 dark:bg-violet-900/20 border border-violet-100 dark:border-violet-800/40 rounded-xl text-violet-700 dark:text-violet-300 text-sm font-semibold hover:bg-violet-100 transition-colors">
                        <i class="fa-brands fa-facebook-messenger text-xl text-violet-500"></i>
                        <span>Open Messenger Chat</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs ml-auto opacity-60"></i>
                    </a>
                    @endif
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-3 leading-relaxed">
                    <i class="fa-solid fa-circle-info mr-1"></i> Reach out to coordinate delivery or confirm order details.
                </p>
            </div>
            @endif

        </div>
    </div>

    {{-- Cancel Modal JS --}}
    <script>
        // Move modal to <body> to escape overflow:hidden layout wrapper
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('cancelModal');
            if (modal) document.body.appendChild(modal);
        });
        function openCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.body.style.overflow = '';
            const sel = document.getElementById('statusSelect');
            if (sel) sel.value = '{{ $order->status }}';
        }
        function confirmCancel() {
            document.getElementById('statusInput').value = 'cancelled';
            document.getElementById('statusForm').submit();
        }
        function handleStatusChange(val) {
            if (val === 'cancelled') {
                openCancelModal();
            } else {
                document.getElementById('statusInput').value = val;
            }
        }
        function trySubmitStatus() {
            const sel = document.getElementById('statusSelect');
            if (sel.value === 'cancelled') {
                openCancelModal();
            } else {
                document.getElementById('statusForm').submit();
            }
        }
    </script>
</x-seller-layout>
