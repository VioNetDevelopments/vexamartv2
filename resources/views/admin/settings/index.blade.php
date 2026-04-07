@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4 animate-fade-in-down">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent">
                    Pengaturan Toko
                </h1>
            </div>

            @if(session('success'))
                <div class="animate-fade-in-up rounded-2xl bg-success/10 border border-success/20 p-4 flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-5 w-5 text-success"></i>
                    <span class="text-success font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Store Identity -->
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                @csrf
                @method('PUT')

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="store" class="w-5 h-5 text-accent-500"></i>
                        Identitas Toko
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama Toko *</label>
                            <input type="text" name="store_name" value="{{ $settings['store_name']->value ?? 'VexaMart' }}" required
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Logo Toko</label>
                            <input type="file" name="logo" accept="image/*"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                            @if($settings['store_logo']->value ?? false)
                                <img src="{{ asset('storage/' . $settings['store_logo']->value) }}" class="mt-2 h-16 rounded-lg">
                            @endif
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Alamat Toko</label>
                            <textarea name="store_address" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">{{ $settings['store_address']->value ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Telepon</label>
                            <input type="text" name="store_phone" value="{{ $settings['store_phone']->value ?? '' }}"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                            <input type="email" name="store_email" value="{{ $settings['store_email']->value ?? '' }}"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Financial Settings -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="wallet" class="w-5 h-5 text-accent-500"></i>
                        Pengaturan Keuangan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mata Uang</label>
                            <input type="text" name="currency" value="{{ $settings['currency']->value ?? 'Rp' }}" maxlength="10"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Pajak (%)</label>
                            <input type="number" name="tax_rate" value="{{ $settings['tax_rate']->value ?? 0 }}" min="0" max="100" step="0.01"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Receipt Settings -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="receipt" class="w-5 h-5 text-accent-500"></i>
                        Template Struk
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Header Struk</label>
                            <textarea name="receipt_header" rows="3" placeholder="Teks yang muncul di bagian atas struk"
                                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">{{ $settings['receipt_header']->value ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Footer Struk</label>
                            <textarea name="receipt_footer" rows="3" placeholder="Teks yang muncul di bagian bawah struk"
                                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">{{ $settings['receipt_footer']->value ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Backup Section -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="database" class="w-5 h-5 text-accent-500"></i>
                        Backup Database
                    </h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-navy-900 dark:text-white">Download Backup</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Backup database MySQL dalam format .sql</p>
                        </div>
                        <a href="{{ route('admin.settings.backup') }}" class="rounded-xl bg-slate-100 dark:bg-navy-800 px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-navy-700 transition-colors">
                            <i data-lucide="download" class="inline h-4 w-4 mr-2"></i>Download
                        </a>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" class="rounded-xl bg-accent-500 px-6 py-3 text-sm font-medium text-white shadow-lg shadow-accent-500/30 hover:bg-accent-600 transition-colors">
                        <i data-lucide="save" class="inline h-4 w-4 mr-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

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

    @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
        </script>
    @endpush
@endsection