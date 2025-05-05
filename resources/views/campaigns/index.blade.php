@extends('layouts.base')

@section('title', 'ক্যাম্পেইন সমূহ')@section('styles_before')<style>
        .campaign-card {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            height: 100%;
        }

        .campaign-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .campaign-card .card-header {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            color: white;
            border-radius: 8px 8px 0 0;
            padding: 15px;
        }

        .campaign-card .card-footer {
            background-color: #f9f9f9;
            border-top: 1px solid #eee;
        }

        .campaign-type-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
        }

        .featured-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1;
            background-color: #FFD700;
            color: #000;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .campaign-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 0;
        }

        .campaign-status {
            position: absolute;
            bottom: 10px;
            right: 10px;
            z-index: 1;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-publish {
            background-color: #4CAF50;
            color: white;
        }

        .status-draft {
            background-color: #FFC107;
            color: black;
        }

        .status-scheduled {
            background-color: #2196F3;
            color: white;
        }

        .campaign-dates {
            font-size: 0.8rem;
            color: #666;
        }

        .filter-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .action-buttons .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
@endsection



@section('content')
                <div class="content">
                    <x-alert />

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h2 class="font-w600 mb-2">ক্যাম্পেইন সমূহ</h2>
                            <p class="text-muted">দলের ক্যাম্পেইন তালিকা ও ব্যবস্থাপনা</p>
                        </div>
                     
                    </div>

        <!-- Filters - Fixed Version -->
        <div class="filter-section mb-4">
            <form action="{{ route('campaigns.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">স্ট্যাটাস</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">সব স্ট্যাটাস</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>অপ্রকাশিত</option>
                                <option value="publish" {{ request('status') == 'publish' ? 'selected' : '' }}>প্রকাশিত</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>শিডিউল করা
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="campaign_type">ক্যাম্পেইন ধরন</label>
                            <select class="form-control" id="campaign_type" name="campaign_type">
                                <option value="">সব ধরন</option>
                                <option value="nationwide" {{ request('campaign_type') == 'nationwide' ? 'selected' : '' }}>
                                    বাংলাদেশ জুড়ে</option>
                                <option value="division" {{ request('campaign_type') == 'division' ? 'selected' : '' }}>বিভাগ
                                    ভিত্তিক</option>
                                <option value="district" {{ request('campaign_type') == 'district' ? 'selected' : '' }}>জেলা
                                    ভিত্তিক</option>
                                <option value="upazila" {{ request('campaign_type') == 'upazila' ? 'selected' : '' }}>উপজেলা
                                    ভিত্তিক</option>
                                <option value="union" {{ request('campaign_type') == 'union' ? 'selected' : '' }}>ইউনিয়ন ভিত্তিক
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="featured">ফিচারড</label>
                            <select class="form-control" id="featured" name="featured">
                                <option value="">সব</option>
                                <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>শুধু ফিচারড</option>
                                <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>ফিচারড নয়</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">সার্চ</label>
                            <input type="text" class="form-control" id="search" name="search" placeholder="ক্যাম্পেইন খুঁজুন..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search mr-1"></i> খুঁজুন
                        </button>
                        <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">
                            <i class="fa fa-redo mr-1"></i> রিসেট
                        </a>
                    </div>
                </div>
            </form>
        </div>
    <div class="row mb-4">
        
        <div class="col-md-4">
            <a href="{{ route('campaigns.create') }}" class="btn btn-success">
                <i class="fa fa-plus mr-1"></i> নতুন ক্যাম্পেইন
            </a>
        </div>
    </div>
            <!-- Campaigns List -->
            @if($campaigns->isEmpty())
                <div class="alert alert-info">
                    <i class="fa fa-info-circle mr-1"></i> কোন ক্যাম্পেইন পাওয়া যায়নি!
                </div>
            @else
                <div class="row">
                    @foreach($campaigns as $campaign)
                        <div class="col-md-4 mb-4">
                            <div class="card campaign-card">
                                @if($campaign->featured)
                                    <div class="featured-badge">
                                        <i class="fa fa-star mr-1"></i> ফিচারড
                                    </div>
                                @endif
                                <div class="campaign-type-badge badge badge-pill badge-primary">
                                    @if($campaign->is_nationwide)
                                        বাংলাদেশ জুড়ে
                                    @elseif($campaign->campaign_type == 'division')
                                        বিভাগ ভিত্তিক
                                    @elseif($campaign->campaign_type == 'district')
                                        জেলা ভিত্তিক
                                    @elseif($campaign->campaign_type == 'upazila')
                                        উপজেলা ভিত্তিক
                                    @elseif($campaign->campaign_type == 'union')
                                        ইউনিয়ন ভিত্তিক
                                    @endif
                                </div>

                                @if($campaign->images->count() > 0)
                                    <img src="{{ asset('storage/' . $campaign->images->first()->file_path) }}" class="campaign-image" alt="{{ $campaign->title }}">
                                @else
                                    <img src="{{ asset('images/campaign-placeholder.jpg') }}" class="campaign-image" alt="{{ $campaign->title }}">
                                @endif



                                <div class="card-body">
                                    <h4 class="font-w600 mb-2">{{ $campaign->title }}</h4>
                                    <div class="campaign-dates mb-2">
                                        <i class="fa fa-calendar-alt mr-1"></i> শুরু: {{ $campaign->start_date->format('d/m/Y') }}
                                        <br>
                                        <i class="fa fa-calendar-check mr-1"></i> শেষ: {{ $campaign->end_date->format('d/m/Y') }}
                                    </div>
                                    <p class="text-muted mb-0">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($campaign->description), 100) }}
                                    </p>
                                </div>
                                <div class=" badge badge-pill status-{{ $campaign->status }}">
                                    @if($campaign->status == 'publish')
                                        প্রকাশিত
                                    @elseif($campaign->status == 'draft')
                                        অপ্রকাশিত
                                    @elseif($campaign->status == 'scheduled')
                                        শিডিউল করা
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-6">
                                            @if($campaign->analytics)
                                                <span class="badge badge-light" title="দর্শন সংখ্যা">
                                                    <i class="fa fa-eye"></i> {{ $campaign->analytics->views }}
                                                </span>
                                                <span class="badge badge-light" title="সমর্থক সংখ্যা">
                                                    <i class="fa fa-users"></i> {{ $campaign->analytics->supporters_count }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-6 text-right">
                                            <!-- Create a flex container to align buttons in a row -->
                                            <div class="d-flex justify-content-end">
                                                <!-- View Button -->
                                                <a class="btn btn-sm btn-info mr-1" title="View Details" href="{{ route('campaigns.show', $campaign) }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Edit Button -->
                                                <a href="{{ route('campaigns.edit', $campaign) }}" title="Edit" class="btn btn-sm btn-info mr-1">
                                                    <i class="fa fa-edit" ></i>
                                                </a>

                                                <!-- Delete Button (Opens modal) -->
                                                <button type="button" title="Delete" class="btn btn-sm btn-danger mr-1" data-toggle="modal"
                                                    data-target="#deleteModal{{ $campaign->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                                <!-- Publish/Unpublish Button -->
                                                @if($campaign->status != 'publish')
                                                    <form action="{{ route('campaigns.change.status', $campaign) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="publish">
                                                        <button title="Publish" type="submit" class="btn btn-sm btn-success mr-1">
                                                            <i class="fa fa-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('campaigns.change.status', $campaign) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="draft">
                                                        <button title="Unpublish" type="submit" class="btn btn-sm btn-warning mr-1">
                                                            <i class="fa fa-times-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- Featured Button -->
                                                <form action="{{ route('campaigns.toggle.featured', $campaign) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" title="Change Featured Status" class="btn {{ $campaign->featured ? 'btn-danger' : 'btn-primary' }} btn-sm">
                                                        <i class="fa fa-star"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Delete Confirmation Modal for each campaign -->
                        <div class="modal fade" id="deleteModal{{ $campaign->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $campaign->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $campaign->id }}">নিশ্চিতকরণ</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        আপনি কি নিশ্চিত যে আপনি "{{ $campaign->title }}" ক্যাম্পেইন মুছতে চান?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল করুন</button>
                                        <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">মুছে ফেলুন</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>



                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $campaigns->appends(request()->query())->links() }}
                </div>

            @endif
                </div>
@endsection

@push('scripts')
    <!-- Add this JavaScript to your campaigns index page (either directly or as an included file) -->
    <script>
        $(document).ready(function () {
            // Initialize delete confirmation modals
            $('.delete-campaign-btn').on('click', function (e) {
                e.preventDefault();
                const campaignId = $(this).data('id');
                const campaignTitle = $(this).data('title');

                // Set campaign title in modal
                $('#campaignTitle').text(campaignTitle);

                // Set form action with correct campaign ID
                $('#deleteCampaignForm').attr('action', $(this).data('action'));

                // Show the modal
                $('#deleteCampaignModal').modal('show');
            });
        });
    </script>

    
@endpush    