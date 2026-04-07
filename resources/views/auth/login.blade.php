<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - VexaMart POS</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F8FAFC 0%, #E2E8F0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        /* Main Container - Landscape PC Layout */
        .login-container {
            width: 1200px;
            max-width: 95%;
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 80px -20px rgba(15, 23, 42, 0.15);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 700px;
        }
        
        /* Left Side - Branding */
        .brand-section {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0F172A 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        
        .brand-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            animation: pulse 8s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }
        
        .brand-logo {
            position: relative;
            z-index: 2;
        }
        
        .brand-logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2563EB 0%, #3B82F6 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.4);
        }
        
        .brand-logo-icon svg {
            width: 40px;
            height: 40px;
            color: white;
        }
        
        .brand-title {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 16px;
            position: relative;
            z-index: 2;
        }
        
        .brand-description {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
        }
        
        .brand-features {
            position: relative;
            z-index: 2;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 16px;
            font-size: 0.95rem;
        }
        
        .feature-icon {
            width: 24px;
            height: 24px;
            background: rgba(37, 99, 235, 0.3);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .feature-icon svg {
            width: 14px;
            height: 14px;
            color: #60A5FA;
        }
        
        /* Right Side - Form */
        .form-section {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            position: relative;
        }
        
        .form-header {
            margin-bottom: 40px;
        }
        
        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 8px;
        }
        
        .form-subtitle {
            color: #64748B;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #0F172A;
            margin-bottom: 8px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            width: 20px;
            height: 20px;
        }
        
        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #E2E8F0;
            border-radius: 14px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            background: #F8FAFC;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #2563EB;
            background: white;
            box-shadow: 0 0 0 5px rgba(37, 99, 235, 0.1);
        }
        
        .form-input::placeholder {
            color: #94A3B8;
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            cursor: pointer;
            width: 20px;
            height: 20px;
        }
        
        .password-toggle:hover {
            color: #2563EB;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .remember-me input {
            width: 18px;
            height: 18px;
            accent-color: #2563EB;
            cursor: pointer;
        }
        
        .remember-me span {
            font-size: 0.875rem;
            color: #64748B;
        }
        
        .forgot-link {
            font-size: 0.875rem;
            color: #2563EB;
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.3);
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(15, 23, 42, 0.4);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 32px 0;
            color: #94A3B8;
            font-size: 0.875rem;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #E2E8F0, transparent);
        }
        
        .divider span {
            padding: 0 16px;
        }
        
        .social-login {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        
        .social-btn {
            width: 56px;
            height: 56px;
            border: 2px solid #E2E8F0;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            text-decoration: none;
        }
        
        .social-btn:hover {
            border-color: #2563EB;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.2);
        }
        
        .social-btn svg {
            width: 24px;
            height: 24px;
        }
        
        .demo-box {
            margin-top: 32px;
            padding: 20px;
            background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
            border-radius: 16px;
            border: 1px dashed #CBD5E1;
        }
        
        .demo-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748B;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .demo-credential {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            color: #0F172A;
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            font-family: monospace;
        }
        
        .demo-credential:last-child {
            margin-bottom: 0;
        }
        
        .error-message {
            background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
            border: 1px solid #FECACA;
            color: #DC2626;
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .success-message {
            background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
            border: 1px solid #BBF7D0;
            color: #16A34A;
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-size: 0.875rem;
        }
        
        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 32px;
            font-size: 0.9rem;
            color: #64748B;
        }
        
        .register-link a {
            color: #2563EB;
            font-weight: 600;
            text-decoration: none;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 500px;
            }
            .brand-section {
                display: none;
            }
            .form-section {
                padding: 40px 30px;
            }
        }
        
        @media (max-width: 640px) {
            body {
                padding: 20px 16px;
            }
            .form-section {
                padding: 30px 20px;
            }
            .form-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="brand-section">
            <div class="brand-logo">
                <div class="brand-logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-13.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/>
                    </svg>
                </div>
                <h1 class="brand-title">VexaMart POS</h1>
                <p class="brand-description">
                    Sistem Point of Sale modern untuk minimarket Anda. Kelola penjualan, stok, dan laporan dengan mudah dan profesional.
                </p>
            </div>
            
            <div class="brand-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span>Transaksi Cepat & Mudah</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span>Manajemen Stok Otomatis</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span>Laporan Real-time</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span>Multi Payment Support</span>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif
            
            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif
            
            @if (session('error'))
                <div class="error-message">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="nama@email.com" 
                               value="{{ old('email') }}" required autocomplete="email">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input type="password" id="password" name="password" class="form-input" 
                               placeholder="••••••••" required autocomplete="current-password">
                        <svg class="password-toggle" onclick="togglePassword()" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
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
            
            <div class="divider">
                <span>atau gunakan demo</span>
            </div>
            
            <div class="demo-box">
                <div class="demo-title">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Credential Demo:
                </div>
                <div class="demo-credential">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    Admin: admin@vexamart.com / password
                </div>
                <div class="demo-credential">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    Kasir: kasir@vexamart.com / password
                </div>
            </div>
            
            <div class="register-link">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
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
        
        document.getElementById('loginForm')?.addEventListener('submit', function(e) {
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