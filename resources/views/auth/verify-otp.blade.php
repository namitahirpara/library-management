@extends('layouts.app')

@section('title', 'Verify OTP - Library Management System')

@section('content')
<style>
    :root {
        --primary-color: #4F46E5;
        --primary-dark: #3730A3;
        --secondary-color: #06B6D4;
        --accent-color: #10B981;
        --warning-color: #F59E0B;
        --danger-color: #EF4444;
        --dark-color: #1F2937;
        --light-color: #F8FAFC;
        --white-color: #FFFFFF;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-400: #9CA3AF;
        --gray-500: #6B7280;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
        --gray-900: #111827;
        
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --gradient-accent: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --gradient-success: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    body {
        background: var(--gradient-primary);
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
        position: relative;
        overflow-x: hidden;
    }

    /* Animated Background */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
        pointer-events: none;
        z-index: -1;
        animation: backgroundShift 20s ease-in-out infinite;
    }

    @keyframes backgroundShift {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(1deg); }
    }

    /* Floating Elements */
    .floating-element {
        position: absolute;
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
        pointer-events: none;
    }

    .floating-element:nth-child(1) {
        top: 15%;
        left: 15%;
        animation-delay: 0s;
    }

    .floating-element:nth-child(2) {
        top: 25%;
        right: 15%;
        animation-delay: 2s;
    }

    .floating-element:nth-child(3) {
        bottom: 25%;
        left: 10%;
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(180deg); }
    }

    .verify-otp-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem 0;
        position: relative;
        z-index: 1;
    }

    .verify-otp-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(25px);
        border-radius: 16px;
        box-shadow: var(--shadow-2xl);
        overflow: hidden;
        max-width: 400px;
        width: 100%;
        animation: slideInUp 0.8s ease-out;
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
    }

    .verify-otp-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: var(--gradient-primary);
    }

    .verify-otp-header {
        background: var(--gradient-primary);
        color: white;
        padding: 0.875rem 1.5rem 0.75rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .verify-otp-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="2" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        opacity: 0.3;
    }

    .verify-otp-icon {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-size: 1.25rem;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .verify-otp-icon:hover {
        transform: scale(1.1) rotate(5deg);
        background: rgba(255, 255, 255, 0.3);
    }

    .verify-otp-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        margin: 0;
        font-size: 1.125rem;
        position: relative;
        z-index: 1;
    }

    .verify-otp-subtitle {
        opacity: 0.95;
        font-size: 0.9rem;
        margin: 0.5rem 0 0;
        position: relative;
        z-index: 1;
        font-weight: 400;
    }

    .verify-otp-body {
        padding: 0.875rem 1.5rem;
        background: rgba(255, 255, 255, 0.8);
    }

    .form-group {
        margin-bottom: 0.75rem;
        position: relative;
    }

    .form-label {
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 0.25rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }

    .otp-input {
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
        letter-spacing: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        width: 100%;
        font-family: 'Courier New', monospace;
    }

    .otp-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        background: white;
        transform: translateY(-2px);
        outline: none;
    }

    .otp-input.is-invalid {
        border-color: var(--danger-color);
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .btn-verify-otp {
        background: var(--gradient-primary);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        width: 100%;
        margin-top: 0.5rem;
        box-shadow: var(--shadow-lg);
    }

    .btn-verify-otp::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s;
    }

    .btn-verify-otp:hover::before {
        left: 100%;
    }

    .btn-verify-otp:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-2xl);
        color: white;
    }

    .btn-verify-otp:active {
        transform: translateY(-1px);
    }

    .verify-otp-footer {
        background: rgba(248, 250, 252, 0.9);
        padding: 1rem;
        text-align: center;
        border-top: 1px solid var(--gray-200);
        backdrop-filter: blur(10px);
    }

    .verify-otp-footer a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }

    .verify-otp-footer a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--gradient-primary);
        transition: width 0.3s ease;
    }

    .verify-otp-footer a:hover {
        color: var(--primary-dark);
    }

    .verify-otp-footer a:hover::after {
        width: 100%;
    }

    .invalid-feedback {
        color: var(--danger-color);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        font-weight: 500;
    }

    .invalid-feedback i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
        font-weight: 500;
        backdrop-filter: blur(10px);
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .alert-info {
        background: rgba(59, 130, 246, 0.1);
        color: var(--primary-color);
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .email-display {
        background: rgba(79, 70, 229, 0.1);
        border: 1px solid rgba(79, 70, 229, 0.2);
        border-radius: 8px;
        padding: 0.75rem;
        margin-bottom: 1rem;
        text-align: center;
        color: var(--primary-color);
        font-weight: 600;
    }

    .resend-otp {
        text-align: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-200);
    }

    .resend-otp a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .resend-otp a:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-group {
        animation: fadeInUp 0.6s ease-out;
        animation-delay: calc(var(--field-index, 0) * 0.1s);
    }

    @media (max-width: 768px) {
        .verify-otp-card {
            margin: 1rem;
            max-width: none;
        }
        
        .verify-otp-header {
            padding: 2rem 1.5rem 1.5rem;
        }
        
        .verify-otp-body {
            padding: 2rem 1.5rem;
        }

        .verify-otp-title {
            font-size: 1.75rem;
        }

        .floating-element {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .verify-otp-container {
            padding: 1rem 0;
        }
        
        .verify-otp-card {
            margin: 0.5rem;
        }
        
        .verify-otp-header {
            padding: 1.5rem 1rem 1rem;
        }
        
        .verify-otp-body {
            padding: 1.5rem 1rem;
        }
    }
</style>

<!-- Floating Elements -->
<div class="floating-element"></div>
<div class="floating-element"></div>
<div class="floating-element"></div>

<div class="verify-otp-container">
    <div class="verify-otp-card">
        <div class="verify-otp-header">
            <div class="verify-otp-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1 class="verify-otp-title">Verify OTP</h1>
            <p class="verify-otp-subtitle">Enter the 6-digit code sent to your email</p>
        </div>
        
        <div class="verify-otp-body">
            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                </div>
            @endif

            <div class="email-display">
                <i class="fas fa-envelope me-2"></i>{{ $email ?? session('email') }}
            </div>

            <form method="POST" action="{{ route('password.verify.submit') }}">
                @csrf
                
                <div class="form-group" style="--field-index: 1;">
                    <label for="otp" class="form-label">Enter OTP</label>
                    <input type="text" 
                           class="otp-input @error('otp') is-invalid @enderror" 
                           id="otp" 
                           name="otp" 
                           placeholder="000000" 
                           maxlength="6" 
                           pattern="[0-9]{6}" 
                           inputmode="numeric"
                           autocomplete="one-time-code"
                           required 
                           autofocus>
                    @error('otp')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-verify-otp">
                    <i class="fas fa-check me-2"></i>Verify OTP
                </button>
            </form>

            <div class="resend-otp">
                <a href="{{ route('password.request') }}">
                    <i class="fas fa-redo me-1"></i>Resend OTP
                </a>
            </div>
        </div>
        
        <div class="verify-otp-footer">
            <a href="{{ route('login') }}">
                <i class="fas fa-arrow-left me-1"></i>Back to Login
            </a>
        </div>
    </div>
</div>

<script>
    // Auto-focus and format OTP input
    const otpInput = document.getElementById('otp');
    
    otpInput.addEventListener('input', function(e) {
        // Remove any non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limit to 6 digits
        if (this.value.length > 6) {
            this.value = this.value.slice(0, 6);
        }
        
        // Auto-submit when 6 digits are entered
        if (this.value.length === 6) {
            this.form.submit();
        }
    });

    // Handle paste events
    otpInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const pastedData = (e.clipboardData || window.clipboardData).getData('text');
        const numbers = pastedData.replace(/[^0-9]/g, '').slice(0, 6);
        this.value = numbers;
        
        if (numbers.length === 6) {
            this.form.submit();
        }
    });

    // Handle backspace to clear field
    otpInput.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && this.value.length === 0) {
            e.preventDefault();
        }
    });
</script>
@endsection 