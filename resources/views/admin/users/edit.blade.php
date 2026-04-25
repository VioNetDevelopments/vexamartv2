@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <!-- Animated Background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-accent-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse"
                style="animation-delay: 2s;"></div>
        </div>

        <div class="relative max-w-5xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4 animate-fade-in-down">
                <a href="{{ route('admin.users.index') }}"
                    class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                </a>
                <div>
                    <h1
                        class="text-3xl font-black text-navy-900 dark:text-white">
                        Edit User
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">Update informasi user</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" 
                @submit.prevent="notify.confirm('Yakin KING mau update data user ini? Cek lagi ya datanya!', () => $el.submit())"
                x-data="{ 
                      avatarPreview: null,
                      existingAvatar: '{{ $user->avatar ?? '' }}',
                      showPassword: false
                  }" class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div
                            class="bg-white dark:bg-navy-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-white/10">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                    <i data-lucide="user" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-navy-900 dark:text-white">Informasi Dasar</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Data pribadi user</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <!-- Name -->
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <div class="relative">
                                        <i data-lucide="user"
                                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                            class="w-full rounded-xl border border-slate-200 pl-12 pr-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all @error('name') border-danger @enderror"
                                            placeholder="Masukkan nama lengkap">
                                    </div>
                                    @error('name')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="relative">
                                        <i data-lucide="mail"
                                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                            class="w-full rounded-xl border border-slate-200 pl-12 pr-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all @error('email') border-danger @enderror"
                                            placeholder="email@example.com">
                                    </div>
                                    @error('email')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                                        Nomor Telepon
                                    </label>
                                    <div class="relative">
                                        <i data-lucide="phone"
                                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                            class="w-full rounded-xl border border-slate-200 pl-12 pr-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all"
                                            placeholder="08123456789">
                                    </div>
                                    @error('phone')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Security -->
                        <div
                            class="bg-white dark:bg-navy-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-white/10">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                                    <i data-lucide="lock" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-navy-900 dark:text-white">Keamanan</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Ubah password jika diperlukan</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <!-- Password -->
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                                        Password Baru
                                    </label>
                                    <div class="relative">
                                        <i data-lucide="lock"
                                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                        <input :type="showPassword ? 'text' : 'password'" name="password" autocomplete="new-password"
                                            class="w-full rounded-xl border border-slate-200 pl-12 pr-12 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all @error('password') border-danger @enderror"
                                            placeholder="Kosongkan jika tidak ingin mengubah">
                                        <button type="button" @click="showPassword = !showPassword"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-accent-500 transition-colors flex items-center justify-center">
                                            <!-- Eye Icon -->
                                            <svg x-show="!showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            <!-- Eye Off Icon -->
                                            <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off"><path d="M9.88 9.88 1.5 1.5"/><path d="M2 12s3-7 10-7a9.77 9.77 0 0 1 5 1.41"/><path d="M22 12s-3 7-10 7a9.77 9.77 0 0 1-5-1.41"/><path d="m15.12 15.12-1.54-1.54"/><path d="m11.3 11.3-1.64-1.64"/><path d="M21.5 21.5 17 17"/><path d="M12 15a3 3 0 0 1-3-3"/><circle cx="12" cy="12" r="3"/><path d="M15 12a3 3 0 0 1-1.35 2.5"/></svg>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-slate-500 mt-1">Minimal 8 karakter</p>
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                                        Konfirmasi Password
                                    </label>
                                    <div class="relative">
                                        <i data-lucide="lock"
                                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                        <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" autocomplete="new-password"
                                            class="w-full rounded-xl border border-slate-200 pl-12 pr-12 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all"
                                            placeholder="Konfirmasi password baru">
                                        <button type="button" @click="showPassword = !showPassword"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-accent-500 transition-colors">
                                            <span x-show="!showPassword" x-cloak>
                                                <i data-lucide="eye" class="w-5 h-5"></i>
                                            </span>
                                            <span x-show="showPassword" x-cloak>
                                                <i data-lucide="eye-off" class="w-5 h-5"></i>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Avatar -->
                        <div
                            class="bg-white dark:bg-navy-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-white/10">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                                    <i data-lucide="image" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-navy-900 dark:text-white">Foto Profil</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Upload foto user</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <!-- Preview -->
                                <div class="aspect-square rounded-2xl border-2 border-dashed border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-navy-800 flex items-center justify-center overflow-hidden cursor-pointer hover:border-accent-500 transition-colors"
                                    @click="$refs.avatarInput.click()">
                                    <template x-if="avatarPreview">
                                        <img :src="avatarPreview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!avatarPreview && existingAvatar">
                                        <img src="{{ asset('storage/' . $user->avatar) }}"
                                            class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!avatarPreview && !existingAvatar">
                                        <div class="text-center p-4">
                                            <div
                                                class="w-16 h-16 rounded-full bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center mx-auto mb-3 shadow-lg shadow-accent-500/30">
                                                <i data-lucide="upload" class="w-8 h-8 text-white"></i>
                                            </div>
                                            <p class="text-sm font-bold text-navy-900 dark:text-white">Klik untuk upload</p>
                                            <p class="text-xs text-slate-500 mt-1">Max 2MB (JPG, PNG)</p>
                                        </div>
                                    </template>
                                </div>

                                <input type="file" name="avatar" accept="image/*" x-ref="avatarInput"
                                    @change="handleFileSelect" class="hidden">

                                @if($user->avatar)
                                    <button type="button" @click="existingAvatar = ''; $refs.avatarInput.value = ''"
                                        class="w-full rounded-xl border border-danger/20 text-danger py-2.5 text-sm font-bold hover:bg-danger/5 transition-colors">
                                        <i data-lucide="trash-2" class="inline w-4 h-4 mr-1"></i>
                                        Hapus Foto
                                    </button>
                                @endif

                                @error('avatar')
                                    <p class="text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Role & Status -->
                        <div
                            class="bg-white dark:bg-navy-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-white/10">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/30">
                                    <i data-lucide="shield" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-navy-900 dark:text-white">Role & Status</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Hak akses user</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <!-- Role -->
                                <div x-data="{ roleOpen: false, roleVal: '{{ old('role', $user->role) }}', roleText: '{{ old('role', $user->role) == 'owner' ? 'Owner' : (old('role', $user->role) == 'admin' ? 'Admin' : (old('role', $user->role) == 'cashier' ? 'Kasir' : (old('role', $user->role) == 'customer' ? 'Customer' : 'Kasir'))) }}' }">
                                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                                        Role <span class="text-danger">*</span>
                                    </label>
                                    <input type="hidden" name="role" :value="roleVal" required>
                                    <div class="relative" style="z-index: 50;">
                                        <button type="button" @click="roleOpen = !roleOpen" @click.away="roleOpen = false"
                                                class="w-full flex items-center justify-between pl-4 pr-3 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white @error('role') border-danger @enderror">
                                            <span x-text="roleText"></span>
                                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': roleOpen}"></i>
                                        </button>
                                        <div x-show="roleOpen" x-transition
                                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10"
                                             style="top: 100%;">
                                            <button type="button" @click="roleVal='owner'; roleText='Owner'; roleOpen=false"
                                                    class="w-full text-left px-4 py-2.5 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 text-slate-600 dark:text-slate-300 flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center"><i data-lucide="crown" class="w-4 h-4 text-purple-600"></i></span>
                                                Owner
                                            </button>
                                            <button type="button" @click="roleVal='admin'; roleText='Admin'; roleOpen=false"
                                                    class="w-full text-left px-4 py-2.5 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 text-slate-600 dark:text-slate-300 flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center"><i data-lucide="shield" class="w-4 h-4 text-blue-600"></i></span>
                                                Admin
                                            </button>
                                            <button type="button" @click="roleVal='cashier'; roleText='Kasir'; roleOpen=false"
                                                    class="w-full text-left px-4 py-2.5 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 text-slate-600 dark:text-slate-300 flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center"><i data-lucide="monitor-smartphone" class="w-4 h-4 text-green-600"></i></span>
                                                Kasir
                                            </button>
                                            <button type="button" @click="roleVal='customer'; roleText='Customer'; roleOpen=false"
                                                    class="w-full text-left px-4 py-2.5 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 text-slate-600 dark:text-slate-300 flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center"><i data-lucide="user" class="w-4 h-4 text-slate-500"></i></span>
                                                Customer
                                            </button>
                                        </div>
                                    </div>
                                    @error('role')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-3" x-data="{ activeStatus: '{{ old('is_active', $user->is_active ? '1' : '0') }}' }">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="is_active" value="1" x-model="activeStatus" class="sr-only">
                                            <div
                                                :class="activeStatus === '1' ? 'border-success bg-success text-white shadow-lg shadow-success/30' : 'border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400'"
                                                class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 transition-all duration-300">
                                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                                <span class="text-sm font-bold">Aktif</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="is_active" value="0" x-model="activeStatus" class="sr-only">
                                            <div
                                                :class="activeStatus === '0' ? 'border-danger bg-danger text-white shadow-lg shadow-danger/30' : 'border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400'"
                                                class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 transition-all duration-300">
                                                <i data-lucide="x-circle" class="w-5 h-5"></i>
                                                <span class="text-sm font-bold">Nonaktif</span>
                                            </div>
                                        </label>
                                    </div>
                                    @error('is_active')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-3">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-bold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all hover:-translate-y-0.5">
                                <i data-lucide="save" class="w-5 h-5"></i>
                                <span>Update User</span>
                            </button>
                            <a href="{{ route('admin.users.index') }}"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">
                                <i data-lucide="x" class="w-5 h-5"></i>
                                <span>Batal</span>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran gambar maksimal 2MB!');
                        event.target.value = '';
                        return;
                    }

                    if (!file.type.startsWith('image/')) {
                        alert('Hanya file gambar yang diperbolehkan!');
                        event.target.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        this.avatarPreview = e.target.result;
                    }.bind(this);
                    reader.readAsDataURL(file);
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                lucide.createIcons();
            });
        </script>
    @endpush

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

            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush
@endsection