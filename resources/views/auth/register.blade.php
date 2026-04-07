<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - VexaMart POS</title>
    
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
        
        .register-container {
            width: 1200px;
            max-width: 95%;
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 80px -20px rgba(15, 23, 42, 0.15);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 750px;
        }
        
        .brand-section {
            background: linear-gradient(135deg, #2563EB 0%, #3B82F6 50%, #2563EB 100%);
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
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
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
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
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
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
        }
        
        .benefits-list {
            position: relative;
            z-index: 2;
        }
        
        .benefit-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            color: white;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        .benefit-icon {
            width: 28px;
            height: 28px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .benefit-icon svg {
            width: 16px;
            height: 16px;
            color: white;
        }
        
        .benefit-text strong {
            display: block;
            margin-bottom: 4px;
        }
        
        .benefit-text span {
            opacity: 0.8;
            font-size: 0.85rem;
        }
        
        .form-section {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            overflow-y: auto;
            max-height: 750px;
        }
        
        .form-header {
            margin-bottom: 32px;
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
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #0F172A;
            margin-bottom: 8px;
        }
        
        .form-label .required {
            color: #DC2626;
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
        
        .form-input.error {
            border-color: #DC2626;
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
        
        .password-strength {
            margin-top: 8px;
        }
        
        .strength-bar {
            height: 4px;
            background: #E2E8F0;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 6px;
        }
        
        .strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        
        .strength-fill.weak { width: 25%; background: #EF4444; }
        .strength-fill.fair { width: 50%; background: #F59E0B; }
        .strength-fill.good { width: 75%; background: #3B82F6; }
        .strength-fill.strong { width: 100%; background: #16A34A; }
        
        .strength-text {
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .strength-text.weak { color: #EF4444; }
        .strength-text.fair { color: #F59E0B; }
        .strength-text.good { color: #3B82F6; }
        .strength-text.strong { color: #16A34A; }
        
        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 24px;
        }
        
        .terms-checkbox input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            accent-color: #2563EB;
            cursor: pointer;
        }
        
        .terms-checkbox label {
            font-size: 0.875rem;
            color: #64748B;
            cursor: pointer;
            line-height: 1.5;
        }
        
        .terms-checkbox a {
            color: #2563EB;
            font-weight: 500;
            text-decoration: none;
        }
        
        .terms-checkbox a:hover {
            text-decoration: underline;
        }
        
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #2563EB 0%, #3B82F6 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.3);
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(37, 99, 235, 0.4);
        }
        
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 0.9rem;
            color: #64748B;
        }
        
        .login-link a {
            color: #2563EB;
            font-weight: 600;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 1024px) {
            .register-container {
                grid-template-columns: 1fr;
                max-width: 500px;
            }
            .brand-section {
                display: none;
            }
            .form-section {
                padding: 40px 30px;
                max-height: none;
            }
        }
        
        @media (max-width: 640px) {
            body {
                padding: 20px 16px;
            }
            .form-section {
                padding: 30px 20px;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Left Side - Branding -->
        <div class="brand-section">
            <div class="brand-logo">
                <div class="brand-logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-13.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/>
                    </svg>
                </div>
                <h1 class="brand-title">Bergabung dengan VexaMart</h1>
                <p class="brand-description">
                    Mulai kelola minimarket Anda dengan sistem POS modern yang mudah digunakan dan profesional.
                </p>
            </div>
            
            <div class="benefits-list">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="benefit-text">
                        <strong>Gratis 14 Hari Trial</strong>
                        <span>Coba semua fitur premium tanpa biaya</span>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="benefit-text">
                        <strong>Multi User Support</strong>
                        <span>Tambahkan kasir dan staff tanpa batas</span>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="benefit-text">
                        <strong>Cloud Backup</strong>
                        <span>Data aman dan bisa diakses kapan saja</span>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="benefit-text">
                        <strong>24/7 Support</strong>
                        <span>Tim support siap membantu Anda</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Register Form -->
        <div class="form-section">
            <div class="form-header">
                <h2 class="form-title">Buat Akun Baru 🎉</h2>
                <p class="form-subtitle">Lengkapi form di bawah untuk memulai</p>
            </div>
            
            @if ($errors->any())
                <div class="error-message">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <input type="text" id="name" name="name" class="form-input" 
                                   placeholder="John Doe" value="{{ old('name') }}" required autocomplete="name">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="phone">No. Telepon</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <input type="tel" id="phone" name="phone" class="form-input" 
                                   placeholder="08123456789" value="{{ old('phone') }}" autocomplete="tel">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="email">Email Address <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="nama@email.com" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="password">Password <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password" id="password" name="password" class="form-input" 
                                   placeholder="••••••••" required oninput="checkPasswordStrength()" autocomplete="new-password">
                            <svg class="password-toggle" onclick="togglePassword('password', this)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <p class="strength-text" id="strengthText"></p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" 
                                   placeholder="••••••••" required autocomplete="new-password">
                        </div>
                    </div>
                </div>
                
                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        Saya menyetujui <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a> VexaMart
                    </label>
                </div>
                
                <button type="submit" class="btn-submit" id="submitBtn" disabled>
                    <span id="btnText">Daftar Sekarang</span>
                    <span id="btnLoading" style="display:none;">Memproses...</span>
                </button>
            </form>
            
            <div class="login-link">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
    
    <script>
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
        
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            const submitBtn = document.getElementById('submitBtn');
            
            if (!password) {
                strengthFill.className = 'strength-fill';
                strengthText.className = 'strength-text';
                strengthText.textContent = '';
                submitBtn.disabled = true;
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
                strengthText.textContent = 'Lemah';
                strengthText.classList.add('weak');
                submitBtn.disabled = true;
            } else if (strength === 2) {
                strengthFill.classList.add('fair');
                strengthText.textContent = 'Cukup';
                strengthText.classList.add('fair');
                submitBtn.disabled = true;
            } else if (strength === 3 || strength === 4) {
                strengthFill.classList.add('good');
                strengthText.textContent = 'Bagus';
                strengthText.classList.add('good');
                submitBtn.disabled = false;
            } else if (strength === 5) {
                strengthFill.classList.add('strong');
                strengthText.textContent = 'Sangat Kuat! ✓';
                strengthText.classList.add('strong');
                submitBtn.disabled = false;
            }
        }
        
        document.getElementById('registerForm')?.addEventListener('submit', function(e) {
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