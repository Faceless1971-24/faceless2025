<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
                /* Custom CSS */
        :root {
            --primary: #1E3A8A;         /* Rich navy blue */
            --primary-dark: #0F2259;    /* Darker navy */
            --primary-light: #3B5ECA;   /* Lighter blue */
            --accent: #F59E0B;          /* Warm amber/gold */
            --accent-hover: #D97706;    /* Darker amber for hover states */
            --text-on-dark: #FFFFFF;
            --text-dark: #121212;
        }

        body {
            font-family: 'Hind Siliguri', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .brand-logo {
            height: 45px;
            transition: transform 0.3s;
        }

        .brand-logo:hover {
            transform: scale(1.05);
        }

        .navbar-nav .nav-link {
            color: var(--text-on-dark);
            padding: 1rem 1.2rem;
            position: relative;
            font-weight: 500;
        }

        .navbar-nav .nav-link:before {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: var(--accent);
            transition: all 0.3s;
            opacity: 0;
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover:before,
        .navbar-nav .nav-link.active:before {
            width: 80%;
            opacity: 1;
        }

        .btn-auth {
            background: var(--accent);
            color: var(--text-dark);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-auth:hover {
            background: var(--accent-hover);
            color: var(--text-on-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .dropdown-item {
            padding: 0.7rem 1.2rem;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: rgba(30, 58, 138, 0.1);
            padding-left: 1.5rem;
        }

        /* Footer Styles */
        .footer {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: var(--text-on-dark);
            margin-top: auto;
        }

        .footer-top {
            padding: 3rem 0;
        }

        .footer-heading {
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
            padding-bottom: 0.5rem;
        }

        .footer-heading:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent);
        }

        .footer-link {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            display: block;
            margin-bottom: 0.8rem;
            transition: all 0.3s;
        }

        .footer-link:hover {
            color: var(--text-on-dark);
            transform: translateX(5px);
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            color: var(--text-on-dark);
            margin-right: 0.8rem;
            transition: all 0.3s;
        }

        .social-icon:hover {
            background: var(--accent);
            color: var(--text-dark);
            transform: translateY(-3px);
        }

        .copyright {
            background: rgba(0,0,0,0.25);
            padding: 1rem 0;
            text-align: center;
        }

        @media (max-width: 768px) {
            .navbar-nav .nav-link {
                padding: 0.7rem 1rem;
            }
            
            .navbar-nav .nav-link:before {
                display: none;
            }
        }
    </style>


    @yield('styles')
</head>

<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('theme/media/facelessD.jpeg') }}" alt="{{ config('app.name') }}" class="brand-logo">
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item px-3 py-2 fs-5">

                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="fas fa-home me-1"></i> হোম
                            </a>
                        </li>
                            @auth
                                @php
                                    $user = Auth::user();
                                @endphp

                                @if($user->membership_status === 'approved' || $user->isAdmin())

                                            <li class="nav-item px-3 py-2 fs-5">
                                                <a class="nav-link {{ request()->routeIs('frontend.campaigns.*') ? 'active' : '' }}" href="{{ route('frontend.campaigns.index') }}">
                                                    <i class="fas fa-bullhorn me-1"></i> ক্যাম্পেইন
                                                </a>
                                            </li>
                                            <li class="nav-item px-3 py-2 fs-5">
                                                <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="">
                                                    <i class="fas fa-newspaper me-1"></i> সংবাদ
                                                </a>
                                            </li>
                                            <li class="nav-item px-3 py-2 fs-5">
                                                <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="">
                                                    <i class="fas fa-calendar-alt me-1"></i> ইভেন্ট
                                                </a>
                                            </li>
                                        @endif
                            @endauth
                        <li class="nav-item px-3 py-2 fs-5">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('membership.index') }}">
                                <i class="fas fa-user-plus me-1"></i> সদস্য                            </a>
                        </li>
                    </ul>
                    
                    <div class="d-flex">
                        @auth
                            <div class="dropdown">
                                <button class="btn-auth dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    @if(Auth::user()->photo)
                                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="User Image" class="rounded-circle"
                                            style="width: 30px; height: 30px; object-fit: cover;">
                                    @else
                                        <i class="fa-solid fa-user-circle" style="font-size: 20px;"></i>
                                    @endif
                                    {{ Auth::user()->name }}
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="fa-solid fa-user me-2"></i> প্রোফাইল
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('membership.index') }}">
                                            <i class="fa-solid fa-id-card me-2"></i> সদস্য                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    @if(Auth::user()->isAdmin())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('dashboard.index') }}">
                                                <i class="fa-solid fa-gauge-high me-2"></i> ড্যাশবোর্ড
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fa-solid fa-sign-out-alt me-2"></i> লগআউট
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn-auth">
                                <i class="fas fa-sign-in-alt me-1"></i> লগইন
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-xl-6 col-md-12">
                        <h5 class="footer-heading">আমাদের সম্পর্কে</h5>
                        <p class="mb-4">আমরা একটি রাজনৈতিক দল যা বাংলাদেশের সাধারণ মানুষের প্রতিনিধিত্ব করে এবং জনগণের কল্যাণে কাজ করে।</p>
                        <div class="mt-4">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    
                    <div class="col6 col-md-6">
                        <h5 class="footer-heading">দ্রুত লিঙ্ক</h5>
                        <a href="{{ route('home') }}" class="footer-link"><i class="fas fa-chevron-right me-2"></i>হোম</a>
@auth
    @php
    $user = Auth::user();
    @endphp

    @if($user->membership_status === 'approved' || $user->isAdmin())

                                    <a href="{{ route('frontend.campaigns.index') }}" class="footer-link"><i class="fas fa-chevron-right me-2"></i>ক্যাম্পেইন</a>
                                    <a href="{{ route('membership.index') }}" class="footer-link"><i class="fas fa-chevron-right me-2"></i>সদস্য</a>
                                @endif
@endauth
                    </div>
                    
                    
                </div>
            </div>
        </div>
        
        <div class="copyright">
            <div class="container">
                <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}। সর্বস্বত্ব সংরক্ষিত।</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // CSRF token setup for AJAX requests
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Set up CSRF token for any fetch requests
            window.fetchWithCSRF = function(url, options = {}) {
                options.headers = options.headers || {};
                options.headers['X-CSRF-TOKEN'] = token;
                return fetch(url, options);
            };
        });
    </script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    @yield('scripts')
</body>
</html>