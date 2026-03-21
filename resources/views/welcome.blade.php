<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prep for IELTS | The Official IELTS Familiarisation Test</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary-gold: #ce9d3c;
            --main-dark: #0d1624;
            --main-navy: #1a2a44;
            --text-gray: #64748b;
            --light-bg: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--main-dark);
            background-color: #fbfbfb;
            overflow-x: hidden;
            letter-spacing: -0.01em;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* --- Navbar --- */
        .navbar {
            padding: 0.75rem 0;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.4s ease;
        }
        .navbar-brand img {
            height: 65px;
            transition: transform 0.3s;
        }
        .navbar-brand:hover img {
            transform: scale(1.05);
        }
        .nav-link {
            font-weight: 500;
            color: var(--main-dark);
            margin: 0 0.4rem;
            padding: 0.5rem 0.8rem !important;
            border-radius: 8px;
            position: relative;
            transition: all 0.3s;
        }
        .nav-link:hover {
            color: var(--primary-gold);
            background: rgba(206, 157, 60, 0.05);
        }

        .btn-auth {
            border-radius: 50px;
            padding: 0.55rem 1.4rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .btn-login {
            color: var(--main-dark);
            border: 1px solid #e2e8f0;
            background: #fff;
        }
        .btn-login:hover {
            background: #f8fafc;
            border-color: var(--main-dark);
            transform: translateY(-1px);
        }
        .btn-book {
            background: linear-gradient(135deg, var(--primary-gold) 0%, #b88a35 100%);
            color: #fff;
            border: none;
            box-shadow: 0 8px 15px rgba(206, 157, 60, 0.25);
        }
        .btn-book:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #b88a35 0%, #9e762b 100%);
            color: #fff;
            box-shadow: 0 12px 20px rgba(206, 157, 60, 0.35);
        }

        /* --- Hero Section --- */
        .hero {
            position: relative;
            padding: 80px 0;
            background-image: url('{{ asset("images/hero-bg.png") }}');
            background-size: cover;
            background-position: center;
            min-height: 520px;
            display: flex;
            align-items: center;
            border-bottom: 4px solid var(--primary-gold);
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.15);
            z-index: 1;
        }
        .hero .container {
            position: relative;
            z-index: 2;
        }
        .hero-card {
            background: rgba(255, 255, 255, 0.98);
            padding: 3.5rem;
            max-width: 580px;
            border-radius: 12px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.12);
            position: relative;
            animation: slideUp 0.8s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hero-card::before {
            content: '';
            position: absolute;
            top: 2.5rem;
            left: 0;
            bottom: 2.5rem;
            width: 6px;
            background-color: var(--primary-gold);
            border-radius: 0 4px 4px 0;
        }
        .hero-card h1 {
            font-size: 3.2rem;
            line-height: 1.05;
            margin-bottom: 1.2rem;
            color: var(--main-dark);
        }
        .hero-card p {
            color: #475569;
            font-size: 1.05rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* --- Sections --- */
        .section {
            padding: 100px 0;
        }
        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }
        .section-title h2 {
            font-size: 2.4rem;
            margin-bottom: 1rem;
            color: var(--main-dark);
        }
        .section-title .underline {
            width: 50px;
            height: 4px;
            background: var(--primary-gold);
            margin: 0 auto;
            border-radius: 2px;
        }

        /* --- Module Cards --- */
        .module-card {
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem 2rem;
            text-align: center;
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            cursor: pointer;
        }
        .module-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.08);
            border-color: rgba(206, 157, 60, 0.2);
        }
        .module-icon {
            width: 80px;
            height: 80px;
            background-color: rgba(206, 157, 60, 0.08);
            color: var(--primary-gold);
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            margin: 0 auto 1.8rem;
            transition: all 0.4s;
        }
        .module-card:hover .module-icon {
            background-color: var(--primary-gold);
            color: #fff;
            transform: scale(1.1) rotate(5deg);
        }
        .module-card h4 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .module-card p {
            color: var(--text-gray);
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 0;
        }

        /* --- Footer --- */
        footer {
            background: var(--main-dark);
            color: #fff;
            padding: 60px 0 20px;
        }
        .footer-logo img {
            height: 40px;
            margin-bottom: 1.5rem;
            filter: brightness(100);
        }
        .footer-links h5 {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--primary-gold);
        }
        .footer-links ul {
            padding: 0;
            list-style: none;
        }
        .footer-links ul li {
            margin-bottom: 0.8rem;
        }
        .footer-links ul li a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer-links ul li a:hover {
            color: #fff;
        }
        .social-icons a {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.05);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 10px;
            color: #fff;
            transition: all 0.3s;
        }
        .social-icons a:hover {
            background: var(--primary-gold);
            transform: scale(1.1);
        }

        /* --- Breadcrumb --- */
        .breadcrumb-section {
            padding: 12px 0;
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
        }
        .breadcrumb-item {
            font-size: 0.8rem;
            color: var(--text-gray);
            font-weight: 400;
        }
        .breadcrumb-item a {
            color: var(--text-gray);
            text-decoration: none;
            transition: color 0.3s;
        }
        .breadcrumb-item a:hover {
            color: var(--primary-gold);
        }
        .breadcrumb-item.active {
            color: #E31837; /* More standard IDP red */
            font-weight: 600;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            padding: 0 10px;
            color: #cbd5e1;
            font-weight: 300;
        }

        /* --- Test Selection Modal --- */
        .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }
        .selection-card {
            background: #fff;
            border: 1.5px solid #f1f5f9;
            border-radius: 16px;
            padding: 2.5rem 1.5rem;
            text-align: center;
            height: 100%;
            transition: all 0.4s ease;
            cursor: pointer;
            text-decoration: none;
            display: block;
            color: inherit;
        }
        .selection-card:hover {
            border-color: var(--primary-gold);
            background: rgba(206, 157, 60, 0.05);
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.1);
        }
        .selection-card .icon {
            width: 80px;
            height: 80px;
            background: #f8fafc;
            color: var(--main-dark);
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 24px;
            margin: 0 auto 1.8rem;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .selection-card:hover .icon {
            background: var(--primary-gold);
            color: #fff;
            transform: scale(1.1) rotate(5deg);
        }
        .selection-card h4 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        .selection-card p {
            font-size: 0.95rem;
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 1.8rem;
        }
        .selection-card .btn-select {
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            background: #fff;
            color: var(--main-dark);
            border: 1.5px solid #e2e8f0;
            transition: all 0.3s;
            display: inline-block;
        }
        .selection-card:hover .btn-select {
            background: var(--primary-gold);
            color: #fff;
            border-color: var(--primary-gold);
            box-shadow: 0 10px 20px rgba(206, 157, 60, 0.25);
        }

        @media (max-width: 1199px) {
            .navbar-brand img {
                height: 55px;
            }
            .nav-link {
                font-size: 0.9rem;
                margin: 0 0.2rem;
                padding: 0.4rem 0.6rem !important;
            }
            .btn-auth {
                padding: 0.45rem 1rem;
                font-size: 0.75rem;
                white-space: nowrap;
            }
        }

        @media (max-width: 767px) {
            .navbar .container {
                padding-left: 12px;
                padding-right: 12px;
            }
            .hero {
                padding: 40px 0;
                min-height: auto;
            }
            .hero-card {
                padding: 2rem;
                margin: 0 12px;
                border-radius: 16px;
                text-align: center;
            }
            .hero-card::before {
                display: none;
            }
            .hero-card h1 {
                font-size: 2.1rem;
                margin-bottom: 1rem;
            }
            .hero-card p {
                font-size: 0.95rem;
                margin-bottom: 1.5rem;
            }
            .navbar-brand img {
                height: 50px;
            }
            .navbar-collapse {
                margin-top: 0.75rem;
                padding: 1rem;
                border-radius: 12px;
            }
            .nav-link {
                font-size: 0.9rem;
                padding: 0.6rem 1rem !important;
            }
            .section {
                padding: 60px 0;
            }
            .section-title h2 {
                font-size: 1.8rem;
            }
            .module-card {
                padding: 2rem 1.5rem;
                border-radius: 16px;
            }
            .module-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            .btn-auth {
                font-size: 0.85rem;
            }
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        .navbar-toggler {
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .navbar-toggler:hover {
            background: #f8fafc;
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-md">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/opera-dark-logo.webp') }}" alt="IELTS System Logo">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="fas fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" onclick="openTestSelection('Listening')">Listening</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" onclick="openTestSelection('Reading')">Reading</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" onclick="openTestSelection('Writing')">Writing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" onclick="openTestSelection('Speaking')">Speaking</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    @auth('web')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-auth btn-login">Admin Panel</a>
                    @elseif(auth('student')->check())
                        <a href="{{ route('student.dashboard') }}" class="btn btn-auth btn-login">Student Portal</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-auth btn-login">Login</a>
                    @endauth
                    <a href="{{ route('login') }}" class="btn btn-auth btn-book">Book your test</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="breadcrumb-section d-none d-md-block">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">IELTS Australia</a></li>
                    <li class="breadcrumb-item"><a href="#">Prepare for IELTS</a></li>
                    <li class="breadcrumb-item active" aria-current="page">The official IELTS Familiarisation Test</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="hero-card">
                        <h1>The Official IELTS Familiarisation Test</h1>
                        <p>Are you taking your IELTS test on a computer soon? Take the free IELTS Familiarisation test on computer to know what to expect on your test day.</p>
                        <a href="{{ route('login') }}" class="btn btn-auth btn-book px-5 py-3">Start Practice Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules Section -->
    <section class="section bg-light" id="modules">
        <div class="container">
            <div class="section-title">
                <h2>Explore the Modules</h2>
                <div class="underline"></div>
            </div>
            <div class="row g-4">
                <div class="col-md-3 col-sm-6" id="listening">
                    <div class="module-card" onclick="openTestSelection('Listening')">
                        <div class="module-icon">
                            <i class="fas fa-headphones"></i>
                        </div>
                        <h4>Listening</h4>
                        <p>Evaluate your ability to understand main ideas and factual information through various audio segments.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" id="reading">
                    <div class="module-card" onclick="openTestSelection('Reading')">
                        <div class="module-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h4>Reading</h4>
                        <p>Test your reading skills with three long texts ranging from descriptive to discursive and analytical.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" id="writing">
                    <div class="module-card" onclick="openTestSelection('Writing')">
                        <div class="module-icon">
                            <i class="fas fa-pen-nib"></i>
                        </div>
                        <h4>Writing</h4>
                        <p>Demonstrate your ability to write descriptive reports and discursive essays on diverse academic topics.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" id="speaking">
                    <div class="module-card" onclick="openTestSelection('Speaking')">
                        <div class="module-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h4>Speaking</h4>
                        <p>Simulate a face-to-face interview to assess your spoken fluency and communicative effectiveness.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Preparation Tips Section -->
    <section class="section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="p-4" style="background: var(--main-dark); border-radius: 20px; color: #fff;">
                        <h2 class="mb-4" style="color: var(--primary-gold);">Why Choose Our Platform?</h2>
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex align-items-start gap-3">
                                <i class="fas fa-check-circle mt-1" style="color: var(--primary-gold);"></i>
                                <div>
                                    <h5 class="mb-1">Authentic Experience</h5>
                                    <p class="mb-0 text-white-50">Our simulation matches the actual IELTS interface precisely.</p>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start gap-3">
                                <i class="fas fa-check-circle mt-1" style="color: var(--primary-gold);"></i>
                                <div>
                                    <h5 class="mb-1">Real-time Performance</h5>
                                    <p class="mb-0 text-white-50">Get instant results and insights on your strengths and weaknesses.</p>
                                </div>
                            </li>
                            <li class="mb-0 d-flex align-items-start gap-3">
                                <i class="fas fa-check-circle mt-1" style="color: var(--primary-gold);"></i>
                                <div>
                                    <h5 class="mb-1">Official Material</h5>
                                    <p class="mb-0 text-white-50">All test questions follow the latest global IELTS standards.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h2>Master the Test Format</h2>
                    <p class="lead text-muted mb-4">Preparation is the key to achieving your desired band score.</p>
                    <p>Our platform provides a comprehensive environment for students to familiarize themselves with the computer-delivered IELTS format. From screen layouts to timing mechanisms, we ensure you are 100% prepared.</p>
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-auth btn-login me-3">Learn More</a>
                        <a href="{{ route('login') }}" class="btn btn-auth btn-book">Get Started <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-4 mb-4">
                    <div class="footer-logo">
                        <img src="{{ asset('images/opera-dark-logo.webp') }}" alt="Logo">
                    </div>
                    <p class="text-white-50 pe-lg-5">Providing world-class IELTS preparation and training solutions since 2010. Our goal is to empower students to reach their international goals.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 footer-links">
                    <h5>Modules</h5>
                    <ul>
                        <li><a href="javascript:void(0)" onclick="openTestSelection('Listening')">Listening</a></li>
                        <li><a href="javascript:void(0)" onclick="openTestSelection('Reading')">Reading</a></li>
                        <li><a href="javascript:void(0)" onclick="openTestSelection('Writing')">Writing</a></li>
                        <li><a href="javascript:void(0)" onclick="openTestSelection('Speaking')">Speaking</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 footer-links">
                    <h5>Resources</h5>
                    <ul>
                        <li><a href="#">Preparation Tips</a></li>
                        <li><a href="#">IELTS FAQ</a></li>
                        <li><a href="#">Official Documents</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 mb-4 footer-links">
                    <h5>Quick Contact</h5>
                    <p class="text-white-50"><i class="fas fa-envelope me-2"></i> info@ielts-system.com</p>
                    <p class="text-white-50"><i class="fas fa-phone me-2"></i> +91 98765 43210</p>
                    <p class="text-white-50"><i class="fas fa-map-marker-alt me-2"></i> Sector 17, Chandigarh, India</p>
                </div>
            </div>
            <hr style="background-color: rgba(255,255,255,0.1);">
            <div class="row mt-4">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-50 mb-0">&copy; 2026 IELTS Professional System. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <a href="#" class="text-white-50 text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-white-50 text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Test Selection Modal (Multi-step) -->
    <div class="modal fade" id="testSelectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-lg-5 pt-0">
                    <!-- Step 1: Select Test Type -->
                    <div id="step-test-type">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold mb-2">Select Your Test Type</h2>
                            <p class="text-muted" id="selectionModuleSubtitle">Please choose between Academic or General Training</p>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="selection-card" onclick="goToStep('level', 'Academic')">
                                    <div class="icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h4>IELTS Academic</h4>
                                    <p>For people applying for higher education or professional registration in an English-speaking environment.</p>
                                    <span class="btn-select">Select Academic</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="selection-card" onclick="goToStep('level', 'General Training')">
                                    <div class="icon">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <h4>IELTS General</h4>
                                    <p>For those going to English-speaking countries for secondary education, work experience, or migration.</p>
                                    <span class="btn-select">Select General</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Select Level (dynamic from DB) -->
                    <div id="step-level" style="display: none;">
                        <div class="text-center mb-5">
                            <div class="mb-3">
                                <span class="badge rounded-pill bg-light text-dark px-3 py-2 border" id="selectedTypeBadge">Academic</span>
                            </div>
                            <h2 class="fw-bold mb-2">Select Your Level</h2>
                            <p class="text-muted">Choose your current preparation stage</p>
                        </div>
                        <div class="row g-4" id="level-cards-container">
                            <!-- Dynamically populated via JS -->
                            <div class="col-12 text-center py-4">
                                <div class="spinner-border text-warning" role="status"></div>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <button class="btn btn-link text-muted text-decoration-none" onclick="goToStep('test-type')">
                                <i class="fas fa-chevron-left me-2"></i> Back to Test Type
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Select Module Set (dynamic from DB) -->
                    <div id="step-modules" style="display: none;">
                        <div class="text-center mb-5">
                            <div class="mb-3 d-flex justify-content-center gap-2">
                                <span class="badge rounded-pill bg-light text-dark px-3 py-2 border" id="moduleBadgeType"></span>
                                <span class="badge rounded-pill bg-warning text-dark px-3 py-2" id="moduleBadgeLevel"></span>
                            </div>
                            <h2 class="fw-bold mb-2">Select a Module</h2>
                            <p class="text-muted" id="moduleSubtitle">Choose a module to start your practice</p>
                        </div>
                        <div class="row g-4" id="module-cards-container">
                            <!-- Dynamically populated via JS -->
                        </div>
                        <div class="text-center mt-5">
                            <button class="btn btn-link text-muted text-decoration-none" onclick="goToStep('level')">
                                <i class="fas fa-chevron-left me-2"></i> Back to Level
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Tests List inside selected Module -->
                    <div id="step-tests" style="display: none;">
                        <div class="text-center mb-4">
                            <div class="mb-3 d-flex justify-content-center gap-2">
                                <span class="badge rounded-pill bg-light text-dark px-3 py-2 border" id="testBadgeType"></span>
                                <span class="badge rounded-pill bg-warning text-dark px-3 py-2" id="testBadgeLevel"></span>
                                <span class="badge rounded-pill bg-dark text-white px-3 py-2" id="testBadgeModule"></span>
                            </div>
                            <h2 class="fw-bold mb-2">Available Tests</h2>
                            <p class="text-muted" id="testSubtitle">Login to attempt any test below</p>
                        </div>
                        <div id="tests-list-container">
                            <!-- Dynamically populated via JS -->
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-link text-muted text-decoration-none" onclick="goToStep('modules')">
                                <i class="fas fa-chevron-left me-2"></i> Back to Modules
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global state to track selections
        let currentModule = '';
        let currentType = '';
        let currentLevelId = null;
        let currentLevelName = '';

        // Level icon map (fallback icons based on index)
        const levelIcons = ['fa-seedling', 'fa-layer-group', 'fa-fire', 'fa-star', 'fa-rocket'];

        function openTestSelection(moduleName) {
            currentModule = moduleName;
            document.getElementById('selectionModuleSubtitle').innerText =
                'Choose between Academic or General for ' + moduleName;

            // Reset to Step 1
            showOnlyStep('test-type');

            var selectionModal = new bootstrap.Modal(document.getElementById('testSelectionModal'));
            selectionModal.show();
        }

        function showOnlyStep(step) {
            ['test-type', 'level', 'modules', 'tests'].forEach(s => {
                const el = document.getElementById('step-' + s);
                if (el) el.style.display = (s === step) ? 'block' : 'none';
            });
        }

        function goToStep(step, typeSelected) {
            if (step === 'level') {
                currentType = typeSelected || currentType;
                document.getElementById('selectedTypeBadge').innerText = currentType;

                showOnlyStep('level');
                fetchLevels();

            } else if (step === 'test-type') {
                showOnlyStep('test-type');

            } else if (step === 'modules') {
                showOnlyStep('modules');
            }
        }

        // Fetch levels from the server
        function fetchLevels() {
            const container = document.getElementById('level-cards-container');
            container.innerHTML = '<div class="col-12 text-center py-4"><div class="spinner-border text-warning" role="status"></div></div>';

            fetch('/api/levels')
                .then(r => r.json())
                .then(levels => {
                    if (levels.length === 0) {
                        container.innerHTML = '<div class="col-12 text-center py-4 text-muted"><i class="fas fa-inbox fa-2x mb-2"></i><p>No levels found.</p></div>';
                        return;
                    }

                    const colSize = levels.length <= 3 ? `col-md-${12 / levels.length}` : 'col-md-4';

                    container.innerHTML = levels.map((level, i) => `
                        <div class="${colSize}">
                            <div class="selection-card p-4" onclick="selectLevel(${level.id}, '${level.name.replace(/'/g, "\\'")}')"
                                 style="cursor:pointer;">
                                <div class="icon" style="width:60px;height:60px;font-size:1.5rem;border-radius:16px;">
                                    <i class="fas ${levelIcons[i] || 'fa-star'}"></i>
                                </div>
                                <h5 class="fw-bold">${level.name}</h5>
                                <p style="font-size:0.85rem;">${level.description || 'Select to see available modules.'}</p>
                                <span class="btn-select py-2" style="font-size:0.8rem;">Choose Level</span>
                            </div>
                        </div>
                    `).join('');
                })
                .catch(() => {
                    container.innerHTML = '<div class="col-12 text-center text-danger">Failed to load levels. Please try again.</div>';
                });
        }

        // User picked a level → fetch module sets
        function selectLevel(levelId, levelName) {
            currentLevelId = levelId;
            currentLevelName = levelName;

            document.getElementById('moduleBadgeType').innerText = currentType;
            document.getElementById('moduleBadgeLevel').innerText = levelName;
            document.getElementById('moduleSubtitle').innerText =
                `${currentModule} modules for ${currentType} — ${levelName}`;

            showOnlyStep('modules');
            fetchModuleSets();
        }

        // Fetch module sets from the server
        function fetchModuleSets() {
            const container = document.getElementById('module-cards-container');
            container.innerHTML = '<div class="col-12 text-center py-4"><div class="spinner-border text-warning" role="status"></div></div>';

            const categorySlug = currentModule.toLowerCase();
            const url = `/api/module-sets?category=${encodeURIComponent(categorySlug)}&test_type=${encodeURIComponent(currentType)}&level_id=${currentLevelId}`;

            fetch(url)
                .then(r => r.json())
                .then(modules => {
                    if (modules.length === 0) {
                        container.innerHTML = `
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No modules available yet</h5>
                                <p class="text-muted small">No active modules found for <strong>${currentType}</strong> — <strong>${currentLevelName}</strong> in <strong>${currentModule}</strong>.</p>
                                <a href="{{ route('login') }}" class="btn btn-warning mt-2 px-4" style="border-radius:50px;">Login to Access Tests</a>
                            </div>`;
                        return;
                    }

                    const colSize = modules.length === 1 ? 'col-md-6 mx-auto' : 'col-md-4';

                    container.innerHTML = modules.map(mod => `
                        <div class="${colSize}">
                            <div class="selection-card p-4" onclick="selectModule(${mod.id}, '${mod.name.replace(/'/g, "\\'")}')"
                                 style="cursor:pointer;">
                                <div class="icon" style="width:60px;height:60px;font-size:1.5rem;border-radius:16px;">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h5 class="fw-bold">${mod.name}</h5>
                                <p style="font-size:0.85rem;">${mod.tests_count} test${mod.tests_count !== 1 ? 's' : ''} inside</p>
                                <span class="btn-select py-2" style="font-size:0.8rem;">View Tests</span>
                            </div>
                        </div>
                    `).join('');
                })
                .catch(() => {
                    container.innerHTML = '<div class="col-12 text-center text-danger">Failed to load modules. Please try again.</div>';
                });
        }

        // User clicked a module → show tests
        function selectModule(moduleSetId, moduleSetName) {
            document.getElementById('testBadgeType').innerText   = currentType;
            document.getElementById('testBadgeLevel').innerText  = currentLevelName;
            document.getElementById('testBadgeModule').innerText = moduleSetName;
            document.getElementById('testSubtitle').innerText    =
                `Tests inside: ${moduleSetName} — ${currentModule} / ${currentType} / ${currentLevelName}`;

            showOnlyStep('tests');
            fetchTests(moduleSetId);
        }

        // Fetch tests for a specific module set
        function fetchTests(moduleSetId) {
            const container = document.getElementById('tests-list-container');
            container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-warning" role="status"></div></div>';

            fetch(`/api/tests?module_set_id=${moduleSetId}`)
                .then(r => r.json())
                .then(tests => {
                    if (tests.length === 0) {
                        container.innerHTML = `
                            <div class="text-center py-5">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Tests Available Yet</h5>
                                <p class="text-muted small">Tests will appear here once added by the admin.</p>
                            </div>`;
                        return;
                    }

                    container.innerHTML = `
                        <div class="list-group shadow-sm" style="border-radius:14px;overflow:hidden;">
                            ${tests.map((test, i) => {
                                const testUrl = "{{ route('student.tests.show', ':id') }}".replace(':id', test.id);
                                return `
                                    <a href="${testUrl}"
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 px-4"
                                       style="border-left:4px solid var(--primary-gold); transition:all 0.2s;"
                                       onmouseover="this.style.background='rgba(206,157,60,0.06)'"
                                       onmouseout="this.style.background=''">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="d-flex align-items-center justify-content-center rounded-circle bg-warning text-dark fw-bold"
                                                  style="width:34px;height:34px;font-size:0.85rem;flex-shrink:0;">${i + 1}</span>
                                            <span class="fw-semibold" style="color:#0d1624;">${test.name}</span>
                                        </div>
                                        <span class="btn btn-sm btn-warning px-3" style="border-radius:50px;font-size:0.78rem;font-weight:600;">
                                            Start <i class="fas fa-arrow-right ms-1"></i>
                                        </span>
                                    </a>
                                `;
                            }).join('')}
                        </div>
                        <p class="text-center text-muted small mt-3">
                            <i class="fas fa-lock me-1"></i>Please <a href="{{ route('login') }}" class="text-warning fw-bold">login</a> to attempt a test.
                        </p>`;
                })
                .catch(() => {
                    container.innerHTML = '<div class="text-center text-danger">Failed to load tests. Please try again.</div>';
                });
        }
    </script>
</body>
</html>
