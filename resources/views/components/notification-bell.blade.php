@props(['position' => 'top-right'])

<div x-data="notificationSystem()" x-init="init()" class="relative">
    <!-- Notification Bell -->
    <button @click="toggleDropdown(); $el.classList.add('animate-wiggle'); setTimeout(() => $el.classList.remove('animate-wiggle'), 300)"
        class="relative p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-navy-800 transition-colors">
        <i data-lucide="bell" class="icon-sm text-slate-600 dark:text-slate-300"></i>

        <!-- Unread Badge -->
        <template x-if="unreadCount > 0">
            <span
                class="absolute top-1 right-1 flex items-center justify-center bg-red-500 text-white font-bold rounded-full transition-all duration-300"
                :class="unreadCount === 1 ? 'w-2 h-2' : 'w-4 h-4 text-[9px]'">
                <span x-show="unreadCount > 1" x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
            </span>
        </template>

        <!-- Pulse Animation -->
        <template x-if="hasNewNotifications">
            <span class="absolute inset-0 rounded-xl bg-red-500/10 animate-ping"></span>
        </template>
    </button>

    <!-- Dropdown -->
    <div x-show="isOpen" @click.away="closeDropdown()" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95" 
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200" 
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="absolute right-0 mt-3 w-80 sm:w-96 bg-white dark:bg-navy-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-white/10 z-[100] overflow-hidden"
        style="display: none;">

        <!-- Header -->
        <div class="px-5 py-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h3 class="text-base font-black text-navy-900 dark:text-white">Notifikasi</h3>
                <template x-if="unreadCount > 0">
                    <span class="px-2 py-0.5 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-[10px] font-black rounded-full" x-text="unreadCount"></span>
                </template>
            </div>
            <a href="{{ auth()->user()->role === 'cashier' ? route('cashier.transactions') : route('admin.transactions.index') }}" 
               class="text-xs font-bold text-blue-500 hover:text-blue-600 transition-colors">Lihat semua</a>
        </div>

        <!-- Notifications List -->
        <div class="max-h-[320px] overflow-y-auto custom-scrollbar">
            <template x-if="notifications.length === 0">
                <div class="px-5 py-10 text-center">
                    <div class="w-14 h-14 bg-slate-50 dark:bg-navy-800 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="bell-off" class="w-7 h-7 text-slate-300 dark:text-slate-600"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-900 dark:text-white">Belum ada info baru, King!</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Nanti kalau ada kabar pasti kita kasih tau.</p>
                </div>
            </template>

            <div class="divide-y divide-slate-50 dark:divide-white/5">
                <template x-for="notification in notifications" :key="notification.id">
                    <div @click="handleNotificationClick(notification)"
                        class="px-5 py-3.5 hover:bg-slate-50/80 dark:hover:bg-white/5 cursor-pointer transition-all relative group"
                        :class="notification.read_at ? 'opacity-60' : 'bg-blue-50/30 dark:bg-blue-900/10'">
                        
                        <div class="flex gap-3">
                            <!-- Icon Logic -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm"
                                    :class="{
                                        'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400': notification.type === 'payment_received',
                                        'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400': notification.type === 'low_stock',
                                        'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400': !['payment_received', 'low_stock'].includes(notification.type)
                                    }">
                                    <i :data-lucide="notification.type === 'payment_received' ? 'check-circle' : (notification.type === 'low_stock' ? 'alert-triangle' : 'info')" 
                                       class="w-4 h-4"></i>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-0.5">
                                    <p class="text-sm font-black text-navy-900 dark:text-white leading-tight" x-text="notification.title"></p>
                                    <span class="text-[9px] font-medium text-slate-400 dark:text-slate-500 whitespace-nowrap" x-text="formatTime(notification.created_at)"></span>
                                </div>
                                <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-tight line-clamp-2" x-text="notification.message"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-3 bg-slate-50/50 dark:bg-navy-950/50 border-t border-slate-100 dark:border-white/5">
            <button @click="markAllAsRead()" 
                    class="w-full py-2 px-4 bg-white dark:bg-navy-800 text-slate-700 dark:text-slate-200 text-[11px] font-black rounded-xl border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-navy-700 transition-all shadow-sm flex items-center justify-center gap-2 group active:scale-[0.98]">
                <i data-lucide="check-check" class="w-3.5 h-3.5 text-slate-400 group-hover:text-blue-500 transition-colors"></i>
                Tandai Sudah Dibaca
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function notificationSystem() {
            return {
                isOpen: false,
                notifications: [],
                unreadCount: 0,
                hasNewNotifications: false,
                lastCheck: null,

                init() {
                    this.fetchNotifications();
                    // Polling every 10 seconds
                    setInterval(() => this.fetchNotifications(), 10000);
                    
                    // Listen for instant refresh events
                    window.addEventListener('refresh-notifications', () => {
                        this.fetchNotifications();
                    });

                    lucide.createIcons();
                },

                toggleDropdown() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen) {
                        this.hasNewNotifications = false;
                    }
                },

                closeDropdown() {
                    this.isOpen = false;
                },

                fetchNotifications() {
                    fetch('/cashier/notifications', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        }
                    })
                        .then(r => r.json())
                        .then(data => {
                            const unreadNotifications = data.notifications.filter(n => !n.read_at);
                            
                            // Get the highest ID from current unread notifications
                            const latestId = unreadNotifications.length > 0 
                                ? Math.max(...unreadNotifications.map(n => n.id)) 
                                : 0;

                            // If we have a new notification with a higher ID than before, play sound
                            if (latestId > (this.lastNotificationId || 0) && this.lastNotificationId !== undefined) {
                                this.hasNewNotifications = true;
                                this.playNotificationSound();
                            }

                            this.notifications = data.notifications;
                            this.unreadCount = data.unread_count;
                            
                            // Update our tracker
                            this.lastNotificationId = latestId;
                            
                            setTimeout(() => lucide.createIcons(), 100);
                        });
                },

                markAsRead(id) {
                    return fetch('/cashier/notifications/read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify({ id: id })
                    })
                        .then(() => {
                            this.fetchNotifications();
                        });
                },

                handleNotificationClick(notification) {
                    // Mark as read first
                    this.markAsRead(notification.id);

                    // Determine where to go
                    let url = '#';
                    const isAdmin = '{{ auth()->user()->role }}' !== 'cashier';

                    switch (notification.type) {
                        case 'payment_received':
                            if (notification.data && notification.data.transaction_id) {
                                url = isAdmin 
                                    ? `/admin/transactions/${notification.data.transaction_id}`
                                    : `/cashier/transactions/${notification.data.transaction_id}`;
                            } else {
                                url = isAdmin ? '{{ route('admin.transactions.index') }}' : '{{ route('cashier.transactions') }}';
                            }
                            break;
                        case 'low_stock':
                            if (notification.data && notification.data.product_id) {
                                url = `/admin/products/${notification.data.product_id}/edit`;
                            } else {
                                url = '{{ route('admin.stock.index') }}';
                            }
                            break;
                        case 'product_updated':
                        case 'product_created':
                            if (notification.data && notification.data.product_id) {
                                url = `/admin/products/${notification.data.product_id}/edit`;
                            } else {
                                url = '{{ route('admin.products.index') }}';
                            }
                            break;
                        case 'product_deleted':
                            url = '{{ route('admin.products.index') }}';
                            break;
                    }

                    if (url !== '#') {
                        window.location.href = url;
                    }
                },

                markAllAsRead() {
                    const unreadIds = this.notifications.filter(n => !n.read_at).map(n => n.id);

                    if (unreadIds.length === 0) {
                        this.isOpen = false;
                        return;
                    }

                    // Optimistic Update: Update UI immediately
                    this.unreadCount = 0;
                    this.notifications.forEach(n => {
                        if (!n.read_at) n.read_at = new Date().toISOString();
                    });
                    this.hasNewNotifications = false;

                    // Close immediately for snappy feel
                    this.isOpen = false;

                    Promise.all(unreadIds.map(id =>
                        fetch('/cashier/notifications/read', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: JSON.stringify({ id: id })
                        })
                    ))
                        .then(() => {
                            // Silently sync with server
                            this.fetchNotifications();
                        });
                },

                formatTime(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diff = now - date;
                    const minutes = Math.floor(diff / 60000);
                    const hours = Math.floor(diff / 3600000);
                    const days = Math.floor(diff / 86400000);

                    if (minutes < 1) return 'Baru saja';
                    if (minutes < 60) return `${minutes} menit yang lalu`;
                    if (hours < 24) return `${hours} jam yang lalu`;
                    if (days < 7) return `${days} hari yang lalu`;
                    return date.toLocaleDateString('id-ID');
                },

                playNotificationSound() {
                    // Create a simple beep sound using Web Audio API
                    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();

                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);

                    oscillator.frequency.value = 800;
                    oscillator.type = 'sine';

                    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.5);
                }
            }
        }
    </script>
@endpush