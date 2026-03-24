<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IELTS Panel</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0d1624 0%, #1a2a44 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Abstract shapes in background */
        .bg-shape-1 {
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: rgba(206, 157, 60, 0.1);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
        }

        .bg-shape-2 {
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 500px;
            height: 500px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1000px;
            max-height: 95vh;
            display: flex;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(20px);
            overflow: hidden;
        }

        @media (max-height: 700px) {
            .login-wrapper {
                max-height: 98vh;
            }
            .login-sidebar, .login-form-container {
                overflow-y: auto;
            }
        }

        .login-sidebar {
            flex: 1;
            background: #fdfbf5;
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            border-right: 1px solid rgba(0,0,0,0.05);
            position: relative;
        }

        .login-sidebar img {
            max-width: 220px;
            margin-bottom: 3rem;
            mix-blend-mode: multiply;
        }

        .red-title {
            color: #e63946;
            font-weight: 800;
            font-size: 2.8rem;
            margin-bottom: 0.25rem;
            letter-spacing: -1px;
            line-height: 1;
        }

        .login-sidebar h3 {
            color: #0d1624;
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 1.25rem;
        }

        .login-sidebar p {
            color: #64748b;
            font-size: 1.05rem;
            line-height: 1.6;
            width: 100%;
        }

        .contact-section {
            width: 100%;
            margin-top: auto;
        }

        .contact-card {
            background: #ffffff;
            padding: 1.25rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .contact-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            font-weight: 600;
        }

        .contact-details {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: #ce9d3c;
            font-weight: 700;
            font-size: 1.2rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .contact-details i {
            width: 35px;
            height: 35px;
            background: rgba(206, 157, 60, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ce9d3c;
            font-size: 1rem;
        }

        .contact-details:hover {
            color: #b88a35;
            transform: translateX(5px);
        }

        .login-form-container {
            flex: 1.2;
            padding: 4rem 4rem;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header h2 {
            color: #0d1624;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        .login-header p {
            color: #64748b;
            margin-bottom: 2rem;
        }

        .form-floating > label {
            color: #64748b;
            padding-left: 1.25rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: 1.5px solid #e2e8f0;
            height: calc(3.5rem + 2px);
            line-height: 1.25;
            transition: all 0.3s ease;
            font-size: 1rem;
            background-color: #f8fafc;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: #ce9d3c;
            box-shadow: 0 0 0 4px rgba(206, 157, 60, 0.1);
        }

        .input-group-text {
            border: none;
            background: transparent;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: #94a3b8;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #ce9d3c;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(206, 157, 60, 0.3);
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #b88a35;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(206, 157, 60, 0.4);
            color: #fff;
        }

        .form-check-input:checked {
            background-color: #ce9d3c;
            border-color: #ce9d3c;
        }

        .form-check-label {
            color: #64748b;
        }

        .forgot-password {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 900px) {
            .login-wrapper {
                flex-direction: column;
                margin: 10px;
                max-width: 500px;
                width: calc(100% - 20px);
                max-height: 95vh;
            }
            .login-sidebar {
                padding: 1.5rem;
                border-right: none;
                border-bottom: 1px solid rgba(0,0,0,0.05);
                flex: 0 0 auto;
                justify-content: center;
                gap: 1.5rem;
                align-items: center;
                text-align: center;
            }
            .login-sidebar img {
                max-width: 100px;
                margin-bottom: 0.5rem;
            }
            .red-title {
                font-size: 1.8rem;
            }
            .login-sidebar h3 {
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }
            .login-sidebar p, .contact-section {
                display: none;
            }
            .login-form-container {
                padding: 1.5rem 1rem;
                flex: 1 1 auto;
                overflow-y: auto;
            }
            .login-header h2 {
                font-size: 1.25rem;
            }
            .login-header p {
                margin-bottom: 1rem;
                font-size: 0.9rem;
            }
            .form-floating mb-4 {
                margin-bottom: 0.75rem !important;
            }
            .btn-primary {
                padding: 0.7rem;
            }
        }

        @media (max-width: 480px) {
            .login-wrapper {
                margin: 5px;
                width: calc(100% - 10px);
            }
            .login-form-container {
                padding: 1.25rem 0.75rem;
            }
        }
        
        /* Hide scrollbars for cleaner look but still allow scrolling if needed */
        .login-sidebar::-webkit-scrollbar,
        .login-form-container::-webkit-scrollbar {
            width: 4px;
        }
        .login-sidebar::-webkit-scrollbar-thumb,
        .login-form-container::-webkit-scrollbar-thumb {
            background: rgba(206, 157, 60, 0.2);
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <!-- Background Shapes -->
    <div class="bg-shape-1"></div>
    <div class="bg-shape-2"></div>

    <div class="container d-flex justify-content-center">
        <div class="login-wrapper">
            <!-- Left Branding Side -->
            <div class="login-sidebar">
                <div class="top-content">
                    <img src="{{ asset('images/opera-dark-logo.webp') }}" alt="IELTS System Logo">
                    <h1 class="red-title">IELTS</h1>
                    <h3>Welcome Back</h3>
                    <p>Manage tests, students, and results all in one powerful platform.</p>
                </div>

                <div class="contact-section">
                    <div class="contact-card">
                        <span class="contact-label">Need Assistance?</span>
                        <a href="tel:8796853467" class="contact-details">
                            <i class="fas fa-phone-alt"></i>
                            <span>8796853467</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Form Side -->
            <div class="login-form-container">
                <div class="login-header">
                    <h2>Sign In</h2>
                    <p>Enter your credentials to access the portal.</p>
                </div>
                
                @if ($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div style="margin: 0">{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    
                    <div class="form-floating mb-4 position-relative">
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email address</label>
                    </div>

                    <div class="form-floating mb-4 position-relative">
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Password">
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        <span class="input-group-text" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Sign In <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>

