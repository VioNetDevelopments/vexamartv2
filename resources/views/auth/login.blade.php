<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VexaMart POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-navy-950 flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-accent-500 text-white shadow-glow mb-4">
                <i data-lucide="shopping-bag" class="w-8 h-8"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">VexaMart</h1>
            <p class="text-slate-400">Point of Sale System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-navy-900 rounded-2xl shadow-2xl border border-white/10 p-8">
            <h2 class="text-xl font-semibold text-white mb-6">Sign In</h2>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-danger/10 border border-danger/20 text-danger text-sm">
                    <i data-lucide="alert-circle" class="inline w-4 h-4 mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" x-data="{ showPassword: false }">
                @csrf

                <!-- Email -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500"></i>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full bg-navy-800 border border-white/10 rounded-xl pl-10 pr-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-accent-500 focus:ring-1 focus:ring-accent-500 transition-all @error('email') border-danger @enderror"
                               placeholder="admin@vexamart.com" required autofocus>
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500"></i>
                        <input :type="showPassword ? 'text' : 'password'" name="password"
                               class="w-full bg-navy-800 border border-white/10 rounded-xl pl-10 pr-12 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-accent-500 focus:ring-1 focus:ring-accent-500 transition-all @error('password') border-danger @enderror"
                               placeholder="••••••••" required>
                        <button type="button" @click="showPassword = !showPassword" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                            <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" 
                               class="w-4 h-4 rounded border-white/20 bg-navy-800 text-accent-500 focus:ring-accent-500 focus:ring-offset-0">
                        <span class="text-sm text-slate-400">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-accent-500 hover:text-accent-400 transition-colors">Forgot password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-accent-500 hover:bg-accent-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-accent-500/30 transition-all duration-200 hover:shadow-accent-500/50 hover:-translate-y-0.5">
                    Sign In
                </button>
            </form>

            <!-- Demo Credentials -->
            <div class="mt-6 pt-6 border-t border-white/10">
                <p class="text-xs text-slate-500 text-center mb-3">Demo Credentials</p>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="bg-navy-800 rounded-lg p-3">
                        <p class="text-slate-400 mb-1">Admin</p>
                        <p class="text-white font-mono">admin@vexamart.com</p>
                        <p class="text-slate-500 font-mono">password</p>
                    </div>
                    <div class="bg-navy-800 rounded-lg p-3">
                        <p class="text-slate-400 mb-1">Cashier</p>
                        <p class="text-white font-mono">kasir@vexamart.com</p>
                        <p class="text-slate-500 font-mono">password</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-slate-500 text-sm mt-8">
            © {{ date('Y') }} VexaMart POS. All rights reserved.
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>