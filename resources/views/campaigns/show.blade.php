@extends('layouts.base')

@section('title', 'ক্যাম্পেইন বিস্তারিত')

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="h3 fw-bold mb-1">
                    <a href="{{ route('campaigns.index') }}" class="text-decoration-none">ক্যাম্পেইন</a>
                    <small class="fw-normal text-muted"> / বিস্তারিত</small>
                </h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="content">
        <!-- Basic Info Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ $campaign->title }}</h3>
                <div class="block-options">
                    <span class="badge rounded-pill 
                        @if($campaign->status == 'publish') bg-success 
                        @elseif($campaign->status == 'draft') bg-secondary 
                        @else bg-warning @endif">
                        @if($campaign->status == 'publish') প্রকাশিত
                        @elseif($campaign->status == 'draft') অপ্রকাশিত
                        @elseif($campaign->status == 'scheduled') শিডিউল করা
                        @endif
                    </span>
                </div>
            </div>

            <div class="block-content">
                <div class="row">
                    <!-- Left Column: Image and Info -->
                    <div class="col-md-4 mb-4">
                        <!-- Campaign Image -->
                        <div class="mb-4 text-center">
                            @if($campaign->images->count() > 0)
                                <img class="img-fluid rounded" 
                                    src="{{ asset('storage/' . $campaign->images->first()->file_path) }}"
                                    alt="{{ $campaign->title }}">
                            @else
                                <img class="img-fluid rounded" 
                                    src="{{ asset('images/campaign-placeholder.jpg') }}" 
                                    alt="{{ $campaign->title }}">
                            @endif
                        </div>

                        <!-- Key Information -->
                        <div class="fs-sm mb-4">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <strong>তৈরিকারক:</strong>
                                <span>{{ $campaign->creator ? $campaign->creator->name : 'অজানা' }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <strong>ক্যাম্পেইনের ধরন:</strong>
                                <span>
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
                                </span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <strong>শুরুর তারিখ:</strong>
                                <span>{{ $campaign->start_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <strong>শেষের তারিখ:</strong>
                                <span>{{ $campaign->end_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <strong>তৈরি হয়েছে:</strong>
                                <span>{{ $campaign->created_at->format('d M Y') }}</span>
                            </div>
                            @if($campaign->analytics)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <strong>ভিউ সংখ্যা:</strong>
                                <span>{{ $campaign->analytics->views }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <strong>সমর্থক সংখ্যা:</strong>
                                <span>{{ $campaign->analytics->supporters_count }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex">
                            <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-alt-primary flex-grow-1 me-2">
                                <i class="fa fa-edit me-1"></i> এডিট করুন
                            </a>
                                <!-- Delete Button -->
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteCampaignModal">
                                    <i class="fa fa-trash me-1"></i> মুছে ফেলুন
                                </button>

                        </div>
                    </div>

                    <!-- Right Column: Description and Other Info -->
                    <div class="col-md-8">
                        <!-- Description Section -->
                        <div class="mb-5">
                            <h4 class="fw-semibold mb-3 border-bottom pb-2">ক্যাম্পেইন বিবরণ</h4>
                            <div class="campaign-description">
                                {!! $campaign->description !!}
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="mb-5">
                            <h4 class="fw-semibold mb-3 border-bottom pb-2">অবস্থান</h4>
                            @if($campaign->is_nationwide)
                                <div class="alert alert-info">
                                    <i class="fa fa-globe me-2"></i> এই ক্যাম্পেইন বাংলাদেশের সকল অঞ্চলে চলবে।
                                </div>
                            @else
                                <div class="row">
                                    <!-- Divisions -->
                                    @if($campaign->divisions->count() > 0)
                                        <div class="col-md-6 mb-3">
                                            <h6 class="fw-semibold text-muted mb-2">বিভাগ:</h6>
                                            <div>
                                                @foreach($campaign->divisions as $division)
                                                    <span class="badge bg-primary mb-1 me-1">{{ $division->bn_name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Districts -->
                                    @if($campaign->districts->count() > 0)
                                        <div class="col-md-6 mb-3">
                                            <h6 class="fw-semibold text-muted mb-2">জেলা:</h6>
                                            <div>
                                                @foreach($campaign->districts as $district)
                                                    <span class="badge bg-secondary mb-1 me-1">{{ $district->bn_name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <!-- Upazilas -->
                                    @if($campaign->upazilas->count() > 0)
                                        <div class="col-md-6 mb-3">
                                            <h6 class="fw-semibold text-muted mb-2">উপজেলা:</h6>
                                            <div>
                                                @foreach($campaign->upazilas as $upazila)
                                                    <span class="badge bg-info mb-1 me-1">{{ $upazila->bn_name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Unions -->
                                    @if($campaign->unions->count() > 0)
                                        <div class="col-md-6 mb-3">
                                            <h6 class="fw-semibold text-muted mb-2">ইউনিয়ন:</h6>
                                            <div>
                                                @foreach($campaign->unions as $union)
                                                    <span class="badge bg-success mb-1 me-1">{{ $union->bn_name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Media Section -->
                        <div class="mb-5">
                            <h4 class="fw-semibold mb-3 border-bottom pb-2">মিডিয়া সমূহ</h4>
                            @if(!$campaign->images->count() && !$campaign->audio && !$campaign->video && !$campaign->files->count())
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle me-2"></i> কোন মিডিয়া ফাইল যুক্ত করা হয়নি।
                                </div>
                            @else
                                <!-- Images -->
                                @if($campaign->images->count() > 1)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold text-muted mb-2">ইমেজ সমূহ:</h6>
                                        <div class="row">
                                            @foreach($campaign->images->skip(1) as $image)
                                                <div class="col-sm-6 col-lg-4 mb-3">
                                                    <a href="{{ asset('storage/' . $image->file_path) }}" target="_blank" class="d-block">
                                                        <img src="{{ asset('storage/' . $image->file_path) }}"
                                                            alt="{{ $image->file_name }}" class="img-fluid rounded">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Audio -->
                                @if($campaign->audio)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold text-muted mb-2">অডিও:</h6>
                                        <div class="bg-light p-3 rounded">
                                            <p class="mb-1 small">{{ $campaign->audio->file_name }}</p>
                                            <audio controls class="w-100">
                                                <source src="{{ asset('storage/' . $campaign->audio->file_path) }}"
                                                    type="{{ $campaign->audio->file_type }}">
                                                আপনার ব্রাউজার অডিও সাপোর্ট করে না।
                                            </audio>
                                        </div>
                                    </div>
                                @endif

                                <!-- Video -->
                                @if($campaign->video)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold text-muted mb-2">ভিডিও:</h6>
                                        <div class="bg-light p-3 rounded">
                                            <p class="mb-1 small">{{ $campaign->video->file_name }}</p>
                                            <video controls class="w-100">
                                                <source src="{{ asset('storage/' . $campaign->video->file_path) }}"
                                                    type="{{ $campaign->video->file_type }}">
                                                আপনার ব্রাউজার ভিডিও সাপোর্ট করে না।
                                            </video>
                                        </div>
                                    </div>
                                @endif

                                <!-- Files -->
                                @if($campaign->files->count() > 0)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold text-muted mb-2">ডকুমেন্ট সমূহ:</h6>
                                        <div class="list-group">
                                            @foreach($campaign->files as $file)
                                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fa fa-file me-2"></i>
                                                        {{ $file->file_name }}
                                                    </div>
                                                    <span class="badge bg-primary rounded-pill">
                                                        <i class="fa fa-download"></i>
                                                    </span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Modal -->
    <div class="modal fade" id="deleteCampaignModal" tabindex="-1" role="dialog" aria-labelledby="deleteCampaignModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCampaignModalLabel">নিশ্চিতকরণ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    আপনি কি নিশ্চিত যে আপনি "{{ $campaign->title }}" ক্যাম্পেইন মুছতে চান?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল করুন</button>
                    <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">মুছে ফেলুন</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Simple styles for campaign details page */
    .campaign-description {
        line-height: 1.5;
    }
    .campaign-description img {
        max-width: 100%;
        height: auto;
        margin: 0.5rem 0;
    }
    .badge {
        font-weight: normal;
    }
</style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Ensure the modal is properly initialized
            $('#deleteCampaignModal').on('show.bs.modal', function (event) {
                console.log('Modal is opening');
            });
        });
    </script>
@endpush
