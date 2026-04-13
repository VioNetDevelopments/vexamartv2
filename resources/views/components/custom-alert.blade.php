@props([
    'type' => 'info',
    'title' => '',
    'message' => '',
    'show' => false,
    'confirmText' => 'Ya, Hapus',
    'cancelText' => 'Batalkan',
    'onConfirm' => '',
    'onCancel' => ''
])

<div x-data="customAlert({ 
    show: {{ $show ? 'true' : 'false' }},
    type: '{{ $type }}',
    onConfirm: {{ $onConfirm ?: 'null' }},
    onCancel: {{ $onCancel ?: 'null' }}
})"
     x-show="show"
     x-cloak
     @custom-alert.window="showAlert($event.detail)"
     class="fixed inset-0 z-[100] flex items-center justify-center p-4">
    
    <!-- Backdrop with Blur -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="if(type === 'confirm') cancel()"></div>
    
    <!-- Alert Box -->
    <div class="relative bg-white dark:bg-navy-900 rounded-3xl p-0 max-w-md w-full shadow-2xl overflow-hidden border border-white/20 dark:border-white/5"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4">
        
        <!-- Header with Single Icon -->
        <div class="relative p-8 pb-0" :class="{
            'bg-gradient-to-br from-success/10 via-success/5 to-transparent': type === 'success',
            'bg-gradient-to-br from-danger/10 via-danger/5 to-transparent': type === 'error' || type === 'confirm',
            'bg-gradient-to-br from-warning/10 via-warning/5 to-transparent': type === 'warning',
            'bg-gradient-to-br from-blue-500/10 via-blue-500/5 to-transparent': type === 'info'
        }">
            <!-- Single Animated Icon -->
            <div class="flex justify-center -mt-16 mb-4">
                <div class="relative">
                    <!-- Pulse Ring -->
                    <div class="absolute inset-0 animate-ping opacity-20" :class="{
                        'bg-success': type === 'success',
                        'bg-danger': type === 'error' || type === 'confirm',
                        'bg-warning': type === 'warning',
                        'bg-blue-500': type === 'info'
                    }" style="border-radius: 50%; animation-duration: 2s;"></div>
                    
                    <!-- Icon Circle with Single Icon -->
                    <div class="relative w-24 h-24 rounded-full flex items-center justify-center shadow-2xl" :class="{
                        'bg-gradient-to-br from-success to-success/80': type === 'success',
                        'bg-gradient-to-br from-danger to-danger/80': type === 'error' || type === 'confirm',
                        'bg-gradient-to-br from-warning to-warning/80': type === 'warning',
                        'bg-gradient-to-br from-blue-500 to-blue-600': type === 'info'
                    }">
                        <template x-if="type === 'success'">
                            <i data-lucide="check" class="w-12 h-12 text-white"></i>
                        </template>
                        <template x-if="type === 'error'">
                            <i data-lucide="x" class="w-12 h-12 text-white"></i>
                        </template>
                        <template x-if="type === 'warning'">
                            <i data-lucide="alert-triangle" class="w-12 h-12 text-white"></i>
                        </template>
                        <template x-if="type === 'info'">
                            <i data-lucide="info" class="w-12 h-12 text-white"></i>
                        </template>
                        <template x-if="type === 'confirm'">
                            <i data-lucide="alert-circle" class="w-12 h-12 text-white"></i>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Title & Message -->
            <div class="text-center mb-2">
                <h3 class="text-2xl font-black text-navy-900 dark:text-white mb-3" x-text="title"></h3>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 leading-relaxed" x-text="message"></p>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="p-6 pt-2 flex gap-3">
            @if($type === 'confirm')
                <button @click="cancel()" 
                        class="flex-1 px-6 py-3.5 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all duration-200 active:scale-95">
                    {{ $cancelText }}
                </button>
                <button @click="confirm()" 
                        class="flex-1 px-6 py-3.5 rounded-xl bg-gradient-to-r from-danger to-danger/80 text-white font-bold shadow-lg shadow-danger/30 hover:shadow-danger/50 transition-all duration-200 active:scale-95 hover:-translate-y-0.5">
                    {{ $confirmText }}
                </button>
            @else
                <button @click="close()" 
                        class="flex-1 px-6 py-3.5 rounded-xl bg-gradient-to-r from-accent-500 to-accent-600 text-white font-bold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all duration-200 active:scale-95 hover:-translate-y-0.5">
                    OK
                </button>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function customAlert(config = {}) {
    return {
        show: false,
        type: 'info',
        title: '',
        message: '',
        onConfirm: null,
        onCancel: null,
        
        init() {
            this.show = config.show || false;
            this.type = config.type || 'info';
            this.onConfirm = config.onConfirm || null;
            this.onCancel = config.onCancel || null;
        },
        
        showAlert(detail) {
            this.type = detail.type || 'info';
            this.title = detail.title || '';
            this.message = detail.message || '';
            this.onConfirm = detail.onConfirm || null;
            this.onCancel = detail.onCancel || null;
            this.show = true;
            
            setTimeout(() => lucide.createIcons(), 100);
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