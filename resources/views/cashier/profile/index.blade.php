@extends('layouts.app')

@section('page-title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 py-8"
     x-data="profilePage({ name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->email) }}' })">
    <div class="max-w-6xl mx-auto px-6">
        <!-- Back Button -->
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 group mb-6 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-all">
            <div class="w-9 h-9 rounded-xl bg-white dark:bg-navy-900 border border-slate-200 dark:border-white/10 flex items-center justify-center shadow-sm group-hover:shadow transition-all group-hover:-translate-x-1">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </div>
            <span class="text-sm font-bold">Kembali</span>
        </a>

        <!-- Header -->
        <div class="mb-8 animate-fade-in-down">
            <h1 class="text-3xl font-black gradient-text mb-2">Profil Saya</h1>
            <p class="text-slate-500">Kelola informasi akun dan pengaturan</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-3 animate-fade-in-up">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Sidebar - Profile Card -->
            <div class="lg:col-span-4 xl:col-span-4">
                <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl p-6 sticky top-24 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <!-- Avatar with AJAX Preview -->
                    <div class="text-center mb-6" x-data="{ 
                        previewUrl: null,
                        handleFileSelect(event) {
                            const file = event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.previewUrl = e.target.result;
                                };
                                reader.readAsDataURL(file);
                            }
                        }
                    }">
                        <div class="relative inline-block group">
                            <div class="w-40 h-40 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 p-1 shadow-2xl mx-auto animate-pulse-slow">
                                <div class="w-full h-full rounded-full bg-slate-100 dark:bg-navy-800 overflow-hidden">
                                    <img :src="previewUrl || ({{ $user->avatar ? "'".asset('storage/'.$user->avatar)."'" : 'null' }})" 
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="w-full h-full items-center justify-center text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-blue-600 to-blue-700"
                                         style="display: {{ $user->avatar ? 'none' : 'flex' }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </div>
                            </div>
                            <label class="absolute bottom-1 right-1 w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white cursor-pointer hover:scale-110 hover:shadow-lg hover:shadow-blue-500/50 transition-all duration-300">
                                <i data-lucide="camera" class="w-5 h-5"></i>
                                <input type="file" name="avatar" accept="image/*" class="hidden" @change="handleFileSelect">
                            </label>
                        </div>
                        
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mt-5" x-text="userName">{{ $user->name }}</h2>
                        <p class="text-sm font-medium text-slate-500 capitalize">{{ $user->role }}</p>
                        <p class="text-xs text-slate-400 mt-2" x-text="userEmail">{{ $user->email }}</p>
                    </div>

                    <!-- Quick Info -->
                    <div class="space-y-3 pt-6 border-t border-slate-200 dark:border-white/10">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-navy-800/50 hover:bg-slate-100 dark:hover:bg-navy-800 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                <i data-lucide="mail" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-black text-slate-400 uppercase">Email</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300 truncate" x-text="userEmail">{{ $user->email }}</p>
                            </div>
                        </div>
                        
                        @if($user->phone)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-navy-800/50 hover:bg-slate-100 dark:hover:bg-navy-800 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                    <i data-lucide="phone" class="w-5 h-5 text-green-600"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] font-black text-slate-400 uppercase">Telepon</p>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $user->phone }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-navy-800/50 hover:bg-slate-100 dark:hover:bg-navy-800 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                                <i data-lucide="calendar" class="w-5 h-5 text-purple-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-black text-slate-400 uppercase">Bergabung</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Cashier Stats -->
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-6 border-t border-slate-200 dark:border-white/10">
                        <div class="text-center p-4 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/10 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-1">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-500/20 mx-auto mb-2">
                                <i data-lucide="shopping-cart" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <p class="text-3xl font-black text-blue-600" x-text="{{ $stats['total_transactions'] }}">{{ $stats['total_transactions'] }}</p>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mt-1">Transaksi</p>
                        </div>
                        <div class="text-center p-4 rounded-2xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/10 hover:shadow-lg hover:shadow-green-500/20 transition-all duration-300 hover:-translate-y-1">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-green-500/20 mx-auto mb-2">
                                <i data-lucide="trending-up" class="w-5 h-5 text-green-600"></i>
                            </div>
                            <p class="text-2xl font-black text-green-600">
                                <span x-text="formatSales({{ $stats['total_sales'] ?: 0 }})">{{ $stats['total_sales'] > 0 ? 'Rp ' . number_format($stats['total_sales'] / 1000000, 1) . ' JT' : '0' }}</span>
                            </p>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mt-1">Total Penjualan</p>
                        </div>
                    </div>

                    <!-- Shift Stats -->
                    @php
                        $activeShift = \App\Models\CashierShift::where('user_id', $user->id)->where('status', 'open')->first();
                        $completedShifts = \App\Models\CashierShift::where('user_id', $user->id)->where('status', 'closed')->count();
                    @endphp
                    <div class="mt-4 p-4 rounded-2xl bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-900/10 border border-orange-200 dark:border-orange-800">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black text-orange-600 uppercase">Status Shift</span>
                            @if($activeShift)
                                <span class="flex items-center gap-1 text-xs font-bold text-green-600">
                                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="text-xs font-bold text-slate-500">Tidak Aktif</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Shift Selesai</span>
                            <span class="text-lg font-black text-orange-600">{{ $completedShifts }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content -->
            <div class="lg:col-span-8 xl:col-span-8">
                <form id="mainForm" @submit.prevent="submitChanges" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Edit Profile Form -->
                    <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl p-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i data-lucide="user" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 dark:text-white">Edit Profil</h3>
                                <p class="text-sm text-slate-500">Update informasi akun Anda</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap *</label>
                                <div class="relative">
                                    <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                    <input type="text" name="name" :value="userName" required
                                           class="w-full rounded-xl border border-slate-200 dark:border-white/10 pl-12 pr-4 py-4 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Email *</label>
                                <div class="relative">
                                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                    <input type="email" name="email" :value="userEmail" required
                                           class="w-full rounded-xl border border-slate-200 dark:border-white/10 pl-12 pr-4 py-4 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nomor Telepon</label>
                                <div class="relative">
                                    <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                           class="w-full rounded-xl border border-slate-200 dark:border-white/10 pl-12 pr-4 py-4 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Role</label>
                                <div class="relative">
                                    <i data-lucide="shield" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                    <div class="w-full rounded-xl border border-slate-200 dark:border-white/10 pl-12 pr-4 py-4 bg-slate-50 dark:bg-navy-800">
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 capitalize">{{ $user->role }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl p-8 animate-fade-in-up" style="animation-delay: 0.3s;">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-600 to-purple-700 flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <i data-lucide="lock" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 dark:text-white">Ubah Password</h3>
                                <p class="text-sm text-slate-500">Isi hanya jika Anda ingin mengubah sandi</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Password Saat Ini</label>
                                <div class="relative" x-data="{ show: false }">
                                    <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                    <input :type="show ? 'text' : 'password'" name="current_password"
                                           class="w-full rounded-xl border border-slate-200 dark:border-white/10 pl-12 pr-12 py-4 text-sm font-medium focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 outline-none">
                                        <div x-show="!show"><i data-lucide="eye" class="w-5 h-5"></i></div>
                                        <div x-show="show" x-cloak><i data-lucide="eye-off" class="w-5 h-5"></i></div>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Password Baru</label>
                                    <div class="relative" x-data="{ show: false }">
                                        <i data-lucide="key" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                        <input :type="show ? 'text' : 'password'" name="password"
                                               class="w-full rounded-xl border border-slate-200 dark:border-white/10 pl-12 pr-12 py-4 text-sm font-medium focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 outline-none">
                                            <div x-show="!show"><i data-lucide="eye" class="w-5 h-5"></i></div>
                                            <div x-show="show" x-cloak><i data-lucide="eye-off" class="w-5 h-5"></i></div>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                                    <div class="relative" x-data="{ show: false }">
                                        <i data-lucide="key" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                        <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                               class="w-full rounded-xl border border-slate-200 dark:border-white/10 pl-12 pr-12 py-4 text-sm font-medium focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 outline-none">
                                            <div x-show="!show"><i data-lucide="eye" class="w-5 h-5"></i></div>
                                            <div x-show="show" x-cloak><i data-lucide="eye-off" class="w-5 h-5"></i></div>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end gap-4 pt-6 border-t border-slate-200 dark:border-white/10">
                                <button type="button" @click="resetForm" class="px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all duration-200">
                                    <i data-lucide="rotate-ccw" class="inline w-5 h-5 mr-2"></i>
                                    Reset
                                </button>
                                <button type="submit" :disabled="loading"
                                        class="px-8 py-4 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i data-lucide="save" class="w-5 h-5"></i>
                                    <span x-text="loading ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan Perubahan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
function profilePage(initialData) {
    return {
        userName: initialData.name,
        userEmail: initialData.email,
        avatarPreview: null,
        loading: false,
        passwordLoading: false,
        showToast: false,
        toastMessage: '',
        
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.avatarPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        async submitChanges() {
            window.notify.confirm('Udah yakin mau ganti profilnya nih, KING?', async () => {
                this.loading = true;
                const form = document.getElementById('mainForm');
                const formData = new FormData(form);
                
                let profileMessage = '';
                let passwordMessage = '';
                let hasError = false;

                // 1. Update Profile Layer
                try {
                    const response = await fetch('{{ route('cashier.profile.update') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.userName = data.user.name;
                        this.userEmail = data.user.email;
                        
                        document.querySelectorAll('[data-header-name]').forEach(el => {
                            el.textContent = data.user.name;
                        });
                        document.querySelectorAll('[data-header-initials]').forEach(el => {
                            el.textContent = data.user.initials;
                        });
                        profileMessage = data.message || 'Profil udah di-update nih';
                    } else {
                        hasError = true;
                        profileMessage = data.message || 'Gagal update profil.';
                    }
                } catch (error) {
                    hasError = true;
                    profileMessage = 'Terjadi kesalahan sistem.';
                }
                
                // 2. Update Password Layer (ONLY IF current password is provided)
                if (formData.get('current_password')) {
                    try {
                        const passResponse = await fetch('{{ route('cashier.profile.password') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        const passData = await passResponse.json();
                        
                        if (passData.success) {
                            passwordMessage = passData.message || 'Password joss! Udah diganti.';
                            form.querySelector('[name="current_password"]').value = '';
                            form.querySelector('[name="password"]').value = '';
                            form.querySelector('[name="password_confirmation"]').value = '';
                        } else {
                            hasError = true;
                            window.notify.error(passData.message || 'Password saat ini salah');
                        }
                    } catch (error) {
                        hasError = true;
                        window.notify.error('Terjadi kesalahan saat mengubah password.');
                    }
                }

                this.loading = false;
                
                if (!hasError) {
                    window.location.href = "{{ url()->previous() !== url()->current() ? url()->previous() : url('/cashier/pos') }}";
                } else if (!formData.get('current_password')) {
                     window.notify.error(profileMessage);
                }
            });
        },
        
        resetForm() {
            this.userName = initialData.name;
            this.userEmail = initialData.email;
            this.avatarPreview = null;
        },
        
        formatSales(amount) {
            amount = parseFloat(amount) || 0;
            if (amount >= 1000000000) {
                return 'Rp ' + (amount / 1000000000).toFixed(1).replace('.0', '') + ' M';
            } else if (amount >= 1000000) {
                return 'Rp ' + (amount / 1000000).toFixed(1).replace('.0', '') + ' JT';
            } else if (amount >= 1000) {
                return 'Rp ' + (amount / 1000).toFixed(1).replace('.0', '') + ' K';
            }
            return 'Rp ' + amount;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush

@push('styles')
<style>
/* Hide Native Browser Password Reveal Buttons */
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear {
    display: none;
}
input::-webkit-credentials-auto-fill-button {
    visibility: hidden;
    position: absolute;
    right: 0;
}
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes pulse-slow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}
.animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; opacity: 0; }
.animate-fade-in-down { animation: fade-in-down 0.6s ease-out forwards; }
.animate-pulse-slow { animation: pulse-slow 3s ease-in-out infinite; }
</style>
@endpush
@endsection