@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="login-container">
        <!-- Animated background -->
        <div class="animated-background">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>

        <div class="login-wrapper">
            <div class="login-card">
                <div class="brand-area">
                    <div class="brand-logo">
                        <img src="{{ asset('theme/media/facelessD.jpeg') }}" alt="Logo">
                    </div>
                    <h1 class="welcome-text">Welcome Back</h1>
                    <p class="welcome-subtext">Sign in to continue to your account</p>
                </div>

                <div class="alert-container">
                    <x-alert />
                </div>

                <div class="login-form-container">
                    <form class="login-form" action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="userid">{{ __('User ID') }}</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="userid" name="userid" value="{{ old('userid') }}" 
                                    placeholder="Enter your user ID" required autofocus />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="password-label-row">
                                <label for="password">{{ __('Password') }}</label>
                            </div>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="password" name="password" 
                                    placeholder="Enter your password" required autocomplete="current-password" />
                                <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group remember-me">
                            <div class="custom-checkbox">
                                <input type="checkbox" id="remember_me" name="remember" />
                                <label for="remember_me">{{ __('Remember me') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn-signin">
                                {{ __('Sign in') }}
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="login-footer">
                    <p>Protected by encryption. Your data is safe with us.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            height: 100vh;
            overflow: hidden;
        }

        /* Animated Background */
        .animated-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            /* background-image: url('/images/bg-s.png'); */
            background-size: cover;
            background-position: center;
            z-index: -2;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: float 15s infinite;
            backdrop-filter: blur(5px);
        }

        .shape-1 {
            width: 500px;
            height: 500px;
            top: -150px;
            right: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 400px;
            height: 400px;
            bottom: -100px;
            left: -100px;
            animation-delay: 3s;
            background: rgba(72, 126, 176, 0.05);
        }

        .shape-3 {
            width: 300px;
            height: 300px;
            bottom: 30%;
            right: 20%;
            animation-delay: 6s;
            background: rgba(38, 84, 124, 0.05);
        }

        .shape-4 {
            width: 200px;
            height: 200px;
            top: 20%;
            left: 20%;
            animation-delay: 9s;
            background: rgba(25, 55, 83, 0.05);
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(10px, 15px) rotate(3deg); }
            50% { transform: translate(5px, -10px) rotate(-3deg); }
            75% { transform: translate(-10px, 5px) rotate(2deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }

        /* Container for login card */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
            position: relative;
        }

        /* Login wrapper */
        .login-wrapper {
            width: 100%;
            max-width: 450px;
            z-index: 10;
        }

        /* Login Card */
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }

        /* Brand Area */
        .brand-area {
            padding: 30px 40px 20px;
            text-align: center;
        }

        .brand-logo {
            margin-bottom: 15px;
        }

        .brand-logo img {
            max-width: 100px;
            opacity: 0.85;
            transition: opacity 0.3s ease;
        }

        .brand-logo img:hover {
            opacity: 1;
        }

        .welcome-text {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
        }

        .welcome-subtext {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        /* Alert Container */
        .alert-container {
            padding: 0 40px;
        }

        /* Login Form */
        .login-form-container {
            padding: 20px 40px 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #34495e;
        }

        .password-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .forgot-link {
            font-size: 13px;
            color: #3498db;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .input-with-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            color: #95a5a6;
            font-size: 16px;
        }

        .input-with-icon input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #fff;
            color: #2c3e50;
        }

        .input-with-icon input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
            outline: none;
        }

        .input-with-icon input::placeholder {
            color: #bdc3c7;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            background: none;
            border: none;
            color: #95a5a6;
            cursor: pointer;
            font-size: 16px;
            transition: color 0.2s ease;
        }

        .toggle-password:hover {
            color: #3498db;
        }

        /* Remember Me */
        .remember-me {
            margin-top: 15px;
        }

        .custom-checkbox {
            display: flex;
            align-items: center;
        }

        .custom-checkbox input {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: #3498db;
        }

        .custom-checkbox label {
            margin-bottom: 0;
            font-size: 14px;
            color: #7f8c8d;
            cursor: pointer;
        }

        /* Sign In Button */
        .btn-signin {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .btn-signin i {
            margin-left: 10px;
            transition: transform 0.3s ease;
        }

        .btn-signin:hover {
            background: linear-gradient(135deg, #2980b9, #1a5276);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(41, 128, 185, 0.4);
        }

        .btn-signin:hover i {
            transform: translateX(5px);
        }

        .btn-signin:active {
            transform: translateY(0);
        }

        /* Login Footer */
        .login-footer {
            padding: 20px 40px;
            background: rgba(236, 240, 241, 0.5);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(189, 195, 199, 0.3);
        }

        .login-footer p {
            font-size: 12px;
            color: #7f8c8d;
        }

        .security-icons i {
            margin-left: 10px;
            color: #27ae60;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-card {
                border-radius: 15px;
            }

            .brand-area,
            .login-form-container {
                padding: 20px 25px;
            }

            .login-footer {
                padding: 15px 25px;
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .welcome-text {
                font-size: 22px;
            }

            .input-with-icon input {
                padding: 12px 15px 12px 40px;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.querySelector('#password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    // Toggle eye icon
                    const eyeIcon = this.querySelector('i');
                    eyeIcon.classList.toggle('fa-eye');
                    eyeIcon.classList.toggle('fa-eye-slash');
                });
            }

            // Add floating labels effect
            const inputs = document.querySelectorAll('.input-with-icon input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('input-focused');
                });

                input.addEventListener('blur', function() {
                    if (this.value.length === 0) {
                        this.parentElement.classList.remove('input-focused');
                    }
                });

                // Check if input has value on page load
                if (input.value.length > 0) {
                    input.parentElement.classList.add('input-focused');
                }
            });
        });
    </script>
@endsection