<div class="relative" x-data="{
        open: false,
        notifications: [],
        unreadCount: 0,
        init() {
            this.fetchNotifications();
            setInterval(() => this.fetchNotifications(), 60000); // 1 minute
        },
        async fetchNotifications() {
            try {
                const res = await fetch('{{ route('notifications.index') }}');
                if(res.ok) {
                    const data = await res.json();
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                }
            } catch(e) {}
        },
        async markAsRead(notification) {
            if (!notification.is_read) {
                try {
                    const res = await fetch(`/notifications/${notification.id}/read`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        }
                    });
                    if (res.ok) {
                        notification.is_read = true;
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                } catch(e) {}
            }
            if (notification.link) window.location.href = notification.link;
        },
        async markAllRead() {
            try {
                const res = await fetch('{{ route('notifications.read-all') }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });
                if (res.ok) {
                    this.notifications.forEach(n => n.is_read = true);
                    this.unreadCount = 0;
                }
            } catch(e) {}
        },
        formatTime(dateStr) {
            const diffMs = Math.abs(new Date() - new Date(dateStr));
            const mins = Math.floor(diffMs / 60000);
            if (mins < 60) return mins <= 1 ? 'Just now' : mins + 'm ago';
            const hours = Math.floor(mins / 60);
            if (hours < 24) return hours + 'h ago';
            const days = Math.floor(hours / 24);
            if (days < 7) return days + 'd ago';
            return new Date(dateStr).toLocaleDateString();
        },
        getIconConfig(type) {
            const config = {
                'order': { class: 'text-blue-600 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400', d: 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z' },
                'review': { class: 'text-yellow-600 bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400', d: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z' },
                'inquiry': { class: 'text-green-600 bg-green-100 dark:bg-green-900/30 dark:text-green-400', d: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z' },
                'product': { class: 'text-purple-600 bg-purple-100 dark:bg-purple-900/30 dark:text-purple-400', d: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' },
                'bell': { class: 'text-violet-600 bg-violet-100 dark:bg-violet-900/30 dark:text-violet-400', d: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9' }
            };
            return config[type] || config['bell'];
        }
    }" @click.away="open = false">
    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-red-500 border-2 border-white dark:border-[#13111C] rounded-full"></span>
    </button>

    <div x-show="open" x-transition.opacity.duration.200ms class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-[#13111C] rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 z-50 overflow-hidden" style="display: none;">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-800">
            <h3 class="font-bold text-gray-900 dark:text-white">Notifications</h3>
            <button x-show="unreadCount > 0" @click="markAllRead()" class="text-xs text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 font-medium transition-colors">Mark all as read</button>
        </div>
        <div class="overflow-y-auto max-h-[400px] bg-gray-50/50 dark:bg-[#0B0A0F]/50">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    You have no notifications right now.
                </div>
            </template>
            <template x-for="notification in notifications" :key="notification.id">
                <div @click="markAsRead(notification)" 
                     class="flex gap-3 px-4 py-3 border-b border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors"
                     :class="notification.is_read ? 'opacity-70' : 'bg-violet-50/30 dark:bg-violet-900/10'">
                    <div class="shrink-0 mt-1">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" :class="getIconConfig(notification.icon).class">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getIconConfig(notification.icon).d" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="notification.title"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2" x-text="notification.message"></p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 font-medium" x-text="formatTime(notification.created_at)"></p>
                    </div>
                    <div class="shrink-0 flex items-center justify-center w-2">
                        <div x-show="!notification.is_read" class="w-2 h-2 rounded-full bg-violet-600 dark:bg-violet-500"></div>
                    </div>
                </div>
            </template>
        </div>
        <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-[#13111C] text-center">
            <span class="text-xs text-gray-400" x-show="notifications.length > 0">You're up to date</span>
        </div>
    </div>
</div>
