@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 animate-fade-in-down mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                <i data-lucide="settings" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">Konfigurasi Sistem</h1>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Atur parameter dan identitas operasional toko Anda</p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest bg-slate-50 dark:bg-navy-900/50 px-4 py-2 rounded-full border border-slate-200/50 dark:border-white/5">
            <span class="h-2 w-2 rounded-full bg-success animate-pulse"></span>
            Server: Online
        </div>
    </div>

    @if(session('success'))
    <div class="rounded-[1.5rem] bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 p-5 flex items-center gap-4 animate-fade-in-down">
        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
            <i data-lucide="check-circle-2" class="h-5 w-5"></i>
        </div>
        <div>
            <p class="text-sm font-black text-emerald-800 dark:text-emerald-400 leading-none">Berhasil Diperbarui!</p>
            <p class="text-[11px] font-bold text-emerald-600 dark:text-emerald-500/60 mt-1 uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Store Identity Card -->
        <div class="group bg-white dark:bg-navy-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5 overflow-hidden transition-all duration-500 hover:shadow-2xl">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-accent-50 dark:bg-accent-500/10 text-accent-500 flex items-center justify-center">
                    <i data-lucide="store" class="w-5 h-5"></i>
                </div>
                <h3 class="text-xl font-black text-navy-900 dark:text-white tracking-tight">Identitas Bisnis</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nama Brand / Toko</label>
                    <div class="relative group/input">
                        <i data-lucide="tag" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within/input:text-accent-500 transition-colors"></i>
                        <input type="text" name="store_name" value="{{ $settings['store_name']->value ?? 'VexaMart' }}" required
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Logo Visual</label>
                    <div class="flex items-center gap-4">
                        <div class="relative group/logo">
                            @if($settings['store_logo']->value ?? false)
                            <img src="{{ asset('storage/'.$settings['store_logo']->value) }}" class="h-14 w-14 rounded-2xl object-cover border-2 border-slate-100 dark:border-white/5 shadow-md">
                            @else
                            <div class="h-14 w-14 rounded-2xl bg-slate-100 dark:bg-navy-800 flex items-center justify-center text-slate-400 border-2 border-dashed border-slate-200 dark:border-white/5">
                                <i data-lucide="image" class="w-6 h-6"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 relative">
                            <input type="file" name="logo" accept="image/*" id="logo_input" class="hidden">
                            <label for="logo_input" class="flex items-center justify-center px-6 py-3.5 rounded-2xl border-2 border-dashed border-slate-200 dark:border-white/5 text-xs font-black uppercase tracking-widest text-slate-500 hover:border-accent-500 hover:text-accent-500 dark:hover:border-accent-500 cursor-pointer transition-all active:scale-95">
                                <span>Pilih Gambar</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Alamat Operasional Terdaftar</label>
                    <div class="relative group/input">
                        <i data-lucide="map-pin" class="absolute left-4 top-4 w-4 h-4 text-slate-400 group-focus-within/input:text-accent-500 transition-colors"></i>
                        <textarea name="store_address" rows="3" 
                                  class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner resize-none">{{ $settings['store_address']->value ?? '' }}</textarea>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nomor Kontak Toko</label>
                    <div class="relative group/input">
                        <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within/input:text-accent-500 transition-colors"></i>
                        <input type="text" name="store_phone" value="{{ $settings['store_phone']->value ?? '' }}"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Alamat Email Bisnis</label>
                    <div class="relative group/input">
                        <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within/input:text-accent-500 transition-colors"></i>
                        <input type="email" name="store_email" value="{{ $settings['store_email']->value ?? '' }}"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner">
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial & Localization Card -->
        <div class="bg-white dark:bg-navy-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5 transition-all duration-500 hover:shadow-2xl">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-success/10 text-success flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
                <h3 class="text-xl font-black text-navy-900 dark:text-white tracking-tight">Finansial & Lokalisasi</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Simbol Mata Uang</label>
                    <div class="relative group/input">
                        <i data-lucide="dollar-sign" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within/input:text-accent-500 transition-colors"></i>
                        <input type="text" name="currency" value="{{ $settings['currency']->value ?? 'Rp' }}" maxlength="10"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Tarif Pajak (%)</label>
                    <div class="relative group/input">
                        <i data-lucide="percent" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within/input:text-accent-500 transition-colors"></i>
                        <input type="number" name="tax_rate" value="{{ $settings['tax_rate']->value ?? 0 }}" min="0" max="100" step="0.01"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner">
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Customization Card -->
        <div class="bg-white dark:bg-navy-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5 transition-all duration-500 hover:shadow-2xl">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center">
                    <i data-lucide="printer" class="w-5 h-5"></i>
                </div>
                <h3 class="text-xl font-black text-navy-900 dark:text-white tracking-tight">Kustomisasi Struk</h3>
            </div>
            
            <div class="space-y-6">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Teks Header Struk</label>
                    <div class="relative group/input">
                        <i data-lucide="heading" class="absolute left-4 top-4 w-4 h-4 text-slate-400 group-focus-within/input:text-accent-500 transition-colors"></i>
                        <textarea name="receipt_header" rows="3" placeholder="Contoh: Selamat Datang di VexaMart"
                                  class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner resize-none">{{ $settings['receipt_header']->value ?? '' }}</textarea>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Teks Footer Struk</label>
                    <div class="relative group/input">
                        <i data-lucide="info" class="absolute left-4 top-4 w-4 h-4 text-slate-400 group-focus-within/input:text-accent-500 transition-colors"></i>
                        <textarea name="receipt_footer" rows="3" placeholder="Contoh: Barang yang sudah dibeli tidak dapat ditukar"
                                  class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner resize-none">{{ $settings['receipt_footer']->value ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone / Maintenance Card -->
        <div class="bg-white dark:bg-navy-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5 transition-all duration-500 hover:shadow-2xl">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-slate-500/10 text-slate-500 flex items-center justify-center">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </div>
                <h3 class="text-xl font-black text-navy-900 dark:text-white tracking-tight">Pencadangan Data</h3>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 p-6 rounded-3xl bg-slate-50 dark:bg-navy-800/50 border border-slate-100 dark:border-white/5">
                <div class="flex items-center gap-4 text-center sm:text-left">
                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-navy-900 flex items-center justify-center shadow-sm">
                        <i data-lucide="archive" class="w-6 h-6 text-slate-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-navy-900 dark:text-white">Arsip Database Lengkap</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Format Ekspor: MySQL (.sql)</p>
                    </div>
                </div>
                <a href="{{ route('admin.settings.backup') }}" 
                   class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-3 rounded-2xl bg-navy-950 dark:bg-white text-white dark:text-navy-950 text-xs font-black uppercase tracking-widest hover:shadow-lg transition-all active:scale-95">
                    <i data-lucide="download-cloud" class="w-4 h-4"></i>
                    <span>Download Backup</span>
                </a>
            </div>
        </div>

        <!-- Footer Action -->
        <div class="flex justify-end pt-4">
            <button type="submit" 
                    class="group relative flex items-center gap-3 px-10 py-5 rounded-[2rem] bg-accent-500 text-white font-black uppercase tracking-widest text-sm shadow-2xl shadow-accent-500/40 hover:bg-accent-600 hover:-translate-y-1.5 transition-all duration-300 active:scale-95 active:translate-y-0">
                <i data-lucide="save" class="w-5 h-5 transition-transform group-hover:rotate-12"></i>
                <span>Simpan Seluruh Perubahan</span>
                <div class="absolute inset-x-4 top-0 h-[1px] bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
            </button>
        </div>
    </form>
</div>
@endsection