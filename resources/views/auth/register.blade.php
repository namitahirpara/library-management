@extends('layouts.app')

@section('title', 'Register - Library Management System')

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

    .register-container {
        min-height: calc(100vh - 100px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0;
        position: relative;
        z-index: 1;
        margin-top: 0.5rem;
    }

    /* Override main container spacing for registration page */
    main.container {
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }
</style>

<!-- Floating Elements -->
<div class="floating-element"></div>
<div class="floating-element"></div>
<div class="floating-element"></div>

<div class="register-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-12">
                <div class="card shadow-2xl border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-primary text-white text-center py-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-10 rounded-2 p-2 mb-2">
                            <i class="fas fa-user-plus fa-lg"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">Create Account</h4>
                        <p class="mb-0 opacity-75 small">Join our library management system</p>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-3 p-lg-4">
                        <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                            @csrf
                            
                            <!-- Personal Information Section -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h6 class="text-primary mb-2 fw-bold">
                                        <i class="fas fa-user-circle me-1"></i>Personal Information
                                    </h6>
                                </div>
                                
                                <div class="col-md-6 mb-2">
                                    <label for="name" class="form-label fw-semibold text-muted">
                                        <i class="fas fa-user me-1"></i>Full Name
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Enter your full name" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-2">
                                    <label for="email" class="form-label fw-semibold text-muted">
                                        <i class="fas fa-envelope me-1"></i>Email Address
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter your email address" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-2">
                                    <label for="phone" class="form-label fw-semibold text-muted">
                                        <i class="fas fa-phone me-1"></i>Phone Number
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="Enter your phone number">
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-2">
                                    <label for="address" class="form-label fw-semibold text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>Address
                                    </label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" 
                                              name="address" 
                                              rows="2"
                                              placeholder="Enter your address">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Security Section -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h6 class="text-primary mb-2 fw-bold">
                                        <i class="fas fa-shield-alt me-1"></i>Security Settings
                                    </h6>
                                </div>
                                
                                <div class="col-md-6 mb-2">
                                    <label for="password" class="form-label fw-semibold text-muted">
                                        <i class="fas fa-lock me-1"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Create a password" 
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-2">
                                    <label for="password_confirmation" class="form-label fw-semibold text-muted">
                                        <i class="fas fa-lock me-1"></i>Confirm Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Confirm password" 
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Role Selection Section -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h6 class="text-primary mb-2 fw-bold">
                                        <i class="fas fa-user-tag me-1"></i>Account Type
                                    </h6>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="role_id" class="form-label fw-semibold text-muted">
                                        <i class="fas fa-user-tag me-1"></i>Select Your Role
                                    </label>
                                    <select class="form-select @error('role_id') is-invalid @enderror" 
                                            id="role_id" 
                                            name="role_id" 
                                            required>
                                        <option value="">Choose your role...</option>
                                        <option value="1" {{ old('role_id') == '1' ? 'selected' : '' }}>
                                            <i class="fas fa-user-cog me-2"></i>Admin
                                        </option>
                                        <option value="2" {{ old('role_id') == '2' ? 'selected' : '' }}>
                                            <i class="fas fa-user-shield me-2"></i>Librarian
                                        </option>
                                        <option value="3" {{ old('role_id') == '3' ? 'selected' : '' }}>
                                            <i class="fas fa-user me-2"></i>Student
                                        </option>
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                

                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary fw-bold">
                                            <i class="fas fa-user-plus me-2"></i>Create Account
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light text-center py-2">
                        <p class="mb-0 text-muted small">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-primary">
                                <i class="fas fa-sign-in-alt me-1"></i>Sign in here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(25px);
        border-radius: 20px;
        box-shadow: var(--shadow-2xl);
        overflow: hidden;
        animation: slideInUp 0.8s ease-out;
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: var(--gradient-primary);
    }

    .form-control, .form-select, textarea.form-control {
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        font-weight: 500;
    }

    .form-control:focus, .form-select:focus, textarea.form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        background: white;
        transform: translateY(-2px);
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: var(--danger-color);
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .btn-primary {
        background: var(--gradient-primary);
        border: none;
        border-radius: 12px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-2xl);
        color: white;
    }

    .btn-outline-secondary {
        border: 2px solid var(--gray-200);
        border-left: none;
        border-radius: 0 12px 12px 0;
        background-color: var(--gray-100);
    }

    .btn-outline-secondary:hover {
        background-color: var(--gray-200);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .input-group .form-control {
        border-right: none;
        border-radius: 12px 0 0 12px;
    }

    .card-header {
        background: var(--gradient-primary) !important;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="2" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        opacity: 0.3;
    }

    .form-label {
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .form-text {
        font-size: 0.8rem;
        color: var(--gray-600);
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .card-header h4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        margin: 0;
        font-size: 1.25rem;
        position: relative;
        z-index: 1;
    }

    .card-header p {
        opacity: 0.95;
        font-size: 0.9rem;
        margin: 0.25rem 0 0;
        position: relative;
        z-index: 1;
        font-weight: 400;
    }

    .bg-light {
        background-color: var(--gray-100) !important;
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
        .card-body {
            padding: 2rem !important;
        }
        
        .card-header {
            padding: 3rem 2rem !important;
        }
        
        .card-header h2 {
            font-size: 1.5rem;
        }
        
        .card-header p {
            font-size: 1rem;
        }
        
        .card-footer {
            padding: 2rem !important;
        }

        .floating-element {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .register-container {
            padding: 1rem 0;
        }
        
        .card {
            margin: 0.5rem;
        }
        
        .card-header {
            padding: 2rem 1rem !important;
        }
        
        .card-body {
            padding: 1.5rem 1rem !important;
        }
    }
</style>

<script>
    // Password toggle functionality
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const password = document.getElementById('password_confirmation');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
@endsection 