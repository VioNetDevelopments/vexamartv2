<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'VexaMart') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-30px) rotate(5deg);
            }
        }

        .animate-float {
            animation: float 8s ease-in-out infinite;
        }

        @keyframes gradient-shift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient-shift 15s ease infinite;
        }

        @keyframes slide-in-right {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-in-right {
            animation: slide-in-right 0.8s ease-out forwards;
        }

        @keyframes slide-in-left {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-in-left {
            animation: slide-in-left 0.8s ease-out forwards;
        }

        .password-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 flex items-center justify-center p-4"
    x-data="{ 
          showPassword: false,
          remember: false,
          email: '',
          password: ''
      }">

    <!-- Login Container - Landscape (FIXED SIZE) -->
    <div class="w-full max-w-5xl bg-white dark:bg-navy-900 rounded-3xl shadow-2xl shadow-slate-200/50 dark:shadow-black/30 overflow-hidden flex flex-col lg:flex-row animate-slide-in-right"
        style="min-height: 600px;">

        <!-- Left Side - Branding & Visual -->
        <div
            class="lg:w-2/5 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 relative overflow-hidden flex flex-col justify-between p-8 animate-gradient">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl animate-float"></div>
                <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-blue-400/20 rounded-full blur-3xl animate-float"
                    style="animation-delay: 2s;"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 animate-slide-in-left">
                <!-- Logo -->
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm shadow-xl mb-6">
                    <i data-lucide="shopping-bag" class="w-8 h-8 text-white"></i>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-black text-white mb-3 leading-tight">
                    VEXALYN STORE
                </h1>
                <p class="text-sm text-blue-100 mb-6">
                    Sistem Manajemen POS Modern & Terpercaya
                </p>
            </div>

            <!-- Features -->
            <div class="relative z-10 space-y-3 animate-slide-in-left" style="animation-delay: 0.2s;">
                <div class="flex items-center gap-3 text-white/90">
                    <div
                        class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                        <i data-lucide="shield-check" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="text-sm font-medium">Keamanan Terjamin</span>
                </div>
                <div class="flex items-center gap-3 text-white/90">
                    <div
                        class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                        <i data-lucide="zap" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="text-sm font-medium">Proses Cepat & Real-time</span>
                </div>
                <div class="flex items-center gap-3 text-white/90">
                    <div
                        class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                        <i data-lucide="chart-bar" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="text-sm font-medium">Laporan Lengkap</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10 animate-slide-in-left" style="animation-delay: 0.4s;">
                <p class="text-xs text-blue-200">
                    © {{ date('Y') }} Vexalyn Corporation
                </p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="lg:w-3/5 bg-white dark:bg-navy-900 p-8 lg:p-12 flex flex-col justify-center">
            <div class="max-w-sm mx-auto w-full">
                <!-- Header -->
                <div class="mb-8 animate-slide-in-right">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-2">
                        Selamat Datang Kembali
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">
                        Silakan login untuk melanjutkan ke dashboard
                    </p>
                </div>

                <!-- Alerts -->
                @if(session('success'))
                    <div
                        class="mb-6 p-4 rounded-2xl bg-success/10 border border-success/20 flex items-center gap-3 animate-slide-in-right">
                        <i data-lucide="check-circle" class="w-5 h-5 text-success flex-shrink-0"></i>
                        <span class="text-sm font-medium text-success">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div
                        class="mb-6 p-4 rounded-2xl bg-danger/10 border border-danger/20 flex items-center gap-3 animate-slide-in-right">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-danger flex-shrink-0"></i>
                        <span class="text-sm font-medium text-danger">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div class="animate-slide-in-right" style="animation-delay: 0.1s;">
                        <label
                            class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <input type="email" name="email" x-model="email" value="{{ old('email') }}" required
                                autofocus
                                class="w-full rounded-2xl border border-slate-200 dark:border-white/10 pl-12 pr-4 py-3.5 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all @error('email') border-danger @enderror"
                                placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="animate-slide-in-right" style="animation-delay: 0.2s;">
                        <label
                            class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password"
                                required
                                class="w-full rounded-2xl border border-slate-200 dark:border-white/10 pl-12 pr-12 py-3.5 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all @error('password') border-danger @enderror"
                                placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword"
                                class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 transition-colors">
                                <i data-lucide="eye" x-show="!showPassword" x-cloak class="w-5 h-5"></i>
                                <i data-lucide="eye-off" x-show="showPassword" x-cloak class="w-5 h-5"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between animate-slide-in-right"
                        style="animation-delay: 0.3s;">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" x-model="remember"
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Ingat saya</span>
                        </label>
                        @if (\Illuminate\Support\Facades\Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold text-sm shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all hover:-translate-y-0.5 active:translate-y-0 animate-slide-in-right"
                        style="animation-delay: 0.4s;">
                        <i data-lucide="log-in" class="inline w-5 h-5 mr-2"></i>
                        Masuk ke Dashboard
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6 animate-slide-in-right" style="animation-delay: 0.5s;">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200 dark:border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white dark:bg-navy-900 px-4 text-slate-400">Belum punya akun?</span>
                    </div>
                </div>

                <!-- Register Link -->
                <div class="text-center animate-slide-in-right" style="animation-delay: 0.6s;">
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-blue-600 text-blue-600 font-bold text-sm hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
</body>

</html>