@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <!-- Animated Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-accent-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative max-w-6xl mx-auto space-y-6">
        <!-- Header with Icon -->
        <div class="flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                    <i data-lucide="settings" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">
                        Pengaturan Toko
                    </h1>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Kelola informasi toko dan pengaturan sistem</p>
                </div>
            </div>
        </div>

        <!-- Settings Form -->
        <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;">
            @csrf
            @method('PUT')

            <!-- Company & Store Info Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Company Information -->
                <div class="bg-white dark:bg-navy-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-white/10">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i data-lucide="building-2" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-navy-900 dark:text-white">Informasi Perusahaan</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Data legal perusahaan</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nama Perusahaan</label>
                            <input type="text" name="company_name" value="{{ $settings['company_name'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Tagline/Slogan</label>
                            <input type="text" name="store_tagline" value="{{ $settings['store_tagline'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all"
                                placeholder="Contoh: Solusi Belanja Modern">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Alamat Perusahaan</label>
                            <textarea name="company_address" rows="3"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all resize-none">{{ $settings['company_address'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Kota/Kabupaten</label>
                            <select name="company_city" id="companyCity"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Store Information -->
                <div class="bg-white dark:bg-navy-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-white/10">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                            <i data-lucide="store" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-navy-900 dark:text-white">Informasi Toko</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Detail operasional toko</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nama Toko</label>
                            <input type="text" name="store_name" value="{{ $settings['store_name'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Telepon</label>
                                <input type="text" name="store_phone" value="{{ $settings['store_phone'] ?? '' }}"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Email</label>
                                <input type="email" name="store_email" value="{{ $settings['store_email'] ?? '' }}"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Mata Uang</label>
                            <input type="text" name="currency" value="{{ $settings['currency'] ?? 'Rp' }}" maxlength="10"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Alamat Toko</label>
                            <textarea name="store_address" rows="2"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all resize-none">{{ $settings['store_address'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Kota/Kabupaten</label>
                            <select name="store_city" id="storeCity"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo Upload & Receipt Settings Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Logo Upload -->
                <div class="bg-white dark:bg-navy-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-white/10">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                            <i data-lucide="image" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-navy-900 dark:text-white">Logo Toko</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Upload logo toko Anda</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Logo Preview Area -->
                        <div class="relative group cursor-pointer" onclick="document.getElementById('logoInput').click()">
                            <div class="w-full h-48 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 dark:from-navy-800 dark:to-navy-700 border-2 border-dashed border-slate-300 dark:border-white/10 overflow-hidden flex items-center justify-center hover:border-accent-500 transition-colors">
                                <div id="logoPreviewContainer" class="w-full h-full relative">
                                    @if(isset($settings['store_logo']) && $settings['store_logo'])
                                        <img id="logoPreview" src="{{ asset('storage/' . $settings['store_logo']) }}" alt="Logo" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <div class="text-center text-white">
                                                <i data-lucide="upload" class="w-12 h-12 mx-auto mb-2"></i>
                                                <p class="text-sm font-bold">Klik untuk ganti logo</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center p-6">
                                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-accent-500/30">
                                                <i data-lucide="image" class="w-10 h-10 text-white"></i>
                                            </div>
                                            <p class="text-sm font-bold text-navy-900 dark:text-white mb-1">Klik untuk upload logo</p>
                                            <p class="text-xs text-slate-500">PNG, JPG, WEBP (Max 5MB)</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <input type="file" name="logo" id="logoInput" accept="image/*" class="hidden">
                        </div>

                        <!-- File Info -->
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-navy-800/50 border border-slate-200 dark:border-white/5">
                            <div class="flex items-center gap-2">
                                <i data-lucide="info" class="w-4 h-4 text-slate-400"></i>
                                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Ukuran maksimal 5MB</span>
                            </div>
                            @if(isset($settings['store_logo']) && $settings['store_logo'])
                                <button type="button" onclick="resetLogo()" class="text-xs font-bold text-danger hover:text-danger/80 transition-colors flex items-center gap-1">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    Hapus Logo
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Receipt Settings -->
                <div class="bg-white dark:bg-navy-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-white/10">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/30">
                            <i data-lucide="receipt" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-navy-900 dark:text-white">Pengaturan Struk</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Konfigurasi cetak struk</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Pajak (%)</label>
                                <div class="relative">
                                    <input type="number" name="tax_rate" value="{{ $settings['tax_rate'] ?? 0 }}" min="0" max="100" step="0.01"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">%</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Diskon Default (Rp)</label>
                                <div class="relative">
                                    <input type="number" name="default_discount" value="{{ $settings['default_discount'] ?? 0 }}" min="0" step="1"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Header Struk</label>
                            <textarea name="receipt_header" rows="2"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all resize-none">{{ $settings['receipt_header'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Footer Struk</label>
                            <textarea name="receipt_footer" rows="2"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all resize-none">{{ $settings['receipt_footer'] ?? '' }}</textarea>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-navy-800/50 border border-slate-200 dark:border-white/5">
                            <input type="checkbox" name="show_qr_on_receipt" value="1" {{ ($settings['show_qr_on_receipt'] ?? false) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-accent-500 focus:ring-accent-500">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Tampilkan QR Code di struk</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Providers Section -->
            <div class="bg-white dark:bg-navy-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 dark:border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i data-lucide="wallet" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-navy-900 dark:text-white">Daftar Bank & E-Wallet</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Kelola pilihan pembayaran untuk kasir</p>
                        </div>
                    </div>
                    <button type="button" onclick="openAddProviderModal()"
                        class="px-4 py-2 rounded-xl bg-accent-500 hover:bg-accent-600 text-white text-xs font-bold transition-all flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Tambah Provider
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="providersList">
                    <!-- Dynamic Content via JS -->
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-200 dark:border-white/10">
                <button type="button" onclick="confirmReset()"
                    class="px-6 py-3 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all duration-200">
                    <i data-lucide="rotate-ccw" class="inline h-4 w-4 mr-2"></i>
                    Reset Form
                </button>
                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-accent-500 to-accent-600 text-white font-bold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    <span>Simpan Pengaturan</span>
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

@push('scripts')
<script>
// Indonesian Cities/Regencies Data (Sample - in production, use API)
const indonesianCities = [
    "Kabupaten Aceh Barat", "Kabupaten Aceh Besar", "Kabupaten Aceh Jaya", "Kabupaten Aceh Selatan", "Kabupaten Aceh Singkil",
    "Kabupaten Aceh Tamiang", "Kabupaten Aceh Tengah", "Kabupaten Aceh Tenggara", "Kabupaten Aceh Timur", "Kabupaten Aceh Utara",
    "Kabupaten Bener Meriah", "Kabupaten Bireuen", "Kabupaten Gayo Lues", "Kabupaten Nagan Raya", "Kabupaten Pidie",
    "Kabupaten Pidie Jaya", "Kabupaten Simeulue", "Kota Banda Aceh", "Kota Langsa", "Kota Lhokseumawe",
    "Kota Sabang", "Kota Subulussalam", "Kabupaten Asahan", "Kabupaten Batu Bara", "Kabupaten Dairi",
    "Kabupaten Deli Serdang", "Kabupaten Humbang Hasundutan", "Kabupaten Karo", "Kabupaten Labuhanbatu", "Kabupaten Labuhanbatu Selatan",
    "Kabupaten Labuhanbatu Utara", "Kabupaten Langkat", "Kabupaten Mandailing Natal", "Kabupaten Nias", "Kabupaten Nias Barat",
    "Kabupaten Nias Selatan", "Kabupaten Nias Utara", "Kabupaten Padang Lawas", "Kabupaten Padang Lawas Utara", "Kabupaten Pakpak Bharat",
    "Kabupaten Samosir", "Kabupaten Serdang Bedagai", "Kabupaten Simalungun", "Kabupaten Tapanuli Selatan", "Kabupaten Tapanuli Tengah",
    "Kabupaten Tapanuli Utara", "Kabupaten Toba Samosir", "Kota Binjai", "Kota Gunungsitoli", "Kota Medan",
    "Kota Padang Sidempuan", "Kota Pematang Siantar", "Kota Sibolga", "Kota Tanjung Balai", "Kota Tebing Tinggi",
    "Kabupaten Agam", "Kabupaten Dharmasraya", "Kabupaten Kepulauan Mentawai", "Kabupaten Lima Puluh Kota", "Kabupaten Padang Pariaman",
    "Kabupaten Pasaman", "Kabupaten Pasaman Barat", "Kabupaten Pesisir Selatan", "Kabupaten Sijunjung", "Kabupaten Solok",
    "Kabupaten Solok Selatan", "Kabupaten Tanah Datar", "Kota Bukittinggi", "Kota Padang", "Kota Padang Panjang",
    "Kota Pariaman", "Kota Payakumbuh", "Kota Sawahlunto", "Kota Solok", "Kabupaten Bengkalis",
    "Kabupaten Indragiri Hilir", "Kabupaten Indragiri Hulu", "Kabupaten Kampar", "Kabupaten Kepulauan Meranti", "Kabupaten Kuantan Singingi",
    "Kabupaten Pelalawan", "Kabupaten Rokan Hilir", "Kabupaten Rokan Hulu", "Kabupaten Siak", "Kota Dumai",
    "Kota Pekanbaru", "Kabupaten Bintan", "Kabupaten Karimun", "Kabupaten Kepulauan Anambas", "Kabupaten Lingga",
    "Kabupaten Natuna", "Kota Batam", "Kota Tanjung Pinang", "Kabupaten Batang Hari", "Kabupaten Bungo",
    "Kabupaten Kerinci", "Kabupaten Merangin", "Kabupaten Muaro Jambi", "Kabupaten Sarolangun", "Kabupaten Tanjung Jabung Barat",
    "Kabupaten Tanjung Jabung Timur", "Kabupaten Tebo", "Kota Jambi", "Kota Sungai Penuh", "Kabupaten Banyuasin",
    "Kabupaten Empat Lawang", "Kabupaten Lahat", "Kabupaten Muara Enim", "Kabupaten Musi Banyuasin", "Kabupaten Musi Rawas",
    "Kabupaten Musi Rawas Utara", "Kabupaten Ogan Ilir", "Kabupaten Ogan Komering Ilir", "Kabupaten Ogan Komering Ulu", "Kabupaten Ogan Komering Ulu Selatan",
    "Kabupaten Ogan Komering Ulu Timur", "Kabupaten Penukal Abab Lematang Ilir", "Kota Lubuklinggau", "Kota Pagar Alam", "Kota Palembang",
    "Kota Prabumulih", "Kabupaten Bangka", "Kabupaten Bangka Barat", "Kabupaten Bangka Selatan", "Kabupaten Bangka Tengah",
    "Kabupaten Belitung", "Kabupaten Belitung Timur", "Kota Pangkal Pinang", "Kabupaten Lampung Barat", "Kabupaten Lampung Selatan",
    "Kabupaten Lampung Tengah", "Kabupaten Lampung Timur", "Kabupaten Lampung Utara", "Kabupaten Mesuji", "Kabupaten Pesawaran",
    "Kabupaten Pesisir Barat", "Kabupaten Pringsewu", "Kabupaten Tulang Bawang", "Kabupaten Tulang Bawang Barat", "Kabupaten Way Kanan",
    "Kota Bandar Lampung", "Kota Metro", "Kabupaten Lebak", "Kabupaten Pandeglang", "Kabupaten Serang",
    "Kabupaten Tangerang", "Kota Cilegon", "Kota Serang", "Kota Tangerang", "Kota Tangerang Selatan",
    "Kabupaten Bandung", "Kabupaten Bandung Barat", "Kabupaten Bekasi", "Kabupaten Bogor", "Kabupaten Ciamis",
    "Kabupaten Cianjur", "Kabupaten Cirebon", "Kabupaten Garut", "Kabupaten Indramayu", "Kabupaten Karawang",
    "Kabupaten Kuningan", "Kabupaten Majalengka", "Kabupaten Pangandaran", "Kabupaten Purwakarta", "Kabupaten Subang",
    "Kabupaten Sukabumi", "Kabupaten Sumedang", "Kabupaten Tasikmalaya", "Kota Bandung", "Kota Banjar",
    "Kota Bekasi", "Kota Bogor", "Kota Cimahi", "Kota Cirebon", "Kota Depok",
    "Kota Sukabumi", "Kota Tasikmalaya", "Kabupaten Banjarnegara", "Kabupaten Banyumas", "Kabupaten Batang",
    "Kabupaten Blora", "Kabupaten Boyolali", "Kabupaten Brebes", "Kabupaten Cilacap", "Kabupaten Demak",
    "Kabupaten Grobogan", "Kabupaten Jepara", "Kabupaten Karanganyar", "Kabupaten Kebumen", "Kabupaten Kendal",
    "Kabupaten Klaten", "Kabupaten Kudus", "Kabupaten Magelang", "Kabupaten Pati", "Kabupaten Pekalongan",
    "Kabupaten Pemalang", "Kabupaten Purbalingga", "Kabupaten Purworejo", "Kabupaten Rembang", "Kabupaten Semarang",
    "Kabupaten Sragen", "Kabupaten Sukoharjo", "Kabupaten Tegal", "Kabupaten Temanggung", "Kabupaten Wonogiri",
    "Kabupaten Wonosobo", "Kota Magelang", "Kota Pekalongan", "Kota Salatiga", "Kota Semarang",
    "Kota Surakarta", "Kota Tegal", "Kabupaten Bantul", "Kabupaten Gunung Kidul", "Kabupaten Kulon Progo",
    "Kabupaten Sleman", "Kota Yogyakarta", "Kabupaten Bangkalan", "Kabupaten Banyuwangi", "Kabupaten Blitar",
    "Kabupaten Bojonegoro", "Kabupaten Bondowoso", "Kabupaten Gresik", "Kabupaten Jember", "Kabupaten Jombang",
    "Kabupaten Kediri", "Kabupaten Lamongan", "Kabupaten Lumajang", "Kabupaten Madiun", "Kabupaten Magetan",
    "Kabupaten Malang", "Kabupaten Mojokerto", "Kabupaten Nganjuk", "Kabupaten Ngawi", "Kabupaten Pacitan",
    "Kabupaten Pamekasan", "Kabupaten Pasuruan", "Kabupaten Ponorogo", "Kabupaten Probolinggo", "Kabupaten Sampang",
    "Kabupaten Sidoarjo", "Kabupaten Situbondo", "Kabupaten Sumenep", "Kabupaten Trenggalek", "Kabupaten Tuban",
    "Kabupaten Tulungagung", "Kota Batu", "Kota Blitar", "Kota Kediri", "Kota Madiun",
    "Kota Malang", "Kota Mojokerto", "Kota Pasuruan", "Kota Probolinggo", "Kota Surabaya",
    "Kabupaten Badung", "Kabupaten Bangli", "Kabupaten Buleleng", "Kabupaten Gianyar", "Kabupaten Jembrana",
    "Kabupaten Karangasem", "Kabupaten Klungkung", "Kabupaten Tabanan", "Kota Denpasar", "Kabupaten Bima",
    "Kabupaten Dompu", "Kabupaten Lombok Barat", "Kabupaten Lombok Tengah", "Kabupaten Lombok Timur", "Kabupaten Lombok Utara",
    "Kabupaten Sumbawa", "Kabupaten Sumbawa Barat", "Kota Bima", "Kabupaten Alor", "Kabupaten Belu",
    "Kabupaten Ende", "Kabupaten Flores Timur", "Kabupaten Kupang", "Kabupaten Lembata", "Kabupaten Malaka",
    "Kabupaten Manggarai", "Kabupaten Manggarai Barat", "Kabupaten Manggarai Timur", "Kabupaten Ngada", "Kabupaten Nagekeo",
    "Kabupaten Rote Ndao", "Kabupaten Sabu Raijua", "Kabupaten Sikka", "Kabupaten Sumba Barat", "Kabupaten Sumba Barat Daya",
    "Kabupaten Sumba Tengah", "Kabupaten Sumba Timur", "Kota Kupang", "Kabupaten Bengkayang", "Kabupaten Kapuas Hulu",
    "Kabupaten Kayong Utara", "Kabupaten Ketapang", "Kabupaten Kubu Raya", "Kabupaten Landak", "Kabupaten Melawi",
    "Kabupaten Mempawah", "Kabupaten Sambas", "Kabupaten Sanggau", "Kabupaten Sekadau", "Kabupaten Sintang",
    "Kota Pontianak", "Kota Singkawang", "Kabupaten Barito Selatan", "Kabupaten Barito Timur", "Kabupaten Barito Utara",
    "Kabupaten Gunung Mas", "Kabupaten Kapuas", "Kabupaten Katingan", "Kabupaten Kotawaringin Barat", "Kabupaten Kotawaringin Timur",
    "Kabupaten Lamandau", "Kabupaten Murung Raya", "Kabupaten Pulang Pisau", "Kabupaten Sukamara", "Kabupaten Seruyan",
    "Kota Palangka Raya", "Kabupaten Balangan", "Kabupaten Banjar", "Kabupaten Barito Kuala", "Kabupaten Hulu Sungai Selatan",
    "Kabupaten Hulu Sungai Tengah", "Kabupaten Hulu Sungai Utara", "Kabupaten Kotabaru", "Kabupaten Tabalong", "Kabupaten Tanah Bumbu",
    "Kabupaten Tanah Laut", "Kabupaten Tapin", "Kota Banjarbaru", "Kota Banjarmasin", "Kabupaten Nunukan",
    "Kabupaten Penajam Paser Utara", "Kabupaten Paser", "Kabupaten Kutai Barat", "Kabupaten Kutai Kartanegara", "Kabupaten Kutai Timur",
    "Kabupaten Berau", "Kabupaten Mahakam Ulu", "Kota Balikpapan", "Kota Bontang", "Kota Samarinda",
    "Kabupaten Bolaang Mongondow", "Kabupaten Bolaang Mongondow Selatan", "Kabupaten Bolaang Mongondow Timur", "Kabupaten Bolaang Mongondow Utara", "Kabupaten Kepulauan Sangihe",
    "Kabupaten Kepulauan Siau Tagulandang Biaro", "Kabupaten Kepulauan Talaud", "Kabupaten Minahasa", "Kabupaten Minahasa Selatan", "Kabupaten Minahasa Tenggara",
    "Kabupaten Minahasa Utara", "Kabupaten Kepulauan Sula", "Kota Bitung", "Kota Kotamobagu", "Kota Manado",
    "Kota Tomohon", "Kabupaten Banggai", "Kabupaten Banggai Kepulauan", "Kabupaten Banggai Laut", "Kabupaten Buol",
    "Kabupaten Donggala", "Kabupaten Morowali", "Kabupaten Morowali Utara", "Kabupaten Parigi Moutong", "Kabupaten Poso",
    "Kabupaten Sigi", "Kabupaten Tojo Una-Una", "Kabupaten Toli-Toli", "Kota Palu", "Kabupaten Bombana",
    "Kabupaten Buton", "Kabupaten Buton Selatan", "Kabupaten Buton Tengah", "Kabupaten Buton Utara", "Kabupaten Kolaka",
    "Kabupaten Kolaka Timur", "Kabupaten Kolaka Utara", "Kabupaten Konawe", "Kabupaten Konawe Kepulauan", "Kabupaten Konawe Selatan",
    "Kabupaten Konawe Utara", "Kabupaten Muna", "Kabupaten Muna Barat", "Kabupaten Wakatobi", "Kota Baubau",
    "Kota Kendari", "Kabupaten Buru", "Kabupaten Buru Selatan", "Kabupaten Kepulauan Aru", "Kabupaten Maluku Barat Daya",
    "Kabupaten Maluku Tengah", "Kabupaten Maluku Tenggara", "Kabupaten Maluku Tenggara Barat", "Kabupaten Seram Bagian Barat", "Kabupaten Seram Bagian Timur",
    "Kota Ambon", "Kota Tual", "Kabupaten Halmahera Barat", "Kabupaten Halmahera Selatan", "Kabupaten Halmahera Tengah",
    "Kabupaten Halmahera Timur", "Kabupaten Halmahera Utara", "Kabupaten Kepulauan Sula", "Kabupaten Pulau Morotai", "Kabupaten Pulau Taliabu",
    "Kota Ternate", "Kota Tidore Kepulauan", "Kabupaten Fakfak", "Kabupaten Kaimana", "Kabupaten Manokwari",
    "Kabupaten Manokwari Selatan", "Kabupaten Maybrat", "Kabupaten Pegunungan Arfak", "Kabupaten Raja Ampat", "Kabupaten Sorong",
    "Kabupaten Sorong Selatan", "Kabupaten Tambrauw", "Kabupaten Teluk Bintuni", "Kabupaten Teluk Wondama", "Kota Sorong",
    "Kabupaten Asmat", "Kabupaten Biak Numfor", "Kabupaten Boven Digoel", "Kabupaten Deiyai", "Kabupaten Dogiyai",
    "Kabupaten Intan Jaya", "Kabupaten Jayapura", "Kabupaten Jayawijaya", "Kabupaten Keerom", "Kabupaten Kepulauan Yapen",
    "Kabupaten Lanny Jaya", "Kabupaten Mamberamo Raya", "Kabupaten Mamberamo Tengah", "Kabupaten Mappi", "Kabupaten Merauke",
    "Kabupaten Mimika", "Kabupaten Nabire", "Kabupaten Nduga", "Kabupaten Paniai", "Kabupaten Pegunungan Bintang",
    "Kabupaten Puncak", "Kabupaten Puncak Jaya", "Kabupaten Sarmi", "Kabupaten Supiori", "Kabupaten Tolikara",
    "Kabupaten Waropen", "Kabupaten Yahukimo", "Kabupaten Yalimo", "Kota Jayapura"
];

function resetLogo() {
    confirmAction({
        title: 'Hapus Logo, King?',
        text: 'Yakin mau hapus logo dan balik ke logo default?',
        icon: 'warning',
        confirmColor: '#ef4444',
        confirmText: 'Ya, Hapus!',
        callback: () => document.getElementById('resetLogoForm').submit()
    });
}

function confirmReset() {
    confirmAction({
        title: 'Reset Form, King?',
        text: 'Semua perubahan yang belum disimpan bakal ilang nih!',
        icon: 'info',
        confirmColor: '#64748b',
        confirmText: 'Ya, Reset',
        callback: () => {
            document.getElementById('settingsForm').reset();
            loadCitiesToDropdown('companyCity', "{{ $settings['company_city'] ?? '' }}");
            loadCitiesToDropdown('storeCity', "{{ $settings['store_city'] ?? '' }}");
        }
    });
}

function loadCitiesToDropdown(selectId, selectedValue) {
    const select = document.getElementById(selectId);
    if (!select) return;
    
    select.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
    
    indonesianCities.forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = city;
        if (city === selectedValue) {
            option.selected = true;
        }
        select.appendChild(option);
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // Load cities to dropdowns
    loadCitiesToDropdown('companyCity', "{{ $settings['company_city'] ?? '' }}");
    loadCitiesToDropdown('storeCity', "{{ $settings['store_city'] ?? '' }}");
    
    // Logo upload preview with AJAX
    const logoInput = document.getElementById('logoInput');
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB! File Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
                    logoInput.value = '';
                    return;
                }

                // Check file type
                if (!file.type.startsWith('image/')) {
                    alert('Hanya file gambar yang diperbolehkan!');
                    logoInput.value = '';
                    return;
                }

                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.getElementById('logoPreviewContainer');
                    if (previewContainer) {
                        previewContainer.innerHTML = `
                            <img id="logoPreview" src="${e.target.result}" alt="Logo" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                                <div class="text-center text-white">
                                    <i data-lucide="upload" class="w-12 h-12 mx-auto mb-2"></i>
                                    <p class="text-sm font-bold">Klik untuk ganti logo</p>
                                </div>
                            </div>
                        `;
                        lucide.createIcons();
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Load Payment Providers
    function loadProviders() {
        fetch('{{ route('admin.payment-providers.index') }}')
            .then(r => r.json())
            .then(providers => {
                const container = document.getElementById('providersList');
                if (!container) return;
                
                container.innerHTML = providers.map(p => `
                    <div class="p-4 rounded-2xl border-2 ${p.is_active ? 'border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-navy-800/30' : 'border-dashed border-slate-200 dark:border-white/10 opacity-60'} transition-all hover:border-accent-500/30">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-navy-700 shadow-sm flex items-center justify-center font-bold text-xs uppercase text-slate-400">
                                ${p.name.substring(0, 3)}
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" onclick="toggleProvider(${p.id})" class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-navy-700 transition-colors ${p.is_active ? 'text-success' : 'text-slate-400'}">
                                    <i data-lucide="${p.is_active ? 'eye' : 'eye-off'}" class="w-4 h-4"></i>
                                </button>
                                <button type="button" onclick="deleteProvider(${p.id})" class="p-1.5 rounded-lg hover:bg-danger/10 text-danger opacity-0 group-hover:opacity-100 transition-all">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        <h4 class="font-bold text-sm text-navy-900 dark:text-white mb-0.5">${p.name}</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">${p.type === 'bank' ? 'Transfer Bank' : 'E-Wallet'}</p>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                            <p>${p.account_number || '-'}</p>
                            <p class="truncate">${p.account_name || '-'}</p>
                        </div>
                    </div>
                `).join('');
                lucide.createIcons();
            });
    }

    window.toggleProvider = function(id) {
        fetch(\`/admin/payment-providers/\${id}/toggle\`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(r => r.json()).then(data => {
            if (data.success) loadProviders();
        });
    }

    window.deleteProvider = function(id) {
        confirmAction({
            title: 'Hapus Provider?',
            text: 'Provider ini bakal diapus permanen dari pilihan kasir!',
            icon: 'warning',
            confirmColor: '#ef4444',
            confirmText: 'Ya, Hapus',
            callback: () => {
                fetch(\`/admin/payment-providers/\${id}\`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        toast('Berhasil!', 'Provider udah diapus, King.', 'success');
                        loadProviders();
                    }
                });
            }
        });
    }

    window.openAddProviderModal = function() {
        Swal.fire({
            title: 'Tambah Provider Baru',
            html: \`
                <div class="space-y-4 text-left p-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Provider</label>
                        <input id="swal-name" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-accent-500/10 outline-none" placeholder="Contoh: BCA, DANA, OVO">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tipe</label>
                        <select id="swal-type" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none">
                            <option value="bank">Transfer Bank</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nomor Rekening/HP</label>
                        <input id="swal-account" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-accent-500/10 outline-none" placeholder="123456789">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Pemilik</label>
                        <input id="swal-holder" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-accent-500/10 outline-none" placeholder="VEXAMART STORE">
                    </div>
                </div>
            \`,
            showCancelButton: true,
            confirmButtonText: 'Simpan Provider',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#3b82f6',
            preConfirm: () => {
                return {
                    name: document.getElementById('swal-name').value,
                    type: document.getElementById('swal-type').value,
                    account_number: document.getElementById('swal-account').value,
                    account_name: document.getElementById('swal-holder').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route('admin.payment-providers.store') }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify(result.value)
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        toast('Mantap!', 'Provider baru udah terdaftar!', 'success');
                        loadProviders();
                    }
                });
            }
        });
    }

    loadProviders();

    // Form submission with confirmation
    const settingsForm = document.getElementById('settingsForm');
    if (settingsForm) {
        settingsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            confirmAction({
                title: 'Simpan Pengaturan, King?',
                text: 'Pastikan semua data sudah benar ya!',
                icon: 'question',
                confirmColor: '#3b82f6',
                confirmText: 'Ya, Simpan!',
                callback: () => {
                    const submitBtn = settingsForm.querySelector('button[type="submit"]');
                    submitBtn.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i><span>Menyimpan...</span>';
                    submitBtn.disabled = true;
                    lucide.createIcons();
                    settingsForm.submit();
                }
            });
        });
    }
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