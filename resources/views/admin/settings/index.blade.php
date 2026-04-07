@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Pengaturan Toko</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Konfigurasi sistem dan preferensi toko</p>
    </div>

    @if(session('success'))
    <div class="rounded-xl bg-success/10 border border-success/20 p-4 flex items-center gap-3">
        <i data-lucide="check-circle" class="h-5 w-5 text-success"></i>
        <span class="text-success">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Store Identity -->
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Identitas Toko</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Toko *</label>
                    <input type="text" name="store_name" value="{{ $settings['store_name']->value ?? 'VexaMart' }}" required
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Logo Toko</label>
                    <input type="file" name="logo" accept="image/*"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                    @if($settings['store_logo']->value ?? false)
                    <img src="{{ asset('storage/'.$settings['store_logo']->value) }}" class="mt-2 h-16 rounded-lg">
                    @endif
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alamat Toko</label>
                    <textarea name="store_address" rows="2" class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">{{ $settings['store_address']->value ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Telepon</label>
                    <input type="text" name="store_phone" value="{{ $settings['store_phone']->value ?? '' }}"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                    <input type="email" name="store_email" value="{{ $settings['store_email']->value ?? '' }}"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                </div>
            </div>
        </div>

        <!-- Financial Settings -->
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Pengaturan Keuangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mata Uang</label>
                    <input type="text" name="currency" value="{{ $settings['currency']->value ?? 'Rp' }}" maxlength="10"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pajak (%)</label>
                    <input type="number" name="tax_rate" value="{{ $settings['tax_rate']->value ?? 0 }}" min="0" max="100" step="0.01"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                </div>
            </div>
        </div>

        <!-- Receipt Settings -->
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Template Struk</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Header Struk</label>
                    <textarea name="receipt_header" rows="3" placeholder="Teks yang muncul di bagian atas struk"
                              class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">{{ $settings['receipt_header']->value ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Footer Struk</label>
                    <textarea name="receipt_footer" rows="3" placeholder="Teks yang muncul di bagian bawah struk"
                              class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">{{ $settings['receipt_footer']->value ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Backup Section -->
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Backup Database</h3>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-navy-900 dark:text-white">Download Backup</p>
                    <p class="text-sm text-slate-500">Backup database MySQL dalam format .sql</p>
                </div>
                <a href="{{ route('admin.settings.backup') }}" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-200 dark:bg-navy-800 dark:text-slate-300">
                    <i data-lucide="download" class="inline h-4 w-4 mr-2"></i> Download
                </a>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-accent-500 px-6 py-2.5 text-sm font-medium text-white shadow-lg shadow-accent-500/30 hover:bg-accent-600">
                <i data-lucide="save" class="inline h-4 w-4 mr-2"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection