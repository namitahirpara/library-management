@extends('layouts.app')

@section('title', 'Login - Library Management System')

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

    .login-container {
        min-height: calc(100vh - 100px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0;
        position: relative;
        z-index: 1;
        margin-top: -20px;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(25px);
        border-radius: 16px;
        box-shadow: var(--shadow-2xl);
        overflow: hidden;
        max-width: 500px;
        width: 100%;
        animation: slideInUp 0.8s ease-out;
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
    }

    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: var(--gradient-primary);
    }

    .login-header {
        background: var(--gradient-primary);
        color: white;
        padding: 0.875rem 1.5rem 0.75rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .login-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="2" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        opacity: 0.3;
    }

    .login-icon {
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

    .login-icon:hover {
        transform: scale(1.1) rotate(5deg);
        background: rgba(255, 255, 255, 0.3);
    }

    .login-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        margin: 0;
        font-size: 1.125rem;
        position: relative;
        z-index: 1;
    }

    .login-subtitle {
        opacity: 0.95;
        font-size: 0.9rem;
        margin: 0.5rem 0 0;
        position: relative;
        z-index: 1;
        font-weight: 400;
    }

    .login-body {
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

    .form-control {
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        padding: 0.75rem 1rem 0.75rem 3rem;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        font-weight: 500;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        background: white;
        transform: translateY(-2px);
    }

    .form-control.is-invalid {
        border-color: var(--danger-color);
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .input-group {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-color);
        z-index: 10;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus + .input-icon {
        color: var(--primary-dark);
        transform: translateY(-50%) scale(1.1);
    }

    .form-check {
        margin: 1rem 0;
        display: flex;
        align-items: center;
        padding: 0;
    }

    .form-check-input {
        border: 2px solid var(--primary-color);
        border-radius: 8px;
        margin-right: 0.75rem;
        width: 1.2rem;
        height: 1.2rem;
        transition: all 0.3s ease;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }

    .form-check-label {
        color: var(--gray-700);
        font-weight: 500;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .btn-login {
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

    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s;
    }

    .btn-login:hover::before {
        left: 100%;
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-2xl);
        color: white;
    }

    .btn-login:active {
        transform: translateY(-1px);
    }

    .login-footer {
        background: rgba(248, 250, 252, 0.9);
        padding: 1rem;
        text-align: center;
        border-top: 1px solid var(--gray-200);
        backdrop-filter: blur(10px);
    }

    .login-footer a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }

    .login-footer a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--gradient-primary);
        transition: width 0.3s ease;
    }

    .login-footer a:hover {
        color: var(--primary-dark);
    }

    .login-footer a:hover::after {
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

    .forgot-password-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .forgot-password-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--gradient-primary);
        transition: width 0.3s ease;
    }

    .forgot-password-link:hover {
        color: var(--primary-dark);
    }

    .forgot-password-link:hover::after {
        width: 100%;
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
        .login-container {
            margin-top: -15px;
        }
        
        .login-card {
            margin: 0.5rem;
            max-width: none;
        }
        
        .login-header {
            padding: 1.5rem 1.25rem 1.25rem;
        }
        
        .login-body {
            padding: 1.5rem 1.25rem;
        }

        .login-title {
            font-size: 1.5rem;
        }

        .floating-element {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .login-container {
            padding: 0.25rem 0;
            margin-top: -10px;
        }
        
        .login-card {
            margin: 0.25rem;
        }
        
        .login-header {
            padding: 1rem 0.75rem 0.75rem;
        }
        
        .login-body {
            padding: 1rem 0.75rem;
        }
    }
</style>

<!-- Floating Elements -->
<div class="floating-element"></div>
<div class="floating-element"></div>
<div class="floating-element"></div>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to your library account</p>
        </div>
        
        <div class="login-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group" style="--field-index: 1;">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="Enter your email address" required autofocus>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group" style="--field-index: 2;">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group" style="--field-index: 3;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me for 30 days
                            </label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-password-link">
                            <i class="fas fa-key me-1"></i>Forgot Password?
                        </a>
                    </div>
                </div>

                <button type="submit" class="btn btn-login" style="--field-index: 4;">
                    <i class="fas fa-sign-in-alt me-2"></i>Sign In to Dashboard
                </button>
            </form>
        </div>
        
        <div class="login-footer">
            <p class="mb-0">
                <i class="fas fa-user-plus me-1"></i>
                Don't have an account? 
                <a href="{{ route('register') }}">Create one here</a>
            </p>
        </div>
    </div>
</div>

<script>
    // Add enhanced interactions
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // Add button click effect
    document.querySelector('.btn-login').addEventListener('click', function() {
        this.style.transform = 'translateY(-1px) scale(0.98)';
        setTimeout(() => {
            this.style.transform = 'translateY(-3px) scale(1)';
        }, 150);
    });
</script>
@endsection 