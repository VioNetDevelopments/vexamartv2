<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name', 'VexaMart') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }
        .animate-float {
            animation: float 8s ease-in-out infinite;
        }
        
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient-shift 15s ease infinite;
        }
        
        @keyframes slide-in-right {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in-right {
            animation: slide-in-right 0.8s ease-out forwards;
        }
        
        @keyframes slide-in-left {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in-left {
            animation: slide-in-left 0.8s ease-out forwards;
        }
        
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .password-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 flex items-center justify-center p-4"
      x-data="{ 
          showPassword: false,
          showPasswordConfirm: false,
          name: '',
          email: '',
          password: '',
          passwordConfirm: '',
          terms: false,
          
          get passwordStrength() {
              if (!this.password) return { score: 0, label: '', color: '' };
              
              let score = 0;
              if (this.password.length >= 8) score++;
              if (this.password.length >= 12) score++;
              if (/[a-z]/.test(this.password) && /[A-Z]/.test(this.password)) score++;
              if (/\d/.test(this.password)) score++;
              if (/[^a-zA-Z0-9]/.test(this.password)) score++;
              
              if (score <= 1) return { score: 1, label: 'Lemah', color: 'bg-danger' };
              if (score <= 2) return { score: 2, label: 'Kurang Kuat', color: 'bg-warning' };
              if (score <= 3) return { score: 3, label: 'Cukup', color: 'bg-blue-500' };
              if (score <= 4) return { score: 4, label: 'Kuat', color: 'bg-success' };
              return { score: 5, label: 'Sangat Kuat', color: 'bg-success' };
          },
          
          get passwordsMatch() {
              return this.password && this.passwordConfirm && this.password === this.passwordConfirm;
          }
      }">
    
    <!-- Register Container - Landscape (FIXED SIZE) -->
    <div class="w-full max-w-5xl bg-white dark:bg-navy-900 rounded-3xl shadow-2xl shadow-slate-200/50 dark:shadow-black/30 overflow-hidden flex flex-col lg:flex-row animate-slide-in-right" style="min-height: 650px;">
        
        <!-- Left Side - Branding & Visual -->
        <div class="lg:w-2/5 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 relative overflow-hidden flex flex-col justify-between p-8 animate-gradient">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl animate-float"></div>
                <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-blue-400/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 animate-slide-in-left">
                <!-- Logo -->
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm shadow-xl mb-6">
                    <i data-lucide="user-plus" class="w-8 h-8 text-white"></i>
                </div>
                
                <!-- Title -->
                <h1 class="text-3xl font-black text-white mb-3 leading-tight">
                    Bergabung Sekarang
                </h1>
                <p class="text-sm text-blue-100 mb-6">
                    Kelola bisnis Anda dengan sistem POS modern
                </p>
            </div>
            
            <!-- Benefits -->
            <div class="relative z-10 space-y-3 animate-slide-in-left" style="animation-delay: 0.2s;">
                <div class="flex items-center gap-3 text-white/90">
                    <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="text-sm font-medium">Akses ke Dashboard Lengkap</span>
                </div>
                <div class="flex items-center gap-3 text-white/90">
                    <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="text-sm font-medium">Manajemen Produk & Stok</span>
                </div>
                <div class="flex items-center gap-3 text-white/90">
                    <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="text-sm font-medium">Laporan Penjualan Real-time</span>
                </div>
                <div class="flex items-center gap-3 text-white/90">
                    <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="text-sm font-medium">Multi-user Support</span>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="relative z-10 animate-slide-in-left" style="animation-delay: 0.4s;">
                <p class="text-xs text-blue-200">
                    © {{ date('Y') }} Vexalyn Corporation
                </p>
            </div>
        </div>
        
        <!-- Right Side - Register Form -->
        <div class="lg:w-3/5 bg-white dark:bg-navy-900 p-8 lg:p-12 flex flex-col justify-center overflow-y-auto">
            <div class="max-w-sm mx-auto w-full">
                <!-- Header -->
                <div class="mb-8 animate-slide-in-right">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-2">
                        Buat Akun Baru
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">
                        Lengkapi form di bawah untuk mendaftar
                    </p>
                </div>

                <!-- Form -->
                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div class="animate-slide-in-right" style="animation-delay: 0.1s;">
                        <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                            Nama Lengkap
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="name" x-model="name" value="{{ old('name') }}" required autofocus
                                   class="w-full rounded-2xl border border-slate-200 dark:border-white/10 pl-12 pr-4 py-3.5 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all @error('name') border-danger @enderror"
                                   placeholder="John Doe">
                        </div>
                        @error('name')
                            <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="animate-slide-in-right" style="animation-delay: 0.2s;">
                        <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <input type="email" name="email" x-model="email" value="{{ old('email') }}" required
                                   class="w-full rounded-2xl border border-slate-200 dark:border-white/10 pl-12 pr-4 py-3.5 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all @error('email') border-danger @enderror"
                                   placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password with Strength Indicator -->
                    <div class="animate-slide-in-right" style="animation-delay: 0.3s;">
                        <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required
                                   class="w-full rounded-2xl border border-slate-200 dark:border-white/10 pl-12 pr-12 py-3.5 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all @error('password') border-danger @enderror"
                                   placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 transition-colors">
                                <i data-lucide="eye" x-show="!showPassword" x-cloak class="w-5 h-5"></i>
                                <i data-lucide="eye-off" x-show="showPassword" x-cloak class="w-5 h-5"></i>
                            </button>
                        </div>
                        
                        <!-- Password Strength Indicator -->
                        <div class="mt-3" x-show="password" x-transition>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="flex-1 h-1 bg-slate-200 dark:bg-navy-700 rounded-full overflow-hidden">
                                    <div class="strength-bar h-full" 
                                         :class="passwordStrength.color"
                                         :style="'width: ' + (passwordStrength.score * 20) + '%'"></div>
                                </div>
                                <span class="text-xs font-bold" 
                                      :class="{
                                          'text-danger': passwordStrength.score <= 1,
                                          'text-warning': passwordStrength.score === 2,
                                          'text-blue-500': passwordStrength.score === 3,
                                          'text-success': passwordStrength.score >= 4
                                      }"
                                      x-text="passwordStrength.label"></span>
                            </div>
                            <ul class="text-[10px] text-slate-500 space-y-1">
                                <li class="flex items-center gap-1.5">
                                    <i data-lucide="check" class="w-3 h-3" :class="password.length >= 8 ? 'text-success' : 'text-slate-300'"></i>
                                    <span>Minimal 8 karakter</span>
                                </li>
                                <li class="flex items-center gap-1.5">
                                    <i data-lucide="check" class="w-3 h-3" :class="(/[a-z]/.test(password) && /[A-Z]/.test(password)) ? 'text-success' : 'text-slate-300'"></i>
                                    <span>Huruf besar & kecil</span>
                                </li>
                                <li class="flex items-center gap-1.5">
                                    <i data-lucide="check" class="w-3 h-3" :class="/\d/.test(password) ? 'text-success' : 'text-slate-300'"></i>
                                    <span>Angka</span>
                                </li>
                            </ul>
                        </div>
                        
                        @error('password')
                            <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="animate-slide-in-right" style="animation-delay: 0.4s;">
                        <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                            Konfirmasi Password
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </div>
                            <input :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" x-model="passwordConfirm" required
                                   class="w-full rounded-2xl border border-slate-200 dark:border-white/10 pl-12 pr-12 py-3.5 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all"
                                   placeholder="••••••••">
                            <button type="button" @click="showPasswordConfirm = !showPasswordConfirm"
                                    class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 transition-colors">
                                <i data-lucide="eye" x-show="!showPasswordConfirm" x-cloak class="w-5 h-5"></i>
                                <i data-lucide="eye-off" x-show="showPasswordConfirm" x-cloak class="w-5 h-5"></i>
                            </button>
                        </div>
                        
                        <!-- Password Match Indicator -->
                        <div class="mt-2" x-show="passwordConfirm" x-transition>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check-circle" x-show="passwordsMatch" x-cloak class="w-4 h-4 text-success"></i>
                                <i data-lucide="x-circle" x-show="!passwordsMatch && passwordConfirm" x-cloak class="w-4 h-4 text-danger"></i>
                                <span class="text-xs" 
                                      :class="{
                                          'text-success': passwordsMatch,
                                          'text-danger': !passwordsMatch && passwordConfirm
                                      }"
                                      x-text="passwordsMatch ? 'Password cocok' : 'Password tidak cocok'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="flex items-start gap-3 animate-slide-in-right" style="animation-delay: 0.5s;">
                        <input type="checkbox" name="terms" x-model="terms" required
                               class="w-4 h-4 mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Saya setuju dengan <a href="#" class="font-bold text-blue-600 hover:text-blue-700 underline">Syarat & Ketentuan</a> serta <a href="#" class="font-bold text-blue-600 hover:text-blue-700 underline">Kebijakan Privasi</a> yang berlaku
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold text-sm shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all hover:-translate-y-0.5 active:translate-y-0 animate-slide-in-right"
                            style="animation-delay: 0.6s;">
                        <i data-lucide="user-plus" class="inline w-5 h-5 mr-2"></i>
                        Buat Akun Sekarang
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6 animate-slide-in-right" style="animation-delay: 0.7s;">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200 dark:border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white dark:bg-navy-900 px-4 text-slate-400">Sudah punya akun?</span>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center animate-slide-in-right" style="animation-delay: 0.8s;">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-blue-600 text-blue-600 font-bold text-sm hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        Login di sini
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>