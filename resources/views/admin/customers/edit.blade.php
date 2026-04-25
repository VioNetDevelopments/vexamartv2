@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <div class="relative max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4 animate-fade-in-down">
            <a href="{{ route('admin.customers.index') }}" 
               class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
            </a>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <i data-lucide="edit" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-400 dark:to-blue-500 bg-clip-text text-transparent tracking-tight">
                        Edit Pelanggan
                    </h1>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Update data pelanggan dan membership</p>
                </div>
            </div>
        </div>

        <!-- Edit Form Card -->
        <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
            <!-- Form Header with Customer Info -->
            <div class="relative px-8 py-6 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center gap-6">
                    <div class="h-20 w-20 rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl">
                        <div class="h-full w-full rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white text-3xl font-black shadow-lg">
                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                        </div>
                    </div>
                    <div class="text-white">
                        <h2 class="text-2xl font-black mb-1">{{ $customer->name }}</h2>
                        <div class="flex items-center gap-2 text-white/90 text-sm">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span>Member sejak {{ $customer->created_at->format('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form id="editCustomerForm" action="{{ route('admin.customers.update', $customer) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Personal Information Section -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-500/10">
                            <i data-lucide="user" class="w-5 h-5 text-blue-500"></i>
                        </div>
                        <h3 class="text-lg font-black text-navy-900 dark:text-white">Informasi Pribadi</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                <i data-lucide="user" class="inline w-4 h-4 mr-1.5"></i>
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                                   class="w-full rounded-xl border border-slate-200 px-4 py-3.5 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                            @error('name')
                                <p class="text-xs text-danger mt-2 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                <i data-lucide="mail" class="inline w-4 h-4 mr-1.5"></i>
                                Email <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                                       class="w-full rounded-xl border border-slate-200 pl-12 pr-4 py-3.5 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                            </div>
                            @error('email')
                                <p class="text-xs text-danger mt-2 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                <i data-lucide="phone" class="inline w-4 h-4 mr-1.5"></i>
                                Telepon <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}"
                                       class="w-full rounded-xl border border-slate-200 pl-12 pr-4 py-3.5 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                            </div>
                            @error('phone')
                                <p class="text-xs text-danger mt-2 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                <i data-lucide="map-pin" class="inline w-4 h-4 mr-1.5"></i>
                                Alamat <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <textarea name="address" rows="3"
                                      class="w-full rounded-xl border border-slate-200 px-4 py-3.5 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all resize-none">{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <p class="text-xs text-danger mt-2 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Membership Section -->
                <div class="border-t border-slate-100 dark:border-white/10 pt-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-purple-500/10">
                            <i data-lucide="crown" class="w-5 h-5 text-purple-500"></i>
                        </div>
                        <h3 class="text-lg font-black text-navy-900 dark:text-white">Membership & Loyalty</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Membership Level -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                <i data-lucide="award" class="inline w-4 h-4 mr-1.5"></i>
                                Membership Level <span class="text-danger">*</span>
                            </label>
                            
                            <!-- Custom Select with Alpine.js -->
                            <div class="relative" x-data="{ open: false, selected: '{{ old('membership', $customer->membership) }}' }">
                                <input type="hidden" name="membership" :value="selected">
                                
                                <button type="button" @click="open = !open" @click.away="open = false"
                                        class="w-full flex items-center justify-between px-4 py-3.5 rounded-xl border border-slate-200 bg-white dark:bg-navy-800 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:border-white/10 transition-all">
                                    <div class="flex items-center gap-2">
                                        <template x-if="selected === 'regular'">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="user" class="w-4 h-4 text-slate-500"></i>
                                                <span class="dark:text-white">Regular</span>
                                            </div>
                                        </template>
                                        <template x-if="selected === 'gold'">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="star" class="w-4 h-4 text-yellow-500"></i>
                                                <span class="dark:text-white">Gold Member</span>
                                            </div>
                                        </template>
                                        <template x-if="selected === 'platinum'">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="crown" class="w-4 h-4 text-purple-500"></i>
                                                <span class="dark:text-white">Platinum Member</span>
                                            </div>
                                        </template>
                                    </div>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="{'rotate-180': open}"></i>
                                </button>
                                
                                <div x-show="open" x-transition
                                     class="absolute z-50 mt-2 w-full rounded-xl bg-white dark:bg-navy-900 border border-slate-200 dark:border-white/10 shadow-lg overflow-hidden">
                                    <button type="button" @click="selected = 'regular'; open = false"
                                            class="w-full flex items-center gap-2 px-4 py-3 text-sm hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors dark:text-white"
                                            :class="selected === 'regular' ? 'bg-blue-50 dark:bg-blue-900/20' : ''">
                                        <i data-lucide="user" class="w-4 h-4 text-slate-500"></i>
                                        <span>Regular</span>
                                    </button>
                                    <button type="button" @click="selected = 'gold'; open = false"
                                            class="w-full flex items-center gap-2 px-4 py-3 text-sm hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors dark:text-white"
                                            :class="selected === 'gold' ? 'bg-yellow-50 dark:bg-yellow-900/20' : ''">
                                        <i data-lucide="star" class="w-4 h-4 text-yellow-500"></i>
                                        <span>Gold Member</span>
                                    </button>
                                    <button type="button" @click="selected = 'platinum'; open = false"
                                            class="w-full flex items-center gap-2 px-4 py-3 text-sm hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors dark:text-white"
                                            :class="selected === 'platinum' ? 'bg-purple-50 dark:bg-purple-900/20' : ''">
                                        <i data-lucide="crown" class="w-4 h-4 text-purple-500"></i>
                                        <span>Platinum Member</span>
                                    </button>
                                </div>
                            </div>
                            
                            @error('membership')
                                <p class="text-xs text-danger mt-2 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Loyalty Points (Read Only) -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                <i data-lucide="gift" class="inline w-4 h-4 mr-1.5"></i>
                                Poin Loyalty
                            </label>
                            <div class="flex items-center gap-4 px-4 py-3.5 rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-900/10 border border-purple-200 dark:border-purple-800">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-500/20">
                                    <i data-lucide="gift" class="w-5 h-5 text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="text-2xl font-black text-purple-600 dark:text-purple-400">{{ number_format($customer->loyalty_points) }}</span>
                                    <span class="text-sm font-medium text-purple-600/70 dark:text-purple-400/70 ml-1">poin</span>
                                </div>
                                <i data-lucide="lock" class="w-5 h-5 text-purple-400"></i>
                            </div>
                            <p class="text-xs font-medium text-slate-400 mt-2 flex items-center gap-1">
                                <i data-lucide="info" class="w-3 h-3"></i>
                                Poin otomatis bertambah saat transaksi
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-4 pt-6 border-t border-slate-100 dark:border-white/10">
                    <a href="{{ route('admin.customers.index') }}" 
                       class="flex-1 px-6 py-4 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold text-center hover:bg-slate-50 dark:hover:bg-navy-800 transition-all duration-200">
                        <i data-lucide="x" class="inline h-5 w-5 mr-2"></i>
                        Batal
                    </a>
                    <button type="button" onclick="confirmSave()"
                            class="flex-1 px-6 py-4 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-200 hover:-translate-y-0.5">
                        <i data-lucide="save" class="inline h-5 w-5 mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Alert Modal Component -->
<x-alert-modal />

@push('scripts')
<script>
// Initialize icons
function initIcons() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
        console.log('Icons initialized');
    }
}

// Confirm save function
function confirmSave() {
    console.log('Confirm save clicked');
    
    window.dispatchEvent(new CustomEvent('alert-modal', {
        detail: {
            type: 'confirm',
            title: 'Simpan Perubahan',
            message: 'Apakah Anda yakin ingin menyimpan perubahan data pelanggan ini?',
            confirmText: 'Ya, Simpan',
            cancelText: 'Batalkan',
            onConfirm: function() {
                console.log('Confirmed! Submitting form...');
                document.getElementById('editCustomerForm').submit();
            }
        }
    }));
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing icons...');
    
    // Initialize icons immediately
    initIcons();
    
    // Re-initialize icons after a short delay (for Alpine rendering)
    setTimeout(() => {
        initIcons();
    }, 100);
    
    // Phone formatting
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
        });
    }

    // Re-initialize icons after Alpine updates
    document.addEventListener('alpine:initialized', () => {
        setTimeout(() => {
            initIcons();
        }, 200);
    });
    
    // Show session messages
    @if(session('success'))
        setTimeout(() => {
            window.dispatchEvent(new CustomEvent('alert-modal', {
                detail: {
                    type: 'success',
                    title: 'Berhasil!',
                    message: '{{ session('success') }}'
                }
            }));
        }, 100);
    @endif
    
    @if(session('error'))
        setTimeout(() => {
            window.dispatchEvent(new CustomEvent('alert-modal', {
                detail: {
                    type: 'error',
                    title: 'Gagal!',
                    message: '{{ session('error') }}'
                }
            }));
        }, 100);
    @endif
});

// Watch for DOM changes and re-initialize icons
const observer = new MutationObserver((mutations) => {
    let shouldReinit = false;
    
    mutations.forEach((mutation) => {
        if (mutation.addedNodes.length > 0) {
            shouldReinit = true;
        }
    });
    
    if (shouldReinit) {
        setTimeout(() => {
            initIcons();
        }, 50);
    }
});

// Start observing after page load
document.addEventListener('DOMContentLoaded', () => {
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
</script>
@endpush

@push('styles')
<style>
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; opacity: 0; }
.animate-fade-in-down { animation: fade-in-down 0.6s ease-out forwards; }
</style>
@endpush
@endsection