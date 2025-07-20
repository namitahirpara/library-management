<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Library Management System</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Custom Styles -->
        <style>
            :root {
                --primary: #4F46E5;
                --primary-dark: #3730A3;
                --secondary: #06B6D4;
                --success: #10B981;
                --warning: #F59E0B;
                --danger: #EF4444;
                --dark: #1F2937;
                --light: #F8FAFC;
                --white: #FFFFFF;
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

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                background: var(--gradient-primary);
                min-height: 100vh;
                position: relative;
                overflow-x: hidden;
                color: var(--gray-800);
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

            /* Floating Elements - Smaller */
            .floating-element {
                position: absolute;
                width: 40px;
                height: 40px;
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
                50% { transform: translateY(-10px) rotate(180deg); }
            }

            /* Navigation - Compact */
            .navbar-custom {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: none;
                border-radius: 15px;
                margin: 0.5rem 0;
                box-shadow: var(--shadow-lg);
                padding: 0.5rem 0;
                transition: all 0.3s ease;
            }

            .navbar-custom:hover {
                box-shadow: var(--shadow-xl);
            }

            .navbar-brand-custom {
                font-family: 'Poppins', sans-serif;
                font-weight: 700;
                font-size: 1.25rem;
                background: var(--gradient-primary);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                text-decoration: none;
            }

            .btn-custom {
                border-radius: 10px;
                padding: 0.5rem 1rem;
                font-weight: 600;
                font-size: 0.85rem;
                transition: all 0.3s ease;
                border: none;
                position: relative;
                overflow: hidden;
                text-decoration: none;
                display: inline-block;
            }

            .btn-custom::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .btn-custom:hover::before {
                left: 100%;
            }

            .btn-primary-custom {
                background: var(--gradient-primary);
                color: white;
                box-shadow: var(--shadow-md);
            }

            .btn-primary-custom:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
                color: white;
            }

            .btn-outline-custom {
                background: rgba(255, 255, 255, 0.9);
                color: var(--primary);
                border: 1px solid var(--primary);
                backdrop-filter: blur(10px);
            }

            .btn-outline-custom:hover {
                background: var(--primary);
                color: white;
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
            }

            /* Hero Section - Compact */
            .hero-section {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(25px);
                border-radius: 20px;
                box-shadow: var(--shadow-xl);
                border: 1px solid rgba(255, 255, 255, 0.3);
                position: relative;
                overflow: hidden;
                margin: 1.5rem 0;
                padding: 2.5rem 2rem;
                animation: slideInUp 1s ease-out;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: var(--gradient-primary);
            }

            .hero-title {
                font-family: 'Poppins', sans-serif;
                font-weight: 800;
                font-size: 2.5rem;
                background: var(--gradient-primary);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 1rem;
                line-height: 1.2;
            }

            .hero-subtitle {
                font-size: 1rem;
                color: var(--gray-600);
                line-height: 1.6;
                margin-bottom: 2rem;
                font-weight: 400;
            }

            /* Feature Cards - Compact */
            .feature-card {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: 15px;
                padding: 1.5rem 1rem;
                text-align: center;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
                height: 100%;
                box-shadow: var(--shadow-md);
            }

            .feature-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: var(--gradient-primary);
                transform: scaleX(0);
                transition: transform 0.4s ease;
            }

            .feature-card:hover {
                transform: translateY(-8px) scale(1.02);
                box-shadow: var(--shadow-xl);
            }

            .feature-card:hover::before {
                transform: scaleX(1);
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                background: var(--gradient-primary);
                border-radius: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                color: white;
                font-size: 1.5rem;
                box-shadow: var(--shadow-lg);
                transition: all 0.4s ease;
                position: relative;
            }

            .feature-icon::after {
                content: '';
                position: absolute;
                top: -3px;
                left: -3px;
                right: -3px;
                bottom: -3px;
                background: var(--gradient-primary);
                border-radius: 18px;
                z-index: -1;
                opacity: 0.3;
                transition: all 0.4s ease;
            }

            .feature-card:hover .feature-icon {
                transform: scale(1.1) rotate(5deg);
            }

            .feature-card:hover .feature-icon::after {
                transform: scale(1.15);
                opacity: 0.5;
            }

            .feature-title {
                font-family: 'Poppins', sans-serif;
                font-weight: 700;
                font-size: 1.25rem;
                color: var(--gray-800);
                margin-bottom: 0.75rem;
            }

            .feature-description {
                color: var(--gray-600);
                line-height: 1.6;
                font-size: 0.9rem;
                font-weight: 400;
            }

            /* Stats Section - Compact */
            .stats-section {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border-radius: 15px;
                padding: 2rem 1.5rem;
                margin: 2rem 0;
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: var(--shadow-lg);
            }

            .stat-item {
                text-align: center;
                padding: 1rem 0.5rem;
                position: relative;
            }

            .stat-item::after {
                content: '';
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 1px;
                height: 50%;
                background: linear-gradient(to bottom, transparent, var(--gray-300), transparent);
            }

            .stat-item:last-child::after {
                display: none;
            }

            .stat-number {
                font-family: 'Poppins', sans-serif;
                font-weight: 800;
                font-size: 2.5rem;
                background: var(--gradient-primary);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 0.25rem;
                line-height: 1;
            }

            .stat-label {
                color: var(--gray-600);
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-size: 0.75rem;
            }

            /* Testimonials Section - Compact */
            .testimonials-section {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border-radius: 15px;
                padding: 2rem 1.5rem;
                margin: 2rem 0;
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: var(--shadow-lg);
            }

            .testimonial-card {
                background: rgba(255, 255, 255, 0.7);
                border-radius: 12px;
                padding: 1.5rem 1rem;
                text-align: center;
                border: 1px solid rgba(255, 255, 255, 0.3);
                transition: all 0.3s ease;
                height: 100%;
            }

            .testimonial-card:hover {
                transform: translateY(-5px);
                box-shadow: var(--shadow-lg);
            }

            .testimonial-avatar {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: var(--gradient-primary);
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                color: white;
                font-size: 1.25rem;
                font-weight: 600;
            }

            .testimonial-text {
                font-style: italic;
                color: var(--gray-700);
                font-size: 0.9rem;
                line-height: 1.6;
                margin-bottom: 1rem;
            }

            .testimonial-author {
                font-weight: 600;
                color: var(--gray-800);
                margin-bottom: 0.25rem;
                font-size: 0.9rem;
            }

            .testimonial-role {
                color: var(--gray-500);
                font-size: 0.75rem;
            }

            /* CTA Section - Compact */
            .cta-section {
                background: var(--gradient-primary);
                border-radius: 20px;
                padding: 2.5rem 2rem;
                text-align: center;
                color: white;
                margin: 2rem 0;
                position: relative;
                overflow: hidden;
                box-shadow: var(--shadow-xl);
            }

            .cta-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="2" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
                opacity: 0.3;
            }

            .cta-title {
                font-family: 'Poppins', sans-serif;
                font-weight: 800;
                font-size: 2rem;
                margin-bottom: 1rem;
                position: relative;
                z-index: 1;
            }

            .cta-description {
                font-size: 1rem;
                margin-bottom: 1.5rem;
                opacity: 0.95;
                position: relative;
                z-index: 1;
                font-weight: 400;
            }

            .btn-cta {
                background: rgba(255, 255, 255, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.4);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 10px;
                font-weight: 600;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                backdrop-filter: blur(10px);
                position: relative;
                z-index: 1;
                text-decoration: none;
                display: inline-block;
            }

            .btn-cta:hover {
                background: rgba(255, 255, 255, 0.3);
                border-color: rgba(255, 255, 255, 0.6);
                transform: translateY(-2px);
                color: white;
                box-shadow: var(--shadow-lg);
            }

            /* Footer - Compact */
            .footer-custom {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border-radius: 15px;
                padding: 1.5rem;
                text-align: center;
                margin-top: 2rem;
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: var(--shadow-md);
            }

            .footer-text {
                color: var(--gray-600);
                margin: 0;
                font-weight: 500;
                font-size: 0.85rem;
            }

            /* Animations */
            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .hero-section {
                animation: slideInUp 1s ease-out;
            }

            .feature-card {
                animation: fadeInUp 0.8s ease-out;
                animation-delay: calc(var(--card-index, 0) * 0.2s);
            }

            .stats-section {
                animation: fadeInUp 1s ease-out;
                animation-delay: 0.5s;
            }

            .testimonials-section {
                animation: fadeInUp 1s ease-out;
                animation-delay: 0.7s;
            }

            .cta-section {
                animation: fadeInUp 1s ease-out;
                animation-delay: 0.8s;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2rem;
                }
                
                .hero-subtitle {
                    font-size: 0.9rem;
                }
                
                .feature-card {
                    margin-bottom: 1rem;
                }
                
                .stats-section {
                    padding: 1.5rem 1rem;
                }
                
                .stat-item::after {
                    display: none;
                }
                
                .testimonials-section {
                    padding: 1.5rem 1rem;
                }
                
                .cta-section {
                    padding: 2rem 1rem;
                }
                
                .cta-title {
                    font-size: 1.75rem;
                }
                
                .navbar-brand-custom {
                    font-size: 1.1rem;
                }
            }

            @media (max-width: 576px) {
                .hero-section {
                    padding: 2rem 1rem;
                }
                
                .hero-title {
                    font-size: 1.75rem;
                }
                
                .feature-card {
                    padding: 1.25rem 0.75rem;
                }
                
                .stat-number {
                    font-size: 2rem;
                }
                
                .testimonial-card {
                    padding: 1.25rem 0.75rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- Floating Elements -->
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>

        <div class="container-fluid py-2">
            <!-- Navigation -->
            <nav class="navbar navbar-expand-lg navbar-custom">
                <div class="container">
                    <a class="navbar-brand-custom" href="#">
                        <i class="fas fa-book-open me-2"></i>
                        Library Management System
                    </a>
                    <div class="navbar-nav ms-auto">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary-custom btn-custom me-2">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-custom btn-custom me-2">
                                    <i class="fas fa-sign-in-alt me-1"></i>Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-primary-custom btn-custom">
                                        <i class="fas fa-user-plus me-1"></i>Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="hero-section text-center">
                            <h1 class="hero-title">
                                Modern Library Management
                            </h1>
                            <p class="hero-subtitle">
                                Transform your library operations with our cutting-edge management system. 
                                Streamline book tracking, member management, and borrowing processes with intelligent automation.
                            </p>
                            
                            <!-- Features Grid -->
                            <div class="row g-3 mb-4">
                                <div class="col-lg-4 col-md-6">
                                    <div class="feature-card" style="--card-index: 1;">
                                        <div class="feature-icon">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <h3 class="feature-title">Smart Catalog</h3>
                                        <p class="feature-description">
                                            Advanced book cataloging with intelligent search, filtering, and comprehensive inventory management.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="feature-card" style="--card-index: 2;">
                                        <div class="feature-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <h3 class="feature-title">Member Management</h3>
                                        <p class="feature-description">
                                            Complete member profiles with activity tracking, automated notifications, and engagement analytics.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="feature-card" style="--card-index: 3;">
                                        <div class="feature-icon">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <h3 class="feature-title">Borrowing System</h3>
                                        <p class="feature-description">
                                            Automated borrowing, returns, and overdue tracking with smart email notifications and reminders.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="container">
                <div class="stats-section">
                    <div class="row">
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">10K+</div>
                                <div class="stat-label">Books Managed</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">5K+</div>
                                <div class="stat-label">Active Members</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">50K+</div>
                                <div class="stat-label">Borrowings</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">99%</div>
                                <div class="stat-label">Uptime</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonials Section -->
            <div class="container">
                <div class="testimonials-section">
                    <div class="row text-center mb-3">
                        <div class="col-12">
                            <h2 class="h3 fw-bold mb-2" style="background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                What Our Users Say
                            </h2>
                            <p class="text-muted small">Discover why libraries choose our system</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <div class="testimonial-card">
                                <div class="testimonial-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <p class="testimonial-text">
                                    "This system has completely transformed how we manage our library. The automation features save us hours every week."
                                </p>
                                <h6 class="testimonial-author">Sarah Johnson</h6>
                                <p class="testimonial-role">Head Librarian, City Library</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="testimonial-card">
                                <div class="testimonial-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <p class="testimonial-text">
                                    "The user interface is intuitive and the reporting features are exactly what we needed for our growing library."
                                </p>
                                <h6 class="testimonial-author">Michael Chen</h6>
                                <p class="testimonial-role">Library Director, University Library</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="testimonial-card">
                                <div class="testimonial-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <p class="testimonial-text">
                                    "Excellent customer support and regular updates keep our library running smoothly. Highly recommended!"
                                </p>
                                <h6 class="testimonial-author">Emily Rodriguez</h6>
                                <p class="testimonial-role">Assistant Librarian, Community Library</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="container">
                <div class="cta-section">
                    <h2 class="cta-title">Ready to Transform Your Library?</h2>
                    <p class="cta-description">
                        Join thousands of libraries worldwide that have revolutionized their operations with our advanced management system.
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-cta">
                        <i class="fas fa-rocket me-1"></i>Start Free Trial
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="container">
                <div class="footer-custom">
                    <p class="footer-text">
                        <i class="fas fa-copyright me-1"></i>
                        {{ date('Y') }} Library Management System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Additional JavaScript for enhanced interactions -->
        <script>
            // Add scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all feature cards and testimonial cards
            document.querySelectorAll('.feature-card, .testimonial-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                observer.observe(card);
            });

            // Add hover effects to buttons
            document.querySelectorAll('.btn-custom').forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.02)';
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Add smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        </script>
    </body>
</html>
