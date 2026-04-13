@props([
    'type' => 'info',
    'title' => '',
    'message' => '',
    'show' => false,
    'confirmText' => 'Ya, Hapus',
    'cancelText' => 'Batalkan',
    'onConfirm' => null,
    'onCancel' => null
])

<div x-data="alertModal({ 
    show: {{ $show ? 'true' : 'false' }},
    type: '{{ $type }}',
    onConfirm: {{ $onConfirm ? '() => {' . $onConfirm . '}' : 'null' }},
    onCancel: {{ $onCancel ? '() => {' . $onCancel . '}' : 'null' }}
})"
     x-show="show"
     x-cloak
     @alert-modal.window="showModal($event.detail)"
     class="fixed inset-0 z-[100] flex items-center justify-center p-4">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="if(type === 'confirm') cancel()"></div>
    
    <!-- Modal Box - FIXED: More top padding -->
    <div class="relative bg-white dark:bg-navy-900 rounded-3xl max-w-md w-full shadow-2xl overflow-hidden"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4">
        
        <!-- Header with Icon - FIXED: More padding -->
        <div class="relative px-8 pt-20 pb-6" :class="{
            'bg-gradient-to-br from-success/10 via-success/5 to-transparent': type === 'success',
            'bg-gradient-to-br from-danger/10 via-danger/5 to-transparent': type === 'error' || type === 'confirm',
            'bg-gradient-to-br from-warning/10 via-warning/5 to-transparent': type === 'warning',
            'bg-gradient-to-br from-blue-500/10 via-blue-500/5 to-transparent': type === 'info'
        }">
            <!-- Icon - FIXED: Less negative margin -->
            <div class="flex justify-center -mt-16 mb-6">
                <div class="relative">
                    <!-- Pulse Ring -->
                    <div class="absolute inset-0 animate-ping opacity-20 rounded-full" :class="{
                        'bg-success': type === 'success',
                        'bg-danger': type === 'error' || type === 'confirm',
                        'bg-warning': type === 'warning',
                        'bg-blue-500': type === 'info'
                    }" style="animation-duration: 2s;"></div>
                    
                    <!-- Icon Circle - FIXED: Smaller size -->
                    <div class="relative w-20 h-20 rounded-full flex items-center justify-center shadow-2xl" :class="{
                        'bg-gradient-to-br from-success to-success/80': type === 'success',
                        'bg-gradient-to-br from-danger to-danger/80': type === 'error' || type === 'confirm',
                        'bg-gradient-to-br from-warning to-warning/80': type === 'warning',
                        'bg-gradient-to-br from-blue-500 to-blue-600': type === 'info'
                    }">
                        <template x-if="type === 'success'">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </template>
                        <template x-if="type === 'error'">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </template>
                        <template x-if="type === 'warning'">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </template>
                        <template x-if="type === 'info'">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </template>
                        <template x-if="type === 'confirm'">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Title & Message -->
            <div class="text-center">
                <h3 class="text-2xl font-black text-navy-900 dark:text-white mb-3" x-text="title"></h3>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 leading-relaxed px-4" x-text="message"></p>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="p-6 pt-0 flex gap-3">
            <template x-if="type === 'confirm'">
                <div class="flex gap-3 w-full">
                    <button @click="cancel()" 
                            class="flex-1 px-6 py-3.5 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all duration-200 active:scale-95"
                            x-text="cancelText">
                    </button>
                    <button @click="confirm()" 
                            class="flex-1 px-6 py-3.5 rounded-xl bg-gradient-to-r from-danger to-danger/80 text-white font-bold shadow-lg shadow-danger/30 hover:shadow-danger/50 transition-all duration-200 active:scale-95 hover:-translate-y-0.5"
                            x-text="confirmText">
                    </button>
                </div>
            </template>
            <template x-if="type !== 'confirm'">
                <button @click="close()" 
                        class="flex-1 px-6 py-3.5 rounded-xl bg-gradient-to-r from-accent-500 to-accent-600 text-white font-bold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all duration-200 active:scale-95 hover:-translate-y-0.5">
                    OK
                </button>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
function alertModal(config = {}) {
    return {
        show: false,
        type: 'info',
        title: '',
        message: '',
        confirmText: 'Ya, Hapus',
        cancelText: 'Batalkan',
        onConfirm: null,
        onCancel: null,
        
        init() {
            this.show = config.show || false;
            this.type = config.type || 'info';
            this.title = config.title || '';
            this.message = config.message || '';
            this.confirmText = config.confirmText || 'Ya, Hapus';
            this.cancelText = config.cancelText || 'Batalkan';
            this.onConfirm = config.onConfirm || null;
            this.onCancel = config.onCancel || null;
        },
        
        showModal(detail) {
            this.type = detail.type || 'info';
            this.title = detail.title || '';
            this.message = detail.message || '';
            this.confirmText = detail.confirmText || 'Ya, Hapus';
            this.cancelText = detail.cancelText || 'Batalkan';
            this.onConfirm = detail.onConfirm || null;
            this.onCancel = detail.onCancel || null;
            this.show = true;
        },
        
        confirm() {
            if (this.onConfirm) {
                this.onConfirm();
            }
            this.show = false;
        },
        
        cancel() {
            if (this.onCancel) {
                this.onCancel();
            }
            this.show = false;
        },
        
        close() {
            this.show = false;
        }
    }
}
</script>
@endpush

@push('styles')
<style>
[x-cloak] { display: none !important; }
</style>
@endpush