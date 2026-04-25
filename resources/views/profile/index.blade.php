@extends('layouts.app')

@section('page-title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 py-8"
     x-data="profilePage({{ json_encode(['name' => $user->name, 'email' => $user->email, 'avatar' => $user->avatar]) }})">
    
    <!-- Success Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed top-6 right-6 z-50"
         x-cloak>
        <div class="flex items-center gap-4 p-5 rounded-2xl bg-gradient-to-r from-yellow-400 to-orange-400 text-white shadow-2xl shadow-yellow-500/30">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <i data-lucide="check" class="w-6 h-6"></i>
            </div>
            <div class="flex-1">
                <p class="font-bold text-sm">Berhasil!</p>
                <p class="text-xs opacity-90" x-text="toastMessage"></p>
            </div>
            <button @click="showToast = false" class="p-1 hover:bg-white/20 rounded-lg transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6">
        <!-- Back Button -->
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 group mb-6 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-all">
            <div class="w-9 h-9 rounded-xl bg-white dark:bg-navy-900 border border-slate-200 dark:border-white/10 flex items-center justify-center shadow-sm group-hover:shadow transition-all group-hover:-translate-x-1">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </div>
            <span class="text-sm font-bold">Kembali</span>
        </a>

        <!-- Header -->
        <div class="mb-8 animate-fade-in-down">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-2">Profil Saya</h1>
            <p class="text-slate-500 dark:text-slate-400">Kelola informasi akun dan pengaturan</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left Sidebar - Profile Card (WIDER - 4 columns) -->
            <div class="lg:col-span-4 xl:col-span-4">
                <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <!-- Avatar with AJAX Preview -->
                    <div class="text-center mb-6">
                        <div class="relative inline-block group">
                            <div class="w-40 h-40 rounded-full bg-gradient-to-br from-blue-600 via-blue-700 to-purple-600 p-1 shadow-2xl mx-auto animate-pulse-slow">
                                <div class="w-full h-full rounded-full bg-slate-100 dark:bg-navy-800 overflow-hidden">
                                    <img :src="avatarPreview || ({{ $user->avatar ? "'".asset('storage/'.$user->avatar)."'" : 'null' }})" 
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="w-full h-full items-center justify-center text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-blue-600 to-purple-600"
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
                    <div class="space-y-4 pt-6 border-t border-slate-200 dark:border-white/10">
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

                    <!-- Real Stats from Database (matches admin transactions page) -->
                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-white/10"
                         x-data="{
                             targetTx: {{ $stats['total_transactions'] }},
                             targetSales: {{ $stats['total_sales'] }},
                             targetAvg: {{ $stats['avg_per_transaction'] }},
                             animatedTx: 0,
                             animatedSales: 0,
                             animatedAvg: 0,
                             formatRp(val) {
                                 if (val >= 1000000000) return 'Rp ' + (val / 1000000000).toFixed(1) + 'M';
                                 if (val >= 1000000)    return 'Rp ' + (val / 1000000).toFixed(1) + 'Jt';
                                 if (val >= 1000)       return 'Rp ' + (val / 1000).toFixed(0) + 'k';
                                 return 'Rp ' + Math.round(val).toLocaleString('id-ID');
                             },
                             init() {
                                 const steps = 50;
                                 const stepMs = 1200 / steps;
                                 let step = 0;
                                 const timer = setInterval(() => {
                                     step++;
                                     const ease = 1 - Math.pow(1 - step / steps, 3);
                                     this.animatedTx    = Math.round(this.targetTx    * ease);
                                     this.animatedSales = Math.round(this.targetSales * ease);
                                     this.animatedAvg   = Math.round(this.targetAvg   * ease);
                                     if (step >= steps) {
                                         this.animatedTx    = this.targetTx;
                                         this.animatedSales = this.targetSales;
                                         this.animatedAvg   = this.targetAvg;
                                         clearInterval(timer);
                                     }
                                 }, stepMs);
                             }
                         }"
                         x-init="init()">

                        <!-- Total Transaksi + Total Penjualan -->
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div class="text-center p-4 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/10 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-1 cursor-default">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-500/20 mx-auto mb-2">
                                    <i data-lucide="shopping-cart" class="w-5 h-5 text-blue-600"></i>
                                </div>
                                <p class="text-3xl font-black text-blue-600" x-text="animatedTx.toLocaleString('id-ID')">{{ number_format($stats['total_transactions']) }}</p>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mt-1">Total Transaksi</p>
                            </div>
                            <div class="text-center p-4 rounded-2xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/10 hover:shadow-lg hover:shadow-green-500/20 transition-all duration-300 hover:-translate-y-1 cursor-default">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-green-500/20 mx-auto mb-2">
                                    <i data-lucide="trending-up" class="w-5 h-5 text-green-600"></i>
                                </div>
                                <p class="text-lg font-black text-green-600 leading-tight" x-text="formatRp(animatedSales)">{{ $stats['total_sales'] >= 1000000 ? 'Rp ' . number_format($stats['total_sales'] / 1000000, 1) . 'Jt' : 'Rp ' . number_format($stats['total_sales'], 0, ',', '.') }}</p>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mt-1">Total Penjualan</p>
                            </div>
                        </div>

                        <!-- Rata-rata per Transaksi -->
                        <div class="text-center p-4 rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-900/10 hover:shadow-lg hover:shadow-purple-500/20 transition-all duration-300 hover:-translate-y-1 cursor-default">
                            <div class="flex items-center justify-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-purple-500/20 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="bar-chart-2" class="w-4 h-4 text-purple-600"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-lg font-black text-purple-600 leading-tight" x-text="formatRp(animatedAvg)">{{ 'Rp ' . number_format($stats['avg_per_transaction'], 0, ',', '.') }}</p>
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Rata-rata / Transaksi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content (8 columns) -->
            <div class="lg:col-span-8 xl:col-span-8">
                <form id="mainForm" @submit.prevent="submitChanges" class="h-full flex flex-col space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Edit Profil Card -->
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

                    <!-- Change Password Card Integrated (Flex-1) -->
                    <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl p-8 animate-fade-in-up flex-1 flex flex-col" style="animation-delay: 0.3s;">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-purple-700 flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <i data-lucide="lock" class="w-5 h-5 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white">Ubah Password</h3>
                                <p class="text-xs text-slate-500">Isi hanya jika Anda ingin mengubah sandi</p>
                            </div>
                        </div>

                            <div class="space-y-6">
                                <div x-data="{ show: false }">
                                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Password Saat Ini</label>
                                    <div class="relative">
                                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                        <input :type="show ? 'text' : 'password'" name="current_password"
                                               class="w-full rounded-xl border border-slate-200 dark:border-white/10 pl-12 pr-12 py-4 text-sm font-medium focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 outline-none">
                                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            <svg x-cloak x-show="show" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div x-data="{ show: false }">
                                        <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Password Baru</label>
                                        <div class="relative">
                                            <i data-lucide="key" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                            <input :type="show ? 'text' : 'password'" name="password"
                                                   class="w-full rounded-xl border border-slate-200 dark:border-white/10 pl-12 pr-12 py-4 text-sm font-medium focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 outline-none">
                                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                <svg x-cloak x-show="show" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div x-data="{ show: false }">
                                        <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                                        <div class="relative">
                                            <i data-lucide="key" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                            <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                                   class="w-full rounded-xl border border-slate-200 dark:border-white/10 pl-12 pr-12 py-4 text-sm font-medium focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 outline-none">
                                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                <svg x-cloak x-show="show" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-white/10 mt-auto">
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
                    </form>
                </div>
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
            const form = document.getElementById('mainForm');
            const currentPassword = form.querySelector('[name="current_password"]').value;
            const newPassword = form.querySelector('[name="password"]').value;
            const confirmPassword = form.querySelector('[name="password_confirmation"]').value;
            
            if (newPassword || confirmPassword) {
                if (!currentPassword) {
                    notify.error('Masukkan password saat ini jika ingin mengubah password!');
                    return;
                }
                if (newPassword !== confirmPassword) {
                    notify.error('Password baru dan konfirmasi tidak cocok!');
                    return;
                }
            }
            
            notify.confirm('Udah yakin mau ganti profilnya nih, KING?', async () => {
                this.loading = true;
                const formData = new FormData(form);
                
                try {
                    // Update Profile First
                    let resProfile = await fetch("{{ route('profile.update') }}", {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    
                    let dataProfile = await resProfile.json();
                    
                    if (dataProfile.success) {
                        this.userName = dataProfile.user.name;
                        this.userEmail = dataProfile.user.email;
                        if (dataProfile.user.avatar_url) {
                            this.avatarPreview = dataProfile.user.avatar_url;
                            document.querySelectorAll('.header-avatar-img').forEach(el => {
                                el.src = dataProfile.user.avatar_url;
                                el.style.display = 'block';
                            });
                            document.querySelectorAll('[data-header-initials]').forEach(el => {
                                el.style.display = 'none';
                            });
                        }
                        
                        document.querySelectorAll('[data-header-name]').forEach(el => {
                            el.textContent = dataProfile.user.name;
                        });
                        document.querySelectorAll('[data-header-initials]').forEach(el => {
                            el.textContent = dataProfile.user.initials;
                        });

                        // Then update password if fields exist
                        if (currentPassword && newPassword) {
                            let resPass = await fetch("{{ route('profile.password') }}", {
                                method: 'POST',
                                body: formData,
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            });
                            let dataPass = await resPass.json();
                            
                            if (dataPass.success) {
                                window.location.href = "{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.dashboard') }}";
                            } else {
                                notify.error(dataPass.message || 'Password saat ini salah!');
                            }
                        } else {
                            window.location.href = "{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.dashboard') }}";
                        }
                    } else {
                        notify.error(dataProfile.message || 'Gagal menyimpan profil');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    notify.error('Terjadi kesalahan saat menghubungi server');
                } finally {
                    this.loading = false;
                }
            });
        },
        
        resetForm() {
            this.userName = initialData.name;
            this.userEmail = initialData.email;
            this.avatarPreview = null;
        },
        
        formatSales(amount) {
            if (amount >= 1000000) {
                return 'Rp ' + (amount / 1000000).toFixed(1) + 'M';
            } else if (amount >= 1000) {
                return 'Rp ' + (amount / 1000).toFixed(1) + 'K';
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

/* Hide Native Browser Password Reveal Buttons */
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear,
input[type="password"]::-webkit-contacts-auto-fill-button,
input[type="password"]::-webkit-credentials-auto-fill-button,
input[type="password"]::-webkit-reveal-button {
    display: none !important;
    visibility: hidden !important;
    pointer-events: none !important;
}
/* Fix Auto-fill background */
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 1000px #0f172a inset !important; /* navy-900 */
    -webkit-text-fill-color: white !important;
    transition: background-color 5000s ease-in-out 0s;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
@endpush
@endsection