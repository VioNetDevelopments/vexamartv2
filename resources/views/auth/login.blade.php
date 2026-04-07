<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - VexaMart POS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 900px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            max-height: 90vh;
        }

        .brand-section {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            padding: 40px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
        }

        .brand-logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .brand-logo-icon svg {
            width: 32px;
            height: 32px;
            color: white;
        }

        .brand-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .brand-description {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .feature-icon {
            width: 20px;
            height: 20px;
            background: rgba(59, 130, 246, 0.3);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon svg {
            width: 12px;
            height: 12px;
            color: #60A5FA;
        }

        .form-section {
            padding: 40px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        .form-header {
            margin-bottom: 28px;
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 6px;
        }

        .form-subtitle {
            color: #64748B;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #0F172A;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            width: 18px;
            height: 18px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 44px;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            background: #F8FAFC;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #3B82F6;
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        .password-toggle:hover {
            color: #3B82F6;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-me input {
            width: 16px;
            height: 16px;
            accent-color: #3B82F6;
            cursor: pointer;
        }

        .remember-me span {
            font-size: 0.85rem;
            color: #64748B;
        }

        .forgot-link {
            font-size: 0.85rem;
            color: #3B82F6;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.4);
        }


        .error-message {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .success-message {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            color: #16A34A;
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }


        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                max-height: none;
            }

            .brand-section {
                display: none;
            }

            .form-section {
                padding: 30px 25px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="brand-section">
            <div>
                <div class="brand-logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-13.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                    </svg>
                </div>
                <h1 class="brand-title">VexaMart POS</h1>
                <p class="brand-description">
                    Sistem Point of Sale modern untuk minimarket Anda. Kelola penjualan, stok, dan laporan dengan mudah.
                </p>
            </div>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span>Transaksi Cepat</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span>Stok Otomatis</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span>Laporan Real-time</span>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="form-section">
            <div class="form-header">
                <h2 class="form-title">Selamat Datang! 👋</h2>
                <p class="form-subtitle">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            @if ($errors->any())
                <div class="error-message">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        <input type="email" id="email" name="email" class="form-input" placeholder="nama@email.com"
                            value="{{ old('email') }}" required autocomplete="email">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••"
                            required autocomplete="current-password">
                        <svg class="password-toggle" onclick="togglePassword()" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat Saya</span>
                    </label>
                    <a href="#" class="forgot-link">Lupa Password?</a>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span id="btnText">Masuk ke Dashboard</span>
                    <span id="btnLoading" style="display:none;">Memproses...</span>
                </button>
            </form>

            <div class="register-link" style="margin-top: 20px; text-align: center; padding-top: 16px; border-top: 1px solid #E2E8F0;">
                <span style="color: #64748B; font-size: 0.85rem;">Belum punya akun?</span>
                <a href="{{ route('register') }}" style="color: #3B82F6; font-weight: 600; margin-left: 6px; text-decoration: none;">Daftar sekarang</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                passwordInput.type = 'password';
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }

        document.getElementById('loginForm')?.addEventListener('submit', function (e) {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');

            btn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
        });
    </script>
</body>

</html>