@extends('layouts.base')

@section('title', 'ক্যাম্পেইন এডিট')@section('styles_before')
    <link rel="stylesheet" href="{{ asset('theme/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/js/plugins/select2/css/select2.min.css') }}">
    <style>
        .custom-card-header {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            color: white;
            border-bottom: none;
            padding: 15px 20px;
        }
        .custom-form-section {
            margin-bottom: 25px;
            padding-bottom: 5px;
        }
        .section-heading {
            color: #388E3C;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        .btn-custom-success {
            background-color: #4CAF50;
            border-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-custom-success:hover {
            background-color: #388E3C;
            border-color: #388E3C;
        }
        .file-upload-container {
            border: 2px dashed #ced4da;
            padding: 20px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: all 0.2s;
            background-color: #f8f9fa;
        }
        .file-upload-container:hover {
            border-color: #4CAF50;
        }
        .required-asterisk {
            color: #dc3545;
            font-weight: bold;
        }
        .campaign-type-selector {
            display: flex;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            flex-wrap: wrap;
        }
        .campaign-type-option {
            flex: 1;
            min-width: 150px;
            margin: 5px;
            text-align: center;
            padding: 15px;
            border: 2px solid #ced4da;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .campaign-type-option:hover {
            border-color: #4CAF50;
            background-color: #f0f9f0;
        }
        .campaign-type-option.selected {
            border-color: #4CAF50;
            background-color: #e8f5e9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .campaign-type-option i {
            display: block;
            font-size: 24px;
            margin-bottom: 10px;
            color: #4CAF50;
        }
        .settings-container {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
        }
        .existing-media-item {
            position: relative;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
            background-color: #fff;
        }
        .existing-media-item img, .existing-media-item video {
            max-width: 100%;
            border-radius: 4px;
        }
        .media-item-actions {
            position: absolute;
            top: 5px;
            right: 5px;
        }
        .media-name {
            font-weight: 600;
            color: #495057;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            display: block;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a href="{{ route('campaigns.index') }}">ক্যাম্পেইন</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">ক্যাম্পেইন এডিট</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="campaign-page">
            <x-alert />

            <form id="editCampaignForm" action="{{ route('campaigns.update', $campaign) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header custom-card-header">
                        <h3 class="block-title">ক্যাম্পেইন এডিট করুন</h3>
                    </div>

                    <div class="block-content block-content-full">
                        <!-- Title and Description -->
                        <div class="custom-form-section">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">ক্যাম্পেইন শিরোনাম <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title" required
                                            value="{{ old('title', $campaign->title) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">ক্যাম্পেইন বিবরণ <span class="required-asterisk">*</span></label>
                                        <textarea id="summernoteEditCampaign" class="form-control" name="description" required>{{ old('description', $campaign->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Campaign Type Selection -->
                        <div class="custom-form-section">
                            <h2 class="section-heading">
                                ক্যাম্পেইনের ধরন নির্বাচন করুন
                                <small class="text-muted ml-2">
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                        title="ক্যাম্পেইনের অবস্থান পরিধি নির্ধারণ করুন"></i>
                                </small>
                            </h2>

                            <div class="campaign-type-selector">
                                <div class="campaign-type-option {{ $campaign->is_nationwide ? 'selected' : '' }}" data-type="nationwide">
                                    <i class="fa fa-globe"></i>
                                    <h5>বাংলাদেশ জুড়ে</h5>
                                    <p class="text-muted small">সমগ্র দেশে ক্যাম্পেইন চালানো হবে</p>
                                </div>
                                <div class="campaign-type-option {{ (!$campaign->is_nationwide && $campaign->campaign_type == 'division') ? 'selected' : '' }}" data-type="division">
                                    <i class="fa fa-map"></i>
                                    <h5>বিভাগ ভিত্তিক</h5>
                                    <p class="text-muted small">নির্দিষ্ট বিভাগে ক্যাম্পেইন চালানো হবে</p>
                                </div>
                                <div class="campaign-type-option {{ (!$campaign->is_nationwide && $campaign->campaign_type == 'district') ? 'selected' : '' }}" data-type="district">
                                    <i class="fa fa-map-marker"></i>
                                    <h5>জেলা ভিত্তিক</h5>
                                    <p class="text-muted small">নির্দিষ্ট জেলায় ক্যাম্পেইন চালানো হবে</p>
                                </div>
                                <div class="campaign-type-option {{ (!$campaign->is_nationwide && $campaign->campaign_type == 'upazila') ? 'selected' : '' }}" data-type="upazila">
                                    <i class="fa fa-map-o"></i>
                                    <h5>উপজেলা ভিত্তিক</h5>
                                    <p class="text-muted small">নির্দিষ্ট উপজেলায় ক্যাম্পেইন চালানো হবে</p>
                                </div>
                                <div class="campaign-type-option {{ (!$campaign->is_nationwide && $campaign->campaign_type == 'union') ? 'selected' : '' }}" data-type="union">
                                    <i class="fa fa-map-pin"></i>
                                    <h5>ইউনিয়ন ভিত্তিক</h5>
                                    <p class="text-muted small">নির্দিষ্ট ইউনিয়নে ক্যাম্পেইন চালানো হবে</p>
                                </div>
                            </div>

                            <input type="hidden" name="campaign_type" id="campaign_type" value="{{ old('campaign_type', $campaign->campaign_type) }}">
                            <input type="hidden" name="is_nationwide" id="is_nationwide" value="{{ old('is_nationwide', $campaign->is_nationwide ? '1' : '0') }}">
                        </div>

                        <!-- Location Selection -->
                        <div class="custom-form-section" id="location-selection-container" style="{{ $campaign->is_nationwide ? 'display: none;' : '' }}">
                            <h2 class="section-heading">
                                অবস্থান নির্বাচন করুন
                                <small class="text-muted ml-2">
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                        title="ক্যাম্পেইন চালানোর অবস্থান নির্বাচন করুন"></i>
                                </small>
                            </h2>

                            <div class="row">
                                <!-- Division Dropdown -->
                                <div class="col-md-3" id="division-container">
                                    <div class="form-group">
                                        <label for="division_ids">বিভাগ <span class="required-asterisk location-required">*</span></label>
                                        <select name="division_ids[]" id="division_ids" class="form-control js-select2" multiple>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}" {{ in_array($division->id, $selectedDivisions) ? 'selected' : '' }}>
                                                    {{ $division->bn_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- District Dropdown -->
                                <div class="col-md-3" id="district-container" style="{{ in_array($campaign->campaign_type, ['district', 'upazila', 'union']) ? '' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label for="district_ids">জেলা <span class="required-asterisk location-required">*</span></label>
                                        <select name="district_ids[]" id="district_ids" class="form-control js-select2" multiple>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}" {{ in_array($district->id, $selectedDistricts) ? 'selected' : '' }}>
                                                    {{ $district->bn_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Upazila Dropdown -->
                                <div class="col-md-3" id="upazila-container" style="{{ in_array($campaign->campaign_type, ['upazila', 'union']) ? '' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label for="upazila_ids">উপজেলা <span class="required-asterisk location-required">*</span></label>
                                        <select name="upazila_ids[]" id="upazila_ids" class="form-control js-select2" multiple>
                                            @foreach ($upazilas as $upazila)
                                                <option value="{{ $upazila->id }}" {{ in_array($upazila->id, $selectedUpazilas) ? 'selected' : '' }}>
                                                    {{ $upazila->bn_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Union Dropdown -->
                                <div class="col-md-3" id="union-container" style="{{ $campaign->campaign_type == 'union' ? '' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label for="union_ids">ইউনিয়ন <span class="required-asterisk location-required">*</span></label>
                                        <select name="union_ids[]" id="union_ids" class="form-control js-select2" multiple>
                                            @foreach ($unions as $union)
                                                <option value="{{ $union->id }}" {{ in_array($union->id, $selectedUnions) ? 'selected' : '' }}>
                                                    {{ $union->bn_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Campaign Dates -->
                        <div class="custom-form-section">
                            <h2 class="section-heading">
                                সময়কাল নির্ধারণ করুন
                                <small class="text-muted ml-2">
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                        title="ক্যাম্পেইন শুরু এবং শেষের তারিখ নির্ধারণ করুন"></i>
                                </small>
                            </h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">শুরুর তারিখ <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control js-datepicker" id="start_date"
                                            name="start_date" required data-week-start="0" data-autoclose="true"
                                            data-today-highlight="true" data-date-format="dd/mm/yyyy" 
                                            value="{{ old('start_date', $campaign->start_date->format('d/m/Y')) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">শেষের তারিখ <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control js-datepicker" id="end_date" name="end_date"
                                            required data-week-start="0" data-autoclose="true" data-today-highlight="true"
                                            data-date-format="dd/mm/yyyy" value="{{ old('end_date', $campaign->end_date->format('d/m/Y')) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Media Section -->
                        <div class="custom-form-section">
                            <h2 class="section-heading">
                                বর্তমান মিডিয়া
                                <small class="text-muted ml-2">
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                        title="বর্তমান মিডিয়া ফাইলগুলো"></i>
                                </small>
                            </h2>

                            <!-- Display existing images -->
                            @if($campaign->images->count() > 0)
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">ইমেজ সমূহ</h5>
                                        <div class="row">
                                            @foreach($campaign->images as $image)
                                                <div class="col-md-3 mb-3">
                                                    <div class="existing-media-item">
                                                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $image->file_name }}" class="img-fluid">
                                                        <div class="media-item-actions">
                                                            <button type="button" class="btn btn-danger btn-sm delete-media" 
                                                                    data-type="image" 
                                                                    data-id="{{ $image->id }}" 
                                                                    data-title="{{ $image->file_name }}">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Display existing audio -->
                            @if($campaign->audio)
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">অডিও</h5>
                                        <div class="existing-media-item">
                                            <audio controls style="width: 100%;">
                                                <source src="{{ asset('storage/' . $campaign->audio->file_path) }}" type="{{ $campaign->audio->file_type }}">
                                                আপনার ব্রাউজার অডিও সাপোর্ট করে না
                                            </audio>
                                            <div class="media-item-actions">
                                                <button type="button" class="btn btn-danger btn-sm delete-media" 
                                                        data-type="audio" 
                                                        data-id="{{ $campaign->audio->id }}" 
                                                        data-title="{{ $campaign->audio->file_name }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Display existing video -->
                            @if($campaign->video)
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">ভিডিও</h5>
                                        <div class="existing-media-item">
                                            <video controls style="width: 100%;">
                                                <source src="{{ asset('storage/' . $campaign->video->file_path) }}" type="{{ $campaign->video->file_type }}">
                                                আপনার ব্রাউজার ভিডিও সাপোর্ট করে না
                                            </video>
                                            <div class="media-item-actions">
                                                <button type="button" class="btn btn-danger btn-sm delete-media" 
                                                        data-type="video" 
                                                        data-id="{{ $campaign->video->id }}" 
                                                        data-title="{{ $campaign->video->file_name }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Display existing files -->
                            @if($campaign->files->count() > 0)
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">ডকুমেন্ট সমূহ</h5>
                                        <div class="row">
                                            @foreach($campaign->files as $file)
                                                <div class="col-md-4 mb-3">
                                                    <div class="existing-media-item">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fa fa-file fa-2x mr-3 text-primary"></i>
                                                            <div class="flex-grow-1">
                                                                <span class="media-name">{{ $file->file_name }}</span>
                                                                <small class="d-block text-muted">{{ number_format($file->file_size / 1024, 2) }} KB</small>
                                                            </div>
                                                        </div>
                                                        <div class="media-item-actions">
                                                            <a href="{{ asset('storage/' . $file->file_path) }}" class="btn btn-info btn-sm mr-1" target="_blank">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-danger btn-sm delete-media" 
                                                                    data-type="file" 
                                                                    data-id="{{ $file->id }}" 
                                                                    data-title="{{ $file->file_name }}">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- New Media Upload Section -->
                        <div class="custom-form-section">
                            <h2 class="section-heading">
                                নতুন মিডিয়া সংযুক্তি
                                <small class="text-muted ml-2">
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                        title="ক্যাম্পেইনের জন্য নতুন মিডিয়া আপলোড করুন"></i>
                                </small>
                            </h2>
                            <!-- Images Upload -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="campaign_images">ইমেজ সংযুক্তি</label>
                                        <div class="file-upload-container">
                                            <input type="file" class="form-control-file" id="campaign_images"
                                                name="campaign_images[]" multiple accept="image/*">
                                            <small class="text-muted mt-2 d-block">JPG, PNG, GIF (সর্বোচ্চ 5MB)</small>
                                        </div>
                                        <div id="image-preview-container"></div>
                                    </div>
                                </div>

                                <!-- Audio Upload -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="campaign_audio">অডিও সংযুক্তি</label>
                                        <div class="file-upload-container">
                                            <input type="file" class="form-control-file" id="campaign_audio"
                                                name="campaign_audio" accept="audio/*">
                                            <small class="text-muted mt-2 d-block">MP3, WAV (সর্বোচ্চ 10MB)</small>
                                        </div>
                                        <div id="audio-preview-container" class="audio-preview-container" style="display: none;">
                                            <audio id="audio-preview" controls style="width: 100%;"></audio>
                                        </div>
                                    </div>
                                </div>

                                <!-- Video Upload -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="campaign_video">ভিডিও সংযুক্তি</label>
                                        <div class="file-upload-container">
                                            <input type="file" class="form-control-file" id="campaign_video"
                                                name="campaign_video" accept="video/*">
                                            <small class="text-muted mt-2 d-block">MP4, WEBM (সর্বোচ্চ 50MB)</small>
                                        </div>
                                        <div id="video-preview-container" style="display: none;">
                                            <video id="video-preview" controls style="width: 100%; max-height: 200px;"></video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Document Upload Section -->
                        <div class="custom-form-section">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="file_paths">ডকুমেন্ট সংযুক্তি</label>
                                        <div class="file-upload-container">
                                            <input type="file" class="form-control-file" id="file_paths" name="file_paths[]" multiple>
                                            <small class="text-muted mt-2 d-block">PDF, DOCX, XLSX, PPT (সর্বোচ্চ 10MB)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Campaign Settings -->
                        <div class="custom-form-section">
                            <h2 class="section-heading">ক্যাম্পেইন সেটিংস</h2>
                            <div class="settings-container">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">স্ট্যাটাস <span class="required-asterisk">*</span></label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="draft" {{ old('status', $campaign->status) == 'draft' ? 'selected' : '' }}>অপ্রকাশিত</option>
                                                <option value="publish" {{ old('status', $campaign->status) == 'publish' ? 'selected' : '' }}>প্রকাশ</option>
                                                <option value="scheduled" {{ old('status', $campaign->status) == 'scheduled' ? 'selected' : '' }}>শিডিউল করা</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="notification_send">নোটিফিকেশন পাঠানো হবে কি? <span class="required-asterisk">*</span></label>
                                            <select class="form-control" id="notification_send" name="notification_send">
                                                <option value="no" {{ old('notification_send', $campaign->notification_send) == 'no' ? 'selected' : '' }}>না</option>
                                                <option value="yes" {{ old('notification_send', $campaign->notification_send) == 'yes' ? 'selected' : '' }}>হ্যাঁ</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="featured">ফিচারড ক্যাম্পেইন?</label>
                                            <select class="form-control" id="featured" name="featured">
                                                <option value="0" {{ old('featured', $campaign->featured) ? '' : 'selected' }}>না</option>
                                                <option value="1" {{ old('featured', $campaign->featured) ? 'selected' : '' }}>হ্যাঁ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group mt-4 d-flex justify-content-center">
                            <button type="submit" class="btn btn-custom-success" id="saveCampaign">
                                <i class="fa fa-save mr-1"></i> ক্যাম্পেইন আপডেট করুন
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Delete Media Confirmation Modal -->
            <div class="modal fade" id="deleteMediaModal" tabindex="-1" role="dialog" aria-labelledby="deleteMediaModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteMediaModalLabel">নিশ্চিতকরণ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            আপনি কি নিশ্চিত যে আপনি <span id="mediaTitle" class="font-weight-bold"></span> মুছতে চান?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল করুন</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteMedia">মুছে ফেলুন</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        jQuery(function () { One.helpers(['datepicker', 'select2']); });
    </script>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize Select2

$('#division_ids, #district_ids, #upazila_ids, #union_ids').select2({
    placeholder: 'Select option',
    width: '100%' // Keep it full width
});

            // Initialize Summernote
            $('#summernoteEditCampaign').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onInit: function() {
                        $(this).summernote('code', $(this).val());
                    }
                }
            });

            // Campaign Type Selector
            $('.campaign-type-option').on('click', function () {
                const type = $(this).data('type');

                // Remove selected class from all options
                $('.campaign-type-option').removeClass('selected');

                // Add selected class to clicked option
                $(this).addClass('selected');

                // Set campaign type value in hidden field
                $('#campaign_type').val(type);

                // Handle nationwide selection
                if (type === 'nationwide') {
                    $('#is_nationwide').val(1);
                    $('#location-selection-container').hide();
                } else {
                    $('#is_nationwide').val(0);
                    $('#location-selection-container').show();

                    // Show/hide appropriate dropdowns based on selection
                    if (type === 'division') {
                        $('#division-container').show();
                        $('#district-container, #upazila-container, #union-container').hide();
                    } else if (type === 'district') {
                        $('#division-container, #district-container').show();
                        $('#upazila-container, #union-container').hide();
                    } else if (type === 'upazila') {
                        $('#division-container, #district-container, #upazila-container').show();
                        $('#union-container').hide();
                    } else if (type === 'union') {
                        $('#division-container, #district-container, #upazila-container, #union-container').show();
                    }
                }
            });

            $("#division_ids").change(function () {
                const divisionId = $(this).val();
                if (divisionId) {
                    // Reset and disable lower selects
                    $("#district_ids").html('<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>').prop("disabled", true);
                    $("#upazila_ids").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get districts
                    $.ajax({
                        url: '/get-districts/' + divisionId,
                        type: 'GET',
                        success: function (data) {
                            let districtOptions = '<option value="">জেলা নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                districtOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#district_ids").html(districtOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#district_ids").html('<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>').prop("disabled", true);
                    $("#upazila_ids").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });

            $("#district_ids").change(function () {
                const districtId = $(this).val();
                if (districtId) {
                    // Reset and disable lower selects
                    $("#upazila_ids").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get upazilas
                    $.ajax({
                        url: '/get-upazilas/' + districtId,
                        type: 'GET',
                        success: function (data) {
                            let upazilaOptions = '<option value="">উপজেলা নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                upazilaOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#upazila_ids").html(upazilaOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#upazila_ids").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });

            $("#upazila_ids").change(function () {
                const upazilaId = $(this).val();
                if (upazilaId) {
                    // Reset union select
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get unions
                    $.ajax({
                        url: '/get-unions/' + upazilaId,
                        type: 'GET',
                        success: function (data) {
                            let unionOptions = '<option value="">ইউনিয়ন নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                unionOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#union_ids").html(unionOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });

            // Media file previews
            $('#campaign_images').on('change', function () {
                const files = this.files;
                $('#image-preview-container').empty();

                if (files.length > 0) {
                    for (let i = 0; i < Math.min(files.length, 3); i++) {
                        const file = files[i];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                $('#image-preview-container').append(
                                    `<img src="${e.target.result}" alt="Preview" class="img-fluid mt-2" style="max-height: 150px; margin-right: 10px;">`
                                );
                            }
                            reader.readAsDataURL(file);
                        }
                    }

                    if (files.length > 3) {
                        $('#image-preview-container').append(
                            `<p class="text-muted mt-2">+${files.length - 3} আরও ইমেজ</p>`
                        );
                    }
                }
            });

            // Audio Preview
            $('#campaign_audio').on('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#audio-preview').attr('src', e.target.result);
                        $('#audio-preview-container').show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#audio-preview-container').hide();
                }
            });

            // Video Preview
            $('#campaign_video').on('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#video-preview').attr('src', e.target.result);
                        $('#video-preview-container').show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#video-preview-container').hide();
                }
            });

            // Media deletion handling
            let deleteMediaType, deleteMediaId;

            $('.delete-media').on('click', function() {
                deleteMediaType = $(this).data('type');
                deleteMediaId = $(this).data('id');
                const mediaTitle = $(this).data('title');

                $('#mediaTitle').text(mediaTitle);
                $('#deleteMediaModal').modal('show');
            });

            $('#confirmDeleteMedia').on('click', function() {
                // Send AJAX request to delete the media
                $.ajax({
                    url: "{{ route('campaigns.delete.media') }}",
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}",
                        type: deleteMediaType,
                        id: deleteMediaId
                    },
                    success: function(response) {
                        if (response.success) {
                            // Close modal
                            $('#deleteMediaModal').modal('hide');
                            // Show success message
                            const alertHTML = `
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fa fa-check-circle mr-1"></i> ${response.message}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `;
                            $('.campaign-page').prepend(alertHTML);

                            // Remove the element from the DOM
                            $(`button[data-type="${deleteMediaType}"][data-id="${deleteMediaId}"]`).closest('.existing-media-item').parent().remove();
                        } else {
                            alert('মিডিয়া মুছতে সমস্যা হয়েছে।');
                        }
                    },
                    error: function() {
                        alert('মিডিয়া মুছতে সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।');
                    }
                });
            });

            // Form validation before submission
            $('#editCampaignForm').on('submit', function (e) {
                const campaignType = $('#campaign_type').val();
                const isNationwide = $('#is_nationwide').val() === '1';
                const divisionIds = $('#division_ids').val() || [];
                const districtIds = $('#district_ids').val() || [];
                const upazilaIds = $('#upazila_ids').val() || [];
                const unionIds = $('#union_ids').val() || [];
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                // Basic form validation
                if (!$('#title').val().trim()) {
                    e.preventDefault();
                    alert('ক্যাম্পেইন শিরোনাম দিন');
                    return false;
                }

                if (!campaignType) {
                    e.preventDefault();
                    alert('অনুগ্রহ করে ক্যাম্পেইনের ধরন নির্বাচন করুন');
                    return false;
                }

                // Location validation based on campaign type
                if (!isNationwide) {
                    if (campaignType === 'division' && divisionIds.length === 0) {
                        e.preventDefault();
                        alert('অনুগ্রহ করে কমপক্ষে একটি বিভাগ নির্বাচন করুন');
                        return false;
                    } else if (campaignType === 'district' && districtIds.length === 0) {
                        e.preventDefault();
                        alert('অনুগ্রহ করে কমপক্ষে একটি জেলা নির্বাচন করুন');
                        return false;
                    } else if (campaignType === 'upazila' && upazilaIds.length === 0) {
                        e.preventDefault();
                        alert('অনুগ্রহ করে কমপক্ষে একটি উপজেলা নির্বাচন করুন');
                        return false;
                    } else if (campaignType === 'union' && unionIds.length === 0) {
                        e.preventDefault();
                        alert('অনুগ্রহ করে কমপক্ষে একটি ইউনিয়ন নির্বাচন করুন');
                        return false;
                    }
                }

                // Date validation
                if (!startDate || !endDate) {
                    e.preventDefault();
                    alert('অনুগ্রহ করে শুরু ও শেষ তারিখ নির্বাচন করুন');
                    return false;
                }

                // Compare dates
                const startDateParts = startDate.split('/');
                const endDateParts = endDate.split('/');
                const startDateObj = new Date(startDateParts[2], startDateParts[1] - 1, startDateParts[0]);
                const endDateObj = new Date(endDateParts[2], endDateParts[1] - 1, endDateParts[0]);

                if (startDateObj > endDateObj) {
                    e.preventDefault();
                    alert('শেষের তারিখ শুরুর তারিখের পরে হতে হবে');
                    return false;
                }

                // Disable the submit button to prevent double submission
                $('#saveCampaign').prop('disabled', true);
                return true;
            });

            // Enable tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush