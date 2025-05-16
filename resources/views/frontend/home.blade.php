            {{-- resources/views/frontend/home.blade.php --}}
@extends('frontend.layouts.master')

@section('title', 'বাংলাদেশ উন্নয়ন আন্দোলন - Times Style')@section('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">


    <style>
    /* Homepage CSS Styles */
    :root {
    /* Core colors - match with master layout */
    --primary: #1E3A8A;
    --primary-dark: #0F2259;
    --primary-light: #3B5ECA;
    --accent: #F59E0B;
    --accent-hover: #D97706;
    --text-on-dark: #FFFFFF;
    --text-dark: #121212;

    /* Additional variables for this page */
    --gray-100: #f8f9fa;
    --gray-200: #e9ecef;
    --gray-300: #dee2e6;
    --gray-500: #adb5bd;
    --gray-700: #495057;
    --gray-900: #212529;
    --radius-sm: 6px;
    --radius: 10px;
    --radius-lg: 15px;
    --transition-fast: 0.2s ease;
    --transition: 0.3s ease;
    --shadow-sm: 0 2px 10px rgba(0,0,0,0.05);
    --shadow: 0 5px 20px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 30px rgba(0,0,0,0.15);
    --font-serif: 'Playfair Display', 'Hind Siliguri', serif;
    --font-sans: 'Hind Siliguri', sans-serif;
    }

    /* Base Styles */
    .bd-times {
    color: var(--gray-900);
    }

    /* Typography */
    .bd-times h1, .bd-times h2, .bd-times h3, 
    .bd-times h4, .bd-times h5, .bd-times .serif {
    font-family: var(--font-serif);
    }

    .bd-times .display-1 {
    font-size: clamp(2.5rem, 5vw, 4rem);
    line-height: 1.2;
    font-weight: 900;
    letter-spacing: -0.02em;
    }

    .bd-times .lead {
    font-size: clamp(1.1rem, 2vw, 1.3rem);
    font-weight: 400;
    line-height: 1.6;
    }

    /* Layout */
    .bd-container {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 1rem;
    }

    .bd-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin: 2rem auto;
    }

    @media (min-width: 992px) {
    .bd-grid {
    grid-template-columns: 25% 1fr 25%;
    }
    }

    /* Cards & Components */
    .bd-card {
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    height: 100%;
    transition: transform var(--transition), box-shadow var(--transition);
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    }

    .bd-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow);
    }

    .bd-card-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: 1.25rem 1.5rem;
    position: relative;
    }

    .bd-card-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 1.5rem;
    width: 3rem;
    height: 3px;
    background: var(--accent);
    }

    .bd-card-body {
    padding: 1.5rem;
    flex: 1;
    }

    .bd-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    }

    /* Section Styling */
    .bd-section {
    position: relative;
    margin-bottom: 3rem;
    }

    .bd-section-title {
    position: relative;
    margin-bottom: 2rem;
    font-weight: 800;
    text-align: center;
    color: var(--primary-dark);
    }

    .bd-section-title::after {
    content: '';
    position: absolute;
    bottom: -0.75rem;
    left: 50%;
    transform: translateX(-50%);
    width: 6rem;
    height: 3px;
    background: var(--accent);
    border-radius: 2px;
    }

    /* Hero Section */
    .bd-hero {
    position: relative;
    padding: 4rem 0;
    text-align: center;
    margin-bottom: 3rem;
    background: linear-gradient(rgba(255,255,255,0.95), rgba(248,249,250,0.9)), 
    url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%231e3a8a' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .bd-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, 
    transparent, 
    var(--primary-light), 
    transparent
    );
    }

    .bd-hero .display-1 {
    color: var(--primary-dark);
    }

    /* List Items */
    .bd-list-item {
    padding: 1.25rem 0;
    border-bottom: 1px solid var(--gray-200);
    transition: transform var(--transition-fast);
    }

    .bd-list-item:last-child {
    border-bottom: none;
    }

    .bd-list-item:hover {
    transform: translateX(5px);
    }

    .bd-list-item .badge {
    background: rgba(30, 58, 138, 0.1);
    color: var(--primary);
    font-weight: 500;
    padding: 0.25rem 0.75rem;
    border-radius: 2rem;
    margin-bottom: 0.5rem;
    display: inline-block;
    }

    .bd-list-item .title {
    font-weight: 700;
    font-size: 1.125rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
    color: var(--dark);
    transition: color var(--transition-fast);
    }

    .bd-list-item:hover .title {
    color: var(--primary);
    }

    .bd-list-item .meta {
    color: var(--gray-700);
    font-size: 0.875rem;
    }

    /* Campaign Cards */
    .bd-campaigns {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    }

    .bd-campaign-card {
    border: none;
    border-radius: var(--radius);
    overflow: hidden;
    transition: transform var(--transition), box-shadow var(--transition);
    box-shadow: var(--shadow-sm);
    height: 100%;
    display: flex;
    flex-direction: column;
    background: white;
    }

    .bd-campaign-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    }

    .bd-campaign-img {
    position: relative;
    overflow: hidden;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
    }

    .bd-campaign-img img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition);
    }

    .bd-campaign-card:hover .bd-campaign-img img {
    transform: scale(1.05);
    }

    .bd-campaign-tag {
    position: absolute;
    right: 1rem;
    top: 1rem;
    z-index: 2;
    background: rgba(255, 255, 255, 0.9);
    color: var(--primary);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.35rem 0.75rem;
    border-radius: 2rem;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .bd-campaign-body {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    }

    .bd-campaign-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    line-height: 1.4;
    color: var(--gray-900);
    transition: color var(--transition-fast);
    }

    .bd-campaign-card:hover .bd-campaign-title {
    color: var(--primary);
    }

    .bd-campaign-text {
    color: var(--gray-700);
    margin-bottom: 1.25rem;
    flex-grow: 1;
    }

    .bd-campaign-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    font-size: 0.875rem;
    }

    .bd-campaign-date {
    color: var(--gray-700);
    }

    /* Buttons & Filters */
    .bd-filter-group {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 2.5rem;
    }

    .bd-btn {
    display: inline-block;
    padding: 0.5rem 1.25rem;
    border-radius: 2rem;
    font-weight: 500;
    text-align: center;
    vertical-align: middle;
    user-select: none;
    background: transparent;
    transition: all var(--transition);
    text-decoration: none;
    }

    .bd-btn-outline {
    border: 2px solid var(--primary);
    color: var(--primary);
    }

    .bd-btn-outline:hover,
    .bd-btn-outline.active {
    background: var(--primary);
    color: white;
    box-shadow: 0 4px 10px rgba(30, 58, 138, 0.25);
    }

    .bd-btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border: none;
    box-shadow: 0 4px 10px rgba(30, 58, 138, 0.25);
    }

    .bd-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(30, 58, 138, 0.3);
    color: white;
    }

    .bd-btn-sm {
    padding: 0.35rem 0.85rem;
    font-size: 0.875rem;
    }

    .bd-btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.125rem;
    }

    .bd-center {
    text-align: center;
    }

    /* Animations */
    .bd-fade-up {
    animation: bdFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
    }

    .bd-delay-1 { animation-delay: 0.1s; }
    .bd-delay-2 { animation-delay: 0.2s; }
    .bd-delay-3 { animation-delay: 0.3s; }

    @keyframes bdFadeUp {
    from {
    opacity: 0;
    transform: translateY(20px);
    }
    to {
    opacity: 1;
    transform: translateY(0);
    }
    }

    /* Utility Classes */
    .bd-mt-sm { margin-top: 1rem; }
    .bd-mt-md { margin-top: 2rem; }
    .bd-mt-lg { margin-top: 3rem; }
    .bd-mb-sm { margin-bottom: 1rem; }
    .bd-mb-md { margin-bottom: 2rem; }
    .bd-mb-lg { margin-bottom: 3rem; }
    </style>
@endsection

@section('content')
        <div class="bd-times">
            <!-- Modern Hero Section -->
            <div class="bd-hero bd-fade-up">
                <div class="bd-container">
                    <h1 class="display-1">বাংলাদেশ উন্নয়ন অভিযান</h1>
                    <p class="lead">সামাজিক পরিবর্তন এবং জাতীয় উন্নয়নের জন্য আমাদের অবিরাম প্রচেষ্টা</p>

                    <!-- Quick Access Buttons -->
                    <div class="bd-filter-group bd-mt-md">
    @auth
        @php
            $user = Auth::user();
        @endphp

        @if($user->membership_status === 'approved' || $user->isAdmin())
                                <a href="{{ route('frontend.campaigns.index') }}" class="bd-btn bd-btn-primary bd-btn-sm">
                                    <i class="fas fa-bullhorn me-2"></i> ক্যাম্পেইন দেখুন
                                </a>
                            @endif
    @endauth

                        <a href="{{ route('membership.index') }}" class="bd-btn bd-btn-outline bd-btn-sm">
                            <i class="fas fa-user-plus me-2"></i> সদস্য গ্রহণ
                        </a>

                    </div>
                </div>
            </div>

@auth
    @php
        $user = Auth::user();
    @endphp

    @if($user->membership_status === 'approved' || $user->isAdmin())
                    <!-- Main Content - Single Container Layout -->
                    <div class="bd-container">
                        <!-- Featured Campaign Slider Section -->
                        <div class="bd-section bd-fade-up bd-delay-1">
                            <h2 class="bd-section-title">প্রধান ক্যাম্পেইন</h2>

                            <div id="featuredCampaignSlider" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    @foreach($featuredCampaigns as $key => $campaign)
                                        <button type="button" data-bs-target="#featuredCampaignSlider" data-bs-slide-to="{{ $key }}" 
                                            class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" 
                                            aria-label="Slide {{ $key + 1 }}"></button>
                                    @endforeach
                                </div>

                                <div class="carousel-inner">
                                    @forelse($featuredCampaigns as $key => $campaign)
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <div class="bd-campaign-card mx-auto" style="max-width: 800px;">
                                                <div class="bd-campaign-img">
                                                    @if($campaign->images->count() > 0)
                                                        <img src="{{ asset('storage/' . $campaign->images->first()->file_path) }}" 
                                                            alt="{{ $campaign->title }}">
                                                    @else
                                                        <img src="{{ asset('images/campaign-placeholder.jpg') }}" 
                                                            alt="{{ $campaign->title }}">
                                                    @endif
                                                    <div class="bd-campaign-tag">
                                                        <i class="fas fa-star me-1"></i> বিশেষ
                                                    </div>
                                                </div>
                                                <div class="bd-campaign-body">
                                                    <h3 class="bd-campaign-title">{{ $campaign->title }}</h3>
                                                    <p class="bd-campaign-text">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($campaign->description), 200) }}
                                                    </p>
                                                    <div class="bd-campaign-meta">
                                                        <span class="bd-campaign-date">
                                                            <i class="far fa-calendar-alt me-1"></i>
                                                            {{ $campaign->start_date->format('d/m/Y') }}
                                                        </span>
                                                        <a href="{{ route('frontend.campaigns.show', $campaign->id) }}" 
                                                        class="bd-btn bd-btn-primary bd-btn-sm">
                                                            বিস্তারিত <i class="fas fa-arrow-right ms-1"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="carousel-item active">
                                            <div class="alert alert-info text-center py-4 w-100">
                                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                                <p class="mb-0">বর্তমানে কোন বিশেষ ক্যাম্পেইন নেই।</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>

                            @if($featuredCampaigns->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#featuredCampaignSlider" data-bs-slide="prev"
                                    style="background-color: rgba(0, 0, 0, 0.5); border-radius: 50%; width: 40px; height: 40px; top: 50%; transform: translateY(-50%); left: 15px;">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>

                                <button class="carousel-control-next" type="button" data-bs-target="#featuredCampaignSlider" data-bs-slide="next"
                                    style="background-color: rgba(0, 0, 0, 0.5); border-radius: 50%; width: 40px; height: 40px; top: 50%; transform: translateY(-50%); right: 15px;">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif

                            </div>
                        </div>

                        <!-- Top Performing Campaigns Section -->
                        <div class="bd-section bd-fade-up bd-delay-2">
                            <h2 class="bd-section-title">সর্বাধিক জনপ্রিয় ক্যাম্পেইন</h2>

                            <div class="bd-campaigns" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                                @forelse($topCampaigns as $campaign)
                                    <div class="bd-campaign-card">
                                        <div class="bd-campaign-img">
                                            @if($campaign->images->count() > 0)
                                                <img src="{{ asset('storage/' . $campaign->images->first()->file_path) }}" 
                                                    alt="{{ $campaign->title }}">
                                            @else
                                                <img src="{{ asset('images/campaign-placeholder.jpg') }}" 
                                                    alt="{{ $campaign->title }}">
                                            @endif
                                            <div class="bd-campaign-tag" style="background-color: var(--accent);">
                                                <i class="fas fa-chart-line me-1"></i> জনপ্রিয়
                                            </div>
                                        </div>
                                        <div class="bd-campaign-body">
                                            <h3 class="bd-campaign-title">{{ $campaign->title }}</h3>
                                            <p class="bd-campaign-text">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($campaign->description), 120) }}
                                            </p>
                                            <div class="campaign-stats" style="display: flex; margin-bottom: 15px;">
                                                <div style="flex: 1; text-align: center; border-right: 1px solid var(--gray-200);">
                                                    <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">
                                                        {{ number_format($campaign->analytics->views) }}
                                                    </div>
                                                    <div style="font-size: 0.75rem; color: var(--gray-700);">দর্শন</div>
                                                </div>
                                                <div style="flex: 1; text-align: center; border-right: 1px solid var(--gray-200);">
                                                    <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">
                                                        {{ number_format($campaign->analytics->engagements) }}
                                                    </div>
                                                    <div style="font-size: 0.75rem; color: var(--gray-700);">ইন্টারঅ্যাকশন</div>
                                                </div>
                                                <div style="flex: 1; text-align: center;">
                                                    <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">
                                                        {{ number_format($campaign->analytics->supporters_count) }}
                                                    </div>
                                                    <div style="font-size: 0.75rem; color: var(--gray-700);">সমর্থক</div>
                                                </div>
                                            </div>
                                            <div class="bd-campaign-meta">
                                                <span class="bd-campaign-date">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $campaign->start_date->format('d/m/Y') }}
                                                </span>
                                                <a href="{{ route('frontend.campaigns.show', $campaign->id) }}" 
                                                class="bd-btn bd-btn-primary bd-btn-sm">
                                                    বিস্তারিত <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-info text-center py-4">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <p class="mb-0">পর্যাপ্ত তথ্য নেই।</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Two Column Flex Layout -->
                        <div class="bd-grid" style="grid-template-columns: 1fr 1fr; grid-template-rows: auto auto;">

                                            <!-- Our Campaigns Section - Spans Full Width -->
                            <div class="bd-fade-up bd-delay-3" style="grid-column: 1 / -1;">
                                <div class="bd-section">
                                    <h2 class="bd-section-title">আমাদের ক্যাম্পেইন</h2>

                                    <!-- Filter Buttons -->
                                    <div class="bd-filter-group">
                                        <button type="button" class="bd-btn bd-btn-outline active" data-filter="all">
                                            সব ক্যাম্পেইন
                                        </button>
                                        <button type="button" class="bd-btn bd-btn-outline" data-filter="nationwide">
                                            জাতীয় পর্যায়ে
                                        </button>
                                        <button type="button" class="bd-btn bd-btn-outline" data-filter="regional">
                                            আঞ্চলিক
                                        </button>
                                        <button type="button" class="bd-btn bd-btn-outline" data-filter="local">
                                            স্থানীয়
                                        </button>
                                    </div>

                                    <!-- Campaigns Grid (3 column layout) -->
                                    <div class="bd-campaigns" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
                                    @forelse($latestCampaigns as $campaign)
                                            <div class="bd-campaign-card" 
                                                data-category="{{ $campaign->is_nationwide ? 'nationwide' :
                    ($campaign->campaign_type == 'division' ? 'regional' :
                        ($campaign->campaign_type == 'district' ? 'local' : 'other')) }}">
                                                <div class="bd-campaign-img">
                                                    @if($campaign->images->count() > 0)
                                                        <img src="{{ asset('storage/' . $campaign->images->first()->file_path) }}" 
                                                            alt="{{ $campaign->title }}">
                                                    @else
                                                        <img src="{{ asset('images/campaign-placeholder.jpg') }}" 
                                                            alt="{{ $campaign->title }}">
                                                    @endif

                                                    <div class="bd-campaign-tag">
                                                        @if($campaign->is_nationwide)
                                                            <i class="fas fa-flag me-1"></i> জাতীয়
                                                        @elseif($campaign->campaign_type == 'division')
                                                            <i class="fas fa-map me-1"></i> আঞ্চলিক
                                                        @elseif($campaign->campaign_type == 'district')
                                                            <i class="fas fa-map-marker me-1"></i> স্থানীয়
                                                        @else
                                                            <i class="fas fa-globe me-1"></i> অন্যান্য
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="bd-campaign-body">
                                                    <h3 class="bd-campaign-title">{{ $campaign->title }}</h3>
                                                    <p class="bd-campaign-text">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($campaign->description), 100) }}
                                                    </p>
                                                    <div class="bd-campaign-meta">
                                                        <span class="bd-campaign-date">
                                                            <i class="far fa-calendar-alt me-1"></i>
                                                            {{ $campaign->start_date->format('d/m/Y') }}
                                                        </span>
                                                        <a href="{{ route('frontend.campaigns.show', $campaign->id) }}" 
                                                        class="bd-btn bd-btn-primary bd-btn-sm">
                                                            বিস্তারিত <i class="fas fa-arrow-right ms-1"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                    @empty
                                    <div class="col-12">
                                    <div class="alert alert-info text-center py-4">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <p class="mb-0">বর্তমানে কোন ক্যাম্পেইন নেই।</p>
                                    </div>
                                    </div>
                                    @endforelse
                                    </div>

                                    <!-- View All Button -->
                                    <div class="bd-center bd-mt-lg">
                                        <a href="{{ route('frontend.campaigns.index') }}" class="bd-btn bd-btn-primary bd-btn-lg">
                                            সকল ক্যাম্পেইন দেখুন <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Upcoming Events Section -->
                            <div class="bd-fade-up bd-delay-1">
                                <div class="bd-card">
                                    <div class="bd-card-header">
                                        <h2 class="bd-card-title">আসন্ন ইভেন্টস</h2>
                                    </div>
                                    <div class="bd-card-body">
                                        <div class="bd-list-item">
                                            <div class="badge">
                                                <i class="far fa-calendar-alt me-1"></i> ২৮ মে, ২০২৫
                                            </div>
                                            <h4 class="title">রাজধানী ঢাকায় বার্ষিক সম্মেলন</h4>
                                            <p class="meta">
                                                <i class="fas fa-map-marker-alt me-1"></i> বঙ্গবন্ধু আন্তর্জাতিক সম্মেলন কেন্দ্র
                                            </p>
                                        </div>
                                        <div class="bd-list-item">
                                            <div class="badge">
                                                <i class="far fa-calendar-alt me-1"></i> ১৫ জুন, ২০২৫
                                            </div>
                                            <h4 class="title">নেতৃত্ব বিকাশ কর্মশালা</h4>
                                            <p class="meta">
                                                <i class="fas fa-map-marker-alt me-1"></i> উন্নয়ন কম্পিউটার প্রশিক্ষণ কেন্দ্র, ঢাকা
                                            </p>
                                        </div>
                                        <div class="bd-list-item">
                                            <div class="badge">
                                                <i class="far fa-calendar-alt me-1"></i> ২২ জুন, ২০২৫
                                            </div>
                                            <h4 class="title">সামাজিক উন্নয়ন প্রজেক্ট উদ্বোধন</h4>
                                            <p class="meta">
                                                <i class="fas fa-map-marker-alt me-1"></i> রাজশাহী সিটি কমিউনিটি সেন্টার
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent News Section -->
                            <div class="bd-fade-up bd-delay-2">
                                <div class="bd-card">
                                    <div class="bd-card-header">
                                        <h2 class="bd-card-title">সাম্প্রতিক সংবাদ</h2>
                                    </div>
                                    <div class="bd-card-body">
                                        <div class="bd-list-item">
                                            <div class="badge">
                                                <i class="far fa-newspaper me-1"></i> ১৫ এপ্রিল, ২০২৫
                                            </div>
                                            <h4 class="title">নতুন শাখা উদ্বোধন করেছে বাংলাদেশ উন্নয়ন আন্দোলন</h4>
                                            <p class="meta">খুলনা বিভাগে নতুন শাখা উদ্বোধন করেছে বাংলাদেশ উন্নয়ন আন্দোলন।</p>
                                        </div>
                                        <div class="bd-list-item">
                                            <div class="badge">
                                                <i class="far fa-newspaper me-1"></i> ২২ এপ্রিল, ২০২৫
                                            </div>
                                            <h4 class="title">যুব উন্নয়ন প্রকল্পের সাফল্য</h4>
                                            <p class="meta">গত বছর শুরু হওয়া যুব উন্নয়ন প্রকল্প এখন ফলপ্রসূ হতে শুরু করেছে।</p>
                                        </div>
                                        <div class="bd-list-item">
                                            <div class="badge">
                                                <i class="far fa-newspaper me-1"></i> ৩০ এপ্রিল, ২০২৫
                                            </div>
                                            <h4 class="title">সফল হল বৃক্ষরোপণ ক্যাম্পেইন</h4>
                                            <p class="meta">গত মাসে শুরু হওয়া বৃক্ষরোপণ ক্যাম্পেইনে ৫০,০০০ গাছ রোপণের লক্ষ্যমাত্রা অর্জন করা গেছে।</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                @endif
            @endauth

        </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Campaign Filter functionality
            const filterButtons = document.querySelectorAll('.bd-btn-outline[data-filter]');
            const campaignCards = document.querySelectorAll('.bd-campaign-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to clicked button
                    this.classList.add('active');

                    const category = this.getAttribute('data-filter');

                    // Show or hide campaign cards based on category
                    campaignCards.forEach(card => {
                        if (category === 'all') {
                            card.style.display = 'flex';
                        } else {
                            if (card.getAttribute('data-category') === category) {
                                card.style.display = 'flex';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            });

            // Initialize animations
            setTimeout(() => {
                document.querySelectorAll('.bd-fade-up').forEach(el => {
                    el.style.opacity = '1';
                });
            }, 100);
        });
    </script>
@endsection