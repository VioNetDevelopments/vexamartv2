@props(['position' => 'top-right'])

<div x-data="notificationSystem()" x-init="init()" class="relative">
    <!-- Notification Bell -->
    <button @click="toggleDropdown()"
        class="relative p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-navy-800 transition-colors">
        <i data-lucide="bell" class="w-5 h-5 text-slate-600 dark:text-slate-300"></i>

        <!-- Unread Badge -->
        <template x-if="unreadCount > 0">
            <span
                class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">
                <span x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
            </span>
        </template>

        <!-- Pulse Animation for new notifications -->
        <template x-if="hasNewNotifications">
            <span class="absolute inset-0 rounded-xl bg-red-500/20 animate-ping"></span>
        </template>
    </button>

    <!-- Dropdown -->
    <div x-show="isOpen" @click.away="closeDropdown()" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="absolute right-0 mt-2 w-96 bg-white dark:bg-navy-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-white/10 z-50 overflow-hidden"
        style="display: none;">

        <!-- Header -->
        <div
            class="px-4 py-3 border-b border-slate-200 dark:border-white/10 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white dark:from-navy-800 dark:to-navy-900">
            <h3 class="font-bold text-slate-800 dark:text-white">Notifikasi</h3>
            <template x-if="unreadCount > 0">
                <button @click="markAllAsRead()" class="text-xs text-accent-600 hover:text-accent-700 font-medium">
                    Tandai semua dibaca
                </button>
            </template>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-8 text-center">
                    <i data-lucide="bell-off" class="w-12 h-12 text-slate-300 mx-auto mb-2"></i>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Tidak ada notifikasi</p>
                </div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <div @click="markAsRead(notification.id)"
                    class="px-4 py-3 border-b border-slate-100 dark:border-white/5 hover:bg-slate-50 dark:hover:bg-navy-800/50 cursor-pointer transition-colors"
                    :class="notification.read_at ? 'opacity-60' : 'bg-blue-50/50 dark:bg-blue-900/10'">

                    <!-- Payment Notification -->
                    <template x-if="notification.type === 'payment_received'">
                        <div class="flex gap-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 dark:text-white"
                                    x-text="notification.title"></p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5"
                                    x-text="notification.message"></p>
                                <p class="text-xs text-slate-400 mt-1" x-text="formatTime(notification.created_at)"></p>
                            </div>
                        </div>
                    </template>

                    <!-- Low Stock Notification -->
                    <template x-if="notification.type === 'low_stock'">
                        <div class="flex gap-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                                <i data-lucide="alert-triangle"
                                    class="w-5 h-5 text-yellow-600 dark:text-yellow-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 dark:text-white"
                                    x-text="notification.title"></p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5"
                                    x-text="notification.message"></p>
                                <p class="text-xs text-slate-400 mt-1" x-text="formatTime(notification.created_at)"></p>
                            </div>
                        </div>
                    </template>

                    <!-- Other Notifications -->
                    <template x-if="!['payment_received', 'low_stock'].includes(notification.type)">
                        <div class="flex gap-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 dark:text-white"
                                    x-text="notification.title"></p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5"
                                    x-text="notification.message"></p>
                                <p class="text-xs text-slate-400 mt-1" x-text="formatTime(notification.created_at)"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div
            class="px-4 py-2 border-t border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-navy-800/50 text-center">
            <a href="#" class="text-xs text-accent-600 hover:text-accent-700 font-medium">Lihat semua notifikasi</a>
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
                    // Poll every 10 seconds for new notifications
                    setInterval(() => this.fetchNotifications(), 10000);
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
                            const newNotifications = data.notifications.filter(n => !n.read_at);
                            if (newNotifications.length > 0 && this.lastCheck !== null) {
                                this.hasNewNotifications = true;
                                // Play notification sound
                                this.playNotificationSound();
                            }
                            this.notifications = data.notifications;
                            this.unreadCount = data.unread_count;
                            this.lastCheck = new Date();
                            setTimeout(() => lucide.createIcons(), 100);
                        });
                },

                markAsRead(id) {
                    fetch('/cashier/notifications/read', {
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

                markAllAsRead() {
                    const unreadIds = this.notifications.filter(n => !n.read_at).map(n => n.id);

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