@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-5xl mx-auto space-y-6">
            <!-- Header (No Back Button) -->
            <div class="flex items-center justify-between animate-fade-in-down">
                <div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent">
                        Pengaturan Toko
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">Kelola informasi toko dan pengaturan sistem</p>
                </div>
            </div>

            @if(session('success'))
                <div class="animate-fade-in-up rounded-2xl bg-success/10 border border-success/20 p-4 flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-5 w-5 text-success"></i>
                    <span class="text-success font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="animate-fade-in-up rounded-2xl bg-danger/10 border border-danger/20 p-4">
                    <ul class="text-danger text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Settings Form -->
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                @csrf
                @method('PUT')

                <!-- Company Information -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="building-2" class="w-5 h-5 text-accent-500"></i>
                        Informasi Perusahaan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama
                                Perusahaan</label>
                            <input type="text" name="company_name" value="{{ $settings['company_name'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tagline/Slogan</label>
                            <input type="text" name="store_tagline"
                                value="{{ $settings['store_tagline'] ?? 'Solusi Belanja Modern' }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                                placeholder="Contoh: Solusi Belanja Modern">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Alamat
                                Perusahaan</label>
                            <textarea name="company_address" rows="2"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">{{ $settings['company_address'] ?? '' }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Kota/Kabupaten</label>
                            <input type="text" name="company_city" value="{{ $settings['company_city'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Store Information -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="store" class="w-5 h-5 text-accent-500"></i>
                        Informasi Toko
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama
                                Toko</label>
                            <input type="text" name="store_name" value="{{ $settings['store_name'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Telepon</label>
                            <input type="text" name="store_phone" value="{{ $settings['store_phone'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                            <input type="email" name="store_email" value="{{ $settings['store_email'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mata
                                Uang</label>
                            <input type="text" name="currency" value="{{ $settings['currency'] ?? 'Rp' }}" maxlength="10"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Alamat
                                Toko</label>
                            <textarea name="store_address" rows="2"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">{{ $settings['store_address'] ?? '' }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Kota/Kecamatan</label>
                            <input type="text" name="store_city" value="{{ $settings['store_city'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Logo Upload -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="image" class="w-5 h-5 text-accent-500"></i>
                        Logo Toko
                    </h3>

                    <div class="flex items-center gap-6">
                        <div class="w-32 h-32 rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden border-2 border-dashed border-slate-300 dark:border-white/10 flex items-center justify-center"
                            id="logoPreviewContainer">
                            @if(isset($settings['store_logo']) && $settings['store_logo'])
                                <img id="logoPreview" src="{{ asset('storage/' . $settings['store_logo']) }}" alt="Logo"
                                    class="w-full h-full object-cover">
                            @else
                                <i data-lucide="image" class="w-12 h-12 text-slate-400"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Upload
                                Logo</label>
                            <input type="file" name="logo" id="logoInput" accept="image/*"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Max 2MB (JPG, PNG, WEBP)</p>
                            <div class="mt-3 flex gap-2">
                                <button type="button" onclick="resetLogo()"
                                    class="px-4 py-2 rounded-lg border border-danger/20 text-danger hover:bg-danger/5 transition-colors text-sm font-medium">
                                    <i data-lucide="trash-2" class="inline h-4 w-4 mr-1"></i>
                                    Reset ke Logo Default
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Receipt Settings -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="receipt" class="w-5 h-5 text-accent-500"></i>
                        Pengaturan Struk
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Footer
                                Struk</label>
                            <textarea name="receipt_footer" rows="2"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">{{ $settings['receipt_footer'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Pajak
                                (%)</label>
                            <input type="number" name="tax_rate" value="{{ $settings['tax_rate'] ?? 0 }}" min="0" max="100"
                                step="0.01"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end gap-3">
                    <button type="reset"
                        class="px-6 py-3 rounded-xl border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-medium hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                        Reset Form
                    </button>
                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-accent-500 to-accent-600 text-white font-medium shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all hover:-translate-y-0.5">
                        <i data-lucide="save" class="inline h-4 w-4 mr-2"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden form for logo reset -->
    <form id="resetLogoForm" action="{{ route('admin.settings.reset-logo') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('styles')
        <style>
            @keyframes fade-in-up {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fade-in-down {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-up {
                animation: fade-in-up 0.6s ease-out forwards;
                opacity: 0;
            }

            .animate-fade-in-down {
                animation: fade-in-down 0.6s ease-out forwards;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function resetLogo() {
                if (confirm('Yakin ingin menghapus logo dan kembali ke logo default?')) {
                    document.getElementById('resetLogoForm').submit();
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                lucide.createIcons();

                // Preview logo before upload
                const logoInput = document.getElementById('logoInput');
                const logoPreview = document.getElementById('logoPreview');
                const logoPreviewContainer = document.getElementById('logoPreviewContainer');

                if (logoInput && logoPreviewContainer) {
                    logoInput.addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (file) {
                            if (file.size > 2 * 1024 * 1024) {
                                alert('Ukuran file maksimal 2MB!');
                                logoInput.value = '';
                                return;
                            }

                            if (!file.type.startsWith('image/')) {
                                alert('Hanya file gambar yang diperbolehkan!');
                                logoInput.value = '';
                                return;
                            }

                            const reader = new FileReader();
                            reader.onload = function (e) {
                                if (logoPreview) {
                                    logoPreview.src = e.target.result;
                                } else {
                                    logoPreviewContainer.innerHTML = '<img id="logoPreview" src="' + e.target.result + '" alt="Logo" class="w-full h-full object-cover">';
                                }
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }

                // Dispatch event after successful update - using PHP variables passed via data attributes
                var hasSuccess = document.getElementById('hasSuccess')?.value === '1';
                var storeName = document.getElementById('storeNameData')?.value || '';
                var logoUrl = document.getElementById('logoUrlData')?.value || '';

                if (hasSuccess) {
                    setTimeout(function () {
                        window.dispatchEvent(new CustomEvent('settingsUpdated', {
                            detail: {
                                store_name: storeName,
                                logo_url: logoUrl
                            }
                        }));
                    }, 500);
                }
            });
        </script>

        <!-- Hidden inputs for passing PHP data to JS safely -->
        <input type="hidden" id="hasSuccess" value="{{ session('success') ? '1' : '0' }}">
        <input type="hidden" id="storeNameData" value="{{ $settings['store_name'] ?? '' }}">
        <input type="hidden" id="logoUrlData"
            value="{{ isset($settings['store_logo']) ? asset('storage/' . $settings['store_logo']) : '' }}">
    @endpush
@endsection