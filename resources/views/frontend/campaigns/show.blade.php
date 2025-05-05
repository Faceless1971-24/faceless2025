{{-- resources/views/frontend/campaigns/show.blade.php --}}
@extends('frontend.layouts.master')

@section('title', $campaign->title)

@section('styles')
     <style>
        /* Campaign Website Blue Theme CSS */

    :root {
        --primary-color: #1a56db;       /* Main blue */
        --secondary-color: #3b82f6;     /* Light blue */
        --accent-color: #0d47a1;        /* Dark blue */
        --light-bg: #f0f4f8;            /* Light background */
        --dark-bg: #1e293b;             /* Dark background */
        --success-color: #047857;       /* Success green */
        --warning-color: #eab308;       /* Warning yellow */
        --border-radius: 10px;
        --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    /* Campaign Header */
    .campaign-header {
        background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 100%);
        padding: 40px 0 20px;
        position: relative;
        overflow: hidden;
    }

    .campaign-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%231a56db' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.5;
    }

    /* Page Title */
    .page-title {
        position: relative;
        margin-bottom: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .page-title::after {
        content: '';
        display: block;
        width: 70px;
        height: 4px;
        background: var(--secondary-color);
        margin-top: 8px;
        border-radius: 2px;
    }

    /* Breadcrumb */
    .custom-breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 1.5rem;
    }

    .breadcrumb-item a {
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.3s;
    }

    .breadcrumb-item a:hover {
        color: var(--secondary-color);
    }

    .breadcrumb-item.active {
        color: #64748b;
    }

    /* Campaign Images */
    .campaign-gallery {
        position: relative;
        margin-bottom: 2rem;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--box-shadow);
    }

    .campaign-main-image {
        width: 100%;
        height: 450px;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .campaign-thumbnails {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        overflow-x: auto;
        padding-bottom: 5px;
        scrollbar-width: thin;
    }

    .campaign-thumbnails::-webkit-scrollbar {
        height: 6px;
    }

    .campaign-thumbnails::-webkit-scrollbar-thumb {
        background-color: rgba(59, 130, 246, 0.5);
        border-radius: 10px;
    }

    .campaign-thumbnail {
        width: 90px;
        height: 70px;
        object-fit: cover;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
        flex-shrink: 0;
    }

    .campaign-thumbnail:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .campaign-thumbnail.active {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    /* Campaign Meta */
    .campaign-meta-container {
        background: white;
        border-radius: var(--border-radius);
        padding: 20px;
        box-shadow: var(--box-shadow);
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--primary-color);
    }

    .meta-title {
        font-size: 1.1rem;
        margin-bottom: 1rem;
        color: var(--primary-color);
        font-weight: 600;
    }

    .campaign-meta {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }

    .campaign-meta-item {
        display: flex;
        align-items: center;
        padding: 10px;
        background-color: var(--light-bg);
        border-radius: 8px;
        transition: all 0.3s;
    }

    .campaign-meta-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.1);
    }

    .meta-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(59, 130, 246, 0.1);
        border-radius: 50%;
        margin-right: 12px;
        color: var(--primary-color);
    }

    .meta-text {
        font-size: 0.9rem;
    }

    .meta-label {
        display: block;
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 2px;
    }

    /* Campaign Content Section */
    .content-section {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 30px;
        margin-bottom: 30px;
        border-top: 4px solid var(--primary-color);
    }

    .section-title {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--primary-color);
        position: relative;
        padding-bottom: 10px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: var(--secondary-color);
    }

    .campaign-description {
        line-height: 1.8;
        color: #334155;
    }

    /* Media Section */
    .media-card {
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border-radius: var(--border-radius);
        transition: all 0.3s;
        overflow: hidden;
        background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
    }

    .media-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(59, 130, 246, 0.1);
    }

    .media-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
    }

    .media-title {
        font-size: 1.2rem;
        margin-bottom: 1rem;
        color: var(--primary-color);
    }

    audio {
        width: 100%;
        border-radius: 30px;
        background-color: #f1f5f9;
    }

    audio::-webkit-media-controls-panel {
        background-color: #f1f5f9;
    }

    audio::-webkit-media-controls-play-button,
    audio::-webkit-media-controls-volume-slider,
    audio::-webkit-media-controls-mute-button {
        color: var(--primary-color);
    }

    .video-container {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Comments Section */
    .comments-section {
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--box-shadow);
    }

    .comments-header {
        background-color: var(--primary-color);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
    }

    .comments-body {
        padding: 20px;
        background-color: white;
    }

    .comment-item {
        background-color: var(--light-bg);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        transition: transform 0.3s;
        border-left: 3px solid #cbd5e1;
    }

    .comment-item:hover {
        transform: translateX(5px);
        border-left: 3px solid var(--secondary-color);
    }

    .comment-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .comment-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #e2e8f0;
        margin-right: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .comment-user {
        margin-bottom: 0;
        font-weight: 600;
        color: var(--dark-bg);
    }

    .comment-time {
        font-size: 0.8rem;
        color: #64748b;
    }

    .no-comments {
        text-align: center;
        padding: 40px 20px;
        background-color: #f8fafc;
        border-radius: 8px;
    }

    .no-comments-icon {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 15px;
    }

    .comment-form-container {
        background-color: var(--light-bg);
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        border: 1px solid #e2e8f0;
    }

    .comment-form-container textarea {
        border: 1px solid #cbd5e1;
        transition: all 0.3s;
    }

    .comment-form-container textarea:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Sidebar */
    .sidebar-card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 20px;
        margin-bottom: 25px;
        border-top: 3px solid var(--primary-color);
    }

    .sidebar-title {
        font-size: 1.2rem;
        margin-bottom: 1.2rem;
        color: var(--primary-color);
        position: relative;
        padding-bottom: 10px;
    }

    .sidebar-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background-color: var(--secondary-color);
    }

    /* Support Progress */
    .progress {
        height: 10px;
        border-radius: 5px;
        background-color: #e2e8f0;
        margin-bottom: 8px;
        overflow: visible;
    }

    .progress-bar {
        background: linear-gradient(45deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: 5px;
        position: relative;
        transition: width 1.5s ease;
    }

    .progress-bar::after {
        content: "";
        position: absolute;
        right: -2px;
        top: -5px;
        width: 20px;
        height: 20px;
        background-color: var(--secondary-color);
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 10px rgba(59, 130, 246, 0.3);
    }

    .support-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #64748b;
    }

    /* Support Form */
    .support-btn {
        background: linear-gradient(45deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s;
        border-radius: 30px;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .support-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }

    .support-success {
        background-color: #ecfdf5;
        border-left: 4px solid var(--success-color);
        border-radius: 5px;
        padding: 15px;
    }

    .login-prompt {
        background-color: #f0f9ff;
        border-left: 4px solid var(--primary-color);
        border-radius: 5px;
        padding: 15px;
    }

    /* Share Buttons */
    .share-container {
        margin-top: 1.5rem;
    }

    .share-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .share-button {
        flex: 1;
        min-width: 120px;
        padding: 10px 15px;
        border-radius: 30px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .share-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: white;
    }

    .share-facebook {
        background-color: #4267B2;
    }

    .share-twitter {
        background-color: #1DA1F2;
    }

    .share-whatsapp {
        background-color: #25D366;
    }

    /* Supporters Gallery */
    .supporters-container {
        margin-top: 1.5rem;
    }

    .supporters-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .supporter-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .supporter-avatar:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 8px 15px rgba(59, 130, 246, 0.2);
        z-index: 1;
    }

    .supporter-more {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--primary-color);
        border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .supporter-more:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(59, 130, 246, 0.2);
    }

    /* Related Campaigns */
    .related-card {
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        margin-bottom: 15px;
        border: none;
        background-color: white;
    }

    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(59, 130, 246, 0.1);
    }

    .related-image {
        height: 100%;
        object-fit: cover;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        transition: all 0.5s;
    }

    .related-card:hover .related-image {
        transform: scale(1.05);
    }

    .related-body {
        padding: 12px;
    }

    .related-title {
        font-weight: 600;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: 0.95rem;
        color: var(--dark-bg);
    }

    .related-meta {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 10px;
    }

    .related-btn {
        padding: 5px 12px;
        font-size: 0.8rem;
        border-radius: 20px;
        border-color: var(--primary-color);
        color: var(--primary-color);
        transition: all 0.3s;
    }

    .related-btn:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateX(3px);
    }

    /* Call to Action Card */
    .cta-card {
        text-align: center;
        padding: 25px 20px;
        background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 100%);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .cta-icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .cta-title {
        font-size: 1.5rem;
        color: var(--dark-bg);
        margin-bottom: 15px;
    }

    .cta-text {
        color: #475569;
        margin-bottom: 20px;
    }

    .cta-btn {
        background: linear-gradient(45deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        padding: 10px 25px;
        font-weight: 600;
        border-radius: 30px;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        transition: all 0.3s;
    }

    .cta-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.6s ease forwards;
    }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }

    /* Custom Form Styling */
    .custom-form-control {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 12px 15px;
        transition: all 0.3s;
    }

    .custom-form-control:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .custom-form-label {
        color: #475569;
        font-weight: 500;
        margin-bottom: 8px;
    }

    /* Button Enhancement */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Responsive fixes */
    @media (max-width: 992px) {
        .campaign-meta {
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .campaign-meta {
            grid-template-columns: 1fr 1fr;
        }

        .content-section {
            padding: 20px;
        }

        .campaign-main-image {
            height: 350px;
        }

        .share-button {
            min-width: 100px;
        }
    }

    @media (max-width: 576px) {
        .campaign-meta {
            grid-template-columns: 1fr;
        }

        .campaign-main-image {
            height: 250px;
        }

        .share-button {
            flex: 100%;
            margin-bottom: 10px;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .section-title {
            font-size: 1.3rem;
        }
    }

    /* Enhanced Accessibility */
    .screen-reader-text {
        border: 0;
        clip: rect(1px, 1px, 1px, 1px);
        clip-path: inset(50%);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute !important;
        width: 1px;
        word-wrap: normal !important;
    }

    .skip-link {
        background-color: var(--primary-color);
        color: white;
        font-weight: 700;
        left: 50%;
        padding: 8px;
        position: absolute;
        transform: translateY(-100%);
        transition: transform 0.3s;
    }

    .skip-link:focus {
        transform: translateY(0%);
    }
     </style>
@endsection

@section('content')
        <div class="campaign-header">
            <div class="container py-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb custom-breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('frontend.campaigns.index') }}">ক্যাম্পেইন</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $campaign->title }}</li>
                    </ol>
                </nav>

                <h1 class="page-title">{{ $campaign->title }}</h1>
            </div>
        </div>

        <div class="container py-5">
            <div class="row g-4">
                <!-- Main Content Column -->
                <div class="col-lg-8">
                    <!-- Campaign Gallery -->
                    <div class="campaign-gallery">
                        @if($campaign->images->count() > 0)
                            <img id="main-campaign-image" src="{{ asset('storage/' . $campaign->images->first()->file_path) }}"
                                class="campaign-main-image" alt="{{ $campaign->title }}">
                        @else
                            <img src="{{ asset('images/campaign-placeholder.jpg') }}" class="campaign-main-image"
                                alt="{{ $campaign->title }}">
                        @endif

                        @if($campaign->images->count() > 1)
                            <div class="campaign-thumbnails">
                                @foreach($campaign->images as $index => $image)
                                    <img src="{{ asset('storage/' . $image->file_path) }}"
                                        class="campaign-thumbnail {{ $index === 0 ? 'active' : '' }}"
                                        data-src="{{ asset('storage/' . $image->file_path) }}" alt="Campaign image {{ $index + 1 }}">
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Campaign Meta Information -->
                    <div class="campaign-meta-container">
                        <h2 class="meta-title">ক্যাম্পেইন তথ্য</h2>
                        <div class="campaign-meta">
                            <div class="campaign-meta-item">
                                <div class="meta-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">অবস্থান</span>
                                    <strong>
                                        @if($campaign->is_nationwide)
                                            বাংলাদেশ জুড়ে
                                        @else
                                            @if($campaign->divisions->isNotEmpty())
                                                {{ $campaign->divisions->pluck('name')->join(', ') }}
                                            @endif

                                            @if($campaign->districts->isNotEmpty())
                                                , {{ $campaign->districts->pluck('name')->join(', ') }}
                                            @endif

                                            @if($campaign->upazilas->isNotEmpty())
                                                , {{ $campaign->upazilas->pluck('name')->join(', ') }}
                                            @endif

                                            @if($campaign->unions->isNotEmpty())
                                                , {{ $campaign->unions->pluck('name')->join(', ') }}
                                            @endif
                                        @endif
                                    </strong>

                                </div>
                            </div>

                            <div class="campaign-meta-item">
                                <div class="meta-icon">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">শুরুর তারিখ</span>
                                    <strong>{{ $campaign->start_date->format('d/m/Y') }}</strong>
                                </div>
                            </div>

                            <div class="campaign-meta-item">
                                <div class="meta-icon">
                                    <i class="far fa-calendar-check"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">শেষের তারিখ</span>
                                    <strong>{{ $campaign->end_date->format('d/m/Y') }}</strong>
                                </div>
                            </div>

                            <div class="campaign-meta-item">
                                <div class="meta-icon">
                                    <i class="far fa-eye"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">দর্শন</span>
                                    <strong>{{ $campaign->analytics ? $campaign->analytics->views : 0 }} জন</strong>
                                </div>
                            </div>

                            <div class="campaign-meta-item">
                                <div class="meta-icon">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">সমর্থন</span>
                                    <strong>{{ $campaign->analytics ? $campaign->analytics->supporters_count : 0 }} জন</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campaign Description -->
                    <div class="content-section">
                        <h2 class="section-title">ক্যাম্পেইন বিবরণ</h2>
                        <div class="campaign-description">
                            {!! $campaign->description !!}
                        </div>
                    </div>

                    <!-- Campaign Media (Audio/Video) -->
                    <div class="row g-4">
                        @if($campaign->audio)
                            <div class="col-md-12">
                                <div class="content-section">
                                    <h2 class="section-title">অডিও</h2>
                                    <div class="media-card p-4">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="media-icon me-3">
                                                <i class="fas fa-headphones"></i>
                                            </div>
                                            <h3 class="media-title mb-0">{{ $campaign->audio->title ?: 'ক্যাম্পেইন অডিও' }}</h3>
                                        </div>
                                        <audio controls class="w-100">
                                            <source src="{{ asset('storage/' . $campaign->audio->file_path) }}" type="audio/mp3">
                                            আপনার ব্রাউজার অডিও সাপোর্ট করে না।
                                        </audio>
                                        @if($campaign->audio->description)
                                            <p class="mt-3 text-muted">{{ $campaign->audio->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($campaign->video)
                            <div class="col-md-12">
                                <div class="content-section">
                                    <h2 class="section-title">ভিডিও</h2>
                                    <div class="media-card p-4">
                                        <h3 class="media-title">{{ $campaign->video->title ?: 'ক্যাম্পেইন ভিডিও' }}</h3>
                                        <div class="video-container">
                                            @if(Str::contains($campaign->video->file_path, ['youtube.com', 'youtu.be']))
                                                <div class="ratio ratio-16x9 mb-3">
                                                    <iframe
                                                        src="{{ Str::contains($campaign->video->file_path, 'youtu.be')
            ? 'https://www.youtube.com/embed/' . Str::after($campaign->video->file_path, 'youtu.be/')
            : 'https://www.youtube.com/embed/' . Str::after($campaign->video->file_path, 'v=') }}"
                                                        title="{{ $campaign->video->title }}" allowfullscreen>
                                                    </iframe>
                                                </div>
                                            @else
                                                <div class="ratio ratio-16x9 mb-3">
                                                    <video controls>
                                                        <source src="{{ asset('storage/' . $campaign->video->file_path) }}"
                                                            type="video/mp4">
                                                        আপনার ব্রাউজার ভিডিও সাপোর্ট করে না।
                                                    </video>
                                                </div>
                                            @endif
                                        </div>
                                        @if($campaign->video->description)
                                            <p class="mt-3 text-muted">{{ $campaign->video->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Comments Section -->
                   <!-- Comments Section -->
                <div class="comments-section">
                    <div class="comments-header mb-2">
                        <h2 class="mb-0"><i class="far fa-comments me-2"></i> মতামত</h2>
                    </div>
                    <div class="comments-body">
                        @if($campaign->comments->count() > 0)
                            <div class="row comments-list gx-2 gy-2">
                                @foreach($campaign->comments as $comment)
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="comment-item border rounded p-2 h-100 d-flex">
                                            @if($comment->user->profile_photo_path)
                                                <img src="{{ asset('storage/' . $comment->user->profile_photo_path) }}"
                                                    alt="{{ $comment->user->name }}" class="comment-avatar me-2"
                                                    style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                                            @else
                                                <div class="comment-avatar me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px; background: #e9ecef; border-radius: 50%;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="comment-user mb-0">{{ $comment->user->name }}</h6>
                                                    <small class="text-muted comment-time">{{ $comment->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-0 small comment-body">{{ $comment->comment }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="no-comments text-center py-3">
                                <div class="no-comments-icon mb-2">
                                    <i class="far fa-comment-dots fs-3 text-muted"></i>
                                </div>
                                <h5 class="mb-1">এখনও কোন মতামত নেই</h5>
                                <p class="text-muted small mb-0">প্রথম মতামত দিয়ে আলোচনা শুরু করুন!</p>
                            </div>
                        @endif

                        @auth
                            <div class="comment-form-container mt-3">
                                <h5 class="mb-2">আপনার মতামত জানান</h5>
                                <form action="{{ route('frontend.campaigns.comment', $campaign) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <textarea id="comment" name="comment" class="form-control @error('comment') is-invalid @enderror"
                                            rows="2" placeholder="আপনার মতামত লিখুন..." required></textarea>
                                        @error('comment')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary support-btn">
                                        <i class="far fa-paper-plane me-1"></i> মতামত জমা দিন
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="login-prompt mt-3 small">
                                <i class="fas fa-info-circle me-1"></i> মতামত দিতে
                                <a href="{{ route('login') }}" class="ms-1">লগইন</a> করুন।
                            </div>
                        @endauth
                    </div>
                </div>




                </div>

                <!-- Sidebar Column -->
                <div class="col-lg-4">
                    <!-- Support Card -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">ক্যাম্পেইন সমর্থন</h3>

                        @if($campaign->analytics)
                            <div class="mb-4">
                                <h6 class="mb-2">সমর্থন অগ্রগতি</h6>
                                <div class="progress">
                                    @php
    $targetSupporters = 1000; // Example target
    $currentSupporters = $campaign->analytics->supporters_count;
    $percentage = min(100, round(($currentSupporters / $targetSupporters) * 100));
                                    @endphp
                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="support-stats mt-2">
                                    <span>{{ $currentSupporters }} জন</span>
                                    <span>লক্ষ্য: {{ $targetSupporters }} জন</span>
                                </div>
                            </div>
                        @endif

                        @auth
                            @if(!$hasSupported)
                                <form action="{{ route('frontend.campaigns.support', $campaign) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="support-message" class="form-label">আপনার মতামত (ঐচ্ছিক)</label>
                                        <textarea id="support-message" name="message" class="form-control" rows="3"
                                            placeholder="আপনার অভিজ্ঞতা শেয়ার করুন..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success support-btn w-100">
                                        <i class="fas fa-thumbs-up me-2"></i> সমর্থন করুন
                                    </button>
                                </form>
                            @else
                                <div class="support-success">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2 fa-2x"></i>
                                        <div>
                                            <h5 class="mb-1">ধন্যবাদ!</h5>
                                            <p class="mb-0">আপনি ইতিমধ্যে এই ক্যাম্পেইন সমর্থন করেছেন!</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="login-prompt">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle text-info me-2 fa-2x"></i>
                                    <div>
                                        <h5 class="mb-1">সমর্থন করুন</h5>
                                        <p class="mb-0">ক্যাম্পেইন সমর্থন করতে <a href="{{ route('login') }}">লগইন</a> করুন।</p>
                                    </div>
                                </div>
                            </div>
                        @endauth

                        <!-- Share Buttons -->
                        <div class="share-container">
                            <h6 class="mb-3"><i class="fas fa-share-alt me-2"></i> শেয়ার করুন</h6>
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('frontend.campaigns.show', $campaign->id)) }}"
                                    target="_blank" class="share-button share-facebook" id="share-facebook">
                                    <i class="fab fa-facebook-f me-2"></i> ফেসবুক
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('frontend.campaigns.show', $campaign->id)) }}&text={{ urlencode($campaign->title) }}"
                                    target="_blank" class="share-button share-twitter" id="share-twitter">
                                    <i class="fab fa-twitter me-2"></i> টুইটার
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($campaign->title . ' - ' . route('frontend.campaigns.show', $campaign->id)) }}"
                                    target="_blank" class="share-button share-whatsapp" id="share-whatsapp">
                                    <i class="fab fa-whatsapp me-2"></i> হোয়াটসঅ্যাপ
                                </a>
                            </div>
                        </div>

                        <!-- Supporters Gallery -->
                        @if($campaign->supporters->count() > 0)
                            <div class="supporters-container">
                                <h6 class="mb-3"><i class="fas fa-users me-2"></i> সর্বশেষ সমর্থক</h6>
                                <div class="supporters-gallery">
                                    @foreach($campaign->supporters->take(10) as $supporter)
                                        <div class="supporter-profile" title="{{ $supporter->user->name }}">
                                            @if($supporter->user->profile_photo_path)
                                                <img src="{{ asset('storage/' . $supporter->user->profile_photo_path) }}"
                                                    alt="{{ $supporter->user->name }}" class="supporter-avatar">
                                            @else
                                                <div class="supporter-avatar d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    @if($campaign->analytics && $campaign->analytics->supporters_count > 10)
                                        <div class="supporter-profile" title="{{ $campaign->analytics->supporters_count - 10 }} জন আরও">
                                            <div class="supporter-more">
                                                <span>+{{ $campaign->analytics->supporters_count - 10 }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Related Campaigns -->
                    @if($relatedCampaigns->count() > 0)
                        <div class="sidebar-card">
                            <h3 class="sidebar-title">সম্পর্কিত ক্যাম্পেইন</h3>

                            @foreach($relatedCampaigns as $relatedCampaign)
                                <div class="card related-card mb-3">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-4">
                                            @if($relatedCampaign->images->count() > 0)
                                                <img src="{{ asset('storage/' . $relatedCampaign->images->first()->file_path) }}"
                                                    class="related-image img-fluid rounded-start" alt="{{ $relatedCampaign->title }}">
                                            @else
                                                <img src="{{ asset('images/campaign-placeholder.jpg') }}" class="related-image img-fluid rounded-start"
                                                    alt="{{ $relatedCampaign->title }}">
                                            @endif
                                        </div>

                                        <div class="col-8">
                                            <div class="related-body p-2">
                                                <h6 class="related-title mb-1">
                                                    {{ Str::limit($relatedCampaign->title, 40) }}
                                                </h6>

                                                <p class="related-meta mb-2 small text-muted">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $relatedCampaign->start_date->format('d/m/Y') }}
                                                </p>

                                                <a href="{{ route('frontend.campaigns.show', $relatedCampaign->id) }}"
                                                    class="btn btn-sm btn-outline-primary related-btn">
                                                    <i class="fas fa-arrow-right me-1"></i> বিস্তারিত দেখুন
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Call to Action Card
                    <div class="sidebar-card bg-light">
                        <div class="text-center py-3">
                            <div class="mb-3">
                                <i class="fas fa-bullhorn fa-3x text-primary"></i>
                            </div>
                            <h4 class="mb-3">নিজের ক্যাম্পেইন শুরু করুন</h4>
                            <p class="mb-4">আপনার নিজের সামাজিক উদ্যোগ বা আন্দোলন শুরু করতে চান? আজই একটি নতুন ক্যাম্পেইন তৈরি
                                করুন।</p>

                        </div>
                    </div> -->
                </div>
            </div>
        </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Image thumbnail switcher
            $('.campaign-thumbnail').on('click', function () {
                const newSrc = $(this).data('src');
                $('#main-campaign-image').attr('src', newSrc);
                $('.campaign-thumbnail').removeClass('active');
                $(this).addClass('active');
            });

            // Handle social sharing analytics
            $('.share-button').on('click', function (e) {
                // Record share analytics
                $.ajax({
                    url: "{{ route('frontend.campaigns.share', $campaign) }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        console.log('Share recorded successfully');
                    },
                    error: function (error) {
                        console.log('Error recording share');
                    }
                });
            });

            // Smooth scroll to comments when clicking comment button
            $('.scroll-to-comments').on('click', function (e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.comments-section').offset().top - 100
                }, 500);
            });

            // Add animation when page loads
            $('.campaign-meta-item, .sidebar-card, .content-section').addClass('animate__animated animate__fadeIn');
        });
    </script>
@endsection