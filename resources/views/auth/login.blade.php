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
            background: #FFFFFF;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-container {
            position: relative;
            width: 1000px;
            max-width: 95%;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.15);
            overflow: hidden;
            display: flex;
            min-height: 650px;
        }
        
        /* Panel Kiri (Navy Blue Theme) */
        .auth-panel {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            transition: transform 0.8s cubic-bezier(0.77, 0, 0.175, 1);
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 35px;
            text-align: center;
        }
        
        .auth-panel.login {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            transform: translateX(0);
        }
        
        .auth-panel.register {
            background: linear-gradient(135deg, #2563EB 0%, #3B82F6 100%);
            transform: translateX(100%);
        }
        
        .logo-container {
            width: 90px;
            height: 90px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
        }
        
        .logo-container svg {
            width: 50px;
            height: 50px;
            color: white;
        }
        
        .panel-content { color: white; z-index: 1; }
        .panel-content h1 { 
            font-size: 1.5rem; 
            font-weight: 700; 
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }
        .panel-content p { 
            font-size: 0.9rem; 
            opacity: 0.9; 
            margin-bottom: 2rem;
            line-height: 1.5;
        }
        
        .btn-toggle {
            padding: 14px 45px;
            border: 2px solid white;
            background: transparent;
            color: white;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.4s ease;
        }
        
        .btn-toggle:hover {
            background: white;
            color: #0F172A;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* Container Form di Kanan */
        .forms-container {
            position: absolute;
            right: 0;
            top: 0;
            width: 50%;
            height: 100%;
            overflow: hidden;
        }
        
        /* Individual Forms */
        .form-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            transition: all 0.6s ease-in-out;
        }
        
        /* Login Form - Default Visible */
        #loginForm {
            opacity: 1;
            z-index: 2;
            transform: translateX(0);
        }
        
        /* Register Form - Default Hidden */
        #registerForm {
            opacity: 0;
            z-index: 1;
            transform: translateX(100%);
        }
        
        /* Saat Toggle ke Register */
        #loginForm.hidden {
            opacity: 0;
            z-index: 1;
            transform: translateX(-100%);
            pointer-events: none;
        }
        
        #registerForm.visible {
            opacity: 1;
            z-index: 2;
            transform: translateX(0);
            pointer-events: auto;
        }
        
        .form-header { margin-bottom: 2rem; }
        .form-header h2 { 
            font-size: 1.75rem; 
            font-weight: 700; 
            color: #0F172A; 
            margin-bottom: 0.5rem; 
        }
        .form-header p { color: #64748B; font-size: 0.95rem; }
        
        .input-group { width: 100%; margin-bottom: 1.25rem; }
        .input-group label { 
            display: block; 
            font-size: 0.85rem; 
            font-weight: 600; 
            color: #0F172A; 
            margin-bottom: 0.5rem; 
        }
        
        .input-wrapper { position: relative; }
        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 0.95rem;
            background: #F8FAFC;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }
        .input-wrapper input:focus {
            outline: none;
            border-color: #2563EB;
            background: white;
            box-shadow: 0 0 0 5px rgba(37, 99, 235, 0.15);
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
        .password-toggle:hover { color: #2563EB; }
        
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #0F172A;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.25);
            margin-top: 0.75rem;
        }
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.35);
            background: #1E293B;
        }
        
        .error-message {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .success-message {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            color: #16A34A;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0 1.5rem;
            font-size: 0.85rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748B;
            cursor: pointer;
        }
        
        .remember-me input {
            width: 16px;
            height: 16px;
            accent-color: #2563EB;
            cursor: pointer;
        }
        
        .forgot-link {
            color: #2563EB;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link:hover { text-decoration: underline; }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #94A3B8;
            font-size: 0.85rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #E2E8F0, transparent);
        }
        .divider span { padding: 0 1.25rem; }
        
        .social-login { display: flex; justify-content: center; gap: 1rem; }
        .social-btn {
            width: 50px;
            height: 50px;
            border: 2px solid #E2E8F0;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.4s ease;
            background: white;
            text-decoration: none;
        }
        .social-btn:hover {
            border-color: #2563EB;
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.2);
        }
        .social-btn svg { width: 24px; height: 24px; }
        
        .demo-credentials {
            margin-top: 2rem;
            padding: 1rem;
            background: #F8FAFC;
            border-radius: 12px;
            border: 1px dashed #CBD5E1;
        }
        .demo-credentials p {
            font-size: 0.75rem;
            color: #64748B;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .demo-credentials code {
            display: block;
            font-size: 0.7rem;
            color: #0F172A;
            background: white;
            padding: 4px 8px;
            border-radius: 4px;
            margin: 2px 0;
            font-family: monospace;
        }
        
        .terms-text {
            font-size: 0.75rem;
            color: #94A3B8;
            text-align: center;
            margin-top: 1.5rem;
            line-height: 1.6;
        }
        .terms-text a { color: #2563EB; font-weight: 600; text-decoration: none; }
        .terms-text a:hover { text-decoration: underline; }
        
        .password-strength { margin-top: 0.75rem; }
        .strength-bar { height: 4px; background: #E2E8F0; border-radius: 2px; overflow: hidden; margin-bottom: 0.5rem; }
        .strength-fill { height: 100%; width: 0%; transition: all 0.3s ease; border-radius: 2px; }
        .strength-fill.weak { width: 25%; background: #EF4444; }
        .strength-fill.fair { width: 50%; background: #F59E0B; }
        .strength-fill.good { width: 75%; background: #3B82F6; }
        .strength-fill.strong { width: 100%; background: #16A34A; }
        .strength-text { font-size: 0.75rem; font-weight: 600; text-align: right; }
        .strength-text.weak { color: #EF4444; }
        .strength-text.fair { color: #F59E0B; }
        .strength-text.good { color: #3B82F6; }
        .strength-text.strong { color: #16A34A; }
        
        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .logo-container { animation: float 3s ease-in-out infinite; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .auth-container { 
                flex-direction: column; 
                height: auto; 
                min-height: auto;
                border-radius: 16px;
            }
            .auth-panel { 
                display: none; 
            }
            .forms-container { 
                position: relative; 
                width: 100%; 
                height: auto;
                left: 0;
            }
            .form-container { 
                position: relative; 
                width: 100%; 
                opacity: 1 !important; 
                z-index: 2 !important; 
                padding: 30px 25px;
                transform: translateX(0) !important;
            }
            #registerForm { display: none; }
            #registerForm.visible { display: flex; }
            .form-header h2 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Sliding Panel (KIRI) -->
        <div class="auth-panel login" id="authPanel">
            <div class="logo-container">
                <!-- VexaMart Logo Icon -->
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-13.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/>
                </svg>
            </div>
            <div class="panel-content">
                <h1 id="panelTitle">Selamat Datang di VexaMart</h1>
                <p id="panelText">Sistem Point of Sale Modern untuk Minimarket Anda - Cepat, Aman, dan Profesional</p>
                <button class="btn-toggle" id="toggleBtn" onclick="toggleAuth()">Buat Akun Baru</button>
            </div>
        </div>
        
        <!-- Forms Container (KANAN) -->
        <div class="forms-container">
            <!-- Login Form -->
            <div class="form-container" id="loginForm">
                <div class="form-header">
                    <h2>Masuk ke Akun</h2>
                    <p>Silakan login untuk mengakses dashboard VexaMart</p>
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
                
                <form method="POST" action="{{ route('login') }}" id="loginFormEl">
                    @csrf
                    <div class="input-group">
                        <label for="login-email">Email Address</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <input type="email" id="login-email" name="email" placeholder="admin@vexamart.com" required value="{{ old('email') }}" autocomplete="email">
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label for="login-password">Password</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password" id="login-password" name="password" placeholder="••••••••" required autocomplete="current-password">
                            <svg class="password-toggle" id="toggleLoginPassword" fill="none" stroke="currentColor" viewBox="0 0 24 24" onclick="togglePassword('login-password', this)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="remember-forgot">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Ingat Saya</span>
                        </label>
                        <a href="#" class="forgot-link">Lupa Password?</a>
                    </div>
                    
                    <button type="submit" class="btn-submit" id="loginBtn">
                        <span id="loginBtnText">Masuk ke Dashboard</span>
                        <span id="loginBtnLoading" style="display:none;">Memproses...</span>
                    </button>
                </form>
                
                <div class="divider"><span>atau gunakan demo</span></div>
                
                <!-- Demo Credentials -->
                <div class="demo-credentials">
                    <p>🔑 Credential Demo:</p>
                    <code>👤 Admin: admin@vexamart.com / password</code>
                    <code>👤 Kasir: kasir@vexamart.com / password</code>
                </div>
            </div>
            
            <!-- Register Form -->
            <div class="form-container" id="registerForm">
                <div class="form-header">
                    <h2>Buat Akun Baru</h2>
                    <p>Lengkapi form untuk bergabung dengan VexaMart</p>
                </div>
                
                @if ($errors->any())
                    <div class="error-message">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <form method="POST" action="#" id="registerFormEl">
                    @csrf
                    <div class="input-group">
                        <label for="register-name">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <input type="text" id="register-name" name="name" placeholder="John Doe" required value="{{ old('name') }}" autocomplete="name">
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label for="register-email">Email</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <input type="email" id="register-email" name="email" placeholder="nama@email.com" required value="{{ old('email') }}" autocomplete="email">
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label for="register-password">Password</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password" id="register-password" name="password" placeholder="••••••••" required oninput="checkPasswordStrength()" autocomplete="new-password">
                            <svg class="password-toggle" id="toggleRegisterPassword" fill="none" stroke="currentColor" viewBox="0 0 24 24" onclick="togglePassword('register-password', this)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                            <p class="strength-text" id="strengthText"></p>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label for="register-password-confirm">Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password" id="register-password-confirm" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit" id="registerBtn">
                        <span id="registerBtnText">Daftar Sekarang</span>
                        <span id="registerBtnLoading" style="display:none;">Memproses...</span>
                    </button>
                    
                    <p class="terms-text">
                        Dengan mendaftar, Anda menyetujui <a href="#">Syarat & Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> VexaMart.
                    </p>
                </form>
                
                <div class="divider"><span>atau daftar dengan</span></div>
                
                <div class="social-login">
                    <a href="#" class="social-btn" title="Google" onclick="return false;">
                        <svg viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-btn" title="GitHub" onclick="return false;">
                        <svg viewBox="0 0 24 24" fill="#171717">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle between Login and Register
        function toggleAuth() {
            const panel = document.getElementById('authPanel');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const panelTitle = document.getElementById('panelTitle');
            const panelText = document.getElementById('panelText');
            const toggleBtn = document.getElementById('toggleBtn');
            
            if (loginForm.classList.contains('hidden')) {
                // Show Login
                panel.classList.remove('register');
                panel.classList.add('login');
                loginForm.classList.remove('hidden');
                registerForm.classList.remove('visible');
                panelTitle.textContent = 'Selamat Datang di VexaMart';
                panelText.textContent = 'Sistem Point of Sale Modern untuk Minimarket Anda - Cepat, Aman, dan Profesional';
                toggleBtn.textContent = 'Buat Akun Baru';
            } else {
                // Show Register
                panel.classList.remove('login');
                panel.classList.add('register');
                loginForm.classList.add('hidden');
                registerForm.classList.add('visible');
                panelTitle.textContent = 'Halo, Selamat Datang!';
                panelText.textContent = 'Bergabunglah dengan VexaMart dan kelola toko Anda dengan lebih efisien';
                toggleBtn.textContent = 'Sudah Punya Akun?';
            }
        }
        
        // Toggle Password Visibility
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
        
        // Password Strength Checker
        function checkPasswordStrength() {
            const password = document.getElementById('register-password').value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            
            if (!password) {
                strengthFill.className = 'strength-fill';
                strengthText.className = 'strength-text';
                strengthText.textContent = '';
                return;
            }
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[^a-zA-Z0-9]+/)) strength++;
            
            strengthFill.className = 'strength-fill';
            strengthText.className = 'strength-text';
            
            if (strength <= 1) {
                strengthFill.classList.add('weak');
                strengthText.textContent = 'Lemah - Tambahkan karakter';
                strengthText.classList.add('weak');
            } else if (strength === 2) {
                strengthFill.classList.add('fair');
                strengthText.textContent = 'Cukup - Tambahkan angka/simbol';
                strengthText.classList.add('fair');
            } else if (strength === 3 || strength === 4) {
                strengthFill.classList.add('good');
                strengthText.textContent = 'Bagus - Password cukup kuat';
                strengthText.classList.add('good');
            } else if (strength === 5) {
                strengthFill.classList.add('strong');
                strengthText.textContent = 'Sangat Kuat! ✓';
                strengthText.classList.add('strong');
            }
        }
        
        // Form Loading State
        document.getElementById('loginFormEl')?.addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const btnText = document.getElementById('loginBtnText');
            const btnLoading = document.getElementById('loginBtnLoading');
            
            btn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
        });
        
        document.getElementById('registerFormEl')?.addEventListener('submit', function(e) {
            const btn = document.getElementById('registerBtn');
            const btnText = document.getElementById('registerBtnText');
            const btnLoading = document.getElementById('registerBtnLoading');
            
            btn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
        });
        
        // Initialize Lucide Icons if available
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>