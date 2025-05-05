@extends('frontend.layouts.master')

@section('title', 'ক্যাম্পেইন সমূহ')

@section('styles')
    <style>
        /* Minimalist Campaign Page Styles */
        .cp-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .cp-hero {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 3rem 1.5rem;
            color: #fff;
            border-radius: 0 0 30px 30px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .cp-hero::before {
            content: "";
            position: absolute;
            height: 200%;
            width: 200%;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20,20 L80,20 L80,80 L20,80 Z' fill='%23ffffff20'/%3E%3C/svg%3E");
            top: -50%;
            left: -50%;
            transform: rotate(35deg);
            animation: cp-pattern 60s linear infinite;
        }

        @keyframes cp-pattern {
            0% {
                transform: rotate(0)
            }

            100% {
                transform: rotate(360deg)
            }
        }

        .cp-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .cp-lead {
            max-width: 600px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .cp-filter {
            background: #fff;
            margin-top: -2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            position: relative;
            z-index: 2;
            overflow: hidden;
        }

        .cp-filter-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            color: var(--primary-dark);
            font-weight: 600;
        }

        .cp-filter-body {
            padding: 0 1.25rem 1.25rem;
        }

        .cp-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .cp-card {
            display: flex;
            flex-direction: column;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            height: 100%;
        }

        .cp-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .cp-card-featured::before {
            content: "";
            position: absolute;
            inset: 0;
            border: 3px solid var(--accent);
            border-radius: 16px;
            pointer-events: none;
            z-index: 1;
        }

        .cp-img-wrap {
            position: relative;
            padding-top: 56%;
            overflow: hidden;
        }

        .cp-img-wrap img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .cp-card:hover .cp-img-wrap img {
            transform: scale(1.1);
        }

        .cp-badge {
            position: absolute;
            display: inline-block;
            padding: 0.25rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 600;
            border-radius: 30px;
            z-index: 2;
        }

        .cp-featured {
            top: 1rem;
            left: 1rem;
            background: var(--accent);
            color: var(--text-dark);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cp-type {
            top: 1rem;
            right: 1rem;
            background: var(--primary);
            color: white;
        }

        .cp-content {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .cp-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--primary-dark);
        }

        .cp-meta {
            font-size: 0.85rem;
            color: var(--gray-700);
            margin-bottom: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .cp-desc {
            color: var(--gray-700);
            margin-bottom: 1rem;
            flex-grow: 1;
        }

        .cp-stats {
            display: flex;
            margin-top: auto;
            border-top: 1px solid #f0f0f0;
            padding-top: 1rem;
            justify-content: space-between;
            align-items: center;
        }

        .cp-stat {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.8rem;
            color: var(--primary-dark);
        }

        .cp-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .cp-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .cp-empty {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .cp-grid {
                grid-template-columns: 1fr;
            }

            .cp-hero {
                padding: 2rem 1rem;
                border-radius: 0 0 20px 20px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="cp-hero">
        <div class="cp-container">
            <h1 class="cp-title">আমাদের ক্যাম্পেইন সমূহ</h1>
            <p class="cp-lead">পরিবর্তনের জন্য আমাদের অংশীদার হোন। আমাদের ক্যাম্পেইনে সমর্থন দিন এবং ইতিবাচক পরিবর্তনে
                অংশগ্রহণ করুন।</p>
        </div>
    </div>

    <div class="cp-container">
        <!-- Filter Section -->
        <div class="cp-filter">
            <button class="cp-filter-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#cpFilterBody">
                <span>ক্যাম্পেইন ফিল্টার করুন</span>
                <i class="fas fa-sliders-h"></i>
            </button>

            <div class="collapse" id="cpFilterBody">
                <div class="cp-filter-body">
                    <form action="{{ route('frontend.campaigns.index') }}" method="GET" id="filter-form" class="row">
                        <div class="col-md-4 mb-3">
                            <select class="form-select" id="campaign_type" name="campaign_type">
                                <option value="">সব ধরন</option>
                                <option value="nationwide" {{ request('campaign_type') == 'nationwide' ? 'selected' : '' }}>
                                    বাংলাদেশ জুড়ে</option>
                                <option value="division" {{ request('campaign_type') == 'division' ? 'selected' : '' }}>বিভাগ
                                    ভিত্তিক</option>
                                <option value="district" {{ request('campaign_type') == 'district' ? 'selected' : '' }}>জেলা
                                    ভিত্তিক</option>
                                <option value="upazila" {{ request('campaign_type') == 'upazila' ? 'selected' : '' }}>উপজেলা
                                    ভিত্তিক</option>
                                <option value="union" {{ request('campaign_type') == 'union' ? 'selected' : '' }}>ইউনিয়ন
                                    ভিত্তিক</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <select class="form-select" id="division_id" name="division_id">
                                <option value="">সব বিভাগ</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->bn_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <select class="form-select" id="district_id" name="district_id" {{ request('division_id') ? '' : 'disabled' }}>
                                <option value="">সব জেলা</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="ক্যাম্পেইন খুঁজুন..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4 mb-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-search me-1"></i> খুঁজুন
                            </button>
                            <a href="{{ route('frontend.campaigns.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Campaigns Grid -->
        @if($campaigns->isEmpty())
            <div class="cp-empty">
                <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                <h4>কোন ক্যাম্পেইন পাওয়া যায়নি!</h4>
                <p>দুঃখিত, আপনার অনুসন্ধান অনুযায়ী কোন ক্যাম্পেইন পাওয়া যায়নি।</p>
                <a href="{{ route('frontend.campaigns.index') }}" class="cp-btn mt-3">
                    <i class="fas fa-redo me-1"></i> সব ক্যাম্পেইন দেখুন
                </a>
            </div>
        @else
            <div class="cp-grid">
                @foreach($campaigns as $campaign)
                    <div class="cp-card {{ $campaign->featured ? 'cp-card-featured' : '' }}">
                        <div class="cp-img-wrap">
                            @if($campaign->images->count() > 0)
                                <img src="{{ asset('storage/' . $campaign->images->first()->file_path) }}" alt="{{ $campaign->title }}">
                            @else
                                <img src="{{ asset('images/campaign-placeholder.jpg') }}" alt="{{ $campaign->title }}">
                            @endif

                            @if($campaign->featured)
                                <span class="cp-badge cp-featured">
                                    <i class="fas fa-star me-1"></i> বিশেষ
                                </span>
                            @endif

                            <span class="cp-badge cp-type">
                                @if($campaign->is_nationwide)
                                    <i class="fas fa-flag me-1"></i> জাতীয়
                                @elseif($campaign->campaign_type == 'division')
                                    <i class="fas fa-map me-1"></i> বিভাগীয়
                                @elseif($campaign->campaign_type == 'district')
                                    <i class="fas fa-map-marker me-1"></i> জেলা
                                @elseif($campaign->campaign_type == 'upazila')
                                    <i class="fas fa-map-pin me-1"></i> উপজেলা
                                @else
                                    <i class="fas fa-street-view me-1"></i> ইউনিয়ন
                                @endif
                            </span>
                        </div>

                        <div class="cp-content">
                            <h3 class="cp-name">{{ $campaign->title }}</h3>

                            <div class="cp-meta">
                                <span><i class="far fa-calendar-alt me-1"></i> {{ $campaign->start_date->format('d/m/Y') }}</span>
                                <span><i class="far fa-calendar-check me-1"></i> {{ $campaign->end_date->format('d/m/Y') }}</span>
                            </div>

                            <p class="cp-desc">
                                {{ \Illuminate\Support\Str::limit(strip_tags($campaign->description), 100) }}
                            </p>

                            <div class="cp-stats">
                                <div class="d-flex gap-2">
                                    @if($campaign->analytics)
                                        <span class="cp-stat" title="দর্শন সংখ্যা">
                                            <i class="far fa-eye"></i> {{ $campaign->analytics->views }}
                                        </span>
                                        <span class="cp-stat" title="সমর্থক সংখ্যা">
                                            <i class="fas fa-users"></i> {{ $campaign->analytics->supporters_count }}
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('frontend.campaigns.show', $campaign->id) }}" class="cp-btn">
                                    বিস্তারিত <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center my-4">
                {{ $campaigns->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#division_id').on('change', function () {
                const divisionId = $(this).val();
                const districtSelect = $('#district_id');

                districtSelect.html('<option value="">সব জেলা</option>').prop('disabled', !divisionId);

                if (divisionId) {
                    $.ajax({
                        url: '/get-districts/' + divisionId,
                        type: 'GET',
                        success: function (data) {
                            $.each(data, function (id, name) {
                                districtSelect.append(new Option(name, id));
                            });

                            @if(request()->has('district_id'))
                                districtSelect.val('{{ request('district_id') }}');
                            @endif
            }
                    });
                }
            });

            @if(request()->has('division_id'))
                $('#division_id').trigger('change');
            @endif
    });
    </script>
@endsection